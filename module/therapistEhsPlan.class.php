<?php

	/** 

	 * 

	 * Copyright (c) 2008 Tx Xchange.

	 * 

	 * This class is for following functionality:

	 * 1) Shows list of Therapist Template plan.

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

  	class therapistEhsPlan extends application{

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

				/*

					This block of statement(s) are to handle all the actions supported by this Login class

					that is it could be the case that more then one action are handled by login

					for example at first the action is "login" then after submit say action is submit

					so if login is explicitly called we have the login action set (which is also our default action)

					else whatever action is it is set in $str.				

				*/

				$str = $this->value('action');

			}else{

				$str = "therapistEhsPlan"; //default if no action is specified

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

				$this->output =$this->config['error_message'];

			} 

			$this->display();

		}

		/**

		 * This function shows list of Template plan for Therapist.

		 * @SKS 25th november 2011

		 * @access public

		 */

		function therapistEhsPlan() { 

			
                        //Action for deleting the plans
                        if($this->value('act') == 'deletePlan') {

				if( $this->value('plan_id') != "" && is_numeric($this->value('plan_id'))) {

					$query = "update plan p set status = 3 where p.user_id = '{$this->userInfo('user_id')}' and p.user_type = '2' and p.plan_id = '{$this->value('plan_id')}' ";
					$this->execute_query($query);
				}
			}

                        //End here
		        $clinicId = $this->clinicInfo('clinic_id');
                        //$Ehspatients = $this->getProviderEHSPatients($clinicId);
                        if($this->is_corporate($clinicId)==1){
                            $Ehspatients = $this->get_paitent_list($clinicId);
                        }else{
                            $Ehspatients = $this->getProviderEHSPatients($clinicId);
                        }
                        $totalEhsPatients = count($Ehspatients);
                        if($totalEhsPatients == '0') {
                                header("location:index.php?action=therapistEhsPatient&ehsunsub=0");
                        } 
                        //Call get satisfaction js script			
                        $replace['get_satisfaction'] = $this->get_satisfaction();
	                
                        //Header and sidebar
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();
			
                        $templatePlanName  = $_SESSION['providerLabel']['Template Plan Name']!=''?$_SESSION['providerLabel']['Template Plan Name']:"Template Plan Name";
			$replace['p.plan_name'] = $templatePlanName;

			$planListHead = array(
			                        'p.plan_name' => $templatePlanName,
			                        'no_treatments' => '# V',
			                        'no_articles' => '# A',
			);
			$replace['planListHead'] = $this->build_template($this->get_template("planListHead"),$planListHead);

			include_once("template/therapistEHS/therapistArray.php");

			if(isset($_SESSION['type'])){
                                unset($_SESSION['type']);	
			}

			if( $this->value('path') == 'my_patient' ){
				$this->formArray = $PatientPlanListAction;
			}

			else {
				$this->formArray = $planListAction;
			}

			if($this->value('sort') != "") {

				if($this->value('order') == 'desc' ){
                                        $orderby = " order by {$this->value('sort')} desc ";
				}

				else {
                                        $orderby = " order by {$this->value('sort')} ";
                                }
			}

			else {
				$orderby = " order by p.plan_name ";
			}

			$replace['search'] = "";

			if($this->value('search') == "" && $this->value('restore_search') != "" ) {
				$_REQUEST['search'] = $this->value('restore_search');
			}
			
                        if($this->value('search') != ""){

				$replace['search'] = $this->value('search');

				 $query = "select *,(select count(*) from plan_treatment 

						  	where plan_treatment.plan_id = p.plan_id) AS no_treatments,

							(select count(*) from plan_article pa inner join article a on a.article_id = pa.article_id and a.status = 1 where pa.plan_id = p.plan_id) AS no_articles

							from plan p  where p.user_id = '{$this->userInfo('user_id')}' and p.user_type = '2' and p.patient_id is null and p.status = 1 and p.ehsFlag = '0' and p.plan_name like '%{$this->value('search')}%'  

				 {$orderby}";
                 
                $sqlcount="select count(1) from plan p  where p.user_id = '{$this->userInfo('user_id')}' and p.user_type = '2' and p.patient_id is null and p.status = 1 and p.ehsFlag = '0' and p.plan_name like '%{$this->value('search')}%'  

				 {$orderby}";
                      

			}

			else{

				 $query = "select *,(select count(*) from plan_treatment 

						  where plan_treatment.plan_id = p.plan_id) AS no_treatments,

						(select count(*) from plan_article pa inner join article a on a.article_id = pa.article_id and a.status = 1  where pa.plan_id = p.plan_id) AS no_articles

						from plan p where p.user_id = '{$this->userInfo('user_id')}' and p.user_type = '2' and p.patient_id is null and p.status = 1  
                                                and p.ehsFlag = '0' {$orderby}";
                        
                  $sqlcount="select count(1) from plan p where p.user_id = '{$this->userInfo('user_id')}' and p.user_type = '2' and p.patient_id is null and p.status = 1 
                        and p.ehsFlag = '0' {$orderby}";       
                        
                         

			}
		
            
			$link = $this->pagination($rows = 0,$query,$this->value('action'),$this->value('search'),'','','',$sqlcount);				

			$replace['link'] = $link['nav'];

			$result = $link['result'];								  	

			if(is_resource($result)){

				while($row = $this->fetch_array($result)){

					$row['style'] = ($c++%2)?"line1":"line2";

					$row['dropDownWidth'] = ($this->value('path') == 'my_patient'?'90':'120');

					$row['status'] = $this->config['patientStatus'][$row['status']];

					$row['actionOption'] = $this->build_select_option($this->formArray);

					$replace['planRecord'] .= $this->build_template($this->get_template("planRecord"),$row);
				}

				if($this->num_rows($result) == 0){

					$replace['colspan'] = 5;

					$replace['planRecord'] = $this->build_template($this->get_template("no_record_found"),$replace);
				}

			}	

	
			$query_string = array();

			$query_string['patient_id'] = $this->value('patient_id');

			$query_string['path'] = $this->value('path');

			$replace['planListHead'] = $this->build_template($this->get_template("planListHead"),$this->table_heading($planListHead,"p.plan_name",$query_string));

			$replace['patient_name'] = "";

			$replace['path'] = $this->value('path');

            // Personalized GUI
            $LabelTitle=$_SESSION['providerLabel']['My Template Plans']!=''?$_SESSION['providerLabel']['My Template Plans']:"MY TEMPLATE PLANS";
            $replace['path_name'] = strtoupper($LabelTitle);
            $imageCreateNewTemplatePlan=$_SESSION['providerLabel']['images/createNewTemplatePlan.gif']!=''?$_SESSION['providerLabel']['images/createNewTemplatePlan.gif']:"images/createNewTemplatePlan.gif";
            $replace['imageCreateNewTemplatePlan'] = $imageCreateNewTemplatePlan;
            $templatePlanLibrary=$_SESSION['providerLabel']['Template Plan Library']!=''?$_SESSION['providerLabel']['Template Plan Library']:"Template Plan Library";
			$replace['templatePlanLibrary']=$templatePlanLibrary;
			$templatePlanName  =$_SESSION['providerLabel']['Template Plan Name']!=''?$_SESSION['providerLabel']['Template Plan Name']:"Template Plan Name";
			$replace['p.plan_name']=$templatePlanName;
			
			if($this->value('path') == "my_patient"){

				//$replace['path_name'] = "MY Patients";

			}

			//$replace['patient_id'] = $this->value('patient_id'); SKS

			/*if($this->value('patient_id') != "" ){
                                $title = $this->userInfo('name_title',$this->value('patient_id'));
                                $name_first = $this->userInfo('name_first',$this->value('patient_id'));
                                $name_last = $this->userInfo('name_last',$this->value('patient_id'));
                                $replace['patient_name'] .= '/&nbsp;' . $this->fullName($title,$name_first,$name_last) . '&nbsp;';
			}*/

			$replace['body'] = $this->build_template($this->get_template("planList"),$replace);

			$replace['browser_title'] = "Tx Xchange: ".$LabelTitle;

			$this->output = $this->build_template($this->get_template("main"),$replace);

		}

                /**

                 * Function for assigning plans to the ehs patients. 

                 * @SKS 23rdnovember 2011

                 * @access public

                 */

                function createNewEhsPlan() { 

                        if($this->value('type') == "treatment_plan" ) {

                                $_SESSION['type'] = "treatment_plan";

                                $replace['type'] = "treatment_plan";


                        } elseif( $this->value('type') == "finish" ) {

			        $_SESSION['type'] = 'finish';

			        $replace['type'] = "finish";

			}

			else {

				if(isset($_SESSION['type'])){

					session_unregister('type');

				}

				$replace['type'] = "";

			}

		$replace['plan_name'] = "";
                
                $schd = $this->value('schd');
                $clinicId = $this->clinicInfo('clinic_id');
                if($this->is_corporate($clinicId)==1){
                    
                     $Ehspatients = $this->get_paitent_list($clinicId);
                }else{
                    $Ehspatients = $this->getProviderEHSPatients($clinicId);
                }
                //$Ehspatients = $this->getProviderEHSPatients($clinicId);
                
                $totalEhsPatients = count($Ehspatients);
                if($totalEhsPatients == '0') {
                        header("location:index.php?action=therapistEhsPatient&ehsunsub=0");
                } 
                if($schd!= '') {
                        $replace['schd'] = $schd;
                }
			$replace['plan_id'] = "";

	                //For plan edit action only

			if($this->value('act') == "plan_edit" && $this->value('plan_id') != "" ) {

				$query = "select * from plan where plan_id = '{$this->value('plan_id')}' ";

				$result = $this->execute_query($query);

				if($row = $this->fetch_array($result)){

					$replace['plan_name'] = $row['plan_name'];

					$replace['plan_id'] = $row['plan_id'];
				}

			}

			elseif ($this->value('plan_id') != "") { 

				$replace['plan_name'] = $this->value('plan_name');

				$replace['plan_id'] = $this->value('plan_id');
			}

			if( $this->value('submitted_x') != "" ) { 

				if( $this->value('plan_name') == ""){

					$error[] = "Please enter Plan name.";

				}

				else{ 

					$flag = 0;
			
					if(( $_SESSION['type'] == "treatment_plan" || $_SESSION['type'] == "finish" )) {

						$flag = 1;
					}

                                        if( $flag == 1 ) {

						if( $this->value('plan_id') != "") {
							$query = " select * from plan where user_id = '{$this->userInfo('user_id')}' and plan_name LIKE '".addslashes($this->value('plan_name'))."' and plan_id != '{$this->value('plan_id')}'  and patient_id is null   and (status = 1 OR status = 2) and ehsFlag= 0 ";//echo "<br>";


						}

						else{
							 $query = " select * from plan where user_id = '{$this->userInfo('user_id')}' and plan_name LIKE '".addslashes($this->value('plan_name'))."'   and patient_id is null and (status = 1 OR status = 2) ";
						}
                                               // echo  $query;exit;
//echo "<pre>";exit;
						$result = $this->execute_query($query);
                                                //echo $this->num_rows($result);exit;
						if($this->num_rows($result) > 0 ){

							$error[] = "Plan name already exist."; 

						}

					}

				}

				if(isset($error) && count($error) > 0){

					$replace['error'] = $this->show_error($error);

				}

				else{

					$data = array(

						'plan_name' => $this->value('plan_name'),

						'user_id' => $this->userInfo('user_id'),

						'user_type' => '2',
                                        
                                                'ehsFlag' => '1',

                                                'clinicId' => $clinicId,

						//'status' => (isset($_SESSION['type']) && $_SESSION['type'] == "treatment_plan")?'3':'1',
                                                 'status' => '4',       
                                                'unread_plan'=>1

					);

					if($this->value('plan_id') && $schd == 'edit'){ //echo "abasdasdc";exit;

						$where = " plan_id =  '{$this->value('plan_id')}' ";
                                               

						$data['modified'] = date("Y-m-d");
                                                $schd = $this->value('schd');
                                                if($schd == 'edit') {
                                                       $data['schdulerAction'] = '2';
                                                } else {
                                                        $data['schdulerAction'] = '1';
                                                }                                               
                                                $data['status'] = '1';
                                                
						$this->update($this->config['table']['plan'],$data,$where);

						header("location:index.php?action=selectEhsTreatment&plan_id={$this->value('plan_id')}");

					}

					else{ 
//echo "abc";exit;
						$data['creation_date'] = date("Y-m-d H:i:s");
						$data['modified'] = date("Y-m-d H:i:s");
                                                $data['is_public'] = null;
                                                $data['schdulerAction'] = '1';
                                                $data['clinicId'] = $clinicId;
                                                $data['parent_template_id'] =$this->value('plan_id');
                                        
						if($this->insert($this->config['table']['plan'],$data)){

							$plan_id = $this->insert_id();
                                                        if($this->value('plan_id')!= '') {
                                                          /* check for plan_treatment */
                            $sqlplanTreatment="select * from plan_treatment where plan_id=".$this->value('plan_id');
                            $resplanTreatment=$this->execute_query($sqlplanTreatment);
                            $numplanTreatment=$this->num_rows($resplanTreatment);
                            if($numplanTreatment>0){
                                while($rowplanTreatment=$this->fetch_array($resplanTreatment)){
                                    $arrayPlanTreatment=array(
                                    'plan_id'           =>  $plan_id,
                                    'treatment_id'      =>  $rowplanTreatment['treatment_id'],
                                    'instruction'       =>  $rowplanTreatment['instruction'],
                                    'sets'              =>  $rowplanTreatment['sets'],
                                    'reps'              =>  $rowplanTreatment['reps'],
                                    'hold'              =>  $rowplanTreatment['hold'],
                                    'benefit'           =>  $rowplanTreatment['benefit'],
                                    'lrb'               =>  $rowplanTreatment['lrb'],
                                    'treatment_order'   =>  $rowplanTreatment['treatment_order'],
                                    'creation_date'     =>  date("Y-m-d H:i:s"),
                                    'status'            =>  1
                                    );
                                 $this->insert('plan_treatment',$arrayPlanTreatment);   
                               }
                           }

                                /* check for plan_article */
                            $sqlPlanArticle="select * from plan_article where plan_id=".$this->value('plan_id');
                            $resPlanArtical=$this->execute_query($sqlPlanArticle);
                            $numPlanArtical=$this->num_rows($resPlanArtical);
                            if($numPlanArtical>0){
                                while($rowPlanArtical=$this->fetch_array($resPlanArtical)){
                                    $arrayPlanArtical=array(
                                    'plan_id'=>$plan_id,
                                    'article_id'=>$rowPlanArtical['article_id'],
                                    'creation_date'=>date("Y-m-d H:i:s"),
                                    'status'=>1
                                    );
                                 $this->insert('plan_article',$arrayPlanArtical);   
                                    
                                }
                                
                            }


                       }
                                                        
                            
							header("location:index.php?action=selectEhsTreatment&plan_id={$plan_id}");
						}
					}
				}
			}

			$replace['header'] = $this->build_template($this->get_template("header"));

			$replace['footer'] = $this->build_template($this->get_template("footer"));

			$replace['sidebar'] = $this->sidebar();

			$replace['footer'] = $this->build_template($this->get_template("footer"));
			
		 
			$replace['get_satisfaction'] = $this->get_satisfaction();
			 

			$nav_bar = array(

								'plan' => ($this->value('plan_id') != "")?'<a href="index.php?action=createNewEhsPlan&act=plan_edit&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/01_plan_gray.gif" /></a>':'<img src="images/01_plan_gray.gif" />',

								'step1' => ($this->value('plan_id') != "")?'<img src="images/stepIcons_1_red_sm.gif">':'<img src="images/stepIcons_1_red_sm.gif">',

								'step2' => ($this->value('plan_id') != "")?'<a href="index.php?action=selectEhsTreatment&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/stepIcons_2_gray_sm.gif"></a>':'<img src="images/stepIcons_2_gray_sm.gif">',

								'step3' => ($this->value('plan_id') != "")?'<a href="index.php?action=customize_instruction_ehs&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/stepIcons_3_gray_sm.gif"></a>':'<img src="images/stepIcons_3_gray_sm.gif">',

								'step4' => ($this->value('plan_id') != "")?'<a href="index.php?action=customize_articles_ehs&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/stepIcons_4_gray_sm.gif"></a>':'<img src="images/stepIcons_4_gray_sm.gif">',

								

						);

			if(is_array($replace)){

				$replace = $replace + $nav_bar;

			}

			



			$replace['patient_image'] = '<img src="images/03_patient_gray.gif" />';

			$replace['assign_image'] = '<img src="images/04_assign_gray.gif" />';

			// Personalized GUI
            $LabelTitle=$_SESSION['providerLabel']['My Template Plans']!=''?$_SESSION['providerLabel']['My Template Plans']:"MY TX PLANS";
            $replace['path_name'] = strtoupper($LabelTitle);

			$replace['body'] = $this->build_template($this->get_template("createNewPlan"),$replace);

			$replace['browser_title'] = "Tx Xchange: Create New Plan";

			$this->output = $this->build_template($this->get_template("main"),$replace);

		}


		/**

		 * This function copy existing Template plans or Treatment plans in to new one and change original plan into archive.
		 *
		 * @access public
		 */

		function copyExistingEhsPlan(){
			if( $this->value('type') == "finish" ){

				$_SESSION['type'] = 'finish';

				$replace['type'] = "finish";
                        }

			else{

				if(isset($_SESSION['type'])){

					session_unregister('type');

				}

				$replace['type'] = "";

			}

			

			$replace['plan_name'] = "";

			$replace['plan_id'] = "";

			if($this->value('act') == "plan_edit" && $this->value('plan_id') != "" ){

				$query = "select * from plan where plan_id = '{$this->value('plan_id')}' ";

				$result = $this->execute_query($query);

				if($row = $this->fetch_array($result)){

					$replace['plan_name'] = $row['plan_name'];

					$replace['plan_id'] = $row['plan_id'];
				}
			}

			elseif ($this->value('plan_id') != ""){

				$replace['plan_name'] = $this->value('plan_name');

				$replace['plan_id'] = $this->value('plan_id');
			}

			if( $this->value('submitted_x') != "" ){

				//print_r($_POST);exit;

				if( $this->value('plan_name') == ""){

					$error[] = "Please enter Plan name.";

				}

				else{
						$query = " select * from plan where plan_name = '".addslashes($this->value('plan_name'))."'";
						$result = $this->execute_query($query);
						if($this->num_rows($result) > 0 ){
							$error[] = "The plan name already exists. Please use a different one."; 
						}
				}

				if(isset($error) && count($error) > 0){

					$replace['error'] = $this->show_error($error);

				}

				else{
                                                $notify = $this->value('notify');
                                                if($notify > 0) {
                                                        $notify = $this->value('notify');

                                                } else {
                                                         $notify = '0';
                                                }    					        
                                                $query = " select * from plan where plan_id = ".$this->value('plan_id');
						$sourceResult = $this->execute_query($query);
						$sourceRow = $this->fetch_array($sourceResult);
						$sourceData = array(
									'plan_name' => $this->value('plan_name'),
									'parent_template_id' => $sourceRow['parent_template_id'],
									'user_id' => $this->userInfo('user_id'),
									'patient_id' => $sourceRow['patient_id'],
									'user_type' => 2,
									'status' => 4,
                                                                        'ehsFlag' => '1',
                                                                        'schdulerAction' => '1',
									'unread_plan'=>1
								);

					
                                                $sourceData['old_plan_id'] = $this->value('plan_id');
						$sourceData['creation_date'] = date("Y-m-d H:i:s");
						$sourceData['modified'] = date("Y-m-d H:i:s");
                                                $sourceData['is_public'] = null;

						if($this->insert($this->config['table']['plan'],$sourceData)){
							$new_plan_id = $this->insert_id();
                            /* check for plan_article */
                            $sqlPlanArticle="select * from plan_article where plan_id=".$this->value('plan_id');
                            $resPlanArtical=$this->execute_query($sqlPlanArticle);
                            $numPlanArtical=$this->num_rows($resPlanArtical);
                            if($numPlanArtical>0){
                                while($rowPlanArtical=$this->fetch_array($resPlanArtical)){
                                    $arrayPlanArtical=array(
                                    'plan_id'=>$new_plan_id,
                                    'article_id'=>$rowPlanArtical['article_id'],
                                    'creation_date'=>date("Y-m-d H:i:s"),
                                    'status'=>1
                                    );
                                 $this->insert('plan_article',$arrayPlanArtical);   
                                    
                                }
                                
                            }
                            /* check for plan_treatment */
                            $sqlplanTreatment="select * from plan_treatment where plan_id=".$this->value('plan_id');
                            $resplanTreatment=$this->execute_query($sqlplanTreatment);
                            $numplanTreatment=$this->num_rows($resplanTreatment);
                            if($numplanTreatment>0){
                                while($rowplanTreatment=$this->fetch_array($resplanTreatment)){
                                    $arrayPlanTreatment=array(
                                    'plan_id'           =>  $new_plan_id,
                                    'treatment_id'      =>  $rowplanTreatment['treatment_id'],
                                    'instruction'       =>  $rowplanTreatment['instruction'],
                                    'sets'              =>  $rowplanTreatment['sets'],
                                    'reps'              =>  $rowplanTreatment['reps'],
                                    'hold'              =>  $rowplanTreatment['hold'],
                                    'benefit'           =>  $rowplanTreatment['benefit'],
                                    'lrb'               =>  $rowplanTreatment['lrb'],
                                    'treatment_order'   =>  $rowplanTreatment['treatment_order'],
                                    'creation_date'     =>  date("Y-m-d H:i:s"),
                                    'status'            =>  1
                                    );
                                 $this->insert('plan_treatment',$arrayPlanTreatment);   
                               }
                           }
                            
                            
                            
                            //Updating the copied plan to status archive '2'
							$where = " plan_id =  '{$this->value('plan_id')}' ";
							//$updateData['status'] = '2';
                                                        //$updateData['old_plan_id'] = $this->value('plan_id');
							$updateData['modified'] = date("Y-m-d");
							$this->update($this->config['table']['plan'],$updateData,$where);
							header("location:index.php?action=selectEhsTreatment&plan_id={$new_plan_id}");

						}
				}

			}

			$replace['header'] = $this->build_template($this->get_template("header"));

			$replace['footer'] = $this->build_template($this->get_template("footer"));

			$replace['sidebar'] = $this->sidebar();

			$replace['footer'] = $this->build_template($this->get_template("footer"));
			
		 
			$replace['get_satisfaction'] = $this->get_satisfaction();
			 

			$nav_bar = array(

								'plan' => ($this->value('plan_id') != "")?'<a href="index.php?action=createNewEhsPlan&act=plan_edit&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/01_plan_gray.gif" /></a>':'<img src="images/01_plan_gray.gif" />',

								'step1' => ($this->value('plan_id') != "")?'<img src="images/stepIcons_1_red_sm.gif">':'<img src="images/stepIcons_1_red_sm.gif">',

								'step2' => ($this->value('plan_id') != "")?'<a href="index.php?action=selectEhsTreatment&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/stepIcons_2_gray_sm.gif"></a>':'<img src="images/stepIcons_2_gray_sm.gif">',

								'step3' => ($this->value('plan_id') != "")?'<a href="index.php?action=customize_instruction_ehs&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/stepIcons_3_gray_sm.gif"></a>':'<img src="images/stepIcons_3_gray_sm.gif">',

								'step4' => ($this->value('plan_id') != "")?'<a href="index.php?action=customize_articles_ehs&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/stepIcons_4_gray_sm.gif"></a>':'<img src="images/stepIcons_4_gray_sm.gif">',

								

						);

			if(is_array($replace)){

				$replace = $replace + $nav_bar;

			}

			



			$replace['patient_image'] = '<img src="images/03_patient_gray.gif" />';

			$replace['assign_image'] = '<img src="images/04_assign_gray.gif" />';
			
			
			

			$replace['body'] = $this->build_template($this->get_template("createNewPlan"),$replace);

			$replace['browser_title'] = "Tx Xchange: Create New Plan";

			$this->output = $this->build_template($this->get_template("main"),$replace);

		}

		/**

		 * This function lists treatments and helps in selecting treatment for Template plan or Treatment plan.

		 *

		 * @access public

		 */

		function selectEhsTreatment() {

			$replace['patient_id'] = "";
			 
			$replace['get_satisfaction'] = $this->get_satisfaction();

			/*if(isset($_SESSION['patient_id']) && $_SESSION['patient_id'] != "") {
                
                                $title = $this->get_field($_SESSION['patient_id'],user,'name_title');
                                $name_first = $this->get_field($_SESSION['patient_id'],user,'name_first');
                                $name_last = $this->get_field($_SESSION['patient_id'],user,'name_last');
                                $replace['patient_name'] .= '/&nbsp;' . $this->fullName($title,$name_first,$name_last) . '&nbsp;';

			        $replace['patient_id'] = $_SESSION['patient_id'];
                   	}*/

			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();
			$replace['plan_id'] = $this->value('plan_id');
			$replace['plan_title'] = strtoupper($this->get_field($this->value('plan_id'), $this->config['table']['plan'], "plan_name"));
			$replace['type'] = "";
			if($_SESSION['type'] == "finish"){
				$replace['type'] = 'finish';
			}
			elseif($_SESSION['type'] == "treatment_plan"){
				$replace['type'] = 'treatment_plan';
			}

		
			$replace['patient_image'] = '<img src="images/03_patient_gray.gif" />';

			$replace['assign_image'] = '<img src="images/04_assign_gray.gif" />';

			

			$nav_bar = array(

								'plan' => ($this->value('plan_id') != "")?'<a href="index.php?action=createNewEhsPlan&act=plan_edit&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/01_plan_gray.gif" /></a>':'<img src="images/01_plan_gray.gif" />',

								'step1' => ($this->value('plan_id') != "")?'<a href="index.php?action=createNewEhsPlan&act=plan_edit&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/stepIcons_1_gray_sm.gif"></a>':'<img src="images/stepIcons_1_gray_sm.gif">',

								'step2' => ($this->value('plan_id') != "")?'<img src="images/stepIcons_2_red_sm.gif">':'<img src="images/stepIcons_2_red_sm.gif">',

								'step3' => ($this->value('plan_id') != "")?'<a href="index.php?action=customize_instruction_ehs&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/stepIcons_3_gray_sm.gif"></a>':'<img src="images/stepIcons_3_gray_sm.gif">',

								'step4' => ($this->value('plan_id') != "")?'<a href="index.php?action=customize_articles_ehs&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/stepIcons_4_gray_sm.gif"></a>':'<img src="images/stepIcons_4_gray_sm.gif">',

								

						);

			if(is_array($replace)){

				$replace = $replace + $nav_bar;

			}

			

			// Build Back button Url

			$replace['act'] = "plan_edit";

			$replace['action'] = "createNewEhsPlan";

			$backUrl = array('action','plan_id','type','act');

			$replace['back_url'] = $this->buildBackUrl($backUrl,$replace);

			// End of build Back button Url

			// Personalized GUI
            $LabelTitle=$_SESSION['providerLabel']['My Template Plans']!=''?$_SESSION['providerLabel']['My Template Plans']:"MY TX PLANS";
            $replace['path_name'] = strtoupper($LabelTitle);
			$LabelTreatmentSearch=$_SESSION['providerLabel']['SEARCH TREATMENT']!=''?$_SESSION['providerLabel']['SEARCH TREATMENT']:"SEARCH TREATMENT";
            $LabelTreatmentResult=$_SESSION['providerLabel']['TREATMENT RESULTS']!=''?$_SESSION['providerLabel']['TREATMENT RESULTS']:"TREATMENT RESULTS";
			$LabelTreatmentSelected=$_SESSION['providerLabel']['SELECTED TREATMENTS']!=''?$_SESSION['providerLabel']['SELECTED TREATMENTS']:"SELECTED TREATMENTS";
			$replace['SEARCH_TREATMENT'] = strtoupper($LabelTreatmentSearch);
			$replace['TREATMENT_RESULTS'] = strtoupper($LabelTreatmentResult);
			$replace['SELECTED_TREATMENTS'] = strtoupper($LabelTreatmentSelected);
			
			$replace['body'] = $this->build_template($this->get_template("selectTreatment"),$replace);
		
			$replace['browser_title'] = "Tx Xchange: Select Treatment";

			$this->output = $this->build_template($this->get_template("main"),$replace);			

		}

		/**

		 * This funciton lists treatments of any Template plans or Treatment plan.

		 *

		 * @access public

		 */

		function customize_instruction_ehs() {

		
			$replace['get_satisfaction'] = $this->get_satisfaction();
			 
			

			// To show the edited instruction in different color.

			if( $this->value("edit_instruction") != "1" )

				$_SESSION['edit_record'] = array(); 

		

			$replace['header'] = $this->build_template($this->get_template("header"));

			$replace['sidebar'] = $this->sidebar();

			$replace['plan_id'] = $this->value('plan_id');

			$replace['plan_title'] = strtoupper($this->get_field($this->value('plan_id'), $this->config['table']['plan'], "plan_name"));

			$replace['customize_instruction_head'] = $this->build_template($this->get_template("customize_instruction_head"));

			include_once("template/therapistEHS/therapistArray.php");

			$this->formArray = $customizeInstructionAction;

			$query = "select pt.plan_treatment_id,pt.treatment_order,pt.treatment_id,trt.treatment_name,

						pt.instruction,pt.benefit,pt.sets,pt.reps,pt.hold,pt.lrb,pt.plan_id from plan_treatment pt

						inner join treatment trt on pt.treatment_id = trt.treatment_id 

						where pt.plan_id = '{$this->value('plan_id')}' order by pt.treatment_order";

						

			$result = $this->execute_query($query);								  	

			if(is_resource($result)){

				while($row = $this->fetch_array($result)){

					

					if( $this->value("edit_instruction") == "1" && is_array($_SESSION['edit_record']) && in_array($row['plan_treatment_id'],$_SESSION['edit_record']) ){

						$row['show_selected'] = 'style="border-top: #090 1px solid; border-bottom: #090 1px solid; background-color: #cfc;"';

						$row['style'] = "";

					}

					else{

						$row['style'] = ($c++%2)?"line1":"line2";

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

			

			$replace['type'] = "";

			if($_SESSION['type'] == "finish"){

				$replace['type'] = 'finish';

			}

			elseif($_SESSION['type'] == "treatment_plan"){

				$replace['type'] = 'treatment_plan';

			}

				

			$replace['patient_image'] = '<img src="images/03_patient_gray.gif" />';

			$replace['assign_image'] = '<img src="images/04_assign_gray.gif" />';

			

			$nav_bar = array(

								'plan' => ($this->value('plan_id') != "")?'<a href="index.php?action=createNewEhsPlan&act=plan_edit&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/01_plan_gray.gif" /></a>':'<img src="images/01_plan_gray.gif" />',

								'step1' => ($this->value('plan_id') != "")?'<a href="index.php?action=createNewEhsPlan&act=plan_edit&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/stepIcons_1_gray_sm.gif"></a>':'<img src="images/stepIcons_1_gray_sm.gif">',

								'step2' => ($this->value('plan_id') != "")?'<a href="index.php?action=selectEhsTreatment&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/stepIcons_2_gray_sm.gif"></a>':'<img src="images/stepIcons_2_gray_sm.gif">',

								'step3' => ($this->value('plan_id') != "")?'<img src="images/stepIcons_3_red_sm.gif">':'<img src="images/stepIcons_3_red_sm.gif">',

								'step4' => ($this->value('plan_id') != "")?'<a href="index.php?action=customize_articles_ehs&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/stepIcons_4_gray_sm.gif"></a>':'<img src="images/stepIcons_4_gray_sm.gif">',

								

						);

			if(is_array($replace)){

				$replace = $replace + $nav_bar;

			}

			

			// Build Back button Url

			$replace['act'] = "plan_edit";

			$replace['action'] = "selectEhsTreatment";

			$backUrl = array('action','plan_id','type','act');

			$replace['back_url'] = $this->buildBackUrl($backUrl,$replace);

			// End of build Back button Url

			$replace['body'] = $this->build_template($this->get_template("customize_instruction"),$replace);

			$replace['browser_title'] = "Tx Xchange: Customize Instruction";
			// Personalized GUI
                            $LabelTitle=$_SESSION['providerLabel']['My Template Plans']!=''?$_SESSION['providerLabel']['My Template Plans']:"MY TX PLANS";
                            $replace['path_name'] = strtoupper($LabelTitle);
                            $LabelTreatmentHeading=$_SESSION['providerLabel']['Treatments in Plan']!=''?$_SESSION['providerLabel']['Treatments in Plan']:"Treatments in Plan";
                            $replace['Treatments_in_Plan'] = $LabelTreatmentHeading;
                            $LabelTreatmenttitle=$_SESSION['providerLabel']['Treatment']!=''?$_SESSION['providerLabel']['Treatment']:"Treatment";
                            $replace['TreatmentHead'] = $LabelTreatmenttitle;
                            $this->output = $this->build_template($this->get_template("main"),$replace);			

		}

		/**

		 * This funciton helps in editing instruction,benefits,lrb,etc of selected treatment for Template plans or Treatment plan.

		 *

		 * @access public

		 */

		function edit_instruction_ehs() {
			include_once("template/therapistEHS/therapistArray.php");

			if( $this->value('update') == "update" && $this->value('id') != ""){

				$where = " plan_treatment_id = '{$this->value('id')}' ";

				$updateArr = $this->fillForm($instructionFormArray,true);

				if($this->update($this->config['table']['plan_treatment'],$updateArr,$where)){

					$_SESSION['edit_record'][] = $this->value('id');

					?>

					<script language="javascript" >

						window.close();

						window.opener.location.href = "index.php?action=customize_instruction_ehs&plan_id=<?php echo $this->get_field($this->value('id'),"plan_treatment","plan_id") ?>&edit_instruction=1";

					</script>

					<?php	

					

				}

				//echo "shail";exit;

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

			$replace['browserTitle'] = "Edit Instruction";
			 
			$replace['get_satisfaction'] = $this->get_satisfaction();
            
            // Personalized GUI 
            //Label 
            $DefaultInstructions=$_SESSION['providerLabel']['Default Instructions']!=''?$_SESSION['providerLabel']['Default Instructions']:'Default Instructions';
            $BenefitofTreatment=$_SESSION['providerLabel']['Benefit of Treatment']!=''?$_SESSION['providerLabel']['Benefit of Treatment']:'Benefit of Treatment';            

            
            //Label
            $replace['labelDefaultInstructions']=$DefaultInstructions;
            $replace['labelBenefitofTreatment']=$BenefitofTreatment;

                // Fields
                $displayFieldSets='';
                $displayFieldReps='';
                $displayFieldHold='';
                $displayFieldLRB='';
                
                $SetsDisplay=$_SESSION['providerField']['Sets']!=''?$_SESSION['providerField']['Sets']:'1';
                $RepsDisplay=$_SESSION['providerField']['Reps']!=''?$_SESSION['providerField']['Reps']:'1';
                $HoldDisplay=$_SESSION['providerField']['Hold']!=''?$_SESSION['providerField']['Hold']:'1';
                $LRBDisplay=$_SESSION['providerField']['LRB']!=''?$_SESSION['providerField']['LRB']:'1';
                
                if($SetsDisplay=='0'){
                    $displayFieldSets='none';      
                }
                if($RepsDisplay=='0'){
                    $displayFieldReps='none';      
                }
                if($HoldDisplay=='0'){
                    $displayFieldHold='none';      
                }
                if($LRBDisplay=='0'){
                    $displayFieldLRB='none';      
                }
                
                $replace['displayFieldLRB']=$displayFieldLRB;
                $replace['displayFieldReps']=$displayFieldReps;
                $replace['displayFieldHold']=$displayFieldHold;
                $replace['displayFieldSets']=$displayFieldSets;
                

			 
			$this->output = $this->build_template($this->get_template("main"),$replace);			

		}

		/**

		 * This function helps in assocating any article with Template plan or Treatment plan.

		 *

		 * @access public

		 */

		function customize_articles_ehs(){

			if($this->value('act') == "remove_article" && $this->value('article_id') != "" ){

				$this->remove_article($this->value('article_id'));

			}

			if($this->value('act') == "add_article" && $this->value('article_id') != "" ){

				$this->add_article($this->value('article_id'));

			}

			$replace['search']=$this->value('search');
			$replace['page']=$this->value('page');

			$replace['plan_id'] = $this->value('plan_id');

			$replace['plan_title'] = strtoupper($this->get_field($this->value('plan_id'), $this->config['table']['plan'], "plan_name"));

			$replace['header'] = $this->build_template($this->get_template("header"));

			$replace['sidebar'] = $this->sidebar();

			$replace['plan_id'] = $this->value('plan_id');

			$replace['added_articles'] = $this->added_articles( $this->value('plan_id'));

			$arr = $this->article_library();

			$replace['patient'] = '<img src="images/03_patient_gray.gif" />';

			if(isset($arr) && is_array($arr)){

				$replace['article_library'] = $arr['article_library'];

				$replace['link'] = $arr['link'];

				$replace['article_head'] = $arr['article_head'];

				$replace['sort'] = $this->value('sort');

				$replace['order'] = $this->value('order');

				

			}

			if(isset($_SESSION['type']) && $_SESSION['type'] == "treatment_plan"){

				$replace['type_of_button'] = $this->build_template($this->get_template("assign_button_template"),$replace);

				if($this->value('plan_id') != ""){

					//$replace['patient'] = '<a href="index.php?action=assign_plan_patient&plan_id='.$this->value("plan_id").'"><img src="images/03_patient_red.gif" /></a>';

				}

			}

			elseif(isset($_SESSION['type']) && $_SESSION['type'] == "finish"){

	                        $replace['type_of_button'] = $this->build_template($this->get_template("finish_button_template"),$replace);
			}

			else{

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
                //$replace['publish_button'] .= "&nbsp;" . $this->build_template($this->get_template("save_as_button_template"),$replace);
			}

			

			$replace['type'] = "";

			if($_SESSION['type'] == "finish"){

				$replace['type'] = 'finish';

			}

			elseif($_SESSION['type'] == "treatment_plan"){

				$replace['type'] = 'treatment_plan';

			}

			

			$replace['patient_image'] = '<img src="images/03_patient_gray.gif" />';

			$replace['assign_image'] = '<img src="images/04_assign_gray.gif" />';

			

			$nav_bar = array(

								'plan' => ($this->value('plan_id') != "")?'<a href="index.php?action=createNewEhsPlan&act=plan_edit&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/01_plan_gray.gif" /></a>':'<img src="images/01_plan_gray.gif" />',

								'step1' => ($this->value('plan_id') != "")?'<a href="index.php?action=createNewEhsPlan&act=plan_edit&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/stepIcons_1_gray_sm.gif"></a>':'<img src="images/stepIcons_1_gray_sm.gif">',

								'step2' => ($this->value('plan_id') != "")?'<a href="index.php?action=selectEhsTreatment&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/stepIcons_2_gray_sm.gif"></a>':'<img src="images/stepIcons_2_gray_sm.gif">',

								'step3' => ($this->value('plan_id') != "")?'<a href="index.php?action=customize_instruction_ehs&plan_id='.$this->value("plan_id").'&type='.$_SESSION['type'].'"><img src="images/stepIcons_3_gray_sm.gif"></a>':'<img src="images/stepIcons_3_gray_sm.gif">',

								'step4' => ($this->value('plan_id') != "")?'<img src="images/stepIcons_4_red_sm.gif">':'<img src="images/stepIcons_4_red_sm.gif">',

								

						);

			if(is_array($replace)){

				$replace = $replace + $nav_bar;

			}

			// Build Back button Url

			$replace['act'] = "plan_edit";

			$replace['action'] = "customize_instruction_ehs";

			$backUrl = array('action','plan_id','type','act');

			$replace['back_url'] = $this->buildBackUrl($backUrl,$replace);

			// End of build Back button Url

			

			// Tempalte for filtering article.

			//$replace['search_url'] = htmlspecialchars($_SERVER['REQUEST_URI'],ENT_QUOTES)."&clear_search=2&page=0";
			$replace['search_url'] = "/index.php?action=customize_articles_ehs&clear_search=2&page=0&plan_id=".$this->value('plan_id');
			$clear_search_url .= "index.php?";
			foreach ($_REQUEST as $key => $value) {
				if($key == "search" ){
					continue;
				}
				else{
					$clear_search_url .= $key."=".$value."&";
				}
				
			}
			$clear_search_url .= "&clear_search=1&page=0";
			$replace['clear_search_url'] = $clear_search_url;
			//$replace['clear_search_url'] = substr(htmlspecialchars($_SERVER['REQUEST_URI'],ENT_QUOTES)."&clear_search=1&page=0",1);
			

			$replace['articleFilter'] = $this->build_template($this->get_template("articleFilter"),$replace);

			// End of Template code.

			

			$replace['body'] = $this->build_template($this->get_template("customize_articles"),$replace);

			$replace['browser_title'] = "Tx Xchange: Customize Article";
			

			// 	Personalized GUI
            $LabelTitle=$_SESSION['providerLabel']['My Template Plans']!=''?$_SESSION['providerLabel']['My Template Plans']:"MY TX PLANS";
            $replace['path_name'] = strtoupper($LabelTitle);
		 
			$replace['get_satisfaction'] = $this->get_satisfaction();
			 
			$this->output = $this->build_template($this->get_template("main"),$replace);

		}
        /**
        * This function shows the form for entering name of copy of template plan. 
        */
        function save_as_template_plan(){
            $replace['new_name'] = trim($this->value('new_name'));
            $replace['javascriptAlert'] = "";
            $replace['plan_id'] = $this->value('plan_id');
            
            $user_id = $this->userInfo('user_id');
            if( $this->value('submit_action') == "Submit" ){
                if($replace['new_name'] != ""){
                    $query = "select count(*) from plan p  
                        where p.user_id = '{$user_id}' and p.user_type = '2' 
                        and p.patient_id is null and p.status = 1 
                        and p.plan_name = '{$replace['new_name']}' ";     
                    $result = @mysql_query($query);
                    $count = @mysql_result($result,0);
                    if( $count > 0 ){
                        $replace['javascriptAlert'] = "alert('Template name already exist.')";
                    }
                    else{
                        if( is_numeric($user_id) && is_numeric($replace['plan_id'] )){ 
                            $this->copy_plan( $user_id, $replace['plan_id'], $replace['new_name']);
                            $replace['javascriptAlert'] = "parent.parent.GB_CURRENT.hide();";
                            $replace['javascriptAlert'] .= "top.location = 'index.php?action=therapistPlan';";
                        }
                        else{
                            $replace['javascriptAlert'] = "alert('Failed to copy Template plan.')";
                        }
                    }
                }
            }
            $this->output = $this->build_template($this->get_template("save_as_template"),$replace);
        }
		/**

		 * This function removes any associated article from Template plan or Treatment plan.

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

		 * This function associates any article with Template plan or Treatment plan.

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

		 * This function shows list of articles associated with any Template plan or Treatment plan.

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

		 * This function shows list of articles present in Therapist(s) article library.

		 *

		 * @return array

		 * @access public

		 */

		function article_library(){

				include("template/therapist/therapistArray.php");

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



				/* Search String if any */

				$sqlWhere = "";

				if($this->value('search')!='' && $this->value('clear_search') != 1 ){

					$sqlWhere = " AND ((".$this->makeSearch(ALL_WORDS,$this->value('search'),'a.article_name').") or (" .$this->makeSearch(ALL_WORDS,$this->value('search'),'a.headline')."))";

				}
				
				/*  Search String End     */

						

				//$query = " select * from article a where a.user_id = '{$this->userInfo('user_id')}' and a.status = 1  $sqlWhere {$orderby} ";
				
				$query = "SELECT DISTINCT * FROM article a WHERE a.user_id = '{$this->userInfo('user_id')}' and a.status = 1  $sqlWhere GROUP BY a.article_name,a.headline " .$orderby ;
				
				$skipValue = array('act','article_id');

				$link = $this->pagination($rows = 0,$query,$this->value('action'),$this->value('search'),$skipValue);				

				$replace['link'] = $link['nav'];

				$result = $link['result'];
				
				if( is_resource($result) && $this->num_rows($result) > 0 ){
					while($row = $this->fetch_array($result)){
	
						$row['style'] = ($c++%2)?"line1":"line2";
	
						$row['modified'] = $this->formatDate($row['modified']);
	
						$row['optionAction'] = $this->build_select_option($articleLibOption);
	
						$replace['article_library'] .= $this->build_template($this->get_template("article_library"),$row);
	
					}
				}	
				else{
					$replace['article_library'] .= "<tr><td colspan ='4' >No Records.</td></tr>";
				}
				$query_string = array(

					'plan_id' => $this->value('plan_id'),

					'search' => $this->value('search'),

					'clear_search' => $this->value('clear_search')

				);

				$replace['article_head'] = $this->build_template($this->get_template("article_head"),$this->table_heading($step4ArticleLib,"a.modified",$query_string));

				return $replace;

		}

		/**

		 * Constructs a fragment of a "WHERE" clause that can be used to conduct a search.

		 * Note that this uses "LIKE" subclauses with wildcards, and will force a full table scan.

		 * 

		 *

		 * $searchMode must be ANY_WORD, ALL_WORDS, ALL_WORDS_IN_ORDER, or EXACT_PHRASE.

		 * $searchForWords and $inColumns can each be either an array or a comma-separated string.

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

		 * This function assigns any Template plan to Patient.

		 *

		 * @access public

		 */

		function assign_plan_patient_ehs(){

			if( $this->value('plan_id') != "" && $this->value('patient_id') != "" ){

				header("location:index.php?action=choose_patient_ehs&act=plan_customize&plan_id={$this->value('plan_id')}");

			}

			include("template/therapistEhs/therapistArray.php");

			$replace['header'] = $this->build_template($this->get_template("header"));

			$replace['sidebar'] = $this->sidebar();

			$replace['act'] = $this->value('act');

			$replace['plan_id'] = $this->value('plan_id');

			$replace['plan_title'] = strtoupper($this->get_field($this->value('plan_id'), $this->config['table']['plan'], "plan_name"));

			$query_string = array(

				'plan_id' => $this->value('plan_id')

			);

			$replace['assign_plan_patient_head'] = $this->build_template($this->get_template("assign_plan_patient_head"),$this->table_heading($assignPatientHead,"u.name_last",$query_string));

			$privateKey = $this->config['private_key'];
            $sort = " AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') ";

			if( $this->value('sort') != ""){

				if( $this->value('sort')  == 'u.name_last' ){
                    $sort = "AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}')";
                }
                else
                    $sort = $this->value('sort');

			}

			$order = " asc ";

			if($this->value('order') != ""){

				$order = $this->value('order');

			}

			$where = "";

			if($this->value('search') != "" ){
                $privateKey = $this->config['private_key'];
				$where = " and  ( CAST(AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') AS CHAR) like '%{$this->value('search')}%' or CAST(AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') AS CHAR) like '%{$this->value('search')}%') ";

			}
            $privateKey = $this->config['private_key'];
			$query = " select 
                        AES_DECRYPT(UNHEX(u.name_title),'{$privateKey}') as name_title, 
                        AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as name_first,
                        AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as name_last, 
                        tp.patient_id from therapist_patient  tp
						inner join user u on u.user_id = tp.patient_id 
						and u.usertype_id = '1' and ( u.status = '1' or u.user_id in (select u_id from program_user where p_status =  '1' ) )
						where tp.therapist_id = '{$this->userInfo('user_id')}' and 
                        u.user_id in 
                        (
                            select user_id from clinic_user 
                            where clinic_id = '{$this->clinicInfo('clinic_id',$this->userInfo('user_id'))}'
                        )  
                        {$where} order by {$sort} {$order} ";

			

			$link = $this->pagination($rows = 0,$query,$this->value('action'),$this->value('search'),'');				

			$replace['link'] = $link['nav'];

			$result = $link['result'];								  	

			if(is_resource($result)){

				while($row = $this->fetch_array($result)){

					$row['style'] = ($c++%2)?"line1":"line2";

					$row['actionOption'] = $this->build_select_option($assignPatientOption);

					$replace['assign_plan_patient_record'] .= $this->build_template($this->get_template("assign_plan_patient_record"),$row);

				}

			}

			$nav_bar = array(

								'patient' => ($this->value('plan_id') != "")?'<a href="index.php?action=assign_plan_patient&plan_id='.$this->value("plan_id").'"><img src="images/03_patient_red.gif" /></a>':'<img src="images/03_patient_red.gif" />',

								'assign' => ($this->value('plan_id') != "")?'<a href="index.php?action=createNewPlan&act=plan_edit&plan_id='.$this->value("plan_id").'"><img src="images/04_assign_gray.gif" /></a>':'<img src="images/04_assign_gray.gif" />',

						);

			if(is_array($nav_bar)){

				$replace = $replace + $nav_bar;

			}

			

			$replace['type'] = "";

			$temp = $replace['act'];

			$replace['back_url'] = "";

			if($_SESSION['type'] == "treatment_plan"){

				$replace['type'] = 'treatment_plan';

				// Build Back button Url

					$replace['act'] = "plan_edit";

					$replace['action'] = "customize_articles";

					$backUrl = array('action','patient_id','plan_id','type','act');

					$replace['back_url'] = "<img src='images/btn-back.jpg' value='Back' onClick=\"window.location='".$this->buildBackUrl($backUrl,$replace)."'\" />";

				// End of build Back button Url

			}

			if($_SESSION['type'] == "finish"){

				$replace['type'] = 'finish';

				// Build Back button Url

					$temp = $replace['act'];

					$replace['act'] = "plan_edit";

					$replace['action'] = "customize_articles";

					$backUrl = array('action','plan_id','type','act');

					$replace['back_url'] = "<img src='images/btn-back.jpg' value='Back' onClick='window.location='".$this->buildBackUrl($backUrl,$replace)."' />";

				// End of build Back button Url

			}

			

			$replace['act'] = $temp;

			$replace['body'] = $this->build_template($this->get_template("assign_plan_patient"),$replace);

			$replace['browser_title'] = "Tx Xchange: Assign Plan to Patient";
            $replace['get_satisfaction'] = $this->get_satisfaction();   
			$this->output = $this->build_template($this->get_template("main"),$replace);

		}

		/**

		 * This function shows patient list.

		 * 

		 * @access public

		 */

		function view_patient_details(){

			$replace['browserTitle'] = "Patient Detail";

			if($this->value('id') != "" ){
                $privateKey = $this->config['private_key'];
				/*$query = "select *,
                          AES_DECRYPT(UNHEX(name_title),'{$privateKey}') as name_title, 
                          AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
                          AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last  
                          from user where usertype_id = '1' and user_id = '{$this->value('id')}' ";*/
                $query = "select * from user where usertype_id = '1' and user_id = '{$this->value('id')}' ";          

				$result = $this->execute_query($query);

				if($row = $this->fetch_array($result)){
                    $encrypt_field = array('name_title','name_first','name_last','password','address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
                    $row  = $this->decrypt_field($row, $encrypt_field);
			//print_r($row);		
                    //$row['state'] = $row['state'];
					
					if($row['country']=='US')
					$cntry = 'United States';
					if($row['country']=='CAN')
					$cntry = 'Canada';
					
					$row['country'] = $cntry;
					//print_r($row);

					$replace['mainRegion'] = $this->build_template($this->get_template("view_patient_details"),$row);

				}

			}

			else{

				$replace['mainRegion'] = "Patient not found";

			}

												

			$this->output = $this->build_template($this->get_template("popuptemplate"),$replace);

		}

		/**

		 * This function helps in selecting any patient.

		 * 

		 * @access public

		 *

		 */

		function choose_patient_ehs(){

			if($this->value('act') == "plan_customize"){

				unset($_SESSION['type']);

				header("location:index.php?action=assigned_plan_patient&plan_id={$this->value('plan_id')}&url=createNewEhsPlan");

			}

			include("template/therapistEHS/therapistArray.php");

			$replace['header'] = $this->build_template($this->get_template("header"));

			$replace['sidebar'] = $this->sidebar();

			$replace['plan_id'] = $this->value('plan_id');

			$replace['plan_title'] = strtoupper($this->get_field($this->value('plan_id'), $this->config['table']['plan'], "plan_name"));

			//$replace['patient_id'] = $this->value('patient_id');

			//$replace['name_title'] = $this->userInfo("name_title",$this->value('patient_id'));

			//$replace['name_first'] = strtoupper($this->userInfo("name_first",$this->value('patient_id')));

			//$replace['name_last'] = strtoupper($this->userInfo("name_last",$this->value('patient_id')));

			

			$replace['type'] = "";

			if($_SESSION['type'] == "treatment_plan"){

				$replace['type'] = 'treatment_plan';

			}

			if($_SESSION['type'] == "finish"){

				$replace['type'] = 'finish';

			}

			

			$replace['step1'] = "<img src='images/01_plan_gray.gif' />";

			$replace['step2'] = "<img src='images/02_customize_gray.gif' />";

			$replace['step3'] = "<img src='images/03_patient_gray.gif' />";

			$replace['step4'] = "<img src='images/04_assign_red.gif' />";

			

			$replace['body'] = $this->build_template($this->get_template("assign_notify"),$replace);

			$replace['browser_title'] = "Tx Xchange: Select Patient";
		 
			$replace['get_satisfaction'] = $this->get_satisfaction();
			 
			$this->output = $this->build_template($this->get_template("main"),$replace);

		}

		/**

		 * This function copies any Template plan and assign it to selected Patient.

		 * 

		 * @access public

		 *

		 */

		function assigned_plan_patient_ehs() { 

		        //assign plan to patient
                      
			if( $this->value('plan_id') != "") {

                                $notify = $this->value('notify');
                                if($notify > 0) {
                                        $notify = $this->value('notify');

                                } else {
                                         $notify = '0';
                                }

                                 $clinicId = $this->clinicInfo('clinic_id');

				if( $_SESSION['type'] == "treatment_plan" ){ 

					if( $this->value('plan_id') != ""){

						$data = array(
									'user_type' => 2,

                                                                        'ehsFlag' => 1,

									'modified' => date("Y-m-d"),

									'status' => 1

								);

						$where = " plan_id = '{$this->value('plan_id')}'";

						if($this->update($this->config['table']['plan'],$data,$where)){

							//echo "create new treatment plan successfull updation";

						}

					}

					header("location:index.php?action=therapistEhsPatient&mass=plan");

					exit();

				}

				
                                //echo $_SESSION['type'];exit;
				if( $_SESSION['type'] == "finish" ) { 


					if( $this->value('plan_id') != "" ){

						$data = array(
									'user_type' => 2,

                                                                        'ehsFlag' => '1',

                                                                        'clinicId' => $clinicId,

                                                                        'scheduler_status' => '1',

                                                                         'notify' => $notify,

									'modified' => date("Y-m-d"),

									'status' => '4'

								);
                                                
                                                

						$where = " plan_id = '{$this->value('plan_id')}'";

						if($this->update($this->config['table']['plan'],$data,$where)){

                                                        //Archive the old plan whose copy is made
                                                        $sqlArchiveoldPlan = "SELECT * FROM plan WHERE plan_id = '{$this->value('plan_id')}' AND old_plan_id IS NOT NULL";
                                                        $rsArchive = $this->execute_query($sqlArchiveoldPlan);
                                                        $numrowArchive = $this->num_rows($rsArchive);
                                                        if($numrowArchive > 0) {
                                                                $rowArchive = mysql_fetch_assoc($rsArchive);
                                                                $old_plan_id =  $rowArchive['old_plan_id'];
                                                                $where1 = " plan_id = '{$old_plan_id}'";
                                                                $updateData['status'] = '2';
							        $updateData['modified'] = date("Y-m-d");
							        $this->update($this->config['table']['plan'],$updateData,$where1); 

                                                                // $ehsPatientArr = $this->getProviderEHSPatients($clinicId);
                                                                 if($this->is_corporate($clinicId)==1){
                    
                                                                         $ehsPatientArr= $this->get_paitent_list($clinicId);
                                                                     }else{
                                                                         $ehsPatientArr= $this->getProviderEHSPatients($clinicId);
                                                                    }
                                                                 $patientCount = count($ehsPatientArr);
                                                                 $pat = 0;
                                                                 while($pat < $patientCount) { 
                                                                             $query1 = "update plan set status = '2' where parent_template_id  = {$rowArchive['old_plan_id']} AND patient_id = '$ehsPatientArr[$pat]' ";
                                                                             $result1 = $this->execute_query($query1);
                                                                             $pat++;
                                                                 } 
                                                        }

                                                        //End here

							header("location:index.php?action=therapistEhsPatient&mass=plan");

					                exit();
						}

					}

					//header("location:index.php?action=therapistEhsPatient");

					//exit();

				}

                                 $query = "select * from plan where plan_id = '{$this->value('plan_id')}' ";
				$plan_result = $this->execute_query($query);

				if(is_resource($plan_result)){

                                        $row = mysql_fetch_assoc($plan_result);

                                        $data = array(
						'clinicId' => $clinicId,

                                                 'plan_name' => $row['plan_name'],

                                                 'parent_template_id' => $row['plan_id'],
        
                                                'user_id' => $this->userInfo('user_id'),

					        'user_type' => '2',

                                                'scheduler_status' => '1',

                                                'notify' => $notify,
                
                                                'ehsFlag' => '1',
                                                
                                                'schdulerAction' => '1',
                
					        'creation_date' => date("Y-m-d"),

					        'modified_date' => date("Y-m-d"),

					        'status' => '4'

					);

                                        if($this->insert($this->config['table']['plan'],$data)){

						$new_plan_id = $this->insert_id();

						// copy all treatments associated with plan to new plan id.

						$query = "select * from plan_treatment where plan_id = '{$this->value('plan_id')}' ";

						$plan_treatment = $this->execute_query($query);

						while($row = $this->fetch_array($plan_treatment)){

							$data = array(

										'plan_id' => $new_plan_id,

										'treatment_id' => $row['treatment_id'],

										'instruction' => $row['instruction'],

										'sets' => $row['sets'],

										'reps' => $row['reps'],

										'hold' => $row['hold'],

										'benefit' => $row['benefit'],

										'lrb' => $row['lrb'],

										'treatment_order' => $row['treatment_order'],

										'creation_date' => date("Y-m-d"),

										'modified' => date("Y-m-d"),

									);

							$this->insert($this->config['table']['plan_treatment'],$data);

						}

						// copy all articles associated with plan to new plan id.

						$query = "select * from plan_article where plan_id = '{$this->value('plan_id')}' ";

						$plan_article = $this->execute_query($query);

						while($row = $this->fetch_array($plan_article)){

							$data = array(

										'plan_id' => $new_plan_id,

										'article_id' => $row['article_id'],

										'creation_date' => date("Y-m-d"),

										'modified' => date("Y-m-d"),

									);

							$this->insert($this->config['table']['plan_article'],$data);

						}

						if($this->value('url') == "createNewPlan" ){

							header("location:index.php?action=createNewEhsPlan&type=finish&act=plan_edit&plan_id={$new_plan_id}");

							exit();

						}

						header("location:index.php?action=therapistEhsPatient&mass=plan");

					}



                                       
                                       
                                     
                                }

			}

		}

		function notify_mail_patient(){

			if( $this->value('plan_id') != "" && $this->value('patient_id') != "" ){

				// Notification mail to patient about the new plan being assigned to him/her by therapist.

				if($this->value('notify') == '1'){

					//have the HTML content
                    $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
				if($clinic_channel==1){
                                $business_url=$this->config['business_tx']; 
                                $support_email=$this->config['email_tx'];
                            }else{
                                $business_url=$this->config['business_wx']; 
                                $support_email=$this->config['email_wx'];   
                            }
					$data = array(

						'plan_name' => $this->get_field($this->value('plan_id'),'plan','plan_name'),

						'url' => $this->config['url'],

						'images_url' => $this->config['images_url'],
					    'business_url'=>$business_url,
                        'support_email'=>$support_email

						);


					
	                $clinic_type = $this->getUserClinicType($this->value('patient_id'));
	               
	                if( $clinic_channel == 1){
	                	$subject = "Your Exercise or Treatment Plan ";
	                	$message = $this->build_template("mail_content/plpto/notify_mail_plpto.php",$data);
	                }
	                else{
	                	$subject = "Your Exercise or Treatment Plan ";
                        $message = $this->build_template("mail_content/plpto/notify_mail_plpto.php",$data);
                    	
	                }	
				    //$message = $this->build_template("mail_content/notify_mail.php",$data);

					$to = $this->userInfo("username",$this->value('patient_id'));

					

					

					// To send HTML mail, the Content-type header must be set

					$headers  = 'MIME-Version: 1.0' . "\n";

					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";

					

					// Additional headers

					//$headers .= 'To: '.$fullName.'<'.$_POST['email_address'].'>'. "\n";

					//$headers .= "From: " . $this->userInfo('name_title') . "&nbsp;" . $this->userInfo('name_first') ."&nbsp;" . $this->userInfo('name_last') ."<". $this->userInfo('username') .">" . "\n";

					$headers .= "From: " . "Tx Xchange <do-not-reply@txxchange.com>" . "\n";

					$returnpath = '-fdo-not-reply@txxchange.com';
					// Mail it

					mail($to, $subject, $message, $headers, $returnpath);

				}

			

			header("location:index.php?action=therapistViewPatient&id={$this->value('patient_id')}");

			}

			exit();

		}
        /**
        * This function will activate new plan and copy new plans to all existing active therapist's of system.
        */
        function activateClinicPlan(){
                $clinic_id = $this->clinicInfo("clinic_id");
                if( is_numeric($clinic_id) ) {
                    $plan_id = $this->value('plan_id');
                    
                    if( is_numeric($plan_id)){
                    
                        //Get plan status
                        $query = "select is_public from plan where plan_id = '{$plan_id}' "; 
                        $result = @mysql_query($query);
                        if( $row = @mysql_fetch_array($result) ){
                           $is_public = $row['is_public'];
                        }
                        if( (is_null($is_public) || $is_public != 1) && $this->value('act') == 'publish' ){
                            $this->copy_plan_to_all_account_clinic($clinic_id, $plan_id);
                            $is_public_sql = " ,is_public = 1 ";
                        }
                        else{
                            $is_public_sql = "";
                        }
                       
                       $query = " update plan set status = 1 $is_public_sql where plan_id = '{$plan_id}' "; 
                       @mysql_query($query);
                    }
                    //unset($_SESSION['plan_id']);
                    
               }
               // Redirect to Home page.
               header("location:index.php?action=therapist");
                
                
        }
        /**
        * This function will get the list of clinic in the account.
        */
        function copy_plan_to_all_account_clinic($clinic_id, $plan_id){
            if( is_numeric($clinic_id) && $clinic_id!= 0 ){
                $parent_clinic_id = $this->get_field($clinic_id,'clinic','parent_clinic_id');
                if( is_numeric($parent_clinic_id)){
                    if( $parent_clinic_id == 0){
                        $sql= "select clinic_id from clinic where parent_clinic_id = '{$clinic_id}' or clinic_id = '{$clinic_id}'  ";                        
                    }
                    else{
                        $sql= "select clinic_id from clinic where parent_clinic_id = '{$parent_clinic_id}' or clinic_id = '{$parent_clinic_id}' ";
                    }
                }
            }
            if( !empty($sql) ){
                $result = @mysql_query($sql);
                while( $row = @mysql_fetch_array($result)){
                    $this->copy_plan_to_all_clinic_therapists($row['clinic_id'],$plan_id);
                }
            }
            return "";
        }
        /**
        * This function will copy plan new plan to all existing therapist of application.
        */
        function copy_plan_to_all_clinic_therapists($clinic_id, $plan_id){
            if( is_numeric($plan_id) && $plan_id > 0 && is_numeric($clinic_id) && $clinic_id > 0 ){
                
                $privateKey = $this->config['private_key'];
                $query = " select u.*,
                           AES_DECRYPT(UNHEX(u.name_title),'{$privateKey}') as name_title, 
                           AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as name_first,
                           AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as name_last 
                           from clinic_user cu
                           inner join user u on cu.user_id = u.user_id and u.usertype_id = 2 and u.status = 1 and u.user_id != '{$this->userInfo('user_id')}'
                           where cu.clinic_id = '{$clinic_id}' ";
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

         * To show the left navigation panel.

         *

         * @return string

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

	$obj = new therapistEhsPlan();

?>

