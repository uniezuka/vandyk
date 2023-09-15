<?php

namespace App\Services;

class InsurerService extends BaseService
{
    protected $limit = 20;

    public function getPaged($page = 1)
    {
        $offset = ($page-1) * $this->limit;

        $builder = $this->db->table('insurer');
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
        $builder = $this->db->table('insurer');

        $data = [
            'naic'                  => $message->naic,
            'name'                  => $message->name,
        ];

        $builder->insert($data);

        $id = $this->db->insertID();

        return $this->findOne($id);
    }

    public function findOne($id)
    {
        $builder = $this->db->table('insurer');
        $builder->where('insurer_id', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }

    public function update(object $message)
    {
        $builder = $this->db->table('insurer');

        $data = [
            'naic'                  => $message->naic,
            'name'                  => $message->name,
        ];

        $builder->set($data);
        $builder->where('insurer_id', $message->insurer_id);
        $builder->update();
        
        return $this->findOne($message->insurer_id);
    }

    public function getAll()
    {
        $builder = $this->db->table('insurer');
        $builder->select('*');

        $query = $builder->get();

        return $query->getResult();
    }

    public function setActive($id, $isActive = true)
    {
        $builder = $this->db->table('insurer');

        $data = [
            'is_active' => $isActive,
        ];

        $builder->set($data);
        $builder->where('insurer_id', $id);
        $builder->update();
        
        return $this->findOne($id);
    }
}
