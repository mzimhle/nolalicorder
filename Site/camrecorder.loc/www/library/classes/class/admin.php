<?php
require_once '_comm.php';
//custom admin item class as admin table abstraction
class class_admin extends Zend_Db_Table_Abstract
{
   //declare table variables
    protected $_name 		= 'admin';
	protected $_primary 	= 'admin_code';

	public 	$_comm		= null;

	function init()	{
		$this->_comm	= new class_comm();
	}
	/**
	 * Insert the database record
	 * example: $table->insert($data);
	 * @param array $data
     * @return boolean
	 */ 
	public function insert(array $data) {
		$data['admin_code']	= $this->createCode(); 
		$success = parent::insert($data);	

		if($success) {
			$adminData = $this->getByCode($success);
			if($adminData) {
				if($adminData['admin_email'] != '' && $adminData['participant_code'] != '') {
					$templateData = $this->_comm->_template->getTemplate('EMAIL', 'ADMIN', 'REGISTERED');
					if($templateData) {
						$recipient							= $adminData;
						$recipient['recipient_code'] 		= $adminData['admin_code'];
						$recipient['recipient_name'] 		= $adminData['admin_name'];
						$recipient['recipient_password']	= $adminData['admin_password'];
						$recipient['recipient_cellphone'] 	= $adminData['admin_cellphone'];
						$recipient['recipient_type'] 		= 'ADMIN';
						$recipient['recipient_email'] 		= $adminData['admin_email'];
						$this->_comm->sendEmail($recipient, $templateData);
					}
				}
				if($adminData['admin_cellphone'] != '' && $adminData['participant_code'] != '') {
					$templateData = $this->_comm->_template->getTemplate('SMS', 'ADMIN', 'REGISTERED');
					if($templateData) {
						$recipient							= $adminData;
						$recipient['recipient_code'] 		= $adminData['admin_code'];
						$recipient['recipient_name'] 		= $adminData['admin_name'];
						$recipient['recipient_password']	= $adminData['admin_password'];
						$recipient['recipient_cellphone'] 	= $adminData['admin_cellphone'];
						$recipient['recipient_type'] 		= 'ADMIN';
						$recipient['recipient_email']		= $adminData['admin_email'];
						$this->_comm->sendSMS($recipient, $templateData);
					}
				}
			} else {
				return false;
			}
			return $success;
		} else {
			return false;
		}
    }
	/**
	 * Update the database record
	 * example: $table->update($data, $where);
	 * @param query string $where
	 * @param array $data
     * @return boolean
	 */
    public function update(array $data, $where)
    {
        return parent::update($data, $where);
    }
	/**
	 * get admin by admin Id
 	 * @param string id
     * @return object
	 */
	public function getByCode($code) {	
		$select = $this->_db->select()	
			->from(array('admin' => 'admin'))
			->where('admin_code = ?', $code)
			->limit(1);
       
		$result = $this->_db->fetchRow($select);
        return ($result == false) ? false : $result = $result;

	}
	/**
	 * Check user login
	 * example: $table->checkLogin($username, $admin_password);
	 * @param query string $username
	 * @param query string $admin_password
     * @return boolean
	 */
	public function checkLogin($username = '', $admin_password= '') {
		$select = $this->_db->select()	
			->from(array('admin' => 'admin'))	
			->where('admin_email = ?', $username)
			->where('admin_password = ?', $admin_password);

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;
	}

	public function paginate($start, $length, $filter = array()) {
	
		$where	= 'admin_deleted = 0';
		$csv	= 0;

		if(count($filter) > 0) {
			for($i = 0; $i < count($filter); $i++) {
				if(isset($filter[$i]['filter_search']) && trim($filter[$i]['filter_search']) != '') {
					$array = explode(" ",trim($filter[$i]['filter_search']));					
					if(count($array) > 0) {
						$where .= " and (";
						for($s = 0; $s < count($array); $s++) {
							$text = $array[$s];
							$this->sanitize($text);
							$where .= "lower(concat_ws(admin_name, admin_email, admin_cellphone)) like lower('%$text%')";
							if(($s+1) != count($array)) {
								$where .= ' or ';
							}
						}
					}
					$where .= ")";
				} else if(isset($filter[$i]['filter_csv']) && (int)trim($filter[$i]['filter_csv']) == 1) {
					$csv = 1;
				}
			}
		}

		$select = $this->_db->select()
			->from(array('admin' => 'admin'), array('admin_code', 'admin_name', 'admin_email', 'admin_cellphone', 'admin_password'))
			->where('admin_deleted = 0')	
			->where($where);

		if($csv) {
			$result = $this->_db->fetchAll($select);
			return ($result == false) ? false : $result = $result;	
		} else {
			$result_count = $this->_db->fetchRow("select count(*) as query_count from ($select) as query");
			$result = $this->_db->fetchAll($select . " limit $start, $length");
			return ($result === false) ? false : $result = array('count'=>$result_count['query_count'],'displayrecords'=>count($result),'records'=>$result);
		}
	}
	/**
	 * get job by job users Id
 	 * @param string job id
     * @return object
	 */
	public function getByEmail($email, $code = null) {
		if($code == null) {
			$select = $this->_db->select()	
				->from(array('admin' => 'admin'))							
				->where('admin_email = ?', $email)
				->limit(1);
       } else {
			$select = $this->_db->select()	
				->from(array('admin' => 'admin'))													
				->where('admin_email = ?', $email)
				->where('admin_code != ?', $code)
				->limit(1);		
	   }

	   $result = $this->_db->fetchRow($select);
       return ($result == false) ? false : $result = $result;
	}
	/**
	 * get job by job users Id
 	 * @param string job id
     * @return object
	 */
	public function validateEmail($string) {
		if(!filter_var($string, FILTER_VALIDATE_EMAIL)) {
			return '';
		} else {
			return trim($string);
		}
	}
	
