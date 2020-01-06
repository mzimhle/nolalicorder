<?php
ini_set('max_execution_time', 1200); //300 seconds = 5 minutes
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');

/**
 * Standard includes
 */
require_once 'config/database.php';
require_once 'config/smarty.php';
/** Check for login */
require_once 'includes/auth.php';

require_once 'class/course.php';

$courseObject	= new class_course(); 

if(isset($_GET['delete_code'])) {
	
	$errorArray				= array();
	$errorArray['error']	= '';
	$errorArray['result']	= 0;	
	$formValid				= true;
	$success				= NULL;
	$code					= trim($_GET['delete_code']);

	if($errorArray['error']  == '' && $errorArray['result']  == 0 ) {

		$courseData = $courseObject->getByCode($code);
		
		if($courseData) {
			$data							= array();
			$data['course_deleted'] 	= 1;

			$where		= $courseObject->getAdapter()->quoteInto('course_code = ?', $code);
			$success	= $courseObject->update($data, $where);	
			
			$errorArray['error']	= '';
			$errorArray['result']	= 1;			

		} else {
			$errorArray['error']	= 'Could not find course';
			$errorArray['result']	= 0;				
		}
	}
	
	echo json_encode($errorArray);
	exit;
}

/* Setup Pagination. */
if(isset($_GET['action']) && trim($_GET['action']) == 'search') {

	$filter	= array();
	$csv	= 0;
	$start 	= isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : 0;
	$length	= isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : 20;
	
	if(isset($_REQUEST['filter_search']) && trim($_REQUEST['filter_search']) != '') $filter[] = array('filter_search' => trim($_REQUEST['filter_search']));
	if(isset($_REQUEST['filter_csv']) && trim($_REQUEST['filter_csv']) != '') { $filter[] = array('filter_csv' => (int)trim($_REQUEST['filter_csv'])); $csv = (int)trim($_REQUEST['filter_csv']); }

	$courseData = $courseObject->paginate($start, $length, $filter);

	if(!$csv) {

		$course = array();

		if($courseData) {
			for($i = 0; $i < count($courseData['records']); $i++) {
				$item = $courseData['records'][$i];

				$course[$i] = array(
					trim($item['department_name']),
					trim($item['qualification_name']),
					trim($item['course_cipher']),
					'<a href="/configuration/course/details.php?code='.trim($item['course_code']).'" class="'.($item['course_active'] == 0 ? 'error' : 'success').'">'.trim($item['course_name']).'</a>',
					'<button value="Delete" class="btn btn-danger" onclick="deleteModal(\''.$item['course_code'].'\', \'\', \'default\'); return false;">Delete</button>'
				);
			}
		}

		if($courseData) {
			$response['sEcho']					= $_REQUEST['sEcho'];
			$response['iTotalRecords']			= $courseData['displayrecords'];		
			$response['iTotalDisplayRecords']	= $courseData['count'];
			$response['aaData']					= $course;
		} else {
			$response['result']		= false;
			$response['message']	= 'There are no items to show.';			
		}

		echo json_encode($response);
		die();
	} else {
		$row = "Name, Email, Cellphone, Subscriptions\r\n";
		if($courseData) {
			for($i = 0; $i < count($courseData); $i++) {
				$item = $courseData[$i];
				$course[$i] = array(
					str_replace(',', ' ',$item['course_name'].' '.$item['course_surname']),
					str_replace(',', ' ',$item['course_email']),
					str_replace(',', ' ',$item['course_cellphone']),
					str_replace(',', ' ',$item['subscription_name'])
				);
			}
			foreach ($course as $data) {
				$row .= implode(', ', $data)."\r\n";
			}			
		}

		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=course_report_".date('Y-m-d').".csv");
		Header("Cache-Control: private, must-revalidate" ); // HTTP/1.1
		Header("Pragma: private" ); // HTTP/1.0
		print($row);
		exit;		
	}
}

$smarty->display('configuration/course/default.tpl');
?>