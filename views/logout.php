<?php
/**************************************************************************************************
logout.php
    This is the page to display when logout button is pressed.
***************************************************************************************************/

$sess->logout();						// Call logout(), it destroys all session variables and sets $sess->login_status flag to false.
header('location: ?page=index.php');	// Redirect to home page (login page)
exit;
?>
