/**************************************************************************************************
script.js
    jQuery to do the following...
    1) automatically place mouse cursor on the first blank field of a form
    2) client side validation of the fields at
    	- registration form    	
    	- change password form
    	- stock quote form
    	- buy stock form
    	- sell stock form
    No validation for login & reset password form.
***************************************************************************************************/

// function that takes user input & regular expression pattern.
// tests if the user input contains the pattern.
function validateInput(input, pattern) {
	var regEx = pattern;

	if( regEx.test(input) ) {
		//console.log("return true");
		return true;
	}
	else {
		//console.log("return false")	;
		return false;
	}
}

// ready function to wait for document to completely load
$(document).ready(function() {
    
    // place mouse cursor on the first input element on each page
    if ( $("input:first").val() === "" ) {
         $("input:first").focus();  
    } 

/********** REGISTRATION FORM **********/
    var registerFirstName = $('.reg-form-firstname input[type="text"]');        // registration page first name element
    var registerLastName  = $('.reg-form-lastname  input[type="text"]');        // registration page last name element
    var registerEmail     = $('.reg-form-email input[name="email"]');           // registration page email element
    var registerPassword  = $('.reg-form-password input[name="password"]');     // registration page first password element
    var registerPassword2 = $('.reg-form-password2 input[name="password2"]');   // registration page second password element
    var registerEmailFlag     = false;  // flags to show if format is correct for email, password1 & password2
    var registerPasswordFlag  = false;  // form submission will make sure all these flags are true, else no submit
    var registerPassword2Flag = false;

    // event handler for when user types into the email field
    registerEmail.on('input', function() {
    	var userInput = registerEmail.val();       // capture what the user is typing
    	$('.reg-form-submit-msg span').html('');   // set the output message to blank

    	// verify either name@domain.com or name@subdomain.domain.com
        if ( validateInput(userInput, /^[a-zA-Z0-9._\-+]+@[a-zA-Z0-9]+\.[a-zA-Z]+$/) || 			  
    		 validateInput(userInput, /^[a-zA-Z0-9._\-+]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+\.[a-zA-Z]+$/)    
  			) {
    			$('.reg-form-email span').html('');  // if verified, set output message to blank
    			registerEmailFlag = true;            // set flag to true
    	}
        // else the email format is incorrect...
    	else {
    			$('.reg-form-email span').html('<p>Warning: Invalid email address</p>'); // set error message
    			registerEmailFlag = false;                                               // set flag to false
    	}
    })

    // event handler for when user types into the first password field
    registerPassword.on('input', function() {
    	var userInput = registerPassword.val();        // capture user input
    	$('.reg-form-submit-msg span').html('');	   // set output message to blank

    	if ( validateInput(userInput, /[A-Z+]/) && 		// verify at least 1 upper case letter (find at least one character from A-Z)
    	 	 validateInput(userInput, /[a-z+]/)	&&		// verify at least 1 lower case letter (find at least one character from a-z)
    	 	 validateInput(userInput, /[0-9]/)  &&		// verify at least 1 number			   (find at least one character from 0-9)
    	 	 validateInput(userInput, /.{6,}/)	&&		// verify at least 6 characters		   (find at least 6 characters)
    	 	 validateInput(userInput, /^\S*$/)			// verify zero spaces (start->end, have 0 or more non-whitespace characters)
    		) {                                         // if verified...

    		$('.reg-form-password span').html('');       // set output message to blank
    		registerPasswordFlag = true;    		     // set flag to true
    	}
    	else {                                           // else format of password 1 is wrong...
    		$('.reg-form-password span').html('<p>Warning: Invalid password</p>');    // set error message
    		registerPasswordFlag = false;                                             // set flag to false
    	}
    })

    // event handler for when user types into the second password field
    registerPassword2.on('input', function() {
    	var userInput = registerPassword2.val();       // capture user input
    	$('.reg-form-submit-msg span').html('');	   // set output message to blank

    	if ( validateInput(userInput, /[A-Z+]/) && 		// verify at least 1 upper case letter (find at least one character from A-Z)
    	 	 validateInput(userInput, /[a-z+]/)	&&		// verify at least 1 lower case letter (find at least one character from a-z)
    	 	 validateInput(userInput, /[0-9]/)  &&		// verify at least 1 number			   (find at least one character from 0-9)
    	 	 validateInput(userInput, /.{6,}/)	&&		// verify at least 6 characters		   (find at least 6 characters)
    	 	 validateInput(userInput, /^\S*$/)			// verify zero spaces (start->end, have 0 or more non-whitespace characters)
    		) {
    		$('.reg-form-password2 span').html('');     // set output message to blank
    		registerPassword2Flag = true;    		    // set flag to true
    	}
    	else {                                          // else format of password 2 is wrong...
    		$('.reg-form-password2 span').html('<p>Warning: Invalid password</p>');   // set error message
    		registerPassword2Flag = false;                                            // set flag to false
    	} 	
    })

	// event handler for when user presses the submit button of registration page...
	$('form[name="reg-form"]').submit(function(event) {
		// if any of the fields are blank...don't submit form & set error message
        if ( registerFirstName.val() === '' || registerLastName.val()  === '' ||
			 registerEmail.val()     === '' || registerPassword.val()  === '' ||
			 registerPassword2.val() === '' ) {
			//console.log('1=====================');
			event.preventDefault();                                                                  
			$('.reg-form-submit-msg span').html('<p>Error:<br/>A field has been left blank</p>');    
		}
        //  if any of email / pw1 / pw2 are in wrong format...don't submit form & set error message
		else if ( !registerEmailFlag || !registerPasswordFlag || !registerPassword2Flag) {
			//console.log('2=====================');
			event.preventDefault();                                                                  
			$('.reg-form-submit-msg span').html('<p>Error:<br/>Email/Password has incorrect format</p>');	
		}
        // if password1 does not match password2...don't submit form & set error message
		else if ( registerPassword.val() !== registerPassword2.val() ) {
			//console.log('3=====================');
			event.preventDefault();
			$('.reg-form-submit-msg span').html('<p>Error:<br/>Passwords do not match</p>');	
		}
        // else all checks pass, submit the form to server and remove any output message
		else {
			//console.log('4=====================');
			//event.preventDefault();
			$('.reg-form-submit-msg span').html('');	
		}	
	});

/********** CHANGE PASSWORD FORM **********/
	var newPassword   = $('.pw-change-form div input[name="pw-change-1"]');    // change pw form new password 1
	var newPassword2  = $('.pw-change-form div input[name="pw-change-2"]');    // change pw form new password 2
	var newPasswordFlag  = false;  // boolean flags to show if format is password1 & password2
	var newPassword2Flag = false;  // form submission checks that these flags are true, else no submission

    // event handler for when user types into the first new password field
	newPassword.on('input', function() {
    	var userInput = newPassword.val();              // store user input
    	$('.changepw-form-submit-msg span').html('');   // remove any output message on page

        // verify format of new password 1
    	if ( validateInput(userInput, /[A-Z+]/) && 		// verify at least 1 upper case letter (find at least one character from A-Z)
    	 	 validateInput(userInput, /[a-z+]/)	&&		// verify at least 1 lower case letter (find at least one character from a-z)
    	 	 validateInput(userInput, /[0-9]/)  &&		// verify at least 1 number			   (find at least one character from 0-9)
    	 	 validateInput(userInput, /.{6,}/)	&&		// verify at least 6 characters		   (find at least 6 characters)
    	 	 validateInput(userInput, /^\S*$/)			// verify zero spaces (start->end, have 0 or more non-whitespace characters)
    		) {
    		$('.changepw-form-password span').html(''); // if verified, set output message blank
    		newPasswordFlag = true;                     // set flag true
    	}
    	else {                                          // else new password1 has wrong format...
    		$('.changepw-form-password span').html('<p>Warning: Invalid password</p>');   // set error message
    		newPasswordFlag = false;                                                      // set flag false
    	}
    })

    // event handler for when user types into the second new password field
	newPassword2.on('input', function() {
    	var userInput = newPassword2.val();             // store user input
    	$('.changepw-form-submit-msg span').html('');   // remove any output message on page

    	if ( validateInput(userInput, /[A-Z+]/) && 		// verify at least 1 upper case letter (find at least one character from A-Z)
    	 	 validateInput(userInput, /[a-z+]/)	&&		// verify at least 1 lower case letter (find at least one character from a-z)
    	 	 validateInput(userInput, /[0-9]/)  &&		// verify at least 1 number			   (find at least one character from 0-9)
    	 	 validateInput(userInput, /.{6,}/)	&&		// verify at least 6 characters		   (find at least 6 characters)
    	 	 validateInput(userInput, /^\S*$/)			// verify zero spaces (start->end, have 0 or more non-whitespace characters)
    		) {
    		$('.changepw-form-password2 span').html(''); // if verified, set output message blank
    		newPassword2Flag = true;                     // set flag true
    	}
    	else {                                           // else new password2 has wrong format...
    		$('.changepw-form-password2 span').html('<p>Warning: Invalid password</p>');  // set error message
    		newPassword2Flag = false;                                                     // set flag false
    	}
    })
    
    // event handler for when user presses the submit button of change password page...
	$('form.pw-change-form').submit(function(event) { 
		// if any of the fields are blank...don't submit form & set error message
        if ( newPassword.val() === '' || newPassword2.val() === '') {
			//console.log('1=====================');
			event.preventDefault();
			$('.changepw-form-submit-msg span').html('<p>Error: A field has been left blank</p>');
		}
        //  if either new pw1 / new pw2 are in wrong format...don't submit form & set error message
		else if (!newPasswordFlag || !newPassword2Flag) {
			//console.log('2=====================');
			event.preventDefault();
			$('.changepw-form-submit-msg span').html('<p>Error: Password format is incorrect</p>');
		}
        // if new password1 does not match new password2...don't submit form & set error message
		else if (newPassword.val() !== newPassword2.val()) {
			//console.log('3=====================');
			event.preventDefault();
			$('.changepw-form-submit-msg span').html('<p>Error: Passwords do not match</p>');
		}
        // else all checks pass, submit the form to server and remove any output message	
		else {
			//console.log('4=====================');
			//event.preventDefault();
			$('.changepw-form-submit-msg span').html('');	
		}	
	});

/********** GETTING STOCK QUOTE **********/
	// store the symbole entered by user
    var quoteSym = $('#get-quote input[name="symbol-get-quote"]');

    // on form submission, verify the field isn't blank
	$('form[name="get-quote-form"]').submit(function(event) { 
		if ( quoteSym.val() === '' ) {                                // if blank
			event.preventDefault();                                   // don't submit
			$('.get-quote-error').html('Warning: Enter a Symbol');    // show error message
		}
	});

/********** BUYING STOCK QTY **********/
	var buySym   = $('#stock-purchase input[name="symbol-purchase"]');     // buy stock symbol element
	var buyQty   = $('#stock-purchase input[name="qty-purchase"]');        // buy quantity element
	var buyFlag = false;                                                   // flag to show if purchase qty is valid

    // event handler for when user types a number into the buy quantity field
	buyQty.on('input', function() {
    	var userInput = buyQty.val();                   // store user's input

    	if ( validateInput(userInput, /^\d+$/)  &&		// verify qty input is numeric
    	 	 validateInput(userInput, /^\S*$/)			// verify qty input has zero spaces (start->end, have 0 or more non-whitespace characters)
    		) {
    		$('.stock-purchase-error').html('');        // if valid, set output message to blank
    		buyFlag = true;                             // set flag to true
    	}
    	else {                                          // else the quantity of stock to buy is invalid
    		$('.stock-purchase-error').html('Warning: Qty must be numeric');  // set error message
    		buyFlag = false;                                                  // set flag to false
    	}
    })

    // event handler for when user pushes submit button to buy stock
    $('form[name="buy-stock-form"]').submit(function(event) { 
		if ( buySym.val() === '' || buyQty.val() === '') {  // if either field is blank
            event.preventDefault();                   // don't submit, set an error message           
            $('.stock-purchase-error').html('Error: A field is blank');
        }
        else if (!buyFlag) {                          // if the quantity has incorrect format (eg: not a number)
			event.preventDefault();                   // don't submit, set an error message    
			$('.stock-purchase-error').html('Error: Pls enter numeric qty');
		}		
		else {                                        // else submit to server and set any output messages to blank
			$('.stock-purchase-error').html('');	
		}
	});

/********** INPUT VALIDATION FOR SELLING STOCK QTY **********/
	var sellSym = $('#stock-sell input[name="symbol-sell"]');  // sell symbol element
	var sellQty = $('#stock-sell input[name="qty-sell"]');     // sell qty element
	var sellFlag = false;                                      // flag to show if sell qty is valid 

    // event handler for when user types a number into the sell quantity field
	sellQty.on('input', function() {
    	var userInput = sellQty.val();                 // store user input

    	if ( validateInput(userInput, /^\d+$/)  &&		// verify input is all numeric
    	 	 validateInput(userInput, /^\S*$/)			// verify zero spaces (start->end, have 0 or more non-whitespace characters)
    		) {
    		$('.stock-sell-error').html('');            // if valid, set output message to blank
    		sellFlag = true;                            // set flag to true
    	}
    	else {                                          // else sell qty is invalid
    		$('.stock-sell-error').html('Warning: Qty must be numeric');  // set error message
    		sellFlag = false;                                             // set flag to false
    	}
    })

    // event handler for when user pushes submit button to buy stock
    $('form[name="sell-stock-form"]').submit(function(event) { 
		if ( sellSym.val() === '' || sellQty.val() === '') {  // if either field is blank
            event.preventDefault();                           // don't submit, set an error message           
            $('.stock-sell-error').html('Error: A field is blank');
        }
        else if (!sellFlag) {                          // if the quantity has incorrect format (eg: not a number)
            event.preventDefault();                    // don't submit, set an error message    
            $('.stock-sell-error').html('Error: Pls enter numeric qty');
        }       
        else {                                        // else submit to server and set any output messages to blank
            $('.stock-sell-error').html('');    
        }
	});
}); 
