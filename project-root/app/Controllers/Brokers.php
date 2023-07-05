<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class Brokers extends BaseController
{
    protected $pager;
    protected $brokerService;
    protected $authenticationService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->brokerService = service('brokerService');
        $this->authenticationService = service('authenticationService');
    }

    public function index()
    {
        $page  = (int) ($this->request->getGet('page') ?? 1);

        $brokers = $this->brokerService->getAll($page);

        $pager_links = $this->pager->makeLinks($page, $brokers->limit, $brokers->total, 'bootstrap_full');

        $data['brokers'] = $brokers->data;
        $data['title'] = "Brokers List";
        $data['pager_links'] = $pager_links;
        return view('Brokers/index_view', ['data' => $data]);
    }

    public function create()
    {
        helper('form');

        $data['title'] = "Create Broker";

        if (!$this->request->is('post')) {
            return view('Brokers/create_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'name', 'address', 'address2', 'city', 'state', 'zip', 'phone', 'fax', // broker's info
            'username', 'greetings', 'password', 'email', 'iianj', 'isAdmin'       // broker's login info
        ]);

        if ($this->validateData($post, [
            'name' => 'required|max_length[250]|min_length[3]',
            'address'  => 'required|max_length[1000]|min_length[10]',
            'address2'  => 'max_length[1000]',
            'city'  => 'required|max_length[250]',
            'zip'  => 'required',
            'phone'  => 'required|max_length[100]',
            'fax'  => 'max_length[100]',
            'username'  => 'required|max_length[250]|min_length[3]',
            'greetings'  => 'required|max_length[250]|min_length[3]',
            'password'  => 'required|max_length[250]|min_length[6]',
            'email'  => 'max_length[250]|valid_email|required',
        ])) {
            try {
                $this->authenticationService->register((object) $post);
                return redirect()->to('/brokers')->with('message', 'Broker was successfully added.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('Brokers/create_view', ['data' => $data]);
        }
    }

    public function update($id = null)
    {
        helper('form');

        $data['title'] = "Update Broker";
        $data['broker'] = $this->brokerService->findOne($id);

        if (!$this->request->is('post')) {
            return view('Brokers/update_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'name', 'address', 'address2', 'city', 'state', 'zip', 'phone', 'fax', // broker's info
            'username', 'greetings', 'email', 'iianj', 'isAdmin'       // broker's login info
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

                $this->brokerService->update((object) $post);
                return redirect()->to('/brokers')->with('message', 'Broker was successfully updated.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('Brokers/create_view', ['data' => $data]);
        }
        
    }
}
