<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
/*** Standard includes */
require_once 'config/database.php';
require_once 'config/smarty.php';
/*** Check for login */
require_once 'includes/auth.php';
/* class files. */
require_once 'class/account.php';
require_once 'class/template.php';
require_once 'class/File.php';
/* class object */
$accountObject	= new class_account();
$templateObject		= new class_template();
/* class file objects */
$htmlObject		= new File(array('html', 'htm'));
$imageObject	= new File(array('jpg', 'jpeg', 'png', 'gif', 'ico', 'css'));

if (isset($_GET['code']) && trim($_GET['code']) != '') {
	
	$code = trim($_GET['code']);
	
	$templateData = $templateObject->getByCode($code);

	if($templateData) {
		$templateData['template_path'] = str_replace($zfsession->config['path'], '', $templateData['template_file']);
		$smarty->assign('templateData', $templateData);
	} else {
		header('Location: /template/');
		exit;		
	}
}

/* Check posted data. */
if(count($_POST) > 0) {
	$errorArray	= array();
	$data		= array();

	if(!isset($_POST['template_cipher'])) {
		$errorArray['template_cipher'] = 'Please add a cipher';	
	} else if(trim($_POST['template_cipher']) == '') {
		$errorArray['template_cipher'] = 'Please add a cipher';	
	}

	if(!isset($_POST['template_type'])) {
		$errorArray['template_type'] = 'Please add a type';	
	} else if(trim($_POST['template_type']) == '') {
		$errorArray['template_type'] = 'Please add a type';	
	}
	
	if(!isset($_POST['template_category'])) {
		$errorArray['template_category'] = 'Please add a category';	
	} else if(trim($_POST['template_category']) == '') {
		$errorArray['template_category'] = 'Please add a category';	
	} else {

		$category = trim($_POST['template_category']);

		if($category == 'EMAIL') {	
			if(!isset($_POST['template_subject'])) {
				$errorArray['template_subject'] = 'Please add a subject';	
			} else if(trim($_POST['template_subject']) == '') {
				$errorArray['template_subject'] = 'Please add a subject';	
			}
		} else if($category == 'SMS') {
			if(!isset($_POST['template_message'])) {
				$errorArray['template_message'] = 'Please add a message';	
			} else if(trim($_POST['template_message']) == '') {
				$errorArray['template_message'] = 'Please add a message';	
			} else if(strlen(trim($_POST['template_message'])) > 320) {
				$errorArray['template_message'] = 'Message needs to be less than 140 characters';	
			}
		} else {
			/* Check if it already exists. */
			$temp = isset($templateData) ? $templateData['template_code'] : null;
			/* Fetch in checking if it exists. */
			$templateTemp = $templateObject->getTemplate(trim($_POST['template_category']), trim($_POST['template_type']), trim($_POST['template_cipher']), $temp);
			
			if($templateTemp) {
				$errorArray['template_cipher'] = 'Template category, cipher and type already exists, please use another cipher.';
			}
		}
	}

	if(count($errorArray) == 0) {
		/* Add the details. */
		$data						= array();				
		$data['template_category']	= trim($_POST['template_category']);
		$data['template_cipher']	= trim($_POST['template_cipher']);
		$data['template_type']		= trim($_POST['template_type']);
		$data['template_public']	= (int)trim($_POST['template_public']);
		/* Check category if its email or sms. */
		if($category == 'SMS') {
			$data['template_message']	= trim($_POST['template_message']);
		} else if($category == 'EMAIL') {
			$data['template_subject']	= trim($_POST['template_subject']);
		}
		/* Insert or update. */
		if(!isset($templateData)) {
			$success = $templateObject->insert($data);				
		} else {
			$where		= $templateObject->getAdapter()->quoteInto('template_code = ?', $templateData['template_code']);
			$templateObject->update($data, $where);		
			$success	= $templateData['template_code'];			
		}

		if($success && isset($category) && $category == 'EMAIL') {
			/* Upload the html. */
			if(isset($_FILES['htmlfile']['name']) && trim($_FILES['htmlfile']['name']) != '') {
				/* Check validity of the CV. */
				if((int)$_FILES['htmlfile']['size'] != 0 && trim($_FILES['htmlfile']['name']) != '') {
					/* Check if its the right file. */
					$ext = $htmlObject->file_extention($_FILES['htmlfile']['name']); 

					if($ext != '') {
						$checkExt = $htmlObject->getValidateExtention('htmlfile', $ext);

						if(!$checkExt) {
							$errorArray['htmlfile'] = 'Invalid file type something funny with the file format';
						}
					} else {
						$errorArray['htmlfile'] = 'Invalid file type';								
					}
				} else {
					switch((int)$_FILES['htmlfile']['error']) {
						case 1 : $errorArray['htmlfile'] = 'The uploaded file exceeds the maximum upload file size, should be less than 1M'; $formValid = false; break;
						case 2 : $errorArray['htmlfile'] = 'File size exceeds the maximum file size'; $formValid = false; break;
						case 3 : $errorArray['htmlfile'] = 'File was only partically uploaded, please try again'; $formValid = false; break;
						case 4 : $errorArray['htmlfile'] = 'No file was uploaded'; $formValid = false; break;
						case 6 : $errorArray['htmlfile'] = 'Missing a temporary folder'; $formValid = false; break;
						case 7 : $errorArray['htmlfile'] = 'Faild to write file to disk'; $formValid = false; break;
					}
				}
				/* Upload the html file. */
				if(!isset($errorArray['htmlfile'])) {

					$ext		= strtolower($htmlObject->file_extention($_FILES['htmlfile']['name']));
					$filename	= $success.'.'.$ext;		
					$directory	= $zfsession->config['path'].'/media/template/'.strtolower($success).'/';
					$file		= $directory.strtolower($filename);	

					if(!is_dir($directory)) mkdir($directory, 0750, true); 

					if(file_put_contents($file,file_get_contents($_FILES['htmlfile']['tmp_name']))) {

						$data					= array();
						$data['template_file']	= '/media/template/'.strtolower($success).'/'.strtolower($filename);

						$where		= $templateObject->getAdapter()->quoteInto('template_code = ?', $success);
						$templateObject->update($data, $where);							
					}
				}
			}
			/* Upload images. */
			if(isset($_FILES['imagefiles']['name']) && count($_FILES['imagefiles']['name']) > 0) {
				for($i = 0; $i < count($_FILES['imagefiles']['name']); $i++) {
					if((int)$_FILES['imagefiles']['size'][$i] != 0 && trim($_FILES['imagefiles']['name'][$i]) != '') {
						/* Check if its the right file. */
						$ext = $imageObject->file_extention($_FILES['imagefiles']['name'][$i]); 

						if($ext != '') {
							$checkExt = $imageObject->getValidateExtention('imagefiles', $ext, $i);
							if(!$checkExt) {
								$errorArray['imagefiles'] = 'Invalid file type something funny with the file format';
								$formValid = false;						
							}
						} else {
							$errorArray['imagefiles'] = 'Invalid file type';
							$formValid = false;									
						}
					} else {			
						switch((int)$_FILES['imagefiles']['error'][$i]) {
							case 1 : $errorArray['imagefiles'] = 'The uploaded file exceeds the maximum upload file size, should be less than 1M'; $formValid = false; break;
							case 2 : $errorArray['imagefiles'] = 'File size exceeds the maximum file size'; $formValid = false; break;
							case 3 : $errorArray['imagefiles'] = 'File was only partically uploaded, please try again'; $formValid = false; break;
							case 4 : $errorArray['imagefiles'] = 'No file was uploaded'; $formValid = false; break;
							case 6 : $errorArray['imagefiles'] = 'Missing a temporary folder'; $formValid = false; break;
							case 7 : $errorArray['imagefiles'] = 'Faild to write file to disk'; $formValid = false; break;
						}
					}
				}
				/* Upload images */
				if(!isset($errorArray['imagefiles'])) {
					if(isset($_FILES['imagefiles']) && count($_FILES['imagefiles']['name']) > 0) {
						for($i = 0; $i < count($_FILES['imagefiles']['name']); $i++) {
							if($_FILES['imagefiles']['name'][$i] != '') {
								$directory	= $zfsession->config['path'].'\media\template\\'.strtolower($success).'\media\\';
								$file		= $directory.$_FILES['imagefiles']['name'][$i];	

								if(!is_dir($directory)) mkdir($directory, 0777, true); 

								file_put_contents($file,file_get_contents($_FILES['imagefiles']['tmp_name'][$i]));
							}
						}
					}
				}
			}
		}
	}
	/* Check errors and redirect if there are non. */
	if(count($errorArray) != 0) {
		/* if we are here there are errors. */
		$smarty->assign('errorArray', $errorArray);
	} else {
		header('Location: /template/');
		exit;
	}
}

$smarty->display('template/details.tpl');

?>