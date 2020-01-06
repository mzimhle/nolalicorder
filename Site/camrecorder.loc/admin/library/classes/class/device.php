<?php
//custom device item class as device table abstraction
class class_device extends Zend_Db_Table_Abstract
{
   //declare table variables
    protected $_name 		= 'device';
	protected $_primary 	= 'device_code';

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
		$data['device_code']	= $this->createCode(); 
		$data['account_code']	= $this->_account; 
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
        $data['device_updated'] = date('Y-m-d H:i:s');
        return parent::update($data, $where);
    }
	/**
	 * get job by job device Id
 	 * @param string job id
     * @return object
	 */
	public function getByCode($code) {

		$select = $this->_db->select()
			->from(array('device' => 'device'))
			->joinInner(array('account' => 'account'), 'account.account_code = device.account_code', array('account_name'))
			->joinLeft(array('room' => 'room'), 'room.room_code = device.room_code')		
			->joinLeft(array('campus' => 'campus'), 'campus.campus_code = room.campus_code', array('campus_name'))	
			->where('account_deleted = 0 and account.account_code = ?', $this->_account)
			->where('device_code = ?', $code)
			->limit(1);		

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;		
	}
	
	public function paginate($start, $length, $filter = array()) {
	
		$where	= 'device_deleted = 0';
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
							$where .= "lower(concat_ws(device_name, ' ', room_name)) like lower('%$text%')";
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
			->from(array('device' => 'device'))
			->joinInner(array('account' => 'account'), 'account.account_code = device.account_code', array('account_name'))
			->joinLeft(array('room' => 'room'), 'room.room_code = device.room_code', array('room_name'))		
			->joinLeft(array('campus' => 'campus'), 'campus.campus_code = room.campus_code', array('campus_name'))	
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
	 * get domain by domain Municipality Id
 	 * @param string domain id
     * @return object
	 */
	public function getCode($code) {
		$select = $this->_db->select()	
			->from(array('device' => 'device'))	
			->where('device_code = ?', $code)
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
		$code 			= "";
		$AlphaNumeric	= "abcdefghijklmnopqrstuvwxyz0123456789";
		
		$count = strlen($AlphaNumeric) - 1;
		for($i=0;$i<8;$i++){
			$code .= $AlphaNumeric[rand(0,$count)];
		}
		$code .= '-';
		$count = strlen($AlphaNumeric) - 1;
		for($i=0;$i<4;$i++){
			$code .= $AlphaNumeric[rand(0,$count)];
		}	
		$code .= '-';
		$count = strlen($AlphaNumeric) - 1;
		for($i=0;$i<4;$i++){
			$code .= $AlphaNumeric[rand(0,$count)];
		}	
		$code .= '-';
		$count = strlen($AlphaNumeric) - 1;
		for($i=0;$i<4;$i++){
			$code .= $AlphaNumeric[rand(0,$count)];
		}			
		$code .= '-';
		$count = strlen($AlphaNumeric) - 1;
		for($i=0;$i<12;$i++){
			$code .= $AlphaNumeric[rand(0,$count)];
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
	
    function sanitize(&$string) { $string = preg_replace("/[^a-zA-Z0-9_]+/", "", $string);}
    function sanitizeArray(&$array) { for($i = 0; $i < count($array); $i++) { $array[$i] = preg_replace("/[^a-zA-Z0-9_]+/", "", $array[$i]); } }
}
?>