<?php
//command line PHP requires <?php at top of required/included files - otherwise see them as text

define('MAIN_DIRECTORY', preg_replace("/includes/","", dirname(realpath(__FILE__))) . "/"); // need to add trailing slash
define('DB_DOCUMENT_ROOT', dirname(realpath(__FILE__)) . "/"); // need to add trailing slash

require(MAIN_DIRECTORY . "config.php");

if (function_exists('mysqli_connect')) {
	require(DB_DOCUMENT_ROOT . "connection2.0.php");
}
else{
	require(DB_DOCUMENT_ROOT . "connection.php");	
}

if(isset($env_config)){
	$myDB = new DB($env_config);
	$myDB->connect();
}
else{
	echo "Requires Config File";
}
?>