<?php
//custom communicate item class as communicate table abstraction
class class_communicate extends Zend_Db_Table_Abstract {
   //declare table variables
    protected $_name 		= 'communicate';
	protected $_primary 	= 'communicate_code';
	public		$_municipality	= null;

	function init()	{
		global $zfsession;
		$this->_municipality	= $zfsession->municipality;
	}	
	/**
	 * Insert the database record
	 * example: $table->insert($data);
	 * @param array $data
     * @return boolean
	 */ 
	 public function insert(array $data) {
        // add a timestamp
        $data['communicate_added']		= date('Y-m-d H:i:s');
		$data['communicate_code']		= $this->createCode();	
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
        $data['communicate_updated'] = date('Y-m-d H:i:s');
        return parent::update($data, $where);
    }
	/**
	 * get job by job communicate Id
 	 * @param string job id
     * @return object
	 */
	public function getByCode($code) {
		$select = $this->_db->select()
			->from(array('communicate' => 'communicate'))
			->joinLeft(array('template' => 'template'), "template.template_code = communicate.template_code")
			->joinLeft(array('_comm' => '_comm'), "_comm.communicate_code = communicate.communicate_code", array('count(_comm_code) as communicate_count'))
			->joinLeft(array('link' => 'link'), "link.link_parent_type = 'COMMUNICATE' and link.link_parent_code = communicate.communicate_code and link_child_type = 'DEMARCATION' and link_deleted = 0", array('count(link_child_code) as demarcation_count'))
			->where('template.municipality_code = ?', $this->_municipality)
			->where('template_deleted = 0 and communicate_deleted = 0 and communicate.communicate_code = ?', $code)
			->group('communicate.communicate_code')
			->limit(1);	

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;		
	}

	public function paginate($start, $length, $filter = array()) {
	
		$where	= 'communicate_deleted = 0';

		if(count($filter) > 0) {
			for($i = 0; $i < count($filter); $i++) {
				if(isset($filter[$i]['filter_search']) && trim($filter[$i]['filter_search']) != '') {
					$array = explode(" ",trim($filter[$i]['filter_search']));					
					if(count($array) > 0) {
						$where .= " and (";
						for($s = 0; $s < count($array); $s++) {
							$text = $array[$s];
							$this->sanitize($text);
							$where .= "lower(concat_ws(communicate_text, communicate_subject)) like lower('%$text%')";
							if(($s+1) != count($array)) {
								$where .= ' or ';
							}
						}
					}
					$where .= ")";
				} else if(isset($filter[$i]['filter_type']) && trim($filter[$i]['filter_type']) != '') {			
					$text = trim($filter[$i]['filter_type']);
					$this->sanitize($text);
					$where .= " and template_type = '$text'";
				}
			}
		}

		$select = $this->_db->select()
			->from(array('communicate' => 'communicate'), array('communicate_code', 'communicate_subject', 'communicate_text', 'communicate_active'))
			->joinLeft(array('template' => 'template'), "template.template_code = communicate.template_code")
			->joinLeft(array('_comm' => '_comm'), "_comm.communicate_code = communicate.communicate_code", array('count(_comm_code) as communicate_count'))
			->joinLeft(array('link' => 'link'), "link.link_parent_type = 'COMMUNICATE' and link.link_parent_code = communicate.communicate_code and link_child_type = 'DEMARCATION' and link_deleted = 0", array('count(link_child_code) as demarcation_count'))		
			->where('template_deleted = 0 and communicate_deleted = 0')
			->where('communicate_deleted = 0 and template.municipality_code = ?', $this->_municipality)
			->where($where)
			->group('communicate.communicate_code');

		$result_count = $this->_db->fetchRow("select count(*) as query_count from ($select) as query");
		$result = $this->_db->fetchAll($select . " limit $start, $length");
		return ($result === false) ? false : $result = array('count'=>$result_count['query_count'],'displayrecords'=>count($result),'records'=>$result);
		
	}		
	/**
	 * get domain by domain Municipality Id
 	 * @param string domain id
     * @return object
	 */
	public function getCode($code) {
		$select = $this->_db->select()	
			->from(array('communicate' => 'communicate'))	
			->where('communicate_code = ?', $code)
			->limit(1);

		$result = $this->_db->fetchRow($select);
        return ($result == false) ? false : $result = $result;					   

	}
	
	function createCode() {
		/* New code. */
		$code	= date('Ymd').md5(date('Ymd').rand(99,1000));
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
	
    function sanitizeArray(&$array) 
    {        
		for($i = 0; $i < count($array); $i++) {
			$array[$i] = preg_replace("/[^a-zA-Z0-9_]+/", "", $array[$i]);
		}
    }	
}
?>