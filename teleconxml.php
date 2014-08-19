<?php
include("config.php");
function connect_to_db($dbhost, $dbname, $dbuser, $dbpass) {
	$link = @mysql_connect($dbhost, $dbuser, $dbpass) or die("connection failed.");
	if (!$link) {
		echo "<h2>Can't connect to $dbhost as $dbuser</h2>";
		echo "<p><b>Error:</b> ", @mysql_error();
		exit;
	}
	// connecting database.
	if (!@mysql_select_db($dbname)) {
		echo "<h2>Can't select database $dbname</h2>";
		echo "<p><b>Error:</b> ", @mysql_error();
		echo "no";
		exit;
	}
	return $link;
}
connect_to_db($txxchange_config['dbconfig']['db_host_name'], $txxchange_config['dbconfig']['db_name'], $txxchange_config['dbconfig']['db_user_name'], $txxchange_config['dbconfig']['db_password']);



if($_REQUEST['action'] == 'check' ){
	$query = "select * from teleconference where provider_id = '{$_REQUEST['doctorid']}'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	echo "<?xml version='1.0' encoding='UTF-8'?>
			<Provider  patientid='{$row[patient_id]}' >

			</Provider>";

} 
else if($_REQUEST['action'] == 'add') {
	$query = "select * from teleconference where provider_id = '{$_REQUEST['doctorid']}'";
	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);
	if($num_rows > 0){
		$query = "update teleconference set  patient_id =  '{$_REQUEST['patientid']}' where provider_id = '{$_REQUEST['doctorid']}'";
	} else{
		$query = "INSERT INTO teleconference ( provider_id, patient_id ) VALUES ( '{$_REQUEST['doctorid']}', '{$_REQUEST['patientid']}')";
	}
	@mysql_query($query);
}
else if($_REQUEST['action'] == 'delete') {
	$query = 	"delete from teleconference where provider_id = '{$_REQUEST['doctorid']}'";
	@mysql_query($query);
}




?>