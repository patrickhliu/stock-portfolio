<?php
/*
 * 		Class definition for Register, handles the database logic for when a user wants to register a new account
 * 
 */

class Register {
	public  $error_message;
	public  $good_message;
	private $table_name = 'users';
	
//	The constructor takes $_POST array containing user input from registration form & a $db object that was instantiated from registration page.	
	public function __construct ($post, $db) {										
		
		foreach($post as $index=>$value) {											// escape any html tags that are input by user & trim off lead/trailing whitespace
			$post[$index] = trim(  htmlspecialchars( $post[$index] )  );
		}
		
		if ( isset($post['submit']) ) {												// remove 'submit' variable from $post array, it's not needed at this point
			unset($post['submit']);
		}	
		
// If all 3 functions return true, user's been added succesfully.  Return good message to tell user registration was a success.
// If any function fails, return bad message indicating registration failed.		
		if( $this->does_form_validate($post) AND $this->does_user_exist($post, $db) AND $this->insert_user($post, $db) ) {			
			$this->good_message = "Thank You! <br> Your account has been registered !";
			return $this->good_message;
		}		
		else {
			return $this->error_message;
		}		
	}

//	This function verifies user input 1) no empty fields 2) two passwords match 3) correct password format 4) correct email format.
	public function does_form_validate($post) {
		if(  fieldsEmpty($post)  ) {
			$this->error_message = "ERROR: <br> One or more fields are blank.<br> Please try again...";
			return false;
		}
		else if (  $post['password'] != $post['password2'] ) {
			$this->error_message = "ERROR: <br> The two passwords do not match. <br>Please try again...";
			return false;
		}
		else if (  !(preg_match("/[A-Z+]/", $post['password'])) OR !(preg_match("/[a-z+]/", $post['password'])) OR 
				   !(preg_match("/[0-9+]/", $post['password'])) OR (strlen($post['password']) < 6) OR 
				   (preg_match("/\s/", $post['password'])) ) {
			$this->error_message = "ERROR: <br>  The format of the password is wrong. <br>Please try again...";
			return false;
		}
		else if (  !(preg_match("/^[a-zA-Z0-9._\-+]+@[a-zA-Z0-9]+\.[a-zA-Z]+$/", $post['email']))  AND 
				   !(preg_match("/^[a-zA-Z0-9._\-+]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+\.[a-zA-Z]+$/", $post['email']))  ) {
			$this->error_message = "ERROR: <br>  The format of the email is wrong. <br>Please try again...";
			return false;
		}
		else {
			return true;
		}		
	}

//	This function verifies that the registration email has not already been registered.
	public function does_user_exist($post, $db) {				
		$db->prepare("SELECT id FROM $this->table_name WHERE email=?", [  $post['email']  ]);		
		$db->execute();
		
		if(  $db->rowCount() != 0 ) {			
			$this->error_message = "ERROR: <br>  Email address is already registered. <br> Please try again...";					
			return false;
		}
		else {
			return true;
		}
	}

//	This function will create record of user in database
	public function insert_user($post, $db) {
		if ( isset($post['password2']) ) {							// if two other checks went ok, get rid of password 2 as it's no longer needed.  Not sure if this is bad practice, password2 is a needed variable in the method above.
				unset($post['password2']);
		}		
		
		$post['password'] = myCrypt($post['password']);				// blowfish hash the password
		$post['registration_date'] = whatDay();						// get current date/time (west coast US)
			
		$q = "INSERT INTO " . $this->table_name . "(" . implode(", " , array_keys($post)) . ") VALUES ( ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE duplicate = 1";		
		
		$db->prepare($q, array_values($post) );						// PDO prepare & bindParam
		$db->execute();												// PDO execute
		
		if($db->rowCount()) {										// Verify PDO rowCount, if successful it should be 1
			return true;
		}
		
		return false;												// Else return false if the rowCount is 0
	}
}# END class definition
