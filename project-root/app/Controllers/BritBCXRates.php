<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class BritBCXRates extends BaseController
{
    protected $pager;
    protected $britFloodBCXRateService;
    protected $floodFoundationService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->britFloodBCXRateService = service('britFloodBCXRateService');
        $this->floodFoundationService = service('floodFoundationService');
    }

    public function index()
    {
        helper('form');

        $rates = $this->britFloodBCXRateService->getAll();

        $data['rates'] = $rates;
        $data['title'] = "Brit BCX Rates";
        return view('BritBCXRates/index_view', ['data' => $data]);
    }

    public function create()
    {
        helper('form');

        $data['title'] = "Create New Brit BCX Rates";

        if (!$this->request->is('post')) {
            return view('BritBCXRates/create_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'description',
            'bcxrate',
            'dwl4',
            'cont4',
            'both4',
            'dwl3',
            'cont3',
            'both3',
            'dwl2',
            'cont2',
            'both2',
            'dwl1',
            'cont1',
            'both1',
            'dwl0',
            'cont0',
            'both0',
            'floodFoundations',
            'numberOfFloors',
            'isMoreThan'
        ]);

        if ($this->validateData($post, [
            'description' => 'required|max_length[250]',
            'bcxrate' => 'required|numeric',
            'dwl4' => 'required|numeric',
            'cont4' => 'required|numeric',
            'both4' => 'required|numeric',
            'dwl3' => 'required|numeric',
            'cont3' => 'required|numeric',
            'both3' => 'required|numeric',
            'dwl2' => 'required|numeric',
            'cont2' => 'required|numeric',
            'both2' => 'required|numeric',
            'dwl1' => 'required|numeric',
            'cont1' => 'required|numeric',
            'both1' => 'required|numeric',
            'dwl0' => 'required|numeric',
            'cont0' => 'required|numeric',
            'both0' => 'required|numeric',
        ])) {
            try {
                $floodBCXRate = $this->britFloodBCXRateService->create((object) $post);

                $this->britFloodBCXRateService->deleteFloodFoundations($floodBCXRate->brit_flood_bcx_rate_id);

                if (!empty($post['floodFoundations'])) {
                    $floodFoundations = $post['floodFoundations'];
                    $numberOfFloors = $post['numberOfFloors'];
                    $isMoreThan = $post['isMoreThan'];

                    foreach ($floodFoundations as $index => $floodFoundation) {

                        $foundation = $this->floodFoundationService->findOne($floodFoundation);

                        if ($foundation) {
                            $message = new \stdClass();
                            $message->brit_flood_bcx_rate_id = $floodBCXRate->brit_flood_bcx_rate_id;
                            $message->flood_foundation_id = $foundation->flood_foundation_id;
                            $message->numOfFloors = $numberOfFloors[$index];
                            $message->isMoreThanEqual = $isMoreThan[$index];

                            $this->britFloodBCXRateService->upsertFloodFoundation($message);
                        }
                    }
                }

                return redirect()->to('/brit_bcx_rates')->with('message', 'Brit BCX Rate was successfully added.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('BritBCXRates/create_view', ['data' => $data]);
        }
    }

    public function update($id = null)
    {
        helper('form');

        $data['title'] = "Brit Update BCX Rate";
        $data['rate'] = $this->britFloodBCXRateService->findOne($id);

        if (!$data['rate']) {
            return redirect()->to('/brit_bcx_rates')->with('error', "Brit BCX Rate not found.");
        }

        $floodFoundations =  $this->britFloodBCXRateService->getFloodFoundations($id);
        $data['floodFoundations'] = $floodFoundations;

        if (!$this->request->is('post')) {
            return view('BritBCXRates/update_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'description',
            'bcxrate',
            'dwl4',
            'cont4',
            'both4',
            'dwl3',
            'cont3',
            'both3',
            'dwl2',
            'cont2',
            'both2',
            'dwl1',
            'cont1',
            'both1',
            'dwl0',
            'cont0',
            'both0',
            'floodFoundations',
            'numberOfFloors',
            'isMoreThan'
        ]);

        if ($this->validateData($post, [
            'description' => 'required|max_length[250]',
            'bcxrate' => 'required|numeric',
            'dwl4' => 'required|numeric',
            'cont4' => 'required|numeric',
            'both4' => 'required|numeric',
            'dwl3' => 'required|numeric',
            'cont3' => 'required|numeric',
            'both3' => 'required|numeric',
            'dwl2' => 'required|numeric',
            'cont2' => 'required|numeric',
            'both2' => 'required|numeric',
            'dwl1' => 'required|numeric',
            'cont1' => 'required|numeric',
            'both1' => 'required|numeric',
            'dwl0' => 'required|numeric',
            'cont0' => 'required|numeric',
            'both0' => 'required|numeric',
        ])) {
            try {
                $post['brit_flood_bcx_rate_id'] = $id;

                $floodBCXRate = $this->britFloodBCXRateService->update((object) $post);

                $this->britFloodBCXRateService->deleteFloodFoundations($floodBCXRate->brit_flood_bcx_rate_id);

                if (!empty($post['floodFoundations'])) {
                    $floodFoundations = $post['floodFoundations'];
                    $numberOfFloors = $post['numberOfFloors'];
                    $isMoreThan = $post['isMoreThan'];

                    foreach ($floodFoundations as $index => $floodFoundation) {

                        $foundation = $this->floodFoundationService->findOne($floodFoundation);

                        if ($foundation) {
                            $message = new \stdClass();
                            $message->brit_flood_bcx_rate_id = $floodBCXRate->brit_flood_bcx_rate_id;
                            $message->flood_foundation_id = $foundation->flood_foundation_id;
                            $message->numOfFloors = $numberOfFloors[$index];
                            $message->isMoreThanEqual = $isMoreThan[$index];

                            $this->britFloodBCXRateService->upsertFloodFoundation($message);
                        }
                    }
                }

                return redirect()->back()->withInput()->with('message', 'Brit BCX Rate was successfully updated.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('BritBCXRates/update_view', ['data' => $data]);
        }
    }
}
