<?php

namespace App\Controllers;

class Settings extends BaseController
{
    public function transaction_types()
    {
        $data['title'] = "Transaction Types";
        return view('Settings/transaction_types_view', ['data' => $data]);
    }

    public function fire_codes()
    {
        $data['title'] = "Fire Codes";
        return view('Settings/fire_codes_view', ['data' => $data]);
    }

    public function coverage_list()
    {
        $data['title'] = "Coverage List";
        return view('Settings/coverage_list_view', ['data' => $data]);
    }

    public function insurer_naic_list()
    {
        $data['title'] = "Insurer NAIC";
        return view('Settings/insurer_naic_list_view', ['data' => $data]);
    }
}
