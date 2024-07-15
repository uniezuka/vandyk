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
}
