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
        $username = $this->request->getPost('nom_utilisateur');
        $password = $this->request->getPost('mot_de_passe');

        $model = new AdminModel();
        $admin = $model->findByUsername($username);

        // Verifie identifiants (comparaison simple)
        if ($admin && $admin['mot_de_passe'] === $password) {
            session()->set([
                'admin_logged_in' => true,
                'admin_id'        => $admin['id_admin'],
                'admin_nom'       => $admin['nom_utilisateur'],
            ]);
            return redirect()->to(site_url('admin'));
        }

        // Echec : retour au login avec message
        session()->setFlashdata('error', 'Nom utilisateur ou mot de passe incorrect');
        return redirect()->to(site_url('admin/login'));
    }

    // Affiche le tableau de bord
    public function dashboard()
    {
        return view('admin/dashboard');
    }

    // Deconnexion
    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('admin/login'));
    }
}
