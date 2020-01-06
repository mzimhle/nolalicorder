<?php
//custom log item class as log table abstraction
class class_log extends Zend_Db_Table_Abstract
{
   //declare table variables
    protected $_name	= 'log';
	protected $_primary	= 'log_code';
	/**
	 * Insert the database record
	 * example: $table->insert($data);
	 * @param array $data
     * @return boolean
	 */ 
	 public function insert(array $data) {
        // add a timestamp
        $data['log_added']	= date('Y-m-d H:i:s');
		$data['log_code']	= $this->createCode();
		return parent::insert($data);	
    }
	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function getCode($code) {
		$select = $this->_db->select()	
			->from(array('log' => 'log'))	
			->where('log_code = ?', $code)
			->limit(1);

		$result = $this->_db->fetchRow($select);
        return ($result == false) ? false : $result = $result;
	}
	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	function createCode() {
		/* New code. */
		$code	= rand(10000,10000000000).md5(date("Y-m-d h:i:s")).rand(10,10000000000000000);		
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