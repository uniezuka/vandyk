<?php

namespace App\Services;

use Exception;

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

        $subQuery = $this->db->table('fq_flood_quote_meta')
            ->select('DISTINCT flood_quote_id', false) // false to prevent backticks
            ->where('meta_key', 'property_address')
            ->like('meta_value', $search_text, 'after')
            ->getCompiledSelect();

        $builder = $this->db->table('fq_flood_quote fq')
            ->select('fq.*')
            ->join("($subQuery) fq_meta", 'fq.flood_quote_id = fq_meta.flood_quote_id', 'left')
            ->like('last_name', $search_text, 'after')
            ->orLike('first_name', $search_text, 'after')
            ->orLike('address', $search_text, 'after')
            ->orLike('company_name', $search_text, 'after')
            ->orLike('company_name_2', $search_text, 'after')
            ->orderBy('fq.flood_quote_id', 'DESC');

        $total = $builder->countAllResults(false);
        $builder->limit($this->limit);
        $builder->offset($offset);

        $query = $builder->get();

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
            'client_id',
            'firstName',
            'lastName',
            'secondInsured',
            'companyName',
            'companyName2',
            'entityType',
            'address',
            'city',
            'state',
            'zip',
            'cellPhone',
            'homePhone',
            'email',
            'billTo',
            'effectiveDate',
            'expirationDate'
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

    private function upsertMetaValues($message, $flood_quote_id)
    {
        foreach ($message as $key => $value) {
            $sql = "INSERT INTO fq_flood_quote_meta (flood_quote_id, meta_key, meta_value) 
                    VALUES (?, ?, ?)
                    ON DUPLICATE KEY UPDATE meta_value = VALUES(meta_value)";
            $this->db->query($sql, [$flood_quote_id, $key, $value]);
        }
    }

    public function getByClient($client_id, $is_quote_declined = false)
    {
        $subQuery = $this->db->table('fq_flood_quote_meta')
            ->select('DISTINCT flood_quote_id', false) // false to prevent backticks
            ->groupStart()
            ->where('meta_key', 'isQuoteDeclined')
            ->where('meta_value', $is_quote_declined)
            ->groupEnd()
            ->orGroupStart()
            ->where('meta_key IS NULL')
            ->groupEnd()
            ->getCompiledSelect();

        $builder = $this->db->table('fq_flood_quote fq')
            ->select('fq.*')
            ->join("($subQuery) fq_meta", 'fq.flood_quote_id = fq_meta.flood_quote_id', 'left')
            ->groupStart()
            ->where('fq_meta.flood_quote_id IS NOT NULL') // When isQuoteDeclined is false
            ->orWhere('fq.flood_quote_id NOT IN (SELECT flood_quote_id FROM fq_flood_quote_meta WHERE meta_key = "isQuoteDeclined")') // When isQuoteDeclined doesn't exist
            ->groupEnd()
            ->where('fq.client_id', $client_id)
            ->orderBy('fq.flood_quote_id', 'DESC')
            ->orderBy('fq.date_entered', 'DESC');

        $query = $builder->get();

        return $query->getResult();
    }

    public function update(object $message)
    {
        $builder = $this->db->table('flood_quote');

        $data = [
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
        ];

        $builder->set($data);
        $builder->where('flood_quote_id', $message->flood_quote_id);
        $builder->update();

        $flood_quote_id = $message->flood_quote_id;

        $excluded_keys = [
            'client_id',
            'flood_quote_id',
            'firstName',
            'lastName',
            'secondInsured',
            'companyName',
            'companyName2',
            'entityType',
            'address',
            'city',
            'state',
            'zip',
            'cellPhone',
            'homePhone',
            'email',
            'billTo',
            'effectiveDate',
            'expirationDate',
        ];

        foreach ($excluded_keys as $key) {
            unset($message->{$key});
        }

        $this->upsertMetaValues($message, $flood_quote_id);

        return $this->findOne($flood_quote_id);
    }

    public function bind(object $message)
    {
        $isBounded = $this->getFloodQuoteMetaValue($message->flood_quote_id, "isBounded");

        if ($isBounded == 1) throw new Exception("Flood Quote is already bounded!");

        $flood_quote_id = $message->flood_quote_id;

        $excluded_keys = [
            'flood_quote_id',
        ];

        foreach ($excluded_keys as $key) {
            unset($message->{$key});
        }

        $this->upsertMetaValues($message, $flood_quote_id);
    }

    public function updateSLANumber($slaNumber, $flood_quote_id)
    {
        $message = new \stdClass();
        $message->slaNumber = $slaNumber;
        $this->upsertMetaValues($message, $flood_quote_id);
    }

    public function cancelQuoteWithHiscox($message, $flood_quote_id)
    {
        $this->upsertMetaValues($message, $flood_quote_id);
    }
}
