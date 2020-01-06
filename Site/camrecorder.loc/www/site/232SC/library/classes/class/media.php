<?php

//custom account item class as account table abstraction
class class_media extends Zend_Db_Table_Abstract
{
   //declare table variables
    protected $_name	= 'media';
	protected $_primary	= 'media_code';
	/**
	 * Insert the database record
	 * example: $table->insert($data);
	 * @param array $data
     * @return boolean
	 */ 
	 public function insert(array $data) {
        // add a timestamp
        $data['media_added'] 	= date('Y-m-d H:i:s'); 
	
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
        $data['media_updated'] = date('Y-m-d H:i:s');
        return parent::update($data, $where);
    }
	/**
	 * get job by job media Id
 	 * @param string job id
     * @return object
	 */
	public function getByCode($code) {	
		$select = $this->_db->select()	
					->from(array('media' => 'media'))
					->where('media_deleted = 0')
					->where('media_code = ?', $code)
					->limit(1);
       
		$result = $this->_db->fetchRow($select);
        return ($result == false) ? false : $result = $result;
	}
	
	public function getByTypeCode($type, $code) {
		$select = $this->_db->select()	
					->from(array('media' => 'media'))
					->where("media_item_type = ?", $type)
					->where("media_item_code = ?", $code)
					->where('media_deleted = 0')
					->order('media_added desc');					

		$result = $this->_db->fetchAll($select);		
		return ($result == false) ? false : $result = $result;
	}
	
	public function getPrimary($type, $code) {
		$select = $this->_db->select()	
					->from(array('media' => 'media'))
					->where("media_item_type = ?", $type)
					->where("media_item_code = ?", $code)
					->where('media_deleted = 0 and media_primary = 1')
					->order('media_primary')	
					->limit(1);

		$result = $this->_db->fetchRow($select);
        return ($result == false) ? false : $result = $result;	
	}
	
	public function updatePrimary($type, $reference, $code) {
		
		$item = $this->getPrimary($type, $reference);

		if($item) {

			$data					= array();
			$where					= array();
			$data['media_primary']	= 0;
			
			$where[]	= $this->getAdapter()->quoteInto('media_item_type = ?', $type);
			$where[]	= $this->getAdapter()->quoteInto('media_item_code = ?', $reference);
			$success	= $this->update($data, $where);				
		}

		$data					= array();
		$data['media_primary']	= 1;

		$where		= $this->getAdapter()->quoteInto('media_code = ?', $code);
		$success	= $this->update($data, $where);

		return $success;
	}

	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function getCode($reference) {
		$select = $this->_db->select()	
			->from(array('media' => 'media'))	
			->where('media_code = ?', $reference)
			->limit(1);

		$result = $this->_db->fetchRow($select);	
        return ($result == false) ? false : $result = $result;					   
	}

	function createCode() {
		/* New code. */
		$code = md5(date('Y-m-d h:i:s')).rand(0,10000);
		/* First check if it exists or not. */
		$itemCheck = $this->getCode($code);
		
		if($itemCheck) {
			/* It exists. check again. */
			$this->createCode();
		} else {
			return $code;
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
}
?>