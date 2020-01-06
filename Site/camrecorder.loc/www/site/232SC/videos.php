<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
/*** Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';
/* Get the auth. */
require_once 'auth.php';
require_once 'class/student.php';
require_once 'class/class.php';

$studentObject	= new class_student();
$classObject	= new class_class();

if(isset($_GET['class']) && trim($_GET['class']) != '') {

	$class = trim($_GET['class']);

	$classData = $classObject->getByCode($class);

	if($classData) {
		$smarty->assign('classData', $classData);
	} else {
		header('Location: /');
		exit;		
	}
} else {
	header('Location: /');
	exit;		
}

/* Pagination. */
$currentPage	= (isset($_GET['page']) && $_GET['page'] !='' ) ? (int)$_GET['page'] : 1;
$perPage		= (isset($_GET['perpage']) && !is_null($_GET['perpage']) && $_GET['perpage'] != '' && is_numeric($_GET['perpage'])) ? $_GET['per_page'] : 10;
$listedPages	= 5;
$scrollingStyle	= 'Sliding';

$filter	= array();
if(isset($_REQUEST['filter_search']) && trim($_REQUEST['filter_search']) != '') $filter[] = array('filter_search' => trim($_REQUEST['filter_search']));
$filter[] = array('filter_class' => trim($classData['class_code']));

$studentData = $studentObject->paginate($filter, $currentPage, $perPage, $listedPages, $scrollingStyle);
$studentItems = $studentData->getCurrentItems();
if($studentItems) $smarty->assign_by_ref('studentItems', $studentItems);

$paginator = $studentData->setView()->getPages();
$paginate = (array)$paginator;
$smarty->assign_by_ref('paginator', $paginate);

/* Display the template */	
$smarty->display('videos.tpl');
?>