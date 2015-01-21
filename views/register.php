<?php

/*
 *  This is the view for the registration page.  
 *  When registering we need to store user info into the database.
 *  First create a new database object and when $_POST is set (meaning a user has attempted registration), 
 *  call a new Registration object. The instantiation of the object will handle all of the sql work of 
 *  inserting a new record into the database.  
 *  It'll either set a good_message or error_message to display depening if registration was successful or failed.
 */

    
    $db  = new Database;
    
    if(isset($_POST['submit'])) {
        $reg = new Register($_POST, $db);
    }   
?>

<section class = "input-form">
    <h1>CS75 Stocks <br> Registration</h1>
    <form action="?page=register.php" method="POST">
        
        <h2 id="register-message">
            <?php 
                
                if(isset($reg->good_message)) {
                    echo $reg->good_message;
                ?>
                    <br><a href="index.php">>> Login</a>
                <?php
                }
                else if(isset($reg->error_message)) {
                    echo $reg->error_message;
                }                
            ?>          
        </h2>
        
        <input type="text"  name="firstName" placeholder="First Name">
        
        <br><br>
        
        <input type="text"  name="lastName"  placeholder="Last Name">
        
        <br><br>
        
        <input type="email"  name="email"  placeholder="Email">
        
        <br><br>
        
        <input type="text"  name="password" placeholder="Password">
        
        <br><br>
        
        <input type="text"   name="password2" placeholder="Confirm Password">
        
        <br><br>
        
        <input type="submit" name="submit" value="Register">        
    </form>
</section>

    