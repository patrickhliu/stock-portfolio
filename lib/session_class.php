<?php
/**************************************************************************************************
session_class.php
    A class definition for session objects.  Session objects are created when user logs in, 
    and contain methods for maintaining the $_SESSION global variable while user is logged in.
***************************************************************************************************/

class Session {
    private $login_status = false;                  // false means no one logged in.  true means someone is logged in.
    private $user_id;                               // this is a copy of the user's primary key value
        
    // constructor
    public function __construct() {
        session_start();                            // start session to access $_SESSION variables
        $this->check_login();                       // verify if someone is logged in
    }
    
    private function check_login() {
        if(isset($_SESSION['user_id'])) {           // $_SESSION is set when someone logs in, so if that is set
            $this->user_id = $_SESSION['user_id'];  // update the session object properties
            $this->login_status = true;
        }
        else {                                      // else no one is logged in
            unset($this->user_id);                  //  unset the object properties
            $this->login_status = false;
        }
    }
    
    public function getLoginStatus() {              // getter for login_status (either true or false)
        return $this->login_status;                             
    }
    
    public function getID() {                       // getter for user_id                               
        return $this->user_id;
    }
    
    public function login($id) {                    // called when user logs in, passed in user's primary key value
        $this->user_id = $_SESSION['user_id'] = $id;
        $this->login_status = true;
    }
    
    public function logout() {                      // on logout, unset / destroy variables
        unset ( $this->user_id );
        unset ( $_SESSION['user_id']);
        session_destroy();
        $this->login_status = false;
    }       
}

$sess = new Session;                                // instantiate a session right away
?>
