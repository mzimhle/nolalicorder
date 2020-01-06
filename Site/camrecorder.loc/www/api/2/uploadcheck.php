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
require_once 'class/schedule.php';
require_once 'class/log.php';

$accountObject 	= new class_account();
$logObject 		= new class_log();
/* Return data will be here. */
$return			= array();
/* Get client ID. */
$account	= isset(explode('/', $_SERVER['REQUEST_URI'])[3]) ? explode('/', $_SERVER['REQUEST_URI'])[3] : (isset($_GET['account']) && trim($_GET['account']) != '' ? trim($_GET['account']) : '-1');

$schedulecode	= isset(explode('/', $_SERVER['REQUEST_URI'])[4]) ? explode('/', $_SERVER['REQUEST_URI'])[4] : (isset($_GET['schedulecode']) && trim($_GET['schedulecode']) != '' ? trim($_GET['schedulecode']) : '-1');

$result		= array('message' => '', 'result' => 0);
/* Lets check if the client actually exists first. */
$accountData	= $accountObject->getByCode($account);
/* mmmmmm.... */
if($accountData) {
	/* Now lets get the headers that have been sent. */
	$headers = apache_request_headers();
	/* $headers				= array();
	$headers['playerid']	= '69e5be98-c7ca-4002-9c64-066026483c00';
	$headers['playername']	= 'M34 Building'; */
	/* Set the session variable. */
	$zfsession->account	= $accountData['account_code'];
	$zfsession->headers	= $headers;
	// Check the playerid
	if(isset($headers['Playerid']) && trim($headers['Playerid']) != '') {
		/* Add record in the menuclients table with the new or updated details. */
		$deviceData = $deviceObject->getDevice($headers);
		// Check if device exists. 
		if($deviceData) {
			/* We have a client. Lets get the other classes. */
			$scheduleObject	= new class_schedule();
			/* Check if code exists. */
			$scheduleData = $scheduleObject->getScheduleFile($schedulecode);
			/* Check if we have this file. */
			if($scheduleData) {
				if(is_file($_SERVER['DOCUMENT_ROOT']).$scheduleData['schedule_video_path']) {
					$result = array('message' => 'File exists.', 'result' => 1);
				} else {
					$result = array('message' => 'File does not exists, but record exists.', 'result' => 2);
				}
			} else {
				$result = array('message' => 'Schedule code has not been found.', 'result' => 0);
			}
			/* Log what ever happened here. */
			$data = array();
			$data['device_code']	= $deviceData['device_code'];
			$data['log_type']		= 'UPLOADCHECK';
			$data['log_result']		= $result['result'];
			$data['log_message']	= $result['message'];
			$data['log_json']		= json_encode($result);
			/* Log it! */
			if(!$logObject->insert($data)) {
				$result['message'] = "Record not logged.";
			}			
		}
	}
} else {
	$result['message'] = "Account was not found.";
}

echo json_encode($result);
exit;
?>