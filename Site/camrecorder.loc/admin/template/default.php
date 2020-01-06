<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
/*** Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';
/** Check for login */
require_once 'includes/auth.php';

require_once 'class/template.php';
require_once 'class/account.php';

$templateObject	= new class_template(); 
$accountObject	= new class_account(); 

if(isset($_GET['delete_code'])) {

	$errorArray				= array();
	$errorArray['error']	= '';
	$errorArray['result']	= 0;	
	$formValid				= true;
	$code					= trim($_GET['delete_code']);

	if($errorArray['error']  == '' && $errorArray['result']  == 0 ) {

		$data						= array();
		$data['template_deleted']	= 1;

		$where		= $templateObject->getAdapter()->quoteInto('template_code = ?', $code);
		$success	= $templateObject->update($data, $where);	

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

$templateData = $templateObject->getAll();
if($templateData) $smarty->assign('templateData', $templateData);

$accountPairs = $accountObject->pairs();
if($accountPairs) $smarty->assign('accountPairs', $accountPairs);

$smarty->display('template/default.tpl');

?>