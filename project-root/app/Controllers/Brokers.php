<?php

namespace App\Controllers;

class Brokers extends BaseController
{
    public function index()
    {
        $db = db_connect();

        $pager = service('pager');
        $page  = (int) ($this->request->getGet('page') ?? 1);
        $limit = 20;
        $offset = ($page-1) * $limit;

        $builder = $db->table('broker');
        $builder->select('*');
        $builder->join('broker_login', 'broker.broker_id = broker_login.broker_id');
        $query = $builder->get($limit, $offset);

        $total = $builder->countAllResults();

        $pager_links = $pager->makeLinks($page, $limit, $total, 'bootstrap_full');

        $data['brokers'] = $query->getResult();
        $data['title'] = "Brokers List";
        $data['pager_links'] = $pager_links;
        return view('Brokers/index_view', ['data' => $data]);
    }
}
