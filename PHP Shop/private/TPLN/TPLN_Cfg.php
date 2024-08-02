<?php

/***************************** Configuration ***************************************************/
define('TPLN_PATH','../../TPLN/'); // TPLN path slash at end ! (absolute path recommended)

define('TPLN_CACHE_DIR',TPLN_PATH.'cached/'); // default cache dir slash at end !
define('TPLN_CACHE_TIME',3600); // default cache time in seconds

define('TPLN_ERROR_LANG','en'); // default Error language 'en' , 'fr'
define('TPLN_ERROR_ALERT',0);  // activate MAIL alert ? (1=true and 0=false)
define('TPLN_MAIL_ADMIN',''); // email adress for error alert (for the webmaster)
define('TPLN_MAIL_EXPEDITOR',''); // the expeditor email by default
define('TPLN_ERROR_LOGS',0);  // activate error logs ? (1=true and 0=false)
define('TPLN_ERROR_LOGS_FILE',TPLN_PATH.'error_logs.txt'); // default error logs file

define('TPLN_PARSE_GLOBALS',1); // allows TPLN to Parse automatically GET, POST, SESSION variable (1=true and 0=false)

define('TPLN_DEFAULT_IND',0); // allows TPLN to activate default directory and default extension template (1=true and 0=false)
define('TPLN_DEFAULT_PATH',TPLN_PATH.'templates/'); // allows TPLN to activate default directory template if no dir found (absolute path recommended with slash at end)
define('TPLN_DEFAULT_EXT','html'); // allows TPLN to activate default extension template if no extension found (not dot at all)

/***************************** Db configuration  ***************************************************/

define('TPLN_INCLUDE_PEAR',0);
define('TPLN_DB_PEAR','DB.php');

define('TPLN_SQL_QUERY_DEBUG',1); // 0 or 1 to view query on sql error
define('TPLN_DB_TYPE_DEFAULT','mysql'); // your SGBD by defaut option
define('TPLN_DB_HOST_DEFAULT','compaq.vgsoftware.com'); // your host by defaut
define('TPLN_DB_LOGIN_DEFAULT','dev'); // your login by defaut
define('TPLN_DB_PASSWORD_DEFAULT','ved'); // your password by defaut
define('TPLN_DB_BASE_DEFAULT','shop'); // your base by defaut
define('TPLN_DB_PORT',''); // your port by defaut don't touch if you don't know

// DB Navigation
define('TPLN_DB_NavColorFirst','#CCCCCC'); // alternate color by defaut for item {_NavColor}
define('TPLN_DB_NavColorSecond','#FFFFFF'); // alternate color by defaut for item {_NavColor}
/**********************************************************************************/

?>
