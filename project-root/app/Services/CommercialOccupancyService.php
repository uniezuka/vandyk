<?php

namespace App\Services;

class CommercialOccupancyService extends BaseService
{
    protected $limit = 20;

    public function getAll()
    {
        $builder = $this->db->table('commercial_occupancy');
        $builder->select('*');

        $query = $builder->get();

        return $query->getResult();
    }
}
