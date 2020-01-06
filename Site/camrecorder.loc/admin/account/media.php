<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
/*** Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';
/* Authentication */
require_once 'includes/auth.php';
/* Files */
require_once 'class/account.php';
require_once 'class/media.php';
require_once 'class/File.php';
/* Objects */
$accountObject	= new class_account();
$mediaObject	= new class_media();
$fileObject		= new File(array('png', 'jpg', 'jpeg'));

if(isset($_GET['code']) && trim($_GET['code']) != '') {

	$code = trim($_GET['code']);

	$accountData = $accountObject->getByCode($code);

	if($accountData) {
		$smarty->assign('accountData', $accountData);

		$mediaData = $mediaObject->getByTypeCode('ACCOUNT', $accountData['account_code']);

		if($mediaData) {
			$smarty->assign('mediaData', $mediaData);
		}
	} else {
		header('Location: /account/');
		exit;	
	}
} else {
	header('Location: /account/');
	exit;	
}

/* Check posted data. */
if(isset($_GET['delete_code'])) {

	$errorArray				= array();
	$errorArray['error']	= '';
	$errorArray['result']	= 0;
	$accountcode				= trim($_GET['delete_code']);

	if($errorArray['error']  == '' && $errorArray['result']  == 0 ) {
		$data					= array();
		$data['media_deleted']	= 1;

		$where		= $mediaObject->getAdapter()->quoteInto('media_code = ?', $accountcode);
		$success	= $mediaObject->update($data, $where);	

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
/* Check posted data. */
if(isset($_GET['status_code'])) {

	$errorArray				= array();
	$errorArray['error']	= '';
	$errorArray['result']	= 0;
	$accountcode			= trim($_GET['status_code']);

	$mediaItem = $mediaObject->getByCode($accountcode);

	if($mediaItem) {
		$mediaObject->updatePrimary('ACCOUNT', $accountData['account_code'], $mediaItem['media_code'], $mediaItem['media_type'], $mediaItem['media_category']);	
	} else {
		$errorArray['error']	= 'Did not ';
		$errorArray['result']	= 1;		
	}

	$errorArray['error']	= '';
	$errorArray['result']	= 1;				

	echo json_encode($errorArray);
	exit;
}
/* Check posted data. */
if(count($_FILES) > 0) {

	$errorArray	= array();

	if(!isset($_POST['media_category'])) {
		$errorArray[] = 'Please add name of the account';	
	} else if(trim($_POST['media_category']) == '') {
		$errorArray[] = 'Please add name of the account';	
	} else {
		if(isset($_FILES['mediafiles']['name']) && count($_FILES['mediafiles']['name']) > 0) {
			for($i = 0; $i < count($_FILES['mediafiles']['name']); $i++) {
				if((int)$_FILES['mediafiles']['size'][$i] != 0 && trim($_FILES['mediafiles']['name'][$i]) != '') {
					/* Check if its the right file. */
					$ext = $fileObject->file_extention($_FILES['mediafiles']['name'][$i]); 
					if($ext != '') {
						$checkExt = $fileObject->getValidateExtention('mediafiles', $ext, $i);
						if(!$checkExt) {
							$errorArray[] = 'Invalid file type something funny with the file format';					
						}
					} else {
						$errorArray[] = 'Invalid file type';								
					}
				} else {			
					switch((int)$_FILES['mediafiles']['error'][$i]) {
						case 1 : $errorArray[] = 'The uploaded file exceeds the maximum upload file size, should be less than 1M';
						case 2 : $errorArray[] = 'File size exceeds the maximum file size';
						case 3 : $errorArray[] = 'File was only partically uploaded, please try again';
						case 4 : $errorArray[] = 'No file was uploaded';
						case 6 : $errorArray[] = 'Missing a temporary folder';
						case 7 : $errorArray[] = 'Faild to write file to disk';
					}
				}
			}
		}
		
		if(count($errorArray) == 0) {
			if(isset($_FILES['mediafiles']) && count($_FILES['mediafiles']['name']) > 0) {
				for($i = 0; $i < count($_FILES['mediafiles']['name']); $i++) {				
					$data 								= array();
					$data['media_code']			= $mediaObject->createCode();		
					$data['media_item_code']	= $accountData['account_code'];
					$data['media_item_type']	= 'ACCOUNT';
					$data['media_type']			= 'IMAGE';
					$data['media_category']		= trim($_POST['media_category']);
					$data['media_filename']		= $_FILES['mediafiles']['name'][$i];
					
					$ext		= strtolower($fileObject->file_extention($_FILES['mediafiles']['name'][$i]));					
					$filename	= $data['media_code'].'.'.$ext;		
					$directory	= $zfsession->config['path'].'/media/account/'.$accountData['account_code'].'/'.$data['media_code'];
					$file		= $directory.'/'.$filename;	

					if(!is_dir($directory)) mkdir($directory, 0777, true);

					/* Create files for this account type. */ 
					foreach($fileObject->account[$data['media_category']] as $account) {
						/* Change file name. */
						$newfilename = str_replace($filename, $account['code'].$filename, $file);
						/* Resize media. */
						$fileObject->resize_crop_image($account['width'], $account['height'], $_FILES['mediafiles']['tmp_name'][$i], $newfilename);
					}

					$data['media_path']	= '/media/account/'.$accountData['account_code'].'/'.$data['media_code'].'/';
					$data['media_ext']		= '.'.$ext ;
					/* Check for other medias. */
					$primary = $mediaObject->getPrimary('ACCOUNT', $accountData['account_code'], 'IMAGE', $data['media_category']);		

					if($primary) {
						$data['media_primary']	= 0;
					} else {
						$data['media_primary']	= 1;
					}
					$success	= $mediaObject->insert($data);	
				}
			}
			header('Location: /account/media.php?code='.$accountData['account_code']);
			exit;
		} else {
			
		}
	}
	
	/* if we are here there are errors. */
	$smarty->assign('errorArray', $errorArray);
}

/* Display the template */	
$smarty->display('account/media.tpl');
$mediaObject = $errorArray = $data = $accountData = $primary = $ext = $newfilename = $uploadObject = $account = $i = $filename = $file = $directory = $fileObject = $formValid = $checkExt = null;
unset($mediaObject, $errorArray, $data, $accountData, $primary, $ext, $newfilename, $uploadObject, $account, $i, $filename, $file, $directory, $fileObject, $formValid, $checkExt);
?>