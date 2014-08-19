<?php
	
 
	/**
	 * Copyright (c) 2008 Tx Xchange.
	 * 
	 * It includes the functionality for patient listing, Edit existing and Add new patient	 
	 * This class is for Account Admin patient module
	 * 
	 * // necessary classes 
	 * require_once("module/application.class.php");
	 * 
	 * // pagination class
	 * require_once("include/paging/my_pagina_class.php");
	 * 
	 * // validation classes
	 * require_once("include/validation/_includes/classes/validation/ValidationSet.php");
	 * require_once("include/validation/_includes/classes/validation/ValidationError.php");
	 * 
	 */
	
	require_once("include/paging/my_pagina_class.php");
	require_once("module/application.class.php");
	require_once("include/validation/_includes/classes/validation/ValidationSet.php");
	require_once("include/validation/_includes/classes/validation/ValidationError.php");
  	class accountAdmin_patient extends application{
  		
  		/**
  		 * The variable defines the action request
  		 *
  		 * @var String
  		 * @access Private
  		 */
  		
		private $action;
		
		/**
  		 * The variable defines all the fields present in the form
  		 *
  		 * @var String Array
  		 * @access Private
  		 */

		private $form_array;
		
		/**
  		 * The variable defines the error message(if any) against the action request
  		 * It could be an array if more than one error messages are there else a simple variable
  		 * 
  		 * @var String
  		 * @access Private
  		 */
		
		private $error;
		
		/**
  		 * The variable is used for getting final output template or string message to be displayed to the user
		 * This function of statement(s) are to handle all the actions supported by this Login class
		 * that is it could be the case that more then one action are handled by login
		 * for example at first the action is "login" then after submit say action is submit
		 * so if login is explicitly called we have the login action set (which is also our default action)
		 * else whatever action is it is set in $str.				
  		 * 
  		 * @var String
  		 * @access Private
  		 */
		
		private $output;
		
		
		/**
		 * Constructor
		 * 
		 * Check for user login and cll appropriate function of the class.
		 * Get action from query string
		 * Also check that user have rights to access this class or not.
		 * 
		 * Call display function to generate final output.
		 */		
		function __construct(){
			parent::__construct();
			$this->form_array = $this->populate_form_array();
			if($this->value('action')){

				$str = $this->value('action');
			}else{
				$str = "accountAdmin_patient"; //default if no action is specified
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
			if($this->userAccess($str)){
                 // check logged in user is not a primary account admin.
                $clinic_id = $this->clinicInfo('clinic_id');
                $parent_clinic_id = $this->get_field($clinic_id,'clinic','parent_clinic_id');
                $arr_action = array('accountadmin_patient','accountAdminEditPatients','change_status');
                if( is_numeric($parent_clinic_id) && $parent_clinic_id == 0){
                    if(in_array($str,$arr_action)){
                       header('location:index.php?action=accountAdminClinicList');
                       exit();
                    }
                }
                // End
                // Code To Call Personalized GUI
                $this->call_gui();	
				$str = $str."()";
				eval("\$this->$str;"); 
			}
			else{
				$this->output = $this->config['error_message'];
			} 
			
			$this->display();
		}
		
		/**
		 * This function is used to display the patient listing for the particular account admin.
		 *
		 * @access public
		 */
		function accountadmin_patient(){
			
			$this->set_session_page();
			 if($this->userInfo('usertype_id')==2  ){
			$replace['get_satisfaction'] = $this->get_satisfaction();
			}
		$userInfo = $this->userInfo();
            $cli_type=$this->getClinicDetails($this->userInfo('user_id'));
            //if($cli_type['clinic_type']==4){
                $replace['shopshow']=1;
                /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
            /*}else{
                $replace['shopshow']='0';
            }*/
			// set templating variables
			$replace['browser_title'] = 'Tx Xchange: Patient List';
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();
			$replace['footer'] = $this->build_template($this->get_template("footer"));	
			
			if($this->value('sort') != ""){
				if($this->value('order') == 'desc' ){
					$orderby = " order by {$this->value('sort')} desc ";
				}
				else{
					$orderby = " order by {$this->value('sort')} ";
				}
			}
			else{
				$orderby = " order by name_first ";
			}
			
			
			
			/**
			 *	Query for generating the patient list
			 *	CONDITIONS :
			 *	1.  Patient must associated with the clinic whose Therapist(Accound Admin) is logged in
			 *	2.  Patient must have one associated therapist
			 *	3.  Patient status != 3, either Active or InActive
			 */
			$search = "";
			$privateKey = $this->config['private_key'];
			if( $this->value('search')  != "" ){
				$search = $this->value('search');
				$searchSql = " and ( CAST(AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') AS CHAR) like '%{$this->value('search')}%' or CAST(AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') AS CHAR) like '%{$this->value('search')}%') ";
			}
            $privateKey = $this->config['private_key'];
             
			$query = "select AES_DECRYPT(UNHEX(u.name_title),'{$privateKey}') as name_title, u.user_id, cast(AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as char) as name_first, AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as name_last, u.username, u.status, 
						(select group_concat(concat(AES_DECRYPT(UNHEX(u1.name_first),'{$privateKey}'),' ',AES_DECRYPT(UNHEX(u1.name_last),'{$privateKey}')) separator ',<br/>' ) from user u1
						inner join therapist_patient tp on tp.therapist_id = u1.user_id
						where tp.patient_id = u.user_id  group by tp.patient_id 
						) as therapist,ps.subscription_title AS subscription_title
						from user as u left join patient_subscription as ps on u.user_id=ps.user_id and ((ps.subs_status='1' and subs_end_datetime > now()) or (ps.subs_status='2' and subs_end_datetime > now()))
						inner join clinic_user as cu on u.user_id=cu.user_id  where u.usertype_id='1' and u.status!='3' 
						{$searchSql}
						and cu.clinic_id='".$this->clinicInfo("clinic_id")."' {$orderby}
					 ";
      
			$sqlcount = "select count(1)
						from user as u 
						inner join clinic_user as cu on u.user_id=cu.user_id  where u.usertype_id='1' and u.status!='3' 
						{$searchSql}
						and cu.clinic_id='".$this->clinicInfo("clinic_id")."'";
			
			
			// pagination
			$link = $this->pagination($rows = 0,$query,'accountadmin_patient',$search,'','','',$sqlcount);                                          
	
	        $replace['link'] = $link['nav'];
	
	        $result = $link['result']; 	
	
	        // check sql query result
			if(is_resource($result)){
				if( @mysql_num_rows($result) > 0 ){
					while($row = $this->fetch_array($result)){
						$therapistName = $row['therapist'];
						if(empty($therapistName)){
							$therapistName = "Not Assigned";
						}
						$replace['classname'] = (++$k%2) ? 'line2' : 'line1';
						$replace['userId'] = $row['user_id'];
						$replace['patientName'] = $row['name_title'].' '.$row['name_first'].' '.$row['name_last'];
						$replace['patientEmailId'] = $row['username'];
						$replace['associatedTherapistName'] = $therapistName;
                        if($row['subscription_title']=='')
                        $replace['EHealthService']= 'Not Subscribed';
                        else
                        $replace['EHealthService']=$row['subscription_title'];
                        
                        
                         $user_id = $this->userInfo('user_id');
                   /*// echo"<br/>";
                     $clinic_id = $this->get_clinic_info($row['user_id'],'clinic_id');
                    $EHealthService=$this->getPatientSubscriptionDetails($row['user_id'],$this->get_clinic_info($user_id)); 
                    if($EHealthService['subs_title']!=''){
                         $replace['EHealthService']= $EHealthService['subs_title'];    
                    }else{
                         $replace['EHealthService']= 'Not Subscribed';
                    }*/
                    
                    
                    
                    
						$replace['patientStatus'] = $row['status'] != "" ? $this->config['patientStatus'][$row['status']] : "";
						$rowdata .= $this->build_template($this->get_template("temp_patientListRecord"),$replace);
					}
				}
				else{
					$rowdata = $this->build_template($this->get_template("recordNotFound"),$replace);
				}
			}
			else{
				$rowdata = $this->build_template($this->get_template("recordNotFound"),$replace);
			}
			$patientAcctListHead = array(
				'name_first' => 'Patient Name',
				'u.username' => 'Email Address',
				'therapist' => 'Therapist',
                'subscription_title'=>'E-Health Service',
			);
			$replace['patientAcctListHead'] = $this->build_template($this->get_template("patientAcctListHead"),$this->table_heading($patientAcctListHead,"name_first"));
			$replace['rowdata'] = $rowdata;
            $replace['navigationTab'] = $this->build_template($this->get_template("navigationTab"));
			$replace['body'] = $this->build_template($this->get_template("patient_list"),$replace);
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}

		
		/**
		 * Function to Edit the patient Details
		 *
		 * @access public
		 */
		function accountAdminEditPatients()
		{
		$userInfo = $this->userInfo();
            $cli_type=$this->getClinicDetails($this->userInfo('user_id'));
            //if($cli_type['clinic_type']==4){
                $replace['shopshow']=1;
                
            /*}else{
                $replace['shopshow']='0';
            }*/ /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
			$patient_id = $this->value('patient_id')!=session_id()?$this->value('patient_id'):"";
			$option = $this->value('option');
			$replace['patient_id'] = $patient_id;
			$replace['footer'] = $this->build_template($this->get_template("footer"));	
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();
            // In case of Add
			if(empty($patient_id))
			{
				// Populate BLANK FORM fields
				$this->assignValueToArrayFields($this->form_array, '', '', &$replace);
				$replace['operationTitle'] = 'Add';
				$replace['browser_title'] = 'Tx Xchange: Add Patient';
				$replace['buttonName'] = 'Add Patient';
				
				$status_arr['patientStatus'] =  array( '1' => "Current");
				$replace['patient_status_options'] = $this->build_select_option($status_arr['patientStatus'], $replace['patient_status']);
				
				/*if(($replace['newPassword']) == ''){
					$replace['newPassword'] = $this->generateCode(9);
				}*/
				//$replace['newPasswordLabel'] = '<div align="right" ><strong>New Password &nbsp;</strong> </div>';
				//$replace['newPasswordField'] = '<input tabindex="7" type="text" name="newPassword"  value="'.$replace['newPassword'].'" />';
				$replace['newPasswordLabel'] = '';
                $replace['newPasswordField'] = '';
                
				// Because of passing session_id as patient_id
				
				$patient_id = session_id();
                $replace['editReminderlink'] = $patient_id;
				$patient_id = "";
				
				if(!empty($option)){
					// validating form
					$this->validateForm('add');
					if(($this->error) != ""){
						// if error found in form
						$this->assignValueToArrayFields($this->form_array, '', '', &$replace, '0');
						$replace['error'] = $this->error;
					}else{
							$updateArr = $this->assignValueToArrayFields($this->form_array, '', '2', '', 'insert');
							$updateArr['status'] = '1';
							$updateArr['usertype_id'] = '1';
                            $updateArr['created_by'] = $this->userInfo('user_id');
							$updateArr['creation_date'] = date('Y-m-d H:i:s',time());
							$updateArr['modified'] = date('Y-m-d H:i:s',time());
							$updateArr['mass_message_access'] = 1;
							$updateArr['password'] =$this->value('patient_last_name').'01';
							
							$result = $this->insert('user',$updateArr);
							$insertedId = $this->insert_id();
							
							/**
							 *  Replace Records from temprory table to original Table
							 *  1.  From tmp_therapist_patient  to  therapist_patient
							 *  2.  From tmp_patient_reminder  to  patient_reminder
							 */
							$clinicUserArr = array('clinic_id' => $this->clinicInfo('clinic_id'), 'user_id' => $insertedId);
							$result = $this->insert('clinic_user',$clinicUserArr);
							
							$query = "SELECT * FROM tmp_patient_reminder WHERE patient_id = '".session_id()."'";
							$rs = $this->execute_query($query);
							$rows = $this->populate_array($rs, '0');
							
							// count no of rows
							if((count($rows)) > 0 )
							foreach($rows as $row){
								$PatientReminderArr = array('patient_id' => $insertedId,
									'user_id' => $row['user_id'],
									'reminder' => $row['reminder'],
									'creation_date' => $row['creation_date'],
									'modified' => $row['modified'],
									'status' => '1'
									);
								$result = $this->insert('patient_reminder',$PatientReminderArr);

								// deleting temprory tables
								$query = "DELETE FROM tmp_patient_reminder WHERE patient_reminder_id = '".$row['patient_reminder_id']."'";
								$this->execute_query($query);
							}
							
							$query = "SELECT * FROM tmp_therapist_patient WHERE patient_id = '".session_id()."'";
							$rs = $this->execute_query($query);
							$rows = $this->populate_array($rs, '0');
							
							if((count($rows)) > 0 )
							foreach($rows as $row){
								$therapistPatientArr = array('therapist_id' => $row['therapist_id'],
									'patient_id' => $insertedId,
									'creation_date' => $row['creation_date'],
									'modified' => $row['modified'],
									'status' => '1'
									);
								$result = $this->insert('therapist_patient',$therapistPatientArr);

								$query = "DELETE FROM tmp_therapist_patient WHERE therapist_patient_id = '".$row['therapist_patient_id']."'";
								$this->execute_query($query);
							}							
							//have the HTML content
                            $clinicName=html_entity_decode($this->get_clinic_info($insertedId,'clinic_name'), ENT_QUOTES, "UTF-8");
                            $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
                            if($clinic_channel==1){
                                $business_url=$this->config['business_tx'];	
                                $support_email=$this->config['email_tx'];
                            }else{
                                $business_url=$this->config['business_wx']; 
                                $support_email=$this->config['email_wx'];	
                            }
                            
							$data = array(
											'username' => $this->userInfo("username",$insertedId),
											'password' => $this->userInfo("password",$insertedId),
											'url' => $this->config['url'],
											'images_url' => $this->config['images_url'],
                                            'clinic_name'=>$clinicName,
							                'business_url'=>$business_url,
							                'support_email'=>$support_email,
											'name'=>$this->userInfo("name_first",$insertedId)
							    	);
																	
							/*  Mail Section  */
								//$user_id = $this->userInfo('user_id');
								
									
	                        	$clinic_type = $this->getUserClinicType($insertedId);
	                        	if( $clinic_channel == 1){
	                        		$message = $this->build_template("mail_content/plpto/create_new_patient_mail_plpto.php",$data);
	                        	}else{
	                        		$message = $this->build_template("mail_content/wx/create_new_patient_mail_plpto.php",$data);
	                        	}
	                        	/*elseif( $clinic_type == 'elpto' ){
	                        		$message = $this->build_template("mail_content/create_new_patient_mail.php",$data);	
	                        	}*/
								$to = $updateArr['username'];
                               
								$subject = "Your ".$clinicName." Health Record";								
								
								// To send HTML mail, the Content-type header must be set
								$headers  = 'MIME-Version: 1.0' . "\n";
								$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
								
								// Additional headers
								//$headers .= 'To: '.$fullName.'<'.$_POST['email_address'].'>'. "\n";
								//$headers .= "From: " . $this->config['from_email_address'] . "\n";
								if( $clinic_channel == 1){
								 $headers .= "From: ".$this->setmailheader($fullName). " <".$this->config['email_tx'].">" . "\n";
								 $returnpath = "-f".$this->config['email_tx'];
								}else{
								 $headers .= "From: ".$this->setmailheader($fullName). " <".$this->config['email_wx'].">" . "\n";
								 $returnpath = '-f'.$this->config['email_wx']; 	
								}
								//$headers .= 'Cc: example@example.com' . "\n";
								//$headers .= 'Bcc: example@example.com' . "\n";
								
								// Mail it
								mail($to, $subject, $message, $headers, $returnpath);
																						
							header("location:index.php?action=accountadmin_patient");
					}
				}else{
					//  Deleting Temprory table rows for "Edit Reminder" and "Edit associate link", if user comes after clicking button "Add New Patient"
					$query = "DELETE FROM tmp_patient_reminder WHERE patient_id = '".session_id()."'";
					$this->execute_query($query);
					
					$query = "DELETE FROM tmp_therapist_patient WHERE patient_id = '".session_id()."'";
					$this->execute_query($query);							
				}
                $stateArray = array("" => "Choose State...");
                $stateArray = array_merge($stateArray,$this->config['state']);
                $replace['patient_state_options'] = $this->build_select_option($stateArray, $replace['patient_state']);
                $replace['patient_title_options'] = $this->build_select_option(array_merge(array('' => 'choose...'), $this->config['title']), $replace['patient_title']);
                $replace['patient_suffix_options'] = $this->build_select_option(array_merge(array('' => 'choose...'), $this->config['suffix']), $replace['patient_suffix']);
                
                $replace['navigationTab'] = $this->build_template($this->get_template("navigationTab"));            
                $replace['body'] = $this->build_template($this->get_template("accountAdminAddPatient"),$replace);
                
			// In case of Edit				
			}else{
				$replace['operationTitle'] = 'Edit';
				$replace['buttonName'] = 'Save Patient';
				$replace['browser_title'] = 'Tx Xchange: Edit Patient';
				$replace['newPasswordLabel'] = '<div align="right" ><strong>Password &nbsp;</strong> </div>';
                
				$replace['newPassword'] = $this->value('newPassword');
                $replace['cnewPassword'] = $this->value('cnewPassword');
                
                if( !isset($replace['newPassword']) || empty($replace['newPassword']) ){
                    $replace['newPassword'] = $this->userInfo('password',$patient_id);
                }
                if( !isset($replace['cnewPassword']) || empty($replace['cnewPassword']) ){
                    $replace['cnewPassword'] = $this->userInfo('password',$patient_id);
                }
                $replace['newPasswordField'] = '<input tabindex="7" type="password" name="newPassword" value="'.$replace['newPassword'].'" />';
                $replace['confirmPasswordField'] = '<input tabindex="8" type="password" name="cnewPassword" value="'.$replace['cnewPassword'].'" />';
                $replace['confirmPasswordTextField'] = '<div align="right" ><strong>Confirm Password &nbsp;</strong> </div>';
				
				/*$replace['editReminderlink'] = '<a tabindex="17" href="javascript:void(0)" onClick="window.open(\'index.php?action=editReminder&patient_id='.$patient_id.'\', \'EditPatientReminders\', \'width=750,height=480,resizable=1,scrollbars=1\')"><strong>Edit Reminders</strong></a></a><br/>
							<a tabindex="18" href="javascript:void(0)" onClick="window.open(\'index.php?action=associateTherapist&patient_id='.$patient_id.'&actionActivateFrom=accountAdmin\', \'ChooseAssociatedTherapists\', \'width=790,height=600,resizable=1,scrollbars=1\')"><strong>Edit Associated Therapists</strong></a><br/>
							<a href="javascript:void(0);" onClick="window.open(\'index.php?action=loginHistory&patient_id='.$patient_id.'\', \'ViewLoginHistory\', \'width=467,height=462,status=no, toolbar=no,resizable=1,scrollbars=yes\')"><strong>View Login History</strong></a>';	*/
                $replace['editReminderlink'] = $patient_id;
							
				if($option == 'update'){
					$this->validateForm();	
					if($this->error == ""){
						//  Populate FieldArray from FormArray
						
						if(!empty($patient_id)){
							$updateArr = $this->assignValueToArrayFields($this->form_array, '', '2', '', 'insert');
							if($updateArr['mass_message_access']==1)
								$updateArr['mass_message_access']=2;
							else
								$updateArr = $this->removeFromArray($updateArr, array('mass_message_access'=>''));

							$changedPassword = $this->value('newPassword');
							
							// If user doesnot change the existing password, password field removed from array
							if(empty($changedPassword)){
								$updateArr = $this->removeFromArray($updateArr, array('password'=>''));
							}
							
							$where = " user_id = '".$patient_id."'";
							$result = $this->update('user',$updateArr,$where);
							
							if($updateArr['status'] == 3){
								
								// Remove clinic patient relationship from clinic_user Table
								$query_removeClinicPatientRelation = "delete from clinic_user where user_id='".$patient_id."'";
								$this->execute_query($query_removeClinicPatientRelation);
								
								// Remove therapist patient relationship from therapist_patient table
								$query_removetherapistPatientRelation = "delete from therapist_patient where patient_id='".$patient_id."'";
								$this->execute_query($query_removetherapistPatientRelation);
							}
							
							/// If account admin change patient's password, a mail should be sent to patient.
							$url = $this->redirectUrl("accountadmin_patient");
							header("location:$url");
						}
					}else{
						//Show errors and populate FORM fields from $_POST.
						
						$this->assignValueToArrayFields($this->form_array, '', '', &$replace, '0');
						$replace['error'] = $this->error;
					}
				}else{
					//Populate FORM fields from database.
					
					$query = "select * from user where user_id='".$patient_id."'";
					$rs = $this->execute_query($query);
					$row = $this->populate_array($rs);
					// Decrypt data
                        $encrypt_field = array('name_title','name_first','name_last','password', 'address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
                        $row = $this->decrypt_field($row, $encrypt_field);
                     // End Decrypt
					// Fetch Replace array from row
					// populate FormArray from FieldArray
					$this->assignValueToArrayFields($this->form_array, $row, '1', &$replace);
				}
				
				$replace['patient_status_options'] = $this->build_select_option($this->config['patientStatus'], $replace['patient_status']);
                $stateArray = array("" => "Choose State...");
                $stateArray = array_merge($stateArray,$this->config['state']);
                /******************************************************************************************/
				/***code starts here for providing AA to opt out for mass message feature for the patient**/
				$mass_message_access=$row['mass_message_access'];
                $replace['mass_message_access'] = $mass_message_access;
				if($mass_message_access==2)
				{ $replace['mass_message_checked'] = "checked";
				  $replace['mass_message_disabled'] = "disabled";
				}
				else
				{ $replace['mass_message_checked'] = "";
				  $replace['mass_message_disabled'] = "";
				}
				
				/***code ends above for providing AA to opt out for mass message feature for the patient**/
				/****************************************************************************************/
				/* code for view intake paperwork link*/
                $replaceIntake=array();
                $intakeview=$this->execute_query("select * from patient_intake where p_user_id=".$patient_id);
                $numcount=$this->num_rows($intakeview);
                if($numcount=='0'){
                //$replace['intakepaperwork']="<a href='javascript:void(0);' onclick='assignintake(".$this->userInfo('user_id')." ,".$patient_id.");'>Intake Paperwork</a>";    
                    $replaceIntake['labelintakepaperwork']="<b>Intake Paperwork</b>";    
                    $replaceIntake['intakepaperwork']="value='Assign' onclick='assignintake(".$this->userInfo('user_id')." ,".$patient_id.");'";  
                }else{
                 $rowintake=$this->fetch_array($intakeview);
                 //print_r($rowintake);
                  if($rowintake['intake_compl_status']=='0'){
                     $replaceIntake['labelintakepaperwork']='<b>Intake Paperwork Assigned </b>';
                     $replaceIntake['intakepaperwork']="value='Assigned' disabled='true' ";    
                  }
                  else{
                  $replaceIntake['labelintakepaperwork']='<b>Intake Paperwork Completed</b>  ';  
                  $replaceIntake['intakepaperwork']="value='View' onclick='javascript:view_intake_paper();' style='width: 70px;' ";    
                  	  }
                  }
            if($_SESSION['accountFeature']['Intake Paperwork']=='1')      
                $replace['intakeAssign']=$this->build_template($this->get_template('intakeAssign'),$replaceIntake);
                              
                
                $sql="select lab_report_id,lab_title,DATE_FORMAT(lab_datetime, '%m/%d/%Y') as labdate,lab_path from patient_lab_reports where pat_user_id ={$patient_id} order by lab_report_id desc";
        	$query=$this->execute_query($sql);
        	//$row=$this->fetch_array($query);
        	
        	$rowLab = $this->populate_array($query,0);
        	if($rowLab!='')
        	{
				foreach($rowLab as $klab => $kValue)
				{
					//print_r($kValue);
					$replace['labresult_display'].='<tr>
	             <td style=" border-bottom:1px solid #cbcbcb;color:#005b85; padding-left:5px; width:100%; "><a href="index.php?action=downloadfile&lab_report_id='.$kValue['lab_report_id'].'" target="_NEW" >'.$kValue['lab_title'].' '.$kValue['labdate'].'</a> </td>
	            
	            </tr>';
				}

			}
        	/***end of view intake paper work***/
                /* code for show lab result*/
                $pid=$this->value('patient_id');
                $replace['LabResult']="<a href='javascript:void(0);' onclick=\"GB_showCenter('Upload Results & Documents', '/index.php?action=aa_upload_lab_result&pid={$pid}&role=aa',190,430);\"><input type='button'' value='Upload'></a>";
               
                /* end of code */
		$replace['patient_state_options'] = $this->build_select_option($stateArray, $replace['patient_state']);
                $replace['patient_title_options'] = $this->build_select_option(array_merge(array('' => 'choose...'), $this->config['title']), $replace['patient_title']);
                $replace['patient_suffix_options'] = $this->build_select_option(array_merge(array('' => 'choose...'), $this->config['suffix']), $replace['patient_suffix']);
                $replace['navigationTab'] = $this->build_template($this->get_template("navigationTab"));            

                
	        $countryArray = $this->config['country'];
	        $replace['country']=implode("','",$countryArray); 
                $replace['patient_country_options'] = $this->build_select_option($countryArray, $row['country']);
		            
		            
		            
		           
	            if($row['country']=='US') {
	            $stateArray = array("" => "Choose State...");
	            $stateArray = array_merge($stateArray,$this->config['state']);              
	            $replace['patient_state_options'] = $this->build_select_option($stateArray, $row['state']);         
	            }
	           
	            else if($row['country']=='CAN') {
	            $stateArray = array("" => "Choose State...");
	            $stateArray = array_merge($stateArray,$this->config['canada_state']);
	            $replace['patient_state_options'] = $this->build_select_option($stateArray, $replace['patient_state']);         
	            } 
                
                
                
                $replace['body'] = $this->build_template($this->get_template("accountAdminEditPatient"),$replace);
			}
            $replace['get_satisfaction'] = $this->get_satisfaction();
			$this->output = $this->build_template($this->get_template("main"),$replace);					
			$updateArr = $this->assignValueToArrayFields($this->form_array, '', '2');
		}
		
		/**
		 * Function to validate the patient credentials like name address email etc.
		 *
		 * @access public
		 */
		function validateForm($form_type = '')
		{
			$error = "";
			
			$objValidationSet = new ValidationSet();
			$allowchar=array('0'=>'@','1'=>'.','2'=>'_','3'=>'-','4'=>'"','5'=>'');
			// first name validation
			$objValidationSet->addValidator(new StringMinLengthValidator('patient_first_name', 1, "First Name cannot be empty",$this->value('patient_first_name')));								
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('patient_first_name',$allowchar,"Please enter valid characters in First Name",$this->value('patient_first_name')));
			
			// last name validation
			$objValidationSet->addValidator(new StringMinLengthValidator('patient_last_name', 1,"Last Name cannot be empty",$this->value('patient_last_name')));
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('patient_last_name',$allowchar,"Please enter valid characters in last Name",$this->value('patient_last_name')));
			
			// email validation
			$objValidationSet->addValidator(new StringMinLengthValidator('patient_email', 1,"Email cannot be empty",$this->value('patient_email')));
			$objValidationSet->addValidator(new EmailValidator('patient_email',"Invalid email address",$this->value('patient_email')));
			
			// phone number validation
			$objValidationSet->addValidator(new NumericOnlyValidator('patient_phone1', null,"Please enter only numeric values in 1st phone",$this->value('patient_phone1')));
			$objValidationSet->addValidator(new NumericOnlyValidator('patient_phone2', null,"Please enter only numeric values in 2nd phone",$this->value('patient_phone2')));
			
			// address line 1 validation (checking for null values)
			$objValidationSet->addValidator(new StringMinMaxLengthValidator('patient_address', 0,50, "Address cannot be more than 50 characters",$this->value('patient_address')));
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('patient_address',null,"Please enter valid characters in Address",$this->value('patient_address')));
			
			// address line 2 validation (checking for null values)
			$objValidationSet->addValidator(new StringMinMaxLengthValidator('patient_address2',0,50, "Address line 2 cannot be more than 50 characters",$this->value('patient_address2')));
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('patient_address2',null,"Please enter valid characters in address line 2",$this->value('patient_address2')));
			
			// city name validation (checking for null)
			$objValidationSet->addValidator(new StringMinMaxLengthValidator('patient_city',0,20, "City name cannot be more than 20 characters",$this->value('patient_city')));
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('patient_city',null,"Please enter valid characters in city",$this->value('patient_city')));
			
			// zip code validation
			/*$objValidationSet->addValidator(new AlphanumericOnlyValidator('patient_zip', array(' '),"Please enter alphanumeric values in zip",$this->value('patient_zip')));
			$objValidationSet->addValidator(new StringMinMaxLengthValidator('patient_zip', 5, 7, "Zip code should be 5 to 7 alphanumeric characters only",$this->value('patient_zip')));*/
			
            /*if($this->value('clinic_country')=='CAN'){
                $objValidationSet->addValidator(new  AlphanumericOnlyValidator('patient_zip', array(' '), "Zip code should be of alphanumeric characters only",$this->value('zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('patient_zip', 6,7, "Zip code should be  6 to 7 alphanumeric characters only",$this->value('zip')));        
               }else{
                $objValidationSet->addValidator(new  NumericOnlyValidator('patient_zip', null, "Zip code should be of numeric characters only",$this->value('zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('patient_zip', 5,5, "Zip code should be  5 numeric characters only",$this->value('zip')));
            }*/
             
            
            // for password while adding patient. password cannot be empty
           // $objValidationSet->addValidator(new  StringMinLengthValidator('newPassword', 6, "Password cannot be empty and should be of 6 characters in length",$this->value('newPassword')));
                
            
            if( $form_type == 'add' ){
            
                // confirm email validation
                $objValidationSet->addValidator(new StringMinLengthValidator('patient_cemail', 1,"Confirm emial cannot be empty",$this->value('patient_cemail')));            
                $objValidationSet->addValidator(new EmailValidator('patient_cemail',"Invalid Confirm email address",$this->value('patient_cemail')));
                    
			    // matching email and confirm email			
			    $arrFieldNames = array("patient_email","patient_cemail");
			    $arrFieldValues = array($_POST["patient_email"],$_POST["patient_cemail"]);
			    $objValidationSet->addValidator(new  IdenticalValuesValidator($arrFieldNames, "Email and  confirm email does not match",$arrFieldValues));								
            }
			else{
			    // matching password and confirm password
			    $arrPasswordFieldNames = array("newPassword","cnewPassword");
			    $arrPasswordFieldValues = array($_POST["newPassword"],$_POST["cnewPassword"]);
			    $objValidationSet->addValidator(new  IdenticalValuesValidator($arrPasswordFieldNames, "Password and  confirm password does not match",$arrPasswordFieldValues));											
			}
			$objValidationSet->validate();		
			
			// if error found
			if ($objValidationSet->hasErrors())
			{
				$patient_id = $this->value('patient_id');
				$option = $this->value('option');
				$patient_zip = $this->value('patient_zip');
				if(!empty($patient_zip)){
					$arrayFields = array("patient_first_name","patient_last_name","patient_email","patient_cemail", "patient_phone1", "patient_phone2", "patient_address", "patient_address2", "patient_city", "patient_zip");
				}else{
					$arrayFields = array("patient_first_name","patient_last_name","patient_email","patient_cemail", "patient_phone1", "patient_phone2", "patient_address", "patient_address2", "patient_city");
				}
				
				if((!empty($patient_id)) && (!empty($option))){
					$arrayFields = $this->addToArray($arrayFields, array("newPassword", "cnewPassword"));
				}
				else{
                    $arrayFields = $this->addToArray($arrayFields, array("newPassword"));
                }
				for($i=0;$i<count($arrayFields);++$i)
				{
					$errorMsg = $objValidationSet->getErrorByFieldName($arrayFields[$i]);
					if ($errorMsg != "")
					{
						$error = $errorMsg."<br>";
						break;
					}
				}
			}
            /*
                    Check email address for duplicacy
               */
                $emailAddress = $this->value('patient_email');
                $query = "select user_id from user where username='".$emailAddress."' and status != '3'";
                $rs = $this->execute_query($query);
                $num_rows = $this->num_rows($rs);
                if($num_rows == 1){
                    $row = $this->populate_array($rs);
                    $patient_id = $this->value('patient_id');
                    
                    // If userId not mataches with the current patient, which one edited
                    if($row['user_id'] != $patient_id){
                        $error .= "Email address already Exist";
                    }
                }
                if($num_rows > 1){
                    $error .= "Email address already Exist";
                }
                
            /* End of checking for duplicacy */			
            
			// return 
			$this->error = $error;	
		}
		
		
		/**
        *  This function sends mail of login detail to patient.
        */
        function accountAdminmail_login_detail_patient(){
            if(is_numeric($this->value('patient_id'))){
                $query = "select user_id,username,password from user where usertype_id = 1 and user_id = '{$this->value('patient_id')}' and (status = 1 or status = 2)";
                $result = @mysql_query($query);
                if( $row = @mysql_fetch_array($result) ){
                    $email_address = $row['username'];
                    $to = $email_address;
                    //$to = "manabendra.sarkar@hytechpro.com";
                    $clinicName=html_entity_decode($this->get_clinic_info($this->value('patient_id'),'clinic_name'), ENT_QUOTES, "UTF-8");
                    $subject = "Information from ".$this->setmailheader($clinicName);
                    $password = $this->decrypt_data($row['password']);
                    $images_url = $this->config['images_url'];
                    $user_id = $row['user_id'];
                     $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
                    if($clinic_channel==1){
                                $business_url=$this->config['business_tx']; 
                                $support_email=$this->config['email_tx'];
                            }else{
                                $business_url=$this->config['business_wx']; 
                                $support_email=$this->config['email_wx'];   
                            }
                    $data = array(
                        'images_url' => $images_url,
                        'username' => $email_address,
                        'password' => $password,
                        'business_url'=>$business_url,
                        'support_email'=>$support_email
                    );
                   
                    $clinic_type = $this->getUserClinicType($user_id);
	                if( $clinic_channel== 1){
	                	$message = $this->build_template($this->get_template("resend_login_detail_plpto"),$data);
	                }else{
	                	$message = $this->build_template($this->get_template("resend_login_detail_wx"),$data);
	                }
	                /*elseif( $clinic_type == 'elpto' ){
	                	$message = $this->build_template($this->get_template("resend_login_detail"),$data);	
	                }*/
                    
                    
                    //$message = $this->build_template($this->get_template("resend_login_detail"),$data);
                    // To send HTML mail, the Content-type header must be set
                    $headers  = 'MIME-Version: 1.0' . "\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                    $headers .= "From:".$this->setmailheader($clinicName)." <do-not-reply@txxchange.com>\n";
                    $returnpath = '-fdo-not-reply@txxchange.com';
                    
					$success = mail($to,$subject,$message,$headers,$returnpath);
                    if( $success == true){
                        echo "success";
                        return;
                    }
                    else{
                        echo "failed";
                        return;
                    }
                }
            }
            
            echo "Email address not found.";
            return;
              
        }

		/**
		 * Function to change the status of patient from "Active" to "InActive" or alternate
		 *
		 * @access public
		 */
		function change_status()
		{
			$patient_id = $this->value('patient_id');
			$this->set_patientStatusActiveInActive($patient_id);
			header('location:index.php?action=accountadmin_patient');
		}
		
		/**
		 * Function to populate side bar
		 *
		 * @return side bar menu template
 		 * @access public			
		 */
		function sidebar(){
			//code for checking the trial period days left for Provider/AA
			$freetrialstr=$this->getFreeTrialDaysLeft($this->userInfo('user_id'));
			$data = array(
				'name_first' => $this->userInfo('name_first'),
				'name_last' =>  $this->userInfo('name_last'),
				'sysadmin_link' => $this->sysadmin_link(),
				'therapist_link' => $this->therapist_link(),
				'freetrial_link' => $freetrialstr
			);
			
			return $this->build_template($this->get_template("sidebar"),$data);
		}
		
		/**
		 * This is used to get the name of the template file from the config_txxchange.xml
		 * for the action request
		 *
		 * @param String $template
		 * @return template page info
		 * @access public
		 */
		function get_template($template){
			$login_arr = $this->action_parser($this->action,'template') ;
			$pos =  array_search($template, $login_arr['template']['name']); 
			return $login_arr['template']['path'][$pos];
		}
		
		/**
		 * This is used to display the final template page.
		 *
		 * @access public
		 */
		function display(){
			view::$output =  $this->output;
		}
		
		/**
		 * Function to get Therapist Name from user Table, if we pass ID
		 *
		 * @param string $therapistId
		 * @return string $therapist_name
		 */
		function get_therapistNameById($therapistId){
			$query = "select name_first, name_last from user where user_id='".$therapistId."'";
			$rs = $this->execute_query($query);
			$row = $this->fetch_array($rs);
			return $therapist_name = $row['name_first'].' '.$row['name_last'];
		}
		
		
		/**
		 * Function return "Active" if we pass status=1 and "InActive" if we pass status=2
		 *
		 * @param string $status_id
		 * @return string status (active/inactive)
		 */
		function get_patientStatus($status_id){
			if($status_id == '1'){
				return 'Active';
			}elseif($status_id == '2'){
				return 'InActive';
			}else{
				return '';
			}
		}
		
		/**
		 * Function to change the status from Active to InActive or alternate
		 *
		 * @param String $user_id
		 */
		function set_patientStatusActiveInActive($user_id){
			//  query to get current status of the specified user
			$query = "select status from user where user_id='".$user_id."'";
			$rs = $this->execute_query($query);
			$row = $this->fetch_array($rs);
			
			if($row['status'] == '1'){
				$newStatus = '2';
			}elseif($row['status'] == '2'){
				$newStatus = '1';
			}else{
				$newStatus = $row['status'];
			}
			
			//  query for changing the status
			$query2 = "update user set status='".$newStatus."' where user_id='".$user_id."'";
			$rs2 = $this->execute_query($query2);
		}
		
		/**
		 * Function to match database table fields name with form fields name
		 *
		 * @return String array
		 * @access public
		 */
		function populate_form_array()
		{
			$arr = array(
							'patient_title' 			=> 'name_title',
							'patient_first_name' 		=> 'name_first',
							'patient_last_name' 		=> 'name_last',
							'patient_suffix' 			=> 'name_suffix',
							'patient_email' 			=> 'username',
							'newPassword'				=> 'password',
							'patient_cemail'			=> '',
							'patient_phone1'			=> 'phone1',
							'patient_phone2'			=> 'phone2',
							'patient_address'			=> 'address',
							'patient_address2'			=> 'address2',
							'patient_city'				=> 'city',
							'patient_state'				=> 'state',
							'clinic_country'			=> 'country',
							'patient_zip' 				=> 'zip',
							'patient_status'			=> 'status',
							'refering_physician'		=> 'refering_physician',
							'mass_message_access'        => 'mass_message_access'
						);
			return $arr;			
		}
		/**
		 * This function shows the login history of Patients.
		 * 
		 * @access public
		 *
		 */
		function loginHistoryAccountAdmin(){
			$query = "select * from login_history where user_id = '{$this->value('patient_id')}' and user_type = '1' ORDER BY login_date_time DESC ";
			$result = @mysql_query($query);
			while($row = @mysql_fetch_array($result)){
				$login_data = date("l,F j, Y - h:ia ",strtotime($row['login_date_time']));
				$data = array(
					'login_data' => $login_data,
					'style' => $cnt++%2?"ineerwhite":"inergrey"
				);
				$replace['loginHistoryRecord'] .= $this->build_template($this->get_template("loginHistoryRecord"),$data);
			}
			$replace['browser_title'] = "Tx Xchange: View Login History";
			$this->output = $this->build_template($this->get_template("loginHistory"),$replace);
		}
	}
	
	// creating object of this class
	$obj = new accountAdmin_patient();
?>
