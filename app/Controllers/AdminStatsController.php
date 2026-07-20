<?php
namespace App\Controllers;

// Controller : statistiques backoffice (gains et comptes)
// V2 : separation des gains intra/inter operateur + montants a envoyer
class AdminStatsController extends BaseController
{
    /**
     * Situation des gains (frais collectes par type d'operation)
     * V2 : separe les gains entre transferts intra-operateur et inter-operateur
     */
    public function gains()
    {
        // Connexion a la base de donnees
        $db = \Config\Database::connect();

        // Recupere les filtres de date depuis l'URL
        $dateDebut = $this->request->getGet('date_debut');
        $dateFin   = $this->request->getGet('date_fin');

        // Recupere l'ID de l'operateur de l'admin connecte
        $idOperateur = (int) session()->get('admin_operateur_id');

        // -------------------------------------------------------
        // 1) Gains GLOBAUX par type d'operation (comme avant)
        // -------------------------------------------------------
        $builder = $db->table('transactions t')
            ->select('tp.libelle, COUNT(t.id_transaction) as nb_transactions, SUM(t.frais) as total_frais')
            ->join('types_operation tp', 'tp.id_type = t.id_type')
            ->where('t.statut', 'reussi')
            ->groupBy('t.id_type');

        // Filtre par date si renseigne
        if ($dateDebut) {
            $builder->where('t.date_transaction >=', $dateDebut);
        }
        if ($dateFin) {
            $builder->where('t.date_transaction <=', $dateFin . ' 23:59:59');
        }

        // Stocke les gains globaux
        $data['gains'] = $builder->get()->getResultArray();

        // -------------------------------------------------------
        // 2) Gains INTRA-operateur (transferts entre clients du MEME operateur)
        // -------------------------------------------------------
        $builderIntra = $db->table('transactions t')
            ->select('COUNT(t.id_transaction) as nb_transactions, SUM(t.frais) as total_frais')
            ->join('types_operation tp', 'tp.id_type = t.id_type')
            ->join('clients ce', 'ce.id_client = t.id_client_emetteur')       // client emetteur
            ->join('clients cd', 'cd.id_client = t.id_client_destinataire')   // client destinataire
            ->where('tp.code', 'TRANSFERT')                                    // seulement les transferts
            ->where('t.statut', 'reussi')                                      // seulement les reussis
            ->where('ce.id_operateur', $idOperateur)                           // emetteur = notre operateur
            ->where('cd.id_operateur', $idOperateur);                          // destinataire = notre operateur

        // Filtre par date
        if ($dateDebut) {
            $builderIntra->where('t.date_transaction >=', $dateDebut);
        }
        if ($dateFin) {
            $builderIntra->where('t.date_transaction <=', $dateFin . ' 23:59:59');
        }

        // Stocke les gains intra-operateur
        $data['gains_intra'] = $builderIntra->get()->getRowArray();

        // -------------------------------------------------------
        // 3) Gains INTER-operateur (transferts vers des clients d'AUTRES operateurs)
        // Groupes par operateur destinataire
        // -------------------------------------------------------
        $builderInter = $db->table('transactions t')
            ->select('o_dest.nom as nom_operateur_dest, COUNT(t.id_transaction) as nb_transactions, SUM(t.frais) as total_frais')
            ->join('types_operation tp', 'tp.id_type = t.id_type')
            ->join('clients ce', 'ce.id_client = t.id_client_emetteur')       // client emetteur
            ->join('clients cd', 'cd.id_client = t.id_client_destinataire')   // client destinataire
            ->join('operateurs o_dest', 'o_dest.id_operateur = cd.id_operateur') // operateur du destinataire
            ->where('tp.code', 'TRANSFERT')                                    // seulement les transferts
            ->where('t.statut', 'reussi')                                      // seulement les reussis
            ->where('ce.id_operateur', $idOperateur)                           // emetteur = notre operateur
            ->where('cd.id_operateur !=', $idOperateur)                        // destinataire = AUTRE operateur
            ->groupBy('cd.id_operateur');                                       // grouper par operateur dest

        // Filtre par date
        if ($dateDebut) {
            $builderInter->where('t.date_transaction >=', $dateDebut);
        }
        if ($dateFin) {
            $builderInter->where('t.date_transaction <=', $dateFin . ' 23:59:59');
        }

        // Stocke les gains inter-operateur
        $data['gains_inter'] = $builderInter->get()->getResultArray();

        // Passe les filtres de date a la vue pour les re-afficher
        $data['date_debut'] = $dateDebut;
        $data['date_fin']   = $dateFin;

        // Affiche la vue des gains
        return view('admin/stats/gains', $data);
    }

