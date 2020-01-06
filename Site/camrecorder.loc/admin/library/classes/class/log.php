<?php
require_once "Zend/Paginator.php"; 
//custom log item class as log table abstraction
class class_log extends Zend_Db_Table_Abstract
{
   //declare table variables
    protected $_name	= 'log';
	protected $_primary	= 'log_code';
	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function paginate($start, $length, $filter = array()) {
	
		$where	= "log_type is not null";
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
							$where .= "lower(concat_ws(log_type, ' ', log_json, ' ', log_message)) like lower('%$text%')";
							if(($s+1) != count($array)) {
								$where .= ' or ';
							}
						}
					}
					$where .= ")";
				} else if(isset($filter[$i]['filter_device']) && trim($filter[$i]['filter_device']) != '') {
					$text = trim($filter[$i]['filter_device']); 					
					$where .= "and device.device_code = '$text' ";
				}
			}
		}

		$select = $this->_db->select()
			->from(array('log' => 'log'))
			->joinInner(array('device' => 'device'), 'log.device_code = device.device_code', array())				
			->where($where)
			->order('log_added desc');

		if($csv) {
			$result = $this->_db->fetchAll($select);
			return ($result == false) ? false : $result = $result;	
		} else {
			$result_count = $this->_db->fetchRow("select count(*) as query_count from ($select) as query");
			$result = $this->_db->fetchAll($select . " limit $start, $length");
			return ($result === false) ? false : $result = array('count'=>$result_count['query_count'],'displayrecords'=>count($result),'records'=>$result);
		}
	}

    function sanitize(&$string) { $string = preg_replace("/[^a-zA-Z0-9_]+/", "", $string);}
    function sanitizeArray(&$array) { for($i = 0; $i < count($array); $i++) {$array[$i] = preg_replace("/[^a-zA-Z0-9_]+/", "", $array[$i]); } }
}
?>