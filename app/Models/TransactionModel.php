<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * TransactionModel — Gestion des transactions mobile money
 *
 * Ce model gere la table 'transactions' et fournit les methodes pour :
 * - Inserer un depot (automatique, sans frais)
 * - Inserer un retrait (automatique, avec frais selon bareme)
 * - Inserer un transfert (avec frais selon bareme)
 * - Recuperer l'historique des transactions d'un client
 */
class TransactionModel extends Model
{
    // -------------------------------------------------------
    // Configuration du model CI4
    // -------------------------------------------------------

    /** @var string Nom de la table en base de donnees */
    protected $table = 'transactions';

    /** @var string Cle primaire de la table */
    protected $primaryKey = 'id_transaction';

    /** @var array Colonnes autorisees pour l'insertion/mise a jour */
    protected $allowedFields = [
        'id_client_emetteur',
        'id_client_destinataire',
        'id_type',
        'montant',
        'frais',
        'montant_total',
        'date_transaction',
        'statut',
    ];

    /** @var string Type de retour des resultats (tableau associatif) */
    protected $returnType = 'array';

    // -------------------------------------------------------
    // Methodes metier
    // -------------------------------------------------------

    /**
     * Insere un depot pour un client
     *
     * Le depot est suppose automatique (statut = 'reussi' immediatement).
     * Il n'y a pas de frais pour un depot.
     *
     * @param  int       $idClient L'ID du client qui depose
     * @param  float     $montant  Le montant a deposer
     * @return int|false           L'ID de la transaction creee, ou false en cas d'erreur
     */
    public function insererDepot(int $idClient, float $montant): int|false
    {
        // Recuperer l'ID du type d'operation 'DEPOT' dans la table types_operation
        $db = \Config\Database::connect();
        $type = $db->table('types_operation')
                   ->where('code', 'DEPOT')
                   ->get()
                   ->getRowArray();

        if (!$type) {
            return false; // Type DEPOT introuvable en base
        }

        // Inserer la transaction de depot
        $this->insert([
            'id_client_emetteur' => $idClient,
            'id_type'            => $type['id_type'],
            'montant'            => $montant,
            'frais'              => 0,              // Pas de frais pour un depot
            'montant_total'      => $montant,       // montant + frais (0) = montant
            'statut'             => 'reussi',       // Depot automatique = succes immediat
        ]);

        return $this->getInsertID();
    }

    /**
     * Insere un retrait pour un client
     *
     * Le retrait est suppose automatique (statut = 'reussi' immediatement).
     * Les frais sont calcules en amont par BaremeFraisModel::getFraisApplicable().
     *
     * @param  int       $idClient L'ID du client qui retire
     * @param  float     $montant  Le montant a retirer
     * @param  float     $frais    Les frais applicables (calcules par le bareme)
     * @return int|false           L'ID de la transaction creee, ou false en cas d'erreur
     */
    public function insererRetrait(int $idClient, float $montant, float $frais): int|false
    {
        // Recuperer l'ID du type d'operation 'RETRAIT'
        $db = \Config\Database::connect();
        $type = $db->table('types_operation')
                   ->where('code', 'RETRAIT')
                   ->get()
                   ->getRowArray();

        if (!$type) {
            return false; // Type RETRAIT introuvable en base
        }

        // Inserer la transaction de retrait
        $this->insert([
            'id_client_emetteur' => $idClient,
            'id_type'            => $type['id_type'],
            'montant'            => $montant,
            'frais'              => $frais,
            'montant_total'      => $montant + $frais, // Total debite du solde
            'statut'             => 'reussi',          // Retrait automatique
        ]);

        return $this->getInsertID();
    }

    /**
     * Insere un transfert entre deux clients
     *
     * L'emetteur paie le montant + les frais.
     * Le destinataire recoit uniquement le montant.
     *
     * @param  int       $idEmetteur      L'ID du client qui envoie
     * @param  int       $idDestinataire  L'ID du client qui recoit
     * @param  float     $montant         Le montant a transferer
     * @param  float     $frais           Les frais applicables
     * @return int|false                  L'ID de la transaction creee, ou false en cas d'erreur
     */
    public function insererTransfert(int $idEmetteur, int $idDestinataire, float $montant, float $frais): int|false
    {
        // Recuperer l'ID du type d'operation 'TRANSFERT'
        $db = \Config\Database::connect();
        $type = $db->table('types_operation')
                   ->where('code', 'TRANSFERT')
                   ->get()
                   ->getRowArray();

        if (!$type) {
            return false; // Type TRANSFERT introuvable en base
        }

        // Inserer la transaction de transfert
        $this->insert([
            'id_client_emetteur'      => $idEmetteur,
            'id_client_destinataire'  => $idDestinataire,  // Le destinataire du transfert
            'id_type'                 => $type['id_type'],
            'montant'                 => $montant,
            'frais'                   => $frais,
            'montant_total'           => $montant + $frais, // Total debite de l'emetteur
            'statut'                  => 'reussi',
        ]);

        return $this->getInsertID();
    }

