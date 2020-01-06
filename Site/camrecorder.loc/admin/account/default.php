<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');

/*** Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';
/** Check for login */
require_once 'includes/auth.php';

require_once 'class/account.php';

$accountObject	= new class_account(); 

if(isset($_GET['delete_code'])) {
	
	$errorArray				= array();
	$errorArray['error']	= '';
	$errorArray['result']	= 0;	
	$formValid				= true;
	$code					= trim($_GET['delete_code']);
		
	if($errorArray['error']  == '' && $errorArray['result']  == 0 ) {
	
		$data						= array();
		$data['account_deleted']	= 1;
		
		$where		= $accountObject->getAdapter()->quoteInto('account_code = ?', $code);
		$success	= $accountObject->update($data, $where);	

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
	$length	= isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : 100;

	if(isset($_REQUEST['filter_search']) && trim($_REQUEST['filter_search']) != '') $filter[] = array('filter_search' => trim($_REQUEST['filter_search']));

	$accountData = $accountObject->paginate($start, $length, $filter);

	if(!$csv) {
		$accounts = array();
		if($accountData) {
			for($i = 0; $i < count($accountData['records']); $i++) {
				$item = $accountData['records'][$i];
				$accounts[$i] = array(
					trim($item['account_site']),
					'<a href="/account/details.php?code='.trim($item['account_code']).'">'.trim($item['account_name']).'</a>',					
					trim($item['account_contact_cellphone']),
					trim($item['account_contact_email']),
					trim($item['account_contact_telephone']),
					trim($item['account_contact_fax']),
					trim($item['account_address_physical']),
					trim($item['account_address_postal']),
					"<button onclick=\"deleteModal('".$item['account_code']."', '', 'default'); return false;\">Delete</button>"
				);
			}
		}

		if($accountData) {
			$response['sEcho']					= $_REQUEST['sEcho'];
			$response['iTotalRecords']			= $accountData['displayrecords'];		
			$response['iTotalDisplayRecords']	= $accountData['count'];
			$response['aaData']					= $accounts;
		} else {
			$response['result']		= false;
			$response['message']	= 'There are no items to show.';			
		}
		echo json_encode($response);
		die();
	} else {
		$row = "Site, Name, Cellphone, Email, Telephone, Fax Number, Physical Address, Postal Address\r\n";
		if($accountData) {
			for($i = 0; $i < count($accountData); $i++) {
				$item = $accountData[$i];
				$accounts[$i] = array(						
					str_replace(',', ' ',$item['account_site']),
					str_replace(',', ' ',$item['account_name']),
					str_replace(',', ' ',$item['account_contact_cellphone']),
					str_replace(',', ' ',$item['account_contact_email']),
					str_replace(',', ' ',$item['account_contact_telephone']),
					str_replace(',', ' ',$item['account_contact_fax']),
					str_replace(',', ' ',$item['account_address_physical']),
					str_replace(',', ' ',$item['account_address_postal'])
				);
			}
			foreach ($accounts as $data) {
				$row .= implode(', ', $data)."\r\n";
			}			
		}
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=account_report_".date('Y-m-d').".csv");
		Header("Cache-Control: private, must-revalidate" ); // HTTP/1.1
		Header("Pragma: private" ); // HTTP/1.0
	   
		print($row);
		exit;		
	}
}

$smarty->display('account/default.tpl');
?>