<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

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

