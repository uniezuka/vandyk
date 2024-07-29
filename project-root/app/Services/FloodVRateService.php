<?php

namespace App\Services;

class FloodVRateService extends BaseService
{
    protected $limit = 20;

    public function findOne($id)
    {
        $builder = $this->db->table('flood_v_rate');
        $builder->where('flood_v_rate_id ', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }
}
