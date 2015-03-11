<?php
/**************************************************************************************************
index.php
    This is the controller file.
***************************************************************************************************/

// Include various files...
    require_once('../lib/config.php');                          // define variable constants for use
    require_once(ROOT.'/lib/PHPMailer/PHPMailerAutoload.php');  // PHP Mailer to send out password reset emails
    require_once(ROOT.'/lib/functions.php');                    // helper functions
    require_once(ROOT.'/lib/session_class.php');                // class to manage $_SESSION variable for user log-ins  
    require_once(ROOT.'/lib/database_class.php');               // class to manage connections to SQL database
    require_once(ROOT.'/lib/register_class.php');               // model for the registration page view
    require_once(ROOT.'/lib/user_class.php');                   // model for the user's account page view
    require_once(ROOT.'/lib/stock_class.php');                  // class for stock objects
    require_once(ROOT.'/lib/login_class.php');                  // class to manage log in process.
    
    
//  if a $_GET variable is present, we need to get to that page...    
    if(isset($_GET['page'])) {
        // extract the name of the page to retrieve.
        $page = trim( htmlspecialchars($_GET['page']) );       // remove whitespace and convert any html code to a string
        $page = str_replace(".php", "", $page);                // remove the .php extension

        if ($page === 'index') {                               // the index home page will get the login page
            $page = 'login';
        }
        
        // if the header file, $page file and footer file are in their directories...
        // display those pages.  render() is a helper function.
        if( file_exists(VIEW.'/templates/header.php') AND file_exists(VIEW.'/'.$page.'.php') AND file_exists(VIEW.'/templates/footer.php')  ) {
            render('templates/header', 'CS75 Stocks | '.ucwords($page));       // display header
            require_once(VIEW.'/'.$page.'.php');                               // display page specified by $_GET
            render('templates/footer');                                        // display footer
        }
        else {
            render('templates/header', 'CS75 Stocks | 404');                    // else the page doesn't exist, display the 'no exist' page
            require_once(VIEW.'/no_exist.php');
            render('templates/footer');                                
        }       
    }
    // else means $_GET isn't set, display the log-in page (login.php) as the home page
    else {
        if( file_exists(VIEW.'/templates/header.php') AND file_exists(VIEW.'/login.php') AND file_exists(VIEW.'/templates/footer.php')  ) {
            render('templates/header', 'CS75 Stocks | Log In');
            require_once(VIEW.'/login.php');
            render('templates/footer');     
        }
        else {
            render('templates/header', 'CS75 Stocks | 404');
            require_once(VIEW.'/no_exist.php');
            render('templates/footer');
        }           
    } 
?>
