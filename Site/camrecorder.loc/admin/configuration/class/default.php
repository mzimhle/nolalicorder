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

require_once 'class/class.php';

$classObject	= new class_class(); 

if(isset($_GET['delete_code'])) {
	
	$errorArray				= array();
	$errorArray['error']	= '';
	$errorArray['result']	= 0;	
	$formValid				= true;
	$success				= NULL;
	$code					= trim($_GET['delete_code']);

	if($errorArray['error']  == '' && $errorArray['result']  == 0 ) {

		$classData = $classObject->getByCode($code);
		
		if($classData) {
			$data							= array();
			$data['class_deleted'] 	= 1;

			$where		= $classObject->getAdapter()->quoteInto('class_code = ?', $code);
			$success	= $classObject->update($data, $where);	
			
			$errorArray['error']	= '';
			$errorArray['result']	= 1;			

		} else {
			$errorArray['error']	= 'Could not find class';
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

	$classData = $classObject->paginate($start, $length, $filter);

	if(!$csv) {

		$class = array();

		if($classData) {
			for($i = 0; $i < count($classData['records']); $i++) {
				$item = $classData['records'][$i];

				$class[$i] = array(
					trim($item['faculty_name']),
					trim($item['department_name']),
					trim($item['qualification_name']),
					trim($item['course_name']),
					trim($item['year_code']),
					trim($item['semester_code']),
					trim($item['class_cipher']),
					'<a href="/configuration/class/details.php?code='.trim($item['class_code']).'" class="'.($item['class_active'] == 0 ? 'error' : 'success').'">'.trim($item['class_name']).'</a>',
					'<button value="Delete" class="btn btn-danger" onclick="deleteModal(\''.$item['class_code'].'\', \'\', \'default\'); return false;">Delete</button>'
				);
			}
		}

		if($classData) {
			$response['sEcho']					= $_REQUEST['sEcho'];
			$response['iTotalRecords']			= $classData['displayrecords'];		
			$response['iTotalDisplayRecords']	= $classData['count'];
			$response['aaData']					= $class;
		} else {
			$response['result']		= false;
			$response['message']	= 'There are no items to show.';			
		}

		echo json_encode($response);
		die();
	} else {
		$row = "Name, Email, Cellphone, Subscriptions\r\n";
		if($classData) {
			for($i = 0; $i < count($classData); $i++) {
				$item = $classData[$i];
				$class[$i] = array(
					str_replace(',', ' ',$item['class_name'].' '.$item['class_surname']),
					str_replace(',', ' ',$item['class_email']),
					str_replace(',', ' ',$item['class_cellphone']),
					str_replace(',', ' ',$item['subscription_name'])
				);
			}
			foreach ($class as $data) {
				$row .= implode(', ', $data)."\r\n";
			}			
		}

		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=class_report_".date('Y-m-d').".csv");
		Header("Cache-Control: private, must-revalidate" ); // HTTP/1.1
		Header("Pragma: private" ); // HTTP/1.0
		print($row);
		exit;		
	}
}

$smarty->display('configuration/class/default.tpl');
?>