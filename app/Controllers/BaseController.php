<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController — Controleur de base pour toute l'application
 *
 * Tous les controleurs heritent de cette classe.
 * On y charge la session, les helpers, et on initialise la BDD SQLite
 * automatiquement si les tables n'existent pas encore.
 */
abstract class BaseController extends Controller
{
    /**
     * @var \CodeIgniter\Session\Session
     * Instance de la session, disponible dans tous les controleurs enfants
     */
    protected $session;

    /**
     * Helpers charges automatiquement dans tous les controleurs
     * - form : fonctions d'aide pour les formulaires (form_open, form_close, etc.)
     * - url  : fonctions d'aide pour les URLs (base_url, site_url, etc.)
     */
    protected $helpers = ['form', 'url'];

    /**
     * Initialisation du controleur
     * - Charge la session
     * - Initialise la BDD SQLite si les tables n'existent pas
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Ne pas modifier cette ligne — appel au parent obligatoire
        parent::initController($request, $response, $logger);

        // Charger la session pour pouvoir gerer la connexion client
        $this->session = service('session');

        // Initialiser la base de donnees SQLite si les tables n'existent pas encore
        $this->initDatabase();
    }

    /**
     * Initialise la base de donnees SQLite en executant base.sql
     *
     * Cette methode verifie si la table 'operateurs' existe.
     * Si elle n'existe pas, cela signifie que la BDD est vierge
     * et on execute le script base.sql pour creer toutes les tables
     * et inserer les donnees de reference.
     */
    protected function initDatabase(): void
    {
        $db = \Config\Database::connect();

        // Verifier si les tables existent deja en cherchant la table 'operateurs'
        $tables = $db->listTables();
        if (!in_array('operateurs', $tables)) {
            // Lire le fichier base.sql a la racine du projet
            $sqlFile = ROOTPATH . 'base.sql';

            if (file_exists($sqlFile)) {
                $sql = file_get_contents($sqlFile);

                // Decouper le SQL en instructions individuelles (SQLite ne supporte
                // pas l'execution de plusieurs requetes en une seule fois)
                $statements = explode(';', $sql);

                foreach ($statements as $statement) {
                    $statement = trim($statement);
                    // Ignorer les lignes vides et les commentaires
                    if (!empty($statement) && !str_starts_with($statement, '--')) {
                        $db->query($statement);
                    }
                }
            }
        }
    }
}
