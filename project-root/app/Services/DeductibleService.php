<?php

namespace App\Services;

class DeductibleService extends BaseService
{
    protected $limit = 20;

    public function getAll()
    {
        $builder = $this->db->table('deductible');
        $builder->select('*');

        $query = $builder->get();

        return $query->getResult();
    }

    public function create(object $message)
    {
        $builder = $this->db->table('deductible');

        $data = [
            'name'                  => $message->name,
        ];

        $builder->insert($data);

        $id = $this->db->insertID();

        return $this->findOne($id);
    }

    public function findOne($id)
    {
        $builder = $this->db->table('deductible');
        $builder->where('deductible_id', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }

    public function update(object $message)
    {
        $builder = $this->db->table('deductible');

        $data = [
            'name'                  => $message->name,
        ];

        $builder->set($data);
        $builder->where('deductible_id', $message->deductible_id);
        $builder->update();

        return $this->findOne($message->deductible_id);
    }
}
