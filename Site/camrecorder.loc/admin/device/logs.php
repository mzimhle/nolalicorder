<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
/** * Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';
/** Check for login */
require_once 'includes/auth.php';
require_once 'class/log.php';
require_once 'class/device.php';

$logObject		= new class_log();
$deviceObject	= new class_device();

if(isset($_GET['code']) && trim($_GET['code']) != '') {

	$code = trim($_GET['code']);

	$deviceData = $deviceObject->getByCode($code);

	if($deviceData) {
		$smarty->assign('deviceData', $deviceData);
	} else {
		header('Location: /device/');
		exit;		
	}
} else {
	header('Location: /device/');
	exit;		
}

/* Setup Pagination. */
if(isset($_GET['action']) && trim($_GET['action']) == 'search') {

	$filter	= array();
	$csv	= 0;
	$start 	= isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : 0;
	$length	= isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : 20;
	
	if(isset($_REQUEST['filter_search']) && trim($_REQUEST['filter_search']) != '') $filter[] = array('filter_search' => trim($_REQUEST['filter_search']));

	$logData = $logObject->paginate($start, $length, $filter);

	$log = array();

	if($logData) {
		for($i = 0; $i < count($logData['records']); $i++) {
			$item = $logData['records'][$i];
			$log[$i] = array(
				trim($item['log_added']),
				'<a href="#" title=\''.trim($item['log_json']).'\' alt=\''.trim($item['log_json']).'\'>'.trim($item['log_type']).'</a>',
				'<span class="'.($item['log_result'] == 0 ? 'error' : 'success').'">'.trim($item['log_message']).'</span>'	
			);
		}
	}

	if($logData) {
		$response['sEcho']					= $_REQUEST['sEcho'];
		$response['iTotalRecords']			= $logData['displayrecords'];		
		$response['iTotalDisplayRecords']	= $logData['count'];
		$response['aaData']					= $log;
	} else {
		$response['result']		= false;
		$response['message']	= 'There are no items to show.';			
	}

	echo json_encode($response);
	die();
}

/* Display the template */	
$smarty->display('device/logs.tpl');
?>