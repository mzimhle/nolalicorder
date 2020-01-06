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

require_once 'class/qualification.php';

$qualificationObject	= new class_qualification(); 

if(isset($_GET['delete_code'])) {
	
	$errorArray				= array();
	$errorArray['error']	= '';
	$errorArray['result']	= 0;	
	$formValid				= true;
	$success				= NULL;
	$code					= trim($_GET['delete_code']);

	if($errorArray['error']  == '' && $errorArray['result']  == 0 ) {

		$qualificationData = $qualificationObject->getByCode($code);
		
		if($qualificationData) {
			$data							= array();
			$data['qualification_deleted'] 	= 1;

			$where		= $qualificationObject->getAdapter()->quoteInto('qualification_code = ?', $code);
			$success	= $qualificationObject->update($data, $where);	
			
			$errorArray['error']	= '';
			$errorArray['result']	= 1;			

		} else {
			$errorArray['error']	= 'Could not find qualification';
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

	$qualificationData = $qualificationObject->paginate($start, $length, $filter);

	if(!$csv) {

		$qualification = array();

		if($qualificationData) {
			for($i = 0; $i < count($qualificationData['records']); $i++) {
				$item = $qualificationData['records'][$i];

				$qualification[$i] = array(
					trim($item['qualification_cipher']),
					'<a href="/configuration/qualification/details.php?code='.trim($item['qualification_code']).'" class="'.($item['qualification_active'] == 0 ? 'error' : 'success').'">'.trim($item['qualification_name']).'</a>',
					'<button value="Delete" class="btn btn-danger" onclick="deleteModal(\''.$item['qualification_code'].'\', \'\', \'default\'); return false;">Delete</button>'
				);
			}
		}

		if($qualificationData) {
			$response['sEcho']					= $_REQUEST['sEcho'];
			$response['iTotalRecords']			= $qualificationData['displayrecords'];		
			$response['iTotalDisplayRecords']	= $qualificationData['count'];
			$response['aaData']					= $qualification;
		} else {
			$response['result']		= false;
			$response['message']	= 'There are no items to show.';			
		}

		echo json_encode($response);
		die();
	} else {
		$row = "Name, Email, Cellphone, Subscriptions\r\n";
		if($qualificationData) {
			for($i = 0; $i < count($qualificationData); $i++) {
				$item = $qualificationData[$i];
				$qualification[$i] = array(
					str_replace(',', ' ',$item['qualification_name'].' '.$item['qualification_surname']),
					str_replace(',', ' ',$item['qualification_email']),
					str_replace(',', ' ',$item['qualification_cellphone']),
					str_replace(',', ' ',$item['subscription_name'])
				);
			}
			foreach ($qualification as $data) {
				$row .= implode(', ', $data)."\r\n";
			}			
		}

		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=qualification_report_".date('Y-m-d').".csv");
		Header("Cache-Control: private, must-revalidate" ); // HTTP/1.1
		Header("Pragma: private" ); // HTTP/1.0
		print($row);
		exit;		
	}
}

$smarty->display('configuration/qualification/default.tpl');
?>