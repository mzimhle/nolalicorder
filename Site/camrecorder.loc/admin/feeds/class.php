<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
/*** Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';
/*** Check for login */
require_once 'includes/auth.php';
/* Class files. */
require_once 'class/class.php';
/* Class object. */
$classObject	= new class_class();

$results    = array();
$list       = array();	

if(isset($_REQUEST['term'])) {
		
	$q			= strtolower(trim($_REQUEST['term'])); 
	$classData	= $classObject->search($q);
	
	if($classData) {
		for($i = 0; $i < count($classData); $i++) {
			$list[] = array(
				"id"	=> $classData[$i]["class_code"],
				"faculty"	=> $classData[$i]["faculty_name"],
				"department"	=> $classData[$i]["department_name"],
				"department"	=> $classData[$i]["department_name"],
				"course"	=> $classData[$i]["course_name"],
				"year"	=> $classData[$i]["year_code"],
				"semester"	=> $classData[$i]["semester_code"],
				"class"	=> $classData[$i]["class_name"],
				"code"	=> $classData[$i]["class_cipher"],
				"qualification"	=> $classData[$i]["qualification_name"],
				"label"	=> $classData[$i]['faculty_name'].', '.$classData[$i]['department_name'].', '.$classData[$i]['course_name'].', ( '.$classData[$i]['qualification_name'].' ) '.$classData[$i]['class_name'].' - '.$classData[$i]["class_cipher"],
				"value"	=> $classData[$i]['faculty_name'].', '.$classData[$i]['department_name'].', '.$classData[$i]['course_name'].', ( '.$classData[$i]['qualification_name'].' ) '.$classData[$i]['class_name'].' - '.$classData[$i]["class_cipher"]
			);			
		}	
	}
}

if(count($list) > 0) {
	echo json_encode($list); 
	exit;
} else {
	echo json_encode(array('id' => '', 'label' => 'no results')); 
	exit;
}
exit;
?>