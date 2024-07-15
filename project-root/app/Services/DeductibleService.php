<?php

namespace App\Services;

class DeductibleService extends BaseService
{
    protected $limit = 20;

    public function getAll()
    {
        $builder = $this->db->table('deductible');
        $builder->select('*');

        $query = $builder->get();

        return $query->getResult();
    }
}
