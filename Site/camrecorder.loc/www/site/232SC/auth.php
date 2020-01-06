<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
/* standard config include. */
require_once 'config/database.php';
require_once 'config/smarty.php';
//include the Zend class for Authentification
require_once 'Zend/Session.php';

global $config;

// Set up the namespace
$zfsession	= new Zend_Session_Namespace('CUT');
// $zfsession->account = null;
// Check if logged in, otherwise redirect
if (!isset($zfsession->account) || is_null($zfsession->account) || $zfsession->account == '') {
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
}
// Check if logged in, otherwise redirect
if (isset($zfsession->identity) || !is_null($zfsession->identity) || $zfsession->identity != '') {
	/* Class file. */
	require_once 'class/participant.php';
	/* Class object. */
	$participantObject	= new class_participant();	
	/* Get the participant by site. */
	$activeParticipant = $participantObject->getByCode($zfsession->identity);
	/* Check if it exists. */
	if(!$activeParticipant) {
		header('Location: /logout.php');
		exit;
	}
	/* Add to the session. */
	$zfsession->activeParticipant	= $activeParticipant;
	$zfsession->identity			= $activeParticipant['participant_code'];
	/* Clear the participant object. */
	unset($participantObject);
	/* Assign to smarty. */
	$smarty->assign('activeParticipant', $zfsession->activeParticipant);
} else {
	header('Location: /login.php');
	exit;
}

/* Assign config since it exists. */
$smarty->assign('config', $config);
$smarty->assign('account', $zfsession->account);
$smarty->assign('activeAccount', $zfsession->activeAccount);

if(isset($zfsession->participant)) $smarty->assign('participant', $zfsession->participant);

global $zfsession;
?>