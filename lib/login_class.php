<?php
/**************************************************************************************************
login_class.php
    A class definition to create login objects.  
    Login objects are created when user's attempt to log in, 
    and connect with the database to verify...
    1) User's email is in the database (has been registered)
    2) The submitted password matches the password in the database
***************************************************************************************************/

class Login extends Database {
    private $pri;                       // store the user's primary key from database. will be used to create a session with that same id #.
    private $table_name = "users";      // sql table is user's
    private $record;                    // store the returned result from the sql table
    private $error_message;             // create an error message to display
    private $status = false;            // initialize as false; set to true to indicate that database checks pass & the user is authenticated

    // Constructor Function
    public function __construct($post) {
        parent::__construct();
        
        // iterate through $_POST (user's email and password)
        foreach($post as $index=>$value) {                                          // remove whitespace
            $post[$index] = trim(  htmlspecialchars( $post[$index] )  );            // any html code will be converted to a string counterpart
        }
        
        if ( isset($post['submit']) ) {     // remove $_POST['submit'] (variable for the login form submit button)
            unset($post['submit']);
        }

        // if those 2 checks pass (both return true), then set status = true to indicate
        // the user can go to their account page.
        if ( $this->login_fields_ok($post) AND $this->authenticate($post)  ) {
            $this->status = true;
        }       
    }

    //  Getter method to return the object's private properties
    public function getProperty($property) {
        if(property_exists($this, $property)) {
            return $this->$property;
        }
        return false;
    }

    // login_fields_ok() verifies user submitted both email & password (no blanks)
    public function login_fields_ok($post) {
        if( fieldsEmpty($post) ) {      // fieldsEmpty() is a helper function, if it finds a blank...
            $this->error_message =      // set error message
                "<p>ERROR: A field was left blank. Please try again...</p>";
            return false;               // return false indicates that this check failed
        }
        return true;                    // else no blanks found, return true to indicate this check passed
    }

    // authenticate() verifies....
    // 1) the submitted email is in the database (user already registered)
    // 2) the submitted password matches password found in the database
    public function authenticate($post) {                
        // Find the SQL row with this user's email
        $q = "SELECT pri, firstName, lastName, email, password, balance FROM ".$this->table_name." WHERE email=?";
        $this->prepare($q, array_values( [ $post['email'] ] ));
        $this->execute();       
        
        // if a result set was found, store it in 'record' variable
        if ( $this->rowCount() ) {          
            $this->record = $this->fetchRow();

            // compare the submitted password to the database password...        
            if( crypt( $post['password'], $this->record['password'] ) == $this->record['password'] )   {
                $this->pri = $this->record['pri'];  // if both pw's match, store user's primary key into Login object.  It'll be used to start session.
                return true;                        // return true to indicate authenticate() check passes                        
            }       
            
            // else the password's don't match, create a fail message
            // return false to indicate the authenticate() check failed
            $this->error_message = "<p>ERROR: Authentication failure. Please try again...</p>";
            return false;   
        }

        // else the email can't be found in the database, create a fail message
        // return false to indicate the authenticate() check failed
        $this->error_message = "<p>ERROR: User not found. Please try again...</p>";
        return false;       
    }
}
