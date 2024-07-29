<?php

namespace App\Services;

class FloodBCXRateService extends BaseService
{
    protected $limit = 20;

    public function findOne($id)
    {
        $builder = $this->db->table('flood_bcx_rate');
        $builder->where('flood_bcx_rate_id ', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }
}
