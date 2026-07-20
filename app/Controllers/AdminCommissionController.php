<?php
namespace App\Controllers;
use App\Models\CommissionModel;
use App\Models\OperateurModel;

// Controller : gestion des commissions inter-operateur (V2)
// Permet de configurer le % de commission pour les transferts
// entre notre operateur et les autres operateurs
class AdminCommissionController extends BaseController
{
    // Liste les commissions configurees pour notre operateur
    public function index()
    {
        // Charge le model des commissions
        $model = new CommissionModel();
        // Recupere l'ID de l'operateur de l'admin connecte depuis la session
        $idOperateur = (int) session()->get('admin_operateur_id');
        // Recupere les commissions de notre operateur (avec noms des destinataires)
        $data['commissions'] = $model->getBySource($idOperateur);
        // Affiche la vue liste
        return view('admin/commissions/index', $data);
    }

    // Affiche le formulaire de creation d'une commission
    public function create()
    {
        // Charge le model des operateurs
        $opModel = new OperateurModel();
        // Recupere l'ID de notre operateur
        $idOperateur = (int) session()->get('admin_operateur_id');
        // Recupere tous les operateurs SAUF le notre (on ne se facture pas soi-meme)
        $data['operateurs'] = $opModel->where('id_operateur !=', $idOperateur)->findAll();
        // Affiche le formulaire
        return view('admin/commissions/form', $data);
    }

    // Enregistre une nouvelle commission en base
    public function store()
    {
        // Charge le model
        $model = new CommissionModel();
        // Recupere l'ID de notre operateur
        $idOperateur = (int) session()->get('admin_operateur_id');
        // Insere la commission en base
        $model->insert([
            'id_operateur_source' => $idOperateur,                              // Notre operateur
            'id_operateur_dest'   => $this->request->getPost('id_operateur_dest'), // L'autre operateur
            'pourcentage'         => $this->request->getPost('pourcentage'),       // Le % de commission
        ]);
        // Message de succes
        session()->setFlashdata('success', 'Commission ajoutee');
        // Redirige vers la liste
        return redirect()->to(site_url('admin/commissions'));
    }

    // Affiche le formulaire de modification d'une commission
    public function edit($id)
    {
        // Charge les models
        $model = new CommissionModel();
        $opModel = new OperateurModel();
        // Recupere l'ID de notre operateur
        $idOperateur = (int) session()->get('admin_operateur_id');
        // Recupere la commission a modifier
        $data['commission'] = $model->find($id);
        // Recupere les autres operateurs
        $data['operateurs'] = $opModel->where('id_operateur !=', $idOperateur)->findAll();
        // Affiche le formulaire pre-rempli
        return view('admin/commissions/form', $data);
    }

    // Met a jour une commission existante
    public function update($id)
    {
        // Charge le model
        $model = new CommissionModel();
        // Met a jour les champs modifies
        $model->update($id, [
            'id_operateur_dest' => $this->request->getPost('id_operateur_dest'), // Operateur cible
            'pourcentage'       => $this->request->getPost('pourcentage'),       // Nouveau %
        ]);
        // Message de succes
        session()->setFlashdata('success', 'Commission modifiee');
        // Redirige vers la liste
        return redirect()->to(site_url('admin/commissions'));
    }

    // Supprime une commission
    public function delete($id)
    {
        // Charge le model
        $model = new CommissionModel();
        // Supprime la commission
        $model->delete($id);
        // Message de succes
        session()->setFlashdata('success', 'Commission supprimee');
        // Redirige vers la liste
        return redirect()->to(site_url('admin/commissions'));
    }
}
