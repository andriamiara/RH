<?php

namespace App\Controllers;

use App\Models\CongeModel;
use App\Models\DepartementModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;

class EmployeController extends BaseController
{
    public function dashboard()
    {
        if ($guard = $this->requireRole('employe')) {
            return $guard;
        }

        return view('employe/dashboard', $this->buildDashboardData());
    }

    public function create()
    {
        if ($guard = $this->requireRole('employe')) {
            return $guard;
        }

        return view('employe/create', $this->buildEmployeeFormData());
    }

    public function storeConge()
    {
        if ($guard = $this->requireRole('employe')) {
            return $guard;
        }

        $rules = [
            'type_conge_id' => 'required|integer',
            'date_debut'    => 'required|valid_date',
            'date_fin'      => 'required|valid_date',
            'motif'         => 'permit_empty|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = $this->currentUser();

        if ($user === null) {
            return redirect()->to('/login');
        }

        $typeCongeId = (int) $this->request->getPost('type_conge_id');
        $dateDebut = (string) $this->request->getPost('date_debut');
        $dateFin = (string) $this->request->getPost('date_fin');

        $start = strtotime($dateDebut);
        $end = strtotime($dateFin);

        if ($start === false || $end === false || $end < $start) {
            return redirect()->back()->withInput()->with('error', 'La periode selectionnee est invalide.');
        }

        $nbJours = (int) floor(($end - $start) / 86400) + 1;

        $typeCongeModel = new TypeCongeModel();
        $soldeModel = new SoldeModel();
        $congeModel = new CongeModel();
        $typeConge = $typeCongeModel->find($typeCongeId);

        if ($typeConge === null) {
            return redirect()->back()->withInput()->with('error', 'Type de conge introuvable.');
        }

        $existence = $congeModel
            ->where('employe_id', (int) $user['id'])
            ->whereIn('statut', ['en_attente', 'approuvee'])
            ->groupStart()
                ->where('date_debut <=', $dateFin . ' 23:59:59')
                ->where('date_fin >=', $dateDebut . ' 00:00:00')
            ->groupEnd()
            ->first();

        if ($existence !== null) {
            return redirect()->back()->withInput()->with('error', 'Une demande existe deja sur cette periode.');
        }

        $annee = (int) date('Y', $start);
        $solde = $soldeModel
            ->where('employe_id', (int) $user['id'])
            ->where('type_conge_id', $typeCongeId)
            ->where('annee', $annee)
            ->first();

        $soldeRestant = $solde ? ((int) $solde['jours_attribues'] - (int) $solde['jours_pris']) : 0;

        if ((int) $typeConge['deductible'] === 1 && $soldeRestant < $nbJours) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant pour cette demande.');
        }

        $congeModel->insert([
            'employe_id'    => (int) $user['id'],
            'type_conge_id' => $typeCongeId,
            'date_debut'    => $dateDebut . ' 00:00:00',
            'date_fin'      => $dateFin . ' 00:00:00',
            'nb_jours'      => $nbJours,
            'motif'         => trim((string) $this->request->getPost('motif')) ?: null,
            'statut'        => 'en_attente',
        ]);

        return redirect()->to('/employe/demandes')->with('success', 'Votre demande de conge a bien ete soumise.');
    }

    public function index()
    {
        if ($guard = $this->requireRole('employe')) {
            return $guard;
        }

        $user = $this->currentUser();
        $requests = (new CongeModel())->getEmployeeRequests((int) $user['id']);

        return view('employe/index', [
            'requests' => $requests,
        ]);
    }

    public function cancel(int $id)
    {
        if ($guard = $this->requireRole('employe')) {
            return $guard;
        }

        $user = $this->currentUser();
        $congeModel = new CongeModel();
        $request = $congeModel->where('id', $id)->where('employe_id', (int) $user['id'])->first();

        if ($request === null) {
            return redirect()->to('/employe/demandes')->with('error', 'Demande introuvable.');
        }

        if (($request['statut'] ?? '') !== 'en_attente') {
            return redirect()->to('/employe/demandes')->with('error', 'Seules les demandes en attente peuvent etre annulees.');
        }

        $congeModel->update($id, ['statut' => 'annulee']);

        return redirect()->to('/employe/demandes')->with('success', 'La demande a ete annulee.');
    }

