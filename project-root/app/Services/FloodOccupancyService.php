<?php

namespace App\Services;

class FloodOccupancyService extends BaseService
{
    protected $limit = 20;

    public function getAll()
    {
        $builder = $this->db->table('flood_occupancy');
        $builder->select('*');

        $query = $builder->get();

        return $query->getResult();
    }
}
