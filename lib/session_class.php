<?php
/*
 * 		Session class definition
 */


class Session {
	private $login_status = false;								// login_status is used to check if someone is currently logged in
	private $user_id;											// id is used to know who is logged in
		
	public function __construct() {
		session_start();										// start session to access $_SESSION variables
		$this->check_login();									// call the check_login() method to verify if someone is logged in when $sess is instantiated.
	}
	
	private function check_login() {
		if(isset($_SESSION['user_id'])) {						// $_SESSION is set when someone logs in, so if this is set, then user_id & login_status need to reflect that
			$this->user_id = $_SESSION['user_id'];
			$this->login_status = true;
		}
		else {
			unset($this->user_id);								//  else user_id should not be set and login_status needs to be false
			$this->login_status = false;
		}
	}
	
	public function getLoginStatus() {
		return $this->login_status;								// getter for login_status (either true or false)
	}
	
	public function getID() {									// getter for user_id 
		return $this->user_id;
	}
	
	public function login($id) {								//  will set user_id, login_status and $_SESSION variables
		$this->user_id = $_SESSION['user_id'] = $id;
		$this->login_status = true;
	}
	
	public function logout() {									// on logout, unset user_id, set login_status to false and destroy session variables
		unset ( $this->user_id );
		unset ( $_SESSION['user_id']);
		session_destroy();
		$this->login_status = false;
	}		
}

$sess = new Session;
?>
