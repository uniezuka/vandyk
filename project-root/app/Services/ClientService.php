<?php

namespace App\Services;

use CodeIgniter\CLI\Console;

class ClientService extends BaseService
{
    protected $limit = 20;

    public function getPaged($page = 1, $commercialOnly = false, $nonCommercialOnly = false)
    {
        $offset = ($page - 1) * $this->limit;

        $builder = $this->db->table('client');
        
        if ($commercialOnly)
            $query = $builder->getWhere(['is_commercial' => 1], $this->limit, $offset, false);
        else if ($nonCommercialOnly)
            $query = $builder->getWhere(['is_commercial' => 0], $this->limit, $offset, false);
        else
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

    public function search($page = 1, $search_text, $commercialOnly = false, $nonCommercialOnly = false)
    {
        $offset = ($page - 1) * $this->limit;

        $builder = $this->db->table('client');

        $builder->groupStart();
        $builder->like('last_name', $search_text, 'both', null, true);
        $builder->orLike('first_name', $search_text, 'both', null, true);
        $builder->orLike('client_code', $search_text, 'both', null, true);
        $builder->orLike('address', $search_text, 'both', null, true);
        $builder->orLike('business_name', $search_text, 'both', null, true);
        $builder->orLike('business_name2', $search_text, 'both', null, true);
        $builder->groupEnd();

        if ($commercialOnly)
            $builder->where('is_commercial', 1);
        else if ($nonCommercialOnly)
            $builder->where('is_commercial', 0);

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

    public function create(object $message)
    {
        $builder = $this->db->table('client');

        $data = [
            'first_name'                  => $message->firstName,
            'last_name'                   => $message->lastName,
            'insured2_name'               => $message->clientName2,
            'business_name'               => $message->companyName,
            'business_name2'              => $message->companyName2,
            'address'                     => $message->address,
            'city'                        => $message->city,
            'state'                       => $message->state,
            'zip'                         => $message->zip,
            'cell_phone'                  => $message->cellPhone,
            'home_phone'                  => $message->homePhone,
            'email'                       => $message->email,
            'client_code'                 => $message->clientCode,
            'broker_id'                   => $message->brokerId,
            'tag_code'                    => '0',
            'entity_type'                 => $message->entityType,
            'is_commercial'               => ($message->isCommercial === 'true'),
            'business_as'                 => $message->businessAs,
            'business_entity_type_id'     => $message->businessEntityTypeId,
            'date_entered'                => date("Y-m-d H:i:s"),
        ];

        $builder->insert($data);

        $id = $this->db->insertID();

        return $this->findOne($id);
    }

    public function findOne($id)
    {
        $builder = $this->db->table('client');
        $builder->where('client_id', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }

    public function update(object $message)
    {
        $builder = $this->db->table('client');

        echo var_export($message, true);

        $data = [
            'first_name'                  => $message->firstName,
            'last_name'                   => $message->lastName,
            'insured2_name'               => $message->clientName2,
            'business_name'               => $message->companyName,
            'business_name2'              => $message->companyName2,
            'address'                     => $message->address,
            'city'                        => $message->city,
            'state'                       => $message->state,
            'zip'                         => $message->zip,
            'cell_phone'                  => $message->cellPhone,
            'home_phone'                  => $message->homePhone,
            'email'                       => $message->email,
            'client_code'                 => $message->clientCode,
            'broker_id'                   => $message->brokerId,
            'tag_code'                    => '0',
            'entity_type'                 => $message->entityType,
            'is_commercial'               => ($message->isCommercial === 'true'),
            'business_as'                 => $message->businessAs,
            'business_entity_type_id'     => $message->businessEntityTypeId,
        ];

        $builder->set($data);
        $builder->where('client_id', $message->client_id);
        $builder->update();
        
        return $this->findOne($message->client_id);
    }
}
