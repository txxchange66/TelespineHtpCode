<?php
 
require_once("config.php");
$host = $txxchange_config['dbconfig']['db_host_name'];
$user = $txxchange_config['dbconfig']['db_user_name'];
$pass = $txxchange_config['dbconfig']['db_password'];
$db   = $txxchange_config['dbconfig']['db_name'];
$application_path   = $txxchange_config['application_path'];
$templatePath = $application_path."mail_content/telespine/notlogin_threeday.php";
$loginurl=$txxchange_config['telespine_login'];
$imagePath = $txxchange_config['images_url'];
$from_email_address = $txxchange_config['email_telespine'];
$telespine_id=$txxchange_config['telespineid'];

// Make connection with server.
$link = @mysql_connect($host,$user,$pass);
// select database.
@mysql_select_db($db,$link);        

		/* Function for sneding Reminder mail to patients who had not logged in for 3 days after their account creation. */
		function reminderMail(){
            global $from_email_address,$templatePath,$imagePath,$txxchange_config,$telespine_id,$loginurl;
			$query = " SELECT usr.user_id,usr.username,AES_DECRYPT(UNHEX(usr.name_first),'{$txxchange_config['private_key']}') as name_first,AES_DECRYPT(UNHEX(usr.name_last),'{$txxchange_config['private_key']}') as name_last,AES_DECRYPT(UNHEX(usr.password),'{$txxchange_config['private_key']}') as password  FROM user usr,clinic_user cu  WHERE usr.usertype_id =1 AND usr.STATUS !=3 AND usr.agreement = 1 AND DATEDIFF( current_date, DATE( usr.last_login ) ) = '3' AND usr.user_id=cu.user_id AND cu.clinic_id='".$telespine_id."'";
			
									
			$result = @mysql_query($query) ; 
			$cc=1;
			// taking every user's date data line by line
			if(@mysql_num_rows($result)){
				while ($row = @mysql_fetch_array($result)) {
						//fill in data for mail content.
						$data = array() ;
                        $data['images_url'] = $imagePath;
                        $data['password'] = $row['password'];
                        $data['loginurl']=$loginurl;
						$fullname = $row['name_first'].'  '.$row['name_last'];
						$data['fullname'] = $fullname;
						//code section for sending of mail.
						$user_id = $row['user_id'];
	                    $clinicName	=	get_clinic_info($user_id,"clinic_name");
						$data['clinicName'] = $clinicName;
						$data['username'] = $row['username'];
						echo "<br>".$row['username'];
						
						$clinic_type = getUserClinicType($user_id);
						$clinic_channel=getchannel(get_clinic_info($user_id,'clinic_id'));
				        
                            $business_url=$txxchange_config['business_telespine']; 
                            $support_email=$txxchange_config['email_telespine'];
                        
                        $data['business_url'] = $business_url;
                        $data['support_email'] = $support_email;
						
						
						$message = build_template($templatePath,$data);
	                    
						//$message = build_template($templatePath,$data);
                        
						//echo $message;
						
						$to = $fullname.'<'.$row['username'].'>';	
						
						$subject = "Telespine Update - Need Some Help?";
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						
						//$headers .= "From: " .html_entity_decode($clinicName). "<support@txxchange.com>" . "\n";
						//$returnpath = '-fsupport@txxchange.com';
				
						 $headers .= "From: Telespine Support<".$txxchange_config['email_telespine'].">" . "\n";
						 $returnpath = "-f".$txxchange_config['email_telespine'];
						// $headers .= 'Bcc: ' . $from_email_address . "\n"; // will be deleted later.
						// Mail it
						//echo "<br>".$to;
						/*
						if($cc==1){
						@mail('rohit.mishra@hytechpro.com', $subject, $message, $headers, $returnpath); 
						}
						*/
						$cc=$cc+1;
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