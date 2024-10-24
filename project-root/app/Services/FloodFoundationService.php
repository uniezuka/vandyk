<?php

namespace App\Services;

class FloodFoundationService extends BaseService
{
    protected $limit = 20;

    public function getAll()
    {
        $builder = $this->db->table('flood_foundation');
        $builder->select('*');

        $query = $builder->get();

        return $query->getResult();
    }

    public function findOne($id)
    {
        $builder = $this->db->table('flood_foundation');
        $builder->where('flood_foundation_id ', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }

    public function create(object $message)
    {
        $builder = $this->db->table('flood_foundation');

        $data = [
            'name'                  => $message->name,
        ];

        $builder->insert($data);

        $id = $this->db->insertID();

        return $this->findOne($id);
    }

    public function update(object $message)
    {
        $builder = $this->db->table('flood_foundation');

        $data = [
            'name'                  => $message->name,
        ];

        $builder->set($data);
        $builder->where('flood_foundation_id', $message->flood_foundation_id);
        $builder->update();

        return $this->findOne($message->flood_foundation_id);
    }
}
