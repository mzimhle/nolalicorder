<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/Zend/');
// date_default_timezone_set('Africa/Johannesburg');
/*** Standard includes */
require_once 'config/database.php';
require_once 'class/schedule.php';
require_once 'vendor/autoload.php';
$client = new Google_Client();
$client->setApplicationName('API code samples');
$client->setDeveloperKey('AIzaSyDZRa38IIUo9hv8Mo2Lf5RbJ821oIE_hyM');
// Define service object for making API requests.
$service = new Google_Service_YouTube($client);
// Define the $video object, which will be uploaded as the request body.
$video = new Google_Service_YouTube_Video();
// Add 'snippet' object to the $video object.
$videoSnippet = new Google_Service_YouTube_VideoSnippet();
$videoSnippet->setCategoryId('22');
$videoSnippet->setDescription('Description of uploaded video.');
$videoSnippet->setTitle('Test video upload.');
$video->setSnippet($videoSnippet);
// Add 'status' object to the $video object.
$videoStatus = new Google_Service_YouTube_VideoStatus();
$videoStatus->setPrivacyStatus('private');
$video->setStatus($videoStatus);
/* Get Objects. */	
/* We have a client. Lets get the other classes. */
$scheduleObject	= new class_schedule();
/* Check if code exists. */
$scheduleData = $scheduleObject->getForYouTubeUpload();
if($scheduleData) {
	/* Check if file exists. */
	if(is_file($_SERVER['DOCUMENT_ROOT'].$scheduleData['schedule_video_path'])) {
		$file = $_SERVER['DOCUMENT_ROOT'].$scheduleData['schedule_video_path'];
		
		$response = $service->videos->insert(
		  'snippet,status',
		  $video,
		  array(
			'data' => file_get_contents($file),
			'mimeType' => 'video/*',
			'uploadType' => 'multipart'
		  )
		);
		print_r($response);
	}
}
?>