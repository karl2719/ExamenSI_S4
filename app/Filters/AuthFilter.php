<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AuthFilter — Filtre d'authentification pour les pages client
 *
 * Ce filtre est applique sur toutes les routes protegees (dashboard, transactions, etc.)
 * Il verifie que le client est connecte en cherchant 'id_client' dans la session.
 * Si le client n'est pas connecte, il est redirige vers la page de login.
 */
class AuthFilter implements FilterInterface
{
    /**
     * Verifie l'authentification AVANT l'execution du controleur
     *
     * @param  RequestInterface $request    La requete HTTP entrante
     * @param  mixed            $arguments  Arguments optionnels du filtre
     * @return mixed                        Redirection vers /login si non connecte
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verifier si le client est connecte (cle 'connecte' en session)
        if (!session()->get('connecte')) {
            // Non connecte : rediriger vers la page de login
            return redirect()->to('/login');
        }

        // Connecte : laisser passer la requete vers le controleur
    }

    /**
     * Execute apres le controleur — rien a faire ici
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Pas de traitement apres la requete
    }
}
