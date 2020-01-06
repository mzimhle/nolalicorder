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
}
?>