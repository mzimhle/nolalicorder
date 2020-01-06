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

require_once 'class/room.php';

$roomObject		= new class_room(); 

if(isset($_GET['delete_code'])) {
	
	$errorArray				= array();
	$errorArray['error']	= '';
	$errorArray['result']	= 0;	
	$formValid				= true;
	$success				= NULL;
	$code					= trim($_GET['delete_code']);

	if($errorArray['error']  == '' && $errorArray['result']  == 0 ) {

		$roomData = $roomObject->getByCode($code);
		
		if($roomData) {
			$data							= array();
			$data['room_deleted'] 	= 1;

			$where		= $roomObject->getAdapter()->quoteInto('room_code = ?', $code);
			$success	= $roomObject->update($data, $where);	
			
			$errorArray['error']	= '';
			$errorArray['result']	= 1;			

		} else {
			$errorArray['error']	= 'Could not find room';
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

	$roomData = $roomObject->paginate($start, $length, $filter);

	if(!$csv) {

		$room = array();

		if($roomData) {
			for($i = 0; $i < count($roomData['records']); $i++) {
				$item = $roomData['records'][$i];

				$room[$i] = array(
					trim($item['campus_name']),
					trim($item['room_cipher']),
					'<a href="/configuration/room/details.php?code='.trim($item['room_code']).'" class="'.($item['room_active'] == 0 ? 'error' : 'success').'">'.trim($item['room_name']).'</a>',
					trim($item['room_location']),
					'<button value="Delete" class="btn btn-danger" onclick="deleteModal(\''.$item['room_code'].'\', \'\', \'default\'); return false;">Delete</button>'
				);
			}
		}

		if($roomData) {
			$response['sEcho']					= $_REQUEST['sEcho'];
			$response['iTotalRecords']			= $roomData['displayrecords'];		
			$response['iTotalDisplayRecords']	= $roomData['count'];
			$response['aaData']					= $room;
		} else {
			$response['result']		= false;
			$response['message']	= 'There are no items to show.';			
		}

		echo json_encode($response);
		die();
	} else {
		$row = "Name, Email, Cellphone, Subscriptions\r\n";
		if($roomData) {
			for($i = 0; $i < count($roomData); $i++) {
				$item = $roomData[$i];
				$room[$i] = array(
					str_replace(',', ' ',$item['room_name'].' '.$item['room_surname']),
					str_replace(',', ' ',$item['room_email']),
					str_replace(',', ' ',$item['room_cellphone']),
					str_replace(',', ' ',$item['subscription_name'])
				);
			}
			foreach ($room as $data) {
				$row .= implode(', ', $data)."\r\n";
			}			
		}

		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=room_report_".date('Y-m-d').".csv");
		Header("Cache-Control: private, must-revalidate" ); // HTTP/1.1
		Header("Pragma: private" ); // HTTP/1.0
		print($row);
		exit;		
	}
}

$smarty->display('configuration/room/default.tpl');
?>