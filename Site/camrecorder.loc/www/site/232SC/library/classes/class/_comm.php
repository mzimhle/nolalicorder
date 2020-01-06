<?php

require_once 'class/template.php';
require_once 'SendGrid/Response.php';
require_once 'SendGrid/Client.php';
require_once 'SendGrid/SendGrid.php';
require_once 'SendGrid/Mail.php';

//custom account item class as account table abstraction
class class_comm extends Zend_Db_Table_Abstract
{
   //declare table variables
    protected $_name	= '_comm';
	protected $_primary	= '_comm_code';

	public $_template	= null;
	public $_path	= null;
	public $_site	= null;
	
	public $_config			= null;
	public $_sendgrid		= null;
	public $_client			= null;
	public $_mail			= null;
	public $_response		= null;

	function init()	{
		global $zfsession;
		$this->_template	= new class_template();
		$this->_path		= isset($zfsession->config['path']) ? $zfsession->config['path'] : null;
		$this->_site		= isset($zfsession->config['site']) ? $zfsession->config['site'] : null;
		$this->_config		= isset($zfsession->config) ? $zfsession->config : null;
	}
	/**
	 * Insert the database record
	 * example: $table->insert($data);
	 * @param array $data
     * @return boolean
	 */ 
	public function insert(array $data) {
        // add a timestamp
        $data['_comm_added'] 	= date('Y-m-d H:i:s');
        $data['_comm_code'] 	= isset($data['_comm_code']) ? $data['_comm_code'] : $this->createCode();
		return parent::insert($data);		
    }
	/**
	 * get job by job _comm Id
 	 * @param string job id
     * @return object
	 */
	public function viewComm($code) {
		$select = $this->_db->select()	
			->from(array('_comm' => '_comm'))				
			->where('_comm_code = ?', $code)					
			->limit(1);
       
	    $result = $this->_db->fetchRow($select);
        return ($result == false) ? false : $result = $result;
	}
	/**
	 * get job by job _comm Id
 	 * @param string job id
     * @return object
	 */
	public function getByCode($code) {		
		$select = $this->_db->select()	
			->from(array('_comm' => '_comm'))						
			->where('_comm_code = ?', $code)					
			->limit(1);
       
		$result = $this->_db->fetchRow($select);
        return ($result == false) ? false : $result = $result;
	}

	public function sendEmail($recepient, $template) {

		global $smarty;

		$data				= array();
		$data['_comm_code']	= $this->createCode();

		$file = $this->_path.$template['template_file'];

		$html = file_get_contents($file);
		/* Other details. */
		$html = str_replace('[tracking]', $data['_comm_code'], $html);		
		$html = str_replace('[date]', date("F j, Y, g:i a"), $html);
		$html = str_replace('[browser]', '<a style="text-decoration: none;color: black;" href="http://[municipality_site]/mailer/view/'.$data['_comm_code'].'">View mail on browser</a>', $html);
		$html = str_replace('[unsubscribe]', '<a style="text-decoration: none;" href="http://[site]/mailer/unsubscribe/'.$data['_comm_code'].'">Click to unsubscribe</a>', $html);
		$html = str_replace('[track]', '<img src="http://[municipality_site]/mailer/tracking/'.$data['_comm_code'].'" width="0" height="0" border="0"  />', $html);		
		/* Body */
		foreach($recepient as $key => $value) {
			$html = str_replace("[$key]", $value, $html);
		}
		/* Subject */
		foreach($recepient as $key => $value) {
			$template['template_subject'] = str_replace("[$key]", $value, $template['template_subject']);
		}		
		if(isset($recepient['recipient_message'])) $html = str_replace('[message]', $recepient['recipient_message'], $html);
		if(isset($recepient['recipient_message'])) $template['template_subject'] = str_replace('[message]', $recepient['recipient_message'], $template['template_subject']);
		$html = str_replace('[host]', $this->_site, $html);	
		/* Setup email. */
		$email_from 	= new Email($recepient['municipality_name'], $recepient['municipality_contact_email']);
		$email_to		= new Email($recepient['recipient_name'], $recepient['recipient_email']);
		$email_content	= new Content("text/html", $html);
		$mail 			= new Mail($email_from, $template['template_subject'], $email_to, $email_content);
		$email_sendgrid	= new SendGrid($this->_config['sendGrid_api']);
		/* Send email. */
		$response = $email_sendgrid->client->mail()->send()->post($mail);
		/* Save data to the comms table. */
		$data['_comm_sent']			= null;
		$data['_comm_type']			= 'EMAIL';				
		$data['_comm_name']			= $recepient['recipient_name'];
		$data['_comm_item_type']	= $recepient['recipient_type'];
		$data['_comm_item_code']	= $recepient['recipient_code'];
		$data['_comm_sent']			= null;
		$data['_comm_email']		= $recepient['recipient_email'];
		$data['_comm_html']			= $html;
		$data['_commstatus_code']	= $response->statusCode();
		$data['_comm_subject']		= $template['template_subject'];
		$data['template_code']		= $template['template_code'];
		/* Send the email. */
		if((int)$data['_commstatus_code'] >= 200 && (int)$data['_commstatus_code'] <= 299) {
			$data['_comm_sent']		= 1;
			$data['_comm_output']	= 'Success!';
		} else {
			$data['_comm_output']	= 'Failed';	
		}
		return $this->insert($data);
	}

