<?php

namespace App\Controllers;

use App\Models\DepartementModel;

class EmployeController extends BaseController
{
    public function dashboard()
    {
        if ($guard = $this->requireRole('employe')) {
            return $guard;
        }

        return view('employe/dashboard');
    }

    public function create()
    {
        if ($guard = $this->requireRole('employe')) {
            return $guard;
        }

        return view('employe/create');
    }

    public function index()
    {
        if ($guard = $this->requireRole('employe')) {
            return $guard;
        }

        return view('employe/index');
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
}
