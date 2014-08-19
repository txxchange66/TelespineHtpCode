<?php

	

	/**
	 *
	 * Copyright (c) 2008 Tx Xchange
	 * 
	 * It includes the functionality for clinic listing, Edit existing and Add new clinic.
	 * it also includes the functionality for list user, edit, changestatus and delete for selected clinic from list.
	 * 
	 * // necessary classes 
	 * require_once("module/application.class.php");
	 * 
	 * // pagination class
	 * require_once("include/paging/my_pagina_class.php");	
	 * 
	 * //Server side form validation classes
	 * require_once("include/validation/_includes/classes/validation/ValidationSet.php");
	 * require_once("include/validation/_includes/classes/validation/ValidationError.php");
	 * 
	 */
		
	
	// including files	
	require_once("module/application.class.php");
	require_once("include/paging/my_pagina_class.php");	
	require_once("include/validation/_includes/classes/validation/ValidationSet.php");
	require_once("include/validation/_includes/classes/validation/ValidationError.php");	
	
	// class declaration
  	class clinic extends application{
  		
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
		
		
		
		/**
		 *  constructor
		 *  set action variable from url string, if not found action in url, call default action from config.php
		 * 
		 */		
		function __construct(){
			parent::__construct();
			$this->form_array = $this->populate_form_array();
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
				$str = "accountAdminClinic"; //default if no action is specified
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
				$str = $str."()";
				eval("\$this->$str;"); 
			}
			else{
				$this->output = $this->config['error_message'];
			} 
			$this->display();
		}

		
		/**
		 * Function to list the clinic.
		 *
		 * @access public
		 */
		function listClinic(){
			
			$this->set_session_page();
			
			// set sorting parameters on a coulmn
			if($this->value('sort') != ""){
				if($this->value('order') == 'desc' ){
					$orderby = " order by {$this->value('sort')} desc ";
				}
				else{
					$orderby = " order by {$this->value('sort')} ";
				}
			}
			else{
				$orderby = " order by clinic_name ";
			}
            
			// set template variables
			$replace = array();
			$replace['browser_title'] = 'Tx Xchange: List of Clinic';
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();
			$replace['heading'] = 'Account List';
            if( $this->value('search') != "" ){
                $query = "select clinic_id,clinic_name,status, creationDate,trial_date,trial_status,
                          (
                            SELECT count( * ) FROM clinic c2
                            WHERE c2.parent_clinic_id = c1.clinic_id
                          ) + 1 AS noClinic
                          FROM clinic c1
                          WHERE parent_clinic_id = '0 ' and clinic_name like '%{$this->value('search')}%' {$orderby}";
                $sqlcount = "select count(1)
                          FROM clinic c1
                          WHERE parent_clinic_id = '0 ' and clinic_name like '%{$this->value('search')}%'";
            }
            else{
			    $query = "SELECT clinic_id, clinic_name,status , creationDate,trial_date,trial_status,
                         (
                            SELECT count( * ) FROM clinic c2
                            WHERE c2.parent_clinic_id = c1.clinic_id
                         ) + 1 AS noClinic
                         FROM clinic c1
                         WHERE parent_clinic_id = '0 ' {$orderby}";
                $sqlcount = "SELECT count(1)
                         FROM clinic c1
                         WHERE parent_clinic_id = '0 '";
            
            }
			
			$link = $this->pagination($rows = 0,$query,'listClinic',$this->value('search'),'','','',$sqlcount);                                     
	        $replace['link'] = $link['nav'];
			$result = $link['result'];
			if(is_resource($result)){
				while($row = $this->fetch_array($result)){
				$replace['classname'] = (++$k%2) ? 'line2' : 'line1';
				$replace['clinicId'] = $row['clinic_id'];
				$replace['clinicName'] = $row['clinic_name'];
				$replace['clinicStatus'] = $this->get_status($row['status']);
                if($row['trial_status']==1 and ($row['trial_date']!='' or !empty($row['trial_date']))){
                    $replace['clinicStatus'] = 'Trial';
                }
                $replace['noClinic'] = $row['noClinic'];
                $replace['createdOn'] = $this->formatDate($row['creationDate']);
				$rowdata .= $this->build_template($this->get_template("listClinicRecord"),$replace);
				}
			}
			
			$replaceListClinicHead = array(
				'clinic_name' => 'Account Name',
                'noClinic' => '# Clinic',
                'status' => 'Status',
                'creationDate' => 'Created On'
			);
			$replace['listClinicHead'] = $this->build_template($this->get_template("listClinicHead"),$this->table_heading($replaceListClinicHead,"clinic_name"));
						
			$replace['rowdata'] = $rowdata;
			$replace['body'] = $this->build_template($this->get_template("listClinic"),$replace);
			$this->output = $this->build_template($this->get_template("main"),$replace);
			
		}
		/**
		 * Function to View the Clinic Details
		 *
		 * @access public
		 */
		function viewClinicDetails(){
			$replace = array();
			$replace['browser_title'] = 'List of Clinic';
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();		

			$replace['heading'] = 'Clinic Details';
			$clinic_id = $this->value('clinic_id');			
			$query = "select * from clinic where clinic_id='".$clinic_id."'";
			// executing query
			$rs = $this->execute_query($query);
			$row = $this->populate_array($rs);
			// Fetech Replace array from row			
			$this->assignValueToArrayFields($this->form_array, $row, '1', &$replace);
			
			$replace['commaseprator'] = (!empty($replace['clinic_address2'])) ? ', ': '';			
			$replace['clinic_id'] = $clinic_id;
			$this->clinicTherapistListing($replace,$clinic_id);
			$replace['body'] = $this->build_template($this->get_template("clinicInfo"),$replace);
			$this->output = $this->build_template($this->get_template("main"),$replace);				
		}
		/**
		 * Function to Edit Clinic Details
		 *
		 * @access public
		 */
		function editClinicInfo()
		{
			$replace = array();
			$option = $this->value('option');
			$clinic_id = $this->value('clinic_id');
			$corporate_ehs =  $this->value('corporate_ehs');
                        
			$replace['browser_title'] = 'Create New Account';
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();
			$replace['clinic_id'] = $clinic_id;
			
			// When user modify the clinic details
			if((empty($clinic_id)) && (empty($option)))
			{
				// Populate BLANK FORM fields
                            
				$replace['heading'] = 'Create New Account';
				$this->assignValueToArrayFields($this->form_array, '', '1', &$replace);
			}else{
				$replace['heading'] = 'Create New Account';
				if($option == 'update'){
					$this->validateForm();					
					if($this->error == ""){
						//  Populate FieldArray from FormArray
						if(!empty($clinic_id)){
							$updateArr = $this->assignValueToArrayFields($this->form_array, '', '2', '', 'insert');
							$where = " clinic_id = '".$clinic_id."'";
							$result = $this->update('clinic',$updateArr,$where);
						}else{
							$insertArr = $this->assignValueToArrayFields($this->form_array, '', '2', '', 'insert');
							$insertArr['status'] = '1';
                                                        $insertArr['creationDate'] = date("Y-m-d H:i:s");
                                                        $insertArr['parent_clinic_id'] = '0';
                                                        if($corporate_ehs==''){
                                                        $insertArr['corporate_ehs']=0;  
                                                        }
                                                        print_r($insertArr);
                                                        $result = $this->insert('clinic',$insertArr);
						}
						header("location:index.php?action=listClinic");	
					}else{
						//Show errors and populate FORM fields from $_POST.
						$this->assignValueToArrayFields($this->form_array, '', '', &$replace, '0');
						$replace['error'] = $this->error;
					}
				}else{
					//Populate FORM fields from database.
					$query = "select * from clinic where clinic_id='".$clinic_id."'";
					$rs = $this->execute_query($query);
					$row = $this->populate_array($rs);
					// Fetch Replace array from row
					// populate FormArray from FieldArray
					$this->assignValueToArrayFields($this->form_array, $row, '1', &$replace);
				}
			}
		
			$countryArray = $this->config['country'];
           		$replace['country']=implode("','",$countryArray); 
            		$replace['patient_country_options'] = $this->build_select_option($countryArray, $replace['clinic_country']);

           // print_r($_REQUEST);
           // die;
            if($_REQUEST['clinic_country']!=''){
            	if($_REQUEST['clinic_country']=='US')
            	{
            		$stateArray = array("" => "Choose State...");
			$stateArray = array_merge($stateArray,$this->config['state']);
			$replace['stateOptions'] = 	$this->build_select_option($stateArray, $replace['clinic_state']);
			
            	}
            	 else if($_REQUEST['clinic_country']=='CAN')
            	{
            		$stateArray = array("" => "Choose Provinces...");
			$stateArray = array_merge($stateArray,$this->config['canada_state']);
			$replace['stateOptions'] = 	$this->build_select_option($stateArray, $replace['clinic_state']);
			
            	}
            	
            	
            }
            else{
            	
            		$stateArray = array("" => "Choose State...");

			$stateArray = array_merge($stateArray,$this->config['state']);
			$replace['stateOptions'] = 	$this->build_select_option($stateArray, $replace['clinic_state']);
		
            }
			// creating state drop down element
			 if($replace['corporate_ehs']==1)
                             $replace['corporate']='Checked';
                         else
                              $replace['corporate']='';
			$replace['body'] = $this->build_template($this->get_template("editClinicInfo"),$replace);
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}
		
		
		/**
		 * Validating form fields
		 *
		 * @access public
		 */
		function validateForm()
		{
						
			// creating validation object			
			$objValidationSet = new ValidationSet();
			
			// validating clinic name
			$objValidationSet->addValidator(new StringMinLengthValidator('clinic_name', 1, "Clinic Name cannot be empty",$this->value('clinic_name')));					
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('clinic_name', null,"Please enter valid characters in Clinic Name",$this->value('clinic_name')));
			
			// validating clinic address line 1
			//$objValidationSet->addValidator(new StringMinLengthValidator('clinic_address', 1,"Address cannot be empty",$this->value('clinic_address')));
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('clinic_address', null,"Please enter valid characters in address",$this->value('clinic_address')));
			
			// validating clinic address line 2
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('clinic_address2', null,"Please enter valid characters in address line 2",$this->value('clinic_address2')));
			
			// validating city name
			//$objValidationSet->addValidator(new StringMinLengthValidator('clinic_city', 1,"City cannot be empty",$this->value('clinic_city')));
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('clinic_city', null,"Please enter valid characters in city",$this->value('clinic_city')));
			
			// validating state name
			//$objValidationSet->addValidator(new StringMinLengthValidator('clinic_state', 2,"State cannot be empty",$this->value('clinic_state')));
			
			//$objValidationSet->addValidator(new NumericOnlyValidator('clinic_zip', null,"Please enter only numeric values in zip",$this->value('clinic_zip')));
			//$objValidationSet->addValidator(new StringMinMaxLengthValidator('clinic_zip', 5, 7, "Zip code should be 5 to 7 alphanumeric characters",$this->value('clinic_zip')));
			    /*if($this->value('clinic_country')=='CAN'){
                $objValidationSet->addValidator(new  AlphanumericOnlyValidator('clinic_zip', array(' '), "Zip code should be of alphanumeric characters only",$this->value('zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('clinic_zip', 6,7, "Zip code should be  6 to 7 alphanumeric characters only",$this->value('zip')));        
               }else{
                $objValidationSet->addValidator(new  NumericOnlyValidator('clinic_zip', null, "Zip code should be of numeric characters only",$this->value('zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('clinic_zip', 5,5, "Zip code should be  5 numeric characters only",$this->value('zip')));
            }*/
			 $objValidationSet->addValidator(new  StringMinMaxLengthValidator('clinic_zip', 5,7, "Zip code should be  5 to 7 characters only",$this->value('zip')));
            // validating clinic Phone
           // $objValidationSet->addValidator(new StringMinLengthValidator('clinic_phone', 1, "Phone cannot be empty",$this->value('clinic_phone')));
			
			/*
				Check clinic name for duplicacy
			*/
			$clinic_id = $this->value('clinic_id');
							
			//coment by sanjay for duplicate record
			/*if (empty($clinic_id))
			{	
				$clinic_name = $this->value('clinic_name');
				$queryClinic = "SELECT clinic_id FROM clinic WHERE clinic_name = '".$clinic_name."' AND status <> 3";
				$result = $this->execute_query($queryClinic);
				
				//if record found that means clinic name not unique else it is unique
				if ($this->num_rows($result) != 0)
				{
					$objValidationErr = new ValidationError('clinic_name',"Clinic Name : exists in the system. Please choose another.");
					$objValidationSet->addValidationError($objValidationErr);
				}							
				
			}
			else
			{
				$clinic_name = $this->value('clinic_name');
				$queryClinic = "SELECT clinic_id FROM clinic WHERE clinic_name = '".$clinic_name."' AND status <> 3 AND clinic_id <> ".$clinic_id;				
				$result = $this->execute_query($queryClinic);
				
				//if record found that means clinic name not unique else it is unique
				if ($this->num_rows($result) != 0)
				{
					$objValidationErr = new ValidationError('clinic_name',"Clinic Name : exists in the system. Please choose another.");
					$objValidationSet->addValidationError($objValidationErr);
				}			
				
			}*/	
			
			/* End of checking for duplicacy */
			
			
			$objValidationSet->validate();		
			
			if ($objValidationSet->hasErrors())
			{
				/*
				$clinic_zip = $this->value('clinic_zip');
				if(!empty($clinic_zip)){
					$arrayFields = array("clinic_name","clinic_address","clinic_address2","clinic_city","clinic_zip");
				}else{
					$arrayFields = array("clinic_name","clinic_address","clinic_address2","clinic_city");
				}
				*/
				
				$arrayFields = array('clinic_name','clinic_address','clinic_address2','clinic_city', 'clinic_state', 'clinic_zip','clinic_phone');
				
				for($i=0;$i<count($arrayFields);++$i)
				{
					$errorMsg = $objValidationSet->getErrorByFieldName($arrayFields[$i]);
				
					if ($errorMsg != "")
					{
						$this->error = $errorMsg."<br>";
						break;
					}
				}		
							
			}	
			else 
			{
				$this->error = "";	
			}
			
		}

		
		/**
		 * list the therapist of that clinic
		 *
		 * @param array $replace
		 * @param integer $clinicId
		 * @access public
		 */
		function clinicTherapistListing(&$replace,$clinicId)
		{
						
			//$replace = array();
			
			$userInfo = $this->userInfo();
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else 
			{	
				$userId = $userInfo['user_id'];
				$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";
				
				/* Search String if any */
			
				$sqlWhere = "";
				
				if($this->value('search')!='')
				{
					$sqlWhere = " AND ".$this->makeSearch(ALL_WORDS,$this->value('search'),'name_first');
					$replace['searchStr'] = '&search='.$this->value('search');					
				}
				else 
				{
					$sqlWhere = "";
					$replace['searchStr'] = "";
				}	
				
				/*  Search String End     */				
					
								
				/* Defining Sorting */				
			
				
				$orderByClause = "";
				if ($this->value('sort') == '') 
				{
					$replace['username'] = "action=viewClinicDetails&sort=username&order=DESC".$replace['searchStr'];
					$replace['full_name'] = "action=viewClinicDetails&sort=full_name&order=ASC".$replace['searchStr'];
					$replace['usertype_id'] = "action=viewClinicDetails&sort=usertype_id&order=ASC".$replace['searchStr'];
					$replace['last_login'] = "action=viewClinicDetails&sort=last_login&order=ASC".$replace['searchStr'];
					/* Added for bug# 10156 */
					$replace['creation_date'] = "action=viewClinicDetails&sort=creation_date&order=ASC".$replace['searchStr'];
					/* End */
					$replace['status'] = "action=viewClinicDetails&sort=status&order=ASC".$replace['searchStr'];
					
					$replace['usernameImg'] = '&nbsp;<img src="images/sort_asc.gif">';
					
					$orderByClause = " username ASC ";
				}
				else {
					
					$queryStr = $replace['searchStr'];
					$this->setSortFields($replace,"viewClinicDetails",$queryStr);	
					$orderByClause = $replace['orderByClause'];
					
				}
				
				/* End */		

				//Now we may have parameter 'do' set as delete or inactive or active
				if(isset($_GET['do']) && isset($_GET['id']))
				{
					switch ($_GET['do']) 
					{
						case "delete":
										$error = $this->deleteClinicSubscriber();
										$replace['error'] = $error;		
										unset($_GET['do']);				
										unset($_GET['id']);
										
										break;
										
						case "inactive":										
										
										$error = $this->inactiveClinicSubscriber();
										$replace['error'] = $error;	
										unset($_GET['do']);				
										unset($_GET['id']);
										
										break;								
					}

					
				}
				else 
				{
					// dont do anything
					//$replace['error'] = "";	
				}
				
				//Get the therapist list
			    $privateKey = $this->config['private_key'];
				$sqlUser = "SELECT *, CONCAT_WS(' ',AES_DECRYPT(UNHEX(name_title),'{$privateKey}') as name_title,AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last,name_suffix) AS full_name FROM user WHERE user_id IN (SELECT user_id FROM clinic_user WHERE clinic_id = '$clinicId') AND (usertype_id = 2 OR usertype_id = 3) AND (status = 1 OR status = 2)".$sqlWhere." ORDER BY ".$orderByClause;
				
				
				
				//echo $sqlUser;
				
				//$result = $this->execute_query($sqlUser);				
				
				$link = $this->pagination($rows = 0,$sqlUser,'viewClinicDetails',$this->value('search'),array('do','id'));                                          
	
	            $replace['link'] = $link['nav'];
	
	            $result = $link['result']; 	
	            
				
				if($this->num_rows($result)!= 0)
				{
					$replace['clinicThpstTblHead'] = $this->build_template($this->get_template("clinicThpstTblHead"),$replace);
					while($row = $this->fetch_array($result))
					{
						/*echo "Row : <br>";
						print_r($row);
						echo "Row End : <br>";*/
						
						$row['style'] = ($c++%2)?"line1":"line2";
						
						if ($row['last_login'] != null) 
						{
							//$row['last_login'] = date('D, M jS Y \a\t h:iA', strtotime($row['last_login']));					
							$row['last_login'] = $this->formatDate($row['last_login']);					
							
						}	
						else 
						{
							$row['last_login'] = "Never";
						}	

						$row['creation_date'] = $this->formatDate($row['creation_date']);	
						
						if($row['usertype_id'] == 2)
						{
							
							if($row['admin_access'] == 1 && $row['therapist_access'] == 1)
							{
								$row['usertype'] = "Admin, Provider";							
							}
							else if($row['admin_access'] == 1)
							{
								$row['usertype'] = "Admin";							
							}
							else if($row['therapist_access'] == 1)
							{
								$row['usertype'] = "Provider";
							}
							
							
							//$row['usertype'] = "Therapist";							
						}
						else if($row['usertype_id'] == 4)
						{
							$row['usertype'] = "System Admin";
						}
						 
						
						if ($row['status'] == 1)
						{
							$row['statusType'] = "Active";
							$row['clinic_id'] = $clinicId;
							$replace['clinicThpstTblRecord'] .=  $this->build_template($this->get_template("clinicThpstTblRecord"),$row);
						}
						else if ($row['status'] == 2)
						{
							$row['statusType'] = "Inactive";
							$row['clinic_id'] = $clinicId;
							$replace['clinicThpstTblRecord'] .=  $this->build_template($this->get_template("clinicThpstTblRecordInactive"),$row);
							
						}
						
					}
				}
				else 
				{
					$replace['clinicThpstTblHead'] = $this->build_template($this->get_template("clinicThpstTblHead"),$replace);
					$replace['clinicThpstTblRecord'] =  '<tr class="line2"> <td colspan="7">No Provider to list</td></tr>';
					$replace['link'] = "&nbsp;";
				}					
				
				if ($sqlWhere == "") 			
				{		
					//$extraParam['patientId'] = $patientId;
					//$replace['filter'] = $this->build_template($this->get_template("assocTherapistFilter"),$extraParam);	
					//$replace['filter'] = $this->build_template($this->get_template("clinicThpstFilter"));	
				}
				else {
					//$extraParam['search'] = $this->value('search');
					//$extraParam['searchOn'] = $this->build_template($this->get_template("articleSearchOn"),$searchOn);
					$extraParam['searchOn'] = "";
					//$extraParam['patientId'] = $patientId;
					
					//$replace['filter'] = $this->build_template($this->get_template("clinicThpstFilterClear"));
				}				
				
				return  $replace;
				
			}	
			
		}
		
		/**
		 * delete users of clinic
		 *
		 * @access public
		 */
		function deleteClinicSubscriber()
		{
								
			$userInfo = $this->userInfo();
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else 
			{	
				$user_id = (int) $this->value('id');
				
				//extra precaution check if user has access 			
				
				$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";								
				
				if ("SysAdmin" == $userType) 
				{					
					
					$err = "";
					// only sys admin has access to delete 				
					
					//But if the usertype of this userid is sysadmin it can't be deleted
					$query = "SELECT usertype_id FROM user WHERE user_id = ".$user_id;
					$result = $this->execute_query($query);	
					
					if ($this->num_rows($result)!=0)
					{
						$row = $this->fetch_array($result);	
						if ($row['usertype_id'] == 4)
						{
							//Sys admin
							$err = "System Admin cannot be deleted";
						}
						else if ($row['usertype_id'] == 2)
						{
							//Therapist
							//allow only if there are no patient(s) associated with this therapist
							//check
							$queryAssocPatients = "SELECT patient_id FROM therapist_patient WHERE therapist_id = ".$user_id;
							$resultAssocPatients = $this->execute_query($queryAssocPatients);
							
							if ($this->num_rows($resultAssocPatients) == 0)
							{
								$queryUpdate = "UPDATE user SET status = 3 WHERE user_id = ". $user_id;					
								$this->execute_query($queryUpdate);	
							}
							else 
							{
								//There exist relationship
								$err = "There are patient(s) who are associated with this therapist, please remove the association and do the action";
							}
							
							
						}
						
						
					}
					else 
					{
						//No user exist with this id
						$err = "Not a valid user";
					}
										
					//header("location:index.php?action=subscriberListing");
					return $err;
		
				}	
				else
				{
					unset($_GET['id']);	
					header("location:index.php");
				}	
					
			}		
			
		}
		
		/**
		 * set status active of user
		 * 
		 * @access public
		 */
		function activeClinicSubscriber()
		{
			
			/*
				Use to activate the subscriber
			
			*/
			
			$userInfo = $this->userInfo();
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else 
			{	
				$user_id = (int) $this->value('id');
				$clinic_id = (int) $this->value('clinic_id');				
				//extra precaution check if user has access 			
				
				$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";								
				
				if ("SysAdmin" == $userType) 
				{					
					// only sys admin has access to delete 				
				
					$queryUpdate = "UPDATE user SET status = 1 WHERE user_id = ". $user_id;
					
					$this->execute_query($queryUpdate);	
					header("location:index.php?action=viewClinicDetails&clinic_id=$clinic_id");
		
				}	
				else
				{
					unset($_GET['id']);	
					header("location:index.php");
				}	
					
			}		
			
		}
		
		
		/**
		 * set status InActive of the user of the clinic
		 *
		 * @access public
		 */
		function inactiveClinicSubscriber()
		{
			
			/*
				Use to inactivate the subscriber
			
			*/
			
			$userInfo = $this->userInfo();
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else 
			{	
				$user_id = (int) $this->value('id');
				
				//extra precaution check if user has access to inactivate
				
				$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";								
				
				if ("SysAdmin" == $userType) 
				{					
										
					$err = "";
					// only sys admin has access to delete 				
					
					//But if the usertype of this userid is sysadmin it can't be inactivated
					$query = "SELECT usertype_id FROM user WHERE user_id = ".$user_id;
					$result = $this->execute_query($query);	
					
					if ($this->num_rows($result)!=0)
					{
						$row = $this->fetch_array($result);	
						if ($row['usertype_id'] == 4)
						{
							//Sys admin
							$err = "System Admin cannot be made inactive";
						}
						else if ($row['usertype_id'] == 2)
						{
							//Therapist
							//allow only if there are no patient(s) associated with this therapist
							//check
							$queryAssocPatients = "SELECT patient_id FROM therapist_patient WHERE therapist_id = ".$user_id;
							$resultAssocPatients = $this->execute_query($queryAssocPatients);
							
							if ($this->num_rows($resultAssocPatients) == 0)
							{
								$queryUpdate = "UPDATE user SET status = 2 WHERE user_id = ". $user_id;					
								$this->execute_query($queryUpdate);	
							}
							else 
							{
								//There exist relationship
								$err = "There are patient(s) who are associated with this Provider, please remove the association and do the action";
							}
							
							
						}
						
						
					}
					else 
					{
						//No user exist with this id
						$err = "Not a valid user";
					}
										
					//header("location:index.php?action=subscriberListing");
					return $err;				
		
				}	
				else
				{
					unset($_GET['id']);	
					header("location:index.php");
				}	
					
			}		
			
		}
		
		
		/**
		 * confirm before deleting the user of clinic
		 *
		 * @access public
		 */
		function popupDelClinicConfirmBox()
		{
			
			$clinic_id = $this->value('clinic_id');
			$replace['clinic_id'] = $clinic_id;
			
			// Clinic Name which is to be activate/deactivate
			$queryClinicName = "SELECT clinic_name FROM clinic WHERE clinic_id ='".$clinic_id."'";
			$resultClinicName = $this->execute_query($queryClinicName);
			$row = $this->fetch_array($resultClinicName);
			
			$replace['clinic_name'] = $row['clinic_name'];			
			
			// Find out whether clinic has already been made inactive
			$query = "SELECT status FROM clinic WHERE clinic_id = ".$clinic_id;
			$result = $this->execute_query($query);	
					
			$row = $this->fetch_array($result);	
			
			if ($row['status'] == 2)
			{
				// Some Template
				$replace['popupConfirmationHeading'] = 'Clinic Activate Confirmation';
				$replace['popupConfirmationMessage'] = 'You have selected to activate this Clinic. By doing so all the subscribersof this clinic will again be able to login in the system.';
				$replace['changeToStatus'] = '1';
				
				$this->output = $this->build_template($this->get_template("popupConfirmRemove"),$replace);
			}
			else 
			{
				// Some Template
				$replace['popupConfirmationHeading'] = 'Clinic Deactivate Confirmation';
				$replace['popupConfirmationMessage'] = 'You have selected to deactivate this Clinic. By doing so all the subscribers of this clinic will not be able to login in the system.';
				$replace['changeToStatus'] = '2';
				
				$this->output = $this->build_template($this->get_template("popupConfirmRemove"),$replace);
			}		
						
			
			
		}
		
		/**
		 * Remove all the users of the particluar clinic.
		 *
		 * @access public
		 */
		function removeClinicAllSubscribers()
		{
			
			$clinic_id = $this->value('clinic_id');
			$changeTo = $this->value('to');
			
			/* Comment All AJ March 03, 2008
			//Updation 1
			$updateArr = array(

								'status'=> '2'					
								);
									
			$clinicUsersId = "SELECT user_id FROM clinic_user WHERE clinic_id = '".$clinic_id."'";

					
			$where = " user_id IN ( ".$clinicUsersId." ) AND (usertype_id = 2 OR usertype_id = 3)";
						
			$result = $this->update('user',$updateArr,$where);
			
			// Updation 2
			
			// Delete records from table clinic_user but only therapist and account admin not the patients
			
			//Therapist and account admins user ids for this clinic
			$queryIds = "SELECT user.user_id FROM user,clinic_user WHERE user.user_id = clinic_user.user_id AND user.usertype_id <> 3 AND clinic_user.clinic_id = '".$clinic_id."'";
			
			$queryDel = "DELETE FROM clinic_user WHERE user_id IN ( ".$queryIds ." )";
			
			//echo $queryDel;
			
			//$this->execute_query($queryDel);	
			
			*/

			//Updation 3
			$updateArr = array(

								'status'=> $changeTo					
								);	

					
			$where = " clinic_id = '".$clinic_id."'";
						
			$result = $this->update('clinic',$updateArr,$where);	
			
			$url = $this->redirectUrl("listClinic");		
			header("location:".$url);			
		}
		
		
		/**
		 * Edit detials of users of the selected clinic.
		 *
		 * @access public
		 */
		function editClinicSubscriber()
		{
			
			$id = (int) $this->value('id');	
			$clinic_id = (int) $this->value('clinic_id');	
			
			$replace = array();
			
			$userInfo = $this->userInfo();			
			
			$userId = $userInfo['user_id'];
			$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";			
			
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else if ($userType == "SysAdmin" || $userId == $id ) 			
			{	
				
				
				include_once("template/clinic/clinicThpstArray.php");

				$this->formArray = $formArray;			
				
				//Also have the questions from question table
				$questions = array(""=>"Choose...");
				
				$query = "SELECT * FROM questions";
				$result = $this->execute_query($query);
				
				
				if($this->num_rows($result)!= 0)
				{
					while($row = $this->fetch_array($result))
					{	
						$questions[$row['question_id']] = $row['question'];
					}
					
				}				
				
				//check if form has been submitted or not
				
				if (isset($_POST['submitted_edit']) && $_POST['submitted_edit'] == 'Save Subscriber')
				{
					//form submitted check for validation
					
					$this->validateFormClinicSubscriber("Edit",$id);	
					
					//Also check the user status for system admin it cannot be inactive
					//And for therapist can be inactive if there is patient associated with therapist					
					
					if($this->error == "")
					{
						$query = "SELECT usertype_id FROM user WHERE user_id = ".$id;
						$result = $this->execute_query($query);	
						
						if ($this->num_rows($result)!=0)
						{
							$row = $this->fetch_array($result);	
							if ($row['usertype_id'] == 4 && $_POST['status'] == '2')
							{
								//Sys admin
								$this->error = "System Admin cannot be made inactive";
							}
							else if ($row['usertype_id'] == 2 && $_POST['status'] == '2')
							{
								//Therapist
								//allow only if there are no patient(s) associated with this therapist
								//check
								$queryAssocPatients = "SELECT patient_id FROM therapist_patient WHERE therapist_id = ".$id;
								$resultAssocPatients = $this->execute_query($queryAssocPatients);
								
								if ($this->num_rows($resultAssocPatients) == 0)
								{
									$this->error = "";
								}
								else 
								{
									//There exist relationship
									$this->error = "There are patient(s) who are associated with this Provider please remove the association and do the action";
								}
								
								
							}
							else 
							{
								$this->error = "";
							}
							
							
						}
						else 
						{
							$this->error = "Not a valid User";	
						}
					}								
				
					if($this->error == "")
					{
												
						$updateArr = array(
											//'usertype_id'=>$this->value('usertype_id'),
											'username' =>$this->value('username'),											
											'name_first' => $this->value('name_first'),
											'name_last' => $this->value('name_last'),
											'address' => $this->value('address'),
											'address2' => $this->value('address2'),
											'city' => $this->value('city'),
											'state' => $this->value('state'),
											'zip' => $this->value('zip'),
											'phone1' => $this->value('phone1'),
											'phone2' => $this->value('phone2'),
											'fax' => $this->value('fax'),											
											'modified' => date('Y-m-d H:i:s',time()),
											//'system_access' => $system_access,
											//'therapist_access' => $therapist_access,
											'status'=> $this->value('status')					
											);
											
						$where = " user_id = ".$id;
						
						$result = $this->update('user',$updateArr,$where);					
						
						//Check if password need to be updated or not
						if ($this->value('new_password') != '')
						{
							//update password too							
							
							$updateArr = array(
											'password'=>$this->value('new_password')										
											);
											
							$where = " user_id = ".$id;
							
							$result = $this->update('user',$updateArr,$where);	
							
						}
						
						
						//Also update the record in answer table
												
						$updateArr = array(
											'question_id' => $this->value('question_id'),
											'answer'=>$this->value('answer')										
											);
											
						$where = " user_id = ".$id;
							
						$result = $this->update('answers',$updateArr,$where);	
						
						// redirect to list of subscriber
						header("location:index.php?action=viewClinicDetails&clinic_id=$clinic_id");
						
						
					}
					else
					{
						
						$replace = $this->fillForm($this->formArray,$_POST);		
						$replace['subscriber'] = strtoupper($_POST['subscriber']);
				
						$replace['error'] = $this->error;	
						
						//Also set the question
						$selectedQuestion = ($this->value('question_id') == "")? "": $this->value('question_id');
						
						//Also the state
						$selectedState = ($this->value('state') == "")? "": $this->value('state');
						
						//Also the user type
						//$selectedUserType = ($this->value('usertype_id') == "")? "": $this->value('usertype_id');
						
						//Also the status
						$selectedStatus = ($this->value('status') == "")? "": $this->value('status');
					}
					
				}
				else 
				{
					//first time edit form, so values from table
					
					$query = "SELECT * FROM user WHERE user_id = ". $id;
					$result = $this->execute_query($query);
					
					$row = null;
					
					if ($this->num_rows($result) != 0)
					{
						$row = $this->fetch_array($result);	
					}
					
					//also get the question answer
					
					$query = "SELECT * FROM answers WHERE user_id = ".$id;
					
					$result = $this->execute_query($query);
					
					$rowAnswer = null;
					
					if ($this->num_rows($result) != 0)
					{
						$rowAnswer = $this->fetch_array($result);	
					}
					
					$selectedQuestion = $rowAnswer['question_id'];
					$selectedState = $row['state'];		
					//$selectedUserType = $row['usertype_id'];
					$selectedStatus = $row['status'];
					
					$row['answer'] = $rowAnswer['answer'];					
					
					$replace = $this->fillForm($this->formArray,$row);
					$replace['subscriber'] = strtoupper($row['name_first']." ".$row['name_last']);
					
				}			
				
				$replace['id'] = $id;	
				$replace['clinic_id'] = $clinic_id;	
				
				$stateArray = array("" => "Choose State...");
				
				$stateArray = array_merge($stateArray,$this->config['state']);
				
				$replace['stateOptions'] = 	$this->build_select_option($stateArray,$selectedState);		
				$replace['questionOptions']	= $this->build_select_option($questions,$selectedQuestion);
				//$replace['optionsUserType']	= $this->build_select_option($arrUserType,$selectedUserType);
				$replace['optionsStatus']	= $this->build_select_option($arrStatus,$selectedStatus);
						
				
				$replace['header'] = $this->build_template($this->get_template("header"));
				$replace['footer'] = $this->build_template($this->get_template("footer"));							
				//$replace['sidebar'] = $this->build_template($this->get_template("sidebar"));					
				$replace['sidebar'] = $this->sidebar();
					
				$replace['body'] = $this->build_template($this->get_template("editClinicSubscriber"),$replace);
				$replace['browser_title'] = "Tx Xchange: Edit Provider or System Admin Infomation";
				$this->output = $this->build_template($this->get_template("main"),$replace);
					
			}
			else 
			{
				header("location:index.php");
			}
			
			
		}
		
		/**
		 * validating form fields
		 *
		 * @param string $formType
		 * @param boolean $uniqueId
		 * @access public
		 */
		function validateFormClinicSubscriber($formType, $uniqueId = false)
		{
					
			// creating validation object				
			$objValidationSet = new ValidationSet();					
			
			// validating username (email address)
			$objValidationSet->addValidator(new  StringMinLengthValidator('username', 1, "Login cannot be empty",$this->value('username')));					
			$objValidationSet->addValidator(new EmailValidator('username',"Invalid Login/email address",$this->value('username')));
			
			if ($formType == "Add") 
			{
				
				$objValidationSet->addValidator(new  StringMinLengthValidator('new_password', 1, "Password cannot be empty",$this->value('new_password')));					
				
			}	
			
			// matching passwor and new password
			$arrFieldNames = array("new_password","new_password2");
			$arrFieldValues = array($_POST["new_password"],$_POST["new_password2"]);
			$objValidationSet->addValidator(new  IdenticalValuesValidator($arrFieldNames, "New password and  confirm passsword does not match",$arrFieldValues));					
			
			if ($this->value('question_id') == '')
			{
				$objValidationErr = new ValidationError('question_id',"Please select a secret question");
				$objValidationSet->addValidationError($objValidationErr);
			}
			else 
			{
				$objValidationErr = new ValidationError('question_id',"");
				$objValidationSet->addValidationError($objValidationErr);
			}			
			
			// validating answer
			$objValidationSet->addValidator(new  StringMinLengthValidator('answer', 1, "Answer cannot be empty",$this->value('answer')));		
			
			// validating first name
			$objValidationSet->addValidator(new  StringMinLengthValidator('name_first', 1, "First Name cannot be empty",$this->value('name_first')));		
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('name_first',null,"Please enter valid characters in first name",$this->value('name_first')));
			
			$objValidationSet->addValidator(new  StringMinLengthValidator('name_last', 1, "Last Name cannot be empty",$this->value('name_last')));		
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('name_last',null,"Please enter valid characters in last name",$this->value('name_last')));
			
			//$arrSpecialChars = array(" ","/","-",",","#",".","(",")",";","+","%");
			
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('address',null,"Please enter valid characters in address",$this->value('address')));
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('address2',null,"Please enter valid characters in address2",$this->value('address2')));
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('city',null,"Please enter valid characters in city",$this->value('city')));
			
			//$objValidationSet->addValidator(new  ZipValidator('zip',"Invalid zip code",$this->value('zip')));		
			
			/*if ($this->value('zip') != '')
            {
                if($this->value('clinic_country')=='CAN'){
                $objValidationSet->addValidator(new  AlphanumericOnlyValidator('zip', array(' '), "Zip code should be of alphanumeric characters only",$this->value('zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('zip', 6,7, "Zip code should be  6 to 7 alphanumeric characters only",$this->value('zip')));        
               }else{
                $objValidationSet->addValidator(new  NumericOnlyValidator('zip', null, "Zip code should be of numeric characters only",$this->value('zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('zip', 5,5, "Zip code should be  5 numeric characters only",$this->value('zip')));
            }
             }*/ 
			
			
			//$objValidationSet->addValidator(new PhoneValidator('phone1',true, false,"Invalid phone number",$this->value('phone1')));
			//$objValidationSet->addValidator(new PhoneValidator('phone2',true, false,"Invalid phone number",$this->value('phone2')));
			//$objValidationSet->addValidator(new PhoneValidator('fax',true, false,"Invalid fax number",$this->value('fax')));		
			
			
			
			if ($uniqueId === false)
			{	
				$queryEmail = "SELECT user_id FROM user WHERE username = '".$_POST['username']."' AND status <> 3";
				$result = $this->execute_query($queryEmail);
				
				//if record found that means email not unique else it is unique
				if ($this->num_rows($result) != 0)
				{
					$objValidationErr = new ValidationError('username',"Login / Email address : exists in the system. Please choose another.");
					$objValidationSet->addValidationError($objValidationErr);
				}
				
							
				//$objValidationSet->addValidator(new UniqueValueInColumnValidator('username','user','username',null,null,'Login / Email address : exists in the system. Please choose another.',$_POST['username']));
			}
			else
			{
				$queryEmail = "SELECT user_id FROM user WHERE username = '".$_POST['username']."' AND status <> 3 AND user_id <> ".$uniqueId;
				$result = $this->execute_query($queryEmail);
				
				//if record found that means email not unique else it is unique
				if ($this->num_rows($result) != 0)
				{
					$objValidationErr = new ValidationError('username',"Login / Email address : exists in the system. Please choose another.");
					$objValidationSet->addValidationError($objValidationErr);
				}
			
				//$objValidationSet->addValidator(new UniqueValueInColumnValidator('username','user','username','user_id',$uniqueId,'Login / Email address : exists in the system. Please choose another.',$_POST['username']));
			}	
			
			$objValidationSet->validate();		
			
			
			if ($formType == "Add") 
			{
				if ($this->value('usertype_id') == '')
				{
					$objValidationErr = new ValidationError('usertype_id',"Please select a user type");
					$objValidationSet->addValidationError($objValidationErr);
				}
				else 
				{
					$objValidationErr = new ValidationError('usertype_id',"");
					$objValidationSet->addValidationError($objValidationErr);
				}
			}	
			
			
			if ($this->value('usertype_id') == '3' || $this->value('usertype_id') == '2')
			{
				if ($this->value('clinic_id') == '')
				{
					$objValidationErr = new ValidationError('clinic_id',"Please choose clinic");
					$objValidationSet->addValidationError($objValidationErr);
				}				
			}
			

			if ($objValidationSet->hasErrors())
			{
				if($formType == "Add" )
				{
					$arrayFields = array("username","new_password","question_id","answer","name_first","name_last","address","address2","city","zip","usertype_id","clinic_id");
				}
				else if($formType == "Edit" )
				{
					if ($_POST['new_password'] == '')
					{
						$arrayFields = array("username","question_id","answer","name_first","name_last","address","address2","city","zip"/*,"usertype_id"*/);						
					}
					else 
					{
						$arrayFields = array("username","new_password","question_id","answer","name_first","name_last","address","address2","city","zip"/*,"usertype_id"*/);												
					}
					
				}	
				
				for($i=0;$i<count($arrayFields);++$i)
				{
					$errorMsg = $objValidationSet->getErrorByFieldName($arrayFields[$i]);
					//echo "msg : $errorMsg<br>";
					
					if ($errorMsg != "")
					{
						$this->error = $errorMsg."<br>";
						break;
					}
				}			
						
			}	
			else 
			{
				$this->error = "";	
			}
			
		}
		
		
		/**
		 * set the sort coulmn paramenters for listing of users of selected clinic.
		 *
		 * @param array $replace
		 * @param string $action
		 * @param string $queryStr
		 * @access public
		 */
		function setSortFields(&$replace,$action,$queryStr)
		{
			$sortColTblArray = $this->populateSortArray();
			
			$orderByClause = "";		
									
			foreach ($sortColTblArray as $key => $value)
			{
				$strKey = $key.'Img';
				
				if ($this->value('sort') == $key)
				{
					if($this->value('order') == "ASC")
					{
						$replace[$key] = "action=".$action.$queryStr."&sort=".$key."&order=DESC";
						
						$replace[$strKey] = '&nbsp;<img src="images/sort_asc.gif">';
					}
					else 
					{
						$replace[$key] = "action=".$action.$queryStr."&sort=".$key."&order=ASC";
						$replace[$strKey] = '&nbsp;<img src="images/sort_desc.gif">';
					}
					
					$replace['orderByClause'] = $value[$this->value('order')];
					
				}
				else
				{
					
					$replace[$key] = "action=".$action.$queryStr."&sort=".$key."&order=ASC";
					$replace[$strKey] = '';
					
				}			
				
			}					
			
			
		}
		
		
		/**
		 * Function to populate side panel of the page
		 *
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
		
		/**
		 * This function populating $form_array class variable for form editClinicInfo.php
		 *
		 * @return array
		 * @access public
		 */
		function populate_form_array()
		{
			$arr = array(
							'clinic_name' 			=> 'clinic_name',
							'clinic_address' 		=> 'address',
							'clinic_address2' 		=> 'address2',
							'clinic_city' 			=> 'city',
							'clinic_state' 			=> 'state',
							'clinic_zip' 			=> 'zip',
          			                        'clinic_phone'          	=> 'phone',
							'clinic_country'          	=> 'country',
                                                        'corporate_ehs'                 => 'corporate_ehs',
                            
						);
			return $arr;			
		}
		
		/**
		 * This function populating array for sorting the list.
		 *
		 * @return array
		 * @access public
		 */		
		function populateSortArray()
		{
			$sortColTblArray = array(
							"username" => array("ASC" => " username ASC ", "DESC" => " username DESC "),
							"full_name" => array("ASC" => " name_first ASC, name_last ASC ","DESC" =>" name_first DESC, name_last DESC "),
							"usertype_id" => array("ASC" => " usertype_id ASC, therapist_access ASC, admin_access ASC ", "DESC" => " usertype_id DESC, therapist_access DESC, admin_access DESC "),
							"status" => array("ASC" => " status ASC ", "DESC" => " status DESC "),							
							"last_login" => array("ASC" => " last_login ASC", "DESC" => " last_login DESC"),
							"creation_date" => array("ASC" => " creation_date ASC", "DESC" => " creation_date DESC")
			
						);		
						
			return $sortColTblArray;			
		}	
	
	}
	// creating object of this class.
	$obj = new clinic();
?>
