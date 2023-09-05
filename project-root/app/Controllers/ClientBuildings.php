<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class ClientBuildings extends BaseController
{
    protected $clientService;
    protected $foundationService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->clientService = service('clientService');
        $this->foundationService = service('foundationService');
    }

    public function create($client_id = null)
    {
        helper('form');

        $data['title'] = "Add New Building";

        $data['client'] = $this->clientService->findOne($client_id);
        $data['foundations'] = $this->foundationService->getAll();

        if (!$data['client']) {
            return redirect()->to('/clients')->with('error', "Client not found.");
        }

        if (!$this->request->is('post')) {
            return view('ClientBuildings/create_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'streetNumber', 'city', 'state', 'zipCode', 'county', 'latitude', 'longitude', 'description', 'occupancy', 'floors',
            'purpose', 'construction', 'floorArea', 'yearBuilt', 'priorLoss', 'overWater', 'foundationType', 'replacementCost',
            'personalValue', 'incomeExpenseTotal', 'hasBasement', 'basementFinished', 'elevationHeight', 'hasBelowFloorEnclosure',
            'enclosureType', 'completionStatus', 'hasElevator', 'equipmentValue', 'otherPersonalValue', 'street', 'placeId'
        ]);

        if ($this->validateData($post, [
            'streetNumber' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipCode' => 'required',
            'county' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'description' => 'required',
            'purpose' => 'required',
            'construction' => 'required',
            'floorArea' => 'required',
            'yearBuilt' => 'required',
            'foundationType' => 'required',
            'priorLoss' => 'required',
            'replacementCost' => 'required',
            'personalValue' => 'required',
            'incomeExpenseTotal' => 'required',
            'street' => 'required',
        ])) {
            try {
                $this->clientService->addBuilding($client_id, (object) $post);
                return redirect()->to('/client/details/' . $client_id)->with('message', 'A new Building was successfully added.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('ClientBuildings/create_view', ['data' => $data]);
        }   
    }

    public function delete($client_id = null, $building_id) {
        $data['client'] = $this->clientService->findOne($client_id);

        if (!$data['client']) {
            return redirect()->to('/clients')->with('error', "Client not found.");
        }

        try {
            $this->clientService->removeBuilding($client_id, $building_id);
            return redirect()->to('/client/details/' . $client_id)->with('message', 'Building may or have been deleted.');
        } catch(Exception $e) {
            return redirect()->to('/client/details/' . $client_id)->with('error', $e->getMessage());
        }
    }
}
