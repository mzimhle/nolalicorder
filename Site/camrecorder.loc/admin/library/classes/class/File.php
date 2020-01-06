<?php

require_once 'phpThumb/ThumbLib.inc.php';

class File {

	public $allowedExtensions = array();

	public $account = array(
		'BANNER' => array(
			'tiny'		=> array('width' => '220', 'height' => '180', 'code' => 'tny_'),
			'min'		=> array('width' => '440', 'height' => '360', 'code' => 'min_'),
			'thumb'		=> array('width' => '660', 'height' => '540', 'code' => 'tmb_'),
			'big'		=> array('width' => '880', 'height' => '720', 'code' => 'big_'),
			'original'	=> array('width' => '1100', 'height' => '900', 'code' => 'orig_'),
		),
		'LOGO' => array(
			'tiny'		=> array('width' => '220', 'height' => '180', 'code' => 'tny_'),
			'min'		=> array('width' => '440', 'height' => '360', 'code' => 'min_'),
			'thumb'		=> array('width' => '660', 'height' => '540', 'code' => 'tmb_'),
			'big'		=> array('width' => '880', 'height' => '720', 'code' => 'big_'),
			'original'	=> array('width' => '1100', 'height' => '900', 'code' => 'orig_'),
		),
		'HEADER' => array(
			'tiny'		=> array('width' => '220', 'height' => '180', 'code' => 'tny_'),
			'min'		=> array('width' => '440', 'height' => '360', 'code' => 'min_'),
			'thumb'		=> array('width' => '660', 'height' => '540', 'code' => 'tmb_'),
			'big'		=> array('width' => '880', 'height' => '720', 'code' => 'big_'),
			'original'	=> array('width' => '1100', 'height' => '900', 'code' => 'orig_'),
		),
		'FOOTER' => array(
			'tiny'		=> array('width' => '220', 'height' => '180', 'code' => 'tny_'),
			'min'		=> array('width' => '440', 'height' => '360', 'code' => 'min_'),
			'thumb'		=> array('width' => '660', 'height' => '540', 'code' => 'tmb_'),
			'big'		=> array('width' => '880', 'height' => '720', 'code' => 'big_'),
			'original'	=> array('width' => '1100', 'height' => '900', 'code' => 'orig_'),
		)
	);
	
	public $mime_types2 = array(
        'pdf' => 'application/pdf',
		'ico' => 'image/vnd.microsoft.icon'
	);
	
    public $mime_types = array(
            'txt' 	=> 'text/plain',
            'htm'	=> 'text/html',
            'html' 	=> 'text/html',
            'php' 	=> 'text/html',
            'css'	=> 'text/css',
            'js' 	=> 'application/javascript',
            'json' 	=> 'application/json',
            'xml' 	=> 'application/xml',
            'swf' 	=> 'application/x-shockwave-flash',
            'flv' 	=> 'video/x-flv',

            // images
            'png' 	=> 'image/png',
            'jpeg' 	=> 'image/jpeg',
            'jpg' 	=> 'image/jpeg',
            'gif' 	=> 'image/gif',
            'bmp' 	=> 'image/bmp',
            'ico' 	=> 'image/x-icon',
            'tiff' 	=> 'image/tiff',
            'tif' 	=> 'image/tiff',
            'svg' 	=> 'image/svg+xml',
            'svgz' 	=> 'image/svg+xml',

            // archives
            'zip' 	=> 'application/zip',
            'rar' 	=> 'application/x-rar-compressed',
            'exe' 	=> 'application/x-msdownload',
            'msi' 	=> 'application/x-msdownload',
            'cab' 	=> 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' 	=> 'audio/mpeg',
            'qt' 	=> 'video/quicktime',
            'mov' 	=> 'video/quicktime',

            // adobe
			'pdf' 	=> 'application/octet-streamn',
            'psd' 	=> 'image/vnd.adobe.photoshop',
            'ai' 	=> 'application/postscript',
            'eps' 	=> 'application/postscript',
            'ps' 	=> 'application/postscript',

            // ms office
            'doc' 	=> 'application/msword',
            'rtf' 	=> 'application/rtf',
            'xls' 	=> 'application/vnd.ms-excel',
            'ppt' 	=> 'application/vnd.ms-powerpoint',
			'docx' 	=> 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'csv' 	=> 'text/csv',
            // open office
            'odt' 	=> 'application/vnd.oasis.opendocument.text',
            'ods' 	=> 'application/vnd.oasis.opendocument.spreadsheet',
        );	
		    		
