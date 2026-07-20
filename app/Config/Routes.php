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

    // --- Envoi multiple ---
    // GET  : affiche le formulaire d'envoi multiple
    // POST : traite l'envoi multiple (montant divise entre destinataires)
    $routes->get('/transaction/envoi-multiple', 'TransactionController::envoiMultiple');
    $routes->post('/transaction/envoi-multiple', 'TransactionController::envoiMultiple');

    // --- Historique ---
    // Affiche l'historique de toutes les transactions du client
    $routes->get('/transaction/historique', 'TransactionController::historique');
});

// ===== ROUTES BACKOFFICE (admin/) =====

// Auth admin
$routes->get('admin/login', 'AdminAuthController::login');
$routes->post('admin/login', 'AdminAuthController::attemptLogin');
$routes->get('admin/logout', 'AdminAuthController::logout');
$routes->get('admin', 'AdminAuthController::dashboard');

// Operateurs
$routes->get('admin/operateurs', 'AdminOperateurController::index');
$routes->get('admin/operateurs/create', 'AdminOperateurController::create');
$routes->post('admin/operateurs/store', 'AdminOperateurController::store');
$routes->get('admin/operateurs/edit/(:num)', 'AdminOperateurController::edit/$1');
$routes->post('admin/operateurs/update/(:num)', 'AdminOperateurController::update/$1');
$routes->get('admin/operateurs/delete/(:num)', 'AdminOperateurController::delete/$1');

// Prefixes
$routes->get('admin/operateurs/(:num)/prefixes', 'AdminOperateurController::prefixes/$1');
$routes->post('admin/operateurs/(:num)/prefixes/store', 'AdminOperateurController::storePrefix/$1');
$routes->get('admin/prefixes/delete/(:num)', 'AdminOperateurController::deletePrefix/$1');

// Baremes de frais
$routes->get('admin/baremes', 'AdminBaremeController::index');
$routes->get('admin/baremes/create', 'AdminBaremeController::create');
$routes->post('admin/baremes/store', 'AdminBaremeController::store');
$routes->get('admin/baremes/edit/(:num)', 'AdminBaremeController::edit/$1');
$routes->post('admin/baremes/update/(:num)', 'AdminBaremeController::update/$1');
$routes->get('admin/baremes/delete/(:num)', 'AdminBaremeController::delete/$1');

// Statistiques
$routes->get('admin/stats/gains', 'AdminStatsController::gains');
$routes->get('admin/stats/comptes', 'AdminStatsController::comptes');

// ===== V2 : Commissions inter-operateur =====
$routes->get('admin/commissions', 'AdminCommissionController::index');
$routes->get('admin/commissions/create', 'AdminCommissionController::create');
$routes->post('admin/commissions/store', 'AdminCommissionController::store');
$routes->get('admin/commissions/edit/(:num)', 'AdminCommissionController::edit/$1');
$routes->post('admin/commissions/update/(:num)', 'AdminCommissionController::update/$1');
$routes->get('admin/commissions/delete/(:num)', 'AdminCommissionController::delete/$1');

// V2 : Montants a envoyer par operateur
$routes->get('admin/stats/montants-operateurs', 'AdminStatsController::montantsParOperateur');
