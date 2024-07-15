<?php

namespace App\Services;

class FloodQuoteMortgageService extends BaseService
{
    protected $limit = 20;

    public function create(object $message)
    {
        $builder = $this->db->table('flood_quote_mortgage');

        $data = [
            'flood_quote_id'                  => $message->flood_quote_id,
            'loan_index'                  => $message->loan_index,
            'loan_number'      => $message->loan_number,
            'name'      => $message->name,
            'name2'      => $message->name2,
            'address'      => $message->address,
            'city'      => $message->city,
            'state'      => $message->state,
            'zip'      => $message->zip,
            'phone'      => $message->phone,
        ];

        $builder->insert($data);

        $id = $this->db->insertID();

        return $this->findOne($id);
    }

    public function findOne($id)
    {
        $builder = $this->db->table('flood_quote_mortgage');
        $builder->where('flood_quote_mortgage_id', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }

    public function update(object $message)
    {
        $builder = $this->db->table('coverage');

        $data = [
            'loan_number'      => $message->loan_number,
            'name'      => $message->name,
            'name2'      => $message->name2,
            'address'      => $message->address,
            'city'      => $message->city,
            'state'      => $message->state,
            'zip'      => $message->zip,
            'phone'      => $message->phone,
        ];

        $builder->set($data);
        $builder->where('flood_quote_mortgage_id', $message->flood_quote_mortgage_id);
        $builder->update();

        return $this->findOne($message->flood_quote_mortgage_id);
    }
}
