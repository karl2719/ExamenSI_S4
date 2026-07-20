<?php
namespace App\Controllers;
use App\Models\OperateurModel;
use App\Models\PrefixeModel;

// Controller : gestion des operateurs et prefixes
class AdminOperateurController extends BaseController
{
    // Liste tous les operateurs
    public function index()
    {
        $model = new OperateurModel();
        $data['operateurs'] = $model->findAll();
        return view('admin/operateurs/index', $data);
    }

    // Formulaire de creation
    public function create()
    {
        return view('admin/operateurs/form');
    }

    // Enregistre un nouvel operateur
    public function store()
    {
        $model = new OperateurModel();
        $model->insert([
            'nom'  => $this->request->getPost('nom'),
            'code' => $this->request->getPost('code'),
        ]);
        session()->setFlashdata('success', 'Operateur ajoute');
        return redirect()->to(site_url('admin/operateurs'));
    }

    // Formulaire de modification
    public function edit($id)
    {
        $model = new OperateurModel();
        $data['operateur'] = $model->find($id);
        return view('admin/operateurs/form', $data);
    }

    // Met a jour un operateur
    public function update($id)
    {
        $model = new OperateurModel();
        $model->update($id, [
            'nom'  => $this->request->getPost('nom'),
            'code' => $this->request->getPost('code'),
        ]);
        session()->setFlashdata('success', 'Operateur modifie');
        return redirect()->to(site_url('admin/operateurs'));
    }

    // Supprime un operateur
    public function delete($id)
    {
        $model = new OperateurModel();
        $model->delete($id);
        session()->setFlashdata('success', 'Operateur supprime');
        return redirect()->to(site_url('admin/operateurs'));
    }

    // --- PREFIXES ---

    // Liste les prefixes d'un operateur
    public function prefixes($idOperateur)
    {
        $opModel = new OperateurModel();
        $prefModel = new PrefixeModel();

        $data['operateur'] = $opModel->find($idOperateur);
        $data['prefixes']  = $prefModel->getByOperateur($idOperateur);
        return view('admin/operateurs/prefixes', $data);
    }

    // Ajoute un prefixe a un operateur
    public function storePrefix($idOperateur)
    {
        $model = new PrefixeModel();
        $model->insert([
            'prefixe'      => $this->request->getPost('prefixe'),
            'id_operateur' => $idOperateur,
        ]);
        session()->setFlashdata('success', 'Prefixe ajoute');
        return redirect()->to(site_url('admin/operateurs/' . $idOperateur . '/prefixes'));
    }

    // Supprime un prefixe
    public function deletePrefix($id)
    {
        $model = new PrefixeModel();
        $prefixe = $model->find($id);
        $model->delete($id);
        session()->setFlashdata('success', 'Prefixe supprime');
        return redirect()->to(site_url('admin/operateurs/' . $prefixe['id_operateur'] . '/prefixes'));
    }
}
