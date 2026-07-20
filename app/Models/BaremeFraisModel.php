<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * BaremeFraisModel — Gestion des baremes de frais
 *
 * Ce model gere la table 'baremes_frais' et fournit la methode
 * getFraisApplicable() utilisee par le TransactionController
 * pour calculer les frais lors d'un retrait ou d'un transfert.
 *
 * Chaque bareme definit une tranche de montant (min-max) et les frais associes
 * pour un type d'operation donne (retrait ou transfert).
 */
class BaremeFraisModel extends Model
{
    // -------------------------------------------------------
    // Configuration du model CI4
    // -------------------------------------------------------

    /** @var string Nom de la table en base de donnees */
    protected $table = 'baremes_frais';

    /** @var string Cle primaire de la table */
    protected $primaryKey = 'id_bareme';

    /** @var array Colonnes autorisees pour l'insertion/mise a jour */
    protected $allowedFields = [
        'id_type',
        'montant_min',
        'montant_max',
        'frais',
    ];

    /** @var string Type de retour des resultats (tableau associatif) */
    protected $returnType = 'array';

    // -------------------------------------------------------
    // Methodes metier
    // -------------------------------------------------------

    /**
     * Retourne les frais applicables pour un type d'operation et un montant donne
     *
     * Fonctionnement :
     * 1. Cherche dans la table baremes_frais la ligne ou :
     *    - id_type correspond au type d'operation (retrait ou transfert)
     *    - Le montant est compris entre montant_min et montant_max
     * 2. Retourne les frais de cette tranche
     * 3. Si aucune tranche ne correspond, retourne 0
     *
     * Exemple avec le bareme du sujet (pour le retrait) :
     *   - Montant entre 100 et 1000 Ar     => frais = 50 Ar
     *   - Montant entre 1001 et 5000 Ar    => frais = 50 Ar
     *   - Montant entre 5001 et 10000 Ar   => frais = 100 Ar
     *   - etc.
     *
     * @param  int   $idType  L'ID du type d'operation (retrait = 2, transfert = 3)
     * @param  float $montant Le montant de l'operation
     * @return float          Les frais applicables (0 si aucun bareme trouve)
     */
    public function getFraisApplicable(int $idType, float $montant): float
    {
        // Chercher la tranche de frais correspondante au montant
        $bareme = $this->where('id_type', $idType)
                       ->where('montant_min <=', $montant)
                       ->where('montant_max >=', $montant)
                       ->first();

        // Si un bareme est trouve, retourner les frais associes
        // Sinon, retourner 0 (pas de frais)
        return $bareme ? (float) $bareme['frais'] : 0;
    }
}
