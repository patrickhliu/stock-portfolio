<?php 
/**************************************************************************************************
reset-template.php
    The form to request a pass word change.
***************************************************************************************************/
?>

<section class = "input-form">
    <h1>Password Reset</h1>
        <form class="pw-reset-form" action="?page=reset.php" method="POST">         
            <div>
                <label for:"pw-reset-email">Email:</label>
                <input type="email" name="pw-reset-email" id="pw-reset-email" placeholder='Enter email'/>
                <span class = 'client-form-reponse'></span>
            </div>
            <input type="submit" name="pw-reset-submit" value="Reset"/>     
        </form>
</section>