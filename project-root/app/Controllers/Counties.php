<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class Counties extends BaseController
{
    protected $pager;
    protected $countyService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->countyService = service('countyService');
    }

    public function index()
    {
        helper('form');

        $page  = (int) ($this->request->getGet('page') ?? 1);
        $counties = $this->countyService->getPaged($page);
        $pager_links = $this->pager->makeLinks($page, $counties->limit, $counties->total, 'bootstrap_full');

        $data['counties'] = $counties->data;
        $data['title'] = "Counties";
        $data['pager_links'] = $pager_links;
        return view('Counties/index_view', ['data' => $data]);
    }

    public function create()
    {
        helper('form');

        $data['title'] = "Create New County";

        if (!$this->request->is('post')) {
            return view('Counties/create_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'name', 'state'
        ]);

        if ($this->validateData($post, [
            'name' => 'required|max_length[250]',
            'state' => 'required',
        ])) {
            try {
                $this->countyService->create((object) $post);
                return redirect()->to('/counties')->with('message', 'County was successfully added.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('Counties/create_view', ['data' => $data]);
        }
    }

    public function update($id = null)
    {
        helper('form');

        $data['title'] = "Update County";
        $data['county'] = $this->countyService->findOne($id);

        if (!$data['county']) {
            return redirect()->to('/counties')->with('error', "County not found.");
        }

        if (!$this->request->is('post')) {
            return view('Counties/update_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'name', 'state'
        ]);

        if ($this->validateData($post, [
            'name' => 'required|max_length[250]',
            'state' => 'required',
        ])) {
            try {
                $post['county_id'] = $id;

                $this->countyService->update((object) $post);
                return redirect()->back()->withInput()->with('message', 'County was successfully updated.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('Counties/update_view', ['data' => $data]);
        }          
    }
}
