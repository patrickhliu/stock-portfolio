<?php
/**************************************************************************************************
login.php
    This is the default home page, shown by the index.php controller when $_GET isn't set.
***************************************************************************************************/

    if($_POST) {                                                    // if user presses the 'login' button...
        $newLogin = new Login($_POST);                              // create a Login object

        if($newLogin->getProperty('status')) {                      // if true, log-in is successful
            $sess->login( $newLogin->getProperty('pri') );          // start a new session, where session ID = user's primary key
            header('location: ?page=user.php');                     // go to user.php to show the account page for a user
            exit;           
        }
        else {                                                      // else means log-in failed (user email can't be found -or- password is wrong)
        ?>
            <h2 id="form-response-message">
        <?php
                echo $newLogin->getProperty('error_message');       // display the Login objects error message
        ?>
            </h2>
        <?php
                render('templates/login-template');                 // display the login form
        }
    }
    else if ($sess->getLoginStatus() ) {                            // if true, someone is logged in.  display the user's account page.
        header('location: ?page=user.php');
        exit;
    }
    
    else {                                                          // else no POST, no one is logged in; display the login form
        render('templates/login-template');
    }
?>