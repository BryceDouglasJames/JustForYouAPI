<?php

include_once __DIR__ .'/../Models/User.php';
include_once __DIR__ .'/../../config/session.php';


class UserController
{
    protected $db;

    public function __construct($con){
        User::useConnection($con);
    }

    /*
    *  ADMIN FUNCTION
    *  RETURN ALL USERS AT A LIMIT OF 25
    */
    public function getAllUsers(){
        $response = User::getAllUserData();
        return json_encode($response);
    }


    /*
    *  CHECKS REQUEST PAYLOAD THAT CONTAINS USER SIGNUP INFO AND STORES USER INTO TABLE
    *  IF EVERYTHING IS OKAY
    */
    public function addUser($payload){
        if ($payload['username'] === "" || $payload['username'] === "undefined") {
            return false;
        }
        else if ($payload['email'] === "" || $payload['email'] === "undefined") {
            return false;
        }
        else if ($payload['password'] === "" || $payload['password'] === "undefined") {
            return false;
        }else{
            $password = User::hashPassword($payload['password']);
            $cols = array("name", "email", "password", "newuser", );
            $vals = array($payload['username'], $payload['email'], $password, true);
            $response = User::insert($cols, $vals);
            
            /*
            *   WE IDENTIFY EACH USER INTO THE WEEKLY SCORE TABLE BY HAVING 
            *   SELECTED CATEGORY FOLLOWED BY THEIR ENCODED USERNAME
            * 
            *   EXAMPLE: DIET_(ENCODED USERNAME)
            *            FITNESS_(ENCODED USERNAME)
            *
            *   THAT WAY WE CAN DITINGUESH USERS EASIER AND HAVE LESS COLS
            */
            $encryptID = base64_encode($payload['username']);
            $user = User::getByUsername($payload['username']);
            $UID = $user[0]["UID"];
            User::setTable("weeklyscores");
            $cols = array("SCOREID", "UID", 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
            $val1 = array("Diet_".$encryptID, $UID, 0,0,0,0,0,0,0);
            $response = User::insert($cols, $val1);
            $val2 = array("Fitness_".$encryptID, $UID, 0,0,0,0,0,0,0);
            $response = User::insert($cols, $val2);
            $val3 = array("Mental_".$encryptID, $UID, 0,0,0,0,0,0,0);
            $response = User::insert($cols, $val3);
            $val4 = array("Personal_".$encryptID, $UID, 0,0,0,0,0,0,0);
            $response = User::insert($cols, $val4);
            return true;
        }
    }


    /*
    *   GET USER INFO AND RETURN SIMPLE INFO TO BE DISPLAYED ON USER PROFILE
    */
    public function getBasicUserInfo($payload){
        $returnArray = array();
        $userData = User::getUserProfileInfo($payload["username"]);

        $current = strtotime(date("Y-m-d"));
        $DOB =  strtotime($userData[0]["DOB"]);

        $age = floor( ($current - $DOB) / 31536000 );
        $weight = (int)$userData[0]["Weight"];
        $height = (float)$userData[0]["Height"];
        $activity = $userData[0]["Activity"];
        $BMI = $userData[0]["BMI"];

        $returnArray = array($age, $weight, $height, $activity, $BMI);
        return $returnArray;
    }


    /*
    *   GET USER INFO AND UPDATE DELECTED FIELDS
    */
    public function updateBasicUserInfo($payload){
        $cols = array("Weight", "Height", "BMI", "Activity");
        $vals = array();
        $returnArray = array();

        $userData = User::getUserProfileInfo($payload["username"]);

        $username = $userData[0]["DATA_ID"];
        $weight = $payload["Weight"];
        $height = $payload["Height"];
        $activity = $payload["Activity"];
        $BMI = round(703 * ($weight) / ($height**2), 1);
        $vals = array($weight, $height, $BMI, "'".$activity."'");

        User::setTable("userdata");
        
        $answer = User::update($cols, $vals, "'".$username."'");
        if($answer){
            return true;
        }
    }

    /*
    *   INGEST REQUEST PAYLOAD AND CREATE PROFILE INSIDE OF USER ACCESS TABLE 
    */
    public function createNewUserInfo($payload){
        $fieldArray = array();
        $valueArray = array();
        $weight = 0;
        $height = 0;
        $UID = User::getID($payload['username']);
        array_push($fieldArray, "UID");
        array_push($valueArray, $UID);
        foreach ($payload as $key => $value) {
            if($key !== "username"){
                array_push($fieldArray, $key);
                array_push($valueArray, $value);
            }
            if($key == "Weight"){
                $weight = $payload[$key];
            }
            if($key == "Height"){
                $height = $payload[$key];
            }
        }
        User::insert($fieldArray, $valueArray);
        User::setTable("usertable");
        User::update(["NewUser"], [0], $UID);

        User::setTable("userdata");
        $BMI = round(703 * ($weight) / ($height**2), 1);
        User::update(["BMI"], [$BMI], "'".base64_encode($payload["username"])."'");
        return true;
    }

    /*
    *  RETURNS CURRENT USER
    */    
    public function getCurrent($payload){
        $user = User::getByUsername($payload['username']);
        if(!$thisUser){ 
            throw new Error("Trouble finding user");
        }else{
            return $thisUser;
        }
        return $thisUser;
    }

    /*
    *  TODO
    *  ONCE IMAGE STORAGE ANGENT IS ESTABLISHED, HANDLE PROFILE PICTURE STORAGE
    */
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

    /*
    *  DELETE USER BY ID
    */    
    public function delete($id){
        $response = User::deleteById($id);
        return json_encode($response);
    }

    /*
    *  TODO
    *   OH MY GOD, FIX THIS!
    *   WHY DOES EVERY INDEX REDIRECT USE THIS TO DECLARE INSTANCE DB TABLE REFERENCE?
    *   PLZ FIX THIS
    */    
    public function setCurrentTable($thisTable){
        User::setTable($thisTable);
    }
}

?>