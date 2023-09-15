<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class FireCodes extends BaseController
{
    protected $pager;
    protected $fireCodeService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->fireCodeService = service('fireCodeService');
    }

    public function index()
    {
        helper('form');

        $page  = (int) ($this->request->getGet('page') ?? 1);
        $fireCodes = $this->fireCodeService->getPaged($page);
        $pager_links = $this->pager->makeLinks($page, $fireCodes->limit, $fireCodes->total, 'bootstrap_full');

        $data['fireCodes'] = $fireCodes->data;
        $data['title'] = "Fire Codes";
        $data['pager_links'] = $pager_links;
        return view('FireCodes/index_view', ['data' => $data]);
    }

    public function create()
    {
        helper('form');

        $data['title'] = "Create New Fire Code";

        if (!$this->request->is('post')) {
            return view('FireCodes/create_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'name'
        ]);

        if ($this->validateData($post, [
            'name' => 'required|max_length[250]'
        ])) {
            try {
                $this->fireCodeService->create((object) $post);
                return redirect()->to('/fire_codes')->with('message', 'Fire Code was successfully added.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('FireCodes/create_view', ['data' => $data]);
        }
    }

    public function update($id = null)
    {
        helper('form');

        $data['title'] = "Update Fire Code";
        $data['fireCode'] = $this->fireCodeService->findOne($id);

        if (!$data['fireCode']) {
            return redirect()->to('/fire_codes')->with('error', "Fire Code not found.");
        }

        if (!$this->request->is('post')) {
            return view('FireCodes/update_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'name'
        ]);

        if ($this->validateData($post, [
            'name' => 'required|max_length[250]'
        ])) {
            try {
                $post['fire_code_id'] = $id;

                $this->fireCodeService->update((object) $post);
                return redirect()->back()->withInput()->with('message', 'Fire Code was successfully updated.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('FireCodes/update_view', ['data' => $data]);
        }          
    }
}