    /**
     * Effectue un envoi multiple vers plusieurs destinataires
     *
     * Le montant total est divise equitablement entre tous les destinataires.
     * Les frais de transfert sont calcules individuellement pour chaque destinataire.
     * Optionnellement, les frais de retrait peuvent etre inclus (payes par l'emetteur).
     *
     * @param  int    $idEmetteur         L'ID du client qui envoie
     * @param  array  $numeros            Tableau des numeros destinataires
     * @param  float  $montantTotal       Le montant total a repartir
     * @param  bool   $inclureFraisRetrait Si true, inclure les frais de retrait dans le total
     * @return array  Resultat avec 'success' (bool), 'details' (par destinataire), 'message'
     */
    public function envoiMultiple(int $idEmetteur, array $numeros, float $montantTotal, bool $inclureFraisRetrait = false): array
    {
        $clientModel = new ClientModel();
        $baremeFraisModel = new BaremeFraisModel();
        $db = \Config\Database::connect();

        // Nombre de destinataires
        $nbDestinataires = count($numeros);

        // Montant par destinataire (division equitable)
        $montantParDest = floor($montantTotal / $nbDestinataires);

        // Recuperer les types d'operation
        $typeTransfert = $db->table('types_operation')
                            ->where('code', 'TRANSFERT')
                            ->get()
                            ->getRowArray();
        $typeRetrait = $db->table('types_operation')
                          ->where('code', 'RETRAIT')
                          ->get()
                          ->getRowArray();

        if (!$typeTransfert) {
            return ['success' => false, 'message' => 'Type TRANSFERT introuvable', 'details' => []];
        }

        // Phase 1 : Validation et calcul des frais totaux
        $destinations = [];
        $totalDebite = 0;

        foreach ($numeros as $numero) {
            $numero = trim($numero);

            // Verifier le prefixe
            $prefixe = substr($numero, 0, 3);
            $prefixeExiste = $db->table('prefixes')
                                ->where('prefixe', $prefixe)
                                ->get()
                                ->getRowArray();

            if (!$prefixeExiste) {
                return [
                    'success' => false,
                    'message' => 'Le prefixe du numero ' . $numero . ' n\'est pas reconnu',
                    'details' => [],
                ];
            }

            // Chercher ou creer le destinataire
            $destinataire = $clientModel->findByTelephone($numero);
            if (!$destinataire) {
                $idDest = $clientModel->creerAutomatique($numero);
                if (!$idDest) {
                    return [
                        'success' => false,
                        'message' => 'Erreur lors de la creation du compte pour ' . $numero,
                        'details' => [],
                    ];
                }
            } else {
                $idDest = $destinataire['id_client'];
            }

            // Calculer les frais de transfert
            $fraisTransfert = $baremeFraisModel->getFraisApplicable($typeTransfert['id_type'], $montantParDest);

            // Calculer les frais de retrait si option cochee
            $fraisRetrait = 0;
            if ($inclureFraisRetrait && $typeRetrait) {
                $fraisRetrait = $baremeFraisModel->getFraisApplicable($typeRetrait['id_type'], $montantParDest);
            }

            $fraisTotal = $fraisTransfert + $fraisRetrait;
            $totalDebite += $montantParDest + $fraisTotal;

            $destinations[] = [
                'numero'          => $numero,
                'id_destinataire' => $idDest,
                'montant'         => $montantParDest,
                'frais_transfert' => $fraisTransfert,
                'frais_retrait'   => $fraisRetrait,
                'frais_total'     => $fraisTotal,
            ];
        }

        // Phase 2 : Verifier le solde global
        $solde = $clientModel->getSolde($idEmetteur);
        if ($solde < $totalDebite) {
            return [
                'success' => false,
                'message' => 'Solde insuffisant. Votre solde : '
                    . number_format($solde, 0, ',', ' ') . ' Ar. '
                    . 'Total necessaire : '
                    . number_format($totalDebite, 0, ',', ' ') . ' Ar',
                'details' => [],
            ];
        }

        // Phase 3 : Inserer toutes les transactions
        $details = [];
        foreach ($destinations as $dest) {
            $this->insert([
                'id_client_emetteur'     => $idEmetteur,
                'id_client_destinataire' => $dest['id_destinataire'],
                'id_type'                => $typeTransfert['id_type'],
                'montant'                => $dest['montant'],
                'frais'                  => $dest['frais_total'],
                'montant_total'          => $dest['montant'] + $dest['frais_total'],
                'statut'                 => 'reussi',
            ]);

            $details[] = [
                'numero'  => $dest['numero'],
                'montant' => $dest['montant'],
                'frais'   => $dest['frais_total'],
                'success' => true,
            ];
        }

        return [
            'success' => true,
            'message' => 'Envoi multiple effectue avec succes vers ' . $nbDestinataires . ' destinataires',
            'details' => $details,
            'total_debite' => $totalDebite,
        ];
    }

    /**
     * Recupere l'historique complet des transactions d'un client
     *
     * Inclut les transactions ou le client est :
     * - Emetteur (depots, retraits, transferts envoyes)
     * - Destinataire (transferts recus)
     *
     * Les resultats sont tries par date decroissante (plus recentes d'abord).
     *
     * @param  int   $idClient L'ID du client
     * @return array           Liste des transactions avec le libelle du type
     */
    public function getHistorique(int $idClient): array
    {
        $db = \Config\Database::connect();

        // Requete pour recuperer toutes les transactions du client
        // On joint la table types_operation pour avoir le libelle et le code
        return $db->table('transactions t')
                  ->select('t.*, tp.libelle as type_libelle, tp.code as type_code')
                  ->join('types_operation tp', 'tp.id_type = t.id_type')
                  // Le client peut etre emetteur OU destinataire
                  ->groupStart()
                      ->where('t.id_client_emetteur', $idClient)
                      ->orWhere('t.id_client_destinataire', $idClient)
                  ->groupEnd()
                  // Trier par date decroissante (les plus recentes en premier)
                  ->orderBy('t.date_transaction', 'DESC')
                  ->get()
                  ->getResultArray();
    }
}
