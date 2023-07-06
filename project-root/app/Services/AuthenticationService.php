<?php

namespace App\Services;

use App\Models\UserModel;
use Exception;

class AuthenticationService extends BaseService
{
    public function authenticate($username, $password)
    {
        $session = session();
        
        $userModel = new UserModel();
 
        $user = $userModel->where('username', $username)->first();
 
        if(is_null($user)) {
            throw new Exception("Invalid username or password.");
        }
 
        $pwd_verify = password_verify($password, $user['password']);
 
        if(!$pwd_verify) {
            throw new Exception("Invalid username or password.");
        }

        $builder = $this->db->table('broker_role');
        $builder->select('*');
        $builder->join('role', 'broker_role.role_id = role.role_id');
        $builder->where('broker_id', $user['broker_id']);
        $query = $builder->get();

        $role = null;

        foreach ($query->getResult() as $row) {
            $role = (object) array(
                'role_id'   => $row->role_id,
                'name'  => $row->name,
            );
        }
 
        $session_data = [
            'id' => $user['broker_id'],
            'email' => $user['email'],
            'username' => $user['username'],
            'isLoggedIn' => TRUE,
            'isAdmin' => ($role === null) ? FALSE : $role->role_id == 1,
        ];
 
        $session->set($session_data);

        return true;
    }

    public function register(object $message)
    {
        $userModel = new UserModel();
        
        $this->validateUserRegistration($userModel, $message);
        
        $userId = $userModel->insert([
            'username'        => $message->username,
            'email'           => $message->email,
            'greetings'       => $message->greetings,
            'iianj_member'    => $message->iianj === 'true',
            'password'        => password_hash($message->password, PASSWORD_DEFAULT),
        ]);

        if ($userId) {
            $brokerService = service('brokerService');

            $data = [
                'name'          => $message->name,
                'address'       => $message->address,
                'address2'      => $message->address2,
                'city'          => $message->city,
                'state'         => $message->state,
                'zip'           => $message->zip,
                'phone'         => $message->phone,
                'fax'           => $message->fax,
            ];

            $broker = $brokerService->create((object) $data);

            $userModel->save([
                'broker_login_id' => $userId,
                'broker_id'       => $broker->broker_id
            ]);

            $this->insertBrokerRole($broker->broker_id, ($message->isAdmin === 'true') ? 1 : 2);
        }

        return true;
    }

    public function updatePassword(object $message) {
        $userModel = new UserModel();

        $data = [
            'password' => password_hash($message->newPassword, PASSWORD_DEFAULT)
        ];

        $userModel->update($message->broker_login_id, $data);
    }

    private function insertBrokerRole($broker_id, $role_id) {
        $builder = $this->db->table('broker_role');

        $builder->insert([
            'broker_id' => $broker_id,
            'role_id'   => $role_id
        ]);
    }

    private function validateUserRegistration(UserModel $userModel, object $message) {
        $user = $userModel->where('username', $message->username)->first();

        if(! is_null($user)) {
            throw new Exception("Username already exists.");
        }

        $user = $userModel->where('email', $message->email)->first();

        if(! is_null($user)) {
            throw new Exception("Email is already in use."); 
        }
    }
}
