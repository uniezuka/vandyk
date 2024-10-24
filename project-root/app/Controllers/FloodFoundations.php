<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class FloodFoundations extends BaseController
{
    protected $pager;
    protected $floodFoundationService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->floodFoundationService = service('floodFoundationService');
    }

    public function index()
    {
        helper('form');

        $floodFoundations = $this->floodFoundationService->getAll();

        $data['floodFoundations'] = $floodFoundations;
        $data['title'] = "Flood Foundations";
        return view('FloodFoundations/index_view', ['data' => $data]);
    }

    public function create()
    {
        helper('form');

        $data['title'] = "Create New Flood Foundation";

        if (!$this->request->is('post')) {
            return view('FloodFoundations/create_view', ['data' => $data]);
        }

        $post = $this->request->getPost(['name']);

        if ($this->validateData($post, [
            'name' => 'required|max_length[250]'
        ])) {
            try {
                $this->floodFoundationService->create((object) $post);
                return redirect()->to('/flood_foundations')->with('message', 'Flood Foundation was successfully added.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('FloodFoundations/create_view', ['data' => $data]);
        }
    }

    public function update($id = null)
    {
        helper('form');

        $data['title'] = "Update Flood Foundation";
        $data['floodFoundation'] = $this->floodFoundationService->findOne($id);

        if (!$data['floodFoundation']) {
            return redirect()->to('/flood_foundations')->with('error', "Flood Foundation not found.");
        }

        if (!$this->request->is('post')) {
            return view('FloodFoundations/update_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'name',
            'credit'
        ]);

        if ($this->validateData($post, [
            'name' => 'required|max_length[250]',
        ])) {
            try {
                $post['flood_foundation_id'] = $id;

                $this->floodFoundationService->update((object) $post);
                return redirect()->back()->withInput()->with('message', 'Flood Foundation was successfully updated.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('FloodFoundations/update_view', ['data' => $data]);
        }
    }
}
