<?php

include_once  __DIR__ . ("/../src/Models/Model.php");
include_once  __DIR__ . ("/../src/Models/User.php");

class Session
{
	private $logged_in = false;
	public $user_id;
	public $userName;

	function _construct()
	{
		session_start();
		$this-check_login();
	}

	//Simple getter for login status
	public function is_logged_in()
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
			$this->logged_in = true;
		}
	}

	//instead of destroying session, unset session variables
	public function logout()
	{
		unset($_SESSION['user_id']);
		unset($this->user_id);
		unset($_SESSION['user_name']);
		unset($this->userName);
		$this->logged_in = false;
	}

	//verify user login
	private function check_login()
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
}

?>