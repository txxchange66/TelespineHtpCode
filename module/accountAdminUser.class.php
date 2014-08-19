<?php



	/**
	 * 
	 * Copyright (c) 2008 Tx Xchange.
	 * 
	 * This Class includes the functionality like list all the user (Therapist as well as Account Admin) of clinic.
	 * It also provides the functionality of edit and remove users from particular clinic.
	 * 
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
	
	
	// including files
	require_once("include/paging/my_pagina_class.php");		
	require_once("module/application.class.php");
	require_once("include/validation/_includes/classes/validation/ValidationSet.php");
	require_once("include/validation/_includes/classes/validation/ValidationError.php");		
	require_once("include/excel/excelwriter.inc.php");
	
	// class declaration
  	class accountAdminUser extends application{
  		
  	// class variable declaration section
  	
  		/**
  		 * The variable defines the action request
  		 *
  		 * @var string
  		 * @access private
  		 */  		
		private $action;
		

		/**
		 * array
		 *
		 * @var unknown_type
		 * @access private
		 */
		private $arr;
		
		
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
		 * 
		 *
		 * @var string
		 * @access private
		 */
		private $invalid;
		
		
		/**
		 * 
		 *
		 * @var string
		 * @access private
		 */
		private $uploaded;
		
		
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
				/*
					This block of statement(s) are to handle all the actions supported by this Login class
					that is it could be the case that more then one action are handled by login
					for example at first the action is "login" then after submit say action is submit
					so if login is explicitly called we have the login action set (which is also our default action)
					else whatever action is it is set in $str.				
				*/
				$str = $this->value('action');
			}else{
				$str = "userListing"; //default if no action is specified
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
                $arr_action = array('userListing','addUser','editUser','deleteUser');
                if( is_numeric($parent_clinic_id) && $parent_clinic_id == 0){
                    if(in_array($str,$arr_action)){
                       header('location:index.php?action=accountAdminClinicList');
                       exit();
                    }
                }
                // End
				$str = $str."()";
				eval("\$this->$str;"); 
			}
			else{
				$this->output = $this->config['error_message'];
			}
			/*
			$str = $str."()";
			eval("\$this->$str;"); 
			*/
			$this->display();
		}

		
		
		/**
		 * List of users (therapist) of that particluar clinic
		 *
		 * @access public
		 */
		function userListing()
		{
						
			$replace = array();
		$userInfo = $this->userInfo();
            $cli_type=$this->getClinicDetails($this->userInfo('user_id'));
            //if($cli_type['clinic_type']==4){
                $replace['shopshow']=1;
            /*}else{
                $replace['shopshow']='0';
            }*/
			$userInfo = $this->userInfo();
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else 
			{								
				/* Defining Sorting */				
			
				
				$orderByClause = "";
				if ($this->value('sort') == '') 
				{
					$replace['full_name'] = "action=userListing&sort=full_name&order=ASC".$replace['searchStr'];
					$replace['username'] = "action=userListing&sort=username&order=DESC".$replace['searchStr'];					
					//Removed sorting on type for BUg No 9961
					//Again added sorting discussed with puneet order by on 2 fields
					$replace['usertype_id'] = "action=userListing&sort=usertype_id&order=ASC".$replace['searchStr'];
					/* Added for last login column Feb 19, 2008 */	
					$replace['last_login'] = "action=userListing&sort=last_login&order=ASC".$replace['searchStr'];				
					/* End */
					$replace['usernameImg'] = '&nbsp;<img src="images/sort_asc.gif">';
					
					$orderByClause = " cast(full_name as char)  ASC ";
				}
				else {
					
					$queryStr = $replace['searchStr'];
					$this->setSortFields($replace,"userListing",$queryStr);	
					$orderByClause = $replace['orderByClause'];
					
				}
				
				/* End */	
				//Get the logged in user's clinic id
				$clinicId = $this->clinicInfo('clinic_id');
				
				//Get the therapist list
			    $privateKey = $this->config['private_key'];
				 $sqlUser = "SELECT user.*,cast(AES_DECRYPT(UNHEX(user.name_first),'a2dc2a99e649f147bcabc5a99bea7d96') as char ) as name_first_sort ,cast(AES_DECRYPT(UNHEX(user.name_last),'a2dc2a99e649f147bcabc5a99bea7d96') as char ) as name_last_sort , CONCAT_WS(' ',AES_DECRYPT(UNHEX(name_title),'{$privateKey}'),cast(AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as char),cast(AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as char),name_suffix) AS full_name FROM user 
					inner join clinic_user on clinic_user.user_id = user.user_id and user.status = '1'  and clinic_user.clinic_id = '$clinicId' 
					inner join clinic on clinic.clinic_id = clinic_user.clinic_id and clinic.status = '1' WHERE user.usertype_id = '2' 
					ORDER BY ".$orderByClause;
				$sqlcount = "SELECT count(1) FROM user 
					inner join clinic_user on clinic_user.user_id = user.user_id and user.status = '1'  and clinic_user.clinic_id = '$clinicId' 
					inner join clinic on clinic.clinic_id = clinic_user.clinic_id and clinic.status = '1' WHERE user.usertype_id = '2'";
				
				//echo $sqlUser;
				
				//$result = $this->execute_query($sqlUser);				
				
				$link = $this->pagination($rows = 0,$sqlUser,'userListing','','','','',$sqlcount);                                          
	
	            $replace['link'] = $link['nav'];
	
	            $result = $link['result']; 	
				$sqlTotTherapists = "SELECT count(user.user_id) AS totTherapists FROM user 
					inner join clinic_user on clinic_user.user_id = user.user_id and user.status = '1'  and clinic_user.clinic_id = '$clinicId' 
					inner join clinic on clinic.clinic_id = clinic_user.clinic_id and clinic.status = '1' 
					where user.therapist_access = '1' AND user.usertype_id = '2'";
	            //$sqlTotTherapists = "SELECT count(user.user_id) AS totTherapists FROM user, clinic_user WHERE (user.therapist_access = 1) AND user.status = 1 AND clinic.status = 1 AND clinic_user.clinic_id = '$clinicId' AND user.user_id = clinic_user.user_id ";
	            $resultTotTherapists = $this->execute_query($sqlTotTherapists);
	            $rowTotTherapists = $this->fetch_array($resultTotTherapists);
	            
				$replace['totTherapist'] = $rowTotTherapists['totTherapists'];
				
				//$therapist_access = 0;
				//$patient_association = 0;
				
				if($this->num_rows($result)!= 0)
				{
					$replace['userTblHead'] = $this->build_template($this->get_template("userTblHead"),$replace);
					while($row = $this->fetch_array($result))
					{
						/*echo "Row : <br>";
						print_r($row);
						echo "Row End : <br>";*/
						{//Also if Therapist
							
							if ($row['therapist_access'] == 1)
							{
								//check if there are patient(s) associated with this therapist							
								$queryAssocPatients = "SELECT patient_id FROM therapist_patient WHERE therapist_id = ".$row['user_id'];
								$resultAssocPatients = $this->execute_query($queryAssocPatients);
								
								if ($this->num_rows($resultAssocPatients) == 0)
								{
									//No Patients association
									//$patient_association = 0;
									$row['patient_association'] = 0;
								}
								else 
								{
									//There exist patients association
									//$patient_association = 1;
									$row['patient_association'] = 1;
									
								}

													
								
							}
							
						}	
						
						$row['style'] = ($c++%2)?"line1":"line2";	
						
						/*Added last login column Feb 19, 2008 */	
						
						if ($row['last_login'] != null) 
						{
							//$row['last_login'] = date('D, M jS Y \a\t h:iA', strtotime($row['last_login']));					
							$row['last_login'] = $this->formatDate($row['last_login']);					
							
						}	
						else 
						{
							$row['last_login'] = "Never";
						}					
						/* End */
						
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
						else 
						{
							$row['usertype'] = "";
						}
						 
						$replace['userTblRecord'] .=  $this->build_template($this->get_template("userTblRecord"),$row);					
						
					}
				}
				else 
				{
					$replace['userTblHead'] = $this->build_template($this->get_template("userTblHead"),$replace);
					$replace['userTblRecord'] =  '<tr> <td colspan="5">No Users to list</td></tr>';
					$replace['link'] = "&nbsp;";
				}									
				
				
				$replace['header'] = $this->build_template($this->get_template("header"));
				$replace['footer'] = $this->build_template($this->get_template("footer"));	
				$replace['navigationTab'] = $this->build_template($this->get_template("navigationTab"));					
				$replace['sidebar'] = $this->sidebar();
				
				$replace['body'] = $this->build_template($this->get_template("userTemplate"),$replace);
				$replace['browser_title'] = "Tx Xchange: Users List";
				$replace['get_satisfaction'] = $this->get_satisfaction();
                $this->output = $this->build_template($this->get_template("main"),$replace);			
			}	
			
		}
		
		
		
		/**
		 * Create a new user (therapist or account admin) for clinic.
		 *
		 * @access public
		 */
		function addUser()
		{
			$replace = array();
			
                          /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
			$userInfo = $this->userInfo();
			
			$userId = $userInfo['user_id'];
			$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";					
			
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else 			
			{	
				//check if form has been submitted or not
				
				include_once("template/accountAdminUser/userArray.php");

				$this->formArray = $formArray;									
				
				$clinicId = $this->clinicInfo('clinic_id');	
				
				
				if (isset($_POST['submitted_add']) && $_POST['submitted_add'] == 'Add User')
				{
					//form submitted check for validation
					$this->validateFormUser("Add");					
				
					if($this->error == "")
					{
						
						//Form validated, no errors
						//go ahead store the values in db
						
						$admin_access = isset($_POST['adminAccess'])? 1 : 0; 
						$therapist_access = isset($_POST['therapistAccess'])? 1 : 0; 
						
						$insertArr = array(
											'usertype_id'=> 2,
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
											/*'phone2' => $this->value('phone2'),*/
											'fax' => $this->value('fax'),											
											'creation_date' => date('Y-m-d H:i:s',time()),
											'modified' => date('Y-m-d H:i:s',time()),
											'admin_access' => $admin_access,
											'therapist_access' => $therapist_access,
											'status'=> 1,
                                            //'free_trial_date'=>date('Y-m-d H:i:s',time()),
                        					//'trial_status'=>1,
											'practitioner_type'=>$this->value('practitioner_type')					
											);
						
						// Encrypt data
                            //$encrypt_field = array('password','address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
                            //$insertArr = $this->encrypt_field($insertArr, $encrypt_field);
                        // End Encryption
						$this->check_send_invitation($this->value('username'),$this->value('name_first'),$this->value('name_last'),$this->value('practitioner_type'));
						$result = $this->insert('user',$insertArr);
						
						$newlyCreatedUserId = $this->insert_id();		
						
						//Also associate this new user with the clinic so insert the record in clinic_user table
						
						$insertArr = array(
											'user_id'=>$newlyCreatedUserId,
											'clinic_id' =>$clinicId											
											);						
						
						$result = $this->insert('clinic_user',$insertArr);				
						
                        
                        // copy publish plan
                        if(is_numeric($clinicId)){
                            $this->copyPublishPlan($newlyCreatedUserId, $clinicId);    
                        }
                        
						// Also handle the copying of article and treatment template for user if user has the therapist privelege
						
						{//usertype = therapist	
							if	(($therapist_access == 1) || ($admin_access == 1))
							{
							
								//Article Block
								{
									// Original Query : New User not getting the Articles created by Account Admin
									//$queryArticle = "SELECT article.* FROM article INNER JOIN user ON article.user_id = user.user_id 
									//					WHERE user.usertype_id = 4 AND user.status = 1 AND article.status != 3";
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
												*/
												$fileDest = $this->check_article_path($newArticleId);
                                                $fileDestPath = $this->get_article_path($newArticleId);
                                                $fileSourcePath = $this->get_article_path($row['article_id']);
                                                copy($fileSourcePath,$fileDestPath);
                                                $this->update_article_path($newArticleId);
												
												//copy($fileSourcePath,$fileDestPath);	
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
																'user_type' => /*$this->value('usertype_id')*/2,
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
										
							}//end of if (access privilege == therapist)							
							
						}//End of usertype = therapist				
						
						
						//send mail that user created successfully
						{
							//have the HTML content
							$fullName = $this->value('name_first')." ".$this->value('name_last');
							$replace['username'] = $this->value('username');							
							$replace['password'] = $this->value('new_password');
							$replace['url'] = $this->config['url'];
							$replace['images_url'] = $this->config['images_url'];
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
						    $clinic_type = $this->getUserClinicType($newlyCreatedUserId);
							$queryUserType = "SELECT * FROM user WHERE status = 1 AND user_id = ".$newlyCreatedUserId;
							$result = $this->execute_query($queryUserType);
							
							if($this->num_rows($result)!= 0)
								{
									 $rowID = $this->fetch_array($result);
									 $user_Admin = $rowID['admin_access'];
									 $user_Therapist = $rowID['therapist_access'];
								    $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
									 if( $clinic_channel == 1){
										if($user_Therapist == 1 && $user_Admin != 1){
											$subject = "Your Tx Xchange Account Information";
											$message = $this->build_template($this->get_template("newUserMailContent_plpto"),$replace);
										}elseif($user_Admin == 1){
											$subject = "Tx Xchange Account Information";
											$message = $this->build_template($this->get_template("newUserAdminMailContent_plpto"),$replace);
										}
									}else{
									if($user_Therapist == 1 && $user_Admin != 1){
                                            $subject = "Your wholemedx Account Information";
                                            $message = $this->build_template($this->get_template("newUserMailContent_wx"),$replace);
                                        }elseif($user_Admin == 1){
                                            $subject = "wholemedx Account Information";
                                            $message = $this->build_template($this->get_template("newUserAdminMailContent_wx"),$replace);
                                        }
									}
								}							
	                        /*$clinic_type = $this->getUserClinicType($newlyCreatedUserId);
	                        if( $clinic_type == 'plpto'){
	                        	$subject = "Tx Xchange Account Information";
	                        	$message = $this->build_template($this->get_template("newUserMailContent_plpto"),$replace);
	                       	}
	                        elseif( $clinic_type == 'elpto' ){
	                        	$subject = "Tx Xchange Account Information";
	                        	$message = $this->build_template($this->get_template("newUserMailContent"),$replace);	
	                        }*/
							
							
							//$message = $this->build_template($this->get_template("newUserMailContent"),$replace);
							
							//$message = "User Name: ".$_POST['email_address']."<br> Password : ".$userPass;
							
							
							$to = $fullName.'<'.$this->value('username').'>';
							
							
							// To send HTML mail, the Content-type header must be set
							$headers  = 'MIME-Version: 1.0' . "\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
							
							// Additional headers
							//$headers .= 'To: '.$fullName.'<'.$_POST['email_address'].'>'. "\n";
							
							//$headers .= 'From: support@txxchange.com' . "\n";
							//$headers .= 'Cc: example@example.com' . "\n";
							//$headers .= 'Bcc: example@example.com' . "\n";
							//$returnpath = '-fsupport@txxchange.com';
						if( $clinic_channel == 1){
                        $headers .= "From: <".$this->config['email_tx'].">" . "\n";
					    $returnpath = "-f".$this->config['email_tx'];
					    }else{
					    $headers .= "From: <".$this->config['email_wx'].">" . "\n";
					    $returnpath = '-f'.$this->config['email_wx'];   
					    }
												// Mail it					
							@mail($to, $subject, $message, $headers, $returnpath);	
							
						}
						
						
						// redirect to list of subscriber
						header("location:index.php?action=userListing");
						
						
					}
					else
					{
						
						$replace = $this->fillForm($this->formArray,$_POST);	
										
						$replace['error'] = $this->error;							
											
						//Also the state
						$selectedState = ($this->value('state') == "")? "": $this->value('state');		

						if (isset($_POST['therapisAccess']))
						{
							$replace['checkedTherapist'] = "checked";	
						}
						else 
						{
							$replace['checkedTherapist'] = "";	
						}
						
						if (isset($_POST['adminAccess']))
						{
							$replace['checkedAdmin'] = "checked";	
						}
						else 
						{
							$replace['checkedAdmin'] = "";	
						}
						
						if (isset($_POST['clinicAddress']))
						{
							$replace['checkedAddress'] = "checked";	
						}
						else 
						{
							$replace['checkedAddress'] = "";	
						}
						
					
											
						
					}
					
				}
				else 
				{
					//first time form
					$replace = $this->fillForm($this->formArray);					
					$selectedState = "";
										
					$replace['checkedTherapist'] = "checked";
					$replace['checkedAdmin'] = "";
					$replace['checkedAddress'] = "";	
					
				}				
				$selectedPractitioner=$this->value('practitioner_type');
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
				
				$replace['header'] = $this->build_template($this->get_template("header"));
				$replace['footer'] = $this->build_template($this->get_template("footer"));							
				$replace['navigationTab'] = $this->build_template($this->get_template("navigationTab"));
				$replace['sidebar'] = $this->sidebar();
				
				// calling template	
				$replace['body'] = $this->build_template($this->get_template("createUser"),$replace);
				$replace['browser_title'] = "Tx Xchange: Add User";
				$replace['get_satisfaction'] = $this->get_satisfaction();
                $this->output = $this->build_template($this->get_template("main"),$replace);
					
			}
			
			
			
		}
		/**
		 * Return number of article for given user.
		 * 
		 */
		function article_count($id = ""){
			if(is_numeric($id)){
				$query = "SELECT count(*) FROM article WHERE (status = 1 or status = 2)  AND user_id = '".$id."' ";
				$result = @mysql_query($query);
				if($row = @mysql_fetch_array($result)){
					return $row[0];
				}
			}
			return 0;
		}
		/**
		 * Return number of plan for given user.
		 * 
		 */
		function plan_count($id = ""){
			if(is_numeric($id)){
				$query = "select count(*) from plan p where p.user_id = '{$id}' and p.user_type = '2' and p.patient_id is null and p.status = 1 "; 
				$result = @mysql_query($query);
				if($row = @mysql_fetch_array($result)){
					return $row[0];
				}
			}
			return 0;
		}

		/**
		 * Edit the existing user (Therapist or Account Admin)of clinic.
		 *
		 * @access public
		 */
		function editUser()
		{
			
			$id = (int) $this->value('id');	
			
			$replace = array();
                           /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
		$userInfo = $this->userInfo();
            $cli_type=$this->getClinicDetails($this->userInfo('user_id'));
            //if($cli_type['clinic_type']==4){
                $replace['shopshow']=1;
            /*}else{
                $replace['shopshow']='0';
            }*/
			$firstSubscription= "";		
			
			$userInfo = $this->userInfo();			
			
			$userId = $userInfo['user_id'];
			$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";			
			
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else
			{					
				
				include_once("template/accountAdminUser/userArray.php");

				$this->formArray = $formArray;			
				
				$clinicId = $this->clinicInfo('clinic_id');					
						
				
				//check if form has been submitted or not
				
				if (isset($_POST['submitted_edit']) && $_POST['submitted_edit'] == 'Edit User')
				{
					//form submitted check for validation
					
					$this->validateFormUser("Edit",$id);												
				
					if($this->error == "")
					{
						//Form validated, no errors
						//go ahead store the values in db
						
						if ($userId == $id)
						{	
							$admin_access = 1; 
						}	
						else 
						{
							$admin_access = isset($_POST['adminAccess'])? 1 : 0; 
						}
						
						$therapist_access_checkbox = isset($_POST['therapistAccess'])? 1 : 0; 						
						
						$updateArr = array(
											
											'username' =>$this->value('username'),											
											'name_first' => $this->value('name_first'),
											'name_last' => $this->value('name_last'),
											'address' => $this->value('address'),
											'address2' => $this->value('address2'),
											'city' => $this->value('city'),
											'state' => $this->value('state'),
											'country' => $this->value('clinic_country'),
											'zip' => $this->value('zip'),
											'phone1' => $this->value('phone1'),
											/*'phone2' => $this->value('phone2'),*/
											'fax' => $this->value('fax'),																						
											'modified' => date('Y-m-d H:i:s',time()),
											'admin_access' => $admin_access,
											'therapist_access' => $therapist_access_checkbox,
                                            'practitioner_type'=>$this->value('practitioner_type')
														
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
							$updateArr = array(
											'password'=>$this->value('new_password')										
											);
											
							// Encrypt data
                            //$encrypt_field = array('password');
                            //$updateArr = $this->encrypt_field($updateArr, $encrypt_field);
                            // End Encryption
                            $where = " user_id = ".$id;
							$result = $this->update('user',$updateArr,$where);	
							
						}
						
						{
							//if earlier user was having therapist access but now removed then delete therapist_patient association
							if ($_POST['therapist_access'] == 1)
							{
								/*
									so earlier this user was having therapist access now check if therapist check box is still
									check or not if not remove the therapist patient association									
								*/	
								
								if($therapist_access_checkbox == 0)
								{
									//remove the records from therapist_patient table
									
									$sqlUpdate = "DELETE FROM therapist_patient WHERE therapist_id = '".$id."'";			
									$result = $this->execute_query($sqlUpdate);
								}
								
							}
							
							
							
						}
						
						
						
						{//usertype = therapist	and first time subscription
							//if	($therapist_access_checkbox == 1 && $this->value('firstSubscription') == 'Yes')
							if($therapist_access_checkbox == 1 )
							{
							
								//Article Block
								{
									// Count number of Articles for new user. If zero article comes create new articles.
									if( !($this->article_count($id) > 0) ){						
									
									
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
																'user_id' => $id,
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
												$fileDestPath = $this->update_article_path($newArticleId);
												$fileSourcePath = $this->update_article_path($row['article_id']);
												copy($fileSourcePath,$fileDestPath);
												$this->update_article_path($row['article_id']);	
											}										
											
											
										}										
									}
									
									}
								}
									
								//Plan Block
								{
									
									// Count number of Articles for new user. If zero article comes create new articles.
									if( !($this->plan_count($id) > 0) ){		
									
									
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
																'user_id' => $id,
																'patient_id' => NULL,
																'user_type' => /*$this->value('usertype_id')*/2,
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
									
								}
								
								}//End of plan block
								
								//Also update the user table with expiry date for this user as therapist access is given to him for the first time
							
								$updateArr = array(
																							
												'expiry_date' => date('Y-m-d H:i:s',mktime(date('H'), date('i'), date('s'), date("m")  , date("d")+15, date("Y")))
																	
												);
												
								$where = " user_id = ".$id;
														
								$result = $this->update('user',$updateArr,$where);	
										
							}//end of if (access privilege == therapist)							
							
						}//End of usertype = therapist		
							
						
						// redirect to list of subscriber
						header("location:index.php?action=userListing");
						exit();
						
					}
					else
					{
						
						$replace = $this->fillForm($this->formArray,$_POST);
						
						$firstSubscription = $this->value('firstSubscription');	
										
						$replace['error'] = $this->error;							
											
						//Also the state
						$selectedState = ($this->value('state') == "")? "": $this->value('state');		

						if (isset($_POST['therapistAccess']))
						{
							$replace['checkedTherapist'] = "checked";	
						}
						else 
						{
							$replace['checkedTherapist'] = "";	
						}
						
						if (isset($_POST['adminAccess']))
						{
							$replace['checkedAdmin'] = "checked";	
						}
						else 
						{
							if ($userId == $id)
							{
								$replace['checkedAdmin'] = "checked";	
							}
							else 
							{
								$replace['checkedAdmin'] = "";	
							}
						}
						
						if (isset($_POST['clinicAddress']))
						{
							$replace['checkedAddress'] = "checked";	
						}
						else 
						{
							$replace['checkedAddress'] = "";	
						}
						
						$replace['therapist_access'] = $_POST['therapist_access'];
						$replace['patient_association'] = $_POST['patient_association'];
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
                            $encrypt_field = array('name_first','name_last','password', 'address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
                            $row = $this->decrypt_field($row, $encrypt_field);
                        // End Decrypt
					}
					
					if (empty($row['expiry_date']))
					{
						$firstSubscription = "Yes";
					}
					else 
					{
						$firstSubscription = "No";
					}
					
					$selectedState = $row['state'];							
					
					//$row['confirmUsername'] = $row['username'];
					$replace = $this->fillForm($this->formArray,$row);
					//$replace['subscriber'] = strtoupper($row['name_first']." ".$row['name_last']);					
					
					$replace['therapist_access'] = ($row['therapist_access'] == 1)? 1 :0;
					{//Also if Therapist
							
						if ($row['therapist_access'] == 1)
						{
							//check if there are patient(s) associated with this therapist							
							$queryAssocPatients = "SELECT patient_id FROM therapist_patient WHERE therapist_id = ".$id;
							$resultAssocPatients = $this->execute_query($queryAssocPatients);
							
							if ($this->num_rows($resultAssocPatients) == 0)
							{
								//No Patients association
								//$patient_association = 0;
								$replace['patient_association'] = 0;
							}
							else 
							{
								//There exist patients association
								//$patient_association = 1;
								$replace['patient_association'] = 1;
								
							}												
							
						}
						
					}	
					
					
					
					$replace['checkedTherapist'] = ($row['therapist_access'] == 1)? "checked":"";
					$replace['checkedAdmin'] = ($row['admin_access'] == 1)? "checked":"";
					$replace['checkedAddress'] = "";
					
				}			
				
				 $replace['new_password'] = $this->value('new_password');
                $replace['new_password2'] = $this->value('new_password2');
                
                if( !isset($replace['new_password']) || empty($replace['new_password']) ){
                    $replace['new_password'] = $this->userInfo('password',$id);
                }
                if( !isset($replace['new_password2']) || empty($replace['new_password2']) ){
                    $replace['new_password2'] = $this->userInfo('password',$id);
                }
                
				//If clinic admin is editing his own details
				$replace['editAccess'] = ($userId == $id)?"disabled":"";
				
				$replace['firstSubscription'] = $firstSubscription;	
				
				$replace['id'] = $id;	
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
				$replace['header'] = $this->build_template($this->get_template("header"));
                $replace['navigationTab'] = $this->build_template($this->get_template("navigationTab"));
				$replace['footer'] = $this->build_template($this->get_template("footer"));																
				$replace['sidebar'] = $this->sidebar();

				$countryArray = $this->config['country'];
			        $replace['country']=implode("','",$countryArray); 
		                $replace['patient_country_options'] = $this->build_select_option($countryArray, $row['country']);
				            
				            
				            
				           
			            if($row['country']=='US') {
			            $stateArray = array("" => "Choose State...");
			            $stateArray = array_merge($stateArray,$this->config['state']);              
			            $replace['stateOptions'] = $this->build_select_option($stateArray,$selectedState);         
			            }
			           
			            else if($row['country']=='CAN') {
			            $stateArray = array("" => "Choose State...");
			            $stateArray = array_merge($stateArray,$this->config['canada_state']);
			            $replace['stateOptions'] = $this->build_select_option($stateArray, $selectedState);         
			            } 
				
				
				$replace['body'] = $this->build_template($this->get_template("editUser"),$replace);
				$replace['browser_title'] = "Tx Xchange: Edit User";
				$replace['get_satisfaction'] = $this->get_satisfaction();
                $this->output = $this->build_template($this->get_template("main"),$replace);
					
			}
			
			
			
		}
		
		
		/**
		 * Delete the user (Therapist or Account admin) of clinic.
		 * 
		 * @access public
		 *
		 */
		function deleteUser()
		{

						
			$userInfo = $this->userInfo();
			
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else 
			{	
				$therapist_id = (int) $this->value('id');	
				
				$queryUpdate = "UPDATE user SET status = 3 WHERE user_id = ". $therapist_id;					
				$this->execute_query($queryUpdate);	
				
				$sqlUpdate = "DELETE FROM therapist_patient WHERE therapist_id = '".$therapist_id."'";			
				$result = $this->execute_query($sqlUpdate);		
				
				/*$strJS =  '<script language="JavaScript">
						     opener.window.document.location.reload();
						     window.close();</script>';*/
				$strJS =  '<script language="JavaScript">
						     opener.window.document.location.href = "index.php?action=userListing";
						     window.close();</script>';
				echo $strJS;							
					
			}		
			
		}		

		
		/**
		 * Remove the user (Therapist or Account admin) of a clinic.
		 *
		 * @access public
		 */
		function removeUser()
		{

						
			$userInfo = $this->userInfo();
			
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else 
			{	
				$therapist_id = (int) $this->value('id');	
				
				$queryUpdate = "UPDATE user SET status = 3 WHERE user_id = ". $therapist_id;					
				$this->execute_query($queryUpdate);		

				$sqlUpdate = "DELETE FROM therapist_patient WHERE therapist_id = '".$therapist_id."'";			
				$result = $this->execute_query($sqlUpdate);				
				
				header("location:index.php?action=userListing");
						
			}	
			
				
			
		}		
			
		/**
		 * Populating clinic address as address of therapist by Ajax
		 * 
		 * @access public
		 */
		function populateAddress()
		{
			$userInfo = $this->userInfo();			
			
			$userId = $userInfo['user_id'];			
			
			$replace = array();
			
			//Get the clinic address for this user to which this user is associated with
			
			$clinicId = $this->clinicInfo('clinic_id');	 
			
			$query = "SELECT clinic.* FROM clinic WHERE clinic_id = '".$clinicId."'";			
			
			$result = $this->execute_query($query);	
					
			if ($this->num_rows($result)!=0)
			{
				$row = $this->fetch_array($result);	
				
				$replace['address']= $row['address'];
				$replace['address2']= $row['address2'];
				$replace['city']= $row['city'];
				$replace['zip']= $row['zip'];
				$replace['phone1']= $row['phone'];
				$replace['fax']= $row['fax'];
				$selectedState = $row['state'];
			    $selectedCountry = $row['country'];  
				$stateArray = array("" => "Choose State...");
                
                $stateArray = array_merge($stateArray,$this->config['state']);
                if($_REQUEST['clinic_country']=='')
                $selectedCountry=$row['country'];
                else
                $selectedCountry=$_REQUEST['clinic_country'];
                
                $replace['stateOptions'] =     $this->build_select_option($stateArray,$selectedState);   
                $countryArray = $this->config['country'];
                $replace['country']=implode("','",$countryArray); 
                $replace['patient_country_options'] = $this->build_select_option($countryArray, $selectedCountry);
            
            
            
           
            if($selectedCountry=='US') {
            $stateArray = array("" => "Choose State...");
            $stateArray = array_merge($stateArray,$this->config['state']);              
            $replace['stateOptions'] = $this->build_select_option($stateArray, $selectedState);         
            }
           
            else if($selectedCountry=='CAN') {
            $stateArray = array("" => "Choose Province...");
            $stateArray = array_merge($stateArray,$this->config['canada_state']);
            $replace['stateOptions'] = $this->build_select_option($stateArray, $selectedState);         
            } 
			}
			else 
			{
				
				$replace['address']= "";
				$replace['address2']= "";
				$replace['city']= "";
				$replace['zip']= "";
				$replace['phone1']= "";
				//$replace['phone2']= "";
				$replace['fax']= "";
				
				$selectedState = "";
			
				$stateArray = array("" => "Choose State...");
				
				$stateArray = array_merge($stateArray,$this->config['state']);
				
				$replace['stateOptions'] = 	$this->build_select_option($stateArray,$selectedState);	
			}
			
			$this->output = $this->build_template($this->get_template("populateAddress"),$replace);		
		}
		
		
		/**
		 * populate blank fields in form for user address block.
		 * 
		 * @access public
		 */
		function emptyAddress()
		{
			include_once("template/accountAdminUser/userArray.php");
			
			$replace = array();
			
			$this->formArray = $formArray;
			
			$replace = $this->fillForm($this->formArray);					
			
			$selectedState = "";
			
			$stateArray = array("" => "Choose State...");
				
			$stateArray = array_merge($stateArray,$this->config['state']);
				
			$replace['stateOptions'] = 	$this->build_select_option($stateArray,$selectedState);	
			$this->output = $this->build_template($this->get_template("emptyAddress"),$replace);		
		}
		
		/**
		 * Showing confirmation popup message box.
		 * 
		 * @access public
		 */
		function showPopupMsg()
		{
			
			$replace = array();
			$therapistId = $this->value('id');	
            $privateKey = $this->config['private_key'];
			$queryAssocPatients = "SELECT patient_id,AES_DECRYPT(UNHEX(name_first),'{$privateKey}'),AES_DECRYPT(UNHEX(name_last),'{$privateKey}'),username FROM therapist_patient as tp, user WHERE user.user_id = tp.patient_id AND tp.therapist_id = '".$therapistId."'";
			$resultAssocPatients = $this->execute_query($queryAssocPatients);		
			
			if($this->num_rows($resultAssocPatients)!= 0)
			{				
				if($this->num_rows($resultAssocPatients) <= 6){
					$replace['rightPadding'] = "padding-right:17px;";
				}
				while($row = $this->fetch_array($resultAssocPatients))
				{
									
					$row['style'] = ($c++%2)?"line1":"line2";										
					 
					$replaceTbl['patientListTblRecord'] .=  $this->build_template($this->get_template("patientListTblRecord"),$row);					
					
				}
				
				$replace['patientListTbl'] = $this->build_template($this->get_template("patientListTbl"),$replaceTbl);					;
				
			}
			else 
			{
				// no patients list, this will not be the case becoz popup will open only if atleast a single association is there
				$replace['patientListTbl'] = "&nbsp;";
			}
			
						
			
			//Tot Therapists
			$totTherapists = 0;
			$clinicId = $this->clinicInfo('clinic_id');	
			$queryTotTherapists  = " SELECT count(user.user_id) AS totTherapists FROM user 
					inner join clinic_user on clinic_user.user_id = user.user_id and user.status = '1'  and clinic_user.clinic_id = '$clinicId' 
					inner join clinic on clinic.clinic_id = clinic_user.clinic_id and clinic.status = '1' 
					where user.therapist_access = '1' AND user.usertype_id = '2' ";
			//$queryTotTherapists = "SELECT count(user.user_id) as totTherapists FROM clinic_user, user WHERE clinic_user.clinic_id ='$clinicId' AND clinic_user.user_id = user.user_id AND user.expiry_date IS NOT NULL AND user.therapist_access = 1  AND clinic.status = 1 AND user.status = 1";
			$resultTotTherapists = $this->execute_query($queryTotTherapists);
			
			if($this->num_rows($resultTotTherapists)!= 0)
			{				
				$row = $this->fetch_array($resultTotTherapists);
				$totTherapists = $row['totTherapists']; 
				$totTherapists -= 1;
			}
			else 
			{
				$totTherapists = 0;
			}
			//totTherapists End
            if( $this->get_field($clinicId,'clinic','status') != 1 ){
                $totTherapists = 0;    
            }
			
			$replace['totTherapists'] = $totTherapists;				
			
			// First Name Last Name of this Therapist which is to be removed
            $privateKey = $this->config['private_key'];
			$queryFullName = "SELECT AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first, AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last FROM user WHERE user_id ='".$therapistId."'";
			$resultFullName = $this->execute_query($queryFullName);
			$row = $this->fetch_array($resultFullName);
			
			$replace['name_first'] = $row['name_first'];
			$replace['name_last'] = $row['name_last'];			
			
			
			$this->output = $this->build_template($this->get_template("thpstAccessRemovalPopup"),$replace);
			
		}
		
		/**
		 * Showing confirmation popup message for creating a user
		 * 
		 * @access public
		 */
		function popupConfirm()
		{
			$formType = $this->value('formType');
			$replace['formType'] = $formType;
			
			//Tot Therapists
			$totTherapists = 0;
            if( $this->value('clinic_id') != '' ){
                $clinicId = $this->value('clinic_id');
            }
            else{
			    $clinicId = $this->clinicInfo('clinic_id');	
            }
			
			$queryTotTherapists = " SELECT count(user.user_id) AS totTherapists FROM user 
                    inner join clinic_user on clinic_user.user_id = user.user_id and ( user.status = '1') 
                    inner join clinic on clinic.clinic_id = clinic_user.clinic_id and ( clinic.status = '1' )
                    where clinic_user.clinic_id = '$clinicId'  and user.therapist_access = '1' AND user.usertype_id = '2' ";

			$resultTotTherapists = $this->execute_query($queryTotTherapists);
			
			if($this->num_rows($resultTotTherapists)!= 0)
			{				
				$row = $this->fetch_array($resultTotTherapists);
				$totTherapists = $row['totTherapists']; 
			}
			else 
			{
				$totTherapists = 0;
			}
            
            if( $this->get_field($clinicId,'clinic','status') == 1 ){
                $totTherapists += 1;    
            }
            else{
                $totTherapists = 0;
            }
            
			//totTherapists End
			
			$replace['totTherapists'] = $totTherapists;
			$this->output = $this->build_template($this->get_template("popupConfirm"),$replace);
			
		}
		
		
		/**
		 * Showing confirmation popup message if you removes the user.
		 * 
		 * @access public
		 */
		function popupConfirmRemove()
		{
			$replace = array();
			$therapistId = $this->value('id');
			
			$queryUser = "SELECt * FROM user WHERE user_id = '".$therapistId."'";
			$resultUser = $this->execute_query($queryUser);
			$rowUser = $this->fetch_array($resultUser);
			$hasAdminAccess = $rowUser['admin_access'];
			$hasTherapistAccess = $rowUser['therapist_access'];
				
			$privateKey = $this->config['private_key'];
			$queryAssocPatients = "SELECT patient_id,AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last,username FROM therapist_patient as tp, user WHERE user.user_id = tp.patient_id AND tp.therapist_id = '".$therapistId."'";
			$resultAssocPatients = $this->execute_query($queryAssocPatients);		
			
			if($this->num_rows($resultAssocPatients)!= 0)
			{				
				if($this->num_rows($resultAssocPatients) <= 6){
					$replace['rightPadding'] = "padding-right:17px;";
				}
				while($row = $this->fetch_array($resultAssocPatients))
				{
									
					$row['style'] = ($c++%2)?"line1":"line2";										
					 
					$replaceTbl['patientListTblRecord'] .=  $this->build_template($this->get_template("patientListTblRecord"),$row);					
					
				}
				
				$replace['patientListTbl'] = $this->build_template($this->get_template("patientListTbl"),$replaceTbl);					;
				
			}
			else 
			{
				// no patients list
				$replace['patientListTbl'] = "&nbsp;";
			}
			
			
			//Tot Therapists
			$totTherapists = 0;
			$clinicId = $this->clinicInfo('clinic_id');	
			$queryTotTherapists  = " SELECT count(user.user_id) AS totTherapists FROM user 
                    inner join clinic_user on clinic_user.user_id = user.user_id and ( user.status = '1') 
                    inner join clinic on clinic.clinic_id = clinic_user.clinic_id and ( clinic.status = '1' )
                    where clinic_user.clinic_id = '$clinicId'  and user.therapist_access = '1' AND user.usertype_id = '2' ";
			
			$resultTotTherapists = $this->execute_query($queryTotTherapists);
			
			if($this->num_rows($resultTotTherapists)!= 0)
			{				
				$row = $this->fetch_array($resultTotTherapists);
				$totTherapists = $row['totTherapists']; 
				
				if ($hasAdminAccess == 1 AND ($hasTherapistAccess == NULL OR $hasTherapistAccess == 0)) 
				{
					//do nothing					
					//$totTherapists = $totTherapists;			
				}
				else 
				{
					$totTherapists -= 1;			
				}
				
				
			}
			else 
			{
				$totTherapists = 0;
			}
            
            if( $this->get_field($clinicId,'clinic','status') != 1 ){
                $totTherapists = 0;    
            }
			//totTherapists End
			
			$replace['totTherapists'] = $totTherapists;	
			
			
			$privateKey = $this->config['private_key'];
			// First Name Last Name of this Therapist which is to be removed
			$queryFullName = "SELECT AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last FROM user WHERE user_id ='".$therapistId."'";
			$resultFullName = $this->execute_query($queryFullName);
			$row = $this->fetch_array($resultFullName);
			
			$replace['name_first'] = $row['name_first'];
			$replace['name_last'] = $row['name_last'];
			
			
			$replace['id'] = $therapistId;
			if ($hasAdminAccess == 1 AND ($hasTherapistAccess == NULL OR $hasTherapistAccess == 0)) 
			{
				$this->output = $this->build_template($this->get_template("popupConfirmRemoveAdmin"),$replace);
			}
			else 
			{
				$this->output = $this->build_template($this->get_template("popupConfirmRemove"),$replace);	
			}
			
			
			
		}
		
		/**
		 * Preserve query string parameters when action changed to sort list while using pagination.
		 *
		 * @param array $replace
		 * @param string $action
		 * @param string $queryStr
		 * @access public
		 */
		function setSortFields(&$replace,$action,$queryStr)
		{
			include_once("template/accountAdminUser/userArray.php");
			
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
		 * Creating check boxes dinamically.
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
		 * Creating Radio buttons dynamically.
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
		 * validating form fields of page.
		 *
		 * @param string $formType
		 * @param boolean $uniqueId
		 */
		function validateFormUser($formType, $uniqueId = false)
		{
									
			$objValidationSet = new ValidationSet();					
			
			
			// validating first name		
			$objValidationSet->addValidator(new  StringMinLengthValidator('name_first', 1, "First Name cannot be empty",$this->value('name_first')));		
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('name_first',null,"Please enter valid characters in first name",$this->value('name_first')));
			
			// validating last name
			$objValidationSet->addValidator(new  StringMinLengthValidator('name_last', 1, "Last Name cannot be empty",$this->value('name_last')));		
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('name_last',null,"Please enter valid characters in last name",$this->value('name_last')));

			// validating email (username)
			$objValidationSet->addValidator(new  StringMinLengthValidator('username', 1, "Email cannot be empty",$this->value('username')));					
			$objValidationSet->addValidator(new EmailValidator('username',"Invalid email address",$this->value('username')));
			
			
            $objValidationSet->addValidator(new  StringMinLengthValidator('new_password', 6, "Password cannot be empty and should be of 6 characters in length",$this->value('new_password')));
            
            //validation practitioner_type
            $objValidationSet->addValidator(new  StringMinLengthValidator('practitioner_type', 1, "Please choose Provider type",$this->value('practitioner_type')));
            
            
			if ($formType === 'Add' )
			{
                $arrFieldNames = array("username","confirmUsername");
                $arrFieldValues = array($_POST["username"],$_POST["confirmUsername"]);
                $objValidationSet->addValidator(new  IdenticalValuesValidator($arrFieldNames, "Email and  confirm email address does not match",$arrFieldValues));
			}	
			
			$arrFieldNames = array("new_password","new_password2");
			$arrFieldValues = array($_POST["new_password"],$_POST["new_password2"]);
			$objValidationSet->addValidator(new  IdenticalValuesValidator($arrFieldNames, "New password and  confirm passsword does not match",$arrFieldValues));					

			$skipAdminAccessCheck = false;
			
			if ($formType === 'Add')
			{
				$skipAdminAccessCheck = false;
			}
			else if ($formType === 'Edit')
			{
				$userInfo = $this->userInfo();			
			
				$userId = $userInfo['user_id'];
				
				if ($userId == $uniqueId)
				{
					$skipAdminAccessCheck = true;
				}
				else 
				{
					$skipAdminAccessCheck = false;
				}
			}			
					
			if (isset($_POST['therapistAccess']) == false)
			{
				if ($skipAdminAccessCheck === false)
				{
					if (isset($_POST['adminAccess']) == false)
					{
						$objValidationErr = new ValidationError('AccountPrivileges',"Please specify the account privileges for the user");
						$objValidationSet->addValidationError($objValidationErr);	
					}
				}	
				
			}			
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('address',null,"Please enter valid characters in address",$this->value('address')));
			
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('address2',null,"Please enter valid characters in address2",$this->value('address2')));
			
			//$objValidationSet->addValidator(new  StringMinLengthValidator('city', 1, "City cannot be empty",$this->value('city')));		
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('city',null,"Please enter valid characters in city",$this->value('city')));
			
			/*if ($this->value('zip') != '')
			{
				$objValidationSet->addValidator(new   AlphanumericOnlyValidator('zip', array(' '), "Zip code should be of alphanumeric characters only",$this->value('zip')));					
				$objValidationSet->addValidator(new  StringMinMaxLengthValidator('zip', 5,7, "Zip code should be 5 to 7 alphanumeric characters only",$this->value('zip')));		
			}*/	
			 /*if($this->value('clinic_country')=='CAN'){
                $objValidationSet->addValidator(new  AlphanumericOnlyValidator('clinic_zip', array(' '), "Zip code should be of alphanumeric characters only",$this->value('zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('clinic_zip', 6,7, "Zip code should be  6 to 7 alphanumeric characters only",$this->value('zip')));        
               }else{
                $objValidationSet->addValidator(new  NumericOnlyValidator('clinic_zip', null, "Zip code should be of numeric characters only",$this->value('zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('clinic_zip', 5,5, "Zip code should be  5 numeric characters only",$this->value('zip')));
            }*/
						
			//$objValidationSet->addValidator(new  ZipValidator('zip',"Invalid zip code",$this->value('zip')));				
			 $objValidationSet->addValidator(new  StringMinMaxLengthValidator('clinic_zip', 5,7, "Zip code should be  5 to 7 characters only",$this->value('zip')));
			
			if ($uniqueId === false)
			{	
				$queryEmail = "SELECT user_id FROM user WHERE username = '".$_POST['username']."' AND status <> 3";
				$result = $this->execute_query($queryEmail);
				
				//if record found that means email not unique else it is unique
				if ($this->num_rows($result) != 0)
				{
					$objValidationErr = new ValidationError('username',"Email address : exists in the system. Please choose another.");
					$objValidationSet->addValidationError($objValidationErr);
				}							
				
			}
			else
			{
				$queryEmail = "SELECT user_id FROM user WHERE username = '".$_POST['username']."' AND status <> 3 AND user_id <> ".$uniqueId;
				$result = $this->execute_query($queryEmail);
				
				//if record found that means email not unique else it is unique
				if ($this->num_rows($result) != 0)
				{
					$objValidationErr = new ValidationError('username',"Email address : exists in the system. Please choose another.");
					$objValidationSet->addValidationError($objValidationErr);
				}			
				
			}	
			
			$objValidationSet->validate();				
			

			if ($objValidationSet->hasErrors())
			{
				
				$arrayFields = array("name_first","name_last","username","new_password","AccountPrivileges","address","address2","city","state","zip","practitioner_type");						
				
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
		
		function checkValidation()
		{									
			
			$objValidationSet = new ValidationSet();					
			
			$objValidationSet->addValidator(new  StringMinLengthValidator('name_first', 1, "First Name cannot be empty",$this->value('name_first')));		
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('name_first',null,"Please enter valid characters in first name",$this->value('name_first')));
			
			$objValidationSet->addValidator(new  StringMinLengthValidator('name_last', 1, "Last Name cannot be empty",$this->value('name_last')));		
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('name_last',null,"Please enter valid characters in last name",$this->value('name_last')));

			
			$objValidationSet->addValidator(new  StringMinLengthValidator('username', 1, "Email cannot be empty",$this->value('username')));					
			$objValidationSet->addValidator(new EmailValidator('username',"Invalid email address",$this->value('username')));
		if ($this->value('practitioner_type') == '')
				{
					
					$objValidationErr = new ValidationError('practitioner_type',"Please choose Provider type");
					$objValidationSet->addValidationError($objValidationErr);
				}
						
			//$arrFieldNames = array("username","confirmUsername");
			//$arrFieldValues = array($_POST["username"],$_POST["confirmUsername"]);	
			//$objValidationSet->addValidator(new  IdenticalValuesValidator($arrFieldNames, "Email and  confirm email address does not match",$arrFieldValues));					
			
			$uniqueId = $this->value('uniqueId');
			
			if ($uniqueId === 'NA' || $this->value('new_password') != "")
			{
				
				$objValidationSet->addValidator(new  StringMinLengthValidator('new_password', 6, "Password cannot be empty and should be of 6 characters in length",$this->value('new_password')));					
				
			}	
			
			$arrFieldNames = array("new_password","new_password2");
			$arrFieldValues = array($_POST["new_password"],$_POST["new_password2"]);
			$objValidationSet->addValidator(new  IdenticalValuesValidator($arrFieldNames, "New password and  confirm passsword does not match",$arrFieldValues));					
						
			
			if (isset($_POST['therapistAccess']) == false)
			{
				if (isset($_POST['adminAccess']) == false)
				{
					$objValidationErr = new ValidationError('AccountPrivileges',"Please specify the account privileges for the user");
					$objValidationSet->addValidationError($objValidationErr);	
				}
				/*else 
				{
					$objValidationErr = new ValidationError('AccountPrivileges',"");
					$objValidationSet->addValidationError($objValidationErr);
				}*/
				
			}
			/*else 
			{
				$objValidationErr = new ValidationError('AccountPrivileges',"");
				$objValidationSet->addValidationError($objValidationErr);
			}*/				
			
			//Jan 22, 2008 Updation, address state city zip fax and phone will not be mandatory
			//$objValidationSet->addValidator(new  StringMinLengthValidator('address', 1, "Address cannot be empty",$this->value('address')));					
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('address',null,"Please enter valid characters in address",$this->value('address')));			
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('address2',null,"Please enter valid characters in address2",$this->value('address2')));
			//$objValidationSet->addValidator(new  StringMinLengthValidator('city', 1, "City cannot be empty",$this->value('city')));		
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('city',null,"Please enter valid characters in city",$this->value('city')));
			
			/*
			if ($this->value('state') == '')
			{
				$objValidationErr = new ValidationError('state',"Please select state");
				$objValidationSet->addValidationError($objValidationErr);
			}*/
			/*else 
			{
				$objValidationErr = new ValidationError('state',"");
				$objValidationSet->addValidationError($objValidationErr);
			}*/			
			
			
			/*if ($this->value('zip') != '')
			{
				$objValidationSet->addValidator(new   AlphanumericOnlyValidator('zip', array(' '), "Zip code should be of alphanumeric characters only",$this->value('zip')));					
				$objValidationSet->addValidator(new  StringMinMaxLengthValidator('zip', 5,7, "Zip code should be 5 to 7 alphanumeric characters only",$this->value('zip')));		
			}*/	
			 /*if($this->value('clinic_country')=='CAN'){
                $objValidationSet->addValidator(new  AlphanumericOnlyValidator('clinic_zip', array(' '), "Zip code should be of alphanumeric characters only",$this->value('zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('clinic_zip', 6,7, "Zip code should be  6 to 7 alphanumeric characters only",$this->value('zip')));        
               }else{
                $objValidationSet->addValidator(new  NumericOnlyValidator('clinic_zip', null, "Zip code should be of numeric characters only",$this->value('zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('clinic_zip', 5,5, "Zip code should be  5 numeric characters only",$this->value('zip')));
            }*/
						
			//$objValidationSet->addValidator(new  ZipValidator('zip',"Invalid zip code",$this->value('zip')));			
			$objValidationSet->addValidator(new  StringMinMaxLengthValidator('clinic_zip', 5,7, "Zip code should be  5 to 7 characters only",$this->value('zip')));
			
			if ($uniqueId === 'NA')
			{	
				$queryEmail = "SELECT user_id FROM user WHERE username = '".$_POST['username']."' AND status <> 3";
				$result = $this->execute_query($queryEmail);
				
				//if record found that means email not unique else it is unique
				if ($this->num_rows($result) != 0)
				{
					$objValidationErr = new ValidationError('username',"Email address : exists in the system. Please choose another.");
					$objValidationSet->addValidationError($objValidationErr);
				}							
				
			}
			else
			{
				$queryEmail = "SELECT user_id FROM user WHERE username = '".$_POST['username']."' AND status <> 3 AND user_id <> ".$uniqueId;
				$result = $this->execute_query($queryEmail);
				
				//if record found that means email not unique else it is unique
				if ($this->num_rows($result) != 0)
				{
					$objValidationErr = new ValidationError('username',"Email address : exists in the system. Please choose another.");
					$objValidationSet->addValidationError($objValidationErr);
				}			
				
			}	
			
			$objValidationSet->validate();				
			

			if ($objValidationSet->hasErrors())
			{
				
				$arrayFields = array("name_first","name_last","username","new_password","AccountPrivileges","address","address2","city","state","zip","practitioner_type",);						
				
				for($i=0;$i<count($arrayFields);++$i)
				{
					$errorMsg = $objValidationSet->getErrorByFieldName($arrayFields[$i]);
					//echo "msg : $errorMsg<br>";
					
					if ($errorMsg != "")
					{
						echo $errorMsg;
						break;
					}
				}			
						
			}	
			else 
			{
				echo "";	
			}
			
		}
		
		
		/* Extra Functions */
		
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
		
		
		
		
		/* Extra Functions End */
		function switch_back_admin_access(){
			if(isset($_SESSION['tmp_username']) && isset($_SESSION['tmp_password']) ){
				$_SESSION['username'] = $_SESSION['tmp_username'];
				$_SESSION['password'] = $_SESSION['tmp_password'];
				session_unregister('tmp_username');
				session_unregister('tmp_password');
				header("location:index.php");
				exit();
			}
		}
		
		
		
		/**
		 * Function to populate side bar of the html page.
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
         * Function to Account admin mass patient list uplaod..
         * @access public
         */
        function uploadPatientList(){
            ini_set('max_execution_time', -1);
			$replace = array();
            $error = array();
			$color = '#FF0000';
			$user_id=$this->userInfo('user_id');
         $userInfo = $this->userInfo();
          /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
            $cli_type=$this->getClinicDetails($this->userInfo('user_id'));
            //if($cli_type['clinic_type']==4){
                $replace['shopshow']=1;
            /*}else{
                $replace['shopshow']='0';
            }*/
			//code to show results link if uploading is completed
			$query="SELECT * FROM mass_uploaded_files WHERE status=3 AND user_id=".$user_id." ORDER BY id DESC LIMIT 0,1";
			$res=@mysql_query($query);
			if(@mysql_num_rows($res)>0)
			{	$row=mysql_fetch_object($res);
				$str="<tr><td style='padding-left:22px;padding-top:20px;'><a href='javascript:void(0);' onclick='showHide();'>Results - ".$this->formatDate($row->timestamp)."</a><br /><div style='display:none;' id='result'>".stripslashes($row->message)."</div></td></tr>";
				$replace['results']=$str;
			}

			if($this->value('submit_patientlist')=="UPLOAD")
			{	 
				  $size = $_FILES['patient_list']['size'];
				  $files_size_limit = 4*1024*1024;
				  $newfilename=date("m-d-y")."-".$_FILES['patient_list']['name'];
				  $tmpname=$_FILES['patient_list']['tmp_name'];
				  $ext = explode(".",$_FILES['patient_list']['name']);
				  $last=count($ext)-1;
				  if($ext[$last]!="csv")
				  {	  $error[]="Uploaded file is not in correct format (Only .csv allowed)";
				  }
				  elseif($files_size_limit<$size)
				  {		$error[]="File size is large.Maximum filesize allowed is :".($files_size_limit/(1024*1024))." MB";
				  }
				  else
				  {	//vaild condition for uploading and extracting the xls file to insert records.
					
					$target_path=$_SERVER['DOCUMENT_ROOT'].'/patient_lists_uploaded/'.$newfilename;
                    if(move_uploaded_file($tmpname,$target_path))
                    { 
						$error[]="Your file has been uploaded and queued for processing. Once your health records are created, you will receive an email asking you to log back in and check this page for the results. Depending on the number of records you are creating and other server activity, this process could take from a few minutes to a few hours.<br />";
						$color = '#54A113';
					//code to insert the details into mass_uploaded_files table for cron
								$tableArray['user_id'] = $user_id;
								$tableArray['file_name'] = $newfilename;
								$tableArray['timestamp'] = date("Y-m-d H:i:s");
								$tableArray['status'] = "1";
								$tableArray['message'] = "";
								$this->insert($this->config['table']['mass_uploaded_files'], $tableArray);


						/*$i = 0;
						$handle = fopen($target_path, "rb");
						$str="";
						$errorCount=0;
						$errorFormat=0;
						while (($data = fgetcsv($handle, 100000, ",")) !== FALSE) {
							$status=0;
							if($i==0 && (strtolower($data[0])!="first name" || strtolower($data[1])!="last name" || strtolower($data[2])!="email address" || strtolower($data[3])!="patient/client status" || strtolower($data[4])!="subscribe/unsubscribe"))
							{$errorFormat=1;
							 $str.="<li><strong style='color:#0069A0;'>Heading Record </strong>- One of the column headings does not match the required headings. See example template.</li>";
							 $errorCount++;
							}
							if($errorFormat==0 && $i>0 ){
								if($data[0]=="" and $data[1]=="" and $data[2]=="" and $data[3]=="" and $data[4]=="" and $data[5]=="" and $data[6]=="" and $data[7]=="" and $data[8]=="" and $data[9]=="" and $data[10]=="" and $data[11]=="" and $data[12]=="") 
									continue;
							}
							if($errorFormat==0 && $i>0 )
							{	
									
                                    $status=0;
									$mass_message_access=0;
                                    if(strtolower($data[3])=="current")
										$status=1;
									elseif(strtolower($data[3])=="discharged")
										$status=2;
									if(strtolower($data[4])=="subscribe")
										$mass_message_access=1;
									elseif(strtolower($data[4])=="unsubscribe")
										$mass_message_access=2;
                                    
									if($status==0){
                                     $str.="<li><strong style='color:#0069A0;'>Record no. ".($i)."</strong>- The status must be either current or discharged.</li>";
									 $errorCount++;   
                                    }
									elseif($mass_message_access==0){
                                     $str.="<li><strong style='color:#0069A0;'>Record no. ".($i)."</strong>- The status must be either subscribe or unsubscribe.</li>";
									 $errorCount++;   
                                    }elseif(empty($data[0]))
									{	$str.="<li><strong style='color:#0069A0;'>Record no. ".($i)."</strong>- The first name field is missing.</li>";
										$errorCount++;
									}
									elseif(empty($data[1]))
									{	$str.="<li><strong style='color:#0069A0;'>Record no. ".($i)."</strong>- The last name field is missing.</li>";
										$errorCount++;
									}
									elseif(empty($data[2]))
									{	$str.="<li><strong style='color:#0069A0;'>Record no. ".($i)."</strong>- The email field is missing.</li>";
										$errorCount++;
									}
									elseif(empty($data[3]))
									{	$str.="<li><strong style='color:#0069A0;'>Record no. ".($i)."</strong>- The status field is missing.</li>";
										$errorCount++;
									}
									elseif(empty($data[4]))
									{	$str.="<li><strong style='color:#0069A0;'>Record no. ".($i)."</strong>- The subscribe/unsubscribe field is missing.</li>";
										$errorCount++;
									}
									elseif(!$this->validateEmail($data[2]))
									{	$str.="<li><strong style='color:#0069A0;'>Record no. ".($i)."</strong>- The email address format is incorrect.</li>";
										$errorCount++;
									}
									elseif($this->checkUserNameExist($data[2])>0)
									{	$str.="<li><strong style='color:#0069A0;'>Record no. ".($i)."</strong>- The email address already exists in the system.</li>";
										$errorCount++;
									}
									else
									{	
										include_once("template/therapist/therapistArray.php");
										$tableArray['username'] = $data[2];
										$tableArray['password'] = $this->generateCode(9);
										$tableArray['refering_physician'] = $data[12];
										$tableArray['company'] = "";
										$tableArray['name_first'] = $data[0];
										$tableArray['name_last'] = $data[1];
										$tableArray['mass_message_access'] = $mass_message_access;
										$tableArray['address'] = $data[5];
										$tableArray['address2'] = $data[6];
										$tableArray['city'] = $data[7];
										$tableArray['state'] = $data[8];
										$tableArray['zip'] = $data[9];
										$tableArray['phone1'] = $data[10];
										$tableArray['phone2'] = $data[11];
										$tableArray['status'] = $status;
										$tableArray['usertype_id'] = 1;
										$tableArray['creation_date'] = date('Y-m-d H:i:s');
										$tableArray['modified'] = date('Y-m-d H:i:s');
										//echo "<pre/>";print_r($tableArray);
										if($this->insert($this->config['table']['user'], $tableArray))
										{	$therapistArray = array(
												'therapist_id' => $this->userInfo('user_id'),
												'patient_id' => $this->insert_id(),
												'creation_date' => date('Y-m-d H:i:s'),
												'modified' => date('Y-m-d H:i:s'),
												'status' => '1'
												);
											$new_patient_id = $therapistArray['patient_id'];
											if($this->userInfo('therapist_access')==1)
											{$this->insert($this->config['table']['therapist_patient'], $therapistArray);
											}
											$clinicArray = array(
															'clinic_id' => 	$this->clinicInfo('clinic_id'),
															'user_id'	=>  $new_patient_id,
														 );
											// Insert row in clinic_user table to set relation between clinic and patient				 
											$this->insert($this->config['table']['clinic_user'], $clinicArray);
											$clinicName	=	$this->get_clinic_info($new_patient_id,"clinic_name");
											if($tableArray['status']==1){
											$patientReplace = array(
														'username' => $tableArray['username'],
														'password' => $tableArray['password'],
														'url' => $this->config['url'],
														'images_url' => $this->config['images_url'],
														'clinic_name' => $clinicName
												);
											$message = $this->build_template("mail_content/create_new_patient_mail.php",$patientReplace);
											$to = $tableArray['username'];
											$subject = "Your ".$clinicName." Health Record";
											// To send HTML mail, the Content-type header must be set
											$headers  = 'MIME-Version: 1.0' . "\n";
											$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
											$headers .= "From: " .$clinicName. "<support@txxchange.com>" . "\n";
											$returnpath = '-fsupport@txxchange.com';
											// Mail it
											//echo $message;exit;
											@mail($to, $subject, $message, $headers, $returnpath);
											}
										}

									}
							}
							$i++;
							
						}
						fclose($handle);*/
					
						
                    }
                    else
                    {   $error[]="Some failure in file uploading occurred, Try later.";
                    }
				  }
			  
			}
			
			// set template variables
            $replace['error'] = $this->show_error($error,$color);
			$replace['browser_title'] = 'Tx Xchange: Upload Patient List';
            $replace['footer'] = $this->build_template($this->get_template("footer"));
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['url'] = $this->config['images_url'];
            $replace['sidebar'] = $this->sidebar();
            $replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"));
            /*if(isset($errorCount))
			{	if($errorFormat==1)$errorCount=($i-1);
				$replace['successnum']=($i-1)-$errorCount;
				$replace['totalnum']=($i-1);
				if($errorCount>0)
				{	$replace['failnum']="There was a problem with ".$errorCount." of them:";
					$custommessage="Please update the records that contain a problem and upload a new CSV file with <u>only</u> the updated records.";
				}
				$replace['statusMessage']=$str;
				$replace['custommessage']=$custommessage;
				$replace['body'] = $this->build_template($this->get_template("uploadPatientListStatus"),$replace);
			}
			else
			{	$replace['body'] = $this->build_template($this->get_template("uploadPatientList"),$replace);
			}*/

			$replace['body'] = $this->build_template($this->get_template("uploadPatientList"),$replace);
            	$replace['get_satisfaction'] = $this->get_satisfaction();
            $this->output = $this->build_template($this->get_template("main"),$replace);  
        }
        
        /**
        * This function displays list clinics in an account.        
        */
        function txReferralSettings(){
            $clinicId = $this->clinicInfo("clinic_id");
            $userInfo = $this->userInfo();
            if( !is_numeric($clinicId) || $clinicId == 0 ){
                header("location:index.php?action=logout");
                exit();
            }
            $replace = array();
            if($this->value('action_submit')=="submit"){
               if( is_numeric($clinicId) ){
                    $sqlUser = "select * from referral_limit where clinic_id = '{$clinicId}'";
                    $result=$this->execute_query($sqlUser);
                }   
                if($this->num_rows($result)!= 0)
                {
                  $this->validateFormReferral(false);
                  
                  if($this->error == "")
					{ 
					   $row = $this->fetch_array($result);
                       $this->update('referral_limit',array('clinic_refferal_limit'=>$this->value('clinic_refferal_limit'),'clinic_user_limit'=>$this->value('clinic_user_limit')),'id='.$row['id']);
                    }else
                    {
                        $replace['error'] = $this->error;
                    }	
                   
                }else{
                  // add
                  $this->validateFormReferral(false);					
					if($this->error == "")
					{ 
					 $insertArr = array(
											'user_id'                => $userInfo['user_id'],
											'clinic_id'              => $clinicId,
											'clinic_refferal_limit'  =>	$this->value('clinic_refferal_limit'),
											'clinic_user_limit'      =>	$this->value('clinic_user_limit'),
											'status'                 =>  1
                                       );
												
					 $result = $this->insert('referral_limit',$insertArr);
					}else
                    {
					   $replace['error'] = $this->error;
                    }
                    
                
                }
            
            }
            
            
            
            
            
          
            // Retian page value.
            $arr = array(
                'clinic_id' => $clinicId
            );
            $msg = $this->value('msg');
            if( !empty($msg) ){
                $replace['error'] = $this->errorClinicListModule($msg);
            }
            $searchString = $this->value('search');
            if( !empty($searchString) ){
                $searchString = $this->value('search');
                $searchWhere = " and clinic_name like '%{$this->value('search')}%' ";
            }            
            //Get the clinic list
            if( is_numeric($clinicId) ){
                $sqlUser = "select * from referral_limit where clinic_id = '{$clinicId}'";
                $result=$this->execute_query($sqlUser);
            }
            if($this->num_rows($result)!= 0)
            {
             $row = $this->fetch_array($result);
              $replace['clinic_refferal_limit'] = $row['clinic_refferal_limit'];
              $replace['clinic_user_limit'] = $row['clinic_user_limit'];  
            }
            else
            {
              $replace['clinic_refferal_limit'] = 0;
              $replace['clinic_user_limit'] = 0;  
            }
            $replace['clinic_id'] = $clinicId;
           // $replace['header'] = $this->build_template($this->get_template("header"));
          //  $replace['footer'] = $this->build_template($this->get_template("footer"));    
          //  $replace['sidebar'] = $this->sidebar();
            $replace['body'] = $this->build_template($this->get_template("txReferralSetting"),$replace);
            //$replace['browser_title'] = "Tx Xchange: Clinic List";
            $replace['get_satisfaction'] = $this->get_satisfaction();
            $this->output = $this->build_template($this->get_template("main"),$replace);            
            
        }
        
        
        function validateFormReferral($uniqueId)
		{
			// creating validation object		
			$objValidationSet = new ValidationSet();					
			
			// validating username (email address)
			$objValidationSet->addValidator(new  StringMinLengthValidator('clinic_refferal_limit', 1, "Referrals sent by all patients in a month cannot be empty",$this->value('clinic_refferal_limit')));					
			$objValidationSet->addValidator(new NumericOnlyValidator('clinic_refferal_limit','null',"Please enter the numeric value",$this->value('clinic_refferal_limit')));
            if($this->value('clinic_refferal_limit')==0){
                $objValidationErr = new ValidationError('clinic_user_limit',"Referrals sent by all patients in a month cannot be zero.");
				$objValidationSet->addValidationError($objValidationErr);
            }
            
			// validating first name field
			$objValidationSet->addValidator(new  NumericOnlyValidator('clinic_user_limit','null', "Please enter Numeric value in referrals sent by any one patient in a month ",$this->value('clinic_user_limit')));		
			
            if ($this->value('clinic_refferal_limit') !=0  and $this->value('clinic_user_limit') !=0){
               if($this->value('clinic_refferal_limit') < $this->value('clinic_user_limit')){
                	$objValidationErr = new ValidationError('clinic_user_limit',"Referrals sent by any one patient in a month can not be greater than referral sent by all patients in a month.");
					$objValidationSet->addValidationError($objValidationErr);
			     } 
                
                
            }
		$objValidationSet->validate();		

			$formType='Add';
			if ($objValidationSet->hasErrors())
			{
				if($formType == "Add" )
				{
					//$arrayFields = array("username","new_password","question_id","answer","name_first","name_last","address","address2","city","zip","usertype_id","clinic_id");
					$arrayFields = array("clinic_refferal_limit","clinic_user_limit");
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
        * This function displays list clinics in an account.        
        */
        function txReferralReports(){
            $month= date('m');
            $year=date('Y');
            $clinicId = $this->clinicInfo("clinic_id");
            if( !is_numeric($clinicId) || $clinicId == 0 ){
                header("location:index.php?action=logout");
                exit();
            }
            
            $replace = array();
            /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
        $userInfo = $this->userInfo();
            $cli_type=$this->getClinicDetails($this->userInfo('user_id'));
           // if($cli_type['clinic_type']==4){
                $replace['shopshow']=1;
            /*}else{
                $replace['shopshow']='0';
            }*/
            // Retian page value.
            $arr = array(
                'clinic_id' => $clinicId
            );
            $msg = $this->value('msg');
            if( !empty($msg) ){
                $replace['error'] = $this->errorClinicListModule($msg);
            }
            $this->set_session_page($arr);
            //Get  acccount id
            if($this->value('sort') != ""){
                if($this->value('order') == 'desc' ){
                    $orderby = " order by {$this->value('sort')} desc ";
                }
                else{
                    $orderby = " order by {$this->value('sort')} ";
                }
            }
            else{
                $orderby = " order by sent_date ";
            }
            
            //Get the clinic list
            if( is_numeric($clinicId) ){
                if($this->value('period')!=''){
                    $time=explode('~~',$this->value('period'));
                    $month1= $time[0];
                    $year1=$time[1];  
                }else{
                    $month1= date('m');
                    $year1=date('Y');    
                }
                
                
                $sqlUser = "select user.name_first as p_name_first,user.name_last as p_name_last,
                                   patients_refferals.name_first as r_name_first,patients_refferals.name_last as r_name_last,
                                   sent_date,recipient_email
                              from patients_refferals,user 
                              where patients_refferals.user_id=user.user_id 
                                    and patients_refferals.status = 1 
                                    and clinic_id = '{$clinicId}'
                                    and MONTH(sent_date)={$month1} 
                                    and YEAR(sent_date)={$year1}
                                    {$orderby}";
                $sqlquery = "select count(1) as total
                              from patients_refferals,user 
                              where patients_refferals.user_id=user.user_id 
                                    and patients_refferals.status = 1 
                                    and clinic_id = '{$clinicId}'
                                    and MONTH(sent_date)={$month1} 
                                    and YEAR(sent_date)={$year1}";
            }
            $link = $this->pagination($rows = 0,$sqlUser,'txReferralReports',$searchString,'','',20);                                          
            $replace['link'] = $link['nav'];
            $result = $link['result'];     
            
            if($this->num_rows($result)!= 0)
            {
                $replace['reportTblHead'] = $this->build_template($this->get_template("reportTblHead"),$replace);
                while($row = $this->fetch_array($result))
                {
                    $row['style'] = ($c++%2)?"line1":"line2";
                    $row['p_name_first'] = $this->decrypt_data($row['p_name_first']).' '.$this->decrypt_data($row['p_name_last']);
                    $row['r_name_first'] = $row['r_name_first'].' '.$row['r_name_last'];
                    $row['sent_date'] = $this->formatDate($row['sent_date']);
                    $replace['reportTblRecord'] .= $this->build_template($this->get_template("reportTblRecord"),$row);
                }
            }else{
            $row['p_name_first'] = 'No Records Found';
			$row['r_name_first'] = '&nbsp;';
			$row['recipient_email'] = '&nbsp;';
			$row['sent_date'] = "&nbsp;";
             $replace['reportTblRecord'] .= $this->build_template($this->get_template("reportTblRecord"),$row);   
            }
                               
            
            $reportListHead = array(
                'p_name_first' => 'Name of Referring Patient',
                'r_name_first' => 'Name of Person Referred',
                'recipient_email' => 'Email Address of Person Referred',
                'sent_date' => 'Date Referral Sent',
            );
            
            $mon=$month;
            $yer=$year;
            $monthname=array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
            for($i=0;$i<12;$i++){
              $select='';
              if($this->value('period')!=''){
                    $time=explode('~~',$this->value('period'));
                    $month1= $time[0];
                    $year1=$time[1];  
               if($month1==$mon and $year1==$yer)
               $select='selected='."selected";
               }  
            $monview=$mon;
               if(strlen($mon)==1)
                 $monview='0'.$mon;
                 else{
                    $monview=$mon;
                 }
             $period .= "<option value='".$mon."~~".$yer."'".$select.">".$monthname[$monview].','.$yer;
             $mon=$mon-1;
             if($mon==0)
                {
                    $mon=12;
                    $yer=$yer-1;
                }
             
             
             }
            $res=$this->execute_query($sqlquery);
            $totalno=@mysql_fetch_array($res); 
            $replace['totalReferrals']=$totalno['total'];
            $replace['downloadreport']='index.php?action=txReferralReportExcels&period='.$this->value('period');
            $replace['period']=$period;
            $reportperiod['period']=$this->value('period');
            $replace['reportTblHead'] = $this->build_template($this->get_template("reportTblHead"),$this->table_heading($reportListHead,"p_name_first",$reportperiod));
            $replace['clinic_id'] = $clinicId;
           // $url_array = $this->tab_url();
            $replace['navigationTab'] = $this->build_template($this->get_template("navigationTab"));
            
            $replace['location'] = $url_array['location'];
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['footer'] = $this->build_template($this->get_template("footer"));    
            $replace['sidebar'] = $this->sidebar();
            $replace['body'] = $this->build_template($this->get_template("reportTemplate"),$replace);
            $replace['browser_title'] = "Tx Xchange: Referral Report";
            $replace['get_satisfaction'] = $this->get_satisfaction();
            $this->output = $this->build_template($this->get_template("main"),$replace);            
                        
            
        }
        /**
        * This function validate the E-Helth Service form.        
        */
                function validateFormHealthservice($uniqueId)
		{
			// creating validation object		
			$objValidationSet = new ValidationSet();					
			if(($this->value('subs_title')=='') and ($this->value('subs_price')=='') and ($this->value('subs_description')=='' )){
             $objValidationErr = new ValidationError('subs_title',"Please enter information in the service fields so your patients know what they are signing up for");
			 $objValidationSet->addValidationError($objValidationErr);   
            }
			// validating username (email address)
			$objValidationSet->addValidator(new  StringMinLengthValidator('subs_title', 1, "Please enter your E-Health Service Name",$this->value('subs_title')));
            $objValidationSet->addValidator(new  StringMaxLengthValidator('subs_title', 41, "Please Enter Maximum 40 character in E-Health Service Name",html_entity_decode($this->value('subs_title'), ENT_QUOTES, "UTF-8")));
            $objValidationSet->addValidator(new StringMinLengthValidator('subs_price',1,"Please enter the price you will charge for this service on a monthly basis",$this->value('subs_price')));
            //$objValidationSet->addValidator(new  StringMaxLengthValidator('subs_price', 10, "Please Enter Maximum 7 character in Price",$this->value('subs_price')));
            if($this->value('subs_price') > 999999){
             $objValidationErr = new ValidationError('subs_price',"Please Enter Maximum 7 character in Price");
				$objValidationSet->addValidationError($objValidationErr);   
            }
            if(preg_match('#^\d+(\.(\d+))?$#', $this->value('subs_price'))=='0' and $this->value('subs_price')!=''){
             $objValidationErr = new ValidationError('subs_price',"Please enter Numeric value in  price like 30.00");
				$objValidationSet->addValidationError($objValidationErr);   
            }
            //$objValidationSet->addValidator(new NumericOnlyValidator('subs_price',array('.'),"Please enter Numeric value in  price",$this->value('subs_price')));
           if($this->value('subs_price')=='0'){
                $objValidationErr = new ValidationError('subs_price',"Price should be greater than 0.");
				$objValidationSet->addValidationError($objValidationErr);
            }
           	// validating first name field
            
			$objValidationSet->addValidator(new  StringMinLengthValidator('subs_description', 1, "Please enter your E-Health Service Description",html_entity_decode($this->value('subs_description'), ENT_QUOTES, "UTF-8")));		
			$objValidationSet->addValidator(new  StringMaxLengthValidator('subs_description', 505, "Please enter Maximum 500 character in E-Health Service Description",html_entity_decode($this->value('subs_description'), ENT_QUOTES, "UTF-8")));
            $objValidationSet->addValidator(new  StringMaxLengthValidator('subs_feature1', 54, "Please enter Maximum 50 character in Key Features",html_entity_decode($this->value('subs_feature1'), ENT_QUOTES, "UTF-8")));
            $objValidationSet->addValidator(new  StringMaxLengthValidator('subs_feature2', 54, "Please enter Maximum 50 character in Key Features",html_entity_decode($this->value('subs_feature2'), ENT_QUOTES, "UTF-8")));
            $objValidationSet->addValidator(new  StringMaxLengthValidator('subs_feature3', 54, "Please enter Maximum 50 character in Key Features",html_entity_decode($this->value('subs_feature3'), ENT_QUOTES, "UTF-8")));
            $objValidationSet->addValidator(new  StringMaxLengthValidator('subs_feature4', 54, "Please enter Maximum 50 character in Key Features",html_entity_decode($this->value('subs_feature4'), ENT_QUOTES, "UTF-8")));
            $objValidationSet->addValidator(new  StringMaxLengthValidator('subs_feature5', 54, "Please enter Maximum 50 character in Key Features",html_entity_decode($this->value('subs_feature5'), ENT_QUOTES, "UTF-8")));
            $objValidationSet->addValidator(new  StringMaxLengthValidator('subs_feature6', 54, "Please enter Maximum 50 character in Key Features",html_entity_decode($this->value('subs_feature6'), ENT_QUOTES, "UTF-8")));
            $objValidationSet->addValidator(new  StringMaxLengthValidator('subs_feature7', 54, "Please enter Maximum 50 character in Key Features",html_entity_decode($this->value('subs_feature7'), ENT_QUOTES, "UTF-8")));
            
            
            $objValidationSet->validate();		
            $formType='Add';
			if ($objValidationSet->hasErrors())
			{
				if($formType == "Add" )
				{
					$arrayFields = array("subs_title","subs_description","subs_price","subs_feature1","subs_feature2","subs_feature3","subs_feature4","subs_feature5","subs_feature6","subs_feature7");
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
        * This function displays ON/OFF settings for add on services.        
        */
        function addonServices(){
            $from_email_address = $this->config['from_email_address'];
            $clinicId = $this->clinicInfo("clinic_id");
            $userInfo = $this->userInfo();
            if( !is_numeric($clinicId) || $clinicId == 0 ){
                header("location:index.php?action=logout");
                exit();
            }
			$replace = array();
        $userInfo = $this->userInfo();
            $cli_type=$this->getClinicDetails($this->userInfo('user_id'));
           // if($cli_type['clinic_type']==4){
                $replace['shopshow']=1;
            /*}else{
                $replace['shopshow']='0';
            }*/
			if($this->value('action_submit')=="submit"){
               if( is_numeric($clinicId) ){
                    $sqlUser = "select * from referral_limit where clinic_id = '{$clinicId}'";
                    $result=$this->execute_query($sqlUser);
                }
				if($this->value('add-on')==1)
					$referral_report='1';
				else
					$referral_report='0';
                 
                /*if($this->value('program')==1)
                    $program=1;
                    else
                    $program='0';*/     
                if($this->value('store')==1)
                    $store=1;
                else
                    $store='0';
                if($this->value('schedul')==1)
                    $schedul=1;
                else
                    $schedul='0';    
                if($this->value('healthEnable')==1)
                    $healthprogram = 1;
                else
                    $healthprogram = '0';     
                    if($store==1){
                        $sqlpr="select * from clinic where clinic_id=".$clinicId;
                        $respr=$this->execute_query($sqlpr);
                        $row=$this->fetch_object($respr);
                        if($row->address=='' || $row->city=='' || $row->state=='' || $row->zip='' || $row->phone==''){
                          $urlStr="<script>GB_showCenter('Update Clinic Information', '/index.php?action=prxoclinicerror',120,520);</script>";
                          $replace['error']=$urlStr;   
                        
                        }else{
                            $prxo=$this->checkprxostatus($this->userInfo('user_id'));
                            if($prxo=='not register')
                            {
                                $res=$this->registerProviderPrxo($this->userInfo('user_id'));
                            }
                        }
                    }
                if($this->value('add-on')==1)
            			   {
            				
            				if($this->num_rows($result)!= 0)
                            {
                              $this->validateFormReferral(false);
                              
                              if($this->error == "")
            					{ 
            					   $row = $this->fetch_array($result);
                                   $this->update('referral_limit',array('clinic_refferal_limit'=>$this->value('clinic_refferal_limit'),'clinic_user_limit'=>$this->value('clinic_user_limit')),'id='.$row['id']);
                                }else
                                {
                                    $replace['error'] = $this->error;
                                }	
                               
                            }else{
                              // add
                              $this->validateFormReferral(false);					
            					if($this->error == "")
            					{ 
            					 $insertArr = array(
            											'user_id'                => $userInfo['user_id'],
            											'clinic_id'              => $clinicId,
            											'clinic_refferal_limit'  =>	$this->value('clinic_refferal_limit'),
            											'clinic_user_limit'      =>	$this->value('clinic_user_limit'),
            											'status'                 =>  1
                                                   );
            												
            					 $result = $this->insert('referral_limit',$insertArr);
            					}else
                                {
            					   $replace['error'] = $this->error;
                                }
                                
                            
                            }
            			  }
                          
                    if($schedul==1){
                             $schedul_url = $this->value('schedulUrl');
                            // Check url is empty or not.
                            if($schedul_url==''){
                             $replace['error'] = "Please Enter schedule Web Address/URL";    
                            }
                            if( isset($schedul_url) && trim($schedul_url) != ""  ){
                                // Check valid URL.
                                $value = '|' . $schedul_url . '|';
                                $check_pattern = '|http(s)?://([\w-]+\.)+[\w-]+(/[\w- ./?%&=]*)?|';
                                $check = preg_match($check_pattern, $value, $matches, PREG_OFFSET_CAPTURE);
                                if( $check === false || ($check == 0)){
                                    $replace['error'] = "Incorrect URL format";    
                                }
                             }
                             if($replace['error']==""){
                                 $sqlSchedule="select * from scheduling where clinic_id  =   '{$clinicId}'";
                                    $query=$this->execute_query($sqlSchedule);
                                    $numrows=$this->num_rows($query);
                                    if($numrows > 0){
                                        $updateArrschedule = array(
                        											'user_id'    => $userInfo['user_id'],
                        											'schedulUrl' =>	$this->value('schedulUrl')
                        										   );
                                     $where=" clinic_id  =   '{$clinicId}'";
                                        $result=$this->update('scheduling',$updateArrschedule,$where);
                                    }else{
                                     $insertArrschedule = array(
                        											'user_id'    => $userInfo['user_id'],
                        											'clinic_id'  => $clinicId,
                        											'schedulUrl' =>	$this->value('schedulUrl'),
                        											'status'     =>  1
                                                               );
                                      $result=$this->insert('scheduling',$insertArrschedule);   
                                    }
                             }
                       
                        
                    }
                    if($healthprogram == 1){
                       $this->validateFormHealthservice(false);
                       if($this->error==""){
                       $sqlhealth="select * from clinic_subscription where subs_clinic_id =".$clinicId;
                       $queryHealth=$this->execute_query($sqlhealth);
                       $numHealthRow=$this->num_rows($queryHealth);
                       if($numHealthRow==0){
                        
                        $insertArrhealthSettings = array(
                                                'setting_clinic_id'          =>  $clinicId,
                                                'setting_user_id'          =>  $userInfo['user_id'],
                                                'setting_trial_days'        =>  0,
                        						'setting_paypal_username'  =>  '',
                        						'setting_c_date'           =>  date("Y-m-d H:i:s"),
                                                'setting_m_date'           =>  date("Y-m-d H:i:s"),
                                                'setting_m_user_id'        =>  $userInfo['user_id'],
                                                'setting_tx_commision'     =>   10,
                                                'setting_trial'            =>      1,
                                                'setting_status'           =>  1
                                                );
                        $resultSetting=$this->insert('health_settings',$insertArrhealthSettings);
                        $settingId=$this->insert_id();                       
                        $insertArrHealth = array(
                                                'subs_title'        =>  $this->value('subs_title'),
                        						'subs_description'  =>  $this->value('subs_description'),
                        						'subs_feature1'     =>	$this->value('subs_feature1'),
                        						'subs_feature2'     =>  $this->value('subs_feature2'),
                                                'subs_feature3'     =>  $this->value('subs_feature3'),
                                                'subs_feature4'     =>  $this->value('subs_feature4'),
                                                'subs_feature5'     =>  $this->value('subs_feature5'),
                                                'subs_feature6'     =>  $this->value('subs_feature6'),
                                                'subs_feature7'     =>  $this->value('subs_feature7'),
                                                'subs_price'        =>  $this->value('subs_price'),
                                                'subs_c_datetime'   =>  date("Y-m-d H:i:s"),
                                                'subs_clinic_id'    =>  $clinicId,
                                                'subs_c_user_id'    =>  $userInfo['user_id'],
                                                'subs_m_user_id'    =>  $userInfo['user_id'],
                                                'subs_m_datetime'   => date("Y-m-d H:i:s"),
                                                'subs_status'       =>  1,
                                                'setting_id'        =>  $settingId
                                                );
                        $result=$this->insert('clinic_subscription',$insertArrHealth); 
                        $SubjectLine="E-Health Service turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
                        $to = $from_email_address;
						       // To send HTML mail, the Content-type header must be set
						      $headers  = 'MIME-Version: 1.0' . "\n";
						      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						      //$headers .= "From: <support@txxchange.com>" . "\n";
						      //$returnpath = '-fsupport@txxchange.com';
                       $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
						if( $clinic_channel == 1){
						    $headers .= "From: <".$this->config['email_tx'].">" . "\n";
						    $returnpath = "-f".$this->config['email_tx'];
						    }else{
						    $headers .= "From:  <".$this->config['email_wx'].">" . "\n";
						    $returnpath = '-f'.$this->config['email_wx'];   
						    }
						      //echo $message;exit;
						      // Mail it
						      @mail($to, $SubjectLine, '', $headers, $returnpath);
                        //print_r($_REQUEST);
                        //die;
                        
                        
                       }else{
                         $valuehealth=$this->fetch_array($queryHealth);
                         $SubjectLine="";
                          if($valuehealth['subs_status']!=$healthprogram)
                           {
                               if($healthprogram==1)
                               {
                                    $SubjectLine="E-Health Service turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
                               }
                               $to = $from_email_address;
						       // To send HTML mail, the Content-type header must be set
						      $headers  = 'MIME-Version: 1.0' . "\n";
						      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						      //$headers .= "From: <support@txxchange.com>" . "\n";
						      //$returnpath = '-fsupport@txxchange.com';
						      $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
                        if( $clinic_channel == 1){
                            $headers .= "From: <".$this->config['email_tx'].">" . "\n";
                            $returnpath = "-f".$this->config['email_tx'];
                            }else{
                            $headers .= "From:  <".$this->config['email_wx'].">" . "\n";
                            $returnpath = '-f'.$this->config['email_wx'];   
                            }
						      
						      //echo $message;exit;
						      // Mail it
						      @mail($to, $SubjectLine, '', $headers, $returnpath);
                           }
                        $updateArrHealth = array(
                                                'subs_title'        =>  $this->value('subs_title'),
                        						'subs_description'  =>  $this->value('subs_description'),
                        						'subs_feature1'     =>	$this->value('subs_feature1'),
                        						'subs_feature2'     =>  $this->value('subs_feature2'),
                                                'subs_feature3'     =>  $this->value('subs_feature3'),
                                                'subs_feature4'     =>  $this->value('subs_feature4'),
                                                'subs_feature5'     =>  $this->value('subs_feature5'),
                                                'subs_feature6'     =>  $this->value('subs_feature6'),
                                                'subs_feature7'     =>  $this->value('subs_feature7'),
                                                'subs_price'        =>  $this->value('subs_price'),
                                                'subs_clinic_id'    =>  $clinicId,
                                                'subs_m_user_id'    =>  $userInfo['user_id'],
                                                'subs_m_datetime'   => date("Y-m-d H:i:s"),
                                                'subs_status'       =>  1
                                                );
                        $result=$this->update('clinic_subscription',$updateArrHealth,'subs_id='.$valuehealth['subs_id']);                       //update
                       }
                       }
                       $replace['error']=$this->error; 
                    }else{
                     $sqlhealth="select * from clinic_subscription where subs_clinic_id =".$clinicId;
                       $queryHealth=$this->execute_query($sqlhealth);
                       $numHealthRow=$this->num_rows($queryHealth);
                       if($numHealthRow!=0){
                         $valuehealth=$this->fetch_array($queryHealth);
                         $SubjectLine="";
                          
                           if($valuehealth['subs_status']!=$healthprogram)
                           {
                               if($healthprogram==0)
                               {
                                    $SubjectLine="E-Health Service turned off by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
                               }
                               $to = $from_email_address;
						       // To send HTML mail, the Content-type header must be set
						      $headers  = 'MIME-Version: 1.0' . "\n";
						      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						      //$headers .= "From: <support@txxchange.com>" . "\n";
						      //$returnpath = '-fsupport@txxchange.com';
						      $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
                        if( $clinic_channel == 1){
                            $headers .= "From: <".$this->config['email_tx'].">" . "\n";
                            $returnpath = "-f".$this->config['email_tx'];
                            }else{
                            $headers .= "From:  <".$this->config['email_wx'].">" . "\n";
                            $returnpath = '-f'.$this->config['email_wx'];   
                            }
						      
						      //echo $message;exit;
						      // Mail it
						      @mail($to, $SubjectLine, '', $headers, $returnpath);
                           }
                         $this->update('clinic_subscription',array('subs_status'=>'0','subs_m_datetime'=>date("Y-m-d H:i:s"),'subs_m_user_id'=>$userInfo['user_id']),'subs_id='.$valuehealth['subs_id']);//                                                
                       }   
                    }
                    if($replace['error']==''){
				    $sqlAddon = "select * from addon_services where clinic_id = '{$clinicId}'";
					$resultAddon=$this->execute_query($sqlAddon);
					if($this->num_rows($resultAddon)!= 0)
					   { 
					       $SubjectLine="";
                           $rowcheckaddon=$this->fetch_object($resultAddon);
                           if($rowcheckaddon->referral_report!=$referral_report)
                           {
                               if($referral_report==1)
                               {
                                    $SubjectLine="Referral Service turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
                               }
                               else
                               {
                                    $SubjectLine="Referral Service turned off by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
                               }
                               $to = $from_email_address;
						       // To send HTML mail, the Content-type header must be set
						      $headers  = 'MIME-Version: 1.0' . "\n";
						      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						      //$headers .= "From: <support@txxchange.com>" . "\n";
						      //$returnpath = '-fsupport@txxchange.com';
						      $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
                        if( $clinic_channel == 1){
                            $headers .= "From:   <".$this->config['email_tx'].">" . "\n";
                            $returnpath = "-f".$this->config['email_tx'];
                            }else{
                            $headers .= "From:   <".$this->config['email_wx'].">" . "\n";
                            $returnpath = '-f'.$this->config['email_wx'];   
                            }
						      
						      //echo $message;exit;
						      // Mail it
						      @mail($to, $SubjectLine, '', $headers, $returnpath);
                           }
                           if($rowcheckaddon->store!=$store)
                           {
                            if($store==1){
                                    $SubjectLine="Store turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
                                }
                               else
                               {
                                    $SubjectLine="Store turned off by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
                               }
                               $to = $from_email_address;
						       // To send HTML mail, the Content-type header must be set
						      $headers  = 'MIME-Version: 1.0' . "\n";
						      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						      //$headers .= "From: <support@txxchange.com>" . "\n";
						      //$returnpath = '-fsupport@txxchange.com';
                            $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
                        if( $clinic_channel == 1){
                            $headers .= "From:  <".$this->config['email_tx'].">" . "\n";
                            $returnpath = "-f".$this->config['email_tx'];
                            }else{
                            $headers .= "From: <".$this->config['email_wx'].">" . "\n";
                            $returnpath = '-f'.$this->config['email_wx'];   
                            }
						      //echo $message;exit;
						      // Mail it
						      @mail($to, $SubjectLine, '', $headers, $returnpath);
                                
                           }
                           if($rowcheckaddon->scheduling!=$schedul)
                           {
                            if($schedul==1){
                                    $SubjectLine="Scheduling turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
                                }
                               else
                               {
                                    $SubjectLine="Scheduling turned off by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
                               }
                               $to = $from_email_address;
						       // To send HTML mail, the Content-type header must be set
						      $headers  = 'MIME-Version: 1.0' . "\n";
						      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						      //$headers .= "From: <support@txxchange.com>" . "\n";
						      //$returnpath = '-fsupport@txxchange.com';
						      $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
                        if( $clinic_channel == 1){
                            $headers .= "From:  <".$this->config['email_tx'].">" . "\n";
                            $returnpath = "-f".$this->config['email_tx'];
                            }else{
                            $headers .= "From:  <".$this->config['email_wx'].">" . "\n";
                            $returnpath = '-f'.$this->config['email_wx'];   
                            }
						      
						      //echo $message;exit;
						      // Mail it
						      @mail($to, $SubjectLine, '', $headers, $returnpath);
                                
                           }
                          /* if($rowcheckaddon->program!=$program)
                           {
                                if($program==1)
                                {
                                    $SubjectLine="E-Rehab turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
                                }
                                else
                                {
                                    $SubjectLine="E-Rehab turned off by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
                                }
                               $to = $from_email_address;
						       // To send HTML mail, the Content-type header must be set
						      $headers  = 'MIME-Version: 1.0' . "\n";
						      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						      $headers .= "From: <support@txxchange.com>" . "\n";
						      $returnpath = '-fsupport@txxchange.com';
						      //echo $message;exit;
						      // Mail it
						      @mail($to, $SubjectLine, '', $headers, $returnpath);
                                 
                           }*/
                           //$this->update('addon_services',array('referral_report'=>$referral_report,'program'=>$program,'store'=>$store,'scheduling'=>$schedul),'clinic_id='.$clinicId);//
                           $this->update('addon_services',array('referral_report'=>$referral_report,'store'=>$store,'scheduling'=>$schedul),'clinic_id='.$clinicId);//
                           $error   = "Your changes have been saved";
                           $replace['error'] = $error = '<span style="color:green" ><b>'.$error.'</b></span>';
					   }
					   else
						{					$insertAddonArr = array(
											'user_id'                => $userInfo['user_id'],
											'clinic_id'              => $clinicId,
											'referral_report'        =>	$referral_report,
                                            //'program'                => $program,
                                            'store'                  => $store,
											'status'                 =>  1,
                                            'scheduling'             => $schedul
                                       );
					//send mail to jonathan
                      if($referral_report==1)
                      {
                            $SubjectLine="Referral Service turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
                            $to = $from_email_address;
						    // To send HTML mail, the Content-type header must be set
						    $headers  = 'MIME-Version: 1.0' . "\n";
						    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						    //$headers .= "From: <support@txxchange.com>" . "\n";
						    //$returnpath = '-fsupport@txxchange.com';
						    $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
                        if( $clinic_channel == 1){
                            $headers .= "From:  <".$this->config['email_tx'].">" . "\n";
                            $returnpath = "-f".$this->config['email_tx'];
                            }else{
                            $headers .= "From:  <".$this->config['email_wx'].">" . "\n";
                            $returnpath = '-f'.$this->config['email_wx'];   
                            }
						    
						      // Mail it
						      @mail($to, $SubjectLine, '', $headers, $returnpath);
                      }
                      if($store==1)
                      {
                            $SubjectLine="Store turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
                            $to = $from_email_address;
						    // To send HTML mail, the Content-type header must be set
						    $headers  = 'MIME-Version: 1.0' . "\n";
						    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						   // $headers .= "From: <support@txxchange.com>" . "\n";
						    //$returnpath = '-fsupport@txxchange.com';
						    $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
                        if( $clinic_channel == 1){
                            $headers .= "From: <".$this->config['email_tx'].">" . "\n";
                            $returnpath = "-f".$this->config['email_tx'];
                            }else{
                            $headers .= "From: <".$this->config['email_wx'].">" . "\n";
                            $returnpath = '-f'.$this->config['email_wx'];   
                            }
						    
						    // Mail it
						    @mail($to, $SubjectLine, '', $headers, $returnpath);
                       }  
                     /* if($program==1)
                       {
                            $SubjectLine="E-Rehab turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
                            $to = $from_email_address;
						    // To send HTML mail, the Content-type header must be set
						    $headers  = 'MIME-Version: 1.0' . "\n";
						    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						    $headers .= "From: <support@txxchange.com>" . "\n";
						    $returnpath = '-fsupport@txxchange.com';
						    // Mail it
						  @mail($to, $SubjectLine, '', $headers, $returnpath);
                       }*/
                      if($schedul==1)
                       {
                            $SubjectLine="Scheduling turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
                            $to = $from_email_address;
						    // To send HTML mail, the Content-type header must be set
						    $headers  = 'MIME-Version: 1.0' . "\n";
						    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						   // $headers .= "From: <support@txxchange.com>" . "\n";
						   // $returnpath = '-fsupport@txxchange.com';
						   $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
                        if( $clinic_channel == 1){
                            $headers .= "From:  <".$this->config['email_tx'].">" . "\n";
                            $returnpath = "-f".$this->config['email_tx'];
                            }else{
                            $headers .= "From:  <".$this->config['email_wx'].">" . "\n";
                            $returnpath = '-f'.$this->config['email_wx'];   
                            }
						    
						    // Mail it
						  @mail($to, $SubjectLine, '', $headers, $returnpath);
                       } 							
					 $result = $this->insert('addon_services',$insertAddonArr);
                     $error   = "Your changes have been saved";
                     $replace['error'] = $error = '<span style="color:green" ><b>'.$error.'</b></span>';
						}
                 }
              }
            
            
            
            
            
          
            // Retian page value.
            $arr = array(
                'clinic_id' => $clinicId
            );
            $msg = $this->value('msg');
            if( !empty($msg) ){
                $replace['error'] = $this->errorClinicListModule($msg);
            }
            $searchString = $this->value('search');
            if( !empty($searchString) ){
                $searchString = $this->value('search');
                $searchWhere = " and clinic_name like '%{$this->value('search')}%' ";
            }            
            //Get the clinic list
            if( is_numeric($clinicId) ){
                $sqlUser = "select * from referral_limit where clinic_id = '{$clinicId}'";
                $result=$this->execute_query($sqlUser);
            }
            if($this->num_rows($result)!= 0)
            {
             $row = $this->fetch_array($result);
              $replace['clinic_refferal_limit'] = $row['clinic_refferal_limit'];
              $replace['clinic_user_limit'] = $row['clinic_user_limit'];  
            }
            else
            {
              $replace['clinic_refferal_limit'] = 0;
              $replace['clinic_user_limit'] = 0;  
            }
			$replace['display']="none;";$replace['addon']=" ";
			$sqlAddon = "select * from addon_services where clinic_id = '{$clinicId}'";
            $resultAddon=$this->execute_query($sqlAddon);
			if($this->num_rows($resultAddon)!= 0)
                {  $rowAddon = $this->fetch_array($resultAddon);
				   if($rowAddon['referral_report']==1)
					{ 
					   $replace['addon']="checked";
				       $replace['display']="inline;";
					}
                    if($rowAddon['program']==1)
					{ 
					   $replace['program']="checked";
				      // $replace['display']="inline;";
					}else{
                     $replace['program']="";
                    }
                    if($rowAddon['store']==1)
					{ 
					   $replace['store']="checked";
				       $replace['storelink']='<a href="index.php?action=loginprxo" style="font-weight:bold;" target="_blank">Manage Your Store</a>';
					}else{
                     $replace['store']="";
                     $replace['storelink']='<a href="javascript:void(0);" style="font-weight:bold;">Manage Your Store</a>&nbsp;<span style="color:red;">Please turn on your store first and click Save.</span>';
                    }
				if($rowAddon['scheduling']==1)
					{ 
					   $sql="select schedulUrl from scheduling where clinic_id = '{$clinicId}'";
                       $querys=$this->execute_query($sql);
                       $num=$this->num_rows($querys);
                       if($num>0)
                       {
                       $resu=$this->fetch_array($querys);
                      // print_r($resu);die;
                       $replace['schedulUrl']=$resu['schedulUrl'];
                       }
                       else{
                       $replace['schedulUrl']='';
                       }
                       $replace['schedul']="checked";
				       $replace['disabled']='';
                       
					}else{
					   $sql="select schedulUrl from scheduling where clinic_id = '{$clinicId}'";
                       $querys=$this->execute_query($sql);
                       $num=$this->num_rows($querys);
                       if($num>0)
                       {
                       $resu=$this->fetch_array($querys);
                      // print_r($resu);die;
                       $replace['schedulUrl']=$resu['schedulUrl'];
                       }
                       else{
                       $replace['schedulUrl']='';
                       }
                     $replace['schedul']="";
                     $replace['disabled']='disabled';
                    // $replace['schedulUrl']='';
                    }
                }else{
				 $replace['schedulUrl']='';   
				 $replace['program']="";   
                 $replace['store']="";
                 $replace['disabled']='disabled';
                 $replace['schedul']="";
                 $replace['storelink']='<a href="javascript:void(0);" style="font-weight:bold;">Manage Your Store</a>&nbsp;<span style="color:red;">Please turn on your store first and click Save.</span>';   
                }  
			 /*code for e health service*/
                $sqlhealth="select * from clinic_subscription where subs_clinic_id =".$clinicId;
                $queryHealth=$this->execute_query($sqlhealth);
                $numHealthRow=$this->num_rows($queryHealth);
                if($numHealthRow!=0){
                    $valuehealth=$this->fetch_object($queryHealth);
                    if($valuehealth->subs_status==0){
                    $replace['health']='';
                    $replace['readonly']='readOnly'." style='background:#ebebe4'";
                    $replace['display']='none'; 
                    }
                    else{
                    $replace['health']='checked';
                    $replace['readonly']=''. "style ='background:#ffffff'";
                    $replace['display']='block'; 
                   }
                  $replace['subs_title']=$valuehealth->subs_title;;
                  $replace['subs_price']=$valuehealth->subs_price;
                  $replace['subs_description']=$valuehealth->subs_description;
                  $replace['subs_feature1']=$valuehealth->subs_feature1;
                  $replace['subs_feature2']=$valuehealth->subs_feature2;
                  $replace['subs_feature3']=$valuehealth->subs_feature3;
                  $replace['count']=3;
                  $editSubsFeature='';
                  if($valuehealth->subs_feature4!=''){
                  $replace['subs_feature4']=$valuehealth->subs_feature4;
                  $editSubsFeature="<tr><td><input name='subs_feature4' type='text' maxlength='50' id='subs_feature4' value='{$valuehealth->subs_feature4}' '{$replace['readonly']}' /></td>
	<td>&nbsp;</td></tr>";  
    $replace['count']=4;
                  }
                  if($valuehealth->subs_feature5!=''){
                  $replace['subs_feature5']=$valuehealth->subs_feature5;
                  $editSubsFeature="<tr><td><input name='subs_feature4' type='text' maxlength='50' id='subs_feature4' value='{$valuehealth->subs_feature4}' '{$replace['readonly']}' /></td>
	<td>&nbsp;</td></tr><tr><td><input name='subs_feature5' type='text' maxlength='50' id='subs_feature5' value='{$valuehealth->subs_feature5}' '{$replace['readonly']}' /></td>
	<td>&nbsp;</td></tr>";
    $replace['count']=5; 
                  }
                  if($valuehealth->subs_feature6!=''){
                  $replace['subs_feature6']=$valuehealth->subs_feature6;
                  $editSubsFeature="<tr><td><input name='subs_feature4' type='text' maxlength='50' id='subs_feature4' value='{$valuehealth->subs_feature4}' '{$replace['readonly']}' /></td>
	<td>&nbsp;</td></tr><tr><td><input name='subs_feature5' type='text' maxlength='50' id='subs_feature5' value='{$valuehealth->subs_feature5}' '{$replace['readonly']}' /></td>
	<td>&nbsp;</td></tr><tr><td><input name='subs_feature6' type='text' maxlength='50' id='subs_feature6' value='{$valuehealth->subs_feature6}' '{$replace['readonly']}' /></td>
	<td>&nbsp;</td></tr>";
    $replace['count']=6; 
                  }
                  if($valuehealth->subs_feature7!=''){
                  $replace['subs_feature7']=$valuehealth->subs_feature7;
                  $editSubsFeature="<tr><td><input name='subs_feature4' type='text' maxlength='50' id='subs_feature4' value='{$valuehealth->subs_feature4}' '{$replace['readonly']}' /></td>
	<td>&nbsp;</td></tr><tr><td><input name='subs_feature5' type='text' maxlength='50' id='subs_feature5' value='{$valuehealth->subs_feature5}' '{$replace['readonly']}' /></td>
	<td>&nbsp;</td></tr><tr><td><input name='subs_feature6' type='text' maxlength='50' id='subs_feature6' value='{$valuehealth->subs_feature6}' '{$replace['readonly']}' /></td>
	<td>&nbsp;</td></tr><tr><td><input name='subs_feature7' type='text' maxlength='50' id='subs_feature7' value='{$valuehealth->subs_feature7}' '{$replace['readonly']}' /></td>
	<td>&nbsp;</td></tr>"; 
                  $replace['count']=7;
                  $replace['js']='<script language="javascript" type="text/javascript">document.getElementById("link_more").style.display="none";       
         document.getElementById("link_more_help").style.display="none";</script>';
                  }
                  $replace['editSubsFeature']=$editSubsFeature;  
                 }else{
                  $replace['health']='';
                  $replace['count']= 3;
                  $replace['readonly']='readOnly'." style='background:#ebebe4'";
                   $replace['subs_description']='';  
                  $replace['subs_title']='';
                  $replace['subs_feature1']='';
                  $replace['subs_feature2']='';
                  $replace['subs_feature3']='';
                  $replace['subs_feature4']='';
                  $replace['subs_feature5']='';
                  $replace['subs_feature6']='';
                  $replace['subs_feature7']='';
                  $replace['subs_price']='';
                  $replace['display']='block';
                  $replace['js']='<script language="javascript" type="text/javascript">document.getElementById("link_more").style.display="none";       
         document.getElementById("link_more_help").style.display="none";</script>';   
                 }
                /*end of code*/
            $replace['navigationTab'] = $this->build_template($this->get_template("navigationTab"));
			$replace['clinic_id'] = $clinicId;
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['footer'] = $this->build_template($this->get_template("footer"));    
            $replace['sidebar'] = $this->sidebar();
            $replace['reportTblHead'] = $this->build_template($this->get_template("reportTblHead"),$this->table_heading($reportListHead,"p_name_first",$reportperiod));
			$replace['body'] = $this->build_template($this->get_template("txAddonServices"),$replace);
			$replace['referralSettings'] = $this->build_template($this->get_template("txReferralSetting"),$replace);
            $replace['healthsevice'] = $this->build_template($this->get_template("txHealthSevice"),$replace);
            $replace['browser_title'] = "Tx Xchange: Add-on Settings";
            $replace['get_satisfaction'] = $this->get_satisfaction();
            $this->output = $this->build_template($this->get_template("main"),$replace);            
            
        }
        /**
        * This function displays error prox.        
        */
         function prxoclinicerror(){
            $replace['body'] = $this->build_template($this->get_template("prxoclinicerror"));
			$this->output = $this->build_template($this->get_template("main"),$replace);          
        }
        /**
        * This function create excel report         
        */
        
        function txReferralReportExcels(){
            $month= date('m');
            $year=date('Y');
            $clinicId = $this->clinicInfo("clinic_id");
            $clinicName=$this->get_clinic_name($clinicId);
            if( !is_numeric($clinicId) || $clinicId == 0 ){
                header("location:index.php?action=logout");
                exit();
            }
            // Retian page value.
            $arr = array(
                'clinic_id' => $clinicId
            );
            //Get the clinic list
            
            if( is_numeric($clinicId) ){
                if($this->value('period')!=''){
                    $time=explode('~~',$this->value('period'));
                    $month1= $time[0];
                    $year1=$time[1];  
                }else{
                    $month1= date('m');
                    $year1=date('Y');    
                }
                $filename="report/".$clinicName.'_'.$month1.'_'.$year1.'_Referrals_report.xls';
                $filename=str_replace(" ",'_',$filename);
                $excel=new ExcelWriter($filename);
                if($excel==false)
                echo $excel->error;
                $sqlUser = "select user.name_first as p_name_first,user.name_last as p_name_last,
                                   patients_refferals.name_first as r_name_first,patients_refferals.name_last as r_name_last,
                                   sent_date,recipient_email
                              from patients_refferals,user 
                              where patients_refferals.user_id=user.user_id 
                                    and patients_refferals.status = 1 
                                    and clinic_id = '{$clinicId}'
                                    and MONTH(sent_date)={$month1} 
                                    and YEAR(sent_date)={$year1} order by sent_date desc";
            }
            $result = $this->execute_query($sqlUser);     
            
            if($this->num_rows($result)!= 0)
            {
                
		        $myArr=array("<b>Name of Referring Patient</b>","<b>Name of Person Referred</b>","<b>Email Address of Person Referred</b>","<b>Date Referral Sent</b>");
                $excel->writeLine($myArr);
                while($row = $this->fetch_array($result))
                {
                    $excelrow['p_name_first'] = $this->decrypt_data($row['p_name_first']).' '.$this->decrypt_data($row['p_name_last']);
                    $excelrow['r_name_first'] = $row['r_name_first'].' '.$row['r_name_last'];
                    $excelrow['recipient_email']=$row['recipient_email'];
                    $excelrow['sent_date'] = $this->formatDate($row['sent_date']);
                    $excel->writeLine($excelrow);                    
                }
            }else{
               $excelrow['p_name_first'] = 'No Record Found'; 
               $excel->writeLine($excelrow);
                
            }
            $excel->writeRow();
            $excel->writeRow();
            $excel->writeRow();
            $excel->writeCol("<b>Total Number of Referrals Sent (MTD)</b>");
	        $excel->writeCol("<b>".$this->num_rows($result)."</b>");
	        $excel->close();
            if (file_exists($filename)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($filename));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filename));
            ob_clean();
            flush();
            readfile($filename);
            exit;
            }
	        
        } 
        
         /**

		 * Displays confirm popup window for addon services

		 *

		 * @access public

		 */

		

		function confirmAddonServices(){

			$this->output = $this->build_template($this->get_template("confirmAddonServices"));

		}
         /**
		 * This function display's the FAQ page Therapist.
		 * 
		 */
		function faq_therapist(){
			$this->output =  $this->build_template($this->get_template("faq_therapist"));
		}
         
         /**
		 * This function display's the FAQ E-Rehab page Therapist.
		 * 
		 */
		function faq_therapist_erehab(){
			$this->output =  $this->build_template($this->get_template("faq_therapist_erehab"));
		}
        /**
		 * This function display's the FAQ store page Therapist.
		 * 
		 */
		function faq_therapist_store(){
			$this->output =  $this->build_template($this->get_template("faq_therapist_store"));
		}
        /**
		 * This function display's the FAQ E-Rehab page Therapist.
		 * 
		 */
		function faq_therapist_scheduling(){
			$this->output =  $this->build_template($this->get_template("faq_therapist_scheduling"));
		}
  
        public function termsOfServiceForEhealth()
        {
        
		//Get the clinic channel based on clinic is		
		$clinic_channel = $this->getchannel($this->clinicInfo('clinic_id'));
		
		if($clinic_channel == 2)
			$replace['body'] = $this->build_template($this->get_template("wx_termsOfServiceForEhealth"),$replace);			
		else
			$replace['body'] = $this->build_template($this->get_template("termsOfServiceForEhealth"),$replace);			 
            
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
}

	
	// creating object of this class
	$obj = new accountAdminUser();
?>
