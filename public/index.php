<?php 

//  This is the controller file
//  include all files in lib directory, these contain constant variable definitions, class definitions and helper functions
	require_once('../lib/config.php');
	require_once(ROOT.'/lib/functions.php');	
	require_once(ROOT.'/lib/session_class.php');
	require_once(ROOT.'/lib/database_class.php');
	require_once(ROOT.'/lib/register_class.php');
	require_once(ROOT.'/lib/user_class.php');
	require_once(ROOT.'/lib/stock_class.php');
	require_once(ROOT.'/lib/login_class.php');
	

// if the ?page= variable has a value, escape the value and store it in $page and remove the .php extension.
// Verify that these 3 exist: header, view file for the value of $page and footer
// If so then call render() helper function (taken from course prof) to include those files.
// The view file doesn't use render() function, there was a problem with losing scope of variables in those files, that problem doesn't occur when
// I use require_once for the view file.
// If any one of those 3 don't exist; then display the error page.
// So if $_GET['page'] doesn't exist, just display the home page (view file index.php)
	if(isset($_GET['page'])) {
		$page = trim( htmlspecialchars($_GET['page']) );
		$page = str_replace(".php", "", $page);
		
		if( file_exists(VIEW.'/templates/header.php') AND file_exists(VIEW.'/'.$page.'.php') AND file_exists(VIEW.'/templates/footer.php')  ) {
			render('templates/header', 'Finances | '.ucwords($page));
			require_once(VIEW.'/'.$page.'.php');
			render('templates/footer');		
		}
		else {
			require_once(VIEW.'/no_exist.php');		
		}		
	}
	else {
		if( file_exists(VIEW.'/templates/header.php') AND file_exists(VIEW.'/index.php') AND file_exists(VIEW.'/templates/footer.php')  ) {
			render('templates/header', 'Finances | Log In');
			require_once(VIEW.'/index.php');
			render('templates/footer');		
		}
		else {
			require_once(VIEW.'/no_exist.php');		
		}			
	} 
?>
