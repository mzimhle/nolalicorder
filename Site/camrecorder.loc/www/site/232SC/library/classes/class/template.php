<?php

//custom account item class as account table abstraction
class class_template extends Zend_Db_Table_Abstract
{
   //declare table variables
    protected 	$_name		= 'template';
	public		$_primary	= 'template_code';
	public 		$_municipality	= null;

	function init()	{
		global $zfsession;
		$this->_municipality	= isset($zfsession->municipality) ? $zfsession->municipality : null;
	}
	/**
	 * Insert the database record
	 * example: $table->insert($data);
	 * @param array $data
     * @return boolean
	 */
	 public function insert(array $data) {
        // add a timestamp
        $data['template_added'] 	= date('Y-m-d H:i:s');		
        $data['template_code']		= $this->createCode();		
		$data['municipality_code']	= $this->_municipality;		
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
        $data['template_updated'] = date('Y-m-d H:i:s');
        return parent::update($data, $where);
    }
	/**
	 * get job by job template Id
 	 * @param string job id
     * @return object
	 */
	public function getByCode($code) {
		$select = $this->_db->select()	
			->from(array('template' => 'template'))
			->joinLeft(array('municipality' => 'municipality'), 'municipality.municipality_code = template.municipality_code and municipality_deleted = 0')
			->where('template_deleted = 0 and template.municipality_code = ?', $this->_municipality)
			->where('template_code = ?', $code)
			->limit(1);

		$result = $this->_db->fetchRow($select);
        return ($result == false) ? false : $result = $result;
	}
	/**
	 * get job by job template Id
 	 * @param string job id
     * @return object
	 */
	public function getAll(){
		$select = $this->_db->select()	
			->from(array('template' => 'template'))
			->joinLeft(array('municipality' => 'municipality'), 'municipality.municipality_code = template.municipality_code and municipality_deleted = 0')
			->where('template_deleted = 0 and template.municipality_code = ?', $this->_municipality)
			->where('template_deleted = 0')
			->order('template_added desc');
			
		$result = $this->_db->fetchAll($select);
        return ($result == false) ? false : $result = $result;
	}	
	/**
	 * get job by job template Id
 	 * @param string job id
     * @return object
	 */	
	public function getCipher($type, $cipher, $code = null) {
		if($code == null) {
			$select = $this->_db->select()	
				->from(array('template' => 'template'))
				->where("template_cipher != 'TEMPLATE'")
				->where('template_cipher = ?', $cipher)
				->where('template_type = ?', $type)
				->where('template_deleted = 0 and template.municipality_code = ?', $this->_municipality)
				->limit(1);
		} else {
			$select = $this->_db->select()	
				->from(array('template' => 'template'))
				->where("template_cipher != 'TEMPLATE'")
				->where('template_cipher = ?', $cipher)
				->where('template_type = ?', $type)
				->where("template_code != ?", $code)
				->where('template_deleted = 0 and template.municipality_code = ?', $this->_municipality)
				->limit(1);
		}
		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;
	}
	/**
	 * get job by job template Id
 	 * @param string job id
     * @return object
	 */	
	public function getTemplate($category, $type, $cipher, $code = null) {
		if($code == null) {
			$select = $this->_db->select()	
				->from(array('template' => 'template'))
				->where("template_category = ?", $category)
				->where("template_type = ?", $type)
				->where("template_cipher = ?", $cipher)
				->where('template_deleted = 0 and template.municipality_code = ?', $this->_municipality)
				->limit(1);
		} else {
			$select = $this->_db->select()
				->from(array('template' => 'template'))
				->where("template_category = ?", $category)
				->where("template_type = ?", $type)
				->where("template_cipher = ?", $cipher)
				->where("template_code != ?", $code)
				->where('template_deleted = 0 and template.municipality_code = ?', $this->_municipality)
				->limit(1);
		}
		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;
	}	
	/**
	 * get job by job template Id
 	 * @param string job id
     * @return object
	 */	
	public function getByCipher($code) {
		$select = $this->_db->select()	
			->from(array('template' => 'template'))
			->where("template_cipher != 'TEMPLATE'")
			->where('template_code = ?', $code)
			->where('template_deleted = 0 and template.municipality_code = ?', $this->_municipality)
			->limit(1);

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;
	}	
	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function getCiphers(){
		$select = $this->_db->select()	
			->from(array('template' => 'template'))	
			->where("template_cipher != 'TEMPLATE'")
			->where('template_deleted = 0 and template.municipality_code = ?', $this->_municipality);

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
			->from(array('template' => 'template'), array('template_code', "concat(template_category, ' - ', template_cipher)"))	
			->where('template_deleted = 0 and template.municipality_code = ?', $this->_municipality);

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
			->from(array('template' => 'template'))	
			->where('template_code = ?', $code)
			->limit(1);

		$result = $this->_db->fetchRow($select);	
        return ($result == false) ? false : $result = $result;
	}
	
	function createCode() {
		/* New code. */
		$code = "";
		
		$Alphabet 	= "ABCEGHKMNOPQRSUXZ";
		$Number 	= '23456789';
		
		/* First two Alphabets. */
		$count = strlen($Alphabet) - 1;
		
		for($i=0;$i<2;$i++){
			$code .= $Alphabet[rand(0,$count)];
		}
		
		/* Next six numbers */
		$count = strlen($Number) - 1;
		
		for($i=0;$i<1;$i++){
			$code .= $Number[rand(0,$count)];
		}
		
		/* Last alphabet. */
		$count = strlen($Alphabet) - 1;
		
		for($i=0;$i<2;$i++){
			$code .= $Alphabet[rand(0,$count)];
		}
		
		/* Next six numbers */
		$count = strlen($Number) - 1;
		
		for($i=0;$i<3;$i++){
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