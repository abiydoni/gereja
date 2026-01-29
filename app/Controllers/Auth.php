<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function loginProcess()
    {
        // For demo purposes, accepting any login if db is empty, or checking db
        // But since we seeded, we should check against DB.
        // Needs UserModel.
        
        // Mock simple login for functionality demonstration if UserModel not fully set up or validation complex
        // But we should try to do it right.
        
        $session = session();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        
        // Simple manual query for speed since UserModel file not created in this turn yet (wait, I didn't create UserModel yet)
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('username', $username)->get()->getRowArray();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $ses_data = [
                    'id_user'   => $user['id_user'],
                    'username'  => $user['username'],
                    'role'      => $user['role'],
                    'isLoggedIn'=> TRUE
                ];
                $session->set($ses_data);
                return redirect()->to('/dashboard');
            } else {
                $session->setFlashdata('error', 'Password salah.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Username tidak ditemukan.');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
