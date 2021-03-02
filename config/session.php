<?php

require_once "../src/models/Model.php";
require_once "../src/models/User.php";

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

	public function is_logged_in()
	{
		return $this->logged_in;
	}

	public function login($user)
	{
		if($user)
		{
			$this->user_id = $_SESSION['user_id'] = $user->id;
			$this->userName = $_SESSION['user_name'] = $user->username;
			$this->logged_in = true;
		}
	}

	public function logout()
	{
		unset($_SESSION['user_id']);
		unset($this->user_id);
		unset($_SESSION['user_name']);
		unset($this->userName);
		$this->logged_in = false;
	}

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