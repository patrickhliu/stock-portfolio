<?php
/**************************************************************************************************
config.php
    This file defines variable constants for use
***************************************************************************************************/

    DEFINED('DS')    ? null : define('DS'  , DIRECTORY_SEPARATOR);
    DEFINED('ROOT')  ? null : define('ROOT', DS.'xampp'.DS.'htdocs'.DS.'port'.DS.'stock');
    DEFINED('LIB')   ? null : define('LIB' , ROOT.DS.'lib');
    DEFINED('VIEW')  ? null : define('VIEW', ROOT.DS.'views');
    
    DEFINED('DB_SERVER') ? null : define('DB_SERVER', 'localhost');
    DEFINED('DB_USER')   ? null : define('DB_USER', 'pat');
    DEFINED('DB_PASS')   ? null : define('DB_PASS', 'liu');
    DEFINED('DB_NAME')   ? null : define('DB_NAME', 'stockport');

    DEFINED('MAILER_SMTP')   ? null : define('MAILER_SMTP', '');
    DEFINED('MAILER_NAME')   ? null : define('MAILER_NAME', '');
    DEFINED('MAILER_PW')     ? null : define('MAILER_PW'  , '');
    