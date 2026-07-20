<?php
namespace App\Controllers;

// Controller : statistiques backoffice (gains et comptes)
class AdminStatsController extends BaseController
{
    // Situation des gains (frais collectes par type d'operation)
    public function gains()
    {
        $db = \Config\Database::connect();

        // Recupere les filtres de date
        $dateDebut = $this->request->getGet('date_debut');
        $dateFin   = $this->request->getGet('date_fin');

        // Requete : total frais par type d'operation
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

        $data['gains']      = $builder->get()->getResultArray();
        $data['date_debut'] = $dateDebut;
        $data['date_fin']   = $dateFin;

        return view('admin/stats/gains', $data);
    }

    // Situation des comptes clients avec solde calcule
    public function comptes()
    {
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

        $data['comptes'] = $db->query($sql)->getResultArray();
        return view('admin/stats/comptes', $data);
    }
}
