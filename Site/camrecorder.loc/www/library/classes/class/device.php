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
		$data['account_code'] = $this->_account;
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
			->joinLeft(array('room' => 'room'), 'room.room_code = device.room_code and room_deleted = 0')		
			->where('account_deleted = 0 and account.account_code = ?', $this->_account)
			->where('device_code = ?', $code)
			->limit(1);		

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;		
	}
	
	public function getDevice(array $headers) {
		$data						= array();
		$data['device_code']		= $headers['Playerid'];
		if(isset($headers['Playername'])) $data['device_name']	= $headers['Playername'];
		
		$data['device_lastseen']	= date('Y-m-d G:i:s');

		$deviceData = $this->getByCode($data['device_code']);

		if(!$deviceData) {
			$this->insert($data);
		} else {
			/* Update the information. */
			$where		= $this->getAdapter()->quoteInto('device_code = ?', $data['device_code']);
			$this->update($data, $where);			
		}
		/* Retern the data. */
		return $this->getByCode($data['device_code']);
	}	
}
?>