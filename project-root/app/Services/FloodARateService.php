<?php

namespace App\Services;

class FloodARateService extends BaseService
{
    protected $limit = 20;

    public function findOne($id)
    {
        $builder = $this->db->table('flood_a_rate');
        $builder->where('flood_a_rate_id ', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }

    public function getAll()
    {
        $builder = $this->db->table('flood_a_rate');
        $builder->select('*');

        $query = $builder->get();

        return $query->getResult();
    }

    public function create(object $message)
    {
        $builder = $this->db->table('flood_a_rate');

        $data = [
            'description' => $message->description,
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
        $builder = $this->db->table('flood_a_rate');

        $data = [
            'description' => $message->description,
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
        $builder->where('flood_a_rate_id', $message->flood_a_rate_id);
        $builder->update();

        return $this->findOne($message->flood_a_rate_id);
    }

    public function upsertFloodFoundation($flood_a_rate_id, $flood_foundation_id)
    {
        $builder = $this->db->table('flood_a_rate_flood_foundation');
        $builder->where('flood_a_rate_id', $flood_a_rate_id);
        $builder->where('flood_foundation_id', $flood_foundation_id);
        $existingRecord = $builder->get()->getRow();

        if (!$existingRecord) {
            $data = [
                'flood_a_rate_id' => $flood_a_rate_id,
                'flood_foundation_id' => $flood_foundation_id
            ];

            $builder->insert($data);
        }
    }

    public function getFloodFoundations($flood_a_rate_id)
    {
        $builder = $this->db->table('flood_a_rate_flood_foundation');
        $builder->select('flood_foundation.name, flood_foundation.flood_foundation_id');
        $builder->join('flood_foundation', 'flood_a_rate_flood_foundation.flood_foundation_id = flood_foundation.flood_foundation_id');
        $builder->where('flood_a_rate_flood_foundation.flood_a_rate_id', $flood_a_rate_id);

        return $builder->get()->getResultArray();
    }

    public function deleteFloodFoundations($flood_a_rate_id)
    {
        $builder = $this->db->table('flood_a_rate_flood_foundation');
        $builder->where('flood_a_rate_id', $flood_a_rate_id)->delete();
    }

    public function getFloodARateByFoundation($flood_foundation_id)
    {
        $builder = $this->db->table('flood_a_rate');
        $builder->select('flood_a_rate.*');
        $builder->join('flood_a_rate_flood_foundation', 'flood_a_rate.flood_a_rate_id = flood_a_rate_flood_foundation.flood_a_rate_id');
        $builder->where('flood_a_rate_flood_foundation.flood_foundation_id', $flood_foundation_id);

        $query = $builder->get();
        return $query->getResultArray();
    }
}
