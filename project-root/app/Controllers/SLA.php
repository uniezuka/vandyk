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
    protected $slaSettingService;
    protected $insurerService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->slaPolicyService = service('slaPolicyService');
        $this->slaSettingService = service('slaSettingService');
        $this->insurerService = service('insurerService');
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
        $data['currentSLASetting'] = $this->slaSettingService->getCurrent();

        $prevYear = $data['currentSLASetting']->year - 1;
        $data['prevSLASetting'] = $this->slaSettingService->getByYear($prevYear);


        $data['availableSLAPolicies'] = $this->slaPolicyService->getAvailableSLAPolicies(5, $data['currentSLASetting']->prefix);

        $data['prevAvailableSLAPolicies'] = ($data['prevSLASetting']) ?
            $this->slaPolicyService->getAvailableSLAPolicies(5, $data['prevSLASetting']->prefix) : [];

        return view('SLA/index_view', ['data' => $data]);
    }

    public function reclaim()
    {
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
        } catch (Exception $e) {
            return redirect()->to('/sla')->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        helper('form');

        $data['title'] = "Create SLA Code";
        $data['currentSLASetting'] = $this->slaSettingService->getCurrent();

        if (!$this->request->is('post')) {
            return view('SLA/create_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'transactionNumber', 'transactionTypeId', 'insuredName', 'policyNumber', 'effectivityDate', 'expiryDate', 'insurerId', 'firePremium',
            'otherPremium', 'totalPremium', 'location', 'zip', 'county', 'fireCodeId', 'coverageId', 'transactionDate'
        ]);

        if ($this->validateData($post, [
            'insuredName' => 'required',
            'policyNumber' => 'required',
            'effectivityDate' => 'required',
            'expiryDate' => 'required',
            'otherPremium' => 'required',
            'county' => 'required',
            'location' => 'required',
            'zip' => 'required',
            'transactionDate' => 'required',
        ])) {
            try {
                $insurer = $this->insurerService->findOne($post['insurerId']);
                $post['insurerNAIC'] = $insurer->naic;
                $post['transactionNumber'] = $data['currentSLASetting']->prefix . $post['transactionNumber'];

                $this->slaPolicyService->create((object) $post);
                return redirect()->to('/sla')->with('message', 'SLA Number was successfully added.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('SLA/create_view', ['data' => $data]);
        }

        return view('SLA/create_view', ['data' => $data]);
    }

    public function update($id = null)
    {
        helper('form');

        $data['title'] = "Update SLA Code";
        $data['sla_policy'] = $this->slaPolicyService->findOne($id);

        if (!$data['sla_policy']) {
            return redirect()->to('/sla')->with('error', "SLA Policy not found.");
        }

        if (!$this->request->is('post')) {
            return view('SLA/update_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'transactionTypeId', 'insuredName', 'policyNumber', 'effectivityDate', 'expiryDate', 'insurerId', 'firePremium',
            'otherPremium', 'totalPremium', 'location', 'zip', 'county', 'fireCodeId', 'coverageId', 'transactionDate'
        ]);

        if ($this->validateData($post, [
            'insuredName' => 'required',
            'policyNumber' => 'required',
            'effectivityDate' => 'required',
            'expiryDate' => 'required',
            'otherPremium' => 'required',
            'county' => 'required',
            'location' => 'required',
            'zip' => 'required',
            'transactionDate' => 'required',
        ])) {
            try {
                $post['sla_policy_id'] = $id;
                $insurer = $this->insurerService->findOne($post['insurerId']);
                $post['insurerNAIC'] = $insurer->naic;

                $this->slaPolicyService->update((object) $post);
                return redirect()->back()->withInput()->with('message', 'SLA Policy was successfully updated.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('SLA/update_view', ['data' => $data]);
        }
    }
}
