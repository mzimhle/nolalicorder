<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
/*** Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';
/*** Check for login */
require_once 'includes/auth.php';
/* class files */
require_once 'class/schedule.php';
require_once 'class/room.php';
require_once 'class/course.php';
require_once 'class/class.php';
/* class objects */
$scheduleObject	= new class_schedule();
$roomObject		= new class_room();
$courseObject	= new class_course();
$classObject	= new class_class();

if(isset($_GET['code']) && trim($_GET['code']) != '') {

	$code = trim($_GET['code']);

	$courseData = $courseObject->getByCode($code);

	if($courseData) {
		$smarty->assign('courseData', $courseData);
	} else {
		header('Location: /configuration/course/');
		exit;		
	}
} else {
	header('Location: /configuration/course/');
	exit;		
}

$classPairs = $classObject->pairsByCourse($code);
if($classPairs) $smarty->assign('classPairs', $classPairs);

if(isset($_GET['delete_code'])) {
	
	$errorArray				= array();
	$errorArray['error']	= '';
	$errorArray['result']	= 0;	
	$formValid				= true;
	$success				= NULL;
	$code					= trim($_GET['delete_code']);

	if($errorArray['error']  == '' && $errorArray['result']  == 0 ) {

		$data							= array();
		$data['schedule_deleted'] 	= 1;

		$where		= $scheduleObject->getAdapter()->quoteInto('schedule_code = ?', $code);
		$success	= $scheduleObject->update($data, $where);	

		if($success) {
			$errorArray['error']	= '';
			$errorArray['result']	= 1;			
		} else {
			$errorArray['error']	= 'Could not delete, please try again.';
			$errorArray['result']	= 0;				
		}
	}
	echo json_encode($errorArray);
	exit;
}
/* Check posted data. */
if(isset($_POST['schedule_add'])) {

	$return				= array();
	$return['result']	= 1;
	$return['error']	= array();
	
	if(!isset($_POST['regularity_code'])) {
		$return['error'][] = 'Please add a regularity'; 
	} else if(trim($_POST['regularity_code']) == '') {
		$return['error'][] = 'Please add a regularity';
	} else if(!isset($_POST['schedule_date'])) {
		$return['error'][] = 'Please add a day'; 
	} else if(trim($_POST['schedule_date']) == '') {
		$return['error'][] = 'Please add a day';
	} else if(date('Y-m-d', strtotime(trim($_POST['schedule_date']))) != trim($_POST['schedule_date'])) {
		$return['error'][] = 'Please add a valid date, format: YYYY-MM-DD';
	} else if(!isset($_POST['class_code'])) {
		$return['error'][] = 'Please select a class';
	} else if(trim($_POST['class_code']) == '') {
		$return['error'][] = 'Please select a class';
	} else if(!isset($_POST['participant_code'])) {
		$return['error'][] = 'Please select a teacher for this class';
	} else if(trim($_POST['participant_code']) == '') {
		$return['error'][] = 'Please select a teacher for this class';
	} else if(!isset($_POST['room_code'])) {
		$return['error'][] = 'Please select a room for this class';
	} else if(trim($_POST['room_code']) == '') {
		$return['error'][] = 'Please select a room for this class';
	} else {
		if(!isset($_POST['schedule_time_start'])) {
			$return['error'][] = 'Start time required';
		} else if(trim($_POST['schedule_time_start']) == '') {
			$return['error'][] = 'Start time required';
		} else if($scheduleObject->validateTime(trim($_POST['schedule_time_start'])) == '') {
			$return['error'][] = 'Start time - Please add a valid time';
		} else {
			if(!isset($_POST['schedule_time_end'])) {
				$return['error'][] = 'End time required';
			} else if(trim($_POST['schedule_time_end']) == '') {
				$return['error'][] = 'End time required';
			} else if($scheduleObject->validateTime(trim($_POST['schedule_time_end'])) == '') {
				$return['error'][] = 'End time - Please add a valid time';
			} else {
				if(date('H:i', strtotime(trim($_POST['schedule_time_start']))) > date('H:i', strtotime(trim($_POST['schedule_time_end'])))){
					$return['error'][] = 'Start time is greater than end time';
				}
			}
		}
	}

	if(count($return['error']) == 0) {

		$dayslist = $scheduleObject->getDays(trim($_POST['schedule_date']), trim($_POST['regularity_code']), date('m', strtotime(trim($_POST['schedule_date']))));

		if(count($dayslist)) {
			for($i = 0; $i < count($dayslist); $i++) {
				if($scheduleObject->checkAvailable(trim($_POST['schedule_time_start']), trim($_POST['schedule_time_end']), trim($_POST['participant_code']), $dayslist[$i])) {
							$return['error'][] = 'Already booked for this time. '.trim($_POST['schedule_time_start']).' '.trim($_POST['schedule_time_end']).' - '.$dayslist[$i];
				} else {
					/* Add class time slot. */
					$data							= array();
					$data['participant_code']		= trim($_POST['participant_code']);
					$data['class_code']				= trim($_POST['class_code']);
					$data['schedule_date']			= $dayslist[$i];
					$data['room_code']				= trim($_POST['room_code']);
					$data['schedule_time_start']	= trim($_POST['schedule_time_start']);
					$data['schedule_time_end']		= trim($_POST['schedule_time_end']);
					$success						= $scheduleObject->insert($data);	

					if(!$success) {
						$return['error'][] = 'We could not add the time, please add it.';
					}
				}
			}
		} else {
			$return['error'][] = 'No days were found.';
		}
	}
	
	if(count($return['error']) > 0) $return['result'] = 0;
	$return['error'] = implode('<br />', $return['error']);
	echo json_encode($return);
	exit;
}

