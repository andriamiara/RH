<?php

namespace App\Controllers;

use App\Models\DepartementModel;
use App\Models\EmployeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;

class AuthController extends BaseController
{
    public function login()
    {
        if ($this->currentUser() !== null) {
            return redirect()->to($this->dashboardForRole($this->currentUser()['role'] ?? null));
        }

        return view('auth/login');
    }

    public function attemptLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new EmployeModel();
        $user  = $model->where('email', $this->request->getPost('email'))->first();

        if ($user === null || ! password_verify((string) $this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Identifiants incorrects.');
        }

        if ((int) ($user['actif'] ?? 0) !== 1) {
            return redirect()->back()->withInput()->with('error', 'Ce compte est inactif.');
        }

        $this->session->set('user', [
            'id'              => $user['id'],
            'nom'             => $user['nom'],
            'prenom'          => $user['prenom'],
            'email'           => $user['email'],
            'role'            => $user['role'],
            'departement_id'  => $user['departement_id'],
        ]);

        return redirect()->to($this->dashboardForRole($user['role']))->with('success', 'Connexion reussie.');
    }

    public function register()
    {
        if ($this->currentUser() !== null) {
            return redirect()->to($this->dashboardForRole($this->currentUser()['role'] ?? null));
        }

        return view('auth/register', [
            'departements' => (new DepartementModel())->findAll(),
        ]);
    }

    public function storeRegistration()
    {
        $rules = [
            'prenom'         => 'required|min_length[2]|max_length[100]',
            'nom'            => 'required|min_length[2]|max_length[100]',
            'email'          => 'required|valid_email|is_unique[employes.email]',
            'password'       => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
            'departement_id' => 'required|integer',
            'date_embauche'  => 'required|valid_date',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $employeModel = new EmployeModel();
        $departementModel = new DepartementModel();
        $soldeModel = new SoldeModel();
        $typeCongeModel = new TypeCongeModel();

        $departementId = (int) $this->request->getPost('departement_id');
        $departement = $departementModel->find($departementId);

        if ($departement === null) {
            return redirect()->back()->withInput()->with('error', 'Departement introuvable.');
        }

        $employeId = $employeModel->insert([
            'prenom'         => trim((string) $this->request->getPost('prenom')),
            'nom'            => trim((string) $this->request->getPost('nom')),
            'email'          => strtolower(trim((string) $this->request->getPost('email'))),
            'password'       => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'           => 'employe',
            'departement_id' => $departementId,
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

        return redirect()->to('/login')->with('success', 'Inscription terminee. Vous pouvez maintenant vous connecter.');
    }

    public function logout()
    {
        $this->session->destroy();

        return redirect()->to('/login')->with('success', 'Deconnexion reussie.');
    }
}
