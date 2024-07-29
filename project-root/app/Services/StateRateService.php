<?php

namespace App\Services;

class StateRateService extends BaseService
{
    protected $limit = 20;

    public function getByState($state)
    {
        $builder = $this->db->table('state_rate');
        $builder->where('state ', $state);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }
}
