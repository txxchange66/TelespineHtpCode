<?php
	/**
	 * 
	 * Copyright (c) 2008 Tx Xchange.
	 * 
	 * This class consist of some application specific methods.
	 * This methods are used in different child class.
	 * 
	 */
  	class application extends common{
		/**
		 * This function returns resultset of top Template plans.
		 * This template plans are shown in home page of Therapist and System admin.
		 *
		 * @param integer $userType
		 * @param integer $userId
		 * @return mixed
		 */

                 static $providerEhealthStatus;

		function TopAssignedPlans($userType,$userId){

			//SQL for top 10 plans assigned.
                        //p.ehsFlag = '0' means an parent plan which shows on ehs mass assignment page
			if( $userType == '4' ){
				$topPlanSQL = "select *	from plan p where  p.user_type = '{$userType}' and p.patient_id is null and p.status = 1 AND p.ehsFlag = '0' ORDER BY creation_date DESC "; 
			}
			else{
				
				// commented by amit.jain@hytechpro.com on Oct 08, 2007, uncommented on Oct 11, 2007
				$topPlanSQL = "select *	from plan p where p.user_id = '{$userId}' and p.user_type = '{$userType}' and p.patient_id is null and p.status = 1 AND p.ehsFlag = '0' ORDER BY creation_date DESC "; 
				
				
				/*//added by amit.jain@hytechpro.com on Oct 08, 2007, commented on Oct 11, 2007
				$topPlanSQL = "select *	from plan p where p.user_id = '{$userId}' and p.user_type = '{$userType}' and (p.patient_id is null OR p.patient_id = 0) and p.status = 1 ORDER BY creation_date DESC "; */
			}
			 
			$result = $this->execute_query($topPlanSQL);				
			if(is_resource($result))
				return $result;
			else
				return 0;
		}
		/**
		 * This function returns resultset of top articles.
		 * This articles are shown in home page of Therapist and System admin.
		 *
		 * @param integer $userType
		 * @param integer $userId
		 * @return mixed
		 */
		function TopAssignedArticles($userType,$userId){

			//SQL for top 10 assigned articles
			
			if($userType == '4'){
				$topArticleSQL = " SELECT * FROM article a,user u  WHERE a.status = 1 and a.user_id = u.user_id 
				and u.usertype_id = 4  ORDER BY a.creation_date desc ";
			}
			if($userType == '2'){
				//$topArticleSQL = " SELECT * FROM article a,user u  WHERE a.status = 1 and a.user_id = u.user_id and u.usertype_id = 4  ORDER BY a.creation_date desc ";
				$topArticleSQL = " SELECT * FROM article WHERE user_id = '{$this->userInfo('user_id')}' and status = 1 ORDER BY creation_date desc ";
			}
			$result = $this->execute_query($topArticleSQL);				
			if(is_resource($result))
				return $result;
			else
				return 0;
		}
		
		/**
		 * This function adds switch to user link to left navigation panel for system admin.
		 *
		 * @return string
		 * @access public
		 */
		static function sysadmin_link($help_display = true){
			$sysadmin_link = "";
			if(isset($_SESSION['tmp_username']) && $_SESSION['tmp_username'] != "" && isset($_SESSION['tmp_password']) && $_SESSION['tmp_password'] != "" ){
				$private_key = db::$config['private_key'];
                $query = "select * from user where username = '{$_SESSION['tmp_username']}' and AES_DECRYPT(UNHEX(password),'{$private_key}' ) = '{$_SESSION['tmp_password']}' and status != '3' ";
				$result = parent::execute_query($query);
				if($row = parent::fetch_array($result)){
                    $encrypt_field = array('name_title','name_first','name_last','password','address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
                    $row = parent::decrypt_field($row,$encrypt_field);
					if($row['usertype_id'] == '4'){
						if( parent::userinfo('therapist_access') == "1"  ){
							$sysadmin_link = "<li ><a  href='index.php?action=switch_back' >Switch to System Admin</a></li>";
						}
						elseif( parent::userinfo('admin_access') == "1" ){
							$sysadmin_link = "<li ><a  href='index.php?action=switch_back_admin_access' >Switch to System Admin</a></li>";						
						}
					}
				}
			}
			if( parent::userinfo('therapist_access') == "1"  ){
				if($help_display == true ){
					$sysadmin_link .= '<li style="padding-top:10px; ">
       <a style="font-size:12px; margin-right:-5px; display:block; line-height:15px; padding-top:0px;" href="index.php?action=help_therapist" target="_blank"><img src="images/icon-helpfiles.jpg" style="margin-right:5px;" align="left"><span style="display:block; float:left; padding-top:10px;">Feature Help</span></a><div style="clear:both;"></div></li>';					
				}
				
			}
			return $sysadmin_link;
		}
		/**
		 * This function calculates number of unread message.
		 *
		 * @return integer
		 * @access public
		 */
		static function num_messages(){
		  $where=" and  ( m.patient_id IN ( SELECT patient_id FROM therapist_patient WHERE therapist_id = '".parent::userInfo('user_id')."') or m.patient_id = '".parent::userInfo('user_id')."')";
          if(parent::userInfo('admin_access')==1){
			 $clinicId = self::clinicInfo('clinic_id');
             $where .= " or ((mass_patient_id !=0 and replies !=0) OR mass_status=1) and 
                        sender_id = '".parent::userInfo('user_id')."' or  ((mass_patient_id !=0 and replies !=0) 
                        OR mass_status=1) and mass_patient_id='".parent::userInfo('user_id')."' or  ((mass_patient_id =0) OR mass_status=0) and sender_id='".parent::userInfo('user_id')."'";
            /* $where ="  and m.patient_id IN (select  u.user_id from user as u 
                        inner join clinic_user as cu on u.user_id=cu.user_id  
                        and cu.clinic_id in (select clinic_id from clinic where (parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}') and ( status = 1 or status = 2 ) ) 
                        inner join clinic  on clinic.clinic_id = cu.clinic_id and (clinic.status = 1 or clinic.status = 2) 
                        where u.usertype_id='1' and (u.status=1 or u.status = 2)) ";
             $where.="  and sender_id = '".parent::userInfo('user_id')."' and mass_patient_id =0 ";*/
			}
			 $query = "select count( DISTINCT mu.message_id )  from message_user mu 
					  inner join message m on m.message_id = mu.message_id 
					   {$where}	
                      
					 where user_id = '".parent::userInfo('user_id')."' and unread_message = '1'  ";
            
            
                     
            $result = @mysql_query($query);
			if($row = @mysql_fetch_array($result)){
				return $row[0];
			}
			else{
				return 0;
			}
		}
		/**
		 * This function calculates total number of replies for any message.
		 *
		 * @param string $query
		 * @return integer
		 */
		static function total_replies($query = ""){
			$total_replies = 0;
			if($query != ""){
				$result = @mysql_query($query);
				while ($row = @mysql_fetch_array($result)) {
					$total_replies += $row['replies'];					
				}
			}
			return $total_replies;
		}
		/**
		 * This function returns list of navigation link for Therapist interface.
		 *
		 * @return string
		 */
		static function therapist_link() { 
                      
                       
                       $therapistClinicId = self::get_clinic_info(self::userInfo('user_id'));
                       $providerEhealthStatus = self::getProviderEhealthStatus($therapistClinicId);
		       $therapist_link = "";
			if(isset($_SESSION['username']) && $_SESSION['username'] != "" && isset($_SESSION['password']) && $_SESSION['password'] != "" ){
                $private_key = db::$config['private_key'];
				$query = "select * from user where username = '{$_SESSION['username']}' and AES_DECRYPT(UNHEX(password),'{$private_key}' ) ='{$_SESSION['password']}' and status = '1' ";
				$result = parent::execute_query($query);
				if($row = parent::fetch_array($result)){
                    $encrypt_field = array('password','address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
                    $row = parent::decrypt_field($row,$encrypt_field);
					$flag = 0;
					
					if($row['therapist_access'] == '1'){ 
                        
                        if( self::value('action') == "eMaintenance" ){
                            $display = "block";
                            $image =   "/images/list_contract.gif";
                        }
                        else{
                            $display="none";
                            $image =   "/images/list_expand.gif";
                        } 
						$num_message = self::num_messages();
						$therapist_link .= '
									<li ><a href="index.php?action=therapist"  class="selected">Home</a></li>';
								
						$therapist_link .=	'<li>
												<a href="index.php?action=message_listing&sort=sent_date&order=desc" >Messages('.$num_message.')</a>
									</li>';
                                                                        
                                                if(self::is_corporate($therapistClinicId)==1){
   $therapist_link .='<li id="switch" style="background:url('.$image.') no-repeat 0px;  6px;padding-left:15px;" onclick="switchMenu(\'eMaintenance\',this);" >
										<a href="index.php?action=myPatients" onclick="assign();" >My Patients</a>
                                        <div id="eMaintenance" style="padding-left:2px;display:none;margin-right:-10px;" ><a href="index.php?action=therapistEhsPatient&ehsunsub='.$mass.'" onclick="assign();" >E-Health Service</a></div>
									</li>';
    
    
} elseif($providerEhealthStatus == 1) {

                                                                                $Ehspatients = self::CountEHSPatients($therapistClinicId);
                                                                                $totalEhsPatients = count($Ehspatients);
                                                                                if($totalEhsPatients == '0'){
                                                                                        $mass = '1';
                                                                                } else {
                                                                                        $mass = '1';        
                                                                                }                                               
                                                                                                
						 			   $therapist_link .='<li id="switch" style="background:url('.$image.') no-repeat 0px;  6px;padding-left:15px;" onclick="switchMenu(\'eMaintenance\',this);" >
										<a href="index.php?action=myPatients" onclick="assign();" >My Patients</a>
                                        <div id="eMaintenance" style="padding-left:2px;display:none;margin-right:-10px;" ><a href="index.php?action=therapistEhsPatient&ehsunsub='.$mass.'" onclick="assign();" >E-Health Service</a></div>
									</li>'; 
}
else{

                 $therapist_link .='<li><a href="index.php?action=myPatients"  >My Patients</a>           
									                </li>'; 
                }

                            
                                    
									$therapist_link .='<li >
										<a href="index.php?action=therapistPlan" >'.$_SESSION['providerLabel']['My Template Plans'].'</a>
									</li>
									<li>
										<a href="index.php?action=therapistLibrary" >Article Library</a>
									</li>											
											';
						$therapist_link .= '<li ><a href="index.php?action=treatmentManager" >Video Library</a></li>';
                       // $therapist_link .= '<li ><a href="index.php?action=choose_notification_reminder" >Notifications</a></li>';
						$flag = 1;	
					}
					if(($row['admin_access'] == '1')) {
					   $num_message = self::num_messages();
						if($flag == 0){
							$therapist_link .= '
												<li>
												<a href="index.php?action=message_listing&sort=sent_date&order=desc" >Messages('.$num_message.')</a>
									</li>
                                    <li class="Treatment ManagerBtn">
													<a href="index.php?action=treatmentManager" >Video Library</a>
												</li>
												<li>
													<a href="index.php?action=therapistLibrary" >Article Library</a>
												</li>
												';
						}
                        $clinic_id = self::clinicInfo("clinic_id",$row['user_id']);
                        if(is_numeric($clinic_id) && $clinic_id > 0){
                            $parentClinicId = parent::get_field($clinic_id,"clinic","parent_clinic_id");
                            if( is_numeric($parentClinicId) && $parentClinicId == 0){
                                $accountAdminAction = "addonServicesHead";
                               }
                            else{
                                $accountAdminAction = "addonServices";
                                }
                        }

						$therapist_link .= "<li ><a  href='index.php?action={$accountAdminAction}' >Account Admin</a></li>";
                    }
				}
			}
			return $therapist_link;
		}
		
		
		/**
		 * This function decides whether the logged in user has access or not for the module.
		 *
		 * @param string $str
		 * @return boolean
		 */
		function userAccess($str){
			//PRINT_R($_SESSION);die;
            if( isset($_SESSION['username']) && isset($_SESSION['password']) ){
				if($this->userInfo('usertype_id') == '1'){
					$module =  "patient";
					$module_arr = $this->config['module'][$module];
					$userType = "patient";
					/*$prxo=$this->checkprxostatus($this->userInfo('user_id'));
                    if($prxo=='register')
                    {
                        if($_SESSION['key_login']=='')
                        $this->loginPatientPrxo($this->userInfo('user_id'));
                        
                    }
                    else
                    {
                        $res=$this->registerPatientPrxo($this->userInfo('user_id'));
                        if($res=='sucess')
                        $this->loginPatientPrxo($this->userInfo('user_id'));
                        
                    }*/
                    if ($this->userInfo('agreement') != 1)
					{
                        $array=  explode('.', $_SERVER['HTTP_HOST']) ;
                                        if (in_array($this->config['domain'], $array)) {
                                        header("location:index.php?action=patientdashboard");
                                        }else{
                                         header("location:index.php?action=agreement_patient");
                                        }                        

                                                
					}
				}
				elseif ($this->userInfo('usertype_id') == '2'){
					/*if($this->userInfo('admin_access')==1){
                        $prxo=$this->checkprxostatus($this->userInfo('user_id'));
                        if($prxo=='register')
                        {
                           // if($_SESSION['key_login']=='')
                           // $this->loginProviderPrxo($this->userInfo('user_id'));
                            
                        }
                        else
                        {
                           $res=$this->registerProviderPrxo($this->userInfo('user_id'));
                           //if($res=='sucess')
                           //$this->loginProviderPrxo($this->userInfo('user_id'));
                        }
                    }*/
                    if( $this->userInfo('therapist_access') == '1' ){
						$module =  "therapist";
						$module_arr = $this->config['module'][$module];	
					}
					if($this->userInfo('admin_access') == '1' ){
						$module =  "accountAdmin";
						if(is_array($module_arr)){
							$module_arr = array_merge($module_arr,$this->config['module'][$module]);
						}
						else{
							$module_arr = $this->config['module'][$module];
						}
					}
					
					$userType = "therapist";
					if ($this->userInfo('agreement') != 1)
					{
						header("location:index.php?action=agreement");
					}
				}
				elseif ($this->userInfo('usertype_id') == '3'){
					$module =  "accountAdmin";
					$userType = "accountAdmin";
				}
				elseif ($this->userInfo('usertype_id') == '4'){
					$module =  "systemAdmin";
					$module_arr = $this->config['module'][$module];	
					$userType = "systemAdmin";
					if ($this->userInfo('agreement') != 1)
					{
						header("location:index.php?action=agreement");
					}
				}
				//print_r($module_arr);
				if($module != "" && in_array($this->get_module_name($str),$module_arr) ){
					return true;
				}
				return false;				
			}
			return false;
		}
		/**
		 * This function builds url, for back button functionality.
		 *
		 * @param array $paramArray
		 * @param array $dataArray
		 * @return string
		 */
		function buildBackUrl($paramArray,$dataArray){
			$backUrl = "index.php?";
			if(is_array($paramArray) && count($paramArray) > 0){
				
				foreach ($paramArray as $key => $value){
					if(isset($dataArray[$value]) != "" ){
							$backUrl .= $value . "=" . $dataArray[$value] . "&";
					}
				}
				$backUrl= rtrim($backUrl,"&");
			}
			return $backUrl;
			
		}
		/**
		 * This function returns clinic details.
		 *
		 * @param string $field
		 * @param integer $id
		 * @return mixed
		 * @access public
		 */
         static function clinicInfo($field ,$id=""){

			if($id != ""){
				$query = "select * from clinic_user where user_id = '{$id}' ";
				$result = parent::execute_query($query);
				
			}else{
				
				if(!(isset($_SESSION['username']) && $_SESSION['username'] != "")){
	
					return "";
	
				}else{
					$sql= " SELECT user_id FROM user WHERE username =  '{$_SESSION['username']}' and status != 3 " ;
					$rs = parent::execute_query($sql);
					$user_array = parent::populate_array($rs);
                    // Only for eRehab patients.
					if( ! is_array($user_array) ){
                        $erehabSql= " SELECT u.user_id FROM user u
                        inner join program_user pu on u.user_id = pu.u_id and u.usertype_id = 1 and (u.status =1 or u.status = 2) and pu.p_status = 1  
                        WHERE u.username =  '{$_SESSION['username']}' " ;
                        $erehabRs = parent::execute_query($erehabSql);
                        $user_array = parent::populate_array($erehabRs);
                    }
                    // End 
					$query = "select * from clinic_user where user_id = '".$user_array['user_id']."'";
					$result = parent::execute_query($query);					
				
				}
			}
				
			$row = parent::populate_array($result);
			
			if(!empty($row)){
				if($field != ""){
					return $field_value = $row[$field];
				}
				return "";
			}
			return "";
		}
		
		/**
		 * Function return "Active" if we pass status=1 and "InActive" if we pass status=2
		 *
		 * @param integer $status_id
		 * @return string
		 */
		function get_status($status_id){
			if($status_id == '1'){
				return 'Active';
			}elseif($status_id == '2'){
				return 'InActive';
			}else{
				return '';
			}
		}		
		
		/**
		 * Function to change the status from Active to InActive or alternate
		 *
		 * @param integer $id
		 * @param integer $primary_key
		 * @param string $table
		 * @param string $statusFieldName
		 * @access public
		 */
		function set_statusActiveInActive($id, $primary_key, $table, $statusFieldName='status'){
			//  query to get current status of the specified user
			$query = "select ".$statusFieldName." from ".$table." where ".$primary_key."='".$id."'";
			$rs = $this->execute_query($query);
			$row = $this->fetch_array($rs);
			
			if($row['status'] == '1'){
				$newStatus = '2';
			}elseif($row['status'] == '2'){
				$newStatus = '1';
			}else{
				$newStatus = $row['status'];
			}
			
			//  query for changing the status
			$query2 = "update ".$table." set ".$statusFieldName."='".$newStatus."' where ".$primary_key."='".$id."'";
			$rs2 = $this->execute_query($query2);
		}		
		/**
		 * This function formats the subject of message if that messsage is unread.
		 *
		 * @param integer $message_id
		 * @param string $subject
		 * @return string
		 * @access public
		 */
		function unread_message($message_id,$subject){
			$query = "select count(*) from message_user 
					  where message_id = '{$message_id}' 
					  and user_id = '{$this->userinfo('user_id')}' 
					  and unread_message = '1' ";
			$result = @mysql_query($query);
			if( @mysql_num_rows($result) > 0 ){
				if( $row = @mysql_fetch_array($result)){
					if($row[0] > 0){
						$subject = "<b>".$subject."</b>";
						return $subject;
					}
					else{
						return $subject;
					}
				}
				else{
					return $subject;
				}
			}
			else{
				return $subject;
			}
		}
		/**
		 * This function formats the plan name of plan if that plan is unread.
		 *
		 * @param integer $message_id
		 * @param string $subject
		 * @return string
		 * @access public
		 */
		function unread_plan($plan_id,$plan_name){
			$query = "select count(*) from plan 
					  where plan_id = '{$plan_id}' 
					  and patient_id = '{$this->userinfo('user_id')}' 
					  and unread_plan = '1' ";
			$result = @mysql_query($query);
			if( @mysql_num_rows($result) > 0 ){
				if( $row = @mysql_fetch_array($result)){
					if($row[0] > 0){
						$plan_name = "<b>".$plan_name."</b>";
						return $plan_name;
					}
					else{
						return $plan_name;
					}
				}
				else{
					return $plan_name;
				}
			}
			else{
				return $plan_name;
			}
		}
		
		/**
		 * Function to keep the paging and searching information into session.
		 *
		 * @access public
		 * 
		 */
		function set_session_page($arr = '')
		{
			$pagingArray = $this->config['persistpaging'];
			foreach ($pagingArray as $key => $value){
				$pagingArray[$key] = $this->value($key);
			}
			
			if(is_array($arr)){
				foreach($arr as $key2 => $value2){
					$pagingArray[$key2] = $value2;
				}
			}
			
			$action = $this->value('action');
					
			$_SESSION[$action] = $pagingArray; 
		}
		/**
        * Get clinic Id or clinic name of a user.
        */
        function get_clinic_info($user_id,$field = "clinic_id" ){
            if( is_numeric($user_id) && $user_id >0 ){
                $sql = "select clinic_id from clinic_user where user_id = '{$user_id}'";
                $result = @mysql_query($sql);
                while( $row = @mysql_fetch_array($result) ){
                    $clinic_id = $row["clinic_id"];
                    if(is_numeric($clinic_id) && $clinic_id > 0  &&  $field == "clinic_id" ) {
                        return $row[$field];
                    }
                    if( is_numeric($clinic_id) && $clinic_id > 0 &&  $field == "clinic_name" ){
                        $clinic_name = $this->get_field($clinic_id,"clinic",$field);                        
                       return html_entity_decode($clinic_name);
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
                return html_entity_decode($clinic_name);
           }
                
        
            return "";
        }
		/**
		 * This function builds the url. 
		 * This url takes back the user to the same page where from he/she initiated the functionality.
		 *
		 * @param string $action
		 * @return string
		 * @access public
		 */
		function redirectUrl($action = "",$urlConsistArray = "")
		{
			if(!empty($action)){
				
				if(isset($_SESSION[$action]) && count($_SESSION[$action]) > 0){
					$url = "index.php?action=".$action;
					foreach($_SESSION[$action] as $key => $value){
						if(!empty($value) ){
							$url .= "&".$key."=".$value;
						}
					}
				}else{
					$url = "index.php?action=".$action;
				}
			}
			else{
				$url = "index.php";
			}
			return $url;
		}
		/**
        * This function will return formatted full name of user.
        */
		function fullName( $title='',$name_first='',$name_last='',$format=null )
        {
            $fullname = '';
            if( is_null($format) ){
                if( $title != '' ){
                    //$fullname = $title .'&nbsp;';
                }
                if( $name_first != '' ){
                    $fullname .= strtoupper($name_first) . '&nbsp;';
                }
                if( $name_last != '' ){
                    $fullname .= strtoupper($name_last);
                }
            }
            return $fullname;
        }
        
        
        /**
         * This function returns breadcrumb.
         * 
         *
         * @param string $action
         * @param array $navigate
         * @return mixed
         */function breadcrumb($action,$navigate=array()){
            if( isset($action) && $action != ""  ){   
                $breadcrumb = "";
                switch ($action)
                {
                    case 'search_clinic':
                        $breadcrumb = '<a href="index.php?action=system_report">SYSTEM REPORT</a> / <span class="highlight">SEARCH CLINIC</span>';
                        break;    
                    case 'system_report':
                        $breadcrumb = '<a href="index.php?action=system_report">SYSTEM REPORT</a> / <span class="highlight">SEARCH CLINIC</span>';
                        break;
                    case 'global_report':
                        $clinic_id = $this->value('clinic_id');
                        $breadcrumb = "<a href='index.php?action=global_report'>GLOBAL REPORT</a> / <span class='highlight'>REPORT TYPE</span>";
                        break;        
                    case 'viewTherapistOfClinic':
                        $clinic_id = $this->value('clinic_id');
                        $clinic_name = $this->get_field($clinic_id,'clinic','clinic_name');
                        $breadcrumb = "<a href='index.php?action=system_report'>SYSTEM REPORT</a> / <a href='index.php?action=search_clinic&search={$clinic_name}'>" . strtoupper($clinic_name) . '</a> / <span class="highlight">USER LIST</span>';
                        break;    
                    case 'select_period':
                        $clinic_id = $this->value('clinic_id');
                        if(is_numeric($clinic_id) && $clinic_id > 0 ){
                            $clinic_name = $this->get_field($clinic_id,'clinic','clinic_name');
                            $breadcrumb = "<a href='index.php?action=system_report'>SYSTEM REPORT</a> / <a href='index.php?action=search_clinic&search={$clinic_name}'>" . strtoupper($clinic_name) . '</a> / <span class="highlight">PERIOD</span>';
                            $user_id = $this->value('user_id');
                            if(is_numeric($user_id) && $user_id > 0){
                                $name_first = $this->get_field($user_id,'user','name_first');
                                $full_name = $name_first." ";
                                $full_name .= $this->get_field($user_id,'user','name_last');
                                $breadcrumb = "<a href='index.php?action=system_report'>SYSTEM REPORT</a> / <a href='index.php?action=search_clinic&search={$clinic_name}'>" . strtoupper($clinic_name) . '</a> / <a href="index.php?action=viewTherapistOfClinic&search='.$name_first.'&clinic_id='.$clinic_id.'" >'.strtoupper($full_name).'</a> / <span class="highlight">PERIOD</span>';
                            }
                        }
                        break;
                    case 'select_period_global':
                        $report_type = $this->value('report_type');
                        $report_for = "&report_for=".$navigate['report_for'];
                        $breadcrumb = "<a href='index.php?action=global_report&report_type={$report_type}{$report_for}'>GLOBAL REPORT</a> / <span class='highlight'>PERIOD</span>";
                        break;
                    case 'graphical_report':
                        $clinic_id = $this->value('clinic_id');
                        $clinic_name = $this->get_field($clinic_id,'clinic','clinic_name');
                        $period = $this->value('period');
                        $report_type = $this->value('report_type');
                        $report_for = "&report_for=".$navigate['report_for'];
                        if( is_numeric($clinic_id)){
                            $breadcrumb = "<a href='index.php?action=system_report'>SYSTEM REPORT</a> / <a href='index.php?action=search_clinic&search={$clinic_name}'>" . strtoupper($clinic_name) . "</a> / <a href='index.php?action=select_period&clinic_id={$clinic_id}&period={$period}&report_type={$report_type}{$report_for}' >PERIOD</a> /<span class='highlight'>GRAPHICAL REPORT</span>";
                            $user_id = $this->value('user_id');
                            if(is_numeric($user_id) && $user_id > 0){
                                $name_first = $this->get_field($user_id,'user','name_first');
                                $full_name = $name_first." ";
                                $full_name .= $this->get_field($user_id,'user','name_last');
                                $breadcrumb = "<a href='index.php?action=system_report'>SYSTEM REPORT</a> / <a href='index.php?action=search_clinic&search={$clinic_name}'>" . strtoupper($clinic_name) . '</a> / <a href="index.php?action=viewTherapistOfClinic&search='.$name_first.'&clinic_id='.$clinic_id.'" >'.strtoupper($full_name)."</a> / <a href='index.php?action=select_period&clinic_id={$clinic_id}&user_id={$user_id}&period={$period}&report_type={$report_type}{$report_for}' >PERIOD</a> /<span class='highlight'>GRAPHICAL REPORT</span>";
                            }
                        }
                        else{
                            $breadcrumb = "<a href='index.php?action=global_report&report_type={$report_type}'>GLOBAL REPORT</a> / <a href='index.php?action=select_period_global&period={$period}&report_type={$report_type}{$report_for}' >PERIOD</a> /<span class='highlight'>GRAPHICAL REPORT</span>";    
                        }
                        
                        break;
                    case 'tabular_report':
                        $clinic_id = $this->value('clinic_id');
                        $clinic_name = $this->get_field($clinic_id,'clinic','clinic_name');
                        $period = $this->value('period');
                        $report_type = $this->value('report_type');
                        $report_for = "&report_for=".$this->value('report_for');
                        if( is_numeric($clinic_id)){
                            $breadcrumb = "<a href='index.php?action=system_report'>SYSTEM REPORT</a> / <a href='index.php?action=search_clinic&search={$clinic_name}'>" . strtoupper($clinic_name) . "</a> / <a href='index.php?action=select_period&clinic_id={$clinic_id}&period={$period}&report_type={$report_type}{$report_for}' >PERIOD</a> /<span class='highlight'>TABULAR REPORT</span>";
                            $user_id = $this->value('user_id');
                            if(is_numeric($user_id) && $user_id > 0){
                                $name_first = $this->get_field($user_id,'user','name_first');
                                $full_name = $name_first." ";
                                $full_name .= $this->get_field($user_id,'user','name_last');
                                $breadcrumb = "<a href='index.php?action=system_report'>SYSTEM REPORT</a> / <a href='index.php?action=search_clinic&search={$clinic_name}'>" . strtoupper($clinic_name) . '</a> / <a href="index.php?action=viewTherapistOfClinic&search='.$name_first.'&clinic_id='.$clinic_id.'" >'.strtoupper($full_name)."</a> / <a href='index.php?action=select_period&clinic_id={$clinic_id}&user_id={$user_id}&period={$period}&report_type={$report_type}{$report_for}' >PERIOD</a> /<span class='highlight'>TABULAR REPORT</span>";
                            }
                        }
                        else{
                            $breadcrumb = "<a href='index.php?action=global_report&report_type={$report_type}'>GLOBAL REPORT</a> / <a href='index.php?action=select_period_global&period={$period}&report_type={$report_type}' >PERIOD</a> /<span class='highlight'>TABULAR REPORT</span>";
                        }
                        break;
                    
                }
            }
            
            return $breadcrumb;
        }
        /**
        * Get data for Graph.
        */
        function getData($patient_id){
            if( is_numeric($patient_id)){
             $query = " select * from patient_om where patient_id = '{$patient_id}' and status = 2 order by submited_on asc ";
             $result = @mysql_query($query);
             for( $cnt=1; $cnt<=15; $cnt++ ){
                 if($row = @mysql_fetch_array($result) ){
                     $graphData[$cnt] = $row['score'];
                 }
                 else{
                    $graphData[$cnt] = '';
                 }
             }
            }
            return $graphData;
        }
        /**
        * Get graph date.
        */
        function getGraphDate($patient_id){
            if( is_numeric($patient_id)){
             $query = " select * from patient_om where patient_id = '{$patient_id}' and status = 2 order by submited_on asc ";
             $result = @mysql_query($query);
             for( $cnt=1; $cnt<=15; $cnt++ ){
                 if($row = @mysql_fetch_array($result) ){
                     $graphData[$cnt] = date("M-d-Y",strtotime($row['submited_on']));
                 }
                 else{
                    $graphData[$cnt] = '';
                 }
             }
            }
            return $graphData;
        }
        /**
         * This function returns clinic type
         *
         * @param integer $userid
         * 
         * @return mixed
         */
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
        * Get Score for LineGraph.
        */
		function getLineGraphScore($omFormType,$patient_id){
					if( is_numeric($omFormType)){
					 $query = " select * from patient_om where patient_id = '{$patient_id}' and om_form = '{$omFormType}' and status = 2 order by submited_on asc ";
					 $result = @mysql_query($query);
					 for( $cnt=1; $cnt<=10; $cnt++ ){
						 if($row = @mysql_fetch_array($result) ){
							 $graphScore[$cnt]['score'] = $row['score'];
							 $graphScore[$cnt]['score2'] = $row['score2'];
							 $graphScore[$cnt]['submited_on'] = date("M-d-Y",strtotime($row['submited_on']));
						 }
						 else{
							$graphScore[$cnt] = '';
						 }
					 }
					}
					return $graphScore;
		}
        /**
         * This function returns true or false after check invitation is send by user
         *
         * @param integer $userId
         * @param string $userfname
         * @param string $userLname
         * @param string $provider
         * @return mixed
         */
        function check_send_invitation($value,$name_first='',$name_last='',$provider=''){
            $sql="select * from sendinvite where email='".$value."'";
            $res=@mysql_query($sql);
            $numrow=@mysql_num_rows($res);
            if($numrow >0){
                $sql="update sendinvite set first_name='".$name_first."',last_name='".$name_last."',provider='".$provider."',status=2,               dateofregister='".date('Y-m-d H:i:s',time())."' where email='".$value."'";
                 @mysql_query($sql);
                                 
            }else{
            return true;
            }
        }

		/**
		 * Function to convert the simple text URLs in to hyperlinks
		 *
		 * @access public
		 * @ This function is presently not used.
		 */
		/*function hyperlink($string)
		{	
		  $dataArray=explode(" ",$string);
		  //$dataArray=split('[ \r\n]', $string);
		  //print_r($dataArray);
		  $text="";
		  for($i=0;$i<count($dataArray);$i++)
		  {	if(preg_match("/http:/i",$dataArray[$i]))
			{	$text.="<a href='".$dataArray[$i]."' target='_blank'>".$dataArray[$i]."</a> ";
			}
			else if(preg_match("/https:/i",$dataArray[$i]))
			{  $text.="<a href='".$dataArray[$i]."' target='_blank'>".$dataArray[$i]."</a> ";
			}
			else if(preg_match("/www./i",$dataArray[$i]))
			  { $dataArray[$i]=str_replace("www.","http://www.",$dataArray[$i]);
				$text.="<a href='".$dataArray[$i]."' target='_blank'>".$dataArray[$i]."</a> ";
			  }
			else
				$text.=$dataArray[$i]." ";

		  }
		  //echo $text;exit;
		  return $text;
		  
		}*/
		/**
		 * Function to convert the simple text URLs in to hyperlinks
		 *
		 * @access public
		 * @ This function is presently used in  the messaging section.
		 */
		function hyperlink($text)
		{	//echo $text;exit;
		  $text=str_replace("www."," http://www.",$text);
          $text=str_replace("http:// http://"," http://",$text);
		  $text=str_replace("https:// http://","https://",$text);
		  $text=str_replace("$","&#36;",$text);
		  $text = ereg_replace("[a-zA-Z]+://([.]?[a-zA-Z0-9_/-/?%#&=-])*", "<a href=\"\\0\" target='_blank'>\\0</a>", $text);
		  $text = ereg_replace("/(http:\/\/[^\s]+)/", "<a href=\"$1\" target='_blank'>$1</a>", $text);
		  
		  return $text;
		}

		/**
        * This function replace links with new url.
		* @this is not presently used.
        */
        function replace_link_hyperlink($input){
            
            //$regex = '|http(s)?://([\w-]+\.)+[\w-]+(/[\w- ./?%&=]*)?|i';
            $regex = '|^((http(s)?://)?(www.))?([\w-]+\.)+[\w-]+(/[\w- ./?%&=]*)?|i';
            preg_match_all($regex,$input,$match);
            $replace = $match[0];
            
            while( is_array($replace) && list($key,$value) = each($replace) ){
                        
                        $patterns = '|' . $value . '|';
                        
                        $check_pattern = '|^http(s)?://|';
                        
                        if( ! preg_match($check_pattern, $value, $matches, PREG_OFFSET_CAPTURE) ){
                            $value_url = "http://" . $value;
                        }
                        else{
                            $value_url = $value;
                        }
                        

                        $input = preg_replace($patterns, "<a href='{$value_url}' target=new >".$value."</a>", $input);
            }
            
            return $input;

        }

        /**
		 * Function user for to know patient is associated with patient or not
		 *
		 * @access public
		 */
        function check_therapist($patient_id){
            $sql="select therapist_id from therapist_patient where patient_id=".$patient_id;
            $res=mysql_query($sql);
            if(mysql_num_rows( $res)>0 ){
               $flag=0;
               while($row=mysql_fetch_array($res)){
                if($row['therapist_id']==$this->userInfo('user_id')){
                   $flag=1; 
                }
               }
              }
              if($flag==1)
              return 1;
              else
              return 0;
            
        }
		
		/**
		 * Function to include get satisfaction script for providers pages
		 *
		 * @access public
		 */
		function get_satisfaction()
		{	
		  if($this->userInfo('usertype_id')==2 && $this->userInfo('therapist_access')==1){
          $text=file_get_contents('./js/get_satisfaction.js');
		 // return $text;
		  }
          return '';
        }

		/**
		 * Function to check whether a valid email address is entered or not.
		 *
		 * @access public
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
		 * @parameter: username
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
		 * Function to get the free trial days left for Provider/AA.
		 *
		 * @access public
		 * @parameter: userid
		 * @return: string	
		 */
		function getFreeTrialDaysLeft($user_id)
		{	
			$freetrialstr="";
			$query = "select trial_date from clinic c,clinic_user cu where c.clinic_id=cu.clinic_id and c.trial_status=1 and cu.user_id = ".$user_id;
            $result = mysql_query($query);
            if( mysql_num_rows($result) > 0){
                $row=mysql_fetch_object($result);
				$freetrialdate=$row->trial_date;
				$freetrial= db::$config['freetrial'];
								if($freetrialdate !=''){
								$sqlforfretrial="SELECT DATEDIFF(now(),'".$freetrialdate."') as freetrial ";
								$resultforfretrial = $this->execute_query($sqlforfretrial);
								$rowforfretrial  = $this->fetch_array($resultforfretrial);
				$daysleft=30-$rowforfretrial['freetrial'];
				if($this->userInfo('therapist_access')==1 && $this->userInfo('admin_access')==1)
					$action="trialperiodprompt";
				else if($this->userInfo('admin_access')==1 && $this->userInfo('therapist_access')!=1)
					$action="headtrialperiodprompt";
				else
					$action="trialperiodprompt";	
				if($daysleft <= 0)
					$freetrialstr='<li><a href="javascript:void(0);" onclick="freeTrialLeft_handler(\''.$action.'\');" style="font-weight:bold;">Trial Period is over</a></li>';
					
				else
					$freetrialstr='<li><a href="javascript:void(0);" onclick="freeTrialLeft_handler(\''.$action.'\');" style="font-weight:bold;">'.$daysleft.' days left in trial</a></li>';
				}
            }
			return $freetrialstr;
        }	//Function ends here

		/**
		 * This function calculates limit of referrals sending is reached or not.
		 *
		 * @return integer
		 * @access public
		 */
		static function referral_sent_count(){
		  $referral_access=0;
		  $sqlAddon = "select * from addon_services where clinic_id = '".self::clinicInfo('clinic_id')."'  and status = '1'";
            $resultAddon=@mysql_query($sqlAddon);
			if(@mysql_num_rows($resultAddon)!= 0)
                {  $rowAddon = @mysql_fetch_array($resultAddon);
				   $referral_access=$rowAddon['referral_report'];
			    }  

			if($referral_access==1)
			{
			 $query = "select clinic_refferal_limit as crl,clinic_user_limit as cul from referral_limit where clinic_id = '".self::clinicInfo('clinic_id')."' and status = '1'  ";
			 $result = @mysql_query($query);
			 if(@mysql_num_rows($result)>0)
			 {	 $row=@mysql_fetch_object($result);
				 $clinic_limit=$row->crl;
				 $clinic_patient_limit=$row->cul;
				 $query = "SELECT count( * ) AS clinic_sent_count FROM `patients_refferals` WHERE  clinic_id = ".self::clinicInfo('clinic_id')." AND substr( sent_date, 6, 2 )  = '".date("m")."'";
				 $res = @mysql_query($query);
				 $rw=@mysql_fetch_object($res);
				 $clinic_sent_count=$rw->clinic_sent_count;
				 //Condition if only clinic limit is set
				 if($clinic_limit > 0 && $clinic_patient_limit == 0)
				 {	
					if($clinic_sent_count < $clinic_limit)
						return 1;	
				}
				//else part of condition if clinic patient limit is also set.
				else if($clinic_limit > 0 && $clinic_patient_limit > 0)
				 {	//query to fetch how many referals sent by patient
					$query = "SELECT count( * ) AS patient_sent_count FROM `patients_refferals` WHERE  clinic_id = ".self::clinicInfo('clinic_id')." and user_id = ".self::userInfo('user_id')." AND substr( sent_date, 6, 2 )  = '".date("m")."'";
					$res2 = @mysql_query($query);
					$rw2=@mysql_fetch_object($res2);
					$patient_sent_count=$rw2->patient_sent_count;
					if($clinic_sent_count < $clinic_limit && $patient_sent_count < $clinic_patient_limit)
						return 1;	
				}
				
			}
			else
			{	return 1;
			}
			
		  }
		}

		/**
		 * This function renders the send referral link in side bar based on conditional criteria returned by function referral_sent_count().
		 *
		 * @return string
		 * @access public
		 */
		static function referral_link(){
		  
			 $referral_link='';
			 if(self::referral_sent_count())
			 {	 $referral_link='<li style="padding-top:5px; "><a title="Send referral to your friends, family, and colleagues, recommending the provider or clinic they go to." style="font-size:12px; margin-right:-5px; font-weight:bold; display:block; line-height:15px; padding-top:8px;" href="javascript:void(0);" onclick="GB_showCenter(\'Send Referral\', \'/index.php?action=sendreferral\',450,720);"><img src="images/icon-sendinvite.jpg" align="left" style=" margin-right:5px;"><span style="display:block; padding-top:11px;">Send Referral</span></a><div  style="clear:both;"></div></li>';
			}
			return $referral_link;
		}
        
        /**
		 * This function use for check user is registe in to prox or not.
		 * @parameter: userid
		 * @return string
		 * @access public
		 */
        function checkprxostatus($userid){
            $sql="SELECT * FROM user_prxo WHERE clinic_id=".$this->get_clinic_info($userid);
            $result=@mysql_query($sql);
            $numrow=@mysql_num_rows($result);
            if($numrow > 0)
            return 'register';
            else
            return 'not register';
        }
        /**
		 * This function use for check user is registe in to prox or not.
		 * @parameter: userid
		 * @return string
		 * @access public
		 */
        function checkprxoservice($userid){
            $sql="SELECT * FROM addon_services WHERE clinic_id=".$this->get_clinic_info($userid);
            $result=$this->execute_query($sql);
            $numrow=$this->num_rows($result);
            if($numrow > 0){
            $row=$this->fetch_object($result);
                if($row->wellness_store==1)
                    return 'wellness_store';
                elseif($row->store==1)
                    return 'store';
                elseif($row->widget==1)
                   return 'widget';
                else
                   return 'no';
            }else
            return 'no';
        }
        
          /**
		 * This function use for register patient into prxo.
		 * @parameter: userid
		 * @return string
		 * @access public
		 */
        function registerPatientPrxo($userid){
        ini_set("soap.wsdl_cache_enabled", "0");
        require_once('include/lib/nusoap.php');
        $client = new soapclientnusoap('https://www.purerxo.com/PureCommerce/TXXChange.asmx?WSDL','wsdl');
        $client->setDefaultRpcParams(true);
        //$soap_proxy = $client->getProxy();
        $param = array(
                           'firstName'  =>  $this->userInfo('name_first'),
                           'lastName'   =>  $this->userInfo('name_last'),
                           'email'      =>  $this->userInfo('username'),
                           'phone'      =>  $this->userInfo('phone1')
                           );
        //$result = $soap_proxy->CreatePatient($param);  
         
        $success=$result['CreatePatientResult']['results']['success'];
        $error=$result['CreatePatientResult']['results']['error'];
        $key_pass=$result['CreatePatientResult']['results']['key_pass'];
        $key_username=$result['CreatePatientResult']['results']['key_username'] ;
        $newId=0;
        if($key_pass!='' && $key_username!='')
        {
                $arr=array(
                           'user_id'=>$userid,
                           'usertype_id'=>$this->userInfo('usertype_id'),
                           'key_username'=>$key_username,
                           'key_pass'=>$key_pass,
                           'success'=>$success,
                           'error'=>$error,
                           'status'=>1
                           );
                $this->insert('user_prxo',$arr);
                $newId=$this->insert_id();
         }
       // print_r($result);
        if($newId==0)
        return 'fail';
        else
        return 'sucess';
        }
          
          
          
          /**
		 * This function use for register Provider into prxo.
		 * @parameter: userid
		 * @return string
		 * @access public
		 */
        function registerProviderPrxo($userid){
        ini_set("soap.wsdl_cache_enabled", "0");
        require_once('include/lib/nusoap.php');
        $client = new soapclientnusoap('https://www.purerxo.com/PureCommerce/TXXChange.asmx?WSDL','wsdl');
        $client->setDefaultRpcParams(true);
        //$soap_proxy = $client->getProxy();
        $clinicId=$this->clinicInfo('clinic_id');
        //Done to make partnerID 9 for wholemedex        
        $clinic_channel = $this->getchannel($clinicId);
        if($clinic_channel == '2') {
                $partnerID = '9';        
        } else {
                 $partnerID = '1';
        }
        //end here
        $clinicsql="select * from clinic where clinic_id=".$clinicId;
        $clinicres=$this->execute_query($clinicsql);
        $row=$this->fetch_object($clinicres);
        $param = array(
                        'firstName'=>$this->userInfo('name_first'),
                        'lastName'=>$this->userInfo('name_last'),
                        'address'=>$row->address,
                        'city'=>$row->city,
                        'state'=>$row->state,
                        'zip'=>$row->zip,
                        'email'=>$this->userInfo('username'),
                        'phone'=>$row->phone,
                        'partnerID'=>$partnerID,
                        'businessName'=>$this->get_clinic_info($userid,'clinic_name')
                        );
         //print_r($param);
         //die();  
         //uncomment on app
        //$result = $soap_proxy->CreateProvider($param);
        
        $success=$result['CreateProviderResult']['results']['success'];
        $error=$result['CreateProviderResult']['results']['error'];
        $key_pass=$result['CreateProviderResult']['results']['key_pass'];
        $key_username=$result['CreateProviderResult']['results']['key_username'] ;
        $key_sitename=$result['CreateProviderResult']['results']['key_sitename'] ;
        $newId=0;
        if($key_pass!='' && $key_username!='')
        {
                $arr=array(
                           'clinic_id'=>$this->get_clinic_info($userid),
                           'key_username'=>$key_username,
                           'key_pass'=>$key_pass,
                           'success'=>$success,
                           'error'=>$error,
                           'key_sitename'=>$key_sitename,
                           'status'=>1
                           );
                $this->insert('user_prxo',$arr);
                $newId=$this->insert_id();
         }
       // print_r($result);
        if($newId==0)
        return 'fail';
        else
        return 'sucess';
        }
         /**
		 * This function use for register patient into prxo.
		 * @parameter: userid
		 * @return string
		 * @access public
		 */
        function loginPatientPrxo($userid){
            $sql="SELECT * FROM user_prxo where user_id=".$userid;
            $res=mysql_query($sql);
            $row=mysql_fetch_array($res);
            ini_set("soap.wsdl_cache_enabled", "0");
            require_once('include/lib/nusoap.php');
            $client = new soapclientnusoap('https://www.purerxo.com/PureCommerce/TXXChange.asmx?WSDL','wsdl');
            $client->setDefaultRpcParams(true);
            //$soap_proxy = $client->getProxy();
            $param = array(
                            'username'=>$row['key_username'],
                            'password'=>$row['key_pass']
                           );
            //$result = $soap_proxy->LoginPatient($param);
           
            echo $_SESSION['key_login']=$result['LoginPatientResult']['key_login'];
            
           return true;
         }
        /**
         * This function use for register patient into prxo.
         * @parameter: userid
         * @return string
         * @access public
         */
        function loginProviderPrxo($userid){
            $sql="SELECT * FROM user_prxo where clinic_id=".$this->get_clinic_info($userid);
            $res=mysql_query($sql);
            $row=mysql_fetch_array($res);
            ini_set("soap.wsdl_cache_enabled", "0");
            require_once('include/lib/nusoap.php');
            $client = new soapclientnusoap('https://www.purerxo.com/PureCommerce/TXXChange.asmx?WSDL','wsdl');
            $client->setDefaultRpcParams(true);
            //$soap_proxy = $client->getProxy();
            $param = array(
                            'username'=>$row['key_username'],
                            'password'=>$row['key_pass']
                           );
            //$result = $soap_proxy->LoginProvider($param);
            $_SESSION['key_login']=$result['LoginProviderResult']['key_login'];
            //print_r($_SESSION);
            //print_r($result);
           // die();
           return true;
         }
        /**
         * This function use get prxo key
         * @parameter: userid
         * @return string
         * @access public
         */ 
         function get_prxo_key(){
           $sql="SELECT * FROM user_prxo where clinic_id=".$this->get_clinic_info($this->userInfo('user_id'));
           $res=mysql_query($sql);
           $row=mysql_fetch_array($res); 
           return $row['key_sitename'];
         }
         
        /**
         * This function used to get patient subscription details
         * @param integer $user_id
         * @return array
         * @access public
         * @Created 11/05/2011
         */
         public function getPatientSubscriptionDetails($user_id,$clinic_id){

                $querySubscription="select ps.*,cs.subs_title subs_title,DATE_FORMAT(subs_datetime,'%M %e, %Y') subsStartDate,DATE_FORMAT(subs_end_datetime,'%M %e, %Y') subsEndDate from patient_subscription ps,clinic_subscription cs  where cs.subs_id=ps.subscription_subs_id AND ps.clinic_id ='{$clinic_id}' AND ps.user_id='{$user_id}'";
                    // Condition to check if patient is under subscription or Cancelled but within subscription Date
                    $conditionSubscription=" AND ((ps.subs_status = '1' AND ps.paymentType='0' ) OR (ps.subs_status='2' AND DATE_FORMAT( subs_end_datetime, '%Y-%m-%e' ) >= DATE_FORMAT( now() , '%Y-%m-%e' )) or(ps.subs_status='1'  AND ps.paymentType='1' and DATE_FORMAT( subs_end_datetime, '%Y-%m-%e' ) >= DATE_FORMAT( now() , '%Y-%m-%e' )))";
                    $querySubscription.=$conditionSubscription;
                    
                    $resultQuery=$this->execute_query($querySubscription);
                    // Currently asuming single health service 
                    $resultQuerySubscription=$this->fetch_array($resultQuery);
                    return $resultQuerySubscription;         
         }         

        /**
         * This function used to check For assign/view Adult Intake Paper Work
         * @param integer $user_id
         * @param String $userRole
         * @return Bool
         * @access public
         * @Created 15/06/2011
         */
                  
         public function checkAssignIntakePaper($userId,$userRole="patient"){
                // Clinic Type From Config File
                $naturopathicClinicTypeId="2";
                // Provider Type From Config File
                $naturopathicProviderTypeId="2";
                
                $ProviderTypeId=$this->userInfo('practitioner_type',$userId);
                
                $clinicTypeId=1;
                
                
                                
                   
                if($userRole=="patient"){
                   
                    // Check For Provider Type for Patient
                   if($clinicTypeId==$naturopathicProviderTypeId) { return 'true'; } else { return 'false'; }
                   
                }else{
                    // Check For User is AA OR AA/Provider OR Provider
                    
                    $userInfo=$this->userInfo();                                  
                    //$userInfo=1;
                    $userTypeFlag='';
                    if($userInfo['therapist_access']=='1' && $userInfo['admin_access']=='1'){
                        // User is AA and Provider
                        $userTypeFlag.="AP";    
                        
                    }elseif($userInfo['admin_access']=='1'){
                        // User is AA Only
                        $userTypeFlag.="A";    
                    }elseif($userInfo['therapist_access']=='1'){
                        // User is Provider Only
                        $userTypeFlag.="P";                            
                    }
                    
                    // Check For Clinic Type for Provider/Account Admin
                   if($ProviderTypeId==$naturopathicClinicTypeId)     { return 'true'; } else { return 'false'; }
                   
                }
                
             
         }
         
        /**
         * This function used to check For assign/view Adult Intake Paper Work
         * @param integer $user_id
         * @param String $userRole
         * @return array
         * @access public
         * @Created 15/06/2011
         */
         
         public function getClinicDetails($user_id){
                $queryInfo=$this->execute_query("select clinic.*,clinic_type.clinic_type as clinic_title from clinic LEFT JOIN clinic_user ON clinic.clinic_id = clinic_user.clinic_id ,clinic_type where clinic.clinic_type=clinic_type.clinic_type_id AND clinic_user.user_id = '{$user_id}' ");
                
                $resultDetails=array();
                
                if($this->num_rows($queryInfo)>0){
                    $resultDetails=$this->fetch_array($queryInfo);   
                }
                return $resultDetails;      
         }
         
  	         /**
         * This function to assign persoanlized GUI into Session Variable
         * @access public
         * @return void
`        */       
        public function call_gui() {


                        
            $userInfoValue=$this->userInfo();
            $persoanlizedGUI=new lib_gui($userInfoValue);
            if(count($_SESSION['providerLabel'])>0 ){

            }else{            
                // Label v/s Provider Types
                $ProviderTypeLabelsArray=$persoanlizedGUI->getLabelValueProviders();
                foreach($ProviderTypeLabelsArray as $key=>$value){
                        $_SESSION['providerLabel'][$key]=$value;
                }
            }
            if(count($_SESSION['providerField'])>0 ){

            }else{
                // Field v/s Display status
                $ProviderTypeFieldsArray=$persoanlizedGUI->getFieldProvider();
                foreach($ProviderTypeFieldsArray as $key=>$value){
                        $_SESSION['providerField'][$key]=$value;
                }
            }
            if(count($_SESSION['accountFeature'])>0 ){

            }else{
                // For Display/Hide Features Based On Clinic Type
                $clinicTypeFeatures=$persoanlizedGUI->getFeatureClinicType();
                
                foreach($clinicTypeFeatures as $key=>$value){
                        $_SESSION['accountFeature'][$key]=$value;                
                }
            
            }
            
            // For Display/Hide Features Based on Provider Type
            $providerTypeFeatures=$persoanlizedGUI->getFeatureProviderType();
            
            //print_r($providerTypeFeatures);
            
            foreach($providerTypeFeatures as $key=>$value){
                        $_SESSION['providerFeature'][$key]=$value;                
                }            
            
         //echo '<pre>'; print_r($_SESSION); echo '</pre>';
        }       
	
  	
  	
         /**
         * This function used to check For assign/view Adult Intake Paper Work
         * @param integer $user_id
         * @param String $userRole
         * @return array
         * @access public
         * @Created 15/06/2011
         */
         
         public function getUserRole($user_id){
  	 		$clinic_id = self::clinicInfo("clinic_id",$user_id);
            if(is_numeric($clinic_id) && $clinic_id > 0){
            	$parentClinicId = parent::get_field($clinic_id,"clinic","parent_clinic_id");
                	if( is_numeric($parentClinicId) && $parentClinicId == 0){
                    	$userRole = "HeadAccountAdmin";
                        }
                    else{
                    	$userRole = "AccountAdmin";
                        }
            }
			return $userRole;
         }
         /**
         * This function used to get clinic channel
         * @param integer $clinci id
         */
  	public function getchannel($clinic_id){
  	       $sql="SELECT clinic_channel FROM clinic where clinic_id=".$clinic_id;
           $res=mysql_query($sql);
           $row=mysql_fetch_array($res); 
           return $row['clinic_channel'];	
  	}
  	     /**
         * This function used to get article file path
         * @param integer $article id
         * return string $path
         */
  	public function get_article_path($articleId){
           $sql="select *,date_format(creation_date,'%Y') as y1,date_format(creation_date,'%c') as m1,date_format(creation_date,'%e') as d1 from article where article_id=".$articleId;
           $res=mysql_query($sql);
           $row=mysql_fetch_array($res); 
           $year=$row['y1'];
            //echo '<br>';
            $month=$row['m1'];
            //echo '<br>';
            $date=$row['d1'];
            $path=$_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$year.'/'.$month.'/'.$date.'/'.$row['article_id'].'/'.$row['file_url'];
           return $path;   
    }
        /**
         * This function used to update article file path
         * @param integer $article id
         * return string $path
         */
  	public function update_article_path($articleId){
           $sql="select *,date_format(creation_date,'%Y') as y1,date_format(creation_date,'%c') as m1,date_format(creation_date,'%e') as d1 from article where article_id=".$articleId;
           $res=mysql_query($sql);
           $row=mysql_fetch_array($res); 
           $year=$row['y1'];
            //echo '<br>';
            $month=$row['m1'];
            //echo '<br>';
            $date=$row['d1'];
            $path=$year.'/'.$month.'/'.$date.'/'.$row['article_id'].'/'.$row['file_url'];
            $update="UPDATE article SET file_path ='".$path."' where article_id=".$articleId;
            @mysql_query($update);
    }
  	     /**
         * This function used to create file path
         * @param integer $article id
         * return string $path
         */    
    public function check_article_path($articleId){
           $sql="select *,date_format(creation_date,'%Y') as y1,date_format(creation_date,'%c') as m1,date_format(creation_date,'%e') as d1 from article where article_id=".$articleId;
           $res=mysql_query($sql);
           $row=mysql_fetch_array($res); 
           $year=$row['y1'];
            //echo '<br>';
            $month=$row['m1'];
            //echo '<br>';
            $date=$row['d1'];
  	    if(!is_dir($_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'])){
            mkdir($_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url']);
            chmod($_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'],0777);
        }       
       if(!is_dir($_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$year)){
            mkdir($_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$year);
            chmod($_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$year,0777);
        }        
       if(!is_dir($_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$year.'/'.$month)){
            mkdir($_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$year.'/'.$month);
            chmod($_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$year.'/'.$month,0777);
        }
       if(!is_dir($_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$year.'/'.$month.'/'.$date)){
            mkdir($_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$year.'/'.$month.'/'.$date);
            chmod($_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$year.'/'.$month.'/'.$date,0777);
        }
       if(!is_dir($_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$year.'/'.$month.'/'.$date.'/'.$row['article_id'])){
            mkdir($_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$year.'/'.$month.'/'.$date.'/'.$row['article_id']);
            chmod($_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$year.'/'.$month.'/'.$date.'/'.$row['article_id'],0777);
        }
            
        return $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$year.'/'.$month.'/'.$date.'/'.$row['article_id'].'/';        
          
    }
        /**
         * This function used to get article file path without file name for database update
         * @param integer $article id
         * return string $path
         */
  	public function get_article_path_for_database($articleId,$type=''){
           $sql="select *,date_format(creation_date,'%Y') as y1,date_format(creation_date,'%c') as m1,date_format(creation_date,'%e') as d1 from article where article_id=".$articleId;
           $res=mysql_query($sql);
           $row=mysql_fetch_array($res); 
           $year=$row['y1'];
            //echo '<br>';
            $month=$row['m1'];
            //echo '<br>';
            $date=$row['d1'];
            if($type=='display')
            $path=$this->config['tx']['article']['media_url'].$row['file_path'];
            else
            $path=$year.'/'.$month.'/'.$date.'/'.$row['article_id'].'/';
            return $path;   
    }
        /**
         * This function used to get mail form or subject encoding 
         * @param string $message
         * return string $messa ge
         */
    public function setmailheader($str){
    	 $str="=?UTF-8?B?".base64_encode($str)."?=";
    	 return $str;
    }
  	     /**
         * This function used to get provider list  
         * @param string $message
         * return string $message
         */
   
    public function get_paitent_provider($pid){
    	$query = "select therapist_id from therapist_patient 
                                  where patient_id = '{$pid}' and status = 1 ";
                        $result = @mysql_query($query);
                        $providerstr='';
                        //user1:name,user2:name
                        while( $row = @mysql_fetch_array($result)){
                        	//$providerstr.=md5($this->userInfo("username",$row["therapist_id"])).':';
                        	$providerstr.=($this->userInfo("username",$row["therapist_id"])).':';
                        	//$providerstr.=md5($row["therapist_id"]).':';
                        	$providerstr.=$this->userInfo("name_first",$row["therapist_id"]).' ';
                        	$providerstr.=$this->userInfo("name_last",$row["therapist_id"]).',';
                        }	
    	return $providerstr;
    }
  /**
         * This function used to get provider list  
         * @param string $message
         * return string $message
         */
   
    public function get_paitent_providerfor_opentok($pid){
    	$query = "select therapist_id from therapist_patient 
                                  where patient_id = '{$pid}' and status = 1 ";
                        $result = @mysql_query($query);
                        $providerstr='';
                        //user1:name,user2:name
                        while( $row = @mysql_fetch_array($result)){
                        	//$providerstr.=md5($this->userInfo("username",$row["therapist_id"])).':';
                        	$providerstr.=$row["therapist_id"].':';
                        	//$providerstr.=md5($row["therapist_id"]).':';
                        	$providerstr.=$this->userInfo("name_first",$row["therapist_id"]).' ';
                        	$providerstr.=$this->userInfo("name_last",$row["therapist_id"]).',';
                        }	
    	return $providerstr;
    }
        /**
         * This function used to Check the provider E health service status  
         * @param string $message
         * return string $message
         */
        function getProviderEhealthStatus($therapistClinicId) {

                if($therapistClinicId > 0) {
                        $sqlEhealth = "SELECT subs_status FROM clinic_subscription WHERE subs_clinic_id  = " . $therapistClinicId;
                        $queryEhealth = parent::execute_query($sqlEhealth);
                        $numEhealthRow = parent::num_rows($queryEhealth);
                        if($numEhealthRow!= '0') {
                                $valueEhealth = parent::fetch_object($queryEhealth);
                                $ehealthstatus = $valueEhealth->subs_status;
                        } else {

                                $ehealthstatus = '';
                        }
                                    
                }
                 return $ehealthstatus;
   	}

        //Functions for the mass patient assignment in EHS

        /**
         * This function used to Display the provider EHS patients 
         * @param string $ClinicId
         * return array $patientArr
         */
        public function getProviderEHSPatients($clinicId) {
            if($clinicId=='')  
        	$clinicId = self::clinicInfo('clinic_id');  
                if($clinicId > 0) {
                         //$privateKey = $this->config['private_key']; 
                         $sqlehsPatient = "SELECT u.user_id, u.username, u.status, u.ehs , cu.clinic_id, clinic.clinic_name 
                                          FROM
                                            user as u 
                                          inner join clinic_user as cu on u.user_id=cu.user_id 
                                            AND cu.clinic_id in (SELECT clinic_id FROM clinic WHERE parent_clinic_id = {$clinicId} OR clinic_id = {$clinicId} 
                                            and ( status = 1 or status = 2 ) ) INNER JOIN clinic on           
                                          clinic.clinic_id = cu.clinic_id AND (clinic.status = 1 or clinic.status = 2) 
                                          LEFT JOIN patient_subscription AS ps ON u.user_id = ps.user_id 
                                          WHERE u.usertype_id=1 and (u.status = '1' OR u.status = '2') AND ((ps.subs_status='1' AND subs_end_datetime > now())  OR (ps.subs_status='2' AND subs_end_datetime > now())) ORDER BY u.user_id"; 

                      $queryEhsPatient = $this->execute_query($sqlehsPatient);
                      $numEhealthRow = $this->num_rows($queryEhsPatient);
                      if($numEhealthRow!= '0') {
                                while( $row = @mysql_fetch_array($queryEhsPatient)) {
                                        $patientArr[] = $row['user_id'];
                                }
                             return  $patientArr;
                                
                      }

                }

        }

        /**
         * This function used to Display the provider EHS patients 
         * @param string $ClinicId
         * return array $patientArr
         */
        public function CountEHSPatients($clinicId) {
            if($clinicId=='')  
        	$clinicId = self::clinicInfo('clinic_id');  
                if($clinicId > 0) {
                         $sqlehsPatient = "SELECT u.user_id, u.username, u.status, u.ehs , cu.clinic_id, clinic.clinic_name 
                                          FROM
                                            user as u 
                                          inner join clinic_user as cu on u.user_id=cu.user_id 
                                            AND cu.clinic_id in (SELECT clinic_id FROM clinic WHERE parent_clinic_id = {$clinicId} OR clinic_id = {$clinicId} 
                                            and ( status = 1 or status = 2 ) ) INNER JOIN clinic on           
                                          clinic.clinic_id = cu.clinic_id AND (clinic.status = 1 or clinic.status = 2) 
                                          LEFT JOIN patient_subscription AS ps ON u.user_id = ps.user_id 

                                          WHERE u.usertype_id=1 and (u.status = 1 OR u.status = 2) AND (ps.subs_status='1' OR (ps.subs_status='2' AND subs_end_datetime > now())) ORDER BY u.user_id"; 

                      $queryEhsPatient = parent::execute_query($sqlehsPatient);
                      $numEhealthRow = parent::num_rows($queryEhsPatient);
                      if($numEhealthRow!= '0') {
                                while( $row = @mysql_fetch_array($queryEhsPatient)) {
                                        $patientArr[] = $row['user_id'];
                                }
                             return  $patientArr;
                                
                      }

                }

        }

        public function getProviderId($username) {

                if($username!= '') {
                
                        $sqlQuery = "SELECT user_id FROM user WHERE username = '{$username}' and status in (1,2)";
                        $query = $this->execute_query($sqlQuery);
                        $numRow = $this->num_rows($query);
                        if($numRow > 0) {
                                $row = @mysql_fetch_array($query);
                                $therapistId = $row['user_id'];
                        }

                }

                        return $therapistId;

        }

                 
   


        public function getporviderlist($clinicId=''){
        	 if($clinicId=='' || $clinicId==0)
        	$clinicId = self::clinicInfo('clinic_id');
        	 $query = "select clinic_user.user_id from clinic_user,user where clinic_user.user_id=user.user_id and user.usertype_id=2 and user.status=1 and clinic_id = '{$clinicId}'";
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
         * This function used to Display the EHS goals
         * @param string ''
         * return array $output(Ehs Goals)
         */   
        function goalEhs(){
            $clinicId = self::clinicInfo('clinic_id');      
            $query = " select * from patient_goal where  (status = '1' or status = '2' ) and parent_goal_id = '0' and ehsGoal = '1' AND clinicId = '{$clinicId}' order by created_on desc ";
            $result = @mysql_query($query);
            $replace['view_all'] = '';
            if( @mysql_num_rows($result) > 0 ){
                while( $row = @mysql_fetch_array($result) ){
                    $strike = "";
                    $checked = "";
                    if($row['status'] == 2 ){
                        $strike = 'text-decoration: line-through';
                        $checked = 'checked';
                    }
                    //$row['goal'] = $this->lengthtcorrect($row['goal'],38);
                    $replace['list_goal'] .= "<div id='div_{$row['patient_goal_id']}' onmouseover='show_trash(this,1);' onmouseout='show_trash(this,2);' style='width:250px;' >
                                    <span id='span_{$row['patient_goal_id']}'  style='{$strike};display: block; width: 210px; float: right; '  >".$row['goal']."</span>
                                    <span id='trash_{$row['patient_goal_id']}' style='visibility:hidden;' onclick='del_goal(this);'>
                                        <img src='/images/trash.gif'  />
                                    </span><input type='checkbox' name='chk_{$row['patient_goal_id']}' value='{$row['patient_goal_id']}' $checked onclick='stikeout(this);' />
                                    <div style='clear:both;'></div></div>";
                    
                }
                //$replace['view_all'] = '<a href="javascript:view_goal();"  id="allLink"  >View all</a>';
                
            }
            $output = $this->build_template($this->get_template('goal'),$replace);
            return $output;
        }

        function getClinicURL($clinicId) {
                if($clinicId > 0) {

                        $clinicId = self::encrypt_data($clinicId);
                        $clinicURL = $this->config['images_url']."/index.php?action=ehspatientRegistration&cid={$clinicId}";
                }
                return $clinicURL;
        }
        
        function get_patient_subscription($userid){
        	$sql="select * from patient_subscription where user_id={$userid} order by user_subs_id desc limit 0,1"; 
            $query = $this->execute_query($sql);
                        $numRow = $this->num_rows($query);
                        if($numRow > 0) {
                               return $row = @mysql_fetch_array($query);
                                
                        }
        	
        }
  	public function getTherapistlist($clinicId=''){
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
		function clear_temp_article(){ 
			
			$sql_select	=	"DELETE FROM temp_article where  user_id='{$this->userInfo('user_id')}' and clinic_id='{$this->clinicInfo('clinic_id')}'";
        	$res_select	=	$this->execute_query($sql_select);
		}
		
		function country_list(){
			$sql="SELECT `code`,`name` FROM `country` ORDER BY `country`.`order` ASC";
			$query=$this->execute_query($sql);
			while($row=$this->fetch_array($query)){
				$array[$row[code]]=$row[name];
				
			}
			return $array;
			
		}
  	function state_list($country_code){
			$sql="SELECT `state_code`,`state_name` FROM `state` WHERE country_code ='{$country_code}' ORDER BY `state_name` ASC";
			$query=$this->execute_query($sql);
			if($country_code=='CA')
			$array['']='Choose Province...';
			else
			$array['']='Choose state...';
			while($row=$this->fetch_array($query)){
				$array[$row[state_code]]=$row[state_name];
				
			}
			return $array;
			
		}
	function get_sub_fname($profileid){
		$sql="select b_first_name from patient_subscription where payment_paypal_profile='{$profileid}'";
		$res=$this->execute_query($sql);
		$num=$this->num_rows($res);
		if($num>0){
			$row=$this->fetch_array($res);
		}
		return $row['b_first_name'];
		
		
	}
  	function get_sub_lname($profileid){
		$sql="select b_last_name from patient_subscription where payment_paypal_profile='{$profileid}'";
		$res=$this->execute_query($sql);
		$num=$this->num_rows($res);
		if($num>0){
			$row=$this->fetch_array($res);
		}
		return $row['b_last_name'];
					
		}
		
		
		

    function allEmpty($array)
    {
        $array = array_filter($array); return empty($array); // (PHP >= 5.3) or just
        return array_filter($array) === array();
    }
  
     function is_corporate($clinicId){
        if($clinicId=='')
         $clinicId = self::clinicInfo('clinic_id');  
        
        $sql="Select corporate_ehs from clinic where clinic_id='{$clinicId}'";
        $res=  mysql_query($sql);
		$num=mysql_num_rows($res);
		if($num>0){
			$row=mysql_fetch_array($res);
                        //print_r($row);
		}
		return $row['corporate_ehs'];
					
    }
    
    function get_paitent_list($clinicId){
        $privateKey = $this->config['private_key'];
	$clinicId = $this->clinicInfo('clinic_id');
        if($clinicId=='')  
        	$clinicId = self::clinicInfo('clinic_id');  
                if($clinicId > 0) {
                         //$privateKey = $this->config['private_key']; 
                        $query = "select  u.user_id
		from user as u left join patient_subscription as ps on u.user_id=ps.user_id and ((ps.subs_status='1' and ps.paymentType='0') or (ps.subs_status='2' and subs_end_datetime >= now()) or (ps.subs_status='1'  and ps.paymentType='1' and subs_end_datetime > now()))
		inner join clinic_user as cu on u.user_id=cu.user_id
		and cu.clinic_id in (select clinic_id from clinic where (parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}') and ( status = 1 or status = 2 ) )
		inner join clinic  on clinic.clinic_id = cu.clinic_id and (clinic.status = '1' or clinic.status = '2')
		where u.usertype_id='1' and (u.status=1 or u.status = 2)";

                      $queryEhsPatient = $this->execute_query($query);
                      $numEhealthRow = $this->num_rows($queryEhsPatient);
                      if($numEhealthRow!= '0') {
                                while( $row = @mysql_fetch_array($queryEhsPatient)) {
                                        $patientArr[] = $row['user_id'];
                                }
                             return  $patientArr;
                                
                      }

                }
    }
    
    /**
     * Cancels the paypal profile of all patients associated to a clinic,
     * if unsuccessfull, this function logs in database table named "paypal_unsubscribes_errorlogs"
     * 
     * @param Int $clinicid Clinic ID whose patients needs to be unsubscribed
     * @return Array Response from paypal (An associative array)
     */
    protected function unsubscribeClinicEHSPatients($clinicid)
    {
        //include the necessary class file
        require_once 'include/class.paypal.php';

        //paypal response array
        $paypalresponse = array();

        // fetch patients whose EHS is ON and who are part of above clinic.
        $ehspatientsid = $this->getProviderEHSPatients($clinicid);

        if(count($ehspatientsid) > 0)
        {
            //call to paypal for unsubscribing the patients fetched above.
            $API_UserName = urlencode($this->config["paypalprodetails"]["API_UserName"]);
            $API_Password = urlencode($this->config["paypalprodetails"]["API_Password"]);
            $API_Signature = urlencode($this->config["paypalprodetails"]["API_Signature"]);
            $environment = urlencode($this->config["paypalprodetails"]["environment"]);

            $paypal = new paypalProRecurring($API_UserName, $API_Password, $API_Signature, $environment);

            //fetch the paypal_profile_id from patient_subscriptions table
            $resultset = $this->select('patient_subscription', "", "`user_subs_id`, `user_id`, `payment_paypal_profile`, `subscription_title`", "user_id IN (" . implode(",", $ehspatientsid) . ")");
            $ehspatients = $this->fetch_all_rows($resultset);

            //current user logged in details, for providers details
            $userInfor = $this->userInfo();

            foreach($ehspatients as $data)
            {
                $profileID = urlencode($data['payment_paypal_profile']);
                $action = urlencode("Cancel");
                $note = urlencode("Subscription cancelled for " . $data['subscription_title']);
                $nvpStr = "&PROFILEID=$profileID&ACTION=$action&NOTE=$note";

                $paypalresponse = $paypal->PPHttpPost('ManageRecurringPaymentsProfileStatus', $nvpStr);

                if(isset($paypalresponse['txn_type']) && !empty($paypalresponse['txn_type']))
                {
                    $txn_type = $paypalresponse['txn_type'];
                }
                else
                {
                    $txn_type = 'NULL';
                }

                // fill history log of profile activity
                $serializeData = $this->encrypt_data(serialize($paypalresponse));
                $this->execute_query("insert into paypal_history set paypal_profile_number='{$data['payment_paypal_profile']}', user_id='{$userInfor['user_id']}', return_data='{$serializeData}', data_type='unsubscribe' , status_time= now(), txn_type='{$txn_type}' ");
                
                //unsubscribe was not successful
                if(!stristr(strtolower($paypalresponse['ACK']), 'success'))
                {
                    //log it in database table
                    $this->insert("paypal_unsubscribes_errorlogs", array(
                        'clinic_id' => $clinicid,
                        'provider_id' => $userInfor['user_id'],
                        'patient_id' => $data['user_id'],
                        'payment_paypal_profile' => $data['payment_paypal_profile'],
                        'details' => $serializeData,
                        'modified' => date('Y-m-d H:i:s', time())
                    ));
                }
            }
            return $paypalresponse;
        }
    }
    
    /**
     * Cancels the paypal profile of all providers associated to a clinic,
     * if unsuccessfull, this function logs in database table named "paypal_unsubscribes_errorlogs"
     * 
     * @param Int $clinicid Clinic ID whose patients needs to be unsubscribed
     * @return Array Response from paypal (An associative array)
     */
    protected function unsubscribeClinicEHSProviders($clinicid)
    {
        //include the necessary class file
        require_once 'include/class.paypal.php';

        //paypal response array
        $paypalresponse = array();

        // fetch patients whose EHS is ON and who are part of above clinic.
        $ehsprovidersid = $this->getporviderlist($clinicid);

        if($ehsprovidersid != "")
        {
            //call to paypal for unsubscribing the patients fetched above.
            $API_UserName = urlencode($this->config["clinicpaypalprodetails"]["API_UserName"]);
            $API_Password = urlencode($this->config["clinicpaypalprodetails"]["API_Password"]);
            $API_Signature = urlencode($this->config["clinicpaypalprodetails"]["API_Signature"]);
            $environment = urlencode($this->config["clinicpaypalprodetails"]["environment"]);

            $paypal = new paypalProRecurring($API_UserName, $API_Password, $API_Signature, $environment);

            //fetch the paypal_profile_id from patient_subscriptions table
            $resultset = $this->select('provider_subscription', "", "`user_subs_id`, `user_id`, `reurring_profile_id`", "user_id IN (" . $ehsprovidersid . ")");
            $ehsproviders = $this->fetch_all_rows($resultset);
            
            //current user logged in details, for providers details
            $userInfor = $this->userInfo();

            foreach($ehsproviders as $data)
            {
                $profileID = urlencode($data['reurring_profile_id']);
                $action = urlencode("Cancel");
                $note = urlencode("Subscription cancelled for " . $this->get_clinic_info($userInfor['user_id'], "clinic_name"));
                $nvpStr = "&PROFILEID=$profileID&ACTION=$action&NOTE=$note";

                $paypalresponse = $paypal->PPHttpPost('ManageRecurringPaymentsProfileStatus', $nvpStr);

                if(isset($paypalresponse['txn_type']) && !empty($paypalresponse['txn_type']))
                {
                    $txn_type = $paypalresponse['txn_type'];
                }
                else
                {
                    $txn_type = 'NULL';
                }

                // fill history log of profile activity
                $serializeData = $this->encrypt_data(serialize($paypalresponse));
                $this->execute_query("insert into paypal_history set paypal_profile_number='{$data['reurring_profile_id']}', user_id='{$userInfor['user_id']}', return_data='{$serializeData}', data_type='unsubscribe' , status_time= now(), txn_type='{$txn_type}' ");

                //unsubscribe was not successful
                if(!stristr(strtolower($paypalresponse['ACK']), 'success'))
                {
                    //log it in database table
                    $this->insert("paypal_unsubscribes_errorlogs", array(
                        'clinic_id' => $clinicid,
                        'provider_id' => $userInfor['user_id'],
                        'patient_id' => $data['user_id'],
                        'payment_paypal_profile' => $data['reurring_profile_id'],
                        'details' => $serializeData,
                        'modified' => date('Y-m-d H:i:s', time())
                    ));
                }
            }
            return $paypalresponse;
        }
    }
    
    /**
     * unsubscribes a clinic and all associated users, providers
     * 
     * only for clinics whose payment method is online and whose 
     * billing cycle has expires
     * 
     * @param int $clinicid
     */
    public function unsubscribeClinicUsers($clinicid)
    {
        //check if clinic has unsubscribed
        $rsunsubscribed = $this->execute_query("SELECT user_subs_id, unsubscribed FROM provider_subscription WHERE clinic_id = '{$clinicid}' AND unsubscribed = 'yes'");

        if($this->num_rows($rsunsubscribed) > 0)
        {
            //there are two tables in which the subscription expiry date is maintained
            if($this->userInfo('usertype_id') != '1')
            {
                $table = "provider_subscription";
            }
            else
            {
                $table = "patient_subscription";
            }

            //check if the billing cycle is over or not.
            $rssubscriptionstatus = $this->execute_query("
                SELECT
                    user_id,
                    user_subs_id
                FROM
                    {$table}
                WHERE
                    clinic_id = '{$clinicid}'
                AND
                    subs_end_datetime < NOW()
            ");

            if($this->num_rows($rssubscriptionstatus) > 0)
            {
                //deactivate all the associated users
                $sql = "
                    select 
                        clinic_user.user_id  as user_id 
                    from 
                        clinic_user, user 
                    where 
                        clinic_user.user_id = user.user_id 
                        and clinic_user.clinic_id = '{$clinicid}' 
                        and user.usertype_id = 2";

                $res = $this->execute_query($sql);

                if($this->num_rows($res) > 0)
                {
                    $rows = $this->fetch_all_rows($res);

                    foreach($rows as $row)
                    {
                        $sqluser = "update user set trial_status=null, free_trial_date=null, status = '2' where user_id = " . $row['user_id'];
                        $this->execute_query($sqluser);
                    }
                }

                //update the 'status' column in database table
                $this->update("clinic", array('status' => '2'), "clinic_id = {$clinicid} or parent_clinic_id = {$clinicid}");

                //update the patient_subscription table with unsubscribe information
                $this->update("patient_subscription", array('subs_status' => '2'), "clinic_id = {$clinicid}");

                //update the provider_subscription table with unsubscribe information
                $this->update("provider_subscription", array('subs_status' => '2'), "clinic_id = {$clinicid}");
            }
        }
    }
    
    /**
     * Checks if a payment for a clinic is made online
     * 
     * @param int $clinicid The clinic id whose payment mode needs to be checked
     * @return boolean
     */
    public function clinicMadeOnlinePayment($clinicid)
    {
        $resultsetpaymentmode = $this->select("provider_subscription", "", "clinic_id, user_id", "clinic_id = '{$clinicid}'");

        if($this->num_rows($resultsetpaymentmode) > 0)
            return true;
        else
            return false;
    }
    
}

?>
