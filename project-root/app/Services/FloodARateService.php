<?php

namespace App\Services;

class FloodARateService extends BaseService
{
    protected $limit = 20;

    public function findOne($id)
    {
        $builder = $this->db->table('flood_a_rate');
        $builder->where('flood_a_rate_id ', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }
}
