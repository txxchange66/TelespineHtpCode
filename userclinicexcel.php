<?php

require_once("config.php");
$host = $txxchange_config['dbconfig']['db_host_name'];
$user = $txxchange_config['dbconfig']['db_user_name'];
$pass = $txxchange_config['dbconfig']['db_password'];
$db   = $txxchange_config['dbconfig']['db_name'];
$application_path   = $txxchange_config['application_path'];
$templatePath = $application_path."mail_content/mass_patient_upload_mail.php";
$templatePathMassMessage = $application_path."mail_content/mass_message_mail_aa.php";
$imagePath = $txxchange_config['images_url'];
$url = $txxchange_config['url'];
$from_email_address = $txxchange_config['from_email_address'];
// Make connection with server.
$link = @mysql_connect($host,$user,$pass);
 require_once("include/excel/excelwriter.inc.php");

// select database.
@mysql_select_db($db,$link);        
		
        function txReferralReportExcel(){ 
               $filename='report/user_clinic.xls';
               $filename=str_replace(" ",'_',$filename);
               
                $excel=new ExcelWriter($filename);
                if($excel==false)
                echo $excel->error;
                $sqlUser = "select * from user,clinic,clinic_user where user.user_id=clinic_user.user_id and clinic.clinic_id=clinic_user.clinic_id order by user.user_id ASC";
            
            $result = mysql_query($sqlUser);     
            
            if(mysql_num_rows($result)!= 0)
            {
                
		        $myArr=array("First Name","Last Name","Type","Clinic Name","User Name (email address)","Status");
                $excel->writeLine($myArr);
                while($row = mysql_fetch_array($result))
                {
                    $excelrow['name_first'] = decrypt_data($row['name_first']);
                    $excelrow['name_last'] = decrypt_data($row['name_last']);
                    $userType="";
                    $status='';
                    if($row['usertype_id']==1){
                        $userType="Patient";
                        if($row['status']==1){
                            $status='Current';
                        }elseif($row['status']==2){
                            $status='Discharge';
                        }else{
                            $status='Archive';
                        }
                    }elseif($row['usertype_id']==2){
                        $userType="Provider";
                        if($row['status']==1){
                            $status='Active';
                        }elseif($row['status']==2){
                            $status='Inactive';
                        }else{
                            $status='Archive';
                        }
                    }elseif($row['usertype_id']==3){
                        $userType="Account Admin";
                        if($row['status']==1){
                            $status='Active';
                        }elseif($row['status']==2){
                            $status='Inactive';
                        }else{
                            $status='Archive';
                        }
                    }else{
                        $userType="System Admin";
                        if($row['status']==1){
                            $status='Active';
                        }elseif($row['status']==2){
                            $status='Inactive';
                        }else{
                            $status='Archive';
                        }
                    }
                    $excelrow['usertype_id']=$userType;
                    
                    $excelrow['clinic_name'] = $row['clinic_name'];
                    $excelrow['username'] = $row['username'];
                    
                    $excelrow['status'] = $status;
                    
                    $excel->writeLine($excelrow);                    
                }
            }
            $excel->close();
            if (file_exists($filename)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename='.basename($filename));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filename));
                ob_clean();
                flush();
                readfile($filename);
                exit;
            }
	     }
         
        function decrypt_data($data,$privateKey = "a2dc2a99e649f147bcabc5a99bea7d96" ){
                      $query = "select AES_DECRYPT(UNHEX('{$data}'),'{$privateKey}')";
                      $result = @mysql_query($query);
                      if ($result) {
                        return @mysql_result($result, 0);
                      }
                      return "";
            }	
		
			function formatDate($dt,$strFormat = null)
		{
			$formatedDate = null;
			if($dt == null || $dt == ""){
				return "";
			}
			if ($strFormat!=null)
			{
				$formatedDate = date($strFormat, strtotime($dt));
			}
			else 
			{
				$formatedDate =date('m/d/Y h:i A', strtotime($dt));
			}
			
			return $formatedDate;
		}
		//Function Initialization
		txReferralReportExcel();




?>