	public function sendSMS($recepient, $template) {

		$user 		= urlencode($this->_config['clickatell_user']); //"willowvine"; 
		$password 	= urlencode($this->_config['clickatell_password']); //"DUJbgGdNRXROaA"; 
		$api_id		= urlencode($this->_config['clickatell_api_id']); //"3420082"; 
		$baseurl 	= $this->_config['clickatell_baseurl']; 
		
		$text = $recepient['template_message']; 
		$to	= trim($recepient['recipient_cellphone']);
		/* Body */
		foreach($recepient as $key => $value) {
			$text = str_replace("[$key]", $value, $text);
		}
		$data						= array();
		$data['_comm_sent']			= null;
		$data['_comm_type']			= 'SMS';		
		$data['_comm_name']			= $recepient['recipient_name'];
		$data['_comm_item_type']	= $recepient['recipient_type'];
		$data['_comm_item_code']	= $recepient['recipient_code'];
		$data['_comm_cellphone']	= $recepient['recipient_cellphone'];
		$data['_comm_message']		= $text;
		$data['_comm_subject']		= $recepient['template_subject'];
		$data['template_code']		= $recepient['template_code'];
		
		$text = urlencode($text); 
		
		if( preg_match( "/^0[0-9]{9}$/", $to)) {
			
			$url = "$baseurl/http/auth?user=$user&password=$password&api_id=$api_id"; 

			// do auth call 
			$ret = @file($url); 

			// split our response. return string is on first line of the data returned 

			$sess = explode(":",$ret[0]); 
			
			if ($sess[0] == "OK") {
			
				$sess_id = trim($sess[1]); // remove any whitespace 
				
				$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text"; 
				
				// do sendmsg call 
				$ret = file($url); 
				
				$send = explode(":",$ret[0]); 
				
				if ($send[0] == "ID") { 																						
					$data['_comm_output']	= 'Success! : '.$send[0].' : '.$send[1];
					$data['_comm_sent']		= 1;					
				} else  {
					$data['_comm_output']	= 'Send message failed : '.$send[0].' : '.$send[1];
					$data['_comm_sent']		= 0;	  
				}
			} else { 
				$data['_comm_output']	= "Authentication failure: ". $ret[0]; 
				$data['_comm_sent']		= 0;	  
			} 
		} else {
			$data['_comm_output']	=  "Invalid number ".$participant['participant_number'];	
			$data['_comm_sent']		= 0;		  
		}
		
		$this->insert($data);
		
		return $data['_comm_sent'];
	}
	
	/**
	 * get domain by domain Account Id
 	 * @param string domain id
     * @return object
	 */
	public function getCode($reference)
	{
		$select = $this->_db->select()	
			->from(array('_comm' => '_comm'))		
			->where('_comm_code = ?', $reference)
			->limit(1);

	   $result = $this->_db->fetchRow($select);
        return ($result == false) ? false : $result = $result;				   		
	}

	function createCode() {
		/* New reference. */
		$reference = "";
		$codeAlphabet = "123456789";

		$count = strlen($codeAlphabet) - 1;
		
		for($i=0;$i<10;$i++) {
			$reference .= $codeAlphabet[rand(0,$count)];
		}
		
		$reference = md5($reference.date('Y-m-d H:i:s'));
		
		/* First check if it exists or not. */
		$itemCheck = $this->getCode($reference);
		
		if($itemCheck) {
			/* It exists. check again. */
			$this->createCode();
		} else {
			return $reference;
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