<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
/*** Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';
//include the Zend class for Authentification
require_once 'Zend/Session.php';
// Set up the namespace
$zfsession	= new Zend_Session_Namespace('CUT');

/* Class file. */
require_once 'class/account.php';
/* Class object. */
$accountObject	= new class_account();	
/* Get the account by site. */
$activeAccount = $accountObject->getBySite(trim($_SERVER['HTTP_HOST']));
/* Check if it exists. */
if(!$activeAccount) {
	header('Location: /404');
	exit;
}
/* Add to the session. */
$zfsession->activeAccount	= $activeAccount;
$zfsession->account 		= $activeAccount['account_code'];
$zfsession->config			= $config;

unset($accountObject);
/* Assign config since it exists. */
$smarty->assign('config', $config);
$smarty->assign('account', $zfsession->account);
$smarty->assign('activeAccount', $zfsession->activeAccount);	

/* Check posted data. */
if(count($_POST) > 0 && isset($_POST['Participantlogin'])) {
	/* Class file. */
	require_once 'class/participant.php';
	/* Class object. */
	$participantObject = new class_participant();
	/* Return data. */
	$errorArray	= array();
	if(!isset($_POST['participant_password'])) {
		$errorArray[] = 'Password required';	
	} else if(trim($_POST['participant_password']) == '') {
		$errorArray[] = 'Password required';	
	}

	if(!isset($_POST['participant_username'])) {
		$errorArray[] = 'Please add a username ( email or cellphone )';
	} else if(trim($_POST['participant_username']) == '') {
		$errorArray[] = 'Please add a username ( email or cellphone )';
	} else {
		if($participantObject->validateEmail(trim($_POST['participant_username'])) == '') {
			$errorArray[] = 'Please add your email address';
		}
	}

	if(count($errorArray) == 0) {
		$participantData = $participantObject->login(trim($_POST['participant_username']), trim($_POST['participant_password']));
		if($participantData) {
			$zfsession->activeParticipant	= $participantData;
			$zfsession->identity			= $participantData['participant_code'];
		} else {
			$errorArray[] = 'Username and Password not found in the system';
		}
	}
	echo json_encode($errorArray);
	exit;
}

/* Display the template */	
$smarty->display('login.tpl');
?>