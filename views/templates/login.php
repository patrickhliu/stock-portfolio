<?php
/*  This is body of the login page.
 *  It serves as the home page for this site.
 */
 ?>

<section class = "input-form">
        <h1>CS75 Stocks <br> Login</h1>     
        <form action="?page=index.php" method="POST" name="login">      
            <input type="text"   name="email" placeholder="Email">
            <br><br>
        
            <input type="text"   name="password" placeholder="Password">
            <br><br>
        
            <input type="submit" name="submit" value="Login">
            <h5><a href="?page=register.php">Need An Account?</a></h5>
            <h5><a href="?page=forgot_pw.php">Forgot Your Password?</a></h5>        
        </form>
</section>