<?php
	/**
	 * 
	 * Copyright (c) 2008 Tx Xchange.
	 * 
	 * Class for Therapist messaging.
	 *  
	 * Neccessary class for pagination.
	 * require_once("include/paging/my_pagina_class.php");
	 * 
	 * Neccessary class for getting access of application specific methods.
	 * require_once("module/application.class.php");
	 */
	require_once("include/paging/my_pagina_class.php");
	require_once("module/application.class.php");
  	class messageTherapist extends application{
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
		 * 	  If it is not holding any value, assign default value in it.
		 * 2) Check user is logged in or not.
		 * 3) Check the logged in user, have privilege or not to access this class.
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
				$str = "therapistPlan"; //default if no action is specified
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
		 * This method is used to post any new message to patient.
		 * 
		 * @param none
		 * @return none
		 * @access public
		 */
		function compose_message(){
		    $replace['patient_name'] = $this->value('patient_name');
			$patient_name_id = $this->value('patient_name_id');
            $replace['patient_name_id'] = $patient_name_id;
			$replace['subject'] = "";
			$replace['content'] = "";
            
			$replace['get_satisfaction'] = $this->get_satisfaction();
            
			$clinicId = $this->clinicInfo('clinic_id');
            
			$from_record = $this->value('from_record');
            
			if( $this->value('action_submit') == "submit" ){
				
				$error = array();
            	if( $this->value('patient_name_id') == "" ){
					$error[] = "Please select Patient name in to field";
				}
				if( $this->value('subject') == "" ){
					$error[] = "Please enter subject";
				}
				if( $this->value('content') == "" ){
					$error[] = "Please enter message";
				}
				if( is_array($error) && sizeof($error) == 0 ){
				 	$flag=0;
                    if($this->value('patient_name_id')== -1){
                       $sql="select  u.user_id from user as u 
                        inner join clinic_user as cu on u.user_id=cu.user_id  
                        and cu.clinic_id in (select clinic_id from clinic where (parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}') and ( status = 1 or status = 2 ) ) 
                        inner join clinic  on clinic.clinic_id = cu.clinic_id and (clinic.status = 1 or clinic.status = 2) 
                        where u.usertype_id='1' and (u.status=1) and u.mass_message_access=1";
                       $flag=-2; 
                     }
                    if($this->value('patient_name_id')== -2){
                      $sql="select  u.user_id from user as u 
                        inner join clinic_user as cu on u.user_id=cu.user_id  
                        and cu.clinic_id in (select clinic_id from clinic where (parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}') and ( status = 1 or status = 2 ) ) 
                        inner join clinic  on clinic.clinic_id = cu.clinic_id and (clinic.status = 1 or clinic.status = 2) 
                        where u.usertype_id='1' and (u.status = 2) and u.mass_message_access=1";
                      $flag=-2; 
                    }
                    if($this->value('patient_name_id')== -3){
                      $sql="select  u.user_id from user as u 
                        inner join clinic_user as cu on u.user_id=cu.user_id  
                        and cu.clinic_id in (select clinic_id from clinic where (parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}') and ( status = 1 or status = 2 ) ) 
                        inner join clinic  on clinic.clinic_id = cu.clinic_id and (clinic.status = 1 or clinic.status = 2) 
                        where u.usertype_id='1' and (u.status=1 or u.status = 2) and u.mass_message_access=1";
                      $flag=-3; 
                    }
                    if($flag != 0){
                     $result_patient = @mysql_query($sql);
                      if(@mysql_num_rows($result_patient)> 0 ){
                        $data = array(
				 		'user_id' => $this->userInfo("user_id"),
				 		'sender_type' => $this->value('patient_name_id'),
				 		'subject' => $this->encrypt_data($this->value('subject')),
				 		'message' => $this->encrypt_data($this->value('content')),
				 		'status' => '1',
				 		'messageDate' => date('Y-m-d H:i:s',time()),
                        );
                         $this->insert("mass_message",$data);                        
                        }else{
                       $error[] = "There is no patient Associated with you."; 
                      }
                      
                     header("location:index.php?action=message_listing&sort=sent_date&order=desc&mass=mass");
				 	 exit();
                     
                    }
                   else{
                    $data = array(
				 		'patient_id' => $this->value('patient_name_id'),
				 		'sender_id' => $this->userInfo("user_id"),
				 		'subject' => $this->encrypt_data($this->value('subject')),
				 		'content' => $this->encrypt_data($this->value('content')),
				 		'parent_id' => '0',
				 		'sent_date' => date('Y-m-d H:i:s',time()),
                        'recent_date' => date('Y-m-d H:i:s',time()),
                        'replies' => '0'
				 	);
				 	if($this->insert("message",$data)){
				 		$message_id = $this->insert_id();
                        $sqlforAA="select * from therapist_patient where patient_id='{$this->value('patient_name_id')}' and therapist_id='{$this->userInfo('user_id')}' and status=1";
                        $resultAA=@mysql_query($sqlforAA);
                        $numrows=@mysql_num_rows($resultAA);
                        if( $numrows == 0){
                              //echo 'user_id'.$this->value('patient_name_id');
                              //echo '<br>';
                              $usertype=$this->userInfo('usertype_id',$this->value('patient_name_id'));
                              
                              if($usertype==1){
                                $sqludate="update message set aa_message = 1 where message_id=".$message_id;
                                @mysql_query($sqludate);
                              }
                            
                              $data = array(
				 				'message_id' => $message_id,
				 				'user_id' => $this->userInfo("user_id"),
				 				'unread_message' => '2'
				 			);    
                            $this->insert("message_user",$data);
                        }else{
                        
				 		$query = "select therapist_id from therapist_patient 
				 				  where patient_id = '{$this->value('patient_name_id')}' and status = 1";
				 		$result = @mysql_query($query);
                      
				 		while( $row = @mysql_fetch_array($result)){
				 			// Entry for Therapist
				 			$data = array(
				 				'message_id' => $message_id,
				 				'user_id' => $row['therapist_id'],
				 				'unread_message' => '1'
				 			);
				 			
                            if( $row['therapist_id'] == $this->userInfo("user_id") ){
				 				$data['unread_message'] = '2';
                               
				 			}
                            
                            
				 			$this->insert("message_user",$data);
				 			
				 			/*
				 			#####  Message should only go to Patient. #############	
				 				if( $row['therapist_id'] != "" && is_numeric($row['therapist_id']) ){
				 				$this->new_post_notification($row['therapist_id']);
				 			}*/
				 		}
                       
                        }
				 		
				 		// Entry for Patient
				 		$data = array(
				 				'message_id' => $message_id,
				 				'user_id' => $this->value('patient_name_id'),
				 				'unread_message' => '1'
				 		);
				 		$this->insert("message_user",$data);
				 		if( $this->value('patient_name_id') != "" && is_numeric($this->value('patient_name_id') )){
				 			$this->new_post_notification($this->value('patient_name_id'));
				 		}
				 		$error[] = "Your message is successfully posted to ".$this->value('patient_name');
                        if( $from_record == '1' ){
                            header("location:index.php?action=therapistViewPatient&id=$patient_name_id");
                            unset($from_record);
                            exit();
                        }
				 		header("location:index.php?action=message_listing&sort=sent_date&order=desc");
				 		exit();
				 	}
                  }
                  	
				}
				else{
					$replace['patient_name'] = $this->value('patient_name');
					$replace['patient_name_id'] = $this->value('patient_name_id');
					$replace['subject'] = $this->value('subject');
					$replace['content'] = $this->hyperlink($this->value('content'));		
				}
				$replace['error'] = $this->show_error($error);
				
			}
            $replace['from_record'] = $this->value('from_record');
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();
			$replace['body'] = $this->build_template($this->get_template("message_compose"),$replace);
			$replace['browser_title'] = "Tx Xchange: Provider Compose New Message";
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}
		/**
		 * This function formats the E-mail and send it to user.
		 * @param numeric $user_id
		 * @return none
		 * @access public
		 */
		function new_post_notification( $user_id = "" ){
			if( $user_id != "" ){
				$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
			if($clinic_channel==1){
                                $business_url=$this->config['business_tx']; 
                                $support_email=$this->config['email_tx'];
                            }else{
                                $business_url=$this->config['business_wx']; 
                                $support_email=$this->config['email_wx'];   
                            }
				$data = array(
						'url' => $this->config['url'],
						'images_url' => $this->config['images_url'],
					        'business_url'=>$business_url,
        			                'support_email'=>$support_email,
        			                'clinic_name'=>html_entity_decode($this->get_clinic_info($user_id,"clinic_name"), ENT_QUOTES, "UTF-8")
						);
				
				
	            $clinic_type = $this->getUserClinicType($user_id);
	            $clinicName	=	html_entity_decode($this->get_clinic_info($user_id,"clinic_name"), ENT_QUOTES, "UTF-8");
				
	            if( $clinic_channel == 1){
	            	$message = $this->build_template("mail_content/plpto/new_message_plpto.php",$data);
	            }
	            else{
	            	$message = $this->build_template("mail_content/wx/new_message_plpto.php",$data);
	            }		
									
				$to = $this->userInfo('username',$user_id);
				$subject = "Message from your Provider at ".$clinicName;
				// To send HTML mail, the Content-type header must be set
				$headers  = 'MIME-Version: 1.0' . "\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
				
				// Additional headers
				$headers .= "From: " .$this->setmailheader($clinicName). "<do-not-reply@txxchange.com>" . "\n";
				//$headers .= 'Cc: example@example.com' . "\n";
				//$headers .= 'Bcc: example@example.com' . "\n";
				$returnpath = '-fdo-not-reply@txxchange.com';
				// Mail it
				//echo $message;exit;
				@mail($to, $subject, $message, $headers, $returnpath);
			}
			
		}
		/**
		 * This function formats the E-mail and send it to user.
		 * @param numeric $user_id
		 * @return none
		 * @access public
		 */
		function mass_message_new_post_notification( $user_id = "" ){
			
            if( $user_id != "" ){
                $clinicId=$this->get_clinic_info($user_id);
                $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
                $clinicName=html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
                if($clinic_channel==1){
                                $business_url=$this->config['business_tx']; 
                                $support_email=$this->config['email_tx'];
                            }else{
                                $business_url=$this->config['business_wx']; 
                                $support_email=$this->config['email_wx'];   
               }
				$data = array(
						'url' => $this->config['url'],
						'images_url' => $this->config['images_url'],
                        'clinicName'=>$clinicName,
				        'business_url'=>$business_url,
                        'support_email'=>$support_email
						);
				
				//$user_id = $this->userInfo('user_id');
	            $clinic_type = $this->getUserClinicType($user_id);
	           
	            if( $clinic_channel == 1){
	            	$subject = "Message from ".$clinicName;
	            	$message = $this->build_template("mail_content/plpto/mass_new_message_plpto.php",$data);
	            }
	            else{
	            	$subject = "Message from ".$clinicName;
	            	$message = $this->build_template("mail_content/wx/mass_new_message_plpto.php",$data);
	            }		
				//$message = $this->build_template("mail_content/new_message.php",$data);
					
				$to = $this->userInfo('username',$user_id);
				//$from = $this->userInfo("username");
				
				
				// To send HTML mail, the Content-type header must be set
				$headers  = 'MIME-Version: 1.0' . "\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
				
				// Additional headers
				//$headers .= 'To: '.$fullName.'<'.$_POST['email_address'].'>'. "\n";
				//$headers .= "From: " . $this->userInfo('name_title') . "&nbsp;" . $this->userInfo('name_first') ."&nbsp;" . $this->userInfo('name_last') ."<". $this->userInfo('username') .">" . "\n";
				/* $headers .= "To: " . $this->userInfo('name_title',$user_id) . " " . $this->userInfo('name_first',$user_id) ." " . $this->userInfo('name_last',$user_id) ."<". $this->userInfo('username',$user_id) .">" . "\n"; */
				$headers .= "From: ".$this->setmailheader($clinicName)." <do-not-reply@txxchange.com>\n";
				//$headers .= 'Cc: example@example.com' . "\n";
				//$headers .= 'Bcc: example@example.com' . "\n";
				$returnpath = '-fdo-not-reply@txxchange.com';
                // Mail it
				@mail($to, $subject, $message, $headers, $returnpath);
			}
			
		}
        
    /**
     * This functon shows list of messages for Therapist.
     */
    function message_listing()
    {
        $replace['get_satisfaction'] = $this->get_satisfaction();

        if($this->value('sort') != "")
        {
            if($this->value('order') == 'desc')
            {
                $orderby = " order by {$this->value('sort')} desc ";
            }
            else
            {
                $orderby = " order by {$this->value('sort')} ";
            }
        }
        else
        {
            $orderby = " order by from_name ";
        }
        $where = "";
        if($this->value('patient_name_id') != "")
        {
            $status = 1;
            if($this->value('patient_name_id') == -1)
                $status = '0';
            if($this->value('patient_name_id') == -2)
                $status = '0';
            If($this->value('patient_name_id') == -3)
                $status = '0';
            If($this->value('patient_name_id') == -4)
                $status = '0';

            if($status == 1)
            {
                $search1 = " and m.patient_id ='{$this->value('patient_name_id')}' and mass_patient_id =0";
            }
            //$where = " and m.patient_id = {$this->value('patient_name_id')} and mass_status=0 ";
            $where = " and m.patient_id IN (
						SELECT patient_id FROM therapist_patient WHERE therapist_id = '{$this->userInfo('user_id')}'
                     ) and m.parent_id = 0 and  m.mass_status = 0 and m.patient_id = '{$this->value('patient_name_id')}'";
        }
        else
        {
            $where = " and mass_patient_id =0 and  m.patient_id IN (
						SELECT patient_id FROM therapist_patient WHERE therapist_id = '{$this->userInfo('user_id')}'
                     ) and aa_message=0  or  ((mass_patient_id =0)) and sender_id='{$this->userInfo('user_id')}' and m.parent_id = 0 or  ((mass_patient_id =0)) and patient_id='{$this->userInfo('user_id')}' and m.parent_id = 0 or  ((mass_patient_id =0)) and sender_id='{$this->userInfo('user_id')}' and aa_message=1 and m.parent_id = 0 ";
        }

        # Added new valiable for counting replies. 13/02/2008
        $replies_count = 0;
        if(($this->value('patient_name_id') == "" && $this->value('sub') != 'Search') || ($this->value('patient_name') == "" && $this->value('sub') == 'Search') || ($this->value('patient_name') != "" && $this->value('sub') == 'Search' && $this->value('patient_name_id') != ""))
        {
            $privateKey = $this->config['private_key'];
            if($this->userInfo('admin_access') == 1)
            {
                $clinicId = $this->clinicInfo('clinic_id');
                if($this->value('patient_name_id') != "")
                {
                    if($this->value('patient_name_id') == -1)
                    {
                        $where = " and mass_patient_id =-1 and mass_status=1 and sender_id='{$this->userInfo('user_id')}'";
                    }
                    elseif($this->value('patient_name_id') == -2)
                    {
                        $where = " and mass_patient_id =-2 and mass_status=1 and sender_id='{$this->userInfo('user_id')}'";
                    }
                    elseif($this->value('patient_name_id') == -3)
                    {
                        $where = " and mass_patient_id =-3 and mass_status=1 and sender_id='{$this->userInfo('user_id')}'";
                    }
                    elseif($this->value('patient_name_id') == -4)
                    {
                        $where = " and mass_patient_id =-4 and mass_status=1 and sender_id='{$this->userInfo('user_id')}'";
                    }
                    else
                    {
                        //$where = " and m.patient_id = {$this->value('patient_name_id')}  and mass_status=0 and aa_message=0 and m.sender_id='{$this->userInfo('user_id')}' or  ((mass_patient_id =0) OR mass_status=0) and sender_id='{$this->userInfo('user_id')}' and aa_message=1 and m.parent_id = 0 and m.patient_id = {$this->value('patient_name_id')}";
                        // this is comment by sanjay 
                        //$where = " and m.patient_id = {$this->value('patient_name_id')}  and mass_status=0 and aa_message=0 and m.sender_id='{$this->userInfo('user_id')}' or ((mass_patient_id !=0 and replies !=0) OR mass_status=1) and sender_id = '{$this->userInfo('user_id')}' and m.parent_id = 0  or  ((mass_patient_id !=0 and replies !=0) OR mass_status=1) and mass_patient_id='{$this->userInfo('user_id')}' and m.parent_id = 0  or  ((mass_patient_id =0)) and sender_id='{$this->userInfo('user_id')}' and m.parent_id = 0 or  ((mass_patient_id =0)) and patient_id='{$this->userInfo('user_id')}' and m.parent_id = 0 or  ((mass_patient_id =0)) and sender_id='{$this->userInfo('user_id')}' and aa_message=1 and m.parent_id = 0 ";
                        $where .= " or ((mass_patient_id !=0 and replies !=0) OR mass_status=1) and sender_id = '{$this->userInfo('user_id')}' and m.parent_id = 0  or  ((mass_patient_id !=0 and replies !=0) OR mass_status=1) and mass_patient_id='{$this->userInfo('user_id')}' and m.parent_id = 0  or  ((mass_patient_id =0)) and sender_id='{$this->userInfo('user_id')}' and m.parent_id = 0 or  ((mass_patient_id =0)) and patient_id='{$this->value('patient_name_id')}' and m.parent_id = 0 or  ((mass_patient_id =0)) and sender_id='{$this->userInfo('user_id')}' and aa_message=1 and m.parent_id = 0 ";
                    }
                }
                else
                {
                    $where .= " or ((mass_patient_id !=0 and replies !=0) OR mass_status=1) and sender_id = '{$this->userInfo('user_id')}' and m.parent_id = 0  or  ((mass_patient_id !=0 and replies !=0) OR mass_status=1) and mass_patient_id='{$this->userInfo('user_id')}' and m.parent_id = 0  or  ((mass_patient_id =0)) and sender_id='{$this->userInfo('user_id')}' and m.parent_id = 0 or  ((mass_patient_id =0)) and patient_id='{$this->userInfo('user_id')}' and m.parent_id = 0 or  ((mass_patient_id =0)) and sender_id='{$this->userInfo('user_id')}' and aa_message=1 and m.parent_id = 0 ";
                }
            }
            else
            {
                $where .= " or ((mass_patient_id !=0 and replies !=0) OR mass_status=1) and sender_id = '{$this->userInfo('user_id')}' and m.parent_id = 0  or  ((mass_patient_id !=0 and replies !=0) OR mass_status=1) and mass_patient_id='{$this->userInfo('user_id')}' and m.parent_id = 0  or  ((mass_patient_id =0)) and sender_id='{$this->userInfo('user_id')}' and m.parent_id = 0 or  ((mass_patient_id =0)) and patient_id='{$this->userInfo('user_id')}' and m.parent_id = 0 or  ((mass_patient_id =0)) and sender_id='{$this->userInfo('user_id')}' and aa_message=1 and m.parent_id = 0 ";
            }

            $query = "SELECT concat(AES_DECRYPT(UNHEX(name_first),'{$privateKey}'),' ',AES_DECRYPT(UNHEX(name_last),'{$privateKey}')) as from_name,
                     AES_DECRYPT(UNHEX(m.subject),'{$this->config['private_key']}') as subject
                      ,m.recent_date AS sent_date,m.patient_id,m.message_id, 
					  replies,
					  m.patient_id,
                      m.mass_patient_id,
                      m.mass_status 
					  FROM message m
					  inner join user u on u.user_id = m.patient_id {$search1}
					  WHERE m.parent_id = 0 {$where} 
                      and m.message_id not in (select message_id from system_message where user_id = '{$this->userInfo('user_id')}') 
                      {$orderby}";

            $sqlcount = "SELECT count(1) 
					  FROM message m
					  inner join user u on u.user_id = m.patient_id {$search1}
					  WHERE m.parent_id = 0 {$where} 
                      and m.message_id not in (select message_id from system_message where user_id = '{$this->userInfo('user_id')}')";
            //exit;

            $replies_count = $this->total_replies($query);

            $link = $this->pagination($rows = 0, $query, $this->value('message_listing'), $search, '', '', '', $sqlcount);
            $replace['link'] = $link['nav'];
            $result = $link['result'];
            $from_name = '';
            while($row = @mysql_fetch_array($result))
            {

                $check = $this->check_therapist($row['patient_id']);
                $usertype = $this->userInfo('usertype_id', $row['patient_id']);
                if($check == 1)
                {
                    if($usertype == 1)
                        $from_name = "<a href='index.php?action=therapistViewPatient&id={$row['patient_id']}'>" . $this->unread_message($row['message_id'], $row['from_name']) . "</a>";
                    else
                        $from_name = "<a href='index.php?action=therapistViewPatient&id={$row['patient_id']}'>" . $this->unread_message($row['message_id'], $row['from_name']) . ' (Staff)' . "</a>";
                }else
                {
                    if($usertype == 1)
                        $from_name = "<span style='color:black'>" . $this->unread_message($row['message_id'], $row['from_name']) . "</span>";
                    else
                        $from_name = "<span style='color:black'>" . $this->unread_message($row['message_id'], $row['from_name']) . ' (Staff)' . "</span>";
                }
                if($row['mass_status'] == 1)
                {
                    if($row['mass_patient_id'] == -1)
                    {
                        $from_name = "<span style='color:black'>" . 'All Current Patients' . "</span>";
                    }
                    elseif($row['mass_patient_id'] == -2)
                    {
                        $from_name = "<span style='color:black'>" . 'All Discharged Patients' . "</span>";
                    }
                    elseif($row['mass_patient_id'] == -3)
                    {
                        $from_name = "<span style='color:black'>" . 'All Patients' . "</span>";
                    }
                    elseif($row['mass_patient_id'] == -4)
                    {
                        $from_name = "<span style='color:black'>" . 'All EHS Patients' . "</span>";
                    }
                }
                $data = array(
                    'style' => ($c++ % 2) ? "line1" : "line2",
                    'from_name' => $from_name,
                    'subject' => "<a href='index.php?action=set_unread_message&message_id={$row['message_id']}'>" . $this->unread_message($row['message_id'], $row['subject']) . "</a>",
                    'sent_date' => "<a href='index.php?action=set_unread_message&message_id={$row['message_id']}'>" . $this->unread_message($row['message_id'], $this->formatDateExtra($row['sent_date'], $this->userInfo('timezone'))) . "</a>",
                    'replies' => $row['replies']
                );

                $replace['message_list_record'] .= $this->build_template($this->get_template("message_list_record"), $data);
            }
        }
        else
        {
            $replace['message_list_record'] = '<tr><td colspan="3">No record found</td></tr>';
        }
        // list of patients associated with Therapist. 
        $replace['patient_name_id'] = $this->value('patient_name_id');
        $replace['patient_name'] = $this->value('patient_name');
        // End
        // Head of list
        if($this->value('patient_name_id') != "")
        {
            $where = " and  patient_id = {$this->value('patient_name_id')}";
        }

        include_once("template/message/message_array.php");
        $query_string = array();
        $query_string['patient_name_id'] = $this->value('patient_name_id');
        $query_string['patient_name'] = $this->value('patient_name');
        $query_string['sub'] = $this->value('sub');
        $head_arr = $this->table_heading($message_list_head, "from_name", $query_string);
        $head_arr['replies'] = $replies_count;

        $replace['message_list_head'] = $this->build_template($this->get_template("message_list_head"), $head_arr);
        // End
        if($this->value('mass') == 'mass')
            $replace['mass_message_message'] = "Your message has been queued for processing. Once it is delivered , you will receive an email asking you to log back in and check this page for the results. This process could take a from a few minutes to a few hours.<tr><td>&nbsp;</td></tr>";

        $replace['header'] = $this->build_template($this->get_template("header"));
        $replace['sidebar'] = $this->sidebar();
        $replace['body'] = $this->build_template($this->get_template("message_listing"), $replace);
        $replace['browser_title'] = "Tx Xchange: Provider Message Center";
        $this->output = $this->build_template($this->get_template("main"), $replace);
    }
                
		/**
		 * Used to show main message and reply on main message. 
		 * @param none
		 * @return none
		 * @access public
		 */
		function view_message(){
            $replace['current_date'] = date('m-d-Y H:i:s');
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar();
			
			$replace['get_satisfaction'] = $this->get_satisfaction();
		

             // submit reply
            if( $this->value('message_id') != "" ){
            	
            	if( $this->value('submit_action') == "reply_message" ){
            		$error = array();
            		if( $this->value('content') == "" ){
            			$error[] = "Please enter your reply";
            		}
            	
            		if( is_array($error) && count($error) == 0 ){
            	  			$this->send_reply();
            	  			//header("location:index.php?action=view_message&message_id={$this->value('message_id')}");
            	  			header("location:index.php?action=message_listing&sort=sent_date&order=desc");
            	  			exit();
            		}
            	}
            }
            // End 
            
            
            // Show main message or main thread
            if( $this->value('message_id') != "" ){
            	$data = $this->main_message($this->value('message_id'));
            	$replace['main_message'] = $this->build_template($this->get_template("main_message"),$data);
               
                /**
                * Change For Ticket No: BMU 16 By Rohit Mishra On Date 24/1/2013
                */
                $replace['blink'] =$data['blink'];
                // $replace['blink'] = '<a href="index.php?action=therapistViewPatient&id='.$data['sender_id'].'">'.strtoupper($data['name_first']." ".$data['name_last']).'</a>';
                   
                  
                   
                
            }
            // End
            
            // reply message
            if( $this->value('message_id') != "" ){
            	$replace['reply_message'] = $this->reply_message($this->value('message_id'));
            	if($this->get_field($this->value('message_id'),"message","mass_status")==1){
            	       $replace['allows'] = 'disabled';
            	       $replace['message'] = 'Provider can not reply on Mass message/EHS Message ';
            	   }else{	
            	       $replace['allows'] = '';
            	       $replace['message'] = '';
            	   }
             }else{
            	$replace['allows'] = '';
            	$replace['message'] = '';
            }
            // end 
           
            $replace['error'] = $this->show_error($error);
            $replace['message_id'] = $this->value('message_id');
            
            $replace['body'] = $this->build_template($this->get_template("view_message"),$replace);
            $replace['browser_title'] = "Tx Xchange: Provider Compose New Message";
            $this->output = $this->build_template($this->get_template("main"),$replace); 
            
            
        }
        /**
		 * This function formats the subject for any message. And makes unread message bold.
		 *
		 * @param numeric $message_id
		 * @param string $subject
		 * @return string
		 * @access public
		 */
        function set_unread_message(){
        	if( $this->value('message_id') != "" ){
        		$data = array(
        			'unread_message' => 2
        		);
        		
        		$this->update("message_user",$data, " message_id = '{$this->value('message_id')}' and user_id = '{$this->userInfo('user_id')}' ");
        	}
        	header("location:index.php?action=view_message&message_id={$this->value('message_id')}");
        	exit();
        }
        
  	     /**
         * Used to show main message and reply on main message. 
         * @param none
         * @return none
         * @access public
         */
        function view_message_ehs(){
            $replace['current_date'] = date('m-d-Y H:i:s');
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar();
            
            $replace['get_satisfaction'] = $this->get_satisfaction();
        

             // submit reply
            if( $this->value('message_id') != "" ){
                
                if( $this->value('submit_action') == "reply_message" ){
                    $error = array();
                    if( $this->value('content') == "" ){
                        $error[] = "Please enter your reply";
                    }
                
                    if( is_array($error) && count($error) == 0 ){
                            $this->send_reply();
                            //header("location:index.php?action=view_message&message_id={$this->value('message_id')}");
                            header("location:index.php?action=message_listing_ehs&sort=sent_date&order=desc");
                            exit();
                    }
                }
            }
            // End 
            
            
            // Show main message or main thread
            if( $this->value('message_id') != "" ){
                $data = $this->main_message($this->value('message_id'));
                $replace['main_message'] = $this->build_template($this->get_template("main_message"),$data);                    
            }
            // End
            
            // reply message
        // reply message
            if( $this->value('message_id') != "" ){
                $replace['reply_message'] = $this->reply_message($this->value('message_id'));
                if($this->get_field($this->value('message_id'),"message","mass_status")==1){
                       $replace['allows'] = 'disabled';
                       $replace['message'] = 'Provider can not reply on Mass message/EHS Message ';
                   }else{   
                       $replace['allows'] = '';
                       $replace['message'] = '';
                   }
             }else{
                $replace['allows'] = '';
                $replace['message'] = '';
            }
            // end 
           
            $replace['error'] = $this->show_error($error);
            $replace['message_id'] = $this->value('message_id');
            $replace['body'] = $this->build_template($this->get_template("view_message"),$replace);
            $replace['browser_title'] = "Tx Xchange: Provider Compose New Message";
            $this->output = $this->build_template($this->get_template("main"),$replace);                      
        }
        /**
         * This function formats the subject for any message. And makes unread message bold.
         *
         * @param numeric $message_id
         * @param string $subject
         * @return string
         * @access public
         */
        function set_unread_message_ehs(){
            if( $this->value('message_id') != "" ){
                $data = array(
                    'unread_message' => 2
                );
                
                $this->update("message_user",$data, " message_id = '{$this->value('message_id')}' and user_id = '{$this->userInfo('user_id')}' ");
            }
            header("location:index.php?action=view_message_ehs&message_id={$this->value('message_id')}");
            exit();
        }
        /**
        * Returns number of replies.
        */
        function num_replies( $message_id){
            if( $message_id > 0 ){
                $query = "select count(*) from message where  parent_id = '{$message_id}' ";
                $result = @mysql_query($query);
                $row = @mysql_fetch_array($result);
                return $row[0];
            }
            return 0;
        }
        /**
         * Returns list of replies for any post.
         *
         * @param numeric $message_id
         * @return string
         * @access public
         */
        function reply_message($message_id){
        	$query = "select * from message where parent_id = '{$message_id}' order by sent_date asc";
        	$result = @mysql_query($query);
        	while( $row = @mysql_fetch_array($result) ){
	        	// Sender Id retrived from message table.
	        	$sender_id = $this->get_field($row['message_id'],"message","sender_id");
	        	// Therapist or Patient name retrive from user table.
	        	if($sender_id != ""){
                            
                            /**
                             * Change For Ticket No: BMU 16 By Rohit Mishra On Date 24/1/2013
                             */
                            
	        		//$data['name_first'] = $this->get_field($sender_id,"user","name_first");
				//$data['name_last'] = $this->get_field($sender_id,"user","name_last");
                            
                         $usertype=$this->userInfo('usertype_id',$sender_id);
                        
                          
                       if($sender_id!=$this->userInfo('user_id')){
                       if($usertype==1){ 
                        $data['name_first'] = '<a href="index.php?action=therapistViewPatient&id='.$sender_id.'">'.$this->get_field($sender_id,"user","name_first")." ".$this->get_field($sender_id,"user","name_last").'</a>';
                       }else{
                            $data['name_first'] = $this->get_field($sender_id,"user","name_first")." ".$this->get_field($sender_id,"user","name_last");
                       }
                        
                        
                       }else{
                            $data['name_first'] = $this->get_field($sender_id,"user","name_first")." ".$this->get_field($sender_id,"user","name_last");
			//$replace['name_last'] = $this->get_field($sender_id,"user","name_last");
                        }
                            
                        // change End   
                            
	        	}
	        	$data['sent_date'] = $this->formatDateExtra($row['sent_date'], $this->userInfo('timezone'));
	        	$data['content'] = $this->hyperlink(html_entity_decode(nl2br(str_replace("&amp;","&",$this->decrypt_data($row['content'])))));
	        	$reply_message .= $this->build_template($this->get_template("reply_message"),$data);
        	}
	        return $reply_message;
        }
        
        /**
         * 1) validate the reply.
         * 2) After successful validation result.
         * 3) Finaly, insert the reply in message and message user table.
         *
         *  @param none
         *  @return none
         *  @access public
         */
        function send_reply(){
        	$usertype=$this->userInfo('usertype_id',$this->get_field($this->value('message_id'),"message","patient_id"));
          	$data = array(
    				'subject' => 'reply',	
    				'content' => $this->encrypt_data($this->value('content')),
    				'patient_id' => $patient_id,
    				'parent_id' => $this->value('message_id'),
    				'sender_id' => $this->userInfo('user_id'),
    				'sent_date' => date('Y-m-d H:i:s'),
    			);
    			
    			if($this->insert("message",$data)){
                    
                    /*// Delete user_id from system_message table to show the reply.
                    $patient_id = $this->get_field($this->value('message_id'),"message","patient_id");
                    $message_id = $this->value('message_id');
                    $query = "delete from system_message where message_id = '{$message_id}' and user_id = '{$patient_id}' ";
                    @mysql_query($query);*/
                    
                    
    				// Add record for therapist in message user table.
    				$new_message_id = $this->insert_id();
                    
                    // Update recent_date and replies column in message table.
                    $arr_message = array(
                        'recent_date' => $data['sent_date'],
                        'replies' => "'{$this->num_replies($this->value('message_id'))}'"
                    );
                    $query = " update message set recent_date = '{$data['sent_date']}', 
                    replies = '{$this->num_replies($this->value('message_id'))}' where message_id = '{$this->value('message_id')}' ";
                    @mysql_query($query);
                    $sqlAA="select * from therapist_patient where patient_id='{$this->get_field($this->value('message_id'),"message","patient_id")}' and therapist_id='{$this->userInfo('user_id')}'";
                    $resultAA=@mysql_result($sqlAA);
                    if(@mysql_num_rows($resultAA)== 0){
                         if($usertype==1){
                            $data = array(
    			 				    'message_id' => $this->value('message_id'),
    			 				    'user_id' => $this->userInfo("user_id"),
    			 				    'unread_message' => '2'
    			 			     ); 
                          	$this->insert("message_user",$data); 
                        }    
                        
                    }else{
                   	$query = "select therapist_id from therapist_patient 
		 				  where patient_id = '{$this->get_field($this->value('message_id'),"message","patient_id")}' 
		 				  and status = 1 ";
		 			$result = @mysql_query($query);
                   
		 			while( $row = @mysql_fetch_array($result)){
			 			// Entry for Therapist
			 			$data = array(
			 				'message_id' => $this->value('message_id'),
			 				'user_id' => $row['therapist_id'],
			 				'unread_message' => '1'
			 			);
			 			
			 			// For sender the message will not be bold.
			 			if( $row['therapist_id'] == $this->userInfo("user_id") ){
				 				$data['unread_message'] = '2';
                                
				 		}
			 			$this->insert("message_user",$data);
		 			}//end of while
                   }
		 			// End 
		 			
		 			
                    // Add record for patient in message user table.
		 			if($usertype==1){
                            $data = array(
    		 				'message_id' => $this->value('message_id'),
    		 				'user_id' => $this->get_field($this->value('message_id'),"message","patient_id"),
    		 				'unread_message' => '1'
		 			        );
                    $this->insert("message_user",$data);
                    }
		 			// End
		 			/*for provider to provider */
                    if($usertype==2){
                    $data = array(
			 				    'message_id' => $this->value('message_id'),
			 				    'user_id' => $this->userInfo("user_id"),
			 				    'unread_message' => '2'
			 			     ); 
                    $this->insert("message_user",$data);
                    $userId=$this->get_field($this->value('message_id'),"message","patient_id");
                    if($userId==$this->userInfo('user_id')){
                        $userId=$this->get_field($this->value('message_id'),"message","sender_id");  
                    }
                     $data = array(
			 				    'message_id' => $this->value('message_id'),
			 				    'user_id' => $userId,
			 				    'unread_message' => '1'
			 			     ); 
                    $this->insert("message_user",$data);
                    
                    }
                    
		 			//Send E-mail to patient regarding this reply.
		 			$this->new_post_notification( $this->get_field($this->value('message_id'),"message","patient_id"));
    			}
        }
        /**
         * This function populates an associated array with required information for sending an email.
         *
         * @param stirng $message_id
         * @return array
         */
        function main_message($message_id){
        	// Sender Id retrived from message table.
        	$sender_id = $this->get_field($this->value('message_id'),"message","sender_id");
                $patient_id=$this->get_field($this->value('message_id'),"message","patient_id");
        	// Therapist or Patient name retrive from user table.
                
                /**
                * Change For Ticket No: BMU 16 By Rohit Mishra On Date 24/1/2013
                */
                
                
                //echo "<br>sender id=".$sender_id;
                //echo "<br> User Id". $this->userInfo('user_id');
                
               $replace['$sender_id'] =0;
        	if($sender_id != ""){
        		//$replace['name_first'] = $this->get_field($sender_id,"user","name_first");
			//$replace['name_last'] = $this->get_field($sender_id,"user","name_last");
                        
                        $usertype=$this->userInfo('usertype_id',$sender_id);
                        $usertype1=$this->userInfo('usertype_id',$patient_id);
                        
                        
                        if($usertype==1){
                            $replace['blink'] = '<a href="index.php?action=therapistViewPatient&id='.$sender_id.'">'.strtoupper($this->get_field($sender_id,"user","name_first")." ".$this->get_field($sender_id,"user","name_last")).'</a>'; 
                        }else if($usertype1==1){
                           $replace['blink'] = '<a href="index.php?action=therapistViewPatient&id='.$patient_id.'">'.strtoupper($this->get_field($patient_id,"user","name_first")." ".$this->get_field($patient_id,"user","name_last")).'</a>';  
                        }
                        
                        else{
                             $replace['blink'] = strtoupper($this->get_field($sender_id,"user","name_first")." ".$this->get_field($sender_id,"user","name_last"));
                        }
                        
                    
                       
                        if($sender_id!=$this->userInfo('user_id')){
                        $replace['sender_id'] = $sender_id;
                        
                        if($usertype==1){
                        $replace['name_first'] = '<a href="index.php?action=therapistViewPatient&id='.$sender_id.'">'.$this->get_field($sender_id,"user","name_first")." ".$this->get_field($sender_id,"user","name_last").'</a>';
                        }else{
                             $replace['name_first'] = $this->get_field($sender_id,"user","name_first")." ".$this->get_field($sender_id,"user","name_last");
                        }
                        
                        
                        }else{
                            $replace['name_first'] = $this->get_field($sender_id,"user","name_first")." ".$this->get_field($sender_id,"user","name_last");
			//$replace['name_last'] = $this->get_field($sender_id,"user","name_last");
                        }
                     // end change
                        
                                
        	}elseif($sender_id==0){
                $replace['name_first'] = "No";
                $replace['name_last'] = "Reply";                
            }
        	$replace['sent_date'] = $this->formatDateExtra($this->get_field($this->value('message_id'),"message","sent_date"), $this->userInfo('timezone'));
        	$replace['subject'] = $this->decrypt_data($this->get_field($this->value('message_id'),"message","subject"));
        	$replace['content'] = $this->hyperlink(html_entity_decode(nl2br(str_replace("&amp;","&",$this->decrypt_data($this->get_field($this->value('message_id'),"message","content"))))));
        	return $replace;
        }
        /**
         * This function populates an associated array with required information for sending an email.
         *
         * @param stirng $message_id
         * @return array
         */
        function notification_reminder(){
            
            
			$replace['get_satisfaction'] = $this->get_satisfaction();
		
			// Set all required value
            $content = $this->value('content');
            
            $user_id = $this->userInfo('user_id');
            if($this->value('action_submit') == 'submit' ){
                $content_arr = array(
                    'therapist_id' => $user_id,
                    'message' => $this->encrypt_data($content,$this->config['private_key']),
                );
                if( $this->check_message_set($user_id) === false ){
                    $content_arr['creation_date'] = date("Y-m-d H:i:s", time());
                    $this->insert('notification_reminder',$content_arr);
                    $error[] = "Message successfully inserted.";
                    
                }
                else{
                    $where = " therapist_id = '{$user_id}' " ;
                    $this->update('notification_reminder',$content_arr,$where);
                    $error[] = "Message successfully updated.";
                }
                $replace['error'] = $this->show_error($error,"green");
            }
            else{
                    $query = "select message from notification_reminder where therapist_id = '{$user_id}' " ;
                    $result = @mysql_query($query);
                    $row = @mysql_fetch_array($result);
                    $content = $this->decrypt_data($row['message'],$this->config['private_key']);
            }
            
            $replace['content'] = $this->hyperlink($content);
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar();
            $replace['body'] = $this->build_template($this->get_template("notification_reminder"),$replace);
            $replace['browser_title'] = "Tx Xchange: Notification and Reminders";
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
        /**
        * This function shows chooses for Therapist which action he want to perform. Wether he want to compose a message or 
        * set interval for Outcome measure.
        */
        function choose_notification_reminder(){
           
			$replace['get_satisfaction'] = $this->get_satisfaction();
			
			$select_option = $this->value('select_option');
            if( $select_option == "one" ){
                header("location:index.php?action=notification_reminder");
                exit();
            }
            elseif( $select_option == "two" ){
                header("location:index.php?action=set_interval_om");
                exit();
            }
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar();
            $replace['body'] = $this->build_template($this->get_template("notification_reminder"),$replace);
            $replace['browser_title'] = "Tx Xchange: Notification and Reminders";
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
        /**
        * This function shows page for setting interval for outcome measure form.
        */
        function set_interval_om(){
            
			$replace['get_satisfaction'] = $this->get_satisfaction();
		
			$flag = false;
            // Get user_id
            $user_id = $this->userInfo('user_id');
            if( $this->value('action_submit') == 'submit' ){
                // Make data array
                $set_interval_arr = array(
                        'therapist_id' => $user_id,
                        'acute' => $this->value('acute'),
                        'sub_acute' => $this->value('sub-acute'),
                        'chronic' => $this->value('chronic')
                    );
                // Check whether record exist or not.
                $flag = $this->check_set_interval($user_id);
                if( $flag == false){
                    $set_interval_arr['creation_date'] = date("Y-m-d H:i:s", time());
                    // Insert record in data base.
                    $this->insert('set_interval_om',$set_interval_arr);
                }
                else{
                    // Update set interval for Therapist
                    $where = " therapist_id = '{$user_id}' ";
                    $this->update('set_interval_om', $set_interval_arr, $where);
                }
            }
            $replace['acute'] = "";
            $replace['sub-acute'] = ""; 
            $replace['chronic'] = "";
            $row = $this->select('set_interval_om',''," acute, sub_acute, chronic ","therapist_id = {$user_id} ");
            if( is_array($row) && count($row) > 0 ){
                $replace['acute'] = $row['acute'];
                $replace['sub-acute'] = $row['sub_acute']; 
                $replace['chronic'] = $row['chronic'];
            }
            
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar();
            $replace['body'] = $this->build_template($this->get_template("set_time_interval"),$replace);
            $replace['browser_title'] = "Tx Xchange: Notification and Reminders";
            $this->output = $this->build_template($this->get_template("main"),$replace);    
        }
        /**
        * Checks whether Therapist has set interval for om or not.
        * If interval is set function will return true otherwise false.
        */
        function check_set_interval( $user_id = "" ){
            
            if( is_numeric($user_id) > 0 ) {
                $query = "select therapist_id from set_interval_om where therapist_id = '{$user_id}' ";
                $result = @mysql_query($query);
                if( @mysql_num_rows($result) > 0 ){
                    return true;
                }
                else{
                    return false;
                }
            }
        }
        /**
        * Checks whether Therapist has set message for his/her Patient.
        * If message is set function will return true otherwise false.
        */
        function check_message_set( $user_id = "" ){
            
            if( is_numeric($user_id) > 0 ) {
                $query = "select therapist_id from notification_reminder where therapist_id = '{$user_id}' ";
                $result = @mysql_query($query);
                if( @mysql_num_rows($result) > 0 ){
                    return true;
                }
                else{
                    return false;
                }
            }
        }
        
  	/**
         * This method is used to post any new message to patient.
         * 
         * @param none
         * @return none
         * @access public
         */
        function compose_message_ehs(){
            $replace['patient_name'] = $this->value('patient_name');
            $patient_name_id = $this->value('patient_name_id');
            $replace['patient_name_id'] = $patient_name_id;
            $replace['subject'] = "";
            $replace['content'] = "";
            
            $replace['get_satisfaction'] = $this->get_satisfaction();
            
            $clinicId = $this->clinicInfo('clinic_id');
            //$Ehspatients = $this->getProviderEHSPatients($clinicId);
            if($this->is_corporate($clinicId)==1){
                $Ehspatients= $this->get_paitent_list($clinicId);
            }else{
                $Ehspatients= $this->getProviderEHSPatients($clinicId);
            }
            $totalEhsPatients = count($Ehspatients);
            if($totalEhsPatients == '0') {
                header("location:index.php?action=therapistEhsPatient&ehsunsub=0");
            } 
            
            $from_record = $this->value('from_record');
            
            if( $this->value('action_submit') == "submit" ){
                
                $error = array();
                if( $this->value('patient_name_id') == "" ){
                    $error[] = "Please select Patient name in to field";
                }
                if( $this->value('subject') == "" ){
                    $error[] = "Please enter subject";
                }
                if( $this->value('content') == "" ){
                    $error[] = "Please enter message";
                }
                if( is_array($error) && sizeof($error) == 0 ){
                    $flag=0;
                    if($this->value('patient_name_id')== -4){
                        if($this->is_corporate($clinicId)==1){
                            $sql = "select  u.user_id
		from user as u left join patient_subscription as ps on u.user_id=ps.user_id and ((ps.subs_status='1' and ps.paymentType='0') or (ps.subs_status='2' and subs_end_datetime >= now()) or (ps.subs_status='1'  and ps.paymentType='1' and subs_end_datetime > now()))
		inner join clinic_user as cu on u.user_id=cu.user_id
		and cu.clinic_id in (select clinic_id from clinic where (parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}') and ( status = 1 or status = 2 ) )
		inner join clinic  on clinic.clinic_id = cu.clinic_id and (clinic.status = '1' or clinic.status = '2')
		where u.usertype_id='1' and (u.status=1 or u.status = 2)";
                        }else{
                             $sql="SELECT  u.user_id FROM user as u inner join clinic_user as cu on u.user_id=cu.user_id AND cu.clinic_id in (SELECT clinic_id FROM clinic WHERE parent_clinic_id = {$clinicId} OR clinic_id = {$clinicId}  and ( status = 1 or status = 2 ) ) INNER JOIN clinic on clinic.clinic_id = cu.clinic_id AND (clinic.status = 1 or clinic.status = 2) 
                                   LEFT JOIN patient_subscription AS ps ON u.user_id = ps.user_id 
                                   WHERE u.usertype_id=1 and (u.status = '1' OR u.status = '2') and ((ps.subs_status='1' and subs_end_datetime > now()) or (ps.subs_status='2' and subs_end_datetime > now()))";
                        }
                       
                        
                        
                       $flag=-4; 
                     }
                    if($flag != 0){
                     $result_patient = @mysql_query($sql);
                      if(@mysql_num_rows($result_patient)> 0 ){
                        $data = array(
                        'user_id' => $this->userInfo("user_id"),
                        'sender_type' => $this->value('patient_name_id'),
                        'subject' => $this->encrypt_data($this->value('subject')),
                        'message' => $this->encrypt_data($this->value('content')),
                        'status' => '1',
                        'messageDate' => date('Y-m-d H:i:s',time()),
                        );
                         $this->insert("mass_message",$data);                        
                        }else{
                       $error[] = "There is no patient Associated with EHS ."; 
                      }
                      
                     header("location:index.php?action=therapistEhsPatient&mass=message");
                     exit();
                    }
                    /*else{
                    $data = array(
                        'patient_id' => $this->value('patient_name_id'),
                        'sender_id' => $this->userInfo("user_id"),
                        'subject' => $this->encrypt_data($this->value('subject')),
                        'content' => $this->encrypt_data($this->value('content')),
                        'parent_id' => '0',
                        'sent_date' => date('Y-m-d H:i:s',time()),
                        'recent_date' => date('Y-m-d H:i:s',time()),
                        'replies' => '0'
                    );
                    if($this->insert("message",$data)){
                        $message_id = $this->insert_id();
                        $sqlforAA="select * from therapist_patient where patient_id='{$this->value('patient_name_id')}' and therapist_id='{$this->userInfo('user_id')}' and status=1";
                        $resultAA=@mysql_query($sqlforAA);
                        $numrows=@mysql_num_rows($resultAA);
                        if( $numrows == 0){
                              //echo 'user_id'.$this->value('patient_name_id');
                              //echo '<br>';
                              $usertype=$this->userInfo('usertype_id',$this->value('patient_name_id'));
                              
                              if($usertype==1){
                                $sqludate="update message set aa_message = 1 where message_id=".$message_id;
                                @mysql_query($sqludate);
                              }
                            
                              $data = array(
                                'message_id' => $message_id,
                                'user_id' => $this->userInfo("user_id"),
                                'unread_message' => '2'
                            );    
                            $this->insert("message_user",$data);
                        }else{
                        
                        $query = "select therapist_id from therapist_patient 
                                  where patient_id = '{$this->value('patient_name_id')}' and status = 1";
                        $result = @mysql_query($query);
                      
                        while( $row = @mysql_fetch_array($result)){
                            // Entry for Therapist
                            $data = array(
                                'message_id' => $message_id,
                                'user_id' => $row['therapist_id'],
                                'unread_message' => '1'
                            );
                            
                            if( $row['therapist_id'] == $this->userInfo("user_id") ){
                                $data['unread_message'] = '2';
                               
                            }
                            
                            
                            $this->insert("message_user",$data);
                            
                            /*
                            #####  Message should only go to Patient. ############# 
                                if( $row['therapist_id'] != "" && is_numeric($row['therapist_id']) ){
                                $this->new_post_notification($row['therapist_id']);
                            }*/
                        /*}
                       
                        }
                        
                        // Entry for Patient
                        $data = array(
                                'message_id' => $message_id,
                                'user_id' => $this->value('patient_name_id'),
                                'unread_message' => '1'
                        );
                        $this->insert("message_user",$data);
                        if( $this->value('patient_name_id') != "" && is_numeric($this->value('patient_name_id') )){
                            $this->new_post_notification($this->value('patient_name_id'));
                        }
                        $error[] = "Your message is successfully posted to ".$this->value('patient_name');
                        if( $from_record == '1' ){
                            header("location:index.php?action=therapistViewPatient&id=$patient_name_id");
                            unset($from_record);
                            exit();
                        }
                        header("location:index.php?action=message_listing&sort=sent_date&order=desc");
                        exit();
                    }
                  }*/
                    
                }
                else{
                    $replace['patient_name'] = $this->value('patient_name');
                    $replace['patient_name_id'] = $this->value('patient_name_id');
                    $replace['subject'] = $this->value('subject');
                    $replace['content'] = $this->hyperlink($this->value('content'));        
                }
                $replace['error'] = $this->show_error($error);
                
            }
            $replace['from_record'] = $this->value('from_record');
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar();
            $replace['body'] = $this->build_template($this->get_template("message_compose"),$replace);
            $replace['browser_title'] = "Tx Xchange: Provider Compose New Message";
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
				'sysadmin_link' => $this->sysadmin_link(),
				'therapist_link' => $this->therapist_link()
			);
			
			return $this->build_template($this->get_template("sidebar"),$data);
		}
		/**
		 * Returns the template path.
		 *
		 * @param string $template
		 * @return string
		 * @access public
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

		function message_listing_ehs(){

                $clinicId = $this->clinicInfo('clinic_id');
                //$Ehspatients = $this->getProviderEHSPatients($clinicId);
                if($this->is_corporate($clinicId)==1){
                $Ehspatients= $this->get_paitent_list($clinicId);
            }else{
                $Ehspatients= $this->getProviderEHSPatients($clinicId);
            }
                $totalEhsPatients = count($Ehspatients);
                if($totalEhsPatients == '0') {
                        header("location:index.php?action=therapistEhsPatient&ehsunsub=0");
                } 
            $replace['get_satisfaction'] = $this->get_satisfaction();
            if($this->is_corporate($clinicId)==1){
                $patient=implode(',',$this->get_paitent_list($clinicId));
            }else{
               $patient=implode(',',$this->getProviderEHSPatients());
            }
            //$patient=implode(',',$this->getProviderEHSPatients());
            if($this->value('sort') != ""){
                if($this->value('order') == 'desc' ){
                    $orderby = " order by {$this->value('sort')} desc ";
                }
                else{
                    $orderby = " order by {$this->value('sort')} ";
                }
            }
            else{
                $orderby = " order by from_name ";
            }
            $where = "";            
            if($this->value('patient_name_id') != ""){
                 
                $where = " and m.patient_id = {$this->value('patient_name_id')} and mass_status=0 ";
               
            }else {
                $where = " and mass_patient_id =-4 and  m.patient_id IN (
                     SELECT patient_id FROM therapist_patient WHERE therapist_id in ({$this->getporviderlist()})
                     )  and aa_message=0  or  ((mass_patient_id =-4)) and sender_id in ({$patient}) and
                     m.parent_id = 0 or  ((mass_patient_id =-4)) and patient_id='{{$patient}' and 
                     m.parent_id = 0 or  ((mass_patient_id =-4)) and sender_id='{$this->getporviderlist()}'
                     and aa_message=1 and m.parent_id = 0";
            }
           
            # Added new valiable for counting replies. 13/02/2008
            $replies_count = 0;
            if (($this->value('patient_name_id') == "" && $this->value('sub') != 'Search') || ($this->value('patient_name') == "" && $this->value('sub') == 'Search') || ($this->value('patient_name') != "" && $this->value('sub') == 'Search' && $this->value('patient_name_id') != "")) {
            $privateKey = $this->config['private_key']; 
            //if($this->userInfo('admin_access')==1){//
             $clinicId = $this->clinicInfo('clinic_id');
             if($this->value('patient_name_id') != ""){
                if($this->value('patient_name_id')==-4){
                   $where = " and mass_patient_id =-4 and mass_status=1 and in ({$this->getporviderlist()})";
                 }else{
                }
            }else {
              //$where .= " or ((mass_patient_id !=0 and replies !=0) OR mass_status=1) and sender_id = '{$this->userInfo('user_id')}' and m.parent_id = 0  or  ((mass_patient_id !=0 and replies !=0) OR mass_status=1) and mass_patient_id='{$this->userInfo('user_id')}' and m.parent_id = 0  or  ((mass_patient_id =0)) and sender_id='{$this->userInfo('user_id')}' and m.parent_id = 0 or  ((mass_patient_id =0)) and patient_id='{$this->userInfo('user_id')}' and m.parent_id = 0 or  ((mass_patient_id =0)) and sender_id='{$this->getporviderlist()}' and aa_message=1 and m.parent_id = 0 ";
                 $where = " and mass_patient_id =-4 and mass_status=1 and sender_id in ({$this->getporviderlist()})";          
            }
          //  }
              $query = "SELECT concat(AES_DECRYPT(UNHEX(name_first),'{$privateKey}'),' ',AES_DECRYPT(UNHEX(name_last),'{$privateKey}')) as from_name,
                     AES_DECRYPT(UNHEX(m.subject),'{$this->config['private_key']}') as subject
                      ,m.recent_date AS sent_date,m.patient_id,m.message_id, 
                      replies,
                      m.patient_id,
                      m.mass_patient_id,
                      m.mass_status 
                      FROM message m
                      inner join user u on u.user_id = m.patient_id 
                      WHERE m.parent_id = 0 {$where} 
                      or ((mass_patient_id =-4 and replies !=0)) and sender_id in ({$this->getporviderlist()}) and m.message_id not in (select message_id from system_message where user_id IN({$this->getporviderlist()})) 
                      {$orderby}"; 

            $sqlcount = "SELECT count(1) 
                      FROM message m
                      inner join user u on u.user_id = m.patient_id 
                      WHERE m.parent_id = 0 {$where} 
                      or ((mass_patient_id =-4 and replies !=0)) and sender_id in ({$this->getporviderlist()}) and m.message_id not in (select message_id from system_message where user_id IN({$this->getporviderlist()}))";        
                      //exit;
             
            $replies_count = $this->total_replies($query);
            
            $link = $this->pagination($rows = 0,$query,$this->value('message_listing'),$search,'','','',$sqlcount);             
            $replace['link'] = $link['nav'];            
            $result = $link['result'];          
            $from_name='';
            while($row = @mysql_fetch_array($result)){
               
                $check=$this->check_therapist($row['patient_id']);
                $usertype=$this->userInfo('usertype_id',$row['patient_id']);
                if($check==1){
                    if($usertype==1)
                        $from_name="<a href='index.php?action=therapistViewPatient&id={$row['patient_id']}'>".$this->unread_message($row['message_id'],$row['from_name'])."</a>";
                    else
                       $from_name="<a href='index.php?action=therapistViewPatient&id={$row['patient_id']}'>".$this->unread_message($row['message_id'],$row['from_name']).' (Staff)'."</a>"; 
                }else{
                   if($usertype==1)
                   $from_name="<span style='color:black'>".$this->unread_message($row['message_id'],$row['from_name'])."</span>";
                   else
                    $from_name="<span style='color:black'>".$this->unread_message($row['message_id'],$row['from_name']).' (Staff)'."</span>";
                }
                if($row['mass_status']==1){
                    if($row['mass_patient_id']== -1){
                      $from_name="<span style='color:black'>".'All Current Patients'."</span>";  
                    }elseif($row['mass_patient_id']== -2){
                      $from_name="<span style='color:black'>".'All Discharged Patients'."</span>";  
                        
                    }elseif($row['mass_patient_id']== -3){
                      $from_name="<span style='color:black'>".'All Patients'."</span>";  
                    }
                elseif($row['mass_patient_id']== -4){
                      $from_name="<span style='color:black'>".'All EHS Patients'."</span>";  
                    }
                }
                $data = array(
                    'style' => ($c++%2)?"line1":"line2",                
                    'from_name' => $from_name,
                    'subject' => "<a href='index.php?action=set_unread_message_ehs&message_id={$row['message_id']}'>".$this->unread_message($row['message_id'],$row['subject'])."</a>",
                    'sent_date' => "<a href='index.php?action=set_unread_message_ehs&message_id={$row['message_id']}'>".$this->unread_message($row['message_id'],$this->formatDate($row['sent_date']))."</a>", 
                    'replies' => $row['replies'] 
                );
                
                $replace['message_list_record'] .= $this->build_template($this->get_template("message_list_record"),$data);
        
            }
        }else {
            $replace['message_list_record'] = '<tr><td colspan="3">No record found</td></tr>';
        }
            // list of patients associated with Therapist. 
                $replace['patient_name_id'] = $this->value('patient_name_id');
                $replace['patient_name'] = $this->value('patient_name');
            // End
             
            
            // Head of list
            if( $this->value('patient_name_id') != "" ){
                $where = " and  patient_id = {$this->value('patient_name_id')}";
            }
            
            include_once("template/message/message_array.php");
            $query_string = array();
            $query_string['patient_name_id'] = $this->value('patient_name_id');
            $query_string['patient_name'] = $this->value('patient_name');
            $query_string['sub'] = $this->value('sub');
            $head_arr = $this->table_heading($message_list_head,"from_name",$query_string);
            $head_arr['replies'] = $replies_count;
            
            $replace['message_list_head'] = $this->build_template($this->get_template("message_list_head"),$head_arr);
            // End
            if($this->value('mass')=='mass')
            $replace['mass_message_message']="Your message has been queued for processing. Once it is delivered , you will receive an email asking you to log back in and check this page for the results. This process could take a from a few minutes to a few hours.<tr><td>&nbsp;</td></tr>";
            
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar();
            $replace['body'] = $this->build_template($this->get_template("message_listing"),$replace);
            $replace['browser_title'] = "Tx Xchange: Provider Message Center";
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
		
	}
	$obj = new messageTherapist();
?>
