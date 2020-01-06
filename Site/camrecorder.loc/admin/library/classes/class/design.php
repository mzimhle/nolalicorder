<?php

//custom account item class as account table abstraction
class class_design extends Zend_Db_Table_Abstract
{
   //declare table variables
    protected 	$_name		= 'design';
	public		$_primary	= 'design_code';
	public 		$_account	= null;

	function init()	{
		global $zfsession;
		$this->_account	= $zfsession->account;
	}
	/**
	 * Insert the database record
	 * example: $table->insert($data);
	 * @param array $data
     * @return boolean
	 */
	 public function insert(array $data) {
        // add a timestamp
        $data['design_added']	= date('Y-m-d H:i:s');		
        $data['design_code']	= $this->createCode();		
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
        $data['design_updated'] = date('Y-m-d H:i:s');
        return parent::update($data, $where);
    }
	/**
	 * get job by job design Id
 	 * @param string job id
     * @return object
	 */
	public function getByCode($code) {
		$select = $this->_db->select()	
			->from(array('design' => 'design'))
			->joinInner(array('account' => 'account'), 'account.account_code = design.account_code and account_deleted = 0')
			->where('design_deleted = 0')
			->where('design_code = ?', $code)
			->limit(1);

		$result = $this->_db->fetchRow($select);
        return ($result == false) ? false : $result = $result;
	}
	/**
	 * get job by job design Id
 	 * @param string job id
     * @return object
	 */
	public function getAll(){
		$select = $this->_db->select()	
			->from(array('design' => 'design'))
			->joinInner(array('account' => 'account'), 'account.account_code = design.account_code and account_deleted = 0')
			->where('design.account_code = ?', $this->_account)
			->where("design_deleted = 0")
			->order('design_added desc');
		$result = $this->_db->fetchAll($select);
        return ($result == false) ? false : $result = $result;
	}
	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function pairs(){
		$select = $this->_db->select()	
			->from(array('design' => 'design'), array('design_code', "design_name"))	
			->where('design_deleted = 0 and design.account_code = ?', $this->_account);

		$result = $this->_db->fetchPairs($select);	
        return ($result == false) ? false : $result = $result;
	}
	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function getCode($code){
		$select = $this->_db->select()	
			->from(array('design' => 'design'))	
			->where('design_code = ?', $code)
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
}
?>