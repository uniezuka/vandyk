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

    public function update($client_id = null, $building_id)
    {
        helper('form');

        $data['title'] = "Update Client Building";

        $data['client'] = $this->clientService->findOne($client_id);
        $data['foundations'] = $this->foundationService->getAll();

        if (!$data['client']) {
            return redirect()->to('/clients')->with('error', "Client not found.");
        }

        $data['building'] = $this->clientService->getBuilding($building_id);

        if (!$data['building']) {
            return redirect()->to('/client/details/' . $client_id)->with('error', "Building not found.");
        }

        if (!$this->request->is('post')) {
            return view('ClientBuildings/update_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'address', 'city', 'state', 'zipCode', 'county', 'latitude', 'longitude', 'description', 'occupancy', 'floors',
            'purpose', 'construction', 'floorArea', 'yearBuilt', 'priorLoss', 'overWater', 'foundationType', 'replacementCost',
            'personalValue', 'incomeExpenseTotal', 'hasBasement', 'basementFinished', 'elevationHeight', 'hasBelowFloorEnclosure',
            'enclosureType', 'completionStatus', 'hasElevator', 'equipmentValue', 'otherPersonalValue', 'street', 'placeId'
        ]);

        if ($this->validateData($post, [
            'address' => 'required',
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
        ])) {
            try {
                $post['client_building_id'] = $building_id;

                $this->clientService->updateBuilding((object) $post);
                return redirect()->to('/client/details/' . $client_id)->with('message', 'Building was successfully updated.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('ClientBuildings/update_view', ['data' => $data]);
        }  
    }

    public function mortgage_update($client_id = null, $building_id) {
        helper('form');

        $client = $this->clientService->findOne($client_id);

        if (!$client) {
            return redirect()->to('/clients')->with('error', "Client not found.");
        }

        $building = $this->clientService->getBuilding($building_id);

        if (!$building) {
            return redirect()->to('/client/details/' . $client_id)->with('error', "Building not found.");
        }

        if (!$this->request->is('post')) {
            return redirect()->to('/client/details/' . $client_id);
        }

        $post = $this->request->getPost([
            'mortgage1Name', 'mortgage1Name2', 'mortgage1Address', 'mortgage1City', 'mortgage1State', 'mortgage1Zip', 'mortgage1Phone', 'mortgage1Loan',
            'mortgage2Name', 'mortgage2Name2', 'mortgage2Address', 'mortgage2City', 'mortgage2State', 'mortgage2Zip', 'mortgage2Phone', 'mortgage2Loan',
        ]);

        try {
            $post['client_building_id'] = $building_id;

            $this->clientService->updateMortgage((object) $post);
            return redirect()->to('/client/' . $client_id . '/building/update/' . $building_id)->with('message', 'Mortgage was successfully updated.');
        } catch(Exception $e) {
            return redirect()->to('/client/' . $client_id . '/building/update/' . $building_id)->with('error', $e->getMessage());
        }
    }
}
