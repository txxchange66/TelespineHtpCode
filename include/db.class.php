<?php	

	/**
	 * Copyright (c) 2008 Tx Xchange.
	 *
	 * Class for common database functions used in application.
	 * This class is responsible for connecting from database and handle queries.
	 */

class db{
    /**
     * 
     *
     * @var unknown_type
     * @access private
     */
    static private $instance = NULL;

    /**
     * Config file array
     *
     * @var unknown_type
     * @access public
     */
    static public $config = NULL;

    /**
     * constructur
     * Including confir file array.
     * @param unknown_type $config
     * @access public
     */
    private function __construct($config=""){
            if($config != ""){
                    include_once($config);

            }
            else{

                    include_once("config.php");
            }

            self::$config = $txxchange_config;

            self::$instance = self::connect_to_db(self::$config['dbconfig']['db_host_name'], self::$config['dbconfig']['db_name'], self::$config['dbconfig']['db_user_name'],self::$config['dbconfig']['db_password']);
    }


    /**
     * creating instance of this class
     *
     * @param string $config
     * @return unknown
     * @access public
     */
    static public function getinstance($config=""){
            if( self::$instance == NULL ){
                    if($config != ""){
                            new db($config);

                    }
                    else{ 
                            new db();
                    }	
            }
            return self::$instance;
    }

