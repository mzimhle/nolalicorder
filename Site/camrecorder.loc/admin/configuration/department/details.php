<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');

/*** Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';
/*** Check for login */
require_once 'includes/auth.php';
/* Class files */
require_once 'class/faculty.php';
require_once 'class/department.php';

$facultyObject	= new class_faculty(); 
$departmentObject		= new class_department(); 

if(isset($_GET['code']) && trim($_GET['code']) != '') {

	$code = trim($_GET['code']);

	$departmentData = $departmentObject->getByCode($code);

	if($departmentData) {
		$smarty->assign('departmentData', $departmentData);
	} else {
		header('Location: /configuration/department/');
		exit;		
	}
}

/* Check posted data. */
if(count($_POST) > 0) {

	$errorArray	= array();
	$data		= array();

	if(!isset($_POST['department_name'])) {
		$errorArray['department_name'] = 'Please add name of the department';	
	} else if(trim($_POST['department_name']) == '') {
		$errorArray['department_name'] = 'Please add name of the department';	
	}

	if(!isset($_POST['faculty_code'])) {
		$errorArray['faculty_code'] = 'Please add faculty location of the department';	
	} else if(trim($_POST['faculty_code']) == '') {
		$errorArray['faculty_code'] = 'Please add faculty location of the department';	
	}
	
	if(count($errorArray) == 0) {
		/* Add the details. */
		$data						= array();				
		$data['department_name']	= trim($_POST['department_name']);
		$data['faculty_code']		= trim($_POST['faculty_code']);
		/* Insert or update. */
		if(!isset($departmentData)) {
			$success	= $departmentObject->insert($data);
		} else {
			$where		= $departmentObject->getAdapter()->quoteInto('department_code = ?', $departmentData['department_code']);
			$departmentObject->update($data, $where);		
			$success	= $departmentData['department_code'];			
		}
	}
	/* Check errors and redirect if there are non. */
	if(count($errorArray) != 0) {
		/* if we are here there are errors. */
		$smarty->assign('errorArray', $errorArray);
	} else {
		header('Location: /configuration/department/');
		exit;
	}
}
$facultypairs = $facultyObject->pairs();
if($facultypairs) $smarty->assign('facultypairs', $facultypairs);

$smarty->display('configuration/department/details.tpl');
?>