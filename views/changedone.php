<?php
/**************************************************************************************************
changedone.php
    The page reponds to the POST request to change a user's password.
    Verify the two passwords pass validation, then update the user's password
    in the SQL table.  If validation fails, display an error and re-display the
    form to change the password.
***************************************************************************************************/

    $db = new Database;
    $errorMsg;              // error message to display on user mistake

    // To change a password, the user has to type in the new password twice.
    // checkTwo is a helper function that verifies the format of the new password.
    function checkTwo($pass1, $pass2) {
        global $errorMsg;

        if(  empty($pass1) || empty($pass2) ) {        // make sure user filled in both password fields
            $errorMsg = '<p>ERROR:<br/>A field was left blank.';
            return false;                    // return false to indicate this check failed
        }
        
        else if (  $pass1 !== $pass2 ) {  // make sure both passwords match
            $errorMsg = "<p>ERROR:<br/>The two passwords do not match.</p>";
            return false;                      // return false to indicate this check failed
        }
        // verify password format: at least 1 uppercase letter, at least 1 lower case letter, at least 1 number
        // at least 6 characters long, & 0 spaces.
        else if (  !(preg_match("/[A-Z+]/", $pass1)) OR !(preg_match("/[a-z+]/", $pass1)) OR 
                   !(preg_match("/[0-9+]/", $pass1)) OR (strlen($pass1) < 6) OR (preg_match("/\s/", $pass1)) ) {
            $errorMsg = '<p>ERROR:<br/>Password format is incorrect.</p>';
            return false;                   // return false to indicate this check failed
        }
        else {
            return true;                    // all 3 checks pass, return true
        }
    }

// if a POST request is submitted, this means reset key is verified & user can change their password.
    if ( isset($_POST['pw-change-submit'])) {
        unset($_POST['pw-change-submit']);          // remove it because we don't want fieldsEmpty() to check that element
        $userEmail   = $_GET['email'];                                  // extract user email address
        $pass1 = $_POST['pw-change-1'];
        $pass2 = $_POST['pw-change-2'];
        $hashPass = myCrypt($_POST['pw-change-1']);                     // hash the new password, to store in database

        // if new password format is bad, user will need to fill out form again.
        // the form uses this string as it's action attribute.  It has a $_GET variable set.
        $handlerURL = '?page=changedone.php&email='.$userEmail; 

        // if the two passwords pass the check...
        if ( checkTwo($pass1, $pass2) ) {
            // update the user's password in the database...
            $db->prepare(  "UPDATE users SET password=? WHERE email=?", [$hashPass, $userEmail]  );
            $db->execute();

            // below display a success message with link to log-in page
?>
            <h2 id="form-response-message">    
                <p>Success!  Your password has been updated!</p>
                <a href="?page=index.php">>> Log-In</a>
            </h2>
<?php
        }
        else {  // else the check of the 2 passwords failed...

        // display an error message & re-display the new password form
?>
            <h2 id="form-response-message">    
                <?php echo $errorMsg; ?>
            </h2>
<?php
            require_once(VIEW.'/templates/change-template.php');
        }
    }
?>