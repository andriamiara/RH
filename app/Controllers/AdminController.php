<?php

namespace App\Controllers;

use App\Models\DepartementModel;
use App\Models\EmployeModel;
use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;

class AdminController extends BaseController
{
    public function dashboard()
    {
        if ($guard = $this->requireRole('admin')) {
            return $guard;
        }

        $employeModel = new EmployeModel();
        $departementModel = new DepartementModel();
        $typeCongeModel = new TypeCongeModel();
        $congeModel = new CongeModel();

        $monthStart = date('Y-m-01 00:00:00');
        $monthEnd = date('Y-m-t 23:59:59');

        $approvedThisMonth = $congeModel
            ->where('statut', 'approuvee')
            ->groupStart()
                ->where('date_debut <=', $monthEnd)
                ->where('date_fin >=', $monthStart)
            ->groupEnd()
            ->findAll();

        $absencesThisMonth = 0;

        foreach ($approvedThisMonth as $conge) {
            $start = max(strtotime((string) $conge['date_debut']), strtotime($monthStart));
            $end = min(strtotime((string) $conge['date_fin']), strtotime($monthEnd));

            if ($start !== false && $end !== false && $end >= $start) {
                $absencesThisMonth += (int) floor(($end - $start) / 86400) + 1;
            }
        }

        $recentRequests = $congeModel
            ->select('conges.*, types_conge.libelle as type_libelle, employes.prenom, employes.nom')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->join('employes', 'employes.id = conges.employe_id')
            ->orderBy('conges.created_at', 'DESC')
            ->findAll(5);

        return view('admin/dashboard', [
            'employesActifs'     => $employeModel->where('actif', 1)->countAllResults(),
            'departementsCount'  => $departementModel->countAllResults(),
            'typesCongeCount'    => $typeCongeModel->countAllResults(),
            'demandesEnAttente'  => $congeModel->where('statut', 'en_attente')->countAllResults(),
            'absencesThisMonth'  => $absencesThisMonth,
            'currentMonthLabel'  => date('m/Y'),
            'recentRequests'     => $recentRequests,
        ]);
    }

    public function soldes()
    {
        if ($guard = $this->requireRole('admin')) {
            return $guard;
        }

        $soldeModel = new SoldeModel();
        $editId = (int) $this->request->getGet('edit');
        $editingSolde = $editId > 0
            ? $soldeModel
                ->select('soldes.*, employes.prenom, employes.nom, types_conge.libelle as type_libelle')
                ->join('employes', 'employes.id = soldes.employe_id')
                ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
                ->where('employes.role', 'employe')
                ->find($editId)
            : null;

        return view('admin/soldes', [
            'soldes' => $soldeModel
                ->select('soldes.*, employes.prenom, employes.nom, employes.email, employes.role, types_conge.libelle as type_libelle')
                ->join('employes', 'employes.id = soldes.employe_id')
                ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
                ->where('employes.role', 'employe')
                ->orderBy('soldes.annee', 'DESC')
                ->orderBy('employes.nom', 'ASC')
                ->findAll(),
            'employes'     => (new EmployeModel())
                ->where('actif', 1)
                ->where('role', 'employe')
                ->orderBy('nom', 'ASC')
                ->findAll(),
            'typesConge'   => (new TypeCongeModel())->orderBy('libelle', 'ASC')->findAll(),
            'editingSolde' => $editingSolde,
        ]);
    }

