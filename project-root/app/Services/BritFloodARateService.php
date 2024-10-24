<?php

namespace App\Services;

class BritFloodARateService extends BaseService
{
    protected $limit = 20;

    public function findOne($id)
    {
        $builder = $this->db->table('brit_flood_a_rate');
        $builder->where('brit_flood_a_rate_id ', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }

    public function getAll()
    {
        $builder = $this->db->table('brit_flood_a_rate');
        $builder->select('brit_flood_a_rate.*, county.name as county_name');
        $builder->join('county', 'brit_flood_a_rate.county_id = county.county_id', 'left');

        $query = $builder->get();

        return $query->getResult();
    }

    public function create(object $message)
    {
        $builder = $this->db->table('brit_flood_a_rate');

        $data = [
            'description' => $message->description,
            'zip' => $message->zip,
            'state_code' => $message->state_code,
            'county_id' => $message->county_id,
            'dwl4' => $message->dwl4,
            'cont4' => $message->cont4,
            'both4' => $message->both4,
            'dwl3' => $message->dwl3,
            'cont3' => $message->cont3,
            'both3' => $message->both3,
            'dwl2' => $message->dwl2,
            'cont2' => $message->cont2,
            'both2' => $message->both2,
            'dwl1' => $message->dwl1,
            'cont1' => $message->cont1,
            'both1' => $message->both1,
            'dwl0' => $message->dwl0,
            'cont0' => $message->cont0,
            'both0' => $message->both0,
            'dwl-1' => $message->dwl_1,
            'cont-1' => $message->cont_1,
            'both-1' => $message->both_1,
        ];

        $builder->insert($data);

        $id = $this->db->insertID();

        return $this->findOne($id);
    }

    public function update(object $message)
    {
        $builder = $this->db->table('brit_flood_a_rate');

        $data = [
            'description' => $message->description,
            'zip' => $message->zip,
            'state_code' => $message->state_code,
            'county_id' => $message->county_id,
            'dwl4' => $message->dwl4,
            'cont4' => $message->cont4,
            'both4' => $message->both4,
            'dwl3' => $message->dwl3,
            'cont3' => $message->cont3,
            'both3' => $message->both3,
            'dwl2' => $message->dwl2,
            'cont2' => $message->cont2,
            'both2' => $message->both2,
            'dwl1' => $message->dwl1,
            'cont1' => $message->cont1,
            'both1' => $message->both1,
            'dwl0' => $message->dwl0,
            'cont0' => $message->cont0,
            'both0' => $message->both0,
            'dwl-1' => $message->dwl_1,
            'cont-1' => $message->cont_1,
            'both-1' => $message->both_1,
        ];
        $builder->set($data);
        $builder->where('brit_flood_a_rate_id', $message->brit_flood_a_rate_id);
        $builder->update();

        return $this->findOne($message->brit_flood_a_rate_id);
    }

    public function upsertFloodFoundation($brit_flood_a_rate_id, $flood_foundation_id)
    {
        $builder = $this->db->table('brit_flood_a_rate_flood_foundation');
        $builder->where('brit_flood_a_rate_id', $brit_flood_a_rate_id);
        $builder->where('flood_foundation_id', $flood_foundation_id);
        $existingRecord = $builder->get()->getRow();

        if (!$existingRecord) {
            $data = [
                'brit_flood_a_rate_id' => $brit_flood_a_rate_id,
                'flood_foundation_id' => $flood_foundation_id
            ];

            $builder->insert($data);
        }
    }

    public function getFloodFoundations($brit_flood_a_rate_id)
    {
        $builder = $this->db->table('brit_flood_a_rate_flood_foundation');
        $builder->select('flood_foundation.name, flood_foundation.flood_foundation_id');
        $builder->join('flood_foundation', 'brit_flood_a_rate_flood_foundation.flood_foundation_id = flood_foundation.flood_foundation_id');
        $builder->where('brit_flood_a_rate_flood_foundation.brit_flood_a_rate_id', $brit_flood_a_rate_id);

        return $builder->get()->getResultArray();
    }

    public function deleteFloodFoundations($brit_flood_a_rate_id)
    {
        $builder = $this->db->table('brit_flood_a_rate_flood_foundation');
        $builder->where('brit_flood_a_rate_id', $brit_flood_a_rate_id)->delete();
    }

    public function getBritFloodARateByFoundation($flood_foundation_id)
    {
        $builder = $this->db->table('brit_flood_a_rate');
        $builder->select('brit_flood_a_rate.*');
        $builder->join('brit_flood_a_rate_flood_foundation', 'brit_flood_a_rate.brit_flood_a_rate_id = brit_flood_a_rate_flood_foundation.brit_flood_a_rate_id');
        $builder->where('brit_flood_a_rate_flood_foundation.flood_foundation_id', $flood_foundation_id);

        $query = $builder->get();
        return $query->getResultArray();
    }
}
