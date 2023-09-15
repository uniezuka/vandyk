<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class TransactionTypes extends BaseController
{
    protected $pager;
    protected $transactionTypeService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->transactionTypeService = service('transactionTypeService');
    }

    public function index()
    {
        helper('form');

        $page  = (int) ($this->request->getGet('page') ?? 1);
        $transactionTypes = $this->transactionTypeService->getPaged($page);
        $pager_links = $this->pager->makeLinks($page, $transactionTypes->limit, $transactionTypes->total, 'bootstrap_full');

        $data['transactionTypes'] = $transactionTypes->data;
        $data['title'] = "Transaction Types";
        $data['pager_links'] = $pager_links;
        return view('TransactionTypes/index_view', ['data' => $data]);
    }

    public function create()
    {
        helper('form');

        $data['title'] = "Create New Transaction Type";

        if (!$this->request->is('post')) {
            return view('TransactionTypes/create_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'name'
        ]);

        if ($this->validateData($post, [
            'name' => 'required|max_length[250]'
        ])) {
            try {
                $this->transactionTypeService->create((object) $post);
                return redirect()->to('/transaction_types')->with('message', 'Transaction Type was successfully added.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('TransactionTypes/create_view', ['data' => $data]);
        }
    }

    public function update($id = null)
    {
        helper('form');

        $data['title'] = "Update Transaction Type";
        $data['transactionType'] = $this->transactionTypeService->findOne($id);

        if (!$data['transactionType']) {
            return redirect()->to('/transaction_types')->with('error', "Transaction Type not found.");
        }

        if (!$this->request->is('post')) {
            return view('TransactionTypes/update_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'name'
        ]);

        if ($this->validateData($post, [
            'name' => 'required|max_length[250]'
        ])) {
            try {
                $post['transaction_type_id'] = $id;

                $this->transactionTypeService->update((object) $post);
                return redirect()->back()->withInput()->with('message', 'Transaction Type was successfully updated.');
            } catch(Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
        else {
            return view('TransactionTypes/update_view', ['data' => $data]);
        }          
    }
}
