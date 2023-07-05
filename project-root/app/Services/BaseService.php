<?php

namespace App\Services;

abstract class BaseService
{
    protected $db;

    public function __construct()
    {
        $this->db = db_connect();
    }
}
