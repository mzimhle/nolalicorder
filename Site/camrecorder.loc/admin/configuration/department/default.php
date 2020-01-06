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

require_once 'class/department.php';

$departmentObject	= new class_department(); 

if(isset($_GET['delete_code'])) {
	
	$errorArray				= array();
	$errorArray['error']	= '';
	$errorArray['result']	= 0;	
	$formValid				= true;
	$success				= NULL;
	$code					= trim($_GET['delete_code']);

	if($errorArray['error']  == '' && $errorArray['result']  == 0 ) {

		$departmentData = $departmentObject->getByCode($code);
		
		if($departmentData) {
			$data							= array();
			$data['department_deleted'] 	= 1;

			$where		= $departmentObject->getAdapter()->quoteInto('department_code = ?', $code);
			$success	= $departmentObject->update($data, $where);	
			
			$errorArray['error']	= '';
			$errorArray['result']	= 1;			

		} else {
			$errorArray['error']	= 'Could not find department';
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

	$departmentData = $departmentObject->paginate($start, $length, $filter);

	if(!$csv) {

		$department = array();

		if($departmentData) {
			for($i = 0; $i < count($departmentData['records']); $i++) {
				$item = $departmentData['records'][$i];

				$department[$i] = array(
					trim($item['faculty_name']),
					'<a href="/configuration/department/details.php?code='.trim($item['department_code']).'" class="'.($item['department_active'] == 0 ? 'error' : 'success').'">'.trim($item['department_name']).'</a>',
					'<button value="Delete" class="btn btn-danger" onclick="deleteModal(\''.$item['department_code'].'\', \'\', \'default\'); return false;">Delete</button>'
				);
			}
		}

		if($departmentData) {
			$response['sEcho']					= $_REQUEST['sEcho'];
			$response['iTotalRecords']			= $departmentData['displayrecords'];		
			$response['iTotalDisplayRecords']	= $departmentData['count'];
			$response['aaData']					= $department;
		} else {
			$response['result']		= false;
			$response['message']	= 'There are no items to show.';			
		}

		echo json_encode($response);
		die();
	} else {
		$row = "Name, Email, Cellphone, Subscriptions\r\n";
		if($departmentData) {
			for($i = 0; $i < count($departmentData); $i++) {
				$item = $departmentData[$i];
				$department[$i] = array(
					str_replace(',', ' ',$item['department_name'].' '.$item['department_surname']),
					str_replace(',', ' ',$item['department_email']),
					str_replace(',', ' ',$item['department_cellphone']),
					str_replace(',', ' ',$item['subscription_name'])
				);
			}
			foreach ($department as $data) {
				$row .= implode(', ', $data)."\r\n";
			}			
		}

		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=department_report_".date('Y-m-d').".csv");
		Header("Cache-Control: private, must-revalidate" ); // HTTP/1.1
		Header("Pragma: private" ); // HTTP/1.0
		print($row);
		exit;		
	}
}

$smarty->display('configuration/department/default.tpl');
?>