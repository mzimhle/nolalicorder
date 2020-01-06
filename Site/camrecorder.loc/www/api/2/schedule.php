<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
// date_default_timezone_set('Africa/Johannesburg');
/*** Standard includes */
require_once 'config/database.php';
//include the Zend class for Authentification
require_once 'Zend/Session.php';
// Set up the namespace
$zfsession	= new Zend_Session_Namespace('API_CALL_2');

require_once 'class/account.php';
require_once 'class/schedule.php';
require_once 'class/device.php';
require_once 'class/log.php';

$accountObject 	= new class_account();
$logObject 		= new class_log();
/* Return data will be here. */
$return			= array();
/* Get client ID. */
$account = isset(explode('/', $_SERVER['REQUEST_URI'])[3]) ? explode('/', $_SERVER['REQUEST_URI'])[3] : (isset($_GET['account']) && trim($_GET['account']) != '' ? trim($_GET['account']) : '-1');
/* Lets check if the client actually exists first. */
$accountData	= $accountObject->getByCode($account);
/* mmmmmm.... */
if($accountData) {
	/* Now lets get the headers that have been sent. */
	$headers = apache_request_headers();
	/* $headers 				= array();
	$headers['Playerid']	= '37b07feb-e83f-47a8-a24c-33c8be894c26';
	$headers['Playername']	= 'HUAWEI_PHONE'; */
	/* Set the session variable. */
	$zfsession->account	= $accountData['account_code'];
	$zfsession->headers	= $headers;
	$result				= array('day' => '', 'code' => '', 'schedule' => array(array('code' => '', 'start' => '00:00', 'end' => '00:00')), 'message' => '', 'result' => 0);
	if(isset($headers['Playerid']) && trim($headers['Playerid']) != '') {
		/* We have a client. Lets get the other classes. */
		$scheduleObject	= new class_schedule();
		$deviceObject 	= new class_device();
		/* Add record in the menuclients table with the new or updated details. */
		$deviceData = $deviceObject->getDevice($headers);

		if($deviceData) {
			if($deviceData['room_code'] != '') {
				$scheduleData = $scheduleObject->getSchedule();
				if($scheduleData) {
					$result['day']	= date('Y-m-d');
					$result['schedule'] = array();
					for($i = 0; $i < count($scheduleData); $i++) {
						$result['schedule'][$i]['start']	= $scheduleData[$i]['schedule_time_start'];
						$result['schedule'][$i]['end']		= $scheduleData[$i]['schedule_time_end'];						
						$result['schedule'][$i]['code']		= $scheduleData[$i]['schedule_code'];
						$result['result'] 					= 1;
					}
				} else {
					$result['message']	= "No schedule for today.";
					$result['result']	= 2;
				}				
			} else {				
				$result['message'] = "Room has not been assigned.";
			}
			/* Log what ever happened here. */
			$data = array();
			$data['device_code']	= $deviceData['device_code'];
			$data['log_type']		= 'SCHEDULE';
			$data['log_result']		= $result['result'];
			$data['log_message']	= $result['result'] == 0 ? $result['message'] : "Recieved today's schedule for this device.";
			$data['log_json']		= json_encode($result);
			/* Log it! */
			if(!$logObject->insert($data)) {
				$result['message'] = "Record not logged.";
			}
		} else {
			$result['message'] = "Device has not been found";
		}
	} else {
		$result['message'] = "Player ID has not been found";
	}
} else {
	$result['message'] = "Account was not found.";
}

echo json_encode($result);
exit;
?>