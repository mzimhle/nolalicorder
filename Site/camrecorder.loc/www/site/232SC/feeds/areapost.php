<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
/*** Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';
/* Get the auth. */
require_once 'site/khadok/auth.php';
/* Class file. */
require_once 'class/areapost.php';
/* Class object. */
$areapostObject	= new class_areapost();

$results    = array();
$list       = array();	

if(isset($_REQUEST['term'])) {

	$q				= strtolower(trim($_REQUEST['term'])); 
	$areapostData	= $areapostObject->search($q);

	if($areapostData) {
		for($i = 0; $i < count($areapostData); $i++) {
			$list[] = array(
				"id"	=> $areapostData[$i]["areapost_code"],
				"label"	=> $areapostData[$i]['areapost_name'],
				"value"	=> $areapostData[$i]['areapost_name'],
			);			
		}	
	}
}

if(count($list) > 0) {
	echo json_encode($list); 
	exit;
} else {
	echo json_encode(array('id' => '', 'label' => 'no results')); 
	exit;
}
exit;
?>