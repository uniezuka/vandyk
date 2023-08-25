<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class ClientBuildings extends BaseController
{
    protected $clientService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->clientService = service('clientService');
    }

    public function create($client_id = null)
    {
        helper('form');

        $data['title'] = "Add New Building";

        $data['client'] = $this->clientService->findOne($client_id);

        if (!$data['client']) {
            return redirect()->to('/clients')->with('error', "Client not found.");
        }
        



        return view('ClientBuildings/create_view', ['data' => $data]);
    }
}
