<?php

namespace App\Services;

class FloodZoneService extends BaseService
{
    protected $limit = 20;

    public function getAll()
    {
        $builder = $this->db->table('flood_zone');
        $builder->orderBy('flood_zone_id', 'ASC');
        $query   = $builder->get();

        return $query->getResult();
    }
}
