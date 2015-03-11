<?php
/**************************************************************************************************
register_class.php
    A class definition for user registration.
***************************************************************************************************/
class Register {
    private $error_message;                 // create an error message to display
    private $table_name = 'users';          // sql table
    private $status = false;                // initialize as false; set to true to indicate that database checks pass & the user is registered
    
// Constructor Function
// arguments are $_POST (user name, email, password to register & $db object)
    public function __construct ($post, $db) {                                     
        
        // iterate through the user's info in $_POST
        foreach($post as $index=>$value) {                                          
            $post[$index] = trim(  htmlspecialchars( $post[$index] )  );    // remove whitespace, html code will become a string
        }
        
        if ( isset($post['submit']) ) { //remove $_POST['submit'] (variable for the registration form submit button)
            unset($post['submit']);
        }   
        
// if these 3 checks pass (return true), then set status = true to indicate successful registration
        if( $this->does_form_validate($post) AND $this->does_user_exist($post, $db) ) {          
            if ( $this->insert_user($post, $db) ) {
                $this->status = true;
            }           
        }
    }

//  Getter method to return the object's private properties
    public function getProperty($property) {
        if(property_exists($this, $property)) {
            return $this->$property;
        }
        return false;
    }

//  does_form_validate() verifies the following...
//  1) user did not submit any empty fields
//  2) the two passwords submitted by user match 
//  3) correct password format 
//  4) correct email format
    public function does_form_validate($post) {
        if(  fieldsEmpty($post)  ) {        // fieldsEmpty() is a helper function, if it finds a blank...
            $this->error_message =          // set an error message
                "<p>ERROR: A field was left blank. Please try again...</p>";
            return false;                   // return false to indicate this check failed
        }
        else if (  $post['password'] != $post['password2'] ) {  // if the two submitted pw's don't match...
            $this->error_message =          // set an error message
                "<p>ERROR:<br/>The two passwords do not match.<br/>Please try again...</p>";
            return false;                   // return false to indicate this check failed
        }
        // verify password format: at least 1 uppercase letter, at least 1 lower case letter, at least 1 number
        // and at least 6 characters long.  If password fails any of the criteria...
        else if (  !(preg_match("/[A-Z+]/", $post['password'])) OR !(preg_match("/[a-z+]/", $post['password'])) OR 
                   !(preg_match("/[0-9+]/", $post['password'])) OR (strlen($post['password']) < 6) OR 
                   (preg_match("/\s/", $post['password'])) ) {
            $this->error_message =          // set an error message
                "<p>ERROR: The format of the password is wrong. Please try again...</p>";
            return false;                   // return false to indicate this check failed
        }
        // verify email format: name@something.com -or- name@something.abc.com are acceptable
        // if this check fails...
        else if (  !(preg_match("/^[a-zA-Z0-9._\-+]+@[a-zA-Z0-9]+\.[a-zA-Z]+$/", $post['email']))  AND 
                   !(preg_match("/^[a-zA-Z0-9._\-+]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+\.[a-zA-Z]+$/", $post['email']))  ) {
            $this->error_message =          // set an error message
                "<p>ERROR: The format of the email is wrong. Please try again...</p>";
            return false;                   // return false to indicate this check failed
        }
        else {
            return true;    // if everything passes, return true to indicate all these checks passed
        }       
    }
//  does_user_exist() verifies that the registration email has not already been registered.
    public function does_user_exist($post, $db) {               
        $db->prepare("SELECT pri FROM $this->table_name WHERE email=?", [  $post['email']  ]);       
        $db->execute();
        
        // if email isn't 0, it's already in the database and registered
        if(  $db->rowCount() != 0 ) {           
            $this->error_message = "<p>ERROR: Email address is already registered. Please try again...</p>";                    
            return false;       // set error message & return false to indicate this check failed
        }
        else {
            return true;        // else email not found in database, return true to indicate this check passed
        }
    }
//  This function will create record of the user in database
    public function insert_user($post, $db) {
        // if two other checks went ok, get rid of password2
        if ( isset($post['password2']) ) {                          
                unset($post['password2']);
        }       
        
        // myCrypt() is a helper function.  will blowfish hash the submitted password
        $post['password'] = myCrypt($post['password']);             

        // whatDay() is a helper function.  will retrieve current data/time to store in database as registration date.
        $post['registration_date'] = whatDay();                     
        
        // insert all post values into the database
        $q = "INSERT INTO " . $this->table_name . "(" . implode(", " , array_keys($post)) . ") 
            VALUES ( ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE duplicate = 1";      
        $db->prepare($q, array_values($post) );                     
        $db->execute();                                             
        
        // Verify PDO rowCount, if it's 1, than SQL INSERT was successful...
        if( $db->rowCount() !== 0) {                                       
            return true;    // return true to indicate this check passed
        }
        
        // Else Insert failed, return false to indicate this check failed
        return false;                                               
    }
}# END class definition