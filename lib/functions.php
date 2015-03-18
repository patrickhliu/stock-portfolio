<?php
/**************************************************************************************************
helpers.php
    This file contains other helper functions
***************************************************************************************************/
 
    //  render() is from the CS75 Prof, and is used to include() page content (header, body, footer) 
    //  and a title (optional argument, only for headers)
    function render($template, $title="") {
        $path = ROOT.'/views/' . $template . '.php';
        require_once($path);
    }

    //  whatDay() returns the current date/time (PST)
    function whatDay() {
        date_default_timezone_set('America/Los_Angeles');
        $date = date('Y-m-d H:i:s');
        return $date;
    }

    //  myCrypt() takes a user's password, performs Blowfish Hashing for user passwords
    //  the hash is stored in the database
    function myCrypt($input, $rounds = 9) {
        $salt = "";
        $saltChars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));

        for ($i = 0; $i < 22; $i++) {
            $salt .= $saltChars[array_rand($saltChars)];        
        }
        
        return crypt($input, sprintf('$2y$%02d$', $rounds) . $salt);    
    }

    // fieldsEmpty() verifies if any input fields in the $_POST array of 
    // the registration / login pages are blank
    function fieldsEmpty($arr = []) {
        if( array_search("", $arr)) {
            return true;
        }
        return false;
        }

    // createResetKey() is used when user wants to reset password...
    // 1) user requests to reset password
    // 2) email sent to user with one-time reset key generated below
    // 3) user clicks on link to use the rest key, then is allowed to change their password
    function createResetKey() {
        $character = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));  // create a array: [ A-Z, a-z, 0-9];
        $reset_key = "";
        
        for ($x = 0; $x < 40; $x++) {
            $reset_key .= $character[array_rand($character)];  // 40 times, copy random element from array and append it to $reset_key
        }

        return $reset_key;
    }

    // sendLetter() sends the email when user requests password change.
    // PHPMailer is used to send the email
    function sendLetter($sendTo, $subject, $body) {     
        $mail = new PHPMailer;
        //$mail->SMTPDebug = 3;                                 // Enable verbose debug output
        $mail->isSMTP();                                        // Set mailer to use SMTP
        $mail->Host = MAILER_SMTP;                              // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                                 // Enable SMTP authentication
        $mail->Username = MAILER_NAME;                          // SMTP username
        $mail->Password = MAILER_PW;                            // SMTP password
        $mail->SMTPSecure = 'tls';                              // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                      // TCP port to connect to
        $mail->From = MAILER_NAME;
        $mail->FromName = 'CS75 Stocks';
        //$mail->addAddress($_POST['email'], 'Joe User');       // Add a recipient
        $mail->addAddress($sendTo);                             // Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');
        $mail->WordWrap = 50;                                   // Set word wrap to 50 characters
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                    // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;        
        $mail->AltBody = "test";                                // This is the body in plain text for non-HTML mail clients
            
        if(!$mail->send()) {
            echo "<p>".'Error: ' . $mail->ErrorInfo."</p>";
        } else {
            echo "<p>Password Reset</p>";
            echo "<p>Check your email for instructions</p>";
            echo "<a href='?page=index.php'>>> Log-In</a>";
        }
    }

?> 