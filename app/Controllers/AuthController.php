<?php

namespace App\Controllers;

use App\Models\ClientModel;

/**
 * AuthController — Gestion de l'authentification client
 *
 * Ce controleur gere le login automatique par numero de telephone.
 * Pas d'inscription prealable : si le numero n'existe pas en base,
 * le client est cree automatiquement lors de la connexion.
 *
 * Routes associees :
 *   GET  /login  -> index()   : affiche le formulaire de connexion
 *   POST /login  -> login()   : traite la connexion
 *   GET  /logout -> logout()  : deconnexion
 */
class AuthController extends BaseController
{
    /**
     * Affiche le formulaire de connexion (saisie du numero de telephone)
     *
     * Si le client est deja connecte, on le redirige directement vers le dashboard.
     */
    public function index()
    {
        // Si le client est deja connecte, pas besoin de se reconnecter
        if (session()->get('connecte')) {
            return redirect()->to('/compte');
        }

        // Afficher le formulaire de connexion
        return view('client/login');
    }

    /**
     * Traite la connexion automatique par numero de telephone
     *
     * Etapes :
     * 1. Valider le numero (non vide, minimum 10 chiffres)
     * 2. Verifier que le prefixe (3 premiers chiffres) est connu
     * 3. Chercher le client en base par son numero
     * 4. Si le client n'existe pas, le creer automatiquement
     * 5. Stocker les infos du client en session
     * 6. Rediriger vers le dashboard
     */
    public function login()
    {
        // Recuperer le numero saisi dans le formulaire
        $numero = $this->request->getPost('numero_telephone');

        // ---- Validation du numero ----
        if (empty($numero) || strlen($numero) < 10) {
            return redirect()->back()
                             ->with('error', 'Numero de telephone invalide (minimum 10 chiffres)');
        }

        // ---- Verification du prefixe ----
        // Extraire les 3 premiers chiffres (ex: "033", "037")
        $prefixe = substr($numero, 0, 3);

        $db = \Config\Database::connect();
        $prefixeExiste = $db->table('prefixes')
                            ->where('prefixe', $prefixe)
                            ->get()
                            ->getRowArray();

        if (!$prefixeExiste) {
            return redirect()->back()
                             ->with('error', 'Prefixe non reconnu. Prefixes valides : 033, 037');
        }

        // ---- Recherche ou creation du client ----
        $clientModel = new ClientModel();

        // Chercher le client par son numero de telephone
        $client = $clientModel->findByTelephone($numero);

        if (!$client) {
            // Le client n'existe pas encore — le creer automatiquement
            // (pas d'inscription prealable comme demande dans le sujet)
            $idClient = $clientModel->creerAutomatique($numero);

            if (!$idClient) {
                return redirect()->back()
                                 ->with('error', 'Erreur lors de la creation du compte');
            }

            // Recuperer les donnees du client nouvellement cree
            $client = $clientModel->find($idClient);
        }

        // ---- Demarrer la session ----
        // Stocker les informations du client en session
        session()->set([
            'id_client'         => $client['id_client'],
            'numero_telephone'  => $client['numero_telephone'],
            'connecte'          => true, // Flag de connexion pour le filtre AuthFilter
        ]);

        // Rediriger vers le dashboard client
        return redirect()->to('/compte');
    }

    /**
     * Deconnexion du client
     *
     * Detruit la session et redirige vers la page de login.
     */
    public function logout()
    {
        // Detruire toute la session
        session()->destroy();

        // Rediriger vers la page de connexion
        return redirect()->to('/login');
    }
}
