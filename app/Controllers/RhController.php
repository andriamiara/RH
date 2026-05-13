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

        return view('rh/index');
    }
}
