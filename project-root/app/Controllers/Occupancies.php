<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class Occupancies extends BaseController
{
    protected $pager;
    protected $occupancyService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->occupancyService = service('occupancyService');
    }

    public function index()
    {
        helper('form');

        $page  = (int) ($this->request->getGet('page') ?? 1);
        $occupancies = $this->occupancyService->getPaged($page);
        $pager_links = $this->pager->makeLinks($page, $occupancies->limit, $occupancies->total, 'bootstrap_full');

        $data['occupancies'] = $occupancies->data;
        $data['title'] = "Occupancies";
        $data['pager_links'] = $pager_links;
        return view('Occupancies/index_view', ['data' => $data]);
    }

    public function create()
    {
        helper('form');

        $data['title'] = "Create New Occupancy";

        if (!$this->request->is('post')) {
            return view('Occupancies/create_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'label', 'value'
        ]);

        if ($this->validateData($post, [
            'value' => 'required|max_length[250]'
        ])) {
            try {
                $this->occupancyService->create((object) $post);
                return redirect()->to('/occupancies')->with('message', 'Occupancy was successfully added.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('Occupancies/create_view', ['data' => $data]);
        }
    }

    public function update($id = null)
    {
        helper('form');

        $data['title'] = "Update Occupancy";
        $data['occupancy'] = $this->occupancyService->findOne($id);

        if (!$data['occupancy']) {
            return redirect()->to('/occupancies')->with('error', "Occupancy not found.");
        }

        if (!$this->request->is('post')) {
            return view('Occupancies/update_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'label', 'value'
        ]);

        if ($this->validateData($post, [
            'value' => 'required|max_length[250]'
        ])) {
            try {
                $post['occupancy_id'] = $id;

                $this->occupancyService->update((object) $post);
                return redirect()->back()->withInput()->with('message', 'Occupancy was successfully updated.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('Occupancies/update_view', ['data' => $data]);
        }
    }
}
