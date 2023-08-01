<?php

namespace App\Services;

class EntityService extends BaseService
{
    protected $limit = 20;

    public function getBusinessEntityTypes()
    {
        $builder = $this->db->table('business_entity_type');
        $query   = $builder->get();

        return $query->getResult();
    }
}