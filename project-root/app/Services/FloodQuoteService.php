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
        $builder = $this->db->table('flood_quote_meta');

        $builder->whereIn('flood_quote_id', $flood_quote_ids);

        $query = $builder->get();

        return $query->getResult();
    }
}
