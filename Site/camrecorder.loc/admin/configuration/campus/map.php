<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
/*** Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';

/*** Check for login */
require_once 'includes/auth.php';
/* Other resources. */
/* objects. */
require_once 'class/campus.php';

$campusObject	= new class_campus();

if (isset($_GET['code']) && trim($_GET['code']) != '') {
	$code = trim($_GET['code']);
	$campusData = $campusObject->getByCode($code);
	if(!$campusData) {
		header('Location: /configuration/campus/');
		exit;
	} else {
		$smarty->assign('campusData', $campusData);
	}
} else {
	header('Location: /configuration/campus/');
	exit;
}

/* Check posted data. */
if(count($_POST) > 0) {

	$errorArray	= array();
	/* The link. */
	if(!isset($_POST['campus_map_latitude'])) {
		$errorArray['campus_map_latitude'] = 'Latitude required';
	} else if(trim($_POST['campus_map_latitude']) == '') {
		$errorArray['campus_map_latitude'] = 'Latitude required';
	}

	if(!isset($_POST['campus_map_longitude'])) {
		$errorArray['campus_map_longitude'] = 'Longitude required';
	} else if(trim($_POST['campus_map_longitude']) == '') {
		$errorArray['campus_map_longitude'] = 'Longitude required';
	}

	/* The add */
	if(count($errorArray) == 0) {
		$data							= array();
		$data['campus_map_latitude']	= trim($_POST['campus_map_latitude']);
		$data['campus_map_longitude']	= trim($_POST['campus_map_longitude']);

		$where		= $campusObject->getAdapter()->quoteInto('campus_code = ?', $campusData['campus_code']);
		$success	= $campusObject->update($data, $where);		

		if($success) {
			/* Send out an email over here. */
			header('Location: /configuration/campus/map.php?code='.$campusData['campus_code']);
			exit;
		}
	}
	/* if we are here there are errors. */
	$smarty->assign('errorArray', $errorArray);
}

$smarty->display('configuration/campus/map.tpl');
?>