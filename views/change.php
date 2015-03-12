<?php
/**************************************************************************************************
change.php
    The page to actually change an account's password. User makes request to change password, 
    receives email with link to click on.  The link contains a reset key that was created 
    when user made their request.  The link takes user to this page.
    This page verifies the reset key, and then shows user a form where they can change their password.
***************************************************************************************************/

    $db = new Database;
    
    // this branch is when the user first lands on page after clicking on the link in the email that was sent to them...
    // the link creates a get request with parameters email & reset-key and this branch verifies that reset key
    if ( isset($_GET['reset-key'])  ) {
        $userEmail   = $_GET['email'];                      // extract email from $_GET
        $userResetKey = $_GET['reset-key'];                 // extract reset key from $_GET
        $handlerURL = '?page=changedone.php&email='.$userEmail; 

        // Verify that user's email is in the database, if so the get the database's reset key value
        $db->prepare("SELECT reset_key FROM users WHERE email=?", [$userEmail]);
        $db->execute();
        $resultSet = $db->fetchRow();
        $dbResetKey = $resultSet['reset_key'];
        
        // compare the database's reset key to the user's reset key
        // if they match, set the database reset key column to NULL.  
        // Once NULL, the key in the email becomes "expired" and that email link is no longer valid.
        // and display the form where the user can actually change their password
        if ( $userResetKey === $dbResetKey ) {
            $db->prepare(  "UPDATE users SET reset_key=? WHERE email=?", [NULL, $userEmail]  );
            $db->execute();
            require_once(VIEW.'/templates/change-template.php');
        }
        else { // else means the key is invalid, meaning the link in the email has "expired"
?>
            <h2 id="form-response-message">    
                <p>ERROR: You provided an invalid key.</p>
                <p>Please make another request to reset your password.</p>
                <a href="?page=reset.php">>> Reset Password</a>
            </h2>
<?php
        }
    }
?>