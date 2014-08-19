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
	require_once("include/paging/my_pagina_class.php");	
	require_once("module/application.class.php");
	require_once("include/validation/_includes/classes/validation/ValidationSet.php");
	require_once("include/validation/_includes/classes/validation/ValidationError.php");	
	
	// class declaration
  	class sendinvite extends application{
  		
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
			for example at first the action is "home" then after submit say action is submit
			so if login is explicitly called we have the home action set (which is also our default action)
			else whatever action is it is set in $str.				
			*/
				$str = $this->value('action');
			}else{				
				$str = "home"; //default if no action is specified
			}

			$this->action = $str;
			$str = $str."()";
			eval("\$this->$str;"); 
			$this->display();			
		}

		
		/**
		 * Function to send ivitation form paitent to provider 
		 *
		 * @access public
		 */
		function sendinvite()
		{
			$userInfo = $this->userInfo();
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else 
			{	
			$replace = array();
			$stateArray = array("" => "Choose Provider...");
			$query="select * from practitioner where   status =1";
			$result=$this->execute_query($query);
			while($row=$this->fetch_array($result)){
				$stateArray[$row['practitioner_id']]=$row['name'];
			}
			$replace['PractitionerOptions'] = 	$this->build_select_option($stateArray, $replace['practitiner_type']);
			$replace['provider_email']=$this->value('provider_email');
			$replace['name_first']=$this->value('name_first');
			$replace['name_last']=$this->value('name_last');

			$replace['personalmessage']=$this->value('personal_message');
            //$replace['dark']='dark';
            //$replace['light']='light';
            
			$replace['body'] = $this->build_template($this->get_template("sendinvite"),$replace);
			$this->output = $this->build_template($this->get_template("main"),$replace);
			}
		}
		
		/**
		 * Function to send ivitation form paitent to provider on submit 
		 *
		 * @access public
		 */
		function sendinvite_send(){
		$this->formArray = $formArray;
        //print_r($_REQUEST);exit;
        	
		if (isset($_POST['Submit']) && $_POST['Submit'] == 'send invite')
				{
				//echo '<pre>';
				//print_r($_POST);
				$userInfo = $this->userInfo();
				//print_r($userInfo);
				//form submitted check for validation
					$this->validateFormInvite(false);					
					if($this->error == "")
					{
						//Form validated, no errors
						//go ahead store the values in db
						
						$insertArr = array(
											'user_id'           =>  $userInfo['user_id'],
											'email'             =>  $this->value('provider_email'),
											'first_name'        =>	$this->value('name_first'),
											'last_name'         =>	$this->value('name_last'),
											'personal_message'  =>	$this->value('personal_message'),
											'provider'          =>	$this->value('practitiner_type'),
											'datefosend'        =>	date('Y-m-d H:i:s',time()),
											'status'            =>  1,
                                            'user_type'         =>  $userInfo['usertype_id']
											);
												
						$result = $this->insert('sendinvite',$insertArr);
						
						//send mail that user created successfully
						{
							//have the HTML content
                            $userid=$userInfo['user_id'];
							$query = "select clinic_id from clinic_user where user_id = '{$userid}'";
				            $result = @mysql_query($query);
				            $row_clinic = @mysql_fetch_array($result);
				            $clinic_id = $row_clinic['clinic_id'];
                            $query1="select clinic_name from clinic where clinic_id= '{$clinic_id}'";
                            $result1 = @mysql_query($query1);
				            $row_clinic_name = @mysql_fetch_array($result1);
                            
                            $fullNamePatient        = $userInfo['name_first']." ".$userInfo['name_last'];
                            $fullName               = $this->value('name_first')." ".$this->value('name_last');
							$replace['username']    = $this->value('email');
							$replace['url']         = $this->config['url'];
							$replace['images_url']  = $this->config['images_url'];
							$replace['password']    = $password;
							
							
							$clinic_type = 'plpto';
							$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
							
                            if($clinic_channel==1){
                                $business_url=$this->config['business_tx']; 
                                $support_email=$this->config['email_tx'];
                            }else{
                                $business_url=$this->config['business_wx']; 
                                $support_email=$this->config['email_wx'];   
                            }
                            $replace1['business_url'] = $business_url;
                            $replace1['support_email'] = $support_email;
							//mail content for jonathon 
							$sqlpractitioner                =   "select * from practitioner where  practitioner_id = ".$this->value('practitiner_type');
							$resultpractitioner             =   $this->execute_query($sqlpractitioner);
							$rowpractitioner                =   $this->fetch_array($resultpractitioner);
							$replace1['url']                =   $this->config['url'];
							$replace1['images_url']         =   $this->config['images_url'];
							$replace1['name_first']         =   $this->value('name_first');
							$replace1['name_last']          =   $this->value('name_last');
							$replace1['provider_email']     =   $this->value('provider_email');
							$replace1['clinicname']         =   $row_clinic_name['clinic_name'];
                            $replace1['name']               =   $fullNamePatient;
                            $replace1['practitioner_type']  =   $rowpractitioner['name'];
                            $replace1['personalmessage']    =   $this->value('personal_message');
							$subject                        =   "Invite from a Patient/Client of Yours";
							$to                             =   $fullName.'<'.$this->value('provider_email').'>';
							
                            if( $clinic_channel == 1)
							 $message                        =   $this->build_template($this->get_template("sendinviteContent"),$replace1);
							else
							 $message                        =   $this->build_template($this->get_template("sendinviteContentwx"),$replace1);
							// To send HTML mail, the Content-type header must be set
							$headers  = 'MIME-Version: 1.0' . "\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
							
							// Additional headers
							//$headers .= 'To: '.$fullName.'<'.$_POST['email_address'].'>'. "\n";
							
							//$headers .= 'From: '.$this->config['from_email_address'] . " \n";
							$headers .= 'From: do-not-reply@txxchange.com' . "\n";
							//$headers .= 'Cc: example@example.com' . "\n";
							//$headers .= 'Bcc: example@example.com' . "\n";
							
							$returnpath = '-fdo-not-reply@txxchange.com';
                            // Mail it	
                            //echo $subject;
							//echo $message;exit;
							@mail($to, $subject, $message, $headers, $returnpath);	
							header("location:index.php?action=sendinvite_send_thanks");         
						}
						
						
						// redirect to list of subscriber
						//header("location:index.php?action=subscriberListing");
						
						
					}
					else
					{
						$replace = $this->fillForm($this->formArray,$_POST);						
						$replace['error'] = $this->error;	
						
			$query="select * from practitioner where   status =1";
            $result=$this->execute_query($query);
            while($row=$this->fetch_array($result)){
                $stateArray[$row['practitioner_id']]=$row['name'];
            }
            $replace['PractitionerOptions'] =     $this->build_select_option($stateArray, $replace['practitiner_type']);
            $replace['provider_email']=$this->value('provider_email');
            $replace['name_first']=$this->value('name_first');
            $replace['name_last']=$this->value('name_last');

            $replace['personalmessage']=$this->value('personal_message');

            $replace['body'] = $this->build_template($this->get_template("sendinvite"),$replace);
            $this->output = $this->build_template($this->get_template("main"),$replace);
					}
					
				}
		
		}
		
  	function validateFormInvite($uniqueId)
		{
			// creating validation object		
			$objValidationSet = new ValidationSet();					
			
			// validating username (email address)
			$objValidationSet->addValidator(new  StringMinLengthValidator('provider_email', 1, "Provider email cannot be empty",$this->value('provider_email')));					
			$objValidationSet->addValidator(new EmailValidator('provider_email',"Invalid Provider email address",$this->value('provider_email')));
			// validating first name field
			$objValidationSet->addValidator(new  StringMinLengthValidator('name_first', 1, "First Name cannot be empty",$this->value('name_first')));		
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('name_first',null,"Please enter valid characters in first name",$this->value('name_first')));
			
			// validating last name field
			$objValidationSet->addValidator(new  StringMinLengthValidator('name_last', 1, "Last Name cannot be empty",$this->value('name_last')));		
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('name_last',null,"Please enter valid characters in last name",$this->value('name_last')));
			$objValidationSet->addValidator(new  StringMinLengthValidator('personal_message', 1, "Personal Message cannot be empty",$this->value('personal_message')));		
			//$objValidationSet->addValidator(new AlphanumericOnlyValidator('personal_message',null,"Please enter valid characters in Personal Message",$this->value('personal_message')));
						
			if ($uniqueId === false)
			{	
				$queryEmail = "SELECT user_id FROM user WHERE username = '".$_POST['provider_email']."' AND status <> 3";
				$result = $this->execute_query($queryEmail);
				
				//if record found that means email not unique else it is unique
				if ($this->num_rows($result) != 0)
				{
					$objValidationErr = new ValidationError('provider_email',"Provider Email address : exists in the system. Please choose another.");
					$objValidationSet->addValidationError($objValidationErr);
				}
				
			}
					
			
			$objValidationSet->validate();		

			if ($this->value('practitiner_type') == '')
				{
					
					$objValidationErr = new ValidationError('practitiner_type',"Please choose Provider type");
					$objValidationSet->addValidationError($objValidationErr);
				}				
			
			$formType='Add';
			if ($objValidationSet->hasErrors())
			{
				if($formType == "Add" )
				{
					//$arrayFields = array("username","new_password","question_id","answer","name_first","name_last","address","address2","city","zip","usertype_id","clinic_id");
					$arrayFields = array("provider_email","name_first","name_last","practitiner_type","personal_message");
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
         * This function populating thanks page
         *
         * @return array
         * @access public
         */
          function sendinvite_send_thanks(){
             $replace['body'] = $this->build_template($this->get_template("thankssendinvite"),$replace);
             $this->output = $this->build_template($this->get_template("main"),$replace);
          
          
          }

		  /**
		 * Function to send referral invitation from paitent to individuals friends. 
		 *
		 * @access public
		 */
		function sendreferral()
		{
		$send=$this->value('val');
        
            $userInfo = $this->userInfo();
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else 
			{	
			$replace = array();
            $replace['close']='';
			$replace['email']=$this->value('email');
			$replace['name_first']=$this->value('name_first');
			$replace['name_last']=$this->value('name_last');
            if($send=='send')
            {
                //$replace['close']='$(document).ready(function() { closeWindow();})';
                  $replace['close']='window.onload=closeWindow;'; 
            }else{
               $replace['close']=''; 
            }
			$replace['personalmessage']=$this->value('personal_message');
            $replace['body'] = $this->build_template($this->get_template("sendreferral"),$replace);
			$this->output = $this->build_template($this->get_template("main"),$replace);
			}
		}
         
		/**
		 * Function to send referral ivitation form paitent to friends on submit 
		 *
		 * @access public
		 */
		function sendreferral_send(){
		$this->formArray = $formArray;
        //print_r($_REQUEST);exit;
        
        	
		if (isset($_POST['Submit']) && $_POST['Submit'] == 'send referral')
		{
				$userInfo = $this->userInfo();
				//checking if patient has reached the referral sending limit or not
				if(!$this->referral_sent_count())
				{ $replace['error'] = "<br />You have crossed the referral sending limit of this month.";
				  $replace['email']=$this->value('email');			            $replace['name_first']=$this->value('name_first');
				  $replace['name_last']=$this->value('name_last');
				  $replace['personalmessage']=$this->value('personal_message');	
				}
				else{
				//form submitted check for validation
					$this->validateFormReferral(false,$userInfo['user_id']);					
					if($this->error == "")
					{
						//Form validated, no errors
						//go ahead store the values in db
						
						$insertArr = array(
											'user_id'           =>  $userInfo['user_id'],
											'clinic_id'         =>	$this->clinicInfo('clinic_id'),
											'recipient_email'   =>  $this->value('email'),
											'name_first'        =>	$this->value('name_first'),
											'name_last'         =>	$this->value('name_last'),
											);
												
						   $result = $this->insert('patients_refferals',$insertArr);
						
						   //send mail to referral recipient
						
							//have the HTML content
                            $userid=$userInfo['user_id'];
							$clinic_id = $this->clinicInfo('clinic_id');
                            $query="SELECT u.username,AES_DECRYPT( UNHEX(u.name_first),'{$this->config['private_key']}' ) AS name_first,AES_DECRYPT( UNHEX(u.name_last),'{$this->config['private_key']}' ) AS name_last  FROM `clinic_user` cu, user u WHERE cu.user_id = u.user_id AND cu.`clinic_id` ='{$clinic_id}' and u.status=1 ORDER BY cu.`clinic_user_id` ASC LIMIT 1 ";							$result=@mysql_query($query);
							$res=@mysql_fetch_object($result);
							$provider_email=$res->username;
							$provider_name=$res->name_first." ".$res->name_last;
							$query1="select * from clinic where clinic_id= '{$clinic_id}'";
                            $result1 = @mysql_query($query1);
				            $row_clinic                     =   @mysql_fetch_array($result1);
                            $fullNamePatient                =   html_entity_decode($userInfo['name_first']." ".$userInfo['name_last'], ENT_QUOTES, "UTF-8");
                            $fullNameRecipient              =   html_entity_decode($this->value('name_first')." ".$this->value('name_last'), ENT_QUOTES, "UTF-8");
							$replace1['url']                =   $this->config['url'];
							$replace1['images_url']         =   $this->config['images_url'];
							$replace1['fullNamePatient']	=	$fullNamePatient;
							$replace1['fullNameRecipient']	=	$fullNameRecipient;
							$replace1['name_first']         =   $this->value('name_first');
							$replace1['name_last']          =   $this->value('name_last');
							$replace1['email']				=   $this->value('email');
							$replace1['clinicname']         =   $row_clinic['clinic_name'];
                            $replace1['clinic_website_address']	=   $row_clinic['clinic_website_address'];
                            if($row_clinic['clinic_logo']!='')
							$replace1['clinic_logo']		=   '<img src='.$this->config['images_url'].'/asset/images/clinic_logo/'.$row_clinic['clinic_logo'].'>';
							else
                            $replace1['clinic_logo']		=   '';
                            $replace1['clinic_phone']		=   $row_clinic['phone'];
							$replace1['clinic_email']		=   $provider_email;
							$replace1['clinic_address']		=   $row_clinic['address'];
							$replace1['clinic_address2']	=   $row_clinic['address2'];
							$replace1['city']				=   $row_clinic['city'];
							$replace1['state']				=   $row_clinic['state'];
							$replace1['zip']				=   $row_clinic['zip'];
							$replace1['clinic_overview']	=   nl2br($row_clinic['clinic_overview']);
							$replace1['provider_name']      =   $provider_name;
                            $replace1['personalmessage']    =   $this->value('personal_message');
							$subject                        =   "Recommendation from ".$fullNamePatient;
							$to                             =   $fullNameRecipient.'<'.$this->value('email').'>';
							$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
                            if( $clinic_channel == 1)
							$message                        =   $this->build_template($this->get_template("sendreferralContent"),$replace1);
							else
							$message                        =   $this->build_template($this->get_template("sendreferralContentwx"),$replace1);
							// To send HTML mail, the Content-type header must be set
							$headers  = 'MIME-Version: 1.0' . "\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
							$headers .= 'From: '.$this->setmailheader($fullNamePatient).'<do-not-reply@txxchange.com>'. "\n";
							//$headers .= 'Bcc: neelabh.tyagi@gmail.com' . "\n";
							$returnpath = '-fdo-not-reply@txxchange.com';
                            // Mail it	
                            //echo $headers;exit;
							//echo $message;exit;
							@mail($to, $subject, $message, $headers, $returnpath);	
							header("location:index.php?action=sendreferral&val=send");       
						
					}
					else
					{
						$replace = $this->fillForm($this->formArray,$_POST);						
						$replace['error'] = "<br />".$this->error;	
						$replace['name_first']=$this->value('name_first');
						$replace['name_last']=$this->value('name_last');
						$replace['email']=$this->value('email');
                         $replace['close']='';
			            $replace['personalmessage']=$this->value('personal_message');
            		}
				}
				$replace['body'] = $this->build_template($this->get_template("sendreferral"),$replace);
				$this->output = $this->build_template($this->get_template("main"),$replace);
			}
		
		}	//Function ends here

		/**
		 * Function to send referral ivitation form paitent to friends on submit 
		 *
		 * @access: public
		 * @param: bool $uniqueId, int user_id
		 * @return string
		 */
  		function validateFormReferral($uniqueId,$user_id)
		{
			// creating validation object		
			$objValidationSet = new ValidationSet();					
			
			// validating first name field
			$objValidationSet->addValidator(new  StringMinLengthValidator('name_first', 1, "First Name cannot be empty",$this->value('name_first')));		
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('name_first',null,"Please enter valid characters in first name",$this->value('name_first')));
			
			// validating last name field
			$objValidationSet->addValidator(new  StringMinLengthValidator('name_last', 1, "Last Name cannot be empty",$this->value('name_last')));		
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('name_last',null,"Please enter valid characters in last name",$this->value('name_last')));

			// validating username (email address)
			$objValidationSet->addValidator(new  StringMinLengthValidator('email', 1, "email cannot be empty",$this->value('email')));					
			$objValidationSet->addValidator(new EmailValidator('email',"Invalid email address",$this->value('email')));
			
			$objValidationSet->addValidator(new  StringMinLengthValidator('personal_message', 1, "Personal Message cannot be empty",$this->value('personal_message')));		
			//$objValidationSet->addValidator(new AlphanumericOnlyValidator('personal_message',null,"Please enter valid characters in Personal Message",$this->value('personal_message')));
						
			if ($uniqueId === false)
			{	
				$queryEmail = "SELECT id FROM patients_refferals WHERE recipient_email = '".$_POST['email']."' AND user_id = ".$user_id;
				$result = $this->execute_query($queryEmail);
				
				//if record found that means email not unique else it is unique
				if ($this->num_rows($result) != 0)
				{
					$objValidationErr = new ValidationError('email',"You've sent a referral to this person previously. Thank you.");
					$objValidationSet->addValidationError($objValidationErr);
				}
				
			}

		/*	if ($uniqueId === false)
			{	
				$queryEmail = "SELECT user_id FROM user WHERE username = '".$_POST['email']."' AND status <> 3";
				$result = $this->execute_query($queryEmail);
				
				//if record found that means email not unique else it is unique
				if ($this->num_rows($result) != 0)
				{
					$objValidationErr = new ValidationError('email',"Recipient Email address : exists in the system. Please choose another.");
					$objValidationSet->addValidationError($objValidationErr);
				}
				
			}
					
		*/	
			$objValidationSet->validate();		

			$formType='Add';
			if ($objValidationSet->hasErrors())
			{
				if($formType == "Add" )
				{
					//$arrayFields = array("username","new_password","question_id","answer","name_first","name_last","address","address2","city","zip","usertype_id","clinic_id");
					$arrayFields = array("name_first","name_last","email","personal_message");
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
         * This function populating thanks page for patient referral success
         *
         * @return array
         * @access public
         */
          function sendreferral_send_thanks(){
             $replace['body'] = $this->build_template($this->get_template("thankssendreferral"),$replace);
             $this->output = $this->build_template($this->get_template("main"),$replace);
          
          
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
                            'provider_email'    => 'provider_email',
                            'name_first'        => 'name_first',
                            'name_last'         => 'name_last',
                            'personal_message'  => 'personal_message',
                            'practitiner_type'  => 'practitiner_type'                          
                        );
            return $arr;            
        }
		/**
		 * Function to send ivitation form paitent to provider 
		 *
		 * @access public
		 */
		function sendinvite_provider()
		{
			$userInfo = $this->userInfo();
			//echo '<pre>';
            //print_r($userInfo);
            
            if (!$userInfo)
			{
				header("location:index.php");
			}
			else 
			{	
			$replace = array();
			$stateArray = array("" => "Choose Provider...");
			$query="select * from practitioner where   status =1";
			$result=$this->execute_query($query);
			while($row=$this->fetch_array($result)){
				$stateArray[$row['practitioner_id']]=$row['name'];
			}
			$replace['PractitionerOptions'] = 	$this->build_select_option($stateArray, $replace['practitiner_type']);
			$replace['provider_email']=$this->value('provider_email');
			$replace['name_first']=$this->value('name_first');
			$replace['name_last']=$this->value('name_last');

			$replace['personalmessage']=$this->value('personalmessage');
            //$replace['dark']='dark';
            //$replace['light']='light';
            
			$replace['body'] = $this->build_template($this->get_template("sendinvite_provider"),$replace);
			$this->output = $this->build_template($this->get_template("main"),$replace);
			}
		}
		
        /**
		 * Function to send ivitation form provider to provider on submit 
		 *
		 * @access public
		 */
		function send_invite_provider_to_provider(){
		$this->formArray = $formArray;
       // print_r($_REQUEST);
        	
		if (isset($_POST['Submit']) && $_POST['Submit'] == 'send invite')
				{
				//echo '<pre>';
				//print_r($_POST);
				$userInfo = $this->userInfo();
				//print_r($userInfo);
				//form submitted check for validation
					$this->validateFormInvite(false);					
					if($this->error == "")
					{
						//Form validated, no errors
						//go ahead store the values in db
						
						$insertArr = array(
											'user_id'           =>  $userInfo['user_id'],
											'email'             =>  $this->value('provider_email'),
											'first_name'        =>	$this->value('name_first'),
											'last_name'         =>	$this->value('name_last'),
											'personal_message'  =>	$this->value('personal_message'),
											'provider'          =>	$this->value('practitiner_type'),
											'datefosend'        =>	date('Y-m-d H:i:s',time()),
											'status'            =>  1,
                                            'user_type'         =>  $userInfo['usertype_id']
											);
												
						$result = $this->insert('sendinvite',$insertArr);
						
						//send mail that user created successfully
						{
							//have the HTML content
                            $userid=$userInfo['user_id'];
							$query = "select clinic_id from clinic_user where user_id = '{$userid}'";
				            $result = @mysql_query($query);
				            $row_clinic = @mysql_fetch_array($result);
				            $clinic_id = $row_clinic['clinic_id'];
                            $query_clinic="select clinic_name from clinic where clinic_id= '{$clinic_id}'";
                            $result_clinic = @mysql_query($query_clinic);
				            $row_clinic_name = @mysql_fetch_array($result_clinic);
                            $fullNameProvider       = html_entity_decode($userInfo['name_first']." ".$userInfo['name_last'], ENT_QUOTES, "UTF-8");
                            $fullName               = html_entity_decode($this->value('name_first')." ".$this->value('name_last'), ENT_QUOTES, "UTF-8");
							$replace['username']    = $this->value('email');
							$replace['url']         = $this->config['url'];
							$replace['images_url']  = $this->config['images_url'];
							$replace['password']    = $password;
							
							
							$clinic_type = 'plpto';
							
							//mail content for jonathon
							$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
							
                            if($clinic_channel==1){
                                $business_url=$this->config['business_tx']; 
                                $support_email=$this->config['email_tx'];
                            }else{
                                $business_url=$this->config['business_wx']; 
                                $support_email=$this->config['email_wx'];   
                            }
                            $replace1['business_url'] = $business_url;
                            $replace1['support_email'] = $support_email; 
							$sqlpractitioner                =   "select * from practitioner where  practitioner_id = ".$this->value('practitiner_type');
							$resultpractitioner             =   $this->execute_query($sqlpractitioner);
							$rowpractitioner                =   $this->fetch_array($resultpractitioner);
							$replace1['url']                =   $this->config['url'];
							$replace1['images_url']         =   $this->config['images_url'];
							$replace1['name_first']         =   $this->value('name_first');
							$replace1['name_last']          =   $this->value('name_last');
							$replace1['provider_email']     =   $this->value('provider_email');
							$replace1['clinicname']         =   $row_clinic_name['clinic_name'];
                            $replace1['name']               =   $fullNameProvider;
                            $replace1['practitioner_type']  =   $rowpractitioner['name'];
                            $replace1['personalmessage']    =   $this->value('personal_message');
							$subject                        =   $fullNameProvider." invites you to check out Tx Xchange";
							$to                             =   $fullName.'<'.$this->value('provider_email').'>';
							
                            if( $clinic_channel == 1)
							$message                        =   
							$this->build_template($this->get_template("sendinviteContentProviderToProvider"),$replace1);
							else
							$message                        =   
                            $this->build_template($this->get_template("sendinviteContentProviderToProviderwx"),$replace1);
                            
							// To send HTML mail, the Content-type header must be set
							$headers  = 'MIME-Version: 1.0' . "\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
							
							// Additional headers
							//$headers .= 'To: '.$fullName.'<'.$_POST['email_address'].'>'. "\n";
							
							//$headers .= 'From: '.$this->config['from_email_address'] . " \n";
							$headers .= 'From: Tx Xchange <do-not-reply@txxchange.com>' . "\n";
							//$headers .= 'Cc: example@example.com' . "\n";
							//$headers .= 'Bcc: example@example.com' . "\n";
							
							$returnpath = '-fdo-not-reply@txxchange.com';
                            // Mail it	
                            //echo $subject;
							//echo $message;exit;
							@mail($to, $subject, $message, $headers, $returnpath);	
							header("location:index.php?action=send_invite_by_provider_to_provider_thanks");         
						}
						
						
						// redirect to list of subscriber
						//header("location:index.php?action=subscriberListing");
						
						
					}
					else
					{
						$replace = $this->fillForm($this->formArray,$_POST);						
						$replace['error'] = $this->error;	
						
			$query="select * from practitioner where   status =1";
            $result=$this->execute_query($query);
            while($row=$this->fetch_array($result)){
                $stateArray[$row['practitioner_id']]=$row['name'];
            }
            $replace['PractitionerOptions'] =     $this->build_select_option($stateArray, $replace['practitiner_type']);
            $replace['provider_email']=$this->value('provider_email');
            $replace['name_first']=$this->value('name_first');
            $replace['name_last']=$this->value('name_last');

            $replace['personalmessage']=$this->value('personal_message');

            $replace['body'] = $this->build_template($this->get_template("sendinvite"),$replace);
            $this->output = $this->build_template($this->get_template("main"),$replace);
					}
					
				}
		
		}
        
		 /**
         * This function populating thanks page
         *
         * @return array
         * @access public
         */
          function send_invite_by_provider_to_provider_thanks(){
             $replace['body'] = $this->build_template($this->get_template("thankssendinvite"),$replace);
             $this->output = $this->build_template($this->get_template("main"),$replace);
          
          
          }
		

		 /**
		 * Function to display sytem admin notifications to users
		 *
		 * @access public
		 */
		function read_notification()
		{
			$str="";
			$privateKey = $this->config['private_key'];	
			$query="select AES_DECRYPT(UNHEX(message),'{$privateKey}') as message,posted_date  from message_notifications where id IN (".$_GET['ids'].") ORDER BY posted_date DESC";
				$result=$this->execute_query($query);
				while($row=$this->fetch_object($result)){
					$str.="<tr><td align='right'><b>Message Posted: ".$this->formatDateExtra($row->posted_date, $this->userInfo('timezone'))."</b></td></tr><tr><td>".$this->hyperlink(nl2br(str_replace("&amp;","&",$row->message)))."</td></tr>";
			}

			$replace['personalmessage']=$str;
            $replace['body'] = $this->build_template($this->get_template("read_notification"),$replace);
			$this->output = $this->build_template($this->get_template("main"),$replace);

			//Now deleting the messages as these are one time viewing only.
			$query = "DELETE FROM message_notifications_users where  user_id=".$this->userInfo('user_id');
			@mysql_query($query);

			
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
		
		
	}
	// creating object of this class.
	$obj = new sendinvite();
?>
