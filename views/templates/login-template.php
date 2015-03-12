<?php
/**************************************************************************************************
login-template.php
    The log-in form for the login page
***************************************************************************************************/
?>

<section class = "input-form">
        <h1>Login</h1>      
        <form action="?page=index.php" method="POST" name="login">      
            <div class="login-form-email">
                <label for:'email'>Email:</label>
                <input type="email"     name="email"    id="email"    placeholder="Email">
                <span class = 'client-form-reponse'></span>
            </div>

            <div class="login-form-password">
                <label for:'email'>Password:</label>
                <input type="password" name="password" id="password" placeholder="Password">
                <span class = 'client-form-reponse'></span>
            </div>          

            <input type="submit"   name="submit" value="Login">
            <h5><a href="?page=register.php">Need An Account?</a></h5>
            <h5><a href="?page=reset.php">Forgot Your Password?</a></h5>
        </form>
</section>