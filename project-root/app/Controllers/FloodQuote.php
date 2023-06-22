<?php

namespace App\Controllers;

class FloodQuote extends BaseController
{
    public function create()
    {
        $data['title'] = "Create Flood Quote";
        return view('FloodQuote/create_view', ['data' => $data]);
    }

    public function update()
    {
        $data['title'] = "Update Flood Quote";
        return view('FloodQuote/update_view', ['data' => $data]);
    }
}