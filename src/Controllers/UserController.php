<?php

include_once __DIR__ .'/../Models/User.php';

class UserController
{
    protected $db;

    public function __construct($con){
        User::useConnection($con);
    }

    public function getAllUsers(){
        $response = User::getAllUserData();
        return json_encode($response);
    }


    public function addUser($name, $email, $pass){
        $cols = array("name", "email", "pass");
        $vals = array($name, $email, $pass);
        $response = User::insert($cols, $vals);
        return json_encode($response);
    }

    public function getById($ID){
        
    }

    public function getCurrent($payload){
        $user = User::getByUsername($payload['username']);
        return $user;
    }

    public function createUser($payload){

    }

    //must configure model/user relation properly
    public function authenticate($payload){

        $user = User::getByUsername($payload['username']);
        $verify = User::verifyPassword($payload['password']);
        if(!$verify){
            throw new HttpForbiddenException($payload, "Invalid username or password.");
        }
        return json_encode($user);
    }

    public function delete($id){
        $response = User::deleteById($id);
        return json_encode($response);
    }

    public function create(){

    }

    public function setCurrentTable($thisTable){
        User::setTable($thisTable);
    }
}

?>