<?php

namespace App\Services;

class SLAPolicyService extends BaseService
{
    protected $limit = 20;

    public function getPaged($page = 1)
    {
        $offset = ($page - 1) * $this->limit;

        $builder = $this->db->table('sla_policy');
        $builder->select('sla_policy.*, transaction_type.name as transaction_name');
        $builder->join('transaction_type', 'transaction_type.transaction_type_id = sla_policy.transaction_type_id', 'left');
        $builder->where('sla_policy.transaction_type_id !=', 0);
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
        $builder->select('sla_policy.*, transaction_type.name as transaction_name');
        $builder->join('transaction_type', 'transaction_type.transaction_type_id = sla_policy.transaction_type_id', 'left');

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

    public function create(object $message)
    {
        $builder = $this->db->table('sla_policy');

        $data = [
            'transaction_number'           => $message->transactionNumber,
            'transaction_type_id'          => $message->transactionTypeId,
            'insured_name'                 => $message->insuredName,
            'policy_number'                => $message->policyNumber,
            'effectivity_date '            => $message->effectivityDate,
            'expiry_date'                  => $message->expiryDate,
            'insurer_naic'                 => $message->insurerNAIC,
            'insurer_id'                   => $message->insurerId,
            'fire_premium'                 => $message->firePremium,
            'other_premium'                => $message->otherPremium,
            'total_premium'                => $message->totalPremium,
            'location'                     => $message->location,
            'zip'                          => $message->zip,
            'county'                       => $message->county,
            'fire_code_id'                 => $message->fireCodeId,
            'coverage_id'                  => $message->coverageId,
            'transaction_date'             => $message->transactionDate,
            'fire_tax'             => $message->fireTax,
            'reg_tax'             => $message->regTax,
        ];

        $builder->insert($data);

        $id = $this->db->insertID();

        return $this->findOne($id);
    }

    public function update(object $message)
    {
        $builder = $this->db->table('sla_policy');

        $data = [
            'transaction_type_id'          => $message->transactionTypeId,
            'insured_name'                 => $message->insuredName,
            'policy_number'                => $message->policyNumber,
            'effectivity_date '            => $message->effectivityDate,
            'expiry_date'                  => $message->expiryDate,
            'insurer_naic'                 => $message->insurerNAIC,
            'insurer_id'                   => $message->insurerId,
            'fire_premium'                 => $message->firePremium,
            'other_premium'                => $message->otherPremium,
            'total_premium'                => $message->totalPremium,
            'location'                     => $message->location,
            'zip'                          => $message->zip,
            'county'                       => $message->county,
            'fire_code_id'                 => $message->fireCodeId,
            'coverage_id'                  => $message->coverageId,
            'transaction_date'             => $message->transactionDate,
            'fire_tax'             => $message->fireTax,
            'reg_tax'             => $message->regTax,
        ];

        $builder->set($data);
        $builder->where('sla_policy_id', $message->sla_policy_id);
        $builder->update();

        return $this->findOne($message->sla_policy_id);
    }

    public function getAvailableSLAPolicies($limit = 1, $startsWith = "")
    {
        $builder = $this->db->table('sla_policy');

        $builder->select('sla_policy.*, transaction_type.name as transaction_name');
        $builder->join('transaction_type', 'transaction_type.transaction_type_id = sla_policy.transaction_type_id', 'left');

        $builder->groupStart();
        $builder->where('policy_number', "");
        $builder->orWhere('policy_number', null);
        $builder->groupEnd();
        $builder->like('transaction_number', $startsWith, 'after');

        $builder->orderBy('transaction_number', 'ASC');

        $query = $builder->get($limit, 0, false);

        return $query->getResult();
    }

    public function findByTransactionNumber($transaction_number)
    {
        $builder = $this->db->table('sla_policy');
        $builder->where('transaction_number', $transaction_number);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }

    public function getLatestPolicy($startsWith = "")
    {
        $builder = $this->db->table('sla_policy');
        $builder->like('transaction_number', $startsWith, 'after');
        $builder->orderBy('transaction_number', 'DESC');

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }
}
