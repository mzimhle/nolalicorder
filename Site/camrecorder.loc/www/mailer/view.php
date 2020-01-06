<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
/**
 * Standard includes
 */
require_once 'config/database.php';

$array = explode('/',$_SERVER['REQUEST_URI']);

if (isset($array[3]) && trim($array[3]) != '') {

	/* class files */
	require_once 'class/_comm.php';
	/* class object */
	$commObject	= new class_comm();
	
	$code = trim($array[3]);

	$commData = $commObject->getByCode($code);

	if($commData) {
		if($commData['_comm_type'] == 'EMAIL') {
			$html = $commData['_comm_html'];
			$html = str_replace('[media]', 'http://'.$_SERVER['HTTP_HOST'].'/media/template/'.$commData['template_code'].'/media/', $html);
			echo $html;
		} else {
			echo $commData['_comm_message'];
		}
	} else {
		header('Location: /');
		exit;
	}
} else {
	header('Location: /');
	exit;
}

?>