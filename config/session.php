<?php

include_once  __DIR__ . ("/../src/Models/Model.php");
include_once  __DIR__ . ("/../src/Models/User.php");

class Session
{
	private $logged_in = false;
	public $user_id;
	public $userName;
	public $user_role;
	
	//creates a session when an instance of this class is created
	function _construct()
	{	
		//$this->verify_login();
	}

	//Simple getter for login status
	public function check_login()
	{
		return $this->logged_in;
	}

	public function login($payload){
        $user = array();
        $returnArray = array();
        $user = User::getByUsername($payload['username']);
        $password = User::verifyPassword($payload["password"]);
        if($user = $payload['username'] && sizeof($password) != 0){
            $UID = User::getID($payload['username']);
			User::recordLogin($UID);
            return $UID;
        }else{
			
            return false;
        }
	}

	public function logout($payload){
        $user = array();
        $returnArray = array();
        $user = User::getByUsername($payload['username']);
        if($user = $payload['username']){
            $UID = User::getID($payload['username']);
			User::recordLogout($UID);
			return true;
        }else{
            return false;
        }
	}

	//ROUGH AUTHENTICATION> NEEDS TO BE MANAGED
    public function authenticate($payload){
        $user = array();
        $returnArray = array();
        $user = User::getByUsername($payload['username']);
        $session_id = User::getId($payload["username"]);
        
        if($user[0]["NewUser"] == true){
            array_push($returnArray, true);
			array_push($returnArray, true);
			return $returnArray;
		} 
		
		if($user[0]["Name"] == $payload["username"] && $session_id == $payload["session_id"]){
            array_push($returnArray, true);
			array_push($returnArray, false);
			return $returnArray;
		}   

		if(!$user[0]["Name"] || $user[0]["Name"] != $payload["username"] || $session_id != $payload["session_id"]){
            array_push($returnArray, false);
			array_push($returnArray, false);
			return $returnArray;
        }
        
        
    }

	//Upon Login, Associate user sessions
	public function addSession($user)
	{
		
	}

	//instead of destroying session, unset session variables
	public function endSession($data)
	{

	}

	//verify user login
	public function verify_session($user)
	{

	}


	//getter for session ID: returns current session ID
	public function get_SID()
	{

	}
}

?>