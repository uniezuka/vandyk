<?php

namespace App\Services;

class ProducerService extends BaseService
{
    protected $limit = 20;

    public function getAll()
    {
        $builder = $this->db->table('producer');
        $builder->orderBy('producer_id', 'ASC');
        $query   = $builder->get();

        return $query->getResult();
    }
}
