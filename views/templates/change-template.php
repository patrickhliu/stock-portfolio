<?php
/**************************************************************************************************
change-template.php
    The form where user can change their account password by entering a new password.
***************************************************************************************************/
?>

	<section class = "input-form">
	    <h1>Change Password: <?php echo $userEmail; ?></h1>
        <form class="pw-change-form" action=<?php echo $handlerURL; ?> method="POST">           
            <div class='changepw-form-password'>
                <label for:"pw-change-1">New Password:</label>
                <input type="password" name="pw-change-1" id="pw-change-1" placeholder='Enter new password'/>
                <span class = 'client-form-reponse'></span>
            </div>
            <div class='changepw-form-password2'>
                <label for:"pw-change-2">Confirm New Password:</label>
                <input type="password" name="pw-change-2" id="pw-change-2" placeholder='Confirm new password'/>
                <span class = 'client-form-reponse'></span>
            </div>                                             
            <input type="submit" name="pw-change-submit" value="Submit"/>       
            <div class='changepw-form-submit-msg'>
                <span class = 'client-form-reponse'></span>
            </div>   
            <p>Password Requirements:</p>
            <ul>
                <li>Contain at least 1 lower case letter</li>
                <li>Contain at least 1 upper case letter</li>
                <li>Contain at least 1 number</li>
                <li>Be at least 6 characters in length</li>
                <li>No spaces allowed</li>
            </ul>
        </form>
    </section>
