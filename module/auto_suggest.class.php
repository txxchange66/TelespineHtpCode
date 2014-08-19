<?php


	/**
	 *
	 * Copyright (c) 2008 Tx Xchange
	 *
	 * Class for populate list of patient automatically by typing letters in text box.
	 * 
	 */
		
	
	// starting session.
	session_start();
	
	
	// including configuration file
	require_once("../config.php");
	
	
	
	/**
	 * Connecting from database 
	 */
	$host = $txxchange_config['dbconfig']['db_host_name'];
	$user = $txxchange_config['dbconfig']['db_user_name'];
	$password = $txxchange_config['dbconfig']['db_password'];
	$dbname = $txxchange_config['dbconfig']['db_name'];
	$conn = mysql_connect($host,$user,$password);
	@mysql_select_db($dbname,$conn);
	
	/**
	 * This is a autosuggest dropdown functionality for listing patients by using AJAX.
	 *
	 */
	class auto_suggest {
		
		/**
		 * Function called to list the patient names.
		 *
		 */
		function ajax_dynamic_list(){
            global $txxchange_config;
            $privateKey = $txxchange_config['private_key'];
          // $_GET['letters']='che';
          // $_GET['getCountriesByLetters']='hari';
			$query = "select user_id,admin_access,therapist_access from user where username = '".$_SESSION['username']."' and AES_DECRYPT(UNHEX(password),'{$privateKey}') = '".$_SESSION['password']."' and status = 1 and usertype_id = 2 " ;
			$result = @mysql_query($query);
			$row = @mysql_fetch_array($result);
            $clinicId = $this->clinicInfo('clinic_id',$row['user_id']);
			$therapist_id = $row['user_id'];
                 if(isset($_GET['getCountriesByLetters']) && isset($_GET['letters'])){
				$letters = $_GET['letters'];
				$letters = preg_replace("/[^a-z0-9. ]/si","",$letters);
				if(strlen($letters)>2){
				$queryt= "select user_id ,usertype_id,concat(AES_DECRYPT(UNHEX(name_first),'{$privateKey}'),' ',AES_DECRYPT(UNHEX(name_last),'{$privateKey}')) as username from user
						 where ((concat(CAST(AES_DECRYPT(UNHEX(name_first),'{$privateKey}') AS CHAR),' ',CAST(AES_DECRYPT(UNHEX(name_last),'{$privateKey}') AS CHAR)) like '".$letters."%') || (concat(AES_DECRYPT(UNHEX(name_last),'{$privateKey}'),' ',AES_DECRYPT(UNHEX(name_first),'{$privateKey}')) like '".$letters."%')) and 
                         user_id in(SELECT user.user_id FROM user inner join clinic_user on clinic_user.user_id = user.user_id 
and (user.status = '1' or user.status = '2') and clinic_user.clinic_id in 
(select clinic_id from clinic where parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}' and ( status = 1 or status = 2) ) 
inner join clinic on clinic.clinic_id = clinic_user.clinic_id and ( clinic.status = '1' or clinic.status = '2' ) WHERE user.usertype_id = '2' and user.user_id !=".
$row['user_id'].")";
                $query = "(select user_id ,usertype_id,concat(AES_DECRYPT(UNHEX(name_first),'{$privateKey}'),' ',AES_DECRYPT(UNHEX(name_last),'{$privateKey}')) as username from user
						 where ((concat(CAST(AES_DECRYPT(UNHEX(name_first),'{$privateKey}') AS CHAR),' ',CAST(AES_DECRYPT(UNHEX(name_last),'{$privateKey}') AS CHAR)) like '".$letters."%') || (concat(AES_DECRYPT(UNHEX(name_last),'{$privateKey}'),' ',AES_DECRYPT(UNHEX(name_first),'{$privateKey}')) like '".$letters."%')) 
						 and user_id in (select patient_id from therapist_patient where therapist_id = '".$therapist_id."' and status = 1 )	
						 and usertype_id = 1 and ( status in (1,2) or user_id in (select u_id from program_user where p_status =  '1'))) union ( $queryt )";
                
               
				
				if($row['admin_access']==1){
				     //echo "1###strlen($letters)'".strlen($letters).'cid'.$clinicId."'|";
                    $sql="select  u.user_id from user as u 
                        inner join clinic_user as cu on u.user_id=cu.user_id  
                        and cu.clinic_id in (select clinic_id from clinic where (parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}') and ( status = 1 or status = 2 ) ) 
                        inner join clinic  on clinic.clinic_id = cu.clinic_id and (clinic.status = 1 or clinic.status = 2) 
                        where u.usertype_id='1' and u.mass_message_access=1";
                        
                     $queryt= "select user_id ,usertype_id,concat(AES_DECRYPT(UNHEX(name_first),'{$privateKey}'),' ',AES_DECRYPT(UNHEX(name_last),'{$privateKey}')) as username from user
						 where ((concat(CAST(AES_DECRYPT(UNHEX(name_first),'{$privateKey}') AS CHAR),' ',CAST(AES_DECRYPT(UNHEX(name_last),'{$privateKey}') AS CHAR)) like '".$letters."%') || (concat(AES_DECRYPT(UNHEX(name_last),'{$privateKey}'),' ',AES_DECRYPT(UNHEX(name_first),'{$privateKey}')) like '".$letters."%')) and 
                         user_id in(SELECT user.user_id FROM user inner join clinic_user on clinic_user.user_id = user.user_id 
and (user.status = '1' or user.status = '2') and clinic_user.clinic_id in 
(select clinic_id from clinic where parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}' and ( status = 1 or status = 2) ) 
inner join clinic on clinic.clinic_id = clinic_user.clinic_id and ( clinic.status = '1' or clinic.status = '2' ) WHERE user.usertype_id = '2' and user.user_id !=".
$row['user_id'].")";   
                    
                    $query = "(select user_id ,usertype_id,concat(AES_DECRYPT(UNHEX(name_first),'{$privateKey}'),' ',AES_DECRYPT(UNHEX(name_last),'{$privateKey}')) as username from user
						 where ((concat(CAST(AES_DECRYPT(UNHEX(name_first),'{$privateKey}') AS CHAR),' ',CAST(AES_DECRYPT(UNHEX(name_last),'{$privateKey}') AS CHAR)) like '".$letters."%') || (concat(AES_DECRYPT(UNHEX(name_last),'{$privateKey}'),' ',AES_DECRYPT(UNHEX(name_first),'{$privateKey}')) like '".$letters."%')) 
						 	   and user_id in (".$sql.")  and ( status in (1,2) or user_id in (select u_id from program_user where p_status =  '1' ))) union ($queryt)"; 
                    
				}
                //echo $query;
               $res = mysql_query($query) or die(mysql_error()); 
                				
                
                   # echo "1###strlen($letters)'".strlen($letters)."'|";
                    
                    while($inf = mysql_fetch_array($res)){
				       // if($row["therapist_access"]==1){
				        if($inf["usertype_id"]==2){
                            echo $inf["user_id"]."###".$inf["username"].' (Staff)'."|";
                         //   }
                         }else{
                             echo $inf["user_id"]."###".$inf["username"]."|";
                            
                         }
                    }
               
                  
                    
                    if($row["admin_access"]==1){
                        if($letters=='all' or $letters=='All' or $letters=='all ' or $letters=='All ' )
                        {
                            echo '-1'."###".'All Current Patients'."|"; 
                            echo '-2'."###".'All Discharged Patients'."|";
                            echo '-3'."###".'All Patients'."|";
                        }
                   }    	
			}		
		}
		
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
				$query = "select * from clinic_user where user_id = '{$id}' ";
				$result = mysql_query($query);
				
			}else{
				
				if(!(isset($_SESSION['username']) && $_SESSION['username'] != "")){
	
					return "";
	
				}else{
					
					$sql= " SELECT user_id FROM user WHERE username =  '{$_SESSION['username']}' and status = 1 " ;
					$rs = mysql_query($sql);
					$user_array = $this->populate_array($rs);
                    // Only for eRehab patients.
					if( ! is_array($user_array) ){
                        $erehabSql= " SELECT u.user_id FROM user u
                        inner join program_user pu on u.user_id = pu.u_id and u.usertype_id = 1 and (u.status =1 or u.status = 2) and pu.p_status = 1  
                        WHERE u.username =  '{$_SESSION['username']}' " ;
                        $erehabRs = mysql_query($erehabSql);
                        $user_array = mysql_query($erehabRs);
                    }
                    // End 
					$query = "select * from clinic_user where user_id = '".$user_array['user_id']."'";
					$result = mysql_query($query);					
				
				}
			}
				
			$row = $this->populate_array($result);
			if(!empty($row)){
				if($field != ""){
					return $field_value = $row[$field];
				}
				return "";
			}
			return "";
		}
        
        function populate_array($result, $fetch_type=""){
				$returnValue = "";
				if(is_resource($result)){
					if(@mysql_num_rows($result) > 0 ){
							switch($fetch_type)
							{
								case '0':
									// returns associative array
									$array = array();
									while($row = @mysql_fetch_assoc($result)){
										$array[] = $row;
									}
								break;								
								case '1':
									$array = @mysql_fetch_row($result);
								break;
								case '2':
									// returns object type
									$array = array();
									while($row = @mysql_fetch_object($result)){
										$array[] = $row;
									}
								break;
								case '3':
									$array = array();
									while($row = @mysql_fetch_array($result)){
										$array[] = $row;
									}
								break;
								default:
									$array = @mysql_fetch_assoc($result);
								break;
							}
						// gets momory free
						@mysql_free_result($result);
						if(is_array($array) && (count($array) > 0)){
							$returnValue = $array;
						}
					}
				}
				return $returnValue;
			}
         
	}
	
	/**
	 * Initialize the object of this class
	 */		
	$obj = new auto_suggest();
	$obj->ajax_dynamic_list();
?>
