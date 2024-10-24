<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class BCXRates extends BaseController
{
    protected $pager;
    protected $floodBCXRateService;
    protected $floodFoundationService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->floodBCXRateService = service('floodBCXRateService');
        $this->floodFoundationService = service('floodFoundationService');
    }

    public function index()
    {
        helper('form');

        $rates = $this->floodBCXRateService->getAll();

        $data['rates'] = $rates;
        $data['title'] = "BCX Rates";
        return view('BCXRates/index_view', ['data' => $data]);
    }

    public function create()
    {
        helper('form');

        $data['title'] = "Create New BCX Rates";

        if (!$this->request->is('post')) {
            return view('BCXRates/create_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'description',
            'rate',
            'floodFoundations',
            'numberOfFloors',
            'isMoreThan'
        ]);

        if ($this->validateData($post, [
            'description' => 'required|max_length[250]',
            'rate' => 'required|numeric',
        ])) {
            try {
                $floodBCXRate = $this->floodBCXRateService->create((object) $post);

                $this->floodBCXRateService->deleteFloodFoundations($floodBCXRate->flood_bcx_rate_id);

                if (!empty($post['floodFoundations'])) {
                    $floodFoundations = $post['floodFoundations'];
                    $numberOfFloors = $post['numberOfFloors'];
                    $isMoreThan = $post['isMoreThan'];

                    foreach ($floodFoundations as $index => $floodFoundation) {

                        $foundation = $this->floodFoundationService->findOne($floodFoundation);

                        if ($foundation) {
                            $message = new \stdClass();
                            $message->flood_bcx_rate_id = $floodBCXRate->flood_bcx_rate_id;
                            $message->flood_foundation_id = $foundation->flood_foundation_id;
                            $message->numOfFloors = $numberOfFloors[$index];
                            $message->isMoreThanEqual = $isMoreThan[$index];

                            $this->floodBCXRateService->upsertFloodFoundation($message);
                        }
                    }
                }

                return redirect()->to('/bcx_rates')->with('message', 'BCX Rate was successfully added.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('BCXRates/create_view', ['data' => $data]);
        }
    }

    public function update($id = null)
    {
        helper('form');

        $data['title'] = "Update BCX Rate";
        $data['rate'] = $this->floodBCXRateService->findOne($id);

        if (!$data['rate']) {
            return redirect()->to('/bcx_rates')->with('error', "BCX Rate not found.");
        }

        $floodFoundations =  $this->floodBCXRateService->getFloodFoundations($id);
        $data['floodFoundations'] = $floodFoundations;

        if (!$this->request->is('post')) {
            return view('BCXRates/update_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'description',
            'rate',
            'floodFoundations',
            'numberOfFloors',
            'isMoreThan'
        ]);

        if ($this->validateData($post, [
            'description' => 'required|max_length[250]',
            'rate' => 'required|numeric',
        ])) {
            try {
                $post['flood_bcx_rate_id'] = $id;

                $floodBCXRate = $this->floodBCXRateService->update((object) $post);

                $this->floodBCXRateService->deleteFloodFoundations($floodBCXRate->flood_bcx_rate_id);

                if (!empty($post['floodFoundations'])) {
                    $floodFoundations = $post['floodFoundations'];
                    $numberOfFloors = $post['numberOfFloors'];
                    $isMoreThan = $post['isMoreThan'];

                    foreach ($floodFoundations as $index => $floodFoundation) {

                        $foundation = $this->floodFoundationService->findOne($floodFoundation);

                        if ($foundation) {
                            $message = new \stdClass();
                            $message->flood_bcx_rate_id = $floodBCXRate->flood_bcx_rate_id;
                            $message->flood_foundation_id = $foundation->flood_foundation_id;
                            $message->numOfFloors = $numberOfFloors[$index];
                            $message->isMoreThanEqual = $isMoreThan[$index];

                            $this->floodBCXRateService->upsertFloodFoundation($message);
                        }
                    }
                }

                return redirect()->back()->withInput()->with('message', 'BCX Rate was successfully updated.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('BCXRates/update_view', ['data' => $data]);
        }
    }
}
