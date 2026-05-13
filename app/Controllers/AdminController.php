<?php

namespace App\Controllers;

use App\Models\DepartementModel;
use App\Models\EmployeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;

class AdminController extends BaseController
{
    public function dashboard()
    {
        if ($guard = $this->requireRole('admin')) {
            return $guard;
        }

        return view('admin/dashboard');
    }

    public function employes()
    {
        if ($guard = $this->requireRole('admin')) {
            return $guard;
        }

        $employeModel = new EmployeModel();
        $editId = (int) $this->request->getGet('edit');
        $editingEmploye = $editId > 0 ? $employeModel->find($editId) : null;

        return view('admin/employes', [
            'employes'       => $employeModel
                ->select('employes.*, departements.nom as departement_nom')
                ->join('departements', 'departements.id = employes.departement_id', 'left')
                ->orderBy('employes.id', 'DESC')
                ->findAll(),
            'departements'   => (new DepartementModel())->findAll(),
            'editingEmploye' => $editingEmploye,
        ]);
    }

    public function storeEmploye()
    {
        if ($guard = $this->requireRole('admin')) {
            return $guard;
        }

        $rules = [
            'prenom'         => 'required|min_length[2]|max_length[100]',
            'nom'            => 'required|min_length[2]|max_length[100]',
            'email'          => 'required|valid_email|is_unique[employes.email]',
            'password'       => 'required|min_length[6]',
            'role'           => 'required|in_list[employe,rh,admin]',
            'departement_id' => 'required|integer',
            'date_embauche'  => 'required|valid_date',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/admin/employes')->withInput()->with('errors', $this->validator->getErrors());
        }

        $employeModel = new EmployeModel();
        $typeCongeModel = new TypeCongeModel();
        $soldeModel = new SoldeModel();

        $employeId = $employeModel->insert([
            'prenom'         => trim((string) $this->request->getPost('prenom')),
            'nom'            => trim((string) $this->request->getPost('nom')),
            'email'          => strtolower(trim((string) $this->request->getPost('email'))),
            'password'       => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'           => (string) $this->request->getPost('role'),
            'departement_id' => (int) $this->request->getPost('departement_id'),
            'date_embauche'  => (string) $this->request->getPost('date_embauche') . ' 00:00:00',
            'actif'          => 1,
        ], true);

        $annee = (int) date('Y');

        foreach ($typeCongeModel->findAll() as $typeConge) {
            $soldeModel->insert([
                'employe_id'      => $employeId,
                'type_conge_id'   => $typeConge['id'],
                'annee'           => $annee,
                'jours_attribues' => $typeConge['jours_annuels'],
                'jours_pris'      => 0,
            ]);
        }

        return redirect()->to('/admin/employes')->with('success', 'Employe cree avec succes.');
    }

    public function updateEmploye(int $id)
    {
        if ($guard = $this->requireRole('admin')) {
            return $guard;
        }

        $employeModel = new EmployeModel();
        $employe = $employeModel->find($id);

        if ($employe === null) {
            return redirect()->to('/admin/employes')->with('error', 'Employe introuvable.');
        }

        $rules = [
            'prenom'         => 'required|min_length[2]|max_length[100]',
            'nom'            => 'required|min_length[2]|max_length[100]',
            'email'          => 'required|valid_email',
            'role'           => 'required|in_list[employe,rh,admin]',
            'departement_id' => 'required|integer',
            'date_embauche'  => 'required|valid_date',
            'password'       => 'permit_empty|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/admin/employes?edit=' . $id)->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = strtolower(trim((string) $this->request->getPost('email')));
        $duplicate = $employeModel->where('email', $email)->where('id !=', $id)->first();

        if ($duplicate !== null) {
            return redirect()->to('/admin/employes?edit=' . $id)->withInput()->with('error', 'Cette adresse email est deja utilisee.');
        }

        $data = [
            'prenom'         => trim((string) $this->request->getPost('prenom')),
            'nom'            => trim((string) $this->request->getPost('nom')),
            'email'          => $email,
            'role'           => (string) $this->request->getPost('role'),
            'departement_id' => (int) $this->request->getPost('departement_id'),
            'date_embauche'  => (string) $this->request->getPost('date_embauche') . ' 00:00:00',
        ];

        $password = trim((string) $this->request->getPost('password'));

        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $employeModel->update($id, $data);

        return redirect()->to('/admin/employes')->with('success', 'Employe mis a jour.');
    }

    public function deactivateEmploye(int $id)
    {
        if ($guard = $this->requireRole('admin')) {
            return $guard;
        }

        $employeModel = new EmployeModel();
        $employe = $employeModel->find($id);

        if ($employe === null) {
            return redirect()->to('/admin/employes')->with('error', 'Employe introuvable.');
        }

        if ((int) $employe['id'] === (int) ($this->currentUser()['id'] ?? 0)) {
            return redirect()->to('/admin/employes')->with('error', 'Vous ne pouvez pas vous desactiver vous-meme.');
        }

        $employeModel->update($id, ['actif' => 0]);

        return redirect()->to('/admin/employes')->with('success', 'Employe desactive.');
    }
}
