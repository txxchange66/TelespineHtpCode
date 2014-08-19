<?php


	/** 
	 *
	 * Copyright (c) 2008 Tx Xchange
	 *
	 * Class for creating, editing and deleting users (Therapist & Account Admin)for clinic.
	 * 
	 * // necessary classes 
	 * require_once("module/application.class.php");
	 * 
	 * //pagination class
	 * require_once("include/paging/my_pagina_class.php");
	 *  
	 * // file upload class
	 * require_once("include/fileupload/class.upload.php");
	 * 
	 * //Server side form validation classes
	 * require_once("include/validation/_includes/classes/validation/ValidationSet.php");
	 * require_once("include/validation/_includes/classes/validation/ValidationError.php");
	 * 
	 */
		
	
	// including files
	require_once("include/paging/my_pagina_class.php");	
	require_once("include/fileupload/class.upload.php");	
	require_once("module/application.class.php");
	require_once("include/validation/_includes/classes/validation/ValidationSet.php");
	require_once("include/validation/_includes/classes/validation/ValidationError.php");		
	
	
	// class declaration
  	class subscriberManager extends application{
  		
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
		 * 
		 *
		 * @var unknown_type
		 */
		private $invalid;
		
		
		
		/**
		 * 
		 *
		 * @var unknown_type
		 */
		private $uploaded;
		
		
		
		
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
				header("location:index.php?sysAdmin");//default if no action is specified
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
		 * List all the users of clinic.
		 *
		 * @access public
		 */
		function subscriberListing()
		{
						
			$replace = array();
			
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
					$privateKey = $this->config['private_key'];
                    $field_arr = array("CAST(AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as CHAR)","CAST(AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as CHAR)","username");
                    $sqlWhere = " AND ".$this->makeSearch(ALL_WORDS,$this->value('search'),$field_arr);
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
					$replace['username'] = "action=subscriberListing&sort=username&order=DESC".$replace['searchStr'];
					$replace['full_name'] = "action=subscriberListing&sort=full_name&order=ASC".$replace['searchStr'];
					$replace['usertype_id'] = "action=subscriberListing&sort=usertype_id&order=ASC".$replace['searchStr'];
					$replace['last_login'] = "action=subscriberListing&sort=last_login&order=ASC".$replace['searchStr'];
					/* Added for bug# 10156 */
					$replace['creation_date'] = "action=subscriberListing&sort=creation_date&order=ASC".$replace['searchStr'];
					/* End */
					$replace['status'] = "action=subscriberListing&sort=status&order=ASC".$replace['searchStr'];
					
					$replace['usernameImg'] = '&nbsp;<img src="images/sort_asc.gif">';
					
					$orderByClause = " username ASC ";
				}
				else {
					
					$queryStr = $replace['searchStr'];
					$this->setSortFields($replace,"subscriberListing",$queryStr);	
                    if($this->value('sort') == 'full_name'){
                        if( $this->value('order') == "ASC" ){
                            $privateKey = $this->config['private_key'];  
                            $orderByClause = " cast(AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as char) ASC, cast(AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as char) ASC ";
                        }
                        else{
                                 $privateKey = $this->config['private_key'];  
                               $orderByClause =  " cast(AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as char) DESC, cast(AES_DECRYPT(UNHEX(name_last),'{$privateKey}')as char) DESC ";
                        }
                        //$orderByClause = $replace['orderByClause'];
                    }
                    else
					    $orderByClause = $replace['orderByClause'];
					
				}
				
				/* End */		

				//Now we may have parameter 'do' set as delete or inactive or active
				if(isset($_GET['do']) && isset($_GET['id']))
				{
					switch ($_GET['do']) 
					{
						case "delete":
										$error = $this->deleteSubscriber();
										$replace['error'] = $error;		
										unset($_GET['do']);				
										unset($_GET['id']);
										
										break;
										
						case "inactive":										
										
										$error = $this->inactiveSubscriber();
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
				$sqlUser = "SELECT *, 
                            CONCAT_WS(' ',
                            AES_DECRYPT(UNHEX(name_title),'{$privateKey}') , 
                            AES_DECRYPT(UNHEX(name_first),'{$privateKey}') ,
                            AES_DECRYPT(UNHEX(name_last),'{$privateKey}') ,
                            name_suffix) AS full_name FROM user WHERE (usertype_id = 2 OR usertype_id = 4) AND (status = 1 OR status = 2)".$sqlWhere." ORDER BY ".$orderByClause;
				$sqlcount= "SELECT count(1) FROM user WHERE (usertype_id = 2 OR usertype_id = 4) AND (status = 1 OR status = 2)".$sqlWhere;
				
				//echo  $sqlUser;
				
				//$result = $this->execute_query($sqlUser);				
				
				$link = $this->pagination($rows = 0,$sqlUser,'subscriberListing',$this->value('search'),array('do','id'),'','',$sqlcount);                                          
	
	            $replace['link'] = $link['nav'];
	
	            $result = $link['result']; 	
	            
				
				if($this->num_rows($result)!= 0)
				{
					$replace['subscriberTblHead'] = $this->build_template($this->get_template("subscriberTblHead"),$replace);
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
						 
						
						if ($row['status'] == 1 and $row['trial_status']==1)
						{
							$row['statusType'] = "Trial";
							$replace['subscriberTblRecord'] .=  $this->build_template($this->get_template("subscriberTblRecord"),$row);
						}
                        else if ($row['status'] == 1 and $row['trial_status']!=1)
						{
							$row['statusType'] = "Active";
							$replace['subscriberTblRecord'] .=  $this->build_template($this->get_template("subscriberTblRecord"),$row);
						}
						else if ($row['status'] == 2)
						{
							$row['statusType'] = "Inactive";
							$replace['subscriberTblRecord'] .=  $this->build_template($this->get_template("subscriberTblRecordInactive"),$row);
							
						}
						
					}
				}
				else 
				{
					$replace['subscriberTblHead'] = $this->build_template($this->get_template("subscriberTblHead"),$replace);
					$replace['subscriberTblRecord'] =  '<tr> <td colspan="7">No Subscribers to list</td></tr>';
					$replace['link'] = "&nbsp;";
				}					
				
				if ($sqlWhere == "") 			
				{		
					//$extraParam['patientId'] = $patientId;
					//$replace['filter'] = $this->build_template($this->get_template("assocTherapistFilter"),$extraParam);	
					$replace['filter'] = $this->build_template($this->get_template("subscriberFilter"));	
				}
				else {
					//$extraParam['search'] = $this->value('search');
					//$extraParam['searchOn'] = $this->build_template($this->get_template("articleSearchOn"),$searchOn);
					$extraParam['searchOn'] = "";
					//$extraParam['patientId'] = $patientId;
					
					$replace['filter'] = $this->build_template($this->get_template("subscriberFilterClear"));
				}				
				
				
				
				$replace['header'] = $this->build_template($this->get_template("header"));
				$replace['footer'] = $this->build_template($this->get_template("footer"));	
				//$replace['sidebar'] = $this->build_template($this->get_template("sidebar"));					
				$replace['sidebar'] = $this->sidebar();
				
				$replace['body'] = $this->build_template($this->get_template("subscriberTemplate"),$replace);
				$replace['browser_title'] = "Tx Xchange: Subscriber List";
				$this->output = $this->build_template($this->get_template("main"),$replace);			
			}	
			
		}
		
		
		/**
		 * Remove the user from clinic.
		 *
		 * @return string
		 * @access public
		 */
		function deleteSubscriber()
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
		 * Set status Active for the user.
		 *
		 * @access public
		 */
		function activeSubscriber()
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
				//extra precaution check if user has access 			
				$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";								
				if ("SysAdmin" == $userType) 
				{					
					// only sys admin has access to delete 				
					$queryUpdate = "UPDATE user SET status = 1,trial_status=null,free_trial_date=null WHERE user_id = ". $user_id;
					$this->execute_query($queryUpdate);	
					header("location:index.php?action=subscriberListing");
				}	
				else
				{
					unset($_GET['id']);	
					header("location:index.php");
				}	
					
			}		
			
		}
		
		
		/**
		 * Set status InActive for the user.
		 *
		 * @access public
		 */		
		function inactiveSubscriber()
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
								$queryUpdate = "UPDATE user SET status = 2,trial_status=null, free_trial_date=null WHERE user_id = ". $user_id;					
								$this->execute_query($queryUpdate);	
							}
							else 
							{
								//There exist relationship
								//$err = "There are patient(s) who are associated with this therapist, please remove the association and do the action";
                                //this is done in rc 244 uc 1
                                $queryUpdate = "UPDATE user SET status = 2 ,trial_status=null,free_trial_date=null WHERE user_id = ". $user_id;					
								$this->execute_query($queryUpdate);
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
		 * Create a new user for clinic.
		 *
		 * @access public
		 */
		function createSubscriber()
		{
			
			$replace = array();
			
			$userInfo = $this->userInfo();
			
			
			$userId = $userInfo['user_id'];
			$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";			
			
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else if ($userType == "SysAdmin") 			
			{	
				//check if form has been submitted or not
				
				include_once("template/subscriberManager/subscriberArray.php");

				$this->formArray = $formArray;			
				
				//Also have the questions from question table
				/**
				 * Remove challenging question from System admin interface.
				  
				 
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
				
					* End of comment for challenge question.
				*/
				
				if (isset($_POST['submitted_add']) && $_POST['submitted_add'] == 'Add Subscriber')
				{
					//form submitted check for validation
					$this->validateFormSubscriber("Add");					
				
					if($this->error == "")
					{
						//Form validated, no errors
						//go ahead store the values in db
						
						$system_access = ($this->value('usertype_id') == '4')? 1 : 0; 
						$therapist_access = ($this->value('usertype_id') == '2')? 1 : 0;
						
						$userTypeId = ($this->value('usertype_id') == '3')? 2 : $this->value('usertype_id');
						$admin_access = ($this->value('usertype_id') == '3')? 1 : 0;
						
						$insertArr = array(
											'usertype_id'=>$userTypeId,
											'username' =>$this->value('username'),
											'password' => $this->value('new_password'),
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
											'creation_date' => date('Y-m-d H:i:s',time()),
											'modified' => date('Y-m-d H:i:s',time()),
											'admin_access'=> $admin_access,
											'system_access' => $system_access,
											'therapist_access' => $therapist_access,
											'status'=> $this->value('status'),
											'country'=> $this->value('clinic_country'),
											//'free_trial_date'=>date('Y-m-d H:i:s',time()),
											//'trial_status'=>1,
											'practitioner_type'=>$this->value('practitioner_type')
											);
						$this->check_send_invitation($this->value('username'),$this->value('name_first'),$this->value('name_last'),$this->value('practitioner_type'));						
						$result = $this->insert('user',$insertArr);
						
						$newlyCreatedUserId = $this->insert_id();
						
                        
                        $clinicId = $this->value('clinic_id');
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
						if ($this->value('usertype_id') == '3' || $this->value('usertype_id') == '2') //edited so that therapist is also incorporated along with account admin
						{
							$clinicId = $this->value('clinic_id');
							$insertArrClinicUser = array(
												'user_id'=>$newlyCreatedUserId,
												'clinic_id' =>$clinicId											
												);						
							
							$result = $this->insert('clinic_user',$insertArrClinicUser);
						}		
						
						// Also handle the copying of article and treatment template for user if usertype is therapist
						
						{//usertype = therapist	
							if(	$this->value('usertype_id') == '3' || $this->value('usertype_id') == '2')
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
											
																						
											$result = $this->insert('article',$insertArr);
											$newArticleId = $this->insert_id();
											
											if (!empty($row['file_url']))
											{
												/*$fileDest = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url']. $newArticleId . '/';
												if(!file_exists($fileDest))
												{
													mkdir($fileDest);
													chmod($fileDest, 0777);
												}
												
												
												$fileDestPath = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url']. $newArticleId . '/'.$row['file_url'];
																							
												$fileSourcePath = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$row['article_id'] . '/'.$row['file_url'];
												
												copy($fileSourcePath,$fileDestPath);*/
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
							$fullName = html_entity_decode($this->value('name_first')." ".$this->value('name_last'), ENT_QUOTES, "UTF-8");
							$replace['username'] = $this->value('username');
							$replace['password'] = $this->value('new_password');
							$replace['url'] = $this->config['url'];
							$replace['images_url'] = $this->config['images_url'];
							
							$clinic_type = $this->getUserClinicType($newlyCreatedUserId);
							
							$queryUserType = "SELECT * FROM user WHERE status = 1 AND user_id = ".$newlyCreatedUserId;
							$result = $this->execute_query($queryUserType);
							
							if($this->num_rows($result)!= 0)
								{
									 $rowID = $this->fetch_array($result);
									 $user_Admin = $rowID['admin_access'];
									 $user_Therapist = $rowID['therapist_access'];
								$clinic_channel=$this->getchannel($clinicId);
								
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
										if($user_Therapist == 1){
											$subject = "Your Tx Xchange Account Information";
											$message = $this->build_template($this->get_template("newUserMailContent_plpto"),$replace);
										}elseif($user_Admin == 1){
											$subject = "Tx Xchange Account Information";
											$message = $this->build_template($this->get_template("newUserAdminMailContent_plpto"),$replace);
										}
									}else{
									if($user_Therapist == 1){
                                            $subject = "Your wholemedx Account Information";
                                            $message = $this->build_template($this->get_template("newUserMailContent_wx"),$replace);
                                        }elseif($user_Admin == 1){
                                            $subject = "Wholemedx Account Information";
                                            $message = $this->build_template($this->get_template("newUserAdminMailContent_wx"),$replace);
                                        }
									}
								}
							//$message = $this->build_template($this->get_template("newUserMailContent"),$replace);
							//$message = "User Name: ".$_POST['email_address']."<br> Password : ".$userPass;
							
							
							$to = $fullName.'<'.$this->value('username').'>';
							
							
							// To send HTML mail, the Content-type header must be set
							$headers  = 'MIME-Version: 1.0' . "\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
							
							// Additional headers
							//$headers .= 'To: '.$fullName.'<'.$_POST['email_address'].'>'. "\n";
							
							//$headers .= 'From: '.$this->config['from_email_address'] . " \n";
							//$headers .= 'From: support@txxchange.com' . "\n";
							//$returnpath = '-fsupport@txxchange.com';
						
							if( $clinic_channel == 1){
							    $headers .= "From: <".$this->config['email_tx'].">" . "\n";
							    $returnpath = "-f".$this->config['email_tx'];
							    }else{
							    $headers .= "From: <".$this->config['email_wx'].">" . "\n";
							    $returnpath = '-f'.$this->config['email_wx'];   
							    }
                            // Mail it					
							mail($to, $subject, $message, $headers, $returnpath);	
							
						}
						
						
						// redirect to list of subscriber
						header("location:index.php?action=subscriberListing");
						
						
					}
					else
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
							$replace['clinic_name'] = $this->value('clinic_name');
							$PractitionerArray = array("" => "Choose Provider...");
							$query="select * from practitioner where   status =1";
							$result=$this->execute_query($query);
							while($row1=$this->fetch_array($result)){
								$PractitionerArray[$row1['practitioner_id']]=$row1['name'];
							}
							
							$replace['PractitionerOptions'] = 	$this->build_select_option($PractitionerArray, $replace['practitioner_type']);
						
						}
						else 
						{
							$replace['selClinicDisplay'] = "none";
							$replace['clinic_name'] = "";
							$replace['clinic_id']="";
							$PractitionerArray = array("" => "Choose Provider...");
							$query="select * from practitioner where   status =1";
							$result=$this->execute_query($query);
							while($row1=$this->fetch_array($result)){
								$PractitionerArray[$row1['practitioner_id']]=$row1['name'];
							}
							
							$replace['PractitionerOptions'] = 	$this->build_select_option($PractitionerArray, $replace['practitioner_type']);
						}
						//Also the status
						$selectedStatus = $this->value('status');
					}
					
				}
				else 
				{
					//first time form
					$replace = $this->fillForm($this->formArray);
					//Also set the question
					//$selectedQuestion = "";
					$selectedState = "";
					$selectedUserType = "";
					$selectedStatus = "1";
					$replace['selClinicDisplay'] = "none";
					$replace['clinic_id'] = "";
					$replace['clinic_name'] = "";
						$PractitionerArray = array("" => "Choose Provider...");
							$query="select * from practitioner where   status =1";
							$result=$this->execute_query($query);
							while($row=$this->fetch_array($result)){
								$PractitionerArray[$row['practitioner_id']]=$row['name'];
							}
							
							$replace['PractitionerOptions'] = 	$this->build_select_option($PractitionerArray, $replace['practitioner_type']);
				}				
				

							//print_r($_REQUEST);

			            $replace['patient_country_options'] = $this->build_select_option($countryArray, $_REQUEST['clinic_country']);
			            
						 if($_REQUEST['clinic_country']=='US') {
			            $stateArray = array("" => "Choose State...");
			            $stateArray = array_merge($stateArray,$this->config['state']);              
			            $replace['stateOptions'] = $this->build_select_option($stateArray, $_REQUEST['state']);         
			            }
			           
			            else if($_REQUEST['clinic_country']=='CAN') {
			            $stateArray = array("" => "Choose Province...");
			            $stateArray = array_merge($stateArray,$this->config['canada_state']);
			            $replace['stateOptions'] = $this->build_select_option($stateArray, $_REQUEST['state']);         
               }


				
				
				//$replace['questionOptions']	= $this->build_select_option($questions,$selectedQuestion);
					
				$replace['optionsUserType']	= $this->build_select_option($arrUserType,$selectedUserType);
				$replace['optionsStatus']	= $this->build_select_option($arrStatus,$selectedStatus);
						
				
				$replace['header'] = $this->build_template($this->get_template("header"));
				$replace['footer'] = $this->build_template($this->get_template("footer"));							
				//$replace['sidebar'] = $this->build_template($this->get_template("sidebar"));					
				$replace['sidebar'] = $this->sidebar();

				$countryArray = $this->config['country'];
           			$replace['country']=implode("','",$countryArray); 
            		        $replace['patient_country_options'] = $this->build_select_option($countryArray, $_REQUEST['clinic_country']);
            
            		        
            		        if($_REQUEST['clinic_country']!='')
            		        {
            		        	
            		        			 if($_REQUEST['clinic_country']=='US') {
			            $stateArray = array("" => "Choose State...");
			            $stateArray = array_merge($stateArray,$this->config['state']);              
			            $replace['stateOptions'] = $this->build_select_option($stateArray, $_REQUEST['state']);         
			            }
			           
			            else if($_REQUEST['clinic_country']=='CAN') {
			            $stateArray = array("" => "Choose Province...");
			            $stateArray = array_merge($stateArray,$this->config['canada_state']);
			            $replace['stateOptions'] = $this->build_select_option($stateArray, $_REQUEST['state']);         
            		        	
			            }
            		        }
            		        
            		        else if($_REQUEST['clinic_country']=='' || $_REQUEST['clinic_country']=='NULL'){
            		         $stateArray = array("" => "Choose State...");
			         $stateArray = array_merge($stateArray,$this->config['state']);              
			         $replace['stateOptions'] = $this->build_select_option($stateArray, $_REQUEST['state']);         
            		        }
				
				$replace['body'] = $this->build_template($this->get_template("createSubscriber"),$replace);
				$replace['browser_title'] = "Tx Xchange: Create Provider or System Admin";
				$this->output = $this->build_template($this->get_template("main"),$replace);
					
			}
			else 
			{
				header("location:index.php");
			}
			
			
		}	
		
		
		/**
		 * Edit existing user of clinic.
		 *
		 * @access public
		 */
		function editSubscriber()
		{
			
			$id = (int) $this->value('id');	
			
			$replace = array();
			
			$userInfo = $this->userInfo();			
			//print_r($userInfo);
			$userId = $userInfo['user_id'];
			$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";			
			
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else if ($userType == "SysAdmin" || $userId == $id ) 			
			{	
				
				
				include_once("template/subscriberManager/subscriberArray.php");

				$this->formArray = $formArray;			
                
				
				
				//check if form has been submitted or not
				
				if (isset($_POST['submitted_edit']) && $_POST['submitted_edit'] == 'Save Subscriber')
				{
					//form submitted check for validation
					
					$this->validateFormSubscriber("Edit",$id);	
					
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
								//	$this->error = "There are patient(s) who are associated with this therapist please remove the association and do the action";
                                    $this->error = "";
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
						//Form validated, no errors
						//go ahead store the values in db
                        $trial_status       =   null;
                        $free_trial_date    =   null;
                        $status             =   $this->value('status');
						$sql="select * from user where user_id =".$id;
                        $sqlquery=@mysql_query($sql);
                        $sqlresult=@mysql_fetch_array($sqlquery);
						if($sqlresult['status']!=$_POST['status']){
						  if(($sqlresult['status']==1 and $_POST['status']==3) or ($sqlresult['status']==2 and $_POST['status']==3)){
						      //change status active /inactive to trial
                              $trial_status=1;
                              $free_trial_date=date('Y-m-d H:i:s',time());
                              $status=1;
						  }
                          if($sqlresult['status']==3 and $_POST['status']==1){
                           // do need full 
                           $trial_status=null;
                           $free_trial_date=null;
                           $status=1;
                           
                          }
                          if($sqlresult['status']==3 and $_POST['status']==2){
                           // do need full
                           $trial_status=null;
                           $free_trial_date=null;
                           $status=2; 
                           
                          }
                        }
						
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
											'status'=> $status,
											'practitioner_type'=>$this->value('practitioner_type'),
						                                        'trial_status'=>$trial_status,
						                                        'free_trial_date'=>$free_trial_date,
											'country'=>$this->value('clinic_country'),
											);
											
                                                                                                                
						// Encrypt data
                            //$encrypt_field = array('address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
                            //$updateArr = $this->encrypt_field($updateArr, $encrypt_field);
                        // End Encryption
                        
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
						
						// redirect to list of subscriber
						header("location:index.php?action=subscriberListing");
						
						
					}
					else
					{
						
						$replace = $this->fillForm($this->formArray,$_POST);		
						$replace['subscriber'] = strtoupper($_POST['subscriber']);
				
						$replace['error'] = $this->error;	
						
						//Also set the question
						//$selectedQuestion = ($this->value('question_id') == "")? "": $this->value('question_id');
						
						//Also the state
						$selectedState = ($this->value('state') == "")? "": $this->value('state');
						
						//Also the user type
						//$selectedUserType = ($this->value('usertype_id') == "")? "": $this->value('usertype_id');
						
						//Also the status
                        $selectedStatus = ($this->value('status') == "")? "": $this->value('status');
                        $selectedPractitioner=($this->value('practitioner_type') == "")? "": $this->value('practitioner_type');
                        $query_status = "SELECT trial_status,free_trial_date FROM user WHERE user_id = ".$id;
						$result_status = $this->execute_query($query_status);	
                        $row_status = $this->fetch_array($result_status);
                        if($row_status['trial_status']==1 and !empty($row_status['free_trial_date'])){
                            $selectedStatus=3;
                        }
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
                        // Decrypt data
                            $encrypt_field = array('name_title','name_first','name_last','password','address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
                            $row = $this->decrypt_field($row, $encrypt_field);
                        // End Decrypt	
					}
					
					//$selectedQuestion = $rowAnswer['question_id'];
					$selectedState = $row['state'];		
					//$selectedUserType = $row['usertype_id'];
                    $selectedPractitioner=$row['practitioner_type'];
					$selectedStatus = $row['status'];
					if($row['trial_status']==1 and !empty($row['free_trial_date'])){
                    $selectedStatus =   3;
                    }
					$replace = $this->fillForm($this->formArray,$row);
					$replace['subscriber'] = strtoupper($row['name_first']." ".$row['name_last']);
					
				}			
				
				$replace['id'] = $id;
                  $usertype_id=$this->value('usertype_id');
                if($usertype_id==0 or $usertype_id=='' or empty($usertype_id)){
                    $usertype_id=$row['usertype_id'];
                  }
                
                if($usertype_id==2){
						$replace['selPractitionerOptionsDisplay']='block';
					}
					else{
						$replace['selPractitionerOptionsDisplay']='none';
				}	
               	$replace['usertype_id'] = $usertype_id;	
               	$replace['new_password'] = $this->value('new_password');
                $replace['new_password2'] = $this->value('new_password2');
                
                if( !isset($replace['new_password']) || empty($replace['new_password']) ){
                    $replace['new_password'] = $this->userInfo('password',$id);
                }
                if( !isset($replace['new_password2']) || empty($replace['new_password2']) ){
                    $replace['new_password2'] = $this->userInfo('password',$id);
                }
				    
				if($selectedPractitioner==0 or $selectedPractitioner=='' or empty($selectedPractitioner))	
				$selectedPractitioner=$row['practitioner_type'];
                                
				$PractitionerArray = array("" => "Choose Provider...");
				$query="select * from practitioner where   status =1";
				$result=$this->execute_query($query);
				while($rowPractitioner=$this->fetch_array($result)){
					$PractitionerArray[$rowPractitioner['practitioner_id']]=$rowPractitioner['name'];
				}
				$replace['PractitionerOptions'] = 	$this->build_select_option($PractitionerArray, $selectedPractitioner);				
				
				$stateArray = array("" => "Choose State...");
				$stateArray = array_merge($stateArray,$this->config['state']);
				
				$replace['stateOptions'] = 	$this->build_select_option($stateArray,$selectedState);
                               		
				$replace['optionsStatus']	= $this->build_select_option($arrStatus,$selectedStatus);
				
						
				
				$replace['header'] = $this->build_template($this->get_template("header"));
				$replace['footer'] = $this->build_template($this->get_template("footer"));							
				$replace['sidebar'] = $this->sidebar();

				$countryArray = $this->config['country'];
		           	$replace['country']=implode("','",$countryArray); 
		           	
		            
			 if($_REQUEST['clinic_country']!='')
            		        {
            		        $replace['patient_country_options'] = $this->build_select_option($countryArray, $_REQUEST['clinic_country']);
		            	
            		        			 if($_REQUEST['clinic_country']=='US') {
			            $stateArray = array("" => "Choose State...");
			            $stateArray = array_merge($stateArray,$this->config['state']);              
			            $replace['stateOptions'] = $this->build_select_option($stateArray, $_REQUEST['state']);         
			            }
			           
			            else if($_REQUEST['clinic_country']=='CAN') {
			            $stateArray = array("" => "Choose Province...");
			            $stateArray = array_merge($stateArray,$this->config['canada_state']);
			            $replace['stateOptions'] = $this->build_select_option($stateArray, $_REQUEST['state']);         
            		        	
			            }
            		        }
		           elseif(!isset($_REQUEST['clinic_country'])){
		           	
		           	
		           	$replace['patient_country_options'] = $this->build_select_option($countryArray, $row['country']);
		            
		          	if($row['country']=='US') {
		          	  $stateArray = array("" => "Choose State...");
		          	  $stateArray = array_merge($stateArray,$this->config['state']);              
		          	  $replace['stateOptions'] = $this->build_select_option($stateArray, $row['state']);         
		          	 }
		           
		          	 else if($row['country']=='CAN') {
		          	  $stateArray = array("" => "Choose State...");
		           	  $stateArray = array_merge($stateArray,$this->config['canada_state']);
		           	  $replace['stateOptions'] = $this->build_select_option($stateArray, $row['state']);         
		               	 }   
		           }
		           
            
           
				$replace['body'] = $this->build_template($this->get_template("editSubscriber"),$replace);
				$replace['browser_title'] = "Tx Xchange: Edit Provider or System Admin Infomation";
				$this->output = $this->build_template($this->get_template("main"),$replace);
					
			}
			else 
			{
				header("location:index.php");
			}
			
			
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
			include_once("template/subscriberManager/subscriberArray.php");
			
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
		 * Creating Check boxes dynamically.
		 *
		 * @param array $arrCheckBox
		 * @param string $inputName
		 * @param string $value
		 * @return string
		 * @access public
		 */
		function buildInputCheckBox($arrCheckBox,$inputName, $value)
		{
			$noElements = count($arrCheckBox); 
			
			$inputCheckBox = "";
			
			for($i=0;$i<$noElements;++$i)
			{
				$inputCheckBox.= "<input type='checkbox' name='".$inputName."[]' " .$arrCheckBox[$i]['extra']. " value='".$arrCheckBox[$i]['value']."'";
				if (is_array($value))
				{
					$inputCheckBox.= in_array($arrCheckBox[$i]['value'],$value) ? "checked='checked'":"";	
				}				
				
				$inputCheckBox.= " /> &nbsp;<label>". $arrCheckBox[$i]['lblName']."</label>&nbsp";		
			}
			
			return $inputCheckBox;
			
		}
		
		
		/**
		 * Creating Radio Buttons dynamically.
		 *
		 * @param array $arrRadioBox
		 * @param string $inputName
		 * @param string $value
		 * @return string
		 * @access public
		 */
		function buildInputRadioBox($arrRadioBox,$inputName,$value)
		{
			$noElements = count($arrRadioBox); 
			
			$inputRadioBox = "";
			
			for($i=0;$i<$noElements;++$i)
			{
				$inputRadioBox.= "<input type='radio' name='".$inputName."' " .$arrRadioBox[$i]['extra']. " value='".$arrRadioBox[$i]['value']."'";
				if ($arrRadioBox[$i]['value'] == $value)
				{
					$inputRadioBox.= "checked='checked'";	
				}				
				
				$inputRadioBox.= " /> &nbsp;<label>". $arrRadioBox[$i]['lblName']."</label>&nbsp";		
			}
			
			return $inputRadioBox;
		}
		
		
		/**
		 * Validate form fields
		 *
		 * @param string $formType
		 * @param integer $uniqueId
		 */
		function validateFormSubscriber($formType, $uniqueId = false)
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
			
			// matching password and confirm password
			$arrFieldNames = array("new_password","new_password2");
			$arrFieldValues = array($_POST["new_password"],$_POST["new_password2"]);
			$objValidationSet->addValidator(new  IdenticalValuesValidator($arrFieldNames, "New password and  confirm passsword does not match",$arrFieldValues));					
			
			/**
			
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
			
			**/
			
			// validating first name field
			$objValidationSet->addValidator(new  StringMinLengthValidator('name_first', 1, "First Name cannot be empty",$this->value('name_first')));		
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('name_first',null,"Please enter valid characters in first name",$this->value('name_first')));
			
			// validating last name field
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

			if ($this->value('usertype_id') == '3' || $this->value('usertype_id') == '2')
			{
				
				if ($this->value('practitioner_type') == '')
				{
					
					$objValidationErr = new ValidationError('practitioner_type',"Please choose Provider type");
					$objValidationSet->addValidationError($objValidationErr);
				}				
			}

			if ($objValidationSet->hasErrors())
			{
				if($formType == "Add" )
				{
					//$arrayFields = array("username","new_password","question_id","answer","name_first","name_last","address","address2","city","zip","usertype_id","clinic_id");
					$arrayFields = array("username","new_password","name_first","name_last","address","address2","city","zip","usertype_id","clinic_id","practitioner_type");
				}
				else if($formType == "Edit" )
				{
					if ($_POST['new_password'] == '')
					{
						//$arrayFields = array("username","question_id","answer","name_first","name_last","address","address2","city","zip"/*,"usertype_id"*/);
						$arrayFields = array("username","name_first","name_last","address","address2","city","zip","practitioner_type","usertype_id");						
					}
					else 
					{
						//$arrayFields = array("username","new_password","question_id","answer","name_first","name_last","address","address2","city","zip"/*,"usertype_id"*/);												
						$arrayFields = array("username","new_password","name_first","name_last","address","address2","city","zip","practitioner_type","usertype_id");
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
		 * Populate clinic list.
		 *
		 * @access public
		 */
		function PopupClinicList()
		{
		
			$replace = array();			
			
			$sql = "SELECT * FROM clinic WHERE status = '1'";
			$result = $this->execute_query($sql);
			$totalRows = $this->num_rows($result);
			$rows = $this->populate_array($result, '0');
					
			$k=0;
			$template = '';
			$template = $this->build_template($this->get_template("clinicListMainStartOuter"));
			
			if ($totalRows >= 1 )
		  	{
				$template .= $this->build_template($this->get_template("clinicListMainStart"));
	           		foreach($rows as $row)
	           		{       
	           			$clinicName = $row['clinic_name'];
	           			$template .= $this->build_template($this->get_template("clinicListChild"),$row);				   		
	                	$k++;
	                	if($k >= 2)
	                	{
					    	$k=0;
	                    	$template .= '</tr><tr>';
	                	} // IF close
	          		} // Foreach Close
	      		 $template .= $this->build_template($this->get_template("clinicListMainEnd"));
		  	} // IF Close
			else
			{
				$template .= $this->build_template($this->get_template("clinicListChildNoData"));				 
			} // Else Close
			
		    $template .= $this->build_template($this->get_template("clinicListMainEndOuter"));
		    
		    $replace['clinicList'] = $template;
		    if ($totalRows >= 1 )
		    {
		    	$replace['clinicListPopupButton'] .= $this->build_template($this->get_template("clinicListPopupButton"));
		    }
		    else 
		    {
		    	$replace['clinicListPopupButton'] .= "";
		    }
	    	//$replace['header'] = $this->build_template($this->get_template("header"));
			//$replace['footer'] = $this->build_template($this->get_template("footer"));		
			$replace['body'] = $this->build_template($this->get_template("clinicListPopup"),$replace);
			$replace['browser_title'] = "Clinic List";
			$this->output = $this->build_template($this->get_template("main"),$replace);
			
		}// Function Close
		
		//End
		
		### Extra Functions #####
		
		/**
		 * Constructs a fragment of a "WHERE" clause that can be used to conduct a search.
		 * Note that this uses "LIKE" subclauses with wildcards, and will force a full table scan.
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
		 * Populate side panel for page dynamically.
		 *
		 * @return string
		 * @access public
		 */
		function sidebar(){
			$data = array(
				'name_first' => $this->userInfo('name_first'),
				'name_last' =>  $this->userInfo('name_last')
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
        
                /**
        * This function will copy those articles,  which are associated with plan.
        * 
        */
        function copy_article($newlyPlanId,$plan_id){
            
            {//Plan_Article Block
                
                $queryPlanArticle = "SELECT * FROM plan_article WHERE status = 1 AND plan_id = '{$plan_id}' " ;
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
        }
        /**
        * This function copies plan information from plan for same clinic user.
        * 
        */
        function copy_plan($userId,$planId,$new_name=""){
            if( is_numeric($userId) && is_numeric($planId) ){
                 
                if($new_name == "" ){
                    // Retrive plan from plan table.
                    $query = "select * from plan where plan_id = '{$planId}' ";
                    $result = @mysql_query($query);
                    $row = @mysql_fetch_array($result);
                    $new_name = $row['plan_name'];
                }
                
                // Create array for inserting record.
                $insertArr = array(
                    'plan_name'=> $new_name,
                    'parent_template_id' => NULL,
                    'user_id' => $userId,
                    'patient_id' => NULL,
                    'user_type' => 2,
                    'is_public' => NULL,
                    'creation_date' => $row['creation_date'],                                                                            
                    'status' => 1
                );
                
                // Insert record.
                $result = $this->insert('plan',$insertArr);
                
                //Get new plan id
                $newlyPlanId = $this->insert_id();
                
                // Copy treatments associated with planId.
                if(is_numeric($newlyPlanId)){
                    // copy treatments in the plan.
                    $this->copy_plan_treatment($newlyPlanId,$planId);
                    // copy articles in the plan.
                    $this->copy_article($newlyPlanId,$planId);
                }
            }     
        
        }
        /**
        * @desc This function copies all Treatments associated with the given plan.
        */
        function copy_plan_treatment($newlyPlanId,$planId){
            
            // Check planId is numeric or not.
            if(is_numeric($planId)){
                
                // Get treatment from plan_treatment table.
                $queryPlanTreatment = "SELECT * FROM plan_treatment WHERE plan_id = '{$planId}' " ;
                $resultPlanTreatment = @mysql_query($queryPlanTreatment);

                // Check for number of treatment in the plan. Must be greater then Zero.
                if( @mysql_num_rows($resultPlanTreatment) > 0 )
                {
                    // Create Array for Treatment present in the planId.
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
                                    'creation_date' => $row['creation_date']            
                                );
                        
                        // Insert Treatment in plan_treatment table for PlanId.        
                        $result = $this->insert('plan_treatment',$insertArr);
                        
                    } 
                } 
            }
        }
		
        /**
        *  This function sends mail of login detail to patient.
        */
        function system_mail_login_detail_provider(){
            if(is_numeric($this->value('provider_id'))){
                $query = "select user_id,username,password from user where usertype_id = 2 and user_id = '{$this->value('provider_id')}' and (status = 1 or status = 2 or status=3 )";
                $result = @mysql_query($query);
                if( $row = @mysql_fetch_array($result) ){
                    $email_address = $row['username'];
                    $to = $email_address;
                    //$to = "manabendra.sarkar@hytechpro.com";
                    $clinicName=html_entity_decode($this->get_clinic_info($row['user_id'],'clinic_name'), ENT_QUOTES, "UTF-8");
                    $subject = "Information from ".$clinicName;
                    $password = $this->decrypt_data($row['password']);
                    $images_url = $this->config['images_url'];
                    $user_id = $row['user_id'];
                    //$this->get_template("resend_login_detail");
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
	                
                    if( $clinic_channel == 1)
                   	$message = $this->build_template($this->get_template("resend_login_detail_plpto"),$data);
	               else
	                $message = $this->build_template($this->get_template("resend_login_detail_wx"),$data);
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
        
	}// Class Closed
	
	
	/**
	 * Initialize the object of this class
	 */	
	$obj = new subscriberManager();
?>
