<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class Insurers extends BaseController
{
    protected $pager;
    protected $insurerService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->insurerService = service('insurerService');
    }

    public function index()
    {
        helper('form');

        $page  = (int) ($this->request->getGet('page') ?? 1);
        $insurers = $this->insurerService->getPaged($page);
        $pager_links = $this->pager->makeLinks($page, $insurers->limit, $insurers->total, 'bootstrap_full');

        $data['insurers'] = $insurers->data;
        $data['title'] = "Insurers";
        $data['pager_links'] = $pager_links;
        return view('Insurers/index_view', ['data' => $data]);
    }

    public function create()
    {
        helper('form');

        $data['title'] = "Create New Insurer";

        if (!$this->request->is('post')) {
            return view('Insurers/create_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'naic', 'name'
        ]);

        if ($this->validateData($post, [
            'naic' => 'required|max_length[250]',
            'name' => 'required|max_length[250]'
        ])) {
            try {
                $this->insurerService->create((object) $post);
                return redirect()->to('/insurers')->with('message', 'Insurer was successfully added.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('Insurers/create_view', ['data' => $data]);
        }
    }

    public function update($id = null)
    {
        helper('form');

        $data['title'] = "Update Insurer";
        $data['insurer'] = $this->insurerService->findOne($id);

        if (!$data['insurer']) {
            return redirect()->to('/insurers')->with('error', "Insurer not found.");
        }

        if (!$this->request->is('post')) {
            return view('Insurers/update_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'naic', 'name'
        ]);

        if ($this->validateData($post, [
            'naic' => 'required|max_length[250]',
            'name' => 'required|max_length[250]'
        ])) {
            try {
                $post['insurer_id'] = $id;

                $this->insurerService->update((object) $post);
                return redirect()->back()->withInput()->with('message', 'Insurer was successfully updated.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('Insurers/update_view', ['data' => $data]);
        }          
    }

    public function activate($id = null)
    {
        helper('form');

        $data['insurer'] = $this->insurerService->findOne($id);

        if (!$data['insurer']) {
            return redirect()->to('/insurers')->with('error', "Insurer not found.");
        }

        $this->insurerService->setActive($id);

        return redirect()->to('/insurers')->with('message', "Insurer has been activated.");      
    }

    public function deactivate($id = null)
    {
        helper('form');

        $data['insurer'] = $this->insurerService->findOne($id);

        if (!$data['insurer']) {
            return redirect()->to('/insurers')->with('error', "Insurer not found.");
        }

        $this->insurerService->setActive($id, false);

        return redirect()->to('/insurers')->with('message', "Insurer has been deactivated.");      
    }
}
