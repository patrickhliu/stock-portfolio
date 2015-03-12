<?php
/**************************************************************************************************
register.php
    This is the view for the registration page. User gets to this page via the login page (home)
***************************************************************************************************/
    // create a new database object, info submitted by user will go into database
    $db  = new Database;
    
    // when user clicks on the 'Register' button, create a 'Register' object
    // the object will use the $_POST array & $db object to insert data into the 'users' table
    if(isset($_POST['submit'])) {
        $reg = new Register($_POST, $db);
    ?>
        <h2 id="form-response-message">
        <?php
            if($reg->getProperty('status')) {
    ?>
               <p>Registration Successful!</p>
               <a href="index.php">>> Log-In</a>
    <?php
            }
            else {
                echo $reg->getProperty('error_message');
            }
    ?>          
            </h2>
    <?php
    }   
?>

<!-- HTML code for registration page -->
<section class = "input-form">
    <h1>Registration</h1>
    <form action="?page=register.php" method="POST" name='reg-form'>
        <div class='reg-form-firstname'>
            <label for:'firstName'>First Name:</label>
            <input type="text"     name="firstName" placeholder="First Name">
        </div>       

        <div class='reg-form-lastname'>
            <label for:'lastName'>Last Name:</label>
            <input type="text"     name="lastName"  placeholder="Last Name">
        </div>

        <div class='reg-form-email'>
            <label for:'email'>Email:</label>
            <input type="email"    name="email"     placeholder="Email">
            <span class = 'client-form-reponse'></span>
        </div>

        <div class='reg-form-password'>
            <label for:'password'>Password:</label>
            <input type="password" name="password"  placeholder="Password">
            <span class = 'client-form-reponse'></span>
        </div>

        <div class='reg-form-password2'>
            <label for:'password2'>Confirm Password:</label>
            <input type="password" name="password2" placeholder="Confirm Password">
            <span class = 'client-form-reponse'></span>
        </div>

        <input type="submit"   name="submit"    value="Register">

        <div class='reg-form-submit-msg'>            
            <span class = 'client-form-reponse'></span>             
        </div>

        <p>Acceptable Email Addresses:</p>
        <ul>
            <li>username@domain.extension <br/> (eg: joe@gmail.com)</li>
            <li>username@subdomain.domain.extension <br/> (eg: joe@abc.gmail.com)</li>
        </ul>

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