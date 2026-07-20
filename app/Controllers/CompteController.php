<?php

namespace App\Controllers;

use App\Models\ClientModel;

/**
 * CompteController — Affichage du dashboard client
 *
 * Ce controleur gere l'affichage du tableau de bord du client connecte.
 * Il affiche le solde actuel et les boutons pour acceder aux operations.
 *
 * Route associee :
 *   GET /compte -> index() : affiche le dashboard
 */
class CompteController extends BaseController
{
    /**
     * Affiche le dashboard du client avec son solde et les boutons d'operations
     *
     * Donnees passees a la vue :
     * - client : les informations du client (numero, operateur, etc.)
     * - solde  : le solde calcule a partir des transactions
     */
    public function index()
    {
        $clientModel = new ClientModel();

        // Recuperer l'ID du client connecte depuis la session
        $idClient = session()->get('id_client');

        // Recuperer les informations completes du client
        $client = $clientModel->find($idClient);

        // Calculer le solde actuel du client
        // (somme des depots - retraits - transferts envoyes + transferts recus)
        $solde = $clientModel->getSolde($idClient);

        // Afficher le dashboard avec les donnees
        return view('client/dashboard', [
            'client' => $client,
            'solde'  => $solde,
        ]);
    }
}