    public function saveSolde()
    {
        if ($guard = $this->requireRole('admin')) {
            return $guard;
        }

        $rules = [
            'solde_id'        => 'permit_empty|integer',
            'employe_id'      => 'required|integer',
            'type_conge_id'   => 'required|integer',
            'annee'           => 'required|integer|greater_than_equal_to[2000]',
            'jours_attribues' => 'required|integer|greater_than_equal_to[0]',
            'jours_pris'      => 'required|integer|greater_than_equal_to[0]',
        ];

        if (! $this->validate($rules)) {
            $target = $this->request->getPost('solde_id') ? '/admin/soldes?edit=' . $this->request->getPost('solde_id') : '/admin/soldes';
            return redirect()->to($target)->withInput()->with('errors', $this->validator->getErrors());
        }

        $soldeId = (int) ($this->request->getPost('solde_id') ?: 0);
        $employeId = (int) $this->request->getPost('employe_id');
        $typeCongeId = (int) $this->request->getPost('type_conge_id');
        $annee = (int) $this->request->getPost('annee');
        $joursAttribues = (int) $this->request->getPost('jours_attribues');
        $joursPris = (int) $this->request->getPost('jours_pris');
        $employe = (new EmployeModel())->find($employeId);

        if ($employe === null || ($employe['role'] ?? null) !== 'employe') {
            $target = $soldeId > 0 ? '/admin/soldes?edit=' . $soldeId : '/admin/soldes';
            return redirect()->to($target)->withInput()->with('error', 'Le solde annuel ne peut etre gere que pour un employe.');
        }

        if ($joursPris > $joursAttribues) {
            $target = $soldeId > 0 ? '/admin/soldes?edit=' . $soldeId : '/admin/soldes';
            return redirect()->to($target)->withInput()->with('error', 'Les jours pris ne peuvent pas depasser les jours attribues.');
        }

        $soldeModel = new SoldeModel();
        $existing = $soldeModel
            ->where('employe_id', $employeId)
            ->where('type_conge_id', $typeCongeId)
            ->where('annee', $annee)
            ->first();

        if ($existing !== null && (int) $existing['id'] !== $soldeId) {
            return redirect()->to('/admin/soldes')->withInput()->with('error', 'Un solde existe deja pour cet employe, ce type et cette annee.');
        }

        $payload = [
            'employe_id'      => $employeId,
            'type_conge_id'   => $typeCongeId,
            'annee'           => $annee,
            'jours_attribues' => $joursAttribues,
            'jours_pris'      => $joursPris,
        ];

        if ($soldeId > 0) {
            $soldeModel->update($soldeId, $payload);
            return redirect()->to('/admin/soldes')->with('success', 'Solde annuel ajuste.');
        }

        $soldeModel->insert($payload);
        return redirect()->to('/admin/soldes')->with('success', 'Solde annuel initialise.');
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

    public function departements()
    {
        if ($guard = $this->requireRole('admin')) {
            return $guard;
        }

        $departementModel = new DepartementModel();
        $editId = (int) $this->request->getGet('edit');
        $editingDepartement = $editId > 0 ? $departementModel->find($editId) : null;

        return view('admin/departements', [
            'departements' => $departementModel
                ->select('departements.*, COUNT(employes.id) as employes_count')
                ->join('employes', 'employes.departement_id = departements.id', 'left')
                ->groupBy('departements.id')
                ->orderBy('departements.nom', 'ASC')
                ->findAll(),
            'editingDepartement' => $editingDepartement,
        ]);
    }

    public function storeDepartement()
    {
        if ($guard = $this->requireRole('admin')) {
            return $guard;
        }

        $rules = [
            'nom'         => 'required|min_length[2]|max_length[100]|is_unique[departements.nom]',
            'description' => 'permit_empty|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/admin/departements')->withInput()->with('errors', $this->validator->getErrors());
        }

        (new DepartementModel())->insert([
            'nom'         => trim((string) $this->request->getPost('nom')),
            'description' => trim((string) $this->request->getPost('description')) ?: null,
        ]);

        return redirect()->to('/admin/departements')->with('success', 'Departement cree avec succes.');
    }

    public function updateDepartement(int $id)
    {
        if ($guard = $this->requireRole('admin')) {
            return $guard;
        }

        $departementModel = new DepartementModel();
        $departement = $departementModel->find($id);

        if ($departement === null) {
            return redirect()->to('/admin/departements')->with('error', 'Departement introuvable.');
        }

        $rules = [
            'nom'         => 'required|min_length[2]|max_length[100]',
            'description' => 'permit_empty|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/admin/departements?edit=' . $id)->withInput()->with('errors', $this->validator->getErrors());
        }

        $nom = trim((string) $this->request->getPost('nom'));
        $duplicate = $departementModel->where('nom', $nom)->where('id !=', $id)->first();

        if ($duplicate !== null) {
            return redirect()->to('/admin/departements?edit=' . $id)->withInput()->with('error', 'Un departement avec ce nom existe deja.');
        }

        $departementModel->update($id, [
            'nom'         => $nom,
            'description' => trim((string) $this->request->getPost('description')) ?: null,
        ]);

