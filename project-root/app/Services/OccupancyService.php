<?php

namespace App\Services;

class OccupancyService extends BaseService
{
    protected $limit = 20;

    public function getPaged($page = 1)
    {
        $offset = ($page-1) * $this->limit;

        $builder = $this->db->table('occupancy');
        $builder->select('*');
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

    public function create(object $message)
    {
        $builder = $this->db->table('occupancy');

        $data = [
            'label'                  => $message->label,
            'value'                  => $message->value,
        ];

        $builder->insert($data);

        $id = $this->db->insertID();

        return $this->findOne($id);
    }

    public function findOne($id)
    {
        $builder = $this->db->table('occupancy');
        $builder->where('occupancy_id', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }

    public function update(object $message)
    {
        $builder = $this->db->table('occupancy');

        $data = [
            'label'                  => $message->label,
            'value'                  => $message->value,
        ];

        $builder->set($data);
        $builder->where('occupancy_id', $message->occupancy_id);
        $builder->update();
        
        return $this->findOne($message->coccupancy_id);
    }

    public function getAll()
    {
        $builder = $this->db->table('occupancy');
        $builder->select('*');

        $query = $builder->get();

        return $query->getResult();
    }
}
