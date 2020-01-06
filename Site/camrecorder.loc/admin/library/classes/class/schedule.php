<?php

//custom account item class as account table abstraction
class class_schedule extends Zend_Db_Table_Abstract
{
   //declare table variables
    protected $_name	= 'schedule';
	protected $_primary	= 'schedule_code';	
	public	$_account	= null;
	
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
        $data['schedule_added']	= date('Y-m-d H:i:s');
        $data['schedule_code']	= $this->createCode();		
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
		$data['schedule_updated'] = date('Y-m-d H:i:s');
        return parent::update($data, $where);
    }
	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function getActive($class, $participant, $date) {
		$select = $this->_db->select()
			->from(array('schedule' => 'schedule'))
			->where('schedule.account_code = ?', $this->_account)
			->where('schedule.class_code = ?', $class)
			->where('schedule.participant_code = ?', $participant)
			->where('schedule_deleted = 0 and schedule.schedule_date = ?', $date);

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;
	}	
	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function getByCode($code) {
		$select = $this->_db->select()
			->from(array('schedule' => 'schedule'))
			->joinInner(array('participant' => 'participant'), "participant.participant_code = schedule.participant_code")
			->joinInner(array('class' => 'class'), "class.class_code = schedule.class_code")
			->where('schedule.account_code = ?', $this->_account)
			->where('schedule_deleted = 0 and schedule.schedule_code = ?', $code)
			->where("class_deleted = 0 and participant_deleted = 0");
			
		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;
	}
	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function getVideo($code) {
		$select = $this->_db->select()
			->from(array('schedule' => 'schedule'))
			->joinInner(array('participant' => 'participant'), "participant.participant_code = schedule.participant_code", array('participant_name'))
			->joinInner(array('class' => 'class'), "class.class_code = schedule.class_code")
			->joinInner(array('course' => 'course'), "course.course_code = class.course_code")
			->joinInner(array('room' => 'room'), "room.room_code = schedule.room_code")
			->where('schedule.account_code = ?', $this->_account)
			->where('course_deleted = 0 and schedule_deleted = 0')
			->where('schedule_deleted = 0 and schedule.schedule_code = ?', $code)
			->where("class_deleted = 0 and participant_deleted = 0 and schedule_video_uploaded = 1");
			
		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;
	}	
	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function paginate($start, $length, $filter = array()) {

		$where	= 'participant_deleted = 0';
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
							$where .= "lower(concat_ws(participant_name, ' ', class_name, ' ', participant_email, ' ', participant_cellphone, course_name)) like lower('%$text%')";
							if(($s+1) != count($array)) {
								$where .= ' or ';
							}
						}
					}
					$where .= ")";
				} else if(isset($filter[$i]['filter_class']) && trim($filter[$i]['filter_class']) != '') {
					$text = trim($filter[$i]['filter_class']);
					$this->sanitize($text);
					$where .= " and class.class_code = '$text'";
				} else if(isset($filter[$i]['filter_room']) && trim($filter[$i]['filter_room']) != '') {
					$text = trim($filter[$i]['filter_room']);
					$this->sanitize($text);
					$where .= " and room.room_code = '$text'";
				} else if(isset($filter[$i]['filter_date']) && trim($filter[$i]['filter_date']) != '') {
					$text = trim($filter[$i]['filter_date']);
					$this->sanitize($text);
					$where .= " and schedule.schedule_date = '$text'";
				} else if(isset($filter[$i]['filter_course']) && trim($filter[$i]['filter_course']) != '') {
					$text = trim($filter[$i]['filter_course']);
					$this->sanitize($text);
					$where .= " and class.course_code = '$text'";
				} else if(isset($filter[$i]['filter_month']) && trim($filter[$i]['filter_month']) != '') {
					$text = trim($filter[$i]['filter_month']);
					$this->sanitize($text);
					$where .= " and MONTH(schedule.schedule_date) = $text";
				} else if(isset($filter[$i]['filter_year']) && trim($filter[$i]['filter_year']) != '') {
					$text = trim($filter[$i]['filter_year']);
					$this->sanitize($text);
					$where .= " and YEAR(schedule.schedule_date) = $text";
				}
			}
		}

		$select = $this->_db->select()
			->from(array('schedule' => 'schedule'))
			->joinInner(array('participant' => 'participant'), "participant.participant_code = schedule.participant_code", array('participant_name'))
			->joinInner(array('class' => 'class'), "class.class_code = schedule.class_code", array('class_name', 'class_cipher'))
			->joinInner(array('course' => 'course'), "course.course_code = class.course_code", array('course_name'))
			->joinInner(array('room' => 'room'), "room.room_code = schedule.room_code", array('room_name'))
			->where('schedule.account_code = ?', $this->_account)
			->where('course_deleted = 0 and schedule_deleted = 0')
			->where("class_deleted = 0 and participant_deleted = 0")
			->where($where)
			->order(array('schedule_video_uploaded asc', 'schedule_date desc', 'schedule_time_start desc'));

		$result_count	= $this->_db->fetchRow("select count(*) as query_count from ($select) as query");
		$result			= $this->_db->fetchAll($select . " limit $start, $length");

		return ($result === false) ? false : $result = array('count' => $result_count['query_count'], 'displayrecords' => count($result), 'records' => $result);
	}
	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function getCode($code) {
		$select = $this->_db->select()	
			->from(array('schedule' => 'schedule'))	
			->where('schedule_code = ?', $code)
			->limit(1);

	   $result = $this->_db->fetchRow($select);	
       return ($result == false) ? false : $result = $result;					   
	}

	function createCode() {
		/* New code. */
		$code = date('Ymdhis').md5(date('Y-m-d h:i:s')).rand(0,10000);
		/* First check if it exists or not. */
		$itemCheck = $this->getCode($code);
		
		if($itemCheck) {
			/* It exists. check again. */
			$this->createCode();
		} else {
			return $code;
		}
	}

	public function checkAvailable($starttime, $endtime, $participant, $date) {
		$select = "select * from schedule where schedule_active = 1 and schedule_deleted = 0 and 
				((schedule_time_start < ? and schedule_time_end > ?) OR
				(schedule_time_start < ? and schedule_time_end > ?) OR
				(schedule_time_start > ? and schedule_time_end < ?)) and participant_code = ? and account_code = ? and schedule_date = ?;";	
		$result = $this->_db->fetchRow($select, array($starttime, $endtime, $endtime, $starttime, $starttime, $endtime, $participant, $this->_account, $date));

		return ($result == false) ? false : $result = $result;			
	}

	public function validateTime($string) {
		if (preg_match('/^\d{2}:\d{2}$/', $string)) {
			if (preg_match("/(2[0-3]|[0][0-9]|1[0-9]):([0-5][0-9])/", $string)) {
				return $string;
			}
		}
		return '';
	}
	
	function getDays($date, $type = 'ONCEOFF', $month) {
		if($type == 'ONCEOFF') {
			return $dates = array($date);
		} else if($type == 'WEEK') {
			// parse about any English textual datetime description into a Unix timestamp 
			$ts = strtotime($date);
			// find the year (ISO-8601 year number) and the current week
			$year = date('o', $ts);
			$week = date('W', $ts);
			$dates = array($date);
			// print week for the current date
			for($i = 1; $i <= 7; $i++) {
				// timestamp from ISO week date format
				$ts = strtotime("+$i day", strtotime($date));
				// if($i != 6 && $i != 7) {
					if($month == date("m", $ts)) {
						$dates[] = date("Y-m-d", $ts);
					}
				// }
			}
			return $dates;
		} else {
			$dayslist 	= array();
			$days 		= array();
			$days[0]	= $date;
			$days[1]	= date( "Y-m-d" ,strtotime('next Sunday', strtotime( $days[0] ) ) );
			$days[2]	= date( "Y-m-d" ,strtotime('+1 week', strtotime( $days[1])));
			$days[3]	= date( "Y-m-d" ,strtotime('+1 week', strtotime( $days[2] ) ) );
			$days[4]	= date( "Y-m-d" ,strtotime('+1 week', strtotime( $days[3] ) ) );
			
			for($i = 0; $i < count($days); $i++) {
				if(date('m', strtotime($days[$i])) == $month) {
					$dayslist = array_merge($dayslist, $this->getDays($days[$i], 'WEEK', $month));
				}
			}
			return $dayslist;
		}
	}
	
    function sanitize(&$string) { $string = preg_replace("/[^a-zA-Z0-9_]+/", "", $string);}
    function sanitizeArray(&$array) { for($i = 0; $i < count($array); $i++) {$array[$i] = preg_replace("/[^a-zA-Z0-9_]+/", "", $array[$i]);}}
}
?>