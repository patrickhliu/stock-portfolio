<?php

//  This file contains constant variable definitions used by the other files.

	DEFINED('DS')    ? null : define('DS', DIRECTORY_SEPARATOR);
	DEFINED('ROOT')  ? null : define('ROOT', DS.'xampp'.DS.'htdocs'.DS.'cs75'.DS.'finance');
	DEFINED('LIB')   ? null : define('LIB',  DS.'xampp'.DS.'htdocs'.DS.'cs75'.DS.'finance'.DS.'lib');
	DEFINED('VIEW')  ? null : define('VIEW',  DS.'xampp'.DS.'htdocs'.DS.'cs75'.DS.'finance'.DS.'views');
	
	DEFINED('DB_SERVER') ? null : define('DB_SERVER', 'localhost');
	DEFINED('DB_USER')   ? null : define('DB_USER', 'pat');
	DEFINED('DB_PASS')   ? null : define('DB_PASS', 'liu');
	DEFINED('DB_NAME')   ? null : define('DB_NAME', 'finance');
	
	