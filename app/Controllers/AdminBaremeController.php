<?php
namespace App\Controllers;
use App\Models\BaremeFraisModel;
use App\Models\TypeOperationModel;

// Controller : gestion des baremes de frais
class AdminBaremeController extends BaseController
{
    // Liste tous les baremes, groupes par type
    public function index()
    {
        $baremeModel = new BaremeFraisModel();
        $typeModel   = new TypeOperationModel();

        $data['types']   = $typeModel->findAll();
        $data['baremes'] = $baremeModel->orderBy('montant_min', 'ASC')->findAll();
        return view('admin/baremes/index', $data);
    }

    // Formulaire de creation
    public function create()
    {
        $typeModel = new TypeOperationModel();
        $data['types'] = $typeModel->findAll();
        return view('admin/baremes/form', $data);
    }

    // Enregistre un nouveau bareme
    public function store()
    {
        $model = new BaremeFraisModel();
        $model->insert([
            'id_type'     => $this->request->getPost('id_type'),
            'montant_min' => $this->request->getPost('montant_min'),
            'montant_max' => $this->request->getPost('montant_max'),
            'frais'       => $this->request->getPost('frais'),
        ]);
        session()->setFlashdata('success', 'Bareme ajoute');
        return redirect()->to(site_url('admin/baremes'));
    }

    // Formulaire de modification
    public function edit($id)
    {
        $baremeModel = new BaremeFraisModel();
        $typeModel   = new TypeOperationModel();

        $data['bareme'] = $baremeModel->find($id);
        $data['types']  = $typeModel->findAll();
        return view('admin/baremes/form', $data);
    }

    // Met a jour un bareme
    public function update($id)
    {
        $model = new BaremeFraisModel();
        $model->update($id, [
            'id_type'     => $this->request->getPost('id_type'),
            'montant_min' => $this->request->getPost('montant_min'),
            'montant_max' => $this->request->getPost('montant_max'),
            'frais'       => $this->request->getPost('frais'),
        ]);
        session()->setFlashdata('success', 'Bareme modifie');
        return redirect()->to(site_url('admin/baremes'));
    }

    // Supprime un bareme
    public function delete($id)
    {
        $model = new BaremeFraisModel();
        $model->delete($id);
        session()->setFlashdata('success', 'Bareme supprime');
        return redirect()->to(site_url('admin/baremes'));
    }
}
