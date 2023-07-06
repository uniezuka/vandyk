<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class Home extends BaseController
{
    protected $brokerService;
    protected $authenticationService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->brokerService = service('brokerService');
        $this->authenticationService = service('authenticationService');
    }

    public function index()
    {
        $data['title'] = "Index";
        return view('Home/index_view', ['data' => $data]);
    }

    public function profile()
    {
        helper('form');

        $data['title'] = "Profile";
        $id = session()->get('id');
        $data['broker'] = $this->brokerService->findOne($id);

        if (!$this->request->is('post')) {
            return view('Home/profile_view', ['data' => $data]);
        }
        
        $post = $this->request->getPost([
            'name', 'address', 'address2', 'city', 'state', 'zip', 'phone', 'fax', // broker's info
            'greetings'      // broker's login info
        ]);

        if ($this->validateData($post, [
            'name'      => 'required|max_length[250]|min_length[3]',
            'address'   => 'required|max_length[1000]|min_length[10]',
            'address2'  => 'max_length[1000]',
            'city'      => 'required|max_length[250]',
            'zip'       => 'required',
            'phone'     => 'required|max_length[100]',
            'fax'       => 'max_length[100]',
            'greetings' => 'required|max_length[250]|min_length[3]',
        ])) {
            try {
                $post['broker_id'] = $id;
                $post['broker_login_id'] = $data['broker']->broker_login_id;
                $post['isAdmin'] = (is_admin()) ? 'true' : 'false' ;
                $post['iianj'] = ($data['broker']->iianj_member) ? 'true' : 'false' ;
                
                $this->brokerService->update((object) $post);
                return redirect()->back()->withInput()->with('message', 'Profile has been updated.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('Home/profile_view', ['data' => $data]);
        }
    }

    public function change_password()
    {
        helper('form');
        $data['title'] = "Password Management";
        $id = session()->get('id');

        if (!$this->request->is('post')) {
            return view('Home/change_password_view', ['data' => $data]);
        }

        $post = $this->request->getPost([ 'newPassword', 'reEnterPassword' ]);

        if ($this->validateData($post, [
            'newPassword'          => 'required|max_length[250]|min_length[6]',
            'reEnterPassword'      => 'matches[newPassword]',
        ])) {
            try {
                $broker = $this->brokerService->findOne($id);
                $post['broker_login_id'] = $broker->broker_login_id;
                
                $this->authenticationService->updatePassword((object) $post);
                return redirect()->back()->withInput()->with('message', 'Password has been updated.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('Home/change_password_view', ['data' => $data]);
        }
    }
}
