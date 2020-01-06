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

require_once 'class/participant.php';
require_once 'class/File.php';

$participantObject	= new class_participant(); 
$fileObject 		= new File(array('txt', 'csv'));

if(isset($_GET['delete_code'])) {
	
	$errorArray				= array();
	$errorArray['error']	= '';
	$errorArray['result']	= 0;	
	$formValid				= true;
	$success				= NULL;
	$code					= trim($_GET['delete_code']);

	if($errorArray['error']  == '' && $errorArray['result']  == 0 ) {

		$participantData = $participantObject->getByCode($code);
		
		if($participantData) {
			$data							= array();
			$data['participant_deleted'] 	= 1;

			$where		= $participantObject->getAdapter()->quoteInto('participant_code = ?', $code);
			$success	= $participantObject->update($data, $where);	

			$errorArray['error']	= '';
			$errorArray['result']	= 1;			

		} else {
			$errorArray['error']	= 'Could not find participant';
			$errorArray['result']	= 0;				
		}
	}
	
	echo json_encode($errorArray);
	exit;
}

if(isset($_GET['status_code'])) {
	
	$errorArray				= array();
	$errorArray['error']	= '';
	$errorArray['result']	= 0;	
	$formValid				= true;
	$code					= trim($_GET['status_code']);
	$status					= trim($_GET['status']);

	if($errorArray['error']  == '' && $errorArray['result']  == 0 ) {

		$data						= array();
		$data['participant_active']	= $status;

		$where		= array();
		$where[]	= $participantObject->getAdapter()->quoteInto('account_code = ?', $zfsession->account);
		$where[]	= $participantObject->getAdapter()->quoteInto('participant_code = ?', $code);
		$success	= $participantObject->update($data, $where);	

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

/* Setup Pagination. */
if(isset($_GET['action']) && trim($_GET['action']) == 'search') {

	$filter	= array();
	$csv	= 0;
	$start 	= isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : 0;
	$length	= isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : 20;
	
	$filter[] = array('filter_category' => 'TEACHER');
	if(isset($_REQUEST['filter_search']) && trim($_REQUEST['filter_search']) != '') $filter[] = array('filter_search' => trim($_REQUEST['filter_search']));
	if(isset($_REQUEST['filter_csv']) && trim($_REQUEST['filter_csv']) != '') { $filter[] = array('filter_csv' => (int)trim($_REQUEST['filter_csv'])); $csv = (int)trim($_REQUEST['filter_csv']); }

	$participantData = $participantObject->paginate($start, $length, $filter);

	if(!$csv) {

		$participants = array();

		if($participantData) {
			for($i = 0; $i < count($participantData['records']); $i++) {
				$item = $participantData['records'][$i];

				$participants[$i] = array(
					'<a href="/teacher/details.php?code='.trim($item['participant_code']).'" class="'.($item['participant_active'] == 0 ? 'error' : 'success').'">'.trim($item['participant_name']).'</a>',
					$item['participant_email'],
					$item['participant_cellphone'],
					$item['participant_password'],
					'<button value="Delete" class="btn btn-danger" onclick="deleteModal(\''.$item['participant_code'].'\', \'\', \'default\'); return false;">Delete</button>'
				);
			}
		}

		if($participantData) {
			$response['sEcho']					= $_REQUEST['sEcho'];
			$response['iTotalRecords']			= $participantData['displayrecords'];		
			$response['iTotalDisplayRecords']	= $participantData['count'];
			$response['aaData']					= $participants;
		} else {
			$response['result']		= false;
			$response['message']	= 'There are no items to show.';			
		}

		echo json_encode($response);
		die();
	} else {
		$row = "Name, Email, Cellphone, Subscriptions\r\n";
		if($participantData) {
			for($i = 0; $i < count($participantData); $i++) {
				$item = $participantData[$i];
				$participants[$i] = array(
					str_replace(',', ' ',$item['participant_name'].' '.$item['participant_surname']),
					str_replace(',', ' ',$item['participant_email']),
					str_replace(',', ' ',$item['participant_cellphone']),
					str_replace(',', ' ',$item['subscription_name'])
				);
			}
			foreach ($participants as $data) {
				$row .= implode(', ', $data)."\r\n";
			}			
		}

		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=participant_report_".date('Y-m-d').".csv");
		Header("Cache-Control: private, must-revalidate" ); // HTTP/1.1
		Header("Pragma: private" ); // HTTP/1.0
		print($row);
		exit;		
	}
}

/* Check posted data. */
if(count($_POST) > 0 && isset($_POST['import_file'])) {

	$errorArray	= array();
	$data 		= array();
	$formValid	= true;
	$success	= NULL;
	
	if(isset($_FILES['importfile'])) {
		/* Check validity of the CV. */
		if((int)$_FILES['importfile']['size'] != 0 && trim($_FILES['importfile']['name']) != '') {
			/* Check if its the right file. */
			$ext = $fileObject->file_extention($_FILES['importfile']['name']); 

			if($ext != '') {				
				$checkExt = $fileObject->getValidateExtention('importfile', $ext);				
				if(!$checkExt) {
					$errorArray['importfile'] = 'Invalid file type something funny with the file format';
					$formValid = false;						
				}
			} else {
				$errorArray['importfile'] = 'Invalid file type';
				$formValid = false;									
			}
		} else {
			switch((int)$_FILES['importfile']['error']) {
				case 1 : $errorArray['importfile'] = 'The uploaded file exceeds the maximum upload file size, should be less than 1M'; $formValid = false; break;
				case 2 : $errorArray['importfile'] = 'File size exceeds the maximum file size'; $formValid = false; break;
				case 3 : $errorArray['importfile'] = 'File was only partically uploaded, please try again'; $formValid = false; break;
				case 4 : $errorArray['importfile'] = 'No file was uploaded'; $formValid = false; break;
				case 6 : $errorArray['importfile'] = 'Missing a temporary folder'; $formValid = false; break;
				case 7 : $errorArray['importfile'] = 'Faild to write file to disk'; $formValid = false; break;
			}
		}
	}		

	if(count($errorArray) == 0 && $formValid == true) {

		$importData = $participantObject->readImport(array(), 'TEACHER');

		if(!$importData) {
			$errorArray['importfile'] = 'Uploaded file is empty or there cilent selected does not exist.';
			$formValid = false;				
		} else {
			$smarty->assign('errors', $importData);
		}
	}
	/* if we are here there are errors. */
	$smarty->assign('errorArray', $errorArray);	
	if(isset($errors)) $smarty->assign('errors', $errors);	
}

$smarty->display('teacher/default.tpl');
?>