<?php

namespace App\Services;

class SLAPolicyService extends BaseService
{
    protected $limit = 20;

    public function getPaged($page = 1)
    {
        $offset = ($page - 1) * $this->limit;

        $builder = $this->db->table('sla_policy');
        $builder->where('transaction_type_id !=', 0);
        $builder->orderBy('transaction_number', 'DESC');
        
        $query = $builder->get($this->limit, $offset, false);

        $total = $builder->countAllResults(false);

        return (object) array(
            'data'   => $query->getResult(),
            'total'  => $total,
            'page'   => $page,
            'limit'  => $this->limit,
            'offset' => $offset,
        );
    }

    public function search($page = 1, $search_text)
    {
        $offset = ($page - 1) * $this->limit;

        $builder = $this->db->table('sla_policy');

        $builder->groupStart();
        $builder->like('insured_name', $search_text, 'both', null, true);
        $builder->orLike('policy_number', $search_text, 'both', null, true);
        $builder->groupEnd();

        $builder->where('transaction_type_id !=', 0);
        $builder->orderBy('transaction_number', 'DESC');

        $total = $builder->countAllResults(false);

        $query = $builder->get($this->limit, $offset, false);

        return (object) array(
            'data'   => $query->getResult(),
            'total'  => $total,
            'page'   => $page,
            'limit'  => $this->limit,
            'offset' => $offset,
        );
    }

    public function reclaim($sla_policy_id)
    {
        $builder = $this->db->table('sla_policy');

        $data = [
            'transaction_type_id'         => 0,
            'insured_name'                => '',
            'policy_number'               => '',
            'effectivity_date '           => '',
            'expiry_date'                 => '',
            'fire_premium'                => 0,
            'other_premium'               => 0,
            'total_premium'               => 0,
            'county'                      => '',
            'location'                    => '',
            'zip'                         => '',
            'fire_code_id'                => 0,
            'coverage_id'                 => 0,
            'insurer_naic'                => '',
            'transaction_date'            => '',
            'insurer_id'                  => 0,
        ];

        $builder->set($data);
        $builder->where('sla_policy_id', $sla_policy_id);
        $builder->update();
        
        return $this->findOne($sla_policy_id);
    }

    public function findOne($id)
    {
        $builder = $this->db->table('sla_policy');
        $builder->where('sla_policy_id', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }
}
