<?php

//custom account item class as account table abstraction
class class_template extends Zend_Db_Table_Abstract
{
   //declare table variables
    protected 	$_name		= 'template';
	public		$_primary	= 'template_code';
	public 		$_account	= null;

	function init()	{
		global $zfsession;
		$this->_account	= isset($zfsession->account) ? $zfsession->account : null;
	}
	/**
	 * Insert the database record
	 * example: $table->insert($data);
	 * @param array $data
     * @return boolean
	 */
	 public function insert(array $data) {
        // add a timestamp
        $data['template_added']	= date('Y-m-d H:i:s');		
        $data['template_code']	= $this->createCode();		
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
			->joinLeft(array('account' => 'account'), 'account.account_code = template.account_code and account_deleted = 0')
			->where('template_deleted = 0')
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
		if($this->_account == null) {
			$select = $this->_db->select()	
				->from(array('template' => 'template'))
				->where("template_deleted = 0 and (campaign_code = '' or campaign_code is null)")
				->order('template_added desc');
		} else {
			$select = $this->_db->select()	
				->from(array('template' => 'template'))
				->joinLeft(array('account' => 'account'), 'account.account_code = template.account_code and account_deleted = 0')
				->where('template.account_code = ?', $this->_account)
				->where("template_deleted = 0 and (campaign_code = '' or campaign_code is null)")
				->order('template_added desc');
		}
		$result = $this->_db->fetchAll($select);
        return ($result == false) ? false : $result = $result;
	}	
	/**
	 * get job by job template Id
 	 * @param string job id
     * @return object
	 */
	public function getByCampaign($campaign, $type = 'SMS'){

		$subscription = $this->_db->select()
			->from(array('link' => 'link'), array())
			->joinInner(array('template' => 'template'), "template.template_code = link.link_parent_code and link.link_parent_type = 'TEMPLATE'", array('template_code'))
			->joinInner(array('subscription' => 'subscription'), "subscription.subscription_code = link.link_child_code and link.link_child_type = 'SUBSCRIPTION'", array("count(subscription_code) as subscription_count"))
			->where("link_deleted = 0 and subscription_deleted = 0 and template_deleted = 0")
			->where('subscription.account_code = ?', $this->_account)
			->group('link.link_child_code');

		$select = $this->_db->select()	
			->from(array('template' => 'template'))
			->joinLeft(array('account' => 'account'), 'account.account_code = template.account_code and account_deleted = 0')
			->joinLeft(array('subscription' => $subscription), 'subscription.template_code = template.template_code', array('ifnull(subscription_count,0) as subscription_count'))	
			->where("template_cipher = 'CAMPAIGN' and template_public = 1 and campaign_code = ?", $campaign)			
			->where("template_category = ?", $type)	
			->where('account_deleted = 0 and template_deleted = 0 and template.account_code = ?', $this->_account)
			->order('template_added desc');
			
		$result = $this->_db->fetchAll($select);
        return ($result == false) ? false : $result = $result;
	}
	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function campaignSMSPairs($campaign){
		$select = $this->_db->select()
			->from(array('template' => 'template'), array('template_code', "concat(template_category, ' - ', template_message)"))	
			->where('template_deleted = 0 and template.account_code = ?', $this->_account)
			->where('template.campaign_code = ?', $campaign);

		$result = $this->_db->fetchPairs($select);	
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
				->where('template_deleted = 0 and template.account_code = ?', $this->_account)
				->limit(1);
		} else {
			$select = $this->_db->select()	
				->from(array('template' => 'template'))
				->where("template_cipher != 'TEMPLATE'")
				->where('template_cipher = ?', $cipher)
				->where('template_type = ?', $type)
				->where("template_code != ?", $code)
				->where('template_deleted = 0 and template.account_code = ?', $this->_account)
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
		if($this->_account == null) {
			if($code == null) {
				$select = $this->_db->select()	
					->from(array('template' => 'template'))
					->where("template_category = ?", $category)
					->where("template_type = ?", $type)
					->where("template_cipher = ?", $cipher)
					->where('template_deleted = 0')
					->limit(1);
			} else {
				$select = $this->_db->select()
					->from(array('template' => 'template'))
					->where("template_category = ?", $category)
					->where("template_type = ?", $type)
					->where("template_cipher = ?", $cipher)
					->where("template_code != ?", $code)
					->where('template_deleted = 0')
					->limit(1);
			}
		} else {
			if($code == null) {
				$select = $this->_db->select()	
					->from(array('template' => 'template'))
					->where("template_category = ?", $category)
					->where("template_type = ?", $type)
					->where("template_cipher = ?", $cipher)
					->where('template_deleted = 0 and template.account_code = ?', $this->_account)
					->limit(1);
			} else {
				$select = $this->_db->select()
					->from(array('template' => 'template'))
					->where("template_category = ?", $category)
					->where("template_type = ?", $type)
					->where("template_cipher = ?", $cipher)
					->where("template_code != ?", $code)
					->where('template_deleted = 0 and template.account_code = ?', $this->_account)
					->limit(1);
			}
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
			->where('template_deleted = 0 and template.account_code = ?', $this->_account)
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
			->where('template_deleted = 0 and template.account_code = ?', $this->_account);

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
			->where('template_deleted = 0 and template.account_code = ?', $this->_account);

		$result = $this->_db->fetchPairs($select);	
        return ($result == false) ? false : $result = $result;
	}
	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function publicPairs($type = 'EMAIL'){
		$select = $this->_db->select()	
			->from(array('template' => 'template'), array('template_code', "concat(template_category, ' - ', template_cipher)"))	
			->where('template_public = 1 and template_deleted = 0 and template.account_code = ?', $this->_account)
			->where('template_category = ?', $type);

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
}
?>