<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class VRates extends BaseController
{
    protected $pager;
    protected $floodVRateService;
    protected $floodFoundationService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->floodVRateService = service('floodVRateService');
        $this->floodFoundationService = service('floodFoundationService');
    }

    public function index()
    {
        helper('form');

        $rates = $this->floodVRateService->getAll();

        $data['rates'] = $rates;
        $data['title'] = "V Rates";
        return view('VRates/index_view', ['data' => $data]);
    }

    public function create()
    {
        helper('form');

        $data['title'] = "Create New V Rates";

        if (!$this->request->is('post')) {
            return view('VRates/create_view', ['data' => $data]);
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
            'dwl-1',
            'cont-1',
            'both-1',
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
            'dwl-1' => 'required|numeric',
            'cont-1' => 'required|numeric',
            'both-1' => 'required|numeric',
        ])) {
            try {
                $post['dwl_1'] = $post['dwl-1'];
                $post['cont_1'] = $post['cont-1'];
                $post['both_1'] = $post['both-1'];

                $floodVRate = $this->floodVRateService->create((object) $post);

                $this->floodVRateService->deleteFloodFoundations($floodVRate->flood_v_rate_id);

                if (!empty($post['floodFoundations'])) {
                    $floodFoundations = $post['floodFoundations'];

                    foreach ($floodFoundations as $floodFoundation) {

                        $foundation = $this->floodFoundationService->findOne($floodFoundation);

                        if ($foundation) {
                            $this->floodVRateService->upsertFloodFoundation($floodVRate->flood_v_rate_id, $foundation->flood_foundation_id);
                        }
                    }
                }

                return redirect()->to('/v_rates')->with('message', 'V Rate was successfully added.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('VRates/create_view', ['data' => $data]);
        }
    }

    public function update($id = null)
    {
        helper('form');

        $data['title'] = "Update V Rate";
        $data['rate'] = $this->floodVRateService->findOne($id);

        if (!$data['rate']) {
            return redirect()->to('/v_rates')->with('error', "V Rate not found.");
        }

        $floodFoundations =  $this->floodVRateService->getFloodFoundations($id);
        $data['floodFoundations'] = $floodFoundations;

        if (!$this->request->is('post')) {
            return view('VRates/update_view', ['data' => $data]);
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
            'dwl-1',
            'cont-1',
            'both-1',
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
            'dwl-1' => 'required|numeric',
            'cont-1' => 'required|numeric',
            'both-1' => 'required|numeric',
        ])) {
            try {
                $post['flood_v_rate_id'] = $id;
                $post['dwl_1'] = $post['dwl-1'];
                $post['cont_1'] = $post['cont-1'];
                $post['both_1'] = $post['both-1'];

                $floodVRate = $this->floodVRateService->update((object) $post);

                $this->floodVRateService->deleteFloodFoundations($floodVRate->flood_v_rate_id);

                if (!empty($post['floodFoundations'])) {
                    $floodFoundations = $post['floodFoundations'];

                    foreach ($floodFoundations as $floodFoundation) {

                        $foundation = $this->floodFoundationService->findOne($floodFoundation);

                        if ($foundation) {
                            $this->floodVRateService->upsertFloodFoundation($floodVRate->flood_v_rate_id, $foundation->flood_foundation_id);
                        }
                    }
                }

                return redirect()->back()->withInput()->with('message', 'V Rate was successfully updated.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('VRates/update_view', ['data' => $data]);
        }
    }
}
