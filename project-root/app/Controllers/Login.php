<?php

namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController
{
    public function index()
    {
        $data['title'] = "Login";
        return view('Login/index_view', ['data' => $data]);
    }

    public function authenticate()
    {
        $session = session();
        
        $userModel = new UserModel();
 
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $userModel->where('username', $username)->first();
 
        if(is_null($user)) {
            return redirect()->back()->withInput()->with('error', 'Invalid username or password.');
        }
 
        $pwd_verify = password_verify($password, $user['password']);
 
        if(!$pwd_verify) {
            return redirect()->back()->withInput()->with('error', 'Invalid username or password.');
        }
 
        $session_data = [
            'id' => $user['broker_id'],
            'email' => $user['email'],
            'username' => $user['username'],
            'isLoggedIn' => TRUE
        ];
 
        $session->set($session_data);
        return redirect()->to('/');
    }

    public function logout() {
        session_destroy();
        return redirect()->to('/login');
    }
}