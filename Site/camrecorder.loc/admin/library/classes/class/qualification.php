<?php
//custom qualification item class as qualification table abstraction
class class_qualification extends Zend_Db_Table_Abstract
{
   //declare table variables
    protected $_name 		= 'qualification';
	protected $_primary 	= 'qualification_code';

	public	$_account		= null;
	
	function init()	{
		global $zfsession;
		$this->_account		= $zfsession->account;
	}
	/**
	 * Insert the database record
	 * example: $table->insert($data);
	 * @param array $data
     * @return boolean
	 */ 
	 public function insert(array $data) {
        // add a timestamp
        $data['qualification_added']		= date('Y-m-d H:i:s');
		$data['qualification_code']		= $this->createCode();
		$data['account_code']		= $this->_account;
		return parent::insert($data);	
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
        $data['qualification_updated'] = date('Y-m-d H:i:s');
        return parent::update($data, $where);
    }
	/**
	 * get job by job qualification Id
 	 * @param string job id
     * @return object
	 */
	public function getByCode($code) {

		$select = $this->_db->select()
			->from(array('qualification' => 'qualification'))
			->joinInner(array('account' => 'account'), 'account.account_code = qualification.account_code', array('account_name'))			
			->where('account_deleted = 0 and account.account_code = ?', $this->_account)
			->where('qualification_code = ?', $code)
			->limit(1);		

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;		
	}

	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function pairs(){
		$select = $this->_db->select()
			->from(array('qualification' => 'qualification'), array('qualification_code', "concat(qualification_cipher, ' - ', qualification_name) qualification_name"))
			->joinInner(array('account' => 'account'), 'account.account_code = qualification.account_code', array())			
			->where('account_deleted = 0 and account.account_code = ?', $this->_account);

		$result = $this->_db->fetchPairs($select);	
        return ($result == false) ? false : $result = $result;
	}	
	
	public function paginate($start, $length, $filter = array()) {
	
		$where	= 'qualification_deleted = 0';
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
							$where .= "lower(concat_ws(qualification_name, ' ', qualification_surname, ' ', qualification_email, qualification_cellphone)) like lower('%$text%')";
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
			->from(array('qualification' => 'qualification'))
			->joinInner(array('account' => 'account'), 'account.account_code = qualification.account_code', array('account_name'))		
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
	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function getCode($code) {
		$select = $this->_db->select()	
			->from(array('qualification' => 'qualification'))	
			->where('qualification_code = ?', $code)
			->limit(1);

		$result = $this->_db->fetchRow($select);
        return ($result == false) ? false : $result = $result;
	}
	
	function createCode() {
		/* New code. */
		$code	= rand(0,10000).md5(date("Y-m-d h:i:s"));		
		/* First check if it exists or not. */
		$itemCheck = $this->getCode($code);
		if($itemCheck) {
			/* It exists. check again. */
			$this->createCode();
		} else {
			return $code;
		}
	}

    function sanitize(&$string) { $string = preg_replace("/[^a-zA-Z0-9_]+/", "", $string);}
    function sanitizeArray(&$array) { for($i = 0; $i < count($array); $i++) { $array[$i] = preg_replace("/[^a-zA-Z0-9_]+/", "", $array[$i]); } }
}
?>