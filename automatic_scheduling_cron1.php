<?php
require_once("config.php"); 
$host = $txxchange_config['dbconfig']['db_host_name'];
$user = $txxchange_config['dbconfig']['db_user_name'];
$pass = $txxchange_config['dbconfig']['db_password'];
$db   = $txxchange_config['dbconfig']['db_name'];
$date =$txxchange_config['configdate'];
$telespineid =$txxchange_config['telespineid'];
$loginurl=$txxchange_config['telespine_login'];
$privatekey = $txxchange_config['private_key'];
$application_path   = $txxchange_config['application_path'];
$templatePath = $application_path."mail_content/mass_patient_upload_mail.php";
$templatePathMassMessage = $application_path."mail_content/mass_message_mail_aa.php";
$templatePathwx = $application_path."mail_content/wx/mass_patient_upload_mail.php";
$templatePathMassMessagewx = $application_path."mail_content/wx/mass_message_mail_aa.php";
$business_telespine=$txxchange_config['business_telespine']; 
$support_telespine=$txxchange_config['email_telespine'];
$imagePath = $txxchange_config['images_url'];
$url = $txxchange_config['url'];
$from_email_address = $txxchange_config['from_email_address'];

$business_wx=$txxchange_config['business_wx'];
$business_tx=$txxchange_config['business_tx'];

$email_wx=$txxchange_config['email_tx'];
$email_tx=$txxchange_config['email_wx'];

$images_url=$txxchange_config['images_url'];
// Make connection with server.
$link = @mysql_connect($host,$user,$pass);
// select database.
@mysql_select_db($db,$link);        


function automatic_scheduling_assign(){
    global $date,$txxchange_config ;
   echo  $sql="SELECT * FROM addon_services WHERE automatic_scheduling='1'";
    $query= @mysql_query($sql);
    if(@mysql_num_rows($query)!=0){
        
        while ($row=@mysql_fetch_object($query)){
            echo '<br>'.$row->clinic_id.'<br>';
           $paitentarray=  getProviderEHSPatients($row->clinic_id,$date);
           //print_r($paitentarray);die;
           if(is_array($paitentarray)){
           foreach($paitentarray as $key=>$val){
               echo '<br>'.'days'.$key.'   id =>'.$val.'<br>';
                //Assigned Health Video Series
               
             $days=  explode('~', $key);
             
           //  if($days[1]!=1){
               $sqlcheck="select * from automaticscheduling_content where patient_id={$val} and clinic_id={$row->clinic_id} and day={$days[1]}";
               $querycheck=  mysql_query($sqlcheck);
               if(mysql_num_rows($querycheck)==0){
                   $sqlinsert="insert into automaticscheduling_content SET patient_id={$val},clinic_id={$row->clinic_id},day={$days[1]}";
                   $queryinsert=  mysql_query($sqlinsert);
               }
              schedulerEhsPlanAssignment($days[1],$val,$row->clinic_id);
               //Assigned articles
              schedulerEhsArticle($days[1],$val,$row->clinic_id);
               //Assigned Goal
              schedulerEhsGoal($days[1],$val,$row->clinic_id);
               //Assigned Reminders
              schedulerAddEhsReminder($days[1],$val,$row->clinic_id);
            //}
               if($row->clinic_id==$txxchange_config['telespineid']){
                   echo "mail function call";
              sendEmailToPatient($days[1],$val,$row->clinic_id);
               }
              
             }
           }
        }
    }
}



