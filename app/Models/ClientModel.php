<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ClientModel — Gestion des clients du mobile money
 *
 * Ce model gere la table 'clients' et fournit les methodes pour :
 * - Rechercher un client par son numero de telephone
 * - Creer automatiquement un client (sans inscription prealable)
 * - Calculer le solde d'un client a partir de ses transactions
 */
class ClientModel extends Model
{
    // -------------------------------------------------------
    // Configuration du model CI4
    // -------------------------------------------------------

    /** @var string Nom de la table en base de donnees */
    protected $table = 'clients';

    /** @var string Cle primaire de la table */
    protected $primaryKey = 'id_client';

    /** @var array Colonnes autorisees pour l'insertion/mise a jour */
    protected $allowedFields = ['numero_telephone', 'id_operateur', 'date_creation'];

    /** @var string Type de retour des resultats (tableau associatif) */
    protected $returnType = 'array';

    // -------------------------------------------------------
    // Methodes metier
    // -------------------------------------------------------

    /**
     * Recherche un client par son numero de telephone
     *
     * @param  string $numero Le numero de telephone a chercher
     * @return array|null     Les donnees du client ou null si introuvable
     */
    public function findByTelephone(string $numero): ?array
    {
        return $this->where('numero_telephone', $numero)->first();
    }

    /**
     * Cree automatiquement un client a partir de son numero de telephone
     *
     * Fonctionnement :
     * 1. Extrait le prefixe (3 premiers chiffres) du numero
     * 2. Cherche l'operateur correspondant dans la table 'prefixes'
     * 3. Cree le client avec cet operateur
     *
     * @param  string    $numero Le numero de telephone du nouveau client
     * @return int|false         L'ID du client cree, ou false si le prefixe est inconnu
     */
    public function creerAutomatique(string $numero): int|false
    {
        // Extraire le prefixe (3 premiers caracteres, ex: "033")
        $prefixe = substr($numero, 0, 3);

        // Chercher l'operateur correspondant au prefixe dans la table 'prefixes'
        $db = \Config\Database::connect();
        $result = $db->table('prefixes')
                     ->where('prefixe', $prefixe)
                     ->get()
                     ->getRowArray();

        // Si le prefixe n'est pas reconnu, on ne peut pas creer le client
        if (!$result) {
            return false;
        }

        // Inserer le nouveau client avec l'operateur detecte
        $this->insert([
            'numero_telephone' => $numero,
            'id_operateur'     => $result['id_operateur'],
        ]);

        // Retourner l'ID du client nouvellement cree
        return $this->getInsertID();
    }

    /**
     * Calcule le solde d'un client a partir de ses transactions
     *
     * Le solde est calcule ainsi :
     *   + Somme des depots (montant)
     *   - Somme des retraits (montant + frais)
     *   - Somme des transferts envoyes (montant + frais)
     *   + Somme des transferts recus (montant)
     *
     * Seules les transactions avec statut 'reussi' sont comptees.
     *
     * @param  int   $idClient L'ID du client
     * @return float           Le solde calcule
     */
    public function getSolde(int $idClient): float
    {
        $db = \Config\Database::connect();

        // ---------------------------------------------------
        // 1) Somme des depots (le client est emetteur, type = DEPOT)
        //    On ajoute le montant au solde
        // ---------------------------------------------------
        $depots = $db->query(
            "SELECT COALESCE(SUM(t.montant), 0) AS total
             FROM transactions t
             JOIN types_operation tp ON tp.id_type = t.id_type
             WHERE tp.code = 'DEPOT'
               AND t.id_client_emetteur = ?
               AND t.statut = 'reussi'",
            [$idClient]
        )->getRowArray();
        $totalDepots = (float)$depots['total'];

        // ---------------------------------------------------
        // 2) Somme des retraits (le client est emetteur, type = RETRAIT)
        //    On soustrait montant + frais du solde
        // ---------------------------------------------------
        $retraits = $db->query(
            "SELECT COALESCE(SUM(t.montant + t.frais), 0) AS total
             FROM transactions t
             JOIN types_operation tp ON tp.id_type = t.id_type
             WHERE tp.code = 'RETRAIT'
               AND t.id_client_emetteur = ?
               AND t.statut = 'reussi'",
            [$idClient]
        )->getRowArray();
        $totalRetraits = (float)$retraits['total'];

        // ---------------------------------------------------
        // 3) Somme des transferts envoyes (le client est emetteur, type = TRANSFERT)
        //    On soustrait montant + frais du solde
        // ---------------------------------------------------
        $transfertsEnvoyes = $db->query(
            "SELECT COALESCE(SUM(t.montant + t.frais), 0) AS total
             FROM transactions t
             JOIN types_operation tp ON tp.id_type = t.id_type
             WHERE tp.code = 'TRANSFERT'
               AND t.id_client_emetteur = ?
               AND t.statut = 'reussi'",
            [$idClient]
        )->getRowArray();
        $totalTransfertsEnvoyes = (float)$transfertsEnvoyes['total'];

        // ---------------------------------------------------
        // 4) Somme des transferts recus (le client est destinataire, type = TRANSFERT)
        //    On ajoute le montant au solde
        // ---------------------------------------------------
        $transfertsRecus = $db->query(
            "SELECT COALESCE(SUM(t.montant), 0) AS total
             FROM transactions t
             JOIN types_operation tp ON tp.id_type = t.id_type
             WHERE tp.code = 'TRANSFERT'
               AND t.id_client_destinataire = ?
               AND t.statut = 'reussi'",
            [$idClient]
        )->getRowArray();
        $totalTransfertsRecus = (float)$transfertsRecus['total'];

        // ---------------------------------------------------
        // Calcul final du solde
        // ---------------------------------------------------
        return $totalDepots - $totalRetraits - $totalTransfertsEnvoyes + $totalTransfertsRecus;
    }
}