    /**
     * Situation des comptes clients avec solde calcule
     * (Inchange par rapport a V1)
     */
    public function comptes()
    {
        // Connexion a la base
        $db = \Config\Database::connect();

        // Calcul du solde : depots - retraits - transferts envoyes + transferts recus
        $sql = "
            SELECT
                c.id_client,
                c.numero_telephone,
                o.nom AS nom_operateur,
                c.date_creation,
                COALESCE(
                    (SELECT SUM(t.montant) FROM transactions t
                     WHERE t.id_client_emetteur = c.id_client AND t.id_type = 1 AND t.statut = 'reussi'), 0
                )
                - COALESCE(
                    (SELECT SUM(t.montant_total) FROM transactions t
                     WHERE t.id_client_emetteur = c.id_client AND t.id_type = 2 AND t.statut = 'reussi'), 0
                )
                - COALESCE(
                    (SELECT SUM(t.montant_total) FROM transactions t
                     WHERE t.id_client_emetteur = c.id_client AND t.id_type = 3 AND t.statut = 'reussi'), 0
                )
                + COALESCE(
                    (SELECT SUM(t.montant) FROM transactions t
                     WHERE t.id_client_destinataire = c.id_client AND t.id_type = 3 AND t.statut = 'reussi'), 0
                )
                AS solde
            FROM clients c
            JOIN operateurs o ON o.id_operateur = c.id_operateur
            ORDER BY c.id_client
        ";

        // Execute la requete et passe les resultats a la vue
        $data['comptes'] = $db->query($sql)->getResultArray();
        return view('admin/stats/comptes', $data);
    }

    /**
     * V2 : Situation des montants a envoyer a chaque operateur
     * Calcule combien on doit reverser a chaque operateur externe
     * pour les transferts recus par leurs clients
     */
    public function montantsParOperateur()
    {
        // Connexion a la base
        $db = \Config\Database::connect();

        // Recupere l'ID de notre operateur
        $idOperateur = (int) session()->get('admin_operateur_id');

        // Recupere les filtres de date
        $dateDebut = $this->request->getGet('date_debut');
        $dateFin   = $this->request->getGet('date_fin');

        // Requete : total des montants transferes vers chaque operateur externe
        // On joint clients emetteur et destinataire pour identifier les operateurs
        $builder = $db->table('transactions t')
            ->select('
                o_dest.id_operateur as id_op_dest,
                o_dest.nom as nom_operateur,
                o_dest.code as code_operateur,
                COUNT(t.id_transaction) as nb_transferts,
                SUM(t.montant) as total_montant,
                SUM(t.frais) as total_frais
            ')
            ->join('types_operation tp', 'tp.id_type = t.id_type')              // type d'operation
            ->join('clients ce', 'ce.id_client = t.id_client_emetteur')         // client emetteur
            ->join('clients cd', 'cd.id_client = t.id_client_destinataire')     // client destinataire
            ->join('operateurs o_dest', 'o_dest.id_operateur = cd.id_operateur') // operateur du destinataire
            ->where('tp.code', 'TRANSFERT')                                      // seulement les transferts
            ->where('t.statut', 'reussi')                                        // seulement les reussis
            ->where('ce.id_operateur', $idOperateur)                             // emetteur = notre operateur
            ->where('cd.id_operateur !=', $idOperateur)                          // destinataire = AUTRE operateur
            ->groupBy('cd.id_operateur');                                         // un resultat par operateur

        // Filtre par date si renseigne
        if ($dateDebut) {
            $builder->where('t.date_transaction >=', $dateDebut);
        }
        if ($dateFin) {
            $builder->where('t.date_transaction <=', $dateFin . ' 23:59:59');
        }

        // Execute la requete
        $resultats = $builder->get()->getResultArray();

        // Pour chaque operateur, on recupere le % de commission configure
        $commissionModel = new \App\Models\CommissionModel();
        foreach ($resultats as &$row) {
            // Recupere le % de commission pour cet operateur
            $row['pourcentage'] = $commissionModel->getCommission($idOperateur, $row['id_op_dest']);
            // Calcule le montant de la commission
            $row['montant_commission'] = $row['total_montant'] * ($row['pourcentage'] / 100);
            // Calcule le montant net a reverser (montant - commission)
            $row['montant_net'] = $row['total_montant'] - $row['montant_commission'];
        }
        // Libere la reference
        unset($row);

        // Passe les donnees a la vue
        $data['resultats']  = $resultats;
        $data['date_debut'] = $dateDebut;
        $data['date_fin']   = $dateFin;

        // Affiche la vue
        return view('admin/stats/montants_operateurs', $data);
    }
}
