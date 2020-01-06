<?php

set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');

/** Standard includes */
require_once 'config/database.php';

require_once 'includes/auth.php';

global $smarty;

if(isset($_GET['create_bit'])) {
	
	require_once 'class/_social.php';
	 
	$socialObject = new class_social();
	
	$url = $socialObject->createBit(trim($_GET['create_bit']));
	
	echo $url;
	die();
}

if(isset($_GET['vet_select']) && trim($_GET['vet_select']) != '') {
	
	require_once 'class/item.php';
	 
	$itemObject	= new class_item();
	$vet 				= '<option value=""> ------------ </option>';
	
	$item = $itemObject->selectParents('VET', strtoupper(trim($_GET['vet_select'])));
	
	if($item) {
		while ($value = current($item)) {
			$vet .= '<option value="'.key($item).'">'.$value.'</option>';
			next($item);
		}		
	}
	echo $vet;
	die();
}

if(isset($_GET['vet_code'])) {

	require_once 'class/vet.php';
	 
	$vetObject = new class_vet();
	
	$return				= array();
	$return['error']	= '';
	$return['result']	= 1;	
	$error				= array();
	$formValid		= true;
	$success			= NULL;

	if(!isset($_REQUEST['message'])) {
		$error[]	= 'Please add a message';
	} else if(trim($_REQUEST['message']) == '') {
		$error[]	= 'Please add a message';	
	}

	if(!isset($_REQUEST['status'])) {
		$error[]	= 'Please choose a status';
	} else if(trim($_REQUEST['status']) == '') {
		$error[]	= 'Please choose a status';
	}

	if(!isset($_REQUEST['type'])) {
		$error[]	= 'Please choose a type';
	} else if(trim($_REQUEST['type']) == '') {
		$error[]	= 'Please choose a type';
	}

	if(!isset($_REQUEST['vet_code'])) {
		$error[]	= 'Please choose member to vet';
	} else if(trim($_REQUEST['vet_code']) == '') {
		$error[]	= 'Please choose member to vet';
	}

	if(count($error)  == 0) {

		$data							= array();
		$data['item_code']			= trim($_REQUEST['status']);
		$data['vet_item_type']	= strtoupper(trim($_REQUEST['type']));
		$data['vet_item_code']	= trim($_REQUEST['vet_code']);
		$data['vet_reason']		= trim($_REQUEST['message']);

		$success	= $vetObject->insert($data);	
		
		if(!$success) {
			$error[]	= 'Could not add this, please try it again.';
		}
	}
	
	if(count($error)  != 0) {
		$return['error']	= implode('<br />', $error);
		$return['result']	= 0;	
	}
	
	echo json_encode($return);
	exit;
}

if(isset($_GET['create_social'])) {

	require_once 'class/_social.php';
	 
	$socialObject = new class_social();
	
	$errorArray				= array();
	$errorArray['error']	= '';
	$errorArray['result']	= 0;	
	$formValid				= true;
	$success					= NULL;

	if(!isset($_REQUEST['type'])) {
		$errorArray['error']	= 'Please add type of post';
		$errorArray['result']	= 0;
	} else if(trim($_REQUEST['type']) == '') {
		$errorArray['error']	= 'Please add type of post';
		$errorArray['result']	= 0;		
	}

	if(!isset($_REQUEST['code'])) {
		$errorArray['error']	= 'Please add the code of the type';
		$errorArray['result']	= 0;
	} else if(trim($_REQUEST['code']) == '') {
		$errorArray['error']	= 'Please add the code of the type';
		$errorArray['result']	= 0;		
	}

	if(!isset($_REQUEST['message'])) {
		$errorArray['error']	= 'Please add the message';
		$errorArray['result']	= 0;
	} else if(trim($_REQUEST['message']) == '') {
		$errorArray['error']	= 'Please add the message';
		$errorArray['result']	= 0;		
	}

	if($errorArray['error']  == '' && $errorArray['result']  == 0 ) {

		$data	= array();
		$data['_social_item_code']	= trim($_REQUEST['code']);
		$data['_social_item_type']	= trim($_REQUEST['type']);
		$data['_social_message']	= trim($_REQUEST['message']);

		$success	= $socialObject->insert($data);	
		
		if($success) {
			$errorArray['error']	= '';
			$errorArray['result']	= 1;			
		} else {
			$errorArray['error']	= 'Could not add this, please try it again.';
			$errorArray['result']	= 0;				
		}
	}
	 
	echo json_encode($errorArray);
	exit;
}

 /* Display the template
 */	
$smarty->display('includes/javascript.tpl');
?>