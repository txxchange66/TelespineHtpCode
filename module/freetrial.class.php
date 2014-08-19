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
        require_once("include/class.paypal.php");
	require_once("include/validation/_includes/classes/validation/ValidationSet.php");
	require_once("include/validation/_includes/classes/validation/ValidationError.php");	
	
	// class declaration
  	class freetrial extends application{
  		
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
		 * Function to Edit Clinic Details
		 *
		 * @access public
		 */
		function freetrial()
		{
			//echo $this->config['from_email_address'];exit;
			$replace = array();
			$option = $this->value('option');
			$clinic_id = $this->value('clinic_id');
			
			$replace['browser_title'] = 'Free Trial';
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();
			$replace['clinic_id'] = $clinic_id;
			
			// When user modify the clinic details
			if((empty($clinic_id)) && (empty($option)))
			{
				// Populate BLANK FORM fields
				$replace['heading'] = 'Create New Account';
				//print_r($this->form_array);
				$this->assignValueToArrayFields($this->form_array, '', '1', &$replace);
			}else{
				$replace['heading'] = 'Create New Account';
				if($option == 'update'){
					$this->validateForm();					
					if($this->error == ""){
						//  Populate FieldArray from FormArray
						{
							$insertArr = $this->assignValueToArrayFields($this->form_array, '', '2', '', 'insert');
							$insertArr['status'] = '1';
                            $insertArr['creationDate'] = date("Y-m-d H:i:s");
                            $insertArr['parent_clinic_id'] = '0';
                            
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
		
			// creating state drop down element
					
			$stateArray = array("" => "Choose Provider...");
			$query="select * from practitioner where   status =1";
			$result=$this->execute_query($query);
			while($row=$this->fetch_array($result)){
				$stateArray[$row['practitioner_id']]=$row['name'];
			}
			$replace['PractitionerOptions'] = 	$this->build_select_option($stateArray, $replace['practitiner_type']);
			$replace['body'] = $this->build_template($this->get_template("freetrial"),$replace);
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}
		
		
		function freetrial_register(){
		/*
		 * step	1 check user duplication
		 * step 2 create clinic
		 * step 3 create user
		 * step 4 assign user to clinic
		 * step 5 send mail to jonathan
		 * step 6 send email to user
		 * setp 7 return sucess or fail
		 * 
		 * */
                 
                  
		if ($_REQUEST['action'] == 'freetrial_register')
				{
                     //echo '<pre>';
                     // print_r($_SESSION);
					if($_SESSION['clinic']['business_name'] != '')
					{	
						//Form validated, no errors
						//go ahead store the values in db
                        			
                            $API_UserName       =       urlencode($this->config["clinicpaypalprodetails"]["API_UserName"]);
                            $API_Password       =       urlencode($this->config["clinicpaypalprodetails"]["API_Password"]);
                            $API_Signature      =       urlencode($this->config["clinicpaypalprodetails"]["API_Signature"]);
                            $environment        =       urlencode($this->config["clinicpaypalprodetails"]["environment"]);
                            $currencyID         =       urlencode($this->config["clinicpaypalprodetails"]["currencyID"]);
                            $Frequency          =       urlencode($this->config["clinicpaypalprodetails"]["billingPeriod"]);
                            $billingFreq        =       urlencode($this->config["clinicpaypalprodetails"]["billingFreq"]);                  
                            $paymentAmount      =   $_SESSION['clinic']['cardPayment'];
                            $creditCardType     =   $_SESSION['clinic']['cardType'];
                            $creditCardNumber   =   $_SESSION['clinic']['cardNumber'];
                            $expDateMonth       =   $_SESSION['clinic']['exprMonth'];
                            $expDateYear        =   $_SESSION['clinic']['exprYear'];
                            $cvv2Number         =   $_SESSION['clinic']['cvvNumber'];
                            $firstName          =   $_SESSION['clinic']['name_first'];
                            $lastName           =   $_SESSION['clinic']['name_last'];
                            $address1           =   $_SESSION['clinic']['address1'];
                            $city               =   $_SESSION['clinic']['city'];            
                            $state              =   $_SESSION['clinic']['state'];
                            $zipcode            =   $_SESSION['clinic']['zipcode'];
                            $BillingCountry     =   $_SESSION['clinic']['country'];
                            $emailaddress       =   $_SESSION['clinic']['username'];
                            $address2           =   $_SESSION['clinic']['address2'];
                            $practitiner_type   =   $_SESSION['clinic']['practitiner_type'];
                            $phone1             =   $_SESSION['clinic']['phone1'];
                            $desc               =   urlencode($_SESSION['clinic']['business_name']);
 			    $BillingstreetAddr  =   urlencode($address1);
                           
                            $system_access = 0; 
			    $therapist_access = 1;
			    $userTypeId = 2;
			    $admin_access = 1;
			    $password = $this->genRandomString();
			    print_r($_SESSION);
                            $queryprovider= $this->execute_query("select * from user where username='". $emailaddress."' and status=4");
                            $numqeryprovider=$this->num_rows($queryprovider);
                            if($numqeryprovider==0)
                            {
                            $insertArr = array(
                                        'usertype_id'   =>  $userTypeId,
                                        'username'      =>  $emailaddress,
                                        'password'      =>  $password,
                                        'name_first'    =>  $firstName,
                                        'name_last'     =>  $lastName,
                                        'address'       =>  $address1,  
                                        'address2'      =>  $address2,
                                        'city'          =>  $city,
                                        'state'         =>  $state,
                                        'country'       =>  $BillingCountry,
                                        'zip'           =>  $zipcode,
                                        'phone1'        =>  $phone1,
                                        'creation_date' => date('Y-m-d H:i:s', time()),
                                        'modified' => date('Y-m-d H:i:s', time()),
                                        'admin_access' => $admin_access,
                                        'system_access' => $system_access,
                                        'therapist_access' => $therapist_access,
                                        'status' => 4,
                                        'free_trial_date' => date('Y-m-d H:i:s', time()),
                                        'trial_status' => 1,
                                        'practitioner_type' => $practitiner_type
                                        );
					$this->check_send_invitation($this->value('email'));						
												
					$result = $this->insert('user',$insertArr);
						
					$newlyCreatedUserId = $this->insert_id();
					/*
					 * create clinic
					 */
                                $insertArr['status']            = '1';
                                $insertArr['clinic_name']       = $_SESSION['clinic']['business_name'];
                                $insertArr['creationDate']      = date("Y-m-d H:i:s");
                                $insertArr['parent_clinic_id']  = '0';
                                $insertArr['zip']               = $zipcode;
                                $insertArr['trial_status']      = 1;
                                $insertArr['trial_date']        = date("Y-m-d H:i:s");
                                $insertArr['address']           = $address1; 
                                $insertArr['address2']          = $address2;
                                $insertArr['city']              = $city;
                                $insertArr['country']           = $BillingCountry;
                                $insertArr['state']             = $state;
                                $insertArr['phone']             = $phone1;
                                
                                $result = $this->insert('clinic',$insertArr);
                                $clinicId = $this->insert_id();
                            	if ($userTypeId=='2') //edited so that therapist is also incorporated along with account admin
						{
							$insertArrClinicUser = array(
												'user_id'=>$newlyCreatedUserId,
												'clinic_id' =>$clinicId											
												);						
							
							$result = $this->insert('clinic_user',$insertArrClinicUser);
							$insertArrClinicservice = array(
												'service_name'=>'plpto',
												'clinic_id' =>$clinicId											
												);						
							
							$result = $this->insert('clinic_service',$insertArrClinicservice);
							
							
						}
                            }else{
                               //get user id 
                            $row                    =   $this->fetch_array($queryprovider);
                            $newlyCreatedUserId     =   $row['user_id'];
                            $query     = $this->execute_query("select * from clinic_user where user_id=".$newlyCreatedUserId);
                            $row_clinic= $this->fetch_array($query);
                            $clinicId  = $row_clinic['clinic_id'];
                //get clinic id
                                
                            }
                                        ////////////payment 
                            $new_provider_id= $newlyCreatedUserId ;
                             //make one time payment
                            $nvpStr="&PAYMENTACTION=Sale&AMT=$paymentAmount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=".$expDateMonth.$expDateYear."&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state"."&ZIP=$zipcode&COUNTRYCODE=$BillingCountry&CURRENCYCODE=$currencyID&EMAIL=".$emailaddress."&DESC=".$desc;
                            $status=$this->submit_clinic_onetime_subscription($nvpStr,$new_provider_id,$clinicId , $cid,$newuser);
                            //if sucess
                            //
                            if($status=='sucess'){
                               $startDate = urlencode(date('Y-m-d H:i:s', strtotime("+1 days")));
                               $billingPeriod = urlencode($Frequency);                // or "Day", "Week", "SemiMonth", "Year"
                               $clinicName=html_entity_decode($_SESSION['clinic']['business_name'],ENT_QUOTES, "UTF-8");
                               $desc=urlencode($clinicName);
                               $BillingstreetAddr=urlencode($address1);
                               $Billingstreet2Addr=urlencode($address2);
                               $BillingCity=urlencode($city);
                               $BillingState=urlencode($state);
                               $BillingCountry=urlencode($country);
                               $Billingzip=urlencode($zipcode);
                               $nvpStr="&FIRSTNAME=$firstName&LASTNAME=$lastName&EMAIL=$emailaddress&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=$expDateMonth$expDateYear&CVV2=$cvv2Number&AMT=$paymentAmount&CURRENCYCODE=$currencyID&PROFILESTARTDATE=$startDate";
                               $nvpStr .= "&BILLINGPERIOD=$billingPeriod&BILLINGFREQUENCY=$billingFreq&DESC=$desc";
                                // For Billing Address
                               $nvpStr .= "&STREET=$BillingstreetAddr&STREET2=$Billingstreet2Addr&CITY=$BillingCity&STATE=$BillingState&COUNTRYCODE=$BillingCountry&ZIP=$Billingzip";
                               // Code to Limit the MAXFAILEDPAYMENTS
                                $nvpStr .='&MAXFAILEDPAYMENTS=1&AUTOBILLOUTAMT=AddToNextBilling';
                                $this->submit_provider_subscription($nvpStr,$new_provider_id,$clinicId , $cid,$newuser);  
                                
                            }else{
                             //fail
                            //send user to payment page with error code
                             header("location:index.php?action=payment_clinic&cid=1".$messageReturn);
                             die();    
                            }
                            //create recurring profile
                            //copy all the template plan and article 
                            
                            $query=$this->execute_query("update user set status=1 where user_id=".$new_provider_id);
                            $query=$this->execute_query("update clinic set status=1 where clinic_id=".$clinicId);
                           
                            
                            
                            
                            
                            
                            
                            
                            
                        if(is_numeric($clinicId)){
                            $this->copyPublishPlan($newlyCreatedUserId, $clinicId);    
                        }
                        
                        
						//Also insert the record in answer table
						/**
						 * remove challenging  question.
						 
						$insertArr = array(
											'user_id'=>$newlyCreatedUserId,
											'question_id' =>$this->value('question_id'),
											'answer' => $this->value('answer')				
											);
											
						$result = $this->insert('answers',$insertArr);
						
						* End of comment.
						
						*/
						
						//Also associate this new user(account admin) with the clinic so insert the record in clinic_user table
						//for therapist add additional condition $this->value('usertype_id') == '2' (future release)
							
						
						// Also handle the copying of article and treatment template for user if usertype is therapist
						
						{//usertype = therapist	
							if(	$userTypeId=='2')
							{
							
								//Article Block
								{
									
								$queryArticle = "SELECT article.* FROM article INNER JOIN user ON article.user_id = user.user_id 
														WHERE user.usertype_id = 4 AND user.status = 1 AND article.status != 3";
									
								$resultArticle = $this->execute_query($queryArticle);
									
									
									// now copy article data with user_id as $newlyCreatedUserId
								$this->num_rows($resultArticle);
                                    							
									if($this->num_rows($resultArticle)!= 0)
									{
										while($row = $this->fetch_array($resultArticle))
										{	
												
											$insertArr = array(
																'article_name'=>$row['article_name'],
																'headline' =>$row['headline'],
																'article_type' => $row['article_type'],
																'link_url' => $row['link_url'],
																'file_url' => $row['file_url'],
																'user_id' => $newlyCreatedUserId,
																//'creation_date' => date('Y-m-d H:i:s',time()),			
																'creation_date' => $row['creation_date'],
																'modified'=>$row['modified'],				
																'status'=> $row['status'],																
																'parent_article_id'	=> $row['article_id']				
																);										
											
																						
											
                                           // print_r($insertArr);
                                            $result = $this->insert('article',$insertArr);
											$newArticleId = $this->insert_id();
											
											if (!empty($row['file_url']))
											{
												$fileDest = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url']. $newArticleId . '/';
												if(!file_exists($fileDest))
												{
													mkdir($fileDest);
													chmod($fileDest, 0777);
												}
												
												
												$fileDestPath = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url']. $newArticleId . '/'.$row['file_url'];
																							
												$fileSourcePath = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$row['article_id'] . '/'.$row['file_url'];
												
												copy($fileSourcePath,$fileDestPath);	
											}										
											
											
										}										
									}
								}
									
								//Plan Block
								{
									$queryPlan = "SELECT plan.* FROM plan INNER JOIN user ON plan.user_id = user.user_id 
													WHERE user.usertype_id = 4 AND user.status = 1 AND plan.patient_id IS NULL AND plan.status = 1 AND plan.is_public = 1";
									$resultPlan = $this->execute_query($queryPlan);
									
									$plan_id = 0;
									
									if($this->num_rows($resultPlan)!= 0)
									{
										while($row = $this->fetch_array($resultPlan))
										{	
														
											$insertArr = array(
																'plan_name'=>$row['plan_name'],
																'parent_template_id' =>NULL,
																'user_id' => $newlyCreatedUserId,
																'patient_id' => NULL,
																'user_type' => 2,
																'is_public' => NULL,
																//'creation_date' => date('Y-m-d H:i:s',time()),
																'creation_date' => $row['creation_date'],																			
																'status'=> $row['status']					
																);
																
											$result = $this->insert('plan',$insertArr);
										
											$plan_id = $row['plan_id'];
											
											//Newly plan id
											$newlyPlanId = $this->insert_id();
											
											
											
											{//Plan_Article Block
											
												/*
													Also for this particular DBrow fetch from plan table have the plan_id
													now find this plan_id in table plan_article	get the rows for this plan_id
													insert rows into plan_article with all data same except replace plan_id with newly created plan_id									
													
												*/
												
												$queryPlanArticle = "SELECT * FROM plan_article WHERE status = 1 AND plan_id = ".$plan_id ;
												$resultPlanArticle = $this->execute_query($queryPlanArticle);
												
												
														
												if($this->num_rows($resultPlanArticle)!= 0)
												{
													while($row = $this->fetch_array($resultPlanArticle))
													{	
														$insertArr = array(
																'plan_id'=> $newlyPlanId,
																'article_id' => $row['article_id'],							
																'creation_date' => date('Y-m-d H:i:s',time()),			
																'status'=> $row['status']					
																);
																
														$result = $this->insert('plan_article',$insertArr);
														
													}
												}
											}	
											
											{//Plan_Treatment Block
											
												/*
													Also for this particular DBrow fetch from plan table have the plan_id
													now find this plan_id in table plan_treatment	get the rows for this plan_id
													insert rows into plan_treatment with all data same except replace plan_id with newly created plan_id									
													
												*/
												
												$queryPlanTreatment = "SELECT * FROM plan_treatment WHERE plan_id = ".$plan_id ;
												$resultPlanTreatment = $this->execute_query($queryPlanTreatment);
												
												
														
												if($this->num_rows($resultPlanTreatment)!= 0)
												{
													while($row = $this->fetch_array($resultPlanTreatment))
													{	
														
														$row['sets'] = (empty($row['sets']))? "":$row['sets'];
														$row['reps'] = (empty($row['reps']))? "":$row['reps'];
														$row['hold'] = (empty($row['hold']))? "":$row['hold'];
														$row['lrb'] = (empty($row['lrb']))? "":$row['lrb'];
														$row['treatment_order'] = (empty($row['treatment_order']))? "":$row['treatment_order'];
																												
														$insertArr = array(
																'plan_id'=> $newlyPlanId,
																'treatment_id' => $row['treatment_id'],						
																'instruction' => $row['instruction'],			
																'sets'=> $row['sets'],
																'reps'=> $row['reps'],
																'hold' => $row['hold'],
																'benefit'=> $row['benefit'],
																'lrb'=> $row['lrb'],
																'treatment_order' => $row['treatment_order'],				
																'creation_date' => date('Y-m-d H:i:s',time())			
																);
																
														$result = $this->insert('plan_treatment',$insertArr);
														
													} // end of while
												} // end of if block of result of plan_treatment
											}//End of plan treatment block
										} //While block for result rows from plan table
									}//End of if block of result rows from plan table												
									
								}//End of plan block									
								
								//Also update the user table with expiry date for this user as therapist access is given to him for the first time
							
								
								$updateArr = array(
																							
												'expiry_date' => date('Y-m-d H:i:s',mktime(date('H'), date('i'), date('s'), date("m")  , date("d")+15, date("Y")))
																	
												);
												
								$where = " user_id = ".$newlyCreatedUserId;
														
								$result = $this->update('user',$updateArr,$where);	
								
									
							}//end of if (usertype == therapist)
						
						}//End of usertype = therapist				
						
						//send mail that user created successfully
						{
							//have the HTML content
							$fullName = $firstName." ".$lastName;
							$replace['username'] = $emailaddress;
							$replace['url'] = $this->config['url'];
							$replace['images_url'] = $this->config['images_url'];
							$replace['password'] = $password;
							
							
							$clinic_type = 'plpto';
							
							$queryUserType = "SELECT * FROM user WHERE status = 1 AND user_id = ".$newlyCreatedUserId;
							$result = $this->execute_query($queryUserType);
							
							if($this->num_rows($result)!= 0)
								{
									 $rowID = $this->fetch_array($result);
									 $user_Admin = $rowID['admin_access'];
									 $user_Therapist = $rowID['therapist_access'];
								
									 if( $clinic_type == 'plpto'){
										if($user_Therapist == 1){
											$subject = "Your Tx Xchange Account Information";
											$message = $this->build_template($this->get_template("newUserMailContent_plpto_freetrial"),$replace);
										}
									}
								}
							//mail content for jonathon 
							$sqlpractitioner="select * from practitioner where  practitioner_id = ".$_SESSION['clinic']['practitiner_type'];
							$resultpractitioner = $this->execute_query($sqlpractitioner);
							$rowpractitioner = $this->fetch_array($resultpractitioner);
							$replace1['url'] = $this->config['url'];
							$replace1['images_url'] = $this->config['images_url'];
							$replace1['businessname']=$_SESSION['clinic']['business_name'];
							$replace1['name_first']=$firstName;
							$replace1['name_last']=$lastName;
							$replace1['username']=$emailaddress;
							$replace1['practitioner_type']=$rowpractitioner['name'];
							$replace1['zip_code']=$zipcode;
							$subject1 = "Your Tx Xchange Account Information";
							$message1 = $this->build_template($this->get_template("admin_mail_freetrial"),$replace1);
							$to1 = $this->config['from_email_address'];
							
							$to = $fullName.'<'.$emailaddress.'>';
							
							
							// To send HTML mail, the Content-type header must be set
							$headers  = 'MIME-Version: 1.0' . "\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
							
							// Additional headers
							//$headers .= 'To: '.$fullName.'<'.$_POST['email_address'].'>'. "\n";
							
							//$headers .= 'From: '.$this->config['from_email_address'] . " \n";
							$headers .= 'From: support@txxchange.com' . "\n";
							//$headers .= 'Cc: example@example.com' . "\n";
							//$headers .= 'Bcc: example@example.com' . "\n";
							
							$returnpath = '-fsupport@txxchange.com';
                            // Mail it	
                           // echo $subject;
							
                                                        @mail($to, $subject, $message, $headers, $returnpath);	
							@mail($to1, $subject1, $message1, $headers, $returnpath);
							
						}
						
						
						// redirect to list of subscriber
						//header("location:index.php?action=freetrial");
                                                @session_destroy();
                                                @header("Location: /index.php?action=payment_clinic&status=paymentsuccess");
						//@header("Location: http://localhost/txxchange_business/index.php?option=com_helloworld&Itemid=44&status=success");
						exit;
						
						
					}
					/*else
					{
						$replace = $this->fillForm($this->formArray,$_POST);						
						$replace['error'] = $this->error;	
						
						//Also set the question
						//$selectedQuestion = ($this->value('question_id') == "")? "": $this->value('question_id');
						
						//Also the state
						$selectedState = ($this->value('state') == "")? "": $this->value('state');
						
						//Also the user type
						$selectedUserType = ($this->value('usertype_id') == "")? "": $this->value('usertype_id');
						
						if ($selectedUserType == 3 || $selectedUserType == 2) //edited so that therapist is also incorporated along with account admin
						{
							$replace['selClinicDisplay'] = "block";
							$replace['clinic_id'] = $this->value('clinic_id');
							$replace['clinic_name'] = $this->value('clinic_name');;
						}
						else 
						{
							$replace['selClinicDisplay'] = "none";
							$replace['clinic_name'] = "";
							$replace['clinic_id']="";
						}
						//Also the status
						$selectedStatus = $this->value('status');
					}
				return 'sucess';*/
				}
		
		}
		
  	function validateFormSubscriber($email)
		{
			$queryEmail = "SELECT user_id FROM user WHERE username = '".$email."' AND status not in(3,4) ";
			$result = $this->execute_query($queryEmail);
				//if record found that means email not unique else it is unique
				if ($this->num_rows($result) != 0)
				{
					return 1;
				}
				else
				{return 0;		
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
							'clinic_name'		=> 'clinic_name',
							'name_first' 			=> 'name_first',
							'name_last' 			=> 'name_last',
							'email' 			=> 'email',
							'practitiner_type' 	=> 'practitiner_type',
							'clinic_zip' 		=> 'clinic_zip'                            
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
  	/**
        * Copy published plan to newly created Therapist
        */
       function copyPublishPlan($user_id, $clinic_id) {

        if (is_numeric($clinic_id) && $clinic_id != 0) {
            $parent_clinic_id = $this->get_field($clinic_id, 'clinic', 'parent_clinic_id');
            if (is_numeric($parent_clinic_id)) {
                if ($parent_clinic_id == 0) {
                    echo $query = "SELECT cu.user_id
                        FROM clinic_user AS cu
                        INNER JOIN clinic AS cl ON cu.clinic_id = cl.clinic_id
                        INNER JOIN user as us on us.user_id = cu.user_id and us.status = 1 and us.usertype_id  = 2
                        WHERE (cl.parent_clinic_id = '{$clinic_id}' or cl.clinic_id = '{$clinic_id}') 
                        AND cl.status =1 
                    ";
                } else {
                    echo $query = "SELECT cu.user_id 
                        FROM clinic_user AS cu
                        INNER JOIN clinic AS cl ON cu.clinic_id = cl.clinic_id
                        INNER JOIN user as us on us.user_id = cu.user_id and us.status = 1 and us.usertype_id  = 2
                        WHERE cl.parent_clinic_id = '{$parent_clinic_id}' or cl.clinic_id = '{$parent_clinic_id}' 
                        AND cl.status =1 
                    ";
                }

                if (!empty($query)) {
                    echo $sql = "select * from plan 
                                where 
                                    status = 1 
                                    and user_type = 2 
                                    and is_public = 1 
                                    and patient_id is null 
                                    and user_id in 
                                    (
                                       $query 
                                    )";

                    $result = @mysql_query($sql);
                    while ($row = @mysql_fetch_array($result)) {
                        $this->copy_plan($user_id, $row['plan_id']);
                    }
                }
            }
        }
        return "";
    }

    function genRandomString() {
        $length = 7;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $string = '';

        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters))];
        }

        return $string;
    }
// 	
    function account_regestration(){
        $replace = array();
        $logotx='txlogo';
        $header_1 = "<div id='line'><div id='".$logotx."' style='float:left; padding-left:55px;' ></div><div id='subheader' style='float:right; padding-right:55px;' ><div id='cliniclogo'></div></div><div style='clear:both;'></div></div>";
        $replace['header']=$header_1;
      
        if($_REQUEST['submit']=='Continue'){
        $status=$this->validateFormSubscriber($_REQUEST['username']);					
	if($status == '0')
	{
            $_SESSION['clinic']['name_first']=$_REQUEST['name_first'];
            $_SESSION['clinic']['business_name']=$_REQUEST['business_name'];
            $_SESSION['clinic']['name_last']=$_REQUEST['name_last'];
            $_SESSION['clinic']['practitiner_type']=$_REQUEST['practitiner_type'];
            $_SESSION['clinic']['username']=$_REQUEST['username'];
            $_SESSION['clinic']['clinic_type']=$_REQUEST['clinic_type'];
            $_SESSION['clinic']['phone1']=$_REQUEST['phone1'];
            @header("Location:/index.php?action=payment_clinic");
            
        }  else {
         $replace['dublicateuser']='<br><font style="color:#FF0000;font-weight:normal;">Email Address already exists.  </font>';      
        }
        $replace['name_first']=$_REQUEST['name_first'];
        $replace['business_name']=$_REQUEST['business_name'];
        $replace['name_last']=$_REQUEST['name_last'];
        $replace['username']=$_REQUEST['username'];
        $replace['phone1']=$_REQUEST['phone1'];
        $query="select * from practitioner where   status =1";
			$result=$this->execute_query($query);
			while($rowPractitioner=$this->fetch_array($result)){
				$PractitionerArray[$rowPractitioner['practitioner_id']]=$rowPractitioner['name'];
			}
			$replace['PractitionerOptions'] = 	$this->build_select_option($PractitionerArray, $_REQUEST[practitiner_type]);
                        
        $query="select * from clinic_type where clinic_status = '1'";
			$result=$this->execute_query($query);
			while($rowPractitioner=$this->fetch_array($result)){
				$clinic_typeArray[$rowPractitioner['clinic_type_id']]=$rowPractitioner['clinic_type'];
			}
                        
			$replace['clinic_typeOptions'] = 	$this->build_select_option($clinic_typeArray, $_REQUEST[clinic_type]);
        }else{
        $replace['name_first']='';
        $replace['business_name']='';
        $replace['name_last']='';
        $replace['username']='';
        $replace['phone1']='';
        $query="select * from practitioner where   status =1";
			$result=$this->execute_query($query);
			while($rowPractitioner=$this->fetch_array($result)){
				$PractitionerArray[$rowPractitioner['practitioner_id']]=$rowPractitioner['name'];
			}
			$replace['PractitionerOptions'] = 	$this->build_select_option($PractitionerArray, $selectedPractitioner);
                        
        $query="select * from clinic_type where clinic_status = '1'";
			$result=$this->execute_query($query);
			while($rowPractitioner=$this->fetch_array($result)){
				$clinic_typeArray[$rowPractitioner['clinic_type_id']]=$rowPractitioner['clinic_type'];
			}
                        
			$replace['clinic_typeOptions'] = 	$this->build_select_option($clinic_typeArray, $selectedclinic_type);
        }
        $replace['cid']=$_REQUEST['cid'];
        $replace['footer'] = $this->build_template($this->get_template("footer"));    
        $replace['browser_title'] = "Tx Xchange: Clinic Subscription";
        $replace['body'] = $this->build_template($this->get_template("signupform"),$replace);
        
        $this->output = $this->build_template($this->get_template("main"),$replace);
    }
    
    
    function payment_clinic(){
            $replace = array();
        if($_REQUEST['status']=='paymentsuccess'){
            
            $RegistrationMessage = "<script>GB_showCenter('Sign-up Confirmation' , '/index.php?action=clinicsucesspaymentmessage',170,550);</script>";
            $replace['RegistrationMessage'] = $RegistrationMessage;
            $replace['body'] = $this->build_template($this->get_template("payment_clinic"),$replace);
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
        
  
                        //Encrypted clinic id                        
                       

                        //Paypal Error codes starts here
                        $errorCode=$this->value('errorCode');
                        if($errorCode!='') {
                            $customerrormessage=$this->PaypalErroCodeslist($errorCode);
                            if($customerrormessage=='')                    
                                {
                                    $customerrormessage="Invalid Credit Card Details";                        
                                }
                            $replace['errorMessage']='<tr><td colspan="4" style="height:20px; color:#0069a0;; font-weight:bold"><font style="color:#FF0000;font-weight:normal;">'.$customerrormessage.'</font></td></tr><tr><td colspan="4" style="height:5px; color:#0069a0;; font-weight:bold"></td></tr>';   
                            
                        }

                        //End here


     $replace['namefValid']='<font style="color:#FF0000;font-weight:normal;">'.urldecode($this->value('namefValid')).'</font>';
            if($this->value('validData')=='true')   {
                    if(urldecode($this->value('namefValid'))!=''){      
                        $replace['namefValid']='<font style="color:#FF0000;font-weight:normal;">'.urldecode($this->value('namefValid')).'</font>';                                         
                     $replace['retainedFname']='';
                    }     
                      $replace['retainedFname']=$_SESSION['clinic']['fname'] ;                                                                            
                    if(urldecode($this->value('namelValid'))!='')       {
                        $replace['namelValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('namelValid').'</font>';                                                  
                     $replace['retainedlname']='' ; 
                    }
                    $replace['retainedlname']=$_SESSION['clinic']['lname'] ;                     
                     if(urldecode($_REQUEST['emailValid'])!='')       {
                        $replace['emailValid']='<font style="color:#FF0000;font-weight:normal;">'.$_REQUEST['emailValid'].'</font>';                                                  
                    
                     $replace['retainedEmail']='' ; 
                    }
                        $replace['retainedEmail']=$_SESSION['clinic']['emailAddress'] ;  
                                                                    
                    if(urldecode($this->value('cnumberValid'))!='')   {     
                        $replace['cnumberValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cnumberValid').'</font>';
                        $replace['retainedcardNumber'] = '';
                     } 
                                                                                      
                    $replace['retainedcardNumber'] = $_SESSION['clinic']['cardNumber']; 
                                                                                                          
                    if(urldecode($this->value('cexpValid'))!='')  {           
                        $replace['cexpValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cexpValid').'</font>';
                        //$replace['retainedcardNumber'] = '';
                      }                                                          
                    if(urldecode($this->value('ccvvValid'))!='') {            
                        $replace['ccvvValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('ccvvValid').'</font>'; 
                        $replace['retainedcvvNumber'] = '';
                    }                                      
                    $replace['retainedcvvNumber'] = $_SESSION['clinic']['cvvNumber'];                                        
                    if(urldecode($this->value('ctypeValid'))!='') {                
                        $replace['ctypeValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('ctypeValid').'</font>'; 
                        $replace['retainVisaType'] = '';
                        $replace['retainMasterType'] = '';
                     }
                        if($_SESSION['clinic']['cardType'] == 'Visa') {
                                $replace['retainVisaType'] = 'selected="selected"';
                                $replace['retainMasterType'] = '';
                        }
                        elseif($_SESSION['clinic']['cardType'] == 'MasterCard') {
                                $replace['retainMasterType'] = 'selected="selected"';
                                $replace['retainVisaType'] = '';
                        }
                        else {
                                $replace['retainVisaType'] = '';
                                $replace['retainMasterType'] = '';
                        }
                                                                    
                    if(urldecode($this->value('address1Valid'))!='')   {           
                       $replace['address1Valid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('address1Valid').'</font>';                                              
                       $replace['retainedadd1']='' ;  
                    }     
                       
                       
                    
                        $replace['retainedadd1']=$_SESSION['clinic']['address1'] ;
                     
                       
                    if(urldecode($this->value('cityValid'))!='')    {               
                        $replace['cityValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cityValid').'</font>';                                                         
                        $replace['retainedcity']='';  
                    }
                        
                        $replace['retainedcity']=$_SESSION['clinic']['city'] ;
                     
                                                            
                    if(urldecode($this->value('stateValid'))!='')  {                  
                        $replace['stateValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('stateValid').'</font>';                                             
                    
                        $replace['retainedstate']=''; 
                    
                    }         
                        
                        $replace['retainedstate']=$_SESSION['clinic']['state'] ;
                     
                            
                    if(urldecode($this->value('zipcodeValid'))!='')    {               
                        $replace['zipcodeValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('zipcodeValid').'</font>';                                               
                    
                        $replace['retainedzip']='' ;
                    }
                        
                    
                        $replace['retainedzip']=$_SESSION['clinic']['zipcode'] ;

                

                    if(urldecode($this->value('address2'))=='')
                        $replace['retainedadd2']=$_SESSION['clinic']['address2'] ;
                    } else {
                    $replace['namefValid']='';
                    $replace['retainedFname']='';      
                    $replace['namelValid']=''; 
                    $replace['emailValid']=''; 
                    $replace['retainedlname']='' ;     
                    $replace['cnumberValid']='';   
                    $replace['cexpValid']='';   
                    $replace['ccvvValid']='';   
                    $replace['ctypeValid']='';
                    $replace['address1Valid']=''; 
                    $replace['retainedadd1']='' ;   
                    $replace['retainedadd2']='' ;               
                    $replace['cityValid']='';
                    $replace['retainedcity']='' ;         
                    $replace['stateValid']='';
                    $replace['retainedstate']='' ;         
                    $replace['zipcodeValid']='';
                    $replace['retainedzip']='' ; 
                    $replace['RetaincountryUS']='' ;                 
                    $replace['RetaincountryCAN']='' ; 
                    $replace['retainedEmail']='';  
                    $replace['retainedcardNumber']='';             
                    $replace['retainVisaType']=''; 
                    $replace['retainMasterType']=''; 
                    $replace['retainedcvvNumber']='';
                         
             }
            //echo "test".$_SESSION['pateintSubs']['clinicProvider'];
            
          //$clinicProvidersArray = array("" => "Select Provider");
           $clinicProvidersArray = $clinicProviders;//print_r($clinicProvidersArray);exit;
           $replace['clinicProvidersOptions'] = $this->build_select_option($clinicProvidersArray, $_SESSION['clinic']['clinicProvider']);

            $logotx='txlogo';
	    $header_1 = "<div id='line'><div id='".$logotx."' style='float:left; padding-left:55px;' ></div><div id='subheader' style='float:right; padding-right:55px;' ><div id='cliniclogo'></div></div><div style='clear:both;'></div></div>";
	    $replace['header']=$header_1;
            $replace['footer'] = $this->build_template($this->get_template("footer"));    
            $replace['HealthProgramPrice']=$this->config['clinic_price'];
             $replace['HealthProgramDuration']="per User/Month";
            $replace['HealthProgramData'] = "With Tx Xchange, there are no contracts, upfront, training, or support costs. Start today and begin to receive the significant financial, operational, and clinical benefits we provide.";
            $replace['keyFeatures']="<li>Create New Revenue Streams </li>";
            $replace['keyFeatures'].="<li>Increase Patient/Client Retention </li>";
            $replace['keyFeatures'].="<li>Reduce Uncompensated Time </li>";
            $replace['keyFeatures'].="<li>Improve Patient/Client Outcomes</li>";
            $replace['KeyFeatureHeading']="Key Benefits";
            $replace['browser_title'] = "Tx Xchange: Payment Information";
            
            $monthArray = array();
            $monthArray = $this->config['monthsArray'];
            $replace['monthsOptions'] = $this->build_select_option($monthArray, $_SESSION['clinic']['exprMonth']); 
           
            $yearArray = array();
            $yearArray = $this->config['yearArray'];
            $replace['yearOptions'] = $this->build_select_option($yearArray, $_SESSION['clinic']['exprYear']);
            $countryarray=$this->country_list(); 
			$replace['country']=$this->build_select_option($countryarray, $_SESSION['clinic']['country']);
           if($_SESSION['clinic']['country']=='')
            $country_code='US';
		else
	    $country_code=$_SESSION['clinic']['country'];
	    $stateArray=$this->state_list($country_code);
	    $replace['stateOptions']=$this->build_select_option($stateArray, $_SESSION['clinic']['state']);
            //Unsetting the Session variable stored for Remembering field values if any wrong/incomplete info submitted by user.
            //print_r($_SESSION);
           // unset($_SESSION['pateintSubs']);
            $replace['body'] = $this->build_template($this->get_template("payment_clinic"),$replace);
            $this->output = $this->build_template($this->get_template("main"),$replace);
        
    }
function PaypalErroCodeslist($errorCode){
	if($errorCode!='' or $errorCode!='0' ){
		$sql="select * from paypal_error_code where error_code='{$errorCode}'";
		$result=$this->execute_query($sql);
		if($this->num_rows($result)>0){
			$row=$this->fetch_object($result);
			$customerrormessage=$row->long_message;
		}else{
			$customerrormessage="Invalid Credit Card Details";
		}
	}else{
		$customerrormessage="Invalid Credit Card Details";
	}
	return $customerrormessage;
}

function submit_clinic_payment(){
        
    //Configuration settings...
            $API_UserName       =       urlencode($this->config["clinicpaypalprodetails"]["API_UserName"]);
            $API_Password       =       urlencode($this->config["clinicpaypalprodetails"]["API_Password"]);
            $API_Signature      =       urlencode($this->config["clinicpaypalprodetails"]["API_Signature"]);
            $environment        =       urlencode($this->config["clinicpaypalprodetails"]["environment"]);
            $currencyID         =       urlencode($this->config["clinicpaypalprodetails"]["currencyID"]);
            $Frequency          =       urlencode($this->config["clinicpaypalprodetails"]["billingPeriod"]);
            $billingFreq        =       urlencode($this->config["clinicpaypalprodetails"]["billingFreq"]);

            $creditCardType     =       $this->value('cardType');
            $creditCardNumber   =       $this->value("cardNumber");
            $expDateMonth       =       $this->value('exprMonth');
            $expDateYear        =       $this->value('exprYear');
            $cvv2Number         =       $this->value('cvvNumber');
            $address1           =       $this->value('address1');
            $address2           =       $this->value('address2');
            $city               =       $this->value('city');
            $state              =       $this->value('state');
            $zipcode            =       $this->value('zipcode');
            $country            =       $this->value('country');
            $cardPayment        =         $this->value('cardPayment');
            //Code To Validate User Entered Data
            $error=array();
            
            if(trim($creditCardNumber)=="" || strlen(trim($creditCardNumber))<14 || strlen(trim($creditCardNumber))>16){
                $error['cnumberValid']="Please enter valid credit card number";
            }
            
            if(trim($creditCardType)==""){
                $error['ctypeValid']="Please select credit card type";
            }

            if(trim($expDateMonth)=="" || trim($expDateYear)=="" ){
                $error['cexpValid']="Please select credit card expiration";
            }
                        
            if(trim($cvv2Number)==""){
                $error['ccvvValid']="Please enter CVV number";
            }else if(!is_numeric(trim($cvv2Number))){
                $error['ccvvValid']="Please enter valid CVV number";
            }
            if(trim($address1)==""){
                $error['address1Valid']="Please enter address1";
            }
            if(trim($city)==""){
                $error['cityValid']="Please enter city";
            }
            if(trim($state)==""){
                $error['stateValid']="Please select state/province";
            }
            if(trim($zipcode)==""){
                $error['zipcodeValid']="Please enter zip/postal code";
            }
            /**
           return '1'// do only payment                
           return '2';//subscription exist
           return '3';//exist other clinic
           return '4';//exist not a patient
           return '5';// email not exist create health record and mank payment
                      
             * 
             * */ 
             
             
            if(!empty($error)){
                $messageReturn="&validData=true";
                foreach($error as $key=>$value)   {
                    $messageReturn.="&".$key."=".urlencode($value);
                }
               header("location:index.php?action=payment_clinic".$messageReturn);
               die();
            }

            
            $_SESSION['clinic']['cardPayment']=$_REQUEST['cardPayment'];
            $_SESSION['clinic']['cardType']=$_REQUEST['cardType'];
            $_SESSION['clinic']['cardNumber']=$_REQUEST['cardNumber'];
            $_SESSION['clinic']['cvvNumber']=$_REQUEST['cvvNumber'];
            $_SESSION['clinic']['exprMonth']=$_REQUEST['exprMonth'];
            $_SESSION['clinic']['exprYear']=$_REQUEST['exprYear'];
            $_SESSION['clinic']['address1']=$_REQUEST['address1'];
            $_SESSION['clinic']['address2']=$_REQUEST['address2'];
            $_SESSION['clinic']['city']=$_REQUEST['city'];
            $_SESSION['clinic']['state']=$_REQUEST['state'];
            $_SESSION['clinic']['country']=$_REQUEST['country'];
            $_SESSION['clinic']['zipcode']=$_REQUEST['zipcode'];
            
        /*pay pal code $paymentType 0 for recurring and 1 for onetime payment*/
                $paymentAmount = urlencode($this->value('cardPayment'));
                $currencyID = urlencode($currencyID);// or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
                $startDate = urlencode(date("Y-m-d")."T0:0:0");
                $billingPeriod = urlencode($Frequency);                // or "Day", "Week", "SemiMonth", "Year"
                $billingFreq = urlencode($billingFreq); // combination of this and  must be at most a year
                $clinicName=html_entity_decode($_SESSION['clinic']['business_name'],ENT_QUOTES, "UTF-8");
                $desc=urlencode($clinicName.' - '.html_entity_decode('clinic register',ENT_QUOTES, "UTF-8"));
 		$BillingstreetAddr=urlencode($address1);
                $Billingstreet2Addr=urlencode($address2);
                $BillingCity=urlencode($city);
                $BillingState=urlencode($state);
                $BillingCountry=urlencode($country);
                $Billingzip=urlencode($zipcode);
                
                @header("Location: /index.php?action=freetrial_register");
                die;
                //
                //create account and provider with inactive user
                //use one time payment
                //if sucess the create recurring payment
                //active clinic and activate provider
                
                ////////////////////
                       
                   
       }
    
function submit_clinic_onetime_subscription($nvpStr,$new_provider_id,$clinic_id, $cid,$newuser) {
    
            $API_UserName       =   urlencode($this->config["clinicpaypalprodetails"]["API_UserName"]);
            $API_Password       =   urlencode($this->config["clinicpaypalprodetails"]["API_Password"]);
            $API_Signature      =   urlencode($this->config["clinicpaypalprodetails"]["API_Signature"]);
            $environment        =   urlencode($this->config["clinicpaypalprodetails"]["environment"]);
            $currencyID         =   urlencode($this->config["clinicpaypalprodetails"]["currencyID"]);
            $Frequency          =   urlencode($this->config["clinicpaypalprodetails"]["billingPeriod"]);
            $billingFreq        =   urlencode($this->config["clinicpaypalprodetails"]["billingFreq"]);
            $userInfor          =   $this->userInfo($new_patient_id);
            $paymentAmount      =   $_SESSION['clinic']['cardPayment'];
            $creditCardType     =   $_SESSION['clinic']['cardType'];
            $creditCardNumber   =   $_SESSION['clinic']['cardNumber'];
            $expDateMonth       =   $_SESSION['clinic']['exprMonth'];
            $expDateYear        =   $_SESSION['clinic']['exprYear'];
            $cvv2Number         =   $_SESSION['clinic']['cvvNumber'];
            $firstName          =   $_SESSION['clinic']['name_first'];
            $lastName           =   $_SESSION['clinic']['name_last'];
            $address1           =   $_SESSION['clinic']['address1'];
            $address2           =   $_SESSION['clinic']['address2'];
            $city               =   $_SESSION['clinic']['city'];            
            $state              =   $_SESSION['clinic']['state'];
            $zipcode            =   $_SESSION['clinic']['zipcode'];
            $BillingCountry     =   $_SESSION['clinic']['country'];
            $country            =   $_SESSION['clinic']['country'];
            $emailaddress       =   $_SESSION['clinic']['username'];
            $desc               =   urlencode($_SESSION['clinic']['business_name']);
            $BillingstreetAddr  =   urlencode($address1);
            $cid                =   $clinic_id;
            $paymentType        =   'onetime';
            $onetime_price      =   $_SESSION['clinic']['cardPayment'];
//echo $nvpStr;
                            // echo $nvpStrTest;
            $paypalProClass=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
           // print_r($paypalProClass);
            
            // Paypal API request For Recurring profile creation.
            
            $httpParsedResponseAr = $paypalProClass->hash_call('doDirectPayment', $nvpStr);            
            /* echo "<pre>";
            print_r($httpParsedResponseAr);
            echo "</pre>";
            die;*/
            // Code to fill history Log of Profile creation
            $this->paypalHistory($httpParsedResponseAr,$new_provider_id,$httpParsedResponseAr['TRANSACTIONID'],'onetime');
            
           
            // Check the status of the payment.If success OR success with warning then change status and update database and make insertions
            if(strtolower($httpParsedResponseAr['ACK'])=="success" || strtolower($httpParsedResponseAr['ACK'])=="successwithwarning") {
                // Make the database changes and redirect to the home page.
                $this->saveDataSubscriptionOneTime(trim(urldecode($httpParsedResponseAr['TRANSACTIONID'])),$startDate,$new_provider_id,$clinic_id);
               // header("location:index.php?action=login"); 
                /*if($newuser==1) {
                        $this->sendmailtopaitentcreate($new_patient_id,$clinic_id);
                        $error['registered']="registration complete.";
                } else {
                         $error['subscribed']="successfully subscribed.";
                }*/
                if(!empty($error)){
                $messageReturn="&validData=true";
                foreach($error as $key=>$value)   {
                    $messageReturn.="&".$key."=".urlencode($value);
                }
                        header("location:/index.php?action=payment_clinic&cid=2".$messageReturn);
                        die();
                }

                return 'sucess';
            }
            else
            { 
                $errorCode=$httpParsedResponseAr['L_ERRORCODE0'];   
                //Delete the newly created patient record
                $querydelPatient = "DELETE FROM user WHERE user_id = '{$new_provider_id}'";
                $this->execute_query($querydelPatient);

                //Delete the newly created patient record from therapist patient
               // $querydelTherapistPatient = "DELETE FROM therapist_patient WHERE patient_id  = '{$new_patient_id}'";
               // $this->execute_query($querydelTherapistPatient);

                //Delete the newly created patient record from therapist patient
                $querydelClinicUser = "DELETE FROM clinic_user WHERE user_id = '{$new_provider_id}'";
                $this->execute_query($querydelClinicUser); 

               header("location:/index.php?action=payment_clinic&cid=3&validData=true&errorCode={$errorCode}");  
               die;
            }
           
        }    

   /**
        * This is used to update the databse when paypal request is received
        * @param Array $responseArray
        * @param Integer $user_id
        * @param varchar $profile_id
        * @param Varchar $data_type
        * @return void
        * @access public
        */
        
        public function paypalHistory($responseArray,$user_id,$profile_id,$data_type){
            // Code To add New data column txn_type
            $txn_type=$responseArray['txn_type'];
            $serializeData=$this->encrypt_data(serialize($responseArray));
                        //Change in txn_type
                        $queryInsertHistory=$this->execute_query("insert into paypal_history_clinic set paypal_profile_number='{$profile_id}', user_id='{$user_id}',return_data='{$serializeData}',data_type='{$data_type}' , status_time= now(),txn_type='{$txn_type}' ");
                        //$queryInsertHistory=$this->execute_query("insert into paypal_history set paypal_profile_number='{$profile_id}', user_id='{$user_id}',return_data='{$serializeData}',data_type='{$data_type}' , status_time= now() ");
                        
                
                
        
        }
/**
         * This function saves the Patient information and update the database as subscription purchased.
         * Also the end date is set to one month more than subscription start date.
         * @param 
         * @return array $subscriptionInfo
         * @access public
         */  
                
        function saveDataSubscriptionOneTime($profileNumber,$startDate,$new_provider_id,$clinic_id){
            // Gather information from REQUEST
            $paymentAmount      =   $_SESSION['clinic']['cardPayment'];
            $creditCardType     =   $_SESSION['clinic']['cardType'];
            $creditCardNumber   =   $_SESSION['clinic']['cardNumber'];
            $expDateMonth       =   $_SESSION['clinic']['exprMonth'];
            $expDateYear        =   $_SESSION['clinic']['exprYear'];
            $cvv2Number         =   $_SESSION['clinic']['cvvNumber'];
            $firstName          =   $_SESSION['clinic']['name_first'];
            $lastName           =   $_SESSION['clinic']['name_last'];
            $address1           =   $_SESSION['clinic']['address1'];
            $address2           =   $_SESSION['clinic']['address2'];
            $city               =   $_SESSION['clinic']['city'];            
            $state              =   $_SESSION['clinic']['state'];
            $zipcode            =   $_SESSION['clinic']['zipcode'];
            $BillingCountry     =   $_SESSION['clinic']['country'];
            $country            =   $_SESSION['clinic']['country'];
            $email              =   $_SESSION['clinic']['username'];
            $desc               =   urlencode($_SESSION['clinic']['business_name']);
            $userInfor=$this->userInfo($new_provider_id);
            $clinicID=$clinic_id;
            $insertName=$firstName.' '.$lastName;
            $Frequency=urlencode($this->config["clinicpaypalprodetails"]["billingPeriod"]);
            $billingFreq=urlencode($this->config["clinicpaypalprodetails"]["billingFreq"]);
            
            //Query Health Program Details
            $querySubscriptEntry="insert into provider_subscription set `clinic_id`='{$clinicID}',`user_id`='{$new_provider_id}',`subs_datetime`=now(), `subscription_title`='{$desc}' ,`subs_status`='1',`subs_end_datetime`=DATE_ADD(now(), INTERVAL {$billingFreq} {$Frequency}),`address1`='{$address1}',`address2`='{$address2}',`city`='{$city}',`state`='{$state}',`zipcode`='{$zipcode}',`country`='{$country}',`onetime_paypal_profile`= '{$profileNumber}',`b_first_name`='{$firstName}',`b_last_name`='{$lastName}',`ehsTimePeriod`='{$billingFreq}', `paymentType`='onetime',`onetime_price`='{$paymentAmount}'";
            $result=$this->execute_query($querySubscriptEntry);
            $subs_insert_id=$this->insert_id();
            $data=array('provider_subscription_user_subs_id'=> $subs_insert_id,
                'user_id'=>$new_provider_id,
                'user_subs_id'=>$subs_insert_id,
                'payment_paypal_profile'=>$profileNumber,
                'paymnet_datetime'=>date('Y-m-d:e:h:s'),
                'payment_price'=>$paymentAmount,
                'payment_b_name'=>$firstName.' '.$lastName,
                'paymnet_b_email'=>$email,
                'paymnet_status'=>1,
                'payment_txn_type'=>'onetime'
                );
                $this->insert('provider_payment',$data);
}

/**
         * Function to validate Credit Card Infromation and create Recurring CC Profile.
         * @access public
         */
        function submit_provider_subscription($nvpStr,$new_provider_id,$clinic_id, $cid,$newuser)
        {
            $userInfor=$this->userInfo($new_provider_id);
            $API_UserName=urlencode($this->config["clinicpaypalprodetails"]["API_UserName"]);
            $API_Password=urlencode($this->config["clinicpaypalprodetails"]["API_Password"]);
            $API_Signature=urlencode($this->config["clinicpaypalprodetails"]["API_Signature"]);
            $environment=urlencode($this->config["clinicpaypalprodetails"]["environment"]);
            $currencyID=urlencode($this->config["clinicpaypalprodetails"]["currencyID"]);
            $Frequency=urlencode($this->config["clinicpaypalprodetails"]["billingPeriod"]);
            $billingFreq=urlencode($this->config["clinicpaypalprodetails"]["billingFreq"]);
            
            $paymentAmount      =   $_SESSION['clinic']['cardPayment'];
            $creditCardType     =   $_SESSION['clinic']['cardType'];
            $creditCardNumber   =   $_SESSION['clinic']['cardNumber'];
            $expDateMonth       =   $_SESSION['clinic']['exprMonth'];
            $expDateYear        =   $_SESSION['clinic']['exprYear'];
            $cvv2Number         =   $_SESSION['clinic']['cvvNumber'];
            $firstName          =   $_SESSION['clinic']['name_first'];
            $lastName           =   $_SESSION['clinic']['name_last'];
            $address1           =   $_SESSION['clinic']['address1'];
            $address2           =   $_SESSION['clinic']['address2'];
            $city               =   $_SESSION['clinic']['city'];            
            $state              =   $_SESSION['clinic']['state'];
            $zipcode            =   $_SESSION['clinic']['zipcode'];
            $BillingCountry     =   $_SESSION['clinic']['country'];
            $country            =   $_SESSION['clinic']['country'];
            $email              =   $_SESSION['clinic']['username'];
            $emailaddress       =   $_SESSION['clinic']['username'];
            $desc               =   urlencode($_SESSION['clinic']['business_name']);
            $cid                =   $clinic_id;
            
            $ehsTimePeriod      =       0;
            $paymentType        =       0;
            $onetime_price      =       0;            


                //echo $nvpStr."<br>"; 
            // Paypal Recurring Object
            $paypalProClass=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
            $userInfor=$this->userInfo();
            // Paypal API request For Recurring profile creation.
            $insertArr['user_Id']=$new_provider_id;
            $insertArr['clinic_id']=$clinic_id;
            $insertArr['date']=date("Y-m-d H:i:s");
            $sqlrequest="select * from subscription_request where user_Id='{$new_provider_id}' and clinic_id='{$clinic_id}' and status='1'";
	    	$requestquery=$this->execute_query($sqlrequest);
	    	if($this->num_rows($requestquery)==0){	
            		$this->insert('subscription_request',$insertArr);
            		$statusid=$this->insert_id();
            		$httpParsedResponseAr = $paypalProClass->PPHttpPost('CreateRecurringPaymentsProfile', $nvpStr);            
            		$this->paypalHistory($httpParsedResponseAr,$new_provider_id,trim(urldecode($httpParsedResponseAr['PROFILEID'])),'signup');
            		// Check the status of the payment.If success OR success with warning then change status and update database and make insertions
            		if(strtolower($httpParsedResponseAr['ACK'])=="success" || strtolower($httpParsedResponseAr['ACK'])=="successwithwarning")    {
            		    // Make the database changes and redirect to the home page.
            		    $this->saveDataSubscription(trim(urldecode($httpParsedResponseAr['PROFILEID'])),$startDate,$new_provider_id,$clinic_id);
            			// header("location:index.php?action=login"); 

        		       $this->execute_query("update subscription_request set status='2' where id='".$statusid."'"); 

        		        if(!empty($error)){
        			        $messageReturn="&validData=true";
        			        foreach($error as $key=>$value)   {
			                    $messageReturn.="&".$key."=".urlencode($value);
                			}
		                        header("location:/index.php?action=payment_clinic&cid=4".$messageReturn);
                		        die();
                		}
                		//die();
            		}
           		 else
           		{
              
                		$errorCode=$httpParsedResponseAr['L_ERRORCODE0'];
				$this->execute_query("update subscription_request set status='3' where id='".$statusid."'");    
                		header("location:index.php?action=payment_clinic&cid=5&validData=true&errorCode={$errorCode}");    
            		}
            	}else{
               	 	header("location:/index.php?action=payment_clinic&cid=6&validData=true&errorCode={$errorCode}");    
            	}
           
        }

         /**
         * This function saves the Patient information and update the database as subscription purchased.
         * Also the end date is set to one month more than subscription start date.
         * @param 
         * @return array $subscriptionInfo
         * @access public
         */  
                
        function saveDataSubscription($profileNumber,$startDate,$new_provider_id,$clinic_id){
            // Gather information from REQUEST
            $userInfor=$this->userInfo($new_provider_id);
            $clinicID=$clinic_id;
            $firstName = $this->value('firstname');
            $lastName = $this->value('lastname');
            $insertName=$firstName.' '.$lastName;
            $HealthProgramID = $this->value('HealthProgramID');
            $email=$this->userInfo('username',$new_provider_id);
            $paymentAmount = $this->value('cardPayment'); 
            
            $Frequency=urlencode($this->config["clinicpaypalprodetails"]["billingPeriod"]);
            $billingFreq=urlencode($this->config["clinicpaypalprodetails"]["billingFreq"]);
            $address1=$this->value('address1');
            $address2=$this->value('address2');
            $city=$this->value('city');
            $state=$this->value('state');
            $zipcode=$this->value('zipcode');
            $country=$this->value('country');
            
            //Query Health Program Details
            $querySubscriptEntry="update provider_subscription set `reurring_profile_id`='{$profileNumber}' where `clinic_id`='{$clinicID}' and `user_id`='{$new_provider_id}'";
            $result=$this->execute_query($querySubscriptEntry);
       }

          public function ipnPaymentclinicServices(){
                // Fetch the request variables.
                $requestData=$_REQUEST;
                print_r($requestData);
                   // Log For IPN Request
                   $fp=fopen("asset/ipnclinicLogFiles/".$requestData['recurring_payment_id'].time().".txt","w+");
                   fwrite($fp,date('Y-m-d H:m')." ############################################# "."\n");
                   foreach($requestData as $key=>$value){   
                            fwrite($fp,$key."=".$value."\n");
                   } 
                   fclose($fp);

                // Code to fill history Log of Profile creation
                $userInfor=$this->userInfo();
                $this->paypalHistory($requestData,'',trim(urldecode($requestData['recurring_payment_id'])),'payment');

                // Fetch the Paymnet Status.
                $payment_status=urldecode($requestData['payment_status']);
                
                // Code For txn_type
                $payment_txn_type=urldecode($requestData['txn_type']);
                
                // Fetch the Profile ID assocuiated.
                $Paypal_Profile_Id=urldecode($requestData['recurring_payment_id']);

                // Fetch The Payment.
                $next_payment_date=urldecode($requestData['next_payment_date']);
                $next_payment_date=date('Y-m-d H:i:s',strtotime($next_payment_date));
                $payment_price=$amountPaid=$requestData['amount'];
                $paymnet_b_email=$buyerEmail=$requestData['payer_email'];
                $payment_b_name=$requestData['first_name'].' '.$requestData['last_name'];
                // Block To check Sucessfull Paymnet Made from Paypal
                if(strtolower($payment_status)=='completed'){
                //Query To fetch the Profile Data
                $querySelectSubscription=$this->execute_query("select * from  provider_subscription where reurring_profile_id = '{$Paypal_Profile_Id}' ");
                if($this->num_rows($querySelectSubscription)>0){
                    $resultSelectSubscription=$this->fetch_object($querySelectSubscription);
                    $user_id=$resultSelectSubscription->user_id;
                    $patient_subscription_user_subs_id=$resultSelectSubscription->user_subs_id;     //user_subs_id
                    $user_subs_id=$resultSelectSubscription->user_subs_id;                          //user_subs_id
                    $payment_price=$resultSelectSubscription->subs_price;                          //user_subs_id
                    
                    //Send Email To Patient About the Payment.
                    
                    // Check Top stop the payment email for the first time                    
                    $checkQuery=$this->execute_query("select count(user_id) as CNT  from provider_payment where payment_paypal_profile = '{$Paypal_Profile_Id}'  AND user_id='{$user_id}' ");
                    $resultCheckQuery=$this->fetch_object($checkQuery);
                    if($resultCheckQuery->CNT > 0 )       {
                            $resultHealthProgram['subscription_title']=$resultSelectSubscription->subscription_title;           //Service Name
                            $resultHealthProgram['subs_price']=$payment_price;                                                  //Service Price
                            $clinic_id=$subscriptionDetail->clinic_id;                                                          //Clinic ID
                            $userInfor['user_id']=$user_id;
                            $userInfor['username']=$this->userInfo('username',$user_id);
                            $this->sendEmailPaymentprovider($clinic_id,$userInfor,$resultHealthProgram);
                    
                    }

                        // Query To update the Patient Payment databse Table
                        $updatePaymnetDatabase=$this->execute_query("update provider_payment set paymnet_status = '0' where payment_paypal_profile= '{$Paypal_Profile_Id}' ");
                        // Change in query to add txn_type
                        $updatePaymnetDatabase=$this->execute_query("insert into provider_payment set payment_paypal_profile = '{$Paypal_Profile_Id}', 
                                                                                                                      user_id='{$user_id}',
                                                                                           provider_subscription_user_subs_id='{$patient_subscription_user_subs_id}',
                                                                                                                 user_subs_id='{$user_subs_id}',
                                                                                                               payment_b_name='{$payment_b_name}',
                                                                                                              paymnet_b_email='{$paymnet_b_email}',
                                                                                                                payment_price='{$payment_price}' ,
                                                                                                               paymnet_status= '1',
                                                                                                             paymnet_datetime= now() , 
                                                                                                             payment_txn_type='{$payment_txn_type}' ");
                        //$updatePaymnetDatabase=$this->execute_query("insert into patient_payment set payment_paypal_profile = '{$Paypal_Profile_Id}', user_id='{$user_id}',patient_subscription_user_subs_id='{$patient_subscription_user_subs_id}',user_subs_id='{$user_subs_id}',payment_b_name='{$payment_b_name}',paymnet_b_email='{$paymnet_b_email}',payment_price='{$payment_price}' ,paymnet_status= '1',paymnet_datetime=now() ");
                        // Query To update Patient Subscription Table
                        $updatePatientSubscriptionDatabase=$this->execute_query("update provider_subscription set subs_status='1',
                                                                                                                  subs_datetime=now(),
                                                                                                                  subs_end_datetime='{$next_payment_date}' 
                                                                                                                  where user_subs_id='{$patient_subscription_user_subs_id}' ");

                        }
                }/*elseif(strtolower($payment_status)!=''){
                // Block to update status when payment not sucessfull
                $querySelectSubscription=$this->execute_query("select * from patient_subscription where payment_paypal_profile = '{$Paypal_Profile_Id}' ");
                if($this->num_rows($querySelectSubscription)>0){
                        $resultSelectSubscription=$this->fetch_object($querySelectSubscription);
                        $patient_subscription_user_subs_id=$resultSelectSubscription->user_subs_id;
                        // Query To update Patient Subscription Table
                        $updatePatientSubscriptionDatabase=$this->execute_query("update patient_subscription set subs_status='2' where user_subs_id='{$patient_subscription_user_subs_id}' ");
                       }                    
                }  */
                
                    // Changes for implementing txn_type
                else{
                        // Changes for implementing txn_type ,  Max Attempts made
                        if($payment_txn_type=='recurring_payment_suspended_due_to_max_failed_payment' || $payment_txn_type=='recurring_payment_failed'){
                        	
                            // Block to update status when payment not sucessfull after three attempts
                            $querySelectSubscription=$this->execute_query("select * from provider_subscription where payment_paypal_profile = '{$Paypal_Profile_Id}' ");
                            if($this->num_rows($querySelectSubscription)>0){
                                $resultSelectSubscription=$this->fetch_object($querySelectSubscription);
                                $user_id=$resultSelectSubscription->user_id;                                                        //user_id
                                $patient_subscription_user_subs_id=$resultSelectSubscription->user_subs_id;   //user_subs_id
                                $user_subs_id=$resultSelectSubscription->user_subs_id;  
                                // Query To update Patient Subscription Table
                                $updatePatientSubscriptionDatabase=$this->execute_query("update provider_subscription set subs_status='3' where user_subs_id='{$patient_subscription_user_subs_id}' ");
                                //send a mail for supended user
      			    		$clinicName=html_entity_decode($this->get_clinic_info($resultSelectSubscription->user_id,"clinic_name"), ENT_QUOTES, "UTF-8");
                    			$replacePatient['images_url']=$this->config['images_url'];
                    			$Patientsubject = 'Your Tx Xchange Account is Inactive';
                   			$replacePatient['clinicName']=$clinicName;
	                    		// Content Of the email send to  patient
                    			$Patientmessage = $this->build_template($this->get_template("clinicprofileSuspendedtx"),$replacePatient);
                    			$userInfor['username']=$this->userInfo('username',$user_id);
                    			$Patientto = $userInfor['username'];
                    			// To send HTML mail, the Content-type header must be set

                    			$Patientheaders  = 'MIME-Version: 1.0' . "\n";
                    			$Patientheaders .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                   			$Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8"). " <".$this->config['email_tx'].">" . "\n";
                        		$Patientreturnpath = "-f".$this->config['email_tx'];  
                        	    // Mail it
                                        @mail($Patientto, $Patientsubject, $Patientmessage, $Patientheaders, $Patientreturnpath); 
                   			} 
                                        $clinicid=$resultSelectSubscription->clinic_id;
                                        $responce=$this->unsubscribeClinicEHSPatients($clinicid);
                                        
                                        
                      }
                }
                if($payment_txn_type=='recurring_payment_skipped'){
                 $payer_email=$requestData['payer_email'];
                 $querySelectSubscription=$this->execute_query("select * from provider_subscription where payment_paypal_profile = '{$Paypal_Profile_Id}' ");
                if($this->num_rows($querySelectSubscription)>0){
                    $resultSelectSubscription=$this->fetch_object($querySelectSubscription);
		    		$clinicName=html_entity_decode($this->get_clinic_info($resultSelectSubscription->user_id,"clinic_name"), ENT_QUOTES, "UTF-8");
                    // Send Email To Patients 
                    $replacePatient['images_url']=$this->config['images_url'];
                    $Patientsubject = "Your Payment to Tx Xchange Failed";
                    $replacePatient['ServiceName']=html_entity_decode($resultSelectSubscription->subscription_title, ENT_QUOTES, "UTF-8");
                    $replacePatient['clinicName']=$clinicName;
                    $user_id=$resultSelectSubscription->user_id; 
                    // Content Of the email send to  patient
                    $Patientmessage = $this->build_template($this->get_template("clinicprofileSkipedtx"),$replacePatient);
                    
                    $userInfor['username']=$this->userInfo('username',$user_id);
                    $Patientto = $userInfor['username'];
                    // To send HTML mail, the Content-type header must be set

                    $Patientheaders  = 'MIME-Version: 1.0' . "\n";
                    $Patientheaders .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                   // $Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8")." <support@txxchange.com>" . "\n";
                    //$Patientreturnpath = '-fsupport@txxchange.com';
                        $Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8"). " <".$this->config['email_tx'].">" . "\n";
                        $Patientreturnpath = "-f".$this->config['email_tx'];
                    // Mail it
                    @mail($Patientto, $Patientsubject, $Patientmessage, $Patientheaders, $Patientreturnpath); 
                    }
                	
                }
                
         }    
       
         /**
         * This function sends Extenal mail when Payment is processed from paypal:(E-Health Service Name) Monthly Charge To Patients
         * @param Integer $clinic_id
         * @param Integer $user_id
         * @param Object  $resultHealthProgram
         * @return void
         * @access public
         */
        
         public function sendEmailPaymentprovider($clinic_id,$userInfor,$resultHealthProgram){

                    $clinicName=html_entity_decode($this->get_clinic_info($userInfor['user_id'],"clinic_name"), ENT_QUOTES, "UTF-8");

                    // Send Email To Patients 
                    $replacePatient['images_url']=$this->config['images_url'];
                    $Patientsubject = html_entity_decode($resultHealthProgram['subscription_title'], ENT_QUOTES, "UTF-8")." Monthly Charge";
                    $replacePatient['ServiceName']=html_entity_decode($resultHealthProgram['subscription_title'], ENT_QUOTES, "UTF-8");
                    $replacePatient['ClinicName']=$clinicName;
                    $replacePatient['price']='\$'.$resultHealthProgram['subs_price'];

                    // Content Of the email send to  patient
                    //$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
                  //  if( $clinic_channel == 1)
                    $Patientmessage = $this->build_template($this->get_template("clinicUserPaymentMailContent"),$replacePatient);
                   // else
                   // $Patientmessage = $this->build_template($this->get_template("PatientUserPaymentMailContentwx"),$replacePatient);
                    $Patientto = $userInfor['username'];
                    // To send HTML mail, the Content-type header must be set

                    $Patientheaders  = 'MIME-Version: 1.0' . "\n";
                    $Patientheaders .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                    //$Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8")." <support@txxchange.com>" . "\n";
                    //$Patientreturnpath = '-fsupport@txxchange.com';

                    // Mail it
                     //   if( $clinic_channel == 1){
                        $Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8"). " <".$this->config['email_tx'].">" . "\n";
                        $Patientreturnpath = "-f".$this->config['email_tx'];
                       // }else{
                        //$Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8"). " <".$this->config['email_wx'].">" . "\n";
                        //$Patientreturnpath = '-f'.$this->config['email_wx'];   
                       // }
                    @mail($Patientto, $Patientsubject, $Patientmessage, $Patientheaders, $Patientreturnpath);                    
        }  
        
          /**
         * This function returns the subscription information based on clinic id associated with the Patients.
         * @param Integer $clinic_id
         * @return array $subscriptionInfo
         * @access public
         */ 
         
         function show_Privacy_policy(){
                  $this->output = $this->build_template($this->get_template("show_Privacy_policy"),$replace);
         }
         
         
         	function clinicsucesspaymentmessage() {
                       $ehsname = $_REQUEST['ehsname'];                
                       $replace['alreadysub']="You have successfully signed-up for your account. Please check your email for temporary login information and how to get started. Thanks.";
                       $replace['body'] = $this->build_template($this->get_template("clinicsucesspaymentmessage"),$replace);
                       $this->output = $this->build_template($this->get_template("main"),$replace);

                }
                
            function submit_update_clinic_card(){
            //Configuration settings...
            $API_UserName       =       urlencode($this->config["clinicpaypalprodetails"]["API_UserName"]);
            $API_Password       =       urlencode($this->config["clinicpaypalprodetails"]["API_Password"]);
            $API_Signature      =       urlencode($this->config["clinicpaypalprodetails"]["API_Signature"]);
            $environment        =       urlencode($this->config["clinicpaypalprodetails"]["environment"]);
            $currencyID         =       urlencode($this->config["clinicpaypalprodetails"]["currencyID"]);
            $Frequency          =       urlencode($this->config["clinicpaypalprodetails"]["billingPeriod"]);
            $billingFreq        =       urlencode($this->config["clinicpaypalprodetails"]["billingFreq"]);
            $_SESSION['clinic']['cardPayment'] =$_REQUEST['cardPayment'];
            $_SESSION['clinic']['cardType']=$_REQUEST['cardType'];
            $_SESSION['clinic']['cardNumber']=$_REQUEST['cardNumber'];
            $_SESSION['clinic']['cvvNumber']=$_REQUEST['cvvNumber'];
            $_SESSION['clinic']['exprMonth']=$_REQUEST['exprMonth'];
            $_SESSION['clinic']['exprYear']=$_REQUEST['exprYear'];
            $_SESSION['clinic']['address1']=$_REQUEST['address1'];
            $_SESSION['clinic']['address2']=$_REQUEST['address2'];
            $_SESSION['clinic']['city']=$_REQUEST['city'];
            $_SESSION['clinic']['state']=$_REQUEST['state'];
            $_SESSION['clinic']['country']=$_REQUEST['country'];
            $_SESSION['clinic']['zipcode']=$_REQUEST['zipcode'];
            $creditCardType     =       $this->value('cardType');
            $creditCardNumber   =       $this->value("cardNumber");
            $expDateMonth       =       $this->value('exprMonth');
            $expDateYear        =       $this->value('exprYear');
            $cvv2Number         =       $this->value('cvvNumber');
            $address1           =       $this->value('address1');
            $address2           =       $this->value('address2');
            $city               =       $this->value('city');
            $state              =       $this->value('state');
            $zipcode            =       $this->value('zipcode');
            $country            =       $this->value('country');
            $cardPayment        =       $this->value('cardPayment');
            $clinic_id        =         $this->value('clinic_id');
            //Code To Validate User Entered Data
            $error=array();
            
            if(trim($creditCardNumber)=="" || strlen(trim($creditCardNumber))<14 || strlen(trim($creditCardNumber))>16){
                $error['cnumberValid']="Please enter valid credit card number";
            }
            
            if(trim($creditCardType)==""){
                $error['ctypeValid']="Please select credit card type";
            }

            if(trim($expDateMonth)=="" || trim($expDateYear)=="" ){
                $error['cexpValid']="Please select credit card expiration";
            }
                        
            if(trim($cvv2Number)==""){
                $error['ccvvValid']="Please enter CVV number";
            }else if(!is_numeric(trim($cvv2Number))){
                $error['ccvvValid']="Please enter valid CVV number";
            }
            if(trim($address1)==""){
                $error['address1Valid']="Please enter address1";
            }
            if(trim($city)==""){
                $error['cityValid']="Please enter city";
            }
            if(trim($state)==""){
                $error['stateValid']="Please select state/province";
            }
            if(trim($zipcode)==""){
                $error['zipcodeValid']="Please enter zip/postal code";
            }
            if(!empty($error)){
                $messageReturn="&validData=true";
                foreach($error as $key=>$value)   {
                    $messageReturn.="&".$key."=".urlencode($value);
                }
               header("location:index.php?action=updatecliniccreditcard&cid=1".$messageReturn.'&clinic_id='.$_REQUEST['clinic_id']);
               die();
            }

            
            
                 //////////////////
                //1) get clinic paypal profile
                $sql="select * from provider_subscription  where clinic_id =".$clinic_id." order by user_subs_id limit 0,1";
                $query=$this->execute_query($sql);
                if($this->num_rows($query)==1){
                    $row=$this->fetch_object($query);
                }
                    $Paypal_Profile=$row->reurring_profile_id; 
                    $userInfor          =   $this->userInfo();
                    $clinicName         =   html_entity_decode($this->get_clinic_info($userInfor['user_id'],"clinic_name"), ENT_QUOTES, "UTF-8");
                    $desc               =   urlencode($clinicName);
                    $profileId          =   urlencode($Paypal_Profile);
                    $BillingstreetAddr  =   urlencode($address1);
                    $Billingstreet2Addr =   urlencode($address2);
                    $BillingCity        =   urlencode($city);
                    $BillingState       =   urlencode($state);
                    $BillingCountry     =   urlencode($country);
                    $Billingzip         =   urlencode($zipcode);
                    $emailaddress       =   $this->userInfo('username',$user_id);
        /*pay pal code $paymentType 0 for recurring and 1 for onetime payment*/
                    $paymentAmount      =   urlencode($this->value('cardPayment'));
                    $currencyID         =   urlencode($currencyID);// or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
                    $startDate          =   urlencode(date("Y-m-d")."T0:0:0");
                    $paypalObject       =   new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                    $profileID          =   urlencode($Paypal_Profile);
                    $nvpStr             =   "&PROFILEID=$profileID";
                    $paypalObject       =   new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                    $httpParsedResponseAr = $paypalObject->PPHttpPost('GetRecurringPaymentsProfileDetails', $nvpStr);
                    //$this->printR($httpParsedResponseAr);
                   //2) one time payment $1
                    
                    
                    $nvpStr="&PAYMENTACTION=Sale&AMT=$paymentAmount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=".$expDateMonth.$expDateYear."&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state"."&ZIP=$zipcode&COUNTRYCODE=$BillingCountry&CURRENCYCODE=$currencyID&EMAIL=".$emailaddress."&DESC=".$desc;                    
                    $paypalProClass=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                    $httpParsedResponseonetimeAr = $paypalProClass->hash_call('doDirectPayment', $nvpStr);            
                    /* echo "<pre>";
                    print_r($httpParsedResponseonetimeAr);
                    echo "</pre>";
                    die;*/
                    // Code to fill history Log of Profile creation
                    $this->paypalHistory($httpParsedResponseonetimeAr,$new_provider_id,$httpParsedResponseonetimeAr['TRANSACTIONID'],'onetime');
           // Check the status of the payment.If success OR success with warning then change status and update database and make insertions
                    if(strtolower($httpParsedResponseonetimeAr['ACK'])=="success" || strtolower($httpParsedResponseonetimeAr['ACK'])=="successwithwarning") {
                        
                        
                    // update credit card and refund money
             $fname=$this->userInfo('name_first'); 
             $lname=$this->userInfo('name_last');
             $nvpStr="&FIRSTNAME=$fname&LASTNAME=$lname&PROFILEID=$profileId&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=$expDateMonth$expDateYear&CVV2=$cvv2Number";
             $nvpStr .= "&STREET=$BillingstreetAddr&STREET2=$Billingstreet2Addr&CITY=$BillingCity&STATE=$BillingState&COUNTRYCODE=$BillingCountry&ZIP=$Billingzip";
             $httpParsedResponseupdateAr = $paypalProClass->PPHttpPost('UpdateRecurringPaymentsProfile', $nvpStr);
			
            // Code to fill history Log of Profile creation
            $this->paypalHistory($httpParsedResponseupdateAr,$userInfor['user_id'],trim(urldecode($httpParsedResponseupdateAr['PROFILEID'])),'update');
            
           /* echo "<pre>";
            print_r($httpParsedResponseAr);
            echo "</pre>";
  	   die;`*/    
            // Check the status of the payment.If success OR success with warning then change status and update database and make insertions
            if(strtolower($httpParsedResponseupdateAr['ACK'])=="success" || strtolower($httpParsedResponseupdateAr['ACK'])=="successwithwarning")    {
                    $expdate=date('m').date('Y');
                    $SQL="select * from updated_clinic_credit_card where paypal_id='{$profileId}' and expdate='{$expdate}'";
                    $query=$this->execute_query($SQL);
                    if($this->num_rows($query)>0){
                        $res=$this->fetch_object($query);
            		$update="update updated_clinic_credit_card set status_mail='1' where id='{$res->id}'";
            		$query=$this->execute_query($update);
            		}
                        //refund code
                        $transactionID = $httpParsedResponseonetimeAr['TRANSACTIONID'];
                        $refundType = urlencode('Full');						// or 'Partial'
                        $memo='refund for '.$clinicName;													// required if Partial.
                        // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
                         // Add request-specific fields to the request string.
                        $nvpStr = "&TRANSACTIONID=$transactionID&REFUNDTYPE=$refundType&CURRENCYCODE=$currencyID";

                        if(isset($memo)) {
                                $nvpStr .= "&NOTE=$memo";
                        }

                        // Execute the API operation; see the PPHttpPost function above.
                        $httpParsedResponseRefundAr = $paypalObject->PPHttpPost('RefundTransaction', $nvpStr);

                        if("SUCCESS" == strtoupper($httpParsedResponseRefundAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseRefundAr["ACK"])) {
                               header("location:index.php?action=accountAdminClinicList&cid=2&status=1&clinic_id=".$_REQUEST['clinic_id']);
                               die;
                        } else  {
                                $errorCode=$httpParsedResponseRefundAr['L_ERRORCODE0'];
				header("location:index.php?action=updatecliniccreditcard&cid=3&clinic_id=".$_REQUEST['clinic_id']."&errorCode={$errorCode}");
                                die;
                        }
                }else{
                //die();
                // Payment unsucessfull redirect to the Paymnet page again with error code.
                $errorCode=$httpParsedResponseAr['L_ERRORCODE0'];
                $_SESSION['clinic']['add1']=$address1;
                $_SESSION['clinic']['add2']=$address2;    
                $_SESSION['clinic']['city']=$city;
                $_SESSION['clinic']['state']=$state;
                $_SESSION['clinic']['zip']=$zipcode; 
                $_SESSION['clinic']['country']=$country; 
                $_SESSION['clinic']['cardType']=$creditCardType;
                $_SESSION['clinic']['exprMonth']=$expDateMonth;
                $_SESSION['clinic']['exprYear']=$expDateYear;
                $_SESSION['clinic']['cardNumber']=$creditCardNumber;
                $_SESSION['clinic']['cvvNumber']=$cvv2Number;
                $errorCode=$httpParsedResponseupdateAr['L_ERRORCODE0'];
		header("location:index.php?action=updatecliniccreditcard&cid=4&clinic_id=".$_REQUEST['clinic_id']."&errorCode={$errorCode}");
                die;    
            }
                        
                    }else{
                     $errorCode=$httpParsedResponseonetimeAr['L_ERRORCODE0'];
                    header("location:index.php?action=updatecliniccreditcard&cid=5&clinic_id=".$_REQUEST['clinic_id']."&errorCode={$errorCode}");
                    die;     
                    
                        
                    }
                    
                //
                //3) sucess update cc
                //
                
           
                //4)    cut outstanding amount
                //
                //
                //
                //5)    return amount
                // Set request-specific fields.
                //6)fail show error
                //
                //
                
                
                   
        }    
                
function updateaccountpaypalprofile()
{
            //$this->printR($_SESSION['clinic']); 
		$replace = array();
		 $errorCode=$this->value('errorCode');
                        if($errorCode!='') {
                            $customerrormessage=$this->PaypalErroCodeslist($errorCode);
                            if($customerrormessage=='')                    
                                {
                                    $customerrormessage="Invalid Credit Card Details";                        
                                }
                            $replace['errorMessage']='<tr><td colspan="4" style="height:20px; color:#0069a0;; font-weight:bold"><font style="color:#FF0000;font-weight:normal;">'.$customerrormessage.'</font></td></tr><tr><td colspan="4" style="height:5px; color:#0069a0;; font-weight:bold"></td></tr>';   
                            
                   }
                
                
                $option = $this->value('option');
		$clinic_id = $this->clinicInfo('clinic_id');
		$userInfo = $this->userInfo();
		$cli_type=$this->getClinicDetails($this->userInfo('user_id'));
		//if($cli_type['clinic_type']==4){
		$replace['shopshow']=1;
		$replace['header'] = $this->build_template($this->get_template("header"));
		$replace['sidebar'] = $this->sidebar();
		$replace['clinic_id'] = $clinic_id;
		$replace['subaction'] = $this->value('subaction');
		
		$stateArray = array("" => "Choose State...");
		$stateArray = array_merge($stateArray,$this->config['state']);
		$replace['stateOptions'] = $this->build_select_option($stateArray, $row['clinic_state']);
		//$url_array = $this->tab_url();
		$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
$replace['namefValid']='<font style="color:#FF0000;font-weight:normal;">'.urldecode($this->value('namefValid')).'</font>';
            if($this->value('validData')=='true')   {
                    if(urldecode($this->value('namefValid'))!=''){      
                        $replace['namefValid']='<font style="color:#FF0000;font-weight:normal;">'.urldecode($this->value('namefValid')).'</font>';                                         
                     $replace['retainedFname']='';
                    }     
                      $replace['retainedFname']=$_SESSION['clinic']['fname'] ;                                                                            
                    if(urldecode($this->value('namelValid'))!='')       {
                        $replace['namelValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('namelValid').'</font>';                                                  
                     $replace['retainedlname']='' ; 
                    }
                    $replace['retainedlname']=$_SESSION['clinic']['lname'] ;                     
                     if(urldecode($_REQUEST['emailValid'])!='')       {
                        $replace['emailValid']='<font style="color:#FF0000;font-weight:normal;">'.$_REQUEST['emailValid'].'</font>';                                                  
                    
                     $replace['retainedEmail']='' ; 
                    }
                        $replace['retainedEmail']=$_SESSION['clinic']['emailAddress'] ;  
                                                                    
                    if(urldecode($this->value('cnumberValid'))!='')   {     
                        $replace['cnumberValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cnumberValid').'</font>';
                        $replace['retainedcardNumber'] = '';
                     } 
                                                                                      
                    $replace['retainedcardNumber'] = $_SESSION['clinic']['cardNumber']; 
                                                                                                          
                    if(urldecode($this->value('cexpValid'))!='')  {           
                        $replace['cexpValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cexpValid').'</font>';
                        //$replace['retainedcardNumber'] = '';
                      }                                                          
                    if(urldecode($this->value('ccvvValid'))!='') {            
                        $replace['ccvvValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('ccvvValid').'</font>'; 
                        $replace['retainedcvvNumber'] = '';
                    }                                      
                    $replace['retainedcvvNumber'] = $_SESSION['clinic']['cvvNumber'];                                        
                    if(urldecode($this->value('ctypeValid'))!='') {                
                        $replace['ctypeValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('ctypeValid').'</font>'; 
                        $replace['retainVisaType'] = '';
                        $replace['retainMasterType'] = '';
                     }
                        if($_SESSION['clinic']['cardType'] == 'Visa') {
                                $replace['retainVisaType'] = 'selected="selected"';
                                $replace['retainMasterType'] = '';
                        }
                        elseif($_SESSION['clinic']['cardType'] == 'MasterCard') {
                                $replace['retainMasterType'] = 'selected="selected"';
                                $replace['retainVisaType'] = '';
                        }
                        else {
                                $replace['retainVisaType'] = '';
                                $replace['retainMasterType'] = '';
                        }
                                                                    
                    if(urldecode($this->value('address1Valid'))!='')   {           
                       $replace['address1Valid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('address1Valid').'</font>';                                              
                       $replace['retainedadd1']='' ;  
                    }     
                       
                       
                    
                        $replace['retainedadd1']=$_SESSION['clinic']['address1'] ;
                     
                       
                    if(urldecode($this->value('cityValid'))!='')    {               
                        $replace['cityValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cityValid').'</font>';                                                         
                        $replace['retainedcity']='';  
                    }
                        
                        $replace['retainedcity']=$_SESSION['clinic']['city'] ;
                     
                                                            
                    if(urldecode($this->value('stateValid'))!='')  {                  
                        $replace['stateValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('stateValid').'</font>';                                             
                    
                        $replace['retainedstate']=''; 
                    
                    }         
                        
                        $replace['retainedstate']=$_SESSION['clinic']['state'] ;
                     
                            
                    if(urldecode($this->value('zipcodeValid'))!='')    {               
                        $replace['zipcodeValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('zipcodeValid').'</font>';                                               
                    
                        $replace['retainedzip']='' ;
                    }
                        
                    
                        $replace['retainedzip']=$_SESSION['clinic']['zipcode'] ;

                

                    if(urldecode($this->value('address2'))=='')
                        $replace['retainedadd2']=$_SESSION['clinic']['address2'] ;
                    } else {
                    $replace['namefValid']='';
                    $replace['retainedFname']='';      
                    $replace['namelValid']=''; 
                    $replace['emailValid']=''; 
                    $replace['retainedlname']='' ;     
                    $replace['cnumberValid']='';   
                    $replace['cexpValid']='';   
                    $replace['ccvvValid']='';   
                    $replace['ctypeValid']='';
                    $replace['address1Valid']=''; 
                    $replace['retainedadd1']='' ;   
                    $replace['retainedadd2']='' ;               
                    $replace['cityValid']='';
                    $replace['retainedcity']='' ;         
                    $replace['stateValid']='';
                    $replace['retainedstate']='' ;         
                    $replace['zipcodeValid']='';
                    $replace['retainedzip']='' ; 
                    $replace['RetaincountryUS']='' ;                 
                    $replace['RetaincountryCAN']='' ; 
                    $replace['retainedEmail']='';  
                    $replace['retainedcardNumber']='';             
                    $replace['retainVisaType']=''; 
                    $replace['retainMasterType']=''; 
                    $replace['retainedcvvNumber']='';
                         
             }
            //echo "test".$_SESSION['pateintSubs']['clinicProvider'];
            
          //$clinicProvidersArray = array("" => "Select Provider");
           $clinicProvidersArray = $clinicProviders;//print_r($clinicProvidersArray);exit;
           $replace['clinicProvidersOptions'] = $this->build_select_option($clinicProvidersArray, $_SESSION['clinic']['clinicProvider']);

            $logotx='txlogo';
	    $header_1 = "<div id='line'><div id='".$logotx."' style='float:left; padding-left:55px;' ></div><div id='subheader' style='float:right; padding-right:55px;' ><div id='cliniclogo'></div></div><div style='clear:both;'></div></div>";
	    $replace['header']=$header_1;
            $replace['footer'] = $this->build_template($this->get_template("footer"));    
            $replace['HealthProgramPrice']=$this->config['clinic_price'];
             $replace['HealthProgramDuration']="per User/Month";
            $replace['HealthProgramData'] = "With Tx Xchange, there are no contracts, upfront, training, or support costs. Start today and begin to receive the significant financial, operational, and clinical benefits we provide.";
            $replace['keyFeatures']="<li>Create New Revenue Streams </li>";
            $replace['keyFeatures'].="<li>Increase Patient/Client Retention </li>";
            $replace['keyFeatures'].="<li>Reduce Uncompensated Time </li>";
            $replace['keyFeatures'].="<li>Improve Patient/Client Outcomes</li>";
            $replace['KeyFeatureHeading']="Key Benefits";
            $replace['browser_title'] = "Tx Xchange: Payment Information";
            if($_SESSION['clinic']['cardType'] == 'Visa') {
                                $replace['retainVisaType'] = 'selected="selected"';
                                $replace['retainMasterType'] = '';
                        }
                        elseif($_SESSION['clinic']['cardType'] == 'MasterCard') {
                                $replace['retainMasterType'] = 'selected="selected"';
                                $replace['retainVisaType'] = '';
                        }
                        else {
                                $replace['retainVisaType'] = '';
                                $replace['retainMasterType'] = '';
                        }
            $replace['cardNumber']	=	$_SESSION['clinic']['cardNumber'];
            $replace['cvvNumber']	=	$_SESSION['clinic']['cvvNumber'];
            $replace['address1']	=	$_SESSION['clinic']['address1'];
            $replace['address2']	=	$_SESSION['clinic']['address2'];
            $replace['city']		=	$_SESSION['clinic']['city'];
            $replace['zipcode']		=	$_SESSION['clinic']['zipcode'];
            
            $monthArray = array();
            $monthArray = $this->config['monthsArray'];
            $replace['monthsOptions'] = $this->build_select_option($monthArray, $_SESSION['clinic']['exprMonth']); 
           
            $yearArray = array();
            $yearArray = $this->config['yearArray'];
            $replace['yearOptions'] = $this->build_select_option($yearArray, $_SESSION['clinic']['exprYear']);
            $countryarray=$this->country_list(); 
			$replace['country']=$this->build_select_option($countryarray, $_SESSION['clinic']['country']);
           if($_SESSION['clinic']['country']=='')
            $country_code='US';
		else
	    $country_code=$_SESSION['clinic']['country'];
	    $stateArray=$this->state_list($country_code);
	    $replace['stateOptions']=$this->build_select_option($stateArray, $_SESSION['clinic']['state']);		 

            
            $sql="select * from provider_subscription  where clinic_id =".$clinic_id." order by user_subs_id limit 0,1";
                $query=$this->execute_query($sql);
                if($this->num_rows($query)==1){
                 $row=$this->fetch_object($query);
                 //include 'freetrial.class.php';
                 //$obj=new freetrial();
                 $httpParsedResponseAr=  $this->getpaypalprofiledetail($row->reurring_profile_id);
                 $replace['ccno']=$httpParsedResponseAr['ACCT'];
            }
            $replace['cardPayment']=1;
	   $replace['body'] = $this->build_template($this->get_template("update_account_cradit_card"),$replace);
	   $replace['get_satisfaction'] = $this->get_satisfaction();
	   $this->output = $this->build_template($this->get_template("main"),$replace);
	}    
    
     function getpaypalprofiledetail($Paypal_Profile){
                    $API_UserName       =       urlencode($this->config["clinicpaypalprodetails"]["API_UserName"]);
                    $API_Password       =       urlencode($this->config["clinicpaypalprodetails"]["API_Password"]);
                    $API_Signature      =       urlencode($this->config["clinicpaypalprodetails"]["API_Signature"]);
                    $environment        =       urlencode($this->config["clinicpaypalprodetails"]["environment"]);
                    $currencyID         =       urlencode($this->config["clinicpaypalprodetails"]["currencyID"]);
                    $Frequency          =       urlencode($this->config["clinicpaypalprodetails"]["billingPeriod"]);
                    $billingFreq        =       urlencode($this->config["clinicpaypalprodetails"]["billingFreq"]);
                    
                    $paypalObjectCancel=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                    $profileID=urlencode($Paypal_Profile);
                    $action=urlencode("Cancel");
                    $nvpStr="&PROFILEID=$profileID";
                    $paypalObjectCancel=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                    $httpParsedResponseAr = $paypalObjectCancel->PPHttpPost('GetRecurringPaymentsProfileDetails', $nvpStr);
                    return $httpParsedResponseAr;
                    
                }
    
     function submit_update_clinic_card_suspended(){
                    //Configuration settings...
            $API_UserName       =       urlencode($this->config["clinicpaypalprodetails"]["API_UserName"]);
            $API_Password       =       urlencode($this->config["clinicpaypalprodetails"]["API_Password"]);
            $API_Signature      =       urlencode($this->config["clinicpaypalprodetails"]["API_Signature"]);
            $environment        =       urlencode($this->config["clinicpaypalprodetails"]["environment"]);
            $currencyID         =       urlencode($this->config["clinicpaypalprodetails"]["currencyID"]);
            $Frequency          =       urlencode($this->config["clinicpaypalprodetails"]["billingPeriod"]);
            $billingFreq        =       urlencode($this->config["clinicpaypalprodetails"]["billingFreq"]);
            $_SESSION['clinic']['cardPayment'] =$_REQUEST['cardPayment'];
            $_SESSION['clinic']['cardType']=$_REQUEST['cardType'];
            $_SESSION['clinic']['cardNumber']=$_REQUEST['cardNumber'];
            $_SESSION['clinic']['cvvNumber']=$_REQUEST['cvvNumber'];
            $_SESSION['clinic']['exprMonth']=$_REQUEST['exprMonth'];
            $_SESSION['clinic']['exprYear']=$_REQUEST['exprYear'];
            $_SESSION['clinic']['address1']=$_REQUEST['address1'];
            $_SESSION['clinic']['address2']=$_REQUEST['address2'];
            $_SESSION['clinic']['city']=$_REQUEST['city'];
            $_SESSION['clinic']['state']=$_REQUEST['state'];
            $_SESSION['clinic']['country']=$_REQUEST['country'];
            $_SESSION['clinic']['zipcode']=$_REQUEST['zipcode'];
            $creditCardType     =       $this->value('cardType');
            $creditCardNumber   =       $this->value("cardNumber");
            $expDateMonth       =       $this->value('exprMonth');
            $expDateYear        =       $this->value('exprYear');
            $cvv2Number         =       $this->value('cvvNumber');
            $address1           =       $this->value('address1');
            $address2           =       $this->value('address2');
            $city               =       $this->value('city');
            $state              =       $this->value('state');
            $zipcode            =       $this->value('zipcode');
            $country            =       $this->value('country');
            $cardPayment        =       $this->value('cardPayment');
            $clinic_id = $this->clinicInfo('clinic_id');
            //Code To Validate User Entered Data
            $error=array();
            
            if(trim($creditCardNumber)=="" || strlen(trim($creditCardNumber))<14 || strlen(trim($creditCardNumber))>16){
                $error['cnumberValid']="Please enter valid credit card number";
            }
            
            if(trim($creditCardType)==""){
                $error['ctypeValid']="Please select credit card type";
            }

            if(trim($expDateMonth)=="" || trim($expDateYear)=="" ){
                $error['cexpValid']="Please select credit card expiration";
            }
                        
            if(trim($cvv2Number)==""){
                $error['ccvvValid']="Please enter CVV number";
            }else if(!is_numeric(trim($cvv2Number))){
                $error['ccvvValid']="Please enter valid CVV number";
            }
            if(trim($address1)==""){
                $error['address1Valid']="Please enter address1";
            }
            if(trim($city)==""){
                $error['cityValid']="Please enter city";
            }
            if(trim($state)==""){
                $error['stateValid']="Please select state/province";
            }
            if(trim($zipcode)==""){
                $error['zipcodeValid']="Please enter zip/postal code";
            }
            if(!empty($error)){
                $messageReturn="&validData=true";
                foreach($error as $key=>$value)   {
                    $messageReturn.="&".$key."=".urlencode($value);
                }
               header("location:index.php?action=updateaccountpaypalprofile&cid=1".$messageReturn.'&clinic_id='.$_REQUEST['clinic_id']);
               die();
            }

            
            
                 //////////////////
                //1) get clinic paypal profile
                $sql="select * from provider_subscription  where clinic_id =".$clinic_id." order by user_subs_id limit 0,1";
                $query=$this->execute_query($sql);
                if($this->num_rows($query)==1){
                    $row=$this->fetch_object($query);
                }
                    $Paypal_Profile=$row->reurring_profile_id; 
                    $userInfor          =   $this->userInfo();
                    $clinicName         =   html_entity_decode($this->get_clinic_info($userInfor['user_id'],"clinic_name"), ENT_QUOTES, "UTF-8");
                    $desc               =   urlencode($clinicName);
                    $profileId          =   urlencode($Paypal_Profile);
                    $BillingstreetAddr  =   urlencode($address1);
                    $Billingstreet2Addr =   urlencode($address2);
                    $BillingCity        =   urlencode($city);
                    $BillingState       =   urlencode($state);
                    $BillingCountry     =   urlencode($country);
                    $Billingzip         =   urlencode($zipcode);
                    $emailaddress       =   $this->userInfo('username',$user_id);
        /*pay pal code $paymentType 0 for recurring and 1 for onetime payment*/
                    $paymentAmount      =   urlencode($this->value('cardPayment'));
                    $currencyID         =   urlencode($currencyID);// or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
                    $startDate          =   urlencode(date("Y-m-d")."T0:0:0");
                    $paypalObject       =   new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                    $profileID          =   urlencode($Paypal_Profile);
                    $nvpStr             =   "&PROFILEID=$profileID";
                    $paypalObject       =   new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                    $httpParsedResponseAr = $paypalObject->PPHttpPost('GetRecurringPaymentsProfileDetails', $nvpStr);
                    //$this->printR($httpParsedResponseAr);
                   //2) one time payment $1
                    
                    
                    $nvpStr="&PAYMENTACTION=Sale&AMT=$paymentAmount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=".$expDateMonth.$expDateYear."&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state"."&ZIP=$zipcode&COUNTRYCODE=$BillingCountry&CURRENCYCODE=$currencyID&EMAIL=".$emailaddress."&DESC=".$desc;                    
                    $paypalProClass=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                    $httpParsedResponseonetimeAr = $paypalProClass->hash_call('doDirectPayment', $nvpStr);            
                    /* echo "<pre>";
                    print_r($httpParsedResponseonetimeAr);
                    echo "</pre>";
                    die;*/
                    // Code to fill history Log of Profile creation
                    $this->paypalHistory($httpParsedResponseonetimeAr,$new_provider_id,$httpParsedResponseonetimeAr['TRANSACTIONID'],'onetime');
            //Check the status of the payment.If success OR success with warning then change status and update database and make insertions
                    if(strtolower($httpParsedResponseonetimeAr['ACK'])=="success" || strtolower($httpParsedResponseonetimeAr['ACK'])=="successwithwarning") {
                        
                        
                    // update credit card and refund money
             $fname=$this->userInfo('name_first'); 
             $lname=$this->userInfo('name_last');
             $nvpStr="&FIRSTNAME=$fname&LASTNAME=$lname&PROFILEID=$profileId&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=$expDateMonth$expDateYear&CVV2=$cvv2Number";
             $nvpStr .= "&STREET=$BillingstreetAddr&STREET2=$Billingstreet2Addr&CITY=$BillingCity&STATE=$BillingState&COUNTRYCODE=$BillingCountry&ZIP=$Billingzip";
             $httpParsedResponseupdateAr = $paypalProClass->PPHttpPost('UpdateRecurringPaymentsProfile', $nvpStr);
             
             
			
            // Code to fill history Log of Profile creation
            $this->paypalHistory($httpParsedResponseupdateAr,$userInfor['user_id'],trim(urldecode($httpParsedResponseupdateAr['PROFILEID'])),'update');
            
           /* echo "<pre>";
            print_r($httpParsedResponseAr);
            echo "</pre>";
  	   die;`*/    
            // Check the status of the payment.If success OR success with warning then change status and update database and make insertions
            if(strtolower($httpParsedResponseupdateAr['ACK'])=="success" || strtolower($httpParsedResponseupdateAr['ACK'])=="successwithwarning")    {
                    $expdate=date('m').date('Y');
                    $SQL="select * from updated_clinic_credit_card where paypal_id='{$profileId}' and expdate='{$expdate}'";
                    $query=$this->execute_query($SQL);
                    if($this->num_rows($query)>0){
                        $res=$this->fetch_object($query);
            		$update="update updated_clinic_credit_card set status_mail='1' where id='{$res->id}'";
            		$query=$this->execute_query($update);
            		}
                        //refund code
                        $transactionID = $httpParsedResponseonetimeAr['TRANSACTIONID'];
                        $refundType = urlencode('Full');						// or 'Partial'
                        $memo='refund for '.$clinicName;													// required if Partial.
                        // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
                         // Add request-specific fields to the request string.
                        $nvpStr = "&TRANSACTIONID=$transactionID&REFUNDTYPE=$refundType&CURRENCYCODE=$currencyID";

                        if(isset($memo)) {
                                $nvpStr .= "&NOTE=$memo";
                        }

                        // Execute the API operation; see the PPHttpPost function above.
                                $httpParsedResponseRefundAr = $paypalObject->PPHttpPost('RefundTransaction', $nvpStr);
                                $action=urlencode("Reactivate");
                                $note=urlencode("Reactivate Subscription for ".$clinicName);
                                $nvpStr="&PROFILEID=$profileId&ACTION=$action&NOTE=$desc";
                                $paypalObjectActive=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                                $httpParsedResponseArActive = $paypalObjectActive->PPHttpPost('ManageRecurringPaymentsProfileStatus', $nvpStr);
				$nvpStr="&PROFILEID=$profileId";
                                $httpParsedResponseoutstandingbill = $paypalObjectActive->PPHttpPost('BillOutstandingAmount', $nvpStr);
                                
                                $this->UpdateDataSubscription(trim(urldecode($httpParsedResponseAr['PROFILEID'])),$startDate,$clinic_id);    
                        
                        //if("SUCCESS" == strtoupper($httpParsedResponseRefundAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseRefundAr["ACK"])) {
                               header("location:index.php?action=therapist&cid=2&clinic_id=".$_REQUEST['clinic_id']);
                               die;
                        /*} else  {
                                $errorCode=$httpParsedResponseRefundAr['L_ERRORCODE0'];
				header("location:index.php?action=updateaccountpaypalprofile&cid=3&clinic_id=".$_REQUEST['clinic_id']."&errorCode={$errorCode}");
                                die;
                        }*/
                }else{
                //die();
                // Payment unsucessfull redirect to the Paymnet page again with error code.
                $errorCode=$httpParsedResponseAr['L_ERRORCODE0'];
                $_SESSION['clinic']['add1']=$address1;
                $_SESSION['clinic']['add2']=$address2;    
                $_SESSION['clinic']['city']=$city;
                $_SESSION['clinic']['state']=$state;
                $_SESSION['clinic']['zip']=$zipcode; 
                $_SESSION['clinic']['country']=$country; 
                $_SESSION['clinic']['cardType']=$creditCardType;
                $_SESSION['clinic']['exprMonth']=$expDateMonth;
                $_SESSION['clinic']['exprYear']=$expDateYear;
                $_SESSION['clinic']['cardNumber']=$creditCardNumber;
                $_SESSION['clinic']['cvvNumber']=$cvv2Number;
                $errorCode=$httpParsedResponseupdateAr['L_ERRORCODE0'];
		header("location:index.php?action=updateaccountpaypalprofile&cid=4&clinic_id=".$_REQUEST['clinic_id']."&errorCode={$errorCode}");
                die;    
            }
                        
                    }else{
                     $errorCode=$httpParsedResponseonetimeAr['L_ERRORCODE0'];
                    header("location:index.php?action=updateaccountpaypalprofile&cid=5&clinic_id=".$_REQUEST['clinic_id']."&errorCode={$errorCode}");
                    die;     
                    
                        
                    }
                    
                //
                //3) sucess update cc
                //
                
           
                //4)    cut outstanding amount
                //
                //
                //
                //5)    return amount
                // Set request-specific fields.
                //6)fail show error
                //
                //
                }
       	/**
         * This function saves the Patient information and update the database as subscription purchased.
         * Also the end date is set to one month more than subscription start date.
         * @param 
         * @return array $subscriptionInfo
         * @access public
         */  
                
        function UpdateDataSubscription($profileNumber,$startDate,$clinic_id){
            // Gather information from REQUEST
            $Frequency=urlencode($this->config["clinicpaypalprodetails"]["billingPeriod"]);
            $billingFreq=urlencode($this->config["clinicpaypalprodetails"]["billingFreq"]);
			$userInfor=$this->userInfo();
            $clinicID=$this->clinicInfo('clinic_id');
            $Frequency=urlencode($this->config["clinicpaypalprodetails"]["billingPeriod"]);
            $billingFreq=urlencode($this->config["clinicpaypalprodetails"]["billingFreq"]);
            $querySubscriptEntry="update provider_subscription set  `subs_datetime`=now(), `subs_status`='1',`subs_end_datetime`=DATE_ADD(now(), INTERVAL {$billingFreq} {$Frequency}) where clinic_id=".$clinic_id;
            $result=$this->execute_query($querySubscriptEntry);
           

           // Send email to the Account Admin 
           //$this->sendTxmessageAccountAdmin($clinicID,$userInfor,$resultHealthProgram);

           // Send email to the Patient
           //$this->sendEmailSignUpPatients($clinic_id,$userInfor,$resultHealthProgram,0);
        
        }         
        /**
         * This function returns the subscription information based on clinic id associated with the Patients.
         * @param Integer $clinic_id
         * @return array $subscriptionInfo
         * @access public
         */ 
         
         function show_refund_policy_clinic(){
                  $this->output = $this->build_template($this->get_template("show_refund_policy"),$replace);
         }        
                
                
                }
	// creating object of this class.
	$obj = new freetrial();
?>