        return redirect()->to('/admin/departements')->with('success', 'Departement mis a jour.');
    }

    public function deleteDepartement(int $id)
    {
        if ($guard = $this->requireRole('admin')) {
            return $guard;
        }

        $departementModel = new DepartementModel();
        $departement = $departementModel->find($id);

        if ($departement === null) {
            return redirect()->to('/admin/departements')->with('error', 'Departement introuvable.');
        }

        $hasEmployees = (new EmployeModel())->where('departement_id', $id)->countAllResults() > 0;

        if ($hasEmployees) {
            return redirect()->to('/admin/departements')->with('error', 'Impossible de supprimer un departement rattache a des employes.');
        }

        $departementModel->delete($id);

        return redirect()->to('/admin/departements')->with('success', 'Departement supprime.');
    }

    public function typesConge()
    {
        if ($guard = $this->requireRole('admin')) {
            return $guard;
        }

        $typeCongeModel = new TypeCongeModel();
        $editId = (int) $this->request->getGet('edit');
        $editingType = $editId > 0 ? $typeCongeModel->find($editId) : null;

        return view('admin/types_conge', [
            'typesConge' => $typeCongeModel
                ->select('types_conge.*, COUNT(DISTINCT soldes.id) as soldes_count, COUNT(DISTINCT conges.id) as conges_count')
                ->join('soldes', 'soldes.type_conge_id = types_conge.id', 'left')
                ->join('conges', 'conges.type_conge_id = types_conge.id', 'left')
                ->groupBy('types_conge.id')
                ->orderBy('types_conge.id', 'ASC')
                ->findAll(),
            'editingType' => $editingType,
        ]);
    }

    public function storeTypeConge()
    {
        if ($guard = $this->requireRole('admin')) {
            return $guard;
        }

        $rules = [
            'libelle'       => 'required|min_length[2]|max_length[100]|is_unique[types_conge.libelle]',
            'jours_annuels' => 'required|integer|greater_than_equal_to[0]',
            'deductible'    => 'required|in_list[0,1]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/admin/types-conge')->withInput()->with('errors', $this->validator->getErrors());
        }

        (new TypeCongeModel())->insert([
            'libelle'       => trim((string) $this->request->getPost('libelle')),
            'jours_annuels' => (int) $this->request->getPost('jours_annuels'),
            'deductible'    => (int) $this->request->getPost('deductible'),
        ]);

        return redirect()->to('/admin/types-conge')->with('success', 'Type de conge cree avec succes.');
    }

    public function updateTypeConge(int $id)
    {
        if ($guard = $this->requireRole('admin')) {
            return $guard;
        }

        $typeCongeModel = new TypeCongeModel();
        $typeConge = $typeCongeModel->find($id);

        if ($typeConge === null) {
            return redirect()->to('/admin/types-conge')->with('error', 'Type de conge introuvable.');
        }

        $rules = [
            'libelle'       => 'required|min_length[2]|max_length[100]',
            'jours_annuels' => 'required|integer|greater_than_equal_to[0]',
            'deductible'    => 'required|in_list[0,1]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/admin/types-conge?edit=' . $id)->withInput()->with('errors', $this->validator->getErrors());
        }

        $libelle = trim((string) $this->request->getPost('libelle'));
        $duplicate = $typeCongeModel->where('libelle', $libelle)->where('id !=', $id)->first();

        if ($duplicate !== null) {
            return redirect()->to('/admin/types-conge?edit=' . $id)->withInput()->with('error', 'Un type de conge avec ce libelle existe deja.');
        }

        $typeCongeModel->update($id, [
            'libelle'       => $libelle,
            'jours_annuels' => (int) $this->request->getPost('jours_annuels'),
            'deductible'    => (int) $this->request->getPost('deductible'),
        ]);

        return redirect()->to('/admin/types-conge')->with('success', 'Type de conge mis a jour.');
    }

    public function deleteTypeConge(int $id)
    {
        if ($guard = $this->requireRole('admin')) {
            return $guard;
        }

        $typeCongeModel = new TypeCongeModel();
        $typeConge = $typeCongeModel->find($id);

        if ($typeConge === null) {
            return redirect()->to('/admin/types-conge')->with('error', 'Type de conge introuvable.');
        }

        $hasSoldes = (new SoldeModel())->where('type_conge_id', $id)->countAllResults() > 0;
        $hasConges = (new CongeModel())->where('type_conge_id', $id)->countAllResults() > 0;

        if ($hasSoldes || $hasConges) {
            return redirect()->to('/admin/types-conge')->with('error', 'Impossible de supprimer un type de conge deja utilise.');
        }

        $typeCongeModel->delete($id);

        return redirect()->to('/admin/types-conge')->with('success', 'Type de conge supprime.');
    }
}
