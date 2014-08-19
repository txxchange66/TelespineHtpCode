<?php
	/**
	 *
	 * Copyright (c) 2008 Tx Xchange
	 * 
	 * It includes the functionality for the creation of health record.
	 * It also includes the payment of patient by the paypal recuring subscription and one time payment
	 * 
	 */
		
	
	// Including files	
	require_once("module/application.class.php");
        require_once("include/class.paypal.php");
	require_once("include/validation/_includes/classes/validation/ValidationSet.php");
	require_once("include/validation/_includes/classes/validation/ValidationError.php");	
	
	// Class declaration
  	class ehspatientRegistration extends application {
  		
	// class variable declaration section

	
  		/**
  		 * Get action from header and calling function
  		 * The variable defines the action request
  		 *
  		 * @var string
  		 * @access private
  		 */
		private $action;
		
		
		
		/**
		 * Populate html form elements as an array
		 * The variable defines all the fields present in the form
		 *
		 * @var array
		 * @access private
		 */
		private $form_array;		
		
		
		
		/**
  		 * The variable defines the error message(if any) against the action request
  		 * It could be an array if more than one error messages are there else a simple variable
		 *
		 * @var string
		 * @access private
		 */
		private $error;	
		
		
		
		/**
         * Pass as parameter in display() function which shows output in browser.
         * 
  		 * The variable is used for getting final output template or string message to be displayed to the user
		 * This function of statement(s) are to handle all the actions supported by this Login class
		 * that is it could be the case that more then one action are handled by login
		 * for example at first the action is "login" then after submit say action is submit
		 * so if login is explicitly called we have the login action set (which is also our default action)
		 * else whatever action is it is set in $str.
		 *
		 * @var string
		 * @access private
		 */
		private $output;
		
		
		
		/**
		 *  constructor
		 *  set action variable from url string, if not found action in url, call default action from config.php
		 * 
		 */		
		function __construct(){
			parent::__construct();
			$this->form_array = $this->populate_form_array();
			if($this->value('action')){
			/*
			This block of statement(s) are to handle all the actions supported by this Login class
			that is it could be the case that more then one action are handled by login
			for example at first the action is "home" then after submit say action is submit
			so if login is explicitly called we have the home action set (which is also our default action)
			else whatever action is it is set in $str.				
			*/
				$str = $this->value('action');
			}else{				
				$str = "home"; //default if no action is specified
			}

			$this->action = $str;
			$str = $str."()";
			eval("\$this->$str;"); 
			$this->display();			
		}

		
	/**
         * This function returns the subscription information based on clinic id associated with the Patients.
         * @param Integer $clinic_id
         * @return array $subscriptionInfo
         * @access public
         */      
           
        function subscriptionInfo($clinic_id) {
                 
                $querySubs="select clinic_subscription.*,clinic.* from health_settings,clinic_subscription,clinic where 
                                    health_settings.setting_id=clinic_subscription.setting_id AND clinic.clinic_id = 
                                    health_settings.setting_clinic_id AND clinic.clinic_id='{$clinic_id}' ";
                 $resultSubs = $this->execute_query($querySubs);
                 $resultSubsArray=$this->fetch_array($resultSubs);
                 return $resultSubsArray;  
        
        } 

         /**
         * This function returns the Provider for the clinic
         * @param Integer $clinic_id
         * @return array $subscriptionInfo
         * @access public
         */      
        function clinicProviderInfo($clinicId) {
                 $privateKey = $this->config['private_key']; 
                   $sqlUser = "SELECT user.user_id, CONCAT_WS(' ',
                    AES_DECRYPT(UNHEX(user.name_title),'{$privateKey}'), 
                    AES_DECRYPT(UNHEX(user.name_first),'{$privateKey}'),
                    AES_DECRYPT(UNHEX(user.name_last),'{$privateKey}'),
                    name_suffix) AS full_name
                    FROM user 
                    inner join clinic_user on clinic_user.user_id = user.user_id 
                    and (user.status = '1' or user.status = '2') 
                    and clinic_user.clinic_id in (select clinic_id from clinic where parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}' and ( status = 1 or status = 2) ) 
                    inner join clinic on clinic.clinic_id = clinic_user.clinic_id and ( clinic.status = '1' or clinic.status = '2' ) WHERE user.usertype_id = '2' order by full_name";
                $resultSubs = $this->execute_query($sqlUser);
                $numRow = $this->num_rows($resultSubs);
                 if($numEhealthRow!= '0') {
                        while( $row = $this->fetch_array($resultSubs)) {
                                $resultSubsArray[$row['user_id']] = $row['full_name'];
                        }
                 }
                 return $resultSubsArray;                 
        } 
		/**
		 * Function to return error description
		 *
		 * @access public
		 */
  	function PaypalErroCodeslist($errorCode){
	if($errorCode!='' or $errorCode!='0' ){
		$sql="select * from paypal_error_code where error_code='{$errorCode}'";
		$result=$this->execute_query($sql);
		if($this->num_rows($result)>0){
			$row=$this->fetch_object($result);
			$customerrormessage=$row->long_message;
		}else{
			$customerrormessage="Invalid Credit Card Details";
		}
	}else{
		$customerrormessage="Invalid Credit Card Details";
	}
	return $customerrormessage;
}
		/**
		 * Function to Edit Clinic Details
		 *
		 * @access public
		 */
		function ehspatientRegistration() {
 
	                $replace = array();
                        //Encrypted clinic id                        
                        $cid = $this->value('cid');
                        //Clinic id decryption starts here
                        $privateKey = $this->config['private_key'];
                        $query = "select AES_DECRYPT(UNHEX('{$cid}'),'{$privateKey}')";
                        $result = @mysql_query($query);
                        if ($result) {
                                $clinic_id = @mysql_result($result, 0);
                                $replace['clinic_id'] = $cid;
                        }
                        //Clinic id decryption End here


                        //Paypal Error codes starts here
                        $errorCode=$this->value('errorCode');
                        if($errorCode!='') {
                            $customerrormessage=$this->PaypalErroCodeslist($errorCode);
                            if($customerrormessage=='')                    
                                {
                                    $customerrormessage="Invalid Credit Card Details";                        
                                }
                            $replace['errorMessage']='<tr><td colspan="4" style="height:20px; color:#0069a0;; font-weight:bold"><font style="color:#FF0000;font-weight:normal;">'.$customerrormessage.'</font></td></tr><tr><td colspan="4" style="height:5px; color:#0069a0;; font-weight:bold"></td></tr>';   
                            
                        }

                        //End here


     $replace['namefValid']='<font style="color:#FF0000;font-weight:normal;">'.urldecode($this->value('namefValid')).'</font>';
            if($this->value('validData')=='true')   {
                    if(urldecode($this->value('namefValid'))!=''){      
                        $replace['namefValid']='<font style="color:#FF0000;font-weight:normal;">'.urldecode($this->value('namefValid')).'</font>';                                         
                     $replace['retainedFname']='';
                    }     
                      $replace['retainedFname']=$_SESSION['pateintSubs']['fname'] ;                                                                            
                    if(urldecode($this->value('namelValid'))!='')       {
                        $replace['namelValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('namelValid').'</font>';                                                  
                     $replace['retainedlname']='' ; 
                    }
  $replace['retainedlname']=$_SESSION['pateintSubs']['lname'] ;                     
                     if(urldecode($_REQUEST['emailValid'])!='')       {
                        $replace['emailValid']='<font style="color:#FF0000;font-weight:normal;">'.$_REQUEST['emailValid'].'</font>';                                                  
                    
                     $replace['retainedEmail']='' ; 
                    }
                        $replace['retainedEmail']=$_SESSION['pateintSubs']['emailAddress'] ;  
                                                                    
                    if(urldecode($this->value('cnumberValid'))!='')   {     
                        $replace['cnumberValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cnumberValid').'</font>';
                        $replace['retainedcardNumber'] = '';
                     } 
                                                                                      
                    $replace['retainedcardNumber'] = $_SESSION['pateintSubs']['cardNumber']; 
                                                                                                          
                    if(urldecode($this->value('cexpValid'))!='')  {           
                        $replace['cexpValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cexpValid').'</font>';
                        //$replace['retainedcardNumber'] = '';
                      }                                                          
                    if(urldecode($this->value('ccvvValid'))!='') {            
                        $replace['ccvvValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('ccvvValid').'</font>'; 
                        $replace['retainedcvvNumber'] = '';
                    }                                      
         $replace['retainedcvvNumber'] = $_SESSION['pateintSubs']['cvvNumber'];                                        
                    if(urldecode($this->value('ctypeValid'))!='') {                
                        $replace['ctypeValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('ctypeValid').'</font>'; 
                        $replace['retainVisaType'] = '';
                        $replace['retainMasterType'] = '';
                     }
                        if($_SESSION['pateintSubs']['cardType'] == 'Visa') {
                                $replace['retainVisaType'] = 'selected="selected"';
                                $replace['retainMasterType'] = '';
                        }
                        elseif($_SESSION['pateintSubs']['cardType'] == 'MasterCard') {
                                $replace['retainMasterType'] = 'selected="selected"';
                                $replace['retainVisaType'] = '';
                        }
                        else {
                                $replace['retainVisaType'] = '';
                                $replace['retainMasterType'] = '';
                        }
                                                                    
                    if(urldecode($this->value('address1Valid'))!='')   {           
                       $replace['address1Valid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('address1Valid').'</font>';                                              
                       $replace['retainedadd1']='' ;  
                    }     
                       
                       
                    
                        $replace['retainedadd1']=$_SESSION['pateintSubs']['add1'] ;
                     
                       
                    if(urldecode($this->value('cityValid'))!='')    {               
                        $replace['cityValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cityValid').'</font>';                                                         
                        $replace['retainedcity']='';  
                    }
                        
                        $replace['retainedcity']=$_SESSION['pateintSubs']['city'] ;
                     
                                                            
                    if(urldecode($this->value('stateValid'))!='')  {                  
                        $replace['stateValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('stateValid').'</font>';                                             
                    
                        $replace['retainedstate']=''; 
                    
                    }         
                        
                        $replace['retainedstate']=$_SESSION['pateintSubs']['state'] ;
                     
                            
                    if(urldecode($this->value('zipcodeValid'))!='')    {               
                        $replace['zipcodeValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('zipcodeValid').'</font>';                                               
                    
                        $replace['retainedzip']='' ;
                    }
                        
                    
                        $replace['retainedzip']=$_SESSION['pateintSubs']['zip'] ;

                

                    if(urldecode($this->value('address2'))=='')
                        $replace['retainedadd2']=$_SESSION['pateintSubs']['add2'] ;
                    } else {
                    $replace['namefValid']='';
                    $replace['retainedFname']='';      
                    $replace['namelValid']=''; 
                    $replace['emailValid']=''; 
                    $replace['retainedlname']='' ;     
                    $replace['cnumberValid']='';   
                    $replace['cexpValid']='';   
                    $replace['ccvvValid']='';   
                    $replace['ctypeValid']='';
                    $replace['address1Valid']=''; 
                    $replace['retainedadd1']='' ;   
                    $replace['retainedadd2']='' ;               
                    $replace['cityValid']='';
                    $replace['retainedcity']='' ;         
                    $replace['stateValid']='';
                    $replace['retainedstate']='' ;         
                    $replace['zipcodeValid']='';
                    $replace['retainedzip']='' ; 
                    $replace['RetaincountryUS']='' ;                 
                    $replace['RetaincountryCAN']='' ; 
                    $replace['retainedEmail']='';  
                    $replace['retainedcardNumber']='';             
                    $replace['retainVisaType']=''; 
                    $replace['retainMasterType']=''; 
                    $replace['retainedcvvNumber']='';
                         
             }
            //echo "test".$_SESSION['pateintSubs']['clinicProvider'];
            $dataSubs=$this->subscriptionInfo($clinic_id);
       
            $ehsSubscriptionStatus = $dataSubs['subs_status'];
            
           $clinicProviders=$this->clinicProviderInfo($clinic_id);

          //$clinicProvidersArray = array("" => "Select Provider");
           $clinicProvidersArray = $clinicProviders;//print_r($clinicProvidersArray);exit;
           $replace['clinicProvidersOptions'] = $this->build_select_option($clinicProvidersArray, $_SESSION['pateintSubs']['clinicProvider']);

            //$replace['header'] = $this->build_template($this->get_template("header"));
            //$replace['header'] = "<img src='images/logo2.gif' width='191' height='66' alt='Tx Xchange' class='headLogo' />";
			$clinic_channel=$this->getchannel($clinic_id);
                if($clinic_channel==2){
	            $logotx='wxlogo';
	        }else{
	            $logotx='txlogo';
	        }
            $header_1 = "<div id='line'><div id='".$logotx."' style='float:left; padding-left:55px;' ></div><div id='subheader' style='float:right; padding-right:55px;' ><div id='cliniclogo'></div></div><div style='clear:both;'></div></div>";
	     $query = " select * from clinic where clinic_id = '{$clinic_id}' ";
            $result_2 = @mysql_query($query);
            if( $row_1 = @mysql_fetch_array($result_2) ){
                if($row_1['clinic_logo'] == ""){
                	$header_1 = "<div id='line'><div id='".$logotx."' style='float:left; padding-left:55px;' ></div><div id='subheader' style='float:right; padding-right:55px;' ><div id='cliniclogo'></div></div><div style='clear:both;'></div></div>";
                }else{
                $final_logo="<div class='cliniclogo_patient' style='float:left; padding-left:35px;padding-top:10px;' ><table><tr><td style='text-align:center; height:120px;'><a href='{$row_1['clinic_website_address']}' target='new' style='cursor: hand;' ><img alt='' src='/asset/images/clinic_logo/{$row_1['clinic_logo']}' border='0'/></a></td></tr></table></div>";	
                $header_1 = "<div id='line'>{$final_logo}<div id='subheader' style='float:right;'>{$referral_link}</div><div style='clear:both;'></div></div>";	
                }
            }
            $replace['header']=$header_1;
            $replace['footer'] = $this->build_template($this->get_template("footer"));    
            $replace['HealthProgramName']=$dataSubs["subs_title"];
            $replace['HealthProgramDescription']=html_entity_decode(str_replace('$','$ ',$dataSubs["subs_description"]));
            $replace['ehsTimePeriod']=$dataSubs["ehsTimePeriod"]; 
            $replace['paymentType']=$dataSubs["paymentType"];
         
            
            $keyFeatureNum=0;
            
            if($dataSubs['subs_feature1']!=''){
                $replace['keyFeatures']="<li>".$dataSubs['subs_feature1']."</li>";
                $keyFeatureNum++;
            }
            if($dataSubs['subs_feature2']!=''){
                $replace['keyFeatures'].="<li>".$dataSubs['subs_feature2']."</li>";
                $keyFeatureNum++;
            }
            if($dataSubs['subs_feature3']!=''){
                $replace['keyFeatures'].="<li>".$dataSubs['subs_feature3']."</li>";
                $keyFeatureNum++;
            }
            if($dataSubs['subs_feature4']!=''){
                $replace['keyFeatures'].="<li>".$dataSubs['subs_feature4']."</li>";
                $keyFeatureNum++;
            }
            if($dataSubs['subs_feature5']!=''){
                $replace['keyFeatures'].="<li>".$dataSubs['subs_feature5']."</li>";
                $keyFeatureNum++;
            }
            if($dataSubs['subs_feature6']!=''){
                $replace['keyFeatures'].="<li>".$dataSubs['subs_feature6']."</li>";
                $keyFeatureNum++;
            }
            if($dataSubs['subs_feature7']!=''){
                $replace['keyFeatures'].="<li>".$dataSubs['subs_feature7']."</li>";
                $keyFeatureNum++;
            }
            
            if($keyFeatureNum>0){
                    $replace["KeyFeatureHeading"]="Key Features";
            }
            
            
            $replace['HealthProgramID']=$dataSubs['subs_id'];
            if($dataSubs['paymentType'] == '0') {
                $replace['HealthProgramDuration'] = "a month";
                $replace['HealthProgramPrice']=$dataSubs['subs_price'];
                $replace['HealthProgramData'] = "This is an online health service provided by ". $dataSubs['clinic_name'].". There are no contracts and you can cancel at any time.";
            } else {

                if($dataSubs['ehsTimePeriod'] > 1)
                        $replace['HealthProgramDuration'] = " for ".$dataSubs['ehsTimePeriod']. " months";
                else 
                        $replace['HealthProgramDuration'] = " for ".$dataSubs['ehsTimePeriod']. " month";
                $replace['HealthProgramPrice']=$dataSubs['onetime_price'];
                $replace['HealthProgramData'] = "This is an online health service provided by ".$dataSubs['clinic_name'].". At the end of the term listed above, your credit card will <u>not</u> be charged again.";
            }
            $replace['HealthProgramClinicName']=$dataSubs['clinic_name'];
           // $replace['body'] = $this->build_template($this->get_template("ehspatientRegistration"),$replace);

            $replace['browser_title'] = "Tx Xchange: Patient Subscription";
            
            
            //replace the states array for US and CANADA 
            /*$stateArray = array("" => "Choose state...");
            
            $stateArray = array_merge($stateArray,$this->config['state']);

            foreach($stateArray as $key=>$value){
                    $stateJavascript.="'{$key}':'{$value}',";
            }
            $stateJavascript=trim($stateJavascript,',');
            
            
            //$replace['state']=implode("','",$stateArray);
            $replace['state']=$stateJavascript;
            

            $stateCanadaArray = array("" => "Choose Province...");
            $stateCanadaArray = array_merge($stateCanadaArray,$this->config['canada_state']);

            foreach($stateCanadaArray as $key=>$value){
                    $canadastateJavascript.="'{$key}':'{$value}',";
            }
            $canadastateJavascript=trim($canadastateJavascript,',');
          // echo "<pre>"; print_r($_REQUEST);exit;
            //$replace['canada_state']=implode("','",$stateCanadaArray);
            $replace['canada_state']=$canadastateJavascript;*/
            
            $monthArray = array();
            $monthArray = $this->config['monthsArray'];
            $replace['monthsOptions'] = $this->build_select_option($monthArray, $_SESSION['pateintSubs']['exprMonth']); 
           
            $yearArray = array();
            $yearArray = $this->config['yearArray'];
            $replace['yearOptions'] = $this->build_select_option($yearArray, $_SESSION['pateintSubs']['exprYear']);
            $countryarray=$this->country_list(); 
			$replace['country']=$this->build_select_option($countryarray, $_SESSION['pateintSubs']['country']);
            /*$replace['RetaincountryCAN']=''; 
            $replace['RetaincountryUS']='';*/
            //checks if user country is US in session
            /*if($_SESSION['pateintSubs']['country']=='US'){ 
                 $replace['RetaincountryUS']='selected';
                 $replace['RetaincountryCAN']='';
                 $replace['patient_state_options'] = $this->build_select_option($stateArray, $_SESSION['pateintSubs']['state']);         
            //checks if user country is Canada in session
            }elseif($_SESSION['pateintSubs']['country']=='CAN'){   
                      $replace['RetaincountryUS']='';
                      $replace['RetaincountryCAN']='selected';                     
                      $replace['stateOptions'] = $this->build_select_option($stateCanadaArray, $_SESSION['pateintSubs']['state']);         

            }else{
                $replace['patient_state_options'] = $this->build_select_option($stateArray, $replace['patient_state']);                      
            }*/
			if($_SESSION['pateintSubs']['country']=='')
			$country_code='US';
			else
			$country_code=$_SESSION['pateintSubs']['country'];
			$stateArray=$this->state_list($country_code);
                        //$this->printR($stateArray);
			$replace['stateOptions']=$this->build_select_option($stateArray, $_SESSION['pateintSubs']['state']);
            //Unsetting the Session variable stored for Remembering field values if any wrong/incomplete info submitted by                  user.
            //print_r($_SESSION);
            unset($_SESSION['pateintSubs']);
            if($clinic_id == '') {
                                $Ehsmessage = "HTTP Error 400 - Bad Request /Bad File Request. Please contact system administrator.";
                                $replace['Ehsmessage'] = $Ehsmessage;
                                $replace['body'] = $this->build_template($this->get_template("ehspatientRegistration1"),$replace);
             }else
            if($ehsSubscriptionStatus == '0' || $ehsSubscriptionStatus == '') {
            	
            	if($dataSubs["subs_title"]!='') {

                    $Ehsmessage = "We're sorry, but ".$dataSubs["subs_title"]." is not currently being offered at this time.";
            	} else {
            		
            		$Ehsmessage = "We're sorry, but clinic's E-Health service is not currently being offered at this time.";
            	} 
                $replace['Ehsmessage'] = $Ehsmessage;
                $replace['body'] = $this->build_template($this->get_template("ehspatientRegistration1"),$replace);
             }  else {
                        if($clinic_id == '') {
                                $Ehsmessage = "HTTP Error 400 - Bad Request /Bad File Request. Please contact system administrator.";
                                $replace['Ehsmessage'] = $Ehsmessage;
                                $replace['body'] = $this->build_template($this->get_template("ehspatientRegistration1"),$replace);
                        } else {
                                $replace['body'] = $this->build_template($this->get_template("ehspatientRegistration"),$replace);
                }

            }

                        $querymsg = $_REQUEST['alreadysub'];
                        $registered = $_REQUEST['registered'];
                        $subscribed = $_REQUEST['subscribed'];
                        if(isset($querymsg)) {
                            session_destroy();  
                        	$SubscriptionMessage = "<script>GB_showCenter('Sign-up Confirmation', '/index.php?action=loginsubscription&ehsname={$dataSubs["subs_title"]}',150,400);</script>";
                            $replace['SubscriptionMessage'] = $SubscriptionMessage;

                        }

                        if(isset($registered) || isset($subscribed)) {
                        	   session_destroy();
                              $RegistrationMessage = "<script>GB_showCenter('Sign-up Confirmation' , '/index.php?action=patientRegistration&subscribed={$subscribed}&ehsname={$dataSubs["subs_title"]}',170,550);</script>";
                              $replace['RegistrationMessage'] = $RegistrationMessage;

                        }
        
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}
		
		

		/**
		 * This function gets the template path from xml file.
		 *
		 * @param string $template - pass template file name as defined in xml file for that template file.
		 * @return string - template file
		 * @access private
		 */		
		function get_template($template){
			$login_arr = $this->action_parser($this->action,'template') ;
			$pos =  array_search($template, $login_arr['template']['name']); 
			return $login_arr['template']['path'][$pos];
		}
		
		/**
		 * This function sends the output to browser.
		 * 
		 * @access public
		 */		
		function display(){
			view::$output =  $this->output;
		}
		
		/**
		 * This function populating $form_array class variable for form editClinicInfo.php
		 *
		 * @return array
		 * @access public
		 */
		function populate_form_array()
		{
			$arr = array(
							'clinic_name'		=> 'clinic_name',
							'name_first' 			=> 'name_first',
							'name_last' 			=> 'name_last',
							'email' 			=> 'email',
							'practitiner_type' 	=> 'practitiner_type',
							'clinic_zip' 		=> 'clinic_zip'                            
						);
			return $arr;			
		}


                /**
         * Function to validate Credit Card Infromation and create Recurring CC Profile.
         * @access public
         */
        function submit_ehs_patient_subscription()
        {
            
            $cid = $this->value('cid');
              $privateKey = $this->config['private_key'];
              $query = "select AES_DECRYPT(UNHEX('{$cid}'),'{$privateKey}')";
              $result = @mysql_query($query);
              if ($result) {
                        $clinic_id = @mysql_result($result, 0);
              }
              
        	//Configuration settings...
            $API_UserName       =       urlencode($this->config["paypalprodetails"]["API_UserName"]);
            $API_Password       =       urlencode($this->config["paypalprodetails"]["API_Password"]);
            $API_Signature      =       urlencode($this->config["paypalprodetails"]["API_Signature"]);
            $environment        =       urlencode($this->config["paypalprodetails"]["environment"]);
            $currencyID         =       urlencode($this->config["paypalprodetails"]["currencyID"]);
            $Frequency          =       urlencode($this->config["paypalprodetails"]["billingPeriod"]);
            $billingFreq        =       urlencode($this->config["paypalprodetails"]["billingFreq"]);

            $firstName          =       $this->value('firstname');
            $lastName           =       $this->value('lastname');
            $emailaddress       =       $_REQUEST['emailaddress'];
            $cid                =       $_REQUEST['cid'];
            $clinicProvider     =       $this->value('clinicProvider');
            $creditCardType     =       $this->value('cardType');
            $creditCardNumber   =       $this->value("cardNumber");
            $expDateMonth       =       $this->value('exprMonth');
            $expDateYear        =       $this->value('exprYear');
            $cvv2Number         =       $this->value('cvvNumber');
            $address1           =       $this->value('address1');
            $address2           =       $this->value('address2');
            $city               =       $this->value('city');
            $state              =       $this->value('state');
            $zipcode            =       $this->value('zipcode');
            $country            =       $this->value('country');
            $ehsTimePeriod      =       $this->value('ehsTimePeriod');
            $paymentType        =       $this->value('paymentType');
            $onetime_price      =       $this->value('onetime_price');
            
            //Code To Validate User Entered Data
            $error=array();
            if(trim($firstName) =="" ){
                $error['namefValid']="Please enter firstname";   
            }

            if(trim($lastName)==""){
                $error['namelValid']="Please enter lastname";   
            }

            if(trim($emailaddress)==""){
                $error['emailValid']="Please enter email address";   
            }  

            /*if(trim($clinicProvider)==""){
                $error['providerValid']="Please select provider";   
            }*/              
            
            if(trim($creditCardNumber)=="" || strlen(trim($creditCardNumber))<14 || strlen(trim($creditCardNumber))>16){
                $error['cnumberValid']="Please enter valid credit card number";
            }
            
            if(trim($creditCardType)==""){
                $error['ctypeValid']="Please select credit card type";
            }

            if(trim($expDateMonth)=="" || trim($expDateYear)=="" ){
                $error['cexpValid']="Please select credit card expiration";
            }
                        
            if(trim($cvv2Number)==""){
                $error['ccvvValid']="Please enter CVV number";
            }else if(!is_numeric(trim($cvv2Number))){
                $error['ccvvValid']="Please enter valid CVV number";
            }
            if(trim($address1)==""){
                $error['address1Valid']="Please enter address1";
            }
            if(trim($city)==""){
                $error['cityValid']="Please enter city";
            }
            if(trim($state)==""){
                $error['stateValid']="Please select state/province";
            }
            if(trim($zipcode)==""){
                $error['zipcodeValid']="Please enter zip/postal code";
            }
            //Valid email address check
             $validEmail = $this->isValidEmail($emailaddress);
             if(!$validEmail) {
                $error['emailValid']= "Please enter valid email address";
             }
              $emailAlreadyexist = $this->checkEmail($emailaddress,$clinic_id);
                if($emailAlreadyexist == 4) {
                $error['emailValid']= "This email address already exists.<br>Please choose another.";
             }elseif($emailAlreadyexist ==3){
              $error['emailValid']= "This email address already exists.<br>Please choose another.";
             }

                
            
           /*
           *
           return '1'// do only payment                
           return '2';//subscription exist
           return '3';//exist other clinic
           return '4';//exist not a patient
           return '5';// email not exist create health record and mank payment
                      
             * 
             * */ 
             
             if(!empty($error)){
                $messageReturn="&validData=true";
                foreach($error as $key=>$value)   {
                    $messageReturn.="&".$key."=".urlencode($value);
                }
                $_SESSION['pateintSubs']['fname']=$firstName;
                $_SESSION['pateintSubs']['lname']=$lastName;
                $_SESSION['pateintSubs']['emailAddress']=$emailaddress;
                $_SESSION['pateintSubs']['add1']=$address1;
                $_SESSION['pateintSubs']['add2']=$address2;    
                $_SESSION['pateintSubs']['city']=$city;
                $_SESSION['pateintSubs']['state']=$state;
                $_SESSION['pateintSubs']['zip']=$zipcode; 
                $_SESSION['pateintSubs']['country']=$country;
                $_SESSION['pateintSubs']['cardType']=$creditCardType;
                $_SESSION['pateintSubs']['exprMonth']=$expDateMonth;
                $_SESSION['pateintSubs']['exprYear']=$expDateYear;
                $_SESSION['pateintSubs']['cardNumber']=$creditCardNumber;
                $_SESSION['pateintSubs']['cvvNumber']=$cvv2Number;                         
                header("location:index.php?action=ehspatientRegistration&cid={$cid}".$messageReturn);
                die();
            }

            
            if($emailAlreadyexist ==2){
             $error['alreadysub']="There is already an account in our system with this email address. Please use a different one.";
                         if(!empty($error)){
                        $messageReturn="&validData=true";
                        foreach($error as $key=>$value)   {
                            $messageReturn.="&".$key."=".urlencode($value);
                        }
                        header("location:index.php?action=ehspatientRegistration&cid={$cid}".$messageReturn);
                        die();
                 }	
            }   

            $newuser='';
               if($emailAlreadyexist == 5) {                
              //Create patient registration
                        include_once("template/ehspatientRegistration/patientArray.php");
                        $this->formArray = $formArray;
                        $replace = $this->fillForm($this->formArray,true);
                        $this->formArray = $tableFieldArray;	
		        $tableArray = ($this->fillTableArray($this->formArray));
                        $tableArray['username'] = $emailaddress;
                        $tableArray['ehs'] = '1';
                        $tableArray['status'] = '1';
                        $tableArray['usertype_id'] = 1;
                        $tableArray['usertype_id'] = 1;
                        $tableArray['created_by'] = 1;
                        $tableArray['creation_date'] = date('Y-m-d H:i:s');
                        $tableArray['modified'] = date('Y-m-d H:i:s');
                        $tableArray['mass_message_access'] = 1;
                        $tableArray['password'] = $this->value('lastname').'01';
 
                        
                $clinic_user=0;
                if($this->insert($this->config['table']['user'], $tableArray)) {
                	$new_patient_id=$this->insert_id();
                    $providerList=$this->getTherapistlist($clinic_id);
                    if($providerList!=''){
                    	$providerlistarray=explode(',',$providerList);
                    	foreach($providerlistarray as $key=>$val){
                    		$data = array(
                                        'therapist_id' => $val,
                                        'patient_id' => $new_patient_id,
                                        'creation_date' => date('Y-m-d H:i:s'),
                                        'modified' => date('Y-m-d H:i:s'),
                                        'status' => '1'
                                        );
                            $this->insert($this->config['table']['therapist_patient'], $data); 
                            $clinic_user=1;       
                    	       }
                        }    
                	
                	    $newuser=1;
                        $userInfor=$this->userInfo($new_patient_id);
                        if($clinic_user==1){

                    $data = array(
					'clinic_id' => 	$clinic_id,
					'user_id'	=>  $new_patient_id,
				        );

                                // Insert row in clinic_user table to set relation between clinic and patient
                       if($this->insert($this->config['table']['clinic_user'], $data)) {
				        $replace['error'] = "successfull entry of record";	
                                   // mail to new patient contains username and password.//have the HTML content 

                       
                                }
                        }
                }
              /* $sql=  $this->execute_query("select * from addon_services where clinic_id={$clinic_id}");
               $queryauto=$this->fetch_object($sql);
               if($queryauto->automatic_scheduling==1){
                   
                   //assign content
                //Assigned Health Video Series
              $this->schedulerEhsPlanAssignment(1,$new_patient_id,$clinic_id);
               //Assigned articles
              $this->schedulerEhsArticle(1,$new_patient_id,$clinic_id);
               //Assigned Goal
              $this->schedulerEhsGoal(1,$new_patient_id,$clinic_id);
               //Assigned Reminders
              $this->schedulerAddEhsReminder(1,$new_patient_id,$clinic_id);
              // code for assign Oswestry form to user
               }
               */
                
        } elseif($emailAlreadyexist==1) {

                  $new_patient_id = $this->getProviderId($emailaddress);
                  $userInfor=$this->userInfo($new_patient_id);
                   $newuser=2; 
             /*  $sql=  $this->execute_query("select * from addon_services where clinic_id={$clinic_id}");
               $queryauto=$this->fetch_object($sql);
               if($queryauto->automatic_scheduling==1){
                   
                   //assign content
                //Assigned Health Video Series
              $this->schedulerEhsPlanAssignment(1,$new_patient_id,$clinic_id);
               //Assigned articles
              $this->schedulerEhsArticle(1,$new_patient_id,$clinic_id);
               //Assigned Goal
              $this->schedulerEhsGoal(1,$new_patient_id,$clinic_id);
               //Assigned Reminders
              $this->schedulerAddEhsReminder(1,$new_patient_id,$clinic_id);
              // code for assign Oswestry form to user
               }*/
                   
                   

        }

                /*pay pal code $paymentType 0 for recurring and 1 for onetime payment*/
                 $paymentAmount = urlencode($this->value('cardPayment'));
                 $currencyID = urlencode($currencyID);// or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
                 $startDate = urlencode(date("Y-m-d")."T0:0:0");
                 $billingPeriod = urlencode($Frequency);                // or "Day", "Week", "SemiMonth", "Year"
                 $billingFreq = urlencode($billingFreq); // combination of this and  must be at most a year
                 $clinicName=html_entity_decode($this->get_clinic_info($new_patient_id,"clinic_name"),ENT_QUOTES, "UTF-8");
                $desc=urlencode($clinicName.' - '.html_entity_decode($this->value('HealthDescription'),ENT_QUOTES, "UTF-8"));
 				$BillingstreetAddr=urlencode($address1);
                $Billingstreet2Addr=urlencode($address2);
                $BillingCity=urlencode($city);
                $BillingState=urlencode($state);
                $BillingCountry=urlencode($country);
                $Billingzip=urlencode($zipcode);
                if($paymentType==0) {
                       
                     $nvpStr="&FIRSTNAME=$firstName&LASTNAME=$lastName&EMAIL=$emailaddress&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=$expDateMonth$expDateYear&CVV2=$cvv2Number&AMT=$paymentAmount&CURRENCYCODE=$currencyID&PROFILESTARTDATE=$startDate";
                    $nvpStr .= "&BILLINGPERIOD=$billingPeriod&BILLINGFREQUENCY=$billingFreq&DESC=$desc";
            // For Billing Address
            
               
                $nvpStr .= "&STREET=$BillingstreetAddr&STREET2=$Billingstreet2Addr&CITY=$BillingCity&STATE=$BillingState&COUNTRYCODE=$BillingCountry&ZIP=$Billingzip";
            
            // Code to Limit the MAXFAILEDPAYMENTS
             $nvpStr .='&MAXFAILEDPAYMENTS=1&AUTOBILLOUTAMT=AddToNextBilling';
                $this->submit_patient_subscription($nvpStr,$new_patient_id,$clinic_id, $cid,$newuser);
                } else {
   
             $nvpStr="&PAYMENTACTION=Sale&AMT=$paymentAmount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=".$expDateMonth.$expDateYear."&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state"."&ZIP=$zipcode&COUNTRYCODE=$BillingCountry&CURRENCYCODE=$currencyID&EMAIL=".$emailaddress."&DESC=".$desc;
             $this->submit_patient_onetime_subscription($nvpStr,$new_patient_id,$clinic_id, $cid,$newuser);

}
                

        }


        function isValidEmail($email){
	        return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
        }

/**
         * Function to validate Credit Card Infromation and create Recurring CC Profile.
         * @access public
         */
        function submit_patient_subscription($nvpStr,$new_patient_id,$clinic_id, $cid,$newuser)
        {
            $userInfor=$this->userInfo($new_patient_id);
            $API_UserName=urlencode($this->config["paypalprodetails"]["API_UserName"]);
            $API_Password=urlencode($this->config["paypalprodetails"]["API_Password"]);
            $API_Signature=urlencode($this->config["paypalprodetails"]["API_Signature"]);
            $environment=urlencode($this->config["paypalprodetails"]["environment"]);
            $currencyID=urlencode($this->config["paypalprodetails"]["currencyID"]);
            $Frequency=urlencode($this->config["paypalprodetails"]["billingPeriod"]);
            $billingFreq=urlencode($this->config["paypalprodetails"]["billingFreq"]);
            
            $firstName          =       $this->value('firstname');
            $lastName           =       $this->value('lastname');
            $emailaddress       =       $_REQUEST['emailaddress'];
            $cid                =       $_REQUEST['cid'];
            //$clinicProvider     =       $this->value('clinicProvider');
            $creditCardType     =       $this->value('cardType');
            $creditCardNumber   =       $this->value("cardNumber");
            $expDateMonth       =       $this->value('exprMonth');
            $expDateYear        =       $this->value('exprYear');
            $cvv2Number         =       $this->value('cvvNumber');
            $address1           =       $this->value('address1');
            $address2           =       $this->value('address2');
            $city               =       $this->value('city');
            $state              =       $this->value('state');
            $zipcode            =       $this->value('zipcode');
            $country            =       $this->value('country');
            $ehsTimePeriod      =       $this->value('ehsTimePeriod');
            $paymentType        =       $this->value('paymentType');
            $onetime_price      =       $this->value('onetime_price');            


                //echo $nvpStr."<br>"; 
            // Paypal Recurring Object
            $paypalProClass=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
             $userInfor=$this->userInfo();
            // Paypal API request For Recurring profile creation.
            $insertArr['user_Id']=$new_patient_id;
            $insertArr['clinic_id']=$clinic_id;
            $insertArr['date']=date("Y-m-d H:i:s");
            $insertArr['subscription_id']= $this->value('HealthProgramID');
            $sqlPaymentSubscription="SELECT * FROM patient_subscription
							WHERE user_id={$new_patient_id} AND ((subs_status='1' AND subs_end_datetime > now())
							OR (subs_status='2' AND subs_end_datetime > now()))";
            $querysubscription = $this->execute_query($sqlPaymentSubscription);
            $numquerysubscription = $this->num_rows($querysubscription);
            if($numquerysubscription==0){
            	$sqlrequest="select * from subscription_request where user_Id='{$new_patient_id}' and clinic_id='{$clinic_id}' and status='1'";
	    	$requestquery=$this->execute_query($sqlrequest);
	    	if($this->num_rows($requestquery)==0){	
            		$this->insert('subscription_request',$insertArr);
            		$statusid=$this->insert_id();
            		$httpParsedResponseAr = $paypalProClass->PPHttpPost('CreateRecurringPaymentsProfile', $nvpStr);            
            		$this->paypalHistory($httpParsedResponseAr,$new_patient_id,trim(urldecode($httpParsedResponseAr['PROFILEID'])),'signup');
            		// Check the status of the payment.If success OR success with warning then change status and update database and make insertions
            		if(strtolower($httpParsedResponseAr['ACK'])=="success" || strtolower($httpParsedResponseAr['ACK'])=="successwithwarning")    {
            		    // Make the database changes and redirect to the home page.
            		    $this->saveDataSubscription(trim(urldecode($httpParsedResponseAr['PROFILEID'])),$startDate,$new_patient_id,$clinic_id);
            			    if($newuser==1) {
            			            $this->sendmailtopaitentcreate($new_patient_id,$clinic_id);
            			            $error['registered']="registration complete.";
            			    } else {
            			            $error['subscribed']="successfully subscribed.";
            			    }
                                    /* code for automatic scheduling assign first day content*/
                                     $sql=  $this->execute_query("select * from addon_services where clinic_id={$clinic_id}");
                                     $queryauto=$this->fetch_object($sql);
                                     if($queryauto->automatic_scheduling==1){
                   
                                                //assign content
                                                //Assigned Health Video Series
                                                $this->schedulerEhsPlanAssignment(1,$new_patient_id,$clinic_id);
                                                //Assigned articles
                                                $this->schedulerEhsArticle(1,$new_patient_id,$clinic_id);
                                                //Assigned Goal
                                                $this->schedulerEhsGoal(1,$new_patient_id,$clinic_id);
                                                //Assigned Reminders
                                                $this->schedulerAddEhsReminder(1,$new_patient_id,$clinic_id);
                                     }
                                    /* code end*/
                                    
		                // header("location:index.php?action=login"); 

        		       $this->execute_query("update subscription_request set status='2' where id='".$statusid."'"); 

        		        if(!empty($error)){
        			        $messageReturn="&validData=true";
        			        foreach($error as $key=>$value)   {
			                    $messageReturn.="&".$key."=".urlencode($value);
                			}
		                        header("location:index.php?action=ehspatientRegistration&cid={$cid}".$messageReturn);
                		        die();
                		}
                		//die();
            		}
           		 else
           		{
              
                		// Payment unsucessfull redirect to the Paymnet page again with error code.
                		$errorCode=$httpParsedResponseAr['L_ERRORCODE0'];
		                $_SESSION['pateintSubs']['fname']=$firstName;
                		$_SESSION['pateintSubs']['lname']=$lastName;
                		$_SESSION['pateintSubs']['emailAddress']=$emailaddress;
                		$_SESSION['pateintSubs']['add1']=$address1;
                		$_SESSION['pateintSubs']['add2']=$address2;    
                		$_SESSION['pateintSubs']['city']=$city;
                		$_SESSION['pateintSubs']['state']=$state;
                		$_SESSION['pateintSubs']['zip']=$zipcode; 
                		$_SESSION['pateintSubs']['country']=$country;
                		$_SESSION['pateintSubs']['cardType']=$creditCardType;
                		$_SESSION['pateintSubs']['exprMonth']=$expDateMonth;
                		$_SESSION['pateintSubs']['exprYear']=$expDateYear;
                		$_SESSION['pateintSubs']['cardNumber']=$creditCardNumber;
                		$_SESSION['pateintSubs']['cvvNumber']=$cvv2Number;
                		//$_SESSION['pateintSubs']['clinicProvider']=$clinicProvider; 
               		
                
                		//Delete the newly created patient record
                		$querydelPatient = "DELETE FROM user WHERE user_id = '{$new_patient_id}'";
                		$this->execute_query($querydelPatient);

                		//Delete the newly created patient record from therapist patient
                		$querydelTherapistPatient = "DELETE FROM therapist_patient WHERE patient_id  = '{$new_patient_id}'";
                		$this->execute_query($querydelTherapistPatient);

                		//Delete the newly created patient record from therapist patient
                		$querydelClinicUser = "DELETE FROM clinic_user WHERE user_id = '{$new_patient_id}'";
                		$this->execute_query($querydelClinicUser);
                		 $this->execute_query("update subscription_request set status='3' where id='".$statusid."'");    
                		header("location:index.php?action=ehspatientRegistration&cid={$cid}&validData=true&errorCode={$errorCode}");    
            		}
            	}else{
               	 	header("location:index.php?action=ehspatientRegistration&cid={$cid}&validData=true&errorCode={$errorCode}");    
            	}
           }else{
           
           	header("location:index.php?action=ehspatientRegistration&cid={$cid}&validData=true&errorCode={$errorCode}");
           
           }
        }
        function submit_patient_onetime_subscription($nvpStr,$new_patient_id,$clinic_id, $cid,$newuser) {
        
            $API_UserName=urlencode($this->config["paypalprodetails"]["API_UserName"]);
            $API_Password=urlencode($this->config["paypalprodetails"]["API_Password"]);
            $API_Signature=urlencode($this->config["paypalprodetails"]["API_Signature"]);
            $environment=urlencode($this->config["paypalprodetails"]["environment"]);
            $currencyID=urlencode($this->config["paypalprodetails"]["currencyID"]);
            $Frequency=urlencode($this->config["paypalprodetails"]["billingPeriod"]);
            $billingFreq=urlencode($this->config["paypalprodetails"]["billingFreq"]);
            $userInfor=$this->userInfo($new_patient_id);
        
            $firstName          =       $this->value('firstname');
            $lastName           =       $this->value('lastname');
            $emailaddress       =       $_REQUEST['emailaddress'];
            $cid                =       $_REQUEST['cid'];
            //$clinicProvider     =       $this->value('clinicProvider');
            $creditCardType     =       $this->value('cardType');
            $creditCardNumber   =       $this->value("cardNumber");
            $expDateMonth       =       $this->value('exprMonth');
            $expDateYear        =       $this->value('exprYear');
            $cvv2Number         =       $this->value('cvvNumber');
            $address1           =       $this->value('address1');
            $address2           =       $this->value('address2');
            $city               =       $this->value('city');
            $state              =       $this->value('state');
            $zipcode            =       $this->value('zipcode');
            $country            =       $this->value('country');
            $ehsTimePeriod      =       $this->value('ehsTimePeriod');
            $paymentType        =       $this->value('paymentType');
            $onetime_price      =       $this->value('onetime_price');
            
            $paypalProClass=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
            
            // Paypal API request For Recurring profile creation.
            $httpParsedResponseAr = $paypalProClass->hash_call('doDirectPayment', $nvpStr);            
               /* echo "<pre>";
            print_r($httpParsedResponseAr);
           echo "</pre>";
            die;*/
            // Code to fill history Log of Profile creation
            $this->paypalHistory($httpParsedResponseAr,$new_patient_id,$httpParsedResponseAr['TRANSACTIONID'],'onetime');
            
           
            // Check the status of the payment.If success OR success with warning then change status and update database and make insertions
            if(strtolower($httpParsedResponseAr['ACK'])=="success" || strtolower($httpParsedResponseAr['ACK'])=="successwithwarning")    {
                // Make the database changes and redirect to the home page.
                $this->saveDataSubscriptionOneTime(trim(urldecode($httpParsedResponseAr['TRANSACTIONID'])),$startDate,$new_patient_id,$clinic_id);
               // header("location:index.php?action=login"); 
                if($newuser==1) {
                        $this->sendmailtopaitentcreate($new_patient_id,$clinic_id);
                        $error['registered']="registration complete.";
                } else {
                         $error['subscribed']="successfully subscribed.";
                }
                /* code for automatic scheduling assign first day content*/
                 $sql=  $this->execute_query("select * from addon_services where clinic_id={$clinic_id}");
                 $queryauto=$this->fetch_object($sql);
                 if($queryauto->automatic_scheduling==1){
                 //assign content
                 //Assigned Health Video Series
                    $this->schedulerEhsPlanAssignment(1,$new_patient_id,$clinic_id);
                  //Assigned articles
                    $this->schedulerEhsArticle(1,$new_patient_id,$clinic_id);
                   //Assigned Goal
                    $this->schedulerEhsGoal(1,$new_patient_id,$clinic_id);
                    //Assigned Reminders
                    $this->schedulerAddEhsReminder(1,$new_patient_id,$clinic_id);
                 }
                 /* code end*/
                if(!empty($error)){
                $messageReturn="&validData=true";
                foreach($error as $key=>$value)   {
                    $messageReturn.="&".$key."=".urlencode($value);
                }
                        header("location:index.php?action=ehspatientRegistration&cid={$cid}".$messageReturn);
                        die();
                }

                
            }
            else
            { 
                //die();
                // Payment unsucessfull redirect to the Paymnet page again with error code.
                $errorCode=$httpParsedResponseAr['L_ERRORCODE0'];
                $_SESSION['pateintSubs']['fname']=$firstName;
                $_SESSION['pateintSubs']['lname']=$lastName;
                 $_SESSION['pateintSubs']['emailAddress']=$emailaddress;
                $_SESSION['pateintSubs']['add1']=$address1;
                $_SESSION['pateintSubs']['add2']=$address2;    
                $_SESSION['pateintSubs']['city']=$city;
                $_SESSION['pateintSubs']['state']=$state;
                $_SESSION['pateintSubs']['zip']=$zipcode; 
                $_SESSION['pateintSubs']['country']=$country;
                $_SESSION['pateintSubs']['cardType']=$creditCardType;
                $_SESSION['pateintSubs']['exprMonth']=$expDateMonth;
                $_SESSION['pateintSubs']['exprYear']=$expDateYear;
                $_SESSION['pateintSubs']['cardNumber']=$creditCardNumber;
                $_SESSION['pateintSubs']['cvvNumber']=$cvv2Number; 
                //$_SESSION['pateintSubs']['clinicProvider']=$clinicProvider; 
                //echo "<br>";
                  //   echo "$nvpStr,$new_patient_id,$clinic_id";exit;   
                //Delete the newly created patient record
                $querydelPatient = "DELETE FROM user WHERE user_id = '{$new_patient_id}'";
                $this->execute_query($querydelPatient);

                //Delete the newly created patient record from therapist patient
                $querydelTherapistPatient = "DELETE FROM therapist_patient WHERE patient_id  = '{$new_patient_id}'";
                $this->execute_query($querydelTherapistPatient);

                //Delete the newly created patient record from therapist patient
                $querydelClinicUser = "DELETE FROM clinic_user WHERE user_id = '{$new_patient_id}'";
                $this->execute_query($querydelClinicUser); 

               header("location:index.php?action=ehspatientRegistration&cid={$cid}&validData=true&errorCode={$errorCode}");       
            }
           
        }


        function sendmailtopaitentcreate($new_patient_id,$clinic_id){
        	$clinicName=html_entity_decode($this->get_clinic_info($new_patient_id,'clinic_name'), ENT_QUOTES, "UTF-8");
                       $clinic_channel=$this->getchannel($this->get_clinic_info($new_patient_id,'clinic_id'));

                                if($clinic_channel==2){
                                        $business_url=$this->config['business_wx']; 
                                        $support_email=$this->config['email_wx']; 
                                } else {
                                       $business_url=$this->config['business_tx']; 
                                        $support_email=$this->config['email_tx'];                          
                                }
                                
                                $data = array(
                    'username' => $this->userInfo("username",$new_patient_id),
                    'password' => $this->userInfo("password",$new_patient_id),
                    'url' => $this->config['url'],
                    'images_url' => $this->config['images_url'],
                                        'clinic_name'=>$clinicName,
                    'business_url'=>$business_url,
                                        'support_email'=>$support_email,
                                        'name'=>$this->userInfo("name_first",$new_patient_id)
                    );

                               $clinic_type = $this->getUserClinicType($new_patient_id);
                        
                                if( $clinic_channel == '2') {
                    $message = $this->build_template("mail_content/wx/create_new_patient_mail_plpto.php",$data);
                                }else{
                        $message = $this->build_template("mail_content/plpto/create_new_patient_mail_plpto.php",$data); 
                                }
           $to = $this->userInfo("username",$new_patient_id);
            $subject = "Your ".$clinicName." Health Record";            
            // To send HTML mail, the Content-type header must be set
            $headers  = 'MIME-Version: 1.0' . "\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";

                        if( $clinic_channel == 1){
                $headers .= "From: ".$this->setmailheader($clinicName). " <".$this->config['email_tx'].">" . "\n";
                $returnpath = "-f".$this->config['email_tx'];
                } else {
                $headers .= "From: ".$this->setmailheader($clinicName). " <".$this->config['email_wx'].">" . "\n";
                $returnpath = '-f'.$this->config['email_wx'];   
            }
        // Mail it
                    mail($to, $subject, $message, $headers, $returnpath);
        	
        }
        function sendwelcomemail($new_patient_id,$clinic_id){
                                        $fullname=$this->userInfo("name_first",$new_patient_id).' '.$this->userInfo("name_last",$new_patient_id);
                                        $business_url=$this->config['business_telespine']; 
                                        $support_email=$this->config['email_telespine']; 
                     $data = array(
                                   'username' => $this->userInfo("username",$new_patient_id),
                                    'password' => $this->userInfo("password",$new_patient_id),
                                    'url' => $this->config['url'],
                                    'images_url' => $this->config['images_url'],
                                    'clinic_name'=>$clinicName,
                                    'business_url'=>$business_url,
                                    'support_email'=>$support_email,
                                    'fullname'=>$fullname,
                                    'loginurl'=>  $this->config['telespine_login']
                    );
            $message = $this->build_template("mail_content/telespine/welcomesignup_email.php",$data);
            $to = $fullname."<".$this->userInfo("username",$new_patient_id).">";
            $subject = "Welcome to Telespine!";            
            // To send HTML mail, the Content-type header must be set
            $headers  = 'MIME-Version: 1.0' . "\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
            $headers .= "From: Telespine Support <".$support_email.">" . "\n";
            $returnpath = "-f".$support_email;
            // Mail it
            mail($to, $subject, $message, $headers, $returnpath);
            
        }
        
        function sendmailtosysadmin($new_patient_id, $payment_type){
                    $fullname=$this->userInfo("name_first",$new_patient_id).' '.$this->userInfo("name_last",$new_patient_id);
                    $support_email=$this->config['email_telespine']; 
                    $emailid=$this->userInfo("username",$new_patient_id);
                    
            $message = 'Name of User: '.$fullname.'<br>'.'Email address: '.$emailid.' <br>'.'Payment method: '.$payment_type;
            $to = $this->config['telespine_admin']; 
            $subject = "New Telespine Sign-up";            
            // To send HTML mail, the Content-type header must be set
            $headers  = 'MIME-Version: 1.0' . "\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
            $headers .= "From: Telespine Support <".$support_email.">" . "\n";
            $returnpath = "-f".$support_email;
            // Mail it
            mail($to, $subject, $message, $headers, $returnpath); 
            
        }

		
		
	/**
         * This function returns the subscription information based on clinic id associated with the Patients.
         * @param Integer $clinic_id
         * @return array $subscriptionInfo
         * @access public
         */ 
         
         function show_refund_policy1(){
                  $this->output = $this->build_template($this->get_template("show_refund_policy"),$replace);
         }    


        function checkEmail($email,$clinic_id){
                
                if($email!= '') {

                        $sqlEmailCheck = "SELECT * FROM user WHERE username = '{$email}' AND status in(1,2)";
                        $resultCheck = $this->execute_query($sqlEmailCheck);
                        $numrows = $this->num_rows($resultCheck);
                        if($numrows > 0){
                        	$res=$this->fetch_array($resultCheck);
                        	if($res['usertype_id']==1){
                        		$clinic_new_id=$this->get_clinic_info($res['user_id']);
                        		if($clinic_new_id==$clinic_id){
                        			//
                        			$sub_status=$this->check_subscription($res['user_id']);
                        			if($sub_status==0){
                        				//only take payment
                        				return '1';
                        			}else{
                        				return '2';//subscription exist
                        			}
                        			
                        		}else{
                        			return '3';//exist other clinic
                        		}
                        		
                        	}else{
                        		return '4';//exist not a patient
                        	}
                           }else{
                        	return '5';// email not exist create health record and mank payment
                        }
                         
                }else{

                        return 0;
                }

        } 		
  	
        function check_subscription($uid){
        	//Check whether patient is already subscribed with the service or not
               $sqlpatientSubscription="SELECT patient_subscription.user_subs_id user_subs_id 
                                     FROM patient_subscription 
                                     WHERE user_id={$uid} AND ((subs_status='1' AND subs_end_datetime > now())
                                     OR (subs_status='2' AND subs_end_datetime > now()))";
               $result = $this->execute_query($sqlpatientSubscription);
               $numRows = $this->num_rows($result);
               if($numRows > 0) {
                    return 1;
                }else{
                    return 0;
                }

              //End here 
        }
        
        
        
        
        /**
        * This is used to update the databse when paypal request is received
        * @param Array $responseArray
        * @param Integer $user_id
        * @param varchar $profile_id
        * @param Varchar $data_type
        * @return void
        * @access public
        */
        
        public function paypalHistory($responseArray,$user_id,$profile_id,$data_type){
            // Code To add New data column txn_type
            $txn_type=$responseArray['txn_type'];
            $serializeData=$this->encrypt_data(serialize($responseArray));
                        //Change in txn_type
                        $queryInsertHistory=$this->execute_query("insert into paypal_history set paypal_profile_number='{$profile_id}', user_id='{$user_id}',return_data='{$serializeData}',data_type='{$data_type}' , status_time= now(),txn_type='{$txn_type}' ");
                        //$queryInsertHistory=$this->execute_query("insert into paypal_history set paypal_profile_number='{$profile_id}', user_id='{$user_id}',return_data='{$serializeData}',data_type='{$data_type}' , status_time= now() ");
                        
                
                
        
        } 
        /**
         * This function saves the Patient information and update the database as subscription purchased.
         * Also the end date is set to one month more than subscription start date.
         * @param 
         * @return array $subscriptionInfo
         * @access public
         */  
                
        function saveDataSubscription($profileNumber,$startDate,$new_patient_id,$clinic_id){
            // Gather information from REQUEST
            $userInfor=$this->userInfo($new_patient_id);
            $clinicID=$clinic_id;
            $firstName = $this->value('firstname');
            $lastName = $this->value('lastname');
            $insertName=$firstName.' '.$lastName;
            $HealthProgramID = $this->value('HealthProgramID');
            $HealthServiceName = $this->value('HealthDescription');
            //$email=$userInfor['username'];
             $email=$this->userInfo('username',$new_patient_id);
            $paymentAmount = $this->value('cardPayment'); 
            
            $Frequency=urlencode($this->config["paypalprodetails"]["billingPeriod"]);
            $billingFreq=urlencode($this->config["paypalprodetails"]["billingFreq"]);
            $address1=$this->value('address1');
            $address2=$this->value('address2');
            $city=$this->value('city');
            $state=$this->value('state');
            $zipcode=$this->value('zipcode');
            $country=$this->value('country');
            
            //Query Health Program Details
            $queryHealthProgram=$this->execute_query("select * from `clinic_subscription` where subs_id = '{$HealthProgramID}' ");
            $resultHealthProgram=$this->fetch_object($queryHealthProgram);
            $subscriptionName=$resultHealthProgram->subs_title;
            $subscriptionDescription=$resultHealthProgram->subs_description;
            //Features Array
            $keyFeatures=array();
            if($resultHealthProgram->subs_feature1 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature1;
            }
            
            if($resultHealthProgram->subs_feature2 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature2;
            }            
            
            if($resultHealthProgram->subs_feature3 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature3;
            }            
            
            if($resultHealthProgram->subs_feature4 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature4;
            }            
            
            if($resultHealthProgram->subs_feature5 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature5;
            }            
            
            if($resultHealthProgram->subs_feature6 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature6;
            }            
            
            if($resultHealthProgram->subs_feature7 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature7;
            }            
            
            $subscriptionFeatures=serialize($keyFeatures);
            
            $querySubscriptEntry="insert into patient_subscription set `subscription_subs_id`='{$HealthProgramID}',`clinic_id`='{$clinicID}',`user_id`='{$new_patient_id}',`subs_id`='{$HealthProgramID}',`subs_datetime`=now(),`subs_price`='{$paymentAmount}',`subs_status`='1',`subs_end_datetime`=DATE_ADD(now(), INTERVAL {$billingFreq} {$Frequency}),`address1`='{$address1}',`address2`='{$address2}',`city`='{$city}',`state`='{$state}',`zipcode`='{$zipcode}',`country`='{$country}',`payment_paypal_profile`= '{$profileNumber}',`b_first_name`='{$firstName}',`b_last_name`='{$lastName}',subscription_title='{$subscriptionName}',subscription_desc='{$subscriptionDescription}',subscription_feature='{$subscriptionFeatures}'";
            
            $result=$this->execute_query($querySubscriptEntry);
            $subs_insert_id=$this->insert_id();

                $this->execute_query("UPDATE user SET ehs = '1'  WHERE user_id ={$new_patient_id}");
                

           
           // Send email to the Account Admin 
           $this->sendTxmessageAccountAdmin($clinicID,$new_patient_id,$HealthProgramID,'0');

           // Send email to the Patient
           $this->sendEmailSignUpPatients($clinicID,$new_patient_id,$HealthProgramID,'0');
        
        }
	/**
         * This function saves the Patient information and update the database as subscription purchased.
         * Also the end date is set to one month more than subscription start date.
         * @param 
         * @return array $subscriptionInfo
         * @access public
         */  
                
        function saveDataSubscriptionOneTime($profileNumber,$startDate,$new_patient_id,$clinic_id){
            // Gather information from REQUEST
            $userInfor=$this->userInfo($new_patient_id);
            $clinicID=$clinic_id;
            $firstName = $this->value('firstname');
            $lastName = $this->value('lastname');
            $insertName=$firstName.' '.$lastName;
            $HealthProgramID = $this->value('HealthProgramID');
            $HealthServiceName = $this->value('HealthDescription');
            
             $email= $this->userInfo('username',$new_patient_id);
            $paymentAmount = $this->value('cardPayment'); 
            
            $Frequency=urlencode($this->config["paypalprodetails"]["billingPeriod"]);
            $billingFreq=$this->value('ehsTimePeriod');
            $address1=$this->value('address1');
            $address2=$this->value('address2');
            $city=$this->value('city');
            $state=$this->value('state');
            $zipcode=$this->value('zipcode');
            $country=$this->value('country');
            
            
            //Query Health Program Details
            $queryHealthProgram=$this->execute_query("select * from `clinic_subscription` where subs_id = '{$HealthProgramID}' ");
            $resultHealthProgram=$this->fetch_object($queryHealthProgram);
            $subscriptionName=$resultHealthProgram->subs_title;
            $subscriptionDescription=$resultHealthProgram->subs_description;
            $paymentType=$resultHealthProgram->paymentType;
            //Features Array
            $keyFeatures=array();
            if($resultHealthProgram->subs_feature1 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature1;
            }
            
            if($resultHealthProgram->subs_feature2 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature2;
            }            
            
            if($resultHealthProgram->subs_feature3 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature3;
            }            
            
            if($resultHealthProgram->subs_feature4 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature4;
            }            
            
            if($resultHealthProgram->subs_feature5 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature5;
            }            
            
            if($resultHealthProgram->subs_feature6 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature6;
            }            
            
            if($resultHealthProgram->subs_feature7 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature7;
            }            
            
            $subscriptionFeatures=serialize($keyFeatures);
            $onetime_price=$resultHealthProgram->onetime_price;
            
            $querySubscriptEntry="insert into patient_subscription set `subscription_subs_id`='{$HealthProgramID}',`clinic_id`='{$clinicID}',`user_id`='{$new_patient_id}',`subs_id`='{$HealthProgramID}',`subs_datetime`=now(),`subs_status`='1',`subs_end_datetime`=DATE_ADD(now(), INTERVAL {$billingFreq} {$Frequency}),`address1`='{$address1}',`address2`='{$address2}',`city`='{$city}',`state`='{$state}',`zipcode`='{$zipcode}',`country`='{$country}',`payment_paypal_profile`= '{$profileNumber}',`b_first_name`='{$firstName}',`b_last_name`='{$lastName}', `subscription_title`='{$subscriptionName}',`subscription_desc`='{$subscriptionDescription}',`subscription_feature`='{$subscriptionFeatures}',`ehsTimePeriod`='{$billingFreq}', `paymentType`='{$paymentType}',`onetime_price`='{$onetime_price}'";
            
            $result=$this->execute_query($querySubscriptEntry);
            $subs_insert_id=$this->insert_id();
            $data=array('patient_subscription_user_subs_id'=> $subs_insert_id,
                'user_id'=>$new_patient_id,
                'user_subs_id'=>$subs_insert_id,
                'payment_paypal_profile'=>$profileNumber,
                'paymnet_datetime'=>date('Y-m-d:e:h:s'),
                'payment_price'=>$paymentAmount,
                'payment_b_name'=>$firstName.' '.$lastName,
                'paymnet_b_email'=>$email,
               'paymnet_status'=>1,
                'payment_txn_type'=>'onetime'
                );
                $this->insert('patient_payment',$data);

                $this->execute_query("UPDATE user SET ehs = '1'  WHERE user_id = {$new_patient_id}");
           
           // Send email to the Account Admin 
           $this->sendTxmessageAccountAdmin($clinic_id,$new_patient_id,$HealthProgramID,1);

           // Send email to the Patient
           $this->sendEmailSignUpPatients($clinic_id,$new_patient_id,$HealthProgramID,1);
        
        }
 /**
         * This function sends Extenal message:(Patient Name) has signed up for (E-Health Service Name)  TO Account Admins
         * @param Integer $clinic_id
         * @param Integer $user_id
         * @param String  $serviceName
         * @return void
         * @access public
         */
         
         public function sendTxmessageAccountAdmin($clinic_id,$new_patient_id,$HealthProgramID,$payment_type){
                    // Query To get the account admins from patient clinic
                    $userInfor=$this->userInfo($new_patient_id);
                    $queryHealthProgram=$this->execute_query("select * from `clinic_subscription` where subs_id = '{$HealthProgramID}' ");
                    $resultHealthProgram=$this->fetch_object($queryHealthProgram);
                    $clinicName=html_entity_decode($this->get_clinic_info($new_patient_id,"clinic_name"), ENT_QUOTES, "UTF-8");

                    $queryAccountAdmin="select user.* from clinic_user,user where clinic_id = '{$clinic_id}' AND clinic_user.user_id=user.user_id AND user.admin_access=1 AND usertype_id= 2";
                    
                    $resultAccountAdmin=$this->execute_query($queryAccountAdmin);
                    //Send Email To Account Admins 
                    while($ObjresultAccountAdmin=$this->fetch_object($resultAccountAdmin)){

                    $patName=$this->userInfo('name_first',$new_patient_id).' '.$this->userInfo('name_last',$new_patient_id);
                          // Send External Email Message
                          $SubjectLine=html_entity_decode($patName, ENT_QUOTES, "UTF-8")." has signed up for ".html_entity_decode($resultHealthProgram->subs_title, ENT_QUOTES, "UTF-8");
                          $to = $ObjresultAccountAdmin->username;
                          // To send HTML mail, the Content-type header must be set
                          $headers  = 'MIME-Version: 1.0' . "\n";
                          $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                          //$headers .= "From: <support@txxchange.com>" . "\n";
                          //$returnpath = '-fsupport@txxchange.com';
                    
						$clinic_channel=$this->getchannel($this->get_clinic_info($new_patient_id,"clinic_id"));
						if( $clinic_channel == 1){
						    $headers .= "From: <".$this->config['email_tx'].">" . "\n";
						    $returnpath = "-f".$this->config['email_tx'];
						    }else{
						    $headers .= "From: <".$this->config['email_wx'].">" . "\n";
						    $returnpath = '-f'.$this->config['email_wx'];   
						    }
                          //echo $message;exit;
                          // Mail it
                          @mail($to, $SubjectLine, '', $headers, $returnpath);
                    }
                    
                    // Send Email To Patients 

                    $replacePatient['images_url']=$this->config['images_url'];
                    $Patientsubject = html_entity_decode($resultHealthProgram->subs_title, ENT_QUOTES, "UTF-8")."Sign-up Confirmation";
                    $replacePatient['ServiceName']=html_entity_decode($resultHealthProgram->subs_title, ENT_QUOTES, "UTF-8");
                    $replacePatient['ClinicName']=$clinicName;
                    $replacePatient['price']='\$'.$resultHealthProgram->subs_price;
                    $replacePatient['ServiceDescription']=html_entity_decode($resultHealthProgram->subs_description);
                    //Features Array
                    $keyFeatures=array();
                    if($resultHealthProgram->subs_feature1 != ''){
                        $keyFeatures[]=$resultHealthProgram->subs_feature1;
                    }
                    
                    if($resultHealthProgram->subs_feature2 != ''){
                        $keyFeatures[]=$resultHealthProgram->subs_feature2;
                    }            
                    
                    if($resultHealthProgram->subs_feature3 != ''){
                        $keyFeatures[]=$resultHealthProgram->subs_feature3;
                    }            
                    
                    if($resultHealthProgram->subs_feature4 != ''){
                        $keyFeatures[]=$resultHealthProgram->subs_feature4;
                    }            
                    
                    if($resultHealthProgram->subs_feature5 != ''){
                        $keyFeatures[]=$resultHealthProgram->subs_feature5;
                    }            
                    
                    if($resultHealthProgram->subs_feature6 != ''){
                        $keyFeatures[]=$resultHealthProgram->subs_feature6;
                    }            
                    
                    if($resultHealthProgram->subs_feature7 != ''){
                        $keyFeatures[]=$resultHealthProgram->subs_feature7;
                    }                    
                    if(!empty($keyFeatures))
                    $replacePatient['KeyFeatures']=@implode('<br>',$keyFeatures);

                    /*
                    // Uncommnet when copy is recieved
                    // Content Of the email send to  patient
                    $Patientmessage = $this->build_template($this->get_template("PatientUserMailContent"),$replacePatient);
                    
                    $Patientto = $userInfor['username'];
                    // To send HTML mail, the Content-type header must be set

                    $Patientheaders  = 'MIME-Version: 1.0' . "\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                    $Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8")."<support@txxchange.com>" . "\n";
                    $Patientreturnpath = '-fsupport@txxchange.com';

                    // Mail it
                    @mail($Patientto, $Patientsubject, '', $Patientheaders, $Patientreturnpath);                    
                    */                    
                    
                    
                    
                    
        }

        /**
         * This function sends Extenal mail when signup:(E-Health Service Name) Sign-up Confirmation To Patients
         * @param Integer $clinic_id
         * @param Integer $user_id
         * @param Object  $resultHealthProgram
         * @return void
         * @access public
         */
        
      public function sendEmailSignUpPatients($clinic_id,$new_patient_id,$HealthProgramID,$payment_type){
        //$userInfor=$this->userInfo($new_patient_id);
                    $queryHealthProgram=$this->execute_query("select * from `clinic_subscription` where subs_id = '{$HealthProgramID}' ");
                    $resultHealthProgram=$this->fetch_object($queryHealthProgram);
                    //$clinicName=html_entity_decode($this->get_clinic_info($clinic_id), ENT_QUOTES, "UTF-8");
                    $clinicName=html_entity_decode($this->get_clinic_info($new_patient_id,'clinic_name'), ENT_QUOTES, "UTF-8");
                    // Send Email To Patients 
                    $replacePatient['images_url']=$this->config['images_url'];
                    $Patientsubject = html_entity_decode($resultHealthProgram->subs_title, ENT_QUOTES, "UTF-8")." Sign-up Confirmation";
                    $replacePatient['ServiceName']=html_entity_decode($resultHealthProgram->subs_title, ENT_QUOTES, "UTF-8");
                    $replacePatient['ClinicName']=$clinicName;
                    $clinic_channel=$this->getchannel($this->get_clinic_info($new_patient_id,'clinic_id'));
                    if($resultHealthProgram->paymentType==0)
                        $replacePatient['price']='\$'.$resultHealthProgram->subs_price;
                    else
                        $replacePatient['price']='\$'.$resultHealthProgram->onetime_price;
                    
                    $replacePatient['ServiceDescription']=html_entity_decode($resultHealthProgram->subs_description);
                    //Features Array
                    $keyFeatures=array();
                    if($resultHealthProgram->subs_feature1 != ''){
                        $keyFeatures[]=$resultHealthProgram->subs_feature1;
                    }
                    
                    if($resultHealthProgram->subs_feature2 != ''){
                        $keyFeatures[]=$resultHealthProgram->subs_feature2;
                    }            
                    
                    if($resultHealthProgram->subs_feature3 != ''){
                        $keyFeatures[]=$resultHealthProgram->subs_feature3;
                    }            
                    
                    if($resultHealthProgram->subs_feature4 != ''){
                        $keyFeatures[]=$resultHealthProgram->subs_feature4;
                    }            
                    
                    if($resultHealthProgram->subs_feature5 != ''){
                        $keyFeatures[]=$resultHealthProgram->subs_feature5;
                    }            
                    
                    if($resultHealthProgram->subs_feature6 != ''){
                        $keyFeatures[]=$resultHealthProgram->subs_feature6;
                    }            
                    
                    if($resultHealthProgram->subs_feature7 != ''){
                        $keyFeatures[]=$resultHealthProgram->subs_feature7;
                    }                    
                    if(!empty($keyFeatures))
                    $replacePatient['KeyFeatures']=@implode('<br>',$keyFeatures);

                    $check_sql_mail_template=" select * from clinic_ehs_mail_template where clinic_id = '{$clinic_id}' ";
                    $result = $this->execute_query($check_sql_mail_template);
		if($this->num_rows($result) > 0){
			$row=$this->fetch_object($result);
			$Patientmessage=$row->mailcontent;
			 
			
			$Patientmessage=str_replace('{Clinic Logo}',$this->cliniclogo($clinic_id) , $Patientmessage);
			$Patientmessage=str_replace('{First Name}', $this->userInfo('name_first',$new_patient_id), $Patientmessage);
			$Patientmessage=str_replace('{EHS Name}', $replacePatient['ServiceName'], $Patientmessage);
			$Patientsubject = html_entity_decode($row->subject, ENT_QUOTES, "UTF-8");
		}
		else
		{
			// Content Of the email send to  patient
			if($resultHealthProgram->ehsTimePeriod==1)
				$month="month";
			else
				$month="months";
			$replacePatient['monthly']=$resultHealthProgram->ehsTimePeriod.' '.$month;

			if($clinic_channel==1){
				if($payment_type==0)
					$Patientmessage = $this->build_template($this->get_template("PatientUserMailContent"),$replacePatient);
				else
					$Patientmessage = $this->build_template($this->get_template("PatientUserMailContentOnetime"),$replacePatient);
			}
			else{
				if($payment_type==0)
					$Patientmessage = $this->build_template($this->get_template("PatientUserMailContentwx"),$replacePatient);
				else
					$Patientmessage = $this->build_template($this->get_template("PatientUserMailContentwxOnetime"),$replacePatient);
			}


			// To send HTML mail, the Content-type header must be set
		}
		$Patientto = $this->userInfo('username',$new_patient_id);
		$Patientheaders  = 'MIME-Version: 1.0' . "\n";
		$Patientheaders .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
		//$Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8")." <support@txxchange.com>" . "\n";
		//$Patientreturnpath = '-fsupport@txxchange.com';
		 
			
	if( $clinic_channel == 1){
			$Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8"). " <do-not-reply@txxchange.com>" . "\n";
			$Patientreturnpath = "-fdo-not-reply@txxchange.com";
		}else{
			$Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8"). " <do-not-reply@txxchange.com>" . "\n";
			$Patientreturnpath = '-fdo-not-reply@txxchange.com';
		}

		// Mail it
		//$Patientto = "shailesh.kumar@hytechpro.com";
		@mail($Patientto, $Patientsubject, $Patientmessage, $Patientheaders, $Patientreturnpath);
	}

        function loginsubscription() {

                        $ehsname = $_REQUEST['ehsname'];
                       // $replace['alreadysub']="You are already subscribed with {$ehsname}";
                        $replace['alreadysub']="You are already subscribed with clinic's E-Health service. Please login with your credentials.";
                        $replace['body'] = $this->build_template($this->get_template("alreadySubscribed"),$replace);
                        $this->output = $this->build_template($this->get_template("main"),$replace);

                }

        function patientRegistration() {
                       $ehsname = $_REQUEST['ehsname'];                
                       $replace['alreadysub']="You have successfully signed-up for {$ehsname}. If you already have login information, please use it on the next page. Otherwise, please check your email for temporary login information and how to get started. Thanks.";
                       $replace['body'] = $this->build_template($this->get_template("patientRegistration"),$replace);
                       $this->output = $this->build_template($this->get_template("main"),$replace);

                }
                
        function getstate(){
       	$counrty=$this->value('country_code');
       	$stateArray=$this->state_list($counrty);
       	foreach($stateArray as $key=>$value){
                    $stateJavascript.="{$key}:{$value},";
            }
           echo $stateJavascript=trim($stateJavascript,',');
           
       }         
		
        function patient_signup(){
            $replace = array();
            $replace['meta_head'] = $this->build_template($this->get_template("meta_head"));
            $replace['header'] =    $this->build_template($this->get_template("patient_dashboard_header"));
            $replace['footer'] =    $this->build_template($this->get_template("patient_dashboard_footer"));
            $replace['clinicid']= $this->config['telespineid'];
            $replace['theme']="default";
            $replace['errorclass']="";
            $replace['chk']='';
            $replace['chkevent']='';
            $replace['toichked']='';
            $clinic_id=$this->config['telespineid'];
            date_default_timezone_set('America/Denver');
            $date = date('m/d/Y h:i:s a', time());
            $replace['date']=$date;

            /********************************/
            {
                        $privateKey = $this->config['private_key'];
                        $errorCode=$this->value('errorCode');
                        if($errorCode!='') {
                            $customerrormessage=$this->PaypalErroCodeslist($errorCode);
                            if($customerrormessage=='')                    
                                {
                                    $customerrormessage="Invalid Credit Card Details";                        
                                }
                            $replace['errorMessage']=$customerrormessage; 
                            $replace['chk']='checked="checked"';
                             $replace['chkevent']="<script>$(document).ready(function () { 
                if($(\"input:radio[name='usePromoCode']\").is(':checked')){
                                               
                                                $('#billingCCDiv').slideDown(800);
                                                $('#billingPromoDiv').slideUp(800);
					} else {
                                                $('#billingCCDiv').slideUp(800);
                                                $('#billingPromoDiv').slideDown(800);
						} 
                                    }
                   );</script>";
                            $replace['toichked']='checked="checked"';
                        }
                          if($this->value('namefValid')!=''){
                             $replace['style']='style="display:block;"';
                             $replace['atag']='<i class="fa fa-info-circle alert-icon"></i>'; 
                             $replace['errorclass']='fieldError';      
                             $replace['errorMessage']="Please Enter the valid First name.";
                         } 
                        if($this->value('alreadysub')!=''){
                                $replace['errorMessage']=$this->value('alreadysub');
                                  $replace['toichked']='checked="checked"';
                          }
                          if($replace['errorMessage']!=''){
                             $replace['style']='style="display:block;"';
                             $replace['atag']='<i class="fa fa-info-circle alert-icon"></i>'; 
                               $replace['toichked']='checked="checked"';
                         }
                       
                         if($this->value('promocodeValid')!=''){
                             $replace['style']='style="display:block;"';
                             $replace['atag']='<i class="fa fa-info-circle alert-icon"></i>'; 
                             $replace['errorclass']='fieldError';
                             if($this->value('promocodeValid')=="notvalid"){
                                 $replace['errorMessage']="Please Enter the valid Sign-Up code";
                             }else{
                                 $replace['errorMessage']="We're sorry. It appears the sign-up code you're using has been used previously. Please try entering the code again exactly as it appears. Thank you.";
                             }
                               $replace['toichked']='checked="checked"';
                         }
                         if($this->value('discountcodeValid')!=''){
                             $replace['style']='style="display:block;"';
                             $replace['atag']='<i class="fa fa-info-circle alert-icon"></i>'; 
                             $replace['errorclass']='fieldError';
                             if($this->value('discountcodeValid')=="notvalid"){
                                 $replace['errorMessage']="We're sorry. It appears the Discount Coupon code you're using is not valid. Please try entering the code again exactly as it appears. Thank you.";
                             }
                             $replace['chk']='checked="checked"';
                             $replace['chkevent']="<script>$(document).ready(function () { 
                if($('#usePromoCodeFalse').is(':checked')){
                                                $('#billingCCDiv').slideDown(800);
                                                $('#billingPromoDiv').slideUp(800);
					} else {
                                                $('#billingCCDiv').slideUp(800);
                                                $('#billingPromoDiv').slideDown(800);
						} 
                                    }
                   );</script>";
                               $replace['toichked']='checked="checked"';
                         }
                         
                         if($this->value('namefValid')!=''){
                             $replace['style']='style="display:block;"';
                             $replace['atag']='<i class="fa fa-info-circle alert-icon"></i>'; 
                             $replace['errorclass']='fieldError';      
                             $replace['errorMessage']="Please Enter the valid First name.";
                         }
                          
                          if($this->value('namelValid')!=''){
                             $replace['style']='style="display:block;"';
                             $replace['atag']='<i class="fa fa-info-circle alert-icon"></i>'; 
                             $replace['errorclass']='fieldError';      
                             $replace['errorMessage']="Please Enter the valid Last name.";
                         }
                         if($this->value('namelValid')!=''){
                             $replace['style']='style="display:block;"';
                             $replace['atag']='<i class="fa fa-info-circle alert-icon"></i>'; 
                             $replace['errorclass']='fieldError';      
                             $replace['errorMessage']="Please Enter the valid Last name.";
                         }
                         if($this->value('emailValid')!=''){
                             $replace['style']='style="display:block;"';
                             $replace['atag']='<i class="fa fa-info-circle alert-icon"></i>'; 
                             $replace['errorclass']='fieldError';      
                             $replace['errorMessage']="Please Enter the valid Email address.";
                         }
                         if($this->value('cnumberValid')!=''){
                             $replace['style']='style="display:block;"';
                             $replace['atag']='<i class="fa fa-info-circle alert-icon"></i>'; 
                             $replace['errorclass']='fieldError';      
                             $replace['errorMessage']="Please Enter the valid Card number.";
                         }
                         
                         if($this->value('cexpValid')!=''){
                             $replace['style']='style="display:block;"';
                             $replace['atag']='<i class="fa fa-info-circle alert-icon"></i>'; 
                             $replace['errorclass']='fieldError';      
                             $replace['errorMessage']="Please Enter the valid card Expiry date.";
                         }
                         
                         if($this->value('ccvvValid')!=''){
                             $replace['style']='style="display:block;"';
                             $replace['atag']='<i class="fa fa-info-circle alert-icon"></i>'; 
                             $replace['errorclass']='fieldError';      
                             $replace['errorMessage']="Please Enter the valid CVV number.";
                         }
                        
                         if($this->value('address1Valid')!=''){
                             $replace['style']='style="display:block;"';
                             $replace['atag']='<i class="fa fa-info-circle alert-icon"></i>'; 
                             $replace['errorclass']='fieldError';      
                             $replace['errorMessage']="Please Enter the valid Address.";
                         }
                         if($this->value('cityValid')!=''){
                             $replace['style']='style="display:block;"';
                             $replace['atag']='<i class="fa fa-info-circle alert-icon"></i>'; 
                             $replace['errorclass']='fieldError';      
                             $replace['errorMessage']="Please Enter the valid City name.";
                         }
                         
                         if($this->value('stateValid')!=''){
                             $replace['style']='style="display:block;"';
                             $replace['atag']='<i class="fa fa-info-circle alert-icon"></i>'; 
                             $replace['errorclass']='fieldError';      
                             $replace['errorMessage']="Please Enter the valid State name.";
                         }

                         if($this->value('zipcodeValid')!=''){
                             $replace['style']='style="display:block;"';
                             $replace['atag']='<i class="fa fa-info-circle alert-icon"></i>'; 
                             $replace['errorclass']='fieldError';      
                             $replace['errorMessage']="Please Enter the valid Zip code.";
                         }
                         if($this->value('passwordValid')!=''){
                             $replace['style']='style="display:block;"';
                             $replace['atag']='<i class="fa fa-info-circle alert-icon"></i>'; 
                             $replace['errorclass']='fieldError';      
                             $replace['errorMessage']=$this->value('passwordValid');
                         }

                        //End here

     $replace['namefValid']='<font style="color:#FF0000;font-weight:normal;">'.urldecode($this->value('namefValid')).'</font>';
            if($this->value('validData')=='true')   {
                    if(urldecode($this->value('namefValid'))!=''){      
                        $replace['namefValid']='<font style="color:#FF0000;font-weight:normal;">'.urldecode($this->value('namefValid')).'</font>';                                         
                     $replace['retainedFname']='';
                    }     
                      $replace['retainedFname']=$_SESSION['pateintSubs']['fname'] ;                                                                            
                    if(urldecode($this->value('namelValid'))!='')       {
                        $replace['namelValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('namelValid').'</font>';                                                  
                     $replace['retainedlname']='' ; 
                    }
  $replace['retainedlname']=$_SESSION['pateintSubs']['lname'] ;                     
                     if(urldecode($_REQUEST['emailValid'])!='')       {
                        $replace['emailValid']='<font style="color:#FF0000;font-weight:normal;">'.$_REQUEST['email'].'</font>';                                                  
                    
                     $replace['retainedEmail']='' ; 
                    }
                        $replace['retainedEmail']=$_SESSION['pateintSubs']['emailAddress'] ;  
                                                                    
                    if(urldecode($this->value('cnumberValid'))!='')   {     
                        $replace['cnumberValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cnumberValid').'</font>';
                        $replace['retainedcardNumber'] = '';
                     } 
                                                                                      
                    $replace['retainedcardNumber'] = $_SESSION['pateintSubs']['cardNumber']; 
                                                                                                          
                    if(urldecode($this->value('cexpValid'))!='')  {           
                        $replace['cexpValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cexpValid').'</font>';
                        //$replace['retainedcardNumber'] = '';
                      }                                                          
                    if(urldecode($this->value('ccvvValid'))!='') {            
                        $replace['ccvvValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('ccvvValid').'</font>'; 
                        $replace['retainedcvvNumber'] = '';
                    }                                      
         $replace['retainedcvvNumber'] = $_SESSION['pateintSubs']['cvvNumber'];                                        
                    if(urldecode($this->value('ctypeValid'))!='') {                
                        $replace['ctypeValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('ctypeValid').'</font>'; 
                        $replace['retainVisaType'] = '';
                        $replace['retainMasterType'] = '';
                     }
                        if($_SESSION['pateintSubs']['cardType'] == 'Visa') {
                                $replace['retainVisaType'] = 'selected="selected"';
                                $replace['retainMasterType'] = '';
                        }
                        elseif($_SESSION['pateintSubs']['cardType'] == 'MasterCard') {
                                $replace['retainMasterType'] = 'selected="selected"';
                                $replace['retainVisaType'] = '';
                        }
                        else {
                                $replace['retainVisaType'] = '';
                                $replace['retainMasterType'] = '';
                        }
                                                                    
                    if(urldecode($this->value('address1Valid'))!='')   {           
                       $replace['address1Valid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('address1Valid').'</font>';                                              
                       $replace['retainedadd1']='' ;  
                    }     
                       
                       
                    
                        $replace['retainedadd1']=$_SESSION['pateintSubs']['add1'] ;
                     
                       
                    if(urldecode($this->value('cityValid'))!='')    {               
                        $replace['cityValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cityValid').'</font>';                                                         
                        $replace['retainedcity']='';  
                    }
                        
                        $replace['retainedcity']=$_SESSION['pateintSubs']['city'] ;
                     
                                                            
                    if(urldecode($this->value('stateValid'))!='')  {                  
                        $replace['stateValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('stateValid').'</font>';                                             
                    
                        $replace['retainedstate']=''; 
                    
                    }         
                        
                        $replace['retainedstate']=$_SESSION['pateintSubs']['state'] ;
                     
                            
                    if(urldecode($this->value('zipcodeValid'))!='')    {               
                        $replace['zipcodeValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('zipcodeValid').'</font>';                                               
                    
                        $replace['retainedzip']='' ;
                    }
                        
                    
                        $replace['retainedzip']=$_SESSION['pateintSubs']['zip'] ;

                        $replace['promocode']=$_SESSION['pateintSubs']['promocode'];
                        $replace['retaineddiscount_coupon']=$_SESSION['pateintSubs']['discount_coupon'];

                    if(urldecode($this->value('address2'))=='')
                        $replace['retainedadd2']=$_SESSION['pateintSubs']['add2'] ;
                    } else {
                    $replace['namefValid']='';
                    $replace['retainedFname']='';      
                    $replace['namelValid']=''; 
                    $replace['emailValid']=''; 
                    $replace['retainedlname']='' ;     
                    $replace['cnumberValid']='';   
                    $replace['cexpValid']='';   
                    $replace['ccvvValid']='';   
                    $replace['ctypeValid']='';
                    $replace['address1Valid']=''; 
                    $replace['retainedadd1']='' ;   
                    $replace['retainedadd2']='' ;               
                    $replace['cityValid']='';
                    $replace['retainedcity']='' ;         
                    $replace['stateValid']='';
                    $replace['retainedstate']='' ;         
                    $replace['zipcodeValid']='';
                    $replace['retainedzip']='' ; 
                    $replace['RetaincountryUS']='' ;                 
                    $replace['RetaincountryCAN']='' ; 
                    $replace['retainedEmail']='';  
                    $replace['retainedcardNumber']='';             
                    $replace['retainVisaType']=''; 
                    $replace['retainMasterType']=''; 
                    $replace['retainedcvvNumber']='';
                    $replace['promocode']='';
                    $replace['retaineddiscount_coupon']='';
                         
             }
          // $this->printR($_SESSION);
             //echo "test".$_SESSION['pateintSubs']['clinicProvider'];
            $dataSubs=$this->subscriptionInfo($clinic_id);
       
            $ehsSubscriptionStatus = $dataSubs['subs_status'];
            
           $clinicProviders=$this->clinicProviderInfo($clinic_id);
           $clinicProvidersArray = $clinicProviders;//print_r($clinicProvidersArray);exit;
           $replace['clinicProvidersOptions'] = $this->build_select_option($clinicProvidersArray, $_SESSION['pateintSubs']['clinicProvider']);
            $replace['HealthProgramName']=$dataSubs["subs_title"];
            $replace['HealthProgramDescription']=html_entity_decode(str_replace('$','$ ',$dataSubs["subs_description"]));
            $replace['ehsTimePeriod']=$dataSubs["ehsTimePeriod"]; 
            $replace['paymentType']=$dataSubs["paymentType"];
            $replace['HealthProgramID']=$dataSubs['subs_id'];
            if($dataSubs['paymentType'] == '0') {
                $replace['HealthProgramDuration'] = "a month";
                $replace['cardPayment']=$dataSubs['subs_price'];
                $replace['HealthProgramData'] = "This is an online health service provided by ". $dataSubs['clinic_name'].". There are no contracts and you can cancel at any time.";
            } else {

                if($dataSubs['ehsTimePeriod'] > 1)
                        $replace['HealthProgramDuration'] = " for ".$dataSubs['ehsTimePeriod']. " months";
                else 
                        $replace['HealthProgramDuration'] = " for ".$dataSubs['ehsTimePeriod']. " month";
                $replace['HealthProgramPrice']=$dataSubs['onetime_price'];
                 $replace['cardPayment']=$dataSubs['onetime_price'];
                $replace['HealthProgramData'] = "This is an online health service provided by ".$dataSubs['clinic_name'].". At the end of the term listed above, your credit card will <u>not</u> be charged again.";
            }
            $replace['HealthProgramClinicName']=$dataSubs['clinic_name'];
            $replace['ehsTimePeriod']=$dataSubs['ehsTimePeriod'];
            
            $monthArray = array();
            $monthArray = $this->config['monthsArray'];
            $replace['monthsOptions'] = $this->build_select_option($monthArray, $_SESSION['pateintSubs']['exprMonth']); 
           
            $yearArray = array();
            $yearArray = $this->config['yearArray'];
            $replace['yearOptions'] = $this->build_select_option($yearArray, $_SESSION['pateintSubs']['exprYear']);
            $countryarray=$this->country_list(); 
			$replace['country']=$this->build_select_option($countryarray, $_SESSION['pateintSubs']['country']);
          		if($_SESSION['pateintSubs']['country']=='')
			$country_code='US';
			else
			$country_code=$_SESSION['pateintSubs']['country'];
                            $stateArray=$this->state_list('US');
                        //$this->printR($stateArray);
                        //$stateArray=$this->config['state'];
			$replace['stateOptions']=$this->build_select_option($stateArray, $_SESSION['pateintSubs']['state']);
            //Unsetting the Session variable stored for Remembering field values if any wrong/incomplete info submitted by                  user.
            //print_r($_SESSION);
            unset($_SESSION['pateintSubs']);
            if($clinic_id == '') {
                                $Ehsmessage = "HTTP Error 400 - Bad Request /Bad File Request. Please contact system administrator.";
                                $replace['Ehsmessage'] = $Ehsmessage;
                                $replace['body'] = $this->build_template($this->get_template("ehspatientRegistration1"),$replace);
             }else
            if($ehsSubscriptionStatus == '0' || $ehsSubscriptionStatus == '') {
            	
            	if($dataSubs["subs_title"]!='') {

                    $Ehsmessage = "We're sorry, but ".$dataSubs["subs_title"]." is not currently being offered at this time.";
            	} else {
            		
            		$Ehsmessage = "We're sorry, but clinic's E-Health service is not currently being offered at this time.";
            	} 
                $replace['Ehsmessage'] = $Ehsmessage;
                $replace['body'] = $this->build_template($this->get_template("ehspatientRegistration1"),$replace);
             }  else {
                        if($clinic_id == '') {
                                $Ehsmessage = "HTTP Error 400 - Bad Request /Bad File Request. Please contact system administrator.";
                                $replace['Ehsmessage'] = $Ehsmessage;
                                $replace['body'] = $this->build_template($this->get_template("ehspatientRegistration1"),$replace);
                        } else {
                                $replace['body'] = $this->build_template($this->get_template("ehspatientRegistration"),$replace);
                }

            }

                        $querymsg = $_REQUEST['alreadysub'];
                        $registered = $_REQUEST['registered'];
                        $subscribed = $_REQUEST['subscribed'];
                        if(isset($querymsg)) {
                            session_destroy();  
                        	$SubscriptionMessage = "<script>GB_showCenter('Sign-up Confirmation', '/index.php?action=loginsubscription&ehsname={$dataSubs["subs_title"]}',150,400);</script>";
                            $replace['SubscriptionMessage'] = $SubscriptionMessage;

                        }

                        if(isset($registered) || isset($subscribed)) {
                        	   session_destroy();
                              $RegistrationMessage = "<script>GB_showCenter('Sign-up Confirmation' , '/index.php?action=patientRegistration&subscribed={$subscribed}&ehsname={$dataSubs["subs_title"]}',170,550);</script>";
                              $replace['RegistrationMessage'] = $RegistrationMessage;

                        }
        
			
		}
            
            
            
            
            
            
            
            
            /*******************************/
            
            $this->output = $this->build_template($this->get_template("signup"),$replace);  
            
        }
        
        function submitpatientsignup(){
           // $this->printR($_REQUEST);die;
            /*Array
(
    [action] => submitpatientsignup
    [firstName] => sanjay
    [lastName] => gairola
    [email] => sanjay.gairola@hytechpro0118.com
    [password] => Sanjay123
    [confirmNewPassword] => Sanjay123
    [address1] => address1
    [address2] => address2
    [city] => city
    [state] => state
    [zip] => 123456
    [cardNumber] => 4141414141414141
    [expMonth] => 05
    [expYear] => 2018
    [cvv2] => 123
    [clinic_id] => 126
)*/

//Users timezone should be set by $_SESSION['time']

              
            $privateKey = $this->config['private_key'];
            $clinic_id = $this->value('clinic_id');
              
           //Configuration settings...
            $API_UserName       =       urlencode($this->config["paypalprodetails"]["API_UserName"]);
            $API_Password       =       urlencode($this->config["paypalprodetails"]["API_Password"]);
            $API_Signature      =       urlencode($this->config["paypalprodetails"]["API_Signature"]);
            $environment        =       urlencode($this->config["paypalprodetails"]["environment"]);
            $currencyID         =       urlencode($this->config["paypalprodetails"]["currencyID"]);
            $Frequency          =       urlencode($this->config["paypalprodetails"]["billingPeriod"]);
            $billingFreq        =       urlencode($this->config["paypalprodetails"]["billingFreq"]);

            $firstName          =       $this->value('firstName');
            $lastName           =       $this->value('lastName');
            $emailaddress       =       $_REQUEST['email'];
           // $clinicProvider     =       $this->value('clinicProvider');
            $creditCardType     =        'Visa';
            $creditCardNumber   =       $this->value("cardNumber");
            $expDateMonth       =       $this->value('expMonth');
            $expDateYear        =       $this->value('expYear');
            $cvv2Number         =       $this->value('cvv2');
            $address1           =       $this->value('address1');
            $address2           =       $this->value('address2');
            $city               =       $this->value('city');
            $state              =       $this->value('state');
            $zipcode            =       $this->value('zip');
            $country            =       'US';
            $ehsTimePeriod      =       $this->value('ehsTimePeriod');
            $paymentType        =       $this->value('paymentType');
            $onetime_price      =       $this->value('onetime_price');
            $password           =       $this->value('password');
            $confirmNewPassword           =       $this->value('confirmNewPassword');
            $promocode          =       $this->value('promocode');
            $usePromoCode       =       $this->value('usePromoCode');
            $discount_coupon    =       $this->value('discount_coupon');
            
            //Code To Validate User Entered Data
            $error=array();
            if(trim($firstName) =="" ){
                $error['namefValid']="Please enter firstname";   
            }

            if(trim($lastName)==""){
                $error['namelValid']="Please enter lastname";   
            }

            if(trim($emailaddress)==""){
                $error['emailValid']="Please enter email address";   
            } 
            if(trim($password)!=trim($confirmNewPassword)){
                $error['passwordValid']="Please enter same values in password and confirmpassword field.";   
            }

           if($this->value('usePromoCode')==1){
                    if(trim($creditCardNumber)=="" || strlen(trim($creditCardNumber))<13 || strlen(trim($creditCardNumber))>16){
                        $error['cnumberValid']="Please enter valid credit card number";
                    }

                    if(trim($expDateMonth)=="" || trim($expDateYear)=="" ){
                        $error['cexpValid']="Please select credit card expiration";
                    }

                    if(trim($cvv2Number)==""){
                        $error['ccvvValid']="Please enter CVV number";
                    }else if(!is_numeric(trim($cvv2Number))){
                        $error['ccvvValid']="Please enter valid CVV number";
                    }
                   if(trim($address1)==""){
                       $error['address1Valid']="Please enter address1";
                   }
                    if(trim($city)==""){
                        $error['cityValid']="Please enter city";
                    }
                    if(trim($state)==""){
                        $error['stateValid']="Please select state/province";
                    }
                    if(trim($zipcode)==""){
                        $error['zipcodeValid']="Please enter zip/postal code";
                    }
                    if(trim(strlen($discount_coupon))>0){
                        if($this->validatediscountcoupon($discount_coupon)=='no'){
                            $error['discountcodeValid']="notvalid";
                        }
                        
                    }
                    
                }else{
                   if(trim($promocode)=="" || strlen(trim($promocode))!=11){
                             $error['promocodeValid']="notvalid";
                            
                         } 
                    //check for valid promocode
                   if(trim($this->validpromocode($promocode))=='no'){
                       $error['promocodeValid']="notvalid";
                   }elseif (trim($this->validpromocode($promocode))=='used') {
                    $error['promocodeValid']="usedpromocode";
                   } 
                }
            
            //$error['alreadysub']="There is already an account in our system with this email address. Please use a different one";
            //Valid email address check
             $validEmail = $this->isValidEmail($emailaddress);
             if(!$validEmail) {
                $error['alreadysub']= "Please enter valid email address";
             }
              $emailAlreadyexist = $this->checkEmail($emailaddress,$clinic_id);
                if($emailAlreadyexist == 4) {
               $error['alreadysub']="There is already an account in our system with this email address. Please use a different one";
             }elseif($emailAlreadyexist ==3){
               $error['alreadysub']="There is already an account in our system with this email address. Please use a different one";
             }

                
            
           /************
           return '1'// do only payment                
           return '2';//subscription exist
           return '3';//exist other clinic
           return '4';//exist not a patient
           return '5';// email not exist create health record and mank payment
                      
             * 
             * */ 
             
             if(!empty($error)){
                $messageReturn="&validData=true";
                foreach($error as $key=>$value)   {
                    $messageReturn.="&".$key."=".urlencode($value);
                }
                $_SESSION['pateintSubs']['fname']=$firstName;
                $_SESSION['pateintSubs']['lname']=$lastName;
                $_SESSION['pateintSubs']['emailAddress']=$emailaddress;
                $_SESSION['pateintSubs']['add1']=$address1;
                $_SESSION['pateintSubs']['add2']=$address2;    
                $_SESSION['pateintSubs']['city']=$city;
                $_SESSION['pateintSubs']['state']=$state;
                $_SESSION['pateintSubs']['zip']=$zipcode; 
                $_SESSION['pateintSubs']['country']=$country;
                //$_SESSION['pateintSubs']['cardType']=$creditCardType;
                $_SESSION['pateintSubs']['exprMonth']=$expDateMonth;
                $_SESSION['pateintSubs']['exprYear']=$expDateYear;
                $_SESSION['pateintSubs']['cardNumber']=$creditCardNumber;
                $_SESSION['pateintSubs']['cvvNumber']=$cvv2Number;
                $_SESSION['pateintSubs']['promocode']=$promocode;
                $_SESSION['pateintSubs']['usePromoCode']=$usePromoCode;
                $_SESSION['pateintSubs']['discount_coupon']=$discount_coupon;
                header("location:index.php?action=patient_signup".$messageReturn);
                die();
            }

            
            if($emailAlreadyexist ==2){
             $error['alreadysub']="There is already an account in our system with this email address. Please use a different one";
                         if(!empty($error)){
                        $messageReturn="&validData=true";
                        foreach($error as $key=>$value)   {
                            $messageReturn.="&".$key."=".urlencode($value);
                        }
                        header("location:index.php?action=patient_signup".$messageReturn);
                        die();
                 }	
            }   

            $newuser='';
               if($emailAlreadyexist == 5) {                
              //Create patient registration
                        include_once("template/ehspatientRegistration/patientArray.php");
                        $this->formArray = $formArray;
                        $replace = $this->fillForm($this->formArray,true);
                        $this->formArray = $tableFieldArray;	
		        $tableArray = ($this->fillTableArray($this->formArray));
                        $tableArray['username'] = $emailaddress;
                        $tableArray['name_first'] =$firstName ;
                        $tableArray['name_last'] = $lastName;
                        $tableArray['ehs'] = '1';
                        $tableArray['status'] = '1';
                        $tableArray['usertype_id'] = 1;
                        $tableArray['usertype_id'] = 1;
                        $tableArray['created_by'] = 1;
                        $tableArray['creation_date'] = date('Y-m-d H:i:s');
                        $tableArray['modified'] = date('Y-m-d H:i:s');
                        $tableArray['mass_message_access'] = 1;
                        $tableArray['password'] = $password;
                        $tableArray['agreement'] = 1;
 
                        
                $clinic_user=0;
                if($this->insert($this->config['table']['user'], $tableArray)) {
                	$new_patient_id=$this->insert_id();
                    $providerList=$this->getTherapistlist($clinic_id);
                    if($providerList!=''){
                    	$providerlistarray=explode(',',$providerList);
                    	foreach($providerlistarray as $key=>$val){
                    		$data = array(
                                        'therapist_id' => $val,
                                        'patient_id' => $new_patient_id,
                                        'creation_date' => date('Y-m-d H:i:s'),
                                        'modified' => date('Y-m-d H:i:s'),
                                        'status' => '1'
                                        );
                            $this->insert($this->config['table']['therapist_patient'], $data); 
                            $clinic_user=1;       
                    	       }
                        }    
                	
                	    $newuser=1;
                        $userInfor=$this->userInfo($new_patient_id);
                        if($clinic_user==1){

                    $data = array(
					'clinic_id' => 	$clinic_id,
					'user_id'	=>  $new_patient_id,
				        );

                                // Insert row in clinic_user table to set relation between clinic and patient
                       if($this->insert($this->config['table']['clinic_user'], $data)) {
				        $replace['error'] = "successfull entry of record";	
                                   // mail to new patient contains username and password.//have the HTML content 

                       
                                }
                        }
                }
                /*//assign content
                //Assigned Health Video Series
                $this->schedulerEhsPlanAssignment(1,$new_patient_id,$clinic_id);
               //Assigned articles
              $this->schedulerEhsArticle(1,$new_patient_id,$clinic_id);
               //Assigned Goal
              $this->schedulerEhsGoal(1,$new_patient_id,$clinic_id);
               //Assigned Reminders
              $this->schedulerAddEhsReminder(1,$new_patient_id,$clinic_id);
              // code for assign Oswestry form to user
              */
                
        } elseif($emailAlreadyexist==1) {

                  $new_patient_id = $this->getProviderId($emailaddress);
                  $userInfor=$this->userInfo($new_patient_id);
                   $newuser=2; 
                   $this->update('user', array('name_first'=>$firstName,'name_last'=>$lastName,'password'=>$password,'creation_date'=>$new_patient_id), 'user_id='.$new_patient_id);
        }

                /*pay pal code $paymentType 0 for recurring and 1 for onetime payment*/
                 $paymentAmount = urlencode($this->value('cardPayment'));
                 $currencyID = urlencode($currencyID);// or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
                 $startDate = urlencode(date("Y-m-d")."T0:0:0");
                 $billingPeriod = urlencode($Frequency);                // or "Day", "Week", "SemiMonth", "Year"
                 $billingFreq = urlencode($billingFreq); // combination of this and  must be at most a year
                 $clinicName=html_entity_decode($this->get_clinic_info($new_patient_id,"clinic_name"),ENT_QUOTES, "UTF-8");
                $desc=urlencode($clinicName.' - '.html_entity_decode($this->value('HealthDescription'),ENT_QUOTES, "UTF-8"));
 				$BillingstreetAddr=urlencode($address1);
                $Billingstreet2Addr=urlencode($address2);
                $BillingCity=urlencode($city);
                $BillingState=urlencode($state);
                $BillingCountry=urlencode($country);
                $Billingzip=urlencode($zipcode);
                if(trim(strlen($discount_coupon))>0){
                    if($this->validatediscountcoupon($discount_coupon)=='yes'){
                        $discount_money=  $this->get_discount($discount_coupon);
                        $paymentAmount=($paymentAmount-$discount_money);
                    }
                } 
   
             $nvpStr="&PAYMENTACTION=Sale&AMT=$paymentAmount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=".$expDateMonth.$expDateYear."&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state"."&ZIP=$zipcode&COUNTRYCODE=$BillingCountry&CURRENCYCODE=$currencyID&EMAIL=".$emailaddress."&DESC=".$desc;
             if($this->value('usePromoCode')==1){
                    $this->submit_telespine_subscription($nvpStr,$new_patient_id,$clinic_id, $cid,$newuser);
             } else {
                    $this->submit_telespine_promocode_subscription($promocode,$new_patient_id,$clinic_id, $cid,$newuser);
            }
        
         
             }
             
             function validpromocode($promocode){
                 $sql="select * from telespine_promocode where promocode='{$promocode}'";
                 $result=$this->execute_query($sql);
                 if($this->num_rows($result)==1){
                     $fetchstatus=  $this->fetch_object($result);
                     if($fetchstatus->status==1){
                         return 'used';
                     }else{
                        return 'yes'; 
                     }
                     
                 }  else {
                     return 'no';
                 }
             }
             
             function submit_telespine_promocode_subscription($promocode,$new_patient_id,$clinic_id, $cid,$newuser) {
        
            $currencyID=urlencode($this->config["paypalprodetails"]["currencyID"]);
            $Frequency=urlencode($this->config["paypalprodetails"]["billingPeriod"]);
            $billingFreq=urlencode($this->config["paypalprodetails"]["billingFreq"]);
            $userInfor=$this->userInfo($new_patient_id);
        
            $firstName          =       $this->value('firstName');
            $lastName           =       $this->value('lastName');
            $emailaddress       =       $_REQUEST['email'];
            $address1           =       $this->value('address1');
            $address2           =       $this->value('address2');
            $city               =       $this->value('city');
            $state              =       $this->value('state');
            $zipcode            =       $this->value('zip');
            $country            =       'US';
            $ehsTimePeriod      =       $this->value('ehsTimePeriod');
            $paymentType        =       $this->value('paymentType');
            $onetime_price      =       $this->value('onetime_price');
            $password           =       $this->value('password');
           
            
            // Check the status of the payment.If success OR success with warning then change status and update database and make insertions
            if($this->validpromocode($promocode)=='yes'){
                // Make the database changes and redirect to the home page.
                $this->saveDataSubscriptionOneTelespine($promocode,$startDate,$new_patient_id,$clinic_id);
               // header("location:index.php?action=login"); 
                 $sqlupdatepromocode="update telespine_promocode set status='1', userid='{$new_patient_id}', assigndate=now(),clinic_id='{$clinic_id}' where promocode='{$promocode}'";
                 $this->execute_query($sqlupdatepromocode);
                if($newuser==1) {
                       $this->sendwelcomemail($new_patient_id,$clinic_id);
                       $this->sendmailtosysadmin($new_patient_id,'promocode');
                        $error['status']="1";//registration complete
                } else {
                         $error['status']="2";//successfully subscribed.
                }
                
              //Assigned Health Video Series
              $this->schedulerEhsPlanAssignment(1,$new_patient_id,$clinic_id);
               //Assigned articles
              $this->schedulerEhsArticle(1,$new_patient_id,$clinic_id);
               //Assigned Goal
              $this->schedulerEhsGoal(1,$new_patient_id,$clinic_id);
               //Assigned Reminders
              $this->schedulerAddEhsReminder(1,$new_patient_id,$clinic_id);
              if($clinic_id==$this->config['telespineid']){
                    $this->sendEmailToPatient($new_patient_id,$clinic_id);
              }
              // code for assign Oswestry form to user
                       /* assign one day message*/
              $data_base_message='Welcome to your first week of Telespine! During this first week, healing is the number one priority. To heal as quickly as possible, we’ll want you to maintain good posture and keep your spine in a neutral position. This will be the your first priority and help to alleviate the pain. You can also apply ice or heat and practice breathing techniques we’ll provide to speed up the healing process. You will notice that you have a lot of videos to watch this week. Do not be overwhelmed. Many of them are simply informational. During the first two weeks of your program, you will be given more exercises than later in the program. Starting in week two, you will see that you have two rest days each week.  For this first week however, be sure to make time for your recovery exercises each day. Every exercise and stretch will help you build a solid foundation of spinal strength, flexibility and mobility.';
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$new_patient_id."',
													sender_id='".$clinic_id."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
              /*************end of assign message*/ 
                if(!empty($error)){
                $messageReturn="&validData=true";
                foreach($error as $key=>$value)   {
                    $messageReturn.="&".$key."=".urlencode($value);
                }
                        header("location:index.php?action=patientlogin&patientlogin=submitted&username={$emailaddress}&password={$password}&cid={$cid}".$messageReturn);
                        die();
                }

                
            }
            else
            { 
                //die();
                // Payment unsucessfull redirect to the Paymnet page again with error code.
                $errorCode=$httpParsedResponseAr['L_ERRORCODE0'];
                $_SESSION['pateintSubs']['fname']=$firstName;
                $_SESSION['pateintSubs']['lname']=$lastName;
                 $_SESSION['pateintSubs']['emailAddress']=$emailaddress;
                $_SESSION['pateintSubs']['add1']=$address1;
                $_SESSION['pateintSubs']['add2']=$address2;    
                $_SESSION['pateintSubs']['city']=$city;
                $_SESSION['pateintSubs']['state']=$state;
                $_SESSION['pateintSubs']['zip']=$zipcode; 
                $_SESSION['pateintSubs']['country']=$country;
                $_SESSION['pateintSubs']['cardType']=$creditCardType;
                $_SESSION['pateintSubs']['exprMonth']=$expDateMonth;
                $_SESSION['pateintSubs']['exprYear']=$expDateYear;
                $_SESSION['pateintSubs']['cardNumber']=$creditCardNumber;
                $_SESSION['pateintSubs']['cvvNumber']=$cvv2Number; 
                //$_SESSION['pateintSubs']['clinicProvider']=$clinicProvider; 
                //echo "<br>";
                  //   echo "$nvpStr,$new_patient_id,$clinic_id";exit;   
                //Delete the newly created patient record
                $querydelPatient = "DELETE FROM user WHERE user_id = '{$new_patient_id}'";
                $this->execute_query($querydelPatient);

                //Delete the newly created patient record from therapist patient
                $querydelTherapistPatient = "DELETE FROM therapist_patient WHERE patient_id  = '{$new_patient_id}'";
                $this->execute_query($querydelTherapistPatient);

                //Delete the newly created patient record from therapist patient
                $querydelClinicUser = "DELETE FROM clinic_user WHERE user_id = '{$new_patient_id}'";
                $this->execute_query($querydelClinicUser); 

               header("location:index.php?action=patient_signup&cid={$cid}&validData=true&errorCode={$errorCode}");       
            }
           
        }
        function submit_telespine_subscription($nvpStr,$new_patient_id,$clinic_id, $cid,$newuser) {
        
            $API_UserName=urlencode($this->config["paypalprodetails"]["API_UserName"]);
            $API_Password=urlencode($this->config["paypalprodetails"]["API_Password"]);
            $API_Signature=urlencode($this->config["paypalprodetails"]["API_Signature"]);
            $environment=urlencode($this->config["paypalprodetails"]["environment"]);
            $currencyID=urlencode($this->config["paypalprodetails"]["currencyID"]);
            $Frequency=urlencode($this->config["paypalprodetails"]["billingPeriod"]);
            $billingFreq=urlencode($this->config["paypalprodetails"]["billingFreq"]);
            $userInfor=$this->userInfo($new_patient_id);
        
           $firstName          =       $this->value('firstName');
            $lastName           =       $this->value('lastName');
            $emailaddress       =       $_REQUEST['email'];
           // $clinicProvider     =       $this->value('clinicProvider');
           // $creditCardType     =       $this->value('cardType');
            $creditCardNumber   =       $this->value("cardNumber");
            $expDateMonth       =       $this->value('expMonth');
            $expDateYear        =       $this->value('expYear');
            $cvv2Number         =       $this->value('cvv2');
            $address1           =       $this->value('address1');
            $address2           =       $this->value('address2');
            $city               =       $this->value('city');
            $state              =       $this->value('state');
            $zipcode            =       $this->value('zip');
            $country            =       'US';
            $ehsTimePeriod      =       $this->value('ehsTimePeriod');
            $paymentType        =       $this->value('paymentType');
            $onetime_price      =       $this->value('onetime_price');
            $password           =       $this->value('password');
            $discount_coupon    =       $this->value('discount_coupon');
            $paypalProClass=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
            
            // Paypal API request For Recurring profile creation.
            $httpParsedResponseAr = $paypalProClass->hash_call('doDirectPayment', $nvpStr);            
               /* echo "<pre>";
            print_r($httpParsedResponseAr);
           echo "</pre>";
            die;*/
            // Code to fill history Log of Profile creation
            $this->paypalHistory($httpParsedResponseAr,$new_patient_id,$httpParsedResponseAr['TRANSACTIONID'],'onetime');
            
           
            // Check the status of the payment.If success OR success with warning then change status and update database and make insertions
            if(strtolower($httpParsedResponseAr['ACK'])=="success" || strtolower($httpParsedResponseAr['ACK'])=="successwithwarning")    {
                // Make the database changes and redirect to the home page.
                $this->saveDataSubscriptionOneTelespine(trim(urldecode($httpParsedResponseAr['TRANSACTIONID'])),$startDate,$new_patient_id,$clinic_id);
               // header("location:index.php?action=login"); 
                if($newuser==1) {
                        $this->sendwelcomemail($new_patient_id,$clinic_id);
                         $this->sendmailtosysadmin($new_patient_id,'Credit Card');
                        $error['status']="1";//registration complete
                } else {
                         $error['status']="2";//successfully subscribed.
                }
                if(strlen(trim($discount_coupon))>0){
                    if($this->validatediscountcoupon($discount_coupon)=='yes'){
                        $discount_money=  $this->get_discount($discount_coupon);
                        $sql="INSERT INTO `telespine_discountcoupon_user`(`code`, `userid`,`discount`) VALUES ('{$discount_coupon}','{$new_patient_id}','{$discount_money}')";
                        $this->execute_query($sql);
                    }
                }
              //Assigned Health Video Series
              $this->schedulerEhsPlanAssignment(1,$new_patient_id,$clinic_id);
               //Assigned articles
              $this->schedulerEhsArticle(1,$new_patient_id,$clinic_id);
               //Assigned Goal
              $this->schedulerEhsGoal(1,$new_patient_id,$clinic_id);
               //Assigned Reminders
              $this->schedulerAddEhsReminder(1,$new_patient_id,$clinic_id);
              if($clinic_id==$this->config['telespineid']){
                    $this->sendEmailToPatient($new_patient_id,$clinic_id);
              }
              // code for assign Oswestry form to user
              /* assign one day message*/
              $data_base_message='Welcome to your first week of Telespine! During this first week, healing is the number one priority. To heal as quickly as possible, we’ll want you to maintain good posture and keep your spine in a neutral position. This will be the your first priority and help to alleviate the pain. You can also apply ice or heat and practice breathing techniques we’ll provide to speed up the healing process. You will notice that you have a lot of videos to watch this week. Do not be overwhelmed. Many of them are simply informational. During the first two weeks of your program, you will be given more exercises than later in the program. Starting in week two, you will see that you have two rest days each week.  For this first week however, be sure to make time for your recovery exercises each day. Every exercise and stretch will help you build a solid foundation of spinal strength, flexibility and mobility.';
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$new_patient_id."',
													sender_id='".$clinic_id."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
              /*************end of assign message*/                                                 
              
                if(!empty($error)){
                $messageReturn="&validData=true";
                foreach($error as $key=>$value)   {
                    $messageReturn.="&".$key."=".urlencode($value);
                }
                        header("location:index.php?action=patientlogin&patientlogin=submitted&username={$emailaddress}&password={$password}&cid={$cid}".$messageReturn);
                        die();
                }

                
            }
            else
            { 
                //die();
                // Payment unsucessfull redirect to the Paymnet page again with error code.
                $errorCode=$httpParsedResponseAr['L_ERRORCODE0'];
                $_SESSION['pateintSubs']['fname']=$firstName;
                $_SESSION['pateintSubs']['lname']=$lastName;
                 $_SESSION['pateintSubs']['emailAddress']=$emailaddress;
                $_SESSION['pateintSubs']['add1']=$address1;
                $_SESSION['pateintSubs']['add2']=$address2;    
                $_SESSION['pateintSubs']['city']=$city;
                $_SESSION['pateintSubs']['state']=$state;
                $_SESSION['pateintSubs']['zip']=$zipcode; 
                $_SESSION['pateintSubs']['country']=$country;
                $_SESSION['pateintSubs']['cardType']=$creditCardType;
                $_SESSION['pateintSubs']['exprMonth']=$expDateMonth;
                $_SESSION['pateintSubs']['exprYear']=$expDateYear;
                $_SESSION['pateintSubs']['cardNumber']=$creditCardNumber;
                $_SESSION['pateintSubs']['cvvNumber']=$cvv2Number; 
                //$_SESSION['pateintSubs']['clinicProvider']=$clinicProvider; 
                //echo "<br>";
                  //   echo "$nvpStr,$new_patient_id,$clinic_id";exit;   
                //Delete the newly created patient record
                $querydelPatient = "DELETE FROM user WHERE user_id = '{$new_patient_id}'";
                $this->execute_query($querydelPatient);

                //Delete the newly created patient record from therapist patient
                $querydelTherapistPatient = "DELETE FROM therapist_patient WHERE patient_id  = '{$new_patient_id}'";
                $this->execute_query($querydelTherapistPatient);

                //Delete the newly created patient record from therapist patient
                $querydelClinicUser = "DELETE FROM clinic_user WHERE user_id = '{$new_patient_id}'";
                $this->execute_query($querydelClinicUser); 

               header("location:index.php?action=patient_signup&cid={$cid}&validData=true&errorCode={$errorCode}");       
            }
           
        }
        
        function saveDataSubscriptionOneTelespine($profileNumber,$startDate,$new_patient_id,$clinic_id){
            // Gather information from REQUEST
            $userInfor=$this->userInfo($new_patient_id);
            $clinicID=$clinic_id;
            $firstName = $this->value('firstName');
            $lastName = $this->value('lastName');
            $insertName=$firstName.' '.$lastName;
            $HealthProgramID = $this->value('HealthProgramID');
            $HealthServiceName = $this->value('HealthDescription');
            
             $email= $this->userInfo('username',$new_patient_id);
            $paymentAmount = $this->value('cardPayment'); 
            
            $Frequency=urlencode($this->config["paypalprodetails"]["billingPeriod"]);
            $billingFreq=$this->value('ehsTimePeriod');
            if($billingFreq==''){
            $billingFreq=urlencode($this->config["paypalprodetails"]["billingFreq"]);
            }
            $address1=$this->value('address1');
            $address2=$this->value('address2');
            $city=$this->value('city');
            $state=$this->value('state');
            $zipcode=$this->value('zipcode');
            $country=$this->value('country');
            $discount_coupon    =       $this->value('discount_coupon');
            
            
            //Query Health Program Details
            $queryHealthProgram=$this->execute_query("select * from `clinic_subscription` where subs_id = '{$HealthProgramID}' ");
            $resultHealthProgram=$this->fetch_object($queryHealthProgram);
            $subscriptionName=$resultHealthProgram->subs_title;
            $subscriptionDescription=$resultHealthProgram->subs_description;
           if($this->value('usePromoCode')==1)
            $paymentType=$resultHealthProgram->paymentType;
           else
            $paymentType=3; // this is use for promocode
            
            //Features Array
            $keyFeatures=array();
            if($resultHealthProgram->subs_feature1 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature1;
            }
            
            if($resultHealthProgram->subs_feature2 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature2;
            }            
            
            if($resultHealthProgram->subs_feature3 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature3;
            }            
            
            if($resultHealthProgram->subs_feature4 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature4;
            }            
            
            if($resultHealthProgram->subs_feature5 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature5;
            }            
            
            if($resultHealthProgram->subs_feature6 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature6;
            }            
            
            if($resultHealthProgram->subs_feature7 != ''){
                $keyFeatures[]=$resultHealthProgram->subs_feature7;
            }            
            
            $subscriptionFeatures=serialize($keyFeatures);
            $onetime_price=$resultHealthProgram->onetime_price;
             if(trim(strlen($discount_coupon))>0){
                    if($this->validatediscountcoupon($discount_coupon)=='yes'){
                        $discount_money=  $this->get_discount($discount_coupon);
                        $paymentAmount=($paymentAmount-$discount_money);
                        $onetime_price=($onetime_price-$discount_money);
                    }
                }
            $querySubscriptEntry="insert into patient_subscription set `subscription_subs_id`='{$HealthProgramID}',
                                                                       `clinic_id`='{$clinicID}',
                                                                        `user_id`='{$new_patient_id}',
                                                                        `subs_id`='{$HealthProgramID}',
                                                                        `subs_datetime`=now(),
                                                                        `subs_status`='1',
                                                                        `subs_end_datetime`=DATE_ADD(now(), 
                                                                        INTERVAL {$billingFreq} {$Frequency}),
                                                                        `address1`='{$address1}',
                                                                        `address2`='{$address2}',
                                                                        `city`='{$city}',
                                                                        `state`='{$state}',
                                                                        `zipcode`='{$zipcode}',
                                                                        `country`='{$country}',
                                                                        `payment_paypal_profile`= '{$profileNumber}',
                                                                        `b_first_name`='{$firstName}',
                                                                        `b_last_name`='{$lastName}', 
                                                                        `subscription_title`='{$subscriptionName}',
                                                                        `subscription_desc`='{$subscriptionDescription}',
                                                                        `subscription_feature`='{$subscriptionFeatures}',
                                                                        `ehsTimePeriod`='{$billingFreq}', `paymentType`='{$paymentType}',`onetime_price`='{$onetime_price}'";
            
            $result=$this->execute_query($querySubscriptEntry);
            $subs_insert_id=$this->insert_id();
            $data=array('patient_subscription_user_subs_id'=> $subs_insert_id,
                'user_id'=>$new_patient_id,
                'user_subs_id'=>$subs_insert_id,
                'payment_paypal_profile'=>$profileNumber,
                'paymnet_datetime'=>date('Y-m-d:e:h:s'),
                'payment_price'=>$paymentAmount,
                'payment_b_name'=>$firstName.' '.$lastName,
                'paymnet_b_email'=>$email,
               'paymnet_status'=>1,
                'payment_txn_type'=>'onetime'
                );
                $this->insert('patient_payment',$data);

                $this->execute_query("UPDATE user SET ehs = '1'  WHERE user_id = {$new_patient_id}");
           
           // Send email to the Account Admin 
        //   $this->sendTxmessageAccountAdmin($clinic_id,$new_patient_id,$HealthProgramID,1);

           // Send email to the Patient
           //$this->sendEmailSignUpPatients($clinic_id,$new_patient_id,$HealthProgramID,1);
        
        }
        
        
        function schedulerEhsPlanAssignment($day,$pid,$clinicid) {

               // $query = "SELECT * FROM plan where ehsFlag = '2' AND clinicId={$clinicid} AND status = '4' AND scheduleday = {$day}";
                $query = " select * from plan where status =1 AND patient_id IS NULL AND clinicId = '{$clinicid}' AND ehsFlag = '2' and scheduleday ={$day} ORDER BY plan_id DESC";
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

							        );

					          $sqlPlanArticleInsert = "INSERT INTO plan_article set 
							        plan_id = '".$data['plan_id']."',
							        article_id = '".$data['article_id']."',
							        modified = '".$data['modified']."',
							        creation_date = '".$data['creation_date']."'";

				                $resultPlanInsert = @mysql_query($sqlPlanArticleInsert);

				        }

                        $i++;
                }
                 $notify='1';
                                       if($notify == '1') {
                                                $userId = $pid;

                                                $sql = "select username from user where user_id =".$userId." and status='1'" ;
                                                $res1 = @mysql_query($sql);
                                                $rw1 = @mysql_fetch_array($res1);
                                                $username = $rw1['username'];
                                                if($clinicid==$this->config['telespineid']){
                                                    /*if( $userId != "" ) {
                                                          $fullname=$this->userInfo("name_first",$userId).' '.$this->userInfo("name_last",$userId);
                                                          $business_url=$this->config['business_telespine']; 
                                                          $support_email=$this->config['email_telespine']; 
                                                            $data = array(
                                                                  'url' => $this->config['url'],
                                                                  'images_url' => $this->config['images_url'],
                                                                  'business_url'=>$business_url,
                                                                  'support_email'=>$support_email,
                                                                  'fullname'=>$fullname,
                                                                  'loginurl'=>$this->config['telespine_login'],
                                                                  );
                                                             $message = $this->build_template("mail_content/telespine/New_Video_Assigned_Email_Notification.php",$data);
                                                             $to = $fullname."<".$this->userInfo("username",$userId).">";
                                                             $subject = "Telespine Update - New Video(s)";
                                                             // To send HTML mail, the Content-type header must be set
                                                             $headers  = 'MIME-Version: 1.0' . "\n";
                                                             $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                                                             $headers .= "From:  Telespine Support <support@telespine.com>\n";
                                                              $returnpath = '-fsupport@telespine.com';
                                                              // Mail it
                                                              // @mail($to, $subject, $message, $headers, $returnpath);
                                                              @mail($to, $subject, $message, $headers, $returnpath);
                                                              
                                                              
                                                    }*/
                                                }  else {
                                                    
                                                
                                                            if( $userId != "" ) {

                                                                    $clinicId  = $this->get_clinic_info($userId);
                                                                    $clinicName  =  html_entity_decode($this->get_clinic_info($userId ,"clinic_name"), ENT_QUOTES, "UTF-8");
                                                                    $clinic_channel=  $this->getchannel($clinicId);
                                                                     if($clinic_channel==1) {
                                                                           $business_url=$this->config['business_tx'];
                                                                           $support_email=$this->config['email_tx'];
                                                                       } else {
                                                                           $business_url=$this->config['business_wx'];
                                                                           $support_email=$this->config['email_wx'];
                                                                       }
                                                                       $data = array(
                                                                            'plan_name' => $plan_name_mail,
                                                                            'url' => $this->config['url'],
                                                                            'images_url' => $this->config['images_url'],
                                                                            'business_url'=>$business_url,
                                                                            'support_email'=>$support_email,
                                                                           'clinic_name'=>$clinicName
                                                                    );
                                                                    if( $clinic_channel == 1) {
                                                                            $message = $this->build_template("mail_content/plpto/notify_mail_plpto.php",$data);
                                                                    }
                                                                    else{
                                                                            $message = $this->build_template("mail_content/wx/notify_mail_plpto.php",$data);
                                                                    }
                                                                    $to = $username;
                                                                    $subject = "Educational Information from ".$clinicName;
                                                                    // To send HTML mail, the Content-type header must be set
                                                                    $headers  = 'MIME-Version: 1.0' . "\n";
                                                                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                                                                    $headers .= "From: ".$this->setmailheader($clinicName)."<do-not-reply@txxchange.com>\n";
                                                                    $returnpath = '-fdo-not-reply@txxchange.com';
                                                                    // Mail it
                                                                   // @mail($to, $subject, $message, $headers, $returnpath);
                                                                    @mail($to, $subject, $message, $headers, $returnpath);
                                                    }
                                        }
                                }
                                
                }
        }
        
        function schedulerEhsArticle($days,$pid,$clinicId) {
        	//global $application_path,$imagePath,$url,$from_email_address,$business_wx,$business_tx,$email_wx,$email_tx,$images_url;
        	
                 $sendemail="no";
                 $query = "SELECT * FROM patient_article,article where patient_article.article_id=article.article_id and article.status=1 and ehsFlag = '2' AND patient_article.status='1' AND schdularday = {$days} AND clinicID={$clinicId}";
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
                            $sendemail="yes";  
                        $i++;
                }
				if($sendemail=='yes'){
                                     if($clinicId==$this->config['telespineid']){
                                                    
                                                          /*$fullname=$this->userInfo("name_first",$pid).' '.$this->userInfo("name_last",$pid);
                                                          $business_url=$this->config['business_telespine']; 
                                                          $support_email=$this->config['email_telespine']; 
                                                            $data = array(
                                                                  'url' => $this->config['url'],
                                                                  'images_url' => $this->config['images_url'],
                                                                  'business_url'=>$business_url,
                                                                  'support_email'=>$support_email,
                                                                  'fullname'=>$fullname,
                                                                  'loginurl'=>$this->config['telespine_login'],
                                                                  );
                                                             $message = $this->build_template("mail_content/telespine/New_Article_Assigned_Email_Notification.php",$data);
                                                             $to = $fullname."<".$this->userInfo("username",$pid).">";
                                                             $subject = "Telespine Update - New Article";
                                                             // To send HTML mail, the Content-type header must be set
                                                             $headers  = 'MIME-Version: 1.0' . "\n";
                                                             $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                                                             $headers .= "From:  Telespine Support <support@telespine.com>\n";
                                                              $returnpath = '-fsupport@telespine.com';
                                                              // Mail it
                                                              // @mail($to, $subject, $message, $headers, $returnpath);
                                                              @mail($to, $subject, $message, $headers, $returnpath);*/
                                                              
                                                              
                                                    
                                                }  else {
                                        
                                    		$clinicName=html_entity_decode($this->get_clinic_info($pid,'clinic_name'), ENT_QUOTES, "UTF-8");
						$clinicId  = $this->get_clinic_info($pid);
						$clinic_channel=  $this->getchannel($clinicId);
						
				if($clinic_channel==2){
        			$business_url=$this->config['business_wx'];
                                $support_email=$this->config['email_wx'];
				}else{
                                $business_url=$this->config['business_tx'];
                                $support_email=$this->config['email_tx'];  
                                }
                $data = array(
				'url' => $this->config['url'],
                                'images_url' => $this->config['images_url'],
                                'business_url'=>$business_url,
                                'support_email'=>$support_email,
                                'clinic_name'=>$clinicName,
                                
				
				);
			if( $clinic_channel == '2'){
		   		$message = $this->build_template("mail_content/wx/new_article_assign.php",$data);
	        }else{
	           $message = $this->build_template("mail_content/new_article_assign.php",$data);	
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
			$headers .= "From: ".  $this->setmailheader($clinicName). " <do-not-reply@txxchange.com>" . "\n";
			$returnpath = "-fdo-not-reply@txxchange.com";   
			
		}else{
			$headers .= "From: ".  $this->setmailheader($clinicName). " <do-not-reply@txxchange.com>" . "\n";
			$returnpath = "-fdo-not-reply@txxchange.com";
		}
		// Mail it
                mail($to, $subject, $message, $headers, $returnpath);
                                    }	
				}
                }
  }    
  
        function schedulerEhsGoal($day,$pid,$clinicId) {
                
                 $query = "SELECT * FROM patient_goal where ehsGoal = '2' AND schduleday = {$day} and clinicId={$clinicId} AND status !='3'";
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
                      
           }
                
        }
        
        function schedulerAddEhsReminder($day,$pid,$clinicId) {

                 $query = "SELECT * FROM patient_reminder where ehsReminder = '2' AND schduleday = {$day} AND clinicId={$clinicId}";
                 $result = @mysql_query($query);
                 $numRows = @mysql_num_rows($result);

               if($numRows > 0) { 

                        
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
                                        $date = date("Y-m-d");
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
                        $i++;
                }
            }

         }
function validatediscountcoupon($coupnecode){
    if ($coupnecode!='') {
        $sql="SELECT * FROM  `telespine_discountcoupon` WHERE  `code` =  '{$coupnecode}' AND  `status` =1";
        $query=  $this->execute_query($sql);
        $numrows=  $this->num_rows($query);
        if($numrows ==1){
            return 'yes';
        }  else {
            return 'no';    
        }
        
    }  else {
            return 'no';    
    }
}
function get_discount($coupnecode){
   if ($coupnecode!='') {
        $sql="SELECT discount FROM  `telespine_discountcoupon` WHERE  `code` =  '{$coupnecode}' AND  `status` =1";
        $query=  $this->execute_query($sql);
        $numrows=  $this->num_rows($query);
        if($numrows ==1){
            $row=  $this->fetch_object($query);
            return $row->discount;
        }  else {
            return 'no';    
        }
        
    }  else {
            return 'no';    
    }
 
}

function changepassword(){
       
        $userid=  $this->value('userid');
        $key=  $this->value('key');
        $sql="select * from telespine_change_password where userid='{$userid}' and passwordkeys='{$key}' and status='1'";
        $query=  $this->execute_query($sql);
        $num_rows=  $this->num_rows($query);
        $replace = array();
            $replace['meta_head'] = $this->build_template($this->get_template("meta_head"));
            $replace['header'] =    $this->build_template($this->get_template("patient_dashboard_header"));
            $replace['footer'] =    $this->build_template($this->get_template("patient_dashboard_footer"));
            $replace['clinicid']= $this->config['telespineid'];
            $replace['theme']="default";
            $replace['errorclass']="";
             $replace['message']="Your Link is not valid";
        if($num_rows>0){
            $row=  $this->fetch_array($query);
              //update and show success msg page
                    $updateArr = array(
                        'password'=>$row['password']
                    );

                    $where = " user_id = ".$userid;

                    $result = $this->update('user',$updateArr,$where);
                    $updateArrpass = array(
                        'status'=>2
                    );

                    $wherepass = " id = ".$row['id'];

                    $result = $this->update('telespine_change_password',$updateArrpass,$wherepass);

            
            
            
          $replace['message']="Your password has been changed successfully, Now you can use your new password";
            
        }  else {
             $replace['message']="Your Link is not valid";
        }
        
        
         $this->output = $this->build_template($this->get_template("change_password"),$replace);  
    }

    function checksignupcode(){
        $signupcode=  $this->value('signupCodeToCheck');
         $sql="select * from telespine_promocode where promocode='{$signupcode}' and status=0";
                 $result=$this->execute_query($sql);
                 if($this->num_rows($result)==1){
                    echo  'TRUE';
                 }  
                 else
                 {
                    echo 'FALSE';
                 }
    }
    function  checkdiscountcode(){
        $coupnecode=  $this->value('discountCodeToCheck');
        if ($coupnecode!='') {
            $sql="SELECT * FROM  `telespine_discountcoupon` WHERE  `code` =  '{$coupnecode}' AND  `status` =1";
            $query=  $this->execute_query($sql);
            $numrows=  $this->num_rows($query);
           if($numrows ==1){
                echo  $this->get_discount($coupnecode);
           }  else {
                echo 'FALSE';
            }
        
       }else{
            echo 'FALSE';
        }
    }
    function sendEmailToPatient($new_patient_id,$clinic_id){
    
    $date = $this->config['configdate'];
    $business_url=$this->config['business_telespine'];
    $support_email=$this->config['email_telespine'];
    $remindertxt = "";
    $query = "select * from reminder_schedule where date(scheduled_date) = '{$date}' and status = 'pending' and patient_id={$new_patient_id}";
    // Fetch rows from resultset.
	$result = @mysql_query($query);
    
        if(mysql_num_rows($result)!=0){
            while( $row = @mysql_fetch_array($result) ){
                        if($row['reminderEhsFlag']==2){
                                      $remindertxt = $this->get_reminder_automaticschedule($row['patient_id'],$row['reminder_set'],$row['reminderEhsFlag'],$row['assignday']);
                                }
                	
            }
        }                           if($remindertxt!=''){
                                        $textreminder="<p style=\"margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;\">Also, keep the following in mind today - it'll help.</p>
<br>";
                                        $reminder=    $textreminder.$remindertxt;
                                      }
    
                                    $fullname   =   $this->userInfo("name_first",$new_patient_id);
                                    $data       =   array(
							'url' => $this->config['url'],
                                                        'images_url' => $this->config['images_url'],
                                                        'business_url'=>$business_url,
                                                        'support_email'=>$support_email,
                                                        'loginurl'=>$this->config['telespine_login'],
							'reminder' => $reminder,
                                                        'fullname'=>$fullname
                                                        );
			
                                	$message = $this->build_template("mail_content/telespine/reminder.php",$data);
                                        
	                      		$to = $fullname.'<'.$this->userInfo("username",$new_patient_id).'>';
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
                                                $query = "update reminder_schedule  set status = 'sent' where reminder_schedule_id = '{$row['reminder_schedule_id']}' ";
						@mysql_query($query);
                                                }
					}
					else{
                                            if($reminder!=''){	
                                                $query = "update reminder_schedule  set status = 'failed' where reminder_schedule_id = '{$row['reminder_schedule_id']}' ";
						@mysql_query($query);
                                            }
					} 
    }
     function get_reminder_automaticschedule($user_id,$reminder_set,$reminderEhsFlag,$assignday){
            	
            if($user_id != ""){
			$query = "select * from patient_reminder where patient_id = '{$user_id}' and reminder_set='{$reminder_set}' and ehsReminder='{$reminderEhsFlag}' and assignday={$assignday} ORDER BY patient_reminder_id DESC ";
			$result = @mysql_query($query);
                        
			if(@mysql_num_rows($result) > 0){
				$reminder = "<ul>";
				while( $row = @mysql_fetch_array($result) ){
					$reminder .= "<li>" . $this->decrypt_data($row['reminder']) . "</li>";
				}
				$reminder .= "</ul>";
                        //        echo $reminder;
				return $reminder;
			}
			return "";
		}
		return "";
            
            
        }
        
}

        
	// creating object of this class.
	$obj = new ehspatientRegistration();
?>
