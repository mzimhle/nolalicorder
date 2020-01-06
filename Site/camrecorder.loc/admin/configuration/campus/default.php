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

require_once 'class/campus.php';

$campusObject	= new class_campus(); 

if(isset($_GET['delete_code'])) {
	
	$errorArray				= array();
	$errorArray['error']	= '';
	$errorArray['result']	= 0;	
	$formValid				= true;
	$success				= NULL;
	$code					= trim($_GET['delete_code']);

	if($errorArray['error']  == '' && $errorArray['result']  == 0 ) {

		$campusData = $campusObject->getByCode($code);
		
		if($campusData) {
			$data							= array();
			$data['campus_deleted'] 	= 1;

			$where		= $campusObject->getAdapter()->quoteInto('campus_code = ?', $code);
			$success	= $campusObject->update($data, $where);	
			
			$errorArray['error']	= '';
			$errorArray['result']	= 1;			

		} else {
			$errorArray['error']	= 'Could not find campus';
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

	$campusData = $campusObject->paginate($start, $length, $filter);

	if(!$csv) {

		$campus = array();

		if($campusData) {
			for($i = 0; $i < count($campusData['records']); $i++) {
				$item = $campusData['records'][$i];

				$campus[$i] = array(
					trim($item['campus_cipher']),
					'<a href="/configuration/campus/details.php?code='.trim($item['campus_code']).'" class="'.($item['campus_active'] == 0 ? 'error' : 'success').'">'.trim($item['campus_name']).'</a>',
					trim($item['campus_map_address']),
					'<button value="Delete" class="btn btn-danger" onclick="deleteModal(\''.$item['campus_code'].'\', \'\', \'default\'); return false;">Delete</button>'
				);
			}
		}

		if($campusData) {
			$response['sEcho']					= $_REQUEST['sEcho'];
			$response['iTotalRecords']			= $campusData['displayrecords'];		
			$response['iTotalDisplayRecords']	= $campusData['count'];
			$response['aaData']					= $campus;
		} else {
			$response['result']		= false;
			$response['message']	= 'There are no items to show.';			
		}

		echo json_encode($response);
		die();
	} else {
		$row = "Name, Email, Cellphone, Subscriptions\r\n";
		if($campusData) {
			for($i = 0; $i < count($campusData); $i++) {
				$item = $campusData[$i];
				$campus[$i] = array(
					str_replace(',', ' ',$item['campus_name'].' '.$item['campus_surname']),
					str_replace(',', ' ',$item['campus_email']),
					str_replace(',', ' ',$item['campus_cellphone']),
					str_replace(',', ' ',$item['subscription_name'])
				);
			}
			foreach ($campus as $data) {
				$row .= implode(', ', $data)."\r\n";
			}			
		}

		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=campus_report_".date('Y-m-d').".csv");
		Header("Cache-Control: private, must-revalidate" ); // HTTP/1.1
		Header("Pragma: private" ); // HTTP/1.0
		print($row);
		exit;		
	}
}

$smarty->display('configuration/campus/default.tpl');
?>