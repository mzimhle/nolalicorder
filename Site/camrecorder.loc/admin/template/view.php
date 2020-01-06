<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');

/*** Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';
/*** Check for login */
require_once 'includes/auth.php';
/* objects. */
require_once 'class/template.php';

$templateObject	= new class_template();

if (isset($_GET['code']) && trim($_GET['code']) != '') {
	
	$code = trim($_GET['code']);
	
	$templateData = $templateObject->getByCode($code);

	if(!$templateData) {
		header('Location: /template/');
		exit;		
	}
} else {
	header('Location: /template/');
	exit;		
}
$html = file_get_contents($zfsession->config['path'].$templateData['template_file']);
$html = str_replace('[media]', $zfsession->config['site'].'/media/template/'.$templateData['template_code'].'/media/', $html);
$html = str_replace('[account_name]', 'Mzimhle Mosiwe', $html);
$html = str_replace('[account_email]', 'info@amathole.gov.za', $html);
$html = str_replace('[account_password]', '1kdu3', $html);
$html = str_replace('[account_cellphone]', '0812569874', $html);
$html = str_replace('[participant_name]', 'Mzimhle', $html);
$html = str_replace('[participant_surname]', 'Mosiwe', $html);
$html = str_replace('[participant_cellphone]', '0735640764', $html);
$html = str_replace('[participant_code]', 'DKE84KDS', $html);
$html = str_replace('[participant_hashcode]', 'ksdii48fs8e28438458fd8so3ldfg848fj', $html);
$html = str_replace('[participant_email]', 'mzimhle.mosiwe@gmail.com', $html);
$html = str_replace('[participant_password]', 'd4kdw8', $html);
$html = str_replace('[message]', 'This is a message, please write it somewhere...', $html);
$html = str_replace('[enquiry_name]', 'Mzimhle Mosiwe', $html);
$html = str_replace('[enquiry_email]', 'mzimhle.mosiwe@gmail.com', $html);
$html = str_replace('[enquiry_number]', '0735640764', $html);
$html = str_replace('[tracking]', '8472742o460894juf74fj723hf734fyf74', $html);		
$html = str_replace('[date]', date("F j, Y, g:i a"), $html);
$html = str_replace('[browser]', '<a href="http://[account_site]/mailer/view/8472742o460894juf74fj723hf734fyf74">View mail on browser</a>', $html);
$html = str_replace('[account_site]', 'camRecorder.loc', $html);
echo $html;
exit;
?>