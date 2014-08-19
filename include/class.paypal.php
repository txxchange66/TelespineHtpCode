<?php
		class paypalProRecurring {
		
			private $API_UserName;
			private $API_Password;
			private $API_Signature;
			private $environment;
			private $API_Endpoint;
			private $version;
		/*	global $API_Endpoint,
			       $version,
			       $API_UserName,
			       $API_Password,
			       $API_Signature,
			       $nvp_Header, 
			       $subject, 
			       $AUTH_token,
			       $AUTH_signature,
			       $AUTH_timestamp;
		*/	
			
            function __construct($API_UserName,$API_Password,$API_Signature,$environment="sandbox"){
                            $this->API_UserName=urlencode($API_UserName);
                            $this->API_Password=urlencode($API_Password);
                            $this->API_Signature=urlencode($API_Signature);
                            $this->environment=$environment;
                            $this->version=urlencode('51.0');
            }
        
        
        
            public function PPHttpPost($methodName_, $nvpStr_) {
                    $this->API_Endpoint = "https://api-3t.paypal.com/nvp";
                    if("sandbox" === $this->environment || "beta-sandbox" === $this->environment)         
                    {
                        $this->API_Endpoint = "https://api-3t.$this->environment.paypal.com/nvp";
                    }

                    // setting the curl parameters.
                    $ch = curl_init();
                    //echo $this->API_Endpoint;
                    curl_setopt($ch, CURLOPT_URL, $this->API_Endpoint);
                    curl_setopt($ch, CURLOPT_VERBOSE, 1);
            
                    // turning off the server and peer verification(TrustManager Concept).
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
            
            
            
                    // NVPRequest for submitting to server
                    $nvpreq = "METHOD=$methodName_&VERSION=$this->version&PWD=$this->API_Password&USER=$this->API_UserName&SIGNATURE=$this->API_Signature$nvpStr_";
                                        
                    // setting the nvpreq as POST FIELD to curl
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
                
                    // getting response from server
                    $httpResponse = curl_exec($ch);
                                    
                    if(!$httpResponse) {
                        exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
                    }

                    // Extract the RefundTransaction response details
                    $httpResponseAr = explode("&", $httpResponse);
                
                    $httpParsedResponseAr = array();
                    foreach ($httpResponseAr as $i => $value) {
                        $tmpAr = explode("=", $value);
                        if(sizeof($tmpAr) > 1) {
                            $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
                        }
                    }
                    if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
                        exit("Invalid HTTP Response for POST request($nvpreq) to $this->API_Endpoint.");
                    }
                    //print_r($httpParsedResponseAr); die();
                    return $httpParsedResponseAr;
                
            }
            
            
		/**
  * hash_call: Function to perform the API call to PayPal using API signature
  * @methodName is name of API  method.
  * @nvpStr is nvp string.
  * returns an associtive array containing the response from the server.
*/


function hash_call($methodName,$nvpStr)
{
	//echo $nvpStr;
	//die;
 $this->API_Endpoint = "https://api-3t.paypal.com/nvp";
                    if("sandbox" === $this->environment || "beta-sandbox" === $this->environment)         
                    {
                        $this->API_Endpoint = "https://api-3t.$this->environment.paypal.com/nvp";
                    }
$this->USE_PROXY='FALSE';
	//declaring of global variables
    //global $API_Endpoint,$version,$API_UserName,$API_Password,$API_Signature,$nvp_Header, $subject, $AUTH_token,$AUTH_signature,$AUTH_timestamp;
    // form header string
    $nvpheader=$this->nvpHeader();
    //setting the curl parameters.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$this->API_Endpoint);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);

    //turning off the server and peer verification(TrustManager Concept).
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, TRUE);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_POST, 1);
    
    //in case of permission APIs send headers as HTTPheders
    if(!empty($this->AUTH_token) && !empty($this->AUTH_signature) && !empty($this->AUTH_timestamp))
     {
        $headers_array[] = "X-PP-AUTHORIZATION: ".$nvpheader;
  
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_array);
    curl_setopt($ch, CURLOPT_HEADER, false);
    }
    else 
    {
        $nvpStr=$nvpheader.$nvpStr;
    }
    //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
   //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
    if($this->USE_PROXY)
   // curl_setopt ($ch, CURLOPT_PROXY, PROXY_HOST.":".PROXY_PORT); 

    //check if version is included in $nvpStr else include the version.
    if(strlen(str_replace('VERSION=', '', strtoupper($nvpStr))) == strlen($nvpStr)) {
        $nvpStr .= "&VERSION=" . urlencode($this->version) . $nvpStr;  
    }
    echo $nvpreq="METHOD=".$methodName.$nvpStr;
    $nvpreq="METHOD=".urlencode($methodName).$nvpStr;
    
    //setting the nvpreq as POST FIELD to curl
    curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);

    //getting response from server
    $response = curl_exec($ch);

    //convrting NVPResponse to an Associative Array
    $nvpResArray=$this->deformatNVP($response);
    $nvpReqArray=$this->deformatNVP($nvpreq);
    $_SESSION['nvpReqArray']=$nvpReqArray;

    if (curl_errno($ch)) {
        // moving to display page to display curl errors
         echo $_SESSION['curl_error_no']=curl_errno($ch) ;
         echo $_SESSION['curl_error_msg']=curl_error($ch);
         
     } else {
         //closing the curl
            curl_close($ch);
      }

