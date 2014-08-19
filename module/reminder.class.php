<?php
	/**
	 *
	 * Copyright (c) 2008 Tx Xchange
	 *
	 * Class for add, edit, remove reminder.
	 * 
	 * // necessary classes 
	 * require_once("module/application.class.php");
	 * 
	 * // pagination class
	 * require_once("include/paging/my_pagina_class.php");
	 * 
	 * 
	 */
		
	
	// including files
	require_once("include/paging/my_pagina_class.php");
	require_once("module/application.class.php");
	
	// class declaration
  	class reminder extends application{
  		
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
		 * Edit reminder
		 *
		 * @access public
		 */
		function editReminder()
		{			
			$check = '1';
			
			$replace = array();
						
			$userInfo = $this->userInfo();
			
			$patientId = $this->value('patient_id');
			$reminder_set=$this->value('reminder_set');
			$replace['reminder_set']=$reminder_set;
			if(!empty($patientId))
			{

				if($patientId == session_id()){
					$patient_reminder = 'tmp_patient_reminder';
					$check = '0';
					$replace['closePopUpFunction'] = 'Simple';
				}else{
					$patient_reminder = 'patient_reminder';
					$replace['closePopUpFunction'] = '';
				}
				
				
				// make sure Therapists have privileges for this patient
				
				$userId = $userInfo['user_id'];
				$userType = ($userInfo['usertype_id'] == 2 ) ? "Therapist" : "";
				
				if($check == '1'){
				
					if($userInfo['usertype_id'] == 4){
					}
					else{
						$sql = "SELECT * FROM therapist_patient WHERE therapist_id = ".$userId." AND patient_id = '".$patientId."'";
						//echo $sql;
						
						$result = $this->execute_query($sql);
						
						$numRows = $this->num_rows($result);
						//echo $userInfo['admin_access'];
						//echo $numRows;
						//echo $userType;
						if($userType == "Therapist" && $userInfo['admin_access'] != '1' &&  $numRows == 0)
						{
							// close window
							$closeWindowStr = "		
												<script language='JavaScript' type='text/javascript'>
													alert('To set reminder to a patient you should be associated with that patient.');
													window.close();
												</script>";
							echo $closeWindowStr;
							exit();
						}
					}
					
                    $privateKey = $this->config['private_key'];
                    // get patient name
					$query = "SELECT 
                              AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
                              AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last  
                              FROM user WHERE user_id = '".$patientId."'";
					$result = $this->execute_query($query);
					
					$row = $this->fetch_array($result);			
					
					$patientName =strtoupper($row['name_first'])."&nbsp;&nbsp;".strtoupper($row['name_last']);
					$replace['patientId'] = $patientId;
					$replace['patientName'] = $patientName;
				}else{
					// In case of new patient
					$replace['patientId'] = session_id();
					$replace['patientName'] = 'New Patient';
				}

			} 
			else
			{
				// close window			
				$closeWindowStr = "		
									<script language='JavaScript' type='text/javascript'>
										window.close();
									</script>";
				echo $closeWindowStr;					
				exit();
			}
							
							
			/* Defining Sorting */				
		
			
			$orderByClause = "";
			if ($this->value('sort') == '') 
			{
				$orderByClause = "reminder";
				$replace['sortOrderImg1']='&nbsp;<img src="images/sort_asc.gif">';
				$replace['sortOrderImg2']='';
				$replace['sortOrderImg3']='';
				$replace['order1'] = "";
				$replace['order2'] = "";
				$replace['order3'] = "";
				
			}
			else {
				
				switch ($this->value('sort'))
				{
					case 'reminder'		:
													$orderByClause = "reminder";
													
													if ($this->value('order') == '2' ) 
													{
														$orderByClause.= " DESC ";															
														$replace['order1'] = "";
														$replace['order2'] = "";
														$replace['order3'] = "";
														$replace['sortOrderImg1']='&nbsp;<img src="images/sort_desc.gif">';
														$replace['sortOrderImg2']='';
														$replace['sortOrderImg3']='';
														
													}
													else 
													{
														
														$orderByClause.= " ASC ";															
														$replace['order1'] = "&order=2";
														$replace['order2'] = "";	
														$replace['order3'] = "";
														$replace['sortOrderImg1']='&nbsp;<img src="images/sort_asc.gif">';
														$replace['sortOrderImg2']='';	
														$replace['sortOrderImg3']='';													
													}
													
													break;
													
					case 'name' 	:
													$orderByClause = "";
													if ($this->value('order') == '2' ) 
													{
														$orderByClause.= " name_first DESC, name_last DESC ";															
														$replace['order2'] = "";
														$replace['order1'] = "";
														$replace['order3'] = "";
														
														$replace['sortOrderImg1']='';
														$replace['sortOrderImg2']='&nbsp;<img src="images/sort_desc.gif">';
														$replace['sortOrderImg3']='';
														
													}
													else 
													{
														$orderByClause.= " name_first ASC, name_last ASC ";															
														$replace['order2'] = "&order=2";;
														$replace['order1'] = "";		
														$replace['order3'] = "";
														$replace['sortOrderImg1']='';
														$replace['sortOrderImg2']='&nbsp;<img src="images/sort_asc.gif">';												
														$replace['sortOrderImg3']='';
													}
						
													break;	
													
												
					case 'modified' 	:
													$orderByClause = "creation_date";
													if ($this->value('order') == '2' ) 
													{
														$orderByClause.= " DESC ";															
														$replace['order2'] = "";
														$replace['order1'] = "";
														$replace['order3'] = "";
														
														$replace['sortOrderImg1']='';
														$replace['sortOrderImg2']='';
														$replace['sortOrderImg3']='&nbsp;<img src="images/sort_desc.gif">';
														
														
													}
													else 
													{
														$orderByClause.= " ASC ";															
														$replace['order3'] = "&order=2";;
														$replace['order1'] = "";		
														$replace['order2'] = "";
														$replace['sortOrderImg1']='';
														$replace['sortOrderImg2']='';
														$replace['sortOrderImg3']='&nbsp;<img src="images/sort_asc.gif">';												
														
													}
						
													break;	
																	
				}			
				
			}
			
			/* End */	

			$replace['searchStr'] = "";
			$replace['statusMessage'] = "";
            
            $privateKey = $this->config['private_key'];
			//Get the reminders list
            $sqlReminder = "SELECT $patient_reminder.*,AES_DECRYPT(UNHEX($patient_reminder.reminder),'{$this->config['private_key']}') as reminder, 
                            AES_DECRYPT(UNHEX(user.name_first),'{$privateKey}') as name_first,
                            AES_DECRYPT(UNHEX(user.name_last),'{$privateKey}') as name_last
                            FROM $patient_reminder, user WHERE $patient_reminder.user_id = user.user_id AND $patient_reminder.status = 1 AND patient_id = '".$patientId."' And reminder_set='".$reminder_set."' ORDER BY ".$orderByClause;
			//echo $sqlReminder;
			
			$result = $this->execute_query($sqlReminder);				
			if($this->num_rows($result)!= 0)
			{

                $replace['reminderTblHead'] = $this->build_template($this->get_template("reminderTblHead"),$replace);
				while($row = $this->fetch_array($result)){
					$row['style'] = ($c++%2)?"line1":"line2";
					$row['assignedBy'] = strtoupper($row['name_first'])."&nbsp;&nbsp;".strtoupper($row['name_last']);
					$row['dateCreated'] = (!empty($row['creation_date']))? $this->formatDate($row['creation_date']) : "&nbsp;"; 
					$replace['reminderTblRecord'] .=  $this->build_template($this->get_template("reminderTblRecord"),$row);
				}
			}
			else 
			{
				$replace['reminderTblRecord'] =  "No reminders to list";
			}					
			
            $replace['reminderText'] = $this->reminderText($patient_reminder,$patientId,$reminder_set);
			$this->output = $this->build_template($this->get_template("reminderTemplate"),$replace);			
				
		}
        /**
        * Return list of reminder in desc order of create date time.
        */
		function reminderText($patient_reminder, $patientId, $reminder_set=1,$orderByClause = 'creation_date'){
            $privateKey = $this->config['private_key'];                                 
            $sqlReminder = "SELECT $patient_reminder.*,AES_DECRYPT(UNHEX($patient_reminder.reminder),'{$this->config['private_key']}') as reminder, 
                            AES_DECRYPT(UNHEX(user.name_first),'{$privateKey}') as name_first,
                            AES_DECRYPT(UNHEX(user.name_last),'{$privateKey}') as name_last 
                            FROM $patient_reminder, user WHERE $patient_reminder.user_id = user.user_id AND $patient_reminder.status = 1 AND patient_id = '".$patientId."' And $patient_reminder.reminder_set='".$reminder_set."' ORDER BY  patient_reminder_id desc";
            $result = $this->execute_query($sqlReminder);                
            $replace['reminderText'] = '';
            if($this->num_rows($result)!= 0)
            {
                $replace['reminderText'] .= '<ol>';
                $replace['reminderTblHead'] = $this->build_template($this->get_template("reminderTblHead"),$replace);
                while($row = $this->fetch_array($result)){
                    $replace['reminderText'] .= '<li>' . $row['reminder'] . '</li>';
                }
                $replace['reminderText'] .= '</ol>';
            }
            else{
                $replace['reminderText'] = '<ul style="list-style:none;"><li style="padding-top:10px;padding-left:10px;">There are no current reminders.</li></ul>';
            }
            return $replace['reminderText'];
        }
		
		/**
		 * Add a new reminder
		 *
		 * @access public
		 */
		function addReminder()
		{			
			$check = '1';
			
			$replace = array();
						
			$userInfo = $this->userInfo();
			
			$patientId = $this->value('patient_id');
			$reminder_set=$this->value('reminder_set');
			$replace['reminder_set']=$reminder_set;
			if(!empty($patientId))
			{
				
				
				if($patientId == session_id()){
					$patient_reminder = 'tmp_patient_reminder';
					$check = '0';
					$replace['closePopUpFunction'] = 'Simple';
				}else{
					$patient_reminder = 'patient_reminder';
					$replace['closePopUpFunction'] = '';
				}				

				// make sure Therapists have privileges for this patient
				
				$userId = $userInfo['user_id'];
				$userType = ($userInfo['usertype_id'] == 2) ? "Therapist" : "";
				
				if($check == '1'){
					if($userInfo['usertype_id'] == 4){
					}
					else{
						$sql = "SELECT * FROM therapist_patient WHERE therapist_id = ".$userId." AND patient_id = ".$patientId;
						
						$result = $this->execute_query($sql);
						
						$numRows = $this->num_rows($result);
						
						if($userType != "Therapist" && $numRows == 0)
						{
							// close window
							$closeWindowStr = "		
												<script language='JavaScript' type='text/javascript'>
													window.close();
												</script>";
							echo $closeWindowStr;
							exit();
						}
					}
                    
                    $privateKey = $this->config['private_key'];
					// get patient name
					$query = "SELECT 
                              AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
                              AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last 
                              FROM user WHERE user_id = ".$patientId;
					$result = $this->execute_query($query);
					
					$row = $this->fetch_array($result);			
					
					$patientName =strtoupper($row['name_first'])."&nbsp;&nbsp;".strtoupper($row['name_last']);
					$replace['patientId'] = $patientId;
					$replace['patientName'] = $patientName;
					
				}else{
					$replace['patientId'] = session_id();
					$replace['patientName'] = 'New Patient';
				}
			}
			else
			{
				// close window			
				$closeWindowStr = "		
									<script language='JavaScript' type='text/javascript'>
										window.close();
									</script>";
				echo $closeWindowStr;					
				exit();
			}
			
			$msg = "";		
			
			if("Save Reminder" == $this->value('submitted'))
			{
				$newReminder = $this->value('reminder');
				
				if (strlen(trim($newReminder)) == 0) 
				{
					$msg = '<div class="msg_warning">Reminder: Enter a reminder message for this patient.</div>';
				}
				else 
				{
					$validationResult = $this->alnumValidation($newReminder);
				
				
					if ($validationResult != 0)
					{
						$msg = '<div class="msg_warning">Reminder: Enter a valid alphanumeric reminder message for this patient.</div>';
					}
					else 
					{
						$msg = "";
					}
					
				}
				
				if ($msg == "") 
				{
					// error free so enter the reminder in table
					// Update user's modified field.
					$data = array(
						'modified' => date('Y-m-d H:i:s')
					);
					$where = " user_id = '{$this->value('patient_id')}' ";
					$this->update($this->config['table']['user'],$data,$where);
					
					$insertArr = array(
										'patient_id' => $patientId,
										'user_id' =>$userId,
										'reminder' => $this->encrypt_data($this->value('reminder')),
										'creation_date' => date('Y-m-d H:i:s',time()),
										'status' => 1,
										'reminder_set'=>$this->value('reminder_set')					
										);
								
					//$query = "INSERT INTO patient_reminder () VALUES (".$patientId.",".$userId.", '".$this->value('reminder')."',NOW(),1)";						
					
					//$result = $this->execute_query($query);								
					
					$result = $this->insert($patient_reminder,$insertArr);
								
					if ($result)
					{									
						$msg = '<div class="msg_warning">Saved new reminder.</div>';	
					}
					else 
					{
						$msg = '<div class="msg_warning">Failed saving massage.</div>';									
					}			
					
				}	
				
			}
					
			$replace['statusMessage'] = $msg;
							
			/* Defining Sorting */				
		
			
			$orderByClause = "";
			if ($this->value('sort') == '') 
			{
				$orderByClause = "reminder";
				$replace['sortOrderImg1']='&nbsp;<img src="images/sort_asc.gif">';
				$replace['sortOrderImg2']='';
				$replace['sortOrderImg3']='';
				$replace['order1'] = "";
				$replace['order2'] = "";
				$replace['order3'] = "";
				
			}
			else {
				
				switch ($this->value('sort'))
				{
					case 'reminder'		:
													$orderByClause = "reminder";
													
													if ($this->value('order') == '2' ) 
													{
														$orderByClause.= " DESC ";															
														$replace['order1'] = "";
														$replace['order2'] = "";
														$replace['order3'] = "";
														$replace['sortOrderImg1']='&nbsp;<img src="images/sort_desc.gif">';
														$replace['sortOrderImg2']='';
														$replace['sortOrderImg3']='';
														
													}
													else 
													{
														
														$orderByClause.= " ASC ";															
														$replace['order1'] = "&order=2";
														$replace['order2'] = "";	
														$replace['order3'] = "";
														$replace['sortOrderImg1']='&nbsp;<img src="images/sort_asc.gif">';
														$replace['sortOrderImg2']='';	
														$replace['sortOrderImg3']='';													
													}
													
													break;
													
					case 'name' 	:
													$orderByClause = "";
													if ($this->value('order') == '2' ) 
													{
														$orderByClause.= " name_first DESC, name_last DESC ";															
														$replace['order2'] = "";
														$replace['order1'] = "";
														$replace['order3'] = "";
														
														$replace['sortOrderImg1']='';
														$replace['sortOrderImg2']='&nbsp;<img src="images/sort_desc.gif">';
														$replace['sortOrderImg3']='';
														
													}
													else 
													{
														$orderByClause.= " name_first ASC, name_last ASC ";															
														$replace['order2'] = "&order=2";;
														$replace['order1'] = "";		
														$replace['order3'] = "";
														$replace['sortOrderImg1']='';
														$replace['sortOrderImg2']='&nbsp;<img src="images/sort_asc.gif">';												
														$replace['sortOrderImg3']='';
													}
						
													break;	
													
												
					case 'modified' 	:
													$orderByClause = "creation_date";
													if ($this->value('order') == '2' ) 
													{
														$orderByClause.= " DESC ";															
														$replace['order2'] = "";
														$replace['order1'] = "";
														$replace['order3'] = "";
														
														$replace['sortOrderImg1']='';
														$replace['sortOrderImg2']='';
														$replace['sortOrderImg3']='&nbsp;<img src="images/sort_desc.gif">';
														
														
													}
													else 
													{
														$orderByClause.= " ASC ";															
														$replace['order3'] = "&order=2";;
														$replace['order1'] = "";		
														$replace['order2'] = "";
														$replace['sortOrderImg1']='';
														$replace['sortOrderImg2']='';
														$replace['sortOrderImg3']='&nbsp;<img src="images/sort_asc.gif">';												
														
													}
						
													break;	
																	
				}			
				
			}
			
			/* End */	

			$replace['searchStr'] = "";
			//Get the reminders list
            $privateKey = $this->config['private_key'];
			$sqlReminder = "SELECT $patient_reminder.*,AES_DECRYPT(UNHEX($patient_reminder.reminder),'{$this->config['private_key']}') as reminder, 
                            AES_DECRYPT(UNHEX(user.name_first),'{$privateKey}') as name_first,
                            AES_DECRYPT(UNHEX(user.name_last),'{$privateKey}') as name_last 
                            FROM $patient_reminder, user WHERE $patient_reminder.user_id = user.user_id AND $patient_reminder.status = 1 AND patient_id = '".$patientId."' And reminder_set= '".$reminder_set."'ORDER BY ".$orderByClause;
			
			//echo $sqlReminder;
			
			$result = $this->execute_query($sqlReminder);					
			if($this->num_rows($result)!= 0)
			{
				$replace['reminderTblHead'] = $this->build_template($this->get_template("reminderTblHead"),$replace);
				while($row = $this->fetch_array($result)){
					$row['style'] = ($c++%2)?"line1":"line2";
					$row['assignedBy'] = strtoupper($row['name_first'])."&nbsp;&nbsp;".strtoupper($row['name_last']);
					$row['dateCreated'] = (!empty($row['creation_date']))? $this->formatDate($row['creation_date']) : "&nbsp;"; 
					$replace['reminderTblRecord'] .=  $this->build_template($this->get_template("reminderTblRecord"),$row);
				}
			}
			else 
			{
				$replace['reminderTblRecord'] =  "No reminders to list";
			}					
			$replace['reminderText'] = $this->reminderText($patient_reminder,$patientId,$reminder_set);
			$this->output = $this->build_template($this->get_template("reminderTemplate"),$replace);			
				
		}
		
		
		/**
		 * Delete reminder
		 *
		 * @access public
		 */
		function removeReminder()
		{			
			$check = '1';
			
			$replace = array();
						
			$userInfo = $this->userInfo();
			
			$id = $this->value('id');
			
			$patientId = $this->value('patient_id');
			
			$reminder_set=$this->value('reminder_set');
			$replace['reminder_set']=$reminder_set;
			if(!empty($patientId))
			{
						
				
				if($patientId == session_id()){
					$patient_reminder = 'tmp_patient_reminder';
					$check = '0';
					$replace['closePopUpFunction'] = 'Simple';
				}else{
					$patient_reminder = 'patient_reminder';
					$replace['closePopUpFunction'] = '';
				}

				// make sure Therapists have privileges for this patient
				
				$userId = $userInfo['user_id'];
				$userType = ($userInfo['usertype_id'] == 2) ? "Therapist" : "";
				
				if($check == '1'){
					
					if($userInfo['usertype_id'] == 4){
					}
					else{
					
						$sql = "SELECT * FROM therapist_patient WHERE therapist_id = '".$userId."' AND patient_id = '".$patientId."'";
						
						$result = $this->execute_query($sql);
						
						$numRows = $this->num_rows($result);
						
						if($userType != "Therapist" && $numRows == 0)
						{
							// close window
							$closeWindowStr = "		
												<script language='JavaScript' type='text/javascript'>
													window.close();
												</script>";
							echo $closeWindowStr;
							exit();
						}
					}
					
                    $privateKey = $this->config['private_key'];
                    // get patient name
					$query = "SELECT 
                              AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
                              AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last 
                              FROM user WHERE user_id = ".$patientId;
					$result = $this->execute_query($query);
					
					$row = $this->fetch_array($result);			
					
					$patientName =strtoupper($row['name_first'])."&nbsp;&nbsp;".strtoupper($row['name_last']);
					$replace['patientId'] = $patientId;
					$replace['patientName'] = $patientName;
				
				}else{
					$replace['patientId'] = session_id();
					$replace['patientName'] = 'New Patient';					
				}
			}
			else
			{
				// close window			
				$closeWindowStr = "		
									<script language='JavaScript' type='text/javascript'>
										window.close();
									</script>";
				echo $closeWindowStr;					
				exit();
			}
							
					
			$msg = "";		
			
			/* Remove Block Starts */
			if($userType != "Therapist")
			{
				/*
				
				This part of code is not required.
				User can delete reminder of  Patient, created by other users also.
				
				$sql = "SELECT user_id FROM $patient_reminder WHERE patient_reminder_id = ".$id;				
				$result = $this->execute_query($sql);
				
				$row = $this->fetch_array($result);
				
				if($userId != $row['user_id'])
				{
					$msg = 'You may only delete reminders you have assigned.';					
				}
				
				*/
			}
			
			if ($msg == "") 
			{
				// Update user's modified field.
				$data = array(
					'modified' => date("Y-m-d")
				);
				$where = " user_id = '{$this->value('patient_id')}' ";
				$this->update($this->config['table']['user'],$data,$where);
					
				//$sql = "UPDATE $patient_reminder SET status = 2 WHERE patient_reminder_id = ".$id;
				$sql = "DELETE FROM $patient_reminder WHERE patient_reminder_id = '".$id."'";
				$result = $this->execute_query($sql);
				
				if ($result)
				{									
					$msg = '<div class="msg_warning">Deleted reminder.</div>';	
				}
				else 
				{
					$msg = '<div class="msg_warning">Failed deleting reminder.</div>';									
				}	
			}			
			
			$replace['statusMessage'] = $msg;
			/* Remove Block Ends */
			
					
			/* Defining Sorting */				
		
			
			$orderByClause = "";
			if ($this->value('sort') == '') 
			{
				$orderByClause = "reminder";
				$replace['sortOrderImg1']='&nbsp;<img src="images/sort_asc.gif">';
				$replace['sortOrderImg2']='';
				$replace['sortOrderImg3']='';
				$replace['order1'] = "";
				$replace['order2'] = "";
				$replace['order3'] = "";
				
			}
			else {
				
				switch ($this->value('sort'))
				{
					case 'reminder'		:
													$orderByClause = "reminder";
													
													if ($this->value('order') == '2' ) 
													{
														$orderByClause.= " DESC ";															
														$replace['order1'] = "";
														$replace['order2'] = "";
														$replace['order3'] = "";
														$replace['sortOrderImg1']='&nbsp;<img src="images/sort_desc.gif">';
														$replace['sortOrderImg2']='';
														$replace['sortOrderImg3']='';
														
													}
													else 
													{
														
														$orderByClause.= " ASC ";															
														$replace['order1'] = "&order=2";
														$replace['order2'] = "";	
														$replace['order3'] = "";
														$replace['sortOrderImg1']='&nbsp;<img src="images/sort_asc.gif">';
														$replace['sortOrderImg2']='';	
														$replace['sortOrderImg3']='';													
													}
													
													break;
													
					case 'name' 	:
													$orderByClause = "";
													if ($this->value('order') == '2' ) 
													{
														$orderByClause.= " name_first DESC, name_last DESC ";															
														$replace['order2'] = "";
														$replace['order1'] = "";
														$replace['order3'] = "";
														
														$replace['sortOrderImg1']='';
														$replace['sortOrderImg2']='&nbsp;<img src="images/sort_desc.gif">';
														$replace['sortOrderImg3']='';
														
													}
													else 
													{
														$orderByClause.= " name_first ASC, name_last ASC ";															
														$replace['order2'] = "&order=2";;
														$replace['order1'] = "";		
														$replace['order3'] = "";
														$replace['sortOrderImg1']='';
														$replace['sortOrderImg2']='&nbsp;<img src="images/sort_asc.gif">';												
														$replace['sortOrderImg3']='';
													}
						
													break;	
													
												
					case 'modified' 	:
													$orderByClause = "creation_date";
													if ($this->value('order') == '2' ) 
													{
														$orderByClause.= " DESC ";															
														$replace['order2'] = "";
														$replace['order1'] = "";
														$replace['order3'] = "";
														
														$replace['sortOrderImg1']='';
														$replace['sortOrderImg2']='';
														$replace['sortOrderImg3']='&nbsp;<img src="images/sort_desc.gif">';
														
														
													}
													else 
													{
														$orderByClause.= " ASC ";															
														$replace['order3'] = "&order=2";;
														$replace['order1'] = "";		
														$replace['order2'] = "";
														$replace['sortOrderImg1']='';
														$replace['sortOrderImg2']='';
														$replace['sortOrderImg3']='&nbsp;<img src="images/sort_asc.gif">';												
														
													}
						
													break;	
																	
				}			
				
			}
			
			/* End */	

			$replace['searchStr'] = "";
            
			//Get the reminders list
            $privateKey = $this->config['private_key'];
			$sqlReminder = "SELECT $patient_reminder.*,AES_DECRYPT(UNHEX($patient_reminder.reminder),'{$this->config['private_key']}') as reminder, 
                            AES_DECRYPT(UNHEX(user.name_first),'{$privateKey}') as name_first,
                            AES_DECRYPT(UNHEX(user.name_last),'{$privateKey}') as name_last
                            FROM $patient_reminder, user WHERE $patient_reminder.user_id = user.user_id AND $patient_reminder.status = 1 AND patient_id = '".$patientId."' and $patient_reminder.reminder_set='".$reminder_set."' ORDER BY ".$orderByClause;
			
			//echo $sqlReminder;
			
			$result = $this->execute_query($sqlReminder);		
			if($this->num_rows($result)!= 0)
			{
				$replace['reminderTblHead'] = $this->build_template($this->get_template("reminderTblHead"),$replace);
				$replace['reminderText'] .= '<ol>';
				while($row = $this->fetch_array($result)){
					$row['style'] = ($c++%2)?"line1":"line2";
					$row['assignedBy'] = strtoupper($row['name_first'])."&nbsp;&nbsp;".strtoupper($row['name_last']);
					$row['dateCreated'] = (!empty($row['creation_date']))? $this->formatDate($row['creation_date']) : "&nbsp;"; 				
					$replace['reminderTblRecord'] .=  $this->build_template($this->get_template("reminderTblRecord"),$row);
				}
			}
			else 
			{
				$replace['reminderTblRecord'] =  "No reminders to list";
			}					
			
            
             $replace['reminderText'] = $this->reminderText($patient_reminder,$patientId,$reminder_set);
            $this->output = $this->build_template($this->get_template("reminderTemplate"),$replace);			
				
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
			$str_pattern = '/[^[:alnum:][^"][^\']\:\#\r\n\s\,\.\-\(\)\/\;\+\%]/';
			
			return preg_match_all($str_pattern, $str, $arr_matches);		
			
		}
		
		
		/**
		 * This function gets the template path from xml file.
		 *
		 * @param string $template - pass template file name as defined in xml file for that template file.
		 * @return string - template file
		 * @access private
		 */
		function get_template($template){

			$login_arr = $this->action_parser($this->action,'template');

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
	$obj = new reminder();

?>

