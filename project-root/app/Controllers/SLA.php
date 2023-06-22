<?php

namespace App\Controllers;

class SLA extends BaseController
{
    public function add()
    {
        $data['title'] = "Add SLA Number";
        return view('SLA/add_view', ['data' => $data]);
    }

    public function index()
    {
        $data['title'] = "SLA Numbers";
        return view('SLA/index_view', ['data' => $data]);
    }

    public function edit()
    {
        $data['title'] = "Edit SLA Number";
        return view('SLA/edit_view', ['data' => $data]);
    }
}