return $nvpResArray;
}
		
		
function nvpHeader()
{
//global $API_Endpoint,$version,$API_UserName,$API_Password,$API_Signature,$nvp_Header, $subject, $AUTH_token,$AUTH_signature,$AUTH_timestamp;
$nvpHeaderStr = "";

if(defined('AUTH_MODE')) {
    //$AuthMode = "3TOKEN"; //Merchant's API 3-TOKEN Credential is required to make API Call.
    //$AuthMode = "FIRSTPARTY"; //Only merchant Email is required to make EC Calls.
    //$AuthMode = "THIRDPARTY";Partner's API Credential and Merchant Email as Subject are required.
    $AuthMode = "AUTH_MODE"; 
} 
else {
    
    if((!empty($this->API_UserName)) && (!empty($this->API_Password)) && (!empty($this->API_Signature)) && (!empty($this->subject))) {
        $AuthMode = "THIRDPARTY";
    }
    
    else if((!empty($this->API_UserName)) && (!empty($this->API_Password)) && (!empty($this->API_Signature))) {
        $AuthMode = "3TOKEN";
    }
    
    elseif (!empty($this->AUTH_token) && !empty($this->AUTH_signature) && !empty($this->AUTH_timestamp)) {
        $AuthMode = "PERMISSION";
    }
    elseif(!empty($this->subject)) {
        $AuthMode = "FIRSTPARTY";
    }
}
switch($AuthMode) {
    
    case "3TOKEN" : 
            $nvpHeaderStr = "&PWD=".urlencode($this->API_Password)."&USER=".urlencode($this->API_UserName)."&SIGNATURE=".urlencode($this->API_Signature);
            break;
    case "FIRSTPARTY" :
            $nvpHeaderStr = "&SUBJECT=".urlencode($this->subject);
            break;
    case "THIRDPARTY" :
            $nvpHeaderStr = "&PWD=".urlencode($this->API_Password)."&USER=".urlencode($this->API_UserName)."&SIGNATURE=".urlencode($this->API_Signature)."&SUBJECT=".urlencode($this->subject);
            break;      
    case "PERMISSION" :
            $nvpHeaderStr = formAutorization($this->AUTH_token,$this->AUTH_signature,$this->AUTH_timestamp);
            break;
}
    return $nvpHeaderStr;
}
		
		/** This function will take NVPString and convert it to an Associative Array and it will decode the response.
  * It is usefull to search for a particular key and displaying arrays.
  * @nvpstr is NVPString.
  * @nvpArray is Associative Array.
  */

function deformatNVP($nvpstr)
{

    $intial=0;
    $nvpArray = array();


    while(strlen($nvpstr)){
        //postion of Key
        $keypos= strpos($nvpstr,'=');
        //position of value
        $valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

        /*getting the Key and Value values and storing in a Associative Array*/
        $keyval=substr($nvpstr,$intial,$keypos);
        $valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
        //decoding the respose
        $nvpArray[urldecode($keyval)] =urldecode( $valval);
        $nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
     }
    return $nvpArray;
}	
		}
		// Sample Code Starts here 
        // Code for Create Profile Start Recurring payment
        /*
		$API_UserName = urlencode('cric_1263364472_biz_api1.rediffmail.com');
		$API_Password = urlencode('J8E3SHYXCPE3Z5T4');
		$API_Signature = urlencode('AFcWxV21C7fd0v3bYYYRCpSSRl31A1mTvz04NLX3zSDFTOBwmN.9rbJd');
		$environment = 'sandbox';
		
		//$token =urlencode("");
		$paymentType = 'Authorization';
		$firstName = "Shyam";
		$lastName = "Sunder";
		$email='shyam.sarkar@hytechpro.com';
		$creditCardType = "Visa";
		$creditCardNumber = "4878639796280507";
		$expDateMonth = "01";
		$expDateYear = "2011";
		$cvv2Number = "962";
		$paymentAmount = urlencode("1");
		$currencyID = urlencode("USD");                        // or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
		$startDate = urlencode("2010-02-03T0:0:0");
		$billingPeriod = urlencode("Month");                // or "Day", "Week", "SemiMonth", "Year"
		$billingFreq = urlencode("1");                        // combination of this and billingPeriod must be at most a year
		$desc=urlencode("Testing");
		$nvpStr="&FIRSTNAME=$firstName&LASTNAME=$lastName&EMAIL=$email&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=$expDateMonth$expDateYear&CVV2=$cvv2Number&AMT=$paymentAmount&CURRENCYCODE=$currencyID&PROFILESTARTDATE=$startDate";
		$nvpStr .= "&BILLINGPERIOD=$billingPeriod&BILLINGFREQUENCY=$billingFreq&DESC=$desc";
		//echo $nvpStr."<br>"; 
		$paypalProClass=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
		$httpParsedResponseAr = $paypalProClass->PPHttpPost('CreateRecurringPaymentsProfile', $nvpStr);
        */
       
       
        // Sample code for cancel payment recurring profile ManageRecurringPaymentsProfileStatus
        //I-JP3SS2VH7C4B
        /*
        $API_UserName = urlencode('cric_1263364472_biz_api1.rediffmail.com');
        $API_Password = urlencode('J8E3SHYXCPE3Z5T4');
        $API_Signature = urlencode('AFcWxV21C7fd0v3bYYYRCpSSRl31A1mTvz04NLX3zSDFTOBwmN.9rbJd');
        $environment = 'sandbox';    
        $profileID=urlencode("I-T7XXXVPTLBJV");
        $action=urlencode("Cancel");
        $note=urlencode("CancelSubscription");
        $nvpStr="&PROFILEID=$profileID&ACTION=$action&NOTE=$note";
        $paypalProClass=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
        //echo $nvpStr;
        $httpParsedResponseAr = $paypalProClass->PPHttpPost('ManageRecurringPaymentsProfileStatus', $nvpStr);
        print_r($httpParsedResponseAr);
        */

?>