	function __construct($allowedExtPar = array()) {

		$this->allowedExtensions = $allowedExtPar;
	}
	
	public function valideExt($ext) {
		
		if(in_array($ext, $this->allowedExtensions)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function file_content_type($filename) {
		if(function_exists('mime_content_type')) {
			return mime_content_type($filename);
		} else {
		 $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $this->mime_types)) {
            return $this->mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return '';
        }
		}
	}
	
	public function file_extention($filename) {
		return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
	}
	
	//resize and crop image by center
	function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80) {
	
		$imgsize	= getimagesize($source_file);
		$width 		= $imgsize[0];
		$height 	= $imgsize[1];
		$mime 		= $imgsize['mime'];

		switch($mime){
			case 'image/gif':
				$image_create = "imagecreatefromgif";
				$image = "imagegif";
				break;
	 
			case 'image/png':
				$image_create = "imagecreatefrompng";
				$image = "imagepng";
				$quality = 7;
				break;
	 
			case 'image/jpeg':
				$image_create = "imagecreatefromjpeg";
				$image = "imagejpeg";
				$quality = 80;
			   break;
			   
			 case 'image/jpg':
				$image_create = "imagecreatefromjpeg";
				$image = "imagejpeg";
				$quality = 80;
				break;
			default:
				return false;
				break;
		}
		 
		$dst_img = imagecreatetruecolor($max_width, $max_height);
		$src_img = $image_create($source_file);
		 
		$width_new = $height * $max_width / $max_height;
		$height_new = $width * $max_height / $max_width;
		//if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
		if($width_new > $width){
			//cut point by height
			$h_point = (($height - $height_new) / 2);
			//copy image
			imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
		}else{
			//cut point by width
			$w_point = (($width - $width_new) / 2);
			imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
		}
		 
		$image($dst_img, $dst_dir, $quality);
	 
		if($dst_img)imagedestroy($dst_img);
		if($src_img)imagedestroy($src_img);
	}
	
	public function getValidateExtention($name, $ext, $i = '') {

		if($i === '') {
			$mime = array_search($_FILES[$name]['type'], $this->mime_types); 
			if(!$mime) {
				$mime = array_search($_FILES[$name]['type'], $this->mime_types2); 
			}

			if($this->valideExt($ext)) {			
				return $mime;
			} else {
				return false;
			}
		} else {		
			$mime = array_search($_FILES[$name]['type'][$i], $this->mime_types); 
			
			if(!$mime) {
				$mime = array_search($_FILES[$name]['type'][$i], $this->mime_types2); 
			}

			if($this->valideExt($ext)) {			
				return $mime;
			} else {
				return false;
			}		
		}
	}
	
	public function getValidMimeType($name, $ext) {
		$mime = array_search($_FILES[$name]['type'], $this->mime_types); 
		if(!$mime) {
			$mime = array_search($_FILES[$name]['type'], $this->mime_types2); 
		}
		if($this->valideExt($ext)) {			
			return $mime;
		} else {
			return '';
		}
	}
	
	public function getMimeType($ext) {
		if($this->valideExt($ext)) {						
			return $this->mime_types[$ext];
		} else {
			return '';
		}		
	}
	
	public function getRandomFileName($appendname = null) {		
		if($appendname == null) {
			return time();
		} else {
			return $appendname.'_'.time();
		}
	} 
	
	public function getValidateSize($size) {
		if($this->allowedCVSize() < $size) {
			return false;
		} else {
			return true;
		}
	}
	
	public function allowedCVSize() {
		return 2000000;
		
	}
	
	public function getFileContents($fullpath) {
	
		return file_get_contents($fullpath);
	}
}
?>