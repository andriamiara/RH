<?php

namespace App\Controllers;

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

        return view('admin/employes');
    }
}
