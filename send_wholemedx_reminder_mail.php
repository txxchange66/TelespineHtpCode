<?php
	require_once("config.php"); 
	$host = $txxchange_config['dbconfig']['db_host_name'];
	$user = $txxchange_config['dbconfig']['db_user_name'];
	$pass = $txxchange_config['dbconfig']['db_password'];
	$db   = $txxchange_config['dbconfig']['db_name'];
	$application_path   = $txxchange_config['application_path'];
        $privatekey = $txxchange_config['private_key'];
                
	
	// Make connection with server.
	$link = @mysql_connect($host,$user,$pass);
	
	// select database.
	@mysql_select_db($db,$link);

	// Get current date.
	$date = date("Y-m-d");
	
	// Send 3rd Day reminder
                         $querySelectThree=@mysql_query("select DATEDIFF(now(),assign_datetime),patient_intake.* from patient_intake where intake_sent_email = '0'  AND intake_compl_status = '0' AND DATEDIFF(now(),assign_datetime)>=3 ");
                         if(mysql_num_rows($querySelectThree)>0){
                            while($resultSelectThree=mysql_fetch_array($querySelectThree))   {
                                // Send Email to Patient
                                    $replacePatient=array();
                                		 $clinicName=html_entity_decode(get_clinic_info($resultSelectThree['p_user_id'],"clinic_name"), ENT_QUOTES, "UTF-8");
                                   		 $replacePatient['urlLogin']=$txxchange_config['images_url'];
                                    	 $Patientsubject = "Reminder ".html_entity_decode("-", ENT_QUOTES, "UTF-8")." Your ".$clinicName." New Patient Forms";
                                    $replacePatient['ClinicName']=$clinicName;

                                // Content Of the email send to  patient
                                  $Patientmessage = build_template( $application_path."mail_content/new_patient_reminder_three.php",$replacePatient);
                                    $Patientto = get_username("username",$resultSelectThree['p_user_id']);

                               

                                   $clinic_channel=getchannel($resultSelectThree['clinic_id']);
                                   send_external_email_intake($Patientto,$Patientsubject,$Patientmessage,$clinicName, $clinic_channel);              
                                
                                //Update Database table that 3rd day reminder is send
                                    $updQueryThree="update patient_intake set intake_sent_email = '3' where intake_id = '{$resultSelectThree['intake_id']}' ";
                                    @mysql_query($updQueryThree);
                            }
                         }
                         
                         // Send 5th Day reminder
                         $querySelectFive=mysql_query("select DATEDIFF(now(),assign_datetime),patient_intake.* from patient_intake where intake_sent_email = '3'  AND intake_compl_status = '0' AND DATEDIFF(now(),assign_datetime)>=5 ");
                         if(mysql_num_rows($querySelectFive)>0){
                            while($resultSelectFive=mysql_fetch_array($querySelectFive))   {
                                // Send Email to Patient
                                    $replacePatient=array();
                                    $clinicName=html_entity_decode(get_clinic_info($resultSelectFive['p_user_id'],"clinic_name"), ENT_QUOTES, "UTF-8");
                                    $replacePatient['urlLogin']=$txxchange_config['images_url'];
                                    $Patientsubject = "Reminder ".html_entity_decode("-", ENT_QUOTES, "UTF-8")." Your ".$clinicName." New Patient Forms";
                                    $replacePatient['ClinicName']=$clinicName;

                                // Content Of the email send to  patient
                                    $Patientmessage = build_template( $application_path."mail_content/new_patient_reminder_five.php",$replacePatient);
                                    $Patientto = get_username("username",$resultSelectFive['p_user_id']);
                                    $clinic_channel=getchannel($resultSelectFive['clinic_id']);
                                    send_external_email_intake($Patientto,$Patientsubject,$Patientmessage,$clinicName, $clinic_channel);                                
                                //Update Database table that 3rd day reminder is send
                                    $updQueryFive="update patient_intake set intake_sent_email = '5' where intake_id = '{$resultSelectFive['intake_id']}' ";
                                    @mysql_query($updQueryFive);
                            }
                         }
          function send_external_email_intake($Patientto,$Patientsubject,$Patientmessage,$clinicName, $clinic_channel){
               
                    // To send HTML mail, the Content-type header must be set
                        global $txxchange_config;
                    $Patientheaders  = 'MIME-Version: 1.0' . "\n";
                    $Patientheaders .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                   // $Patientheaders .= "From: ".setmailheader($clinicName)." <support@wholemedx.com>" . "\n";
                    //$Patientreturnpath = '-fsupport@wholemedx.com';
                     if( $clinic_channel == 1){
			    $Patientheaders .= "From: ".setmailheader($clinicName). " <".$txxchange_config['email_tx'].">" . "\n";
			    $Patientreturnpath = "-f".$txxchange_config['email_tx'];
			    }else{
			    $Patientheaders .= "From: ".setmailheader($clinicName). " <".$txxchange_config['email_wx'].">" . "\n";
			      $Patientreturnpath = '-f'.$txxchange_config['email_wx'];   
			    }
                    // Mail it
                    //echo "$Patientto, $Patientsubject, $Patientmessage, $Patientheaders, $Patientreturnpath";exit;
                    @mail($Patientto, $Patientsubject, $Patientmessage, $Patientheaders, $Patientreturnpath);          
         }

         /**
         * This function used to get clinic channel
         * @param integer $clinci id
         */
  	function getchannel($clinic_id){
  	   $sql="SELECT clinic_channel FROM clinic where clinic_id=".$clinic_id;
           $res=mysql_query($sql);
           $row=mysql_fetch_array($res); 
           return $row['clinic_channel'];	
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
	function get_reminder($user_id = ""){
        global $privatekey;
		if($user_id != ""){
			$query = "select * from patient_reminder where patient_id = '{$user_id}' ";
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
    
?>
