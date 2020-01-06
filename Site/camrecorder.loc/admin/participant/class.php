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
require_once 'class/participant.php';
require_once 'class/student.php';

$participantObject	= new class_participant();
$studentObject		= new class_student();

if (isset($_GET['code']) && trim($_GET['code']) != '') {
	
	$code = trim($_GET['code']);
	
	$participantData = $participantObject->getByCode($code);
	
	if(!$participantData) {
		header('Location: /participant/');
		exit;
	}

	$smarty->assign('participantData', $participantData);
	$studentData = $studentObject->getByStudent($code);
	if($studentData) $smarty->assign('studentData', $studentData);
	
} else {
	header('Location: /');
	exit;
}

if(isset($_GET['delete_code'])) {
	$errorArray				= array();
	$errorArray['error']	= '';
	$errorArray['result']	= 1;
	$deletecode				= trim($_GET['delete_code']);
	/* The delete if not. */
	if($errorArray['error']  == '' && $errorArray['result']  == 1 ) {
		$data					= array();
		$data['student_deleted']	= 1;

		$where		= $studentObject->getAdapter()->quoteInto('student_code = ?', $deletecode);
		$success	= $studentObject->update($data, $where);
		
		if(!$success) {
			$errorArray['error']	= 'Could not delete';
			$errorArray['result']	= 0;
		}
	}
	echo json_encode($errorArray);
	exit;
}

/* Check posted data. */
if(count($_POST) > 0) {

	$errorArray	= array();
	/* The student. */
	if(!isset($_POST['class_code'])) {
		$errorArray['class_code'] = 'Class required';
	} else if(trim($_POST['class_code']) == '') {
		$errorArray['class_code'] = 'Class required';
	} else {
		$checkStudent = $studentObject->exists(date('Y'), $participantData['participant_code'], trim($_POST['class_code']));
		
		if($checkStudent) {
			$errorArray['class_code'] = 'Class already linked to this student.';
		}
	}
	/* The add */
	if(count($errorArray) == 0) {
		$data 						= array();
		$data['participant_code']	= $participantData['participant_code'];
		$data['class_code']			= trim($_POST['class_code']);

		$success					= $studentObject->insert($data);			

		if($success) {
			/* Send out an email over here. */
			header('Location: /participant/class.php?code='.$participantData['participant_code']);
			exit;
		}
	}
	/* if we are here there are errors. */
	$smarty->assign('errorArray', $errorArray);
}

$smarty->display('participant/class.tpl');
?>