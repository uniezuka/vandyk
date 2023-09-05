<?php

namespace App\Services;

use CodeIgniter\CLI\Console;

class FoundationService extends BaseService
{
    protected $limit = 20;

    public function getPaged($page = 1)
    {
        $offset = ($page-1) * $this->limit;

        $builder = $this->db->table('foundation');
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
        $builder = $this->db->table('foundation');

        $data = [
            'name'                  => $message->name,
            'is_elavated'           => $message->is_elavated,
        ];

        $builder->insert($data);

        $id = $this->db->insertID();

        return $this->findOne($id);
    }

    public function findOne($id)
    {
        $builder = $this->db->table('foundation');
        $builder->where('foundation_id', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }

    public function update(object $message)
    {
        $builder = $this->db->table('foundation');

        $data = [
            'name'                  => $message->name,
            'is_elavated'           => $message->is_elavated,
        ];

        $builder->set($data);
        $builder->where('foundation_id', $message->foundation_id);
        $builder->update();
        
        return $this->findOne($message->foundation_id);
    }

    public function getAll()
    {
        $builder = $this->db->table('foundation');
        $builder->select('*');

        $query = $builder->get();

        return $query->getResult();
    }
}