function sendEmailToPatient($days,$user_id,$clinic_id){
    global $txxchange_config;
    global $application_path;
    global $privatekey;
    global $telespine_id; 
    global $loginurl;
    $date = $txxchange_config['configdate'];
    $business_url=$txxchange_config['business_telespine'];
    $support_email=$txxchange_config['email_telespine'];
   echo  $query = "select * from reminder_schedule where date(scheduled_date) = '{$date}' and status = 'pending' and patient_id={$user_id}";
    // Fetch rows from resultset.
	$result = @mysql_query($query);
    
        if(mysql_num_rows($result)!=0){
            while( $row = @mysql_fetch_array($result) ){
                        if($row['reminderEhsFlag']==2){
                                      $remindertxt = get_reminder_automaticschedule($row['patient_id'],$row['reminder_set'],$row['reminderEhsFlag'],$row['assignday']);
                                      $reminder_schedule_id=$row['reminder_schedule_id'];
                                }
                	
            }
        }
                                    if($remindertxt!=''){
                                        $textreminder="<p style=\"margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;\">Also, keep the following in mind today - it'll help.</p>
<br>";
                                        $reminder=    $textreminder.$remindertxt;
                                      }
                                    $fullname=get_username("name_first",$user_id);	
                                    $data = array(
							'url' => $txxchange_config['url'],
							'images_url' => $txxchange_config['images_url'],
							'reminder' => $reminder,
                                                        'fullname'=>$fullname,
                                                        'loginurl'=>$loginurl
							);
			
                                	$message = build_template( $application_path."mail_content/telespine/reminder.php",$data);
                                        
	                      		$to = $fullname.'<'.get_username("username",$user_id).'>';
					$subject = "Telespine Update";
					// To send HTML mail, the Content-type header must be set
					$headers  = 'MIME-Version: 1.0' . "\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
					//echo $message;
					// Additional headers
					$headers .= "From: Telespine Support <support@telespine.com>" . "\n";
					$returnpath = '-fsupport@telespine.com';
					// Mail it
					if(mail($to, $subject, $message, $headers,$returnpath) == '1' ){
						if($reminder!=''){
                                                $query = "update reminder_schedule  set status = 'sent' where reminder_schedule_id = '{$reminder_schedule_id}' ";
						@mysql_query($query);
                                                }
					}
					else{
                                            if($reminder!=''){	
                                                $query = "update reminder_schedule  set status = 'failed' where reminder_schedule_id = '{$reminder_schedule_id}' ";
						@mysql_query($query);
                                            }
					}
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
function getTherapistlist($clinicId=''){
             if($clinicId=='' || $clinicId==0)
            		$clinicId = self::clinicInfo('clinic_id');
            $query = "select clinic_user.user_id from clinic_user,user where clinic_user.user_id=user.user_id and user.usertype_id=2 and user.status=1  and user.therapist_access=1 and clinic_id = '{$clinicId}'";
            $result = @mysql_query($query);
            $tempArray = array();
            if( @mysql_num_rows($result) > 0 ){
                while( $row = @mysql_fetch_array($result)){
                    $tempArray[] =$row[user_id]; 
                         
                }
                //print_r($tempArray);
                $str = implode(',',$tempArray);
                return $str;
                
            }
            return "";
            
            
        }
		
		/**
		 * Template parsing function. This parse the templat with placeholders kept in replace array
		 *
		 * @param string $template_path:: path from where template should be read.
		 * @param array $replace:: contains values for placeholders tag in html template.
		 * @return string
		 * @access public
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
		 * This function returns clinic details.
		 *
		 * @param string $field
		 * @param integer $id
		 * @return mixed
		 * @access public
		 */
function clinicInfo($field ,$id=""){
			if($id != ""){
				$query = "select * from clinic_user where user_id = '".$id."'";
				$result = @mysql_query($query);
				$row = @mysql_fetch_array($result);
				if(!empty($row)){
				if($field != ""){
					return $field_value = $row[$field];
				}
			}
			return "";
			}
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
		 * Function to check whether a valid email address is entered or not.
		 *
		 * @access public
		 * @parameter: string $email
		 * @return: boolean true or false	
		 */
		function validateEmail($email)
		{
		   if(eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}(\.[a-zA-Z]{2,3})?(\.[a-zA-Z]{2,3})?$', $email))
			  return true;
		   else
			  return false;
		}

		/**
		 * Function to check whether user exist with passed username(email id) or not.
		 *
		 * @access public
		 * @parameter: string $username
		 * @return: boolean true or false	
		 */
		function checkUserNameExist($username)
		{	
			$sql="select username from user where username='".$username."' AND status!=3";
            $res=@mysql_query($sql);
            $numrow=@mysql_num_rows($res);
            if($numrow >0)              
				return true;                     
            else
	            return false;
        }

		/**
		 * Function to Generate unique random code from A-z,0-9.
		 *
		 * @access public
		 * @parameter: string $length:: length of how much long code is required
		 * @return: string	
		 */
		function generateCode($length=6) {

        	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";

        	$code = "";

        	while (strlen($code) < $length) {

            	$code .= $chars[mt_rand(0,strlen($chars))];

        	}

        	return $code;

    	}

/**
		 * Function to encrypt the data.
		 *
		 * @access public
		 * @parameter: string $data:: value which is to be encrypted.
		 * @parameter: string $privateKey:: hash code used as two way key.	
		 * @return: string	
		 */
		function encrypt_data($data,$privateKey = "a2dc2a99e649f147bcabc5a99bea7d96"){
                      $query = "select HEX(AES_ENCRYPT('{$data}','{$privateKey}'))";
                      $result = @mysql_query($query);
                      if ($result) {
                          return @mysql_result($result, 0);
                      }
                      return "";
            }

	
	function getchannel($clinic_id){
           $sql="SELECT clinic_channel FROM clinic where clinic_id=".$clinic_id;
           $res=mysql_query($sql);
           $row=mysql_fetch_array($res); 
           return $row['clinic_channel'];   
        }

         /**
          * Scheduler function for the mass assignment of the plan to the ehs patients
          * @param numeric $user_id
          * @return channel type
          * @access public
          * @sks 29th november
          */

        function schedulerEhsPlanAssignment($day,$pid,$clinicid) {

               // $query = "SELECT * FROM plan where ehsFlag = '2' AND clinicId={$clinicid} AND status = '4' AND scheduleday = {$day}";
                $query = " select * from plan where status =1 AND patient_id IS NULL AND clinicId = '{$clinicid}' AND ehsFlag = '2' and scheduleday ={$day} ORDER BY plan_id DESC";
                $result = @mysql_query($query);
                $numRows = @mysql_num_rows($result);
                $plan_name_mail='';
                 if($numRows > 0) { 
                         $sqlcheck="select * from automaticscheduling_content where patient_id={$pid} and clinic_id={$clinicid} and day={$day}";
                         $querycheck=  mysql_query($sqlcheck);
                         $rowcheck=  mysql_fetch_assoc($querycheck);
                         if($rowcheck['plan']==0){
                         $i = 0; 
                        while($row =  @mysql_fetch_assoc($result)) { 

                                //$row = @mysql_fetch_assoc($result);
                                $plan_id = $row['plan_id'];
                                $therapistId = $row['user_id'];
                                $plan_name = $row['plan_name'];
                                $clinicId = $row['clinicId'];
                                $notify = $row['notify'];
                                $mass_plan_id = $row['mass_plan_id'];
                               // while($pat < $patientCount) { 
                                        
                                                $data = array(
					                'plan_name' => $row['plan_name'],

					                'parent_template_id' => $row['plan_id'],

					                'user_id' => $row['user_id'],
                                                        
                                                        'patient_id' => $pid,

					                'user_type' => 2,

                                                        'ehsFlag' => '2',

					                'creation_date' => date("Y-m-d"),

					                'modified_date' => date("Y-m-d"),

					                'status' => '1'

				                );
                                                    
                                          $sqlinsert = "INSERT INTO plan set 
							        plan_name = '".$data['plan_name']."',
							        parent_template_id = '".$data['parent_template_id']."',
							        user_id = '".$data['user_id']."',
							        patient_id = '".$data['patient_id']."',
                                                                clinicId ='".$clinicId."',
							        user_type ='".$data['user_type']."',
							        creation_date = '".$data['creation_date']."',
							        modified = '".$data['modified_date']."',
							        status = '".$data['status']."',
							        ehsFlag = '".$data['ehsFlag']."', 
                                                                assignday='".$day."'";

				        $result1 = @mysql_query($sqlinsert);

                                        $new_plan_id = mysql_insert_id();
                                        $plan_name_mail.='<br>'.$plan_name.'<br>';
                                        // copy all treatments associated with plan to new plan id.

				        $sqlPlanTreatment = "select * from plan_treatment where plan_id = '{$plan_id}' ";

				        $plan_treatment = @mysql_query($sqlPlanTreatment);

				        while($rowPlanTreatment = mysql_fetch_array($plan_treatment)){

					        $data = array(
								        'plan_id' => $new_plan_id,

								        'treatment_id' => $rowPlanTreatment['treatment_id'],

								        'instruction' => $rowPlanTreatment['instruction'],

								        'sets' => $rowPlanTreatment['sets'],

								        'reps' => $rowPlanTreatment['reps'],

								        'hold' => $rowPlanTreatment['hold'],

								        'benefit' => $rowPlanTreatment['benefit'],

								        'lrb' => $rowPlanTreatment['lrb'],

								        'treatment_order' => $rowPlanTreatment['treatment_order'],

								        'creation_date' => date("Y-m-d"),

								        'modified' => date("Y-m-d"),

							        );


                                                 $sqlPlanTreatmentInsert = "INSERT INTO plan_treatment set 
							        plan_id = '".$data['plan_id']."',
							        treatment_id = '".$data['treatment_id']."',
							        instruction = '".$data['instruction']."',
							        sets = '".$data['sets']."',
							        reps = '".$data['reps']."',
							        hold ='".$data['hold']."',
							        benefit = '".$data['benefit']."',
							        lrb = '".$data['lrb']."',
							        treatment_order = '".$data['treatment_order']."',
                                                                modified = '".$data['modified']."',
							        creation_date = '".$data['creation_date']."'";

				                $resultPlanTreatment = @mysql_query($sqlPlanTreatmentInsert);

				        }

                                        // copy all articles associated with plan to new plan id.

				         $queryPlanArticle = "select * from plan_article,article where plan_article.article_id=article.article_id and article.status=1 and plan_id = '{$plan_id}' ";

				        $plan_article = @mysql_query($queryPlanArticle);

				        while($rowPlanArticle = @mysql_fetch_array($plan_article)){

					        $data = array(
								        'plan_id' => $new_plan_id,

								        'article_id' => $rowPlanArticle['article_id'],

                           						'creation_date' => date("Y-m-d"),

								        'modified' => date("Y-m-d"),
                                                                        
                                                                        'status'=>$rowPlanArticle['status']

							        );

					           $sqlPlanArticleInsert = "INSERT INTO plan_article set 
							        plan_id = '".$data['plan_id']."',
							        article_id = '".$data['article_id']."',
							        modified = '".$data['modified']."',
							        creation_date = '".$data['creation_date']."',
                                                                status='".$data['status']."'";
                                                                

				                $resultPlanInsert = @mysql_query($sqlPlanArticleInsert);

				        }

                                       

                                //Mail notification end here

                               // $pat++;
                 

                              //}
                        $modified_date = date("Y-m-d");
                        //$queryUpdate = "UPDATE plan SET status = '1' , scheduler_status = '2' ,modified = '{$modified_date}' where plan_id = ".$plan_id;
                        //@mysql_query($queryUpdate);
                        $i++;
                        
                }
                    $sqlupdate="update automaticscheduling_content set plan=1 where id={$rowcheck['id']}";
                    $queryupdate=  mysql_query($sqlupdate);
                     //If assign by the notification
                     $notify='1';
                                       if($notify == '1') {
                                                global $from_email_address, $templatePath, $imagePath, $txxchange_config, $url,$application_path,$loginurl;
                                                
                                                $userId = $pid;

                                                 //= "select username from user where user_id =".$userId." and status='1'" ;
                                                $sql = " SELECT username,AES_DECRYPT(UNHEX(name_first),'{$txxchange_config['private_key']}') as name_first,AES_DECRYPT(UNHEX(name_last),'{$txxchange_config['private_key']}') as name_last FROM user WHERE user_id={$userId}";
                                                $res1 = @mysql_query($sql);
                                                $rw1 = @mysql_fetch_array($res1);
                                                $username = $rw1['username'];
                                                $fullname=$rw1['name_first'].' '.$rw1['name_last'];
                                             if($clinicid==$txxchange_config['telespineid']){
                                                 /*if( $userId != "" ) {
                                                               $business_url=$txxchange_config['business_telespine'];
                                                               $support_email=$txxchange_config['email_telespine'];
                                                               $data = array(
                                                                'plan_name' => $plan_name_mail,
                                                                'url' => $txxchange_config['url'],
                                                                'images_url' => $txxchange_config['images_url'],
                                                                'business_url'=>$business_url,
                                                                'support_email'=>$support_email,
                                                                'clinic_name'=>$clinicName,
                                                                'fullname'=>$fullname, 
                                                                 'loginurl'=>$loginurl      
                                                        );
                                                        
                                                       $message = build_template($application_path."mail_content/telespine/New_Video_Assigned_Email_Notification.php",$data);
                                                       $to = $fullname.'<'.$username.'>';
                                                       $subject = "Telespine Update - New Video(s)";
                                                        // To send HTML mail, the Content-type header must be set
                                                        $headers  = 'MIME-Version: 1.0' . "\n";
                                                        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                                                        $headers .= "From: Telespine Support <support@telespine.com>\n";
                                                        $returnpath = '-fsupport@telespine';
                                                        // Mail it
                                                       // @mail($to, $subject, $message, $headers, $returnpath);
                                                        if(@mail($to, $subject, $message, $headers, $returnpath)){
                                                                echo "mail sent";
                                                        } else {
                                                                echo "mail fail";
                                                         }
                                        }*/
                                             }else{
                                                 if( $userId != "" ) {
                                                        $clinicId  =   get_clinic_info($userId);
                                                        $clinicName  =  html_entity_decode(get_clinic_info($userId ,"clinic_name"), ENT_QUOTES, "UTF-8");
                                                        $clinic_channel=getchannel($clinicId);
                                                         if($clinic_channel==1) {
                                                               $business_url=$txxchange_config['business_tx'];
                                                               $support_email=$txxchange_config['email_tx'];
                                                           } else {
                                                               $business_url=$txxchange_config['business_wx'];
                                                               $support_email=$txxchange_config['email_wx'];
                                                           }
                                                           $data = array(
                                                                'plan_name' => $plan_name_mail,
                                                                'url' => $txxchange_config['url'],
                                                                'images_url' => $txxchange_config['images_url'],
                                                                'business_url'=>$business_url,
                                                                'support_email'=>$support_email,
                                                               'clinic_name'=>$clinicName
                                                        );
                                                        if( $clinic_channel == 1) {
                                                                $message = build_template($application_path."mail_content/plpto/notify_mail_plpto.php",$data);
                                                        }
                                                        else{
                                                                $message = build_template($application_path."mail_content/wx/notify_mail_plpto.php",$data);
                                                        }
                                                        $to = $username;
                                                        $subject = "Educational Information from ".$clinicName;
                                                        // To send HTML mail, the Content-type header must be set
                                                        $headers  = 'MIME-Version: 1.0' . "\n";
                                                        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                                                        $headers .= "From: ".setmailheader($clinicName)."<do-not-reply@txxchange.com>\n";
                                                        $returnpath = '-fdo-not-reply@txxchange.com';
                                                        // Mail it
                                                       // @mail($to, $subject, $message, $headers, $returnpath);
                                                        if(@mail($to, $subject, $message, $headers, $returnpath)){
                                                                echo "mail sent";
                                                        } else {
                                                                echo "mail fail";
                                                         }
                                        }
                                       }
                                }
                    
                }              
                                echo $i." Times Scheduler runs";   echo "<br>";        
                                echo "Scheduler-->".$pid."Patient plan Inserted"; echo "<br>";
                }
              

        }


       
        /**
         * This function used to Check the provider EHS patients 
         * @param string $message
         * return string $message
         */
        function getProviderEHSPatients($clinicId,$date) {
                
                if($clinicId > 0) {

                      $sqlehsPatient = "SELECT u.user_id , u.ehs, DATEDIFF('{$date}', DATE( creation_date ))+1 as days
                                        FROM user as u inner join clinic_user as cu on u.user_id=cu.user_id 
                                        AND cu.clinic_id in (SELECT clinic_id FROM clinic WHERE parent_clinic_id = {$clinicId} OR clinic_id = {$clinicId} and ( status = 1 or status = 2 ) ) 
                                        INNER JOIN clinic ON clinic.clinic_id = cu.clinic_id AND (clinic.status = 1 or clinic.status = 2) 
                                        LEFT JOIN patient_subscription AS ps ON u.user_id = ps.user_id and (ps.subs_status='1' or (ps.subs_status='2' and subs_end_datetime > now()))
                                        WHERE u.usertype_id=1 and (u.status = 1 OR u.status = 2) AND ((ps.subs_status='1'  and subs_end_datetime > now()) OR (ps.subs_status='2' AND ps.subs_end_datetime > now())) ORDER BY u.user_id";

                      $queryEhsPatient = @mysql_query($sqlehsPatient);
                      $numEhealthRow = @mysql_num_rows($queryEhsPatient);
                      if($numEhealthRow!= '0') {
                          $i=0;
                                while( $row = @mysql_fetch_array($queryEhsPatient)) {
                                        $days=$i.'~'.$row['days'];
                                        $patientArr[$days] = $row['user_id'];
                                        $i++;
                                }
                             
                                return  $patientArr;
                                
                      }

                }

        }

        
    function get_paitent_list($clinicId){
                 if($clinicId > 0) {
                $query = "select  u.user_id
		from user as u left join patient_subscription as ps on u.user_id=ps.user_id and ((ps.subs_status='1' and ps.paymentType='0') or (ps.subs_status='2' and subs_end_datetime >= now()) or (ps.subs_status='1'  and ps.paymentType='1' and subs_end_datetime > now()))
		inner join clinic_user as cu on u.user_id=cu.user_id
		and cu.clinic_id in (select clinic_id from clinic where (parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}') and ( status = 1 or status = 2 ) )
		inner join clinic  on clinic.clinic_id = cu.clinic_id and (clinic.status = '1' or clinic.status = '2')
		where u.usertype_id='1' and (u.status=1 or u.status = 2)";
                $queryEhsPatient = @mysql_query($query);
                $numEhealthRow = @mysql_num_rows($queryEhsPatient);
                      if($numEhealthRow!= '0') {
                                while( $row = @mysql_fetch_array($queryEhsPatient)) {
                                        $patientArr[] = $row['user_id'];
                                }
                             return  $patientArr;
                                
                      }

                }
    }
    
    function is_corporate($clinicId){
        $sql="Select corporate_ehs from clinic where clinic_id='{$clinicId}'";
        $res=  mysql_query($sql);
		$num=mysql_num_rows($res);
		if($num>0){
			$row=mysql_fetch_array($res);
                }
		return $row['corporate_ehs'];
}
        /**
         * Scheduler for the mass assignment of goal to EHS patients
         * @SKS 30th November 2011
         * @param string ''
         * return string ''
         */

        function schedulerEhsGoal($day,$pid,$clinicId) {
                
                 $query = "SELECT * FROM patient_goal where ehsGoal = '2' AND schduleday = {$day} and clinicId={$clinicId} AND status !='3'";
                 $result = @mysql_query($query);
                 $numRows = @mysql_num_rows($result);

                if($numRows > 0) { 
                         $sqlcheck="select * from automaticscheduling_content where patient_id={$pid} and clinic_id={$clinicId} and day={$day}";
                         $querycheck=  mysql_query($sqlcheck);
                         $rowcheck=  mysql_fetch_assoc($querycheck);
                         if($rowcheck['goal']==0){ 
                        
                        $i = 0; //$row = @mysql_fetch_assoc($result);
                        while($row =  @mysql_fetch_assoc($result)) { 
                        
                                
                                $created_by = $row['created_by'];
                                $goal = $row['goal'];
                                $clinicId = $row['clinicId'];
                                $patient_goal_id = $row['patient_goal_id'];
                                //$ehsPatientArr = getProviderEHSPatients($clinicId);
                               // while($pat < $patientCount) { 
                                        
                                                $data = array(
					                'parent_goal_id' => $row['patient_goal_id'],

					                'clinicId' => $row['clinicId'],
                                                        
                                                        'created_by' => $pid,

					                'goal' => $goal,

                                                        'status' => '1',

					                'created_on' => date("Y-m-d"),

					                'ehsGoal' => '2'

				                );

                                       $sqlinsert = "INSERT INTO patient_goal SET
							        parent_goal_id = '".$data['parent_goal_id']."',
							        clinicId = '".$data['clinicId']."',
							        goal = '".$data['goal']."',
							        status = '".$data['status']."',
							        created_by ='".$data['created_by']."',
							        created_on = '".$data['created_on']."',
                                                                scheduler_status = '2',
							        ehsGoal = '1',
                                                                assignday='".$day."'"; 

				        $result1 = @mysql_query($sqlinsert);

                                        $pat++;

                               // }

                       
                      
                       // $queryUpdate = "UPDATE patient_goal SET status='1' , scheduler_status = '2' where patient_goal_id = ".$patient_goal_id;
		      //  @mysql_query($queryUpdate);
                        
                          $i++;
                        
                }
                         
                    $sqlupdate="update automaticscheduling_content set goal=1 where id={$rowcheck['id']}";
                    $queryupdate=  mysql_query($sqlupdate);
                        }
                         
                        echo $i." Times Scheduler runs";   echo "<br>";                     
                        echo $pat."Goals added for EHS patients";

           }
                
        }

        /**
         * Mass EHS Reminder Addition
         * @SKS 30th November 2011
         * @param string ''
         * return string ''
         */

        function schedulerAddEhsReminder($day,$pid,$clinicId) {
                global $date ;
                 $query = "SELECT * FROM patient_reminder where ehsReminder = '2' AND schduleday = {$day} AND clinicId={$clinicId}";
                 $result = @mysql_query($query);
                 $numRows = @mysql_num_rows($result);

               if($numRows > 0) { 
                         $sqlcheck="select * from automaticscheduling_content where patient_id={$pid} and clinic_id={$clinicId} and day={$day}";
                         $querycheck=  mysql_query($sqlcheck);
                         $rowcheck=  mysql_fetch_assoc($querycheck);
                         if($rowcheck['reminder']==0){ 
                        
                        $i = 0;
                        while($i < $numRows) { 
                        
                                $row = @mysql_fetch_assoc($result);
                                $patient_reminder_id = $row['patient_reminder_id'];
                                $clinicId = $row['clinicId'];
                                $status = $row['status'];
                         //       while($pat < $patientCount) { 
                                        
                                                $data = array(
					                'parent_reminder_id' => $row['patient_reminder_id'],

					                'clinicId' => $row['clinicId'],
                                                        
                                                        'user_id' =>$row['user_id'],
                                                        
                                                        'patient_id' => $pid,

					                'reminder' => $row['reminder'],

                                                        'status' => '1',

					                'creation_date' => date("Y-m-d"),

                                                        'modified' => date("Y-m-d"),

					                'ehsReminder' => '2'

				                );

                                         $sqlinsert = "INSERT INTO patient_reminder SET
							        parent_reminder_id = '".$data['parent_reminder_id']."',
							        patient_id = '".$data['patient_id']."',
							        user_id = '".$data['user_id']."',
							        clinicId = '".$data['clinicId']."',
							        reminder ='".$data['reminder']."',
							        creation_date = '".$data['creation_date']."',
                                                                modified = '".$data['modified']."',
                                                                status = '1',
							        ehsReminder = '".$data['ehsReminder']."',
                                                                assignday='".$day."'";

				        $result1 = @mysql_query($sqlinsert);
                                        $insertId=  mysql_insert_id();
                                      //  $date = date("Y-m-d");
                    $sql = "select * from reminder_schedule where  DATEDIFF(DATE_FORMAT(scheduled_date,'%Y-%m-%d'),'{$date}') =0 and reminder_set=1 and patient_id=" .$pid;
                    $res = mysql_query($sql);
                    $num = mysql_num_rows($res);
                    if ($num == 0) {
                        $reminder_arr1 = array(
                            'patient_id' => $pid,
                            'therapistId' => 0,
                            'parent_reminder_schedule_id' => $insertId,
                            'scheduled_date' => $date,
                            'reminderEhsFlag' => '2',
                            'status' => 'pending',
                            'reminder_set' => 1,
                            'assignday'=>$day
                        );
                      $sqlreminder="INSERT INTO reminder_schedule SET
                            patient_id= '".$reminder_arr1['patient_id']."',
                            therapistId= '".$reminder_arr1['therapistId']."',
                            parent_reminder_schedule_id= '".$reminder_arr1['parent_reminder_schedule_id']."',
                            scheduled_date= '".$reminder_arr1['scheduled_date']."',
                            reminderEhsFlag= '".$reminder_arr1['reminderEhsFlag']."',
                            status= '".$reminder_arr1['status']."',
                            reminder_set= '".$reminder_arr1['reminder_set']."',
                            assignday='".$reminder_arr1['assignday']."'";
                        $res1=  mysql_query($sqlreminder);
                    }
                   
                                        $pat++;

                                //}

                        

                       // $queryUpdate = "UPDATE patient_reminder SET status = '1' , scheduler_status = '2' ,schdulerAction = '0' where patient_reminder_id = ".$patient_reminder_id;
		      //  @mysql_query($queryUpdate);

                        $i++;

                    }
                    
                     $sqlupdate="update automaticscheduling_content set reminder=1 where id={$rowcheck['id']}";
                     $queryupdate=  mysql_query($sqlupdate);
                     }
                        echo $i."Times scheduler run";
                        echo $pat. "Patient Reminders added by scheduler";echo "<br>";
                       
            }

         }



        /**
         * Mass EHS Reminder Deletion
         * @SKS 30th November 2011
         * @param string ''
         * return string ''
         */

        function schedulerDeleteEhsReminder() {
                
                 $query = "SELECT * FROM patient_reminder where scheduler_status = '1' AND status = '3' AND schdulerAction = '2'";
                 $result = @mysql_query($query);
                 $numRows = @mysql_num_rows($result);

                 if($numRows > 0) { 

                        $i = 0;
                        while($i < $numRows) { 
                        
                                $row = @mysql_fetch_assoc($result);
                                $patient_reminder_id = $row['patient_reminder_id'];
                                $clinicId = $row['clinicId'];
                                //$ehsPatientArr = getProviderEHSPatients($clinicId);
                                if(is_corporate($clinicId)==1){
                                    $ehsPatientArr = get_paitent_list($clinicId);
                                }else{
                                    $ehsPatientArr = getProviderEHSPatients($clinicId);
                                }
                                $patientCount = count($ehsPatientArr);
                                $pat = 0;        
                                
                                while($pat < $patientCount) { 
                             
                                        $sql1 = "DELETE FROM patient_reminder WHERE parent_reminder_id = '".$patient_reminder_id."'";
				        $result1 = @mysql_query($sql1);

                                        $pat++;
                                }

                                $sql = "DELETE FROM patient_reminder WHERE patient_reminder_id  = '".$patient_reminder_id."'";
				$result2 = @mysql_query($sql);
                             $i++;
                        }
                         echo $i."Times scheduler run";                       
                         echo $pat."Patients Reminders Deleted";
                }

        }


        /**
         * Scheduler for the mass assignment of articles to EHS patients
         * @SKS 30th january 2012
         * @param string ''
         * return string ''
         */

        function schedulerEhsArticle($days,$pid,$clinicId) {
            
        	global $txxchange_config,$application_path,$imagePath,$url,$from_email_address,$business_wx,$business_tx,$email_wx,$email_tx,$images_url,$telespineid,$business_telespine,$support_telespine,$loginurl;
                 
        	 $sendemail="no";
                 $query = "SELECT * FROM patient_article,article where patient_article.article_id=article.article_id and article.status=1 and ehsFlag = '2' AND patient_article.status='1' AND schdularday = {$days} AND clinicID={$clinicId}";
                 $result = @mysql_query($query);
                 $numRows = @mysql_num_rows($result);
                if($numRows > 0) {
                       $sqlcheck="select * from automaticscheduling_content where patient_id={$pid} and clinic_id={$clinicId} and day={$days}";
                         $querycheck=  mysql_query($sqlcheck);
                         $rowcheck=  mysql_fetch_assoc($querycheck);
                         if($rowcheck['article']==0){ 
                       $i = 0; 
                        while($row =  @mysql_fetch_assoc($result)) { 
                                $patientArticleId = $row['patientArticleId'];
                                $article_id = $row['article_id'];
                                $clinicID = $row['clinicID'];
                                $therapistId = $row['therapistId'];
                                $creationDate = date('Y-m-d H:i:s');
                                $modiefiedDate = date('Y-m-d H:i:s');
                                //$ehsPatientArr = getProviderEHSPatients($clinicID);
                                //while($pat < $patientCount) {  
                                    $data = array(
						                'article_id' => $article_id,
    	                                'patient_id' => $pid,
                                        'clinicID' => $clinicID,
                                        'therapistId' => $therapistId,
                                        'creationDate' => date('Y-m-d H:i:s'),
                                        'modiefiedDate' => date('Y-m-d H:i:s'),
                                        'ehsFlag' => '2',
					'status' => 1
				        ); 

                              $sqlinsert = "INSERT INTO patient_article SET 
							        	article_id = '".$data['article_id']."',
							        	clinicId = '".$data['clinicID']."',
							        	patient_id = '".$data['patient_id']."',
                                                                        therapistId = '".$data['therapistId']."',
							        	status = '".$data['status']."',
							        	creationDate ='".$data['creationDate']."',
							        	modiefiedDate = '".$data['modiefiedDate']."',
                                                                        scheduler_status = '2',
                                                                        parentArticleId ='".$row['patientArticleId']."',
							        	ehsFlag = '2',
                                                                        assignday='".$days."'"; //echo "</br>";

				        	$result1 = @mysql_query($sqlinsert);
                           // $pat++;  
                            $sendemail="yes";  
                        	//}
	                        // $queryUpdate = "UPDATE patient_article SET status='1' , scheduler_status = '0' where patientArticleId = ".$patientArticleId;//echo "<br>";
                                //@mysql_query($queryUpdate);
                        $i++;
                }
                    $sqlupdate="update automaticscheduling_content set article=1 where id={$rowcheck['id']}";
                    $queryupdate=  mysql_query($sqlupdate);
                         
				if($sendemail=='yes'){
                                   
                                    if($clinicId ==   $telespineid){
                                       
                                      /*  $sql = "SELECT username,AES_DECRYPT(UNHEX(name_first),'{$txxchange_config['private_key']}') as name_first,AES_DECRYPT(UNHEX(name_last),'{$txxchange_config['private_key']}') as name_last FROM user WHERE user_id={$pid}";
                                        $res1 = @mysql_query($sql);
                                        $rw1 = @mysql_fetch_array($res1);
                                        $username = $rw1['username'];
                                        $fullname=$rw1['name_first'].' '.$rw1['name_last'];
                                        $to = $fullname.'<'.$username.'>';
                                        $data = array(
                                                    'url' => $url,
                                                    'images_url' => $images_url,
                                                    'clinic_name'=>$clinicName,
                                                    'business_url'=>$business_telespine,
                                                    'support_email'=>$support_telespine,
                                                    'fullname'=>$fullname,
                                                    'loginurl'=>$loginurl
                                                    );
			                $message = build_template($application_path."mail_content/telespine/New_Article_Assigned_Email_Notification.php",$data);
                                        
                                        $subject = "Telespine Update - New Article";			
                                        // To send HTML mail, the Content-type header must be set
                                        $headers  = 'MIME-Version: 1.0' . "\n";
                                        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                                        $headers .= "From: Telespine Support <support@telespine.com>" . "\n";
                                        $returnpath = "-fsupport@telespine";
                                        @mail($to, $subject, $message, $headers, $returnpath);	*/
                                }else{
                                    
					//$ehsPatientArr = getProviderEHSPatients($clinicID);
                                    		$clinicName=html_entity_decode(get_clinic_info($pid,'clinic_name'), ENT_QUOTES, "UTF-8");
						$clinicId  =   get_clinic_info($pid);
						$clinic_channel=getchannel($clinicId);
						
				if($clinic_channel==2){
        			$business_url=$business_wx; 
                                $support_email=$email_wx;
				}else{
                                $business_url=$business_tx; 
                                $support_email=$email_tx;   
                    }
                $data = array(
				'url' => $url,
				'images_url' => $images_url,
                                'clinic_name'=>$clinicName,
				'business_url'=>$business_url,
                                'support_email'=>$support_email
				);
			if( $clinic_channel == '2'){
		   		$message = build_template($application_path."mail_content/wx/new_article_assign.php",$data);
                        }else{
                                $message = build_template($application_path."mail_content/new_article_assign.php",$data);	
                        }
	      	$sql = "select username from user where user_id =".$pid." and status='1'" ;
                $res1 = @mysql_query($sql);
                $rw1 = @mysql_fetch_array($res1);
                $username = $rw1['username'];
			$to = $username;
			$subject = "Suggested Reading from ".$clinicName;			
		// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
		if( $clinic_channel == 2){
			$headers .= "From: ".setmailheader($clinicName). " <do-not-reply@txxchange.com>" . "\n";
			$returnpath = "-fdo-not-reply@txxchange.com";   
			
		}else{
			$headers .= "From: ".setmailheader($clinicName). " <do-not-reply@txxchange.com>" . "\n";
			$returnpath = "-fdo-not-reply@txxchange.com";
		}
		// Mail it
                
					
                                        @mail($to, $subject, $message, $headers, $returnpath);	
                                        
				}
                         }
                }
                echo $i." Times Scheduler runs";   echo "<br>";                     
                echo $pat."Articles added for EHS patients";
                }
                
                
                
  }    


        /**
         * Mass EHS Reminder Deletion
         * @SKS 30th November 2011
         * @param string ''
         * return string ''
         */

        function schedulerDeleteEhsArticles() {
                
                $query = "SELECT * FROM patient_article where scheduler_status = '1' AND schdulerAction = '2'";
                 $result = @mysql_query($query);
                 $numRows = @mysql_num_rows($result);

                 if($numRows > 0) { 

                        $i = 0;
                        while($i < $numRows) { 
                        		
                                $row = @mysql_fetch_assoc($result);
                                $patientArticleId = $row['patientArticleId'];
                                 $clinicID = $row['clinicID'];
                                //$ehsPatientArr = getProviderEHSPatients($clinicID);
                                 if(is_corporate($clinicId)==1){
                                    $ehsPatientArr = get_paitent_list($clinicID);
                                }else{
                                    $ehsPatientArr = getProviderEHSPatients($clinicID);
                                }
                                $patientCount = count($ehsPatientArr);
                                $pat = 0;        
                                
                                while($pat < $patientCount) { 
                             
                                         $sql1 = "DELETE FROM patient_article WHERE patient_id = '".$ehsPatientArr[$pat]."' and parentArticleId= '".$patientArticleId."' AND ehsFlag = '1'";
				        				$result1 = @mysql_query($sql1);

                                        $pat++;
                                }

                                 $sql = "DELETE FROM patient_article WHERE patientArticleId  = '".$patientArticleId."' AND ehsFlag = '1'";
								$result2 = @mysql_query($sql);
                             $i++;
                        }
                         echo $i."Times scheduler run";   echo "<br>";                      
                         echo $pat."Patients Articles Deleted";
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
		
          function get_reminder_automaticschedule($user_id,$reminder_set,$reminderEhsFlag,$assignday){
            global $privatekey;	
            if($user_id != ""){
			echo $query = "select * from patient_reminder where patient_id = '{$user_id}' and reminder_set='{$reminder_set}' and ehsReminder='{$reminderEhsFlag}' and assignday={$assignday} ORDER BY patient_reminder_id DESC ";
			$result = @mysql_query($query);
                        echo '<br>';
                        echo @mysql_num_rows($result);
                        echo '<br>';
			if(@mysql_num_rows($result) > 0){
				$reminder = "<ul>";
				while( $row = @mysql_fetch_array($result) ){
					$reminder .= "<li>" . decrypt_data($row['reminder'],$privatekey) . "</li>";
				}
				$reminder .= "</ul>";
                        //        echo $reminder;
				return $reminder;
			}
			return "";
		}
		return "";
            
            
        }
    
    
		automatic_scheduling_assign();
                /*schedulerEhsPlanAssignment();//Scheduler for Ehs Plan Assignment
                schedulerEhsGoal(); //Scheduler for EHs Goal Addition
                schedulerEhsArticle();
                schedulerEhsreminder();
            //    schedulerAddEhsReminder();*/


?>