/* Setup Pagination. */
if(isset($_GET['action']) && trim($_GET['action']) == 'search') {

	$filter	= array();
	$csv	= 0;
	$start 	= isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : 0;
	$length	= isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : 20;
	
	if(isset($_REQUEST['filter_room']) && trim($_REQUEST['filter_room']) != '') $filter[] = array('filter_room' => trim($_REQUEST['filter_room']));
	if(isset($_REQUEST['filter_class']) && trim($_REQUEST['filter_class']) != '') $filter[] = array('filter_class' => trim($_REQUEST['filter_class']));
	if(isset($_REQUEST['filter_date']) && trim($_REQUEST['filter_date']) != '') $filter[] = array('filter_date' => trim($_REQUEST['filter_date']));
	if(isset($_REQUEST['filter_month']) && trim($_REQUEST['filter_month']) != '') $filter[] = array('filter_month' => trim($_REQUEST['filter_month']));
	if(isset($_REQUEST['filter_year']) && trim($_REQUEST['filter_year']) != '') $filter[] = array('filter_year' => trim($_REQUEST['filter_year']));
	$filter[] = array('filter_course' => $courseData['course_code']);

	$scheduleData = $scheduleObject->paginate($start, $length, $filter);

	$schedules = array();

	if($scheduleData) {
		for($i = 0; $i < count($scheduleData['records']); $i++) {
			$item = $scheduleData['records'][$i];
			$schedules[$i] = array(		
				((int)$item['schedule_video_uploaded'] == 0 ? 'N/A' : '<a href="/configuration/course/view.php?code='.$item['schedule_code'].'" target="_blank">Watch</a>'),
				$item['course_name'].' / '.$item['class_name'],		
				$item['participant_name'],
				$item['room_name'],
				$item['schedule_date'],
				$item['schedule_time_start'],
				$item['schedule_time_end'],				
				((int)$item['schedule_video_uploaded'] == 0 ? '<button value="Delete" onclick="deleteModal(\''.$item['schedule_code'].'\', \''.$courseData['course_code'].'\', \'schedule\'); return false;">Delete</button>' : 'N/A')
			);
		}
	}

	if($scheduleData) {
		$response['sEcho']					= $_REQUEST['sEcho'];
		$response['iTotalRecords']			= $scheduleData['displayrecords'];		
		$response['iTotalDisplayRecords']	= $scheduleData['count'];
		$response['aaData']					= $schedules;
	} else {
		$response['result']		= false;
		$response['message']	= 'There are no items to show.';			
	}

	echo json_encode($response);
	die();
}

$roomPairs = $roomObject->pairs();
if($roomPairs) $smarty->assign('roomPairs', $roomPairs);

$smarty->display('configuration/course/schedule.tpl');

?>