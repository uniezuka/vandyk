<?php

namespace App\Services;

class FloodQuoteService extends BaseService
{
    protected $limit = 20;

    public function getPaged($page = 1)
    {
        $offset = ($page - 1) * $this->limit;

        $builder = $this->db->table('flood_quote');
        $builder->orderBy('flood_quote_id', 'DESC');

        $query = $builder->get($this->limit, $offset);

        $total = $builder->countAllResults();

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

        // $builder = $this->db->table('flood_quote');
        // $builder->select('*');
        // $builder->join('flood_quote_meta', 'flood_quote.flood_quote_id = flood_quote_meta.flood_quote_id', 'left');
        // $builder->groupStart();
        // $builder->like('last_name', $search_text, 'after');
        // $builder->orLike('first_name', $search_text, 'after');
        // $builder->orLike('address', $search_text, 'after');
        // $builder->orLike('company_name', $search_text, 'after');
        // $builder->orLike('company_name_2', $search_text, 'after');
        // $builder->groupEnd();

        // $builder->orderBy('flood_quote.flood_quote_id', 'DESC');

        // $total = $builder->countAllResults(false);
        // $builder->limit($this->limit);
        // $builder->offset($offset);

        // // $sql = $builder->getCompiledSelect();
        // // echo $sql;

        // $query = $builder->get();

        $builder = $this->db->table('flood_quote');
        $builder->select('flood_quote.*, flood_quote_meta.meta_value');
        $builder->join('flood_quote_meta', 'flood_quote.flood_quote_id = flood_quote_meta.flood_quote_id', 'left');

        $builder->groupStart();
        $builder->like('last_name', $search_text, 'after');
        $builder->orLike('first_name', $search_text, 'after');
        $builder->orLike('address', $search_text, 'after');
        $builder->orLike('company_name', $search_text, 'after');
        $builder->orLike('company_name_2', $search_text, 'after');
        $builder->orGroupStart();
        $builder->where('flood_quote_meta.meta_key', 'property_address');
        $builder->like('flood_quote_meta.meta_value', $search_text, 'after');
        $builder->groupEnd();
        $builder->groupEnd();

        $builder->orderBy('flood_quote.flood_quote_id', 'DESC');

        $total = $builder->countAllResults(false);
        $builder->limit($this->limit);
        $builder->offset($offset);

        // $sql = $builder->getCompiledSelect();
        // echo $sql;

        $query = $builder->get();

        // return null;

        return (object) array(
            'data'   => $query->getResult(),
            'total'  => $total,
            'page'   => $page,
            'limit'  => $this->limit,
            'offset' => $offset,
        );
    }

    public function getFloodQuoteMetas($flood_quote_id)
    {
        $builder = $this->db->table('flood_quote_meta');

        $builder->where('flood_quote_id', $flood_quote_id);

        $query = $builder->get();

        return $query->getResult();
    }

    public function getFloodQuoteMetaValue($flood_quote_id, $meta_key)
    {
        $builder = $this->db->table('flood_quote_meta');

        $builder->where(array('flood_quote_id' => $flood_quote_id, 'meta_key' => $meta_key));

        $query = $builder->get(1);

        $row = $query->getRow();

        return ($row) ? $row->meta_value : "";
    }

    public function getBatchedFloodQuoteMetas($flood_quote_ids)
    {
        if (count($flood_quote_ids) == 0) return [];

        $builder = $this->db->table('flood_quote_meta');

        $builder->whereIn('flood_quote_id', $flood_quote_ids);

        $query = $builder->get();

        return $query->getResult();
    }

    public function findOne($id)
    {
        $builder = $this->db->table('flood_quote');
        $builder->where('flood_quote_id', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }

    public function create(object $message)
    {
        $builder = $this->db->table('flood_quote');

        $data = [
            'client_id'          => $message->client_id,
            'first_name'         => $message->firstName,
            'last_name'          => $message->lastName,
            'insured_name_2'     => $message->secondInsured,
            'company_name'       => $message->companyName,
            'company_name_2'     => $message->companyName2,
            'entity_type'        => $message->entityType,
            'address'            => $message->address,
            'city'               => $message->city,
            'state'              => $message->state,
            'zip'                => $message->zip,
            'cell_phone'         => $message->cellPhone,
            'home_phone'         => $message->homePhone,
            'email'              => $message->email,
            'bill_to'            => $message->billTo,
            'effectivity_date'   => $message->effectiveDate,
            'expiration_date'    => $message->expirationDate,
            'date_entered'       => date('Y-m-d H:i:s'),
        ];

        $builder->insert($data);

        $flood_quote_id = $this->db->insertID();

        $excluded_keys = [
            'client_id', 'firstName', 'lastName', 'secondInsured', 'companyName', 'companyName2',
            'entityType', 'address', 'city', 'state', 'zip', 'cellPhone', 'homePhone', 'email',
            'billTo', 'effectiveDate', 'expirationDate'
        ];

        foreach ($excluded_keys as $key) {
            unset($message->{$key});
        }

        $this->insertMetaValues($message, $flood_quote_id);

        return $this->findOne($flood_quote_id);
    }

    private function insertMetaValues($message, $flood_quote_id)
    {
        $builder = $this->db->table('flood_quote_meta');

        foreach ($message as $key => $value) {
            $data = array(
                'flood_quote_id' => $flood_quote_id,
                'meta_key'       => $key,
                'meta_value'     => $value
            );
            $builder->insert($data);
        }
    }
}
