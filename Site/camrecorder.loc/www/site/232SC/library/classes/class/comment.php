<?php
//custom participant item class as participant table abstraction
class class_comment extends Zend_Db_Table_Abstract {
   //declare table variables
    protected	$_name		= 'comment';
	protected	$_primary	= 'comment_code';

	function init()	{
		global $zfsession;
		$this->_municipality	= $zfsession->municipality;
		$this->_participant		= isset($zfsession->identity) ? $zfsession->identity : null;
	}
	/**
	 * Insert the database record
	 * example: $table->insert($data);
	 * @param array $data
     * @return boolean
	 */ 
	 public function insert(array $data) {
        // add a timestamp
        $data['comment_added']		= date('Y-m-d H:i:s');
		$data['comment_code']		= $this->createCode();
		$data['municipality_code']	= $this->_municipality;
		$data['participant_code']	= $this->_participant;
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
        $data['comment_updated'] = date('Y-m-d H:i:s');
        return parent::update($data, $where);
    }
	/**
	 * get job by job comment Id
 	 * @param string job id
     * @return object
	 */
	public function getByCode($code) {
		$select = $this->_db->select()
			->from(array('comment' => 'comment'))
			->joinInner(array('content' => 'content'), 'content.content_code = comment.content_code')
			->joinInner(array('municipality' => 'municipality'), 'municipality.municipality_code = comment.municipality_code')
			->joinLeft(array('participant' => 'participant'), 'participant.participant_code = comment.participant_code')
			->joinLeft(array('media' => 'media'), "media_item_type = 'PARTICIPANT' and media_item_code = participant.participant_code and media_primary = 1 and media_deleted = 0", array('media_code', 'media_path', 'media_ext'))
			->where('comment_deleted = 0')
			->where('comment_code = ?', $code)
			->limit(1);		

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;		
	}
	/**
	 * get job by job comment Id
 	 * @param string job id
     * @return object
	 */
	public function getByUrl($url) {
		$select = $this->_db->select()
			->from(array('comment' => 'comment'))
			->joinInner(array('content' => 'content'), 'content.content_code = comment.content_code')
			->joinInner(array('municipality' => 'municipality'), 'municipality.municipality_code = comment.municipality_code')
			->joinLeft(array('participant' => 'participant'), 'participant.participant_code = comment.participant_code')
			->joinLeft(array('media' => 'media'), "media_item_type = 'PARTICIPANT' and media_item_code = participant.participant_code and media_primary = 1 and media_deleted = 0", array('media_code', 'media_path', 'media_ext'))
			->where('comment_deleted = 0')
			->where('comment_url = ?', $url)
			->limit(1);		

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;		
	}
	/**
	 * get job by job comment Id
 	 * @param string job id
     * @return object
	 */
	public function getBySite($site) {
		$select = $this->_db->select()
			->from(array('comment' => 'comment'))
			->joinInner(array('content' => 'content'), 'content.content_code = comment.content_code')
			->joinInner(array('municipality' => 'municipality'), 'municipality.municipality_code = comment.municipality_code')
			->joinLeft(array('participant' => 'participant'), 'participant.participant_code = comment.participant_code')
			->joinLeft(array('media' => 'media'), "media_item_type = 'PARTICIPANT' and media_item_code = participant.participant_code and media_primary = 1 and media_deleted = 0", array('media_code', 'media_path', 'media_ext'))
			->where('comment_deleted = 0')
			->where('comment_site = ?', $site)
			->limit(1);		

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;		
	}	
	/**
	 * get job by job comment Id
 	 * @param string job id
     * @return object
	 */	
	public function getByContent($code) {

		$select = $this->_db->select()
			->from(array('comment' => 'comment'))
			->joinInner(array('content' => 'content'), 'content.content_code = comment.content_code')
			->joinInner(array('municipality' => 'municipality'), 'municipality.municipality_code = comment.municipality_code')
			->joinLeft(array('participant' => 'participant'), 'participant.participant_code = comment.participant_code')
			->joinLeft(array('media' => 'media'), "media_item_type = 'PARTICIPANT' and media_item_code = participant.participant_code and media_primary = 1 and media_deleted = 0", array('media_code', 'media_path', 'media_ext'))			
			->where('comment_deleted = 0 and content_deleted = 0 and municipality_deleted = 0 and comment_active = 1')
			->where('content.content_code = ?', $code);

		$result = $this->_db->fetchAll($select);
		return ($result == false) ? false : $result = $result;
	}
	/**
	 * get domain by domain Municipality Id
 	 * @param string domain id
     * @return object
	 */
	public function paginate($start, $length, $filter = array()) {
	
		$where	= 'comment_deleted = 0';
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
							$where .= "lower(concat_ws(comment_text, ' ', participant_name, participant_surname)) like lower('%$text%')";
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
			->from(array('comment' => 'comment'))
			->joinInner(array('content' => 'content'), 'content.content_code = comment.content_code')
			->joinInner(array('municipality' => 'municipality'), 'municipality.municipality_code = comment.municipality_code')
			->joinLeft(array('participant' => 'participant'), 'participant.participant_code = comment.participant_code')
			->joinLeft(array('media' => 'media'), "media_item_type = 'PARTICIPANT' and media_item_code = participant.participant_code and media_primary = 1 and media_deleted = 0", array('media_code', 'media_path', 'media_ext'))			
			->where('comment_deleted = 0 and content_deleted = 0 and municipality_deleted = 0')
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
			->from(array('comment' => 'comment'))	
			->where('comment_code = ?', $code)
			->limit(1);

		$result = $this->_db->fetchRow($select);
        return ($result == false) ? false : $result = $result;					   
	}

	function createCode() {
		/* New code. */
		$code	= date('Ymd').md5(date('Y-m-d h:i:s')).rand(0,10000);
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