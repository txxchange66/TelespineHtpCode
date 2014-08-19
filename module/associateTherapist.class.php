<?php


	/**
	 *
	 * Copyright (c) 2008 Tx Xchange
	 *
	 * Class for associating patient with therapist.
	 * 
	 * // necessary classes 
	 * require_once("module/application.class.php");
	 * 
	 * // pagination class
	 * require_once("include/paging/my_pagina_class.php");
	 * 
	 */
		
	
	// including files
	require_once("include/paging/my_pagina_class.php");
	require_once("module/application.class.php");
	

	// class declaration
  	class associateTherapist extends application{
  		
	// class variable declaration section

	
  		/**
  		 * Get action from header and calling function
  		 * The variable defines the action request
  		 *
  		 * @var string
  		 * @access private
  		 */
		private $action;
		
		
		
		/**
		 * Populate html form elements as an array
		 * The variable defines all the fields present in the form
		 *
		 * @var array
		 * @access private
		 */
		private $form_array;
		
		
		
		/**
  		 * The variable defines the error message(if any) against the action request
  		 * It could be an array if more than one error messages are there else a simple variable
		 *
		 * @var string
		 * @access private
		 */
		private $error;	
		
		
		
		/**
         * Pass as parameter in display() function which shows output in browser.
         * 
  		 * The variable is used for getting final output template or string message to be displayed to the user
		 * This function of statement(s) are to handle all the actions supported by this Login class
		 * that is it could be the case that more then one action are handled by login
		 * for example at first the action is "login" then after submit say action is submit
		 * so if login is explicitly called we have the login action set (which is also our default action)
		 * else whatever action is it is set in $str.
		 *
		 * @var string
		 * @access private
		 */
		private $output;
		
		
		
		
		
		### Constructor #####
		
		/**
		 *  constructor
		 *  set action variable from url string, if not found action in url, call default action from config.php
		 * 
		 */
		function __construct(){

			parent::__construct();

			if($this->value('action')){

				/*

					This block of statement(s) are to handle all the actions supported by this Login class

					that is it could be the case that more then one action are handled by login

					for example at first the action is "login" then after submit say action is submit

					so if login is explicitly called we have the login action set (which is also our default action)

					else whatever action is it is set in $str.				

				*/

				$str = $this->value('action');

			}else{

				$str = "therapist"; //default if no action is specified

			}

			$this->action = $str;

			if($this->get_checkLogin($this->action) == "true" ){

				if( isset($_SESSION['username']) && isset($_SESSION['password']) ){

					if(!$this->chk_login($_SESSION['username'],$_SESSION['password'])){

						header("location:index.php");

					}

				}

				else{

					header("location:index.php");

				}

			}

			//$str = $str."()";

			//eval("\$this->$str;"); 
			
			if($this->userAccess($str)){
				$str = $str."()";
				eval("\$this->$str;"); 
			}
			else{
				$this->output = $this->config['error_message'];
			}

			$this->display();

		}

		
		### Class Functions #####
		
		
		/**
		 * Associate patient with therapist selected from list.
		 * Therapist and Patient both should be of same clinic.
		 * 
		 * When associating from Therapist Interface
		 *
		 * @access public
		 */
		function associateTherapist()
		{

			
			if($this->userInfo('usertype_id')  ==  4 ){
                 if( !empty($_SESSION['parent_clinic_id']) ){
                    $clinicId = $_SESSION['parent_clinic_id'];        
                }
                $patientId = $this->value('patient_id');
                if( $this->value('from') == 'patient_manager' && is_numeric($patientId) && $patientId > 0 ){
                    $clinicId = $this->clinicInfo('clinic_id',$patientId);
                    $parentClinicId = $this->get_field($clinicId,'clinic','parent_clinic_id');
                    if( $parentClinicId != 0 ){
                        $clinicId = $parentClinicId;    
                    }
                    $_SESSION['parent_clinic_id'] = $clinicId;
                    
                }
				$query = "SELECT u.user_id FROM user as u 
				inner join clinic_user as cu on u.user_id = cu.user_id 
				WHERE cu.clinic_id in ( select clinic_id from clinic where parent_clinic_id = '{$clinicId}' or clinic_id = '$clinicId' ) 
				AND u.usertype_id = '2' 
				AND u.therapist_access='1' AND u.status = '1' ";
                
			}
			else{
                if( $this->userInfo('usertype_id') == 2 ){
                    $clinicId = $this->clinicInfo('clinic_id');
                    $parentClinicId = $this->get_field($clinicId,'clinic','parent_clinic_id');
                    if( $parentClinicId != 0 ){
                        $clinicId = $parentClinicId;    
                    }
                }
				$query = "SELECT u.user_id FROM user as u 
				inner join clinic_user as cu on u.user_id = cu.user_id 
				WHERE cu.clinic_id in ( select clinic_id from clinic where parent_clinic_id = '{$clinicId}' or clinic_id = '$clinicId' ) 
				AND u.usertype_id = '2' 
				AND u.therapist_access='1' AND u.status = '1' ";					
			}
			
			$rs = $this->execute_query($query);
			if( !($this->num_rows($rs) > 0) ){
				$this->output = $this->build_template($this->get_template("noAssociatedTherapist"),$replace);				
				return ; 
			}
			
			
			$actionActivateFrom = $this->value('actionActivateFrom');
			$actionActivateFrom = (!empty($actionActivateFrom)) ? 'accountAdmin': '';
			
			$replace = array();
			$replace['browser_title'] = "Associate Provider";			
			$userInfo = $this->userInfo();
			
			$patientId = $this->value('patient_id');
			
			$check = '1';
			
			if($patientId == session_id()){
				$check = '0';
				$therapist_patient = 'tmp_therapist_patient';
			}else{
				$therapist_patient = 'therapist_patient';
			}			
			
			if(empty($patientId))
			{	
				// close window			
				$closeWindowStr = "		
									<script language='JavaScript' type='text/javascript'>
										window.close();
									</script>";
				echo $closeWindowStr;					
				exit();
			}
						
			/* Search String if any */
			
			$sqlWhere = "";
			
			if($this->value('search')!='')
			{
				$privateKey = $this->config['private_key'];
                $arr_search = array("CAST(AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as CHAR)");
                $sqlWhere = " AND ".$this->makeSearch(ALL_WORDS,$this->value('search'),$arr_search);
				$replace['searchStr'] = '&search='.$this->value('search');
			}
			else 
			{
				$sqlWhere = "";
				$replace['searchStr'] = "";
			}	

			/* Defining Sorting */				
			$orderByClause = "";
			
			if($this->userInfo('usertype_id') == '4' ){
                $clinic_id = $clinicId;
			}
			else{
				$clinic_id = "";
			}
			
			$privateKey = $this->config['private_key'];
            if ($this->value('sort') == '') 
			{
				$replace['full_name'] = "action=associateTherapist&clinic_id=".$clinic_id."&patient_id=".$patientId."&sort= full_name&order= DESC".$replace['searchStr'];
				$replace['address'] = "action=associateTherapist&clinic_id=".$clinic_id."&patient_id=".$patientId."&sort= address&order= ASC".$replace['searchStr'];
				$replace['address2'] = "action=associateTherapist&clinic_id=".$clinic_id."&patient_id=".$patientId."&sort= address2&order= ASC".$replace['searchStr'];
				$replace['city'] = "action=associateTherapist&clinic_id=".$clinic_id."&patient_id=".$patientId."&sort= city&order= ASC".$replace['searchStr'];
				$replace['phone1'] = "action=associateTherapist&clinic_id=".$clinic_id."&patient_id=".$patientId."&sort= phone1&order= ASC".$replace['searchStr'];
				$replace['phone2'] = "action=associateTherapist&clinic_id=".$clinic_id."&patient_id=".$patientId."&sort= phone2&order= ASC".$replace['searchStr'];
				$replace['fax'] = "action=associateTherapist&clinic_id=".$clinic_id."&patient_id=".$patientId."&sort= fax&order= ASC".$replace['searchStr'];
				$replace['last_login'] = "action=associateTherapist&clinic_id=".$clinic_id."&patient_id=".$patientId."&sort= last_login&order= ASC".$replace['searchStr'];	
				$replace['full_nameImg'] = '&nbsp;<img src="images/sort_asc.gif">';
				
				$orderByClause = " AES_DECRYPT(UNHEX(name_first),'{$privateKey}') , AES_DECRYPT(UNHEX(name_last),'{$privateKey}')  ";
			}
			else {
				$queryStr = "&clinic_id=".$clinic_id."&patient_id=".$patientId.$replace['searchStr'];
				$this->setSortFields($replace,"associateTherapist",$queryStr);
                if( $this->value('sort') == 'full_name' ){
                    if($this->value('order') == 'DESC' ){
                        $orderByClause = " AES_DECRYPT(UNHEX(name_first),'{$privateKey}') DESC, AES_DECRYPT(UNHEX(name_first),'{$privateKey}') DESC ";
                    }
                    else{
                        $orderByClause = " AES_DECRYPT(UNHEX(name_last),'{$privateKey}') , AES_DECRYPT(UNHEX(name_last),'{$privateKey}')  ";
                    }
                    
                }
                else	
				    $orderByClause = $replace['orderByClause'];
				
			}
			
			/* End */	
						
			$replace['statusMessage'] = "";
			
			//Get the therapist list
            $privateKey = $this->config['private_key'];
			if($this->userInfo('usertype_id')  ==  4 ){
				$sqlTherapist = "SELECT *, 
								CONCAT_WS(' ',AES_DECRYPT(UNHEX(name_title),'{$privateKey}'),AES_DECRYPT(UNHEX(name_first),'{$privateKey}'),
                                AES_DECRYPT(UNHEX(name_last),'{$privateKey}'),name_suffix) AS full_name 
								FROM user as u inner join clinic_user as cu on u.user_id=cu.user_id 
								WHERE 
								u.usertype_id = 2 AND u.therapist_access='1' 
								AND u.status = 1 AND cu.clinic_id in ( select clinic_id from clinic where parent_clinic_id = '{$clinicId}' or clinic_id = '$clinicId' ) " .$sqlWhere." 
								ORDER BY ".$orderByClause;
			}
			else{
				if($this->userInfo('usertype_id')  ==  2){
							$sqlTherapist = "SELECT *, 
								CONCAT_WS(' ',AES_DECRYPT(UNHEX(name_title),'{$privateKey}'),AES_DECRYPT(UNHEX(name_first),'{$privateKey}'),
                                AES_DECRYPT(UNHEX(name_last),'{$privateKey}'),name_suffix) AS full_name 
								FROM user as u inner join clinic_user as cu on u.user_id=cu.user_id 
								WHERE 
								u.usertype_id = 2 AND u.therapist_access='1' 
								AND u.status = 1 AND cu.clinic_id in ( select clinic_id from clinic where parent_clinic_id = '{$clinicId}' or clinic_id = '$clinicId' )  ".$sqlWhere." 
								ORDER BY ".$orderByClause;
				}
			}	
			
			$link = $this->pagination($rows = 0,$sqlTherapist,'associateTherapist',$this->value('search'),'');                                          

            $replace['link'] = $link['nav'];

            $result = $link['result']; 	
			
			if($this->num_rows($result)!= 0)
			{
				$replace['assocTherapistTblHead'] = $this->build_template($this->get_template("assocTherapistTblHead"),$replace);
				while($row = $this->fetch_array($result))
				{
					// Start Decrypt data
                    $encrypt_field = array('name_title','name_first','name_last','password','address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
                    $row = $this->decrypt_field($row, $encrypt_field);
                    // End Decrypt data
                    
                    $row['style'] = ($c++%2)?"line1":"line2";
					if ($row['last_login'] != null) 
					{
						$row['last_login'] = $this->formatDate($row['last_login']);					
					}	
					else 
					{
						$row['last_login'] = "&nbsp;";
					}						
					
					$row['address'] = (empty($row['address'])) ? "&nbsp;" :$row['address'];
					$row['address2'] = (empty($row['address2'])) ? "&nbsp;" :$row['address2'];
					$row['city'] = (empty($row['city'])) ? "&nbsp;" :$row['city'];
					$row['phone1'] = (empty($row['phone1'])) ? "&nbsp;" :$row['phone1'];
					$row['phone2'] = (empty($row['phone2'])) ? "&nbsp;" :$row['phone2'];			
					$row['fax'] = (empty($row['fax'])) ? "&nbsp;" :$row['fax'];	 
					$row['actionActivateFrom'] = (!empty($actionActivateFrom)) ? 'accountAdmin': '';
					$row['patientId'] = $patientId;
					$replace['assocTherapistTblRecord'] .=  $this->build_template($this->get_template("assocTherapistTblRecord"),$row);
				}
			}
			else 
			{
				$replace['assocTherapistTblRecord'] =  "No therapists to list";
			}					
			
			if ($sqlWhere == "") 			
			{		
				$extraParam['patientId'] = $patientId;
				$replace['filter'] = $this->build_template($this->get_template("assocTherapistFilter"),$extraParam);	
			}
			else {
				$extraParam['searchOn'] = "";
				$extraParam['patientId'] = $patientId;
				$replace['filter'] = $this->build_template($this->get_template("assocTherapistFilterClear"),$extraParam);
			}	
			// SQL to list the therapist of the clinic, whose user is logged in
			if($this->userInfo('usertype_id')  ==  4 ){
				$query = "SELECT u.user_id FROM user as u inner join clinic_user as cu on u.user_id = cu.user_id WHERE cu.clinic_id in ( select clinic_id from clinic where parent_clinic_id = '{$clinicId}' or clinic_id = '$clinicId' )  AND u.usertype_id = '2' AND u.therapist_access='1'";
			}
			else{
				$query = "SELECT u.user_id FROM user as u inner join clinic_user as cu on u.user_id = cu.user_id WHERE cu.clinic_id in ( select clinic_id from clinic where parent_clinic_id = '{$clinicId}' or clinic_id = '$clinicId' )  AND u.usertype_id = '2' AND u.therapist_access='1'";					
			}
            //in ( select clinic_id from clinic where parent_clinic_id = '{$clinicId}' or clinic_id = '$clinicId' ) 
			$rs = $this->execute_query($query);
			$row_therapist = $this->populate_array($rs, '0');
			$in_parameter = $this->generateInQueryParameter($row_therapist);
            $privateKey = $this->config['private_key'];
			$sql = "SELECT *, 
                    AES_DECRYPT(UNHEX(u.name_title),'{$privateKey}') as name_title, 
                    AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as name_first,
                    AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as name_last
                    FROM $therapist_patient as tp inner join user as u on tp.therapist_id = u.user_id WHERE tp.patient_id = '".$patientId."' AND tp.therapist_id IN ($in_parameter)";
			$sqlResult = $this->execute_query($sql);
			
			$catCount = 0;
			
			if($this->num_rows($sqlResult) != 0)
			{
				while($c = $this->fetch_array($sqlResult))
				{
					$catCount++;
					$record['therapistName'] = $c['name_title'].' '.$c['name_first'].' '.$c['name_last'].' '.$c['name_suffix'];
					$record['userName'] = $c['username'];
					$record['therapistId'] =  $c['user_id'];
					$record['patientId'] = $patientId;
					$record['actionActivateFrom'] = $actionActivateFrom;
					$replace['assignedTherapistRecord'] .= $this->build_template($this->get_template("assignedTherapistRecord"),$record);					
				}					
				
			}
			else 
			{
				$replace['assignedTherapistRecord'] = "";
			}
			
			
			// Find out if therapist should be singular or plural.
			if($catCount > 1) $therapist = 'Providers';
			else $therapist = 'Provider';
			
			if( $this->userInfo('usertype_id') == 4){
                $replace['clinic_id'] = $clinicId;
			}
			
			$replace['totTherapist'] = $catCount.' '.$therapist;
            $replace['therapistName'] =  $this->getAssociatedTherapist($this->value('patient_id'));
			$this->output = $this->build_template($this->get_template("assocTherapistTemplate"),$replace);			
				
		}
        /**
        * Get associated Therapist.
        */
        function getAssociatedTherapist($patientId = ''){
            if( is_numeric($patientId) && $patientId > 0 ){
                $privateKey = $this->config['private_key'];
                $query = "  select u.username,
                            AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as name_first,
                            AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as name_last 
                            from therapist_patient tp
                            inner join user u on u.user_id = tp.therapist_id 
                            where tp.patient_id  = '{$patientId}' ";
                            
                $result = @mysql_query($query);            
                if( @mysql_num_rows($result) > 0 ){
                    $therapistName = '<table border="0" cellpadding="0" cellspacing="0" width="100%" >';
                      while( $row = @mysql_fetch_array($result) ){
                          $therapistName .= '<tr><td>'.$row['name_first'].'&nbsp;'.$row['name_last'].'</td></tr>';
                      }
                    $therapistName .= '</table>';
                    return $therapistName;
                }
            }
            return '';
        }
        /**
        * Returns true if patient is associated with clinic otherwise false.
        */
        function checkAssociationClinic(){
            $patientId = $this->value('patient_id');
             if(is_numeric($patientId) && $patientId > 0 ){
                    $clinicId = $this->clinicInfo('clinic_id',$patientId);
                    if( is_numeric($clinicId) && $clinicId > 0 ){
                        $this->output = 'success';
                        return;
                    }
             }
             $this->output = 'failed';
             return;
        }
		/**
		 * Preserve paramenters of query string to sort the list on any column while changing action.
		 *
		 * @param array $replace
		 * @param string $action
		 * @param string $queryStr
		 * @access public
		 */
		function setSortFields(&$replace,$action,$queryStr)
		{
			include_once("template/assocTherapist/assocTherapistArray.php");
			
			$orderByClause = "";		
									
			foreach ($sortColTblArray as $key => $value)
			{
				$strKey = $key.'Img';
				
				if ($this->value('sort') == $key)
				{
					if($this->value('order') == "ASC")
					{
						$replace[$key] = "action=".$action.$queryStr."&sort=".$key."&order= DESC";
						
						$replace[$strKey] = '&nbsp;<img src="images/sort_asc.gif">';
					}
					else 
					{
						$replace[$key] = "action=".$action.$queryStr."&sort=".$key."&order= ASC";
						$replace[$strKey] = '&nbsp;<img src="images/sort_desc.gif">';
					}
					
					$replace['orderByClause'] = $value[$this->value('order')];
					
				}
				else
				{
					
					$replace[$key] = "action=".$action.$queryStr."&sort=".$key."&order= ASC";
					$replace[$strKey] = '';
					
				}			
				
			}					
			
			
		}
		
		
		/**
		 * Associate patient with therapist selected from list.
		 * Therapist and Patient both should be of same clinic.
		 * 
		 * When associating from Account Admin interface
		 *
		 * @access public
		 */
		function assignTherapistToPatient()
		{
			
			$actionActivateFrom = $this->value('actionActivateFrom');
			$actionActivateFrom = (!empty($actionActivateFrom)) ? 'accountAdmin': '';
				
			$patientId = $this->value('patient_id');
			
			$check = '1';
			if($patientId == session_id()){
				$check = '0';
				$therapist_patient = 'tmp_therapist_patient';
			}else{
				$therapist_patient = 'therapist_patient';
			}
					
			$user_id = (int) $this->value('user_id');
			//check for duplicate
			$sqlSelect = "SELECT * FROM $therapist_patient WHERE therapist_id = $user_id AND patient_id = '".$patientId."'";
			$resultSelect = $this->execute_query($sqlSelect);	
			
			if  ($this->num_rows($resultSelect) == 0)
			{
	
				//Add patient2therapist
				if($check == '0'){
					$query = "INSERT INTO tmp_therapist_patient (therapist_id, user_id, patient_id,creation_date,status) VALUES ('".$user_id."', '".$this->userInfo('user_id')."', '".$patientId."',NOW(),1)";
				}else{
					$query = "INSERT INTO therapist_patient (therapist_id, patient_id,creation_date,status) VALUES (".$user_id.",'".$patientId."',NOW(),1)";
				}

				$result = $this->execute_query($query);								
								
				if ($result)
				{									
					$_SESSION['msg'] = 'Added Provider to patient successfully.';				
	
				}
				else 
				{
					//some errror not handling										
				}		
			}		
			if( $this->userInfo('usertype_id') == 4){
				$clinic_id = $this->value('clinic_id');
			}
			header("Location: index.php?action=associateTherapist&clinic_id=$clinic_id&patient_id=$patientId&actionActivateFrom=$actionActivateFrom");
			exit();	
			
		}
		
		
		/**
		 * Remove the association from selected therapist .
		 *
		 * @access public
		 */
		function removeAssocTherapist()
		{
			//remove 
			
			$actionActivateFrom = $this->value('actionActivateFrom');
			$actionActivateFrom = (!empty($actionActivateFrom)) ? 'accountAdmin': '';
			
			$patientId = $this->value('patient_id');
			
			$check = '1';
			
			if($patientId == session_id()){
				$check = '0';
				$therapist_patient = 'tmp_therapist_patient';
			}else{
				$therapist_patient = 'therapist_patient';
			}
						
			$therapistId = (int) $this->value('therapist_id');
	
			$sqlUpdate = "DELETE FROM $therapist_patient WHERE therapist_id = $therapistId AND patient_id = '".$patientId."'";
			
			$result = $this->execute_query($sqlUpdate);								
								
			if ($result)
			{									
				$_SESSION['msg'] = 'Provider has been removed from associated Providers.';		

			}
			else 
			{
				//some errror not handling										
			}			
			if( $this->userInfo('usertype_id') == 4){
				$clinic_id = $this->value('clinic_id');
			}
			header("Location: index.php?action=associateTherapist&clinic_id=$clinic_id&patient_id=$patientId&actionActivateFrom=$actionActivateFrom");
			exit();			
			
		}
		
		
		/**
		 * check values for alphanumeric characters only.
		 *
		 * @param string $str
		 * @return integer
		 * @access public
		 */
		function alnumValidation($str)
		{
			$str_pattern = '/[^[:alnum:]\s]/';
			
			return preg_match_all($str_pattern, $str, $arr_matches);		
			
		}
		
		
		/**
		 * Constructs a fragment of a "WHERE" clause that can be used to conduct a search.
		 * Note that this uses "LIKE" subclauses with wildcards, and will force a full table scan.
		 * 
		 *
		 * $searchMode must be ANY_WORD, ALL_WORDS, ALL_WORDS_IN_ORDER, or EXACT_PHRASE.
		 * $searchForWords and $inColumns can each be either an array or a comma-separated string.
		 * 
		 * @access public
		 */
		function makeSearch($searchMode, $searchForWords, $inColumns)
		{
			if (!is_array($searchForWords))
			{
				if ($searchMode == EXACT_PHRASE) $searchForWords = array($searchForWords);
				else $searchForWords = preg_split("/\s+/", $searchForWords, -1, PREG_SPLIT_NO_EMPTY);
			}
			elseif ($searchMode == EXACT_PHRASE && count($searchForWords) > 1)
				$searchForWords = array(implode(' ', $searchForWords));
	
			if (!is_array($inColumns))
				$inColumns = preg_split("/[\s,]+/", $inColumns, -1, PREG_SPLIT_NO_EMPTY);
	
			$where = '';
			foreach ($searchForWords as $searchForWord)
			{
				if (strlen($where)) $where .= ($searchMode == ALL_WORDS) ? ' AND ' : ' OR ';
	
				$sub = '';
				foreach ($inColumns as $inColumn)
				{
					if (strlen($sub)) $sub .= ' OR ';
					$sub .= "$inColumn LIKE '%" . $searchForWord . "%'"; //!! escaping?
				}
	
				$where .= "($sub)";
			}
	
			return $where;
		}
		
			
		/**
		 * This function gets the template path from xml file.
		 *
		 * @param string $template - pass template file name as defined in xml file for that template file.
		 * @return string - template file
		 * @access private
		 */
		function get_template($template){

			$login_arr = $this->action_parser($this->action,'template') ;

			$pos =  array_search($template, $login_arr['template']['name']); 

			return $login_arr['template']['path'][$pos];

		}
		
		
		/**
		 * This function sends the output to browser.
		 * 
		 * @access public
		 */
		function display(){

			view::$output =  $this->output;

		}

		

	}// Class Closed

	
	/**
	 * Initialize the object of this class
	 */	
	$obj = new associateTherapist();

?>

