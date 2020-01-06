<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
/*** Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';

require_once 'includes/auth.php';
require_once 'class/account.php';

$accountObject 	= new class_account();

/* Check posted data. */
if(count($_POST) > 0) {

	$errorArray	= array();

	if(isset($_POST['account_code']) && trim($_POST['account_code']) == '') {
		$errorArray['account_code'] = 'Account is required';
		$formValid = false;		
	} else {
		/* Get the account. */
		$accountData = $accountObject->getByCode(trim($_POST['account_code']));

		if($accountData) {
			$zfsession->activeAccount	= $accountData;
			$zfsession->account		= $accountData['account_code'];
			header('Location: /');
			exit;			
		}	
	}
	/* if we are here there are errors. */
	$smarty->assign('errorArray', $errorArray);	
}

$accountPairs = $accountObject->pairs();
if($accountPairs) $smarty->assign('accountPairs', $accountPairs);

/* Display the template */	
$smarty->display('default.tpl');

?>