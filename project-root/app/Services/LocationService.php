<?php

namespace App\Services;

class LocationService extends BaseService
{
    protected $limit = 20;

    public function getStates()
    {
        $builder = $this->db->table('state');
        $builder->orderBy('name', 'ASC');
        $query   = $builder->get();

        return $query->getResult();
    }
}
