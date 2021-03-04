<?php

require_once __DIR__ . '/Model.php';

class User extends Model
{
    public $username;
    public $password_hash;
    public $user_role;
    public $id;

    protected $private_fields = ["password_hash"];

    private static $secret_key = 'secret_key';

    public function __construct()
    {
        parent::__construct();
    }

    /*public function getByUsername($username){
        return self::getByField("username", $username);
    }*/

    public function verifyPassword($password){
        //return password_verify($password, $this->password_hash);
        return true;
    }
    
}
