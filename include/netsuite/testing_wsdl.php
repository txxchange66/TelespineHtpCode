<?php

include("nusoap.php");

$client = new soapclientw('https://webservices.netsuite.com/wsdl/v2_5_0/netsuite.wsdl', true);

$email = 'puneet.mathur@hytechpro.com';
$password = '6666gunpark'; 
$account = '700337';
$role = '1001';

	$ns='urn:core_2_0.platform.webservices.netsuite.com';
	$ns1='urn:messages_2_0.platform.webservices.netsuite.com';
	
	$soapemail = new nsSoapval('email', '',$email, $ns, '');
	$soappassword = new nsSoapval('password', '',$password, $ns, '');
	$soapaccount = new nsSoapval('account', '',$account, $ns, '');
	$soaprole = new nsSoapval('role', '','',$ns,'',array('internalId' => $role));
	
	$ppt = new nsSoapval('passport', 'Passport', array($soapemail , $soappassword, $soapaccount, $soaprole));
	/*
	$result = $client->call('login',array('login' => $ppt),$ns1);
	
	if ($client->fault) {
    echo '<h2>Fault</h2><pre>';
    print_r($result);
    echo '</pre>';
} else {
    // Check for errors
    $err = $client->getError();
    if ($err) {
        // Display the error
        echo '<h2>Error</h2><pre>' . $err . '</pre>';
    } else {
        // Display the result
        echo '<h2>Result</h2><pre>';
        print_r($result);
    echo '</pre>';
    }
}

*/