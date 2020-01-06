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
/* Get client ID. */
$account	= isset(explode('/', $_SERVER['REQUEST_URI'])[3]) ? explode('/', $_SERVER['REQUEST_URI'])[3] : (isset($_GET['account']) && trim($_GET['account']) != '' ? trim($_GET['account']) : '-1');
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
		/* We have a client. Lets get the other classes. */
		$scheduleObject	= new class_schedule();
		$deviceObject 	= new class_device();
		/* Add record in the menuclients table with the new or updated details. */
		$deviceData = $deviceObject->getDevice($headers);

		if($deviceData) {
			/* Check if we have a video to upload. */
			if(isset($_FILES['uploadVideo'])) {
				/* check if there are any errors. */
				if((int)$_FILES['uploadVideo']['error'] == 0) {
					/* Get the code. */
					$code = str_replace('.mp4', '', trim($_FILES['uploadVideo']['name']));
					/* Check if code exists. */
					$scheduleData = $scheduleObject->getByCode($code);
					if($scheduleData) {
						/* variables. */
						$directory = $_SERVER['DOCUMENT_ROOT'].'/media/schedule/'.date('Y-m-d').'/';
						/* Create the folder for this day. */
						if(!is_dir($directory)) mkdir($directory, 0777, true);
						/* Create the file. */
						$file = $directory.trim($_FILES['uploadVideo']['name']);				
						/* Now lets check if it has been updated with a video yet. */
						if((int)$scheduleData['schedule_video_uploaded'] == 0) {
							/* Now lets upload the file to a path on the server instead of saving it on the database. */
							if(file_put_contents($file, file_get_contents($_FILES['uploadVideo']['tmp_name']))) {
								/* Nothing has been uploaded yet, lets do this. */
								$data								= array();				
								$data['schedule_video_format']		= $_FILES['uploadVideo']['type'];
								$data['schedule_video_size']		= $_FILES['uploadVideo']['size'];
								$data['schedule_video_path']		= '/media/schedule/'.date('Y-m-d').'/'.trim($_FILES['uploadVideo']['name']);
								$data['schedule_video_uploaded'] 	= 1;
								/* Update the table. */
								$where		= $scheduleObject->getAdapter()->quoteInto('schedule_code = ?', $scheduleData['schedule_code']);
								if($scheduleObject->update($data, $where)) {
									$result['message']	= $file.' - was successfully uploaded and table updated.';
									$result['result']	= 1;
								} else {
									$result['message'] 	= $file.' - was successfully uploaded but table not updated.';
									$result['result']	= 0;
								}
							} else {
								$result['message']	= $file.' - has not been successfully uploaded.';
								$result['result']	= 0;
							}
						} else {
							if(is_file($file)) {
								$result['message'] = $file.' - Already uploaded the video and exists in database.';
								$result['result']	= 2;
							} else {
								$result['message'] = $file.' - File uploaded according to database, but file does not exist.';
								$result['result']	= 0;
							}
						}
					} else {
						$result['message'] = $code.' - The code  was not found.';
					}
				} else {
					switch((int)$_FILES['uploadVideo']['error']) {
						case 1 : $result['message'] = trim($_FILES['uploadVideo']['name']).' - The uploaded file exceeds the maximum upload file size';
						case 2 : $result['message'] = trim($_FILES['uploadVideo']['name']).' - File size exceeds the maximum file size';
						case 3 : $result['message'] = trim($_FILES['uploadVideo']['name']).' - File was only partically uploaded, please try again';
						case 4 : $result['message'] = trim($_FILES['uploadVideo']['name']).' - No file was uploaded';
						case 6 : $result['message'] = trim($_FILES['uploadVideo']['name']).' - Missing a temporary folder';
						case 7 : $result['message'] = trim($_FILES['uploadVideo']['name']).' - Faild to write file to disk';
					}
				}
			} else {
				$result['message'] = "There is nothing to upload.";
			}
			/* Log what ever happened here. */
			$data = array();
			$data['device_code']	= $deviceData['device_code'];
			$data['log_type']		= 'UPLOAD';
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