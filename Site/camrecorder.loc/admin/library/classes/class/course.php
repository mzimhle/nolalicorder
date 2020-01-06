<?php
//custom course item class as course table abstraction
class class_course extends Zend_Db_Table_Abstract
{
   //declare table variables
    protected $_name 		= 'course';
	protected $_primary 	= 'course_code';

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
        // add a timestamp
        $data['course_added']		= date('Y-m-d H:i:s');
		$data['course_code']		= $this->createCode();
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
        $data['course_updated'] = date('Y-m-d H:i:s');
        return parent::update($data, $where);
    }
	/**
	 * get job by job course Id
 	 * @param string job id
     * @return object
	 */
	public function getByCode($code) {

		$select = $this->_db->select()
			->from(array('course' => 'course'))
			->joinInner(array('department' => 'department'), 'department.department_code = course.department_code')
			->joinInner(array('faculty' => 'faculty'), 'faculty.faculty_code = department.faculty_code')
			->joinInner(array('qualification' => 'qualification'), 'qualification.qualification_code = course.qualification_code')
			->joinInner(array('account' => 'account'), 'account.account_code = faculty.account_code')				
			->where('faculty_deleted = 0 and department_deleted = 0 and qualification_deleted = 0 and course_deleted = 0 and account_deleted = 0 and account.account_code = ?', $this->_account)	
			->where('course_code = ?', $code)
			->limit(1);		

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;		
	}
	/**
	 * get job by job course Id
 	 * @param string job id
     * @return object
	 */
	public function byDepartment($code) {

		$select = $this->_db->select()
			->from(array('course' => 'course'))
			->joinInner(array('department' => 'department'), 'department.department_code = course.department_code')
			->joinInner(array('faculty' => 'faculty'), 'faculty.faculty_code = department.faculty_code')
			->joinInner(array('qualification' => 'qualification'), 'qualification.qualification_code = course.qualification_code')
			->joinInner(array('account' => 'account'), 'account.account_code = faculty.account_code')				
			->where('faculty_deleted = 0 and department_deleted = 0 and qualification_deleted = 0 and course_deleted = 0 and account_deleted = 0 and account.account_code = ?', $this->_account)	
			->where('course.department_code = ?', $code);

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
			->from(array('course' => 'course'), array('course_code', "concat(department_name, ' - ', course_cipher, ' - ', course_name) course_name"))
			->joinInner(array('department' => 'department'), 'department.department_code = course.department_code', array())
			->joinInner(array('faculty' => 'faculty'), 'faculty.faculty_code = department.faculty_code', array())
			->joinInner(array('qualification' => 'qualification'), 'qualification.qualification_code = course.qualification_code', array())
			->joinInner(array('account' => 'account'), 'account.account_code = faculty.account_code', array())				
			->where('faculty_deleted = 0 and department_deleted = 0 and qualification_deleted = 0 and course_deleted = 0 and account_deleted = 0 and account.account_code = ?', $this->_account)	
			->where('account_deleted = 0 and account.account_code = ?', $this->_account);

		$result = $this->_db->fetchPairs($select);	
        return ($result == false) ? false : $result = $result;
	}	
	
	public function paginate($start, $length, $filter = array()) {
	
		$where	= 'course_deleted = 0';
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
							$where .= "lower(concat_ws(course_name, ' ', department_name)) like lower('%$text%')";
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
			->from(array('course' => 'course'))
			->joinInner(array('department' => 'department'), 'department.department_code = course.department_code', array('department_name'))
			->joinInner(array('faculty' => 'faculty'), 'faculty.faculty_code = department.faculty_code', array('faculty_name'))
			->joinInner(array('qualification' => 'qualification'), 'qualification.qualification_code = course.qualification_code', array('qualification_cipher', 'qualification_name'))
			->joinInner(array('account' => 'account'), 'account.account_code = faculty.account_code', array('account_name'))		
			->where('faculty_deleted = 0 and department_deleted = 0 and qualification_deleted = 0 and course_deleted = 0 and account_deleted = 0 and account.account_code = ?', $this->_account)			
			->where('course_deleted = 0 and account_deleted = 0 and account.account_code = ?', $this->_account)		
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
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function getCode($code) {
		$select = $this->_db->select()	
			->from(array('course' => 'course'))	
			->where('course_code = ?', $code)
			->limit(1);

		$result = $this->_db->fetchRow($select);
        return ($result == false) ? false : $result = $result;
	}
	
	function createCode() {
		/* New code. */
		$code	= rand(0,10000).md5(date("Y-m-d h:i:s"));		
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
    function sanitizeArray(&$array) { for($i = 0; $i < count($array); $i++) { $array[$i] = preg_replace("/[^a-zA-Z0-9_]+/", "", $array[$i]); } }
}
?>