<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class BritARates extends BaseController
{
    protected $pager;
    protected $britFloodARateService;
    protected $floodFoundationService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->britFloodARateService = service('britFloodARateService');
        $this->floodFoundationService = service('floodFoundationService');
    }

    public function index()
    {
        helper('form');

        $rates = $this->britFloodARateService->getAll();

        $data['rates'] = $rates;
        $data['title'] = "Brit A Rates";
        return view('BritARates/index_view', ['data' => $data]);
    }

    public function create()
    {
        helper('form');

        $data['title'] = "Create New Brit A Rates";

        if (!$this->request->is('post')) {
            return view('BritARates/create_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'description',
            'zip',
            'state',
            'county',
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
            'dwl-1' => 'required|numeric',
            'cont-1' => 'required|numeric',
            'both-1' => 'required|numeric',
        ])) {
            try {
                $post['dwl_1'] = $post['dwl-1'];
                $post['cont_1'] = $post['cont-1'];
                $post['both_1'] = $post['both-1'];
                $post['state_code'] = $post['state'];
                $post['county_id'] = $post['county'];

                $floodARate = $this->britFloodARateService->create((object) $post);

                $this->britFloodARateService->deleteFloodFoundations($floodARate->brit_flood_a_rate_id);

                if (!empty($post['floodFoundations'])) {
                    $floodFoundations = $post['floodFoundations'];

                    foreach ($floodFoundations as $floodFoundation) {

                        $foundation = $this->floodFoundationService->findOne($floodFoundation);

                        if ($foundation) {
                            $this->britFloodARateService->upsertFloodFoundation($floodARate->brit_flood_a_rate_id, $foundation->flood_foundation_id);
                        }
                    }
                }

                return redirect()->to('/brit_a_rates')->with('message', 'Brit A Rate was successfully added.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('BritARates/create_view', ['data' => $data]);
        }
    }

    public function update($id = null)
    {
        helper('form');

        $data['title'] = "Update Brit A Rate";
        $data['rate'] = $this->britFloodARateService->findOne($id);

        if (!$data['rate']) {
            return redirect()->to('/brit_a_rates')->with('error', "Brit A Rate not found.");
        }

        $floodFoundations =  $this->britFloodARateService->getFloodFoundations($id);
        $data['floodFoundations'] = $floodFoundations;

        if (!$this->request->is('post')) {
            return view('BritARates/update_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'description',
            'zip',
            'state',
            'county',
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
                $post['brit_flood_a_rate_id'] = $id;
                $post['dwl_1'] = $post['dwl-1'];
                $post['cont_1'] = $post['cont-1'];
                $post['both_1'] = $post['both-1'];
                $post['state_code'] = $post['state'];
                $post['county_id'] = $post['county'];

                $floodARate = $this->britFloodARateService->update((object) $post);

                $this->britFloodARateService->deleteFloodFoundations($floodARate->brit_flood_a_rate_id);

                if (!empty($post['floodFoundations'])) {
                    $floodFoundations = $post['floodFoundations'];

                    foreach ($floodFoundations as $floodFoundation) {

                        $foundation = $this->floodFoundationService->findOne($floodFoundation);

                        if ($foundation) {
                            $this->britFloodARateService->upsertFloodFoundation($floodARate->brit_flood_a_rate_id, $foundation->flood_foundation_id);
                        }
                    }
                }

                return redirect()->back()->withInput()->with('message', 'Brit A Rate was successfully updated.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('BritARates/update_view', ['data' => $data]);
        }
    }
}
