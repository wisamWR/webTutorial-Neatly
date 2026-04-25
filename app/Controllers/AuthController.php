<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\JwtService;

class AuthController extends BaseController
{
    public function __construct()
    {
        helper('form');
    }

    public function login()
    {
        if ($this->request->getPost()) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $token = JwtService::login($email, $password);// kirim ke API eksternal via cURL, jika sukses dapat refreshToken

            if ($token === false) {
                session()->setFlashdata('failed', 'Email atau password salah');
                return redirect()->back();
            }

            session()->set([
                'email' => $email,
                'refreshToken' => $token,
                'isLoggedIn' => TRUE
            ]);//simpan ke session serverside (bukan cookie)

            return redirect()->to('/');
        }

        return view('v_login');
    }

    public function logout()
    {
        $token = session()->get('refreshToken');
        
        if ($token) {
            JwtService::logout($token);
        }

        session()->destroy();
        return redirect()->to('login');
    }
}
