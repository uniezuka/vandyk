<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class SLA extends BaseController
{
    protected $pager;
    protected $slaPolicyService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->slaPolicyService = service('slaPolicyService');
    }

    public function index()
    {
        helper('form');

        $page  = (int) ($this->request->getGet('page') ?? 1);
        $search = $this->request->getGet('search') ?? "";
        $search = trim($search);

        if ($search)
            $slaPolicies = $this->slaPolicyService->search($page, $search);
        else 
            $slaPolicies = $this->slaPolicyService->getPaged($page);

        $pager_links = $this->pager->makeLinks($page, $slaPolicies->limit, $slaPolicies->total, 'bootstrap_full');

        $data['slaPolicies'] = $slaPolicies->data;
        $data['title'] = "SLA Numbers";
        $data['pager_links'] = $pager_links;
        $data['search'] = $this->request->getGet('search') ?? "";
        
        return view('SLA/index_view', ['data' => $data]);
    }

    public function reclaim() {
        helper('form');

        if (!$this->request->is('post')) {
            return redirect()->to('/sla');
        }

        $post = $this->request->getPost(['sla_policy_id']);
        $sla_policy_id = $post['sla_policy_id'];

        $policy = $this->slaPolicyService->findOne($post['sla_policy_id']);

        if (!$policy) {
            return redirect()->to('/sla')->with('error', "SLA Policy not found.");
        }

        try {
            $this->slaPolicyService->reclaim($sla_policy_id);

            return redirect()->to('/sla')->with('message', $policy->transaction_number . ' has been released.');
        } catch(Exception $e) {
            return redirect()->to('/sla')->with('error', $e->getMessage());
        }
    }
}