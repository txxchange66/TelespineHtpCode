<?php	
	/**
	 *
	 * Copyright (c) 2008 Tx Xchange
	 *
	 * This class includes the Intake Adult Paper Work for Naturopatic Clinic and Doctorsfunctionality.
	 * 
	 * // necessary classes  
	 * require_once("module/application.class.php");
	 * 
	 * // file upload class
	 * require_once("include/fileupload/class.upload.php");
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
    // Draw Graph library Files 
    require_once("include/bar-chart-class.php");    
    
	require_once("include/paging/my_pagina_class.php");	
	require_once("include/fileupload/class.upload.php");
	require_once("module/application.class.php");
	require_once("include/validation/_includes/classes/validation/ValidationSet.php");
	require_once("include/validation/_includes/classes/validation/ValidationError.php");	

	// class declaration
  	class intakePaper extends application{
  		
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
                $str = ""; //default if no action is specified
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
            /*
            $str = $str."()";
            eval("\$this->$str;"); 
            $this->display();            
            */
        }
		  /**
         * This function assign intake pater to Patient
         *
         * @param int $providerID -id of the provider/AA. 
         * @param int $PatientID -id of the Patient.
         * @access private
         */ 
           function assignIntake(){
            
            $providerID=$this->value('provider_id');
            $PatientID=$this->value('patient_id');
            $userId=$this->userInfo();
            
	$intakeview=$this->execute_query("select * from patient_brief_intake  where intake_compl_status ='0' and p_user_id=".$PatientID);
                if($this->num_rows($intakeview) >0){
                	echo "brifeintakeassign";
                  }else{
           
            
            if($PatientID!='' and $providerID!=''){
            $intakearray=array(
            'clinic_id'                 =>  $this->clinicInfo('clinic_id',$PatientID),
            'assign_user_id'            =>  $providerID,
            'p_user_id'                 =>  $PatientID,
            'assign_datetime'           =>  date('Y-m-d H:i:s',time()),
            'intake_compl_status'       =>  '0',
            'intake_last_page'          =>  1,
            'intake_page_filled'          =>  '0',
            'intake_sent_email'          =>  '0',
            'intake_last_upd_datetime'  =>  date('Y-m-d H:i:s',time())  
             );
             $this->insert('patient_intake',$intakearray);
             if($this->insert_id()!=0){
                echo "success"; 
                // Send Introductory Email                  
                $this->send_intake_email($PatientID);
                
                }else{
                 echo "fail";   
                }    
            }else{
                echo "fail";
            }
            }
           }
           
           function unassignIntake(){
            
            $providerID=$this->value('provider_id');
            $PatientID=$this->value('patient_id');
            $userId=$this->userInfo();
            if($PatientID!='' and $providerID!=''){ 
           // echo "DELETE FROM patient_intake WHERE assign_user_id ='".$providerID."' AND p_user_id ='".$PatientID."'";die;
            $deletedata=$this->execute_query("DELETE FROM patient_intake WHERE  p_user_id ='".$PatientID."'");
            
             if($deletedata){
                echo "success"; 
                }else{
                 echo "fail";   
                }    
            }else{
                echo "fail";
            }
           }
           /***unassign brief intake form***/
           function unassignbriefIntake(){
            
            $providerID=$this->value('provider_id');
            $PatientID=$this->value('patient_id');
            $userId=$this->userInfo();
            if($PatientID!='' and $providerID!=''){ 
            $deletedata=$this->execute_query("DELETE FROM  patient_brief_intake WHERE p_user_id ='".$PatientID."'");
             if($deletedata){
                echo "success"; 
                }else{
                 echo "fail";   
                }    
            }else{
                echo "fail";
            }
           }
           /**
            * This function assign intake pater to Patient
            *
            * @param int $providerID -id of the provider/AA.
            * @param int $PatientID -id of the Patient.
            * @access private
            */
           function assignbriefIntake(){
           
           	$providerID=$this->value('provider_id');
           	$PatientID=$this->value('patient_id');
           	$userId=$this->userInfo();
           	//echo "select * from patient_intake where intake_compl_status ='0' and p_user_id=".$PatientID;
           	//die;
           	$intakeview=$this->execute_query("select * from patient_intake where intake_compl_status ='0' and p_user_id=".$PatientID);
                if($this->num_rows($intakeview) >0){
                	echo "intakeassign";
                  }else{
           
           
           	if($PatientID!='' and $providerID!=''){
           		$intakearray=array(
           				'clinic_id'                 =>  $this->clinicInfo('clinic_id',$PatientID),
           				'assign_user_id'            =>  $providerID,
           				'p_user_id'                 =>  $PatientID,
           				'assign_datetime'           =>  date('Y-m-d H:i:s',time()),
           				'intake_compl_status'       =>  '0',
           				'intake_sent_email'          =>  '0',
           				'intake_last_upd_datetime'  =>  date('Y-m-d H:i:s',time())
           		);
           		$this->insert('patient_brief_intake',$intakearray);
           		if($this->insert_id()!=0){
           			echo "success";
           			// Send Introductory Email
           			$this->send_intake_email($PatientID);
           
           		}else{
           			echo "fail";
           		}
           	}else{
           		echo "fail";
           	}
           	}
           	 
           }
           
         /**
         * This function sends the initial email to patients for filling the intake form
         * @param Integer $patient_id
         * @access public
         */     
         public function send_intake_email($patient_id){
                
                $clinicName=html_entity_decode($this->get_clinic_info($patient_id,"clinic_name"), ENT_QUOTES, "UTF-8");
                $replacePatient['urlLogin']=$this->config['images_url'];
                $Patientsubject = "Your ".$clinicName." New Patient Forms";
                $replacePatient['ClinicName']=$clinicName;

                // Content Of the email send to  patient
                $Patientmessage = $this->build_template($this->get_template("initialIntakeEmail"),$replacePatient);
                $Patientto = $this->userInfo("username",$patient_id);
                
                $this->send_external_email_intake($Patientto,$Patientsubject,$Patientmessage,$clinicName);
         }
         
         public function send_external_email_intake($Patientto,$Patientsubject,$Patientmessage,$clinicName){

                    // To send HTML mail, the Content-type header must be set

                    $Patientheaders  = 'MIME-Version: 1.0' . "\n";
                    $Patientheaders .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                    //$Patientheaders .= "From: ".$clinicName." <support@txxchange.com>" . "\n";
                    //$Patientreturnpath = '-fsupport@txxchange.com';
                    $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
					if( $clinic_channel == 1){
					    $Patientheaders .= "From: ".$this->setmailheader($clinicName). " <".$this->config['email_tx'].">" . "\n";
					    $Patientreturnpath = "-f".$this->config['email_tx'];
					    }else{
					    $Patientheaders .= "From: ".$this->setmailheader($clinicName). " <".$this->config['email_wx'].">" . "\n";
					    $Patientreturnpath = '-f'.$this->config['email_wx'];   
					    }
                    

                    // Mail it
                    @mail($Patientto, $Patientsubject, $Patientmessage, $Patientheaders, $Patientreturnpath);          
         }  
	    
         /**
         * This function fill intake paper to Patient
         *
         * @access public
         */     
        public function fillintakepaperwork(){

        	$userInfo=$this->userInfo();              
            $patient_id=$userInfo['user_id'];  
            
            $intakeview=$this->execute_query("select intake_last_page,in_gen from patient_intake where p_user_id=".$patient_id);
            $rowintake=$this->fetch_array($intakeview);            
           // echo"<pre>";   
           // print_r($rowintake);         
            $pageOpen=$this->value('open_page_number');
            if($pageOpen==''){
                    $pageOpen=$rowintake['intake_last_page']; 
            }
            
            // Populate the Dropdown values
          
           $intakeviewcon=$this->execute_query("select in_country from patient_intake where p_user_id='".$userInfo['user_id']."' ");
                $rowintakecon=$this->fetch_array($intakeviewcon); 
              
            
            $templateOpen="intakepaperwork".$pageOpen;


                if($rowintakecon['in_country']!=''){
                	if($rowintakecon['in_country']=='CAN')
            			$canadaStatesArray=$this->config['canada_state'];
            		else {
            			$canadaStatesArray=$this->config['state'];
            		     }
                    }
                    else
                    { 
			$canadaStatesArray=$this->config['state'];
		    }
		    
		    if($rowintakecon['in_country']=='' && $this->userInfo('country')!=''){
		    if($this->userInfo('country')=='CAN'){
              			$canadaStatesArray=$this->config['canada_state'];
            		}else {
            		 	$canadaStatesArray=$this->config['state'];
            		 }
		    }
		    
            $canadaStates=$this->build_select_option($canadaStatesArray,$this->userInfo('state'));       
				$countryArray = $this->config['country'];
				//$replaceIntake['country']=implode("','",$countryArray); 
		      $country = $this->build_select_option($countryArray,$this->userInfo('country'));
		                  
                             
            $monthArray=array('1'=>"Jan",'2'=>"Feb",'3'=>"Mar",'4'=>"Apr",'5'=>"May",'6'=>"Jun",'7'=>"Jul",'8'=>"Aug",'9'=>"Sep",'10'=>"Oct",'11'=>"Nov",'12'=>"Dec");
            $dateArray=array();            
            for($i=1;$i<=31;$i++){
                      $dateArray[$i]=str_pad($i,2,'0',STR_PAD_LEFT);
            }
            
            $yearArray=array();
            for($yr=1920;$yr<=date("Y");$yr++){
                $yearArray[$yr]=$yr;
            }            
            
            $yearReportArray=array();
            for($yr=1940;$yr<=date("Y");$yr++){
                $yearReportArray[]=$yr;
            }            
            
            $dob = $this->userInfo('dob');
           $dob_split = explode( '/', $dob );
           $month = $dob_split[0];
           $date = $dob_split[1];
           $year = $dob_split[2];
           if(substr($month, 0, 1) == "0")
           $month = substr($month, 1);
           if(substr($date, 0, 1) == "0")
           $date = substr($date, 1); 
            
            $replaceIntake=array();
            $returnData=$this->get_intake_paperwork_data($patient_id);
            
            $returnData['in_add'] = $this->userInfo('address')." ".$this->userInfo('address2');
            $returnData['in_city'] = $this->userInfo('city');
            $returnData['in_country'] = $this->userInfo('country');
            $returnData['in_prov'] = $this->userInfo('state');
            $returnData['in_tel'] = $this->userInfo('phone1');
            $returnData['in_postal'] = $this->userInfo('zip');
            $replaceIntake['in_genF']=''; 
            $replaceIntake['in_genM']='';

	    if(strtoupper(substr($this->userInfo('gender'),0,1))=='M')
            	$replaceIntake['in_genM'] = 'checked';
            if(strtoupper(substr($this->userInfo('gender'),0,1))=='F')
              	$replaceIntake['in_genF'] = 'checked';
              	
            $returnData['in_dob_mon'] = $month;
            $returnData['in_dob_date'] = $date;
            $returnData['in_dob_year'] = $year;
               // print_r($returnData);                                             
            foreach($returnData as $IntakeKey=>$IntakeValue){
                    if($IntakeKey=='intake_last_page' || $IntakeKey=='intake_compl_status') continue;
                    $IntakeValue=urlencode(html_entity_decode(($this->jsesc($IntakeValue))));
                    $intakeJavascriptString.="\"{$IntakeKey}\":\"{$IntakeValue}\",";                    
            }   
            $intakeJavascriptString=trim($intakeJavascriptString,",");
            $replaceIntake['IntakeArrayData']=$intakeJavascriptString;  
            
                                  
            $replaceIntake['navigation_header']=$this->navigation_header($pageOpen,$rowintake['intake_page_filled']);
            
            $name_first=$this->userInfo('name_first',$patient_id);
            $name_last=$this->userInfo('name_last',$patient_id);
            $replaceIntake['patientName']=$name_first.' '.$name_last;
            $replaceIntake['IntakeCreatedDate']=$this->formatDate($returnData['assign_datetime'],'m-d-Y');
            $replaceIntake['patientEmailAddress']=$returnData['username'];
            $replaceIntake['patient_id']=$patient_id;
            if($rowintake['in_gen']=='M')
            {
            	$replaceIntake['genderBaseSection']=$this->build_template($this->get_template("maleSection"),$replace);
            }
            else {
           	    $replaceIntake['genderBaseSection']=$this->build_template($this->get_template("femaleSection"),$replace);
            }
            
            
            $replaceIntake['selectMonthOption']=$this->build_select_option($monthArray,$month);
            $replaceIntake['selectDateOption']=$this->build_select_option($dateArray,$date);
            $replaceIntake['selectYearOption']=$this->build_select_option($yearArray,$year);  
            $replaceIntake['selectReportYearOption']=$this->build_select_option($yearReportArray);  
            $replaceIntake['patient_country_options']=$country;
            $replaceIntake['stateOption']=$canadaStates;  
            $replaceIntake['address']=$this->userInfo('address')." ".$this->userInfo('address2');
            $replaceIntake['city']=$this->userInfo('city');
            $replaceIntake['zipcode']=$this->userInfo('zip');
            $replaceIntake['tel_home']=$this->userInfo('phone1');
            $replaceIntake['tel_cell']=$this->userInfo('phone2');
            $replaceIntake['gender']=$this->userInfo('gender');
                      
            $this->output = $this->build_template($this->get_template($templateOpen),$replaceIntake);
  		    //$replace['body'] = $this->build_template($this->get_template("intakepaperwork3"),$replace);
            //$this->output = $this->build_template($this->get_template("main"),$replace);
        }
                                                                                            
         /**
         * This function generates the navigation header
         *
         * @access public
         */     

        public function navigation_header($pageOpen,$intake_page_filled=0,$navigation_header_template='navigation_header'){

            
                 /*
                     echo $pageOpen.'<br />';
                     echo $intake_page_filled.'<br />';
                     echo $navigation_header_template.'<br />';
                 */
                 $replace["selectedclass1"]='';
                 $replace["selectedclass2"]='';
                 $replace["selectedclass3"]='';
                 $replace["selectedclass4"]='';
                 $replace["selectedclass5"]='';
                 $replace["selectedclass6"]='';
                 $replace["selectedclass7"]='';
                 $replace["selectedclass8"]='';
                 $replace["selectedclass9"]='';
                 
                 $replace["callJavascriptSubmit1"]='';
                 $replace["callJavascriptSubmit2"]='';
                 $replace["callJavascriptSubmit3"]='';
                 $replace["callJavascriptSubmit4"]='';
                 $replace["callJavascriptSubmit5"]='';
                 $replace["callJavascriptSubmit6"]='';
                 $replace["callJavascriptSubmit7"]='';
                 $replace["callJavascriptSubmit8"]='';
                 $replace["callJavascriptSubmit9"]='';
                 
                 
                 $replace["pageNumber"]=$pageOpen;
                 
                 if($navigation_header_template=='navigation_header_view'){
                     for($i=0;$i<$intake_page_filled;$i++){
                        $keyFill="callJavascriptSubmit".$i;
                        $replace[$keyFill] =" onclick = 'submitIntakeForm(\"".$i."\")' ";
                     }
                 }   else   {
                     for($i=0;$i<$intake_page_filled;$i++){
                        $keyFill="callJavascriptSubmit".$i;
                        //$replace[$keyFill] =" onclick = 'submitIntakeForm(\"".$i."\")' ";
                        $replace[$keyFill] =" onclick = 'return validate(\"intakeform\", \"".$i."\")' ";
                        
                     }
                 }                 
                 
                 
            
                 $selectedClass='selectedclass'.$pageOpen;
                 $replace[$selectedClass]=' class="selected"';
                 return $this->build_template($this->get_template($navigation_header_template),$replace);
        }


         /**
         * This function sends TX message:(Patient Name) has signed up
         * @param Integer $sender_id
         * @param Integer $rec_id
         * @param String  $sub
         * @param String  $content
         * @return void
         * @access public
         */
    
         public function sendIntakeTxMessage($sender_id, $rec_id, $sub, $content) {
        $data = array ('sender_id' => $sender_id,'patient_id' => $rec_id, 'subject' => $this->encrypt_data ( $sub ), 'content' => $this->encrypt_data ( $content ), 'parent_id' => '0', 'sent_date' => date ( 'Y-m-d H:i:s', time () ), 'recent_date' => date ( 'Y-m-d H:i:s', time () ), 'replies' => '0' );
        
        if ($this->insert ( "message", $data )) {
            $message_id = $this->insert_id ();
            
            // Entry for Account Admin
            $data = array ('message_id' => $message_id, 'user_id' => $sender_id, 'unread_message' => '2' );
            $this->insert ( "message_user", $data );
            
            // Entry for Patient
            

            $data = array ('message_id' => $message_id, 'user_id' => $rec_id, 'unread_message' => '1' );
            $this->insert ( "message_user", $data );
        
        }
    
    }

         /**
         * This function to save intake paper by Patient
         * @access public
         */     
        
        public function saveintakepaperwork(){
            
                
              //echo '<pre>'; print_r($_REQUEST); 
                
             //die();

         if($_REQUEST['intake_last_page']=='8'){  
        	if(!isset($_POST['in_sympt_a'])) 
            	        $_POST['in_sympt_a']='';
         
	        if(!isset($_POST['in_sympt_b'])) 
	                    $_POST['in_sympt_b']='';
            
         
         }      
              
              
              
         if($_REQUEST['intake_last_page']=='3'){  
        	if(!isset($_POST['in_knw_dis_w'])) 
            	        $_POST['in_knw_dis_w']='';
         
	        if(!isset($_POST['in_cur_hcreY'])) 
	                    $_POST['in_cur_hcreY']='';
         	if(!isset($_POST['in_cur_hcreN'])) 
	                    $_POST['in_cur_hcreN']='';
         
         }
             
         if($_REQUEST['intake_last_page']=='5'){  
        	if(!isset($_POST['in_exercise_y'])) 
                    $_POST['in_exercise_y']='';        
	        if(!isset($_POST['in_exercise_h'])) 
	                    $_POST['in_exercise_h']='';
         	if(!isset($_POST['in_tele_h'])) 
	                    $_POST['in_tele_h']='';
			if(!isset($_POST['in_read_h'])) 
                    $_POST['in_read_h']='';         
	        if(!isset($_POST['in_religoius_y'])) 
	                    $_POST['in_religoius_y']='';
         	if(!isset($_POST['in_smoked_yrs'])) 
	                    $_POST['in_smoked_yrs']='';	                    
         	if(!isset($_POST['in_smoked_h'])) 
	                    $_POST['in_smoked_h']='';	   
         }
                    
         if($_REQUEST['intake_last_page']=='1'){  
        	if(!isset($_POST['in_emp'])) 
                    $_POST['in_emp']='';
                    
              if(!isset($_POST['in_occupation'])) 
                    $_POST['in_occupation']='';
                    
              if(!isset($_POST['in_hpweek'])) 
                    $_POST['in_hpweek']='';
         }

         if($_REQUEST['intake_last_page']=='1'){       
                if(!isset($_POST['in_work_add'])) 
                    $_POST['in_work_add']='';
         }
                    
         if($_REQUEST['intake_last_page']=='1'){  
        	if(!isset($_POST['in_retired'])) 
                    $_POST['in_retired']='';
         }

         if($_REQUEST['intake_last_page']=='4'){       
                if(!isset($_POST['in_his_cancer'])) 
                    $_POST['in_his_cancer']='';
                if(!isset($_POST['in_his_diabetes'])) 
                    $_POST['in_his_diabetes']='';
                if(!isset($_POST['in_his_heart_disease'])) 
                    $_POST['in_his_heart_disease']='';
                if(!isset($_POST['in_his_high_blood_pressure'])) 
                    $_POST['in_his_high_blood_pressure']='';

                if(!isset($_POST['in_his_kidney_disease'])) 
                    $_POST['in_his_kidney_disease']='';
                    
                if(!isset($_POST['in_his_epilepsy'])) 
                    $_POST['in_his_epilepsy']='';    
                 if(!isset($_POST['in_his_arthritis'])) 
                    $_POST['in_his_arthritis']='';
                if(!isset($_POST['in_his_glaucoma'])) 
                    $_POST['in_his_glaucoma']='';
                if(!isset($_POST['in_his_tuberculosis'])) 
                    $_POST['in_his_tuberculosis']='';
                if(!isset($_POST['in_his_stroke'])) 
                    $_POST['in_his_stroke']='';

                if(!isset($_POST['in_his_anaemia'])) 
                    $_POST['in_his_anaemia']='';
                    
                if(!isset($_POST['in_his_mental_illness'])) 
                    $_POST['in_his_mental_illness']='';  

                 if(!isset($_POST['in_his_asthama'])) 
                    $_POST['in_his_asthama']='';
                if(!isset($_POST['in_his_hay_fever'])) 
                    $_POST['in_his_hay_fever']='';
                if(!isset($_POST['in_his_hives'])) 
                    $_POST['in_his_hives']='';
                    
                 if(!isset($_POST['in_his_stroke'])) 
                    $_POST['in_his_stroke']='';

                if(!isset($_POST['in_ill_sca'])) 
                    $_POST['in_ill_sca']='';
                    
                if(!isset($_POST['in_ill_dip'])) 
                    $_POST['in_ill_dip']='';  

                 if(!isset($_POST['in_ill_rheu'])) 
                    $_POST['in_ill_rheu']='';
                if(!isset($_POST['in_ill_mumps'])) 
                    $_POST['in_ill_mumps']='';
                if(!isset($_POST['in_ill_measls'])) 
                    $_POST['in_ill_measls']=''; 
                if(!isset($_POST['in_ill_gmeasls'])) 
                    $_POST['in_ill_gmeasls']='';    
               
            }

                extract($_REQUEST);
               // print_r($_POST);
                //die();
                
                $userInfo=$this->userInfo();                  
                
                unset($_POST['action']);   
                unset($_POST['submit']);
                unset($_POST['nextPage']);
                unset($_POST['closeChild']);
                
                $FieldArray=$_POST;
                
                foreach($FieldArray as $key => $val){
                        if(is_array($val)){
                        	$val=urlencode(html_entity_decode(($this->jsesc($val))));
                           $FieldArray[$key]=implode(",",$val);
                        }   
                }
                
                $extraFields['intake_last_upd_datetime']=date('Y-m-d H:i:s');
                //$extraFields['intake_compl_status']='0';
                //$extraFields['intake_last_page']='1';

                $FieldArray=array_merge($FieldArray,$extraFields);
                if($FieldArray['in_dob_mon']<10)
               $bir_month = "0".$FieldArray['in_dob_mon'];
               else
               $bir_month = $FieldArray['in_dob_mon'];
               if($FieldArray['in_dob_date']<10)
               $bir_date = "0".$FieldArray['in_dob_date'];
               else 
               $bir_date = $FieldArray['in_dob_date'];
               $dob = $bir_month."/".$bir_date."/".$FieldArray['in_dob_year']; 
                 
                   /* echo '<pre>';
                    print_r($FieldArray);
                    die();
                     */           
                
                // Save the data in the patient intake table
                $this->update('patient_intake',$FieldArray,'p_user_id = '.$userInfo['user_id']);
                
                // Update the last page filled.
                $intakeview=$this->execute_query("select intake_page_filled from patient_intake where p_user_id='".$userInfo['user_id']."' ");
                $rowintake=$this->fetch_array($intakeview); 
                
                if($rowintake['intake_page_filled']<$intake_last_page){
                    
                   $this->update('patient_intake',array('intake_page_filled'=>$intake_last_page),' p_user_id = '.$userInfo['user_id']);     
                }
             //  print_r($_REQUEST);//die;
                $PatId = $this->userInfo();
                
                $pre_userInfo = $this->userInfo();
                if($pre_userInfo['address'] =="")
                $this->update('user',array('address'=>$FieldArray['in_add']),' user_id = '.$userInfo['user_id']);
                if($pre_userInfo['city'] =="")
                $this->update('user',array('city'=>$FieldArray['in_city']),' user_id = '.$userInfo['user_id']);
                if($pre_userInfo['state'] =="")
                $this->update('user',array('state'=>$FieldArray['in_prov']),' user_id = '.$userInfo['user_id']);
                if($pre_userInfo['zip'] =="")
                $this->update('user',array('zip'=>$FieldArray['in_postal']),' user_id = '.$userInfo['user_id']);
                if($pre_userInfo['phone1'] =="")
                $this->update('user',array('phone1'=>$FieldArray['in_tel']),' user_id = '.$userInfo['user_id']);
                if($pre_userInfo['gender'] =="")
                $this->update('user',array('gender'=>$FieldArray['in_gen']),' user_id = '.$userInfo['user_id']);
                if($pre_userInfo['dob'] =="")
                $this->update('user',array('dob'=>$dob),' user_id = '.$userInfo['user_id']);
                
                
                if($intake_compl_status=="1" and $_REQUEST['Button']=='Save & Submit'){
                      // Send Email
                      // Get Associated Provider
                      $selectAsoociatedProviders=$this->execute_query("select * from therapist_patient where patient_id='{$userInfo['user_id']}' AND status= '1' ");
                      
                       
                               $this->sendTxMessageByPatToPro($userInfo['user_id'],$userInfo['user_id'],$userInfo['name_first']." ".$userInfo['name_last']." has completed intake paperwork","");
                        
                     
                      
                     // echo"<pre>";
                  // print_r($PatId);
                      $ClinicId=$this->clinicInfo("clinic_id");
                      $selectAsoociatedAA=$this->execute_query("SELECT * FROM `clinic_user`,`user` WHERE  `clinic_id` = $ClinicId and clinic_user.`user_id`= user.`user_id` and user.admin_access=1 and user.therapist_access is NULL and user.status = 1");
                     if($this->num_rows($selectAsoociatedAA)>0){
                         while($resultAssociateAA=$this->fetch_array($selectAsoociatedAA))   {
                             	//print_r($resultAssociateAA);
                               $this->sendTxMessage($userInfo['user_id'],$resultAssociateAA['user_id'],$userInfo['name_first']." ".$userInfo['name_last']." has completed intake paperwork","");
                         }
                      }
                      
                      
                      
                }
             

                // Close the window based on Patient Request : closeChild
                if($closeChild=='1'){
                		//print_r($_REQUEST);
                		//die;
                        echo '<script type="text/javascript" language="javascript">parent.parent.GB_hide();</script>';
          				if($_REQUEST['Button']=='Save & Exit')
  						echo '<script type="text/javascript" language="javascript">parent.parent.window.location.href="/index.php?action=logout";</script>';
                      
                        die();
                }
                
                header("location:index.php?action=fillintakepaperwork&open_page_number=".$nextPage);                
                die();
                
                
        }
        
         /**
         * This function to save brief intake paper by Patient
         * @access public
         */     
        
        public function fillbriefintakepaperwork(){ 
           //echo "<pre>";print_r($this->userInfo());exit;
           unset($_POST['action']);
           
            if($this->userInfo('country')!=''){
		    if($this->userInfo('country')=='CAN'){
              			$canadaStatesArray=$this->config['canada_state'];
            		}else {
            		 	$canadaStatesArray=$this->config['state'];
            		 }
		    }else{
           	$canadaStatesArray=$this->config['state'];
           	}
           $canadaStates=$this->build_select_option($canadaStatesArray,$this->userInfo('state'));       
           $countryArray = $this->config['country']; 
           $country = $this->build_select_option($countryArray,$this->userInfo('country'));
            
           $monthArray=array('1'=>"Jan",'2'=>"Feb",'3'=>"Mar",'4'=>"Apr",'5'=>"May",'6'=>"Jun",'7'=>"Jul",'8'=>"Aug",'9'=>"Sep",'10'=>"Oct",'11'=>"Nov",'12'=>"Dec");
            $dateArray=array();            
            for($i=1;$i<=31;$i++){
                      $dateArray[$i]=str_pad($i,2,'0',STR_PAD_LEFT);
            }
            
            $yearArray=array();
            for($yr=1920;$yr<=date("Y");$yr++){
                $yearArray[$yr]=$yr;
            } 
            
           $dob = $this->userInfo('dob');
           $dob_split = explode( '/', $dob );
           $month = $dob_split[0];
           $date = $dob_split[1];
           $year = $dob_split[2];
           if(substr($month, 0, 0) == "0")
           $month = substr($month, 1);
           if(substr($date, 0, 0) == "0")
           $date = substr($date, 1);   
           
           $patient_id = $this->userInfo('user_id');
           $briefintakeinfo = $this->getbriefintakepaperwork($patient_id);
           
           $patientName = $this->userInfo('name_first',$patient_id).' '.$this->userInfo('name_last',$patient_id);
           $email = $this->userInfo('username',$patient_id);
           $replaceIntake['patientname']= $patientName;
           $replaceIntake['email']= $email;
           $replaceIntake['assign_datetime']= date ( 'm-d-Y', strtotime($briefintakeinfo['assign_datetime']) );
           $replaceIntake['selectMonthOption']=$this->build_select_option($monthArray,$month);
           $replaceIntake['selectDateOption']=$this->build_select_option($dateArray,$date);
           $replaceIntake['selectYearOption']=$this->build_select_option($yearArray,$year);
           $replaceIntake['stateOption']=$canadaStates;
           $replaceIntake['patient_country_options']=$country;
           $replaceIntake['in_genF']=''; 
           $replaceIntake['in_genM']='';
	    if(strtoupper(substr($this->userInfo('gender'),0,1))=='M')
            	$replaceIntake['in_genM'] = 'checked';
            if(strtoupper(substr($this->userInfo('gender'),0,1))=='F')
              	$replaceIntake['in_genF'] = 'checked';
            if($this->userInfo('address') && $this->userInfo('address2'))
           {
              $replaceIntake['address']=$this->userInfo('address')." ".$this->userInfo('address2');
           }
           else if($this->userInfo('address2'))
           {
                $replaceIntake['address']=$this->userInfo('address2');
           }
           else
           {
              $replaceIntake['address']="";  
           }
           $replaceIntake['city']=$this->userInfo('city');
           $replaceIntake['zipcode']=$this->userInfo('zip');
           $replaceIntake['tel_home']=$this->userInfo('phone1');
           $replaceIntake['tel_cell']=$this->userInfo('phone2');
           
           $data = $_POST;
           if($data['bir_month']<10)
           $bir_month = "0".$data['bir_month'];
           else
           $bir_month = $data['bir_month'];
           if($data['bir_date']<10)
           $bir_date = "0".$data['bir_date'];
           else 
           $bir_date = $data['bir_date'];
           $dob = $bir_month."/".$bir_date."/".$data['bir_year']; 
           //echo "<pre>";print_r($data);exit;echo "<pre>";print_r($this->userInfo());exit;
           if($_REQUEST['action']=='fillbriefintakepaperwork'){
              if(!$this->allEmpty($data)){
              $data['patient_name'] = $patientName;
              $data['email'] = $email;
              $data['intake_compl_status'] = 1;
              $this->update('patient_brief_intake',$data,' p_user_id = '.$patient_id);
              
              $pre_userInfo = $this->userInfo();
              if($pre_userInfo['address'] =="")
              $this->update('user',array('address'=>$data['address']),' user_id = '.$patient_id);
              if($pre_userInfo['city'] =="")
              $this->update('user',array('city'=>$data['city']),' user_id = '.$patient_id);
              if($pre_userInfo['state'] =="")
              $this->update('user',array('state'=>$data['province']),' user_id = '.$patient_id);
              if($pre_userInfo['zip'] =="")
              $this->update('user',array('zip'=>$data['zipcode']),' user_id = '.$patient_id);
              if($pre_userInfo['phone1'] =="")
              $this->update('user',array('phone1'=>$data['tel_home']),' user_id = '.$patient_id);
              if($pre_userInfo['gender'] =="")
              $this->update('user',array('gender'=>$data['gender']),' user_id = '.$patient_id);
              if($pre_userInfo['dob'] =="")
              $this->update('user',array('dob'=>$dob),' user_id = '.$patient_id);
                
                echo '<script type="text/javascript" language="javascript">parent.parent.GB_hide();</script>';
                 die();
                //echo '<script type="text/javascript" language="javascript">parent.parent.window.location.href="/index.php?action=patient";</script>';
//                header("location:index.php?action=patient");                

              }   
           }
          $this->output = $this->build_template($this->get_template("briefintakepaperwork"),$replaceIntake);     
        }

        /**
         * This function to get brief intake paper work data
         * @access public
         */     
        
        public function getbriefintakepaperwork($patient_id){
           $query =  $this->execute_query("select *  from patient_brief_intake where p_user_id  =".$patient_id);
           $numRow = $this->num_rows($query);
            if($numRow > 0) {
                   return $row = @mysql_fetch_array($query);
                    
            }  
        }
        
       /**
         * This function to view brief intake paper work data
         * @access public
         */
       public function view_brief_intake_paperwork(){
            $printform=$this->value('printform'); 
            $replaceIntake=array();
            $patient_id=$this->value('patient_id');
            //$patient_id=114006;
            $returnData=$this->getbriefintakepaperwork($patient_id);
            $name_first=$this->userInfo('name_first',$patient_id);
            $name_last=$this->userInfo('name_last',$patient_id);
            $username = $this->userInfo('username',$patient_id);
            $replaceIntake= $returnData;
            $replaceIntake['patientName']=$name_first.' '.$name_last;
            $replaceIntake['IntakeCreatedDate']=$this->formatDate($returnData['assign_datetime'],'m-d-Y');
            $replaceIntake['patientEmailAddress']=$username;
              
           
            // Populate the Dropdown values
            $canadaStatesArray=$this->config['canada_state'];
            $canadaStates=$this->build_select_option($canadaStatesArray);                        
            $monthArray=array("1" => "Jan","2" => "Feb","3" => "Mar","4" => "Apr","5" => "May","6" => "Jun","7" => "Jul","8" => "Aug","9" => "Sep","10" => "Oct","11" => "Nov","12" => "Dec");
            $dateArray=array();            
            for($i=1;$i<=31;$i++){
                      $dateArray[$i]=str_pad($i,2,'0',STR_PAD_LEFT);
            }
            
            $yearArray=array();
            for($yr=1920;$yr<=date("Y");$yr++){
                $yearArray[$yr]=$yr;
            }            
            
            if($printform == 'yes'){
            }          
            //echo"<pre>";
            //print_r($returnData);
            $replaceIntake['patient_id']=$patient_id;            
       
            $replaceIntake['selectMonthOption']=$this->build_select_option($monthArray,$returnData['bir_month']);
            $replaceIntake['selectDateOption']=$this->build_select_option($dateArray,$returnData['bir_date']);
            $replaceIntake['selectYearOption']=$this->build_select_option($yearArray,$returnData['bir_year']);  
          
	        if($returnData['gender']=='Male')
            {
            	$replaceIntake['gender']="Male";
            }
            else {
          	    $replaceIntake['gender']="Female";
            }
            if($returnData['allergies']=='yes')
            {
            	$replaceIntake['allergies']="allergies_yes";
            }
            else {
          	    $replaceIntake['allergies']="allergies_no";
            }
           if($returnData['immunizations']=='yes')
            {
            	$replaceIntake['immunizations']="immunizations_yes";
            }
            else {
          	    $replaceIntake['immunizations']="immunizations_no";
            }
                
            if($returnData['country']!=''){
                if($returnData['country']=='CAN')
                $canadaStatesArray=$this->config['canada_state'];
                else {
                	$canadaStatesArray=$this->config['state'];
                	}
                            
            }
                   
            $canadaStates=$this->build_select_option($canadaStatesArray,$returnData['province']);       
            $countryArray = $this->config['country'];
				//$replaceIntake['country']=implode("','",$countryArray); 
		      $country = $this->build_select_option($countryArray,$returnData['country']);
		      $replaceIntake['patient_country_options']=$country;
            $replaceIntake['stateOption']=$canadaStates;            
            
		                
            $this->output = $this->build_template($this->get_template("viewbriefintakepaperwork"),$replaceIntake);
         }
       
            public function print_brief_intake_paperwork(){
      
            $replaceIntake=array();
            $patient_id=$this->value('patient_id');
            //$patient_id=114006;
            $returnData=$this->getbriefintakepaperwork($patient_id);
            $name_first=$this->userInfo('name_first',$patient_id);
            $name_last=$this->userInfo('name_last',$patient_id);
            $username = $this->userInfo('username',$patient_id);
            $replaceIntake= $returnData;
            $replaceIntake['patientName']=$name_first.' '.$name_last;
            $replaceIntake['IntakeCreatedDate']=$this->formatDate($returnData['assign_datetime'],'m-d-Y');
            $replaceIntake['patientEmailAddress']=$username;
              
           
            // Populate the Dropdown values
            $canadaStatesArray=$this->config['canada_state'];
            $canadaStates=$this->build_select_option($canadaStatesArray);                        
            $monthArray=array("Jan" => "Jan","Feb" => "Feb","Mar" => "Mar","Apr" => "Apr","May" => "May","Jun" => "Jun","Jul" => "Jul","Aug" => "Aug","Sep" => "Sep","Oct" => "Oct","Nov" => "Nov","Dec" => "Dec");
            $dateArray=array();            
            for($i=1;$i<=31;$i++){
                      $dateArray[$i]=str_pad($i,2,'0',STR_PAD_LEFT);
            }
            
            $yearArray=array();
            for($yr=1920;$yr<=date("Y");$yr++){
                $yearArray[$yr]=$yr;
            }            
                       
            //echo"<pre>";
            //print_r($returnData);
            $replaceIntake['patient_id']=$patient_id;            
       
            $replaceIntake['selectMonthOption']=$this->build_select_option($monthArray,$returnData['bir_month']);
            $replaceIntake['selectDateOption']=$this->build_select_option($dateArray,$returnData['bir_date']);
            $replaceIntake['selectYearOption']=$this->build_select_option($yearArray,$returnData['bir_year']);  
          
	        if($returnData['gender']=='Male')
            {
            	$replaceIntake['gender']="Male";
            }
            else {
          	    $replaceIntake['gender']="Female";
            }
            if($returnData['allergies']=='yes')
            {
            	$replaceIntake['allergies']="allergies_yes";
            }
            else {
          	    $replaceIntake['allergies']="allergies_no";
            }
           if($returnData['immunizations']=='yes')
            {
            	$replaceIntake['immunizations']="immunizations_yes";
            }
            else {
          	    $replaceIntake['immunizations']="immunizations_no";
            }
                
            if($returnData['country']!=''){
                if($returnData['country']=='CAN')
                $canadaStatesArray=$this->config['canada_state'];
                else {
                	$canadaStatesArray=$this->config['state'];
                	}
                            
            }
                   
            $canadaStates=$this->build_select_option($canadaStatesArray,$returnData['province']);       
            $countryArray = $this->config['country'];
				//$replaceIntake['country']=implode("','",$countryArray); 
		      $country = $this->build_select_option($countryArray,$returnData['country']);
		      $replaceIntake['patient_country_options']=$country;
            $replaceIntake['stateOption']=$canadaStates;            
            
		                
            $this->output = $this->build_template($this->get_template("viewbriefintakepaperwork"),$replaceIntake);
         }
       
        
        /**
	 * This function sends TX message from patient to provider.
	 * @param Integer $sender_id
	 * @param Integer $rec_id
	 * @param String  $sub
	 * @param String  $content
	 * @return void
	 * @access public
	 */
	
	public function sendTxMessageByPatToPro($sender_id, $rec_id, $sub, $content) {
		$data = array ('sender_id' => $sender_id,'patient_id' => $rec_id, 'subject' => $this->encrypt_data ( $sub ), 'content' => $this->encrypt_data ( $content ), 'parent_id' => '0', 'sent_date' => date ( 'Y-m-d H:i:s', time () ), 'recent_date' => date ( 'Y-m-d H:i:s', time () ), 'replies' => '0','sent_visible' =>'0' );
		
		if ($this->insert ( "message", $data )) {
			$message_id = $this->insert_id ();
			// Entry for displaying unread messages count in pat section
			$data = array ('message_id' => $message_id, 'user_id' => $sender_id, 'unread_message' => '2' );
			$this->insert ( "message_user", $data );
			
			
			 $thid = "select * from therapist_patient where patient_id = {$sender_id}";
			$queryTh = $this->execute_query ( $thid);	
			
					while($rowTh = $this->fetch_array ( $queryTh))
					{	
			
			// Entry for therapist
			

			$data = array ('message_id' => $message_id, 'user_id' => $rowTh['therapist_id'], 'unread_message' => '1' );
			$this->insert ( "message_user", $data );
					}
		}
	
	}
  	 /**
	 * This function sends TX message:from patient.
	 * @param Integer $sender_id
	 * @param Integer $rec_id
	 * @param String  $sub
	 * @param String  $content
	 * @return void
	 * @access public
	 */
	
	public function sendTxMessageByPat($sender_id, $rec_id, $sub, $content) {
		$data = array ('sender_id' => $sender_id,'patient_id' => $rec_id, 'subject' => $this->encrypt_data ( $sub ), 'content' => $this->encrypt_data ( $content ), 'parent_id' => '0', 'sent_date' => date ( 'Y-m-d H:i:s', time () ), 'recent_date' => date ( 'Y-m-d H:i:s', time () ), 'replies' => '0','sent_visible' =>'0' );
		
		if ($this->insert ( "message", $data )) {
			$message_id = $this->insert_id ();
			// Entry for Account Admin
			$data = array ('message_id' => $message_id, 'user_id' => $this->userInfo ( 'user_id' ), 'unread_message' => '2' );
			$this->insert ( "message_user", $data );
			
			
			 $thid = "select * from therapist_patient where patient_id = {$this->userInfo ( 'user_id' )}";
			$queryTh = $this->execute_query ( $thid);	
			
					while($rowTh = $this->fetch_array ( $queryTh))
					{	
			
			// Entry for therapist
			

			$data = array ('message_id' => $message_id, 'user_id' => $rowTh['therapist_id'], 'unread_message' => '1' );
			$this->insert ( "message_user", $data );
					}
		}
	
	}

	 /**
	 * This function sends TX message:from patient.
	 * @param Integer $sender_id
	 * @param Integer $rec_id
	 * @param String  $sub
	 * @param String  $content
	 * @return void
	 * @access public
	 */
	
	
  	public function sendTxMessage($sender_id, $rec_id, $sub, $content) {
		$data = array ('sender_id' => $sender_id,'patient_id' => $rec_id, 'subject' => $this->encrypt_data ( $sub ), 'content' => $this->encrypt_data ( $content ), 'parent_id' => '0', 'sent_date' => date ( 'Y-m-d H:i:s', time () ), 'recent_date' => date ( 'Y-m-d H:i:s', time () ), 'replies' => '0' );
		
		if ($this->insert ( "message", $data )) {
			$message_id = $this->insert_id ();
			// Entry for Account Admin
			$data = array ('message_id' => $message_id, 'user_id' => $sender_id, 'unread_message' => '2' );
			$this->insert ( "message_user", $data );
			
			// Entry for Patient
			

			$data = array ('message_id' => $message_id, 'user_id' => $rec_id, 'unread_message' => '1' );
			$this->insert ( "message_user", $data );
		
		}
	
	}
	
	
	
	
         /**
         * This function to view intake paper by Patient
         * @access public
         */
         
         public function view_intake_paperwork(){
            $printform=$this->value('printform'); 
            $replaceIntake=array();
            $patient_id=$this->value('patient_id');

            $returnData=$this->get_intake_paperwork_data($patient_id);
            $name_first=$this->userInfo('name_first',$patient_id);
            $name_last=$this->userInfo('name_last',$patient_id);
            $replaceIntake['patientName']=$name_first.' '.$name_last;
            $replaceIntake['IntakeCreatedDate']=$this->formatDate($returnData['assign_datetime'],'m-d-Y');
            $replaceIntake['patientEmailAddress']=$returnData['username'];

            // Populate the Dropdown values
            
            for($hI=140; $hI<200; $hI++)
            {
                $heightInches[]=$hI;    
            }
            for($hC=355; $hC<500; $hC++)
            {
                $heightCms[]=$hC;               
            }
            for($wL=66; $wL<330; $wL++)
            {
                $weightLbs[]=$wL;    
            }
            for($wK=30; $wK<150; $wK++)
            {
                $weightKg[]=$wK;               
            }
            
            $replaceIntake['heightInch']=$this->build_select_option($heightInches);
            $replaceIntake['heightCms']=$this->build_select_option($heightCms);
            
            $replaceIntake['weightLbs']=$this->build_select_option($weightLbs);
            $replaceIntake['weightKg']=$this->build_select_option($weightKg);            

            
            
            //echo '<pre>';
            //print_r($returnData);
            //echo '</pre>';         
                                                             
            foreach($returnData as $IntakeKey=>$IntakeValue){
            	$IntakeValue=urlencode(html_entity_decode(($this->jsesc($IntakeValue))));
                    $intakeJavascriptString.="\"{$IntakeKey}\":\"{$IntakeValue}\",";                    
            } 
            
            $dataGraph=array(       "Physical Environment"                  => array("Physical Environment"=>$returnData['in_phy_env']) ,
                                    "Career"                                => array("Career"=>$returnData['in_career']) ,
                                    "Money"                                 => array("Money"=>$returnData['in_money']) ,
                                    "Health"                                => array("Health"=>$returnData['in_health']) ,
                                    "Significant Other/Romance"             => array("Significant Other/Romance"=>$returnData['in_romance']) ,
                                    "Fun & Recreation"                      => array("Fun & Recreation"=>$returnData['in_fun']) ,
                                    "Personal Growth"                       => array("Personal Growth"=>$returnData['in_growth']) ,
                                    "Family & Friends"                      => array("Family & Friends"=>$returnData['in_fam']) 
                                    );
            
            $graphData=$this->getdrawIntakeGraph($dataGraph);
            $replaceIntake['graphData']=$graphData;     
            
              
            $intakeJavascriptString=trim($intakeJavascriptString,",");
           
            $pageOpen=$this->value('nextPage');
            if($printform == 'yes'){
            }elseif($pageOpen==''){
                    $pageOpen=1;
                
            }
            $templateOpen="view_intakepaperwork".$pageOpen;

            // Populate the Dropdown values
            $canadaStatesArray=$this->config['canada_state'];
            $canadaStates=$this->build_select_option($canadaStatesArray);                        
            $monthArray=array("1" => "Jan","2" => "Feb","3" => "Mar","4" => "Apr","5" => "May","6" => "Jun","7" => "Jul","8" => "Aug","9" => "Sep","10" => "Oct","11" => "Nov","12" => "Dec");
            $dateArray=array();            
            for($i=1;$i<=31;$i++){
                      $dateArray[$i]=str_pad($i,2,'0',STR_PAD_LEFT);
            }
            
            $yearArray=array();
            for($yr=1920;$yr<=date("Y");$yr++){
                $yearArray[$yr]=$yr;
            }            
            
            $yearReportArray=array();
            for($yr=1940;$yr<=date("Y");$yr++){
                $yearReportArray[]=$yr;
            }            
            //echo"<pre>";
            //print_r($returnData);
            $replaceIntake['patient_id']=$patient_id;            
            $replaceIntake['IntakeArrayData']=$intakeJavascriptString;            
            $replaceIntake['navigation_header']=$this->navigation_header($pageOpen,10,"navigation_header_view");
            
            $replaceIntake['selectMonthOption']=$this->build_select_option($monthArray);
            $replaceIntake['selectDateOption']=$this->build_select_option($dateArray);
            $replaceIntake['selectYearOption']=$this->build_select_option($yearArray);  
            $replaceIntake['selectReportYearOption']=$this->build_select_option($yearReportArray);  
	        if($returnData['in_gen']=='M')
	            {
	            	$replaceIntake['genderBaseSection']=$this->build_template($this->get_template("maleSection"),$replace);
	            }
	            else {
	          	    $replaceIntake['genderBaseSection']=$this->build_template($this->get_template("femaleSection"),$replace);
	           }
           
                $intakeviews=$this->execute_query("select in_country,in_prov from patient_intake where p_user_id='".$patient_id."' ");
                $rowintakes=$this->fetch_array($intakeviews); 
              
           
                if($rowintakes['in_country']!=''){
                    if($rowintakes['in_country']=='CAN')
            $canadaStatesArray=$this->config['canada_state'];
            else {
            	$canadaStatesArray=$this->config['state'];
            	}
                                
                    }
                   
            $canadaStates=$this->build_select_option($canadaStatesArray,$rowintakes['in_prov']);       
				$countryArray = $this->config['country'];
				//$replaceIntake['country']=implode("','",$countryArray); 
		      $country = $this->build_select_option($countryArray,$rowintakes['in_country']);
		      $replaceIntake['patient_country_options']=$country;
            $replaceIntake['stateOption']=$canadaStates;            
            
		                
            $this->output = $this->build_template($this->get_template($templateOpen),$replaceIntake);
         }
  	function jsesc($escString){
            $find = array( "'", '’', "\n", "\r", chr(226).chr(128).chr(168), 
            chr(226).chr(128).chr(169), chr(194).chr(133));
            $replace = array( "\\'", "\\'", "\\n ", "", "\\n", "\\n", "\\n");
            return str_replace($find, $replace, stripslashes($escString));
    }
         
         public function print_intake_paperwork(){
            $replaceIntake=array();
            $patient_id=$this->value('patient_id');

            $returnData=$this->get_intake_paperwork_data($patient_id);
            $name_first=$this->userInfo('name_first',$patient_id);
            $name_last=$this->userInfo('name_last',$patient_id);
            $replaceIntake['patientName']=$name_first.' '.$name_last;
            $replaceIntake['IntakeCreatedDate']=$this->formatDate($returnData['assign_datetime'],'m-d-Y');
            $replaceIntake['patientEmailAddress']=$returnData['username'];
            

            
            
            //echo '<pre>';
            //print_r($returnData);
            //echo '</pre>';         
                                                             
            foreach($returnData as $IntakeKey=>$IntakeValue){
                    $intakeJavascriptString.="\"{$IntakeKey}\":\"{$IntakeValue}\",";                    
            }   
            $intakeJavascriptString=trim($intakeJavascriptString,",");
           
            $pageOpen=$this->value('nextPage');
            $templateOpen="view_intakepaperwork";

            // Populate the Dropdown values
            $canadaStatesArray=$this->config['canada_state'];
            $canadaStates=$this->build_select_option($canadaStatesArray);                        
            $monthArray=array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
            $dateArray=array();            
            for($i=1;$i<=31;$i++){
                      $dateArray[]=str_pad($i,2,'0',STR_PAD_LEFT);
            }
            
            $yearArray=array();
            for($yr=1920;$yr<=date("Y");$yr++){
                $yearArray[]=$yr;
            }            
            
            $yearReportArray=array();
            for($yr=1940;$yr<=date("Y");$yr++){
                $yearReportArray[]=$yr;
            }            
            
            
            $replaceIntake['patient_id']=$patient_id;            
            $replaceIntake['IntakeArrayData']=$intakeJavascriptString;            
            $replaceIntake['navigation_header']=$this->navigation_header($pageOpen,10,"navigation_header_view");
            
            $replaceIntake['selectMonthOption']=$this->build_select_option($monthArray);
            $replaceIntake['selectDateOption']=$this->build_select_option($dateArray);
            $replaceIntake['selectYearOption']=$this->build_select_option($yearArray);  
            $replaceIntake['selectReportYearOption']=$this->build_select_option($yearReportArray);  
             if($returnData['in_gen']=='M')
	            {
	            	$replaceIntake['genderBaseSection']=$this->build_template($this->get_template("maleSection"),$replace);
	            }
	            else {
	          	    $replaceIntake['genderBaseSection']=$this->build_template($this->get_template("femaleSection"),$replace);
	           }
            
            
            
            
            $replaceIntake['canadaStates']=$canadaStates;            
            $this->output = $this->build_template($this->get_template($templateOpen),$replaceIntake);
         } 
         
         /**
         * Retrieve the intake paper work details based on patient id.
         * @access public 
         * @param Integer $patient_id
         * @return Array $returnArray
         */
         
         public function get_intake_paperwork_data($patient_id){
             
             $queryIntake="select patient_intake.*,user.name_first,name_last,username from patient_intake,user where patient_intake.p_user_id=user.user_id AND p_user_id = '{$patient_id}' ";
             $resultQuery=$this->execute_query($queryIntake);
                $this->execute_query();
                if($this->num_rows($resultQuery)) {
                    
                    $returnTempArray=$this->fetch_object($resultQuery);
                    foreach($returnTempArray as $keyQ=>$valueQ) {
                            $returnArray[$keyQ]=$valueQ;
                        
                    }
                    
                }
             return $returnArray;
         }
         
         
         /**
         * Send reminder email to patient after 3,5 days of creation.
         * @access public 
         * @return void
         */
         
         public function sentIntakeReminderEmail(){
                         
             
                         // Send 3rd Day reminder
                         $querySelectThree=$this->execute_query("select DATEDIFF(now(),assign_datetime),patient_intake.* from patient_intake where intake_sent_email = '0'  AND DATEDIFF(now(),assign_datetime)>=3 ");
                         if($this->num_rows($querySelectThree)>0){
                            while($resultSelectThree=$this->fetch_array($querySelectThree))   {
                                // Send Email to Patient
                                    $replacePatient=array();
                                    $clinicName=html_entity_decode($this->get_clinic_info($resultSelectThree['p_user_id'],"clinic_name"), ENT_QUOTES, "UTF-8");
                                    $replacePatient['urlLogin']=$this->config['images_url'];
                                    $Patientsubject = "Reminder ".html_entity_decode("-", ENT_QUOTES, "UTF-8")." Your ".$clinicName." New Patient Forms";
                                    $replacePatient['ClinicName']=$clinicName;

                                // Content Of the email send to  patient
                                    $Patientmessage = $this->build_template($this->get_template("threedayreminderIntakeEmail"),$replacePatient);
                                    $Patientto = $this->userInfo("username",$resultSelectThree['p_user_id']);
                                    
                                    $this->send_external_email_intake($Patientto,$Patientsubject,$Patientmessage,$clinicName);              
                                
                                //Update Database table that 3rd day reminder is send
                                    $updQueryThree="update patient_intake set intake_sent_email = '3' where intake_id = '{$resultSelectThree['intake_id']}' ";
                                    $this->execute_query($updQueryThree);
                            }
                         }
                         
                         // Send 5th Day reminder
                         $querySelectFive=$this->execute_query("select DATEDIFF(now(),assign_datetime),patient_intake.* from patient_intake where intake_sent_email = '3'  AND DATEDIFF(now(),assign_datetime)>=5 ");
                         if($this->num_rows($querySelectFive)>0){
                            while($resultSelectFive=$this->fetch_array($querySelectFive))   {
                                // Send Email to Patient
                                    $replacePatient=array();
                                    $clinicName=html_entity_decode($this->get_clinic_info($resultSelectFive['p_user_id'],"clinic_name"), ENT_QUOTES, "UTF-8");
                                    $replacePatient['urlLogin']=$this->config['images_url'];
                                    $Patientsubject = "Reminder ".html_entity_decode("-", ENT_QUOTES, "UTF-8")." Your ".$clinicName." New Patient Forms";
                                    $replacePatient['ClinicName']=$clinicName;

                                // Content Of the email send to  patient
                                    $Patientmessage = $this->build_template($this->get_template("fivedayreminderIntakeEmail"),$replacePatient);
                                    $Patientto = $this->userInfo("username",$resultSelectFive['p_user_id']);
                                    
                                    $this->send_external_email_intake($Patientto,$Patientsubject,$Patientmessage,$clinicName);                                
                                //Update Database table that 3rd day reminder is send
                                    $updQueryFive="update patient_intake set intake_sent_email = '5' where intake_id = '{$resultSelectFive['intake_id']}' ";
                                    $this->execute_query($updQueryFive);
                            }
                         }                         
         }
         
		/**
		 * populate side panel in page.
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
        
        
        public function drawIntakeGraph(){
               $this->getdrawIntakeGraph();
        }
         /**
         * This function returns the graph on the basis of input.
         * @param array $data
         * @access public
         */        
        
        public function  getdrawIntakeGraph($data){

                $grafic = new bar_chart();

                $bars    = array(   "Physical Environment"            => "#298901" ,
                                    "Career"                        => "#298901" ,
                                    "Money"                            => "#298901",
                                    "Health"                        => "#298901",
                                    "Significant Other/Romance"        => "#298901",
                                    "Fun & Recreation"                => "#298901",
                                    "Personal Growth"                => "#298901",
                                    "Family & Friends"                => "#298901"
                                    );
                $height            = "10px";     //the height of the bars. must include "px"
                $units            = "";    //can be: kg, miles, km, etc. in this example this is the code for %  
                $space_b_bars     = "20px";
                                    
                $grafic -> set_bar($bars, $space_b_bars, $height, $units);


                $title             = " "; //the title of the chart. will be displayed at the top
                $title_style    = array("style" => "padding: 5px; font-family : Geneva,Verdana,san-serif; font-size : 28px; font-weight : bold; color : #dd1212;    text-align : center; text-transform: uppercase; margin-bottom: 10px;");
                $info             = "Level Of Satisfaction";
                $info_style        = "style = \"margin: 15px 0px; font-style: italic; padding: 5px; font-family : Geneva,Verdana,san-serif; font-size : 14px; color : #298901;\"";
                $number_of_gridlines = 10;


                $grafic -> chart_settings($title, $title_style, $info, $info_style, $number_of_gridlines);

                /*
                $data = array(      "Physical Environment"                  => array("Physical Environment"=>5) ,
                                    "Career"                                => array("Career"=>6) ,
                                    "Money"                                 => array("Money"=>7) ,
                                    "Health"                                => array("Health"=>9) ,
                                    "Significant Other/Romance"             => array("Significant Other/Romance"=>4) ,
                                    "Fun & Recreation"                      => array("Fun & Recreation"=>3) ,
                                    "Personal Growth"                       => array("Personal Growth"=>10) ,
                                    "Family & Friends"                      => array("Family & Friends"=>8) 
                                    );
                */
                $reverse        = FALSE;    //show the data in reverse order
                $show_legend    = FALSE;    //show the legend for this chart
                $show_percentage= FALSE;        //show percentage for every item
                $show_h_grid    = FALSE;    //show horizontal grid 
                $show_scale        = TRUE;        //show scale at the bottom of the chart
                $max_width        = 460;         //the width in pixels of the right side of the chart for the value of $scale 
                $scale            = 10;        //maximum value on the scale


                $grafic -> graph($data, $reverse, $show_legend, $show_percentage, $show_h_grid, $show_scale, $max_width, $scale);
                $style = array("style" => "width: 680px;");
                $example_4 = $grafic -> output($style);
                return $example_4;            
            
        }
        
      
	}
	$obj = new intakePaper();
?>