    /**
     * connecting database.
     *
     * @param string $dbhost - host name
     * @param string $dbname - database name
     * @param string $dbuser - user name
     * @param string $dbpass - password
     * @return unknown
     * @access public
     */
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
                    exit;
            }
            return $link;
    }

    /**
     * function to execute sql statement.
     *
     * @param string $query
     * @return mysql result set 
     * @access public
     */
    static function execute_query($query) {

                    if (empty($query)) {
                            return 0;
                    }
                    // execute query
                    $result = @mysql_query($query);
                    $error = @mysql_error();
                    // if error occured
                    if (!$result){
                                    echo "<h2>Can't execute query $query</h2>";
                                    echo "<p><b>Error:</b> ". @mysql_error();
                                    exit;
                    }
                    return $result;
    }


    /**
     * check the duplicate enteries in the table
     *
     * @param string $table
     * @param string $col
     * @param string $value
     * @param integer $id
     * @return boolean
     * @access public
     */
    function duplicate_check($table,$col,$value,$id=""){
            $count = 0 ;
            $value = trim($value) ;
            if( $id != ""){
                    $result = @mysql_query ('SELECT '.$col.' FROM '.$table.' WHERE '.$col.'=\''.$value.'\' and '.self::primarykey($table).'!=\''.$id.'\'' );
            }
            else{
                    $result = @mysql_query ('SELECT '.$col.' FROM '.$table.' WHERE '.$col.'=\''.$value.'\'');
            }
            // fetch rows
            $count = @mysql_num_rows($result);
            if($count>=1){
                    return true;
            }else{
                    return false;
            }
    }


     /**
      * Function to insert data into table.
      * insert function - requires the table to be inserted to and an array of column names=>variables
      * 
      * @param string $table - table name
      * @param array $arr - data array
      * @return mysql result set
      * @access public
      */
      function insert($table, $arr) {
            if( $table == 'user' ){
// Encrypt data
$encrypt_field = array('password','address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
$arr = $this->encrypt_field($arr, $encrypt_field);
// End Encryption
}
            $result = self::execute_query("SELECT * FROM $table LIMIT 0,1");
            $columns = @mysql_num_fields($result);
            // getting list of columns of the specified table.
            for ($i = 0; $i < $columns; $i++) {
              $fieldarr[] = @mysql_field_name($result, $i);
            }

            // building insert statement.
            $sqla = "INSERT INTO $table ( ";
            $sqlb = ") VALUES ( ";
            foreach ($arr as $name => $value) {
              if (in_array ($name, $fieldarr)) {
                    $sqla .= "$name,";
                    if (is_array($value)) {
                      $sqlb .= "'|";
                      $j = count($value);
                      for ($i = 0; $i < $j-1; $i++) {
                            $sqlb .= addslashes($value[$i]) . "|,|";
                      }
                      $k = $j-1;
                      $sqlb .= addslashes($value[$k]) . "|',";
                    } else {
                            if( $value == null ){
                            $sqlb .= 'null'.",";
                    }
                    else
                            $sqlb .= "'" . addslashes($value) . "',";
                    }
              }
            }
            $sqlb .= ")";
            $sql = $sqla . $sqlb;
            $sql = str_replace(",)", ")", $sql);

            // executing query.
            $result = self::execute_query($sql);
            return $result;
      }

    /**
     * similar to the insert function except that it also requires the id of the record being updated
     *
     * @param string $table
     * @param array $arr
     * @param string $where
     * @return mysql result set
     * @access public
     */
    function update($table, $arr, $where) {

if( $table == 'user' ){
// Encrypt data
$encrypt_field = array('password','address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
$arr = $this->encrypt_field($arr, $encrypt_field);
// End Encryption
}  

      $result = $this->execute_query("SELECT * FROM $table LIMIT 0,1");
      $columns = @mysql_num_fields($result);
      // getting list of columns of the specified table.
      for ($i = 0; $i < $columns; $i++) {
        $fieldarr[] = @mysql_field_name($result, $i);
      }
      // building update statement.
      $sql = "UPDATE $table SET ";
      foreach ($arr as $name => $value) {
                    if (in_array ($name, $fieldarr)) {
          $sql .= "$name = ";

          if (is_array($value)) {
            $sql .= "'|";
            $j = count($value);
            for ($i = 0; $i < $j-1; $i++) {
              $sql .= addslashes($value[$i]) . "|,|";
            }
            $k = $j-1;
            $sql .= addslashes($value[$k]) . "|',";
          } else {
            if( $value == null ){
                    $sql .= 'null'.",";
            }
            else
                    $sql .= "'" . addslashes($value) . "',";
          }
        }
      }
      $sql .= ";";
      $sql = str_replace(",;", " ", $sql);

      $sql .= " where ".$where;

      if(SHOW_SQL !== true) {
         //echo $sql;

        }
      $result = $this->execute_query($sql);

            return $result;
    }
/**
* This function returns Encrypt data passed to it.
*/
static public function encrypt_data($data,$privateKey = "a2dc2a99e649f147bcabc5a99bea7d96"){
  $query = "select HEX(AES_ENCRYPT('{$data}','{$privateKey}'))";
  $result = @mysql_query($query);
  if ($result) {
      return @mysql_result($result, 0);
  }
  return "";
}
/**
* This function Decrypts data passed to it and returns it back.
*/
static public function decrypt_data($data,$privateKey = "a2dc2a99e649f147bcabc5a99bea7d96" ){
  $query = "select AES_DECRYPT(UNHEX('{$data}'),'{$privateKey}')";
  $result = @mysql_query($query);
  if ($result) {
    return @mysql_result($result, 0);
  }
  return "";
}
/**
* This function encrypts those fields in $data array which are given in $encryp_field array.
*/
function encrypt_field($data, $encrypt_field){
if( is_array($data) && is_array($encrypt_field) ){
foreach( $data as $key => $value ){
   if( in_array($key,$encrypt_field) ){
   $data[$key] = self::encrypt_data($value);
   }
}
}
return $data;
}
/**
* This function decrypts those fields in $data array which are given in $encryp_field array.
*/
function decrypt_field($data, $encrypt_field){
if( is_array($data) && is_array($encrypt_field) ){
foreach( $data as $key => $value ){
    if( in_array($key,$encrypt_field) ){
        $data[$key] = self::decrypt_data($value);
    }
}
}
return $data;
}
    /**
     * select builds a select statement on the following parameters:
     * table = the db table being operated on
     * id = the record id (optional) if you want to select data from a specific record
     * fieldArr = a comma separated array (ie field1,field2,field3) of the fields you want to return from the db (optional, defaults to * for all fields)
     * where = the where clause (optional)
     * orderby = the order by clause (optional)
     * limit = the limit clause (optional)
     *
     * @param string $table
     * @param integer $id
     * @param string $fieldArr
     * @param string $where
     * @param string $orderby
     * @param integer $limit
     * @return mysql result set
     * @access public
     */
    function select($table, $id = "", $fieldsstr = "*", $where = "", $orderby = "", $limit = "") {

      if ($where) {
        $sql = "SELECT $fieldsstr FROM $table WHERE $where";
      } elseif($id) {
        $sql = "SELECT $fieldsstr FROM $table WHERE id = $id";
      } else {
        $sql = "SELECT $fieldsstr FROM $table";
      }
      // set order by clause
      if ($orderby) {
        $sql .= " ORDER BY $orderby";
      }
      // set limit
      if ($limit) {
        $sql .= " LIMIT $limit";
      }

//	print $sql ;
    $result = self::execute_query($sql);

       /* if(SHOW_SQL == true) {
          echo $sql;
        }*/

            return $result;
    }


/**
* delete just deletes a record or records from the database, based on the parameters in the where clause
* delete just deletes a record or records from the database, based on the parameters in the where clause
* 
* @param string $table - table name
* @param string $where
* @access public
*/
function db_delete($table, $where = "") {
    if ($where) {
      $sql = "DELETE FROM $table WHERE $where";
    } else {
      $sql = "DELETE FROM $table";
    }
    /*if(SHOW_SQL == true) {
      echo $sql;
    }*/
    //print($sql);
            self::execute_query($sql);
}

    /**
     * Return Fetch a result row as an associative array and numeric array
     *
     * @param mysql result set $result
     * @return array - both associative and numeric
     * @access public
     */
    function fetch_array($result) {
            return @mysql_fetch_array($result);
    }

    /**
     * Returns all the rows as an associate and numeric array
     * 
     * @param type $result Mysql resultset
     * @return Array An associate array of all the rows
     */
    function fetch_all_rows($resultset)
    {
        $result = array();
        while($data = $this->fetch_array($resultset))
        {
            $result[] = $data;
        }
        return $result;
    }

    /**
     * Get a result row as an enumerated array
     *
     * @param mysql result set $result
     * @return array
     * @access public
     */
    function fetch_row($result) {
            return @mysql_fetch_row($result);
    }

    /**
     * Fetch a result row as an object
     *
     * @param mysql result set $result
     * @return array
     * @access public
     */
    function fetch_object($result) {
            return @mysql_fetch_object($result);
    }

    /**
     * Get number of rows in result
     *
     * @param mysql result set $result
     * @return integer
     * @access public
     */
    function num_rows($result) {
            return @mysql_num_rows($result);
    }

    /**
     * Get number of fields in result
     *
     * @param mysql result set $result
     * @return integer
     * @access public
     */
    function num_fields($result) {
      return @mysql_num_fields($result);
    }

    /**
     * Get the name of the specified field in a result
     *
     * @param mysql result set $result
     * @param integer $i - offset
     * @return string
     * @access public
     */
    function field_names($result, $i) {
      return @mysql_field_name($result, $i);
    }

    /**
     * Move internal result pointer
     *
     * @param mysql result set $result
     * @param boolean $i
     * @access public
     */
    function data_seek($result, $i) {
            @mysql_data_seek($result, $i);
    }

    /**
     * Free result memory
     *
     * @param mysql result set $result
     * @return boolean
     * @access public
     */
    function free_result($result) {
            @mysql_free_result($result);
    }

    /**
     * Get the ID generated from the previous INSERT operation
     *
     * @return interger
     * @access public
     */
    function insert_id()  {
            return @mysql_insert_id();
    }

    /**
     * this is deprecated and should be replaced by data_seek but there are functions in the form class that 
     * use it so I'm leaving it for now
     * 
     * @param string $sql - query
     * @param integer $offset
     * @param integer $limit
     * @return mysql result set
     * @access public
     */
    function move_pointer($sql, $offset, $limit) {
      $sql .= " LIMIT $offset, $limit";
      return @mysql_query($sql);
    }

    /**
     * Returns the fields of a table
     *
     * @param $primeVal
     * @param string $table - table name
     * @param  $field
     * @return string
     * @access public
     */
    function get_field($primeVal, $table, $field) {
        $sql = "SELECT ".$field." FROM `".$table."` WHERE ".self::primarykey($table)." = '".$primeVal."';";
            // execute query
        $result = @mysql_query($sql);
        $row = @@mysql_fetch_array($result);
        $ret=$row[$field];
        if( $table == 'user' && ($field == 'name_title' || $field == 'name_first' || $field == 'name_last') ){
            $ret = self::decrypt_data($ret);   
        }
        @@mysql_free_result($result);
        return $ret;
    }

    /**
     * Returns the primary key of table
     *
     * @param string $table - table name
     * @return string
     * @access public
     */
    function primarykey($table) {
        $sql = "SELECT * FROM `".$table."`;";
        // execute query
        $result = @mysql_query($sql) or @mysql_error();

        //return 'rtnsetid';
        for($i = 0; $i < @mysql_num_fields($result); $i++) {
          if(eregi(".*primary_key.*", @mysql_field_flags($result, $i))){
            $pk=@mysql_field_name($result, $i);
                                    @mysql_free_result($result);
                                    return $pk;
                            }
        }
    }

    /**
     * Returns all rows of table
     *
     * @param sting $table - table name
     * @param string $where - condition statement
     * @param comma seprated string $field
     * @return mysql result set
     * @access public
     */
    function table_record($table,$where="",$field="*"){
            if(trim($where) == "" ){
                    return "";
            }
            else{
                    // building query
                    $query = "select $field from $table where  ".$where;
                    // execute query
                    $result = @mysql_query($query);
                    //echo $query;
                    // fetch rows
                    if(@mysql_num_rows($result) != 1){
                            return "";
                    }
                    return @mysql_fetch_array($result);
            }

    }

    /**
     * Returns rows of table
     *
     * @param string $table - table name
     * @param integer $id
     * @param comma seprated sting $fieldArr
     * @param string $where - condition string
     * @param string $orderby
     * @param integer $limit
     * @return string
     * @access public
     */
    function select_query($table, $id = "", $fieldArr = "*", $where = "", $orderby = "", $limit = "") {

      if($where) {
        $sql = "SELECT $fieldArr FROM $table WHERE $where";
      } elseif($id) {
        $sql = "SELECT $fieldArr FROM $table WHERE id = $id";
      } else {
        $sql = "SELECT $fieldArr FROM $table";
      }
      if ($orderby) {
            // set order by clause
        $sql .= " ORDER BY $orderby";
      }
      if ($limit) {
            // set limit
        $sql .= " LIMIT $limit";
      }
      return $sql;
    }  

    /**
     * This function is calling pagination class and use to implement pagination in pages of application
     *
     * @param unknown_type $rows
     * @param string $sql - sql statement
     * @param string $action - action name gets from query string
     * @param string $search
     * @param array $skipValue
     * @param string $div_name
     * @param string $rows_on_page
     * @return string
     * @access public
     */
    function pagination($rows = 0,$sql='',$action='',$search='',$skipValue=array(),$div_name='',$rows_on_page='',$sqlcount=''){
            $test = new MyPagina("",$action,$div_name,$search,$skipValue,$rows_on_page);
            // remove slashes from sql statement
            $test->sql = stripslashes($sql);
            $test->sqlcount=stripslashes($sqlcount); 
            //echo $test->sql;
            $result = $test->get_page_result(); 
            $num_rows = $test->get_page_num_rows();
            $nav_links =$test->navigation(" | ", "small_white_txt"); 
            $nav_info = $test->page_info("to"); 
            $simple_nav_links = $test->back_forward_link(true); 
            $total_recs = $test->get_total_rows();
            $links['result'] = $result;
            $links['nav']=$nav_links ;
            $links['simple_nav']=$simple_nav_links ;
            $links['page_info']= $test->page_info() ;
            $links['no_of_page']= $test->get_num_pages();


            return $links;
    }


    /**
     * This function checks after execution it return row or not
     *
     * @param array $result
     * @param integer $fetch_type - fetch result type (object, array, row)
     * @return array
     * @access public
     */
    static function populate_array($result, $fetch_type=""){
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


    /**
     * Function to generate string like '1','2','3' to use in "IN Query"
     * e.g, SELECT * FROM user as u WHERE u.user_id IN ('44','48','47','53','27','29')
     * 
     * @param array $array
     * @return string
     * @access public
     */
    function generateInQueryParameter($array){
            // counts the no of elements in array
            $count_rows = count($array);
            $str = "";
            $counter = 0;
            foreach($array as $arr){
                    foreach($arr as $value){
                            $str .= "'".$value."'";
                            $counter++;
                            if($counter != $count_rows){
                                    $str .= ",";
                            }
                    }
            }
            return $str;
    } //Function generateInQueryParameter Ends


    /**
     * Returns a row of table of specified value (primary key id)
     *
     * @param string $tableName - table name
     * @param string $primaryKeyColumnName - primary key column name
     * @param integer $id - primary key value
     * @param sting $coulmnName
     * @return array
     * @access public
     */
    function getTableValue($tableName='', $primaryKeyColumnName='', $id='', $coulmnName=''){
            $returnValue = "";
            if((!empty($tableName)) && (!empty($primaryKeyColumnName)) && (!empty($id))){

                    if(empty($coulmnName)){
                            // if column name not passed as parameter
                            $query = "select * from ".$tableName." where ".$primaryKeyColumnName." = '".$id."'";
                    }else{
                            $query = "select ".$coulmnName." from ".$tableName." where ".$primaryKeyColumnName." = '".$id."'";
                    }

                    // executes sql statement
                    $result = $this->execute_query($query);

                    // fetch data
                    $row = $this->populate_array($result);
                    if(!empty($row)){
                            $returnValue = $row;
                    }
            }// Close If condition

            return $returnValue;
    }// Function GetTableValue Ends
}			
?>
