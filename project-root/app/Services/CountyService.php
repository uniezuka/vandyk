<?php

namespace App\Services;

use CodeIgniter\CLI\Console;

class CountyService extends BaseService
{
    protected $limit = 20;

    public function getPaged($page = 1)
    {
        $offset = ($page-1) * $this->limit;

        $builder = $this->db->table('county');
        $builder->select('*');
        $query = $builder->get($this->limit, $offset);

        $total = $builder->countAllResults();

        return (object) array(
            'data'   => $query->getResult(),
            'total'  => $total,
            'page'   => $page,
            'limit'  => $this->limit,
            'offset' => $offset,
        );
    }

    public function create(object $message)
    {
        $builder = $this->db->table('county');

        $data = [
            'name'                  => $message->name,
            'state'                 => $message->state,
        ];

        $builder->insert($data);

        $id = $this->db->insertID();

        return $this->findOne($id);
    }

    public function findOne($id)
    {
        $builder = $this->db->table('county');
        $builder->where('county_id', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }

    public function update(object $message)
    {
        $builder = $this->db->table('county');

        $data = [
            'name'                  => $message->name,
            'state'                 => $message->state,
        ];

        $builder->set($data);
        $builder->where('county_id', $message->county_id);
        $builder->update();
        
        return $this->findOne($message->county_id);
    }

    public function getAll()
    {
        $builder = $this->db->table('county');
        $builder->select('*');

        $query = $builder->get();

        return $query->getResult();
    }
}
