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
require_once 'class/course.php';
require_once 'class/class.php';

$facultyObject	= new class_faculty(); 
$departmentObject	= new class_department(); 
$courseObject	= new class_course(); 
$classObject	= new class_class(); 

if(isset($_GET['code']) && trim($_GET['code']) != '') {

	$code = trim($_GET['code']);

	$classData = $classObject->getByCode($code);

	if($classData) {
		$smarty->assign('classData', $classData);
	} else {
		header('Location: /configuration/class/');
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
			$select = isset($classData) && $classData['department_code'] == $department['department_code'] ? 'SELECTED' : '';
			$html .= "<option value='".$department['department_code']."' $select>".$department['department_name']."</option>";
		}
	}

	echo $html;
	exit;
}
if(isset($_GET['department'])) {
	
	$code	= trim($_GET['department']);
	$html	= "<option value=''> --- There are no courses --- </option>";

	$coursePairs = $courseObject->byDepartment($code);

	if($coursePairs) {
		$html	= "<option value=''> --- Select a course --- </option>";
		foreach($coursePairs as $course) {
			$select = isset($classData) && $classData['course_code'] == $course['course_code'] ? 'SELECTED' : '';
			$html .= "<option value='".$course['course_code']."' $select>".$course['course_name']."</option>";
		}
	}

	echo $html;
	exit;
}
/* Check posted data. */
if(count($_POST) > 0) {

	$errorArray	= array();
	$data		= array();

	if(!isset($_POST['class_name'])) {
		$errorArray['class_name'] = 'Please add name of the class';	
	} else if(trim($_POST['class_name']) == '') {
		$errorArray['class_name'] = 'Please add name of the class';	
	}

	if(!isset($_POST['class_cipher'])) {
		$errorArray['class_cipher'] = 'Please add code of the class';	
	} else if(trim($_POST['class_cipher']) == '') {
		$errorArray['class_cipher'] = 'Please add code of the class';	
	}	
	
	if(!isset($_POST['course_code'])) {
		$errorArray['course_code'] = 'Please add course of the class';	
	} else if(trim($_POST['course_code']) == '') {
		$errorArray['course_code'] = 'Please add course of the class';	
	}
	
	if(!isset($_POST['year_code'])) {
		$errorArray['year_code'] = 'Please add year of the class';	
	} else if((int)trim($_POST['year_code']) == 0) {
		$errorArray['year_code'] = 'Please add year of the class';	
	}

	if(count($errorArray) == 0) {
		/* Add the details. */
		$data					= array();				
		$data['class_name']		= trim($_POST['class_name']);
		$data['class_cipher']	= trim($_POST['class_cipher']);
		$data['course_code']	= trim($_POST['course_code']);
		$data['year_code']		= (int)trim($_POST['year_code']);
		$data['semester_code']	= (int)trim($_POST['semester_code']);
		/* Insert or update. */
		if(!isset($classData)) {
			$success	= $classObject->insert($data);
		} else {
			$where		= $classObject->getAdapter()->quoteInto('class_code = ?', $classData['class_code']);
			$classObject->update($data, $where);		
			$success	= $classData['class_code'];			
		}
	}
	/* Check errors and redirect if there are non. */
	if(count($errorArray) != 0) {
		/* if we are here there are errors. */
		$smarty->assign('errorArray', $errorArray);
	} else {
		header('Location: /configuration/class/');
		exit;
	}
}
$facultypairs = $facultyObject->pairs();
if($facultypairs) $smarty->assign('facultypairs', $facultypairs);

$smarty->display('configuration/class/details.tpl');
?>