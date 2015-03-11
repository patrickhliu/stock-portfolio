<?php
/**************************************************************************************************
reset.php
    The view for the reset password page...
    1) user submits the email address that they want to change password on
    2) program verifies email is registered (already in the database)
    3) program generates a random reset key code
    4) program sends email with a link to click on.  the url contains the reset code
    5) user clicks on the link, link opens a page verifying the that the reset code is correct
    6) after verification, user can change password on that new page that opened.
***************************************************************************************************/
	
	// user supplies the email needing password change.  
    //database object is used to that the account is already registered (in the database)
	$db = new Database;		

	if(isset($_POST['pw-reset-submit']) && !fieldsEmpty($_POST)) {			// if post, user has requested password change...
		$userEmail = $_POST['pw-reset-email'];								// extract user email address

		$db->prepare("SELECT * FROM users WHERE email=?", [$userEmail]);	// verify that the email address is in database
		$db->execute();

		if ( $db->rowCount() !== 0 ) {										// if it's in the database...
			$resultSet = $db->fetchRow();									// store the row result
			$userName = $resultSet['firstName'].' '.$resultSet['lastName'];	// create an array: ['first name', 'last name']
			$resetKey = createResetKey();									// call helper function to generate a reset key
			
			// create a URL link with the reset key as part of the URL
			// an email will be sent to the user with this link
			// user will click on this link to verify they are the owner of the email address.
			$clickStr = "http://localhost/port/stock/public/?page=change.php&email=".	
							urlencode($userEmail)."&reset-key=".$resetKey;

			// store the generated key in the database.
			// later a check will be made between database key & the key in the URL clicked on by user
			// if they match then that confirms the user is the owner of the email
			$db->prepare(  "UPDATE users SET reset_key=? WHERE email=?", [$resetKey, $userEmail]  );
			$db->execute();

			// email parameters (subject, body message)
			$email_subject = "CS75 Stocks | Password Reset Request";
			$email_body = 
				"Hello ".$userName.',<br/><br/>'.
				'There has been a request to reset the password on your account.'."<br/>".
				'To reset your password, click on this link:  '.
				"<a href = ".$clickStr.">Password Reset</a>".
				'<br/>If you did not make this request, delete this email.';
?>
			<h2 id="form-response-message">    
            	<?php  // sendLetter is a helper function.  It uses PHPMailer to send the email message.
            		sendLetter($userEmail, $email_subject, $email_body); 
            	?>
			</h2>
<?php
		}
		else {	// else means the email address isn't in the database, so display error message.
?>
			<h2 id="form-response-message">    
				<p>ERROR: User Not Found. Please try again...</p>
			</h2>
<?php       
			// display the reset password form
			render('templates/reset-template');
		}
	}
	else if(isset($_POST['pw-reset-submit']) && fieldsEmpty($_POST)) {	// if any fields are empty, alert user...
?>
		<h2 id="form-response-message">    
	        <p>ERROR: A field was left blank. Please try again...</p>
	    </h2>
<?php
		render('templates/reset-template');		// and display the reset password form again
	}
	else { // else means no $_POST request, then just display the reset password form
		render('templates/reset-template');
	}
?>