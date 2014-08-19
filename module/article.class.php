<?php	

	/**
	 * 
	 * Copyright (c) 2008 Tx Xchange.
	 * 
	 * This class includes the functionality like list articles, edit , remove and create new articles.
	 * 
	 * // necessary classes 
	 * require_once("module/application.class.php");
	 * 
	 * // pagination class
	 * require_once("include/paging/my_pagina_class.php");		
	 * 
	 * // validation classes
	 * require_once("include/validation/_includes/classes/validation/ValidationSet.php");
	 * 
	 * // file upload class
	 * require_once("include/fileupload/class.upload.php");	
	 * 
	 */
	
	require_once("module/application.class.php");
	require_once("include/paging/my_pagina_class.php");	

	require_once("include/fileupload/class.upload.php");
	require_once("include/validation/_includes/classes/validation/ValidationSet.php");	
	
  	class article extends application{
  		
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
		private $formArray;
		
		
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
				$str = "sysAdmin"; //default if no action is specified
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
		
		
		/**
		 * Displays list of articles created by system admin
		 *
		 * @access public
		 */
				
		function articleList()
		{
			/*
				This function is used to display the list of articles in a tabular format				
					
			*/
			$this->set_session_page();
			$replace = array();
			$replace['browser_title'] = "Tx Xchange: Article List";
			$userInfo = $this->userInfo();
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else 
			{	
				$userId = $userInfo['user_id'];
				$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";
				
				/* Defining Sorting */				
			
				$orderByClause = "";
				if ($this->value('sort') == '') 
				{
					$orderByClause = "article.article_name";
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
						case 'article_name'		:
														$orderByClause = "article.article_name";
														
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
														
						case 'headline' 	:
														$orderByClause = "article.headline";
														if ($this->value('order') == '2' ) 
														{
															$orderByClause.= " DESC ";															
															$replace['order2'] = "";
															$replace['order1'] = "";
															$replace['order3'] = "";
															
															$replace['sortOrderImg1']='';
															$replace['sortOrderImg2']='&nbsp;<img src="images/sort_desc.gif">';
															$replace['sortOrderImg3']='';
															
														}
														else 
														{
															$orderByClause.= " ASC ";															
															$replace['order2'] = "&order=2";;
															$replace['order1'] = "";		
															$replace['order3'] = "";
															$replace['sortOrderImg1']='';
															$replace['sortOrderImg2']='&nbsp;<img src="images/sort_asc.gif">';												
															$replace['sortOrderImg3']='';
														}
							
														break;	
														
													
						case 'modified' 	:
														$orderByClause = "article.modified";
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
					
				$sqlWhere = "";
			
				if($this->value('search')!='')
				{
					$sqlWhere = " AND ((".$this->makeSearch(ALL_WORDS,$this->value('search'),'article_name').") or (" .$this->makeSearch(ALL_WORDS,$this->value('search'),'headline')."))";
                    //$sqlWhere = " AND ".$this->makeSearch(ALL_WORDS,$this->value('search'),'article_name','article');
					$replace['searchStr'] = '&search='.$this->value('search');
				}
				else 
				{
					$sqlWhere = "";
					$replace['searchStr'] = "";
				}				
				
				
				$replace['header'] = $this->build_template($this->get_template("header"));
				$replace['footer'] = $this->build_template($this->get_template("footer"));	
				//$replace['sidebar'] = $this->build_template($this->get_template("sidebar"));									
				$replace['sidebar'] = $this->sidebar();
				$replace['articleRows'] = $this->getArticleRows($userId,$userType,$orderByClause,$sqlWhere,$replace);
				
				if ($sqlWhere == "") 			
				{						
					$replace['filter'] = $this->build_template($this->get_template("articleFilter"));	
				}
				else {
					//$searchOn['search'] = $this->value('search');
					//$searchOn['searchOn'] = $this->build_template($this->get_template("articleSearchOn"),$searchOn);
					$searchOn['searchOn'] = "";
					$replace['filter'] = $this->build_template($this->get_template("articleFilterClear"),$searchOn);
				}	
				
				$replace['body'] = $this->build_template($this->get_template("articlelist"),$replace);
				$this->output = $this->build_template($this->get_template("main"),$replace);			
			}	
			
		}
		
			
		/**
		 * Use to delete the article, article is not deleted from the database,
		 * only status field value is changed for the particular article
		 *
		 * @access public
		 */
		
		function articleDelete()
		{
			/*
				Use to delete the article, article is not deleted from the database 
				only status field value is changed for the particular article
			
			*/
			
			$userInfo = $this->userInfo();
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else 
			{	
				$articleId = (int) $this->value('id');
				
				//extra precaution check if user has access to delete the article
				
				$userInfo = $this->userInfo();
				$userId = $userInfo['user_id'];
				$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";
				$query = "SELECT user_id FROM article WHERE status <> 3 AND article_id = ". $articleId;
				
				$result = $this->execute_query($query);
				if (!$result) {
					// that is invalid article id or a valid article id but is_deleted == 1
					unset($_GET['id']);	
				}
				else 
				{
					$row = $this->fetch_array($result);
					
					$creatorId = $row['user_id'];
					
					if("SysAdmin" == $userType || $creatorId == $userId)
					{		
						//$queryUpdate = "UPDATE article SET status = 3 WHERE article_id = ". $articleId;
						
						/*
							Commenting above line and editing update clause so that if system deletes one article (article_id)
							then all the article where parent_article_id is same as of this (article_id)
							should also be deleted
						
						*/
						$queryUpdate = "UPDATE article SET status = 3 WHERE article_id = '". $articleId."' OR parent_article_id ='".$articleId."'";
					
						$this->execute_query($queryUpdate);	
		
					}	
					else
					{
						unset($_GET['id']);	
					}
				}	
	
				//header("location:index.php?action=articleList");
				
				$url = $this->redirectUrl('articleList');
				header("location:".$url);				
					
			}	
			
		}
		
		/**
		 * This function is used to edit article	
		 *
		 * @access public
		 */
		
		function articleEdit()
		{
			
			//	This function is used to edit article				
			
						
			$userInfo = $this->userInfo();
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else 			
			{	
				
				//Getting article info
				
				$showForm = 1;
				
				$error = "";
				$articleId = (int) $this->value('id');
				
				$userInfo = $this->userInfo();
				$userId = $userInfo['user_id'];
				$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";				
				
				//check if its a valid article id, does that article exist and if yes then is status active
				$query = "SELECT * FROM article WHERE status <> 3 AND article_id =". $articleId;
				
				$result = $this->execute_query($query);				
				
				if (!$result) 
				{
					// that is invalid article id or a valid article id but is_deleted == 1					
					//unset($_GET['id']);	
					header("location:index.php?action=articleList");
				}
				else 
				{
					
					//its a valid article id so go for edit
					$article_info = $this->fetch_array($result);
					
					$creatorId = $article_info['user_id'];
					
					//check if the user has access to edit the article
					
					if("SysAdmin" == $userType || $creatorId == $userId)
					{		
											
						$fromPost = false;
						
						//check if form has been submitted or not

						if("Save Changes" == $this->value('submitted_save') && "saveArticle" == $_SESSION['articleAction'])
						{							
							//that means form has been posted so check for validation	
							$error = $this->validateArticleForm();				
								
							
							if (count($error) > 0)
							{								
								$fromPost = true;		
								$showForm = 1;													
							}
							else 
							{
														
								//first check the duplicates
								$articleName = addslashes($this->value('article_name'));									
								
								$query = "SELECT article.article_name FROM article INNER JOIN user ON article.user_id = user.user_id WHERE article.status <> 3 AND article.article_name LIKE '".$articleName."' AND user.usertype_id = 4 AND article.article_id <> '".$articleId ."'";
								$result = $this->execute_query($query);
								
								$noRows = $this->num_rows($result);									
								
								if ($noRows != 0)
								{
									$error = 'Article name: exists in the system. Please choose another name';									
									$fromPost = true;
									$showForm = 1;
								}
								else 
								{
									// perfect save the details give the file name
										
									$fileUploadErrMsg = $this->updateArticle($articleId);		
									
									if ("" == $fileUploadErrMsg)
									{
										
										//Also update the article_speciality	
										
										// since its an update so
										$sql = "DELETE FROM article_speciality WHERE article_id = ".$articleId;
										$this->execute_query($sql);										
										
										$tableName = "article_speciality";
										$fieldArray = null;										
											
										for($i=0;$i<count($_POST['speciality']);++$i)
										{
											$fieldArray = array(
															"article_id" => $articleId,
															"speciality" => $_POST['speciality'][$i],
															"creation_date" => "NOW()"							
															);
															
											$result = $this->executeInsertQuery($tableName,$fieldArray,false);
											
										}		
										
										{
											// Also, speciality block
											
											$queryArticle = "SELECT article.article_id FROM article WHERE parent_article_id = ".$articleId;
														
											$resultArticle = $this->execute_query($queryArticle);										
											
																		
											if($this->num_rows($resultArticle)!= 0)
											{
												while($row = $this->fetch_array($resultArticle))
												{	
																
													// since its an update so
													$sql = "DELETE FROM article_speciality WHERE article_id = ".$row['article_id'];
													$this->execute_query($sql);										
													
													$tableName = "article_speciality";
													$fieldArray = null;					
													
													for($i=0;$i<count($_POST['speciality']);++$i)
													{
														$fieldArray = array(
																		"article_id" => $row['article_id'],
																		"speciality" => $_POST['speciality'][$i],
																		"creation_date" => "NOW()"							
																		);
																		
														$result = $this->executeInsertQuery($tableName,$fieldArray,false);
														
													}		
														
												}//end of while
											}//end of if	
											
										}// end of speciality copy block										
										
										// unset $_SESSION['articleAction']
										$_SESSION['articleAction'] = ""; //to avoid resubmission																					
										//show article listing with message article edited successfully
										$showForm = 0;
									}
									else 
									{
										$error = $fileUploadErrMsg;										
										$fromPost = true;
										$showForm = 1;
										
									}								
									
								}							
								
							}						
						}
						elseif ("Save Changes" == $this->value('submitted_save') && (isset($_SESSION['articleAction']) && "" == $_SESSION['articleAction'])) 
						{
								//case resubmission that is user has pressed F5 or refreshed the page after form submission
								// so we need to prevent resubmission of form								
								//show article listing without any message
								$showForm = 0;								
								
						}
						else 
						{
								//first time
								$fromPost = false;
								$showForm = 1;
						}	
						
						
						
						if (1 == $showForm)
						{
							// this block of code displays the article edit form
							$selected = "";				
							
							$userId = $userInfo['user_id'];
							$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";
							$replace['header'] = $this->build_template($this->get_template("header"));
							$replace['footer'] = $this->build_template($this->get_template("footer"));	
							//$replace['sidebar'] = $this->build_template($this->get_template("sidebar"));	
							$replace['sidebar'] = $this->sidebar();
							$replace['articleBreadcrumb'] = strtoupper($article_info['article_name']); 
							$replace['articleHeaderBar'] = $article_info['article_name']; 
							//$replace['richTextAreaPath'] = $this->config['tx']['richTextArea']['dirPath'];
							//$replace['imagePath'] = $this->config['tx']['article']['media_url'];
							//$replace['imageURL'] = $this->config['tx']['article']['media_url'];
							$replace['article_id'] = $articleId;
							
							$arrCheckBox = array(
							array("name" => "speciality1", "lblName" => "Ortho", "value" => 1, "checked" => false, "extra" => "onMouseOver=\"help_text(this, '<!helpText>')\""),
							array("name" => "speciality2", "lblName" => "PED", "value" => 2, "checked" => false, "extra" =>"onMouseOver=\"help_text(this, '<!helpText>')\""),
							array("name" => "speciality3", "lblName" => "Cardio", "value" => 3, "checked" => false, "extra" =>"onMouseOver=\"help_text(this, '<!helpText>')\""),
							array("name" => "speciality4", "lblName" => "Neuro", "value" => 4, "checked" => false, "extra" =>"onMouseOver=\"help_text(this, '<!helpText>')\"")
							
							);	
						
							if ($fromPost === true) 
							{
								$replace['speciality'] = $this->buildInputCheckBox($arrCheckBox,'speciality',$this->value('speciality'));										
							}
							else 
							{								
								$sql = "SELECT speciality FROM article_speciality WHERE article_id = ".$articleId;
								$result = $this->execute_query($sql);								
								
								while($row = $this->fetch_array($result))
								{
									$arraySpeciality[] = $row['speciality'];					
								}
								
								$replace['speciality'] = $this->buildInputCheckBox($arrCheckBox,'speciality',$arraySpeciality);	
								
							}									
							
							$replace['helpText'] = 'Put the checkmark against the speciality box to which this article is related to';
						
							$replace['article_name'] = ($fromPost)? $this->value('article_name') : $article_info['article_name']; 						
							$replace['headline'] = ($fromPost === true)? $this->value('headline') : $article_info['headline']; 											
							
							
							$replace['link_url'] = ($fromPost)? $this->value('link_url') : $article_info['link_url']; 
							
							
							$selected = ($fromPost)? $this->value('article_type') : $article_info['article_type']; 
							
							$selectedStatus = ($fromPost)? $this->value('status') : $article_info['status'];
							
							$replace['displayContent1'] = (1 == $selected)? "block":"none";
							
							$replace['displayContent2'] = (2 == $selected)? "block":"none";					
							
							
							$arrOptions = array(
													""=>"Choose...",													
													"1"=>"Link",
													"2"=>"File"
												);
												
							$arrOptionStatus = array(																							
													"1"=>"Active",
													"2"=>"Inactive"
							
												);
																	
							$fileURL = $article_info['file_url'];				
							$fileInputFieldName = 'file_url';
							
							$replace['error'] = $error;	
							
							$_SESSION['articleAction'] = 'saveArticle'; 
												
							$replace['mayBeShowMedia'] = $this->maybeShowMedia($articleId,$fileInputFieldName,$fileURL);					
												
							$replace['options']	= $this->build_select_option($arrOptions,$selected);	
							
							$replace['optionStatus']	= $this->build_select_option($arrOptionStatus,$selectedStatus);				
							$replace['browser_title'] = "Edit Article";
							$replace['body'] = $this->build_template($this->get_template("articleForm"),$replace);
							$this->output = $this->build_template($this->get_template("main"),$replace);
						}
						else 
						{
							// this block of code redirects to the list of articles
							//header("location:index.php?action=articleList");
							
							$url = $this->redirectUrl('articleList');
							header("location:".$url);
						}
			
					}	
					else
					{
						// this block of code redirects to the list of articles this is to handle the case of URL string 
						// manipulation
						//unset($_GET['id']);	
						header("location:index.php?action=articleList");
					}
					
				}					
							
			}	
		}
		
		/**
		 * Displays confirm popup window for edit article form, 
		 * It displays a confirmation message, saying are you sure you want to edit the article details
		 *
		 * @access public
		 */

		function confirmPopupArticleEdit()
		{
			$this->output = $this->build_template($this->get_template("confirmPopupArticleEdit"));
		}
		
		/**
		 * Displays confirm popup window for create article form, 
		 * It displays a confirmation message, saying are you sure you want to create the article
		 *
		 * @access public
		 */
		function confirmPopupArticleCreate()
		{
			$this->output = $this->build_template($this->get_template("confirmPopupArticleCreate"));
		}		
		
		
		/**
		 * The actual block for article update
		 * the DB fields updation array is prepared in this function
		 *
		 * @access public
		 */
		
		function updateArticle($articleId)		
		{			
			
			// The actual block for article update
			// the DB fields updation array is prepared in this
			
			$contentArray = array();
			$fileError = "";
			
			switch($this->value('article_type'))
			{
				case '1':	
							/*
							$articleName = addslashes($_POST['article_name']);
							$headline = addslashes($_POST['headline']);		
									
							$contentArray['link_url'] = $this->value('link_url');	
							$query = "UPDATE article 
										SET article_name = '{$articleName}',
										 headline = '{$headline}',
										 article_type = {$this->value('article_type')},
										 link_url = '{$this->value('link_url')}',
										 modified = NOW(),
										 status = {$this->value('status')} WHERE article_id = ".$articleId; 	
							
							$result = $this->execute_query($query);								
							*/
							
							//$where = 'article_id = '.$articleId;
							
							//edited, the above WHERE clause has been commented so that now the updation is affected to all the therapists library as well
							
							$where = 'article_id = '.$articleId.' OR (parent_article_id = '.$articleId.' AND status != 3)';
							
							//status
							$status = $this->value('status');
							$status = empty($status)? 2 :$status;
							$modifyTime = date('Y-m-d H:i:s',time());
							
							$updateArr = array(
									'article_name'=>$this->value('article_name'),
									'headline'=>$this->value('headline'),
									'article_type'=>$this->value('article_type'),
									'link_url'=>$this->value('link_url'),								
									'modified'=>$modifyTime,
									'status'=>$status			
									);
							
							$this->update('article',$updateArr,$where);				
													
							break;
							
				case '2':
					
							$fileName = "";									
																	
							$fileError = $this->fileUpload('file_url',$fileName);						
									
							if ("" == $fileError)
							{								
								$contentArray['file_url'] = $fileName;
								$contentArray['file_path'] = $this->get_article_path_for_database($articleId).$fileName;								
							}							
							break;
			}	
			
				
			
			if ($this->value('article_type') == '2') 			
			{
				/*
					check if already this articleId has file_url
				*/
					
				$query = "SELECT article_type,file_url FROM article WHERE article_id = ".$articleId;
				$resultArtcile = $this->execute_query($query);
							
				$row = $this->fetch_array($resultArtcile);
							
				if (!empty($row['file_url']) && "" != $fileError )
				{
					
					/*
						file error is there so two cases
						a) file error is there because file was not uploaded at all that is file was not given
						b) file error is there because file was uploaded but there was some error in uploading
						
						so if its a) then no problem but if its b) then do give error
						to check this check the error code if its 4 then no problem else display error it is problem
					
					*/					
					
					if ($_FILES['file_url']['error'] == 4)
					{				
						$fileError = "";
						//update article without file_url
						
						$status = $this->value('status');
						$status = empty($status)? 2 :$status;
						
						//$where = 'article_id = '.$articleId;
						
						//edited, the above WHERE clause has been commented so that now the updation is affected to all the therapists library as well
							
						$where = 'article_id = '.$articleId.' OR (parent_article_id = '.$articleId.' AND status != 3)';
						
						$modifyTime = date('Y-m-d H:i:s',time());
								
						$updateArr = array(
										'article_name'=>$this->value('article_name'),
										'headline'=>$this->value('headline'),	
										'article_type'=>$this->value('article_type'),								
										'modified'=>$modifyTime,
										'status'=>$status			
										);
								
						$this->update('article',$updateArr,$where);
					}
					else 
					{
						return $fileError;
					}
					
				}								
				else if((!empty($row['file_url']) && "" == $fileError) || (empty($row['file_url']) && "" == $fileError) )
				{
					/*
					$articleName = addslashes($_POST['article_name']);
					$headline = addslashes($_POST['headline']);		
							
					$query = "UPDATE article 
								SET article_name = '{$articleName}',
								 headline = '{$headline}',
								 article_type = {$this->value('article_type')},
								 file_url = '{$contentArray['file_url']}',
								 modified = NOW(),
								 status = {$this->value('status')} WHERE article_id = ".$articleId; 	
					
					$result = $this->execute_query($query);		
					*/
					//status
					$status = $this->value('status');
					$status = empty($status)? 2 :$status;
					
					//$where = 'article_id = '.$articleId;
					
					//edited, the above WHERE clause has been commented so that now the updation is affected to all the therapists library as well
							
					$where = 'article_id = '.$articleId.' OR (parent_article_id = '.$articleId.' AND status != 3)';
					
					$modifyTime = date('Y-m-d H:i:s',time());
						
					$updateArr = array(
									'article_name'=>$this->value('article_name'),
									'headline'=>$this->value('headline'),
									'article_type'=>$this->value('article_type'),
									'file_url'=>$contentArray['file_url'],
					                'file_path'=>$contentArray['file_path'],												
									'modified'=>$modifyTime,
									'status'=>$status			
									);
							
					$this->update('article',$updateArr,$where);	
					
					/*
					   Also, since there is change in file so this file needs to be copied to all the article_ids
					   where parent_article_id is same as that of this system's article_id, that is copy file
					   for all the articles for all the therapists library
					
					*/
					{
						// File copy block
						
						$queryArticle = "SELECT article.article_id FROM article WHERE parent_article_id = ".$articleId;
									
						$resultArticle = $this->execute_query($queryArticle);						
						
													
						if($this->num_rows($resultArticle)!= 0)
						{
							while($row = $this->fetch_array($resultArticle))
							{	
															
								//$fileDest = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url']. $row['article_id']. '/';
								$fileDest = $this->check_article_path($row['article_id']);
								/*if(!file_exists($fileDest))
								{
									mkdir($fileDest);
									chmod($fileDest, 0777);
								}*/
								
								
								//$fileDestPath = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url']. $row['article_id'] . '/'.$contentArray['file_url'];
																			
								//$fileSourcePath = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$articleId . '/'.$contentArray['file_url'];
								$fileDestPath = $this->get_article_path($row['article_id']);
                                                                            
                                $fileSourcePath = $this->get_article_path($articleId);
                                
                                copy($fileSourcePath,$fileDestPath);

                                $this->update_article_path($row['article_id']);
									
							}//end of while
						}//end of if	
						
					}// end of file copy block
					
				}
				elseif (empty($row['file_url']) && "" != $fileError)
				{
					return $fileError;
				}
				
			}			
			
		}		
		
		
		/**
		 * This function is used to create article
		 * 
		 * @access public
		 */
		
		function articleCreate()
		{
			
			$userInfo = $this->userInfo();
			if (!$userInfo)
			{
				header("location:index.php");
			}
			else 			
			{	
				
				$userInfo = $this->userInfo();
				$userId = $userInfo['user_id'];
				$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";		
				
				
				if("SysAdmin" == $userType)
				{		
										
					$fromPost = false;

					if("Add Article" == $this->value('submitted_save') && "saveNewArticle" == $_SESSION['articleAction'])
					{							
						//that means form has been posted so check for validation	
						$error = $this->validateArticleForm();				
							
				
						if (count($error) > 0)
						{								
							$fromPost = true;		
							$showForm = 1;													
						}
						else 
						{
														
							//first check the duplicates
							
							//$query = "SELECT article_name FROM article WHERE article_name LIKE '".$this->value('article_name') ."'";
							$articleName = addslashes($this->value('article_name'));	
							
							$query = "SELECT article.article_name FROM article INNER JOIN user ON article.user_id = user.user_id WHERE article.status <> 3 AND article.article_name LIKE '".$articleName."' AND user.usertype_id = 4 ";
							$result = $this->execute_query($query);
							
							$noRows = $this->num_rows($result);									
								
							if ($noRows != 0)
							{
								$error = 'Article name: exists in the system. Please choose another name';									
								$fromPost = true;
								$showForm = 1;
							}
							else 
							{
								// perfect insert the details
								$articleId = 0;
								$arrayArticleIds = null;
								$fileUploadErrMsg = $this->insertArticle($userId,$userType,$articleId,$arrayArticleIds);										
									
								if ("" == $fileUploadErrMsg)
								{
									//Also insert the article_speciality
									$tableName = "article_speciality";
									$fieldArray = null;
										
									for($i=0;$i<count($_POST['speciality']);++$i)
									{
										$fieldArray = array(
														"article_id" => $articleId,
														"speciality" => $_POST['speciality'][$i],
														"creation_date" => "NOW()"							
														);
														
										$result = $this->executeInsertQuery($tableName,$fieldArray,false);
										
										{
											//The speciality block																														
											
											for ($j=0;$j<count($arrayArticleIds);++$j)														
											{																						
												
												$tableName = "article_speciality";
												$fieldArray = null;					
												
												for($i=0;$i<count($_POST['speciality']);++$i)
												{
													$fieldArray = array(
																	"article_id" =>$arrayArticleIds[$j],
																	"speciality" => $_POST['speciality'][$i],
																	"creation_date" => "NOW()"							
																	);
																	
													$result = $this->executeInsertQuery($tableName,$fieldArray,false);
													
												}		
													
											}//end of for
												
											
										}//end of block
										
									}	
									
									// unset $_SESSION['articleAction']
									$_SESSION['articleAction'] = ""; //to avoid resubmission																					
									//show article listing with message article added successfully
									$showForm = 0;
								}
								else 
								{
									$error = $fileUploadErrMsg;										
									$fromPost = true;
									$showForm = 1;
									
								}								
									
							}
						}															
											
					}
					elseif ("Add Article" == $this->value('submitted_save') && (isset($_SESSION['articleAction']) && "" == $_SESSION['articleAction'])) 
					{
						//case resubmission								
						//show article listing without any message
						$showForm = 0;								
								
					}
					else 
					{
						//first time
						$fromPost = false;
						$showForm = 1;
					}							
						
						
					if (1 == $showForm)
					{
						$selected = "";				
							
						$userId = $userInfo['user_id'];
						$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";
						
						$arrCheckBox = array(
							array("name" => "speciality1", "lblName" => "Ortho", "value" => 1, "checked" => false, "extra" => "onMouseOver=\"help_text(this, '<!helpText>')\""),
							array("name" => "speciality2", "lblName" => "PED", "value" => 2, "checked" => false, "extra" =>"onMouseOver=\"help_text(this, '<!helpText>')\""),
							array("name" => "speciality3", "lblName" => "Cardio", "value" => 3, "checked" => false, "extra" =>"onMouseOver=\"help_text(this, '<!helpText>')\""),
							array("name" => "speciality4", "lblName" => "Neuro", "value" => 4, "checked" => false, "extra" =>"onMouseOver=\"help_text(this, '<!helpText>')\"")
							
						);	
						
						
						
												
						$replace['header'] = $this->build_template($this->get_template("header"));
						$replace['footer'] = $this->build_template($this->get_template("footer"));							
						//$replace['sidebar'] = $this->build_template($this->get_template("sidebar"));					
						$replace['sidebar'] = $this->sidebar();
							
						$replace['article_name'] = ($fromPost)? $this->value('article_name') : ""; 						
						$replace['headline'] = ($fromPost === true)? $this->value('headline') : "";						
						
						$replace['link_url'] = ($fromPost)? $this->value('link_url') : ""; 
							
							
						$selected = ($fromPost)? $this->value('article_type') : ""; 						
						
						$replace['displayContent1'] = (1 == $selected)? "block":"none";
						
						$replace['displayContent2'] = (2 == $selected)? "block":"none";					
							
							
						$arrOptions = array(
												""=>"Choose...",												
												"1"=>"Link",
												"2"=>"File"
											);
												
						
							
						$replace['error'] = $error;	
							
						$_SESSION['articleAction'] = 'saveNewArticle'; 
												
														
						$replace['options']	= $this->build_select_option($arrOptions,$selected);
										
						$replace['speciality'] = $this->buildInputCheckBox($arrCheckBox,'speciality',$this->value('speciality'));
						$replace['helpText'] = 'Put the checkmark against the speciality box to which this article is related to';
						$replace['browser_title'] = "Tx Xchange: Create Article";	
						$replace['body'] = $this->build_template($this->get_template("articleForm"),$replace);
						$this->output = $this->build_template($this->get_template("main"),$replace);
					}
					else 
					{
						// this block of code redirects to the list of articles
						header("location:index.php?action=articleList");
					}
					
				}
				else 
				{
					// this block of code redirects to the list of articles
					header("location:index.php?action=articleList");
				}				
				
			}
		}

		/**
		 * The actual block for article insertion
		 * the DB fields insertion array is prepared in this function
		 *
		 * @access public
		 */	
	
		function insertArticle($userId, $userType, &$articleId,&$arrayArticleIds)		
		{			
			
			// The actual block for article insertion
			// the DB fields insertion array is prepared in this block
			
			$contentArray = array();
			$fileError = "";
			
			$articleName = addslashes($_POST['article_name']);
			$headline = addslashes($_POST['headline']);
				
			if ($this->value('article_type') == 1) 
			{
				
				//status
				$status = $this->value('status');
				$status = empty($status)? 2 :$status;
				
				$insertArr = array(
									'article_name'=>$this->value('article_name'),
									'parent_article_id'=>null,
									'headline'=>$this->value('headline'),
									'article_type'=>$this->value('article_type'),
									'link_url'=>$this->value('link_url'),
									'creation_date'=>date('Y-m-d H:i:s',time()),
									'user_id'=>$userId,
									'modified'=>date('Y-m-d H:i:s',time()),
									'status'=>$status			
									);
									
				$this->insert('article',$insertArr);	
				$articleId = $this->insert_id();				
				
				/*$query = "INSERT INTO article 
										(article_name,headline,article_type,link_url,creation_date,user_id,modified,status) 
								VALUES ('{$articleName}',
										'{$headline}',
										{$this->value('article_type')},
										'{$this->value('link_url')}',
										NOW(),
										$userId,
										NOW(),
										{$this->value('status')})";			*/
				
				$arrayArticleIds = array();
				
				{
					//Block 
					
					$therapistQuery = "SELECT user.user_id FROM user WHERE usertype_id = 2 AND status != 3";
					
					$resultQuery = $this->execute_query($therapistQuery);									
									
					
					if($this->num_rows($resultQuery)!= 0)
					{
						while($row = $this->fetch_array($resultQuery))
						{
							$insertArr = array(
									'article_name'=>$this->value('article_name'),
									'parent_article_id'=>$articleId,
									'headline'=>$this->value('headline'),
									'article_type'=>$this->value('article_type'),
									'link_url'=>$this->value('link_url'),
									'creation_date'=>date('Y-m-d H:i:s',time()),
									'user_id'=>$row['user_id'],
									'modified'=>date('Y-m-d H:i:s',time()),
									'status'=>$status			
									);
									
							$this->insert('article',$insertArr);
							$arrayArticleIds[] = $this->insert_id();					
						}//end of while
						
					}//end of if					
					
				}
				
			}
			else 
			{
				//status
				$status = $this->value('status');
				$status = empty($status)? 2 :$status;
				
				$insertArr = array(
									'article_name'=>$this->value('article_name'),
									'parent_article_id'=>null,
									'headline'=>$this->value('headline'),
									'article_type'=>$this->value('article_type'),									
									'creation_date'=>date('Y-m-d H:i:s',time()),
									'user_id'=>$userId,
									'modified'=>date('Y-m-d H:i:s',time()),
									'status'=>$status			
									);
									
				$this->insert('article',$insertArr);		
								
				$articleId = $this->insert_id();
				
				/*
				$query = "INSERT INTO article 
										(article_name,headline,article_type,creation_date,user_id,modified,status) 
								VALUES ('{$articleName}',
										'{$headline}',
										{$this->value('article_type')},										
										NOW(),
										$userId,
										NOW(),
										{$this->value('status')})";		*/
			}				
							
			
			//$result = $this->execute_query($query);							
				
			
			
			//File Upload Case
			$fileName = "";				
			$errMsg = "";		
			
			if ($this->value('article_type') == '2') 
			{
				/*			
			
				Since we upload files in folder in name of article_id so we need to
			 	first insert the record then get the article_id and then create that name folder
			 	and finally placing files in this newly created folder
			 	
			 	we have the inserted article_id
				*/ 	
			
				// But file upload error could be there so lets check if we have the file at server
				$foo = new Upload($_FILES['file_url']);				
				
			
				if ($foo->uploaded) 
				{
					//file is there on server so now we will finally processed the file					
					
					
					//$fileDest = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url']. $articleId.'/';
					$fileDest = $this->check_article_path($articleId);
							
					
					/*if(!file_exists($fileDest))
					{
						mkdir($fileDest);
						chmod($fileDest,777);
					}*/
					
					// save uploaded file			  
					
				  	$foo->Process($fileDest);				  
				  
				  	if ($foo->processed) 
				  	{ 			     
				  		$fileName = $foo->file_dst_name;				  		
				  	}
				  	else 
				  	{
						//error							
				  		$errMsg = $foo->error;
				  	}
					
					
				}
				else 
				{					
					$errMsg = $foo->error;
				}
				
				
				if ("" != $errMsg)
				{
					// We need to delete the inserted record 
					$where = "article_id = '".$articleId ."'";
					$this->db_delete('article',$where);
					
				}
				else 
				{
					//update file_url field
					$path=date('Y').'/'.date('n').'/'.date('j').'/'.$articleId.'/';
					$tableFields = array("file_url"=>$fileName,
					"file_path"=>$path.$fileName);						
					
					$where = "article_id = '".$articleId ."'";
				
					$this->update('article',$tableFields,$where);	
					
					$arrayArticleIds = array();
				
					{
						//Block 
						
						$therapistQuery = "SELECT user.user_id FROM user WHERE usertype_id = 2 AND status != 3";
						
						$resultQuery = $this->execute_query($therapistQuery);									
										
						
						if($this->num_rows($resultQuery)!= 0)
						{
							while($row = $this->fetch_array($resultQuery))
							{
								$insertArr = array(
										'article_name'=>$this->value('article_name'),
										'parent_article_id'=>$articleId,
										'headline'=>$this->value('headline'),
										'article_type'=>$this->value('article_type'),
										"file_url"=>$fileName,
										'creation_date'=>date('Y-m-d H:i:s',time()),
										'user_id'=>$row['user_id'],
										'modified'=>date('Y-m-d H:i:s',time()),
										'status'=>$status			
										);
										
								$this->insert('article',$insertArr);
								
								$arrayArticleIds[] = $this->insert_id();					
							}//end of while
							
						}//end of if
						
						//Also, copy the uploaded file to all the newly created articles	
						{
							for ($j=0;$j<count($arrayArticleIds);++$j)							
							{	
															
							  $fileDest=$this->check_article_path($arrayArticleIds[$j]);
								//$fileDest = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url']. $arrayArticleIds[$j]. '/';
							    /*	if(!file_exists($fileDest))
								{
									mkdir($fileDest);
									chmod($fileDest, 0777);
								}*/
								
								
								//$fileDestPath = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url']. $arrayArticleIds[$j] . '/'.$fileName;
																			
							//	$fileSourcePath = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$articleId . '/'.$fileName;
							      $fileDestPath = $this->get_article_path($arrayArticleIds[$j]);
                                                                            
                                  $fileSourcePath = $this->get_article_path($articleId);
								
								copy($fileSourcePath,$fileDestPath);
								$this->update_article_path($arrayArticleIds[$j]);
									
							}//end of for
							
						}//end of inner block
						
					}//end of block			
					
				}
				
			}	
			
			return $errMsg;
					
			
		}
		
		/**
		 * Validates the complete article form fields filled in by user while create/edit article
		 *
		 * @access public
		 */
		
		function validateArticleForm()
		{
			
			$error = array();		
			
			$objValidationSet = new ValidationSet();					
			
			$objValidationSet->addValidator(new  StringMinLengthValidator('article_name', 1, "Article Name cannot be empty",$_POST['article_name']));				
			
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('article_name',null,"Please enter valid characters in article name",$this->value('article_name')));
			
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('headline',null,"Please enter valid characters in headline",$this->value('headline')));
			
					
			$objValidationSet->validate();			

			if ($objValidationSet->hasErrors())
			{
				$error[] = $objValidationSet->getErrorByFieldName('article_name');
				$error[] = $objValidationSet->getErrorByFieldName('headline');						
						
			}								
			
			if(trim($this->value('article_type')) == ""){

				$error[] = "Choose the content type for this article";

			}

			if(count($error) == 0 ){ 
				$msg = $this->validateContentType($this->value('article_type'));							
				if ("" != $msg)
				{
					$error[] = $msg;
				}
				
			}		
			

			if(count($error) > 0 ){

				$error = $this->show_error($error);

			}			
			
			return $error;	
			
			
		}
		
		/**
		 * Helper function for validating article content type i.e. article content is either a valid file type or a valid URL link
		 *
		 * @access public
		 */
		
		function validateContentType($articleType)
		{
			
			// check article type			
			$uploaded = false;
			$error = "";		
			
			switch($articleType)
			{
				
				case '1':
					
							$link_url = trim($this->value('link_url'));
							if(!empty($link_url))
							{
								/*if (strpos($link_url, 'http://') != false || strpos($link_url, 'https://') != false) {
									$error = 'Link URL: must begin with "http://" or "https://".';
								}		
								*/
								
								if(!$this->isValidURL($this->value('link_url'))) 
								{ 
								     $error = "Please enter valid URL including http:// or https://"; 
								} 
								
														
							}
							else {
								$error = 'Link URL: was not provided.';
							}
							break;
				case '2':
							// check file upload handle later
							
							/*
							$fileError = $this->fileUpload('file_url');						
							 
							if ("" != $fileError)
							{
								$error = $fileError;
							}
							*/
							break; 
				default:
							//'Unknown article type';
							break;
			}	
			
			return $error;		
			
			
		}
		
		/**
		 * Helper function for validating URL Link
		 * This is a more comprehensive solution for URL validation
		 * @access public
		 */	
		
		function URLValidator($url)
		{
		
			// Set of regular expression rules for parsing
		
            // URL and exracting its parts

            $PATTERNS = array(

                        'protocol' => '((http|https|ftp)://)',

                        'access' => '(([a-z0-9_]+):([a-z0-9-_]*)@)?',

                        'sub_domain' => '(([a-z0-9_-]+\.)*)',

                        'domain' => '(([a-z0-9-]{2,})\.)',

                        'tld' =>'(com|net|org|edu|gov|mil|int|arpa|aero|biz|coop|info|museum|name|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cf|cd|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|fi|fj|fk|fm|fo|fr|fx|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zr|zw)',

                        'port'=>'(:(\d+))?',

                        'path'=>'((/[a-z0-9-_.%~]*)*)?',

                        'query'=>'(\?[^? ]*)?'

                        );
		
			
            $pattern = "`^"

                        .$PATTERNS['protocol']

                        .$PATTERNS['access']

                        .$PATTERNS['sub_domain']

                        .$PATTERNS['domain']

                        .$PATTERNS['tld']

                        .$PATTERNS['port']

                        .$PATTERNS['path']

                        .$PATTERNS['query']

	                        ."$`iU";
		
		    $valid = preg_match($pattern, $url, $COMPONENTS);		
		 
		     return $valid;		
		}		
		
		
		/**
		 * Helper function for validating URL Link
		 *
		 * @access public
		 */
		
		function isValidURL($url) 
		{ 
			//return preg_match ("|^http(s)?://[a-z0-9][a-z0-9\-]+[a-z0-9](\.[a-z]{2,4})+$|i",$url);
		 	return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url); 
		 	//return preg_match("^[http]{1}(://){1}[_a-z0-9-]+(\.){1}[a-z]+$",$url);
		}  

		
		/**
		 * This function is used to get the article rows in article listing
		 * depending upon the article status.
		 * @access public
		 */	

		function getArticleRows($uid,$userType,$orderByClause,$sqlWhere,&$replace)
		{		
			
			/*
				This function is used to get the article rows in article listing
				depending upon the article status.
			
			*/
			
			//$query = "SELECT * FROM article WHERE status <> 3 ".$sqlWhere." ORDER BY " .$orderByClause;
			
			$query = "SELECT article.* FROM article INNER JOIN user ON article.user_id = user.user_id 
														WHERE user.usertype_id = 4  AND article.status <> 3 ".$sqlWhere."  GROUP BY `article_name`,`headline` ORDER BY " .$orderByClause;
			
			//$result = $this->execute_query($query);			
			$link = $this->pagination($rows = 0,$query,'articleList',$this->value('search'),'');                                          

            $replace['link'] = $link['nav'];

            $result = $link['result']; 			
			
			$count =0;
			$articleRows = "";
			if ($this->num_rows($result)!= 0)
			{
				while ($item = $this->fetch_array($result)) 
				{
					$disp = array();
					
					$disp['article_name'] = (!empty($item['article_name'])) ? $item['article_name'] : '?';
					
					if($userType == "SysAdmin" || $item['user_id'] == $uid){
						$disp['article_name'] = '<a href="index.php?action=articleEdit&id=' . $item['article_id'] . '" onClick="help_text(this, \'Click to edit this article\')">' . $disp['article_name'] . '</a>';
					}
					
					$disp['headline'] = (!empty($item['headline'])) ? $item['headline'] : '&nbsp;';				
					$disp['last_update'] = (!empty($item['modified'])) ? $this->formatDate($item['modified']) : "&nbsp;";
					$disp['actions'] = $this->getActions($item, 'article_id',$uid,$userType);			
					
					$class = "";
					
					if($count%2 == 0)
					{
						$class = "line1";
						
					}
					else 
					{
						$class = "line2";
					}
					
					$articleRows .= "<tr class=\"$class\">
										<td>".$disp['article_name']."</td>
										<td>".$disp['headline']."</td>
										<td>".$disp['last_update']."</td>
										<td style=\"white-space:nowrap;text-align:right\">
											<nobr>".
												$disp['actions'].											
											"</nobr>
										</td>
									</tr>";	
					$disp = null;	
					++$count;										
				}
			}
			else 
			{
				$articleRows .= "<tr>
									<td colspan=4> No Articles To List</td>
								</tr>";
				
				$replace['link'] = "&nbsp;";
							
			}
			
			return $articleRows;			
			
			
		}
		
		/**
		 * This function is used for displaying drop down actions(preview/Edit/Delet) that can be performed on an article
		 * 
		 * @access public
		 */	
		
		function getActions(&$item, $idField, $uid,$userType)
		{			
			$ret = '<select size="1" style="width:100px;" class="action_select" onChange="handleAction(this, '.$item[$idField].')">'.
				'<option value="">Actions...</option>'.
				'<option value="preview"			id="act_preview">Preview Article</option>';
			if($userType == "SysAdmin"  || $item['creator_user_id'] == $uid)
			{
				$ret .= '<option value="edit"			id="act_edit">Edit Article</option>'.
				'<option value="delete"			id="act_delete">Delete Article</option>';
			}
			$ret .= '</select>';
		
			return (string) $ret;
		}
		
		
		/**
		 * This is a click event function.
		 * A toggle link for show/hide files in edit article form		 
		 *
		 * @access public
		 */
		function maybeShowMedia($id,$inputFieldName,$fileURLVal)
		{
			$mediaStr = "";
			if($id && !empty($fileURLVal))
			{
				
				$fileName = '/' . $fileURLVal;
				$ext = explode(".",$fileName);
				$extension = strtolower($ext[(count($ext) - 1)]);
				switch($extension)
				{
					case 'jpg'://echo $this->get_article_path_for_database($id,'display');
                                $mediaStr =     '<a href="javascript:void(0)" onClick="toggleMediaDisplay(\'media_' . $inputFieldName . '\')">Show/Hide</a>' .
                                            '<div id="media_' . $inputFieldName . '" style="display:none">';
                                $mediaStr .= '<img src="'.$this->get_article_path_for_database($id,'display'). '" border="0" /></div>';
                                break;
                    case 'png'://echo $this->get_article_path_for_database($id,'display');
                                $mediaStr =     '<a href="javascript:void(0)" onClick="toggleMediaDisplay(\'media_' . $inputFieldName . '\')">Show/Hide</a>' .
                                            '<div id="media_' . $inputFieldName . '" style="display:none">';
                                $mediaStr .= '<img src="'.$this->get_article_path_for_database($id,'display'). '" border="0" /></div>';
                                break;
                    case 'bmp'://echo $this->get_article_path_for_database($id,'display');
                                $mediaStr =     '<a href="javascript:void(0)" onClick="toggleMediaDisplay(\'media_' . $inputFieldName . '\')">Show/Hide</a>' .
                                            '<div id="media_' . $inputFieldName . '" style="display:none">';
                                $mediaStr .= '<img src="'.$this->get_article_path_for_database($id,'display'). '" border="0" /></div>';
                                break;
                     case 'gif'://echo $this->get_article_path_for_database($id,'display');
                                $mediaStr =     '<a href="javascript:void(0)" onClick="toggleMediaDisplay(\'media_' . $inputFieldName . '\')">Show/Hide</a>' .
                                            '<div id="media_' . $inputFieldName . '" style="display:none">';
                                $mediaStr .= '<img src="'.$this->get_article_path_for_database($id,'display'). '" border="0" /></div>';
                                break;
                    default:
								$mediaStr = ' <a href="'.$this->get_article_path_for_database($id,'display'). '" target="_BLANK">Attached File ('.$extension.'): '.substr($fileName,1).'</a>';
				}
				
			}
			else
			{
				$mediaStr = ' Click browse to add a file.';
			}
			
			return $mediaStr;
		}
		
		
		/**
		 * This is a file upload function used for uploading a file to the server,
		 * if the article content type is file.	 
		 *
		 * @access public
		 */
		
		function fileUpload($formField, &$fileName)
		{
			$articleId= $this->value('id');
			
			//$fileDest = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url']. $articleId;			
			$fileDest =  $this->check_article_path($articleId);
			//echo $fileDest;
			
				/*if(!file_exists($fileDest))
				{
					mkdir($fileDest);
					chmod($fileDest,777);
				}
				
*/			$foo = new Upload($_FILES[$formField]); 
			
			if ($foo->uploaded) 
			{
			  // save uploaded image with no changes			  
			  $foo->Process($fileDest);			 
			  
			  if ($foo->processed) 
			  { 			     
			  	$fileName = $foo->file_dst_name;			  	
			  	return $foo->error; 
			  }
			  else 
			  {
				//error				
			  	return $foo->error;
			  }
			}
			else 
			{
				//error				
				return $foo->error;
				
			}
		}	
		
		
		/**
		 * Customized function for creating HTML input checkbox in HTML form
		 *
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
		 * Helper function used for db record insertion execution.
		 * This function is the customized version exclusively for article records insertion
		 *
		 * @access public
		 */
		
		function executeInsertQuery($tableName,$fieldArray,$flagAllFields = true)
		{
			$sql = NULL;
					
			if ($flagAllFields == true)		
			{
				$sql = "INSERT INTO ".$tableName." VALUES (".$fieldArray[0];
				for ($i = 1; $i<count($fieldArray);++$i)
				{
					$sql = $sql.",".$fieldArray[$i];
				}			
				$sql = $sql.")";
			}
			else 
			{
				$keys	= array();
						
				$keys = array_keys($fieldArray);
		
				$sql = "INSERT INTO ".$tableName."(".$keys[0];
				
				for ($i = 1; $i < count($fieldArray); ++$i)
				{
					$sql = $sql.",".$keys[$i];
				}
				
				$sql = $sql.") VALUES (".$fieldArray[$keys[0]];
				
				for ($i = 1; $i<count($fieldArray);++$i)
				{
					$sql = $sql.",".$fieldArray[$keys[$i]];
				}
		
				$sql = $sql.")";
			}	
			
			$result = $this->execute_query($sql);	
			
			return $result;
			
		}
		
		/**
		 * Constructs a fragment of a "WHERE" clause that can be used to conduct a search.
		 * Note that this uses "LIKE" subclauses with wildcards, and will force a full table scan.
		 * 
		 *
		 * $searchMode must be ANY_WORD, ALL_WORDS, ALL_WORDS_IN_ORDER, or EXACT_PHRASE.
		 * $searchForWords and $inColumns can each be either an array or a comma-separated string.
		 */
		
		function makeSearch($searchMode, $searchForWords, $inColumns, $tableName = null)
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
					if ($tableName!=null)
					{
						$sub .= "$tableName.$inColumn LIKE '%" . $searchForWord . "%'"; //!! escaping?
					}
					else 
					{	
						$sub .= "$inColumn LIKE '%" . $searchForWord . "%'"; //!! escaping?
					}	
				}
	
				$where .= "($sub)";
			}
	
			return $where;
		}		
		
		/**
		 * Function to populate side bar
		 *
		 * @return side bar menu template
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
	}
	
	// creating object of this class
	$obj = new article();
?>