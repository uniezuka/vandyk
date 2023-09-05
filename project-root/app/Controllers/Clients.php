<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class Clients extends BaseController
{
    protected $pager;
    protected $clientService;
    protected $brokerService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->clientService = service('clientService');
        $this->brokerService = service('brokerService');
    }

    public function index()
    {
        helper('form');

        $page  = (int) ($this->request->getGet('page') ?? 1);
        $search = $this->request->getGet('search') ?? "";
        $search = trim($search);

        $nonCommercialOnly = $this->request->getGet('nonCommercialOnly') ?? "";
        $nonCommercialOnly = trim($nonCommercialOnly);
        $nonCommercialOnly = ($nonCommercialOnly === 'true');

        $commercialOnly = $this->request->getGet('commercialOnly') ?? "";
        $commercialOnly = trim($commercialOnly);
        $commercialOnly = ($commercialOnly === 'true');

        if ($search)
            $clients = $this->clientService->search($page, $search, $commercialOnly, $nonCommercialOnly);
        else 
            $clients = $this->clientService->getPaged($page, $commercialOnly, $nonCommercialOnly);

        $pager_links = $this->pager->makeLinks($page, $clients->limit, $clients->total, 'bootstrap_full');

        $data['clients'] = $clients->data;
        $data['title'] = "Clients List";
        $data['pager_links'] = $pager_links;
        $data['search'] = $this->request->getGet('search') ?? "";
        $data['non_commercial_only'] = $nonCommercialOnly;
        $data['commercial_only'] = $commercialOnly;

        return view('Clients/index_view', ['data' => $data]);
    }

    public function create()
    {
        helper('form');

        $data['title'] = "Create New Client";

        if (!$this->request->is('post')) {
            return view('Clients/create_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'entityType', 'firstName', 'lastName', 'clientName2', 'companyName', 'companyName2', 'address', 'city', 
            'zip', 'cellPhone', 'homePhone', 'email', 'clientCode', 'brokerId', 'businessEntityTypeId', 'state', 
            'businessAs', 'isCommercial'
        ]);


        if ($this->validateData($post, [
            'firstName' => 'requiredIf[entityType,1,firstName]|max_length[250]',
            'lastName' => 'requiredIf[entityType,1,lastName]|max_length[250]',
            'clientName2' => 'max_length[250]',
            'companyName' => 'requiredIf[entityType,2,companyName]|max_length[250]',
            'companyName2' => 'max_length[250]',
            'address'  => 'required|max_length[1000]',
            'address2'  => 'max_length[1000]',
            'city'  => 'required|max_length[250]',
            'zip'  => 'required',
            'cellPhone'  => 'max_length[100]',
            'homePhone'  => 'max_length[100]',
            'email'  => 'max_length[250]|valid_email',
            'clientCode'  => 'max_length[100]',
            'brokerId'  => 'required',
        ])) {
            try {
                $this->clientService->create((object) $post);
                return redirect()->to('/clients')->with('message', 'Client was successfully added.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('Clients/create_view', ['data' => $data]);
        }
    }

    public function details($id = null)
    {
        helper('form');
        
        $data['title'] = "Client Details";
        $data['client'] = $this->clientService->findOne($id);

        if (!$data['client']) {
            return redirect()->to('/clients')->with('error', "Client not found.");
        }

        $data['broker'] = $this->brokerService->findOne($data['client']->broker_id);

        $data['buildings'] = [];

        if ($data['client']->is_commercial)
            $data['buildings'] = $this->clientService->getBuildings($data['client']->client_id);

        return view('Clients/details_view', ['data' => $data]);
    }

    public function update($id = null)
    {
        helper('form');

        $data['title'] = "Update Client";
        $data['client'] = $this->clientService->findOne($id);

        if (!$data['client']) {
            return redirect()->to('/clients')->with('error', "Client not found.");
        }

        if (!$this->request->is('post')) {
            return view('Clients/update_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'entityType', 'firstName', 'lastName', 'clientName2', 'companyName', 'companyName2', 'address', 'city', 
            'zip', 'cellPhone', 'homePhone', 'email', 'clientCode', 'brokerId', 'businessEntityTypeId', 'state',
            'businessAs', 'isCommercial'
        ]);

        if ($this->validateData($post, [
            'firstName' => 'requiredIf[entityType,1,firstName]|max_length[250]',
            'lastName' => 'requiredIf[entityType,1,lastName]|max_length[250]',
            'clientName2' => 'max_length[250]',
            'companyName' => 'requiredIf[entityType,2,companyName]|max_length[250]',
            'companyName2' => 'max_length[250]',
            'address'  => 'required|max_length[1000]',
            'address2'  => 'max_length[1000]',
            'city'  => 'required|max_length[250]',
            'zip'  => 'required',
            'cellPhone'  => 'max_length[100]',
            'homePhone'  => 'max_length[100]',
            'email'  => 'max_length[250]|valid_email',
            'clientCode'  => 'max_length[100]',
            'brokerId'  => 'required',
        ])) {
            try {
                $post['client_id'] = $id;

                $this->clientService->update((object) $post);
                return redirect()->back()->withInput()->with('message', 'Client was successfully updated.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('Clients/update_view', ['data' => $data]);
        }          
    }
}
