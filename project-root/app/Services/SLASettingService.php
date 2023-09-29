<?php

namespace App\Services;

class SLASettingService extends BaseService
{
    protected $limit = 20;

    public function getPaged($page = 1)
    {
        $offset = ($page-1) * $this->limit;

        $builder = $this->db->table('sla_setting');
        $builder->select('*');
        $builder->orderBy('year', 'DESC');
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
        $builder = $this->db->table('sla_setting');

        $builder->insert($message);

        $id = $this->db->insertID();
        
        return $this->findOne($id);
    }

    public function findOne($id)
    {
        $builder = $this->db->table('sla_setting');
        $builder->where('sla_setting_id ', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }

    public function update(object $message)
    {
        $builder = $this->db->table('sla_setting');

        $data = [
            'year'          => $message->year,
            'prefix'        => $message->prefix,
        ];
        
        $builder->set($data);
        $builder->where('sla_setting_id', $message->sla_setting_id);
        $builder->update();
        
        return $this->findOne($message->sla_setting_id);
    }

    public function setCurrent($id)
    {
        $builder = $this->db->table('sla_setting');

        $data = [
            'is_current'          => false,
        ];
        
        $builder->set($data);
        $builder->where('is_current', true);
        $builder->update();

        $data = [
            'is_current'          => true,
        ];
        
        $builder->set($data);
        $builder->where('sla_setting_id', $id);
        $builder->update();
    }
}
