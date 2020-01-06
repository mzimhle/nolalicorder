<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');

/*** Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';
/*** Check for login */
require_once 'includes/auth.php';
/* Class files */
require_once 'class/qualification.php';
/* Class objects */
$qualificationObject	= new class_qualification(); 

if(isset($_GET['code']) && trim($_GET['code']) != '') {

	$code = trim($_GET['code']);

	$qualificationData = $qualificationObject->getByCode($code);

	if($qualificationData) {
		$smarty->assign('qualificationData', $qualificationData);
	} else {
		header('Location: /configuration/qualification/');
		exit;		
	}
}

/* Check posted data. */
if(count($_POST) > 0) {

	$errorArray	= array();
	$data		= array();

	if(!isset($_POST['qualification_name'])) {
		$errorArray['qualification_name'] = 'Please add name of the qualification';	
	} else if(trim($_POST['qualification_name']) == '') {
		$errorArray['qualification_name'] = 'Please add name of the qualification';	
	}

	if(!isset($_POST['qualification_cipher'])) {
		$errorArray['qualification_cipher'] = 'Please add code of the qualification';	
	} else if(trim($_POST['qualification_cipher']) == '') {
		$errorArray['qualification_cipher'] = 'Please add code of the qualification';	
	}
	
	if(count($errorArray) == 0) {
		/* Add the details. */
		$data							= array();				
		$data['qualification_name']		= trim($_POST['qualification_name']);
		$data['qualification_cipher']	= trim($_POST['qualification_cipher']);
		/* Insert or update. */
		if(!isset($qualificationData)) {
			$success	= $qualificationObject->insert($data);
		} else {
			$where		= $qualificationObject->getAdapter()->quoteInto('qualification_code = ?', $qualificationData['qualification_code']);
			$qualificationObject->update($data, $where);		
			$success	= $qualificationData['qualification_code'];			
		}
	}
	/* Check errors and redirect if there are non. */
	if(count($errorArray) != 0) {
		/* if we are here there are errors. */
		$smarty->assign('errorArray', $errorArray);
	} else {
		header('Location: /configuration/qualification/');
		exit;
	}
}

$smarty->display('configuration/qualification/details.tpl');
?>