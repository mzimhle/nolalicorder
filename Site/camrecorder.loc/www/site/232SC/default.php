<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
/*** Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';
/* Get the auth. */
require_once 'auth.php';
require_once 'class/student.php';

$studentObject	= new class_student();

$studentData = $studentObject->classes();
if($studentData) $smarty->assign('studentData', $studentData);

/* Display the template */	
$smarty->display('default.tpl');
?>