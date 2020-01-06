<?php
//custom participant item class as participant table abstraction
class class_participant extends Zend_Db_Table_Abstract {
   //declare table variables
    protected $_name	= 'participant';
	protected $_primary	= 'participant_code';

	function init()	{
		global $zfsession;
		$this->_account	= $zfsession->account;
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
			->joinLeft(array('media' => 'media'), "media_item_type = 'PARTICIPANT' and media_item_code = participant.participant_code and media_primary = 1 and media_deleted = 0", array('media_code', 'media_path', 'media_ext'))
			->where('account_deleted = 0 and account.account_code = ?', $this->_account)
			->where('participant.participant_code = ?', $code)
			->limit(1);		

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;		
	}
	/**
	 * get job by job participant Id
 	 * @param string job id
     * @return object
	 */
	public function login($username, $password) {

		$select = $this->_db->select()
			->from(array('participant' => 'participant'))
			->joinInner(array('account' => 'account'), 'account.account_code = participant.account_code')
			->joinLeft(array('media' => 'media'), "media_item_type = 'PARTICIPANT' and media_item_code = participant.participant_code and media_primary = 1 and media_deleted = 0", array('media_code', 'media_path', 'media_ext'))
			->where('account_deleted = 0 and account_active = 1 and account.account_code = ?', $this->_account)
			->where("participant.participant_cellphone = '$username' or participant.participant_email = '$username'")
			->where('participant.participant_password = ?', $password)
			->limit(1);		

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;		
	}
	/**
	 * get domain by domain Municipality Id
 	 * @param string domain id
     * @return object
	 */
	public function validateEmail($string) {
		if(!filter_var($string, FILTER_VALIDATE_EMAIL)) {
			return '';
		} else {
			return trim($string);
		}
	}
}
?>