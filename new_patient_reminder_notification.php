<?php
 
require_once("config.php");
$host = $txxchange_config['dbconfig']['db_host_name'];
$user = $txxchange_config['dbconfig']['db_user_name'];
$pass = $txxchange_config['dbconfig']['db_password'];
$db   = $txxchange_config['dbconfig']['db_name'];
$application_path   = $txxchange_config['application_path'];
$templatePath = $application_path."mail_content/remindermail.php";
$templatePath_plpto = $application_path."mail_content/plpto/remindermail_plpto.php";
$templatePath_wx= $application_path."mail_content/wx/remindermail_plpto.php";
$imagePath = $txxchange_config['images_url'];
$from_email_address = $txxchange_config['from_email_address'];
// Make connection with server.
$link = @mysql_connect($host,$user,$pass);
// select database.
@mysql_select_db($db,$link);        

		/* Function for sneding Reminder mail to patients who had not logged in for 3 days after their account creation. */
		function reminderMail(){
            global $from_email_address,$templatePath,$templatePath_plpto,$imagePath,$txxchange_config,$templatePath_wx;
			$query = " SELECT user_id,username,name_first,username,AES_DECRYPT(UNHEX(password),'{$txxchange_config['private_key']}') as password  FROM user WHERE usertype_id =1 AND STATUS !=3 AND agreement = 0 AND DATEDIFF( current_date, DATE( creation_date ) ) = 3 ";
						
			$result = @mysql_query($query) ; 
			// taking every user's date data line by line
			if(@mysql_num_rows($result)){
				while ($row = @mysql_fetch_array($result)) {
						//fill in data for mail content.
						$data = array() ;
                        $data['images_url'] = $imagePath;
                        $data['password'] = $row['password'];
						//code section for sending of mail.
						$user_id = $row['user_id'];
	                    $clinicName	=	get_clinic_info($user_id,"clinic_name");
						$data['clinicName'] = $clinicName;
						$data['username'] = $row['username'];
						$clinic_type = getUserClinicType($user_id);
						$clinic_channel=getchannel(get_clinic_info($user_id,'clinic_id'));
				        if($clinic_channel==1){
                            $business_url=$txxchange_config['business_tx']; 
                            $support_email=$txxchange_config['email_tx'];
                        }else{
                            $business_url=$txxchange_config['business_wx']; 
                            $support_email=$txxchange_config['email_wx'];   
                        }
                        $data['business_url'] = $business_url;
                        $data['support_email'] = $support_email;
	                    if( $clinic_channel == 1){
	                    	$message = build_template($templatePath_plpto,$data);
	                    }
	                    else{
	                       	$message = build_template($templatePath_wx,$data);
	                    }
						//$message = build_template($templatePath,$data);
                        

						$to = $row['username'];	
						$subject = html_entity_decode($clinicName, ENT_QUOTES, "UTF-8")." Follow-up";
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						
						//$headers .= "From: " .html_entity_decode($clinicName). "<support@txxchange.com>" . "\n";
						//$returnpath = '-fsupport@txxchange.com';
				
						if( $clinic_channel == 1){
						    $headers .= "From: ".setmailheader($clinicName). " <".$txxchange_config['email_tx'].">" . "\n";
						    $returnpath = "-f".$txxchange_config['email_tx'];
						    }else{
						    $headers .= "From: ".setmailheader($clinicName). " <".$txxchange_config['email_wx'].">" . "\n";
						    $returnpath = '-f'.$txxchange_config['email_wx'];   
						    }
						
						$headers .= 'Bcc: ' . $from_email_address . "\n"; // will be deleted later.
						// Mail it
						echo $to;
						@mail($to, $subject, $message, $headers, $returnpath); 

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
       
        

		reminderMail($templatePath,$imagePath);






?>