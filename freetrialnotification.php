<?php

require_once("config.php");
$host = $txxchange_config['dbconfig']['db_host_name'];
$user = $txxchange_config['dbconfig']['db_user_name'];
$pass = $txxchange_config['dbconfig']['db_password'];
$db   = $txxchange_config['dbconfig']['db_name'];
$application_path   = $txxchange_config['application_path'];
$templatePath_plpto_1   = $application_path."mail_content/plpto/freetrila_1_remindermail.php";
$templatePath_plpto_2   = $application_path."mail_content/plpto/freetrila_2_remindermail.php";
$templatePath_plpto_3  = $application_path."mail_content/plpto/freetrila_3_remindermail.php";
$templatePath_plpto_4  = $application_path."mail_content/plpto/freetrila_4_remindermail.php";
$templatePath_plpto_5  = $application_path."mail_content/plpto/freetrila_5_remindermail.php";

$imagePath = $txxchange_config['images_url'];
$from_email_address = $txxchange_config['from_email_address'];
// Make connection with server.
$link = @mysql_connect($host,$user,$pass);
// select database.
@mysql_select_db($db,$link);        

		/* Function for sneding Reminder mail to patients who had not logged in for 3 days after their account creation. */
		function reminderMail(){
            global $from_email_address,$templatePath_plpto_1,$templatePath_plpto_2,$templatePath_plpto_3,$templatePath_plpto_4,$templatePath_plpto_5,$imagePath,$txxchange_config;
			$query = " SELECT user_id,username,name_first,username,AES_DECRYPT(UNHEX(password),'{$txxchange_config['private_key']}') as password  FROM user WHERE usertype_id =2 AND STATUS =1 AND trial_status=1 AND payment is null and DATEDIFF( current_date, DATE( free_trial_date ) ) = 3 ";
	
			$result = @mysql_query($query) ; 
			// taking every user's date data line by line
			if(@mysql_num_rows($result)){
				while ($row = @mysql_fetch_array($result)) {
						//fill in data for mail content.
						$data = array() ;
                        $data['images_url'] = $imagePath;
                        $data['password'] = $row['password'] ;
						//code section for sending of mail.
						$user_id = $row['user_id'];
	                   	$message = build_template($templatePath_plpto_1,$data);
	                                          
						$to = $row['username'];
						$subject = "How's Tx Xchange Working for You?";
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						$headers .= "From: support@txxchange.com" . "\n";
						//$headers .= 'Bcc: manabendra.sarkar@hytechpro.com' . "\r\n"; // will be deleted later.
                        $headers .= 'Bcc: ' . $from_email_address . "\n"; // will be deleted later.
						$returnpath = '-fsupport@txxchange.com';
						// Mail it
						//echo $message;
						if(mail($to, $subject, $message, $headers, $returnpath)){
							
						}
				}
			}
			//mail after 10 day
		    $query = " SELECT user_id,username,name_first,username,AES_DECRYPT(UNHEX(password),'{$txxchange_config['private_key']}') as password  FROM user WHERE usertype_id =2 AND STATUS =1 AND trial_status=1 AND payment is null and DATEDIFF( current_date, DATE( free_trial_date ) ) = 10 ";
	
			$result = @mysql_query($query) ; 
			// taking every user's date data line by line
			if(@mysql_num_rows($result)){
				while ($row = @mysql_fetch_array($result)) {
						//fill in data for mail content.
						$data = array() ;
                        $data['images_url'] = $imagePath;
                        $data['password'] = $row['password'] ;
						//code section for sending of mail.
						$user_id = $row['user_id'];
	                   	$message = build_template($templatePath_plpto_2,$data);
	                   	$to = $row['username'];
						$subject = "Tx Xchange Trial Update";
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						$headers .= "From: support@txxchange.com" . "\n";
						//$headers .= 'Bcc: manabendra.sarkar@hytechpro.com' . "\r\n"; // will be deleted later.
                        $headers .= 'Bcc: ' . $from_email_address . "\n"; // will be deleted later.
						$returnpath = '-fsupport@txxchange.com';
						// Mail it
						if(mail($to, $subject, $message, $headers, $returnpath)){
							
						}
				}
			}
			//mail After 20 days
		$query = " SELECT user_id,username,name_first,username,AES_DECRYPT(UNHEX(password),'{$txxchange_config['private_key']}') as password  FROM user WHERE usertype_id =2 AND STATUS =1 AND trial_status=1 AND payment is null and DATEDIFF( current_date, DATE( free_trial_date ) ) = 20 ";
	
			$result = @mysql_query($query) ; 
			// taking every user's date data line by line
			if(@mysql_num_rows($result)){
				while ($row = @mysql_fetch_array($result)) {
						//fill in data for mail content.
						$data = array() ;
                        $data['images_url'] = $imagePath;
                        $data['password'] = $row['password'] ;
						//code section for sending of mail.
						$user_id = $row['user_id'];
	                    $message = build_template($templatePath_plpto_3,$data);
	                    
						$to = $row['username'];
						$subject = "What Your Patients & Clients Want";
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						$headers .= "From: support@txxchange.com" . "\n";
						//$headers .= 'Bcc: manabendra.sarkar@hytechpro.com' . "\r\n"; // will be deleted later.
                        $headers .= 'Bcc: ' . $from_email_address . "\n"; // will be deleted later.
						$returnpath = '-fsupport@txxchange.com';
						// Mail it
						if(mail($to, $subject, $message, $headers, $returnpath)){
							
						}
				}
			}
			//mail after 28 days
		$query = " SELECT user_id,username,name_first,username,AES_DECRYPT(UNHEX(password),'{$txxchange_config['private_key']}') as password  FROM user WHERE usertype_id =2 AND STATUS =1 AND trial_status=1 AND payment is null and DATEDIFF( current_date, DATE( free_trial_date ) ) = 28 ";
	
			$result = @mysql_query($query) ; 
			// taking every user's date data line by line
			if(@mysql_num_rows($result)){
				while ($row = @mysql_fetch_array($result)) {
						//fill in data for mail content.
						$data = array() ;
                        $data['images_url'] = $imagePath;
                        $data['password'] = $row['password'] ;
						//code section for sending of mail.
						$user_id = $row['user_id'];
	                    $message = build_template($templatePath_plpto_4,$data);
	                    $to = $row['username'];
						$subject = "Your Tx Xchange Trial";
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						$headers .= "From: support@txxchange.com" . "\n";
						//$headers .= 'Bcc: manabendra.sarkar@hytechpro.com' . "\r\n"; // will be deleted later.
                        $headers .= 'Bcc: ' . $from_email_address . "\n"; // will be deleted later.
						$returnpath = '-fsupport@txxchange.com';
						// Mail it
						if(mail($to, $subject, $message, $headers, $returnpath)){
							
						}
				}
			}
    //mail after 30 days        
          	$query = " SELECT user_id,username,name_first,username,AES_DECRYPT(UNHEX(password),'{$txxchange_config['private_key']}') as password  FROM user WHERE usertype_id =2 AND STATUS =1 AND trial_status=1 AND payment is null and DATEDIFF( current_date, DATE( free_trial_date ) ) = 30 ";
	
			$result = @mysql_query($query) ; 
			// taking every user's date data line by line
			if(@mysql_num_rows($result)){
				while ($row = @mysql_fetch_array($result)) {
						//fill in data for mail content.
						$data = array() ;
                        $data['images_url'] = $imagePath;
                        $data['password'] = $row['password'] ;
						//code section for sending of mail.
						$user_id = $row['user_id'];
	                   	$message = build_template($templatePath_plpto_5,$data);
	                   	$to = $row['username'];
						$subject = "Telehealth - The Future is Now";
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						$headers .= "From: support@txxchange.com" . "\n";
						//$headers .= 'Bcc: manabendra.sarkar@hytechpro.com' . "\r\n"; // will be deleted later.
                        $headers .= 'Bcc: ' . $from_email_address . "\n"; // will be deleted later.
						$returnpath = '-fsupport@txxchange.com';
						// Mail it
						if(mail($to, $subject, $message, $headers, $returnpath)){
							
						}
				}
			}
			
			
			
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

		reminderMail($templatePath,$imagePath);
?>