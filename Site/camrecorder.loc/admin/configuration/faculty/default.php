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

require_once 'class/faculty.php';

$facultyObject	= new class_faculty(); 

if(isset($_GET['delete_code'])) {
	
	$errorArray				= array();
	$errorArray['error']	= '';
	$errorArray['result']	= 0;	
	$formValid				= true;
	$success				= NULL;
	$code					= trim($_GET['delete_code']);

	if($errorArray['error']  == '' && $errorArray['result']  == 0 ) {

		$facultyData = $facultyObject->getByCode($code);
		
		if($facultyData) {
			$data							= array();
			$data['faculty_deleted'] 	= 1;

			$where		= $facultyObject->getAdapter()->quoteInto('faculty_code = ?', $code);
			$success	= $facultyObject->update($data, $where);	
			
			$errorArray['error']	= '';
			$errorArray['result']	= 1;			

		} else {
			$errorArray['error']	= 'Could not find faculty';
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

	$facultyData = $facultyObject->paginate($start, $length, $filter);

	if(!$csv) {

		$faculty = array();

		if($facultyData) {
			for($i = 0; $i < count($facultyData['records']); $i++) {
				$item = $facultyData['records'][$i];

				$faculty[$i] = array(
					'<a href="/configuration/faculty/details.php?code='.trim($item['faculty_code']).'" class="'.($item['faculty_active'] == 0 ? 'error' : 'success').'">'.trim($item['faculty_name']).'</a>',
					'<button value="Delete" class="btn btn-danger" onclick="deleteModal(\''.$item['faculty_code'].'\', \'\', \'default\'); return false;">Delete</button>'
				);
			}
		}

		if($facultyData) {
			$response['sEcho']					= $_REQUEST['sEcho'];
			$response['iTotalRecords']			= $facultyData['displayrecords'];		
			$response['iTotalDisplayRecords']	= $facultyData['count'];
			$response['aaData']					= $faculty;
		} else {
			$response['result']		= false;
			$response['message']	= 'There are no items to show.';			
		}

		echo json_encode($response);
		die();
	} else {
		$row = "Name, Email, Cellphone, Subscriptions\r\n";
		if($facultyData) {
			for($i = 0; $i < count($facultyData); $i++) {
				$item = $facultyData[$i];
				$faculty[$i] = array(
					str_replace(',', ' ',$item['faculty_name'].' '.$item['faculty_surname']),
					str_replace(',', ' ',$item['faculty_email']),
					str_replace(',', ' ',$item['faculty_cellphone']),
					str_replace(',', ' ',$item['subscription_name'])
				);
			}
			foreach ($faculty as $data) {
				$row .= implode(', ', $data)."\r\n";
			}			
		}

		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=faculty_report_".date('Y-m-d').".csv");
		Header("Cache-Control: private, must-revalidate" ); // HTTP/1.1
		Header("Pragma: private" ); // HTTP/1.0
		print($row);
		exit;		
	}
}

$smarty->display('configuration/faculty/default.tpl');
?>