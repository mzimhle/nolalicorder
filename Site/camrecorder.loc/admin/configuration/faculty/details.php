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
/* Class objects */
$facultyObject	= new class_faculty(); 

if(isset($_GET['code']) && trim($_GET['code']) != '') {

	$code = trim($_GET['code']);

	$facultyData = $facultyObject->getByCode($code);

	if($facultyData) {
		$smarty->assign('facultyData', $facultyData);
	} else {
		header('Location: /configuration/faculty/');
		exit;		
	}
}

/* Check posted data. */
if(count($_POST) > 0) {

	$errorArray	= array();
	$data		= array();

	if(!isset($_POST['faculty_name'])) {
		$errorArray['faculty_name'] = 'Please add name of the faculty';	
	} else if(trim($_POST['faculty_name']) == '') {
		$errorArray['faculty_name'] = 'Please add name of the faculty';	
	}

	if(count($errorArray) == 0) {
		/* Add the details. */
		$data						= array();				
		$data['faculty_name']		= trim($_POST['faculty_name']);
		/* Insert or update. */
		if(!isset($facultyData)) {
			$success	= $facultyObject->insert($data);
		} else {
			$where		= $facultyObject->getAdapter()->quoteInto('faculty_code = ?', $facultyData['faculty_code']);
			$facultyObject->update($data, $where);		
			$success	= $facultyData['faculty_code'];			
		}
	}
	/* Check errors and redirect if there are non. */
	if(count($errorArray) != 0) {
		/* if we are here there are errors. */
		$smarty->assign('errorArray', $errorArray);
	} else {
		header('Location: /configuration/faculty/');
		exit;
	}
}

$smarty->display('configuration/faculty/details.tpl');
?>