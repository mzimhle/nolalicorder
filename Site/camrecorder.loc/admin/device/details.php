<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');

/*** Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';
/*** Check for login */
require_once 'includes/auth.php';
/* Class files */
require_once 'class/device.php';
require_once 'class/campus.php';
require_once 'class/room.php';

$deviceObject	= new class_device(); 
$campusObject	= new class_campus(); 
$roomObject		= new class_room(); 

if(isset($_GET['code']) && trim($_GET['code']) != '') {

	$code = trim($_GET['code']);

	$deviceData = $deviceObject->getByCode($code);

	if($deviceData) {
		$smarty->assign('deviceData', $deviceData);
	} else {
		header('Location: /device/');
		exit;		
	}
}

if(isset($_POST['changeRoom'])) {
	
	$errorArray				= array();
	$errorArray['error']	= '';
	$errorArray['result']	= 1;	

	if(!isset($_POST['room'])) {
		$errorArray['error']	= 'Please select a room';
		$errorArray['result']	= 0;
	} else if(trim($_POST['room']) == '') {
		$errorArray['error']	= 'Please select a room';
		$errorArray['result']	= 0;
	}
	
	if(!isset($_POST['device'])) {
		$errorArray['error']	= 'Please select a device';
		$errorArray['result']	= 0;
	} else if(trim($_POST['device']) == '') {
		$errorArray['error']	= 'Please select a device';
		$errorArray['result']	= 0;
	}
	
	if($errorArray['error']  == '' && $errorArray['result']  == 1 ) {

		$deviceData = $deviceObject->getByCode(trim($_POST['device']));

		if($deviceData) {
			$data				= array();
			$data['room_code']	= trim($_POST['room']);

			$where		= $deviceObject->getAdapter()->quoteInto('device_code = ?', $deviceData['device_code']);
			$success	= $deviceObject->update($data, $where);			
		} else {
			$errorArray['error']	= 'Could not find device';
			$errorArray['result']	= 0;				
		}
	}
	
	echo json_encode($errorArray);
	exit;
}

if(isset($_POST['getRooms'])) {
	
	$html	= '<option value=""> --- No rooms in this campus --- </option>';
	$campus	= trim($_POST['campus']);

	$roomData = $roomObject->getByCampus($campus);
	
	if($roomData) {
		$html	= '<option value=""> --- Select a class --- </option>';
		foreach($roomData as $room) {
			$select = isset($deviceData) && $deviceData['room_code'] == $room['room_code'] ? 'selected' : '';
			$html .= '<option value="'.$room['room_code'].'" '.$select.'>'.$room['room_name'].'</option>';
		}
	}
	
	echo $html;
	exit;
}

/* Check posted data. */
if(count($_POST) > 0) {

	$errorArray	= array();
	$data		= array();

	if(!isset($_POST['room_code'])) {
		$errorArray['room_code'] = 'Please add a room the device is in';	
	} else if(trim($_POST['room_code']) == '') {
		$errorArray['room_code'] = 'Please add a room the device is in';
	}

	if(!isset($_POST['device_name'])) {
		$errorArray['device_name'] = 'Please add the name of the device';
	} else if(trim($_POST['device_name']) == '') {
		$errorArray['device_name'] = 'Please add the name of the device';
	}

	if(count($errorArray) == 0) {
		/* Add the details. */
		$data					= array();				
		$data['device_name']	= trim($_POST['device_name']);
		$data['room_code']		= trim($_POST['room_code']);
		/* Insert or update. */
		if(!isset($deviceData)) {
			$success	= $deviceObject->insert($data);
		} else {
			$where		= $deviceObject->getAdapter()->quoteInto('device_code = ?', $deviceData['device_code']);
			$deviceObject->update($data, $where);		
			$success	= $deviceData['device_code'];			
		}
	}
	/* Check errors and redirect if there are non. */
	if(count($errorArray) != 0) {
		/* if we are here there are errors. */
		$smarty->assign('errorArray', $errorArray);
	} else {
		header('Location: /device/');
		exit;
	}
}

$campusPairs = $campusObject->pairs();
if($campusPairs) $smarty->assign('campusPairs', $campusPairs);

$smarty->display('device/details.tpl');
?>