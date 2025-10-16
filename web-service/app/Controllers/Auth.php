<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;

class Auth extends BaseController
{
    public function showLoginPage()
    {
        // Initialize session
        $session = session();

        // If already logged in, send to landing
        if ($session->has('user')) {
            return redirect()->to('/');
        }

        // Pull flashdata errors/old/success if present
        $errors = $session->getFlashdata('errors') ?? [];
        $old = $session->getFlashdata('old') ?? [];

        return view('auth/login', ['errors' => $errors, 'old' => $old]);
    }
}
