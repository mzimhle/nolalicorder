<?php
//custom class item class as class table abstraction
class class_class extends Zend_Db_Table_Abstract
{
   //declare table variables
    protected $_name 		= 'class';
	protected $_primary 	= 'class_code';

	public	$_account		= null;
	
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
        $data['class_added']	= date('Y-m-d H:i:s');
		$data['class_code']		= $this->createCode();
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
        $data['class_updated'] = date('Y-m-d H:i:s');
        return parent::update($data, $where);
    }
	/**
	 * get job by job class Id
 	 * @param string job id
     * @return object
	 */
	public function getByCode($code) {

		$select = $this->_db->select()
			->from(array('class' => 'class'))
			->joinInner(array('course' => 'course'), 'course.course_code = class.course_code')
			->joinInner(array('department' => 'department'), 'department.department_code = course.department_code')
			->joinInner(array('faculty' => 'faculty'), 'faculty.faculty_code = department.faculty_code')
			->joinInner(array('qualification' => 'qualification'), 'qualification.qualification_code = course.qualification_code')
			->joinInner(array('account' => 'account'), 'account.account_code = faculty.account_code')				
			->where('course_deleted = 0 and faculty_deleted = 0 and department_deleted = 0 and qualification_deleted = 0 and class_deleted = 0 and account_deleted = 0 and account.account_code = ?', $this->_account)	
			->where('class_code = ?', $code)
			->limit(1);		

		$result = $this->_db->fetchRow($select);
		return ($result == false) ? false : $result = $result;		
	}

	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function pairs(){
		$select = $this->_db->select()
			->from(array('class' => 'class'), array('class_code', "concat(class_cipher,' - Year ', year_code, ' - Semester ', semester_code, ' - ', class_name, ' ( ', course_name, ' )') as class_name"))
			->joinInner(array('course' => 'course'), 'course.course_code = class.course_code', array())
			->joinInner(array('department' => 'department'), 'department.department_code = course.department_code', array())
			->joinInner(array('faculty' => 'faculty'), 'faculty.faculty_code = department.faculty_code', array())
			->joinInner(array('qualification' => 'qualification'), 'qualification.qualification_code = course.qualification_code', array())
			->joinInner(array('account' => 'account'), 'account.account_code = faculty.account_code', array())				
			->where('course_deleted = 0 and faculty_deleted = 0 and department_deleted = 0 and qualification_deleted = 0 and class_deleted = 0 and account_deleted = 0 and account.account_code = ?', $this->_account)	
			->order(array('course_name', 'year_code', 'semester_code'));

		$result = $this->_db->fetchPairs($select);	
        return ($result == false) ? false : $result = $result;
	}	

	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function pairsByCourse($code){
		$select = $this->_db->select()
			->from(array('class' => 'class'), array('class_code', "concat(class_cipher,' - Year ', year_code, ' - Semester ', semester_code, ' - ', class_name, ' ( ', course_name, ' )') as class_name"))
			->joinInner(array('course' => 'course'), 'course.course_code = class.course_code', array())
			->joinInner(array('department' => 'department'), 'department.department_code = course.department_code', array())
			->joinInner(array('faculty' => 'faculty'), 'faculty.faculty_code = department.faculty_code', array())
			->joinInner(array('qualification' => 'qualification'), 'qualification.qualification_code = course.qualification_code', array())
			->joinInner(array('account' => 'account'), 'account.account_code = faculty.account_code', array())				
			->where('course_deleted = 0 and faculty_deleted = 0 and department_deleted = 0 and qualification_deleted = 0 and class_deleted = 0 and account_deleted = 0 and account.account_code = ?', $this->_account)
			->where('class.course_code = ?', $code)				
			->order(array('course_name', 'year_code', 'semester_code'));

		$result = $this->_db->fetchPairs($select);	
        return ($result == false) ? false : $result = $result;
	}	
	public function paginate($start, $length, $filter = array()) {
	
		$where	= 'class_deleted = 0';
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
							$where .= "lower(concat_ws(class_name, ' ', department_name, ' ', class_cipher)) like lower('%$text%')";
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
			->from(array('class' => 'class'))
			->joinInner(array('course' => 'course'), 'course.course_code = class.course_code', array('course_name', 'course_cipher'))
			->joinInner(array('department' => 'department'), 'department.department_code = course.department_code', array('department_name'))
			->joinInner(array('faculty' => 'faculty'), 'faculty.faculty_code = department.faculty_code', array('faculty_name'))
			->joinInner(array('qualification' => 'qualification'), 'qualification.qualification_code = course.qualification_code', array('qualification_cipher', 'qualification_name'))
			->joinInner(array('account' => 'account'), 'account.account_code = faculty.account_code', array('account_name'))				
			->where('course_deleted = 0 and faculty_deleted = 0 and department_deleted = 0 and qualification_deleted = 0 and class_deleted = 0 and account_deleted = 0 and account.account_code = ?', $this->_account)		
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
	
	public function search($term, $limit = 10) {
	
		$select = $this->_db->select()
			->from(array('class' => 'class'))
			->joinInner(array('course' => 'course'), 'course.course_code = class.course_code', array('course_name', 'course_cipher'))
			->joinInner(array('department' => 'department'), 'department.department_code = course.department_code', array('department_name'))
			->joinInner(array('faculty' => 'faculty'), 'faculty.faculty_code = department.faculty_code', array('faculty_name'))
			->joinInner(array('qualification' => 'qualification'), 'qualification.qualification_code = course.qualification_code', array('qualification_cipher', 'qualification_name'))
			->joinInner(array('account' => 'account'), 'account.account_code = faculty.account_code', array('account_name'))				
			->where('course_deleted = 0 and faculty_deleted = 0 and department_deleted = 0 and qualification_deleted = 0 and class_deleted = 0 and account_deleted = 0 and account.account_code = ?', $this->_account)				
			->where("lower(concat(class_name, course_name, class_cipher, department_name, faculty_name, qualification_name)) like lower(?)", "%$term%")
			->limit($limit)
			->order("LOCATE('$term', concat_ws(class_name, class_cipher, course_name, department_name, faculty_name, qualification_name))");

		$result = $this->_db->fetchAll($select);
		return ($result == false) ? false : $result = $result;	
	}	
	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function getCode($code) {
		$select = $this->_db->select()	
			->from(array('class' => 'class'))	
			->where('class_code = ?', $code)
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