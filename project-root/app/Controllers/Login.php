<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class Login extends BaseController
{
    protected $authenticationService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->authenticationService = service('authenticationService');
    }

    public function index()
    {
        $data['title'] = "Login";
        return view('Login/index_view', ['data' => $data]);
    }

    public function authenticate()
    {
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        try {
            if ($this->authenticationService->authenticate($username, $password))
                return redirect()->to('/');
            else
                return redirect()->back()->withInput()->with('error', 'Invalid username or password.');
        } catch(Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function logout() {
        session_destroy();
        return redirect()->to('/login');
    }
}