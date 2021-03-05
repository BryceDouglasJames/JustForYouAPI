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


    public function addUser($payload){
        if ($payload['username'] === "" || $payload['username'] === "undefined") {
            //throw new Error("Username cannot be empty");
            return false;
        }
        else if ($payload['email'] === "" || $payload['email'] === "undefined") {
            //throw new Error("Email cannot be empty");
            return false;
        }
        else if ($payload['password'] === "" || $payload['password'] === "undefined") {
            //throw new Error("Password cannot be empty");
            return false;
        }else{
            $cols = array("name", "email", "pass");
            $vals = array($payload['username'], $payload['email'], $payload['password']);
            $response = User::insert($cols, $vals);

            return true;
        }
        /*if(!$response){
            throw new Error("Trouble adding user to table");
            return false;
        }else{
            return true;
        }*/
    }


    public function getCurrent($payload){
        $user = User::getByUsername($payload['username']);
        if(!$thisUser){ 
            throw new Error("Trouble finding user");
        }else{
            return $thisUser;
        }
        return $thisUser;
    }


    public function authenticate($payload){
        $user = User::getByUsername($payload['username']);
        if(!$user) {return false;}
        $verify = User::verifyPassword($payload['password']);
        if(!$verify) {return false;}            
        return true;
    }

    public function delete($id){
        $response = User::deleteById($id);
        return json_encode($response);
    }

    public function setCurrentTable($thisTable){
        User::setTable($thisTable);
    }
}

?>