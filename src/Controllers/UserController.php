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
            $cols = array("name", "email", "password", "newuser");
            $vals = array($payload['username'], $payload['email'], $payload['password'], true);
            $response = User::insert($cols, $vals);

            return true;
        }
    }

    public function createNewUserInfo($payload){
        $fieldArray = array();
        $valueArray = array();

        $UID = User::getID($payload['username']);
        array_push($fieldArray, "UID");
        array_push($valueArray, $UID);

        foreach ($payload as $key => $value) {
            if($key !== "username"){
                array_push($fieldArray, $key);
                array_push($valueArray, $value);
            }
        }

        User::insert($fieldArray, $valueArray);

        User::setTable("usertable");
        User::update(["NewUser"], [0], $UID);

        return true;
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
        $returnArray = array();
        $user = User::getByUsername($payload['username']);
        if(sizeof($user) == 0) {return false;}

        if($user[0]["NewUser"] == true){
            array_push($returnArray, true);
            array_push($returnArray, true);
        }else{
            array_push($returnArray, true);
            array_push($returnArray, false);
        }
        //$verify = User::verifyPassword($payload['password']);
        //if(!$verify) {return false;}            
        return $returnArray;
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