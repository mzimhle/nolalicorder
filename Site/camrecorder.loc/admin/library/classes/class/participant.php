<?php
require_once 'class/student.php';
require_once 'class/_comm.php';
//custom participant item class as participant table abstraction
class class_participant extends Zend_Db_Table_Abstract
{
   //declare table variables
    protected $_name 		= 'participant';
	protected $_primary 	= 'participant_code';

	public	$_account	= null;
	public	$_comm		= null;
	public	$_student	= null;
	
	function init()	{
		global $zfsession;
		$this->_account		= $zfsession->account;
		$this->_comm		= new class_comm();
		$this->_student		= new class_student();
	}
	/**
	 * Insert the database record
	 * example: $table->insert($data);
	 * @param array $data
     * @return boolean
	 */ 
	 public function insert(array $data) {
        // add a timestamp
        $data['participant_added']		= date('Y-m-d H:i:s');
		$data['participant_code']		= $this->createCode();
		$data['participant_password']	= $this->createPassword();
		$data['account_code']			= $this->_account;
		$success 						= parent::insert($data);	

		if($success) {
			$participantData = $this->getByCode($success);
			if($participantData) {
				if($participantData['participant_email'] != '' && $participantData['participant_code'] != '') {
					$templateData = $this->_comm->_template->getTemplate('EMAIL', 'PARTICIPANT', 'REGISTRATION');
					if($templateData) {
						if($participantData['participant_category'] == 'PARTICIPANT') {
							$participantData['participant_category'] = 'Student';
						} else if($participantData['participant_category'] == 'TEACHER') {
							$participantData['participant_category'] = 'Lecturer';
						}
						$recipient							= array_merge($templateData, $participantData);
						$recipient['recipient_code'] 		= $participantData['participant_code'];
						$recipient['recipient_name'] 		= $participantData['participant_name'];
						$recipient['recipient_password']	= $participantData['participant_password'];
						$recipient['recipient_cellphone'] 	= $participantData['participant_cellphone'];
						$recipient['recipient_type'] 		= 'PARTICIPANT';
						$recipient['recipient_email'] 		= $participantData['participant_email'];
						$this->_comm->sendEmail($recipient, $templateData);
					}
				}
				if($participantData['participant_cellphone'] != '' && $participantData['participant_code'] != '') {
					$templateData = $this->_comm->_template->getTemplate('SMS', 'PARTICIPANT', 'REGISTRATION');
					if($templateData) {
						$recipient							= array_merge($templateData, $participantData);
						$recipient['recipient_code'] 		= $participantData['participant_code'];
						$recipient['recipient_name'] 		= $participantData['participant_name'];
						$recipient['recipient_password']	= $participantData['participant_password'];
						$recipient['recipient_cellphone'] 	= $participantData['participant_cellphone'];
						$recipient['recipient_type'] 		= 'PARTICIPANT';
						$recipient['recipient_email']		= $participantData['participant_email'];
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
        $data['participant_updated'] = date('Y-m-d H:i:s');
        return parent::update($data, $where);
    }
	/**
	 * get job by job participant Id
 	 * @param string job id
     * @return object
	 */
	public function getByCode($code) {

		$select = $this->_db->select()
			->from(array('participant' => 'participant'))
			->joinInner(array('account' => 'account'), 'account.account_code = participant.account_code')
			->where('account_deleted = 0 and account.account_code = ?', $this->_account)
			->where('participant_code = ?', $code)
			->limit(1);		

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;		
	}

	public function getByHash($hash, $status) {
		$select = $this->_db->select()
			->from(array('participant' => 'participant'))
			->where('participant_deleted = 0 and participant_hash = ?', $hash)
			->where('participant_active = ?', $status);

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;
	}
	
	public function paginate($start, $length, $filter = array()) {
	
		$where	= 'participant_deleted = 0';
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
							$where .= "lower(concat_ws(participant_name, ' ', participant_email, participant_cellphone)) like lower('%$text%')";
							if(($s+1) != count($array)) {
								$where .= ' or ';
							}
						}
					}
					$where .= ")";
				} else if(isset($filter[$i]['filter_category']) && trim($filter[$i]['filter_category']) != '') {
					$text = trim($filter[$i]['filter_category']);
					$this->sanitize($text);
					$where .= " and participant_category = '$text'";			
				} else if(isset($filter[$i]['filter_csv']) && (int)trim($filter[$i]['filter_csv']) == 1) {
					$csv = 1;
				}
			}
		}

		$select = $this->_db->select()
			->from(array('participant' => 'participant'))
			->joinInner(array('account' => 'account'), 'account.account_code = participant.account_code', array('account_name'))		
			->where('account_deleted = 0 and account.account_code = ?', $this->_account)			
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

	public function search($term, $limit = 10, $category = 'PARTICIPANT') {
		$select = $this->_db->select()
			->from(array('participant' => 'participant'), array('participant_code', 'participant_name', 'participant_email', 'participant_cellphone'))
			->joinInner(array('account' => 'account'), 'account.account_code = participant.account_code', array('account_name'))
			->where('account_deleted = 0 and account.account_code = ?', $this->_account)
			->where('participant_category = ?', $category)
			->where("lower(concat(participant_name, participant_email)) like lower(?)", "%$term%")
			->limit($limit)
			->order("LOCATE('$term', concat_ws(participant_name, participant_email))");

		$result = $this->_db->fetchAll($select);
		return ($result == false) ? false : $result = $result;
	}

