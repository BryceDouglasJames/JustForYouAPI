<?php

include_once __DIR__ .'/../Models/User.php';
include_once __DIR__ .'/../../config/session.php';


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
            $password = User::hashPassword($payload['password']);
            $cols = array("name", "email", "password", "newuser", );
            $vals = array($payload['username'], $payload['email'], $password, true);
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

    public function setPFP($payload){
        $user = User::getByUsername($payload['username']);
        if(!$user || $user[0]["UID"] != $payload["session_id"]){
            throw new error("User does not have permission with credentials");
            return false;
        }else{
            self::setCurrentTable("userdata");
            $pfp  = $payload["file"];
            $cols = array("ProfilePic");
            $vals = array($pfp);
            User::update($cols, $vals, base64_encode($payload['username']));
            return true;
        }
        
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