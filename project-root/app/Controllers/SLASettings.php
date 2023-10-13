<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class SLASettings extends BaseController
{
    protected $pager;
    protected $slaSettingService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->slaSettingService = service('slaSettingService');
    }

    public function index()
    {
        helper('form');

        $page  = (int) ($this->request->getGet('page') ?? 1);
        $sla_settings = $this->slaSettingService->getPaged($page);
        $pager_links = $this->pager->makeLinks($page, $sla_settings->limit, $sla_settings->total, 'bootstrap_full');

        $data['sla_settings'] = $sla_settings->data;
        $data['title'] = "SLA Generator Settings";
        $data['pager_links'] = $pager_links;
        return view('SLASettings/index_view', ['data' => $data]);
    }

    public function create()
    {
        helper('form');

        $data['title'] = "Create New SLA Setting";

        if (!$this->request->is('post')) {
            return view('SLASettings/create_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'year', 'prefix'
        ]);

        if ($this->validateData($post, [
            'year' => 'required|max_length[250]|numeric',
            'prefix' => 'required|max_length[250]'
        ])) {
            try {
                $post['is_current'] = false;
                $this->slaSettingService->create((object) $post);
                return redirect()->to('/sla_settings')->with('message', 'SLA Setting was successfully added.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('SLASettings/create_view', ['data' => $data]);
        }
    }

    public function update($id = null)
    {
        helper('form');

        $data['title'] = "Update SLA Setting";
        $data['sla_setting'] = $this->slaSettingService->findOne($id);

        if (!$data['sla_setting']) {
            return redirect()->to('/sla_settings')->with('error', "SLA Setting not found.");
        }

        if (!$this->request->is('post')) {
            return view('SLASettings/update_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'year', 'prefix'
        ]);

        if ($this->validateData($post, [
            'year' => 'required|max_length[250]|numeric',
            'prefix' => 'required|max_length[250]'
        ])) {
            try {
                $post['sla_setting_id'] = $id;

                $this->slaSettingService->update((object) $post);
                return redirect()->back()->withInput()->with('message', 'SLA Setting was successfully updated.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('SLASettings/update_view', ['data' => $data]);
        }
    }

    public function set_current($id = null)
    {
        helper('form');

        $data['sla_setting'] = $this->slaSettingService->findOne($id);

        if (!$data['sla_setting']) {
            return redirect()->to('/sla_settings')->with('error', "SLA Setting not found.");
        }

        $this->slaSettingService->setCurrent($id);

        return redirect()->to('/sla_settings')->with('message', "Set a current SLA Setting.");
    }
}
