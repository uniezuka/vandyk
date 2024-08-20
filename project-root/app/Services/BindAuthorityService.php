<?php

namespace App\Services;

class BindAuthorityService extends BaseService
{
    protected $limit = 20;

    public function getActive()
    {
        $builder = $this->db->table('bind_authority');
        $builder->orderBy('bind_authority_id', 'ASC');
        $builder->where('is_active', 1);
        $query   = $builder->get();

        return $query->getResult();
    }

    public function findOne($id)
    {
        $builder = $this->db->table('bind_authority');
        $builder->where('bind_authority_id', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }
}
