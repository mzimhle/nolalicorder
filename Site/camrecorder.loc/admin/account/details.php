<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');

/*** Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';
/*** Check for login */
require_once 'includes/auth.php';
/* Class files */
require_once 'class/account.php';
/* Class objects */
$accountObject	= new class_account(); 

if(isset($_GET['code']) && trim($_GET['code']) != '') {

	$code = trim($_GET['code']);

	$accountData = $accountObject->getByCode($code);

	if($accountData) {
		$smarty->assign('accountData', $accountData);
	} else {
		header('Location: /account/');
		exit;		
	}
}

/* Check posted data. */
if(count($_POST) > 0) {

	$errorArray	= array();
	$data		= array();

	if(!isset($_POST['account_name'])) {
		$errorArray['account_name'] = 'Please add name of the account';	
	} else if(trim($_POST['account_name']) == '') {
		$errorArray['account_name'] = 'Please add name of the account';	
	}

	if(!isset($_POST['account_address_physical'])) {
		$errorArray['account_address_physical'] = 'Please add a physical address of the account';	
	} else if(trim($_POST['account_address_physical']) == '') {
		$errorArray['account_address_physical'] = 'Please add a physical address of the account';	
	}

	if(!isset($_POST['account_contact_email'])) {
		$errorArray['account_contact_email'] = 'Please add an email';
	} else if(trim($_POST['account_contact_email']) == '') {
		$errorArray['account_contact_email'] = 'Please add an email';
	} else if($accountObject->validateEmail(trim($_POST['account_contact_email'])) == '') {
		$errorArray['account_contact_email'] = 'Please add a valid email address';
	} else {
		$tempcode = isset($accountData) ? $accountData['account_code'] : null;
		$tempData = $accountObject->getByEmail(trim($_POST['account_contact_email']), $tempcode);
		
		if($tempData) {
			$errorArray['account_contact_email'] = 'Email address is being used, please try a different one.';
		}
	}

	if(!isset($_POST['account_contact_cellphone'])) {
		$errorArray['account_contact_cellphone'] = 'Please add a telephone';
	} else if(trim($_POST['account_contact_cellphone']) == '') {
		$errorArray['account_contact_cellphone'] = 'Please add a telephone';
	} else if($accountObject->validateNumber(trim($_POST['account_contact_cellphone'])) == '') {
		$errorArray['account_contact_cellphone'] = 'Please add a valid telephone';
	} else {
		$tempcode = isset($accountData) ? $accountData['account_code'] : null;
		$tempData = $accountObject->getByCell(trim($_POST['account_contact_cellphone']), $tempcode);
		
		if($tempData) {
			$errorArray['account_contact_cellphone'] = 'Cellphone number is being used, please try a different one.';
		}
	}

	if(isset($_POST['account_contact_telephone']) && trim($_POST['account_contact_telephone']) != '') {
		if($accountObject->validateNumber(trim($_POST['account_contact_telephone'])) == '') {
			$errorArray['account_contact_telephone'] = 'Please add a valid telephone';
		}
	}
	
	if(count($errorArray) == 0) {
		/* Add the details. */
		$data								= array();				
		$data['account_name']				= trim($_POST['account_name']);
		$data['account_site']				= trim($_POST['account_site']);
		$data['account_contact_cellphone']	= trim($_POST['account_contact_cellphone']);
		$data['account_contact_telephone']	= trim($_POST['account_contact_telephone']);
		$data['account_contact_email']		= trim($_POST['account_contact_email']);
		$data['account_contact_fax']		= trim($_POST['account_contact_fax']);
		$data['account_social_twitter']		= trim($_POST['account_social_twitter']);
		$data['account_social_instagram']	= trim($_POST['account_social_instagram']);
		$data['account_social_facebook']	= trim($_POST['account_social_facebook']);
		$data['account_social_linkedin']	= trim($_POST['account_social_linkedin']);
		$data['account_address_physical']	= trim($_POST['account_address_physical']);
		$data['account_address_postal']		= trim($_POST['account_address_postal']);
		/* Insert or update. */
		if(!isset($accountData)) {
			$success = $accountObject->insert($data);				
		} else {
			$where		= $accountObject->getAdapter()->quoteInto('account_code = ?', $accountData['account_code']);
			$accountObject->update($data, $where);		
			$success	= $accountData['account_code'];			
		}
		// Check if the folders have been created.
		if(!is_dir($zfsession->config['path'].'/site/'.$success)) {
			if(!is_dir($zfsession->config['path'].'/site/'.$success)) mkdir($zfsession->config['path'].'/site/'.$success, 0777, true);
		}
	}
	/* Check errors and redirect if there are non. */
	if(count($errorArray) != 0) {
		/* if we are here there are errors. */
		$smarty->assign('errorArray', $errorArray);
	} else {
		header('Location: /account/');
		exit;
	}
}

$smarty->display('account/details.tpl');
?>