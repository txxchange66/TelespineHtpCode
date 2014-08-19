<?php
/**
 * Database connection wrapper
 *
 */
class Connection
{
	var $str_host;
	var $str_user;
	var $str_pass;
	var $str_database;
	var $int_linkID;
	
	/**
	 * creates a database connection
	 *
	 * @param string $a_str_host
	 * @param string $a_str_user
	 * @param string $a_str_pass
	 * @param string $a_str_database
	 * @return Connection
	 */
	function Connection($a_str_host = "localhost", $a_str_user = "user", $a_str_pass = "pass", $a_str_database = "validationtest")
	{
		$this->str_host = $a_str_host;
		$this->str_user = $a_str_user;
		$this->str_pass = $a_str_pass;
		$this->str_database = $a_str_database;
		$this->int_linkID = $this->connect();
	}
	
	/**
	 * mysql_query wrapper
	 *
	 * @param string $a_str_query
	 * @param string $a_str_returnType
	 * @return [recordset]
	 */
	function query($a_str_query, $a_str_returnType=""){
		$recordSet = mysql_query($a_str_query, $this->int_linkID) or die($this->handleError("MySQL Query: $a_str_query  Failed...<br><br>\n"));
		if($a_str_returnType == "assoc" AND is_resource($recordSet)){
			$recordSet = $this->getArray($recordSet);
		}
		return $recordSet;	
	}
	
	/**
	 * mysql_fetch_assoc() wrapper
	 *
	 * @param recordset $result
	 * @return array
	 */
	function getArray($result)
	{
		return mysql_fetch_assoc($result);
	}
	
	/**
	 * mysql_num_rows() wrapper
	 *
	 * @param recordset $result
	 * @return integer
	 */
	function getNumRows($result)
	{
		return mysql_num_rows($result);
	}
	
	/**
	 * Get last inserted id
	 *
	 * @return integer
	 */
	function getLastID()
	{
		return mysql_insert_id();
	}
	
	//=====================
	//PRIVATE
	//=====================
	/**
	 * connect to the database and return linkage id
	 *
	 * @return integer
	 */
	function connect(){
	    $conn = mysql_connect($this->str_host, $this->str_user, $this->str_pass) or die("Failed to connect to ".$this->str_host);
	    mysql_select_db($this->str_database, $conn) or die("Failed to select ".$this->str_database." database");
		return $conn;
	}
	
	
	/**
	 * close the database connection
	 *
	 */
	function close(){
		mysql_close($this->int_linkID) or die("Failed to close database connection");
	}
	

	
	/**
	 * displays error msg
	 *
	 * @param string $a_str_error
	 * @return string
	 */
	function handleError($a_str_error = "Undefined Error...<br>\n"){
		//the following line will display the mysql_error
		echo($_SERVER['PHP_SELF'] . "   Error #" . mysql_errno() . " - " .mysql_error()."<br>");
		return $a_str_error;
	}
}
?>