	/**
	 * get job by job participant Id
 	 * @param string job id
     * @return object
	 */
	public function getByCell($cell, $code = null) {
		if($code == null) {
			$select = $this->_db->select()	
				->from(array('participant' => 'participant'))
				->where('participant.account_code = ?', $this->_account)
				->where('participant_cellphone = ?', $cell)						
				->where('participant_deleted = 0')
				->limit(1);
		} else {
			$select = $this->_db->select()
				->from(array('participant' => 'participant'))
				->where('participant.account_code = ?', $this->_account)
				->where('participant_cellphone = ?', $cell)
				->where('participant_code != ?', $code)
				->where('participant_deleted = 0')
				->limit(1);		
		}

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;
	}
	/**
	 * get job by job participant Id
 	 * @param string job id
     * @return object
	 */
	public function getByEmail($email, $code = null) {
		if($code == null) {
			$select = $this->_db->select()	
				->from(array('participant' => 'participant'))
				->where('participant.account_code = ?', $this->_account)
				->where('participant_email = ?', $email)
				->where('participant_deleted = 0')
				->limit(1);
		} else {
			$select = $this->_db->select()	
				->from(array('participant' => 'participant'))
				->where('participant.account_code = ?', $this->_account)
				->where('participant_email = ?', $email)
				->where('participant_code != ?', $code)
				->where('participant_deleted = 0')
				->limit(1);		
		}

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;
	}
	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function getCode($code) {
		$select = $this->_db->select()	
			->from(array('participant' => 'participant'))	
			->where('participant_code = ?', $code)
			->limit(1);

		$result = $this->_db->fetchRow($select);
        return ($result == false) ? false : $result = $result;
	}
	
	function createCode() {
		/* New code. */
		$code	= date("Ymdhis").rand(0,10000).md5(date("Y-m-d h:i:s"));		
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

	public function importLineFormat($string) {
		/* Remove some weird charactors that windows dont like. */
		$string = str_replace('  ' , ' ' , $string);
		$string = str_replace(',' , ' ' , $string);
		return $string;
	}

	function extractEmail($string){	
		preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $string, $matches);
		if(isset($matches[0][0]) && $this->validateEmail(trim($matches[0][0])) != '') {			
			return $this->validateEmail(trim($matches[0][0]));			
		} else {
			return false;
		}
	}

	function extractNumber($string){	
		preg_match_all("/0[0-9]{9}+/i", $string, $matches);
		if(isset($matches[0][0]) && $this->validateNumber(trim($matches[0][0])) != '') {			
			return $this->validateNumber(trim($matches[0][0]));			
		} else {
			return false;
		}
	}

	public function readImport(array $class, $category = 'PARTICIPANT') {
		if($category == 'PARTICIPANT') {
			if(count($class) == 0) {
				return false;
			}
		}
		//Import uploaded file to Database
		$handle 	= fopen($_FILES['importfile']['tmp_name'], "r");
		$imported 	= array();
		$lines 		= 0;
		$i 			= 0;
		$emailCheck = false;
		
		$errors								= array();			
		$errors['successful']		= 0;
		$errors['baddata']			= 0;
		$errors['badlines']			= '';
		$errors['duplicatecell']	= 0;
		$errors['duplicateemail']	= 0;
		$errors['total']			= 0;	
		
		while (($line = fgets($handle)) !== FALSE) {

			$check					= false;
			$data					= array();			

			$originalline = $line;

			$line = $this->importLineFormat($line);
			/* Check cellphone */
			$number = $this->extractNumber($line);
			if($number) {
				/* Remove cellphone. */
				$line = str_replace($number, '', $line);
				/* Check if cellphone already exists. */					
				$cellCheck = $this->getByCell($number);
				
				if(!$cellCheck) {
					/* Add cellphone */
					$data['participant_cellphone'] = $number;
				} else {
					$errors['duplicatecell']++;
				}				
			}
			/* Check Email. */						
			$email = $this->extractEmail($line);
			
			if($email) {
				/* Remove email address. */
				$line = str_replace($email, '', $line);
				/* Check if email already exists. */
				$emailCheck = $this->getByEmail($email);
				
				if(!$emailCheck) {
					/* Add email. */
					$data['participant_email'] = $email;
				} else {
					$errors['duplicateemail']++;
				}
			}
			/* Check name */
			$data['participant_name']	= $line;

			if(($email !== false && !$emailCheck)) {
				$data['participant_category'] = $category;
				/* Insert. */
				$success = $this->insert($data);
			} else {
				$success = $emailCheck['participant_code'];
			}

			if($success) {
				$errors['successful']++;
				if($category == 'PARTICIPANT') {
					/* Add classes */
					for($i = 0; $i < count($class); $i++) {
						$checkStudent = $this->_student->exists(date('Y'), $success, $class[$i]);

						if(!$checkStudent) {
							$link						= array();					
							$link['participant_code']	= $success;	
							$link['class_code']			= $class[$i];
							$link						= $this->_student->insert($link);
						}
					}
				}
			} else {
				$errors['baddata']++;
				$errors['badlines'] .= 'On Adding Line: '.$originalline.'<br />';
			} 
			$errors['total']++;
		}
		
		return $errors;
	}

    function sanitize(&$string) { $string = preg_replace("/[^a-zA-Z0-9_]+/", "", $string);}

    function sanitizeArray(&$array) {
		for($i = 0; $i < count($array); $i++) {
			$array[$i] = preg_replace("/[^a-zA-Z0-9_]+/", "", $array[$i]);
		}
    }
}
?>