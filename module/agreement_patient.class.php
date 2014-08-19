<?php


	/**
	 * 
	 * Copyright (c) 2008 Tx Xchange.
	 * 
	 * This class check whether Patient accept privacy policy and agreement or not.
	 * Check for Patients only.
	 * 
	 * // necessary classes 
	 * require_once("module/application.class.php");
	 * 
	 * 
	 * // validation classes
	 * require_once("include/validation/_includes/classes/validation/ValidationSet.php");
	 * require_once("include/validation/_includes/classes/validation/ValidationError.php");
	 * 
	 */
	
	
	// including files
	require_once("module/application.class.php");
	require_once("include/validation/_includes/classes/validation/ValidationSet.php");
	require_once("include/validation/_includes/classes/validation/ValidationError.php");

	
	// class declaration starts
  	class agreement_patient extends application{

  		
  	// class variable declaration section
  	
  		/**
  		 * The variable defines the action request
  		 *
  		 * @var string
  		 * @access private
  		 */  		
		private $action;

		
		/**
		 * The variable defines all the fields present in the form
		 *
		 * @var string Array
		 * @access private
		 */		
		private $field_array;

		
		/**
		 * The variable defines the error message(if any) against the action request
		 * It could be an array if more than one error messages are there else a simple variable
		 *
		 * @var string
		 * @access private
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

			if($this->value('action')){				

				$str = $this->value('action');

			}else{

				$str = "agreement_patient"; //default if no action is specified

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

			/*
			if($this->userAccess($str)){
				$str = $str."()";
				if ($this->userInfo('agreement') == 1)
				{
					header("location:index.php?action=therapist");
				}
				eval("\$this->$str;"); 
			}
			else{
				$this->output = $this->config['error_message'];
			} 
			*/
			
			$str = $str."()";
			if ($this->userInfo('agreement') == 1)
			{
                // Check the Patient associated clinic subscription status
                $this->checkSubscriptionehealth(); 
				header("location:index.php?action=patient");
			}
			eval("\$this->$str;"); 
				
			$this->display();

		}

		
		/**
		 * Function to accept the Terms of Services & Privacy Policy and email promotion
		 * 
		 * @access public
		 */
		function agreement_patient(){ 

			if ($this->userInfo('agreement') == 1)
			{
                // Check the Patient associated clinic subscription status   
                $this->checkSubscriptionehealth();
                
                
                
                            $array=  explode('.', $_SERVER['HTTP_HOST']) ;
                            if (in_array($this->config['domain'], $array)) {
                                  header("Location:index.php?action=patientdashboard");
                                  die;
                              }
				header("location:index.php?action=patient");
			}
			
			
			$userInfo = $this->userInfo();			
			
			$replace['firstName'] = $userInfo['name_first'];
			$replace['lastName'] = $userInfo['name_last'];
			$replace['email'] = $userInfo['username'];
			$replace['id'] = $userInfo['user_id'];
		

			$ret = "";
			
			if(!empty($userInfo['address'])) $ret .= $userInfo['address'] . '<br />';
			
			if(!empty($userInfo['address2'])) $ret .= $userInfo['address2'] . '<br />';
			
			if(!empty($ret))
			{
			
				if (!empty($userInfo['city']) && !empty($userInfo['state']) && !empty($userInfo['zip']))
				{
					$ret .= $userInfo['city'] . ', '. $userInfo['state'] . ' ' .$userInfo['zip'].'<br />';									
	
				}
				else if (!empty($userInfo['city']) && !empty($userInfo['state']))
				{
					$ret .= $userInfo['city'] .' ' .$userInfo['state'].'<br />';
				}
				else if (!empty($userInfo['city']) && !empty($userInfo['zip']))
				{
					$ret .= $userInfo['city'] .' ' .$userInfo['zip'].'<br />';
				}
				else if (!empty($userInfo['state']) && !empty($userInfo['zip'])) 
				{
					$ret .= $userInfo['state'] .' ' .$userInfo['zip'].'<br />';	
				}
				else if (!empty($userInfo['city']))
				{
					$ret .= $userInfo['city'].'<br />';
				}
				else if (!empty($userInfo['state']))
				{
					$ret .= $userInfo['state'].'<br />';
				}
				else if (!empty($userInfo['zip']))
				{
					$ret .= $userInfo['zip'].'<br />';
				}
				$ret .= (empty($userInfo['phone1'])) ? "" :"Phone 1:".$userInfo['phone1']."<br/>";
				$ret .= (empty($userInfo['phone2'])) ? "" :"Phone 2:".$userInfo['phone2']."<br/>";			
				$ret .= (empty($userInfo['fax'])) ? "" :"Fax:".$userInfo['fax'];
			}
			else 
			{
				$ret = "No Address Information";
			}
			
			$replace['address'] = $ret;

			$replace['new_password'] = "";		
			$replace['new_password2'] = "";
			$replace['agree_terms'] = "";
			$replace['email_promotions'] = "";
			$replace['answer'] = "";
			
			//Questions from question table
			$questions = array(""=>"-- Select Security Question--");
			
			$query = "SELECT * FROM questions where  question_id not in(3,7)";
			$result = $this->execute_query($query);
			
			
			if($this->num_rows($result)!= 0)
			{
				while($row = $this->fetch_array($result))
				{	
					$questions[$row['question_id']] = $row['question'];
				}
				
			}	
			
			$selectedQuestion = "";
			$replace['questionOptions']	= $this->build_select_option($questions,$selectedQuestion);
			
			
			$replace['header'] = $this->build_template($this->get_template("header"));
			
			
            $patient_id = $this->userInfo('user_id');
			$clinic_type = $this->getUserClinicType($patient_id);
			$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
            if( $clinic_type == 'plpto'){
            	if($clinic_channel==2){
            		$replace['terms_services'] = $this->build_template($this->get_template('consumer_wx')); 
            	 	$replace['privacy_policy'] = $this->build_template($this->get_template('privacy_policy_wx'));
            	 	$replace['channel'] = 'Wholemedx';
            	}else{
            		$replace['terms_services'] = $this->build_template($this->get_template('consumer')); 
            	 	$replace['privacy_policy'] = $this->build_template($this->get_template('privacy_policy'));
            	 	$replace['channel'] ='Tx Xchange';
            	}  
	            $replace['body'] = $this->build_template($this->get_template("agreement_patient"),$replace);
            }
            
			$replace['browser_title'] = "Tx Xchange: Patient Home";
			$this->output = $this->build_template($this->get_template("main"),$replace);

		}		

		/**
		 * Function to validate fields on Terms of Services & Privacy Policy page.
		 * 
		 * @access public
		 */
		function submitPatientAgreement()
		{
			//echo "asdfasd"; exit;
			$id = (int) $this->value('id');	
			
			$replace = array();
			
			$userInfo = $this->userInfo();				
			
			
			if ($userInfo['agreement'] != 1)
			{	
										
				$userInfo = $this->userInfo();			
				
				$replace['firstName'] = $userInfo['name_first'];
				$replace['lastName'] = $userInfo['name_last'];
				$replace['email'] = $userInfo['username'];
				$replace['logoname'] = 'TxXchange_LogoTag.jpg';
				
				
				$ret = "";
				
				if(!empty($userInfo['address'])) $ret .= $userInfo['address'] . '<br />';
				
				if(!empty($userInfo['address2'])) $ret .= $userInfo['address2'] . '<br />';
				
				if(!empty($ret))
				{
				
					if (!empty($userInfo['city']) && !empty($userInfo['state']) && !empty($userInfo['zip']))
					{
						$ret .= $userInfo['city'] . ', '. $userInfo['state'] . ' ' .$userInfo['zip'].'<br />';									
		
					}
					else if (!empty($userInfo['city']) && !empty($userInfo['state']))
					{
						$ret .= $userInfo['city'] .' ' .$userInfo['state'].'<br />';
					}
					else if (!empty($userInfo['city']) && !empty($userInfo['zip']))
					{
						$ret .= $userInfo['city'] .' ' .$userInfo['zip'].'<br />';
					}
					else if (!empty($userInfo['state']) && !empty($userInfo['zip'])) 
					{
						$ret .= $userInfo['state'] .' ' .$userInfo['zip'].'<br />';	
					}
					else if (!empty($userInfo['city']))
					{
						$ret .= $userInfo['city'].'<br />';
					}
					else if (!empty($userInfo['state']))
					{
						$ret .= $userInfo['state'].'<br />';
					}
					else if (!empty($userInfo['zip']))
					{
						$ret .= $userInfo['zip'].'<br />';
					}
				}
				else 
				{
					$ret = "No Address Information";
				}
				
				$replace['address'] = $ret;
				
				//Questions from question table
				$questions = array(""=>"-- Select Security Question--");
				
				$query = "SELECT * FROM questions where  question_id not in (3,7)"; 
				$result = $this->execute_query($query);
				
				
				if($this->num_rows($result)!= 0)
				{
					while($row = $this->fetch_array($result))
					{	
						$questions[$row['question_id']] = $row['question'];
					}
					
				}	
				
		
				//form submitted check for validation
				
				$this->validateAgreement();											
			
				if($this->error == "")
				{
					//Form validated, no errors
					//go ahead store the values in db											
				
					$agreement = 0;
					$email_promotions = 0; 
					
					
					if (isset($_POST['agree_terms'])) 
					{
						$agreement = 1;	
					}
					
					if (isset($_POST['email_promotions'])) 
					{
						$email_promotions = 1;	
					}
					
					$updateArr = array(
										'password'=>$this->value('new_password'),
										'agreement'=>$agreement,
										'email_promotions'=>$email_promotions,
										'agreement_date' => date('Y-m-d H:i:s',time())	
										);
								
									
					$where = " user_id = ".$id;
						
					$result = $this->update('user',$updateArr,$where);	

					//Added Feb 5, 2008 Now check for the secret question answer record exist in answer table or not
					//if yes then update else insert the record in answer table	

					$queryAnswers = "SELECT * FROM answers WHERE user_id = '".$id."'";
					
					$resultAnswers = $this->execute_query($queryAnswers);		
			
					if($this->num_rows($resultAnswers)!= 0)
					{					
					
						$updateArr = array(
											'question_id' => $this->value('question_id'),
											'answer'=>$this->value('answer')										
											);
											
						$where = " user_id = ".$id;
							
						$result = $this->update('answers',$updateArr,$where);	
					}
					else 
					{
						$insertArr = array(
											'user_id'=>$id,
											'question_id' =>$this->value('question_id'),
											'answer' => $this->value('answer')				
											);						
						
						$result = $this->insert('answers',$insertArr);
					}				
					
					$_SESSION['username'] = $userInfo['username'];

					$_SESSION['password'] = $this->value('new_password');
					
					{// Mail Block
					
						//have the HTML content						
						$replace['images_url'] = $this->config['images_url'];
						$replace['Fullname'] = html_entity_decode($userInfo['name_first'])." ".html_entity_decode($userInfo['name_last']);						
						$replace['Datetime'] = date('Y-m-d H:i:s',time());						
						
                        // Get clinic name
                        $clinic_id = $this->clinicInfo("clinic_id");
                        if( isset($clinic_id) && $clinic_id != "" ){
                            $sql = "select clinic_name,clinic_channel from clinic where clinic_id = '{$clinic_id}'";
                            $sql_result = @mysql_query($sql);
                            if( $sql_row = @mysql_fetch_array($sql_result)){
                                $replace['clinic_name'] = "(".html_entity_decode($sql_row['clinic_name'],ENT_QUOTES, 'ISO-8859-15').")";
                            }
                            else{
                                $replace['clinic_name'] = "";
                            }
                        if($sql_row['clinic_channel']==1){
                                $replace['logoname'] = 'TxXchange_LogoTag.jpg';
                            }else{
                                $replace['logoname'] = 'wmdx.jpg';
                            }
                        }
                        // End
                        
						$user_id = $this->userInfo('user_id');
                        $clinic_type = $this->getUserClinicType($user_id);
                        $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
                        if($clinic_channel==1){
                                $business_url=$this->config['business_tx']; 
                                $support_email=$this->config['email_tx'];
                            }else{
                                $business_url=$this->config['business_wx']; 
                                $support_email=$this->config['email_wx'];   
                            }
                            $replace['business_url'] = $business_url;
                            $replace['support_email'] = $support_email;
                        if( $clinic_channel == 1){
                        	$message = $this->build_template($this->get_template("agreementContent_plpto"),$replace);
                        }
                        else{
                        	$message = $this->build_template($this->get_template("agreementContent_wx"),$replace);	
                        }
												
						
						$to = $this->config['from_email_address'];
						//echo $subject = str_replace("&#039;","'",$replace['Fullname'])." has accepted the agreement";
						$subject = html_entity_decode($replace['Fullname'],ENT_QUOTES, 'ISO-8859-15')." has accepted the agreement";
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						
						// Additional headers
						//$headers .= 'To: '.$fullName.'<'.$_POST['email_address'].'>'. "\n";
						
                        if( $clinic_channel == 1){
                            $headers .= "From:  <".$this->config['email_tx'].">" . "\n";
                            $returnpath = "-f".$this->config['email_tx'];
                            }else{
                            $headers .= "From:  <".$this->config['email_wx'].">" . "\n";
                            $returnpath = '-f'.$this->config['email_wx'];   
                            }
						
						//$headers .= 'From: support@txxchange.com'."\n";
						//$headers .= 'Cc: example@example.com' . "\n";
						//$headers .= 'Bcc: example@example.com' . "\n";
						//$returnpath = '-fsupport@txxchange.com';
						// Mail it					
						mail($to, $subject, $message, $headers, $returnpath );	
                        }				
					
                    
                    // Check the Patient associated clininc subscription status
					$this->checkSubscriptionehealth();
					// redirect to therapist home page
					header("location:index.php?action=patient");
					
					
				}
				else
				{
					
								
					$replace['error'] = $this->error;	
					
					//Added Feb 5, 2008 Also set the question
					$selectedQuestion = ($this->value('question_id') == "")? "": $this->value('question_id');	
					
				
					//Also the check marks
					$agree_terms = (isset($_POST['agree_terms']))? 'checked="checked"': '';
					$email_promotions = (isset($_POST['email_promotions']))? 'checked="checked"': '';
					
					
					$replace['agree_terms'] = $agree_terms;
					$replace['email_promotions'] = $email_promotions;
					
					$replace['new_password'] = $this->value('new_password');					
					$replace['new_password2'] = $this->value('new_password2');
					
					$replace['answer'] = $this->value('answer');
					
			
				}
				
									
				
				$replace['id'] = $id;			
				
				$replace['questionOptions']	= $this->build_select_option($questions,$selectedQuestion);
				
				$replace['header'] = $this->build_template($this->get_template("header"));

				$patient_id = $this->userInfo('user_id');
				$clinic_type = $this->getUserClinicType($patient_id);
            	if( $clinic_type == 'plpto'){
            		$replace['terms_services'] = $this->build_template($this->get_template('consumer'));    
	            	$replace['body'] = $this->build_template($this->get_template("agreement_patient"),$replace);
            	}
            	elseif( $clinic_type == 'elpto' ){
		            $company = $this->get_field($patient_id,'user','company');
					if( !empty($company) && is_string($company) ){
		                $replace['terms_services'] = $this->build_template($this->get_template('employee'));
		                $replace['body'] = $this->build_template($this->get_template("agreement_elpto"),$replace);
		            }
		            else{
		                $replace['terms_services'] = $this->build_template($this->get_template('consumer'));    
		                $replace['body'] = $this->build_template($this->get_template("agreement_patient"),$replace);
		            }	
            	}
				
				
			    /*$patient_id = $this->userInfo('user_id');
                $company = $this->get_field($patient_id,'user','company');
                if( !empty($company) && is_string($company) ){
                      $replace['terms_services'] = $this->build_template($this->get_template('employee'));
                      $replace['body'] = $this->build_template($this->get_template("agreement_elpto"),$replace);
                }
                else{
                    $replace['terms_services'] = $this->build_template($this->get_template('consumer'));    
                    $replace['body'] = $this->build_template($this->get_template("agreement_patient"),$replace);
                }*/
                
                
                
                
				//$replace['body'] = $this->build_template($this->get_template("agreement_patient"),$replace);
				$replace['browser_title'] = "Tx Xchange: Patient Home";
				$this->output = $this->build_template($this->get_template("main"),$replace);				
					
			}
			else 
			{
                // Check the Patient associated clininc subscription status
                $this->checkSubscriptionehealth();                
				header("location:index.php?action=patient");
			}
			
			
		}


		/**
		 * Function to validate form fields
		 *
		 * @access public
		 */
		function validateAgreement()
		{
						
			// creating validation object			
			$objValidationSet = new ValidationSet();					
			
			// validating password field
			$objValidationSet->addValidator(new  StringMinLengthValidator('new_password', 6, "Password cannot be empty and must be of minimum 6 character in length",$this->value('new_password')));					
				
			// matching password and new password field
			$arrFieldNames = array("new_password","new_password2");
			$arrFieldValues = array($_POST["new_password"],$_POST["new_password2"]);
			$objValidationSet->addValidator(new  IdenticalValuesValidator($arrFieldNames, "New password and  confirm password does not match",$arrFieldValues));					
			
			# Added on Feb 5, 2008. Start validation for question and answer 
			
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
			
			
			$objValidationSet->addValidator(new  StringMinLengthValidator('answer', 1, "Answer cannot be empty",$this->value('answer')));
			# End validation for question and answer 			
			
			$objValidationSet->validate();		
			
			
			
			if (isset($_POST['agree_terms']))
			{
				$objValidationErr = new ValidationError('usertype_id',"");
				$objValidationSet->addValidationError($objValidationErr);				
				
			}
			else 
			{
				$objValidationErr = new ValidationError('agree_terms',"Please Agree to the Terms of Service & Privacy Policy.");
				$objValidationSet->addValidationError($objValidationErr);
			}
				
			
			
			if ($objValidationSet->hasErrors())
			{
				
				
				$arrayFields = array("new_password","question_id","answer",'agree_terms');
				
					
				
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
         * This is used to get the status of the patient subscription based whether account 
         * has enabled the E-Health Service
         * 
         * @return Void 
         * @access private
         */    		
         
         function checkSubscriptionehealth(){

                    // Patient Associated Clinic Id
                    $clinicId=$this->clinicInfo('clinic_id');
                    // Patient Id
                    $userId=$this->clinicInfo('user_id');
                    if($this->is_corporate($clinicId)==1){
                        return true;
                        die();
                    }
                    
                    $queryEhealthStatus = "select health_settings.* from health_settings,clinic_subscription,clinic 
                                           where health_settings.setting_id=clinic_subscription.setting_id AND clinic.clinic_id = health_settings.setting_clinic_id 
                                           AND clinic.clinic_id='{$clinicId}' AND clinic_subscription.subs_status='1'";
                   //die();
                    $resultEhealthStatus = $this->execute_query($queryEhealthStatus);                   
                    // Check if the associated clininc has enabled the E Health Service
                    if($this->num_rows($resultEhealthStatus)!=0){
                        // Check If the User has opted for the E-Health Service Already
                        
                        $querySubscription="select * from patient_subscription ps where ps.clinic_id ='{$clinicId}' AND ps.user_id='{$userId}'";
                            // Condition to check if patient is under subscription or Cancelled but within subscription Date
                            $conditionSubscription=" AND ((ps.subs_status = '1'  AND subs_end_datetime > now()) OR (ps.subs_status='2' AND subs_end_datetime > now()))  ORDER BY user_subs_id DESC";
                          $querySubscription.=$conditionSubscription;
                        //die();
                        $resultSubscriptionStatus = $this->execute_query($querySubscription);
                            if($this->num_rows($resultSubscriptionStatus)!=0){
                              // Patient has subscribed to the clinic services so do nothing the patient will be redirected to the patient home page.
                              return true;
                            }else{ 
                                
                             // Code to check the txn_type : Case : User Suspended due to Payment Transaction Failure : Starts here
                              $querySubscription="select * from patient_subscription ps where ps.clinic_id ='{$clinicId}' AND ps.user_id='{$userId}'  ORDER BY user_subs_id DESC";
                                $resultSubscriptionStatusQuery = $this->execute_query($querySubscription);
                                if($this->num_rows($resultSubscriptionStatusQuery)>0) {
                                    $resultSubscriptionStatus=$this->fetch_array($resultSubscriptionStatusQuery);//echo "<pre>";print_r($resultSubscriptionStatus);exit;
                                    //echo '<pre>'; print_r($resultSubscriptionStatus);die();
                                    if($resultSubscriptionStatus['subs_status']=='3'){
                                         // Patient has purchased the  E Health Service but the payment was not sucessfull so redirect to the admin contact Page.

                                         //Check For the User Channel Partner            
                                         $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
                                         //$this->destroy_session();
                                         //header("location:index.php?action=patient_admin_contact&clinic_channel=$clinic_channel");
                                         header("location:index.php?action=update_paypal_profile");
                                         die();   
                                    }
                                  if($resultSubscriptionStatus['subs_status']=='1' && $resultSubscriptionStatus['paymentType']==1 and $this->userinfo('ehs',$userId)==1){
                                         // Patient has purchased the  E Health Service but the payment was not sucessfull so redirect to the admin contact Page.
                                         //Check For the User Channel Partner            
                                         //$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
                                         //$this->destroy_session();
                                         header("location:index.php?action=patient_subscription&status=show");
                                         die();   
                                    }
                                $sqlEhealthService = "SELECT ehs FROM user WHERE user_id='{$userId}'";
                                $rsEhealthService = $this->execute_query($sqlEhealthService);
                                $resultEhs = $this->fetch_array($rsEhealthService);
                                                   
                                if($resultSubscriptionStatus['subs_status']=='2' && $resultEhs['ehs'] == '1'){
                                         header("location:index.php?action=patient_subscription");
                                         die();   
                                    }
                                 
                                // Code Ends here for txn_type                    
                               } else { 
                                        //Code to check E health service 
                                        //14th oct 2011
                                        $sqlEhealthService = "SELECT ehs FROM user WHERE user_id='{$userId}'";
                                        $rsEhealthService = $this->execute_query($sqlEhealthService);

                                                $resultEhs = $this->fetch_array($rsEhealthService);
                                                //echo $resultEhs['ehs'];exit;
                                                if($resultEhs['ehs'] == '0') {
                                                        // Clinic has not enabled the health services so do nothing the patient will be redirected to the patient home page.   
                                                        return true;
                                                }  else {
                                                        // Patient has not purchased the  E Health Service so redirect to the subscription Page.
                                                        //return true;    
                                                        header("location:index.php?action=patient_subscription");
                                                             die(); 
                                                }     


                                        
                                     // Patient has not purchased the  E Health Service so redirect to the subscription Page.
                                     header("location:index.php?action=patient_subscription");
                                     die();   
                                 }
                            }
                    }else{
                        // Clinic has not enabled the health services so do nothing the patient will be redirected to the patient home page.   
                        return true;
                    }
         }
         
         public function destroy_session(){
            //unregister the session variables
            session_unregister('username');
            session_unregister('password');
            
            $query = "DELETE FROM tmp_patient_reminder WHERE patient_id = '".session_id()."'";
            $this->execute_query($query);

            $query = "DELETE FROM tmp_therapist_patient WHERE patient_id = '".session_id()."'";
            $this->execute_query($query);

            session_destroy();         
         }         

	}

	// creating object of this class.
	$obj = new agreement_patient();

?>

