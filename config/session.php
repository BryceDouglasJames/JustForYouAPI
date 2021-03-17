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
		session_start();
		$this->verify_login();
	}

	//Simple getter for login status
	public function check_login()
	{
		return $this->logged_in;
	}

	//Upon Login, Associate user sessions
	public function login($user)
	{
		if($user)
		{
			$this->user_id = $_SESSION['user_id'] = $user->id;
			$this->userName = $_SESSION['user_name'] = $user->username;
			$this->user_role = $_SESSION['role'] = $user->user_role;
			$this->logged_in = true;
		}
	}

	//instead of destroying session, unset session variables
	public function logout()
	{
		session_unset();
		$this->logged_in = false;
	}

	//verify user login
	private function verify_login()
	{
		if(isset($_SESSION['user_id']))
		{
			$this->user_id = $_SESSION['user_id'];
			$this->logged_in = true;
		}
		else
		{
			unset($this->user_id);
			$this->logged_in = false;
		}
	}


	//getter for session ID: returns current session ID
	public function get_SID()
	{
		verify_login();
		if(logged_in)
			return session_id();
		else
			return "";

	}
}

?>