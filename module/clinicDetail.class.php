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
	include("module/excelwriter.inc.php");	
	
	
	// class declaration
  	class clinicDetail extends application{
  		
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
				$str = "clinicDetail"; //default if no action is specified
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
		 * List of users (therapist) of that particluar clinic
		 *
		 * @access public
		 */
		function user_listing()
		{
						
			$replace = array();
                        /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
            $msg = $this->value('msg');
            $replace['error'] = $this->errorUserListModule($msg);
            
			if( $this->value('msg') == "1" ){
				$error[] = "There are patient(s) who are associated with this Provider, please remove the association and do the action.";
				$replace['error'] = $this->show_error($error);
			}
			elseif ($this->value('msg') == "2"){
				$replace['error'] = "Not a valid user.";
			}
			
			$userInfo = $this->userInfo();
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else 
			{								
				// Retian page value.
				$arr = array(
					'clinic_id' => $this->value('clinic_id')
				);
				$this->set_session_page($arr);
				
				/* Defining Sorting */				
				$orderByClause = "";
				 if($this->value('sort') != ""){
                    if($this->value('order') == 'desc' ){
                        if($this->value('sort') == 'user.name_first' ){
                             $privateKey = $this->config['private_key'];
                             $orderByClause = " order by cast(AES_DECRYPT(UNHEX(user.name_first),'{$privateKey}') as char) desc "; 
                        }
                        else
                            $orderByClause = " order by {$this->value('sort')} desc ";
                    }
                    else{
                        if($this->value('sort') == 'user.name_first' ){
                             $privateKey = $this->config['private_key'];
                             $orderByClause = " order by cast(AES_DECRYPT(UNHEX(user.name_first),'{$privateKey}') as char) "; 
                        }
                        else
                            $orderByClause = " order by {$this->value('sort')} ";
                    }
                }
                else{
                    $orderByClause = " order by clinic.clinic_name ";
                }
				
				/* End */	
				//Get  clinic id
				
                if( !empty($_SESSION['parent_clinic_id']) ){
                    $clinicId = $_SESSION['parent_clinic_id'];    
                }
                else{
                    header('location:index.php?action=listClinic');    
                    exit();
                }
                // Search string
                $searchString = $this->value('search');
                if( !empty($searchString) ){
                    $searchString = $this->value('search');
                    $privateKey = $this->config['private_key'];
                    $searchWhere = " and ( CAST(AES_DECRYPT(UNHEX(user.name_first),'{$privateKey}') as CHAR) like'%{$this->value('search')}%' or CAST(AES_DECRYPT(UNHEX(user.name_last),'{$privateKey}') as CHAR) like'%{$this->value('search')}%') ";
                }            				
				//Get the therapist list
                $privateKey = $this->config['private_key'];
				$sqlUser = "SELECT user.*, CONCAT_WS(' ',AES_DECRYPT(UNHEX(name_title),'{$privateKey}'),AES_DECRYPT(UNHEX(name_first),'{$privateKey}'), AES_DECRYPT(UNHEX(name_last),'{$privateKey}'),name_suffix) AS full_name,clinic_user.clinic_id,clinic.clinic_name FROM user 
					inner join clinic_user on clinic_user.user_id = user.user_id 
                    and (user.status = '1' or user.status = '2') 
                    and clinic_user.clinic_id in (select clinic_id from clinic where parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}' and (status = 1 or status = 2) ) 
					inner join clinic on clinic.clinic_id = clinic_user.clinic_id and ( clinic.status = '1' or clinic.status = '2') WHERE user.usertype_id = '2' 
					{$searchWhere}  ".$orderByClause;
				$sqlcount = "SELECT count(1) FROM user inner join clinic_user on clinic_user.user_id = user.user_id 
                    and (user.status = '1' or user.status = '2') 
                    and clinic_user.clinic_id in (select clinic_id from clinic where parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}' and (status = 1 or status = 2) ) 
					inner join clinic on clinic.clinic_id = clinic_user.clinic_id and ( clinic.status = '1' or clinic.status = '2') WHERE user.usertype_id = '2' 
					{$searchWhere}";
				//echo $sqlUser;
                $skipValue = array(	"msg" );
				$link = $this->pagination($rows = 0,$sqlUser,'user_listing',$searchString,$skipValue,'','',$sqlcount);                                          
	
	            $replace['link'] = $link['nav'];
	
	            $result = $link['result']; 	
				$sqlTotTherapists = "SELECT count(user.user_id) AS totTherapists FROM user 
					inner join clinic_user on clinic_user.user_id = user.user_id and user.status = '1'  and clinic_user.clinic_id in (select clinic_id from clinic where parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}' and (status = 1 or status = 2)  )
					where user.therapist_access = '1' AND user.usertype_id = '2'";
	            
	            $resultTotTherapists = $this->execute_query($sqlTotTherapists);
	            $rowTotTherapists = $this->fetch_array($resultTotTherapists);
	            
				$replace['totTherapist'] = $rowTotTherapists['totTherapists'];
				
				//$therapist_access = 0;
				//$patient_association = 0;
                $userListHead = array(
                    'user.name_first' => 'Full Name',
                    'user.username' => 'Username',
                    'clinic.clinic_name' => 'Clinic',
                    'user.last_login' => 'Last Login',
                );
                $query_string = array(
                    'clinic_id' => $clinicId
                );
				$replace['userTblHead'] = $this->build_template($this->get_template("userTblHead"),$this->table_heading($userListHead,'user.name_first',$query_string));
				if($this->num_rows($result)!= 0)
				{
					
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
						if($row['status'] == '1'){
							$row['status_text'] = "Inactivate";
							$row['status_url'] = 'inactiveUserSystem';//"index.php?action=inactiveUserSystem&id={$row['user_id']}";
						}
						if($row['status'] == '2'){
							$row['status_text'] = "Activate";
							$row['status_url'] = 'activeUserSystem';//"index.php?action=activeUserSystem&id={$row['user_id']}";
						}
						$row['clinic_id'] = $row['clinic_id'];//$clinicId;
                        
                        if ($row['status'] == 1 and $row['trial_status']==1)
						{
							$row['statusType'] = "Trial";
							
						}
                        else if ($row['status'] == 1 and $row['trial_status']!=1)
						{
							$row['statusType'] = "Active";
							
						}
						else if ($row['status'] == 2)
						{
							$row['statusType'] = "Inactive";
							
							
						}
                        
                        
                        
                        
                        
                        
						$replace['userTblRecord'] .=  $this->build_template($this->get_template("userTblRecord"),$row);					
						
					}
				}
				else 
				{
					$replace['userTblRecord'] =  '<tr> <td colspan="6">No Users to list</td></tr>';
					$replace['link'] = "&nbsp;";
				}									
				
				//$replace['clinic_id'] = $this->value('clinic_id');
                if( !empty($_SESSION['parent_clinic_id']) ){
                    $replace['account_name'] = ($account_name = $this->get_field($_SESSION['parent_clinic_id'],'clinic','clinic_name')) != ''?strtoupper($account_name):'';
                }
                $url_array = $this->tab_url();
                $replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
                $replace['userLocation'] = $url_array['userLocation'];
				$replace['header'] = $this->build_template($this->get_template("header"));
				$replace['footer'] = $this->build_template($this->get_template("footer"));	
				$replace['sidebar'] = $this->sidebar();
				
				$replace['body'] = $this->build_template($this->get_template("userTemplate"),$replace);
				$replace['browser_title'] = "Tx Xchange: Users List";
				$this->output = $this->build_template($this->get_template("main"),$replace);			
			}	
			
		}
        /**
        * This function returns url for tab.
        */
        function tab_url(){
            $url_array = array();
            if( !empty($_SESSION['parent_clinic_id']) ){
                $url_array['location'] = "index.php?action=listAccountClinic&clinic_id={$_SESSION['parent_clinic_id']}";
                $url_array['userLocation'] = "index.php?action=user_listing&clinic_id={$_SESSION['parent_clinic_id']}";
                $url_array['patientLocation'] = "index.php?action=clinic_patient&clinic_id={$_SESSION['parent_clinic_id']}";
            }
            return $url_array;
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
			include_once("template/clinicDetail/userArray.php");
			
			$orderByClause = "";		
									
			foreach ($sortColTblArray as $key => $value)
			{
				$strKey = $key.'Img';
				
				if ($this->value('sort') == $key)
				{
					if($this->value('order') == "ASC")
					{
						$replace[$key] = "action=".$action.$queryStr."&sort=".$key."&order=DESC"."&clinic_id=".$this->value('clinic_id');
						
						$replace[$strKey] = '&nbsp;<img src="images/sort_asc.gif">';
					}
					else 
					{
						$replace[$key] = "action=".$action.$queryStr."&sort=".$key."&order=ASC"."&clinic_id=".$this->value('clinic_id');
						$replace[$strKey] = '&nbsp;<img src="images/sort_desc.gif">';
					}
					
					$replace['orderByClause'] = $value[$this->value('order')];
					
				}
				else
				{
					
					$replace[$key] = "action=".$action.$queryStr."&sort=".$key."&order=ASC"."&clinic_id=".$this->value('clinic_id');
					$replace[$strKey] = '';
					
				}			
				
			}					
			
			
		}
		/**
		 * Delete the user (Therapist or Account admin) of clinic.
		 * 
		 * @access public
		 *
		 */
		function deleteUserSystem()
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
				$url = $this->redirectUrl("user_listing");
				$strJS =  "<script language='JavaScript'>
						     opener.window.document.location.href = '".$url."';
						     window.close();</script>";
				echo $strJS;							
					
			}		
			
		}
		/**
		 * Create a new user (therapist or account admin) for clinic.
		 *
		 * @access public
		 */
		function addUserSystem()
		    {
			$replace = array();
			
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
				
				include_once("template/clinicDetail/userArray.php");

				$this->formArray = $formArray;	
				//print_r($formArray);
				
				$clinicId = $this->value('clinic_id');	
				
				//print_r($_POST);
				if (isset($_POST['submitted_add']) && $_POST['submitted_add'] == 'Add User' && is_numeric($clinicId) && $clinicId > 0 )
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
											'practitioner_type'=>$this->value('practitioner_type'),
											'country'=>$this->value('clinic_country')
											);
						
						// Encrypt data
                            //$encrypt_field = array('address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
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
						
						// Also handle the copying of article and treatment template for user if user has the therapist privelege
						
                        if(is_numeric($clinicId)){
                            $this->copyPublishPlan($newlyCreatedUserId, $clinicId);    
                        }
                        
                        
						{//usertype = therapist	
							if	(($therapist_access == 1) || ($admin_access == 1))
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
	                        $clinic_type = $this->getUserClinicType($newlyCreatedUserId);
							$queryUserType = "SELECT * FROM user WHERE status = 1 AND user_id = ".$newlyCreatedUserId;
							$result = $this->execute_query($queryUserType);
							
							if($this->num_rows($result)!= 0)
								{
									 $rowID = $this->fetch_array($result);
									 $user_Admin = $rowID['admin_access'];
									 $user_Therapist = $rowID['therapist_access'];
								    $clinic_channel=$this->getchannel($clinicId);
									 if( $clinic_channel == 1){
										if($user_Therapist == 1 && $user_Admin != 1){
											$subject = "Your Tx Xchange Account Information";
											$message = $this->build_template($this->get_template("newUserMailContent_plpto"),$replace);
										}elseif($user_Admin == 1 ){
											$subject = "Tx Xchange Account Information";
											$message = $this->build_template($this->get_template("newUserAdminMailContent_plpto"),$replace);
										}
									}else{
									if($user_Therapist == 1 && $user_Admin != 1){
                                            $subject = "Your Wholemedx Account Information";
                                            $message = $this->build_template($this->get_template("newUserMailContent_wx"),$replace);
                                        }elseif($user_Admin == 1 ){
                                            $subject = "Wholemedx Account Information";
                                            $message = $this->build_template($this->get_template("newUserAdminMailContent_wx"),$replace);
                                        }
									}
								}
/*	                        if( $clinic_type == 'plpto'){
	                        	$subject = "Your Login Details For Tx Xchange";
	                        	$message = $this->build_template($this->get_template("newUserMailContent_plpto"),$replace);
	                       	}
	                        elseif( $clinic_type == 'elpto' ){
	                        	$subject = "Your Login Details For LivePTOnline.com";
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
						    $headers .= "From:  <".$this->config['email_tx'].">" . "\n";
						    $returnpath = "-f".$this->config['email_tx'];
						    }else{
						    $headers .= "From:  <".$this->config['email_wx'].">" . "\n";
						    $returnpath = '-f'.$this->config['email_wx'];   
						    }
							// Mail it					
							@mail($to, $subject, $message, $headers, $returnpath);	
							
						}
						
						
						// redirect to list of subscriber
						//header("location:index.php?action=user_listing&clinic_id=".$this->value('clinic_id')."&msg=success");
                        $location = $this->redirectUrl('user_listing').'&msg=user_added';
                        
                        header("location:$location");
                        exit();
						
					}
					else
					{
						
						$replace = $this->fillForm($this->formArray,$_POST);	
										
						$replace['error'] = $this->error;							
											
						//Also the state
						$selectedState = ($this->value('state') == "")? "": $this->value('state');	
						$selectedCountry = ($this->value('clinic_country') == "")? "": $this->value('clinic_country');    	

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
    				$replace['clinic_id'] = $this->value('clinic_id');
                if( !empty($_SESSION['parent_clinic_id']) ){
                    $replace['account_name'] = ($account_name = $this->get_field($_SESSION['parent_clinic_id'],'clinic','clinic_name')) != ''?strtoupper($account_name):'';
                }
                $url_array = $this->tab_url();
                $replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
				$replace['header'] = $this->build_template($this->get_template("header"));
				$replace['footer'] = $this->build_template($this->get_template("footer"));							
						
				$replace['sidebar'] = $this->sidebar();
				
				   $countryArray = $this->config['country'];
		           $replace['country']=implode("','",$countryArray); 
		           $replace['patient_country_options'] = $this->build_select_option($countryArray, $selectedCountry);
		            
		          
		           
		            if($row['country']=='US' || $selectedCountry=='US' ) {
		            $stateArray = array("" => "Choose State...");
		            $stateArray = array_merge($stateArray,$this->config['state']);              
		            $replace['stateOptions'] = $this->build_select_option($stateArray, $selectedState);         
		            }
		           
		            else if($row['country']=='CAN' || $selectedCountry=='CAN') {
		            $stateArray = array("" => "Choose Province...");
		            $stateArray = array_merge($stateArray,$this->config['canada_state']);
		            $replace['stateOptions'] = $this->build_select_option($stateArray, $selectedState);         
		               } else{
		               	
		               	 $stateArray = array("" => "Choose State...");
                         $stateArray = array_merge($stateArray,$this->config['state']);
                         $replace['stateOptions'] =  $this->build_select_option($stateArray,$selectedState);   
                    
		               }  
				
				
				
				
				
				
				// calling template	
				$replace['body'] = $this->build_template($this->get_template("createUser"),$replace);
				$replace['browser_title'] = "Tx Xchange: Add User";
				$this->output = $this->build_template($this->get_template("main"),$replace);
					
			}
		}
        /**
         * Create a new user (therapist or account admin) for clinic.
         *
         * @access public
         */
        function addEditClinicInAccount()
        {

            $replace = array();
            $option = $this->value('option');
            $clinic_id = $this->value('clinic_id');
            
            
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar();
            $replace['clinic_id'] = $clinic_id;
            $replace['subaction'] = $this->value('subaction');
            if( $replace['subaction'] == "edit" ){
                    $replace['heading1'] = 'EDIT CLINIC';
                    $replace['heading'] = 'Edit Clinic';
                    $replace['browser_title'] = 'Tx Xchange: Edit Clinic';
            }
            elseif($replace['subaction'] == "add"){
                    $replace['heading1'] = 'ADD CLINIC';
                    $replace['heading'] = 'Add New Clinic';
                    $replace['browser_title'] = 'Tx Xchange: Add Clinic';
            }
            $this->form_array = $this->populate_clinic_form_array();
            // When user modify the clinic details
            if( $replace['subaction'] == "add" && (empty($option) ))
            {
                // Populate BLANK FORM fields
                $this->assignValueToArrayFields($this->form_array, '', '1', &$replace);
            }
            elseif( $replace['subaction'] == "edit" && empty($option)){
                    //Populate FORM fields from database.
                    $query = "select * from clinic where clinic_id='".$clinic_id."'";
                    $rs = $this->execute_query($query);
                    $row = $this->populate_array($rs);
                    // Fetch Replace array from row
                    // populate FormArray from FieldArray
                    $this->assignValueToArrayFields($this->form_array, $row, '1', &$replace);
            }
            else{
                
                if($option == 'update'){
                    $this->validateClinicForm();                    
                    if($this->error == "")
                        
                        {
                        //  Populate FieldArray from FormArray
                        if(!empty($clinic_id) && $replace['subaction'] == "edit"){
                            $updateArr = $this->assignValueToArrayFields($this->form_array, '', '2', '', 'insert');
                            $where = " clinic_id = '".$clinic_id."'";
                            $result = $this->update('clinic',$updateArr,$where);
                            $location = $this->redirectUrl('listAccountClinic').'&msg=clinic_updated';
                            header("location:{$location}");    
                            exit();
                        }elseif(!empty($clinic_id) && $replace['subaction'] == "add"){
                            $insertArr = $this->assignValueToArrayFields($this->form_array, '', '2', '', 'insert');
                            $insertArr['status'] = '1';
                            $insertArr['creationDate'] = date("Y-m-d H:i:s");
                            if( is_numeric($clinic_id) && $clinic_id != 0 ){
                                $insertArr['parent_clinic_id'] = $clinic_id;
                            }
                            $result = $this->insert('clinic',$insertArr);
                            $location = $this->redirectUrl('listAccountClinic').'&msg=clinic_added';
                            header("location:{$location}");    
                            exit();
                        }
                        
                    }else{
                    	
                    if($_REQUEST['clinic_country']=='US') {
            $stateArray = array("" => "Choose State...");
            $stateArray = array_merge($stateArray,$this->config['state']);              
            $replace['stateOptions'] = $this->build_select_option($stateArray, $_REQUEST['clinic_state']);         
            }
           
            else if($_REQUEST['clinic_country']=='CAN') {
            $stateArray = array("" => "Choose Province...");
            $stateArray = array_merge($stateArray,$this->config['canada_state']);
            $replace['stateOptions'] = $this->build_select_option($stateArray, $_REQUEST['clinic_state']);         
               }
                        //Show errors and populate FORM fields from $_POST.
                        $this->assignValueToArrayFields($this->form_array, '', '', &$replace, '0');
                        $replace['error'] = $this->error;
                    }
                }
            }
        
            // creating state drop down element
         if(!empty($clinic_id) && $replace['subaction'] == "edit"){      
             //echo '<pre>';
             //print_r($replace);
             if($replace['corporate_ehs']==1)
                 $replace['corporate']='Checked';
             else
                $replace['corporate']='';
            $countryArray = $this->config['country'];
            $replace['country']=implode("','",$countryArray); 
            $replace['patient_country_options'] = $this->build_select_option($countryArray, $replace['clinic_country']);
            
            
            
           
            if($row['country']=='US') {
            $stateArray = array("" => "Choose State...");
            $stateArray = array_merge($stateArray,$this->config['state']);              
            $replace['stateOptions'] = $this->build_select_option($stateArray, $replace['clinic_state']);         
            }
           
            else if($row['country']=='CAN') {
            $stateArray = array("" => "Choose Province...");
            $stateArray = array_merge($stateArray,$this->config['canada_state']);
            $replace['stateOptions'] = $this->build_select_option($stateArray, $replace['clinic_state']);         
               }           
                
           
            }
            if( !empty($_SESSION['parent_clinic_id']) ){
                    $replace['account_name'] = ($account_name = $this->get_field($_SESSION['parent_clinic_id'],'clinic','clinic_name')) != ''?strtoupper($account_name):'';
            }
            $url_array = $this->tab_url();
            $replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
            $replace['body'] = $this->build_template($this->get_template("addClinicInAccount"),$replace);
            $this->output = $this->build_template($this->get_template("main"),$replace);
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
		function editUser_System()
		{
			
			$id = (int) $this->value('id');	
			
			$replace = array();
			$firstSubscription= "";		
			$userInfo = $this->userInfo();			
			$userId = $userInfo['user_id'];
			$replace['error']='';
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else
			{					
				include_once("template/clinicDetail/userArray.php");
                $this->formArray = $formArray;			
				
				### Get clinic id from url ###
				$clinicId = $this->value('clinic_id');	
						
				
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
											'zip' => $this->value('zip'),
											'phone1' => $this->value('phone1'),
											/*'phone2' => $this->value('phone2'),*/
											'fax' => $this->value('fax'),																						
											'modified' => date('Y-m-d H:i:s',time()),
											'admin_access' => $admin_access,
											'therapist_access' => $therapist_access_checkbox,
											'practitioner_type'=>$this->value('practitioner_type'),
											'country' => $this->value('clinic_country')
											);
											
                        $row = $this->fetch_array($result);    
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
											
							$where = " user_id = ".$id;
							
							$result = $this->update('user',$updateArr,$where);	
							
						}
						 
				
						{
							## if earlier user was having therapist access but now removed then delete therapist_patient association
							if ($_POST['therapist_access'] == 1)
							{
								
								##	so earlier this user was having therapist access now check if therapist check box is still
								##	check or not if not remove the therapist patient association																	
								if($therapist_access_checkbox == 0)
								{
									## remove the records from therapist_patient table
									
									$sqlUpdate = "DELETE FROM therapist_patient WHERE therapist_id = '".$id."'";			
									$result = $this->execute_query($sqlUpdate);
								}
								
							}
						}
						
						
				
						
						{//usertype = therapist	and first time subscription
							//if	($therapist_access_checkbox == 1 && $this->value('firstSubscription') == 'Yes')
							if ($therapist_access_checkbox == 1 )
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
											
												
												//	Also for this particular DBrow fetch from plan table have the plan_id
												//	now find this plan_id in table plan_article	get the rows for this plan_id
												//	insert rows into plan_article with all data same except replace plan_id with newly created plan_id									
													
												
												
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
											
												
												//	Also for this particular DBrow fetch from plan table have the plan_id
												//	now find this plan_id in table plan_treatment	get the rows for this plan_id
												//	insert rows into plan_treatment with all data same except replace plan_id with newly created plan_id									
													
												
												
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
									
								  }//End of if block of plan_count.
								
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
						$url = $this->redirectUrl("user_listing").'&msg=user_updated';
						header("location:".$url);
						
						
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
                        // Encrypt data
                        $encrypt_field = array('name_title','name_first','name_last','password','address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
                        $row = $this->decrypt_field($row, $encrypt_field);
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
					$selectedCountry=$row['country'];						
					
					//$row['confirmUsername'] = $row['username'];
					$replace = $this->fillForm($this->formArray,$row);
					//$replace['subscriber'] = strtoupper($row['name_first']." ".$row['name_last']);					
					
					$replace['therapist_access'] = ($row['therapist_access'] == 1)? 1 :0;
					
					{
						
						//Also if Therapist
							
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
				$replace['clinic_id'] = $this->value('clinic_id');
                if( !empty($_SESSION['parent_clinic_id']) ){
                    $replace['account_name'] = ($account_name = $this->get_field($_SESSION['parent_clinic_id'],'clinic','clinic_name')) != ''?strtoupper($account_name):'';
                }
                $url_array = $this->tab_url();
                $replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
				$replace['header'] = $this->build_template($this->get_template("header"));
				$replace['footer'] = $this->build_template($this->get_template("footer"));																
				$replace['sidebar'] = $this->sidebar();
				if($this->value('clinic_country')!='')
				$selectedCountry=$this->value('clinic_country');
				    $countryArray = $this->config['country'];
			            $replace['country']=implode("','",$countryArray); 
			            $replace['patient_country_options'] = $this->build_select_option($countryArray, $selectedCountry);
			            


                    if($row['country']=='US' || $selectedCountry=='US' ) {
                    $stateArray = array("" => "Choose State...");
                    $stateArray = array_merge($stateArray,$this->config['state']);              
                    $replace['stateOptions'] = $this->build_select_option($stateArray, $selectedState);         
                    }
                   
                    else if($row['country']=='CAN' || $selectedCountry=='CAN') {
                    $stateArray = array("" => "Choose Province...");
                    $stateArray = array_merge($stateArray,$this->config['canada_state']);
                    $replace['stateOptions'] = $this->build_select_option($stateArray, $selectedState);         
                       } else{
                        
                         $stateArray = array("" => "Choose State...");
                         $stateArray = array_merge($stateArray,$this->config['state']);
                         $replace['stateOptions'] =  $this->build_select_option($stateArray,$selectedState);   
                    
                       } 			            
			            
				$replace['body'] = $this->build_template($this->get_template("editUser"),$replace);
				$replace['browser_title'] = "Tx Xchange: Edit User";
				$this->output = $this->build_template($this->get_template("main"),$replace);
					
			}
			
			
			
		}
        /**
        * Returns error message for user list module.
        */
        function errorClinicListModule($msg){
            if( $msg == 'clinic_added'){
                $error[] = 'New Clinic added successfully';
                $replace['error'] = $this->show_error($error,'green');
            }
            elseif( $msg == 'clinic_updated'){
                $error[] = 'Clinic record updated successfully';
                $replace['error'] = $this->show_error($error,'green');
            }
            
            return $replace['error'];
        }
        /**
        * Returns error message for user list module.
        */
        function errorUserListModule($msg){
            
            if( $msg == "1" ){
                $error[] = 'There are patient(s) who are associated with this Provider, please remove the association and do the action.';
                $replace['error'] = $this->show_error($error);
            }
            elseif($msg == "2"){
                $error[] = 'Not a valid user.';
                $replace['error'] =  $this->show_error($error);
            }
            elseif($msg == 3){
                $error[] = 'User is successfully activated';
                $replace['error'] =  $this->show_error($error,'green');
            }
            elseif($msg == 4){
                $error[] = 'User is successfully inactivated';
                $replace['error'] =  $this->show_error($error,'green');
            }
            elseif($msg == 5){
                $error[] = 'User successfully deleted';
                $replace['error'] =  $this->show_error($error,'green');
            }
            elseif($msg == 'user_updated'){
                $error[] = 'User record updated successfully';
                $replace['error'] =  $this->show_error($error,'green');
            }
            elseif( $msg == 'user_added'){
                $error[] = 'New user added successfully';
                $replace['error'] = $this->show_error($error,'green');
            }
            return $replace['error'];
        }
        /**
        * Returns error message for user list module.
        */
        function errorPatientListModule($msg){
            
            if( $msg == 'patient_updated' ){
                $error[] = 'Record is successfully updated';
                $replace['error'] = $this->show_error($error,'green');
            }
            elseif( $msg == 'patient_added' ){
                $error[] = 'New Patient added successfully';
                $replace['error'] = $this->show_error($error,'green');
            }
            return $replace['error'];
        }
		function checkValidationSystem()
		{									
		//	print_r($_POST);

			$objValidationSet = new ValidationSet();					
			//$allowchar=array('0'=>'@','1'=>'.','2'=>'_','3'=>'-','4'=>'"');	
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
				//$objValidationSet->addValidator(new  AlphanumericOnlyValidator('zip', array(' '), "Zip code should be of alphanumeric characters only",$this->value('zip')));					
				//$objValidationSet->addValidator(new  StringMinMaxLengthValidator('zip', 5,7, "Zip code should be 5 to 7 alphanumeric characters only",$this->value('zip')));
			if($this->value('clinic_country')=='CAN'){
                $objValidationSet->addValidator(new  AlphanumericOnlyValidator('zip', array(' '), "Zip code should be of alphanumeric characters only",$this->value('zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('zip', 6,7, "Zip code should be  6 to 7 alphanumeric characters only",$this->value('zip')));        
               }else{
                $objValidationSet->addValidator(new  NumericOnlyValidator('zip', null, "Zip code should be of numeric characters only",$this->value('zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('zip', 5,5, "Zip code should be  5 numeric characters only",$this->value('zip')));
            }		
			}*/	
			
						
			//$objValidationSet->addValidator(new  ZipValidator('zip',"Invalid zip code",$this->value('zip')));			
			
			
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
				
				$arrayFields = array("name_first","name_last","username","new_password","AccountPrivileges","address","address2","city","state","zip","practitioner_type","clinic_country");						
				
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
		/**
		 * validating form fields of page.
		 *
		 * @param string $formType
		 * @param boolean $uniqueId
		 */
		function validateFormUser($formType, $uniqueId = false)
		{
									
			$objValidationSet = new ValidationSet();					
			
			$allowchar=array('0'=>'@','1'=>'.','2'=>'_','3'=>'-','4'=>'"','5'=>' ');	
			// validating first name		
			$objValidationSet->addValidator(new  StringMinLengthValidator('name_first', 1, "First Name cannot be empty",$this->value('name_first')));		
			$objValidationSet->addValidator(new AlphabeticalOnlyValidator('name_first',$allowchar,"Please enter valid characters in first name",$this->value('name_first')));
			
			// validating last name
			$objValidationSet->addValidator(new  StringMinLengthValidator('name_last', 1, "Last Name cannot be empty",$this->value('name_last')));		
			$objValidationSet->addValidator(new AlphabeticalOnlyValidator('name_last',$allowchar,"Please enter valid characters in last name",$this->value('name_last')));

			// validating email (username)
			$objValidationSet->addValidator(new  StringMinLengthValidator('username', 1, "Email cannot be empty",$this->value('username')));					
			$objValidationSet->addValidator(new EmailValidator('username',"Invalid email address",$this->value('username')));
            $objValidationSet->addValidator(new  StringMinLengthValidator('new_password', 6, "Password cannot be empty and should be of 6 characters in length",$this->value('new_password')));
			//validation for (practitioner_type) 
			$objValidationSet->addValidator(new  StringMinLengthValidator('practitioner_type', 1, "Practitioner Type be empty",$this->value('practitioner_type')));
			
            if ($formType === 'Add' )
			{
                $arrFieldNames = array("username","confirmUsername");
                $arrFieldValues = array($_POST["username"],$_POST["confirmUsername"]);
                $objValidationSet->addValidator(new  IdenticalValuesValidator($arrFieldNames, "Email and  confirm email address does not match",$arrFieldValues));				
			}	
			
			$arrFieldNames = array("new_password","new_password2");
			$arrFieldValues = array($_POST["new_password"],$_POST["new_password2"]);
			$objValidationSet->addValidator(new  IdenticalValuesValidator($arrFieldNames, "New password and  confirm passsword does not match",$arrFieldValues));					
		
			/*
				Also check the validation check for account privileges if user is editing his own account
				then admin_access check mark is disabled in HTML logically it means it has the admin access
				so take care of this scenario
			*/	
			
			
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
			
			
			
			//Jan 22, 2008 Updation, address state city zip fax and phone will not be mandatory
			//$objValidationSet->addValidator(new  StringMinLengthValidator('address', 1, "Address cannot be empty",$this->value('address')));				
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('address',null,"Please enter valid characters in address",$this->value('address')));
			
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('address2',null,"Please enter valid characters in address2",$this->value('address2')));
			
			//$objValidationSet->addValidator(new  StringMinLengthValidator('city', 1, "City cannot be empty",$this->value('city')));		
			$objValidationSet->addValidator(new AlphabeticalOnlyValidator('city',array(' ',',','.'),"Please enter valid characters in city",$this->value('city')));
			
		
			/*if ($this->value('zip') != '')
			{
				/*$objValidationSet->addValidator(new   AlphanumericOnlyValidator('zip', array(' '), "Zip code should be of alphanumeric characters only",$this->value('zip')));					
				$objValidationSet->addValidator(new  StringMinMaxLengthValidator('zip', 5,7, "Zip code should be  5 to 7 alphanumeric characters only",$this->value('zip')));*/
			/*if($this->value('clinic_country')=='CAN'){
                $objValidationSet->addValidator(new  AlphanumericOnlyValidator('zip', array(' '), "Zip code should be of alphanumeric characters only",$this->value('zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('zip', 6,7, "Zip code should be  6 to 7 alphanumeric characters only",$this->value('zip')));        
           }else{
                $objValidationSet->addValidator(new  NumericOnlyValidator('zip', null, "Zip code should be of numeric characters only",$this->value('zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('zip', 5,5, "Zip code should be  5 numeric characters only",$this->value('zip')));
            }
						
			}*/	
			
						
		
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
		/**
		 * Function to populate side panel of the page
		 *
		 * @access public
		 * Populating clinic address as address of therapist by Ajax
		 * 
		 * @access public
		 */
		function populateAddressSystem()
		{
			$userInfo = $this->userInfo();			
			
			$userId = $userInfo['user_id'];			
			
			$replace = array();
			
			//Get the clinic address for this user to which this user is associated with
			
			$clinicId = $this->value('clinic_id');	 
			
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
				//$replace['phone2']= $row['phone2'];
				$replace['fax']= $row['fax'];
				//print_r($row);
				$selectedCountry = $row['country'];
				$stateCountry = $this->config['country'];
				$replace['patient_country_options'] = 	$this->build_select_option($stateCountry,$selectedCountry);	
				$selectedState = $row['state'];
				if($selectedCountry=='US')
				{
					$stateArray = array("" => "Choose State...");
					
					$stateArray = array_merge($stateArray,$this->config['state']);
					
						
				}
				else if($selectedCountry=='CAN')
				{
					$stateArray = array("" => "Choose Province...");
					
					$stateArray = array_merge($stateArray,$this->config['canada_state']);
					
						
				}
				$replace['stateOptions'] = 	$this->build_select_option($stateArray,$selectedState);
			
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
				$selectedCountry = "";
                
                
                $stateCountry = $stateCountry;
                
                $replace['patient_country_options'] =   $this->build_select_option($stateCountry); 
                
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
		function emptyAddressSystem()
		{
			include_once("template/clinicDetail/userArray.php");
			
			$replace = array();
			
			$this->formArray = $formArray;
			
			$replace = $this->fillForm($this->formArray);					
			
			$selectedState = "";
			
			$stateArray = array("" => "Choose State...");
				
			$stateArray = array_merge($stateArray,$this->config['state']);
			$selectedCountry = "";
                
           $stateCountry = $this->config['country'];
                
           $replace['patient_country_options'] =   $this->build_select_option($stateCountry); 
				
			$replace['stateOptions'] = 	$this->build_select_option($stateArray,$selectedState);	
			$this->output = $this->build_template($this->get_template("emptyAddress"),$replace);		
		}
		/**
		 * Showing confirmation popup message for creating a user
		 * 
		 * @access public
		 */
		function popupConfirmSystem()
		{
			$formType = $this->value('formType');
			$replace['formType'] = $formType;
			
			//Tot Therapists
			$totTherapists = 0;
			$clinicId = $this->value('clinic_id');	
			
			
            $queryTotTherapists =  " SELECT count(user.user_id) AS totTherapists FROM user 
                    inner join clinic_user on clinic_user.user_id = user.user_id and ( user.status = '1') 
                    inner join clinic on clinic.clinic_id = clinic_user.clinic_id and ( clinic.status = '1' )
                    where clinic_user.clinic_id = '$clinicId'  and user.therapist_access = '1' AND user.usertype_id = '2' ";
                    
                    
			//echo $queryTotTherapists;
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
			$totTherapists += 1;
			//totTherapists End
			if( $this->get_field($clinicId,'clinic','status') != 1 ){
                $totTherapists = 0;    
            }
			$replace['totTherapists'] = $totTherapists;
			$this->output = $this->build_template($this->get_template("popupConfirm"),$replace);
			
		}
		/**
		 * Showing confirmation popup message if you removes the user.
		 * 
		 * @access public
		 */
		function popupConfirmRemoveSystem()
		{
			$replace = array();
			$therapistId = $this->value('id');
			
			$queryUser = "SELECt * FROM user WHERE user_id = '".$therapistId."'";
			$resultUser = $this->execute_query($queryUser);
			$rowUser = $this->fetch_array($resultUser);
			$hasAdminAccess = $rowUser['admin_access'];
			$hasTherapistAccess = $rowUser['therapist_access'];
				
			$privateKey = $this->config['private_key'];
			$queryAssocPatients = "SELECT patient_id,AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first, AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last, username FROM therapist_patient as tp, user WHERE user.user_id = tp.patient_id AND tp.therapist_id = '".$therapistId."'";
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
			$clinicId = $this->value('clinic_id');	
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
			$clinic_name = $this->get_field($clinicId,'clinic','clinic_name');
            if(!empty($clinic_name)){
                $replace['clinic_name'] = ' from '.$clinic_name.' clinic.';    
            }
            else{
                $replace['clinic_name'] = '';    
            }
			
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
		 * This function is used to display the patient listing for the particular account admin.
		 *
		 * @access public
		 */
		function clinic_patient(){
			
			// Retian page value.
				$arr = array(
					'clinic_id' => $this->value('clinic_id')
				);
				$this->set_session_page($arr);
			// set templating variables
			$replace['browser_title'] = 'Tx Xchange: Patient List';
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();
			$replace['footer'] = $this->build_template($this->get_template("footer"));	
            $replace['error'] = $this->errorPatientListModule($this->value('msg'));
            $privateKey = $this->config['private_key'];
			if($this->value('sort') != ""){
				if($this->value('order') == 'desc' ){
                    if( $this->value('sort') == 'u.name_first' ){
                        $orderby = " order by cast(AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}')  as CHAR) desc ";
                    }
                    else
					    $orderby = " order by {$this->value('sort')} desc ";
				}
				else{
                    if( $this->value('sort') == 'u.name_first' ){
                        $orderby = " order by cast(AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as CHAR) ";
                    }
                    else
					    $orderby = " order by {$this->value('sort')} ";
				}
			}
			else{
				$orderby = " order by cast(AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as CHAR) ";
			}
			
			
			
			/**
			 *	Query for generating the patient list
			 *	CONDITIONS :
			 *	1.  Patient must associated with the clinic whose Therapist(Accound Admin) is logged in
			 *	2.  Patient must have one associated therapist
			 *	3.  Patient status != 3, either Active or InActive
			 */
		
			 if( !empty($_SESSION['parent_clinic_id']) ){
                    $clinicId = $_SESSION['parent_clinic_id'];    
             }
             else{
                    header('location:index.php?action=listClinic');    
                    exit();
             }
            // Search string
            $searchString = $this->value('search');
            if( !empty($searchString) ){
                $privateKey = $this->config['private_key'];
                $searchString = $this->value('search');
                $searchWhere = " and ( CAST(AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as CHAR)  like'%{$this->value('search')}%' or CAST(AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') AS CHAR) like'%{$this->value('search')}%') ";
            }
			$privateKey = $this->config['private_key'];
             $query = "select AES_DECRYPT(UNHEX(u.name_title),'{$privateKey}') as name_title, u.user_id, 
                        AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as name_first, 
                        AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as name_last, 
                        u.username, u.status, cu.clinic_id, clinic.clinic_name
						from user as u 
						inner join clinic_user as cu on u.user_id=cu.user_id  
						and cu.clinic_id in (select clinic_id from clinic where parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}' and ( status = 1 or status = 2 ) ) 
                        inner join clinic  on clinic.clinic_id = cu.clinic_id and (clinic.status = 1 or clinic.status = 2)                          
                        where u.usertype_id=1 and (u.status = 1 or u.status = 2) 
                        {$searchWhere} {$orderby}
					 ";
            $sqlcount = "select count(1)
						from user as u 
						inner join clinic_user as cu on u.user_id=cu.user_id  
						and cu.clinic_id in (select clinic_id from clinic where parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}' and ( status = 1 or status = 2 ) ) 
                        inner join clinic  on clinic.clinic_id = cu.clinic_id and (clinic.status = 1 or clinic.status = 2)                          
                        where u.usertype_id=1 and (u.status = 1 or u.status = 2) 
                        {$searchWhere} {$orderby}
			 ";
			
			// pagination
			$link = $this->pagination($rows = 0,$query,'clinic_patient',$searchString,'','','',$sqlcount);                                          
	        $replace['link'] = $link['nav'];
	        $result = $link['result']; 	
	
	        // check sql query result
			if(is_resource($result)){
				    while($row = $this->fetch_array($result)){
					    $therapistName = $row['therapist'];
					    if( $this->view_therapist(true,$row['user_id'],$this->value('clinic_id')) ){
                            $therapistName = "<a  href='index.php?action=view_therapist&patient_id={$row['user_id']}&clinic_id={$this->value('clinic_id')}'  title='ASSOCIATED PROVIDER' rel='gb_page_center[600, 310]'>View</a> ";
					    }
                        else{
                            $therapistName = "Not Assigned";                        
                        }
					    $replace['classname'] = (++$k%2) ? 'line2' : 'line1';
					    $replace['userId'] = $row['user_id'];
					    $replace['patientName'] = $row['name_title'].' '.$row['name_first'].' '.$row['name_last'];
					    $replace['patientEmailId'] = $row['username'];
					    $replace['associatedTherapistName'] = $therapistName;
                                            $replace['associatedClinicName'] = $this->get_clinic_info($row['user_id'],"clinic_name");
                                            $replace['clinic_id'] = $this->get_clinic_info($row['user_id'],"clinic_id");
					    $replace['patientStatus'] = $row['status'] != "" ? $this->config['patientStatus'][$row['status']] : "";
					    $rowdata .= $this->build_template($this->get_template("temp_patientListRecord"),$replace);
				    }
			}
			$patientAcctListHead = array(
				'u.name_first' => 'Patient Name',
				'u.username' => 'Email Address',
                'clinic.clinic_name' => 'Clinic',
                'u.status' => 'Status'
			);
			
			$query_string = array(
				'clinic_id' => $this->value('clinic_id')
			);
			$replace['patientAcctListHead'] = $this->build_template($this->get_template("patientAcctListHead"),$this->table_heading($patientAcctListHead,"u.name_first",$query_string));
			
			if( empty($rowdata) ){
                $replace['rowdata'] =  '<tr> <td colspan="6">Record not found.</td></tr>';
            }
            else{
                 $replace['rowdata'] = $rowdata;   
            }
			$replace['clinic_id'] = $this->value('clinic_id');
                        //$replace['clinic_id'] = $this->get_clinic_info($row['user_id'],"clinic_id");
            if( !empty($_SESSION['parent_clinic_id']) ){
                    $replace['account_name'] = ($account_name = $this->get_field($_SESSION['parent_clinic_id'],'clinic','clinic_name')) != ''?strtoupper($account_name):'';
            }
            $url_array = $this->tab_url();
            $replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
            $replace['patientLocation'] = $url_array['patientLocation'];
			$replace['body'] = $this->build_template($this->get_template("patient_list"),$replace);
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}
        /**
        * View associated therapist in patient listing page.
        */
        function view_therapist($check=false, $patientId = "", $clinicId = "" ){
            
            if( !( !empty($patientId) && is_numeric($patientId) ) ){
                $patientId = $this->value('patient_id');
            }
            if( !( !empty($clinicId) && is_numeric($clinicId) ) ){
                $clinicId = $this->value('clinic_id');
            }                        
            $privateKey = $this->config['private_key'];
            $sql = "SELECT *,
                        AES_DECRYPT(UNHEX(u.name_title),'{$privateKey}') as name_title, 
                        AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as name_first,
                        AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as name_last
                        FROM therapist_patient tp
                        INNER JOIN user u ON tp.therapist_id = u.user_id and u.usertype_id ='2'
                        WHERE tp.patient_id = '{$patientId}' AND tp.therapist_id
                        IN (
                                SELECT cu.user_id FROM clinic cl
                                INNER JOIN clinic_user cu ON cu.clinic_id = cl.clinic_id
                                AND (cl.clinic_id = '{$clinicId}' OR cl.parent_clinic_id ='{$clinicId}' )
                        )";
           
           $result = @mysql_query($sql);
           if( $check == true ){
               $num_row = @mysql_num_rows($result);
               if( $num_row > 0 ){
                   return true;
               }
               else{
                   return false;
               }
           }
           if( @mysql_num_rows($result) > 0){
               $replace['listTherapist'] .= '<ol style="padding-left:50px;"  >';
               while( $row = @mysql_fetch_array($result)){
                   $replace['listTherapist'] .= "<li>".$row['name_title'].$row['name_first'].' '.$row['name_last']."</li>";
               }
               $replace['listTherapist'] .= '</ol>';
           }
           else{
           }
           $replace['clinic_id'] = $this->value('clinic_id');
           $replace['body'] = $this->build_template($this->get_template("view_therapist"),$replace);
           $replace['browser_title'] = "Tx Xchange: Clinic List";
           $this->output = $this->build_template($this->get_template("main"),$replace);
        }
        /**
        * This function displays list clinics in an account.        
        */
        function listAccountClinic(){
            $replace = array();
            // Retian page value.
            $arr = array(
                'clinic_id' => $this->value('clinic_id')
            );
            $this->set_session_page($arr);
            $replace['error'] = $this->errorClinicListModule($this->value('msg'));
            //Get  acccount id
            $clinicId = $this->value('clinic_id');
            
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
            
            $searchString = $this->value('search');
            if( !empty($searchString) ){
                $searchString = $this->value('search');
                $searchWhere = " and clinic_name like '%{$this->value('search')}%' ";
            }            
            //Get the clinic list
            if( is_numeric($clinicId) ){
                $sqlUser = "select * from clinic where (parent_clinic_id =  '{$clinicId}' or clinic_id = '{$clinicId}') {$searchWhere} {$orderby}";
            }
            
            $link = $this->pagination($rows = 0,$sqlUser,'listAccountClinic',$searchString);                                          
            $replace['link'] = $link['nav'];
            $result = $link['result'];     
            
            if($this->num_rows($result)!= 0)
            {
                $replace['clinicTblHead'] = $this->build_template($this->get_template("clinicTblHead"),$replace);
                while($row = $this->fetch_array($result))
                {
                    $row['style'] = ($c++%2)?"line1":"line2";
                    if( $clinicId == $row['clinic_id'] ){
                        $row['style'] = "line3";
                    }   
                    $row['status_text'] = $this->get_status($row['status'])=='Active'?"Inactivate Clinic":"Activate Clinic";
			//print_r($row);
                    if($row['country']=='US')
                    $row['state'] = $this->config['state'][$row['state']];
                    if($row['country']=='CAN')
                    $row['state'] = $this->config['canada_state'][$row['state']];
                    
                    $row['status'] = $this->get_status($row['status']);
                    if($row['trial_status']==1 and ($row['trial_date']!='' or !empty($row['trial_date']))){
                        $row['status'] = 'Trial';
                        $row['status_text']="Activate Clinic";
                    }
                    
                    $row['creationDate'] = $this->formatDate($row['creationDate']);
                    $replace['clinicTblRecord'] .= $this->build_template($this->get_template("clinicTblRecord"),$row);
                }
            }
                               
            
            $clinicListHead = array(
                'clinic_name' => 'Clinic Name',
                'city' => 'City',
                'state' => 'State',
                'status' => 'Status',
                'creationDate' => 'Created On'
            );
            
            $query_string = array(
                'clinic_id' => $this->value('clinic_id')
            );
            
            $replace['clinicTblHead'] = $this->build_template($this->get_template("clinicTblHead"),$this->table_heading($clinicListHead,"clinic_name",$query_string));
            $replace['clinic_id'] = $this->value('clinic_id');
            $_SESSION['parent_clinic_id'] = $this->value('clinic_id');
            if( !empty($_SESSION['parent_clinic_id']) ){
                    $replace['account_name'] = ($account_name = $this->get_field($_SESSION['parent_clinic_id'],'clinic','clinic_name')) != ''?strtoupper($account_name):'';
            }
            $url_array = $this->tab_url();
            $replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
            $replace['location'] = $url_array['location'];
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['footer'] = $this->build_template($this->get_template("footer"));    
            $replace['sidebar'] = $this->sidebar();
            $replace['body'] = $this->build_template($this->get_template("clinicTemplate"),$replace);
            $replace['browser_title'] = "Tx Xchange: Clinic List";
            $this->output = $this->build_template($this->get_template("main"),$replace);            
            
        }
        /**
        * Function called to inactive primary clinic and clinics in the account.
        */
        function confirmStatusChangeClinic(){
            $clinic_id = $this->value('clinic_id');                
            $parent_clinic_id = $this->get_field($clinic_id,"clinic","parent_clinic_id");
            if($this->value('confirm') == "yes" ){
                if(is_numeric($clinic_id) && $clinic_id != '0' ){
                    $status = $this->get_field($clinic_id,"clinic","status");
                    $trail_status=$this->get_field($clinic_id,"clinic","trial_status");
                    $trial_date=$this->get_field($clinic_id,"clinic","trial_date");
                    if($trail_status == 1 and ($trial_date !='' or !empty($trial_date))){
                        //activate all clinic subclinic and there users
                        //1 get all clinic or subclinic 
                        //change user status
                        //change clinic status
                        $date=null;
                        $sql="select clinic_id from clinic where clinic_id = '{$clinic_id}' or parent_clinic_id = '{$clinic_id}'";
                        $sqlrow=@mysql_query($sql);
                        $numrow=mysql_num_rows($sqlrow);
                        if($numrow > 0){
                            while($res=mysql_fetch_array($sqlrow)){
                                $this->changeClinicUserStatusTrailToActive($res['clinic_id']);   
                            }
                            
                        }
                        
                        $query = "update clinic set trial_status = null,trial_date='{$date}',status=1 where clinic_id = '{$clinic_id}' or parent_clinic_id = '{$clinic_id}' ";
                        @mysql_query($query);
                        
                        
                    }else{
                        if( $status == "1" ){
                        $newStatus = "2";
                    }
                    elseif( $status == "2" ){
                        $newStatus = "1";
                    }
                    if( $parent_clinic_id == 0 && $status == "1" ){
                        //update all user and clinic
                        $sql="select clinic_id from clinic where clinic_id = '{$clinic_id}' or parent_clinic_id = '{$clinic_id}'";
                        $sqlrow=@mysql_query($sql);
                        $numrow=mysql_num_rows($sqlrow);
                        if($numrow > 0){
                            while($res=mysql_fetch_array($sqlrow)){
                                $this->changeClinicUserStatus($res['clinic_id'],$newStatus);
                            }
                            
                        }
                        $query = "update clinic set status = '{$newStatus}' where clinic_id = '{$clinic_id}' or parent_clinic_id = '{$clinic_id}' ";
                        
                    }
                    else{
                        
                        
                        $this->changeClinicUserStatus($clinic_id ,$newStatus);
                        $query = "update clinic set status = '{$newStatus}' where clinic_id = '{$clinic_id}' or parent_clinic_id = '{$clinic_id}'";
                    }
                    @mysql_query($query);
                    
                    }
                    
                    
                }
               $this->output = "<script language='javascript'> 
                                    parent.parent.location.reload();
                                    //parent.parent.GB_hide();
                                    parent.parent.setTimeout('GB_CURRENT.hide()',1000); 
                                </script>";
               return;
                                
            }
            $status = $this->get_field($clinic_id,"clinic","status");  
            if( $status == "1" ){
                $statusText = "inactivate";
            }
            elseif( $status == "2" ){
                $statusText = "activate";
            }
            $trail_status=$this->get_field($clinic_id,"clinic","trial_status");
            $trial_date=$this->get_field($clinic_id,"clinic","trial_date");
            if($trail_status == 1 and ($trial_date !='' or !empty($trial_date))){
                $statusText = "activate";
            }
            if( $parent_clinic_id == 0 && $status == "1"){
                $replace['message'] = "Are you sure you want to $statusText all the clinics including primary clinic in this account?";
            }
            else{
                $replace['message'] = "Are you sure you want to $statusText selected clinic?";
            }
            $replace['clinic_id'] = $this->value('clinic_id');
            $replace['body'] = $this->build_template($this->get_template("confirmStatusChangeClinic"),$replace);
            $replace['browser_title'] = "Tx Xchange: Clinic List";
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
        /**
        * Function called to inactive primary clinic and clinics in the account.
        */
        function trialStatusChangeClinic(){
            $clinic_id = $this->value('clinic_id');                
            $parent_clinic_id = $this->get_field($clinic_id,"clinic","parent_clinic_id");
            if($this->value('confirm') == "yes" ){
                if(is_numeric($clinic_id) && $clinic_id != '0' ){
                    //if( $parent_clinic_id == 0){
                        $date=date("Y-m-d H:i:s");
                        $sql="select clinic_id from clinic where clinic_id = '{$clinic_id}' or parent_clinic_id = '{$clinic_id}'";
                        $sqlrow=@mysql_query($sql);
                        $numrow=mysql_num_rows($sqlrow);
                        if($numrow > 0){
                            while($res=mysql_fetch_array($sqlrow)){
                                $this->changeClinicUserStatusTrail($res['clinic_id']);   
                            }
                            
                        }
                        
                        $query = "update clinic set trial_status = 1,trial_date='{$date}',status=1 where clinic_id = '{$clinic_id}' or parent_clinic_id = '{$clinic_id}' ";
                        
                    //}
                    @mysql_query($query);
                    
                }
               $this->output = "<script language='javascript'> 
                                    parent.parent.location.reload();
                                    //parent.parent.GB_hide();
                                    parent.parent.setTimeout('GB_CURRENT.hide()',1000); 
                                </script>";
               return;
                                
            }
            
            if( $parent_clinic_id == 0){
                $replace['message'] = "Are you sure you want to change the status of this account and its clinics to Trial status?";
            }
            else{
                $replace['message'] = "Are you sure you want to Trial selected clinic?";
            }
            $replace['clinic_id'] = $this->value('clinic_id');
            $replace['body'] = $this->build_template($this->get_template("trialStatusChangeClinic"),$replace);
            $replace['browser_title'] = "Tx Xchange: Clinic List";
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
        
        /**
         * Function to change the status of Clinic user
         *
         * @access public
         */
         function changeClinicUserStatusTrail($clinicId){
            $date=date("Y-m-d H:i:s");
            $sql="select clinic_user.user_id  as user_id from clinic_user,user where clinic_user.user_id=user.user_id and clinic_user.clinic_id='{$clinicId}' and user.usertype_id=2";
            $res=@mysql_query($sql);
            $numrow=mysql_num_rows($res);
            if($numrow > 0){
                while($row=mysql_fetch_array($res)){
                    $sqluser="update user set trial_status=1,free_trial_date='{$date}',status=1 where user_id=".$row['user_id'];
                    @mysql_query($sqluser);
                                      
                }
                
            }
            return;
            
         } 
        /**
         * Function to change the status of Clinic user
         *
         * @access public
         */
         function changeClinicUserStatus($clinicId,$status){
            $date=date("Y-m-d H:i:s");
            $sql="select clinic_user.user_id  as user_id from clinic_user,user where clinic_user.user_id=user.user_id and clinic_user.clinic_id='{$clinicId}' and user.usertype_id=2";
            $res=@mysql_query($sql);
            $numrow=mysql_num_rows($res);
            if($numrow > 0){
                while($row=mysql_fetch_array($res)){
                    $sqluser="update user set trial_status=null ,free_trial_date=null,status='{$status}' where user_id=".$row['user_id'];
                    @mysql_query($sqluser);
                                      
                }
                
            }
            return;
            
         }
         /**
         * Function to change the status of Clinic user
         *
         * @access public
         */
         function changeClinicUserStatusTrailToActive($clinicId){
            
            $sql="select clinic_user.user_id  as user_id from clinic_user,user where clinic_user.user_id=user.user_id and clinic_user.clinic_id='{$clinicId}' and user.usertype_id=2";
            $res=@mysql_query($sql);
            $numrow=mysql_num_rows($res);
            if($numrow > 0){
                while($row=mysql_fetch_array($res)){
                    $sqluser="update user set trial_status=null,free_trial_date=null,status=1 where user_id=".$row['user_id'];
                    @mysql_query($sqluser);
                                      
                }
                
            }
            return;
            
         }
        /**
         * Function to change the status of Clinic
         *
         * @access public
         */
        function changeClinicStatus(){
            $clinic_id = $this->value('clinic_id');
            $this->set_StatusActiveInActive($clinic_id, 'clinic_id', 'clinic');
            $location = $this->redirectUrl("listAccountClinic");
            header("location:$location");
            exit();
        }
		/**
		 * Function to Add or Edit the patient Details
		 *
		 * @access public
		 */
		function SystemAdminEditPatients()
		{       
			$this->form_array = $this->populate_form_array();	
			$replace['clinic_id'] = $this->value('clinic_id');
            $url_array = $this->tab_url();
            $replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
			$patient_id = $this->value('patient_id')!=session_id()?$this->value('patient_id'):"";
			$option = $this->value('option');
			$replace['patient_id'] = $patient_id;
			$replace['footer'] = $this->build_template($this->get_template("footer"));	
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();
                        if($this->is_corporate($this->value('clinic_id'))==1){
                            $replace['corporate']='none';
                         } else {
                            $replace['corporate']='block';
                        }  
                        
                        if($this->value('clinic_id') == EHSCLINICID || EHSCLINICID == '') {
                                $replace['SHOWEHS'] = "display:block";
                        } else {
                                $replace['SHOWEHS'] = "display:none";
                        }
                        
                        //$clinicId = $this->get_clinic_info($user_id,'clinic_id');
                        
                        
			
			// In case of Add
			if(empty($patient_id))
			{
                                
                          /*
                          @date: 13th october 2011
                          @description: 
                                  Check for the E-health service
                                  Functionality added on 13th october as per UC 2.7.4              
                                  It will check the E health service turn /off condition. If the E health service is off then checkbox will become disabled.
                                  If the E- health service is enabled then it will check the patient subscription. If the patient is subscribed then it will be in disable mode 
                                  else in enable mode.
                        */
                        if($this->value('clinic_id')!= '' && $this->value('clinic_id') > 0) {
                                $sqlEhealth = "SELECT subs_status FROM clinic_subscription WHERE subs_clinic_id  = " . $this->value('clinic_id');
                                $queryEhealth = $this->execute_query($sqlEhealth);
                                $numEhealthRow = $this->num_rows($queryEhealth);
                                if($numEhealthRow!= '0') {
                                        $valueEhealth = $this->fetch_object($queryEhealth);
                                        if($valueEhealth->subs_status == 0) {
                                            $replace['ehsEnable'] = 'disabled';
                                             $replace['ehsDisable'] = '0';
                                        } else {
                                            $replace['ehsEnable'] = '';
                                             $replace['ehsDisable'] = '1';
                                                
                                        }
                                } else {
                                        $replace['ehsEnable'] = 'disabled';
                                         $replace['ehsDisable'] = '0';

                                }
                        } else {
                                $replace['ehsEnable'] = 'disabled';
                                $replace['ehsDisable'] = '0';

                        }

                         if($_REQUEST['ehsEnable'] == '' && $_REQUEST['Submit']!= '')
                                $replace['ehsEnable'] = '';  
                        //Ende here				

                                // Populate BLANK FORM fields
				$this->assignValueToArrayFields($this->form_array, '', '', &$replace);
				$replace['operationTitle'] = 'Add';
				$replace['browser_title'] = 'Tx Xchange: Add Patient';
				$replace['buttonName'] = 'Add Patient';
				
				$status_arr['patientStatus'] =  array( '1' => "Current");
				$replace['patient_status_options'] = $this->build_select_option($status_arr['patientStatus'], $replace['patient_status']);
				
				if(($replace['newPassword']) == ''){
					$replace['newPassword'] = $this->generateCode(9);
				}
				//$replace['newPasswordLabel'] = '<div align="right" onMouseOver="help_text(this, \'You can also type your password\')"><strong>New Password :</strong> </div>';
				//$replace['newPasswordField'] = '<input tabindex="7" type="text" name="newPassword" onMouseOver="help_text(this, \'You can also type your password\')" value="'.$replace['newPassword'].'" />';
				$replace['newPasswordLabel'] = '';
                $replace['newPasswordField'] = '';
				
				// Because of passing session_id as patient_id
				$patient_id = session_id();
				$clinic_id = $this->value('clinic_id');
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
							$updateArr['password'] = $this->value('patient_last_name').'01';

                                                        //Modified on 13th oct for E-health service                                
                                                       /* if($this->value('ehsEnable') == 1)
			                           	        $updateArr['ehs'] = '1';
			                                else
				                                $updateArr['ehs'] = '0';*/
                                                        

                                                        if($_REQUEST['ehsDisable']== "0") {
                                                                $updateArr['ehs'] = '1';
                                                        } elseif($_REQUEST['ehsEnable'] == "1") {
                                                                  $updateArr['ehs'] = '1';
                                                        } else {
                                                                  $updateArr['ehs'] = '0';
                                                        }   //print_r($updateArr);exit;
                                                                                //End here
							
                            // Encrypt data
                            //$encrypt_field = array('address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
                            //$updateArr = $this->encrypt_field($updateArr, $encrypt_field);
                            // End Encryption
                            
							$result = $this->insert('user',$updateArr);
							$insertedId = $this->insert_id();
							
							/**
							 *  Replace Records from temprory table to original Table
							 *  1.  From tmp_therapist_patient  to  therapist_patient
							 *  2.  From tmp_patient_reminder  to  patient_reminder
							 */
							$clinicUserArr = array('clinic_id' => $this->value('clinic_id'), 'user_id' => $insertedId);
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
							//clinic name to get it included in email. //part of 2.4.4 UC4
                            $clinicName=html_entity_decode($this->get_clinic_name($this->value('clinic_id')), ENT_QUOTES, "UTF-8");
							//have the HTML content
							$clinic_channel=$this->getchannel($this->value('clinic_id'));
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
											'clinic_name' => $clinicName,
                                            'business_url'=>$business_url,
                                            'support_email'=>$support_email,
                            				'name'=>$this->userInfo("name_first",$insertedId)
							);
																	
							/*  Mail Section  */
							//$user_id = $this->userInfo('user_id');
                        	$clinic_type = $this->getUserClinicType($insertedId);
                        	
                        	if( $clinic_channel == 1){
                        		$message = $this->build_template("mail_content/plpto/create_new_patient_mail_plpto.php",$data);
                        	}
                        	else{
                        		$message = $this->build_template("mail_content/wx/create_new_patient_mail_plpto.php",$data);
                        	}	
								$subject = "Your ".$clinicName." Health Record";							
								$to = $updateArr['username'];
								
								
								// To send HTML mail, the Content-type header must be set
								$headers  = 'MIME-Version: 1.0' . "\n";
								$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
								
								// Additional headers
								//$headers .= "From: " .$clinicName. "<support@txxchange.com>" . "\n";
								//$returnpath = '-fsupport@txxchange.com';
								
								if( $clinic_channel == 1){
								    $headers .= "From: ".$this->setmailheader($clinicName). " <".$this->config['email_tx'].">" . "\n";
								    $returnpath = "-f".$this->config['email_tx'];
								    }else{
								    $headers .= "From: ".$this->setmailheader($clinicName). " <".$this->config['email_wx'].">" . "\n";
								    $returnpath = '-f'.$this->config['email_wx']; 
								    }  
    
								// Mail it
								
								mail($to, $subject, $message, $headers, $returnpath);
							$url = $this->redirectUrl('clinic_patient').'&msg=patient_added';
							header("location:$url");
                            exit();
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
                if( !empty($_SESSION['parent_clinic_id']) ){
                        $replace['account_name'] = ($account_name = $this->get_field($_SESSION['parent_clinic_id'],'clinic','clinic_name')) != ''?strtoupper($account_name):'';
                }
                $url_array = $this->tab_url();
                
              //  print_r($_REQUEST);
                 $countryArray = $this->config['country'];
	             $replace['country']=implode("','",$countryArray); 
	        
			 if( isset($_REQUEST['clinic_country']) && $_REQUEST['clinic_country']!='')
                
                {
                	
                	    
		           $replace['patient_country_options'] = $this->build_select_option($countryArray, $_REQUEST['clinic_country']);
		            if($_REQUEST['clinic_country']=='US') {
		            $stateArray = array("" => "Choose State...");
		            $stateArray = array_merge($stateArray,$this->config['state']);              
		            $replace['patient_state_options'] = $this->build_select_option($stateArray, $_REQUEST['patient_state']);         
		            }
		           
		            else if($_REQUEST['clinic_country']=='CAN') {
		            $stateArray = array("" => "Choose Province...");
		            $stateArray = array_merge($stateArray,$this->config['canada_state']);
		            $replace['patient_state_options'] = $this->build_select_option($stateArray, $_REQUEST['patient_state']);         
		              }            	
                }
                
                if(!isset($_REQUEST['clinic_country']))
               		 {                    	   
		        
                $replace['patient_country_options'] = $this->build_select_option($countryArray, $row['country']);
		            
		            
		            
		           
	            if($row['country']=='US') {
	            $stateArray = array("" => "Choose State...");
	            $stateArray = array_merge($stateArray,$this->config['state']);              
	            $replace['patient_state_options'] = $this->build_select_option($stateArray, $replace['patient_state']);         
	            }
	           
	            else if($row['country']=='CAN') {
	            $stateArray = array("" => "Choose Province...");
	            $stateArray = array_merge($stateArray,$this->config['canada_state']);
	            $replace['patient_state_options'] = $this->build_select_option($stateArray, $replace['patient_state']);         
	            }  
                
            
            }
	        
	       
                $replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
                $replace['body'] = $this->build_template($this->get_template("accountAdminAddPatient"),$replace);
			// In case of Edit				
			}else{
				$replace['operationTitle'] = 'Edit';
				$replace['buttonName'] = 'Save Patient';
				$replace['browser_title'] = 'Tx Xchange: Edit Patient';
				$replace['newPasswordLabel'] = '<div align="right" ><strong>Password :</strong> </div>';
				
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
                $replace['confirmPasswordTextField'] = '<div align="right" ><strong>Confirm Password :</strong> </div>';
                
				$clinic_id = $this->value('clinic_id');
				$replace['editReminderlink'] = $patient_id;
				if($option == 'update'){

                                        /*
                                          @creation date: 13th october 2011
                                          @description: 
                                                  Check for the E-health service
                                                  Functionality added on 13th october as per UC 2.7.4              
                                                  It will check the E health service turn /off condition. If the E health service is off then checkbox will become disabled.
                                                  If the E- health service is enabled then it will check the patient subscription. If the patient is subscribed then it will be in disable mode 
                                                  else in enable mode.
                                          @modification date: 21 October 2011
                                        
                                        */ 
                                        
                                        //echo "test".$clinicIdtest =   $this->get_clinic_info($patient_id,'clinic_id'); 
                                        $clinicId =   $this->value('clinic_id');                
                                        $replace['clinicId'] = $clinicId;
                                        $replace['userId'] = $patient_id;
                                        $ehsAction = $_REQUEST['action'];
                                        $replace['ehsAction'] = $ehsAction;
                                        //Query to check E health service subscription On or Off 
                                        if(!empty($clinicId) && $clinicId > 0) {                                         
                                                $sqlEhealth = "SELECT subs_status FROM clinic_subscription WHERE subs_clinic_id  = " . $clinicId;
                                                $queryEhealth = $this->execute_query($sqlEhealth);
                                                $numEhealthRow = $this->num_rows($queryEhealth);
                                                if($numEhealthRow!= '0') {
                                                        $valueEhealth = $this->fetch_object($queryEhealth);
                                                        if($valueEhealth->subs_status == 0) {
                                                            $replace['ehsEnable'] = 'disabled';
                                                            $replace['ehsDisable'] = '0';
                                                        } else {
                                                                //Query to check whethet patient has enabled e health service or not                                                        
                                                                $sqEhscheck = "SELECT ehs FROM user where user_id = {$patient_id}"; 
                                                                $queryEhs= $this->execute_query($sqEhscheck);
                                                                $numqueryEhs = $this->num_rows($queryEhs);
                                                                 if($numqueryEhs!= '0') {
                                                                        $valueehsService = $this->fetch_object($queryEhs);
                                                                        if($valueehsService->ehs == 0 || $valueehsService->ehs == '') { 
                                                                            $replace['ehsEnable'] = '0';
                                                                             $replace['subs_status'] = '0';
                                                                             $replace['subscrp_id'] = '0';
                                                                              $replace['ehsDisable'] = '1';
                                                                           
                                                                        } else {
                                                                                //Query to check Patient payment subscription                                                                        
                                                                               /* $sqlPaymentSubscription = "SELECT subs_status,subscription_title, user_subs_id ,paymentType,subs_end_datetime, now() as todayDate
                                                                                                                FROM patient_subscription where user_id = {$patient_id}"; */
$sqlPaymentSubscription="SELECT subs_status,subscription_title, user_subs_id,paymentType,subs_end_datetime, now() as todayDate
                                     FROM patient_subscription 
                                     WHERE user_id={$patient_id} AND ((subs_status='1' AND subs_end_datetime > now())
                                     OR (subs_status='2' AND subs_end_datetime > now()))";
                                                                                $querysubscription = $this->execute_query($sqlPaymentSubscription);
                                                                                $numquerysubscription = $this->num_rows($querysubscription);
                                                                                if($numquerysubscription!= '0') {

                                                                                        $valuesubscription = $this->fetch_object($querysubscription);
                                                                                       // $subscription_end_date=strtotime($valuesubscription->subs_end_datetime);
                                                                                        //$todayDate = strtotime($valuesubscription->todayDate);                                                                        
                                                                                        $replace['subs_status'] = $valuesubscription->subs_status;
                                                                                        $replace['subscrp_id'] = $valuesubscription->user_subs_id;
                                                                               /* if($valuesubscription->paymentType > 0) {
                                                if($subscription_end_date > $todayDate) {
                                                                        
                                                        $replace['paymentType'] = $valuesubscription->paymentType;

                                                } else {
                                                        $replace['paymentType'] = '0';
                                                }

                                        }*/
        $replace['paymentType'] = $valuesubscription->paymentType;
                                                                                        
                                                                                } else {
                                                                                        $replace['subs_status'] = '0';
                                                                                        $replace['subscrp_id'] = '0';

                                                                                        //$replace['subs_status'] = '1';
                                                                                        //$replace['subscrp_id'] = '1';
                                                                                        
                                                          
                                                                                }

                                                                                $replace['ehsEnable'] = 'checked';
                                                                                $replace['ehsDisable'] = '1';
                                                }


                                                                 }
                                                                
                                                        }
                                                } else {
                                                        $replace['ehsEnable'] = 'disabled';
                                                        $replace['ehsDisable'] = '0';
                                                }
                                        } else {
                                             $replace['ehsEnable'] = 'disabled';
                                             $replace['ehsDisable'] = '0';

                                        }

                                        //E-health service End here
					$this->validateForm();	
					if($this->error == ""){
						//  Populate FieldArray from FormArray
						
						if(!empty($patient_id)){
							$updateArr = $this->assignValueToArrayFields($this->form_array, '', '2', '', 'insert');
							
							$changedPassword = $this->value('newPassword');
							
							// If user doesnot change the existing password, password field removed from array
							if(empty($changedPassword)){
								$updateArr = $this->removeFromArray($updateArr, array('password'=>''));
							}
                                                        //echo "<pre>";print_r($_REQUEST);exit;
                                                         //E health service
                                                       /* if($this->value('ehsEnable') == 1)
                                                                $updateArr['ehs'] = '1';
                                                         else
	                                                        $updateArr['ehs'] = '0';*/

                                                        //$updateArr['ehs'] = '1';

                                                        //print_r($updateArr);exit;
                                                        if($_REQUEST['ehsDisable']== "0") {
                                                                $updateArr['ehs'] = '1';
                                                       } elseif($_REQUEST['ehsEnable'] == "1") {
                                                                  $updateArr['ehs'] = '1';
                                                        } else {
                                                                  $updateArr['ehs'] = '0';
                                                        }   
                                                        //print_r($updateArr);exit;
              
                //End here
							
                            // Encrypt data
                            //$encrypt_field = array('address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
                            //$updateArr = $this->encrypt_field($updateArr, $encrypt_field);
                            // End Encryption
							
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
							$url = $this->redirectUrl("clinic_patient").'&msg=patient_updated';
							header("location:$url");
                            exit();
						}
					}else{
						//Show errors and populate FORM fields from $_POST.
						
						$this->assignValueToArrayFields($this->form_array, '', '', &$replace, '0');
						$replace['error'] = $this->error;
					}
				}
                else{
					
                                        
                                         /*
                                          @creation date: 13th october 2011
                                          @description: 
                                                  Check for the E-health service
                                                  Functionality added on 13th october as per UC 2.7.4              
                                                  It will check the E health service turn /off condition. If the E health service is off then checkbox will become disabled.
                                                  If the E- health service is enabled then it will check the patient subscription. If the patient is subscribed then it will be in disable mode 
                                                  else in enable mode.
                                          @modification date: 21 October 2011
                                        
                                        */ 
                                        
                                        //$clinicId =   $this->get_clinic_info($patient_id,'clinic_id');
                                        //echo "shailesh".$clinicIdtest =   $this->get_clinic_info($patient_id,'clinic_id');                 
                                        $clinicId =   $this->value('clinic_id');                
                                        $replace['clinicId'] = $clinicId;
                                        $replace['userId'] = $patient_id;
                                        
                                        $ehsAction = $_REQUEST['action'];
                                        $replace['ehsAction'] = $ehsAction;
                                        //Query to check E health service subscription On or Off 
                                        if(!empty($clinicId) && $clinicId > 0) {                                       
                                                $sqlEhealth = "SELECT subs_status FROM clinic_subscription WHERE subs_clinic_id  = " . $clinicId;
                                                $queryEhealth = $this->execute_query($sqlEhealth);
                                                $numEhealthRow = $this->num_rows($queryEhealth);
                                                if($numEhealthRow!= '0') {
                                                        $valueEhealth = $this->fetch_object($queryEhealth);
                                                        if($valueEhealth->subs_status == 0) {
                                                            $replace['ehsEnable'] = 'disabled';
                                                                $replace['ehsDisable'] = '0';
                                                        } else {
                                                                //Query to check whethet patient has enabled e health service or not                                                        
                                                                $sqEhscheck = "SELECT ehs FROM user where user_id = {$patient_id}"; 
                                                                $queryEhs= $this->execute_query($sqEhscheck);
                                                                $numqueryEhs = $this->num_rows($queryEhs);
                                                                 if($numqueryEhs!= '0') {
                                                                        $valueehsService = $this->fetch_object($queryEhs);
                                                                        if($valueehsService->ehs == 0 || $valueehsService->ehs == '') { 
                                                                            $replace['ehsEnable'] = '0';
                                                                             $replace['subs_status'] = '0';
                                                                             $replace['subscrp_id'] = '0';
                                                                                $replace['ehsDisable'] = '1';
                                                                           
                                                                        } else {
                                                                                //Query to check Patient payment subscription                                                                        
                                                                                /*$sqlPaymentSubscription = "SELECT subs_status,subscription_title, user_subs_id ,paymentType,subs_end_datetime, now() as todayDate
                                                                                                                FROM patient_subscription where user_id = {$patient_id}"; */

$sqlPaymentSubscription="SELECT subs_status,subscription_title, user_subs_id,paymentType,subs_end_datetime, now() as todayDate
                                     FROM patient_subscription 
                                     WHERE user_id={$patient_id} AND ((subs_status='1' AND subs_end_datetime > now())
                                     OR (subs_status='2' AND subs_end_datetime > now()))";
                                                                                $querysubscription = $this->execute_query($sqlPaymentSubscription);
                                                                                $numquerysubscription = $this->num_rows($querysubscription);
  if($numquerysubscription!= '0') {

             $valuesubscription = $this->fetch_object($querysubscription);
//$subscription_end_date =  strtotime($valuesubscription->subs_end_datetime);
                                   //$todayDate = strtotime($valuesubscription->todayDate);                                                                                  
                                                                                        $replace['subs_status'] = $valuesubscription->subs_status;
                                                                                        $replace['subscrp_id'] = $valuesubscription->user_subs_id;
$replace['paymentType'] = $valuesubscription->paymentType;                                                
                                                                                } else {
                                                                                        $replace['subs_status'] = '0';
                                                                                        $replace['subscrp_id'] = '0';

                                                                                        //$replace['subs_status'] = '1';
                                                                                        //$replace['subscrp_id'] = '1';
                                                                                        
                                                                                        
                                                        

                                                                                }

                                                                                $replace['ehsEnable'] = 'checked';
                                                                                $replace['ehsDisable'] = '1';
                                                }


                                                                 }
                                                                
                                                        }
                                                } else {
                                                        $replace['ehsEnable'] = 'disabled';
                                                        $replace['ehsDisable'] = '0';
                                                }
                                        } else {
                                                $replace['ehsEnable'] = 'disabled';
                                                $replace['ehsDisable'] = '0';
                                        
                                        }

                                        


                                        //E-health service End here

                                        //Populate FORM fields from database.
					
					$query = "select * from user where user_id='".$patient_id."'";
					$rs = $this->execute_query($query);
					$row = $this->populate_array($rs);
					
                    // Encrypt data
                    $encrypt_field = array('name_title','name_first','name_last','password','address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
                    $row = $this->decrypt_field($row, $encrypt_field);
                    // End Encryption
                        
					// Fetch Replace array from row
					// populate FormArray from FieldArray
					$this->assignValueToArrayFields($this->form_array, $row, '1', &$replace);
				}
				$replace['patient_status_options'] = $this->build_select_option($this->config['patientStatus'], $replace['patient_status']);
                
                $stateArray = array("" => "Choose State...");
                $stateArray = array_merge($stateArray,$this->config['state']);
                //$replace['patient_state_options'] = $this->build_select_option($stateArray, $replace['patient_state']);
                $replace['patient_title_options'] = $this->build_select_option(array_merge(array('' => 'choose...'), $this->config['title']), $replace['patient_title']);
                $replace['patient_suffix_options'] = $this->build_select_option(array_merge(array('' => 'choose...'), $this->config['suffix']), $replace['patient_suffix']);
                if( !empty($_SESSION['parent_clinic_id']) ){
                        $replace['account_name'] = ($account_name = $this->get_field($_SESSION['parent_clinic_id'],'clinic','clinic_name')) != ''?strtoupper($account_name):'';
                }
                $url_array = $this->tab_url();
                $replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);

                
                $countryArray = $this->config['country'];
	        $replace['country']=implode("','",$countryArray); 
	        
	        
	       
	        
	        
	//print_R($_REQUEST);        
	 	if( isset($_REQUEST['clinic_country']) && $_REQUEST['clinic_country']!='')
                
                {
                	
                  
	            $replace['patient_country_options'] = $this->build_select_option($countryArray, $_REQUEST['clinic_country']);
	            
	            if($_REQUEST['clinic_country']=='US') {
	            $stateArray = array("" => "Choose State...");
	            $stateArray = array_merge($stateArray,$this->config['state']);              
	            $replace['patient_state_options'] = $this->build_select_option($stateArray, $_REQUEST['patient_state']);         
	            }
	           
	            else if($_REQUEST['clinic_country']=='CAN') {
	            $stateArray = array("" => "Choose Province...");
	            $stateArray = array_merge($stateArray,$this->config['canada_state']);
	            $replace['patient_state_options'] = $this->build_select_option($stateArray, $_REQUEST['patient_state']);         
              }            										 
                }
                
                if(!isset($_REQUEST['clinic_country']))

                {                    	   
		        
                    $replace['patient_country_options'] = $this->build_select_option($countryArray, $row['country']);
		            
	            if($row['country']=='US') {
	            $stateArray = array("" => "Choose State...");
	            $stateArray = array_merge($stateArray,$this->config['state']);              
	            $replace['patient_state_options'] = $this->build_select_option($stateArray, $replace['patient_state']);         
	            }
	           
	            else if($row['country']=='CAN') {
	            $stateArray = array("" => "Choose Province...");
	            $stateArray = array_merge($stateArray,$this->config['canada_state']);
	            $replace['patient_state_options'] = $this->build_select_option($stateArray, $replace['patient_state']);         
	            } else{
	            $stateArray = array("" => "Choose State...");
                $stateArray = array_merge($stateArray,$this->config['state']);              
                $replace['patient_state_options'] = $this->build_select_option($stateArray, $replace['patient_state']);   
	            }
                
            
            }
               
                
                $replace['body'] = $this->build_template($this->get_template("accountAdminEditPatient"),$replace);
			}
			
			
			$this->output = $this->build_template($this->get_template("main"),$replace);					
			
			$updateArr = $this->assignValueToArrayFields($this->form_array, '', '2');
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
							'patient_title' 		=> 'name_title',
							'patient_first_name' 		=> 'name_first',
							'patient_last_name' 		=> 'name_last',
							'patient_suffix' 		=> 'name_suffix',
							'patient_email' 		=> 'username',
							'newPassword'			=> 'password',
							'patient_cemail'		=> '',
							'patient_phone1'		=> 'phone1',
							'patient_phone2'		=> 'phone2',
							'patient_address'		=> 'address',
							'patient_address2'		=> 'address2',
							'patient_city'			=> 'city',
							'patient_state'			=> 'state',
							'clinic_country'		=> 'country',
							'patient_zip' 			=> 'zip',
							'patient_status'		=> 'status',
							'refering_physician'		=> 'refering_physician'
						);
			return $arr;			
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
			//print_r($allowchar);
            // first name validation
			$objValidationSet->addValidator(new StringMinLengthValidator('patient_first_name', 1, "First Name cannot be empty",$this->value('patient_first_name')));								
			$objValidationSet->addValidator(new AlphabeticalOnlyValidator('patient_first_name',$allowchar,"Please enter valid characters in First Name",$this->value('patient_first_name')));
			
			// last name validation
			$objValidationSet->addValidator(new StringMinLengthValidator('patient_last_name', 1,"Last Name cannot be empty",$this->value('patient_last_name')));
			$objValidationSet->addValidator(new AlphabeticalOnlyValidator('patient_last_name',$allowchar,"Please enter valid characters in last Name",$this->value('patient_last_name')));
			
			// email validation
			$objValidationSet->addValidator(new StringMinLengthValidator('patient_email', 1,"Email cannot be empty",$this->value('patient_email')));
			$objValidationSet->addValidator(new EmailValidator('patient_email',"Invalid email address",$this->value('patient_email')));
			

			
if ($this->value('practitioner_type') == '')
				{
					
					$objValidationErr = new ValidationError('practitioner_type',"Please choose Provider type");
					$objValidationSet->addValidationError($objValidationErr);
				}
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
			$objValidationSet->addValidator(new AlphabeticalOnlyValidator('patient_city',array(' ',',','.'),"Please enter valid characters in city",$this->value('patient_city')));
			
			// zip code validation
			//$objValidationSet->addValidator(new AlphanumericOnlyValidator('patient_zip', array(' '),"Please enter only alphanumeric values in zip",$this->value('patient_zip')));
			//$objValidationSet->addValidator(new StringMinMaxLengthValidator('patient_zip', 5, 7, "Zip code should be 5 to 7 alphanumeric characters only",$this->value('patient_zip')));
		/*if($this->value('clinic_country')=='CAN'){
                $objValidationSet->addValidator(new  AlphanumericOnlyValidator('patient_zip', array(' '), "Zip code should be of alphanumeric characters only",$this->value('patient_zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('patient_zip', 6,7, "Zip code should be  6 to 7 alphanumeric characters only",$this->value('patient_zip')));        
               }else{
                $objValidationSet->addValidator(new  NumericOnlyValidator('patient_zip', null, "Zip code should be of numeric characters only",$this->value('patient_zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('patient_zip', 5,5, "Zip code should be  5 numeric characters only",$this->value('patient_zip')));
            }*/
			
			//$objValidationSet->addValidator(new  StringMinLengthValidator('newPassword', 6, "Password cannot be empty and should be of 6 characters in length",$this->value('newPassword')));
            
			if( $form_type == 'add' ){
                // confirm email validation
                $objValidationSet->addValidator(new StringMinLengthValidator('patient_cemail', 1,"Confirm emial cannot be empty",$this->value('patient_cemail')));            
                $objValidationSet->addValidator(new EmailValidator('patient_cemail',"Invalid Confirm email address",$this->value('patient_cemail')));                
                
                // matching email and confirm email			
			    $arrFieldNames = array("patient_email","patient_cemail");
			    $arrFieldValues = array($_POST["patient_email"],$_POST["patient_cemail"]);
			    $objValidationSet->addValidator(new  IdenticalValuesValidator($arrFieldNames, "Email and  confirm email does not match",$arrFieldValues));
                // for password while adding patient. password cannot be empty
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
		 * Function to get Patient listing for a clinic.
		 *
		 * @access public
		 */
		function systemAdminClinic(){
			
			$this->form_array = $this->populate_clinic_form_array();
			$replace = array();
			// set template variables
			$replace['browser_title'] = 'Tx Xchange: Clinic Details';
			$replace['footer'] = $this->build_template($this->get_template("footer"));
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();
			$replace['clinic_id'] = $this->value('clinic_id');
            $replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$replace);
			
			// sql query
			$query = "select * from clinic where clinic_id='".$this->value('clinic_id')."'";
			
			$rs = $this->execute_query($query);
			$row = $this->populate_array($rs);

			// Fetech Replace array from row
			$this->assignValueToArrayFields($this->form_array, $row, '1', &$replace);
			
			$replace['commaseprator'] = (!empty($replace['clinic_address2'])) ? '<br/>': '';
			$replace['body'] = $this->build_template($this->get_template("clinic_details"),$replace);
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}


		function excelAttachment() {
			ob_start();
			
			$result  = mysql_query("SELECT  a.clinic_name,count(*)as noc,a.creationDate from clinic as a left join clinic_user b on a.clinic_id=b.clinic_id group by a.clinic_id" );
			$numfields = mysql_num_fields($result);
			
			if($excel==false)	
				echo $excel->error;

			$excel=new ExcelWriter("clinicSheet.xls");

			$myArr=array("Account Name","No of Users","Creation Date");
			$excel->writeLine($myArr);

			while($record_row=mysql_fetch_assoc($result)) {
				$excel->writeLine($record_row);
			}
			
			$excel->close();
			echo "data is write into myXls.xls Successfully.";

		}
		/**
		 * Function to match database table fields name with form fields name
		 *
		 * @return String array
		 * @access public
		 */		
		function populate_clinic_form_array()
		{
			$arr = array(
							'clinic_name' 		=> 'clinic_name',
							'clinic_address' 	=> 'address',
							'clinic_address2' 	=> 'address2',
							'clinic_city' 		=> 'city',
							'clinic_state' 		=> 'state',
							'clinic_zip' 		=> 'zip',
				                        'clinic_phone'          => 'phone',
							'clinic_country'        => 'country',
                                                         'corporate_ehs'        =>'corporate_ehs'   
					);
			return $arr;			
		}
		/**
		 * Function to Edit Clinic Details.
		 *
		 * @access public
		 */
		function systemEditClinicDetails()
		{
			$this->form_array = $this->populate_clinic_form_array();
			$replace = array();
			$option = $this->value('option');
			$replace['footer'] = $this->build_template($this->get_template("footer"));	
			$replace['browser_title'] = 'Tx Xchange: Clinic Details';
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();
			$replace['clinic_id'] = $this->value('clinic_id');
			
			// When user modify the clinic details
			if($option == 'update') {
				$this->validateClinicForm();
				if($this->error == ""){
					$this->assignValueToArrayFields($this->form_array);
					//  Populate FieldArray from FormArray
					$updateArr = $this->assignValueToArrayFields($this->form_array, '', '2', '', 'update');
					$where = " clinic_id = ".$this->value('clinic_id');
					
					$result = $this->update('clinic',$updateArr,$where);
					header("location:index.php?action=systemAdminClinic&clinic_id=".$this->value('clinic_id'));
					exit();
				}else{
					$this->assignValueToArrayFields($this->form_array, '', '', &$replace, '0');
					$replace['error'] = $this->error;
				}
			// When user view the current clinic details in Edit mode				
			} else {
				$query = "select * from clinic where clinic_id='".$this->value('clinic_id')."'";
				$rs = $this->execute_query($query);
				$row = $this->populate_array($rs);
				// Fetch Replace array from row
				// populate FormArray from FieldArray
				$this->assignValueToArrayFields($this->form_array, $row, '1', &$replace);
			}
			
			$stateArray = array("" => "Choose State...");
			$stateArray = array_merge($stateArray,$this->config['state']);
			$replace['stateOptions'] = 	$this->build_select_option($stateArray, $replace['clinic_state']);
			$replace['body'] = $this->build_template($this->get_template("clinic_details"),$replace);
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}
		/**
		 * Function to validate the patient credentials like name address email etc.
		 *
		 * @access public
		 */		
		function validateClinicForm()
		{

			// creating validation object			
			$objValidationSet = new ValidationSet();
			
			// validate clinic name
			$objValidationSet->addValidator(new StringMinLengthValidator('clinic_name', 1, "Clinic Name cannot be empty",$this->value('clinic_name')));					
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('clinic_name',null,"Please enter valid characters in Clinic Name",$this->value('clinic_name')));
			
			// validating clinic address line 1
			//$objValidationSet->addValidator(new StringMinMaxLengthValidator('clinic_address', 0,50, "Address cannot be more than 50 characters",$this->value('clinic_address')));			
			//$objValidationSet->addValidator(new StringMinLengthValidator('clinic_address', 1,"Address cannot be empty",$this->value('clinic_address')));
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('clinic_address', null,"Please enter valid characters in address",$this->value('clinic_address')));
			
			// validating clinic address line 2
			$objValidationSet->addValidator(new StringMinMaxLengthValidator('clinic_address2',0,50, "Address line 2 cannot be more than 50 characters",$this->value('clinic_address2')));			
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('clinic_address2', null,"Please enter valid characters in address line 2",$this->value('clinic_address2')));

			// validating city name
			//$objValidationSet->addValidator(new StringMinLengthValidator('clinic_city', 1,"City cannot be empty",$this->value('clinic_city')));
			$objValidationSet->addValidator(new StringMinMaxLengthValidator('clinic_city',0,20, "City name cannot be more than 20 characters",$this->value('clinic_city')));			
			$objValidationSet->addValidator(new AlphabeticalOnlyValidator('clinic_city', array(' ',',','.'),"Please enter valid characters in city",$this->value('clinic_city')));
			
			// validating state name
			//$objValidationSet->addValidator(new StringMinLengthValidator('clinic_state', 2,"State cannot be empty",$this->value('clinic_state')));
			
			// validating zip code
			//$objValidationSet->addValidator(new AlphanumericOnlyValidator('clinic_zip', array(' '),"Please enter only alphanumeric values in zip",$this->value('clinic_zip')));
			//$objValidationSet->addValidator(new StringMinMaxLengthValidator('clinic_zip', 5, 7, "Zip code should be 5 to 7 alphanumeric characters",$this->value('clinic_zip')));
		  /*if($this->value('clinic_country')=='CAN'){
                $objValidationSet->addValidator(new  AlphanumericOnlyValidator('clinic_zip', array(' '), "Zip code should be of alphanumeric characters only",$this->value('clinic_zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('clinic_zip', 6,7, "Zip code should be  6 to 7 alphanumeric characters only",$this->value('clinic_zip')));        
           }else{
                $objValidationSet->addValidator(new  NumericOnlyValidator('clinic_zip', null, "Zip code should be of numeric characters only",$this->value('clinic_zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('clinic_zip', 5,5, "Zip code should be  5 numeric characters only",$this->value('clinic_zip')));
            }*/
			$objValidationSet->addValidator(new  StringMinMaxLengthValidator('clinic_zip', 5,7, "Zip code should be  5 to 7 characters only",$this->value('clinic_zip')));
			// validating phone
           // $objValidationSet->addValidator(new StringMinLengthValidator('clinic_phone', 1, "Phone cannot be empty",$this->value('clinic_phone')));
            
            /*
				Check clinic name for duplicacy
			*/
			
			/*$clinic_id = $this->value('clinic_id');
			
			$clinic_name = $this->value('clinic_name');
			$queryClinic = "SELECT clinic_id FROM clinic WHERE clinic_name = '".$clinic_name."' AND status <> 3 AND clinic_id <> ".$clinic_id;				
			
			
			$result = $this->execute_query($queryClinic);
				
			//if record found that means clinic name not unique else it is unique
			if ($this->num_rows($result) != 0)
			{
				$objValidationErr = new ValidationError('clinic_name',"Clinic Name : exists in the system. Please choose another.");
				$objValidationSet->addValidationError($objValidationErr);
			}							
				*/
			
			/* End of checking for duplicacy */
			
			
			
			$objValidationSet->validate();		
			
			if ($objValidationSet->hasErrors())
			{
				/*
				$clinic_zip = $this->value('clinic_zip');
				if(!empty($clinic_zip)){
					$arrayFields = array("clinic_name","clinic_address","clinic_address2","clinic_city","clinic_state", "clinic_zip");
				}else{
					$arrayFields = array("clinic_name","clinic_address","clinic_address2","clinic_city", "clinic_state");
				}
				*/
				
				$arrayFields = array('clinic_name','clinic_address','clinic_address2','clinic_city','clinic_state', 'clinic_zip','clinic_phone');
				
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
		 * Set status Active for the user.
		 *
		 * @access public
		 */
		function activeUserSystem()
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
					$queryUpdate = "UPDATE user SET status = 1, free_trial_date=null, trial_status=null WHERE user_id = ". $user_id;
					$this->execute_query($queryUpdate);	
                     $msg = 4; 
					$url = $this->redirectUrl("user_listing&msg=3");
					header("location:".$url);
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
		function inactiveUserSystem()
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
								$queryUpdate = "UPDATE user SET status = 2,trial_status=null,free_trial_date=null WHERE user_id = ". $user_id;	
								
								$this->execute_query($queryUpdate);	
							}
							else 
							{
								//$msg = 1;
								//There exist relationship
								//$err = "There are patient(s) who are associated with this therapist, please remove the association and do the action";
                               $queryUpdate = "UPDATE user SET status = 2,trial_status=null,free_trial_date=null WHERE user_id = ". $user_id;	
							   $this->execute_query($queryUpdate);	 
                               $msg = 4; 
								
							}
						
						}
						
					}
					else 
					{
						$msg = 2;
						//No user exist with this id
						$err = "Not a valid user";
						
					}
					$url = $this->redirectUrl("user_listing")."&msg=".$msg;					
					header("location:".$url);
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
		 * Showing confirmation popup message box.
		 * 
		 * @access public
		 */
		function showPopupMsgSystem()
		{
			
			$replace = array();
			$therapistId = $this->value('id');	
            $privateKey = $this->config['private_key'];
            
			$queryAssocPatients = "SELECT patient_id,
                                    AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
                                    AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last,
                                    username FROM therapist_patient as tp, user WHERE user.user_id = tp.patient_id AND tp.therapist_id = '".$therapistId."'";
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
			$clinicId = $this->value('clinic_id');	
			$queryTotTherapists  = " SELECT count(user.user_id) AS totTherapists FROM user 
					inner join clinic_user on clinic_user.user_id = user.user_id and ( user.status = '1' or user.status = '2' )  and clinic_user.clinic_id = '$clinicId' 
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
            $queryFullName = "SELECT 
                                AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
                                AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last
                                FROM user WHERE user_id ='".$therapistId."'";
			$resultFullName = $this->execute_query($queryFullName);
			$row = $this->fetch_array($resultFullName);
			
            $clinic_name = $this->get_field($clinicId,'clinic','clinic_name');
            if(!empty($clinic_name)){
                $replace['clinic_name'] = ' from '.$clinic_name.' clinic.';    
            }
            else{
                $replace['clinic_name'] = '';    
            }
			$replace['name_first'] = $row['name_first'];
			$replace['name_last'] = $row['name_last'];			
			
			
			$this->output = $this->build_template($this->get_template("thpstAccessRemovalPopup"),$replace);
			
		}
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
		 * This function shows the login history of Patients.
		 * 
		 * @access public
		 *
		 */
		function systemLoginHistory(){
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
                 * @desc used to show popup message when user unsubscribe the e health services.
                 * @param void
                 * @return void
                 * @access public
                 */
                 
                 public function systemadmineHealthUnsub(){
                    $userInfo = $this->userInfo();
                    $userId = $this->value('userId'); 
                    $clinicId    =    $this->get_clinic_info($userId,"clinic_id");
                    
                    $subscriptionDetail=$this->getPatientSubscriptionDetails($userId,$clinicId);
                    $ehsAction = $_REQUEST['ehsAction'];
                    $replace['ehsAction'] = $ehsAction;   
                   
                    $replace['message'] = "You are about to unsubscribe this Health Record from {$subscriptionDetail['subscription_title']}. If you do, this patient will no longer be able to log into his patient portal and receive online care from the practitioner at the end of your current service billing period. Are you sure you want to unsubscribe?";
                   // $replace['message'] = "You are about to unsubscribe from E health Service. If you do, this patient will no longer be able to log into his patient portal and receive online care from the practitioner at the end of your current service billing period. Are you sure you want to unsubscribe?";
                    $replace['subscrp_id']=$_REQUEST['subscrp_id'];
                    $replace['userId']=$_REQUEST['userId'];
                    $replace['clinicId']=$_REQUEST['clinicId'];
                    $replace['body'] = $this->build_template($this->get_template("currentEhealthUnsubscribe"),$replace);
                    $replace['browser_title'] = "Tx Xchange: Home";
                    $this->output = $this->build_template($this->get_template("main"),$replace);
                } 

               /**
                * This function subscribes / unsubscribes patient from mass messaging.
                * @access public
                */
                function system_admin_patient_ehs_unsubscribe() {
                    
                    $userId = $this->value('userId'); 
                    $ehs = $this->value('ehs');
                   
                    if($this->value('confirm') == "yes" ){
                        if(is_numeric($this->value('ehs'))){
                            $query = "UPDATE user SET  ehs = '".$this->value('ehs')."' WHERE user_id = ".$userId;
                            $result = @mysql_query($query);
                        }
                        $this->output = "<script language='javascript'> 
                                                parent.parent.location.reload();
                                                //parent.parent.GB_hide();
                                                parent.parent.setTimeout('GB_CURRENT.hide()',1000); 
                                            </script>";
                           return;
                    }
                                
                       if($ehs == 0) {
                                $replace['message'] = "By clicking E health service this patient's service will become disable.<br /><br /><span style='padding-left:20px;'>Do you still want to disable?</span>";
                      } else {  
                        $replace['message'] = "By clicking E health service this patient's service will become enabled.<br /><br /><span style='padding-left:20px;'>Do you still want to enable?</span>";
                      }
                    $replace['ehs'] = $this->value('ehs');
                    $replace['userId'] = $this->value('userId');
                    $replace['clinicId']=$_REQUEST['clinicId'];
                    $ehsAction = $_REQUEST['ehsAction'];
                    $replace['ehsAction'] = $ehsAction;
                    $replace['body'] = $this->build_template($this->get_template("ehealthservice"),$replace);
                    $replace['browser_title'] = "Tx Xchange: Home";
                    $this->output = $this->build_template($this->get_template("main"),$replace);
                }


        /**
                 * This function Enable /Disable E health service
                 * @access public
                 */
                function ehsonetimepaymentsysadmin() {
                    
                    $userId = $this->value('userId'); 
                    $ehs = $this->value('ehs');

                     $replace['message'] = "It is one Time subscribed patient so not able to unsubscribe from EHS";
                    $replace['ehs'] = $this->value('ehs');
                    $replace['userId'] = $this->value('userId');
                    $replace['clinicId']=$_REQUEST['clinicId'];
                    $replace['body'] = $this->build_template($this->get_template("ehsonetimepayment"),$replace);
                    $replace['browser_title'] = "Tx Xchange: Home";
                    $this->output = $this->build_template($this->get_template("main"),$replace);
                }

        
        
        
        
        
	}
	// creating object of this class.
	$obj = new clinicDetail();
?>
