<?php
	/**
	 * 
	 * Copyright (c) 2008 Tx Xchange.
	 * 
	 * This class is for following functionality:
	 * 1) Shows list of system Template plan.
	 * 2) Create Template plan.
	 * 3) Lists Treatment.
	 * 4) Customize instruction of Treatment.
	 * 5) Associate Treatment and article to Template plan.
	 *  
	 * Neccessary class for getting access of application specific methods.
	 * require_once("module/application.class.php");
	 */
	require_once("include/paging/my_pagina_class.php");
	require_once("module/application.class.php");

  	class systemPlan extends application{
		/**
  		 * Action variable is used to hold the action param value.
  		 *
  		 * @var String
  		 * @access Private
  		 */
  		private $action;
  		/**
  		 * This can used in future enhancement.
  		 *
  		 * @var String
  		 * @access private
  		 */
		private $field_array;
		
  		/**
  		 * This can used in future enhancement.
  		 *
  		 * @var String
  		 * @access Private
  		 */
		private $error;
		/**
  		 * Processed out is assigned to this member.
  		 *
  		 * @var String
  		 * @access Private
  		 */
		private $output;

		
		/**
		 * In this method following activities are performed
		 * 1) Checking action parameter, weather its holding any value or not. 
		 * 	  If it is not holding any value we are assigning default value in it.
		 * 2) Check user is logged in or not.
		 * 3) Check the logged in user have privileage or not to access this class.
		 * 4) Show response by using display() method.
		 * @param none
		 * @return none
		 * @access public
		 */
		function __construct(){
			parent::__construct();
			if($this->value('action')){
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
		 * This function shows list of Template plan for system admin.
		 *
		 * @access public
		 */
		function systemPlan(){
			if($this->value('act') == 'deletePlan'){
				if( $this->value('plan_id') != "" && is_numeric($this->value('plan_id'))){
					$query = "update plan set status = 3 where  user_type = '{$this->userInfo('usertype_id')}' and plan_id = '{$this->value('plan_id')}' ";
					$this->execute_query($query);
				}
			}
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();
			$replace['planListHead'] = $this->build_template($this->get_template("planListHead"));
			include_once("template/sysadmin/systemArray.php");
			$this->formArray = $planListAction;
			if($this->value('sort') != ""){
				if($this->value('order') == 'desc' ){
					$orderby = " order by {$this->value('sort')} desc ";
				}
				else{
					$orderby = " order by {$this->value('sort')} ";
				}
			}
			else{
				$orderby = " order by p.plan_name ";
			}
			$replace['search'] = "";
			if($this->value('search') == "" && $this->value('restore_search') != "" ){
				$_REQUEST['search'] = $this->value('restore_search');
			}
			
			if($this->value('search') != ""){
				$replace['search'] = $this->value('search');
				 $query = "select *,(select count(*) from plan_treatment 
						  	where plan_treatment.plan_id = p.plan_id) AS no_treatments,
							(select count(*) from plan_article pa inner join article a on a.article_id = pa.article_id and a.status = 1 where pa.plan_id = p.plan_id) AS no_articles
							from plan p  where  p.user_type = '{$this->userInfo('usertype_id')}' and p.patient_id is null and p.status = 1 and p.plan_name like '%{$this->value('search')}%'  
				 {$orderby}";     
				$sqlcount = "select count(1) from plan p  where  p.user_type = '{$this->userInfo('usertype_id')}' and p.patient_id is null and p.status = 1 and p.plan_name like '%{$this->value('search')}%'  
				 {$orderby}";     
			
            }
			else{
				$query = "select *,(select count(*) from plan_treatment 
						  where plan_treatment.plan_id = p.plan_id) AS no_treatments,
						(select count(*) from plan_article pa inner join article a on a.article_id = pa.article_id and a.status = 1 where pa.plan_id = p.plan_id) AS no_articles
						from plan p where  p.user_type = '{$this->userInfo('usertype_id')}' and p.patient_id is null and p.status = 1 {$orderby}";
                        
              $sqlcount="select count(1) from plan p where  p.user_type = '{$this->userInfo('usertype_id')}' and p.patient_id is null and p.status = 1 {$orderby}";           
			}
		
			$link = $this->pagination($rows = 0,$query,$this->value('action'),$this->value('search'),'','','',$sqlcount);				
			$replace['link'] = $link['nav'];
			$result = $link['result'];								  	
			
			if(is_resource($result)){
				while($row = $this->fetch_array($result)){
					$row['style'] = ($c++%2)?"line1":"line2";
					$row['status'] = $this->config['patientStatus'][$row['status']];
					$row['actionOption'] = $this->build_select_option($this->formArray);
					$replace['planRecord'] .= $this->build_template($this->get_template("planRecord"),$row);
				}
				if($this->num_rows($result) == 0){
					$replace['colspan'] = 5;
					$replace['planRecord'] = $this->build_template($this->get_template("no_record_found"),$replace);
				}
			}
			
			$replace['planListHead'] = $this->build_template($this->get_template("planListHead"),$this->table_heading($planListHead,"p.plan_name"));
			$replace['body'] = $this->build_template($this->get_template("planList"),$replace);
			$replace['browser_title'] = "Tx Xchange: System Plan";
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}
		/**
		 * This function creates Template plan.
		 *
		 * @access public
		 */
		function createNewSystemPlan(){
			$replace['plan_name'] = "";
			$replace['plan_id'] = "";
			$replace['patient_id'] = $this->value('patient_id');
			$replace['checked'] = "";
			if($this->value('act') == "plan_edit" && $this->value('plan_id') != "" ){
				$query = "select * from plan where  user_type = '{$this->userInfo('usertype_id')}' and plan_id = '{$this->value('plan_id')}' ";
				$result = $this->execute_query($query);
				if($row = $this->fetch_array($result)){
					$replace['plan_name'] = $row['plan_name'];
					$replace['plan_id'] = $row['plan_id'];
					$replace['checked'] = $row['is_public'] ?'checked="true"':"";
				}
			}
			elseif ($this->value('plan_id') != ""){
				$replace['plan_name'] = $this->value('plan_name');
				$replace['plan_id'] = $this->value('plan_id');
				$replace['checked'] = $this->value('is_public') ?'checked="true"':"";
			}
			if( $this->value('submitted_x') != "" ){
				
				if( $this->value('plan_name') == ""){
					$error[] = "Please enter Plan name.";
				}
				else{ 
						if( $this->value('plan_id') != ""){
							$query = " select * from plan where user_type = '{$this->userInfo('usertype_id')}' and plan_name = '".addslashes($this->value('plan_name'))."' and plan_id != '{$this->value('plan_id')}' and patient_id is null and status = 1";
						}
						else{
							$query = " select * from plan where user_type = '{$this->userInfo('usertype_id')}' and plan_name = '".addslashes($this->value('plan_name'))."' and patient_id is null and status = 1";
						}
						
						$result = $this->execute_query($query);
						if($this->num_rows($result) > 0 ){
							$error[] = "Plan name already exist."; 
						}
				}
				if(isset($error) && count($error) > 0){
					$replace['error'] = $this->show_error($error);
				}
				else{
					$data = array(
						'plan_name' => $this->value('plan_name'),
						'user_type' => $this->userInfo('usertype_id'),
						'status' => '1',
						//'is_public' => $this->value('is_public')=="1"?'1':null 
					);
					if($this->value('plan_id')){
						$where = " plan_id =  '{$this->value('plan_id')}' ";
						$data['modified'] = date("Y-m-d");
						$this->update($this->config['table']['plan'],$data,$where);
						header("location:index.php?action=systemSelectTreatment&plan_id={$this->value('plan_id')}");
					}
					else{
						$data['user_id'] = $this->userInfo('user_id');
						$data['creation_date'] = date("Y-m-d H:i:s");
						$data['modified'] = date("Y-m-d H:i:s");
                         // is_public field is used for publishing Template Plan.
                        $data['is_public'] = null;
                        $data['status'] = 3;
                        
						if($this->insert($this->config['table']['plan'],$data)){
							$plan_id = $this->insert_id();
                            //$_SESSION['plan_id'] = $plan_id;
							header("location:index.php?action=systemSelectTreatment&plan_id={$plan_id}");
						}
					}
				}
			}
			
			$nav_bar = array(
								'plan' => ($this->value('plan_id') != "")?'<a href="index.php?action=createNewSystemPlan&act=plan_edit&plan_id='.$this->value("plan_id").'"><img src="images/01_plan_gray.gif" /></a>':'<img src="images/01_plan_gray.gif" />',
								'step1' => ($this->value('plan_id') != "")?'<a href="index.php?action=createNewSystemPlan&act=plan_edit&plan_id='.$this->value("plan_id").'"><img src="images/stepIcons_1_gray_sm.gif"></a>':'<img src="images/stepIcons_1_gray_sm.gif">',
								'step2' => ($this->value('plan_id') != "")?'<a href="index.php?action=systemSelectTreatment&plan_id='.$this->value("plan_id").'"><img src="images/stepIcons_2_gray_sm.gif"></a>':'<img src="images/stepIcons_2_gray_sm.gif">',
								'step3' => ($this->value('plan_id') != "")?'<a href="index.php?action=systemCustomize_instruction&plan_id='.$this->value("plan_id").'"><img src="images/stepIcons_3_gray_sm.gif"></a>':'<img src="images/stepIcons_3_gray_sm.gif">',
								'step4' => ($this->value('plan_id') != "")?'<a href="index.php?action=systemCustomize_articles&plan_id='.$this->value("plan_id").'"><img src="images/stepIcons_4_gray_sm.gif"></a>':'<img src="images/stepIcons_4_gray_sm.gif">',
								
						);
			if(is_array($replace)){
				$replace = $replace + $nav_bar;
			}
			
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['footer'] = $this->build_template($this->get_template("footer"));
			$replace['sidebar'] = $this->sidebar();
			$replace['footer'] = $this->build_template($this->get_template("footer"));
			$replace['body'] = $this->build_template($this->get_template("createNewPlan"),$replace);
			$replace['browser_title'] = "Tx Xchange: Create New System Plan";
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}
		/**
		 * This function lists treatments and helps in selecting treatment for Template plan.
		 *
		 * @access public
		 */
		function systemSelectTreatment(){
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();
			$replace['plan_id'] = $this->value('plan_id');
			$replace['plan_title'] = strtoupper($this->get_field($this->value('plan_id'), $this->config['table']['plan'], "plan_name"));
			
			// Build Back button Url
			$replace['act'] = "plan_edit";
			$replace['action'] = "createNewSystemPlan";
			$backUrl = array('action','plan_id','type','act');
			$replace['back_url'] = $this->buildBackUrl($backUrl,$replace);
			// End of build Back button Url
			 
			$replace['body'] = $this->build_template($this->get_template("selectTreatment"),$replace);
			$replace['browser_title'] = "Tx Xchange: Select Treatment";
			$this->output = $this->build_template($this->get_template("main"),$replace);			
		}
		/**
		 * This funciton lists treatments of any Template plans.
		 *
		 * @access public
		 */
		function systemCustomize_instruction(){
			if( $this->value("edit_instruction") != "1" )
				$_SESSION['edit_record'] = array(); 
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();
			$replace['plan_id'] = $this->value('plan_id');
			$replace['plan_title'] = strtoupper($this->get_field($this->value('plan_id'), $this->config['table']['plan'], "plan_name"));
			$replace['customize_instruction_head'] = $this->build_template($this->get_template("customize_instruction_head"));
			include_once("template/sysadmin/systemArray.php");
			$this->formArray = $customizeInstructionAction;
			$query = "select pt.plan_treatment_id,pt.treatment_order,pt.treatment_id,trt.treatment_name,
						pt.instruction,pt.benefit,pt.sets,pt.reps,pt.hold,pt.lrb,pt.plan_id from plan_treatment pt
						inner join treatment trt on pt.treatment_id = trt.treatment_id 
						where pt.plan_id = '{$this->value('plan_id')}' order by pt.treatment_order";
						
			$result = $this->execute_query($query);								  	
			if(is_resource($result)){
				while($row = $this->fetch_array($result)){
					$row['style'] = ($c++%2)?"line1":"line2";
					if( $this->value("edit_instruction") == "1" && is_array($_SESSION['edit_record']) && in_array($row['plan_treatment_id'],$_SESSION['edit_record']) ){
						$row['show_selected'] = 'style="border-top: #090 1px solid; border-bottom: #090 1px solid; background-color: #cfc;"';
					}
					else{
						$row['show_selected'] = "";
					}
					if($row['treatment_name'] == ""){
						$row['treatment_name'] = "&nbsp;";
					}
					if($row['instruction'] == ""){
						$row['instruction'] = "&nbsp;";
					}
					if($row['benefit'] == ""){
						$row['benefit'] = "&nbsp;";
					}
					if($row['sets'] == ""){
						$row['sets'] = "&nbsp;";
					}
					if($row['reps'] == ""){
						$row['reps'] = "&nbsp;";
					}
					if($row['hold'] == ""){
						$row['hold'] = "&nbsp;";
					}
					
					$row['lrb'] = $this->config['lrb'][$row['lrb']];
					if($row['lrb'] == ""){
						$row['lrb'] = "None";
					}
					
					$row['actionOption'] = $this->build_select_option($this->formArray);
                    
                    $filename = $_SERVER['DOCUMENT_ROOT']."/asset/images/treatment/{$row['treatment_id']}/thumb.jpg";
                    if( file_exists($filename) === true ){
                        $row['treatment_image'] = "../asset/images/treatment/{$row['treatment_id']}/thumb.jpg";
                    }
                    else{
                        $row['treatment_image'] = "../images/img-no-image.jpg";
                    }
					$replace['customize_instruction_rec'] .= 
					$this->build_template($this->get_template("customize_instruction_rec"),$row);
				}
			}	

			// Build Back button Url
			$replace['act'] = "plan_edit";
			$replace['action'] = "systemSelectTreatment";
			$backUrl = array('action','plan_id','type','act');
			$replace['back_url'] = $this->buildBackUrl($backUrl,$replace);
			// End of build Back button Url
			
			$replace['body'] = $this->build_template($this->get_template("customize_instruction"),$replace);
			$replace['browser_title'] = "Tx Xchange: Customize Instruction";
			$this->output = $this->build_template($this->get_template("main"),$replace);			
		}
		/**
		 * This funciton helps in editing instruction,benefits,lrb,etc of selected treatment for Template plans.
		 *
		 * @access public
		 */
		function systemEdit_instruction(){
			include_once("template/sysadmin/systemArray.php");
			if( $this->value('update') == "update" && $this->value('id') != ""){
				$where = " plan_treatment_id = '{$this->value('id')}' ";
				$updateArr = $this->fillForm($instructionFormArray,true);
				if($this->update($this->config['table']['plan_treatment'],$updateArr,$where)){
					$_SESSION['edit_record'][] = $this->value('id');
					?>
					<script language="javascript" >
						window.close();
						window.opener.location.href = "index.php?action=systemCustomize_instruction&plan_id=<?php echo $this->get_field($this->value('id'),"plan_treatment","plan_id") ?>&edit_instruction=1";
					</script>
					<?php	
					
				}
				
				$row = $this->fillForm($instructionFormArray,true);
				if(is_array($row)){
					$row['plan_treatment_id'] = $this->value('id');
					$row['lrboption'] = $this->build_select_option($this->config['lrb'],$row['lrb']);
					$mainRegion = $this->build_template($this->get_template("edit_instruction"),$row);
				}
			}
			else{
				$query = "select pt.plan_treatment_id,pl.plan_name,pt.treatment_order,pt.treatment_id,trt.treatment_name,
							pt.instruction,pt.benefit,pt.sets,pt.reps,pt.hold,pt.lrb,pt.plan_id from plan_treatment pt
							inner join treatment trt on pt.treatment_id = trt.treatment_id 
							inner join plan pl on pl.plan_id = pt.plan_id 
							where pt.plan_treatment_id = '{$this->value('id')}' ";
				$result = $this->execute_query($query);								  	
				if(is_resource($result)){
					if($row = $this->fetch_array($result)){
						$row['lrboption'] = $this->build_select_option($this->config['lrb'],$row['lrb']);
						$mainRegion = $this->build_template($this->get_template("edit_instruction"),$row);
					}
				}	
				else{
					$mainRegion = "Record Not found.";
				}
			}	
			$replace['mainRegion'] = $mainRegion;
			$replace['browserTitle'] = " Edit Instruction";
			$this->output = $this->build_template($this->get_template("main"),$replace);			
		}
		/**
		 * This function helps in assocating any article with Template plan.
		 *
		 * @access public
		 */
		function systemCustomize_articles(){
			if($this->value('act') == "remove_article" && $this->value('article_id') != "" ){
				$this->remove_article($this->value('article_id'));
			}
			if($this->value('act') == "add_article" && $this->value('article_id') != "" ){
				$this->add_article($this->value('article_id'));
			}
			$replace['plan_id'] = $this->value('plan_id');
			$replace['plan_title'] = strtoupper($this->get_field($this->value('plan_id'), $this->config['table']['plan'], "plan_name"));
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();
			$replace['plan_id'] = $this->value('plan_id');
			$replace['added_articles'] = $this->added_articles( $this->value('plan_id'));
			$arr = $this->article_library();
			if(isset($arr) && is_array($arr)){
				$replace['article_library'] = $arr['article_library'];
				$replace['link'] = $arr['link'];
				$replace['article_head'] = $arr['article_head'];
				$replace['sort'] = $this->value('sort');
				$replace['order'] = $this->value('order');
			}
			
			$replace['type_of_button'] = $this->build_template($this->get_template("save_button_template"),$replace);
            
            // Get plan ID
            $plan_id = $this->value('plan_id');
            if( is_numeric($plan_id)){
               $query = "select is_public from plan where plan_id = '{$plan_id}' "; 
               $result = @mysql_query($query);
               if( $row = @mysql_fetch_array($result) ){
                   $is_public = $row['is_public'];
               }
            }   
            
            // Check value of is_public field and select tempalate as per value of is_public field
            if( $is_public == 1 ){
                $replace['publish_button'] = $this->build_template($this->get_template("save_and_publish_inactive_button_template"),$replace);
            }
            else{
                $replace['publish_button'] = $this->build_template($this->get_template("save_and_publish_active_button_template"),$replace);
            }
			
			// Build Back button Url
			$replace['act'] = "plan_edit";
			$replace['action'] = "systemCustomize_instruction";
			$backUrl = array('action','plan_id','type','act');
			$replace['back_url'] = $this->buildBackUrl($backUrl,$replace);
			// End of build Back button Url
			
			$replace['body'] = $this->build_template($this->get_template("customize_articles"),$replace);
			$replace['browser_title'] = "Tx Xchange: Customize Article";
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}
        /**
        * This function will activate new plan and copy new plans to all existing active therapist's of system.
        */
        function activateSystemPlan(){

                $plan_id = $this->value('plan_id');
                if( is_numeric($plan_id)){
                   $query = "select is_public from plan where plan_id = '{$plan_id}' "; 
                   $result = @mysql_query($query);
                   if( $row = @mysql_fetch_array($result) ){
                       $is_public = $row['is_public'];
                   }
                   if( (is_null($is_public) || $is_public != 1) && $this->value('act') == 'publish' ){
                        $this->copy_plan_to_all_therapists($plan_id);
                        $is_public_sql = " ,is_public = 1 ";
                   }
                   else{
                       $is_public_sql = "";
                   }
                   // Update status and is_public field is required.
                   $query = " update plan set status = 1 $is_public_sql  where plan_id = '{$plan_id}' "; 
                   @mysql_query($query);
                }
                
                // Redirect to Home page.
                header("location:index.php?action=sysAdmin");
        }
		/**
		 * This function removes any associated article from Template plan.
		 *
		 * @param integer $article_id
		 * @access public
		 */
		function remove_article($article_id){
			if(isset($article_id) && is_numeric($article_id) && $article_id > 0 ) {
				$query = "delete from plan_article where plan_article_id = '{$article_id}' ";
				$this->execute_query($query);
			}
		}
		/**
		 * This function associates any article with Template plan.
		 *
		 * @param integer $article_id
		 * @access public
		 */
		function add_article($article_id){
			if(isset($article_id) && is_numeric($article_id) && $article_id > 0 ) {
				$add_article_arr = array(
					'plan_id' => $this->value('plan_id'),
					'article_id' => $this->value('article_id'),
					'status' => 1,
				); 
				//$query = "select count(*) from plan_article where plan_id = '{$this->value('plan_id')}' and article_id = '{$this->value('article_id')}' ";
				$query = "select count(*) from plan_article pa 
						  inner join article a 	on a.article_id = pa.article_id and a.status = 1 
						  where pa.plan_id = '{$this->value('plan_id')}' and pa.article_id = '{$this->value('article_id')}' ";
				$result = @mysql_query($query);
				if(is_resource($result)){
					$row = @mysql_fetch_array($result);
					if($row[0] == 0 ){
						$this->insert($this->config['table']['plan_article'],$add_article_arr);	
					}	
				}
			}
		}
		/**
		 * This function shows list of articles associated with any Template plan.
		 *
		 * @param integer $id
		 * @return string
		 * @access public
		 */
		function added_articles($id){
			include("template/therapist/therapistArray.php");
			if(is_numeric($id) && $id > 0){
				$query = "select a.article_name, a.headline,pa.article_id, pa.plan_article_id  from plan_article pa 
					inner join article a on a.article_id = pa.article_id and a.status = 1 
					where pa.plan_id = '{$id}' ";
				
				$result = $this->execute_query($query);
				while($row = $this->fetch_array($result)){
					$row['style'] = ($c++%2)?"line1":"line2";
					$row['optionAction'] = $this->build_select_option($articleAddOption);
					$replace['added_articles'] .= $this->build_template($this->get_template("added_articles"),$row);
				}
				return $replace['added_articles'];
			}
			return "";
		}
		/**
		 * This function shows list of articles present in system(s) article library.
		 *
		 * @return array
		 * @access public
		 */
		function article_library(){
				include_once("template/sysadmin/systemArray.php");
				
				if($this->value('sort') != ""){
					if($this->value('order') == 'desc' ){
						$orderby = " order by {$this->value('sort')} desc ";
					}
					else{
						$orderby = " order by {$this->value('sort')} ";
					}
				}
				else{
					$orderby = " order by a.modified desc ";
				}
				
				$query = "SELECT *,a.modified FROM article a,user u  WHERE a.status = 1 and a.user_id = u.user_id 
				and u.usertype_id = 4  {$orderby} ";
				
				
				$skipValue = array('act','article_id');
				
				$link = $this->pagination($rows = 0,$query,$this->value('action'),'',$skipValue);				
				$replace['link'] = $link['nav'];
				$result = $link['result'];
				while($row = $this->fetch_array($result)){
					$row['style'] = ($c++%2)?"line1":"line2";
					$row['modified'] = $this->formatDate($row['modified']);
					$row['optionAction'] = $this->build_select_option($articleLibOption);
					$replace['article_library'] .= $this->build_template($this->get_template("article_library"),$row);
				}
				$query_string = array(
					'plan_id' => $this->value('plan_id'),
				);
				$replace['article_head'] = $this->build_template($this->get_template("article_head"),$this->table_heading($step4ArticleLib,"a.modified",$query_string));
				return $replace;
		}
        /**
        * This function will copy plan new plan to all existing therapist of application.
        */
        function copy_plan_to_all_therapists($plan_id){
            if(is_numeric($plan_id)){
                
                $query = " select user_id from user where usertype_id = 2 and status= 1 ";
                $result = @mysql_query($query);
                while( $row = @mysql_fetch_array($result) ){
                    $this->copy_plan($row['user_id'],$plan_id);    
                }            
            }
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
        function copy_plan($userId,$planId){
            if( is_numeric($userId) && is_numeric($planId) ){
                 
                // Retrive plan from plan table.
                $query = "select * from plan where plan_id = '{$planId}' ";
                $result = @mysql_query($query);
                
                // Create array for inserting record.
                if( $row = @mysql_fetch_array($result) ){
                    $insertArr = array(
                        'plan_name'=>$row['plan_name'],
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
         * To show the left navigation panel.
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
		 * This function returns template path from xml file.
		 *
		 * @param string $template
		 * @return string
		 */
		function get_template($template){
			$login_arr = $this->action_parser($this->action,'template') ;
			$pos =  array_search($template, $login_arr['template']['name']); 
			return $login_arr['template']['path'][$pos];
		}
		/**
		 * This function display's the output.
		 * @access public
		 */
		function display(){
			view::$output =  $this->output;
		}

  	}

	$obj = new systemPlan();

?>

