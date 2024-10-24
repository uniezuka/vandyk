<?php

namespace App\Services;

class FloodBCXRateService extends BaseService
{
    protected $limit = 20;

    public function findOne($id)
    {
        $builder = $this->db->table('flood_bcx_rate');
        $builder->where('flood_bcx_rate_id ', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }

    public function getAll()
    {
        $builder = $this->db->table('flood_bcx_rate');
        $builder->select('*');

        $query = $builder->get();

        return $query->getResult();
    }

    public function create(object $message)
    {
        $builder = $this->db->table('flood_bcx_rate');

        $data = [
            'description' => $message->description,
            'rate' => $message->rate,
        ];

        $builder->insert($data);

        $id = $this->db->insertID();

        return $this->findOne($id);
    }

    public function update(object $message)
    {
        $builder = $this->db->table('flood_bcx_rate');

        $data = [
            'description' => $message->description,
            'rate' => $message->rate,
        ];
        $builder->set($data);
        $builder->where('flood_bcx_rate_id', $message->flood_bcx_rate_id);
        $builder->update();

        return $this->findOne($message->flood_bcx_rate_id);
    }

    public function upsertFloodFoundation($message)
    {
        $builder = $this->db->table('flood_bcx_rate_flood_foundation');
        $builder->where('flood_bcx_rate_id', $message->flood_bcx_rate_id);
        $builder->where('flood_foundation_id', $message->flood_foundation_id);
        $existingRecord = $builder->get()->getRow();

        if (!$existingRecord) {
            $data = [
                'flood_bcx_rate_id' => $message->flood_bcx_rate_id,
                'flood_foundation_id' => $message->flood_foundation_id,
                'num_of_floors' => $message->numOfFloors,
                'is_more_than_equal' => $message->isMoreThanEqual
            ];

            $builder->insert($data);
        }
    }

    public function getFloodFoundations($flood_bcx_rate_id)
    {
        $builder = $this->db->table('flood_bcx_rate_flood_foundation');
        $builder->select('flood_foundation.name, flood_foundation.flood_foundation_id, flood_bcx_rate_flood_foundation.num_of_floors, flood_bcx_rate_flood_foundation.is_more_than_equal');
        $builder->join('flood_foundation', 'flood_bcx_rate_flood_foundation.flood_foundation_id = flood_foundation.flood_foundation_id');
        $builder->where('flood_bcx_rate_flood_foundation.flood_bcx_rate_id', $flood_bcx_rate_id);

        return $builder->get()->getResultArray();
    }

    public function getFloodBCXRateByFoundation($flood_foundation_id, $num_of_floors)
    {
        $builder = $this->db->table('flood_bcx_rate');
        $builder->select('flood_bcx_rate.*');
        $builder->join('flood_bcx_rate_flood_foundation', 'flood_bcx_rate.flood_bcx_rate_id = flood_bcx_rate_flood_foundation.flood_bcx_rate_id');
        $builder->where('flood_bcx_rate_flood_foundation.flood_foundation_id', $flood_foundation_id);
        $builder->groupStart()
            ->where('flood_bcx_rate_flood_foundation.num_of_floors', $num_of_floors)
            ->orGroupStart()
            ->where('flood_bcx_rate_flood_foundation.num_of_floors >=', $num_of_floors)
            ->where('flood_bcx_rate_flood_foundation.is_more_than_equal', 1)
            ->groupEnd()
            ->groupEnd();

        $query = $builder->get();
        return $query->getResultArray();
    }
}
