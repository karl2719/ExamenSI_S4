<?php

/**
 * ===============================================================
 * Configuration des routes — FrontOffice Mobile Money
 * ===============================================================
 *
 * Ce fichier definit toutes les routes de l'application.
 * Les routes sont organisees en 2 groupes :
 *   1. Routes publiques (login, logout) — accessibles sans connexion
 *   2. Routes protegees (compte, transactions) — necessitent le filtre 'auth'
 */

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ---------------------------------------------------------------
// Page d'accueil : redirige vers la page de connexion
// ---------------------------------------------------------------
$routes->get('/', 'AuthController::index');

// ---------------------------------------------------------------
// Routes d'authentification (PUBLIQUES — pas de filtre auth)
// ---------------------------------------------------------------

// Afficher le formulaire de connexion
$routes->get('/login', 'AuthController::index');

// Traiter la connexion (envoi du formulaire)
$routes->post('/login', 'AuthController::login');

// Deconnexion (detruit la session)
$routes->get('/logout', 'AuthController::logout');

// ---------------------------------------------------------------
// Routes PROTEGEES par le filtre d'authentification
// Toutes ces routes necessitent que le client soit connecte.
// Si le client n'est pas connecte, il sera redirige vers /login.
// ---------------------------------------------------------------
$routes->group('', ['filter' => 'auth'], function ($routes) {

    // --- Dashboard client ---
    // Affiche le solde et les boutons d'operations
    $routes->get('/compte', 'CompteController::index');

    // --- Depot ---
    // GET  : affiche le formulaire de depot
    // POST : traite le depot
    $routes->get('/transaction/depot', 'TransactionController::depot');
    $routes->post('/transaction/depot', 'TransactionController::depot');

    // --- Retrait ---
    // GET  : affiche le formulaire de retrait
    // POST : traite le retrait (avec calcul des frais)
    $routes->get('/transaction/retrait', 'TransactionController::retrait');
    $routes->post('/transaction/retrait', 'TransactionController::retrait');

    // --- Transfert ---
    // GET  : affiche le formulaire de transfert
    // POST : traite le transfert (avec calcul des frais)
    $routes->get('/transaction/transfert', 'TransactionController::transfert');
    $routes->post('/transaction/transfert', 'TransactionController::transfert');

    // --- Historique ---
    // Affiche l'historique de toutes les transactions du client
    $routes->get('/transaction/historique', 'TransactionController::historique');
});
