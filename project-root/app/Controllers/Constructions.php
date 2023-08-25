<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class Constructions extends BaseController
{
    protected $pager;
    protected $constructionService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->constructionService = service('constructionService');
    }

    public function index()
    {
        helper('form');

        $page  = (int) ($this->request->getGet('page') ?? 1);
        $constructions = $this->constructionService->getPaged($page);
        $pager_links = $this->pager->makeLinks($page, $constructions->limit, $constructions->total, 'bootstrap_full');

        $data['constructions'] = $constructions->data;
        $data['title'] = "Counties";
        $data['pager_links'] = $pager_links;
        return view('Constructions/index_view', ['data' => $data]);
    }

    public function create()
    {
        helper('form');

        $data['title'] = "Create New Construction";

        if (!$this->request->is('post')) {
            return view('Constructions/create_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'name'
        ]);

        if ($this->validateData($post, [
            'name' => 'required|max_length[250]'
        ])) {
            try {
                $this->constructionService->create((object) $post);
                return redirect()->to('/constructions')->with('message', 'Construction was successfully added.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('Constructions/create_view', ['data' => $data]);
        }
    }

    public function update($id = null)
    {
        helper('form');

        $data['title'] = "Update Construction";
        $data['construction'] = $this->constructionService->findOne($id);

        if (!$data['construction']) {
            return redirect()->to('/constructions')->with('error', "Construction not found.");
        }

        if (!$this->request->is('post')) {
            return view('Constructions/update_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'name'
        ]);

        if ($this->validateData($post, [
            'name' => 'required|max_length[250]'
        ])) {
            try {
                $post['construction_id'] = $id;

                $this->constructionService->update((object) $post);
                return redirect()->back()->withInput()->with('message', 'Construction was successfully updated.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('Constructions/update_view', ['data' => $data]);
        }          
    }
}
