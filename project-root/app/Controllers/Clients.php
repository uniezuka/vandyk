<?php

namespace App\Controllers;

class Clients extends BaseController
{
    public function index()
    {
        $data['title'] = "Clients";
        return view('Clients/index_view', ['data' => $data]);
    }

    public function add()
    {
        $data['title'] = "Add New Client";
        return view('Clients/add_view', ['data' => $data]);
    }

    public function details()
    {
        $data['title'] = "Client Details";
        return view('Clients/details_view', ['data' => $data]);
    }

    public function update()
    {
        $data['title'] = "Update Client";
        return view('Clients/update_view', ['data' => $data]);
    }
}