	public function validateNumber($string) {
		if(preg_match('/^0[0-9]{9}$/', $this->onlyNumber(trim($string)))) {
			return $this->onlyNumber(trim($string));
		} else {
			return '';
		}
	}
	
	public function onlyNumber($string) {
		/* Remove some weird charactors that windows dont like. */
		$string = strtolower($string);
		$string = str_replace(' ' , '' , $string);
		$string = str_replace('__' , '' , $string);
		$string = str_replace(' ' , '' , $string);
		$string = str_replace("`", "", $string);
		$string = str_replace("/", "", $string);
		$string = str_replace("\\", "", $string);
		$string = str_replace("'", "", $string);
		$string = str_replace("(", "", $string);
		$string = str_replace(")", "", $string);
		$string = str_replace("-", "", $string);
		$string = str_replace(".", "", $string);
		$string = str_replace('___' , '' , $string);
		$string = str_replace('__' , '' , $string);	 
		$string = str_replace(' ' , '' , $string);
		$string = str_replace('__' , '' , $string);
		$string = str_replace(' ' , '' , $string);
		$string = str_replace("`", "", $string);
		$string = str_replace("/", "", $string);
		$string = str_replace("\\", "", $string);
		$string = str_replace("'", "", $string);
		$string = str_replace("(", "", $string);
		$string = str_replace(")", "", $string);
		$string = str_replace("-", "", $string);
		$string = str_replace(".", "", $string);
		$string = str_replace("–", "", $string);	
		$string = str_replace("#", "", $string);	
		$string = str_replace("$", "", $string);	
		$string = str_replace("@", "", $string);	
		$string = str_replace("!", "", $string);	
		$string = str_replace("&", "", $string);	
		$string = str_replace(';' , '' , $string);		
		$string = str_replace(':' , '' , $string);		
		$string = str_replace('[' , '' , $string);		
		$string = str_replace(']' , '' , $string);		
		$string = str_replace('|' , '' , $string);		
		$string = str_replace('\\' , '' , $string);		
		$string = str_replace('%' , '' , $string);	
		$string = str_replace(';' , '' , $string);		
		$string = str_replace(' ' , '' , $string);
		$string = str_replace('__' , '' , $string);
		$string = str_replace(' ' , '' , $string);	
		$string = str_replace('-' , '' , $string);	
		$string = str_replace('+27' , '0' , $string);	
		$string = str_replace('(0)' , '' , $string);	
		$string = preg_replace('/^00/', '0', $string);
		$string = preg_replace('/^27/', '0', $string);
		$string = preg_replace('!\s+!',"", strip_tags($string));
		return $string;
	}

	/**
	 * get domain by domain Municipality Id
 	 * @param string domain id
     * @return object
	 */
	public function getCode($code) {
		$select = $this->_db->select()	
			->from(array('admin' => 'admin'))	
			->where('admin_code = ?', $code)
			->limit(1);

		$result = $this->_db->fetchRow($select);
        return ($result == false) ? false : $result = $result;					   

	}
	
	function createCode() {
		/* New code. */
		$code 		= "";
		$Alphabet	= "ABCEGHKMNOPQRSUXZ";
		$Number 	= '1234567890';
		
		/* First two Alphabets. */
		$count = strlen($Alphabet) - 1;
		
		for($i=0;$i<2;$i++){
			$code .= $Alphabet[rand(0,$count)];
		}
		
		/* Next six numbers */
		$count = strlen($Number) - 1;
		
		for($i=0;$i<3;$i++){
			$code .= $Number[rand(0,$count)];
		}
		
		for($i=0;$i<2;$i++){
			$code .= $Alphabet[rand(0,$count)];
		}
		
		/* Next six numbers */
		$count = strlen($Number) - 1;
		
		for($i=0;$i<2;$i++){
			$code .= $Number[rand(0,$count)];
		}		
		/* First check if it exists or not. */
		$itemCheck = $this->getCode($code);
		
		if($itemCheck) {
			/* It exists. check again. */
			$this->createCode();
		} else {
			return $code;
		}
	}	
}
?>