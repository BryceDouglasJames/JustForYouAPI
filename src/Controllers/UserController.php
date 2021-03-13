<?php

include_once __DIR__ .'/../Models/User.php';

class UserController
{
    protected $db;

    public function __construct($con){
        User::useConnection($con);
    }

    //ADMIN FUNCTION return all users at a limit of 25
    public function getAllUsers(){
        $response = User::getAllUserData();
        return json_encode($response);
    }


    //checks request payload that contains user signup info and stores if okay
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


    //returns current user
    public function getCurrent($payload){
        $user = User::getByUsername($payload['username']);
        if(!$thisUser){ 
            throw new Error("Trouble finding user");
        }else{
            return $thisUser;
        }
        return $thisUser;
    }


    //ROUGH AUTHENTICATION> NEEDS TO BE MANAGED
    public function authenticate($payload){
        $user = array();
        $user = User::getByUsername($payload['username']);
        if(sizeof($user) == 0) {return false;}
        //$verify = User::verifyPassword($payload['password']);
        //if(!$verify) {return false;}            
        return true;
    }

    //Delete user by id
    public function delete($id){
        $response = User::deleteById($id);
        return json_encode($response);
    }

    //sets instance table
    public function setCurrentTable($thisTable){
        User::setTable($thisTable);
    }
}

?>