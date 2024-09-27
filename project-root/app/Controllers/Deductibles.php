<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class Deductibles extends BaseController
{
    protected $pager;
    protected $deductibleService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->deductibleService = service('deductibleService');
    }

    public function index()
    {
        helper('form');

        $deductibles = $this->deductibleService->getAll();

        $data['deductibles'] = $deductibles;
        $data['title'] = "Deductibles";
        return view('Deductibles/index_view', ['data' => $data]);
    }

    public function create()
    {
        helper('form');

        $data['title'] = "Create New Deductible";

        if (!$this->request->is('post')) {
            return view('Deductibles/create_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'name'
        ]);

        if ($this->validateData($post, [
            'name' => 'required|max_length[250]'
        ])) {
            try {
                $this->deductibleService->create((object) $post);
                return redirect()->to('/deductibles')->with('message', 'Deductible was successfully added.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('Deductibles/create_view', ['data' => $data]);
        }
    }

    public function update($id = null)
    {
        helper('form');

        $data['title'] = "Update Deductible";
        $data['deductible'] = $this->deductibleService->findOne($id);

        if (!$data['deductible']) {
            return redirect()->to('/deductibles')->with('error', "Deductible not found.");
        }

        if (!$this->request->is('post')) {
            return view('Deductibles/update_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'name'
        ]);

        if ($this->validateData($post, [
            'name' => 'required|max_length[250]'
        ])) {
            try {
                $post['deductible_id'] = $id;

                $this->deductibleService->update((object) $post);
                return redirect()->back()->withInput()->with('message', 'Deductible was successfully updated.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('Deductibles/update_view', ['data' => $data]);
        }
    }
}
