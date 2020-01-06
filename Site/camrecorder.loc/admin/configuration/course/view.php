<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
/* Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';
/* Check for login */
require_once 'includes/auth.php';
/* Classes */
require_once 'class/schedule.php';
/* Objects */
$scheduleObject	= new class_schedule();

if(isset($_GET['code']) && trim($_GET['code']) != '') {

	$code	= trim($_GET['code']);
	
	$scheduleData = $scheduleObject->getVideo($code);

	if($scheduleData) {
		$smarty->assign('scheduleData', $scheduleData);
	} else {
		header('Location: /schedule/');
		exit;
	}
}

$smarty->display('configuration/course/view.tpl');
?>