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
require_once 'class/qualification.php';
require_once 'class/course.php';

$facultyObject			= new class_faculty();
$departmentObject		= new class_department();
$qualificationObject	= new class_qualification();
$courseObject			= new class_course();

if(isset($_GET['code']) && trim($_GET['code']) != '') {

	$code = trim($_GET['code']);

	$courseData = $courseObject->getByCode($code);

	if($courseData) {
		$smarty->assign('courseData', $courseData);
	} else {
		header('Location: /configuration/course/');
		exit;		
	}
}

if(isset($_GET['faculty'])) {
	
	$code	= trim($_GET['faculty']);
	$html	= "<option value=''> --- There are no departments --- </option>";

	$departmentPairs = $departmentObject->byFaculty($code);

	if($departmentPairs) {
		$html	= "<option value=''> --- Select department --- </option>";
		foreach($departmentPairs as $department) {
			$select = isset($courseData) && $courseData['department_code'] == $department['department_code'] ? 'SELECTED' : '';
			$html .= "<option value='".$department['department_code']."' $select>".$department['department_name']."</option>";
		}
	}

	echo $html;
	exit;
}

/* Check posted data. */
if(count($_POST) > 0) {

	$errorArray	= array();
	$data		= array();

	if(!isset($_POST['course_name'])) {
		$errorArray['course_name'] = 'Please add name of the course';	
	} else if(trim($_POST['course_name']) == '') {
		$errorArray['course_name'] = 'Please add name of the course';	
	}

	if(!isset($_POST['department_code'])) {
		$errorArray['department_code'] = 'Please add department of the course';	
	} else if(trim($_POST['department_code']) == '') {
		$errorArray['department_code'] = 'Please add department of the course';	
	}
	
	if(!isset($_POST['qualification_code'])) {
		$errorArray['qualification_code'] = 'Please add qualification of the course';	
	} else if(trim($_POST['qualification_code']) == '') {
		$errorArray['qualification_code'] = 'Please add qualification of the course';	
	}

	if(count($errorArray) == 0) {
		/* Add the details. */
		$data						= array();				
		$data['course_name']		= trim($_POST['course_name']);
		$data['course_cipher']		= trim($_POST['course_cipher']);
		$data['department_code']	= trim($_POST['department_code']);
		$data['qualification_code']	= trim($_POST['qualification_code']);
		/* Insert or update. */
		if(!isset($courseData)) {
			$success	= $courseObject->insert($data);
		} else {
			$where		= $courseObject->getAdapter()->quoteInto('course_code = ?', $courseData['course_code']);
			$courseObject->update($data, $where);		
			$success	= $courseData['course_code'];			
		}
	}
	/* Check errors and redirect if there are non. */
	if(count($errorArray) != 0) {
		/* if we are here there are errors. */
		$smarty->assign('errorArray', $errorArray);
	} else {
		header('Location: /configuration/course/');
		exit;
	}
}
$facultypairs = $facultyObject->pairs();
if($facultypairs) $smarty->assign('facultypairs', $facultypairs);

$qualificationpairs = $qualificationObject->pairs();
if($qualificationpairs) $smarty->assign('qualificationpairs', $qualificationpairs);

$smarty->display('configuration/course/details.tpl');
?>