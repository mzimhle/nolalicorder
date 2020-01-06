<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');

/*** Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';
/*** Check for login */
require_once 'includes/auth.php';
/* Class files */
require_once 'class/participant.php';
/* Class objects */
$participantObject	= new class_participant(); 

if(isset($_GET['code']) && trim($_GET['code']) != '') {

	$code = trim($_GET['code']);

	$participantData = $participantObject->getByCode($code);

	if($participantData) {
		$smarty->assign('participantData', $participantData);
	} else {
		header('Location: /participant/');
		exit;		
	}
}

/* Check posted data. */
if(count($_POST) > 0) {

	$errorArray	= array();
	$data		= array();

	if(!isset($_POST['participant_name'])) {
		$errorArray['participant_name'] = 'Please add name of the participant';	
	} else if(trim($_POST['participant_name']) == '') {
		$errorArray['participant_name'] = 'Please add name of the participant';	
	}
	
	if(!isset($_POST['participant_email'])) {
		$errorArray['participant_email'] = 'Please add an email';
	} else if(trim($_POST['participant_email']) == '') {
		$errorArray['participant_email'] = 'Please add an email';
	} else if($participantObject->validateEmail(trim($_POST['participant_email'])) == '') {
		$errorArray['participant_email'] = 'Please add a valid email address';
	} else {
		$tempcode = isset($participantData) ? $participantData['participant_code'] : null;
		$tempData = $participantObject->getByEmail(trim($_POST['participant_email']), $tempcode);
		
		if($tempData) {
			$errorArray['participant_email'] = 'Email is already being used.';
		}
	}

	if(isset($_POST['participant_cellphone']) && trim($_POST['participant_cellphone']) != '') {
		if($participantObject->validateNumber(trim($_POST['participant_cellphone'])) == '') {
			$errorArray['participant_cellphone'] = 'Please add a valid telephone';
		} else {
			$tempcode = isset($participantData) ? $participantData['participant_code'] : null;
			$tempData = $participantObject->getByCell(trim($_POST['participant_cellphone']), $tempcode);
			
			if($tempData) {
				$errorArray['participant_cellphone'] = 'Cellphone is already being used.';
			}
		}
	}

	if(count($errorArray) == 0) {
		/* Add the details. */
		$data							= array();				
		$data['participant_name']		= trim($_POST['participant_name']);
		$data['participant_email']		= trim($_POST['participant_email']);
		$data['participant_cellphone']	= trim($_POST['participant_cellphone']);
		/* Insert or update. */
		if(!isset($participantData)) {
			$success	= $participantObject->insert($data);
		} else {
			$where		= $participantObject->getAdapter()->quoteInto('participant_code = ?', $participantData['participant_code']);
			$participantObject->update($data, $where);		
			$success	= $participantData['participant_code'];			
		}
	}
	/* Check errors and redirect if there are non. */
	if(count($errorArray) != 0) {
		/* if we are here there are errors. */
		$smarty->assign('errorArray', $errorArray);
	} else {
		header('Location: /participant/class.php?code='.$success);
		exit;
	}
}

$smarty->display('participant/details.tpl');
?>