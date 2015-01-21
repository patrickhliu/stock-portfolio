<?php
/* HELPERS.PHP
 *  This file gets included by other files and contains other helper functions 
 *  that didn't seem to fit in any of the class definitions.
 */
 
//  render() is from the Prof, and gets page content & creates the page title.
function render($template, $title="") {
    $path = ROOT.'/views/' . $template . '.php';   
    require_once($path);
}

//  whatDay() just returns the current west coast date/time
function whatDay() {
    date_default_timezone_set('America/Los_Angeles');
    $date = date('Y-m-d H:i:s');
    return $date;
}

//  myCrypt() takes string and does Blowfish Hashing for user passwords
function myCrypt($input, $rounds = 9) {
    $salt = "";
    $saltChars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));

    for ($i = 0; $i < 22; $i++) {
        $salt .= $saltChars[array_rand($saltChars)];        
    }
    
    return crypt($input, sprintf('$2y$%02d$', $rounds) . $salt);    
}


// fieldsEmpty() verifies if any input fields on registration/login page are blank
function fieldsEmpty($arr = []) {
    if( array_search("", $arr)) {
        return true;
    }
    return false;
    }

?> 


