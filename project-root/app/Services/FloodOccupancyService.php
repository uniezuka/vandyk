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

    public function findOne($id)
    {
        $builder = $this->db->table('flood_occupancy');
        $builder->where('flood_occupancy_id', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }
}
