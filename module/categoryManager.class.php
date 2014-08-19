<?php


	/**
	 *
	 * Copyright (c) 2008 Tx Xchange
	 *
	 * Class for manage treatment category, list category, edit and create category.
	 * 
	 * // necessary classes 
	 * require_once("module/application.class.php");
	 * 
	 * // pagination classes
	 * require_once("include/paging/my_pagina_class.php");
	 * 
	 * 
	 */
		
	
	// including files
	require_once("include/paging/my_pagina_class.php");	
	require_once("module/application.class.php");	
	
	

	// class declaration	
  	class categoryManager extends application{
  		
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
		 * display catagory for treatment.
		 *
		 * @access public
		 */
		function categoryManager()
		{
			
			// get top level categories
			$topCats = array();
			$query = 'SELECT category_name, category_id FROM category WHERE parent_category_id = 0';
			$res_cats = $this->execute_query($query);
			
			if(is_resource($res_cats))
			{
				while($c = $this->fetch_array($res_cats))
				{
					$topCats[$c['category_id']] = $c['category_name'];
				}
			}			
			
			$orderByClause = "";
			if ($this->value('sort') == '') 
			{
				$orderByClause = "category_name";
				$sortOrder['sortOrderImg1']='&nbsp;<img src="images/sort_asc.gif">';
				$sortOrder['sortOrderImg2']='';
				$sortOrder['order1'] = "";
				$sortOrder['order2'] = "";
				
			}
			else {
				
				switch ($this->value('sort'))
				{
					case 'category_name'		:
													$orderByClause = "category_name";
													
													if ($this->value('order') == '2' ) 
													{
														$orderByClause.= " DESC ";
														$_SESSION['order1'] = "";
														$sortOrder['order1'] = $_SESSION['order1'];
														$sortOrder['order2'] = $_SESSION['order2'];
														$sortOrder['sortOrderImg1']='&nbsp;<img src="images/sort_desc.gif">';
														$sortOrder['sortOrderImg2']='';
														
													}
													else 
													{
														
														$orderByClause.= " ASC ";
														$_SESSION['order1'] = "&order=2";
														$sortOrder['order1'] = $_SESSION['order1'];
														$sortOrder['order2'] = $_SESSION['order2'];	
														$sortOrder['sortOrderImg1']='&nbsp;<img src="images/sort_asc.gif">';
														$sortOrder['sortOrderImg2']='';													
													}
													
													break;
													
					case 'parent_category_id' 	:
													$orderByClause = "parent_category_id";
													if ($this->value('order') == '2' ) 
													{
														$orderByClause.= " DESC ";
														$_SESSION['order2'] = "";
														$sortOrder['order2'] = $_SESSION['order2'];
														$sortOrder['order1'] = $_SESSION['order1'];
														$sortOrder['sortOrderImg1']='';
														$sortOrder['sortOrderImg2']='&nbsp;<img src="images/sort_desc.gif">';
														
													}
													else 
													{
														$orderByClause.= " ASC ";
														$_SESSION['order2'] = "&order=2";
														$sortOrder['order2'] = $_SESSION['order2'];
														$sortOrder['order1'] = $_SESSION['order1'];		
														$sortOrder['sortOrderImg1']='';
														$sortOrder['sortOrderImg2']='&nbsp;<img src="images/sort_asc.gif">';												
													}
						
													break;						
				}			
				
			}
			
			$query = 'SELECT * FROM category WHERE status = 1 ORDER BY ' .$orderByClause;
			//$result = $this->execute_query($query);
			
			$link = $this->pagination($rows = 0,$query,'categoryManager','','');                                          

            $replace['link'] = $link['nav'];

            $result = $link['result'];  
			
			if ($this->num_rows($result)!=0)
			{			
				$sortOrder['action'] = "categoryManager";
				$replace['categoryTableHead'] = $this->build_template($this->get_template("categoryTableHead"),$sortOrder);
				$c=0;
				while ($item = $this->fetch_array($result))
				{
						
					$row['style'] = ($c++%2)?"line1":"line2";
					$row['categoryName'] = $item['category_name'];
					
					if ($item['parent_category_id'] == 0)
					{
						$row['categoryId'] = $item['category_id'];
						$row['parentCategory'] = 'TOP';
						$replace['categoryRecord'] .=  $this->build_template($this->get_template("categoryLinkRecord"),$row);						
						
					}
					else 
					{
						
						$row['categoryId'] = $item['category_id'];
						$row['parentCategory'] = $topCats[$item['parent_category_id']];
						$replace['categoryRecord'] .=  $this->build_template($this->get_template("categoryLinkRecord"),$row);	
					}					
					
					
					
				}
			
			}
			else 			
			{
				$replace['categoryTableHead'] = $this->build_template($this->get_template("categoryTableHead"),$sortOrder);
				$replace['categoryRecord'] = '<tr><td colspan = "3">No Treatment categories to list.<br/></td></tr>';		
				$replace['link'] = "&nbsp;";
			}
			
			$replace['operationName'] = "SELECT";
			                                       
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['footer'] = $this->build_template($this->get_template("footer"));
			//$replace['sidebar'] = $this->build_template($this->get_template("sidebar"));
			$replace['sidebar'] = $this->sidebar();
			$replace['filter'] = $this->build_template($this->get_template("categoryFilter"));
			$replace['body'] = $this->build_template($this->get_template("categoryManager"),$replace);
			$replace['browser_title'] = "Tx Xchange: Category Manager";
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}
		
		/**
        * Confirmation page, for new category.
        */
        function confirmCreateNewTreatmentCategory(){
            $this->output = $this->build_template($this->get_template("confirmCreateNewTreatmentCategory"));
        }
		/**
		 * Searching for category name.
		 *
		 * @access public
		 */
		function searchCategory()
		{
			
			// get top level categories		
			
			
			$searchStr = $this->value('search');
			$errorInSearchStr = false;	
		
		
			$validationResult = $this->alnumValidation($searchStr);
			if ($validationResult != 0)
			{
				$errorInSearchStr = true;
			}
			else 
			{
				$errorInSearchStr = false;
			}
			
			
			
			
			$topCats = array();
			$query = 'SELECT category_name, category_id FROM category WHERE parent_category_id = 0';
			$res_cats = $this->execute_query($query);
			
			if(is_resource($res_cats))
			{
				while($c = $this->fetch_array($res_cats))
				{
					$topCats[$c['category_id']] = $c['category_name'];
				}
			}			
			
			$orderByClause = "";
			if ($this->value('sort') == '') 
			{
				$orderByClause = "category_name";
				$sortOrder['sortOrderImg1']='&nbsp;<img src="images/sort_asc.gif">';
				$sortOrder['sortOrderImg2']='';
				$sortOrder['order1'] = "";
				$sortOrder['order2'] = "";
				
			}
			else {
				
				switch ($this->value('sort'))
				{
					case 'category_name'		:
													$orderByClause = "category_name";
													
													if ($this->value('order') == '2' ) 
													{
														$orderByClause.= " DESC ";
														$_SESSION['order1'] = "";
														$sortOrder['order1'] = $_SESSION['order1'];
														$sortOrder['order2'] = $_SESSION['order2'];
														$sortOrder['sortOrderImg1']='&nbsp;<img src="images/sort_desc.gif">';
														$sortOrder['sortOrderImg2']='';
														
													}
													else 
													{
														
														$orderByClause.= " ASC ";
														$_SESSION['order1'] = "&order=2";
														$sortOrder['order1'] = $_SESSION['order1'];
														$sortOrder['order2'] = $_SESSION['order2'];	
														$sortOrder['sortOrderImg1']='&nbsp;<img src="images/sort_asc.gif">';
														$sortOrder['sortOrderImg2']='';													
													}
													
													break;
													
					case 'parent_category_id' 	:
													$orderByClause = "parent_category_id";
													if ($this->value('order') == '2' ) 
													{
														$orderByClause.= " DESC ";
														$_SESSION['order2'] = "";
														$sortOrder['order2'] = $_SESSION['order2'];
														$sortOrder['order1'] = $_SESSION['order1'];
														$sortOrder['sortOrderImg1']='';
														$sortOrder['sortOrderImg2']='&nbsp;<img src="images/sort_desc.gif">';
														
													}
													else 
													{
														$orderByClause.= " ASC ";
														$_SESSION['order2'] = "&order=2";
														$sortOrder['order2'] = $_SESSION['order2'];
														$sortOrder['order1'] = $_SESSION['order1'];		
														$sortOrder['sortOrderImg1']='';
														$sortOrder['sortOrderImg2']='&nbsp;<img src="images/sort_asc.gif">';												
													}
						
													break;						
				}			
				
			}
			
			
			$query = "";
			$executeQuery = false;
			
			if (strlen($searchStr) == 0)
			{
				$query = 'SELECT * FROM category WHERE status = 1 ORDER BY ' .$orderByClause;	
				$executeQuery = true;
			}
			else if ($errorInSearchStr === false)
			{	
			
				$sqlWhere = $this->makeSearch(ALL_WORDS,$searchStr,'category_name');			
		
				$query = 'SELECT * FROM category WHERE'.$sqlWhere.' AND status = 1 ORDER BY ' .$orderByClause;
				$executeQuery = true;
			}
			else 
			{
				$executeQuery = false;
			}
			
			if (true == $executeQuery)
			{
							
				//$result = $this->execute_query($query);
				
				$link = $this->pagination($rows = 0,$query,'searchCategory',$this->value('search'),'');                                          

           		$replace['link'] = $link['nav'];

            	$result = $link['result'];  
				
				if ($this->num_rows($result)!=0)
				{			
					$sortOrder['search'] = $this->value('search');
					$replace['categoryTableHead'] = $this->build_template($this->get_template("categoryTableHeadSearch"),$sortOrder);
					$c=0;
					while ($item = $this->fetch_array($result))
					{
							
						$row['style'] = ($c++%2)?"line1":"line2";
						$row['categoryName'] = $item['category_name'];
						
						if ($item['parent_category_id'] == 0)
						{
							
							$row['parentCategory'] = 'TOP';
							$replace['categoryRecord'] .=  $this->build_template($this->get_template("categoryRecord"),$row);						
							
						}
						else 
						{
							
							$row['categoryId'] = $item['category_id'];
							$row['parentCategory'] = $topCats[$item['parent_category_id']];
							$replace['categoryRecord'] .=  $this->build_template($this->get_template("categoryLinkRecord"),$row);	
						}					
						
						
						
					}
				
				}
				else 
				{
					
					$replace['categoryTableHead'] = $this->build_template($this->get_template("categoryTableHeadSearch"),$sortOrder);
					$replace['categoryRecord'] = '<tr><td colspan = "3">No Treatment categories to list.<br/></td></tr>';		
					$replace['link'] = "&nbsp;";
				}
				
			}	
			else 
			{
				$replace['categoryTableHead'] = $this->build_template($this->get_template("categoryTableHeadSearch"),$sortOrder);
				$replace['categoryRecord'] = '<tr><td colspan = "3">No Treatment categories to list.<br/></td></tr>';		
				$replace['link'] = "&nbsp;";
			}
				
			$replace['operationName'] = "SELECT";
			
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['footer'] = $this->build_template($this->get_template("footer"));
			//$replace['sidebar'] = $this->build_template($this->get_template("sidebar"));
			$replace['sidebar'] = $this->sidebar();
			//$replace['search'] = $this->value('search');				
			//$replace['searchOn'] = $this->build_template($this->get_template("categorySearchOn"),$replace);	
			$replace['searchOn'] = "";
			$replace['filter'] = $this->build_template($this->get_template("categoryFilterClear"),$replace);
			$replace['body'] = $this->build_template($this->get_template("categoryManager"),$replace);
			$replace['browser_title'] = "Tx Xchange: Search Category";
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}
		
		
		/**
		 * Delete treatment category.
		 *
		 * @access public
		 */
		function deleteCategory()
		{
			/*
				Use to delete the category, category is not deleted from the database 
				only status field value is changed for the particular category			
			*/
			
			/*
				Also, may be that this same category is associated with some treatment so
				delete that record from treatment_category table
			*/
			
			$userInfo = $this->userInfo();
			if (!$userInfo)
			{
				header("location:index.php?sysAdmin");
			}
			else 
			{	
				$catId = (int) $this->value('id');
				
				//extra precaution check if user has access to delete the category				
				
				$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";								
				
				if ("SysAdmin" == $userType) 
				{					
					// only sys admin has access to delete the category				
				
					$queryUpdate = "UPDATE category SET status = 2 WHERE category_id = ". $catId;
					
					$this->execute_query($queryUpdate);	
					
					/*
						Removing the treatment_category association record(s)
					
					*/
					
					$queryDelRelation = "DELETE FROM treatment_category WHERE category_id = ".$catId;
					$this->execute_query($queryDelRelation);
					
					header("location:index.php?action=categoryManager");
		
				}	
				else
				{
					unset($_GET['id']);	
					header("location:index.php");
				}					
	
				
					
			}	
			
		}
		
		
		/**
		 * Create a new treatment category.
		 *
		 * @access public
		 */
		function createCategory()
		{
			/*
				Use to create the category				
			*/
			
			$userInfo = $this->userInfo();
			if (!$userInfo)
			{
				header("location:index.php?sysAdmin");
			}
			else 
			{	
								
				//extra precaution check if user has access to create the category				
				
				$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";								
				
				if ("SysAdmin" == $userType) 
				{					
					// only sys admin has access to create the category
					
					$fromPost = false;
					
					//check if form has been submitted or not

					if("Create Category" == $this->value('submitted') && "createCategory" == $_SESSION['categoryAction'])
					{							
						//that means form has been posted so check for validation	
						$error = $this->validateCategoryForm();									
						
						if (count($error) > 0)
						{								
							$fromPost = true;		
							$showForm = 1;													
						}
						else 
						{
							//first check the duplicates
							if( $this->value('parent_category_id') == 'parent' ){
							    $query = "SELECT category_name FROM category WHERE category_name = '".$this->value('category_name') ."' AND parent_category_id = '0' ";
                            }
                            else{
                                $query = "SELECT category_name FROM category WHERE category_name = '".$this->value('category_name') ."' AND parent_category_id = ".$this->value('parent_category_id');
                            }
                            
							$result = $this->execute_query($query);
							
							$noRows = $this->num_rows($result);									
							
							if ($noRows != 0)
							{
								$error = 'Category name: exists in the system in the same parent category. Please choose another name or parent category.';
								$fromPost = true;
								$showForm = 1;
							}
							else 
							{
								// perfect save the category		
								if( $this->value('parent_category_id') == "parent" ){
                                    $query = "INSERT INTO category (category_name,parent_category_id,creation_date,status) VALUES ('".$this->value('category_name')."','0',NOW(),1)";                        
                                }
                                else{
                                    $query = "INSERT INTO category (category_name,parent_category_id,creation_date,status) VALUES ('".$this->value('category_name')."',".$this->value('parent_category_id').",NOW(),1)";                        
                                }
								
								/*$formValues = array(
													'category_name' => $this->value('category_name'),
													'parent_category_id' => $this->value('parent_category_id'),
													'creation_date' => "NOW()",
													'status' => 1									
													);										
															
								*/
								$result = $this->execute_query($query);								
								
								if ($result)
								{									
									$_SESSION['categoryAction'] = ""; //to avoid resubmission	
									//unset($_SESSION['categoryAction']);																				
									//show article listing with message article edited successfully
									$showForm = 0;	
								}
								else 
								{
									$error = 'Unable to insert into the database.';								
									$fromPost = true;
									$showForm = 1;										
								}
															
									
							}						
														
							
						}						
					}
					elseif ("Save Category" == $this->value('submitted') && (isset($_SESSION['categoryAction']) && "" == $_SESSION['categoryAction'])) 
					{
							//case resubmission that is user has pressed F5 or refreshed the page after form submission
							// so we need to prevent resubmission of form								
							//show category listing without any message
							$showForm = 0;								
							
					}/*
					elseif (1)
					{
						/*
							here we will have code if user has clicked on pagination
							then we need to display form along with 2nd page listing of categories 
						*//*
						
					}*/
					else 
					{
							//first time
							$fromPost = false;
							$showForm = 1;
					}	
					
					
					
					if (1 == $showForm)
					{
						
												
						// this block of code displays the category create form along with category listing
						
						/*******************Block of code for category listing*************************/
						
						// get top level categories
						$topCats = array();
						$query = 'SELECT category_name, category_id FROM category WHERE parent_category_id = 0';
						$res_cats = $this->execute_query($query);
						
						if(is_resource($res_cats))
						{
							while($c = $this->fetch_array($res_cats))
							{
								$topCats[$c['category_id']] = $c['category_name'];
							}
						}	
						
						$orderByClause = "";
						
						if ($this->value('sort') == '') 
						{
							$orderByClause = "category_name";
							$sortOrder['sortOrderImg1']='&nbsp;<img src="images/sort_asc.gif">';
							$sortOrder['sortOrderImg2']='';
							$sortOrder['order1'] = "";
							$sortOrder['order2'] = "";
							
						}
						else {
							
							switch ($this->value('sort'))
							{
								case 'category_name'		:
																$orderByClause = "category_name";
																
																if ($this->value('order') == '2' ) 
																{
																	$orderByClause.= " DESC ";
																	$_SESSION['order1'] = "";
																	$sortOrder['order1'] = $_SESSION['order1'];
																	$sortOrder['order2'] = $_SESSION['order2'];
																	$sortOrder['sortOrderImg1']='&nbsp;<img src="images/sort_desc.gif">';
																	$sortOrder['sortOrderImg2']='';
																	
																}
																else 
																{
																	
																	$orderByClause.= " ASC ";
																	$_SESSION['order1'] = "&order=2";
																	$sortOrder['order1'] = $_SESSION['order1'];
																	$sortOrder['order2'] = $_SESSION['order2'];	
																	$sortOrder['sortOrderImg1']='&nbsp;<img src="images/sort_asc.gif">';
																	$sortOrder['sortOrderImg2']='';													
																}
																
																break;
																
								case 'parent_category_id' 	:
																$orderByClause = "parent_category_id";
																if ($this->value('order') == '2' ) 
																{
																	$orderByClause.= " DESC ";
																	$_SESSION['order2'] = "";
																	$sortOrder['order2'] = $_SESSION['order2'];
																	$sortOrder['order1'] = $_SESSION['order1'];
																	$sortOrder['sortOrderImg1']='';
																	$sortOrder['sortOrderImg2']='&nbsp;<img src="images/sort_desc.gif">';
																	
																}
																else 
																{
																	$orderByClause.= " ASC ";
																	$_SESSION['order2'] = "&order=2";
																	$sortOrder['order2'] = $_SESSION['order2'];
																	$sortOrder['order1'] = $_SESSION['order1'];		
																	$sortOrder['sortOrderImg1']='';
																	$sortOrder['sortOrderImg2']='&nbsp;<img src="images/sort_asc.gif">';												
																}
									
																break;						
							}			
							
						}		
						
						$query = 'SELECT * FROM category WHERE status = 1 ORDER BY '.$orderByClause;
						//$result = $this->execute_query($query);
						
						$link = $this->pagination($rows = 0,$query,'createCategory','','');                                          

           				$replace['link'] = $link['nav'];

            			$result = $link['result']; 
						
						if ($this->num_rows($result)!=0)
						{		
							$sortOrder['action'] = "createCategory";
							$replace['categoryTableHead'] = $this->build_template($this->get_template("categoryTableHead"),$sortOrder);
							$c=0;
							while ($item = $this->fetch_array($result))
							{
									
								$row['style'] = ($c++%2)?"line1":"line2";
								$row['categoryName'] = $item['category_name'];
								
								if ($item['parent_category_id'] == 0)
								{
									$row['categoryId'] = $item['category_id'];
									$row['parentCategory'] = 'TOP';
									$replace['categoryRecord'] .=  $this->build_template($this->get_template("categoryLinkRecord"),$row);						
									
								}
								else 
								{
									
									$row['categoryId'] = $item['category_id'];
									$row['parentCategory'] = $topCats[$item['parent_category_id']];
									$replace['categoryRecord'] .=  $this->build_template($this->get_template("categoryLinkRecord"),$row);	
								}					
								
								
								
							}
						
						}
						else 
						{
							
							$replace['categoryTableHead'] = $this->build_template($this->get_template("categoryTableHead"),$sortOrder);
							$replace['categoryRecord'] = '<tr><td colspan = "3">No Treatment categories to list.<br/></td></tr>';		
							$replace['link'] = "&nbsp;";
						}						
						
						/******************End*************************************/
						
						$selected = "";		
						//$replace['breadcrumbCatName'] = "<a href='index.php?action=categoryManager'><SPAN CLASS='CURRENT_ACTION'> ".strtoupper($category_info['category_name'])."</SPAN></a> / ";		
						
						$replace['operationName'] = "CREATE";							
						
						$replace['category_name'] = ($fromPost)? $this->value('category_name') : ""; 																										
						
						$selected = ($fromPost)? $this->value('parent_category_id') : ""; 							
						
						$replace['error'] = $error;	
						
						$_SESSION['categoryAction'] = 'createCategory'; 									
						
						$topCats = array(""=>"Choose...") + array("parent"=>"New Parent Category") + $topCats;									
											
						$replace['options']	= $this->build_select_option($topCats,$selected);				
						
							
						$replace['header'] = $this->build_template($this->get_template("header"));
						$replace['footer'] = $this->build_template($this->get_template("footer"));
						//$replace['sidebar'] = $this->build_template($this->get_template("sidebar"));
						$replace['sidebar'] = $this->sidebar();
						$replace['filter'] = $this->build_template($this->get_template("categoryFilter"));	
						$replace['categoryForm'] = $this->build_template($this->get_template("categoryAddForm"),$replace);
						$replace['body'] = $this->build_template($this->get_template("categoryManager"),$replace);
						$replace['browser_title'] = "Tx Xchange: Create Category";
						$this->output = $this->build_template($this->get_template("main"),$replace);						
						
					}
					else 
					{
						// this block of code redirects to the category listing
						header("location:index.php?action=categoryManager");
					}							
				
					
				}	
				
			}					
	
			
		}
		
		
		/**
		 * Edit an existing treatment category.
		 *
		 * @access public
		 */
		function editCategory()
		{
			/*
				Use to edit the category				
			*/			
			
			$userInfo = $this->userInfo();
			if (!$userInfo)
			{
				header("location:index.php?sysAdmin");
			}
			else 
			{	
				$catId = (int) $this->value('id');
				
				//extra precaution check if user has access to edit the category				
				
				$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";								
				
				if ("SysAdmin" == $userType) 
				{					
					// only sys admin has access to edit the category

					//$query = "SELECT * FROM category WHERE parent_category_id <> 0 AND status <> 2 AND category_id = '". $catId ."'";
                    $query = "SELECT * FROM category WHERE  status =1 AND category_id = '". $catId ."'";
                    $result = $this->execute_query($query);		
					
					if (!$result) 
					{
						// that is invalid category id  					
						//unset($_GET['id']);	
						header("location:index.php?action=categoryManager");
                        exit();
					}
					else 
					{			
						//its a valid category id so go for edit
						$category_info = $this->fetch_array($result);						
						
						$fromPost = false;
						
						//check if form has been submitted or not

						if("Save Category" == $this->value('submitted') && "saveCategory" == $_SESSION['categoryAction'])
						{							
							//that means form has been posted so check for validation	
							$error = $this->validateCategoryForm();									
							
							if (count($error) > 0)
							{								
								$fromPost = true;		
								$showForm = 1;													
							}
							else 
							{
								//first check the duplicates
                                $parent_category_id = $this->value('parent_category_id');
								if( $parent_category_id == 'parent'){
                                    $parent_category_id = 0;
                                }
								$query = "SELECT category_name FROM category WHERE category_name = '".$this->value('category_name') ."' AND category_id != '".$catId ."' AND parent_category_id = '".$parent_category_id."'";
								$result = $this->execute_query($query);
								
								$noRows = $this->num_rows($result);									
								
								if ($noRows != 0)
								{
									$error = 'Category name: exists in the system in the same parent category. Please choose another name or parent category.';
									$fromPost = true;
									$showForm = 1;
								}
								else 
								{
									// perfect save the category
									
									$formValues = array(
														'category_name' => $this->value('category_name'),
														'parent_category_id' => $parent_category_id									
														);										
									
									$where = "category_id = '".$catId ."'";
									$sql = "update category set category_name = '{$this->value('category_name')}', parent_category_id = '{$parent_category_id}' 
                                            where category_id = '{$catId }' ";
                                       
                                    $result = @mysql_query($sql);
                                    
									//$result =$this->update('category',$formValues,$where);
									
									if ($result)
									{									
										// unset $_SESSION['categoryAction']
										$_SESSION['categoryAction'] = ""; //to avoid resubmission	
										//unset($_SESSION['categoryAction']);																							
										//show article listing with message article edited successfully
										$showForm = 0;	
									}
									else 
									{
										$error = 'Unable to update into the database.';								
										$fromPost = true;
										$showForm = 1;										
									}
																
										
								}						
															
								
							}						
						}
						elseif ("Save Category" == $this->value('submitted') && (isset($_SESSION['categoryAction']) && "" == $_SESSION['categoryAction'])) 
						{
								//case resubmission that is user has pressed F5 or refreshed the page after form submission
								// so we need to prevent resubmission of form								
								//show category listing without any message
								$showForm = 0;								
								
						}/*
						elseif (1)
						{
							/*
								here we will have code if user has clicked on pagination
								then we need to display form along with 2nd page listing of categories 
							*//*
							
						}*/
						else 
						{
								//first time
								$fromPost = false;
								$showForm = 1;
						}	
						
						
						
						if (1 == $showForm)
						{
							
													
							// this block of code displays the category edit form along with category listing
							
							/*******************Block of code for category listing*************************/
							
							// get top level categories
							$topCats = array();
							$query = 'SELECT category_name, category_id FROM category WHERE parent_category_id = 0';
							$res_cats = $this->execute_query($query);
							
							if(is_resource($res_cats))
							{
								while($c = $this->fetch_array($res_cats))
								{
									$topCats[$c['category_id']] = $c['category_name'];
								}
							}	
							
							$orderByClause = "";
							if ($this->value('sort') == '') 
							{
								$orderByClause = "category_name";
								$sortOrder['sortOrderImg1']='&nbsp;<img src="images/sort_asc.gif">';
								$sortOrder['sortOrderImg2']='';
								$sortOrder['order1'] = "";
								$sortOrder['order2'] = "";
								
							}
							else {
								
								switch ($this->value('sort'))
								{
									case 'category_name'		:
																	$orderByClause = "category_name";
																	
																	if ($this->value('order') == '2' ) 
																	{
																		$orderByClause.= " DESC ";
																		$_SESSION['order1'] = "";
																		$sortOrder['order1'] = $_SESSION['order1'];
																		$sortOrder['order2'] = $_SESSION['order2'];
																		$sortOrder['sortOrderImg1']='&nbsp;<img src="images/sort_desc.gif">';
																		$sortOrder['sortOrderImg2']='';
																		
																	}
																	else 
																	{
																		
																		$orderByClause.= " ASC ";
																		$_SESSION['order1'] = "&order=2";
																		$sortOrder['order1'] = $_SESSION['order1'];
																		$sortOrder['order2'] = $_SESSION['order2'];	
																		$sortOrder['sortOrderImg1']='&nbsp;<img src="images/sort_asc.gif">';
																		$sortOrder['sortOrderImg2']='';													
																	}
																	
																	break;
																	
									case 'parent_category_id' 	:
																	$orderByClause = "parent_category_id";
																	if ($this->value('order') == '2' ) 
																	{
																		$orderByClause.= " DESC ";
																		$_SESSION['order2'] = "";
																		$sortOrder['order2'] = $_SESSION['order2'];
																		$sortOrder['order1'] = $_SESSION['order1'];
																		$sortOrder['sortOrderImg1']='';
																		$sortOrder['sortOrderImg2']='&nbsp;<img src="images/sort_desc.gif">';
																		
																	}
																	else 
																	{
																		$orderByClause.= " ASC ";
																		$_SESSION['order2'] = "&order=2";
																		$sortOrder['order2'] = $_SESSION['order2'];
																		$sortOrder['order1'] = $_SESSION['order1'];		
																		$sortOrder['sortOrderImg1']='';
																		$sortOrder['sortOrderImg2']='&nbsp;<img src="images/sort_asc.gif">';												
																	}
										
																	break;						
								}			
								
							}		
							
							$query = 'SELECT * FROM category WHERE status = 1 ORDER BY '.$orderByClause;
							//$result = $this->execute_query($query);
							
							$link = $this->pagination($rows = 0,$query,'editCategory','','');                                          

           					$replace['link'] = $link['nav'];

            				$result = $link['result']; 
							
							if ($this->num_rows($result)!=0)
							{			
								$sortOrder['category_id'] = $catId;								
								$replace['categoryTableHead'] = $this->build_template($this->get_template("categoryTableHeadForm"),$sortOrder);
								$c=0;
								while ($item = $this->fetch_array($result))
								{
										
									$row['style'] = ($c++%2)?"line1":"line2";
									$row['categoryName'] = $item['category_name'];
									
									if ($item['parent_category_id'] == 0)
									{
										$row['categoryId'] = $item['category_id']; 
										$row['parentCategory'] = 'TOP';
										$replace['categoryRecord'] .=  $this->build_template($this->get_template("categoryLinkRecord"),$row);						
									}
									else 
									{
										$row['categoryId'] = $item['category_id'];
										$row['parentCategory'] = $topCats[$item['parent_category_id']];
										$replace['categoryRecord'] .=  $this->build_template($this->get_template("categoryLinkRecord"),$row);	
									}					
									
									
									
								}
							
							}
							else 
							{
								
								$replace['categoryTableHead'] = $this->build_template($this->get_template("categoryTableHeadForm"),$sortOrder);
								$replace['categoryRecord'] = '<tr><td colspan = "3">No Treatment categories to list.<br/></td></tr>';		
								$replace['link'] = "&nbsp;";	
							}						
							
							/******************End*************************************/
							$selected = "";		
							$replace['breadcrumbCatName'] = "<a href='index.php?action=categoryManager'><SPAN CLASS='CURRENT_ACTION'> ".strtoupper($category_info['category_name'])."</SPAN></a> / ";		
							
							$replace['operationName'] = "EDIT";							
							
							$replace['category_name'] = ($fromPost)? $this->value('category_name') : $category_info['category_name']; 																				
							
							$replace['category_id'] = $catId;
							$selected = ($fromPost)? $this->value('parent_category_id') : $category_info['parent_category_id']; 							
                            if( $selected == '0' ){
                                $selected = 'parent';
                            }
							
							$replace['error'] = $error;	
							
							$_SESSION['categoryAction'] = 'saveCategory'; 									
							
							$topCats = array(""=>"Choose...") + array("parent"=>"New Parent Category") + $topCats;									
												
							$replace['options']	= $this->build_select_option($topCats,$selected);				
							
								
							$replace['header'] = $this->build_template($this->get_template("header"));
							$replace['footer'] = $this->build_template($this->get_template("footer"));
							//$replace['sidebar'] = $this->build_template($this->get_template("sidebar"));
							$replace['sidebar'] = $this->sidebar();
							$replace['filter'] = $this->build_template($this->get_template("categoryFilter"));	
							$replace['categoryForm'] = $this->build_template($this->get_template("categoryEditForm"),$replace);
							$replace['body'] = $this->build_template($this->get_template("categoryManager"),$replace);
							$replace['browser_title'] = "Tx Xchange: Edit Category";
							$this->output = $this->build_template($this->get_template("main"),$replace);						
							
						}
						else 
						{
							// this block of code redirects to the category listing
							header("location:index.php?action=categoryManager");
						}							
					
					
					}	
				}
				else 
				{
					unset($_GET['id']);	
					header("location:index.php");
				}
			}					
	
			
		}		
		
		
		/**
		 * Validing category form.
		 *
		 * @return string
		 * @access public
		 */
		function validateCategoryForm()
		{
			
			$error = array();			

			if(trim($this->value('category_name')) == "" ){

				$error[] = "Enter the category name";

			}
			
			if(trim($this->value('parent_category_id')) == ""){

				$error[] = "Choose the parent category for this category";

			}

			
			if(count($error) > 0 ){

				$error = $this->show_error($error);

			}			
			
			return $error;	
			
			
		}
		
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
		
		
		/**
		 * Doing Alpha Numeric validation.
		 *
		 * @param string $str
		 * @return integer
		 * @access public
		 */
		function alnumValidation($str)
		{
			$str_pattern = '/[^[:alnum:][^"][^\']\:\;\(\)\#\,\-\/\.\%\+\?\&\s\r\n]/';
			
			return preg_match_all($str_pattern, $str, $arr_matches);		
			
		}
		
		
		/**
		 * Populating Side Panel.
		 *
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
	} // Class Closed
	
	
	// creating object of the class
	$obj = new categoryManager();
?>
