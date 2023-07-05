<?php

namespace App\Services;
use App\Models\UserModel;

class BrokerService extends BaseService
{
    protected $limit = 20;

    public function getAll($page = 1)
    {
        $offset = ($page-1) * $this->limit;

        $builder = $this->db->table('broker');
        $builder->select('*');
        $builder->join('broker_login', 'broker.broker_id = broker_login.broker_id');
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
        $builder = $this->db->table('broker');

        $builder->insert($message);

        $id = $this->db->insertID();
        
        return $this->findOne($id); // TODO
    }

    public function findOne($id)// TODO
    {
        $builder = $this->db->table('broker');
        $builder->select('broker.broker_id, name, address, address2, city, state, zip, phone, fax, 
            broker_login_id, username, greetings, iianj_member, email, acct_lock, last_ip, 
            broker_role_id, role_id');
        $builder->join('broker_login', 'broker.broker_id = broker_login.broker_id', 'left');
        $builder->join('broker_role', 'broker.broker_id = broker_role.broker_id', 'left');
        $builder->where('broker.broker_id', $id);

        $query = $builder->get(1);

        $row = $query->getRow();
        
        return $row;
    }

    public function update(object $message)
    {
        $builder = $this->db->table('broker');

        $data = [
            'name'      => $message->name,
            'address'   => $message->address,
            'address2'  => $message->address2,
            'city'      => $message->city,
            'state'     => $message->state,
            'zip'       => $message->zip,
            'phone'     => $message->phone,
            'fax'       => $message->fax,
        ];
        
        $builder->set($data);
        $builder->where('broker_id', $message->broker_id);
        $builder->update();

        $this->updateBrokerLogin($message);

        $this->updateBrokerRole($message->broker_id, ($message->isAdmin === 'true') ? 1 : 2);
        
        return $this->findOne($message->broker_id);
    }

    private function updateBrokerLogin($message) {
        $userModel = new UserModel();
        $data = [
            'greetings'       => $message->greetings,
            'iianj_member'    => $message->iianj === 'true',
        ];
        $userModel->update($message->broker_login_id, $data);
    }

    private function updateBrokerRole($broker_id, $role_id) {
        $builder = $this->db->table('broker_role');

        $data = [ 'role_id' => $role_id ];
        
        $builder->set($data);
        $builder->where('broker_id', $broker_id);
        $builder->update();
    }
}
