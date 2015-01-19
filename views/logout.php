<?php
/* This is the code for the logout action.
 * Call logout(), it destroys all session variables and sets $sess->login_status flag to false.
 * Then redirect to the home login page.
 */

$sess->logout();
header('location: ?page=index.php');
exit;
?>
