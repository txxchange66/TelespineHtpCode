<?php

require_once("config.php"); 
$host = $txxchange_config['dbconfig']['db_host_name'];
$user = $txxchange_config['dbconfig']['db_user_name'];
$pass = $txxchange_config['dbconfig']['db_password'];
$db   = $txxchange_config['dbconfig']['db_name'];
$application_path   = $txxchange_config['application_path'];
$templatePath = $application_path."mail_content/mass_patient_upload_mail.php";
$templatePathMassMessage = $application_path."mail_content/mass_message_mail_aa.php";
$templatePathwx = $application_path."mail_content/wx/mass_patient_upload_mail.php";
$templatePathMassMessagewx = $application_path."mail_content/wx/mass_message_mail_aa.php";

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
		/**
		 * Function for fetching relevant CSV file from database and uploading records from csv, and notifying   the AA of successfull records creation
		 * @access public
		 */
		function uploadPatientList(){
            global $from_email_address,$templatePath,$imagePath,$txxchange_config,$url,$application_path,$templatePathwx;
			$query = "SELECT * FROM mass_uploaded_files WHERE status ='1' LIMIT 1";
			$result = @mysql_query($query); 
			// taking every file's data
			if(@mysql_num_rows($result)){
				while ($row = @mysql_fetch_array($result)) 
				{
					//fill in data for mail content.
					$data1 = array() ;
                    $data1['images_url'] = $imagePath;
                    $user_id = $row['user_id'];
					$newfilename = $row['file_name'];
					$id = $row['id'];
					$sql = "select username,therapist_access from user where user_id =".$user_id;
					$res1 = @mysql_query($sql);
					$rw1 = @mysql_fetch_array($res1); 
					$username = $rw1['username'];
					$therapist_access = $rw1['therapist_access'];
					//changing the status to 2 so show that its under processing.
					$query="UPDATE mass_uploaded_files SET status='2' where id=".$id;
					@mysql_query($query);
						//code block to read csv file and insert into database stars here	
						$target_path=$application_path.'patient_lists_uploaded/'.$newfilename;
						$i = 0;
						$handle = fopen($target_path, "rb");
						$str="";
						$errorCount=0;
						$errorFormat=0;
						while (($data = fgetcsv($handle, 100000, ",")) !== FALSE) {
							//print_r($data);
							if($i==0 && (strtolower(trim($data[0]))!="first name" || strtolower(trim($data[1]))!="last name" || strtolower(trim($data[2]))!="email address" || strtolower(trim($data[3]))!="patient/client status" || strtolower(trim($data[4]))!="subscribe/unsubscribe" || strtolower(trim($data[5]))!="e-health service"))
							{$errorFormat=1;
							//echo 'hi'; 
							$str.="<li><span style='color:#FF0000;'>Heading Record - One of the column headings does not match the required headings. See example template.</span></li>";
							 $errorCount++;
							}
							if($i==1 && (strtolower(trim($data[0]))=="required" || strtolower(trim($data[1]))=="required"  || strtolower(trim($data[2]))=="required" || strtolower(trim($data[3]))=="required" || strtolower(trim($data[4]))=="required" || strtolower(trim($data[5]))=="required" ))
							{	continue;
							}
							if($errorFormat==0 && $i>0 ){
								if(trim($data[0])=="" and trim($data[1])=="" and trim($data[2])=="" and trim($data[3])=="" and trim($data[4])=="" and trim($data[5])=="" and trim($data[6])=="" and trim($data[7])=="" and trim($data[8])=="" and trim($data[9])=="" and trim($data[10])=="" and trim($data[11])=="" and trim($data[12])==""  and trim($data[13])==""  and trim($data[14])=="") 
									continue;
							}

							if($errorFormat==0 && $i>0 )
							{		
									$status=0;
									$mass_message_access=0;
									$ehsstatus=0;
                                    if(strtolower(trim($data[3]))=="current")
										$status=1;
									elseif(strtolower(trim($data[3]))=="discharged")
										$status=2;
									if(strtolower(trim($data[4]))=="subscribe")
										$mass_message_access=1;
									elseif(strtolower(trim($data[4]))=="unsubscribe")
										$mass_message_access=2;
                                    if(strtolower(trim($data[5]))=="yes"){
                                        $ehs=1;
                                        $ehsstatus=1;
                                    }
                                    elseif(strtolower(trim($data[5]))=="no"){
                                        $ehs=0;
                                        $ehsstatus=2;
                                    }
                                    if($status==0){
                                     $str.="<li><span style='color:#FF0000;'>Record no. ".($i)."- The status must be either current or discharged.</span></li>";
									 $errorCount++;   
                                    }
									elseif($mass_message_access==0){
                                     $str.="<li><span style='color:#FF0000;'>Record no. ".($i)."- The status must be either subscribe or unsubscribe.</span></li>";
									 $errorCount++;   
                                    }
									elseif(empty($data[0]))
									{	$str.="<li><span style='color:#FF0000;'>Record no. ".($i)."- The first name field is missing.</span></li>";
										$errorCount++;
									}
									elseif(empty($data[1]))
									{	$str.="<li><span style='color:#FF0000;'>Record no. ".($i)."- The last name field is missing.</span></li>";
										$errorCount++;
									}
									elseif(empty($data[2]))
									{	$str.="<li><span style='color:#FF0000;'>Record no. ".($i)."- The email field is missing.</span></li>";
										$errorCount++;
									}
									elseif(empty($data[3]))
									{	$str.="<li><span style='color:#FF0000;'>Record no. ".($i)."- The status field is missing.</span></li>";
										$errorCount++;
									}
									elseif(empty($data[4]))
									{	$str.="<li><span style='color:#FF0000;'>Record no. ".($i)."- The subscribe/unsubscribe field is missing.</span></li>";
										$errorCount++;
									}
							        elseif(empty($data[5]))
                                    {   $str.="<li><span style='color:#FF0000;'>Record no. ".($i)."- The E-Health Service field is missing.</span></li>";
                                        $errorCount++;
                                    }
							       elseif($ehsstatus==0){
                                        $str.="<li><span style='color:#FF0000;'>Record no. ".($i)."- The E-Health Service must be either Yes or No.</span></li>";
                                     $errorCount++;   
                                    }
									elseif(validateEmail(trim($data[2]))==false)
									{	$str.="<li><span style='color:#FF0000;'>Record no. ".($i)."- The email address format is incorrect.</span></li>";
										$errorCount++;
									}
									elseif(checkUserNameExist(trim($data[2]))>0)
									{	$str.="<li><span style='color:#FF0000;'>Record no. ".($i)."- The email address already exists in the system.</span></li>";
										$errorCount++;
									}
									else
									{	//include_once("template/therapist/therapistArray.php");
										$tableArray['username'] = trim($data[2]);
										$tableArray['password'] = strtolower(trim($data[1])).'01';
										$tableArray['refering_physician'] = trim($data[14]);
										$tableArray['name_first'] = trim($data[0]);
										$tableArray['name_last'] = trim($data[1]);
										$tableArray['mass_message_access'] = $mass_message_access;
										$tableArray['address'] = trim($data[6]);
										$tableArray['address2'] = trim($data[7]);
										$tableArray['city'] = trim($data[8]);
										$tableArray['state'] = trim($data[9]);
										$tableArray['zip'] = trim($data[10]);
										$country='';
										if(substr(strtolower($data[11]),0,1)=='u'){
											$country='US';
										}
										else if(substr(strtolower($data[11]),0,1)=='c'){
											$country='CAN';
										}
										else{
											$country='';
										}
										$tableArray['country'] = $country;
										$tableArray['phone1'] = trim($data[12]);
										$tableArray['phone2'] = trim($data[13]);
										$tableArray['status'] = $status;
										$tableArray['usertype_id'] = 1;
										$tableArray['creation_date'] = date('Y-m-d H:i:s');
										$tableArray['modified'] = date('Y-m-d H:i:s');
										//echo "<pre/>";print_r($tableArray);
										$insertQuery="INSERT INTO user SET  username='".$tableArray['username']."',password='".encrypt_data($tableArray['password'])."',refering_physician='".$tableArray['refering_physician']."',name_first='".$tableArray['name_first']."',name_last='".$tableArray['name_last']."',mass_message_access='".$tableArray['mass_message_access']."',address='".encrypt_data($tableArray['address'])."',address2='".encrypt_data($tableArray['address2'])."',city='".encrypt_data($tableArray['city'])."',state='".encrypt_data($tableArray['state'])."',zip='".encrypt_data($tableArray['zip'])."',country='".$tableArray['country']."',phone1='".encrypt_data($tableArray['phone1'])."',phone2='".encrypt_data($tableArray['phone2'])."',status='".$tableArray['status']."',usertype_id='1',creation_date='".$tableArray['creation_date']."',modified='".$tableArray['modified']."',ehs='".$ehs."'";
										$insRes=@mysql_query($insertQuery);
										 $clinic_id=clinicInfo('clinic_id',$user_id);
										 $providerList=getTherapistlist($clinic_id);
						                $new_patient_id=mysql_insert_id();
						                if($insRes)
										{		
											if($providerList!=''){
											$providerlistarray=explode(',',$providerList);
											foreach($providerlistarray as $key=>$val){
											$therapistArray = array(
												'therapist_id' => $val,
												'patient_id' => $new_patient_id,
												'creation_date' => date('Y-m-d H:i:s'),
												'modified' => date('Y-m-d H:i:s'),
												'status' => '1'
												);
												$new_patient_id = $therapistArray['patient_id'];
											
												{	
												$thInsert="INSERT INTO therapist_patient SET  therapist_id='".$therapistArray['therapist_id']."',patient_id='".$therapistArray['patient_id']."',creation_date='".$therapistArray['creation_date']."',status='".$therapistArray['status']."',modified='".$therapistArray['modified']."'";
												@mysql_query($thInsert);	
												}
											  }
											}
											
											$clinicArray = array(
															'clinic_id' => 	clinicInfo('clinic_id',$user_id),
															'user_id'	=>  $new_patient_id,
														 );
											// Insert row in clinic_user table to set relation between clinic and patient				 
											$clInsert="INSERT INTO clinic_user SET  clinic_id='".$clinicArray['clinic_id']."',user_id='".$clinicArray['user_id']."'";
											@mysql_query($clInsert);	
											$clinicName	=	html_entity_decode(get_clinic_info($new_patient_id,"clinic_name"), ENT_QUOTES, "UTF-8");
											if($tableArray['status']==1){
											$clinic_channel=getchannel($clinicArray['clinic_id']);
											if($clinic_channel==2){
                                               		$business_url=$txxchange_config['business_wx']; 
					                                $support_email=$txxchange_config['email_wx'];    
												}else{
					                            	$business_url=$txxchange_config['business_tx']; 
                                                	$support_email=$txxchange_config['email_tx'];
                                                }
											$patientReplace = array(
														'username' => $tableArray['username'],
														'password' => $tableArray['password'],
														'url' => $url,
														'images_url' => $imagePath,
														'clinic_name' => $clinicName,
											            'business_url'=>$business_url,
                                                        'support_email'=>$support_email,
                                                        'name'=>$tableArray['name_first']
											
												);
										 
										  if( $clinic_channel == 2)
											$message = build_template($application_path."mail_content/wx/create_new_patient_mail.php",$patientReplace);
										  else
											$message = build_template($application_path."mail_content/create_new_patient_mail.php",$patientReplace);
											$to = $tableArray['username'];
											$clinicName	=	html_entity_decode(get_clinic_info($new_patient_id,"clinic_name"), ENT_QUOTES, "UTF-8");
											$subject = "Your ".$clinicName." Health Record";
											// To send HTML mail, the Content-type header must be set
											$headers  = 'MIME-Version: 1.0' . "\n";
											$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
											/*$headers .= 'Bcc: jde968@yahoo.com' . "\r\n";  
											$headers .= 'Bcc: puneet.mathur@hytechpro.com' . "\r\n";
											$headers .= 'Bcc: jepstein@txxchange.com' . "\r\n";  */  
											//$headers .= "From: " .$clinicName. "<support@txxchange.com>" . "\n";
											//$returnpath = '-fsupport@txxchange.com';
											if( $clinic_channel == 2){
										    $headers .= "From: ".$clinicName. " <".$txxchange_config['email_wx'].">" . "\n";
										    $returnpath = '-f'.$txxchange_config['email_wx'];
											}else{
										    $headers .= "From: ".$clinicName. " <".$txxchange_config['email_tx'].">" . "\n";
										    $returnpath = "-f".$txxchange_config['email_tx'];   
										    }
											// Mail it
											//echo $message;
											 @mail($to, $subject, $message, $headers, $returnpath);
											}
										}

									}
							}
							$i++;
							
						}//CSV reading loops ends here
						fclose($handle);
						//code block to read csv file and insert into database ends here
						if(isset($errorCount))
						{	if($errorFormat==1)$errorCount=($i-1);
							$replace['successnum']=($i-1)-$errorCount;
							$replace['totalnum']=($i-1);
							if($errorCount>0)
							{	$replace['failnum']="There was a problem with ".$errorCount." of them:";
								$custommessage="Please update the records that contain a problem and upload a new CSV file with <u>only</u> the updated records.";
							}
							$replace['statusMessage']=$str;
							$replace['custommessage']=$custommessage;
							$clinic_channel=getchannel(clinicInfo('clinic_id',$user_id));
						
                            if($clinic_channel==1){
                                 $business_url=$txxchange_config['business_tx']; 
                                 $support_email=$txxchange_config['email_tx'];
                            }else{
                                 $business_url=$txxchange_config['business_wx']; 
                                 $support_email=$txxchange_config['email_wx'];   
                            }
                            $replace['business_url'] = $business_url;
                            $replace['support_email'] = $support_email;
                                                
							$message = build_template($application_path."template/headAccountAdminPatient/uploadPatientListStatus.php",$replace);
						}
					echo $message;		
					//changing the status to 3 so show that processing is completed.
					$query="UPDATE mass_uploaded_files SET status='3',message='".addslashes($message)."' where id=".$id;
					@mysql_query($query);

					//code section for sending of mail.
					$clinicName	=	html_entity_decode(get_clinic_info($user_id,"clinic_name"), ENT_QUOTES, "UTF-8");
					$clinic_channel=getchannel(clinicInfo('clinic_id',$user_id));
				    if($clinic_channel==1){
                        $business_url=$txxchange_config['business_tx']; 
                        $support_email=$txxchange_config['email_tx'];
                     }else{
                        $business_url=$txxchange_config['business_wx']; 
                        $support_email=$txxchange_config['email_wx'];   
                     }
                     $data1['business_url'] = $business_url;
                     $data1['support_email'] = $support_email;
                    if( $clinic_channel == 1){
						$message = build_template($templatePath,$data1);
                    $subject = "Tx Xchange File Upload Complete";
                    }
					else{
					   $message = build_template($templatePathwx,$data1);
					$subject = "Wholemedx File Upload Complete";
					}	
	                $to = $username;
					
					// To send HTML mail, the Content-type header must be set
					$headers  = 'MIME-Version: 1.0' . "\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
					//$headers .= "From: Tx Xchange Support<support@txxchange.com>" . "\n";
					//$returnpath = '-fsupport@txxchange.com';
					/*$headers .= 'Bcc: jde968@yahoo.com' . "\r\n";  
											$headers .= 'Bcc: puneet.mathur@hytechpro.com' . "\r\n";
											$headers .= 'Bcc: jepstein@txxchange.com' . "\r\n"; */
				    if( $clinic_channel == 2){
                        $headers .= "From: ".$clinicName. " <".$txxchange_config['email_wx'].">" . "\n";
                        $returnpath = '-f'.$txxchange_config['email_wx']; 
				    	
                    }else{
                        $headers .= "From: ".$clinicName. " <".$txxchange_config['email_tx'].">" . "\n";
                        $returnpath = "-f".$txxchange_config['email_tx'];  
                    }
					//echo $message;
					// Mail it
					@mail($to, $subject, $message, $headers, $returnpath);

				}//outermost while ends here.
			}//mysql_num_rows if condition ends here	
		}//Function ends here
		
		
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

			 /**
			 * Function send mass messages asynchronously.
			 *
			 * @access public
			 * @return: string	
			 */
			function mass_message()
			{
				 global $from_email_address,$templatePathMassMessage,$imagePath,$txxchange_config,$url,$application_path,$templatePathMassMessagewx;
				//-1 for all Current patient -2, for all Discharged patient, -3 for All Patients -4 for ehs paitent
				// u.mass_message_access=1 is used to send messages to patients who had not opted out for mass message
				$sqlmassMessage="SELECT * FROM mass_message WHERE status=1";
				$sqlresult=@mysql_query($sqlmassMessage);
				$numrows=@mysql_num_rows($sqlresult);
				
				if($numrows >0)
				{
					while($resultMassMessage=@mysql_fetch_array($sqlresult))
					{
						if($resultMassMessage['status']==1)
						{
							 $data1 = array() ;
							 $data1['images_url'] = $imagePath;
							 $user_id=$resultMassMessage['user_id'];
							 $sql = "select username,therapist_access from user where user_id =".$user_id;
							 $res1 = @mysql_query($sql);
							 $rw1 = @mysql_fetch_array($res1); 
							 $username = $rw1['username'];
							
							$sqlupdate="update mass_message set status=2 where id=".$resultMassMessage['id'];
							@mysql_query($sqlupdate);
							
							$clinicId=clinicInfo('clinic_id',$user_id);
							if($resultMassMessage['sender_type']== -1){
								$sql="select  u.user_id from user as u 
								inner join clinic_user as cu on u.user_id=cu.user_id  
								and cu.clinic_id in (select clinic_id from clinic where (parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}') and ( status = 1 or status = 2 ) ) 
								inner join clinic  on clinic.clinic_id = cu.clinic_id and (clinic.status = 1 or clinic.status = 2) 
								where u.usertype_id='1' and (u.status=1) and u.mass_message_access=1";
								$flag=-1;   
							}	 
							if($resultMassMessage['sender_type']== -2){
								$sql="select  u.user_id from user as u 
								inner join clinic_user as cu on u.user_id=cu.user_id  
								and cu.clinic_id in (select clinic_id from clinic where (parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}') and ( status = 1 or status = 2 ) ) 
								inner join clinic  on clinic.clinic_id = cu.clinic_id and (clinic.status = 1 or clinic.status = 2) 
								where u.usertype_id='1' and (u.status = 2) and u.mass_message_access=1";
								$flag=-2;
							}
							if($resultMassMessage['sender_type']== -3){
								$sql="select  u.user_id from user as u 
								inner join clinic_user as cu on u.user_id=cu.user_id  
								and cu.clinic_id in (select clinic_id from clinic where (parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}') and ( status = 1 or status = 2 ) ) 
								inner join clinic  on clinic.clinic_id = cu.clinic_id and (clinic.status = 1 or clinic.status = 2) 
								where u.usertype_id='1' and (u.status=1 or u.status = 2) and u.mass_message_access=1";
								$flag=-3;   
							} 
						  if($resultMassMessage['sender_type']== -4){
                                                      if(is_corporate($clinicId)==1){
                                                           $sql = "select  u.user_id from user as u 
                                                           left join patient_subscription as ps on u.user_id=ps.user_id and ((ps.subs_status='1' and ps.paymentType='0') or 
                                                           (ps.subs_status='2' and subs_end_datetime >= now()) or (ps.subs_status='1'  and ps.paymentType='1' and subs_end_datetime > now()))
                                                           inner join clinic_user as cu on u.user_id=cu.user_id and cu.clinic_id in (select clinic_id from clinic 
                                                           where (parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}') and ( status = 1 or status = 2 ) ) 
                                                           inner join clinic  on clinic.clinic_id = cu.clinic_id and (clinic.status = '1' or clinic.status = '2') where u.usertype_id='1' and (u.status=1 or u.status = 2)";
                                                        }else{
                                                            $sql="SELECT  u.user_id FROM user as u 
                                                                inner join clinic_user as cu on u.user_id=cu.user_id
                                                                AND cu.clinic_id in (SELECT clinic_id FROM clinic WHERE parent_clinic_id = {$clinicId} OR clinic_id = {$clinicId}  and ( status = 1 or status = 2 ) ) 
                                                                INNER JOIN clinic on clinic.clinic_id = cu.clinic_id AND (clinic.status = 1 or clinic.status = 2) 
                                                                LEFT JOIN patient_subscription AS ps ON u.user_id = ps.user_id 
                                                                WHERE u.usertype_id=1 and (u.status = 1 OR u.status = 2) and ((ps.subs_status='1' and subs_end_datetime > now()) 
                                                                or (ps.subs_status='2' and subs_end_datetime > now()))";
                                                        }          
                                                      
                                                                
                
                                                                $flag=-4;   
                            } 
							//echo $sql;
                            //exit;
							if($flag != 0)
							{
								$result_patient = @mysql_query($sql);
								if(@mysql_num_rows($result_patient)> 0 )
								{
									$clinic_query="select clinic_id from clinic_user where user_id=".$resultMassMessage["user_id"];
									$clinic_result=@mysql_query($clinic_query);
									$clinic=@mysql_fetch_array($clinic_result);
									  
									$i=0;
									while($row_patient_id=@mysql_fetch_array($result_patient))
									{
										if($i==0) 
										{
											$data = array(
											'patient_id'	=> $row_patient_id["user_id"],
											'sender_id'		=> 	$resultMassMessage["user_id"],
											'subject'		=> 	$resultMassMessage['subject'],
											'content'		=> 	$resultMassMessage['message'],
											'parent_id'		=> 	'0',
											'sent_date'		=> 	$resultMassMessage['messageDate'],
											'recent_date'	=>	$resultMassMessage['messageDate'],
											'replies'		=> '0',
											'mass_message'	=> $clinic['clinic_id'],
											'mass_patient_id'=>$flag,
											'mass_status'=>	1
											);
											$sqlinsert="INSERT INTO message set 
														patient_id='".$data['patient_id']."',
														sender_id='".$data['sender_id']."',
														subject='".$data['subject']."',
														content='".$data['content']."',
														parent_id='".$data['parent_id']."',
														sent_date='".$data['sent_date']."',
														recent_date='".$data['recent_date']."',
														replies='".$data['replies']."',
														mass_message='".$data['mass_message']."',
														mass_patient_id='".$data['mass_patient_id']."',
														mass_status='".$data['mass_status']."'";
											$result=@mysql_query($sqlinsert);
										}
									$i=$i+1;
									$data = array(
										'patient_id'	=> $row_patient_id["user_id"],
										'sender_id'		=> 	$resultMassMessage["user_id"],
										'subject'		=> 	$resultMassMessage['subject'],
										'content'		=> 	$resultMassMessage['message'],
										'parent_id'		=> 	'0',
										'sent_date'		=> 	$resultMassMessage['messageDate'],
										'recent_date'	=>	$resultMassMessage['messageDate'],
										'replies'		=> '0',
										'mass_message'	=> $clinic['clinic_id'],
										'mass_patient_id'=>$flag
										);
										$sqlinsert="INSERT INTO message set 
													patient_id='".$data['patient_id']."',
													sender_id='".$data['sender_id']."',
													subject='".$data['subject']."',
													content='".$data['content']."',
													parent_id='".$data['parent_id']."',
													sent_date='".$data['sent_date']."',
													recent_date='".$data['recent_date']."',
													replies='".$data['replies']."',
													mass_message='".$data['mass_message']."',
													mass_patient_id='".$data['mass_patient_id']."'";
										$result=@mysql_query($sqlinsert);
										if($result)
										{
											$message_id = mysql_insert_id();
											// Entry for Therapist
											$data = array(
												'message_id' => $message_id,
												'user_id' => $user_id,
												'unread_message' => '2'
											);
											//$this->insert("message_user",$data);
											$sqlMessageUser="INSERT INTO message_user SET 
											message_id		='".$data['message_id']."',
											user_id			='".$data['user_id']."',
											unread_message	='".$data['unread_message']."'";
											$resultMessageUser=@mysql_query($sqlMessageUser);
											// Entry for Patient
											$data = array(
												'message_id' => $message_id,
												'user_id' => $row_patient_id["user_id"],
												'unread_message' => '1'
											);
											$sqlMessageUser="INSERT INTO message_user SET 
											message_id		='".$data['message_id']."',
											user_id			='".$data['user_id']."',
											unread_message	='".$data['unread_message']."'";
											$resultMessageUser=@mysql_query($sqlMessageUser);
											if( $row_patient_id["user_id"] != "" && is_numeric($row_patient_id["user_id"] ))
											{
												mass_message_new_post_notification($row_patient_id["user_id"]);
											}
										}
									}
									$sqlupdate="update mass_message set status=3 where id=".$resultMassMessage['id'];
									@mysql_query($sqlupdate);
									//code section for sending of mail.
									$clinic_channel=getchannel($clinic['clinic_id']);
								    if($clinic_channel==1){
                                        $business_url=$txxchange_config['business_tx']; 
                                        $support_email=$txxchange_config['email_tx'];
                                    }else{
                                        $business_url=$txxchange_config['business_wx']; 
                                        $support_email=$txxchange_config['email_wx'];   
                                    }
                                    $data1['business_url'] = $business_url;
                                    $data1['support_email'] = $support_email;
                                    if( $clinic_channel == 1){
									   $message = build_template($templatePathMassMessage,$data1);
									   $subject = "Tx Xchange Message Sent";
                                    }else{
									   	$message = build_template($templatePathMassMessagewx,$data1);
									   	$subject = "Wholemedx Message Sent";
                                    }
								   $to = $username;
								   
								// To send HTML mail, the Content-type header must be set
								   $headers  = 'MIME-Version: 1.0' . "\n";
								   $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
								   //$headers .= "From: Tx Xchange Support<support@txxchange.com>" . "\n";
								   //$returnpath = '-fsupport@txxchange.com';
								  /* $headers .= 'Bcc: jde968@yahoo.com' . "\r\n";  
											$headers .= 'Bcc: puneet.mathur@hytechpro.com' . "\r\n";
											$headers .= 'Bcc: jepstein@txxchange.com' . "\r\n"; */
								if( $clinic_channel == 1){
                                $headers .= "From:  <".$txxchange_config['email_tx'].">" . "\n";
                                $returnpath = "-f".$txxchange_config['email_tx'];
                                }else{
                                $headers .= "From: <".$txxchange_config['email_wx'].">" . "\n";
                                $returnpath = '-f'.$txxchange_config['email_wx'];   
                                }
								   //echo $message;
								   // Mail it
								   @mail($to, $subject, $message, $headers, $returnpath);

									
									
									  $error[] = "Your message is successfully posted to ".$user;
								}else
								{
									   $error[] = "There is no patient Associated with you."; 
								}
							}
						}
					}
					
					//mail to user 
				}
			}
		
		 /**
		 * This function formats the E-mail and send it to user.
		 * @param numeric $user_id
		 * @return none
		 * @access public
		 */
		function mass_message_new_post_notification( $user_id = "" ){
			global $from_email_address,$templatePath,$imagePath,$txxchange_config,$url,$application_path;
			$sql = "select username from user where user_id =".$user_id." and status='1'" ;
			$res1 = @mysql_query($sql);
			$rw1 = @mysql_fetch_array($res1); 
			$username = $rw1['username'];
            if( $user_id != "" ){
                $clinicId	=	get_clinic_info($user_id);
                $clinicName	=	 html_entity_decode(get_clinic_info($user_id  ,"clinic_name"), ENT_QUOTES, "UTF-8");
                $clinic_channel=getchannel($clinicId);
           if($clinic_channel==1){
               $business_url=$txxchange_config['business_tx']; 
               $support_email=$txxchange_config['email_tx'];
           }else{
               $business_url=$txxchange_config['business_wx']; 
               $support_email=$txxchange_config['email_wx'];   
           }
                $data = array(
						'url' => $url,
						'images_url' => $imagePath,
                        'clinicName'=>$clinicName,
                        'business_url'=>$business_url,
                        'support_email'=>$support_email
						);
				$subject = "Message from ".$clinicName;
				
				if($clinic_channel==1)
	            $message = build_template($application_path."mail_content/mass_new_message.php",$data);
	             else
	             $message = build_template($application_path."mail_content/wx/mass_new_message.php",$data);
	            $to = $username;
				// To send HTML mail, the Content-type header must be set
				$headers  = 'MIME-Version: 1.0' . "\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
				$headers .= "From: ".setmailheader($clinicName)." <do-not-reply@txxchange.com>\n";
				/*$headers .= 'Bcc: jde968@yahoo.com' . "\r\n";  
											$headers .= 'Bcc: puneet.mathur@hytechpro.com' . "\r\n";
											$headers .= 'Bcc: jepstein@txxchange.com' . "\r\n"; */
				$returnpath = '-fdo-not-reply@txxchange.com';
                // Mail it
				@mail($to, $subject, $message, $headers, $returnpath);
			}
			
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
          * Scheduler function for the mass assignment of the plan to the ehs patients
          * @param numeric $user_id
          * @return channel type
          * @access public
          * @sks 29th november
          */

        function schedulerEhsPlanAssignment() {

                $query = "SELECT * FROM plan where ehsFlag = '1' AND scheduler_status = '1' AND status = '4' AND schdulerAction = '1'";
                $result = @mysql_query($query);
                $numRows = @mysql_num_rows($result);

                 if($numRows > 0) { 

                        $i = 0; 
                        while($row =  @mysql_fetch_assoc($result)) { 

                                //$row = @mysql_fetch_assoc($result);
                                $plan_id = $row['plan_id'];
                                $therapistId = $row['user_id'];
                                $plan_name = $row['plan_name'];
                                $clinicId = $row['clinicId'];
                                $notify = $row['notify'];
                                $mass_plan_id = $row['mass_plan_id'];
                                if(is_corporate($clinicId)==1){
                                    $ehsPatientArr = get_paitent_list($clinicId);
                                }else{
                                    $ehsPatientArr = getProviderEHSPatients($clinicId);
                                }
                                //$ehsPatientArr = getProviderEHSPatients($clinicId);
                                $patientCount = count($ehsPatientArr);
                                $pat = 0;        
                        
                              while($pat < $patientCount) { 
                                        
                                                $data = array(
					                'plan_name' => $row['plan_name'],

					                'parent_template_id' => $row['plan_id'],

					                'user_id' => $row['user_id'],
                                                        
                                                        'patient_id' => $ehsPatientArr[$pat],

					                'user_type' => 2,

                                                        'ehsFlag' => '1',

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
							        ehsFlag = '".$data['ehsFlag']."'";

				        $result1 = @mysql_query($sqlinsert);

                                        $new_plan_id = mysql_insert_id();

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

				        $queryPlanArticle = "select * from plan_article where plan_id = '{$plan_id}' ";

				        $plan_article = @mysql_query($queryPlanArticle);

				        while($rowPlanArticle = @mysql_fetch_array($plan_article)){

					        $data = array(
								        'plan_id' => $new_plan_id,

								        'article_id' => $rowPlanArticle['article_id'],

                           						'creation_date' => date("Y-m-d"),

								        'modified' => date("Y-m-d"),

							        );

					          $sqlPlanArticleInsert = "INSERT INTO plan_article set 
							        plan_id = '".$data['plan_id']."',
							        article_id = '".$data['article_id']."',
							        modified = '".$data['modified']."',
							        creation_date = '".$data['creation_date']."'";

				                $resultPlanInsert = @mysql_query($sqlPlanArticleInsert);

				        }

                                        //If assign by the notification
                                       if($notify == '1') {
                                                global$from_email_address, $templatePath, $imagePath, $txxchange_config, $url,$application_path;
                                                
                                                $userId = $ehsPatientArr[$pat];

                                                $sql = "select username from user where user_id =".$userId." and status='1'" ;
                                                $res1 = @mysql_query($sql);
                                                $rw1 = @mysql_fetch_array($res1);
                                                $username = $rw1['username'];

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
                                                                'plan_name' => $plan_name,
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

                                //Mail notification end here

                                $pat++;
                 

                              }
                        $modified_date = date("Y-m-d");
                        $queryUpdate = "UPDATE plan SET status = '1' , scheduler_status = '2' ,modified = '{$modified_date}' where plan_id = ".$plan_id;
                        @mysql_query($queryUpdate);
                        $i++;
                }
                                
                                 echo $i." Times Scheduler runs";   echo "<br>";        
                                echo "Scheduler-->".$pat."Patient plan Inserted"; echo "<br>";
                }
              

        }


        /**
          * Scheduler function for the update mass assignment of the plan to the ehs patients
          * @param numeric $user_id
          * @return channel type
          * @access public
          * @sks 29th november
          */

        function schedulerEhsPlanUpdate() {

                $query = "SELECT * FROM plan where ehsFlag = '1' AND scheduler_status = '1' AND status = '4' AND schdulerAction = '2'";
                $result = @mysql_query($query);
                $numRows = @mysql_num_rows($result);

                 if($numRows > 0) { 

                        $i = 0; 
                        while($row =  @mysql_fetch_assoc($result)) { 

                               $plan_id = $row['plan_id'];
                                $therapistId = $row['user_id'];
                                $plan_name = $row['plan_name'];
                                $clinicId = $row['clinicId'];
                                $notify = $row['notify'];
                                $mass_plan_id = $row['mass_plan_id'];
                                //$ehsPatientArr = getProviderEHSPatients($clinicId);
                                if(is_corporate($clinicId)==1){
                                    $ehsPatientArr = get_paitent_list($clinicId);
                                }else{
                                    $ehsPatientArr = getProviderEHSPatients($clinicId);
                                }
                                $patientCount = count($ehsPatientArr);
                                $pat = 0;        
                                
                               while($pat < $patientCount) { 
                                        
                                                $data = array(
					                'plan_name' => $row['plan_name'],

					                'parent_template_id' => $row['plan_id'],

					                'user_id' => $row['user_id'],
                                                        
                                                        'patient_id' => $ehsPatientArr[$pat],

					                'user_type' => 2,

                                                        'ehsFlag' => '1',

					                'creation_date' => date("Y-m-d"),

					                'modified_date' => date("Y-m-d"),

					                'status' => '1'

				                );

                                            $sqlinsert = "UPDATE plan set 
							        plan_name = '".$data['plan_name']."',
							        parent_template_id = '".$data['parent_template_id']."',
							        user_id = '".$data['user_id']."',
							        user_type ='".$data['user_type']."',
							        creation_date = '".$data['creation_date']."',
							        modified = '".$data['modified_date']."',
							        status = '".$data['status']."',
							        ehsFlag = '".$data['ehsFlag']."'
                                                                where patient_id = {$ehsPatientArr[$pat]} AND parent_template_id = ".$plan_id;

				        $result1 = @mysql_query($sqlinsert);

                                        //Treatment deletion				        
                                $sqlDeleteTreatment = "select * from plan_treatment where plan_id = '{$plan_id}' ";
                                $plan_treatment1 = @mysql_query($sqlDeleteTreatment);

                                while($rowPlanTreatment1 = mysql_fetch_array($plan_treatment1)) {
                                        $sqlDeleteTreatment1 = "SELECT plan_id FROM plan WHERE parent_template_id = ".$plan_id;
                                        $rsDeleteTreatment = @mysql_query($sqlDeleteTreatment1);

                                        while($rowDeleteTreatment1 = mysql_fetch_array($rsDeleteTreatment)) {
                                                 $sqlCheck1 = "DELETE FROM plan_treatment WHERE  treatment_id!= '{$rowPlanTreatment1['treatment_id']}' AND plan_id = ".$rowDeleteTreatment1['plan_id'];
                                                 $rsCheck1 = @mysql_query($sqlCheck1);
                                        }
                                } //Treatment deletion end here

                                        //Treatment updation				        
                                        $sqlPlanTreatment = "select * from plan_treatment where plan_id = '{$plan_id}' ";

				        $plan_treatment = @mysql_query($sqlPlanTreatment);

				        while($rowPlanTreatment = mysql_fetch_array($plan_treatment)) {
                                                                                           
                                                        $data = array(
								   
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


                                                        $sqlInsertTreatment = "SELECT plan_id FROM plan WHERE parent_template_id = ".$plan_id;
                                                        $rsInsertTreatment = @mysql_query($sqlInsertTreatment);

                                                        while($rowInsertTreatment = mysql_fetch_array($rsInsertTreatment)) {
                                                                 $sqlCheck = "SELECT * FROM plan_treatment WHERE  treatment_id = '{$rowPlanTreatment['treatment_id']}' AND plan_id = ".$rowInsertTreatment['plan_id'];
                                                                $rsCheck = @mysql_query($sqlCheck);

                                                                $numRows = @mysql_num_rows($rsCheck);
                                                                if($numRows > 0) {
                                                                          $sqlPlanTreatmentInsert = "UPDATE plan_treatment set 
							                                        treatment_id = '".$data['treatment_id']."',
							                                        instruction = '".$data['instruction']."',
							                                        sets = '".$data['sets']."',
							                                        reps = '".$data['reps']."',
							                                        hold ='".$data['hold']."',
							                                        benefit = '".$data['benefit']."',
							                                        lrb = '".$data['lrb']."',
							                                        treatment_order = '".$data['treatment_order']."',
                                                                                                modified = '".$data['modified']."',
							                                        creation_date = '".$data['creation_date']."' WHERE  treatment_id = '{$rowPlanTreatment['treatment_id']}' AND plan_id = ".$rowInsertTreatment['plan_id'];

				                                        $resultPlanTreatment = @mysql_query($sqlPlanTreatmentInsert);                                   
                                                                                  

                                                                } else {
                                                                                $sqlPlanTreatmentInsert = "INSERT INTO plan_treatment set 
                                                                                        plan_id = '".$rowInsertTreatment['plan_id']."',
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

                                                                                $rsplantreatmentInsert = @mysql_query($sqlPlanTreatmentInsert);
                                                                }

                                                        }
                                } //treatment updation end here


                                 //Article Deletion				        
                                $sqlPlanArticle1 = "select * from plan_article where plan_id = '{$plan_id}' ";
                                $plan_article1 = @mysql_query($sqlPlanArticle1);

                                while($rowPlanArticle1 = mysql_fetch_array($plan_article1)) {
                                                        
                                        $sqlDeleteArticle = "SELECT plan_id FROM plan WHERE parent_template_id = ".$plan_id;
                                        $rsDeleteArticle = @mysql_query($sqlDeleteArticle);
                                        while($rowDeleteArticle = mysql_fetch_array($rsDeleteArticle)) {
                                                 $sqlCheck1 = "DELETE FROM plan_article WHERE  article_id!= '{$rowPlanArticle1['article_id']}' AND plan_id = ".$rowDeleteArticle['plan_id'];
                                                 $rsCheck1 = @mysql_query($sqlCheck1);
                                         }
                                } 
                                //Article Deletion end here
                
                                 
                                        // copy all articles associated with plan to new plan id.

				        //Article updation				        
                                $sqlPlanArticle = "select * from plan_article where plan_id = '{$plan_id}' ";

                                $plan_article = @mysql_query($sqlPlanArticle);

                                while($rowPlanArticle = mysql_fetch_array($plan_article)) {
                                                                                                                           
                                 $data = array(
	                                       'article_id' => $rowPlanArticle['article_id'],

		                                'creation_date' => date("Y-m-d"),

                                                'status' => '1',

	                                        'modified' => date("Y-m-d"),

                                        );


                                $sqlInsertArticle = "SELECT plan_id FROM plan WHERE parent_template_id = ".$plan_id;
                                $rsInsertArticle = @mysql_query($sqlInsertArticle);

                                while($rowInsertArticle = mysql_fetch_array($rsInsertArticle)) {
                                         $sqlCheck = "SELECT * FROM plan_article WHERE  article_id = '{$rowPlanArticle['article_id']}' AND plan_id = ".$rowInsertArticle['plan_id'];
                                        $rsCheck = @mysql_query($sqlCheck);

                                         $numRows = @mysql_num_rows($rsCheck);
                                        if($numRows > 0) {
                                                  $sqlPlanArticleInsert = "UPDATE plan_article set 
                                                                        article_id = '".$data['article_id']."',
                                                                        modified = '".$data['modified']."',
                                                                        status = '1',
                                                                        creation_date = '".$data['creation_date']."' 
                                                                        WHERE  treatment_id = '{$rowPlanArticle['article_id']}' AND plan_id = ".$rowInsertArticle['plan_id'];

                                                $resultPlanArticle = @mysql_query($sqlPlanArticleInsert);                                   
                                                                                                                  

                                                } else {
                                                                 $sqlPlanArticleInsert = "INSERT INTO plan_article set 
                                                                        plan_id = '".$rowInsertArticle['plan_id']."',
	                                                                article_id = '".$data['article_id']."',
	                                                                modified = '".$data['modified']."',
	                                                                status = '1',
	                                                                creation_date = '".$data['creation_date']."'";
	                                                                                                                                           
                                                                $resultPlanArticle = @mysql_query($sqlPlanArticleInsert);
                                                }

                                 }
                        } 
                                //Article updation end here


                        
                                        
                         

                                        

                                        //If assign by the notification
                                       if($notify == '1') {
                                                global$from_email_address, $templatePath, $imagePath, $txxchange_config, $url,$application_path;
                                                
                                                $userId = $ehsPatientArr[$pat];

                                                $sql = "select username from user where user_id =".$userId." and status='1'" ;
                                                $res1 = @mysql_query($sql);
                                                $rw1 = @mysql_fetch_array($res1);
                                                $username = $rw1['username'];

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
                                                                'plan_name' => $plan_name,
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
                                                        if(@mail($to, $subject, $message, $headers, $returnpath)){
                                                                echo "mail sent";
                                                        } else {
                                                                echo "mail fail";
                                                         }
                                        }
                                }

                                //Mail notification end here 


                                $pat++;
                 

                              }
                                $modified_date = date("Y-m-d");

                                 $queryUpdate = "UPDATE plan SET status = '1' , scheduler_status = '2' ,modified = '{$modified_date}' where plan_id = ".$plan_id;
                                 @mysql_query($queryUpdate);
                                $i++;

                        }
                                echo $i." Times Scheduler runs";   echo "<br>";        
                                echo "Scheduler-->".$pat."Patient plan Updated"; echo "<br>";

                }
              

        }


        

        /**
         * This function used to Check the provider EHS patients 
         * @param string $message
         * return string $message
         */
        function getProviderEHSPatients($clinicId) {
                
                if($clinicId > 0) {

                        $sqlehsPatient = "SELECT u.user_id , u.ehs
                                        FROM user as u inner join clinic_user as cu on u.user_id=cu.user_id 
                                        AND cu.clinic_id in (SELECT clinic_id FROM clinic WHERE parent_clinic_id = {$clinicId} OR clinic_id = {$clinicId} and ( status = 1 or status = 2 ) ) 
                                        INNER JOIN clinic ON clinic.clinic_id = cu.clinic_id AND (clinic.status = 1 or clinic.status = 2) 
                                        LEFT JOIN patient_subscription AS ps ON u.user_id = ps.user_id and (ps.subs_status='1' or (ps.subs_status='2' and subs_end_datetime > now()))
                                        WHERE u.usertype_id=1 and (u.status = 1 OR u.status = 2) AND ((ps.subs_status='1'  and subs_end_datetime > now()) OR (ps.subs_status='2' AND ps.subs_end_datetime > now())) ORDER BY u.user_id";

                      $queryEhsPatient = @mysql_query($sqlehsPatient);
                      $numEhealthRow = @mysql_num_rows($queryEhsPatient);
                      if($numEhealthRow!= '0') {
                                while( $row = @mysql_fetch_array($queryEhsPatient)) {
                                        $patientArr[] = $row['user_id'];
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

        function schedulerEhsGoal() {
                
                 $query = "SELECT * FROM patient_goal where scheduler_status = '1' AND schdulerAction = '1'";
                 $result = @mysql_query($query);
                 $numRows = @mysql_num_rows($result);

                if($numRows > 0) { 

                        
                        $i = 0; //$row = @mysql_fetch_assoc($result);
                        while($row =  @mysql_fetch_assoc($result)) { 
                        
                                
                                $created_by = $row['created_by'];
                                $goal = $row['goal'];
                                $clinicId = $row['clinicId'];
                                $patient_goal_id = $row['patient_goal_id'];
                                //$ehsPatientArr = getProviderEHSPatients($clinicId);
                                if(is_corporate($clinicId)==1){
                                    $ehsPatientArr = get_paitent_list($clinicId);
                                }else{
                                    $ehsPatientArr = getProviderEHSPatients($clinicId);
                                }
                                $patientCount = count($ehsPatientArr);
                                $pat = 0;        
                                
                                while($pat < $patientCount) { 
                                        
                                                $data = array(
					                'parent_goal_id' => $row['patient_goal_id'],

					                'clinicId' => $row['clinicId'],
                                                        
                                                        'created_by' => $ehsPatientArr[$pat],

					                'goal' => $goal,

                                                        'status' => '1',

					                'created_on' => date("Y-m-d"),

					                'ehsGoal' => '1'

				                );

                                       $sqlinsert = "INSERT INTO patient_goal SET
							        parent_goal_id = '".$data['parent_goal_id']."',
							        clinicId = '".$data['clinicId']."',
							        goal = '".$data['goal']."',
							        status = '".$data['status']."',
							        created_by ='".$data['created_by']."',
							        created_on = '".$data['created_on']."',
                                                                scheduler_status = '2',
							        ehsGoal = '1'"; 

				        $result1 = @mysql_query($sqlinsert);

                                        $pat++;

                                }

                       
                      
                        $queryUpdate = "UPDATE patient_goal SET status='1' , scheduler_status = '2' where patient_goal_id = ".$patient_goal_id;
		        @mysql_query($queryUpdate);
                        
                          $i++;
                        
                }
                        echo $i." Times Scheduler runs";   echo "<br>";                     
                        echo $pat."Goals added for EHS patients";

           }
                
        }


        /**
         * Scheduler for the updation mass assignment of goal to EHS patients
         * @SKS 30th November 2011
         * @param string ''
         * return string ''
         */

        function schedulerUpdateEhsGoal() {
                
                 $query = "SELECT * FROM patient_goal where scheduler_status = '1' AND (schdulerAction = '2' OR schdulerAction = '3')";
                 $result = @mysql_query($query);
                 $numRows = @mysql_num_rows($result);

                if($numRows > 0) { 

                        
                        $i = 0;
                        while($i < $numRows) { 
                        
                                $row = @mysql_fetch_assoc($result);
                                $created_by = $row['created_by 	'];
                                $goal = $row['goal'];
                                $status = $row['status'];
                                $clinicId = $row['clinicId'];
                                $patient_goal_id = $row['patient_goal_id'];
                                //$ehsPatientArr = getProviderEHSPatients($clinicId);
                                if(is_corporate($clinicId)==1){
                                    $ehsPatientArr = get_paitent_list($clinicId);
                                }else{
                                    $ehsPatientArr = getProviderEHSPatients($clinicId);
                                }
                                $patientCount = count($ehsPatientArr);
                                $pat = 0;        
                                
                                while($pat < $patientCount) { 

                                        $query1 = " update patient_goal set status = '{$status}' where  parent_goal_id = '{$patient_goal_id}' ";
                                        $result1 = @mysql_query($query1);

                                        $pat++;
                                }

                        $i++;

                        $queryUpdate = "UPDATE patient_goal SET  scheduler_status = '2' where patient_goal_id = ".$patient_goal_id;
		        @mysql_query($queryUpdate);

                         

                }
                        echo $i." Times Scheduler runs";   echo "<br>";   
                         echo $pat."Goals updated for EHS patients";
                

                }
                
        }

        /**
         * Mass EHS Reminder Addition
         * @SKS 30th November 2011
         * @param string ''
         * return string ''
         */

        function schedulerAddEhsReminder() {

                 $query = "SELECT * FROM patient_reminder where scheduler_status = '1' AND schdulerAction = '1'";
                 $result = @mysql_query($query);
                 $numRows = @mysql_num_rows($result);

               if($numRows > 0) { 

                        
                        $i = 0;
                        while($i < $numRows) { 
                        
                                $row = @mysql_fetch_assoc($result);
                                $patient_reminder_id = $row['patient_reminder_id'];
                                $clinicId = $row['clinicId'];
                                $status = $row['status'];
                                //$ehsPatientArr = getProviderEHSPatients($clinicId);
                                if(is_corporate($clinicId)==1){
                                    $ehsPatientArr = get_paitent_list($clinicId);
                                }else{
                                    $ehsPatientArr = getProviderEHSPatients($clinicId);
                                }
                                $patientCount = count($ehsPatientArr);
                                $pat = 0;        
                                
                                while($pat < $patientCount) { 
                                        
                                                $data = array(
					                'parent_reminder_id' => $row['patient_reminder_id'],

					                'clinicId' => $row['clinicId'],
                                                        
                                                        'user_id' =>$row['user_id'],
                                                        
                                                        'patient_id' => $ehsPatientArr[$pat],

					                'reminder' => $row['reminder'],

                                                        'status' => '1',

					                'creation_date' => date("Y-m-d"),

                                                        'modified' => date("Y-m-d"),

					                'ehsReminder' => '1'

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
							        ehsReminder = '".$data['ehsReminder']."'";

				        $result1 = @mysql_query($sqlinsert);

                                        $pat++;

                                }

                        

                        $queryUpdate = "UPDATE patient_reminder SET status = '1' , scheduler_status = '2' ,schdulerAction = '0' where patient_reminder_id = ".$patient_reminder_id;
		        @mysql_query($queryUpdate);

                        $i++;

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

        function schedulerEhsArticle() {
        	global $application_path,$imagePath,$url,$from_email_address,$business_wx,$business_tx,$email_wx,$email_tx,$images_url;
        	
                $sendemail="no";
                 $query = "SELECT * FROM patient_article where scheduler_status = '1' AND schdulerAction = '1'";
                 $result = @mysql_query($query);
                 $numRows = @mysql_num_rows($result);
                if($numRows > 0) { 
                       $i = 0; 
                        while($row =  @mysql_fetch_assoc($result)) { 
                                $patientArticleId = $row['patientArticleId'];
                                $article_id = $row['article_id'];
                                $clinicID = $row['clinicID'];
                                $therapistId = $row['therapistId'];
                                $creationDate = date('Y-m-d H:i:s');
                                $modiefiedDate = date('Y-m-d H:i:s');
                                //$ehsPatientArr = getProviderEHSPatients($clinicID);
                                if(is_corporate($clinicID)==1){
                                    $ehsPatientArr = get_paitent_list($clinicID);
                                }else{
                                    $ehsPatientArr = getProviderEHSPatients($clinicID);
                                }
                                $patientCount = count($ehsPatientArr);
                                $pat = 0;  
                                while($pat < $patientCount) {  
                                    $data = array(
						                'article_id' => $article_id,
    	                                'patient_id' => $ehsPatientArr[$pat],
                                        'clinicID' => $clinicID,
                                        'therapistId' => $therapistId,
                                        'creationDate' => date('Y-m-d H:i:s'),
                                        'modiefiedDate' => date('Y-m-d H:i:s'),
                                        'ehsFlag' => '1',
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
							        		ehsFlag = '1'"; //echo "</br>";

				        	$result1 = @mysql_query($sqlinsert);
                            $pat++;  
                            $sendemail="yes";  
                        	}
	                         $queryUpdate = "UPDATE patient_article SET status='1' , scheduler_status = '0' where patientArticleId = ".$patientArticleId;//echo "<br>";
		         			@mysql_query($queryUpdate);
                        $i++;
                }
				if($sendemail=='yes'){
					//$ehsPatientArr = getProviderEHSPatients($clinicID);
                                    if(is_corporate($clinicId)==1){
                                        $ehsPatientArr = get_paitent_list($clinicId);
                                    }else{
                                        $ehsPatientArr = getProviderEHSPatients($clinicId);
                                    }
					foreach($ehsPatientArr as $key=>$patient_id)
					{
						$clinicName=html_entity_decode(get_clinic_info($patient_id,'clinic_name'), ENT_QUOTES, "UTF-8");
						$clinicId  =   get_clinic_info($patient_id);
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
	      	$sql = "select username from user where user_id =".$patient_id." and status='1'" ;
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
                mail($to, $subject, $message, $headers, $returnpath);	
					}
				}
                echo $i." Times Scheduler runs";   echo "<br>";                     
                echo $pat."Articles added for EHS patients";

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
		
		//Function Initialization
		uploadPatientList();
                
		mass_message();
                //New Schedulers added for Mass Ehs Assignment
                schedulerEhsPlanAssignment();//Scheduler for Ehs Plan Assignment
                schedulerEhsPlanUpdate();//Scheduler for Ehs Plan Updation
                schedulerEhsGoal(); //Scheduler for EHs Goal Addition
                schedulerUpdateEhsGoal();//Scheduler for Ehs Goal Updation
                schedulerDeleteEhsReminder();//Scheduler for Deletion of EHS Reminders
                schedulerEhsArticle();
                schedulerDeleteEhsArticles();
            //    schedulerAddEhsReminder();


?>
