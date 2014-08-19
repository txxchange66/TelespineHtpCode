<?php
	/**
	 * 
	 * Copyright (c) 2008 Tx Xchange.
	 * 
	 * Class for Patient messaging.
	 * 
	 * Neccessary class for pagination.
	 * require_once("include/paging/my_pagina_class.php");
	 * 
	 * Neccessary class for getting access of application specific methods.
	 * require_once("module/application.class.php");
	 */
	require_once("include/paging/my_pagina_class.php");
	require_once("module/application.class.php");
  	class messagePatient extends application{
		
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
			/**
				* Checking action parameter, weather its hold any value or not. 
		 		* If it is not holding any value we are assigning default value in it.
			 */
			if($this->value('action')){
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
         * This function gets the template path from xml file.
         *
         * @param clinic id .
         * @return scheduling link
         * @access private
         */
        function scheduling($clinic_id){
            $sql="select scheduling from addon_services where clinic_id='{$clinic_id}'";
            $query=$this->execute_query($sql);
            $num=$this->num_rows($query);
            if($num>0){
                $result=$this->fetch_object($query);
                if($result->scheduling==1){
                    $sql1="select * from scheduling where clinic_id='{$clinic_id}'";
                    $query1=$this->execute_query($sql1);
                    $num1=$this->num_rows($query1);
                    if($num1>0){
                        $result1=$this->fetch_object($query1);
                        if($result1->schedulUrl!=''){
                           return $link="<li style='padding-top:13px;'><a style='font-size:12px; margin-right:-5px; font-weight:bold; display:block; line-height:15px;' href=".$result1->schedulUrl." target='_blank'><img src='images/icon-calendar-tx.jpg' align='left' style='margin-right:5px;'></a><a style='font-size:12px; margin-right:-5px; font-weight:bold; display:block; line-height:15px;' href=".$result1->schedulUrl." target='_blank'><span style='display:block; padding-top:1px;color:green'>Schedule Appointment</span></a><div style='clear:both;'></div></li>";
                        }
                     }   
                return false;
            }
            }
            
        }
		
		
		/**
		 * This method is used to post any new message to associated Therapist.
		 * @param none
		 * @return none
		 * @access public
		 */
		function patient_compose_message(){
			
			$replace['to'] = "";
			$replace['subject'] = "";
			$replace['content'] = "";
			if( $this->value('action_submit') == "submit" ){
				
				$error = array();
				
				if( $this->value('to') == "" ){
					$error[] = "You can not compose new message. You are not associated with any Provider.";
				}
				if( $this->value('subject') == "" ){
					$error[] = "Please enter subject";
				}
				if( $this->value('content') == "" ){
					$error[] = "Please enter message";
				}
				
                if( is_array($error) && sizeof($error) == 0 ){
				 	$data = array(
				 		'patient_id' => $this->userInfo("user_id"),
				 		'sender_id' => $this->userInfo("user_id"),
				 		'subject' => $this->encrypt_data($this->value('subject')),
				 		'content' => $this->encrypt_data($this->value('content')),
				 		'parent_id' => '0',
				 		'sent_date' => date('Y-m-d H:i:s',time()),
                                                'recent_date' => date('Y-m-d H:i:s',time()),
                                                'replies' => '0'
				 	);
				 	$sendmail='no';
				 	$clinicId = $this->clinicInfo('clinic_id'); 
				 	$sql_addon="select patient_email_notification from addon_services where clinic_id=".$clinicId;
                        $query_addon=$this->execute_query($sql_addon);
                        if($this->num_rows($query_addon)>0) {
                            $rowaddon=$this->fetch_array($query_addon);
                            //print_r($rowaddon);
                            //die;
                            if($rowaddon[0]['patient_email_notification']==1){
                            	$sendmail='yes';
                            }
                        }
				 	
				 	if($this->insert("message",$data)){
				 		$message_id = $this->insert_id();
				 		$query = "select therapist_id from therapist_patient 
				 				  where patient_id = '{$this->userInfo("user_id")}' and status = 1 ";
				 		$result = @mysql_query($query);
				 		while( $row = @mysql_fetch_array($result)){
				 			// Entry for Therapist
				 			$data = array(
				 				'message_id' => $message_id,
				 				'user_id' => $row['therapist_id'],
				 				'unread_message' => '1'
				 			);
				 			$this->insert("message_user",$data);
				 			
				 			
				 			if( $row['therapist_id'] != "" && is_numeric($row['therapist_id']) ){
				 				if($sendmail=='yes')
				 				$this->new_post_notification($row['therapist_id']);
				 			}
				 			
				 		}
				 		
				 		// Entry for Patient
				 		$data = array(
				 				'message_id' => $message_id,
				 				'user_id' => $this->userInfo("user_id"),
				 				'unread_message' => '2'
				 		);
				 		$this->insert("message_user",$data);
				 		
				 		/*
					 		### notification mail to recepiant ###
					 		if( $this->userInfo("user_id") != "" && is_numeric($this->userInfo("user_id") )){
					 			$this->new_post_notification($this->userInfo("user_id"));
					 		}
				 		*/
				 		$error[] = "Your message is successfully posted to ".$this->value('patient_name');
				 		header("location:index.php?action=patient");
				 		exit();
				 	}	
				}
				else{
					
					$replace['subject'] = $this->value('subject');
					$replace['content'] = $this->hyperlink($this->value('content'));		
				}
				$replace['error'] = $this->show_error($error);
				
			} 
	
			$replace['therapist_name'] = $this->list_therapist();
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();
			$replace['body'] = $this->build_template($this->get_template("message_compose"),$replace);
 
            //Personalized GUI
            $LabelValue=$_SESSION['patientLabel']['Messages']; 
            $replace['headingMessages']=strtoupper($LabelValue);
             $replace['scheduling'] = $this->scheduling($this->clinicInfo('clinic_id'));
			$replace['browser_title'] = "Tx Xchange: Patient Compose New Message";
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}
		/**
		 * This function formats the E-mail and send it to user, using php mail function.
		 *
		 * @param numeric $user_id
		 * @return none
		 * @access public
		 */
		function new_post_notification( $user_id = "" ){
			
			if( $user_id != "" ){
							
				        $data = array(
						'url' => $this->config['url'],
						'images_url' => $this->config['images_url']
						);
				
					
				$to = $this->userInfo('username',$user_id);

				//$from = $this->userInfo("username");
                                 $clinic_channel=$this->getchannel($this->get_clinic_info($user_id,'clinic_id'));
                                 $message='';
                                 if($clinic_channel ==2){
                                 	 $data = array(
													'url' => $this->config['url'],
													'images_url' => $this->config['images_url'],
													'support'=>	'Wholemedx'			
                                 	 );
                                 	
                                 }else{
                                 	$data = array(
													'url' => $this->config['url'],
													'images_url' => $this->config['images_url'],
                                 					'support'=>	'Tx Xchange'
													);
                                 }
                                 
                                 $headers  = 'MIME-Version: 1.0' . "\n";
								$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                                 if($clinic_channel==2){
                                        $business_url=$this->config['business_wx']; 
                                        $support_email=$this->config['email_wx']; 
                                        $message = $this->build_template("mail_content/wx/new_message_patient.php",$data);                               
                                    }else{
                                        $business_url=$this->config['business_tx']; 
                                        $support_email=$this->config['email_tx'];
                                        $message = $this->build_template("mail_content/new_message_patient.php",$data);   
                                    }
                                if( $clinic_channel == 2){
                                        $headers .= "From: ".$this->config['email_wx']. "\n";
										$returnpath = '-f'.$this->config['email_wx'];  	                               
                                        }else{
										$headers .= "From: ".$this->config['email_tx']. "\n";
										$returnpath = "-f".$this->config['email_tx'];
								}       
				$subject = "A patient or client has sent you a message";
				
				// To send HTML mail, the Content-type header must be set
				
				
                // Mail it
				@mail($to, $subject, $message, $headers, $returnpath);
			}
			
		}
		/**
		 * This function returns the list of associated Therapist with any patient.
		 * 
		 * @param none
		 * @return string
		 * @access public
		 */
		function list_therapist(){
			$query = "select therapist_id from therapist_patient where patient_id = '{$this->userInfo('user_id')}' and status = 1 ";
			$result = @mysql_query($query);
			$tempArray = array();
			if( @mysql_num_rows($result) > 0 ){
				while( $row = @mysql_fetch_array($result)){
					$tempArray[] = $this->get_field($row['therapist_id'],"user","name_first")." ".$this->get_field($row['therapist_id'],"user","name_last");
					#$str .= "".$this->get_field($row['therapist_id'],"user","name_first").""; 	
					#$str .= " ".$this->get_field($row['therapist_id'],"user","name_last").","; 	
				}
				//print_r($tempArray);
				$str = implode(',&nbsp;',$tempArray);
				return $str;
				
			}
			return "";
		}
                
    /**
     * This functon shows list of messages for patient.
     * 
     * @param none
     * @return none
     * @access public
     */
    function patient_message_listing()
    {
        if($this->value('sort') != "")
        {
            if($this->value('order') == 'desc' )
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
        $privateKey = $this->config['private_key'];
        $query = "SELECT concat(AES_DECRYPT(UNHEX(name_first),'{$privateKey}'),' ',AES_DECRYPT(UNHEX(name_last),'{$privateKey}')) as from_name,m.subject
          ,m.recent_date AS sent_date,m.patient_id,m.message_id, 
                              replies
                              FROM message m
                              inner join user u on u.user_id = m.sender_id  
                              WHERE m.patient_id = '{$this->userInfo('user_id')}' and m.parent_id = 0  and m.sent_visible='1' and m.mass_status!=1
          and m.message_id not in (select message_id from system_message where user_id = '{$this->userInfo('user_id')}')
           {$orderby}";

        $replies_count = $this->total_replies($query);
        $link = $this->pagination($rows = 0,$query,$this->value('message_listing'),$this->value('search'),'');				
        $replace['link'] = $link['nav'];
        $result = $link['result'];
        # Added new valiable for counting replies. 13/02/2008

        while($row = @mysql_fetch_array($result))
        {
            $data = array(
                //'style' => ($c++%2)?"line1":"line2",				
                'from_name' => "<a href='index.php?action=patient_set_unread_message&message_id={$row['message_id']}'>".$this->unread_message($row['message_id'],$row['from_name'])."</a>",
                'subject' => "<a href='index.php?action=patient_set_unread_message&message_id={$row['message_id']}'>".$this->unread_message($row['message_id'],$this->decrypt_data($row['subject']))."</a>",
                'sent_date' => "<a href='index.php?action=patient_set_unread_message&message_id={$row['message_id']}'>".$this->unread_message($row['message_id'],$this->formatDateExtra($row['sent_date'], $this->userInfo('timezone')))."</a>", 
                'replies' => $row['replies']
            );
            $replace['message_list_record'] .= $this->build_template($this->get_template("message_list_record"),$data);
        }


        // Head of list

        $query = "select count(*) from message_user 
                                    where  user_id = '{$this->userInfo('user_id')}' ";
        $result = @mysql_query($query);
        $row = @mysql_fetch_array($result);
        include_once("template/patient_message/message_array.php");
        $head_arr = $this->table_heading($message_list_head,"from_name");
        $head_arr['replies'] = $replies_count;
        $replace['message_list_head'] = $this->build_template($this->get_template("message_list_head"),$head_arr);
        // End

        $replace['header'] = $this->build_template($this->get_template("header"));
        $replace['sidebar'] = $this->sidebar();
        $replace['body'] = $this->build_template($this->get_template("message_listing"),$replace);


        //Personalized GUI
        $LabelValue=$_SESSION['patientLabel']['Messages'];
        $replace['headingMessages']=strtoupper($LabelValue);
        $replace['intakeform'] ='';
        //echo $_SESSION['intakepaper'];
        //if($urlStr=="")
        {
            //if($_SESSION['intakepaper']!=='assign')
            {
                $sqlintake="select * from patient_intake where p_user_id= ".$this->userInfo('user_id');
                $resultintake=$this->execute_query($sqlintake);
                if($this->num_rows($resultintake)>0)
                {
                    $rowintake=$this->fetch_array($resultintake);
                    if($rowintake['intake_compl_status']==0)
                    {
                        if($rowintake['intake_last_page']=='')
                            $pagenumber=0;
                        else 
                            $pagenumber=$rowintake['intake_last_page'];
                        $replace['intakeform'] ="<script>GB_showCenter('Intake Paperwork', '/index.php?action=fillintakepaperwork',720,960);</script>";                         
                        $_SESSION['intakepaper']='assign';
                    }
                }
                else
                {
                    $_SESSION['intakepaper']='assign';    
                }
            }
        }

        $replace['scheduling'] = $this->scheduling($this->clinicInfo('clinic_id'));

        $replace['browser_title'] = "Tx Xchange: Patient ".$LabelValue." Center";
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
    function unread_message($message_id,$subject)
    {
        $query = "select count(*) from message_user 
                              where message_id = '{$message_id}' 
                              and user_id = '{$this->userinfo('user_id')}' 
                              and unread_message = '1' ";
        $result = @mysql_query($query);
        if( @mysql_num_rows($result) > 0 )
        {
            if( $row = @mysql_fetch_array($result))
            {
                if($row[0] > 0)
                {
                    $subject = "<b>".$subject."</b>";
                    return $subject;
                }
                else
                {
                    return $subject;
                }
            }
            else
            {
                return $subject;
            }
        }
        else
        {
            return $subject;
        }
    }
		
   /**
    * Used to show main message and reply message. 
    * 
    * @return none
    * @access public
    */
    function patient_view_message()
    {
        $replace['current_date'] = date('m-d-Y H:i:s');
        $replace['header'] = $this->build_template($this->get_template("header"));
        $replace['sidebar'] = $this->sidebar();

        // submit reply
        if( $this->value('message_id') != "" )
        {
            if( $this->value('submit_action') == "reply_message" )
            {
                $error = array();
                if( $this->value('content') == "" )
                {
                    $error[] = "Please enter your reply";
                }

                if( is_array($error) && count($error) == 0 )
                {
                    $this->send_reply();
                    //header("location:index.php?action=patient_view_message&message_id={$this->value('message_id')}");
                    header("location:index.php?action=patient");
                    exit();
                }
            }
        }
        // End 


        // Show main message or main thread
        if( $this->value('message_id') != "" )
        {
            $data = $this->main_message($this->value('message_id'));
            //print_r($data); 
            $replace['main_message'] = $this->build_template($this->get_template("main_message"), $data);	            	
        }
        // End

        // reply message
        if( $this->value('message_id') != "" )
        {
            $replace['reply_message'] = $this->reply_message($this->value('message_id'));	            	
        }
        // end 

        $replace['error'] = $this->show_error($error);
        $replace['message_id'] = $this->value('message_id');
        $replace['body'] = $this->build_template($this->get_template("view_message"), $replace);

        //Personalized GUI
        $LabelValue=$_SESSION['patientLabel']['Messages'];
        $replace['headingMessages']=strtoupper($LabelValue);            

        $replace['browser_title'] = "Tx Xchange: Patient Compose New Message";
        $this->output = $this->build_template($this->get_template("main"), $replace);                      
    }
    
        /**
         * Changes the status of message to read in database.
         * @param none
         * @return none
         * @access public
         */
        function patient_set_unread_message(){
        	if( $this->value('message_id') != "" ){
        		$data = array(
        			'unread_message' => 2
        		);
        		
        		$this->update("message_user",$data, " message_id = '{$this->value('message_id')}' and user_id = '{$this->userInfo('user_id')}' ");
        	}
        	header("location:index.php?action=patient_view_message&message_id={$this->value('message_id')}");
        	exit();
        }
    /**
     * Returns list of reply for any post.
     *
     * @param numeric $message_id
     * @return string
     * @access public
     */
    function reply_message($message_id)
    {
        $query = "select * from message where parent_id = '{$message_id}' order by sent_date asc";
        $result = @mysql_query($query);
        while($row = @mysql_fetch_array($result))
        {
            // Sender Id retrived from message table.
            $sender_id = $this->get_field($row['message_id'], "message", "sender_id");
            // Therapist or Patient name retrive from user table.
            if($sender_id != "")
            {
                $data['name_first'] = $this->get_field($sender_id, "user", "name_first");
                $data['name_last'] = $this->get_field($sender_id, "user", "name_last");
            }
            $data['sent_date'] = $this->formatDateExtra($row['sent_date'], $this->userInfo('timezone'));
            $data['content'] = $this->hyperlink(html_entity_decode(nl2br(str_replace("&amp;", "&", $this->decrypt_data($row['content'])))));
            $reply_message .= $this->build_template($this->get_template("reply_message"), $data);
        }
        return $reply_message;
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
         * 1) validate the reply.
         * 2) After successful validation result.
         * 3) Finaly, insert the reply in message and message user table.
         *
         *  @param none
         *  @return none
         *  @access public
         */
        function send_reply(){
        			$sendmail='no';
				 	$clinicId = $this->clinicInfo('clinic_id'); 
				 	$sql_addon="select patient_email_notification from addon_services where clinic_id=".$clinicId;
                        $query_addon=$this->execute_query($sql_addon);
                        if($this->num_rows($query_addon)>0) {
                            $rowaddon=$this->fetch_array($query_addon);
                            //print_r($rowaddon);
                            //die;
                            if($rowaddon[0]['patient_email_notification']==1){
                            	$sendmail='yes';
                            }
                        }
        		$data = array(
    				'subject' => 'reply',	
    				'content' => $this->encrypt_data($this->value('content')),
    				'patient_id' => $this->get_field($this->value('message_id'),"message","patient_id"),
    				'parent_id' => $this->value('message_id'),
    				'sender_id' => $this->userInfo('user_id'),
    				'sent_date' => date('Y-m-d H:i:s'),
    			);
    			
    			if($this->insert("message",$data)){
    				// Add record for therapist in message user table.
    				$new_message_id = $this->insert_id();
                    
                    // Update recent_date and replies column in message table.
                    $arr_message = array(
                        'recent_date' => $data['sent_date'],
                        'replies' => "'{$this->num_replies($this->value('message_id'))}'"
                    );
                    //print_r($arr_message);
                                        
                    //$where = " message_id = '{$this->value('message_id')}' ";
                    $query = " update message set recent_date = '{$data['sent_date']}', 
                    replies = '{$this->num_replies($this->value('message_id'))}' 
                    where message_id = '{$this->value('message_id')}' ";
                    @mysql_query($query);
                    $sql="select * from message where message_id='{$this->value('message_id')}'";
                    $sql_res=@mysql_query($sql);
                    $sql_row = @mysql_fetch_array($sql_res);
                    
                    if($sql_row['mass_patient_id']!=0){
                     $update_message="update message set mass_patient_id=".$sql_row['sender_id']." where message_id=".$this->value('message_id');   
                    // @mysql_query($update_message);                     
                     }
                    
                    if($sql_row['mass_patient_id']==0){
                     $flag1=0;
                     if($this->get_field($this->value('message_id'),"message","patient_id")!=$this->get_field($this->value('message_id'),"message","sender_id")){
                     $flag1=1;
                     }
                    $sqlAA="select * from therapist_patient where patient_id = '{$this->get_field($this->value('message_id'),"message","patient_id")}' and therapist_id='{$this->get_field($this->value('message_id'),"message","sender_id")}'";
                    $resultAA=@mysql_query($sqlAA);
                    if(@mysql_num_rows($resultAA)==0 and $flag1==1){
                      $data = array(
			 				'message_id' => $this->value('message_id'),
			 				'user_id' => $sql_row['sender_id'],
			 				'unread_message' => '1'
			 			);
			 			$this->insert("message_user",$data); 
                    if( $row['therapist_id'] != "" && is_numeric($row['therapist_id']) ){
				 				if($sendmail=='yes')
				 				$this->new_post_notification($row['therapist_id']);
				 			} 
                    }else{
                    
    				$query = "select therapist_id from therapist_patient 
		 				  where patient_id = '{$this->get_field($this->value('message_id'),"message","patient_id")}' 
		 				  and status = 1 ";
		 			$result = @mysql_query($query);
		 			$status=0;
                     while( $row = @mysql_fetch_array($result)){
			 			// Entry for Therapist
			 			
                         $data = array(
			 				'message_id' => $this->value('message_id'),
			 				'user_id' => $row['therapist_id'],
			 				'unread_message' => '1'
			 			);
			 			$this->insert("message_user",$data);
                        if($sql_row['sender_id']==$row['therapist_id']){
                            $status=1;
                        }
                        if($sql_row['sender_id']==$this->userInfo('user_id')){
                            $status=1;
                        }
                     if( $row['therapist_id'] != "" && is_numeric($row['therapist_id']) ){
				 				if($sendmail=='yes')
				 				$this->new_post_notification($row['therapist_id']);
				 			}
                        // Delete user_id from system_message table to show the reply.
                        $message_id = $this->value('message_id');
                        $therapist_id = $row['therapist_id'];
                        $query = "delete from system_message where message_id = '{$message_id}' and user_id = '{$therapist_id}' ";
                        @mysql_query($query);
		 			}
                    }
                   // echo $status;die;
                                                                
                    }else{
                       $data = array(
			 				'message_id' => $this->value('message_id'),
			 				'user_id' => $sql_row['sender_id'],
			 				'unread_message' => '1'
			 			);
			 			$this->insert("message_user",$data); 
                        
                    }
		 			
                     
                     // End 
		 			
		 			// Add record for patient in message user table.
		 			$data = array(
		 				'message_id' => $this->value('message_id'),
		 				'user_id' => $this->get_field($this->value('message_id'),"message","patient_id"),
		 				'unread_message' => '2'
		 			);
		 			$this->insert("message_user",$data);
		 			// End
    			}
        }
        
    /**
     * This function populates an associated array with required information for sending an email.
     *
     * @param stirng $message_id
     * @return array
     * @access public
     */
    function main_message($message_id)
    {
        // Sender Id retrived from message table.
        $sender_id = $this->get_field($this->value('message_id'), "message", "sender_id");
        // Therapist or Patient name retrive from user table.
        //get mass message value
        $mass_message = $this->get_field($this->value('message_id'), "message", "mass_message");
        if($mass_message == 0)
        {
            if($sender_id != "")
            {
                $replace['name_first'] = $this->get_field($sender_id, "user", "name_first");
                $replace['name_last'] = $this->get_field($sender_id, "user", "name_last");
            }
        }
        else
        {
            if($this->get_field($this->value('message_id'), "message", "mass_patient_id") == -4)
            {
                $replace['name_first'] = $this->get_field($sender_id, "user", "name_first");
                $replace['name_last'] = $this->get_field($sender_id, "user", "name_last");
            }
            else
            {
                $clinic_name = $this->get_field($mass_message, "clinic", "clinic_name");
                $replace['clinic_name'] = $clinic_name;
                $replace['unsubscribe_message'] = "To unsubscribe, please visit your Preferences page.";
            }
        }
        $replace['sent_date'] = $this->formatDateExtra($this->get_field($this->value('message_id'), "message", "sent_date"), $this->userInfo('timezone'));
        $replace['subject'] = $this->decrypt_data($this->get_field($this->value('message_id'), "message", "subject"));
        $replace['content'] = $this->hyperlink(html_entity_decode(nl2br(str_replace("&amp;", "&", $this->decrypt_data($this->get_field($this->value('message_id'), "message", "content"))))));
        return $replace;
    }
        
        /**
         * To show the left navigation panel.
         *
         * @return string
         * @access public
         */
		function sidebar(){
               	$userInfo = $this->userInfo();
	$data = array(
			// Personalized GUI
			'bulletin_board' => $this->bulletin_message(),
			'referral_link' => $this->referral_link(),
			'scheduling'=>$this->scheduling($this->clinicInfo('clinic_id')),
			'userName'=> $userInfo['name_first'],
                        'message'=>'<li >
        <a href="index.php?action=patient_message_listing&sort=sent_date&order=desc" >
        <span style="font-size:14px;">'.$_SESSION['patientLabel']['Messages'].'('.$this->num_messages().')</span>
        </a>
      </li>',
                        'teleconsultation'=>'<li >
        <a href="javascript:void(0);" onclick="mypopup();" >
        <span style="font-size:14px;">Teleconsultation</span>
        </a>
      </li>'
	);
        $checkservice=  $this->fetch_all_rows($this->execute_query("select * from addon_services where clinic_id={$this->get_clinic_info($this->userInfo('user_id'))}"));
        //$this->printR($checkservice,$this->get_clinic_info($this->userInfo('user_id')));
         if($checkservice[0]['message']=='1')
          unset($data['message']);   
         if($checkservice[0]['teleconference']=='1')    
          unset($data['teleconsultation']);
           //$this->printR($data);
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
                /**
	 * Function returns bulletin message for current clinic.
	 */

	function bulletin_message(){

		if($this->value('action') != 'patient' ){
			return "";
		}

		// Retrive bulletin message from database.
		if( $this->clinicInfo("clinic_id") != ""  ){

			$query = "select * from bulletin_board where clinic_id = '{$this->clinicInfo("clinic_id")}' ";
			$result = @mysql_query($query);
			if( $row = @mysql_fetch_array($result)  ){
				$data = array(
						//'message' => $this->replace_link_hyperlink($row['message'])
						'message' => htmlspecialchars_decode($row['message']) // row changed by MOHIT SHARMA
				);

				$data['message'] = $this->replace_link_hyperlink($data['message']); // row changed by Manu;
				//$data['message'] = wordwrap( $data['message'] , 19 ,"\n",true);
				$bulletin_board = $this->build_template($this->get_template("bulletin_board"),$data);
				return $bulletin_board;
			}
		}

		$bulletin_board = $this->build_template($this->get_template("bulletin_board"));
		return $bulletin_board;
	}

	}
	$obj = new messagePatient();
?>
