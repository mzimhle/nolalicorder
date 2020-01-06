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
require_once 'class/room.php';

$campusObject	= new class_campus(); 
$roomObject		= new class_room(); 

if(isset($_GET['code']) && trim($_GET['code']) != '') {

	$code = trim($_GET['code']);

	$roomData = $roomObject->getByCode($code);

	if($roomData) {
		$smarty->assign('roomData', $roomData);
	} else {
		header('Location: /configuration/room/');
		exit;		
	}
}

/* Check posted data. */
if(count($_POST) > 0) {

	$errorArray	= array();
	$data		= array();

	if(!isset($_POST['room_name'])) {
		$errorArray['room_name'] = 'Please add name of the room';	
	} else if(trim($_POST['room_name']) == '') {
		$errorArray['room_name'] = 'Please add name of the room';	
	}

	if(!isset($_POST['campus_code'])) {
		$errorArray['campus_code'] = 'Please add campus location of the room';	
	} else if(trim($_POST['campus_code']) == '') {
		$errorArray['campus_code'] = 'Please add campus location of the room';	
	}
	
	if(count($errorArray) == 0) {
		/* Add the details. */
		$data					= array();				
		$data['room_name']		= trim($_POST['room_name']);
		$data['room_location']	= trim($_POST['room_location']);
		$data['campus_code']	= trim($_POST['campus_code']);
		$data['room_cipher']	= trim($_POST['room_cipher']);
		/* Insert or update. */
		if(!isset($roomData)) {
			$success	= $roomObject->insert($data);
		} else {
			$where		= $roomObject->getAdapter()->quoteInto('room_code = ?', $roomData['room_code']);
			$roomObject->update($data, $where);		
			$success	= $roomData['room_code'];			
		}
	}
	/* Check errors and redirect if there are non. */
	if(count($errorArray) != 0) {
		/* if we are here there are errors. */
		$smarty->assign('errorArray', $errorArray);
	} else {
		header('Location: /configuration/room/');
		exit;
	}
}
$campuspairs = $campusObject->pairs();
if($campuspairs) $smarty->assign('campuspairs', $campuspairs);

$smarty->display('configuration/room/details.tpl');
?>