    public function profile()
    {
        if ($guard = $this->requireRole('employe')) {
            return $guard;
        }

        $user = $this->currentUser();
        $departement = null;

        if ($user !== null && isset($user['departement_id'])) {
            $departement = (new DepartementModel())->find($user['departement_id']);
        }

        return view('employe/profile', [
            'user'        => $user,
            'departement' => $departement,
        ]);
    }

    private function buildEmployeeFormData(): array
    {
        $user = $this->currentUser();
        $typeCongeModel = new TypeCongeModel();
        $soldeModel = new SoldeModel();
        $types = $typeCongeModel->findAll();
        $referenceYear = (int) date('Y');
        $latestSolde = $soldeModel->selectMax('annee')->where('employe_id', (int) $user['id'])->first();

        if (! empty($latestSolde['annee'])) {
            $referenceYear = (int) $latestSolde['annee'];
        }

        $soldes = [];

        foreach ($types as $type) {
            $solde = $soldeModel
                ->where('employe_id', (int) $user['id'])
                ->where('type_conge_id', (int) $type['id'])
                ->where('annee', $referenceYear)
                ->first();

            $soldes[] = [
                'id'              => (int) $type['id'],
                'libelle'         => $type['libelle'],
                'jours_annuels'   => (int) $type['jours_annuels'],
                'jours_pris'      => (int) ($solde['jours_pris'] ?? 0),
                'jours_restants'  => $solde ? ((int) $solde['jours_attribues'] - (int) $solde['jours_pris']) : 0,
                'deductible'      => (int) $type['deductible'],
            ];
        }

        return [
            'types'         => $types,
            'soldes'        => $soldes,
            'referenceYear' => $referenceYear,
        ];
    }

    private function buildDashboardData(): array
    {
        $user = $this->currentUser();
        $congeModel = new CongeModel();
        $requests = $congeModel->getEmployeeRequests((int) $user['id']);
        $formData = $this->buildEmployeeFormData();
        $stats = [
            'en_attente' => 0,
            'approuvee'  => 0,
            'refusee'    => 0,
            'annulee'    => 0,
        ];

        foreach ($requests as $request) {
            $statut = $request['statut'] ?? 'en_attente';

            if (array_key_exists($statut, $stats)) {
                $stats[$statut]++;
            }
        }

        $totalRestant = 0;

        foreach ($formData['soldes'] as $solde) {
            if ((int) $solde['deductible'] === 1) {
                $totalRestant += (int) $solde['jours_restants'];
            }
        }

        $monthStart = date('Y-m-01 00:00:00');
        $monthEnd = date('Y-m-t 23:59:59');
        $monthAbsenceDays = 0;

        foreach ($requests as $request) {
            if (($request['statut'] ?? '') !== 'approuvee') {
                continue;
            }

            $start = max(strtotime((string) $request['date_debut']), strtotime($monthStart));
            $end = min(strtotime((string) $request['date_fin']), strtotime($monthEnd));

            if ($start !== false && $end !== false && $end >= $start) {
                $monthAbsenceDays += (int) floor(($end - $start) / 86400) + 1;
            }
        }

        return [
            'user'             => $user,
            'requests'         => array_slice($requests, 0, 5),
            'requestsCount'    => count($requests),
            'stats'            => $stats,
            'soldes'           => $formData['soldes'],
            'referenceYear'    => $formData['referenceYear'],
            'totalRestant'     => $totalRestant,
            'monthAbsenceDays' => $monthAbsenceDays,
            'currentMonthLabel'=> date('m/Y'),
        ];
    }
}
