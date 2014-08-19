<?php
require_once("config.php");
$host = $txxchange_config['dbconfig']['db_host_name'];
$user = $txxchange_config['dbconfig']['db_user_name'];
$pass = $txxchange_config['dbconfig']['db_password'];
$db   = $txxchange_config['dbconfig']['db_name'];
$application_path   = $txxchange_config['application_path'];
$templatePath = $application_path."mail_content/new_message.php";
$templatePath_plpto = $application_path."mail_content/new_message_plpto.php";
$imagePath = $txxchange_config['images_url'];
$from_email_address = $txxchange_config['from_email_address'];
// Make connection with server.
$link = @mysql_connect($host,$user,$pass);
// select database.
@mysql_select_db($db,$link);        

        /* Function for sending Reminder mail to patients based on interval being set by system admin under notifications section. */
        function reminderMail($templatePath,$imagePath){
            
            global $from_email_address;
            $query = "select patient from interval_noncompliant_patient";
            $result = @mysql_query($query);
            if( $row = @mysql_fetch_array($result) ){
                $patient_interval = $row['patient'];
                if(!(is_numeric($patient_interval) && $patient_interval > 0) ){
                    exit();
                }
            }
            $query = " SELECT tp.therapist_id, u.user_id, u.username
                        FROM user AS u
                        INNER JOIN therapist_patient AS tp ON u.user_id = tp.patient_id
                        WHERE u.agreement = 1
                        AND u.STATUS !=3
                        AND u.usertype_id = 1
                        AND DATEDIFF( current_date, DATE( u.last_login ) )/7 = '{$patient_interval}'
            ";
            
            $result = @mysql_query($query) ; 
            // taking every user's date data line by line
            if(@mysql_num_rows($result)){
                while ($row = @mysql_fetch_array($result)) {
                    // Send non-compliant patient email notification.
                    new_post_notification($row['username'], $row['user_id']);
                    TxMessagePatient( $row['user_id'], $row['therapist_id'] );
                }
            }
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
        * Get mail body.
        * 
        * @therapist_id mixed $templatePath
        */
        function get_mail_body($therapist_id){
            global $txxchange_config;
            if(is_numeric($therapist_id)){
                $query = "select  AES_DECRYPT(UNHEX(message),'{$txxchange_config['private_key']}') as message
                from notification_reminder where therapist_id = '{$therapist_id}' ";
                $result = @mysql_query($query);
                if( $row = @mysql_fetch_array($result) ){
                    return $row['message'];
                }
            }
            return false;
        }
        
        /**
         * This function formats the E-mail and send it to user.
         * @param numeric $user_id
         * @return none
         * @access public
         */
        function new_post_notification( $username = "",$user_id ){
            global $txxchange_config;
            global $application_path;
            if( $username != "" ){
                 $clinic_channel=getchannel(get_clinic_info($user_id,'clinic_id'));
            if($clinic_channel==1){
                 $business_url=$txxchange_config['business_tx']; 
                 $support_email=$txxchange_config['email_tx'];
             }else{
                 $business_url=$txxchange_config['business_wx']; 
                 $support_email=$txxchange_config['email_wx'];   
             }
            	$data = array(
                       'url' => $txxchange_config['url'],
                       'images_url' => $txxchange_config['images_url'],
            	       'business_url'=>$business_url,
                       'support_email'=>$support_email
                        );
                
				
                $clinic_type = getUserClinicType($user_id);
               
                        if( $clinic_channel == 1){
                if( $clinic_channel == 1){
                	$message = build_template($application_path."mail_content/plpto/non_compliance_patient_plpto.php",$data);
                }
                else{
                    $message = build_template($application_path."mail_content/wx/non_compliance_patient_plpto.php",$data);	
                }
                        
                //$message = build_template($application_path."mail_content/new_message.php",$data);
                
                $to = $username;
                $subject = "Message from your Provider";
                
                // To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                
                // Additional headers
                $headers .= "From: Tx Xchange <do-not-reply@txxchange.com>\n";
                $returnpath = '-fdo-not-reply@txxchange.com';
                
                // Mail it
                mail($to, $subject, $message, $headers, $returnpath);
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
          * TxMessage to Patient or Therapist
        */
        function TxMessagePatient($patient_id, $therapist_id ){

          if(is_numeric($patient_id) && is_numeric($therapist_id) ){
              $message = get_mail_body($therapist_id); 
              $message_arr = array(
                    'subject'       =>  encrypt_data("Checking in"),
                    'content'       =>  encrypt_data($message),
                    'patient_id'    =>  $patient_id,
                    'sender_id'     =>  $therapist_id,
                    'parent_id'     =>  '0',
                    'sent_date'     =>  date('Y-m-d H:i:s',time())
              );
              insert('message', $message_arr);
              $message_id = insert_id();
              // Entry for Patient
             $data = array(
                 'message_id' => $message_id,
                 'user_id' => $patient_id,
                 'unread_message' => '1'
             );
             insert("message_user",$data);
             // Entry for system message
             $data = array(
                 'message_id' => $message_id,
                 'user_id' => $therapist_id,
             );
             insert("system_message",$data);
          }
        }
        
        /**
          * Function to insert data into table.
          * insert function - requires the table to be inserted to and an array of column names=>variables
          * 
          * @param string $table - table name
          * @param array $arr - data array
          * @return mysql result set
          * @access public
          */
          function insert($table, $arr) {

            $result = execute_query("SELECT * FROM $table LIMIT 0,1");
            $columns = @mysql_num_fields($result);
            // getting list of columns of the specified table.
            for ($i = 0; $i < $columns; $i++) {
              $fieldarr[] = @mysql_field_name($result, $i);
            }
            
            // building insert statement.
            $sqla = "INSERT INTO $table ( ";
            $sqlb = ") VALUES ( ";
            foreach ($arr as $name => $value) {
              if (in_array ($name, $fieldarr)) {
                $sqla .= "$name,";
                if (is_array($value)) {
                  $sqlb .= "'|";
                  $j = count($value);
                  for ($i = 0; $i < $j-1; $i++) {
                    $sqlb .= addslashes($value[$i]) . "|,|";
                  }
                  $k = $j-1;
                  $sqlb .= addslashes($value[$k]) . "|',";
                } else {
                      if( $value == null ){
                          $sqlb .= 'null'.",";
                      }
                      else
                        $sqlb .= "'" . addslashes($value) . "',";
                }
              }
            }
            $sqlb .= ")";
            $sql = $sqla . $sqlb;
            $sql = str_replace(",)", ")", $sql);
             
            // executing query.
            $result = execute_query($sql);
            return $result;
          }
          
          /**
             * function to execute sql statement.
             *
             * @param string $query
             * @return mysql result set 
             * @access public
             */
          function execute_query($query) {
                    
                    if (empty($query)) {
                        return 0;
                    }
                    // execute query
                    $result = @mysql_query($query);
                    $error = @mysql_error();
                    // if error occured
                    if (!$result){
                            echo "<h2>Can't execute query $query</h2>";
                            echo "<p><b>Error:</b> ". @mysql_error();
                            exit;
                    }
                    return $result;
          }
          /**
          * This function returns Encrypt data passed to it.
          */
          function encrypt_data($data,$privateKey = "a2dc2a99e649f147bcabc5a99bea7d96"){
              $query = "select HEX(AES_ENCRYPT('{$data}','{$privateKey}'))";
              $result = @mysql_query($query);
              if ($result) {
                  return @mysql_result($result, 0);
              }
              return "";
          }
          /**
             * Get the ID generated from the previous INSERT operation
             *
             * @return interger
             * @access public
          */
        function insert_id()  {
            return @mysql_insert_id();
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
        reminderMail($templatePath,$imagePath);






?>