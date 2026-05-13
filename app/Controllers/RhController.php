<?php

namespace App\Controllers;

class RhController extends BaseController
{
    public function dashboard()
    {
        if ($guard = $this->requireRole('rh')) {
            return $guard;
        }

        return view('rh/dashboard');
    }

    public function index()
    {
        if ($guard = $this->requireRole('rh')) {
            return $guard;
        }
        $db = \Config\Database::connect();

        $statut = (string) ($this->request->getGet('statut') ?? 'en_attente');
        $departement = (string) ($this->request->getGet('departement') ?? '');

        $builder = $db->table('conges');
        $builder->select('conges.*, employes.nom, employes.prenom, departements.nom as dept_nom, types_conge.libelle, types_conge.deductible, soldes.jours_attribues, soldes.jours_pris, soldes.annee');
        $builder->join('employes', 'employes.id = conges.employe_id');
        $builder->join('departements', 'departements.id = employes.departement_id', 'left');
        $builder->join('types_conge', 'types_conge.id = conges.type_conge_id');
        $builder->join('soldes', "soldes.employe_id = conges.employe_id AND soldes.type_conge_id = conges.type_conge_id AND soldes.annee = CAST(strftime('%Y', conges.date_debut) AS INTEGER)", 'left');

        if ($statut !== '' && $statut !== 'toutes') {
            $builder->where('conges.statut', $statut);
        }

        if ($departement !== '') {
            $builder->where('employes.departement_id', $departement);
        }

        $data = [
            'demandes'           => $builder->orderBy('conges.created_at', 'DESC')->get()->getResultArray(),
            'count_attente'      => $db->table('conges')->where('statut', 'en_attente')->countAllResults(),
            'departements'       => $db->table('departements')->get()->getResultArray(),
            'current_statut'     => $statut,
            'current_departement'=> $departement,
        ];

        return view('rh/index', $data);
    }

    public function approve(int $id)
    {
        if ($guard = $this->requireRole('rh')) {
            return $guard;
        }

        $user = $this->currentUser();
        $db = \Config\Database::connect();

        $db->transBegin();

        $conge = $db->table('conges')->where('id', $id)->get()->getRowArray();

        if ($conge === null) {
            $db->transRollback();
            return redirect()->to('/rh/demandes')->with('error', 'Demande introuvable.');
        }

        if (($conge['statut'] ?? '') !== 'en_attente') {
            $db->transRollback();
            return redirect()->to('/rh/demandes')->with('error', 'Seules les demandes en attente peuvent etre approuvees.');
        }

        $typeConge = $db->table('types_conge')->where('id', (int) $conge['type_conge_id'])->get()->getRowArray();

        if ($typeConge === null) {
            $db->transRollback();
            return redirect()->to('/rh/demandes')->with('error', 'Type de conge introuvable.');
        }

        $annee = (int) date('Y', strtotime((string) $conge['date_debut']));
        $nbJours = (int) $conge['nb_jours'];

        if ((int) $typeConge['deductible'] === 1) {
            $solde = $db->table('soldes')
                ->where('employe_id', (int) $conge['employe_id'])
                ->where('type_conge_id', (int) $conge['type_conge_id'])
                ->where('annee', $annee)
                ->get()
                ->getRowArray();

            if ($solde === null) {
                $db->transRollback();
                return redirect()->to('/rh/demandes')->with('error', 'Solde introuvable pour cet employe.');
            }

            $joursPris = (int) $solde['jours_pris'];
            $joursAttribues = (int) $solde['jours_attribues'];

            if ($joursPris + $nbJours > $joursAttribues) {
                $db->transRollback();
                return redirect()->to('/rh/demandes')
                    ->with('error', 'Solde insuffisant pour approuver cette demande.')
                    ->with('error_refus_id', $id);
            }

            $db->table('soldes')
                ->where('id', (int) $solde['id'])
                ->update(['jours_pris' => $joursPris + $nbJours]);
        }

        $db->table('conges')
            ->where('id', $id)
            ->update([
                'statut'         => 'approuvee',
                'commentaire_rh' => null,
                'traite_par'     => $user['id'] ?? null,
            ]);

        if ($db->transStatus() === false) {
            $db->transRollback();
            return redirect()->to('/rh/demandes')->with('error', 'Une erreur est survenue lors de l\'approbation.');
        }

        $db->transCommit();

        return redirect()->to('/rh/demandes')->with('success', 'La demande a ete approuvee.');
    }

