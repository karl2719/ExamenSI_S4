<?php
namespace App\Controllers;
use App\Models\AdminModel;

// Controller : authentification backoffice
class AdminAuthController extends BaseController
{
    // Affiche le formulaire de login
    public function login()
    {
        return view('admin/login');
    }

    // Traite la tentative de connexion
    public function attemptLogin()
    {
        // Recupere les champs du formulaire
        $username = $this->request->getPost('nom_utilisateur');
        $password = $this->request->getPost('mot_de_passe');

        // Charge le model admin
        $model = new AdminModel();
        // Cherche l'admin par son nom d'utilisateur
        $admin = $model->findByUsername($username);

        // Verifie identifiants (comparaison simple)
        if ($admin && $admin['mot_de_passe'] === $password) {
            // Connexion reussie : on stocke les infos en session
            session()->set([
                'admin_logged_in'    => true,              // Flag de connexion
                'admin_id'           => $admin['id_admin'],           // ID de l'admin
                'admin_nom'          => $admin['nom_utilisateur'],    // Nom affiche
                'admin_operateur_id' => $admin['id_operateur'],       // V2: ID de l'operateur lie
            ]);
            // Redirige vers le dashboard
            return redirect()->to(site_url('admin'));
        }

        // Echec : retour au login avec message d'erreur
        session()->setFlashdata('error', 'Nom utilisateur ou mot de passe incorrect');
        return redirect()->to(site_url('admin/login'));
    }

    // Affiche le tableau de bord
    public function dashboard()
    {
        // V2: Recupere le nom de l'operateur lie a l'admin connecte
        $db = \Config\Database::connect();
        $operateur = $db->table('operateurs')
            ->where('id_operateur', session()->get('admin_operateur_id'))
            ->get()
            ->getRowArray();
        // Passe le nom de l'operateur a la vue
        $data['nom_operateur'] = $operateur ? $operateur['nom'] : 'Non defini';
        return view('admin/dashboard', $data);
    }

    // Deconnexion
    public function logout()
    {
        // Detruit toute la session
        session()->destroy();
        // Redirige vers la page de login
        return redirect()->to(site_url('admin/login'));
    }
}
