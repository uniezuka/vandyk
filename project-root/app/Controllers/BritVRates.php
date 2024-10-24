<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class BritVRates extends BaseController
{
    protected $pager;
    protected $britFloodVRateService;
    protected $floodFoundationService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->britFloodVRateService = service('britFloodVRateService');
        $this->floodFoundationService = service('floodFoundationService');
    }

    public function index()
    {
        helper('form');

        $rates = $this->britFloodVRateService->getAll();

        $data['rates'] = $rates;
        $data['title'] = "Brit V Rates";
        return view('BritVRates/index_view', ['data' => $data]);
    }

    public function create()
    {
        helper('form');

        $data['title'] = "Create New Brit V Rates";

        if (!$this->request->is('post')) {
            return view('BritVRates/create_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'description',
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
        ]);

        if ($this->validateData($post, [
            'description' => 'required|max_length[250]',
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
                $floodVRate = $this->britFloodVRateService->create((object) $post);

                $this->britFloodVRateService->deleteFloodFoundations($floodVRate->brit_flood_v_rate_id);

                if (!empty($post['floodFoundations'])) {
                    $floodFoundations = $post['floodFoundations'];

                    foreach ($floodFoundations as $floodFoundation) {

                        $foundation = $this->floodFoundationService->findOne($floodFoundation);

                        if ($foundation) {
                            $this->britFloodVRateService->upsertFloodFoundation($floodVRate->brit_flood_v_rate_id, $foundation->flood_foundation_id);
                        }
                    }
                }

                return redirect()->to('/brit_v_rates')->with('message', 'Brit V Rate was successfully added.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('BritVRates/create_view', ['data' => $data]);
        }
    }

    public function update($id = null)
    {
        helper('form');

        $data['title'] = "Update Brit V Rate";
        $data['rate'] = $this->britFloodVRateService->findOne($id);

        if (!$data['rate']) {
            return redirect()->to('/brit_v_rates')->with('error', "Brit V Rate not found.");
        }

        $floodFoundations =  $this->britFloodVRateService->getFloodFoundations($id);
        $data['floodFoundations'] = $floodFoundations;

        if (!$this->request->is('post')) {
            return view('BritVRates/update_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'description',
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
            'floodFoundations'
        ]);

        if ($this->validateData($post, [
            'description' => 'required|max_length[250]',
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
                $post['brit_flood_v_rate_id'] = $id;

                $floodVRate = $this->britFloodVRateService->update((object) $post);

                $this->britFloodVRateService->deleteFloodFoundations($floodVRate->brit_flood_v_rate_id);

                if (!empty($post['floodFoundations'])) {
                    $floodFoundations = $post['floodFoundations'];

                    foreach ($floodFoundations as $floodFoundation) {

                        $foundation = $this->floodFoundationService->findOne($floodFoundation);

                        if ($foundation) {
                            $this->britFloodVRateService->upsertFloodFoundation($floodVRate->brit_flood_v_rate_id, $foundation->flood_foundation_id);
                        }
                    }
                }

                return redirect()->back()->withInput()->with('message', 'Brit V Rate was successfully updated.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('BritVRates/update_view', ['data' => $data]);
        }
    }
}
