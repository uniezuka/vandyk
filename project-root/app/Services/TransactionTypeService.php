<?php

namespace App\Services;

class TransactionTypeService extends BaseService
{
    protected $limit = 20;

    public function getPaged($page = 1)
    {
        $offset = ($page-1) * $this->limit;

        $builder = $this->db->table('transaction_type');
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
        $builder = $this->db->table('transaction_type');

        $data = [
            'name'                  => $message->name,
        ];

        $builder->insert($data);

        $id = $this->db->insertID();

        return $this->findOne($id);
    }

    public function findOne($id)
    {
        $builder = $this->db->table('transaction_type');
        $builder->where('transaction_type_id', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }

    public function update(object $message)
    {
        $builder = $this->db->table('transaction_type');

        $data = [
            'name'                  => $message->name,
        ];

        $builder->set($data);
        $builder->where('transaction_type_id', $message->transaction_type_id);
        $builder->update();
        
        return $this->findOne($message->transaction_type_id);
    }

    public function getAll()
    {
        $builder = $this->db->table('transaction_type');
        $builder->select('*');

        $query = $builder->get();

        return $query->getResult();
    }
}
