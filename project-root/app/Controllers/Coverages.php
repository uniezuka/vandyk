<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class Coverages extends BaseController
{
    protected $pager;
    protected $coverageService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->coverageService = service('coverageService');
    }

    public function index()
    {
        helper('form');

        $page  = (int) ($this->request->getGet('page') ?? 1);
        $coverages = $this->coverageService->getPaged($page);
        $pager_links = $this->pager->makeLinks($page, $coverages->limit, $coverages->total, 'bootstrap_full');

        $data['coverages'] = $coverages->data;
        $data['title'] = "Coverages";
        $data['pager_links'] = $pager_links;
        return view('Coverages/index_view', ['data' => $data]);
    }

    public function create()
    {
        helper('form');

        $data['title'] = "Create New Coverage";

        if (!$this->request->is('post')) {
            return view('Coverages/create_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'code', 'name', 'hasFirePremium'
        ]);

        if ($this->validateData($post, [
            'code' => 'required|max_length[250]',
            'name' => 'required|max_length[250]'
        ])) {
            try {
                $this->coverageService->create((object) $post);
                return redirect()->to('/coverages')->with('message', 'Coverage was successfully added.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('Coverages/create_view', ['data' => $data]);
        }
    }

    public function update($id = null)
    {
        helper('form');

        $data['title'] = "Update Coverage";
        $data['coverage'] = $this->coverageService->findOne($id);

        if (!$data['coverage']) {
            return redirect()->to('/coverages')->with('error', "Coverage not found.");
        }

        if (!$this->request->is('post')) {
            return view('Coverages/update_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'code', 'name', 'hasFirePremium'
        ]);

        if ($this->validateData($post, [
            'code' => 'required|max_length[250]',
            'name' => 'required|max_length[250]'
        ])) {
            try {
                $post['coverage_id'] = $id;

                $this->coverageService->update((object) $post);
                return redirect()->back()->withInput()->with('message', 'Coverage was successfully updated.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('Coverages/update_view', ['data' => $data]);
        }          
    }
}
