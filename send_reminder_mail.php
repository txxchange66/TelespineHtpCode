<?php
	require_once("config.php"); 
	$host = $txxchange_config['dbconfig']['db_host_name'];
	$user = $txxchange_config['dbconfig']['db_user_name'];
	$pass = $txxchange_config['dbconfig']['db_password'];
	$db   = $txxchange_config['dbconfig']['db_name'];
        $telespine_id=$txxchange_config['telespineid'];
	$application_path   = $txxchange_config['application_path'];
        $privatekey = $txxchange_config['private_key'];
        $loginurl=$txxchange_config['telespine_login'];
	// Make connection with server.
	$link = @mysql_connect($host,$user,$pass);
	
	// select database.
	@mysql_select_db($db,$link);

	// Get current date.
	$date = $txxchange_config['configdate'];
	
	// Retrive records for sending reminder.
	$query = "select * from reminder_schedule where date(scheduled_date) = '{$date}' and status = 'pending' ";
	
	// Fetch rows from resultset.
	$result = @mysql_query($query);
	while( $row = @mysql_fetch_array($result) ){
		send_reminder($row['patient_id'],$row['reminder_schedule_id'],$row['reminder_set'],$row['reminderEhsFlag'],$row['assignday']);
	}
	/**
	 * Send emails for reminder notification.
	 *
	 * @param numeric $user_id
	 * @param numeric $reminder_schedule_id
	 */
	function send_reminder( $user_id = "", $reminder_schedule_id,$reminder_set,$reminderEhsFlag='0',$assignday='0' ){
		global $txxchange_config;
		global $application_path;
                global $privatekey;
                global $telespine_id; 
                global $loginurl;
			if( $user_id != "" ){
				if($reminderEhsFlag==2){
                                    
                                    $reminder = get_reminder_automaticschedule($user_id,$reminder_set,$reminderEhsFlag,$assignday);
                                }else{
                                 
                                    $reminder = get_reminder($user_id,$reminder_set);
                                }
                                
			if(get_clinic_info($user_id,"clinic_id")==$telespine_id){
                                
                                /*$business_url=$txxchange_config['business_telespine']; 
                                $support_email=$txxchange_config['email_telespine'];
                              if( $reminder != "" ){
                                    $fullname=get_username("name_first",$user_id).' '.get_username("name_last",$user_id);	
                                    $data = array(
							'url' => $txxchange_config['url'],
							'images_url' => $txxchange_config['images_url'],
							'reminder' => $reminder,
                                                        'fullname'=>$fullname,
                                                        'loginurl'=>$loginurl
							);
			
                                	$message = build_template( $application_path."mail_content/telespine/reminder.php",$data);
                                        
	                      		$to = $fullname.'<'.get_username("username",$user_id).'>';
					$subject = "Telespine Update - Today's Reminder(s)";
					// To send HTML mail, the Content-type header must be set
					$headers  = 'MIME-Version: 1.0' . "\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
					//echo $message;
					// Additional headers
					$headers .= "From: Telespine Support <support@telespine.com>" . "\n";
					$returnpath = '-fsupport@telespine.com';
					// Mail it
					if(mail($to, $subject, $message, $headers,$returnpath) == '1' ){
						$query = "update reminder_schedule  set status = 'sent' where reminder_schedule_id = '{$reminder_schedule_id}' ";
						@mysql_query($query);
					}
					else{
						$query = "update reminder_schedule  set status = 'failed' where reminder_schedule_id = '{$reminder_schedule_id}' ";
						@mysql_query($query);
					}
				}
				else{
					$query = "update reminder_schedule  set status = 'no-reminder' where reminder_schedule_id = '{$reminder_schedule_id}' ";
					@mysql_query($query);
				}*/
                            
                                
                        }else {
                                $clinicName	=	get_clinic_info($user_id,"clinic_name");
				$clinic_channel=        getchannel(get_clinic_info($user_id));
                                if($clinic_channel==1){
                                        $business_url=$txxchange_config['business_tx']; 
                                        $support_email=$txxchange_config['email_tx'];
                                }else{
                                        $business_url=$txxchange_config['business_wx']; 
                                        $support_email=$txxchange_config['email_wx'];   
                                }
				if( $reminder != "" ){
					$data = array(
							'url' => $txxchange_config['url'],
							'images_url' => $txxchange_config['images_url'],
							'reminder' => $reminder,
							'clinicName' => $clinicName,
                                                        'business_url'=>$business_url,
                                                        'support_email'=>$support_email
							);
					
	                $clinic_type = getUserClinicType($user_id);
	               
                    if( $clinic_channel == 1){
	                	$message = build_template( $application_path."mail_content/plpto/send_reminder_plpto.php",$data);
	                }
	                else{
	                	$message = build_template( $application_path."mail_content/wx/send_reminder_plpto.php",$data);	
	                }		
							
					$to = get_username("username",$user_id);
					$subject = "Reminder from your Provider at ".$clinicName;
					// To send HTML mail, the Content-type header must be set
					$headers  = 'MIME-Version: 1.0' . "\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
					//echo $message;
					// Additional headers
					$headers .= "From: " .setmailheader($clinicName). "<do-not-reply@txxchange.com>" . "\n";
					$returnpath = '-fdo-not-reply@txxchange.com';
					// Mail it
					if(mail($to, $subject, $message, $headers,$returnpath) == '1' ){
						$query = "update reminder_schedule  set status = 'sent' where reminder_schedule_id = '{$reminder_schedule_id}' ";
						@mysql_query($query);
					}
					else{
						$query = "update reminder_schedule  set status = 'failed' where reminder_schedule_id = '{$reminder_schedule_id}' ";
						@mysql_query($query);
					}
				}
				else{
					$query = "update reminder_schedule  set status = 'no-reminder' where reminder_schedule_id = '{$reminder_schedule_id}' ";
					@mysql_query($query);
				}
                                
                        } 
                                
                                
                                
			}
	}
	/**
         * This function used to get mail form or subject encoding 
         * @param string $message
         * return string $message
         */
     function setmailheader($str){
         $str="=?UTF-8?B?".base64_encode($str)."?=";
         return $str;
    }
	function getUserClinicType($user_id){
        	if( is_numeric($user_id) ){
	            $query = " select clinic_service.service_name from clinic_service JOIN clinic_user ON clinic_service.clinic_id=clinic_user.clinic_id where user_id = '{$user_id}'";
	            $result = @mysql_query($query);
	            if( $row = @mysql_fetch_array($result) ){
	            	$serviceName = $row['service_name'];
	            }
	            return $serviceName;
	        }
        	return ""; 
    }
	/**
	 * Return username of patient.
	 *
	 * @param numeric $user_id
	 */
	function get_username($field_name,$user_id){
        global $privatekey;
		$query = "select $field_name from user where user_id = '{$user_id}' ";
		$result = @mysql_query($query);
		while( $row = @mysql_fetch_array($result)){
            $encrypt_field = array('name_title','name_first','name_last','password','address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
            if( in_array($field_name, $encrypt_field) ){
                $row[$field_name] = decrypt_data($row[$field_name],$privatekey);
                return $row[$field_name];
            }       
			return $row[$field_name];
		}
		return "";
		
	}
    /**
    * This function Decrypts data passed to it and returns it back.
    */
    function decrypt_data($data,$privateKey){
              $query = "select AES_DECRYPT(UNHEX('{$data}'),'{$privateKey}')";
              $result = @mysql_query($query);
              if ($result) {
                return @mysql_result($result, 0);
              }
              return "";
    }
	/**
	 * Retrive reminders for the patient.
	 * 
	 * @param $user_id
	 */
	function get_reminder($user_id = "",$reminder_set=1){
        global $privatekey;
		if($user_id != ""){
			$query = "select * from patient_reminder where patient_id = '{$user_id}' and reminder_set='{$reminder_set}' and assignday IS NULL";
			$result = @mysql_query($query);
			if(@mysql_num_rows($result) > 0){
				$reminder = "<ul>";
				while( $row = @mysql_fetch_array($result) ){
					$reminder .= "<li>" . decrypt_data($row['reminder'],$privatekey) . "</li>";
				}
				$reminder .= "</ul>";
				return $reminder;
			}
			return "";
		}
		return "";
	}
        
        function get_reminder_automaticschedule($user_id,$reminder_set,$reminderEhsFlag,$assignday){
            global $privatekey;	
            if($user_id != ""){
			$query = "select * from patient_reminder where patient_id = '{$user_id}' and reminder_set='{$reminder_set}' and ehsReminder='{$reminderEhsFlag}' and assignday={$assignday} ORDER BY patient_reminder_id DESC ";
			$result = @mysql_query($query);
			if(@mysql_num_rows($result) > 0){
				$reminder = "<ul>";
				while( $row = @mysql_fetch_array($result) ){
					$reminder .= "<li>" . decrypt_data($row['reminder'],$privatekey) . "</li>";
				}
				$reminder .= "</ul>";
                                //echo $reminder;
				return $reminder;
			}
			return "";
		}
		return "";
            
            
        }
	/**
	 * Template parsing function.
	 */
	function build_template($template_path, $replace="") {

			$content = file_get_contents($template_path);
			while( is_array($replace) && list($key,$value) = each($replace) ){
				$patterns = '/<!' . $key . '>/';
				$value = (string)$value;
				if (empty($value) === false) {
					$content = preg_replace($patterns, $value, $content);
				}else{
					$content = preg_replace($patterns, $value, $content);
				}
			}
			return $content;
	}

	/**
		 * This functio get clinic Id or clinic name of a user from clinic_user table.
		 *
		 * @param string $user_id:: user id of which details to be fetched.
		 * @param integer $field:: which field value to get.
		 * @return mixed
		 * @access public
		 */
        function get_clinic_info($user_id,$field = "clinic_id" ){
            if( is_numeric($user_id) && $user_id >0 ){
                $sql = "select clinic_id from clinic_user where user_id = '".$user_id."'";
                $result = @mysql_query($sql);
                while( $row = @mysql_fetch_array($result) ){
                    $clinic_id = $row["clinic_id"];
                    if(is_numeric($clinic_id) && $clinic_id > 0  &&  $field == "clinic_id" ) {
                        return $row[$field];
                    }
                    if( is_numeric($clinic_id) && $clinic_id > 0 &&  $field == "clinic_name" ){
                        $clinic_name = get_clinic_name($clinic_id,"clinic_name");                      return $clinic_name;
                    }
                }
            }
            return "";
        }
		/**
        * Get clinic name of a user from the clinic table.
		* @param integer $clinic_id
		* @return string $clinic_name
		* @access public
        */
        function get_clinic_name($clinic_id,$field = "clinic_name" ){
            if( is_numeric($clinic_id) && $clinic_id >0 ){
                $sql = "select clinic_name from clinic where clinic_id = '{$clinic_id}'";
                $result = @mysql_query($sql);
                $row = @mysql_fetch_array($result); 
                $clinic_name = $row["clinic_name"];
                return $clinic_name;
           }
                
        
            return "";
        }
    /**
         * This function get the channel type.
         * @param numeric $user_id
         * @return channel type
         * @access public
         */
        function getchannel($clinic_id){
           $sql="SELECT clinic_channel FROM clinic where clinic_id=".$clinic_id;
           $res=mysql_query($sql);
           $row=mysql_fetch_array($res); 
           return $row['clinic_channel'];   
        }  
?>