    public function refuse(int $id)
    {
        if ($guard = $this->requireRole('rh')) {
            return $guard;
        }

        $commentaire = trim((string) $this->request->getPost('commentaire_rh'));

        if ($commentaire === '') {
            return redirect()->to('/rh/demandes')
                ->with('error', 'Le commentaire est obligatoire pour refuser une demande.')
                ->with('error_refus_id', $id);
        }

        $user = $this->currentUser();
        $db = \Config\Database::connect();

        $db->transBegin();

        $conge = $db->table('conges')->where('id', $id)->get()->getRowArray();

        if ($conge === null) {
            $db->transRollback();
            return redirect()->to('/rh/demandes')->with('error', 'Demande introuvable.');
        }

        $statut = (string) ($conge['statut'] ?? '');

        if (! in_array($statut, ['en_attente', 'approuvee'], true)) {
            $db->transRollback();
            return redirect()->to('/rh/demandes')->with('error', 'Cette demande ne peut plus etre refusee.');
        }

        if ($statut === 'approuvee') {
            $typeConge = $db->table('types_conge')->where('id', (int) $conge['type_conge_id'])->get()->getRowArray();

            if ($typeConge === null) {
                $db->transRollback();
                return redirect()->to('/rh/demandes')->with('error', 'Type de conge introuvable.');
            }

            if ((int) $typeConge['deductible'] === 1) {
                $annee = (int) date('Y', strtotime((string) $conge['date_debut']));
                $solde = $db->table('soldes')
                    ->where('employe_id', (int) $conge['employe_id'])
                    ->where('type_conge_id', (int) $conge['type_conge_id'])
                    ->where('annee', $annee)
                    ->get()
                    ->getRowArray();

                if ($solde === null) {
                    $db->transRollback();
                    return redirect()->to('/rh/demandes')->with('error', 'Solde introuvable pour cet employe.');
                }

                $joursPris = (int) $solde['jours_pris'];
                $nbJours = (int) $conge['nb_jours'];

                if ($joursPris - $nbJours < 0) {
                    $db->transRollback();
                    return redirect()->to('/rh/demandes')->with('error', 'Impossible de recalculer le solde de cet employe.');
                }

                $db->table('soldes')
                    ->where('id', (int) $solde['id'])
                    ->update(['jours_pris' => $joursPris - $nbJours]);
            }
        }

        $db->table('conges')
            ->where('id', $id)
            ->update([
                'statut'         => 'refusee',
                'commentaire_rh' => $commentaire,
                'traite_par'     => $user['id'] ?? null,
            ]);

        if ($db->transStatus() === false) {
            $db->transRollback();
            return redirect()->to('/rh/demandes')->with('error', 'Une erreur est survenue lors du refus.');
        }

        $db->transCommit();

        return redirect()->to('/rh/demandes')->with('success', 'La demande a ete refusee.');
    }

    public function soldes()
    {
        if ($guard = $this->requireRole('rh')) {
            return $guard;
        }

        $db = \Config\Database::connect();
        $departement = (string) ($this->request->getGet('departement') ?? '');
        $annee = (string) ($this->request->getGet('annee') ?? date('Y'));

        $builder = $db->table('soldes');
        $builder->select('soldes.*, employes.nom, employes.prenom, departements.nom as dept_nom, types_conge.libelle');
        $builder->join('employes', 'employes.id = soldes.employe_id');
        $builder->join('departements', 'departements.id = employes.departement_id', 'left');
        $builder->join('types_conge', 'types_conge.id = soldes.type_conge_id');

        if ($departement !== '') {
            $builder->where('employes.departement_id', $departement);
        }

        if ($annee !== '') {
            $builder->where('soldes.annee', $annee);
        }

        $data = [
            'soldes'            => $builder->orderBy('employes.nom', 'ASC')->orderBy('types_conge.libelle', 'ASC')->get()->getResultArray(),
            'departements'      => $db->table('departements')->get()->getResultArray(),
            'current_departement'=> $departement,
            'current_annee'     => $annee,
        ];

        return view('rh/soldes', $data);
    }
}
