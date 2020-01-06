<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
/* Get the settings. */
$settings = parse_ini_file("settings.ini", true);

if(isset($settings[$_SERVER['HTTP_HOST']])) {
	$config = $settings[$_SERVER['HTTP_HOST']];
} else {
	echo 'Site configuration missing...';
	exit;
}
/* Setup Database Connection. */
require_once('Zend/Db.php');
require_once('Zend/Db/Table.php');

try {
	$conn = Zend_Db::factory('Mysqli', array(
		'host'     	=> $config['host'],
		'username' 	=> $config['user'], 
		'password' 	=> $config['password'],
		'dbname'   	=> $config['database']
	));
	$conn->getConnection();
} catch (Zend_Db_Adapter_Exception $e) {
	/* perhaps a failed login credential, or perhaps the RDBMS is not running */
} catch (Zend_Exception $e) {
	/* perhaps factory() failed to load the specified Adapter class */
	echo 'Failed to connect to database.';
}

/* set the fetchmode to object (this enables you to choose fetchAssoc as well as object */
$conn->setFetchMode(Zend_Db::FETCH_ASSOC);

/* set $conn as the default adapter for all abstracted tables */
Zend_Db_Table_Abstract::setDefaultAdapter($conn);

global $config;
?>