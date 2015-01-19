<?php

/*
 * 		Definition for login class, contains methods to handle the logging in process and to find out if/who is logged in.
 */


class Login extends Database {
	private $id;
	private $firstName;
	private $lastName;
	private $email;
	private $balance;
	private $table_name = "users";
	private $record;
	public $error_message;
	public $good_message;


//  The constructor calls: 1) login_fields_ok() will make sure the user input is valid.  2) authenticate verifies email/pw matches what's in the database.
//	If both checks pass, then constructor sets a good_message variable stating succcessful login.  Actually it's more like a flag because I ended up 
//	never displaying this successful login message.	
	public function __construct($post) {
		parent::__construct();
		
		foreach($post as $index=>$value) {											// escape any html tags that are input by user & trim off lead/trailing whitespace
			$post[$index] = trim(  htmlspecialchars( $post[$index] )  );
		}
		
		if ( isset($post['submit']) ) {												// remove 'submit' variable from $post array, it's not needed at this point
			unset($post['submit']);
		}
		

		if ( $this->login_fields_ok($post) AND $this->authenticate($post)  ) {
			$this->good_message = "Login Successful ! <br> Redirecting...";
		} 		
	}

//	login_fields_ok() takes in the $_POST array containing the user's email and password.
//	It'll call the fieldsEmpty() method to verify if user entered a blank input.  If so set a error_message to display.  
//  Otherwise return true to tell the constructor that this check passed.
	public function login_fields_ok($post) {
		if( fieldsEmpty($post) ) {													// fieldsEmpty() is a helper function.  Verifies user input is not blank
			$this->error_message = "ERROR: <br> One or more fields are blank.<br> Please try again...";
			return false;
		}
		return true;
	}

// authenticate() takes the $_POST array containing user email and password that was entered.
// It'll use the email to pull user info from the database, if it finds a record (rowCount) then store that into an array $this->record.
// If no record found, authenticate() returns false to the constructor.
// If record is found, verify the blowfish hash of the user entered password matches the database entry, if so return true, if not return false.
// 
	public function authenticate($post) {
				
		$q = "SELECT id, firstName, lastName, email, password, balance FROM ".$this->table_name." WHERE email=?";
		$this->prepare($q, array_values( [ $post['email'] ] ));
		$this->execute();		
				
		if ( $this->rowCount() ) {			
			$this->record = $this->fetchRow();
					
			if( crypt( $post['password'], $this->record['password'] ) == $this->record['password'] )   {
				$this->id = $this->record['id'];		// Upon login, we'll store the record[id] into $_SESSION['id'] & $sess->id property
				return true;	
			}		
			$this->error_message = "ERROR: <br> Authentication failure.<br> Please try again...";
			return false;	
		}		
		$this->error_message = "ERROR: <br> Record not found.<br> Please try again...";
		return false;		
	}	
	
	public function getID() {
		return $this->id;								// getter for id property
	}
}
