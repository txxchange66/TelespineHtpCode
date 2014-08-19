<?php
    /**
     *
     * Copyright (c) 2008 Tx Xchange
     *
     * Class includes the functionality to view, edit and list patients.
     * 
     * 
     */ 
        
    
    // including files
    require_once("include/paging/my_pagina_class.php");
    require_once("module/application.class.php");

    
    // class declaration
      class patientManager extends application{
          
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

            $str = $str."()";

            eval("\$this->$str;"); 

            $this->display();

        }
        /**
         * Display patient details
         *
         * @access public
         */
        function viewPatient(){
            $replace = array();
            include_once("template/patientManager/patientArray.php");
            
            if($this->value('id') != "" && $this->value('plan_id') != "" ){
                if($this->value('act') == 'current' ){
                    $query = "update plan set status = 1 where plan_id = '{$this->value('plan_id')}' and status != 3 ";
                }
                if($this->value('act') == 'deletePlan' ){
                    $query = "update plan set status = 3 where plan_id = '{$this->value('plan_id')}'  ";
                }
                if($this->value('act') == 'archive' ){
                    $query = "update plan set status = 2 where plan_id = '{$this->value('plan_id')}' and status != 3 ";
                }
                if($this->value('act') == 'editPlan' ){
                    header("location:index.php?action=createNewPlan&act=plan_edit&type=finish&plan_id={$this->value('plan_id')}&patient_id={$this->value('id')}");
                    exit(0);
                }
                            
                if(isset($query) && $query != ""){
                    $result = $this->execute_query($query);
                }
            }
            $replace = array();

            include_once("template/therapist/therapistArray.php");

            if($this->value('id')){

                $where = " user_id = ".$this->value('id');

                $row = $this->table_record($this->config['table']['user'],$where);
                // Decrypt data
                $encrypt_field = array('name_title','name_first','name_last','password','address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
                $row = $this->decrypt_field($row, $encrypt_field);
                // End Decrypt data
                        
                $this->formArray = $formArray;

                if(is_array($row)){
if($row['country']=='CAN')
$cntry='Canada';
if($row['country']=='US')
$cntry='United States';	
                    $replace = $this->fillForm($this->formArray,$row);
                    $replace['user_id'] = $this->value('id');
                    $replace['status'] = $this->config['patientStatus'][$row['status']];

                    $replace['full_name'] = $row['name_title']. "&nbsp;" . strtoupper($row['name_first'])."&nbsp;&nbsp;".strtoupper($row['name_last']);
                    $replace['patient_name'] = $row['name_first']." ".$row['name_last'];
                    $replace['state'] = $this->config['state'][$replace['state']];
                    
                    $replace['patient_address'] = trim($row['name_title']) != ""?$row['name_title']:"";
                    $replace['patient_address'] .= trim($row['name_first']) != ""?"&nbsp;".ucwords($row['name_first']):"";
                    $replace['patient_address'] .= trim($row['name_last']) != ""?"&nbsp;".ucwords($row['name_last']):"";
                    $replace['patient_address'] .= "<br>";
                    $replace['patient_address'] .= trim($row['address']) != ""?$row['address']."<br>":"";
                    $replace['patient_address'] .= trim($row['address2']) != ""?$row['address2']."<br>":"";
                    $replace['patient_address'] .= trim($row['city']) != ""?$row['city']."&nbsp;":"";
                    $replace['patient_address'] .= trim($row['state']) != ""?$row['state']."&nbsp;":"";
                    $replace['patient_address'] .= trim($row['zip']) != ""?$row['zip']."<br>":"";
                    $replace['patient_address'] .= $cntry."<br>";
                    $replace['patient_address'] .= trim($row['phone1']) != ""?"Phone 1:&nbsp;".$row['phone1']."<br>":"";
                    $replace['patient_address'] .= trim($row['phone2']) != ""?"Phone 2:&nbsp;".$row['phone2']."<br>":"";
                    

                }    

            }
            
            /* Patient Id added by AJ */
                
                    $replace['patientId'] = $this->value('id');
                    $replace['id'] = $this->value('id');
            /* End */
            
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['footer'] = $this->build_template($this->get_template("footer"));
            $replace['sidebar'] = $this->sidebar();
            
            if($this->value('id') != "" && is_numeric($this->value('id'))){
                $query = " select * from plan where patient_id = '{$this->value('id')}' and status !=3 ";
                $result = $this->execute_query($query);
                if( $this->num_rows($result) > 0 ){
                    while($row = $this->fetch_array($result)){
                        $data = array(
                            'plan_id' => $row['plan_id'],
                            'plan_name' => $row['plan_name'],
                            'status' => $row['status'] == '1'?'Current':($row['status'] == '2'?'Archived':'Deleted Plan'),
                            'actionOption' => $row['status'] == '1'?$patientStatusCurrent:$patientStatusArchive
                            );
                        $data['actionOption'] = $this->build_select_option($data['actionOption']);
                        $replace['viewPatientPlanRecord'] .= $this->build_template($this->get_template("viewPatientPlanRecord"),$data);
                    }
                }
            }
            $replace['viewPatientPlanRecord'] .= '</table><br/>';            
            //echo "aaaaa".$replace['viewPatientPlanRecord']; exit;
            // End of plans list.

            // Start e-maintenence patient
            if( $this->value('id') != "" ){
                $user_id = $this->value('id');
                $p_type = $program['cash-based-program'];
                $sql = "select * from program_user where u_id = '{$user_id}' and p_type = '{$p_type}' ";
                $result = @mysql_query($sql);
                if( @mysql_num_rows($result) > 0 ){
                    $replace['program']  = "e-Rehab Program";    
                }
                else{
                    $replace['program']  = "No current programs";
                }
            }
            // End e-maintenence
            
            // patient reminder List.
            $query = "select * from patient_reminder where patient_id = '{$this->value('id')}' and status = 1 ";
            $result = $this->execute_query($query);
            if($this->num_rows($result) > 0 ){
                $cnt = 1;
                while($row = $this->fetch_array($result)){
                    $row['cnt'] = $cnt++;
                    $replace['patient_reminder'] .= $this->build_template($this->get_template("patient_reminder"),$row);
                }
            }
            // End of patient reminder List.
            
            $replace['creation_date'] = $this->formatDate($this->get_field($this->value('id'),"user","creation_date"));
            $replace['patient_name_id'] = $this->value('id');
            $replace['clinic_id'] = $this->value('clinic_id');
            $replace['body'] = $this->build_template($this->get_template("viewPatient"),$replace);
            $replace['browser_title'] = "Tx Xchange: View Patient";
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
        /**
        *  This function sends mail of login detail to patient.
        */
        function system_mail_login_detail_patient(){
            if(is_numeric($this->value('patient_id'))){
                $query = "select user_id,username,password from user where usertype_id = 1 and user_id = '{$this->value('patient_id')}' and (status = 1 or status = 2 or status=3 )";
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
	                
                    if( $clinic_channel == 1){
	                	$message = $this->build_template($this->get_template("resend_login_detail_plpto"),$data);
	                }
	                else{
	                	$message = $this->build_template($this->get_template("resend_login_detail_wx"),$data);	
	                }                    
                    
                    
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
        /**
         * display patient list of the particluar therapist.
         *
         * @access public
         */
        function patientList(){
            $this->set_session_page();
            $replace = array();
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['footer'] = $this->build_template($this->get_template("footer"));
            $replace['sidebar'] =$this->sidebar();    

            include_once("template/patientManager/patientArray.php");
            $this->formArray = $patientListAction;
            
            if($this->value('sort') != ""){
                $privateKey = $this->config['private_key'];
				if($this->value('sort')=="u.username" || $this->value('sort')=="u.status" || $this->value('sort')=="u.last_login" || $this->value('sort')=="clinic_name")
					$encryptFieldFlag=$this->value('sort');
				else
					$encryptFieldFlag="cast(AES_DECRYPT(UNHEX({$this->value('sort')}),'{$privateKey}')  as CHAR)";
				
                if($this->value('order') == 'desc' ){
                    
					$orderby = " order by ".$encryptFieldFlag." desc ";
                }
                else{
                    $orderby = " order by ".$encryptFieldFlag;
                }
            }
            else{
                    $privateKey = $this->config['private_key'];
                    $orderby = " order by cast(AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}')  as CHAR) ";
            }
            
            $where = " u.usertype_id = 1 ";
            if($this->value('search') != ""){
                $replace['search'] = $this->value('search');
                $privateKey = $this->config['private_key'];
                $query = "select * , 
                          CAST(AES_DECRYPT(UNHEX(u.name_title),'{$privateKey}') as CHAR)as name_title, 
                          CAST(AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as CHAR) as name_first,
                          CAST(AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as CHAR)as name_last,
                          (select CONCAT_WS('|',c.clinic_name,c.clinic_id) from clinic_user cu  
                          inner join clinic c on c.clinic_id = cu.clinic_id 
                          where cu.user_id = u.user_id
                          ) as clinic_name,
                          (select p.p_name from program_user pu  
                          inner join program p on p.p_id = pu.p_type 
                          where pu.u_id = u.user_id
                          ) as program
                          from user u where 
                          (
                          CAST(AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as CHAR) like '%{$this->value('search')}%' 
                          or 
                          CAST(AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as CHAR) like '%{$this->value('search')}%' 
                          or 
                          u.username like '%{$this->value('search')}%'
                          ) 
                          and {$where} {$orderby} ";
                        $sqlcount= "select count(1) as count from user u where 
                          (
                          CAST(AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as CHAR) like '%{$this->value('search')}%' 
                          or 
                          CAST(AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as CHAR) like '%{$this->value('search')}%' 
                          or 
                          u.username like '%{$this->value('search')}%'
                          ) 
                          and {$where} {$orderby} ";
                          
                          
            }
            else{
                $privateKey = $this->config['private_key'];
               $query = "select *,
                          AES_DECRYPT(UNHEX(u.name_title),'{$privateKey}') as name_title, 
                          CAST(AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as CHAR)as name_first,
                          CAST(AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}')as CHAR) as name_last,   
                          (select CONCAT_WS('|',c.clinic_name,c.clinic_id) from clinic_user cu  
                          inner join clinic c on c.clinic_id = cu.clinic_id 
                          where cu.user_id = u.user_id
                          ) as clinic_name,
                          (select p.p_name from program_user pu  
                          inner join program p on p.p_id = pu.p_type 
                          where pu.u_id = u.user_id
                          ) as program
                          from user u where {$where} {$orderby} ";
                          
                $sqlcount= "select count(1) as count from user u where {$where} {$orderby} ";          
						  
            }
            
            $link = $this->pagination($rows = 0,$query,$this->value('action'),$this->value('search'),'','','15',$sqlcount);                
            $replace['link'] = $link['nav'];
            $result = $link['result'];                                      

            if(is_resource($result)){
                if( !(@mysql_num_rows($result) > 0) ){
                    $row['message'] = "Record Not Found";
                    $replace['patientListRec'] = $this->build_template($this->get_template("recordNotFound"),$row);
                }
                while($row = $this->fetch_array($result)){
                    $row['style'] = ($c++%2)?"line1":"line2";
                    $row['status'] = $this->config['patientStatus'][$row['status']];
                    $row['last_login'] = $this->formatDate($row['last_login'])!=""?$this->formatDate($row['last_login']):"Never";
                    $clinic_arr = explode("|",$row['clinic_name']);
                    $row['clinic_name'] = $clinic_arr[0]==""?"&nbsp;":$clinic_arr[0];
                    $row['clinic_id'] = $clinic_arr[1];
                    $row['program'] = $row['program']==""?"&nbsp;":$row['program'];
                    $row['actionOption'] = $this->build_select_option($this->formArray);
                    $replace['patientListRec'] .= $this->build_template($this->get_template("patientListRec"),$row);
                }
                
                $replace['patientListHeading'] = $this->build_template($this->get_template("patientListHeading"),$this->table_heading($patientHeading,"name_first"));
                $replace['browser_title'] = "Tx Xchange: Patient Manager";
                $replace['body'] = $this->build_template($this->get_template("patientList"),$replace);
                $this->output = $this->build_template($this->get_template("main"),$replace);
            }
            else{
                echo "Invalid resource";
            }
        }
        function redirectPatientList(){
            $url = $this->redirectUrl("patientList");
            header("location:{$url}");
            exit();
        }
        /**
        * This function shows page for setting time interval for non-compliant Patients.
        */
        function system_notifications(){
            // Process after submitting form.
            if($this->value('action_submit') == 'submit' ){
                $content_arr = array(
                    'patient' => $this->value('inter_patient'),
                    'therapist' => $this->value('inter_therapist')
                );
                $where = " id = 1 " ;
                $this->update('interval_noncompliant_patient',$content_arr,$where);
                $error[] = "Notification Interval saved successfully.";
                $replace['error'] = $this->show_error($error,'green');
            }
			if($this->value('action_submit') == 'message_submit' ){
	            $error1 = array();
				if( $this->value('user_type_id') == "" ){
					$error1[] = "Please select user group";
				}
				if( $this->value('message') == "" ){
					$error1[] = "Please enter message";
				}
				if( is_array($error1) && sizeof($error1) == 0 )
				{
				 	$data = array('message' => $this->encrypt_data($this->value('message')));
				 	$this->insert("message_notifications",$data);
				 	$message_id = $this->insert_id();
					//creating array of user ids for selected user type
					if($this->value('user_type_id')=="1")
					$user_type_str="usertype_id = 1";
					else if($this->value('user_type_id')=="2")
					$user_type_str="usertype_id = 2";
					else if($this->value('user_type_id')=="1,2")
					$user_type_str="usertype_id IN('1','2')";
					$userIds=array();
					$query = "select user_id from user where ".$user_type_str." and (status = 1 or status = 2)";
					$result = @mysql_query($query);
					while($row = @mysql_fetch_object($result))
					{$userIds[]=$row->user_id;
					}
					//echo "<pre />";print_r($userIds);
					foreach($userIds as $user_id)
					{$data = array('message_id' => $message_id,'user_id' => $user_id);
				 	$this->insert("message_notifications_users",$data);
					}
					$error1[] = "Notification sent successfully.";
					$replace['error1'] = $this->show_error($error1,'green');
				}
				else
				{$replace['error1'] = $this->show_error($error1);
				}
                
                
            }

            $query = " select patient,therapist from interval_noncompliant_patient ";
            $result = @mysql_query($query);
            if( is_resource($result) && @mysql_num_rows($result) > 0 ){
               $row = @mysql_fetch_array($result); 
               $replace['inter_patient'] = $row['patient'];
               $replace['inter_therapist'] = $row['therapist']; 
            }
            
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar();
            $replace['body'] = $this->build_template($this->get_template("notification_reminder"),$replace);
            $replace['browser_title'] = "Tx Xchange: Notification and Reminders";
            $this->output = $this->build_template($this->get_template("main"),$replace);

			

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
                'name_last' =>  $this->userInfo('name_last'),
                'sysadmin_link' => $this->sysadmin_link()
            );

            return $this->build_template($this->get_template("sidebar"),$data);

        }
        /**
         * function gets the template path from xml file.
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
    }

    $obj = new patientManager();

?>

