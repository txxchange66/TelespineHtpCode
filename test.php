<?php
 require_once("config.php");
 ini_set("display_errors",1);
require_once("include/class.paypal.php");
$API_UserName=urlencode($txxchange_config["paypalprodetails"]["API_UserName"]);
$API_Password=urlencode($txxchange_config["paypalprodetails"]["API_Password"]);
$API_Signature=urlencode($txxchange_config["paypalprodetails"]["API_Signature"]);
$environment=urlencode($txxchange_config["paypalprodetails"]["environment"]);
$currencyID=urlencode($txxchange_config["paypalprodetails"]["currencyID"]);
$profileId=urlencode('I-2BMER0R87G65');
$nvpStr="&PROFILEID=$profileId";
//&ACTION=$action&NOTE=$desc";
 
 $paypalObjectActive=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                                $httpParsedResponseArActive = $paypalObjectActive->PPHttpPost('BillOutstandingAmount', $nvpStr);
                                echo "<pre>";
					            print_r($httpParsedResponseArActive);
					            echo "</pre>";
					  			
?>
