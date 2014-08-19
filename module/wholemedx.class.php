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
  	class wholemedx extends application{
  		
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
		function wholemedx()
		{
			//echo $this->config['from_email_address'];exit;
			$replace = array();
			$option = $this->value('option');
			$clinic_id = $this->value('clinic_id');
			
			$replace['browser_title'] = 'Whole Medx';
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
		
		
		function addwholemedxaccount(){
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
	
//print_r($_REQUEST);
		if (isset($_POST['submit']) && $_REQUEST['action'] == 'addwholemedxaccount')
				{
				    //print_r($_POST) ;echo "here";exit;
                    //form submitted check for validation
					$status=$this->validateFormSubscriber();
                    $wholmedxurl=$this->config['wholemedxurl'];	
                    $imageurl=$this->confit['images_url'];		
					//echo "hiiii".$status;
					if($status == '1')
						{
					//echo 'dublicate user';exit;
					@header("Location: $wholmedxurl?status=fail_username_same&name_first=".$_POST['name_first']."&name_last=".$_POST['name_last']."&username=".$_POST['username']."&phone1=".$_POST['phone1']."&practitiner_type=".$_POST['practitiner_type']."&clinic_name=".$_POST['clinic_name']."&address=".$_POST['address']."&country=".$_POST['country']."&state=".$_POST['state']."&city=".$_POST['city']."&zip=".$_POST['zip']."&clinic_type=".$_POST['clinic_type']);
					
					//@header("Location: http://localhost/txxchange_business/index.php?option=com_helloworld&Itemid=44&status=fail&business_name=".$_POST['business_name']."&practitiner_type=".$_POST['practitiner_type']."&name_first=".$_POST['name_first']."&name_last=".$_POST['name_last']."&clinic_zip=".$_POST['clinic_zip']."&email=".$_POST['email']);
					exit;
					}
					if($status == '0')
					{	
						//Form validated, no errors
						//go ahead store the values in db
						if(trim($this->value('username'))==''){
						@header("Location: $wholmedxurl?status=fail_username_blank&name_first=".$_POST['name_first']."&name_last=".$_POST['name_last']."&username=".$_POST['username']."&phone1=".$_POST['phone1']."&practitiner_type=".$_POST['practitiner_type']."&clinic_name=".$_POST['clinic_name']."&address=".$_POST['address']."&country=".$_POST['country']."&state=".$_POST['state']."&city=".$_POST['city']."&zip=".$_POST['zip']."&clinic_type=".$_POST['clinic_type']);
						//echo "no email";
                        exit;
						}
                        if(trim($this->value('clinic_name'))==""){
                         @header("Location: $wholmedxurl?status=fail_clinicname_same&name_first=".$_POST['name_first']."&name_last=".$_POST['name_last']."&username=".$_POST['username']."&phone1=".$_POST['phone1']."&practitiner_type=".$_POST['practitiner_type']."&clinic_name=".$_POST['clinic_name']."&address=".$_POST['address']."&country=".$_POST['country']."&state=".$_POST['state']."&city=".$_POST['city']."&zip=".$_POST['zip']."&clinic_type=".$_POST['clinic_type']);
						 // echo "no business";
                          exit;  
                        }
						$system_access = 0; 
						$therapist_access = 1;
						$userTypeId = 2;
						$admin_access = 1;
						$password = $this->genRandomString();
						$insertArr = array(
											'usertype_id'=>$userTypeId,
											'username' =>$this->value('username'),
											'password' => $password,
											'name_first' => $this->value('name_first'),
											'name_last' => $this->value('name_last'),
											'creation_date' => date('Y-m-d H:i:s',time()),
											'modified' => date('Y-m-d H:i:s',time()),
											'admin_access'=> $admin_access,
											'system_access' => $system_access,
											'therapist_access' => $therapist_access,
											'status'=> 1,
											'practitioner_type'=>$this->value('practitiner_type'),
                                            'phone1'=>	$this->value('phone1'),				
											);
						$this->check_send_invitation($this->value('username'));						
												
						$result = $this->insert('user',$insertArr);
						
						$newlyCreatedUserId = $this->insert_id();
						/*
						 * create clinic
						 */
                        	$insertArr['status'] = '1';
                        	$insertArr['clinic_name'] = $this->value('clinic_name');
                            $insertArr['address'] = $this->value('address');
                            $insertArr['country'] = $this->value('country');
                            $insertArr['state'] = $this->value('state');
                            $insertArr['city'] = $this->value('city');
                            $insertArr['creationDate'] = date("Y-m-d H:i:s");
                            $insertArr['parent_clinic_id'] = '0';
                            $insertArr['zip'] = $this->value('zip');
                            $insertArr['clinic_type'] = $this->value('clinic_type');
                            $insertArr['clinic_channel'] = $this->value('clinic_channel');
                            $insertArr['phone'] = $this->value('phone1');
                            $result = $this->insert('clinic',$insertArr);
							$clinicId = $this->insert_id();
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
						
						// Also handle the copying of article and treatment template for user if usertype is therapist
						
						{//usertype = therapist	
							if(	$userTypeId=='2')
							{
							
								//Article Block
								{
									
                                    // Original Query : New User not getting the Articles created by Account Admin
                                    //$queryArticle = "SELECT article.* FROM article INNER JOIN user ON article.user_id = user.user_id 
                                    //                    WHERE user.usertype_id = 4 AND user.status = 1 AND article.status != 3";
                                    $queryArticle = "SELECT article.* FROM article INNER JOIN user ON article.user_id = user.user_id 
                                                        WHERE (user.usertype_id = 4 OR (clinic_id= '{$clinicId}' AND parent_article_id IS NULL )) AND user.status = 1 AND article.status != 3";
									
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
												//$fileDest = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url']. $newArticleId . '/';
												$fileDest = $this->check_article_path($newArticleId);
												$fileDestPath = $this->get_article_path($newArticleId);
                                                $fileSourcePath = $this->get_article_path($row['article_id']);
                                                copy($fileSourcePath,$fileDestPath);
                                                $this->update_article_path($newArticleId); 
												
													
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
							$fullName = $this->value('name_first')." ".$this->value('name_last');
							$replace['username'] = $this->value('email');
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
											$subject = "Your Wholemedx Account Information";
											$message = $this->build_template($this->get_template("newUserMailContent_wx"),$replace);
										}
									}
								}
							//mail content for jonathon 
							$sqlpractitioner="select * from practitioner where  practitioner_id = ".$this->value('practitiner_type');
							$resultpractitioner = $this->execute_query($sqlpractitioner);
							$rowpractitioner = $this->fetch_array($resultpractitioner);
							$replace1['url'] = $this->config['url'];
							$replace1['images_url'] = $this->config['images_url'];
							$replace1['businessname']=$this->value('clinic_name');
							$replace1['name_first']=$this->value('name_first');
							$replace1['name_last']=$this->value('name_last');
							$replace1['username']=$this->value('username');
							$replace1['practitioner_type']=$rowpractitioner['name'];
							$replace1['zip_code']=$this->value('zip');
							$replace1['phone1']=$this->value('phone1');
							$subject1 = "New Wholemedx Account";
							$message1 = $this->build_template($this->get_template("admin_mail_freetrial_wx"),$replace1);
							$to1 = $this->config['from_email_address'];
							$to = $this->setmailheader($fullName).'<'.$this->value('username').'>';
							
							
							// To send HTML mail, the Content-type header must be set
							$headers  = 'MIME-Version: 1.0' . "\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
							
							// Additional headers
							//$headers .= 'To: '.$fullName.'<'.$_POST['email_address'].'>'. "\n";
							
							//$headers .= 'From: '.$this->config['from_email_address'] . " \n";
							$headers .= 'From: support@wholemedx.com' . "\n";
							//$headers .= 'Cc: example@example.com' . "\n";
							//$headers .= 'Bcc: example@example.com' . "\n";
							
							$returnpath = '-fsupport@wholemedx.com';
                            // Mail it	
                           // echo $subject;
							
                            mail($to, $subject, $message, $headers, $returnpath);	
							mail($to1, $subject1, $message1, $headers, $returnpath);
							
						}
						
						
						// redirect to list of subscriber
						//header("location:index.php?action=freetrial");
						@header("Location: $imageurl?status=success");
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
		
  	function validateFormSubscriber()
		{
			$queryEmail = "SELECT user_id FROM user WHERE username = '".$_POST['username']."' AND status <> 3";
			$result = $this->execute_query($queryEmail);
				//if record found that means email not unique else it is unique
				if ($this->num_rows($result) != 0)
				{
					return 1;
				}
				else
				{
				    return 0;		
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
		function copyPublishPlan($user_id,$clinic_id){
            
            if( is_numeric($clinic_id) && $clinic_id!= 0 ){
                $parent_clinic_id = $this->get_field($clinic_id,'clinic','parent_clinic_id');
                if( is_numeric($parent_clinic_id)){
                    if( $parent_clinic_id == 0){
                        $query = "SELECT cu.user_id
                        FROM clinic_user AS cu
                        INNER JOIN clinic AS cl ON cu.clinic_id = cl.clinic_id
                        INNER JOIN user as us on us.user_id = cu.user_id and us.status = 1 and us.usertype_id  = 2
                        WHERE (cl.parent_clinic_id = '{$clinic_id}' or cl.clinic_id = '{$clinic_id}') 
                        AND cl.status =1 
                    ";
                    }
                    else{
                         $query = "SELECT cu.user_id 
                        FROM clinic_user AS cu
                        INNER JOIN clinic AS cl ON cu.clinic_id = cl.clinic_id
                        INNER JOIN user as us on us.user_id = cu.user_id and us.status = 1 and us.usertype_id  = 2
                        WHERE cl.parent_clinic_id = '{$parent_clinic_id}' or cl.clinic_id = '{$parent_clinic_id}' 
                        AND cl.status =1 
                    ";
                    }
                    
                    if( !empty($query) ){
                         $sql = "select * from plan 
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
                        while( $row = @mysql_fetch_array($result)){
                            $this->copy_plan($user_id,$row['plan_id']);
                        }
                    }
                }
            }
            return "";
        }	
  	function  genRandomString() {
    $length = 7;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $string = '';    

    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }

    return $string;
}
	
	}
	// creating object of this class.
	$obj = new wholemedx();
?>
