<?php
require_once 'class/_comm.php';
//custom account item class as account table abstraction
class class_account extends Zend_Db_Table_Abstract {
   //declare table variables
    protected $_name 		= 'account';
	protected $_primary 	= 'account_code';

	public $_comm	= null;

	function init()	{
		global $zfsession;
		$this->_comm	= new class_comm();
	}	
	/**
	 * Insert the database record
	 * example: $table->insert($data);
	 * @param array $data
     * @return boolean
	 */ 
	public function insert(array $data) {
		$data['account_code']		= $this->createCode(); 
		$data['account_password']	= $this->createPassword(); 
		$success = parent::insert($data);	

		if($success) {
			$accountData = $this->getByCode($success);
			if($accountData) {
				if($accountData['account_contact_email'] != '' && $accountData['account_code'] != '') {
					$templateData = $this->_comm->_template->getTemplate('EMAIL', 'ACCOUNT', 'REGISTRATION');
					if($templateData) {
						$recipient							= array_merge($templateData, $accountData);
						$recipient['recipient_code'] 		= $accountData['account_code'];
						$recipient['recipient_name'] 		= $accountData['account_name'];
						$recipient['recipient_password']	= $accountData['account_password'];
						$recipient['recipient_cellphone'] 	= $accountData['account_contact_cellphone'];
						$recipient['recipient_type'] 		= 'ACCOUNT';
						$recipient['recipient_email'] 		= $accountData['account_contact_email'];
						$this->_comm->sendEmail($recipient, $templateData);
					}
				}
				if($accountData['account_contact_cellphone'] != '' && $accountData['account_code'] != '') {
					$templateData = $this->_comm->_template->getTemplate('SMS', 'ACCOUNT', 'REGISTRATION');
					if($templateData) {
						$recipient							= array_merge($templateData, $accountData);
						$recipient['recipient_code'] 		= $accountData['account_code'];
						$recipient['recipient_name'] 		= $accountData['account_name'];
						$recipient['recipient_password']	= $accountData['account_password'];
						$recipient['recipient_cellphone'] 	= $accountData['account_contact_cellphone'];
						$recipient['recipient_type'] 		= 'ACCOUNT';
						$recipient['recipient_email']		= $accountData['account_contact_email'];
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
    public function update(array $data, $where) {
        // add a timestamp
        $data['account_updated'] = date('Y-m-d H:i:s');
        return parent::update($data, $where);
    }
	/**
	 * get job by job account Id
 	 * @param string job id
     * @return object
	 */
	public function getByCode($code) {
		$select = $this->_db->select()
			->from(array('account' => 'account'))
			->where('account_deleted = 0')
			->where('account_code = ?', $code)
			->limit(1);		

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;		
	}
	/**
	 * get domain by domain Municipality Id
 	 * @param string domain id
     * @return object
	 */
	public function pairs() {
		$select = $this->_db->select()
			->from(array('account' => 'account'), array('account_code', 'account_name'))	
			->where('account_deleted = 0');

		$result = $this->_db->fetchPairs($select);
        return ($result == false) ? false : $result = $result;					   
	}

	public function paginate($start, $length, $filter = array()) {

		$where	= 'account_deleted = 0';
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
							$where .= "lower(concat_ws(account_name, ' ', account_contact_email, account_contact_cellphone)) like lower('%$text%')";
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
			->from(array('account' => 'account'))
			->where('account_deleted = 0')		
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
	 * get job by job account Id
 	 * @param string job id
     * @return object
	 */
	public function getByCell($cell, $code = null) {
		if($code == null) {
			$select = $this->_db->select()	
				->from(array('account' => 'account'))
				->where('account_contact_cellphone = ?', $cell)						
				->where('account_deleted = 0')
				->limit(1);
		} else {
			$select = $this->_db->select()
				->from(array('account' => 'account'))
				->where('account_contact_cellphone = ?', $cell)
				->where('account_code != ?', $code)
				->where('account_deleted = 0')
				->limit(1);		
		}

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;
	}
	/**
	 * get job by job account Id
 	 * @param string job id
     * @return object
	 */
	public function getByEmail($email, $code = null) {
		if($code == null) {
			$select = $this->_db->select()	
				->from(array('account' => 'account'))
				->where('account_contact_email = ?', $email)
				->where('account_deleted = 0')
				->limit(1);
		} else {
			$select = $this->_db->select()	
				->from(array('account' => 'account'))
				->where('account_contact_email = ?', $email)
				->where('account_code != ?', $code)
				->where('account_deleted = 0')
				->limit(1);		
		}

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;
	}	
	/**
	 * get domain by domain Municipality Id
 	 * @param string domain id
     * @return object
	 */
	public function getCode($code) {
		$select = $this->_db->select()	
			->from(array('account' => 'account'))	
			->where('account_code = ?', $code)
			->limit(1);

		$result = $this->_db->fetchRow($select);
        return ($result == false) ? false : $result = $result;					   
	}
	/**
	 * get domain by domain Municipality Id
 	 * @param string domain id
     * @return object
	 */
	function createCode() {
		/* New code. */
		$code 		= "";
		$Alphabet	= "ABCEGHKMNOPQRSUXZ";
		$Number 	= '23456789';
		
		$count = strlen($Number) - 1;
		for($i=0;$i<3;$i++){
			$code .= $Number[rand(0,$count)];
		}
		$count = strlen($Alphabet) - 1;
		for($i=0;$i<2;$i++){
			$code .= $Alphabet[rand(0,$count)];
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

	public function createPassword() {
		/* New password. */
		$password	= "";
		$string		= "abcdefghijklmnopqrstuvwxyz023456789";

		$count = strlen($string) - 1;
		for($i=0;$i<6;$i++){
			$password .= $string[rand(0,$count)];
		}		
		return $password;
	}

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
	
	public function validateDate($string) {
		if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $string)) {
			if(date('Y-m-d', strtotime($string)) != $string) {
				return '';
			} else {
				return $string;
			}
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

    function sanitize(&$string) { $string = preg_replace("/[^a-zA-Z0-9_]+/", "", $string);}
    function sanitizeArray(&$array) { for($i = 0; $i < count($array); $i++) {$array[$i] = preg_replace("/[^a-zA-Z0-9_]+/", "", $array[$i]); } }	
}
?>