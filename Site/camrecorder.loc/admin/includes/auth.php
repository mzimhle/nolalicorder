<?php/* Add this on all pages on top. */set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');/* standard config include. */require_once 'config/database.php';require_once 'config/smarty.php';//include the Zend class for Authentificationrequire_once 'Zend/Session.php';// Set up the namespace$zfsession	= new Zend_Session_Namespace('MAILBOK_ADMIN');// Check if logged in, otherwise redirectif (!isset($zfsession->identity) || is_null($zfsession->identity) || $zfsession->identity == '') {	header('Location: /login.php');	exit();} else {	//instantiate the admin class	require_once 'class/admin.php';	require_once 'class/account.php';	// objects	$adminObject	= new class_admin();	$accountObject	= new class_account();	//get user details by username	$adminData = $adminObject->getByCode($zfsession->identity);	$smarty->assign('adminData', $adminData);	/* administrator selected page. */	$zfsession->adminData	= $adminData;	/* Get configuration settings. */	$zfsession->config		= $config;	if($zfsession->account != null) {		$activeAccount = $accountObject->getByCode($zfsession->account);		if($activeAccount) {			$zfsession->activeAccount = $activeAccount;			$smarty->assign('account', $zfsession->account);			$smarty->assign('activeAccount', $activeAccount);		}	}	unset($accountObject, $adminObject, $adminData, $activeAccount);}global $zfsession;$smarty->assign('config', $zfsession->config);?>