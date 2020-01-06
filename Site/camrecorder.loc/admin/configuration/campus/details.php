<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');

/*** Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';
/*** Check for login */
require_once 'includes/auth.php';
/* Class files */
require_once 'class/campus.php';
/* Class objects */
$campusObject	= new class_campus(); 

if(isset($_GET['code']) && trim($_GET['code']) != '') {

	$code = trim($_GET['code']);

	$campusData = $campusObject->getByCode($code);

	if($campusData) {
		$smarty->assign('campusData', $campusData);
	} else {
		header('Location: /configuration/campus/');
		exit;		
	}
}

/* Check posted data. */
if(count($_POST) > 0) {

	$errorArray	= array();
	$data		= array();

	if(!isset($_POST['campus_name'])) {
		$errorArray['campus_name'] = 'Please add name of the campus';	
	} else if(trim($_POST['campus_name']) == '') {
		$errorArray['campus_name'] = 'Please add name of the campus';	
	}

	if(!isset($_POST['campus_map_address'])) {
		$errorArray['campus_map_address'] = 'Please add address of the campus';	
	} else if(trim($_POST['campus_map_address']) == '') {
		$errorArray['campus_map_address'] = 'Please add address of the campus';	
	}

	if(count($errorArray) == 0) {
		/* Add the details. */
		$data						= array();				
		$data['campus_name']		= trim($_POST['campus_name']);
		$data['campus_map_address']	= trim($_POST['campus_map_address']);
		$data['campus_cipher']		= trim($_POST['campus_cipher']);
		/* Insert or update. */
		if(!isset($campusData)) {
			$success	= $campusObject->insert($data);
		} else {
			$where		= $campusObject->getAdapter()->quoteInto('campus_code = ?', $campusData['campus_code']);
			$campusObject->update($data, $where);		
			$success	= $campusData['campus_code'];			
		}
	}
	/* Check errors and redirect if there are non. */
	if(count($errorArray) != 0) {
		/* if we are here there are errors. */
		$smarty->assign('errorArray', $errorArray);
	} else {
		header('Location: /configuration/campus/');
		exit;
	}
}

$smarty->display('configuration/campus/details.tpl');
?>