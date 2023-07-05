<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data['title'] = "Index";
        return view('Home/index_view', ['data' => $data]);
    }

    public function profile()
    {
        $data['title'] = "Profile";
        helper('form');
        
        return view('Home/profile_view', ['data' => $data]);
    }

    public function change_password()
    {
        $data['title'] = "Password Management";
        return view('Home/change_password_view', ['data' => $data]);
    }
}
