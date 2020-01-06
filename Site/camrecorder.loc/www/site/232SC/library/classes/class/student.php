<?php
require_once "Zend/Paginator.php"; 
//custom student item class as student table abstraction
class class_student extends Zend_Db_Table_Abstract
{
   //declare table variables
    protected $_name 		= 'student';
	protected $_primary 	= 'student_code';

	public	$_account		= null;
	public	$_participant	= null;
	
	function init()	{
		global $zfsession;
		$this->_account		= $zfsession->account;
		$this->_participant	= $zfsession->identity;
	}	
	
	public function paginate($filter = array(), $page = 1, $perPage = 10, $listedPages = 10, $scrollingStyle = 'Sliding') {
	
	
		$where	= 'student_deleted = 0';
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
							$where .= "lower(concat_ws(class_name, ' ', course_name)) like lower('%$text%')";
							if(($s+1) != count($array)) {
								$where .= ' or ';
							}
						}
					}
					$where .= ")";
				} else if(isset($filter[$i]['filter_class']) && trim($filter[$i]['filter_class']) != '') {
					$text = trim($filter[$i]['filter_class']);
					$this->sanitize($text);
					$where .= " and student.class_code = '$text'";
				}
			}
		}

		$select = $this->_db->select()->distinct()
			->from(array('schedule' => 'schedule'), array('schedule_code', 'schedule_date', 'schedule_time_start', 'schedule_time_end', 'schedule_video_format', 'schedule_video_size', 'schedule_video_path'))
			->joinInner(array('account' => 'account'), 'account.account_code = schedule.account_code', array('account_name'))
			->joinInner(array('teacher' => 'participant'), 'teacher.participant_code = schedule.participant_code', array('participant_name as teacher_name', 'participant_email as teacher_email', 'participant_cellphone as teacher_cellphone'))
			->joinInner(array('class' => 'class'), 'class.class_code = schedule.class_code', array('class_name'))
			->joinInner(array('course' => 'course'), 'course.course_code = class.course_code', array('course_name'))
			->joinInner(array('department' => 'department'), 'department.department_code = course.department_code', array('department_name'))
			->joinInner(array('faculty' => 'faculty'), 'faculty.faculty_code = department.faculty_code', array('faculty_name'))
			->joinInner(array('room' => 'room'), 'room.room_code = schedule.room_code', array('room_name', 'room_cipher', 'room_location'))
			->joinInner(array('campus' => 'campus'), 'campus.campus_code = room.campus_code', array('campus_name'))
			->joinInner(array('student' => 'student'), 'student.class_code = schedule.class_code', array())
			->joinInner(array('participant' => 'participant'), 'participant.participant_code = student.participant_code', array('participant_name', 'participant_email', 'participant_cellphone'))
			->where('class_deleted = 0 and teacher.participant_deleted = 0 and participant.participant_deleted = 0 and student_deleted = 0 and account_deleted = 0 and account.account_code = ?', $this->_account)
			->where('schedule_video_uploaded = 1 and student.participant_code = ?', $this->_participant)
			->where($where)
			->order('student.student_added desc');

		$paginator = Zend_Paginator::factory($select);
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage($perPage);
		$paginator->setPageRange($listedPages);
		$paginator->setDefaultScrollingStyle($scrollingStyle); 
		$pages = $paginator;

		return $pages;
	}

	public function classes() {

		$select = $this->_db->select()
			->from(array('student' => 'student'))
			->joinInner(array('class' => 'class'), 'class.class_code = student.class_code')
			->joinInner(array('course' => 'course'), 'course.course_code = class.course_code')
			->joinInner(array('department' => 'department'), 'department.department_code = course.department_code')
			->joinInner(array('faculty' => 'faculty'), 'faculty.faculty_code = department.faculty_code')
			->joinInner(array('participant' => 'participant'), 'participant.participant_code = student.participant_code')
			->joinInner(array('account' => 'account'), 'account.account_code = participant.account_code')
			->where('class_deleted = 0 and participant_deleted = 0 and student_deleted = 0 and account_deleted = 0 and account.account_code = ?', $this->_account)
			->where('student.participant_code = ?', $this->_participant);
			
		$result = $this->_db->fetchAll($select);
		return ($result == false) ? false : $result = $result;		
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