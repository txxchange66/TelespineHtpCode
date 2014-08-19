<?php


    /**
     * 
     * Copyright (c) 2011 Tx Xchange.
     * 
     * This class is created for subscription if clinic has enabled the health services.
     * Check for Patients only.
     * 
     * // necessary classes 
     * require_once("module/application.class.php");
     * 
     * 
     * // validation classes
     * require_once("include/validation/_includes/classes/validation/ValidationSet.php");
     * require_once("include/validation/_includes/classes/validation/ValidationError.php");
     * 
     */
    
    
    // including files
    require_once("module/application.class.php");
    require_once("include/validation/_includes/classes/validation/ValidationSet.php");
    require_once("include/validation/_includes/classes/validation/ValidationError.php");
    require_once("include/class.paypal.php");

    
    // class declaration starts
      class patient_subscription extends application{

          
      // class variable declaration section
      
          /**
           * The variable defines the action request
           *
           * @var string
           * @access private
           */          
        private $action;

        
        /**
         * The variable defines all the fields present in the form
         *
         * @var string Array
         * @access private
         */        
        private $field_array;

        
        /**
         * The variable defines the error message(if any) against the action request
         * It could be an array if more than one error messages are there else a simple variable
         *
         * @var string
         * @access private
         */        
        private $error;

               
        /**
           * The variable is used for getting final output template or string message to be displayed to the user
         * This function of statement(s) are to handle all the actions supported by this Login class
         * that is it could be the case that more then one action are handled by login
         * for example at first the action is "login" then after submit say action is submit
         * so if login is explicitly called we have the login action set (which is also our default action)
         * else whatever action is it is set in $str.                
           * 
           * @var String
           * @access Private
           */        
        private $output;
        
        
        
        /**
        * @desc  This variable defines paypal error codes with User specific error messages.
        * 
        * @var array
        * @access Public
        */
        public $PaypalErroCodes=array('10001'=>'Check the Credit Card number or attempt with another credit card.','10501'=>'Invalid merchant configuration','10502'=>'Invalid credit card','10504'=>'Invalid Credit Card Verification Number','10505'=>'Merchant account is not able to accept transactions','10508'=>'Invalid credit card expiration date','10510'=>'The credit card type is not supported. Try another card type','10535'=>'Invalid credit card number and type');
        
        
        /**
         * Constructor
         * 
         * Check for user login and cll appropriate function of the class.
         * Get action from query string
         * Also check that user have rights to access this class or not.
         * 
         * Call display function to generate final output.
         */
        function __construct(){

            parent::__construct();

            if($this->value('action')){                

                $str = $this->value('action');

            }else{

                $str = "patient_subscription"; //default if no action is specified

            }

            $this->action = $str;

            if($this->get_checkLogin($this->action) == "true" ){

                if( isset($_SESSION['username']) && isset($_SESSION['password']) ){

                    if(!$this->chk_login($_SESSION['username'],$_SESSION['password'])){

                        header("location:index.php");

                    }

                }

                else{

                    header("location:index.php");

                }

            }

            /*
            if($this->userAccess($str)){
                $str = $str."()";
                if ($this->userInfo('agreement') == 1)
                {
                    header("location:index.php?action=therapist");
                }
                eval("\$this->$str;"); 
            }
            else{
                $this->output = $this->config['error_message'];
            } 
            */
            
            $str = $str."()";
            eval("\$this->$str;"); 
                
            $this->display();

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
         * Function to accept the Terms of Services & Privacy Policy and email promotion
         * 
         * @access public
         */
        function patient_subscription(){

            
            $errorCode=$this->value('errorCode');
                if($errorCode!='') {
                    $customerrormessage=$this->PaypalErroCodeslist($errorCode);
                    if($customerrormessage=='')                    
                        {
                            $customerrormessage="Invalid Credit Card Details";                        
                        }
                    $replace['errorMessage']='<tr><td colspan="4" style="height:20px; color:#0069a0;; font-weight:bold"><font style="color:#FF0000;font-weight:normal;">'.$customerrormessage.'</font></td></tr>';          
                    
                }
     
              
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
                     
                                                                    
                    if(urldecode($this->value('cnumberValid'))!='')  {      
                        $replace['cnumberValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cnumberValid').'</font>';  
                        $replace['retainedcardNumber'] = '';
                }                                          
                       $replace['retainedcardNumber'] = $_SESSION['pateintSubs']['cardNumber'];  
                                                                                                          
                    if(urldecode($this->value('cexpValid'))!='')             
                        $replace['cexpValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cexpValid').'</font>';                                                           
                    if(urldecode($this->value('ccvvValid'))!='') {            
                        $replace['ccvvValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('ccvvValid').'</font>'; 
                        $replace['retainedcvvNumber'] = '';
                     }
   $replace['retainedcvvNumber'] = $_SESSION['pateintSubs']['cvvNumber'];                                                                 
                    if(urldecode($this->value('ctypeValid'))!='')  {               
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
                    }else{
                    $replace['namefValid']='';
                    $replace['retainedFname']='';      
                    $replace['namelValid']=''; 
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
                    $replace['retainedcardNumber']='';             
                    $replace['retainVisaType']=''; 
                    $replace['retainMasterType']=''; 
                    $replace['retainedcvvNumber']='';
                         
             }
            $userInfo = $this->userInfo();            
            $clinicInfo=$this->clinicInfo('clinic_id');
            $dataSubs=$this->subscriptionInfo($clinicInfo);
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['footer'] = $this->build_template($this->get_template("footer"));    
            $replace['HealthProgramName']=$dataSubs["subs_title"];
            $replace['HealthProgramDescription']=html_entity_decode(str_replace('$','$ ',$dataSubs["subs_description"])); 
            
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
            if($dataSubs['paymentType']==0){
            	$replace['submitsubcription']='submit_patient_subscription';
            	$replace['HealthProgramPrice']=$dataSubs['subs_price'];
            	$replace['HealthProgramPriceshow']='<h1>&#36;'.$dataSubs['subs_price'].' a month</h1>This is an online health service provided by '.$dataSubs['clinic_name'].'. There are no contracts and you can cancel at any time.';
            	$replace['ehsTimePeriod']=$dataSubs['ehsTimePeriod'];
            	
            }else{
            	$replace['HealthProgramPrice']=$dataSubs['onetime_price'];
            	$replace['submitsubcription']='submit_patient_onetime_subscription';
            	if($dataSubs['ehsTimePeriod']==1)
            	$show='month';
            	else
            	$show='months';
            	//$replace['HealthProgramPriceshow']='<h1>&#36;'.$dataSubs['onetime_price']. ' for '.$dataSubs['ehsTimePeriod'].' '.$show.'</h1>This is an online health service provided by '.$dataSubs['clinic_name'].'.';
            	$replace['HealthProgramPriceshow']='<h1>&#36;'.$dataSubs['onetime_price']. ' for '.$dataSubs['ehsTimePeriod'].' '.$show.'</h1>This is an online health service provided by '.$dataSubs['clinic_name'].'. At the end of the term listed above, your credit card will <u>not</u> be charged again.';
            	$replace['ehsTimePeriod']=$dataSubs['ehsTimePeriod'];
            }
            
            $replace['HealthProgramID']=$dataSubs['subs_id'];
            
            $replace['HealthProgramClinicName']=$dataSubs['clinic_name'];
            $replace['body'] = $this->build_template($this->get_template("patient_subscription"),$replace);

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
            $replace['RetaincountryUS']='';
            //checks if user country is US in session
            if($_SESSION['pateintSubs']['country']=='US'){ 
                 $replace['RetaincountryUS']='selected';
                 $replace['RetaincountryCAN']='';
                 $replace['patient_state_options'] = $this->build_select_option($stateArray, $_SESSION['pateintSubs']['state']);         
            //checks if user country is Canada in session
            }elseif($_SESSION['pateintSubs']['country']=='CA'){   
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
			$replace['stateOptions']=$this->build_select_option($stateArray, $_SESSION['pateintSubs']['state']);
             if($this->value('status')=='show')
             {
                $row=$this->get_patient_subscription($userInfo['user_id']);
             //   print_r($row);
             	$urlStr="<script>GB_showCenter('Patient Subscription', '/index.php?action=oneTimeExpMessage&servicename=".$row["subscription_title"]."',130,420);</script>";
                
                
             }else{
                
                $urlStr='';
             } 
             $replace['show']=$urlStr;
            //Unsetting the Session variable stored for Remembering field values if any wrong/incomplete info submitted by                  user.
            //print_r($_SESSION);
            unset($_SESSION['pateintSubs']);
            
            $this->output = $this->build_template($this->get_template("main"),$replace);

        }
        
                

        /**
         * Function to validate Credit Card Infromation and create Recurring CC Profile.
         * @access public
         */
        function submit_patient_subscription()
        {
            $API_UserName=urlencode($this->config["paypalprodetails"]["API_UserName"]);
            $API_Password=urlencode($this->config["paypalprodetails"]["API_Password"]);
            $API_Signature=urlencode($this->config["paypalprodetails"]["API_Signature"]);
            $environment=urlencode($this->config["paypalprodetails"]["environment"]);
            $currencyID=urlencode($this->config["paypalprodetails"]["currencyID"]);
            $Frequency=urlencode($this->config["paypalprodetails"]["billingPeriod"]);
            $billingFreq=urlencode($this->config["paypalprodetails"]["billingFreq"]);
            $userInfor=$this->userInfo();
            
            $firstName = $this->value('firstname');
            $lastName = $this->value('lastname');
            $email=$userInfor['username'];
            $creditCardType = $this->value('cardType');
            $creditCardNumber = $this->value("cardNumber");
            $expDateMonth = $this->value('exprMonth');
            $expDateYear = $this->value('exprYear');
            $cvv2Number = $this->value('cvvNumber');
            $address1 = $this->value('address1');
            $address2 = $this->value('address2');
            $city = $this->value('city');
            $state = $this->value('state');
            $zipcode = $this->value('zipcode');
            $country = $this->value('country');
           
            //Code To Validate User Entered Data
            $error=array();
            if(trim($firstName)=="" ){
                $error['namefValid']="Please enter firstname";   
            }

            if(trim($lastName)==""){
                $error['namelValid']="Please enter lastname";   
            }            
            
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
            if(!empty($error)){
                $messageReturn="&validData=true";
                foreach($error as $key=>$value)   {
                    $messageReturn.="&".$key."=".urlencode($value);
                }
                $_SESSION['pateintSubs']['fname']=$firstName;
                $_SESSION['pateintSubs']['lname']=$lastName;
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
                header("location:index.php?action=patient_subscription".$messageReturn);
                die();
            }
            
            $paymentAmount = urlencode($this->value('cardPayment'));
            $currencyID = urlencode($currencyID);                        // or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
            $startDate = urlencode(date("Y-m-d")."T0:0:0");
            $billingPeriod = urlencode($Frequency);                // or "Day", "Week", "SemiMonth", "Year"
            $billingFreq = urlencode($billingFreq);                        // combination of this and billingPeriod must be at most a year
            $clinicName=html_entity_decode($this->get_clinic_info($userInfor['user_id'],"clinic_name"), ENT_QUOTES, "UTF-8");
            $desc=urlencode($clinicName.' - '.html_entity_decode($this->value('HealthDescription'), ENT_QUOTES, "UTF-8"));
            
            $nvpStr="&FIRSTNAME=$firstName&LASTNAME=$lastName&EMAIL=$email&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=$expDateMonth$expDateYear&CVV2=$cvv2Number&AMT=$paymentAmount&CURRENCYCODE=$currencyID&PROFILESTARTDATE=$startDate";
            $nvpStr .= "&BILLINGPERIOD=$billingPeriod&BILLINGFREQUENCY=$billingFreq&DESC=$desc";
            // For Billing Address
            
                $BillingstreetAddr=urlencode($address1);
                $Billingstreet2Addr=urlencode($address2);
                $BillingCity=urlencode($city);
                $BillingState=urlencode($state);
                $BillingCountry=urlencode($country);
                $Billingzip=urlencode($zipcode);
                $nvpStr .= "&STREET=$BillingstreetAddr&STREET2=$Billingstreet2Addr&CITY=$BillingCity&STATE=$BillingState&COUNTRYCODE=$BillingCountry&ZIP=$Billingzip";
 
             $userInfor=$this->userInfo();
            // Code to Limit the MAXFAILEDPAYMENTS
             $nvpStr .='&MAXFAILEDPAYMENTS=1&AUTOBILLOUTAMT=AddToNextBilling'; 
            //echo $nvpStr."<br>"; 
            // Paypal Recurring Object
            $paypalProClass=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
            
            // Paypal API request For Recurring profile creation.
            $insertArr['user_Id']=$userInfor['user_id'];
            $insertArr['clinic_id']=$clinicID=$this->clinicInfo('clinic_id');
            $insertArr['date']=date("Y-m-d H:i:s");
            $insertArr['status']=1;
            $sqlPaymentSubscription="SELECT * FROM patient_subscription
							WHERE user_id={$userInfor['user_id']} AND ((subs_status='1' AND subs_end_datetime > now())
							OR (subs_status='2' AND subs_end_datetime > now()))";
            $querysubscription = $this->execute_query($sqlPaymentSubscription);
            $numquerysubscription = $this->num_rows($querysubscription);
            if($numquerysubscription==0){
		    $sqlrequest="select * from subscription_request where user_Id='{$userInfor['user_id']}' and clinic_id='{$insertArr['clinic_id']}' and status='1'";
		    $requestquery=$this->execute_query($sqlrequest);
		    if($this->num_rows($requestquery)==0){	           
		    		$insertArr['subscription_id']= $this->value('HealthProgramID');
		    		$this->insert('subscription_request',$insertArr);
		    		$statusid=$this->insert_id();
		    		$httpParsedResponseAr = $paypalProClass->PPHttpPost('CreateRecurringPaymentsProfile', $nvpStr);            
		    		// Code to fill history Log of Profile creation
		    		$this->paypalHistory($httpParsedResponseAr,$userInfor['user_id'],trim(urldecode($httpParsedResponseAr['PROFILEID'])),'signup');
				    /*echo "<pre>";
				    print_r($httpParsedResponseAr);
				    echo "</pre>";
			  			die;*/          
			    // Check the status of the payment.If success OR success with warning then change status and update database and make insertions
                            if(strtolower($httpParsedResponseAr['ACK'])=="success" || strtolower($httpParsedResponseAr['ACK'])=="successwithwarning")    {
			        // Make the database changes and redirect to the home page.
                                
                                $sql=  $this->execute_query("select * from addon_services where clinic_id={$insertArr['clinic_id']}");
                                $queryauto=$this->fetch_object($sql);
                                if($queryauto->automatic_scheduling==1){
                                    $this->execute_query("UPDATE  user SET  creation_date =  now() WHERE  user_id =".$userInfor['user_id']);
                                    //Assigned Health Video Series 
                                    $this->schedulerEhsPlanAssignment(1,$userInfor['user_id'],$insertArr['clinic_id']);
                                    //Assigned articles
                                    $this->schedulerEhsArticle(1,$userInfor['user_id'],$insertArr['clinic_id']);
                                    //Assigned Goal
                                     $this->schedulerEhsGoal(1,$userInfor['user_id'],$insertArr['clinic_id']);
                                    //Assigned Reminders
                                    $this->schedulerAddEhsReminder(1,$userInfor['user_id'],$insertArr['clinic_id']);      
                                }
			        
                                $this->saveDataSubscription(trim(urldecode($httpParsedResponseAr['PROFILEID'])),$startDate);
			        $this->execute_query("update subscription_request set status='2' where id='".$statusid."'");
			        header("location:index.php?action=patient&payment=sucess"); 
			        die();
		    	     }
		   	     else
		    	     {
			      //die();
			      // Payment unsucessfull redirect to the Paymnet page again with error code.
			      $errorCode=$httpParsedResponseAr['L_ERRORCODE0'];
			      $_SESSION['pateintSubs']['fname']=$firstName;
			      $_SESSION['pateintSubs']['lname']=$lastName;
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
			      $this->execute_query("update subscription_request set status='3' where id='".$statusid."'"); 
			      header("location:index.php?action=patient_subscription&validData=true&errorCode={$errorCode}");    
		    	      }
		    }else{
		        header("location:index.php?action=patient_subscription&validData=true&errorCode={$errorCode}");    
		    }
           }else{
       	        header("location:index.php?action=patient_subscription&validData=true&errorCode={$errorCode}");    
           
           }
        }

        /**
         * This function saves the Patient information and update the database as subscription purchased.
         * Also the end date is set to one month more than subscription start date.
         * @param 
         * @return array $subscriptionInfo
         * @access public
         */  
                
        function saveDataSubscription($profileNumber,$startDate){
            // Gather information from REQUEST
            $userInfor=$this->userInfo();
            $clinicID=$this->clinicInfo('clinic_id');
            $firstName = $this->value('firstname');
            $lastName = $this->value('lastname');
            $insertName=$firstName.' '.$lastName;
            $HealthProgramID = $this->value('HealthProgramID');
            $HealthServiceName = $this->value('HealthDescription');
            $email=$userInfor['username'];
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
            
            $querySubscriptEntry="insert into patient_subscription set `subscription_subs_id`='{$HealthProgramID}',`clinic_id`='{$clinicID}',`user_id`='{$userInfor['user_id']}',`subs_id`='{$HealthProgramID}',`subs_datetime`=now(),`subs_price`='{$paymentAmount}',`subs_status`='1',`subs_end_datetime`=DATE_ADD(now(), INTERVAL {$billingFreq} {$Frequency}),`address1`='{$address1}',`address2`='{$address2}',`city`='{$city}',`state`='{$state}',`zipcode`='{$zipcode}',`country`='{$country}',`payment_paypal_profile`= '{$profileNumber}',`b_first_name`='{$firstName}',`b_last_name`='{$lastName}',subscription_title='{$subscriptionName}',subscription_desc='{$subscriptionDescription}',subscription_feature='{$subscriptionFeatures}'";
            
            $result=$this->execute_query($querySubscriptEntry);
            $subs_insert_id=$this->insert_id();

           
           // Send email to the Account Admin 
           $this->sendTxmessageAccountAdmin($clinicID,$userInfor,$resultHealthProgram);

           // Send email to the Patient
           $this->sendEmailSignUpPatients($clinic_id,$userInfor,$resultHealthProgram,0);
        
        }
        
      /**
         * This function saves the Patient information and update the database as subscription purchased.
         * Also the end date is set to one month more than subscription start date.
         * @param 
         * @return array $subscriptionInfo
         * @access public
         */  
                
        function saveDataSubscriptionOneTime($profileNumber,$startDate){
            // Gather information from REQUEST
            $userInfor=$this->userInfo();
            $clinicID=$this->clinicInfo('clinic_id');
            $firstName = $this->value('firstname');
            $lastName = $this->value('lastname');
            $insertName=$firstName.' '.$lastName;
            $HealthProgramID = $this->value('HealthProgramID');
            $HealthServiceName = $this->value('HealthDescription');
            $email=$userInfor['username'];
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
            
            $querySubscriptEntry="insert into patient_subscription set `subscription_subs_id`='{$HealthProgramID}',`clinic_id`='{$clinicID}',`user_id`='{$userInfor['user_id']}',`subs_id`='{$HealthProgramID}',`subs_datetime`=now(),`subs_status`='1',`subs_end_datetime`=DATE_ADD(now(), INTERVAL {$billingFreq} {$Frequency}),`address1`='{$address1}',`address2`='{$address2}',`city`='{$city}',`state`='{$state}',`zipcode`='{$zipcode}',`country`='{$country}',`payment_paypal_profile`= '{$profileNumber}',`b_first_name`='{$firstName}',`b_last_name`='{$lastName}', `subscription_title`='{$subscriptionName}',`subscription_desc`='{$subscriptionDescription}',`subscription_feature`='{$subscriptionFeatures}',`ehsTimePeriod`='{$billingFreq}', `paymentType`='{$paymentType}',`onetime_price`='{$onetime_price}'";
            
            $result=$this->execute_query($querySubscriptEntry);
            $subs_insert_id=$this->insert_id();
            $data=array('patient_subscription_user_subs_id'=> $subs_insert_id,
                'user_id'=>$userInfor['user_id'],
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
           
           // Send email to the Account Admin 
           $this->sendTxmessageAccountAdmin($clinicID,$userInfor,$resultHealthProgram);

           // Send email to the Patient
           $this->sendEmailSignUpPatients($clinic_id,$userInfor,$resultHealthProgram,1);
        
        }
        /**
         * This function returns the subscription information based on clinic id associated with the Patients.
         * @param Integer $clinic_id
         * @return array $subscriptionInfo
         * @access public
         */ 
         
         function show_refund_policy(){
                  $this->output = $this->build_template($this->get_template("show_refund_policy"),$replace);
         }        
        
        /**
         * This function returns the subscription information based on clinic id associated with the Patients.
         * @param Integer $clinic_id
         * @return array $subscriptionInfo
         * @access public
         */      
           
        function subscriptionInfo($clinic_id){
                 
                $querySubs="select clinic_subscription.*,clinic.* from health_settings,clinic_subscription,clinic where 
                                    health_settings.setting_id=clinic_subscription.setting_id AND clinic.clinic_id = 
                                    health_settings.setting_clinic_id AND clinic.clinic_id='{$clinic_id}' AND clinic_subscription.subs_status='1'";
                 $resultSubs = $this->execute_query($querySubs);
                 $resultSubsArray=$this->fetch_array($resultSubs);
                 return $resultSubsArray;                 
        }  
              

         /**
         * This function sends Extenal message:(Patient Name) has signed up for (E-Health Service Name)  TO Account Admins
         * @param Integer $clinic_id
         * @param Integer $user_id
         * @param String  $serviceName
         * @return void
         * @access public
         */
         
         public function sendTxmessageAccountAdmin($clinic_id,$userInfor,$resultHealthProgram){
                    // Query To get the account admins from patient clinic

                    $clinicName=html_entity_decode($this->get_clinic_info($userInfor['user_id'],"clinic_name"), ENT_QUOTES, "UTF-8");

                    $queryAccountAdmin="select user.* from clinic_user,user where clinic_id = '{$clinic_id}' AND clinic_user.user_id=user.user_id AND user.admin_access=1 AND usertype_id= 2";
                    
                    $resultAccountAdmin=$this->execute_query($queryAccountAdmin);
                    //Send Email To Account Admins 
                    while($ObjresultAccountAdmin=$this->fetch_object($resultAccountAdmin)){
                    $patName=$userInfor['name_first'].' '.$userInfor['name_last'];
                          // Send External Email Message
                          $SubjectLine=html_entity_decode($patName, ENT_QUOTES, "UTF-8")." has signed up for ".html_entity_decode($resultHealthProgram->subs_title, ENT_QUOTES, "UTF-8");
                          $to = $ObjresultAccountAdmin->username;
                          // To send HTML mail, the Content-type header must be set
                          $headers  = 'MIME-Version: 1.0' . "\n";
                          $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                          //$headers .= "From: <support@txxchange.com>" . "\n";
                          //$returnpath = '-fsupport@txxchange.com';
                    
						$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
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
        
         public function sendEmailSignUpPatients($clinic_id,$userInfor,$resultHealthProgram,$paymenttype){

                    $clinicName=html_entity_decode($this->get_clinic_info($userInfor['user_id'],"clinic_name"), ENT_QUOTES, "UTF-8");

                    // Send Email To Patients 
                    $replacePatient['images_url']=$this->config['images_url'];
                    $Patientsubject = html_entity_decode($resultHealthProgram->subs_title, ENT_QUOTES, "UTF-8")." Sign-up Confirmation";
                    $replacePatient['ServiceName']=html_entity_decode($resultHealthProgram->subs_title, ENT_QUOTES, "UTF-8");
                    $replacePatient['ClinicName']=$clinicName;
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
                    
                    if($resultHealthProgram->ehsTimePeriod==1)
                    $month="month";
                    else
                    $month="months";
                    $replacePatient['monthly']=$resultHealthProgram->ehsTimePeriod.' '.$month;
                    
                    $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
                    $check_sql_mail_template=" select * from clinic_ehs_mail_template where clinic_id = '{$this->clinicInfo('clinic_id')}'";
		$result = $this->execute_query($check_sql_mail_template);
		if($this->num_rows($result)>0){
			$row=$this->fetch_object($result);
			$Patientmessage=$row->mailcontent;
			$Patientmessage=str_replace('{Clinic Logo}',$this->cliniclogo($this->clinicInfo('clinic_id')), $Patientmessage);
			$Patientmessage=str_replace('{First Name}', $this->userInfo('name_first'), $Patientmessage);
			$Patientmessage=str_replace('{EHS Name}', $replacePatient['ServiceName'], $Patientmessage);
			$Patientsubject = html_entity_decode($row->subject, ENT_QUOTES, "UTF-8");
		}
		else
		{
			
			// Content Of the email send to  patient
			if($clinic_channel==2){
				if($paymenttype==0){
					$Patientmessage = $this->build_template($this->get_template("PatientUserMailContentwx"),$replacePatient);
				}else{
					$Patientmessage = $this->build_template($this->get_template("PatientUserMailContentwxOnetime"),$replacePatient);
				}
			}else{
				if($paymenttype==0){
					$Patientmessage = $this->build_template($this->get_template("PatientUserMailContent"),$replacePatient);
				}else{
					$Patientmessage = $this->build_template($this->get_template("PatientUserMailContentOnetime"),$replacePatient);
				}
					
			}
			
			//$Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8")." <support@txxchange.com>" . "\n";
			//$Patientreturnpath = '-fsupport@txxchange.com';
			//echo $Patientmessage;die;
			

			
		}
		$Patientto = $userInfor['username'];
		// To send HTML mail, the Content-type header must be set
		
		$Patientheaders  = 'MIME-Version: 1.0' . "\n";
		$Patientheaders .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
		if( $clinic_channel == 1){
			$Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8"). " <do-not-reply@txxchange.com>" . "\n";
			$Patientreturnpath = "-fdo-not-reply@txxchange.com";
		}else{
			$Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8"). " <do-not-reply@txxchange.com>" . "\n";
			$Patientreturnpath = '-fdo-not-reply@txxchange.com';
		}
		// Mail it
		@mail($Patientto, $Patientsubject, $Patientmessage, $Patientheaders, $Patientreturnpath);
	}      
 
         /**
         * This function sends Extenal mail when Payment is processed from paypal:(E-Health Service Name) Monthly Charge To Patients
         * @param Integer $clinic_id
         * @param Integer $user_id
         * @param Object  $resultHealthProgram
         * @return void
         * @access public
         */
        
         public function sendEmailPaymentPatients($clinic_id,$userInfor,$resultHealthProgram){

                    $clinicName=html_entity_decode($this->get_clinic_info($userInfor['user_id'],"clinic_name"), ENT_QUOTES, "UTF-8");

                    // Send Email To Patients 
                    $replacePatient['images_url']=$this->config['images_url'];
                    $Patientsubject = html_entity_decode($resultHealthProgram['subscription_title'], ENT_QUOTES, "UTF-8")." Monthly Charge";
                    $replacePatient['ServiceName']=html_entity_decode($resultHealthProgram['subscription_title'], ENT_QUOTES, "UTF-8");
                    $replacePatient['ClinicName']=$clinicName;
                    $replacePatient['price']='\$'.$resultHealthProgram['subs_price'];

                    // Content Of the email send to  patient
                    $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
                    if( $clinic_channel == 1)
                    $Patientmessage = $this->build_template($this->get_template("PatientUserPaymentMailContent"),$replacePatient);
                    else
                    $Patientmessage = $this->build_template($this->get_template("PatientUserPaymentMailContentwx"),$replacePatient);
                    $Patientto = $userInfor['username'];
                    // To send HTML mail, the Content-type header must be set

                    $Patientheaders  = 'MIME-Version: 1.0' . "\n";
                    $Patientheaders .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                    //$Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8")." <support@txxchange.com>" . "\n";
                    //$Patientreturnpath = '-fsupport@txxchange.com';

                    // Mail it
                        if( $clinic_channel == 1){
                        $Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8"). " <".$this->config['email_tx'].">" . "\n";
                        $Patientreturnpath = "-f".$this->config['email_tx'];
                        }else{
                        $Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8"). " <".$this->config['email_wx'].">" . "\n";
                        $Patientreturnpath = '-f'.$this->config['email_wx'];   
                        }
                    @mail($Patientto, $Patientsubject, $Patientmessage, $Patientheaders, $Patientreturnpath);                    
        }    

         /**
         * This function sends Extenal mail when profile is cancelled from paypal: (E-Health Service Name) Cancellation Confirmation To Patients
         * @param Integer $clinic_id
         * @param Integer $user_id
         * @param Object  $resultHealthProgram
         * @return void
         * @access public
         */
        
          public function sendEmailUnsubscribePatients($clinic_id,$userInfor,$resultHealthProgram){

                    $clinicName=html_entity_decode($this->get_clinic_info($userInfor['user_id'],"clinic_name"), ENT_QUOTES, "UTF-8");

                    // Send Email To Patients 
                    $replacePatient['images_url']=$this->config['images_url'];
                    $Patientsubject = html_entity_decode($resultHealthProgram['subscription_title'], ENT_QUOTES, "UTF-8")." Cancellation Confirmation";
                    $replacePatient['ServiceName']=html_entity_decode($resultHealthProgram['subscription_title'], ENT_QUOTES, "UTF-8");
                    $replacePatient['ClinicName']=$clinicName;
                    $replacePatient['price']='$'.$resultHealthProgram['subs_price'];

                    // Content Of the email send to  patient
                    $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
                    if( $clinic_channel == 1)
                    $Patientmessage = $this->build_template($this->get_template("PatientUserUnsubscribeMailContent"),$replacePatient);
                    else
                    $Patientmessage = $this->build_template($this->get_template("PatientUserUnsubscribeMailContentwx"),$replacePatient);
                    $Patientto = $userInfor['username'];
                    // To send HTML mail, the Content-type header must be set

                    $Patientheaders  = 'MIME-Version: 1.0' . "\n";
                    $Patientheaders .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                   // $Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8")." <support@txxchange.com>" . "\n";
                    //$Patientreturnpath = '-fsupport@txxchange.com';
                        if( $clinic_channel == 1){
                        $Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8"). " <".$this->config['email_tx'].">" . "\n";
                        $Patientreturnpath = "-f".$this->config['email_tx'];
                        }else{
                        $Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8"). " <".$this->config['email_wx'].">" . "\n";
                        $Patientreturnpath = '-f'.$this->config['email_wx'];   
                        }

                    // Mail it
                    @mail($Patientto, $Patientsubject, $Patientmessage, $Patientheaders, $Patientreturnpath);                    
        }              
        
         // BackUp Function  Internal TX Message
        /**
         * This function sends TX message:(Patient Name) has signed up for (E-Health Service Name)
         * @param Integer $clinic_id
         * @param Integer $user_id
         * @param String  $serviceName
         * @return void
         * @access public
         */
         
         public function sendTxmessageAccountAdmin_BK($clinic_id,$userInfor,$serviceName){
                    // Query To get the account admins from patient clinic
                    $queryAccountAdmin="select user.* from clinic_user,user where clinic_id = '{$clinic_id}' AND clinic_user.user_id=user.user_id AND user.admin_access=1 AND usertype_id= 2";
                    $resultAccountAdmin=$this->execute_query($queryAccountAdmin);
                    $patientCounter=0;
                    while($ObjresultAccountAdmin=$this->fetch_object($resultAccountAdmin)){
                    $patName=$userInfor['name_first'].' '.$userInfor['name_last'];
                         $subject=$patName." has signed up for ".$serviceName;   
                         // Send TX Message
                     $data = array(
                         'patient_id' => $userInfor['user_id'],
                         'sender_id' => $userInfor['user_id'],
                         'subject' => $this->encrypt_data($subject),
                         'content' => $this->encrypt_data($subject),
                         'parent_id' => '0',
                         'sent_date' => date('Y-m-d H:i:s',time()),
                        'recent_date' => date('Y-m-d H:i:s',time()),
                        'replies' => '0'
                     );
                     
                    // echo $this->decrypt_data($this->encrypt_data($subject));                                                                            
                     
                     if($this->insert("message",$data))
                      {
                             $message_id = $this->insert_id();
                             // Entry for Account Admin
                             $data = array(
                                 'message_id' => $message_id,
                                 'user_id' => $ObjresultAccountAdmin->user_id,
                                 'unread_message' => '1'
                             );
                             $this->insert("message_user",$data);
                             
                         
                         // Entry for Patient
                         if($patientCounter++ < 1) {     
                         $data = array(
                                 'message_id' => $message_id,
                                 'user_id' => $userInfor['user_id'],
                                 'unread_message' => '2'
                         );
                         $this->insert("message_user",$data);                             
                        }     
                      }
                      
                      
                        
                    }
                    
        }

         /**
         * This function unsubscribes(discontinues ) the user from the Health Services
         * @access public
         */
         
         public function unsubscribePatientServices(){
                            $subscrp_id_pk=$this->value('subscrp_id');             
                            $userInfor=$this->userInfo();
                            $clinicID=$this->clinicInfo('clinic_id');                    
                            //echo "XXXXXX $subscrp_id_pk";
                            /*
                            $userId = $userInfo['user_id'];
                            $clinicName    =    $this->get_clinic_info($userId,"clinic_name");
                            $replace['subscrp_id']=$_REQUEST['subscrp_id'];
                            $replace['body'] = $this->build_template($this->get_template("submitPatientUnsubscription"),$replace);
                            echo $this->output = $this->build_template($this->get_template("main"),$replace);   
                            die();
                           */        
                           // Query To get Subscription Details of the patient
                           $queryGetSubsDetails="select patient_subscription.user_id user_id,clinic_subscription.subs_title subs_title,patient_payment.payment_paypal_profile payment_paypal_profile from patient_subscription,clinic_subscription,patient_payment where patient_payment.patient_subscription_user_subs_id=patient_subscription.user_subs_id AND patient_subscription.clinic_id=clinic_subscription.subs_clinic_id AND patient_subscription.user_subs_id = '{$subscrp_id_pk}' AND patient_subscription.user_id='{$userInfor['user_id']}' ";
                           $resultSubs=$this->execute_query($queryGetSubsDetails);
                           
                           // Check If user has selected any subscription to unsubscribe
                           if($this->num_rows($resultSubs)>0) {
                             
                           $resultGetSubsDetails=$this->fetch_object($resultSubs);

                           $user_id=$resultGetSubsDetails->user_id;
                           $Paypal_Profile=trim($resultGetSubsDetails->payment_paypal_profile);
                           $subscriptionname=$resultGetSubsDetails->subs_title;
                           
                           
                                // Paypal Recurring Object
                                $API_UserName=urlencode($this->config["paypalprodetails"]["API_UserName"]);
                                $API_Password=urlencode($this->config["paypalprodetails"]["API_Password"]);
                                $API_Signature=urlencode($this->config["paypalprodetails"]["API_Signature"]);
                                $environment=urlencode($this->config["paypalprodetails"]["environment"]);
                                $currencyID=urlencode($this->config["paypalprodetails"]["currencyID"]);
                                // Paypal Object
                                $paypalObjectCancel=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);                           
                                
                                $profileID=urlencode($Paypal_Profile);
                                
                                //echo "###".$Paypal_Profile."###".$profileID.'<br>';
                                
                                $action=urlencode("Cancel");
                                $note=urlencode("Cancel Subscription for ".$subscriptionname);
                                $nvpStr="&PROFILEID=$profileID&ACTION=$action&NOTE=$note";
                                $paypalObjectCancel=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                                
                                //echo $nvpStr;  die();
                                // API Call To Paypal to Cancel Recurring Profile
                                
                                //echo "IN ".$nvpStr; die();  
                                
                                $httpParsedResponseAr = $paypalObjectCancel->PPHttpPost('ManageRecurringPaymentsProfileStatus', $nvpStr);
                                
                                // Code to fill history Log of Profile creation
                                $userInfor=$this->userInfo();
                                $this->paypalHistory($httpParsedResponseAr,$userInfor['user_id'],$Paypal_Profile,'unsubscribe');
                                
                                
                                
                                //echo "<pre>"; print_r($httpParsedResponseAr); echo "</pre>";
                                //$httpParsedResponseAr['ACK']='success';                        
                                if(strtolower($httpParsedResponseAr['ACK'])=='success'){                         
                                                          
                                        $querySubscriptionDetails="update patient_subscription set subs_status='2' where user_subs_id='{$subscrp_id_pk}'";
                                        $this->execute_query($querySubscriptionDetails);
                                        $querySubscriptionPaymentDetails="update patient_payment set paymnet_status='0' where patient_subscription_user_subs_id='{$subscrp_id_pk}' AND payment_paypal_profile='{$Paypal_Profile}' ";
                                        $this->execute_query($querySubscriptionPaymentDetails);
                                
                                        //echo "INSIDE";
                                        $userInfo = $this->userInfo();
                                        //print_r($userInfo); 
                                        $userId = $userInfo['user_id'];
                                        //$clinicName    =    $this->get_clinic_info($userId,"clinic_name");
                                        $clinicId    =    $this->get_clinic_info($userId,"clinic_id");
                                        $subscriptionDetail=$this->getPatientSubscriptionDetails($userId,$clinicId);
                                      //print_r($subscriptionDetail);
                                        $replace['subscrp_id']=$_REQUEST['subscrp_id'];
                                         
                                        //Send Email To Patient About Cancellation.  
                                        $resultHealthProgram['subscription_title']=$subscriptionDetail['subscription_title'];           //Service Name
                                        $resultHealthProgram['subs_price']=$subscriptionDetail['subs_price'];                           //Service Price
                                        $clinic_id=$subscriptionDetail['clinic_id'];                                                    //Clinic ID
                                        $userInfor['user_id']=$user_id;
                                        $userInfor['username']=$this->userInfo('username',$user_id);
                                        
                                        $this->sendEmailUnsubscribePatients($clinic_id,$userInfor,$resultHealthProgram);

                                        
                                        //$replace['clinicName']=$clinicName;
                                        //Original Text " You are no longer subscribing to <!clinicName>'s E-Health Service. Your credit card will not be charged at the end of the current billing cycle."
                                        $replace['ServiceName']=$subscriptionDetail['subscription_title'];
                                        $replace['body'] = $this->build_template($this->get_template("submitPatientUnsubscription"),$replace);
                                        $this->output = $this->build_template($this->get_template("main"),$replace);                                
                                        
                                }  else {
                                
                                        //Block If there is any problem with the Profile ID and Unsubscription could not be completed
                                        $userInfo = $this->userInfo();
                                        //print_r($userInfo); 
                                        $userId = $userInfo['user_id'];
                                        //$clinicName    =    $this->get_clinic_info($userId,"clinic_name");
                                        $clinicId    =    $this->get_clinic_info($userId,"clinic_id");
                                        $subscriptionDetail=$this->getPatientSubscriptionDetails($userId,$clinicId);
                                        $replace['subscrp_id']=$_REQUEST['subscrp_id'];
                                        
                                        //$replace['clinicName']=$clinicName;
                                        //Original Text " You are no longer subscribing to <!clinicName>'s E-Health Service. Your credit card will not be charged at the end of the current billing cycle."
                                        $replace['ServiceName']=$subscriptionDetail['subscription_title'];
                                        echo  $replace['body'] = $this->build_template($this->get_template("submitPatientNotUnsubscription"),$replace);
                                       // $this->output = $this->build_template($this->get_template("main"),$replace);                                    
                                }
                           
                           }else{

                                        //Block when the payment is not made but the profile is completed.Pateint cannot unsubscribe
                                        $userInfo = $this->userInfo();
                                        //print_r($userInfo); 
                                        $userId = $userInfo['user_id'];
                                        //$clinicName    =    $this->get_clinic_info($userId,"clinic_name");
                                        $clinicId    =    $this->get_clinic_info($userId,"clinic_id");
                                        $subscriptionDetail=$this->getPatientSubscriptionDetails($userId,$clinicId);
                                        $replace['subscrp_id']=$_REQUEST['subscrp_id'];
                                        
                                        //$replace['clinicName']=$clinicName;
                                        //Original Text " You are no longer subscribing to <!clinicName>'s E-Health Service. Your credit card will not be charged at the end of the current billing cycle."
                                        $replace['ServiceName']=$subscriptionDetail['subscription_title'];
                                        echo  $replace['body'] = $this->build_template($this->get_template("submitPatientNotPaidUnsubscription"),$replace);
                                       // $this->output = $this->build_template($this->get_template("main"),$replace);                                   
                           }   
                         
                         
         } 

        /**
         * This function unsubscribes(discontinues ) the user from the Health Services
         * @access public
         * Paypal subscription failure
         * Shail
         */
         
         public function unsubscribePatientServicesEHS(){
                            $subscrp_id_pk = $this->value('subscrp_id'); 
                            $userId = $this->value('userId'); 
                            $clinicID = $this->value('clinicId');  
                            //$userId= '113531'; testing purpose          
                            //echo "XXXXXX $subscrp_id_pk";
                            $userInfor = array();
                            $ehsAction = $_REQUEST['ehsAction'];
                            $replace['ehsAction'] = $ehsAction;

                            $replace['userId'] = $this->value('userId');
                            $replace['clinicId']=$_REQUEST['clinicId'];                            
     
                           // Query To get Subscription Details of the patient
                           $queryGetSubsDetails="select patient_subscription.user_id user_id,clinic_subscription.subs_title subs_title,patient_payment.payment_paypal_profile payment_paypal_profile from patient_subscription,clinic_subscription,patient_payment where patient_payment.patient_subscription_user_subs_id=patient_subscription.user_subs_id AND patient_subscription.clinic_id=clinic_subscription.subs_clinic_id AND patient_subscription.user_subs_id = '{$subscrp_id_pk}' AND patient_subscription.user_id='{$userId}' ";
                           $resultSubs=$this->execute_query($queryGetSubsDetails);
                           
                           // Check If user has selected any subscription to unsubscribe
                           if($this->num_rows($resultSubs)>0) {
                             
                                   $resultGetSubsDetails = $this->fetch_object($resultSubs);
                                  
                                   $user_id = $resultGetSubsDetails->user_id;
                                   $Paypal_Profile = trim($resultGetSubsDetails->payment_paypal_profile);
                                   $subscriptionname = $resultGetSubsDetails->subs_title;
                           
                                // Paypal Recurring Object
                                $API_UserName=urlencode($this->config["paypalprodetails"]["API_UserName"]);
                                $API_Password=urlencode($this->config["paypalprodetails"]["API_Password"]);
                                $API_Signature=urlencode($this->config["paypalprodetails"]["API_Signature"]);
                                $environment=urlencode($this->config["paypalprodetails"]["environment"]);
                                $currencyID=urlencode($this->config["paypalprodetails"]["currencyID"]);
                                // Paypal Object
                                $paypalObjectCancel=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);                           
                                
                                $profileID=urlencode($Paypal_Profile);
                                
                                //echo "###".$Paypal_Profile."###".$profileID.'<br>';
                                
                                $action=urlencode("Cancel");
                                $note=urlencode("Cancel Subscription for ".$subscriptionname);
                                $nvpStr="&PROFILEID=$profileID&ACTION=$action&NOTE=$note";
                                $paypalObjectCancel=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                                
                                //echo $nvpStr;  die();
                                // API Call To Paypal to Cancel Recurring Profile
                                
                                //echo "IN ".$nvpStr; die();  
                                
                                $httpParsedResponseAr = $paypalObjectCancel->PPHttpPost('ManageRecurringPaymentsProfileStatus', $nvpStr);
                                
                                // Code to fill history Log of Profile creation
                                //$userInfor=$this->userInfo();
                                $this->paypalHistory($httpParsedResponseAr,$userId,$Paypal_Profile,'unsubscribe');
                                
                               
                                
                                //echo "<pre>"; print_r($httpParsedResponseAr); echo "</pre>";
                                //$httpParsedResponseAr['ACK']='success';                        
                                if(strtolower($httpParsedResponseAr['ACK'])=='success'){  
                                        //Ehs 
                                        $queryEhs ="update user set ehs = '0' where user_id  = '{$userId}'";                   
                                        $this->execute_query($queryEhs);
                                        //end here
                                        $querySubscriptionDetails="update patient_subscription set subs_status='2' where user_subs_id='{$subscrp_id_pk}'";
                                        $this->execute_query($querySubscriptionDetails);
                                        $querySubscriptionPaymentDetails="update patient_payment set paymnet_status='0' where patient_subscription_user_subs_id='{$subscrp_id_pk}' AND payment_paypal_profile='{$Paypal_Profile}' ";
                                        $this->execute_query($querySubscriptionPaymentDetails);
                                
                                        //echo "INSIDE";
                                       // $userInfo = $this->userInfo();
                                        //print_r($userInfo); 
                                        //$userId = $userInfo['user_id'];
                                        //$clinicName    =    $this->get_clinic_info($userId,"clinic_name");
                                        $clinicId    =    $this->get_clinic_info($userId,"clinic_id");
                                        $subscriptionDetail=$this->getPatientSubscriptionDetails($userId,$clinicId);
                                        //print_r($subscriptionDetail);
                                        $replace['subscrp_id']=$_REQUEST['subscrp_id'];
                                         
                                        //Send Email To Patient About Cancellation.  
                                        $resultHealthProgram['subscription_title']=$subscriptionDetail['subscription_title'];           //Service Name
                                        $resultHealthProgram['subs_price']=$subscriptionDetail['subs_price'];                           //Service Price
                                        $clinic_id=$subscriptionDetail['clinic_id'];                                                    //Clinic ID
                                        //$userInfor['user_id']=$user_id;
                                        //$userInfor['username']=$this->userInfo('username',$user_id);
                                        //shailesh
                                        $userInfor['user_id'] =  $userId;
                                        $userInfor['username']=$this->userInfo('username', $userId);                                        
                                         //Ende here
                                        $this->sendEmailUnsubscribePatients($clinic_id,$userInfor,$resultHealthProgram);

                                        
                                        //$replace['clinicName']=$clinicName;
                                        //Original Text " You are no longer subscribing to <!clinicName>'s E-Health Service. Your credit card will not be charged at the end of the current billing cycle."
                                        $replace['ServiceName']=$subscriptionDetail['subscription_title'];
                                        $replace['body'] = $this->build_template($this->get_template("submitPatientUnsubscription_admin"),$replace);
                                        $this->output = $this->build_template($this->get_template("main"),$replace);                                
                                        
                                }  else {
                                
                                        //Block If there is any problem with the Profile ID and Unsubscription could not be completed
                                        //$userInfo = $this->userInfo();
                                        //print_r($userInfo); 
                                       // $userId = $userInfo['user_id'];//shail
                                        //$clinicName    =    $this->get_clinic_info($userId,"clinic_name");
                                        $clinicId    =    $this->get_clinic_info($userId,"clinic_id");
                                        $subscriptionDetail=$this->getPatientSubscriptionDetails($userId,$clinicId);
                                        $replace['subscrp_id']=$_REQUEST['subscrp_id'];


                                        //$replace['clinicName']=$clinicName;
                                        //Original Text " You are no longer subscribing to <!clinicName>'s E-Health Service. Your credit card will not be charged at the end of the current billing cycle."
                                        $replace['ServiceName']=$subscriptionDetail['subscription_title'];
                                        echo  $replace['body'] = $this->build_template($this->get_template("submitPatientNotUnsubscription_admin"),$replace);
                                       // $this->output = $this->build_template($this->get_template("main"),$replace);                                    
                                }
                           
                           }else{

                                        //Block when the payment is not made but the profile is completed.Pateint cannot unsubscribe
                                        //$userInfo = $this->userInfo();
                                        //print_r($userInfo); 
                                        ///$userId = $userInfo['user_id'];
                                        //$clinicName    =    $this->get_clinic_info($userId,"clinic_name");
                                        $clinicId    =    $this->get_clinic_info($userId,"clinic_id");
                                        $subscriptionDetail=$this->getPatientSubscriptionDetails($userId,$clinicId);
                                        $replace['subscrp_id']=$_REQUEST['subscrp_id'];

                                        //$replace['clinicName']=$clinicName;
                                        //Original Text " You are no longer subscribing to <!clinicName>'s E-Health Service. Your credit card will not be charged at the end of the current billing cycle."
                                        $replace['ServiceName']=$subscriptionDetail['subscription_title'];
                                        echo  $replace['body'] = $this->build_template($this->get_template("submitPatientNotPaidUnsubscription_admin"),$replace);
                                       // $this->output = $this->build_template($this->get_template("main"),$replace);                                   
                           }   
                         
                         
         } 

        
        
        /**
         * This is used to update the databse when payment is made from paypal and Paypal Server send an IPN Request.
         * @access public
         */        
        
         public function ipnPaymentPatientServices(){
                // Fetch the request variables.
                $requestData=$_REQUEST;

                   // Log For IPN Request
                   $fp=fopen("asset/ipnLogFiles/".$requestData['recurring_payment_id'].time().".txt","w+");
                   fwrite($fp,date('Y-m-d H:m')." ############################################# "."\n");
                   foreach($requestData as $key=>$value){   
                            fwrite($fp,$key."=".$value."\n");
                   } 
                   fclose($fp);

                // Code to fill history Log of Profile creation
                $userInfor=$this->userInfo();
                $this->paypalHistory($requestData,'',trim(urldecode($requestData['recurring_payment_id'])),'payment');

                // Fetch the Paymnet Status.
                $payment_status=urldecode($requestData['payment_status']);
                
                // Code For txn_type
                $payment_txn_type=urldecode($requestData['txn_type']);
                
                // Fetch the Profile ID assocuiated.
                $Paypal_Profile_Id=urldecode($requestData['recurring_payment_id']);

                // Fetch The Payment.
                $next_payment_date=urldecode($requestData['next_payment_date']);
                $next_payment_date=date('Y-m-d H:i:s',strtotime($next_payment_date));
                $payment_price=$amountPaid=$requestData['amount'];
                $paymnet_b_email=$buyerEmail=$requestData['payer_email'];
                $payment_b_name=$requestData['first_name'].' '.$requestData['last_name'];
                // Block To check Sucessfull Paymnet Made from Paypal
                if(strtolower($payment_status)=='completed'){
                //Query To fetch the Profile Data
                $querySelectSubscription=$this->execute_query("select * from patient_subscription where payment_paypal_profile = '{$Paypal_Profile_Id}' ");
                if($this->num_rows($querySelectSubscription)>0){
                    $resultSelectSubscription=$this->fetch_object($querySelectSubscription);
                    $user_id=$resultSelectSubscription->user_id;
                    $patient_subscription_user_subs_id=$resultSelectSubscription->user_subs_id;     //user_subs_id
                    $user_subs_id=$resultSelectSubscription->user_subs_id;                          //user_subs_id
                    $payment_price=$resultSelectSubscription->subs_price;                          //user_subs_id
                    
                    //Send Email To Patient About the Payment.
                    
                    // Check Top stop the payment email for the first time                    
                    $checkQuery=$this->execute_query("select count(user_id) as CNT  from patient_payment where payment_paypal_profile = '{$Paypal_Profile_Id}'  AND user_id='{$user_id}' ");
                    $resultCheckQuery=$this->fetch_object($checkQuery);
                    if($resultCheckQuery->CNT > 0 )       {
                            $resultHealthProgram['subscription_title']=$resultSelectSubscription->subscription_title;           //Service Name
                            $resultHealthProgram['subs_price']=$payment_price;                                                  //Service Price
                            $clinic_id=$subscriptionDetail->clinic_id;                                                          //Clinic ID
                            $userInfor['user_id']=$user_id;
                            $userInfor['username']=$this->userInfo('username',$user_id);
                            $this->sendEmailPaymentPatients($clinic_id,$userInfor,$resultHealthProgram);
                    
                    }

                        // Query To update the Patient Payment databse Table
                        $updatePaymnetDatabase=$this->execute_query("update patient_payment set paymnet_status = '0' where payment_paypal_profile= '{$Paypal_Profile_Id}' ");
                        // Change in query to add txn_type
                        $updatePaymnetDatabase=$this->execute_query("insert into patient_payment set payment_paypal_profile = '{$Paypal_Profile_Id}', user_id='{$user_id}',patient_subscription_user_subs_id='{$patient_subscription_user_subs_id}',user_subs_id='{$user_subs_id}',payment_b_name='{$payment_b_name}',paymnet_b_email='{$paymnet_b_email}',payment_price='{$payment_price}' ,paymnet_status= '1',paymnet_datetime=now() , payment_txn_type='{$payment_txn_type}' ");
                        //$updatePaymnetDatabase=$this->execute_query("insert into patient_payment set payment_paypal_profile = '{$Paypal_Profile_Id}', user_id='{$user_id}',patient_subscription_user_subs_id='{$patient_subscription_user_subs_id}',user_subs_id='{$user_subs_id}',payment_b_name='{$payment_b_name}',paymnet_b_email='{$paymnet_b_email}',payment_price='{$payment_price}' ,paymnet_status= '1',paymnet_datetime=now() ");
                        // Query To update Patient Subscription Table
                        $updatePatientSubscriptionDatabase=$this->execute_query("update patient_subscription set subs_status='1',subs_datetime=now(),subs_end_datetime='{$next_payment_date}' where user_subs_id='{$patient_subscription_user_subs_id}' ");

                        }
                }/*elseif(strtolower($payment_status)!=''){
                // Block to update status when payment not sucessfull
                $querySelectSubscription=$this->execute_query("select * from patient_subscription where payment_paypal_profile = '{$Paypal_Profile_Id}' ");
                if($this->num_rows($querySelectSubscription)>0){
                        $resultSelectSubscription=$this->fetch_object($querySelectSubscription);
                        $patient_subscription_user_subs_id=$resultSelectSubscription->user_subs_id;
                        // Query To update Patient Subscription Table
                        $updatePatientSubscriptionDatabase=$this->execute_query("update patient_subscription set subs_status='2' where user_subs_id='{$patient_subscription_user_subs_id}' ");
                       }                    
                }  */
                
                    // Changes for implementing txn_type
                else{
                        // Changes for implementing txn_type ,  Max Attempts made
                        if($payment_txn_type=='recurring_payment_suspended_due_to_max_failed_payment' || $payment_txn_type=='recurring_payment_failed'){
                        	
                            // Block to update status when payment not sucessfull after three attempts
                            $querySelectSubscription=$this->execute_query("select * from patient_subscription where payment_paypal_profile = '{$Paypal_Profile_Id}' ");
                            if($this->num_rows($querySelectSubscription)>0){
                                $resultSelectSubscription=$this->fetch_object($querySelectSubscription);
                                $user_id=$resultSelectSubscription->user_id;                                                        //user_id
                                $patient_subscription_user_subs_id=$resultSelectSubscription->user_subs_id;   //user_subs_id
                                $user_subs_id=$resultSelectSubscription->user_subs_id;  
                                // Query To update Patient Subscription Table
                                $updatePatientSubscriptionDatabase=$this->execute_query("update patient_subscription set subs_status='3' where user_subs_id='{$patient_subscription_user_subs_id}' ");
                                //send a mail for supended user
      			    			$clinicName=html_entity_decode($this->get_clinic_info($resultSelectSubscription->user_id,"clinic_name"), ENT_QUOTES, "UTF-8");
                    			$replacePatient['images_url']=$this->config['images_url'];
                    			$Patientsubject = 'Message Regarding Your '.html_entity_decode($resultSelectSubscription->subscription_title, ENT_QUOTES, "UTF-8")." Status";
                   				$replacePatient['ServiceName']=html_entity_decode($resultSelectSubscription->subscription_title, ENT_QUOTES, "UTF-8");
                    			$replacePatient['clinicName']=$clinicName;
	                    		// Content Of the email send to  patient
                    			$clinic_channel=$this->getchannel($resultSelectSubscription->clinic_id);
                    			if( $clinic_channel == 2)
                    				$Patientmessage = $this->build_template($this->get_template("PatientprofileSuspendedwx"),$replacePatient);
                    			else
                    				$Patientmessage = $this->build_template($this->get_template("PatientprofileSuspendedtx"),$replacePatient);
                    			$userInfor['username']=$this->userInfo('username',$user_id);
                    			$Patientto = $userInfor['username'];
                    			// To send HTML mail, the Content-type header must be set

                    			$Patientheaders  = 'MIME-Version: 1.0' . "\n";
                    			$Patientheaders .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                   				if( $clinic_channel == 2){
                        				$Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8"). " <".$this->config['email_wx'].">" . "\n";
                        				$Patientreturnpath = '-f'.$this->config['email_wx']; 	
                   					
                        		}else{
                        			$Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8"). " <".$this->config['email_tx'].">" . "\n";
                        			$Patientreturnpath = "-f".$this->config['email_tx'];  
                        		}
		                    // Mail it
		                    echo $Patientmessage;
        		           @mail($Patientto, $Patientsubject, $Patientmessage, $Patientheaders, $Patientreturnpath); 
                   			}                        
                      }
                }
                if($payment_txn_type=='recurring_payment_skipped'){
                 $payer_email=$requestData['payer_email'];
                 $querySelectSubscription=$this->execute_query("select * from patient_subscription where payment_paypal_profile = '{$Paypal_Profile_Id}' ");
                if($this->num_rows($querySelectSubscription)>0){
                    $resultSelectSubscription=$this->fetch_object($querySelectSubscription);
		    		$clinicName=html_entity_decode($this->get_clinic_info($resultSelectSubscription->user_id,"clinic_name"), ENT_QUOTES, "UTF-8");
                    // Send Email To Patients 
                    $replacePatient['images_url']=$this->config['images_url'];
                    $Patientsubject = "Message Regarding Your ".html_entity_decode($resultSelectSubscription->subscription_title, ENT_QUOTES, "UTF-8")."  Payment";
                    $replacePatient['ServiceName']=html_entity_decode($resultSelectSubscription->subscription_title, ENT_QUOTES, "UTF-8");
                    $replacePatient['clinicName']=$clinicName;
                    $user_id=$resultSelectSubscription->user_id; 
                    // Content Of the email send to  patient
                    $clinic_channel=$this->getchannel($resultSelectSubscription->clinic_id);
                    if( $clinic_channel == 2)
                    	$Patientmessage = $this->build_template($this->get_template("PatientprofileSkipedwx"),$replacePatient);
                    else
                    	$Patientmessage = $this->build_template($this->get_template("PatientprofileSkipedtx"),$replacePatient);
                    
                   $userInfor['username']=$this->userInfo('username',$user_id);
                   $Patientto = $userInfor['username'];
                    // To send HTML mail, the Content-type header must be set

                    $Patientheaders  = 'MIME-Version: 1.0' . "\n";
                    $Patientheaders .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                   // $Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8")." <support@txxchange.com>" . "\n";
                    //$Patientreturnpath = '-fsupport@txxchange.com';
                        if( $clinic_channel == 1){
                        $Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8"). " <".$this->config['email_tx'].">" . "\n";
                        $Patientreturnpath = "-f".$this->config['email_tx'];
                        }else{
                        $Patientheaders .= "From: ".html_entity_decode($clinicName, ENT_QUOTES, "UTF-8"). " <".$this->config['email_wx'].">" . "\n";
                        $Patientreturnpath = '-f'.$this->config['email_wx'];   
                        }
 					echo $Patientmessage;
                    // Mail it
                    @mail($Patientto, $Patientsubject, $Patientmessage, $Patientheaders, $Patientreturnpath); 
                    }
                	
                }
                
         }        

        /**
        * This is used to update the databse when through simulator request.
        * @access public
        */
                 
         public function SimilatoripnPaymentPatientServices(){
                // Fetch the request variables.
                $requestData=$_REQUEST;

                // Code to fill history Log of Profile creation
                $userInfor=$this->userInfo();
                $this->paypalHistory($requestData,'',trim(urldecode($requestData['recurring_payment_id'])),'payment');

                
                
                // Fetch the Paymnet Status.
                $payment_status=urldecode($requestData['payment_status']);
                
                // Changes for txn_type
                $payment_txn_type=urldecode($requestData['txn_type']);
                
                
                // Fetch the Profile ID assocuiated.
                $Paypal_Profile_Id=urldecode($requestData['recurring_payment_id']);
                // Fetch The Payment.
                //$next_payment_date=urldecode($requestData['next_payment_date']);
                
                $Frequency=urlencode($this->config["paypalprodetails"]["billingPeriod"]);
                $billingFreq=urlencode($this->config["paypalprodetails"]["billingFreq"]);
                $payment_price=$amountPaid=$requestData['amount'];
                $paymnet_b_email=$buyerEmail=$requestData['payer_email'];
                $payment_b_name=$requestData['first_name'].' '.$requestData['last_name'];
                                            
                // Block To check Sucessfull Paymnet Made from Paypal
                if(strtolower($payment_status)=='completed'){
                //Query To fetch the Profile Data
                $querySelectSubscription=$this->execute_query("select * from patient_subscription where payment_paypal_profile = '{$Paypal_Profile_Id}' ");
                if($this->num_rows($querySelectSubscription)>0){
                    $resultSelectSubscription=$this->fetch_object($querySelectSubscription);
                    $user_id=$resultSelectSubscription->user_id;                                                        //user_id
                    $patient_subscription_user_subs_id=$resultSelectSubscription->user_subs_id;   //user_subs_id
                    $user_subs_id=$resultSelectSubscription->user_subs_id;
                    $payment_b_name=$resultSelectSubscription->b_first_name.' '.$resultSelectSubscription->b_last_name;
                    $payment_price=$resultSelectSubscription->subs_price;
                    $clinic_id=$resultSelectSubscription->clinic_id;                                //Clinic ID

                    
                    //Send Email To Patient About the Payment.
                    $resultHealthProgram['subscription_title']=$resultSelectSubscription->subscription_title;           //Service Name
                    $resultHealthProgram['subs_price']=$payment_price;                           //Service Price
                    $clinic_id=$subscriptionDetail->clinic_id;                                                    //Clinic ID
                    $userInfor['user_id']=$user_id;
                    $userInfor['username']=$this->userInfo('username',$user_id);
                    
                    $this->sendEmailPaymentPatients($clinic_id,$userInfor,$resultHealthProgram);
                    

                    


                        // Query To update the Patient Payment databse Table
                        $updatePaymnetDatabase=$this->execute_query("update patient_payment set paymnet_status = '0'  where payment_paypal_profile= '{$Paypal_Profile_Id}'  ");
                        
                        // Changes for txn_type
                        $updatePaymnetDatabase=$this->execute_query("insert into patient_payment set payment_paypal_profile = '{$Paypal_Profile_Id}', user_id='{$user_id}',patient_subscription_user_subs_id='{$patient_subscription_user_subs_id}',user_subs_id='{$user_subs_id}',payment_b_name='{$payment_b_name}',paymnet_b_email='{$paymnet_b_email}',payment_price='{$payment_price}' ,paymnet_status= '1',paymnet_datetime=now() , payment_txn_type= '{$payment_txn_type}' ");
                        //$updatePaymnetDatabase=$this->execute_query("insert into patient_payment set payment_paypal_profile = '{$Paypal_Profile_Id}', user_id='{$user_id}',patient_subscription_user_subs_id='{$patient_subscription_user_subs_id}',user_subs_id='{$user_subs_id}',payment_b_name='{$payment_b_name}',paymnet_b_email='{$paymnet_b_email}',payment_price='{$payment_price}' ,paymnet_status= '1',paymnet_datetime=now() ");
                        // Query To update Patient Subscription Table
                        $updatePatientSubscriptionDatabase=$this->execute_query("update patient_subscription set subs_status='1',subs_datetime=now(),subs_end_datetime=DATE_ADD(now(), INTERVAL {$billingFreq} {$Frequency}) where user_subs_id='{$patient_subscription_user_subs_id}' ");

                        }
                }/*elseif(strtolower($payment_status)!=''){
                // Block to update status when payment not sucessfull
                $querySelectSubscription=$this->execute_query("select * from patient_subscription where payment_paypal_profile = '{$Paypal_Profile_Id}' ");
                if($this->num_rows($querySelectSubscription)>0){    
                    $resultSelectSubscription=$this->fetch_object($querySelectSubscription);
                    $user_id=$resultSelectSubscription->user_id;                                                        //user_id
                    $patient_subscription_user_subs_id=$resultSelectSubscription->user_subs_id;   //user_subs_id
                    $user_subs_id=$resultSelectSubscription->user_subs_id;  
                    // Query To update Patient Subscription Table
                        $updatePatientSubscriptionDatabase=$this->execute_query("update patient_subscription set subs_status='2' where user_subs_id='{$patient_subscription_user_subs_id}' ");
                       }                    
                }  */
                
                    // Changes for implementing txn_type
                else{
                        // Changes for implementing txn_type ,  Max Attempts made
                        if($payment_txn_type=='recurring_payment_suspended_due_to_max_failed_payment' || $payment_txn_type=='recurring_payment_failed'){
                            // Block to update status when payment not sucessfull
                            $querySelectSubscription=$this->execute_query("select * from patient_subscription where payment_paypal_profile = '{$Paypal_Profile_Id}' ");
                            if($this->num_rows($querySelectSubscription)>0){
                                $resultSelectSubscription=$this->fetch_object($querySelectSubscription);
                                $user_id=$resultSelectSubscription->user_id;                                                        //user_id
                                $patient_subscription_user_subs_id=$resultSelectSubscription->user_subs_id;   //user_subs_id
                                $user_subs_id=$resultSelectSubscription->user_subs_id;  
                                // Query To update Patient Subscription Table
                                    $updatePatientSubscriptionDatabase=$this->execute_query("update patient_subscription set subs_status='3' where user_subs_id='{$patient_subscription_user_subs_id}' ");
                                   }                        
                            
                        }
                }
                
                
                
         
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
         * Function to Display Page with patient admin contact in case of payment not sucessfull
         * 
         * @access public
         */
        function patient_admin_contact(){
             
            $clinic_channel=$this->value('clinic_channel');
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['footer'] = $this->build_template($this->get_template("footer"));
            
            // Template for the suitable Copy
            $templateCopy="patient_admin_contact_tx";

            //Select Copy based on channel Type
            if($clinic_channel == 1){
                    $templateCopy="patient_admin_contact_tx";
                }elseif($clinic_channel == 2){
                    $templateCopy="patient_admin_contact_wmdx";
                }
            
                
            $replace['adminCopy']=$this->build_template($this->get_template($templateCopy));    
            $replace['body'] = $this->build_template($this->get_template("patient_admin_contact"),$replace);

            $replace['browser_title'] = "Tx Xchange: Patient Admin Contact";
            
            $this->output = $this->build_template($this->get_template("main"),$replace);

        }
        
        /**
         * This is used to get the name of the template file from the config_txxchange.xml
         * for the action request
         *
         * @param String $template
         * @return template page info
         * @access public
         */        
        function get_template($template){

            $login_arr = $this->action_parser($this->action,'template') ;

            $pos =  array_search($template, $login_arr['template']['name']); 

            return $login_arr['template']['path'][$pos];

        }

        
         /**
         * This is used for one time subscription payment
         * for the action request
         *
         * @param String $template
         * @return template page info
         * @access public
         */
        function submit_patient_onetime_subscription()
      {
            //echo "asdfasd"; exit;
            
          // print_r($_REQUEST);
            
            // Paypal Recurring Object
            $API_UserName=urlencode($this->config["paypalprodetails"]["API_UserName"]);
            $API_Password=urlencode($this->config["paypalprodetails"]["API_Password"]);
            $API_Signature=urlencode($this->config["paypalprodetails"]["API_Signature"]);
            $environment=urlencode($this->config["paypalprodetails"]["environment"]);
            $currencyID=urlencode($this->config["paypalprodetails"]["currencyID"]);
            $Frequency=urlencode($this->config["paypalprodetails"]["billingPeriod"]);
            $billingFreq=urlencode($this->config["paypalprodetails"]["billingFreq"]);
            $userInfor=$this->userInfo();
            
            $firstName = $this->value('firstname');
            $lastName = $this->value('lastname');
            $email=$userInfor['username'];
            $creditCardType = $this->value('cardType');
            $creditCardNumber = $this->value("cardNumber");
            $expDateMonth = $this->value('exprMonth');
            $expDateYear = $this->value('exprYear');
            $cvv2Number = $this->value('cvvNumber');
            $address1 = $this->value('address1');
            $address2 = $this->value('address2');
            $city = $this->value('city');
            $state = $this->value('state');
            $zipcode = $this->value('zipcode');
            $country = $this->value('country');
            
            
            
            
            
   
            //Code To Validate User Entered Data
            $error=array();
            if(trim($firstName)=="" ){
                $error['namefValid']="Please enter firstname";   
            }

            if(trim($lastName)==""){
                $error['namelValid']="Please enter lastname";   
            }            
            
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
            if(!empty($error)){
                $messageReturn="&validData=true";
                foreach($error as $key=>$value)   {
                    $messageReturn.="&".$key."=".urlencode($value);
                }
                $_SESSION['pateintSubs']['fname']=$firstName;
                $_SESSION['pateintSubs']['lname']=$lastName;
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
                header("location:index.php?action=patient_subscription".$messageReturn);
                die();
            }
            
            $paymentAmount = urlencode($this->value('cardPayment'));
            $currencyID = urlencode($currencyID);                        // or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
            $startDate = urlencode(date("Y-m-d")."T0:0:0");
            $billingPeriod = urlencode($Frequency);                // or "Day", "Week", "SemiMonth", "Year"
            $billingFreq = urlencode($billingFreq);                        // combination of this and billingPeriod must be at most a year
            $clinicName=html_entity_decode($this->get_clinic_info($userInfor['user_id'],"clinic_name"), ENT_QUOTES, "UTF-8");
            $desc=urlencode($clinicName.' - '.html_entity_decode($this->value('HealthDescription'), ENT_QUOTES, "UTF-8"));
            
            /*$nvpStr="&FIRSTNAME=$firstName
                     &LASTNAME=$lastName
                     &EMAIL=$email
                     &CREDITCARDTYPE=$creditCardType
                     &ACCT=$creditCardNumber
                     &EXPDATE=$expDateMonth$expDateYear
                     &CVV2=$cvv2Number
                     &AMT=$paymentAmount
                     &CURRENCYCODE=$currencyID
                     &PROFILESTARTDATE=$startDate";
            $nvpStr .= "&BILLINGPERIOD=$billingPeriod
                        &BILLINGFREQUENCY=$billingFreq
                        &DESC=$desc";*/
            // For Billing Address
            
                $BillingstreetAddr=urlencode($address1);
                $Billingstreet2Addr=urlencode($address2);
                $BillingCity=urlencode($city);
                $BillingState=urlencode($state);
                $BillingCountry=urlencode($country);
                $Billingzip=urlencode($zipcode);
               /* $nvpStr .= "&STREET=$BillingstreetAddr
                            &STREET2=$Billingstreet2Addr
                            &CITY=$BillingCity
                            &STATE=$BillingState
                            &COUNTRYCODE=$BillingCountry
                            &ZIP=$Billingzip";*/
 
            
            // Code to Limit the MAXFAILEDPAYMENTS
            /* $nvpStr .='&MAXFAILEDPAYMENTS=1';*/

             $nvpstr="&PAYMENTACTION=Sale&AMT=$paymentAmount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=".$expDateMonth.$expDateYear."&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state"."&ZIP=$zipcode&COUNTRYCODE=$BillingCountry&CURRENCYCODE=$currencyID&EMAIL=".$email."&DESC=".$desc;
             
             
            //echo $nvpStr."<br>"; 
            // Paypal Recurring Object
            $paypalProClass=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
            
            // Paypal API request For Recurring profile creation.
            $httpParsedResponseAr = $paypalProClass->hash_call('doDirectPayment', $nvpstr);            
           /*echo "<pre>";
            print_r($httpParsedResponseAr);
           echo "</pre>";
            die;*/
            // Code to fill history Log of Profile creation
            $userInfor=$this->userInfo();
            $this->paypalHistory($httpParsedResponseAr,$userInfor['user_id'],$httpParsedResponseAr['TRANSACTIONID'],'onetime');
            
           
            // Check the status of the payment.If success OR success with warning then change status and update database and make insertions
            if(strtolower($httpParsedResponseAr['ACK'])=="success" || strtolower($httpParsedResponseAr['ACK'])=="successwithwarning")    {
                // Make the database changes and redirect to the home page.
                $sql=  $this->execute_query("select * from addon_services where clinic_id={$this->clinicInfo('clinic_id')}");
                                $queryauto=$this->fetch_object($sql);
                                if($queryauto->automatic_scheduling==1){
                                    $this->execute_query("UPDATE  user SET  creation_date =  now() WHERE  user_id =".$userInfor['user_id']);
                                    //Assigned Health Video Series 
                                    $this->schedulerEhsPlanAssignment(1,$userInfor['user_id'],$this->clinicInfo('clinic_id'));
                                    //Assigned articles
                                    $this->schedulerEhsArticle(1,$userInfor['user_id'],$this->clinicInfo('clinic_id'));
                                    //Assigned Goal
                                     $this->schedulerEhsGoal(1,$userInfor['user_id'],$this->clinicInfo('clinic_id'));
                                    //Assigned Reminders
                                    $this->schedulerAddEhsReminder(1,$userInfor['user_id'],$this->clinicInfo('clinic_id'));       
                                }
                $this->saveDataSubscriptionOneTime(trim(urldecode($httpParsedResponseAr['TRANSACTIONID'])),$startDate);
                header("location:index.php?action=patient&payment=sucess"); 
                die();
            }
            else
            {
                //die();
                // Payment unsucessfull redirect to the Paymnet page again with error code.
                $errorCode=$httpParsedResponseAr['L_ERRORCODE0'];
                $_SESSION['pateintSubs']['fname']=$firstName;
                $_SESSION['pateintSubs']['lname']=$lastName;
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
                header("location:index.php?action=patient_subscription&validData=true&errorCode={$errorCode}");    
            }
           
        }
        
        function oneTimeExpMessage(){
        	
        	$replace['servicename']=$this->value('servicename');
            $replace['body'] = $this->build_template($this->get_template("oneTimeExpMessage"),$replace);
            $this->output = $this->build_template($this->get_template("main"),$replace);
        	
        }
        
        /**                
         * This is used to display the final template page.
         *
         * @access public
         */        
        
        
        function display(){

            view::$output =  $this->output;

        }
        
             function getprofiledetail(){
        	// Paypal Recurring Object
                                $API_UserName=urlencode($this->config["paypalprodetails"]["API_UserName"]);
                                $API_Password=urlencode($this->config["paypalprodetails"]["API_Password"]);
                                $API_Signature=urlencode($this->config["paypalprodetails"]["API_Signature"]);
                                $environment=urlencode($this->config["paypalprodetails"]["environment"]);
                                $currencyID=urlencode($this->config["paypalprodetails"]["currencyID"]);
                                // Paypal Object
                                $paypalObjectCancel=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);                           
                                
                                $profileID=urlencode($Paypal_Profile);
                                
                                //echo "###".$Paypal_Profile."###".$profileID.'<br>';
                               // $profileID='I-1UXNUEY8UD9Y';
                                $action=urlencode("Cancel");
                                //$note=urlencode("Cancel Subscription for ".$subscriptionname);
                                $nvpStr="&PROFILEID=$profileID";
                                $paypalObjectCancel=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                                
                                //echo $nvpStr;  die();
                                // API Call To Paypal to Cancel Recurring Profile
                                
                                //echo "IN ".$nvpStr; die();  
                                
                                $httpParsedResponseAr = $paypalObjectCancel->PPHttpPost('GetRecurringPaymentsProfileDetails', $nvpStr);
                                /*echo '<pre>';
                                print_r($httpParsedResponseAr);
                                echo '</pre>';*/
        	
        }
        
/**
         * Function to accept the Terms of Services & Privacy Policy and email promotion                                                                            
         * 
         * @access public
         */
        function update_paypal_profile(){

            
            $errorCode=$this->value('errorCode');
                if($errorCode!='') {
                    $customerrormessage=$this->PaypalErroCodeslist($errorCode);
                    if($customerrormessage=='')                    
                        {
                            $customerrormessage="Invalid Credit Card Details";                        
                        }
                    $replace['errorMessage']='<tr><td colspan="4" style="height:20px; color:#0069a0;; font-weight:bold"><font style="color:#FF0000;font-weight:normal;">'.$customerrormessage.'</font></td></tr>';          
                    
                }
     
              
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
                     
                                                                    
                    if(urldecode($this->value('cnumberValid'))!='')  {      
                        $replace['cnumberValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cnumberValid').'</font>';  
                        $replace['retainedcardNumber'] = '';
                }                                          
                       $replace['retainedcardNumber'] = $_SESSION['pateintSubs']['cardNumber'];  
                                                                                                          
                    if(urldecode($this->value('cexpValid'))!='')             
                        $replace['cexpValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cexpValid').'</font>';                                                           
                    if(urldecode($this->value('ccvvValid'))!='') {            
                        $replace['ccvvValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('ccvvValid').'</font>'; 
                        $replace['retainedcvvNumber'] = '';
                     }
  					$replace['retainedcvvNumber'] = $_SESSION['pateintSubs']['cvvNumber'];                                                                 
                    if(urldecode($this->value('ctypeValid'))!='')  {               
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
                    }else{
                    $replace['namefValid']='';
                    $replace['retainedFname']='';      
                    $replace['namelValid']=''; 
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
                    $replace['retainedcardNumber']='';             
                    $replace['retainVisaType']=''; 
                    $replace['retainMasterType']=''; 
                    $replace['retainedcvvNumber']='';
                         
             }
            $userInfo = $this->userInfo();            
            $clinicInfo=$this->clinicInfo('clinic_id');
            $sql="SELECT * FROM patient_subscription  WHERE clinic_id={$clinicInfo} and user_id={$userInfo['user_id']} and subs_status='3'";//and subs_status='3'
            $result=$this->execute_query($sql);
            if($this->num_rows($result)>0){
            	$subdetail=$this->fetch_object($result);
            	
            }
            if($subdetail->payment_paypal_profile!=''){
            	$API_UserName=urlencode($this->config["paypalprodetails"]["API_UserName"]);
                                $API_Password=urlencode($this->config["paypalprodetails"]["API_Password"]);
                                $API_Signature=urlencode($this->config["paypalprodetails"]["API_Signature"]);
                                $environment=urlencode($this->config["paypalprodetails"]["environment"]);
                                $currencyID=urlencode($this->config["paypalprodetails"]["currencyID"]);
                                // Paypal Object
                                $paypalObjectCancel=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);                           
                                $profileID=urlencode($Paypal_Profile);
                                $profileID=$subdetail->payment_paypal_profile;
                                $action=urlencode("Cancel");
                                $nvpStr="&PROFILEID=$profileID";
                                $paypalObjectCancel=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                                $httpParsedResponseAr = $paypalObjectCancel->PPHttpPost('GetRecurringPaymentsProfileDetails', $nvpStr);
                              	$replace['ccno']=$httpParsedResponseAr['ACCT'];
                              	$replace['profileId']=$subdetail->payment_paypal_profile;
                              	$replace['user_subs_id']=$subdetail->user_subs_id;
                              	if($this->value('validData')==''){
                              		$replace['retainedadd1']=urldecode($httpParsedResponseAr['STREET']);
						            $replace['retainedadd2']=urldecode($httpParsedResponseAr['STREET2']);
						            $replace['retainedcity']=urldecode($httpParsedResponseAr['CITY']);
						            $replace['retainedzip']=urldecode($httpParsedResponseAr['ZIP']);
						            $countryarray=$this->country_list(); 
						            $replace['country']=$this->build_select_option($countryarray, $httpParsedResponseAr['COUNTRYCODE']);
						            $stateArray=$this->state_list($httpParsedResponseAr['COUNTRYCODE']);
						            $replace['stateOptions'] = $this->build_select_option($stateArray, urldecode($httpParsedResponseAr['STATE']));
						            /*$replace['RetaincountryCAN']=''; 
						            $replace['RetaincountryUS']='';
						            //checks if user country is US in session
						            if(urldecode($httpParsedResponseAr['COUNTRYCODE'])=='US'){ 
						            	$stateArray = array("" => "Choose state...");
						            	$stateArray = array_merge($stateArray,$this->config['state']);
						                 $replace['RetaincountryUS']='selected';
						                 $replace['RetaincountryCAN']='';
						                 $replace['stateOptions']='';
						                 $replace['patient_state_options'] = $this->build_select_option($stateArray, urldecode($httpParsedResponseAr['STATE']));         
						            //checks if user country is Canada in session
						            }elseif(urldecode($httpParsedResponseAr['COUNTRYCODE'])=='CA'){   
						            	$stateCanadaArray = array("" => "Choose Province...");
						            	$stateCanadaArray = array_merge($stateCanadaArray,$this->config['canada_state']);
						
						            	foreach($stateCanadaArray as $key=>$value){
						                	    $canadastateJavascript.="'{$key}':'{$value}',";
						            	}
						            	$canadastateJavascript=trim($canadastateJavascript,',');
						            
						            //$replace['canada_state']=implode("','",$stateCanadaArray);
						            	$replace['canada_state']=$canadastateJavascript;
						                $replace['RetaincountryUS']='';
						                $replace['RetaincountryCAN']='selected';      
						                $replace['patient_state_options']='';               
						                $replace['stateOptions'] = $this->build_select_option($stateCanadaArray, urldecode($httpParsedResponseAr['STATE']));         
						
						            }*//*else{
						                $replace['patient_state_options'] = $this->build_select_option($stateArray, $replace['patient_state']);                      
						            }*/
                              	}else{
                              		//$countryarray=$this->country_list(); 
                              		//$replace['country']=$this->build_select_option($countryarray);
                              	}                 	
                              	
            	
            }
            $dataSubs=$this->subscriptionInfo($clinicInfo);
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['footer'] = $this->build_template($this->get_template("footer"));    
            $replace['HealthProgramName']=$dataSubs["subs_title"];
            $replace['HealthProgramDescription']=html_entity_decode($dataSubs["subs_description"]); 
            
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
            
            if($dataSubs['paymentType']==0){
            	$replace['submitsubcription']='submit_patient_subscription';
            	$replace['HealthProgramPrice']=$dataSubs['subs_price'];
            	$replace['HealthProgramPriceshow']='<h1>&#36;'.$dataSubs['subs_price'].' a month</h1>This is an online health service provided by '.$dataSubs['clinic_name'].'. There are no contracts and you can cancel at any time.';
            	$replace['ehsTimePeriod']=$dataSubs['ehsTimePeriod'];
            	
            }else{
            	$replace['HealthProgramPrice']=$dataSubs['onetime_price'];
            	$replace['submitsubcription']='submit_patient_onetime_subscription';
            	if($dataSubs['ehsTimePeriod']==1)
            	$show='month';
            	else
            	$show='months';
            	//$replace['HealthProgramPriceshow']='<h1>&#36;'.$dataSubs['onetime_price']. ' for '.$dataSubs['ehsTimePeriod'].' '.$show.'</h1>This is an online health service provided by '.$dataSubs['clinic_name'].'.';
            	$replace['HealthProgramPriceshow']='<h1>&#36;'.$dataSubs['onetime_price']. ' for '.$dataSubs['ehsTimePeriod'].' '.$show.'</h1>This is an online health service provided by '.$dataSubs['clinic_name'].'. At the end of the term listed above, your credit card will <u>not</u> be charged again.';
            	$replace['ehsTimePeriod']=$dataSubs['ehsTimePeriod'];
            }
            
            $replace['HealthProgramID']=$dataSubs['subs_id'];
            
            $replace['HealthProgramClinicName']=$dataSubs['clinic_name'];
            $replace['body'] = $this->build_template($this->get_template("patient_subscription"),$replace);

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
            
            //$replace['canada_state']=implode("','",$stateCanadaArray);
            $replace['canada_state']=$canadastateJavascript;
            */
            $monthArray = array();
            $monthArray = $this->config['monthsArray'];
            $replace['monthsOptions'] = $this->build_select_option($monthArray, $_SESSION['pateintSubs']['exprMonth']); 
           
            $yearArray = array();
            $yearArray = $this->config['yearArray'];
             $replace['yearOptions'] = $this->build_select_option($yearArray, $_SESSION['pateintSubs']['exprYear']);
            
            /*$replace['RetaincountryCAN']=''; 
            $replace['RetaincountryUS']='';
            //checks if user country is US in session
            if($_SESSION['pateintSubs']['country']=='US'){ 
                 $replace['RetaincountryUS']='selected';
                 $replace['RetaincountryCAN']='';
                 $replace['patient_state_options'] = $this->build_select_option($stateArray, $_SESSION['pateintSubs']['state']);         
            //checks if user country is Canada in session
            }elseif($_SESSION['pateintSubs']['country']=='CA'){   
                      $replace['RetaincountryUS']='';
                      $replace['RetaincountryCAN']='selected';                     
                      $replace['stateOptions'] = $this->build_select_option($stateCanadaArray, $_SESSION['pateintSubs']['state']);         

            }else{
                $replace['patient_state_options'] = $this->build_select_option($stateArray, $replace['patient_state']);                      
            }*/

            $countryarray=$this->country_list(); 
			$replace['country']=$this->build_select_option($countryarray, $_SESSION['pateintSubs']['country']); 
            $stateArray=$this->state_list($_SESSION['pateintSubs']['country']);
			$replace['stateOptions'] = $this->build_select_option($stateArray, $_SESSION['pateintSubs']['state']);
            
             if($this->value('status')=='show')
             {
                $row=$this->get_patient_subscription($userInfo['user_id']);
             //   print_r($row);
             	$urlStr="<script>GB_showCenter('Patient Subscription', '/index.php?action=oneTimeExpMessage&servicename=".$row["subscription_title"]."',130,420);</script>";
                
                
             }else{
                
                $urlStr='';
             } 
             $replace['show']=$urlStr;
            //Unsetting the Session variable stored for Remembering field values if any wrong/incomplete info submitted by                  user.
            //print_r($_SESSION);
            unset($_SESSION['pateintSubs']);
            
            $this->output = $this->build_template($this->get_template("main"),$replace);

        }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   

      function update_paypal_profile_submit()
        {
            //print_r($_REQUEST);
            // Paypal Recurring Object
            $API_UserName=urlencode($this->config["paypalprodetails"]["API_UserName"]);
            $API_Password=urlencode($this->config["paypalprodetails"]["API_Password"]);
            $API_Signature=urlencode($this->config["paypalprodetails"]["API_Signature"]);
            $environment=urlencode($this->config["paypalprodetails"]["environment"]);
            $currencyID=urlencode($this->config["paypalprodetails"]["currencyID"]);
            $Frequency=urlencode($this->config["paypalprodetails"]["billingPeriod"]);
            $billingFreq=urlencode($this->config["paypalprodetails"]["billingFreq"]);
            $userInfor=$this->userInfo();
            
            $email=$userInfor['username'];
            $creditCardType = $this->value('cardType');
            $creditCardNumber = $this->value("cardNumber");
            $expDateMonth = $this->value('exprMonth');
            $expDateYear = $this->value('exprYear');
            $cvv2Number = $this->value('cvvNumber');
            $address1 = $this->value('address1');
            $address2 = $this->value('address2');
            $city = $this->value('city');
            $state = $this->value('state');
            $zipcode = $this->value('zipcode');
            $country = $this->value('country');
            //Code To Validate User Entered Data
            $error=array();
                 
            
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
            
            if(!empty($error)){
                $messageReturn="&validData=true";
                foreach($error as $key=>$value)   {
                    $messageReturn.="&".$key."=".urlencode($value);
                }
               
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
                header("location:index.php?action=update_paypal_profile".$messageReturn);
                die();
            }
            
            $startDate = urlencode(date("Y-m-d")."T0:0:0");
            $clinicName=html_entity_decode($this->get_clinic_info($userInfor['user_id'],"clinic_name"), ENT_QUOTES, "UTF-8");
            $desc=urlencode($clinicName.' - '.html_entity_decode($this->value('HealthDescription'), ENT_QUOTES, "UTF-8"));
            $profileId=urlencode($this->value('profileId'));
             $BillingstreetAddr=urlencode($address1);
             $Billingstreet2Addr=urlencode($address2);
             $BillingCity=urlencode($city);
             $BillingState=urlencode($state);
             $BillingCountry=urlencode($country);
              $Billingzip=urlencode($zipcode);
              $fname=urlencode($this->get_sub_fname($this->value('profileId')));
              $lname=urlencode($this->get_sub_lname($this->value('profileId')));
               /* $nvpStr .= "&STREET=$BillingstreetAddr
                            &STREET2=$Billingstreet2Addr
                            &CITY=$BillingCity
                            &STATE=$BillingState
                            &COUNTRYCODE=$BillingCountry
                            &ZIP=$Billingzip";*/
 
            
            // Code to Limit the MAXFAILEDPAYMENTS
            /* $nvpStr .='&MAXFAILEDPAYMENTS=1';*/

            $userInfor=$this->userInfo();
            
            $nvpStr="&FIRSTNAME=$fname&LASTNAME=$lname&PROFILEID=$profileId&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=$expDateMonth$expDateYear&CVV2=$cvv2Number";
            $nvpStr .= "&STREET=$BillingstreetAddr&STREET2=$Billingstreet2Addr&CITY=$BillingCity&STATE=$BillingState&COUNTRYCODE=$BillingCountry&ZIP=$Billingzip";
           // $nvpStr .='&MAXFAILEDPAYMENTS=1'; 
            //echo $nvpStr."<br>"; 
            // Paypal Recurring Object
            //echo $nvpStr;die;
            $paypalProClass=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
            
            // Paypal API request For Recurring profile creation.
            $httpParsedResponseAr = $paypalProClass->PPHttpPost('UpdateRecurringPaymentsProfile', $nvpStr);            
			
            // Code to fill history Log of Profile creation
            $userInfor=$this->userInfo();
            $this->paypalHistory($httpParsedResponseAr,$userInfor['user_id'],trim(urldecode($httpParsedResponseAr['PROFILEID'])),'signup');
            
           /* echo "<pre>";
            print_r($httpParsedResponseAr);
            echo "</pre>";
  			die;      */    
            // Check the status of the payment.If success OR success with warning then change status and update database and make insertions
            if(strtolower($httpParsedResponseAr['ACK'])=="success" || strtolower($httpParsedResponseAr['ACK'])=="successwithwarning")    {
            			$action=urlencode("Reactivate");
                                $note=urlencode("Reactivate Subscription for ".$subscriptionname);
                                $nvpStr="&PROFILEID=$profileId&ACTION=$action&NOTE=$desc";
                                $paypalObjectActive=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                                $httpParsedResponseArActive = $paypalObjectActive->PPHttpPost('ManageRecurringPaymentsProfileStatus', $nvpStr);
				$nvpStr="&PROFILEID=$profileId";
                                $httpParsedResponseoutstandingbill = $paypalObjectActive->PPHttpPost('BillOutstandingAmount', $nvpStr);
                                /*echo "<pre>";
					            print_r($httpParsedResponseArActive);
					            echo "</pre>";
					  			die; */
                // Make the database changes and redirect to the home page.
                    		$user_subs_id=$this->value('user_subs_id');
           			$this->UpdateDataSubscription(trim(urldecode($httpParsedResponseAr['PROFILEID'])),$startDate,$user_subs_id);
                header("location:index.php?action=patient&payment=update"); 
                die();
            }
            else
            {
                //die();
                // Payment unsucessfull redirect to the Paymnet page again with error code.
                $errorCode=$httpParsedResponseAr['L_ERRORCODE0'];
                  $_SESSION['pateintSubs']['fname']=$firstName;
                $_SESSION['pateintSubs']['lname']=$lastName;
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
                header("location:index.php?action=update_paypal_profile&validData=true&errorCode={$errorCode}");    
            }
           
        }
        
      	/**
         * This function saves the Patient information and update the database as subscription purchased.
         * Also the end date is set to one month more than subscription start date.
         * @param 
         * @return array $subscriptionInfo
         * @access public
         */  
                
        function UpdateDataSubscription($profileNumber,$startDate,$user_subs_id){
            // Gather information from REQUEST
            $Frequency=urlencode($this->config["paypalprodetails"]["billingPeriod"]);
            $billingFreq=urlencode($this->config["paypalprodetails"]["billingFreq"]);
			$userInfor=$this->userInfo();
            $clinicID=$this->clinicInfo('clinic_id');
            $Frequency=urlencode($this->config["paypalprodetails"]["billingPeriod"]);
            $billingFreq=urlencode($this->config["paypalprodetails"]["billingFreq"]);
            $querySubscriptEntry="update patient_subscription set  `subs_datetime`=now(), `subs_status`='1',`subs_end_datetime`=DATE_ADD(now(), INTERVAL {$billingFreq} {$Frequency}) where user_subs_id=".$user_subs_id;
            $result=$this->execute_query($querySubscriptEntry);
           
           // Send email to the Account Admin 
           //$this->sendTxmessageAccountAdmin($clinicID,$userInfor,$resultHealthProgram);

           // Send email to the Patient
           //$this->sendEmailSignUpPatients($clinic_id,$userInfor,$resultHealthProgram,0);
        
        }

        function update_credit_card_informaion(){
        //print_r($_REQUEST);
            // Paypal Recurring Object
            $API_UserName=urlencode($this->config["paypalprodetails"]["API_UserName"]);
            $API_Password=urlencode($this->config["paypalprodetails"]["API_Password"]);
            $API_Signature=urlencode($this->config["paypalprodetails"]["API_Signature"]);
            $environment=urlencode($this->config["paypalprodetails"]["environment"]);
            $currencyID=urlencode($this->config["paypalprodetails"]["currencyID"]);
            $Frequency=urlencode($this->config["paypalprodetails"]["billingPeriod"]);
            $billingFreq=urlencode($this->config["paypalprodetails"]["billingFreq"]);
            $userInfor=$this->userInfo();
            
            $email=$userInfor['username'];
            $creditCardType = $this->value('cardType');
            $creditCardNumber = $this->value("cardNumber");
            $expDateMonth = $this->value('exprMonth');
            $expDateYear = $this->value('exprYear');
            $cvv2Number = $this->value('cvvNumber');
            $address1 = $this->value('address1');
            $address2 = $this->value('address2');
            $city = $this->value('city');
            $state = $this->value('state');
            $zipcode = $this->value('zipcode');
            $country = $this->value('country');
            //Code To Validate User Entered Data
            $error=array();
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
            
            if(!empty($error)){
                $messageReturn="&validData=true";
                foreach($error as $key=>$value)   {
                    $messageReturn.="&".$key."=".urlencode($value);
                }
               
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
                header("location:index.php?action=changePass".$messageReturn);
                die();
            }
            
            $startDate = urlencode(date("Y-m-d")."T0:0:0");
            $clinicName=html_entity_decode($this->get_clinic_info($userInfor['user_id'],"clinic_name"), ENT_QUOTES, "UTF-8");
            $desc=urlencode($clinicName.' - '.html_entity_decode($this->value('HealthDescription'), ENT_QUOTES, "UTF-8"));
            $profileId=urlencode($this->value('profileId'));
            $BillingstreetAddr=urlencode($address1);
             $Billingstreet2Addr=urlencode($address2);
             $BillingCity=urlencode($city);
             $BillingState=urlencode($state);
             $BillingCountry=urlencode($country);
              $Billingzip=urlencode($zipcode);
               /* $nvpStr .= "&STREET=$BillingstreetAddr
                            &STREET2=$Billingstreet2Addr
                            &CITY=$BillingCity
                            &STATE=$BillingState
                            &COUNTRYCODE=$BillingCountry
                            &ZIP=$Billingzip";*/
 
            
            // Code to Limit the MAXFAILEDPAYMENTS
            /* $nvpStr .='&MAXFAILEDPAYMENTS=1';*/
				$fname=urlencode($this->get_sub_fname($this->value('profileId')));
              $lname=urlencode($this->get_sub_lname($this->value('profileId')));
             
            $nvpStr="&FIRSTNAME=$fname&LASTNAME=$lname&PROFILEID=$profileId&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=$expDateMonth$expDateYear&CVV2=$cvv2Number";
            $nvpStr .= "&STREET=$BillingstreetAddr&STREET2=$Billingstreet2Addr&CITY=$BillingCity&STATE=$BillingState&COUNTRYCODE=$BillingCountry&ZIP=$Billingzip";
            //$nvpStr .='&MAXFAILEDPAYMENTS=1'; 
            //echo $nvpStr."<br>"; 
            // Paypal Recurring Object
            $paypalProClass=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
            
            // Paypal API request For Recurring profile creation.
            $httpParsedResponseAr = $paypalProClass->PPHttpPost('UpdateRecurringPaymentsProfile', $nvpStr);            
			
            // Code to fill history Log of Profile creation
            $userInfor=$this->userInfo();
            $this->paypalHistory($httpParsedResponseAr,$userInfor['user_id'],trim(urldecode($httpParsedResponseAr['PROFILEID'])),'signup');
            
           /* echo "<pre>";
            print_r($httpParsedResponseAr);
            echo "</pre>";
  			die;      */    
            // Check the status of the payment.If success OR success with warning then change status and update database and make insertions
            if(strtolower($httpParsedResponseAr['ACK'])=="success" || strtolower($httpParsedResponseAr['ACK'])=="successwithwarning")    {
            	$expdate=date('m').date('Y');
            					$SQL="select * from updated_credit_card where paypal_id='{$profileId}' and expdate='{$expdate}'";
            					$query=$this->execute_query($SQL);
            					if($this->num_rows($query)>0){
            						$res=$this->fetch_object($query);
            						$update="update updated_credit_card set status_mail='1' where id='{$res->id}'";
            						$query=$this->execute_query($update);
            					}
            					
            					
            					/*$action=urlencode("Reactivate");
                                $note=urlencode("Cancel Subscription for ".$subscriptionname);
                                $nvpStr="&PROFILEID=$profileId&ACTION=$action&NOTE=$desc";
                                $paypalObjectActive=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                                $httpParsedResponseArActive = $paypalObjectActive->PPHttpPost('ManageRecurringPaymentsProfileStatus', $nvpStr);*/
                                /*echo "<pre>";
					            print_r($httpParsedResponseArActive);
					            echo "</pre>";
					  			die; */
                // Make the database changes and redirect to the home page.
                    		     //$user_subs_id=$this->value('user_subs_id');
           			     		 //$this->UpdateDataSubscription(trim(urldecode($httpParsedResponseAr['PROFILEID'])),$startDate,$user_subs_id);
                header("location:index.php?action=changePass&sucess=1"); 
                die();
            }
            else
            {
                //die();
                // Payment unsucessfull redirect to the Paymnet page again with error code.
                $errorCode=$httpParsedResponseAr['L_ERRORCODE0'];
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
                header("location:index.php?action=changePass&validData=true&errorCode={$errorCode}");    
            }        	
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
        
        
        
                }
    

    // creating object of this class.
    $obj = new patient_subscription();

?>

