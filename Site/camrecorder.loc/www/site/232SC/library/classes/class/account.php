<?php
//custom participant item class as participant table abstraction
class class_account extends Zend_Db_Table_Abstract {
   //declare table variables
    protected $_name 		= 'account';
	protected $_primary 	= 'account_code';
	
	public		$_demarcation	= null;

	function init()	{
		global $zfsession;
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
	 * get job by job account Id
 	 * @param string job id
     * @return object
	 */
	public function getBySite($site) {
		$select = $this->_db->select()
			->from(array('account' => 'account'))
			->where('account_deleted = 0')
			->where('account_site = ?', $site)
			->limit(1);		

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
	
	
}
?>