<?php

/*  This is a controller the login page (which serves as the home page).
 *      First check for POST submission, this means user has attempted to login.
 *  If $_POST is set, we create a new Login object called $somebody.  The login object when instantiated will immediately
 *  check 2 things:  1) Verify login fields are valid 2) Authenticate the user's email & password by verifing user input with what's in the database.
 *  If both checks are ok, a good_message property is set for $somebody.  If either check fails, a bad message property is set for $somebody.
 *      In the case a good_message property is set, then we call $sess->login().  $sess is a object of the Session class.  The login() method
 *  does a few things:  1) sets $_SESSION['id'] & "user_id" property of $sess equal to the id property of the $somebody object.  
 *  2) Additionally, it will also set a $sess property called "login_status" to true.
 *  The $_SESSION variable is used to check if someone is logged in when the $sess object is first instantiated.  The "login_status" property is what
 *  will be used to verify if someone is logged in most other general cases.  Having the id number stored will let us know who is logged in.
 *  Next we redirect the user to their account page.
 *      In the case a bad_message property is set, we just display the error message and re-display the login html form.
 *      If $_POST is not set, we still need to check the value of $sess->getLoginStatus().  If true, someone is logged in, redirect to their account.
 * If false, then no one is logged in so we display the login form.
 *  
 */
    
    if($_POST) {
        $somebody = new Login($_POST);
        
        if(isset($somebody->good_message)) {
            $sess->login($somebody->getID());
            header('location: ?page=user.php');
            exit;           
        }
        else if(isset($somebody->error_message)) {
        ?>
        <h2 id="login-messsage">
        <?php
            echo $somebody->error_message;
        ?>
        </h2>
        <?php
            render('templates/login', 'Finances | Log In');     
        }
    }
    else if ($sess->getLoginStatus() ) {
        header('location: ?page=user.php');
        exit;
    }
    
    else {
        render('templates/login', 'Finances | Log In');
    }
?>