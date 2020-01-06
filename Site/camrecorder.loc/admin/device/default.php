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

require_once 'class/device.php';
require_once 'class/campus.php';
require_once 'class/room.php';

$deviceObject	= new class_device(); 
$campusObject	= new class_campus(); 
$roomObject		= new class_room(); 

if(isset($_GET['delete_code'])) {
	
	$errorArray				= array();
	$errorArray['error']	= '';
	$errorArray['result']	= 0;	
	$formValid				= true;
	$success				= NULL;
	$code					= trim($_GET['delete_code']);

	if($errorArray['error']  == '' && $errorArray['result']  == 0 ) {

		$deviceData = $deviceObject->getByCode($code);
		
		if($deviceData) {
			$data							= array();
			$data['device_deleted'] 	= 1;

			$where		= $deviceObject->getAdapter()->quoteInto('device_code = ?', $code);
			$success	= $deviceObject->update($data, $where);	
			
			$errorArray['error']	= '';
			$errorArray['result']	= 1;			

		} else {
			$errorArray['error']	= 'Could not find device';
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

	$deviceData = $deviceObject->paginate($start, $length, $filter);

	$device = array();

	if($deviceData) {
		for($i = 0; $i < count($deviceData['records']); $i++) {
			$item = $deviceData['records'][$i];

			if(strtotime(trim($item['device_lastseen'])) <= strtotime(date('Y-m-d H:i:s', strtotime('-1 hour')))) {
				$lastseenstatus = '<span class="error">Check Error</spa>';
				$status = 0;
			} else {
				$lastseenstatus = '<span class="success">Okey</spa>';
				$status = 1;
			}

			$device[$i] = array(
				'<a href="/device/logs.php?code='.trim($item['device_code']).'">'.trim($item['device_code']).'</a>',
				'<a href="/device/details.php?code='.trim($item['device_code']).'" class="'.($item['device_active'] == 0 ? 'error' : 'success').'">'.trim($item['device_name']).'</a>',
				trim($item['campus_name']),
				trim($item['room_name']),
				$lastseenstatus,
				trim($item['device_lastseen']),				
				($status == 0 ? '<button value="Change Room" onclick="deleteModal(\''.$item['device_code'].'\', \'\', \'default\'); return false;">Delete</button>' : 'N/A')
			);
		}
	}

	if($deviceData) {
		$response['sEcho']					= $_REQUEST['sEcho'];
		$response['iTotalRecords']			= $deviceData['displayrecords'];		
		$response['iTotalDisplayRecords']	= $deviceData['count'];
		$response['aaData']					= $device;
	} else {
		$response['result']		= false;
		$response['message']	= 'There are no items to show.';			
	}

	echo json_encode($response);
	die();
}

$campusPairs = $campusObject->pairs();
if($campusPairs) $smarty->assign('campusPairs', $campusPairs);

$smarty->display('device/default.tpl');
?>