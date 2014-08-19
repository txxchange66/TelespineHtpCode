<?php

/**
 * This files helps in plan creation process.
 * 1) It returns cats in xml format.
 * 2) It returns treatment list in xml format.
 * 3) It returns plan treatment list in xml format.
 * 4) 
 */

session_start();
if(isset($HTTP_RAW_POST_DATA)){
	$data = $HTTP_RAW_POST_DATA;
}	
else{
	$data = file_get_contents("php://input");
}

require_once('../../include/_dbtrees.php');
$tx = array(
	'article' => array('media_url'=>'/asset/images/article/'),
	'treatment' =>array('media_url'=>'/asset/images/treatment/'),
	'richTextArea'=>array('dirPath' =>'/richtextarea/')
);
	

$debug_sql_local = true;
$sql_query = "";
if(!empty($_GET['act']))
{
	$act = trim($_GET['act']);
	switch($act)
	{
		case 'getCats':
			// return tree of categories
			$dbt = new DbTrees('category', 'parent_category_id');
			$cats = $dbt->getTree(0,NULL,'status = 1'); // let flash sort //, 'category_name');
			
			// echo as xml
			// start output
			header('Pragma: private');
			header('Cache-control: private, must-revalidate');
			header('Content-Type: text/xml');
			echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
			echo '<cats>'."\n";
			foreach($cats as $top_cat_id => $top_cat)
			{
				echo "\t" . '<topcat id="'.$top_cat['category_id'].'" cname="'.$top_cat['category_name'].'">'."\n";
				if(!empty($top_cat['children']))
				{
					foreach($top_cat['children'] as $cat)
					{
						if(!empty($cat['category_name']) && !empty($cat['category_id']))
						{
							echo "\t\t".'<cat id="'.$cat['category_id'].'" cname="'.$cat['category_name'].'" />'."\n";
						}
					}
				}
				echo "\t" . '</topcat>'."\n";
			}
			echo '</cats>';
			break;
			
			case 'getTreatments':
            $tag_search = escape($_GET['s']);
            $tag_search = formatSearchTag($tag_search);
            $mode = escape($_GET['mode']);
            
            // Get userid
            if( isset($_SESSION['username']) ){
                    $sql= " SELECT user_id, usertype_id FROM user WHERE username =  '{$_SESSION['username']}' and status = 1 " ;
                    $rs = _doQuery($sql);
                    $user_array = populate_array($rs);
                    if( $user_array['usertype_id'] ){
                        $user_id = $user_array['user_id'];
                        $user_type =  $user_array['usertype_id'];
                    }
            }
            // End
            if( $mode == 1 || $mode == 2){
                if($mode == 2 && $user_type == 4){
                    header('Pragma: private');
                    header('Cache-control: private, must-revalidate');
                    header('Content-Type: text/xml');
                    echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
                    echo  "<treatments page='0' of='0' error='Database Error'>
                                <sql>no query</sql>
                            </treatments>";
                    exit();
                }
			    // get treatments for category
			    $sqlTable = ' treatment ';
			    $sqlWhere = ' treatment.status = 1 ';
                // Check clinic id.
			    $clinic_id = clinicInfo('clinic_id');
                if(is_numeric($clinic_id) && $clinic_id > 0){
                    $parent_clinic_id = get_field($clinic_id,'clinic','parent_clinic_id');
                    if( $parent_clinic_id == 0 ){
                        $parent_clinic_id = $clinic_id;
                    }
                    else{
                        $clinic_id = $parent_clinic_id;
                    }
                    $sqlWhere .= " AND ( treatment.clinic_id IN  ( select clinic_id from clinic where clinic_id = '{$clinic_id}' 
                                   OR  parent_clinic_id = '{$parent_clinic_id}' )
                                 OR treatment.clinic_id IS NULL
                                )  ";
                }
                else{
                    $sqlWhere .= " AND ( clinic_id IS NULL ) ";
                }
                
                // Favorite filter
                if( isset($_SESSION['username']) ){
                    if( $mode == 2 && $user_type == 2 ){
                        $sqlWhere .= " AND treatment.treatment_id in (select treatment_id from treatment_favorite where user_id = '{$user_id}' ) ";
                    }
                }
                // End Favorite filter
                $sqlOrder = 'order by  treatment.treatment_counts desc ';
                
                
			    
                $groupBy = 'group by treatment.treatment_id';
                
			    // may have s with text filter
                if(!empty($_GET['s']))
                {
                    $srch = trim($_GET['s']);
                    $sqlWhere .= " AND ( 
                                    MATCH (treatment.treatment_tag) AGAINST ('{$tag_search}' IN BOOLEAN MODE ) 
                                    OR treatment.treatment_name = '{$srch}')  ";
                    $sqlFields = " treatment.* ";
                                           
                }
            }
            
            if($mode == 3 && $user_type == 2 ){
                 $sqlTable = ' treatment ';
                 $sqlWhere = ' treatment.status = 1 ';
                 // Check clinic id.
                $clinic_id = clinicInfo('clinic_id');
                if(is_numeric($clinic_id) && $clinic_id > 0){
                    $parent_clinic_id = get_field($clinic_id,'clinic','parent_clinic_id');
                    if( $parent_clinic_id == 0 ){
                        $parent_clinic_id = $clinic_id;
                    }
                    else{
                        $clinic_id = $parent_clinic_id;
                    }
                    $sqlWhere .= " AND ( treatment.clinic_id IN  ( select clinic_id from clinic where clinic_id = '{$clinic_id}' 
                                   OR  parent_clinic_id = '{$parent_clinic_id}' )
                                 OR treatment.clinic_id IS NULL
                                )  ";
                }
                
                
                // Favorite filter
                if( isset($_SESSION['username']) ){
                    if( $user_type == 2 ){
                        $sqlWhere .= " AND treatment.treatment_id in (select treatment_id from treatment_favorite where user_id = '{$user_id}' ) ";
                    }
                }
                // End Favorite filter
                
                $sqlFields = " treatment.* ";
                $sqlOrder = "order by treatment.treatment_counts desc ";
                
            }
            
            // by page
			$page = 1;
			$num_per_page = 18;
			if(!empty($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 0)
				$page = trim($_GET['p']);
			
            
			// start output
			header('Pragma: private');
			header('Cache-control: private, must-revalidate');
			header('Content-Type: text/xml');
			echo '<?xml version="1.0" encoding="utf-8"?>'."\n";

			// get total rows
			$totalRows = countRows($sqlTable, $sqlWhere, ' distinct treatment.treatment_id ');
			
            
            
			// convert rows to pages
			$totalPages = ceil($totalRows/$num_per_page);
            
			// do query
			$debug_sql_local = true;
			if($debug_sql_local)
			{
				ob_start();
			} 
            
			$res = selectPage($sqlTable, $sqlFields, $sqlWhere, $sqlOrder, $num_per_page, $page, $groupBy);
			if($debug_sql_local) 
			{
				$sql = ob_get_contents();
				ob_end_clean();
			} 
			
			if($res !== false)
            {
                if(@mysql_num_rows($res) > 0)
                {
                    echo '<treatments total="'.$totalRows.'" page="'.$page.'" of="'.$totalPages.'">'."\n";
                    if($debug_sql_local) echo '<sql><![CDATA[ ' . $sql_query . ' ]]></sql>';
                    while($t = @mysql_fetch_array($res))
                    {
                        // add matching treatments
                        // as <treatment id="TREATMENT_ID" tname="TREATMENT_NAME" [optional pic="PIC1_URL"] />
                        if(!empty($t['treatment_name']) && !empty($t['treatment_id']))
                        {
                            echo "\t".'<treatment count="'.$t['treatment_counts'].'" id="'.$t['treatment_id'].'" tname="'.addslashes($t['treatment_name']).'" ';
                            if (!empty($t['pic1'])) { echo 'pic="' . canonicalize($tx['treatment']['media_url']. $t['treatment_id']) . '/thumb.jpg"';}
                        else{ 
                                
                        	if((!empty($t['video']))){
                                    echo 'pic="' . canonicalize($tx['treatment']['media_url']. $t['treatment_id']) . '/video.jpg"';
                                }
                            } 
                            echo ' />'."\n";
                        }
                    }
                    echo '</treatments>';
                }
                else
                {
                    
                    // should have caught this before, but no treatmetns match
                    echo '<treatments page="0" of="0" error="No Treatments" >';
                    if($debug_sql_local) echo '<sql><![CDATA[' . $sql_query . ']]></sql>';
                    echo '</treatments>';
                }
                
            }
            else
            {
                 
                // db error - sql result was false
                echo '<treatments page="0" of="0" error="Database Error" >';
                if($debug_sql_local) echo '<sql><![CDATA[' . $sql_query . ']]></sql>';
                echo '</treatments>';
            }

			break;
			case 'getPlan':
			// get treatments for plan
			$pid = escape($_GET['id']);
			if($pid)
			{
				//$debug_sql_local = true;
				$treatmentSql = 'SELECT * '.
					'FROM plan_treatment pt 
					INNER JOIN treatment t on t.treatment_id = pt.treatment_id '.
					'WHERE '." pt.plan_id = $pid " .
					'ORDER BY pt.treatment_order';
				
				$res = _doQuery($treatmentSql);
				header('Pragma: private');
				header('Cache-control: private, must-revalidate');
				header('Content-Type: text/xml');
				echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
				if($res && @mysql_num_rows($res))
				{
					echo '<plan id="'.$pid.'">';
					while($r = @mysql_fetch_array($res))
					{
						echo '<treatment id="'.$r['treatment_id'].'" tname="'.$r['treatment_name'].'" order="'.$r['treatment_order'].'"';
						if (!empty($r['pic1'])) echo ' pic="' . canonicalize($tx['treatment']['media_url'] . $r['treatment_id'] . '/thumb.jpg') . '" ';
						echo ' />';
					}
					echo '</plan>';
				}
				else echo '<plan id="'.$pid.'" />';	
			}
			break;
            case 'getTreatment':
            // get treatment
            $tid = escape($_GET['id']);
            if($tid)
            {
                //$debug_sql_local = true;
                $treatmentSql = "SELECT * FROM treatment t  WHERE  t.treatment_id = '{$tid}' ";
                $res = _doQuery($treatmentSql);
                header('Pragma: private');
                header('Cache-control: private, must-revalidate');
                header('Content-Type: text/xml');
                echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
                if($res && @mysql_num_rows($res))
                {
                    
                    while($r = @mysql_fetch_array($res))
                    {
                        if(!empty($r['treatment_name']) && !empty($r['treatment_id']))
                        {
                            $treatment_path = canonicalize($tx['treatment']['media_url'] . $r['treatment_id']);
                            echo '<treatment id="'.$r['treatment_id'].'" name="'.$r['treatment_name'].'" >';
                            if (!empty($r['pic1']))
                                echo '<image '.' src="'.$treatment_path .'/pic1.jpg' . '" />';
                            
                            if (!empty($r['pic2']))
                                echo '<image '.' src="'.$treatment_path .'/pic2.jpg' . '" />';
                            
                            if (!empty($r['pic3']))
                                echo '<image '.' src="'.$treatment_path .'/pic3.jpg' . '" />';
                            if (!empty($r['video']))
                                echo '<video src="'.$treatment_path .'/' . $r['video'].'" />';
                                
                            if (!empty($r['benefit']))
                                echo '<benefit>' . '<![CDATA[' . $r['benefit'] . ']]>' . '</benefit>';
                            echo '</treatment>';
                        }
                    }
                }
                else
                     echo '<treatment id="'.$tid.'" />';
            }
            break;
            case 'getTags':
            // get tags
                /*$tag_search = escape($_GET['s']);
                $random = escape($_GET['r']);
                //$tagSql = "SELECT distinct(tag_name) FROM tag where tag_name like '%{$tag_search}%' order by tag_name limit 50 ";
                $tagSql = "SELECT  treatment.treatment_tag as tag_name  FROM  treatment   WHERE  treatment.status = 1  AND ( clinic_id IS NULL )  AND ( 
                                    MATCH (treatment.treatment_tag) AGAINST ('{$tag_search}' IN BOOLEAN MODE ) 
                                    )   group by treatment.treatment_id order by  treatment.treatment_counts desc  LIMIT 50 ";
                $result = _doQuery($tagSql);
                
                header('Pragma: private');
                header('Cache-control: private, must-revalidate');
                header('Content-Type: text/xml');
                echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
                if( @mysql_num_rows($result) > 0 ){
                    echo "<tags r='{$random}' >";
                    while( $row = @mysql_fetch_array($result) ){
                        echo "<tag>{$row['tag_name']}</tag>";
                    }
                    echo '</tags>';
                }
                else{
                    echo "<tags r='{$random}' >";
                    echo '</tags>';
                }*/ 
                
            break;
	}
}
elseif(!empty($data))
{
	
	/*
	$fh = fopen('test.xml', 'w');
	fwrite($fh, $HTTP_RAW_POST_DATA);
	fclose($fh);
	*/

	$xml  = simplexml_load_string($data);

	// look for plan id
	$pid = (int) $xml->info['id'];

	if($pid)
	{
		// get treatmetns currently in plan
		$treatmentSql = 'SELECT pt.treatment_id,t.treatment_name '.
					'FROM plan_treatment pt 
					INNER JOIN treatment t on t.treatment_id = pt.treatment_id '.
					'WHERE '." pt.plan_id = $pid " .
					'ORDER BY pt.treatment_order';
		$res = _doQuery($treatmentSql);
		$oldT = array();
		if($res && @mysql_num_rows($res) > 0)
		{
			while($r = @mysql_fetch_array($res))
			{
				if($r['treatment_id'] > 0) $oldT[$r['treatment_id']] = $r['treatment_name'];
			}
		}
		
		// get treatment data
		$order = 1;
		foreach($xml->treatment as $t)
		{
			if($t['id'])
			{
				// check for existing
				$tid = (int) $t['id'];
				if(isset($oldT[$tid]))
				{
					$oldT[$tid] = 0;
					unset($oldT[$tid]);
					$updateSql = " update plan_treatment set treatment_order='".$t['order']."' 
					where treatment_id = $tid and plan_id = $pid ";
					_doQuery($updateSql);
                    //incrementTreatmentCount($tid);  
				}
				else
				{
					_doQuery("DELETE FROM plan_treatment WHERE plan_id=$pid and treatment_id = $tid ");
					$result = select("treatment", '*',' treatment_id = '."'".$tid."'");
					if(is_resource($result)){
						
						if($row = @mysql_fetch_array($result)){
							$ins = array(
								'plan_id' => $pid,
								'treatment_id' => $tid,
								'benefit' => $row['benefit'],
								'instruction' => $row['instruction'],
								'sets' => $row['sets'],
								'reps' => $row['reps'],
								'hold' => $row['hold'],
								'lrb' => $row['lrb'],
								'treatment_order' => $t['order'],
								'creation_date' => date("Y-m-d"),
								'modified' => date("Y-m-d"),
								);	
						}
					}
					insert("plan_treatment", $ins);
                    incrementTreatmentCount($tid);
				}
				$order++;
			}
		}
		
		// cleanup old treatments
		foreach($oldT as $tid => $t_name)
		{
			_doQuery("DELETE FROM plan_treatment WHERE plan_id=$pid and treatment_id = $tid ");
		}
		header('Pragma: private');
		header('Cache-control: private, must-revalidate');
		header('Content-Type: text/xml');
		echo '<reply ok="true" />';
	}
	else
	{
		
		header('Pragma: private');
		header('Cache-control: private, must-revalidate');
		header('Content-Type: text/xml');
		echo '<reply ok="false" reason="No Plan ID Provided" />';
	}
	
}
else
{
	header('Pragma: private');
	header('Cache-control: private, must-revalidate');
	header('Content-Type: text/xml');
	echo '<reply ok="false" reason="No Data" />';
}
$sql_query="";
/**
* @desc 
*/
function formatSearchTag( $tag_search ){
    $tag_search = trim($tag_search);
    if( $tag_search != "" ){
        
        $search_array = explode(" ",$tag_search);
        if( is_array($search_array) && count($search_array) > 0 ){
            foreach( $search_array as $value ){
                $temp = trim($value);
                if( $temp != "" ){
                    if( stopword($temp) === true){
                        continue;
                    }
                    $temp = trim($value);
                    if( is_string(sliceword($temp)) ){
                        $strtmp = sliceword($temp);
                        if(strlen($strtmp) > 2){
                            $str .= "+" . $strtmp . "*"." ";
                        }else {
                            $str .= "+" . $strtmp;
                        } 
                    }
                    else{
                            if(strlen($temp) > 2){
                                $str .= "+" . $temp . "*"." ";
                            }else {
                                $str .= "+" . $temp;
                            } 
                    }
                }
            }
        }
    }
    $str = rtrim($str);
    return $str;    
}
/**
* @desc Increment treatment count
*/
function incrementTreatmentCount( $treatment_id = ""){
    
    if( is_numeric($treatment_id) && $treatment_id > 0 ){
        $query = "update treatment set treatment_counts = treatment_counts + 1 where treatment_id = '{$treatment_id}' ";
        $result = @mysql_query($query);
    }
}
/**
 * Inserts record in table.
 *
 * @param string $table
 * @param array $arr
 * @return resource
 */
function insert($table, $arr) {
				
				
				$result = execute_query("SELECT * FROM $table LIMIT 0,1");
				$columns = @mysql_num_fields($result);
				for ($i = 0; $i < $columns; $i++) {
				  $fieldarr[] = @mysql_field_name($result, $i);
				}
				
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
					  $sqlb .= "'" . addslashes($value) . "',";
					}
				  }
				
				}
				$sqlb .= ")";
				$sql = $sqla . $sqlb;
				 $sql = str_replace(",)", ")", $sql);

				$result = _doQuery($sql);
				return $result;
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
$sql = "SELECT ".$field." FROM `".$table."` WHERE ". primarykey($table) ." = '".$primeVal."';";
    // execute query
    $result = @mysql_query($sql);
    $row = @@mysql_fetch_array($result);
    $ret=$row[$field];
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
				$result = _doQuery($query);	
			}else{
				if(!(isset($_SESSION['username']) && $_SESSION['username'] != "")){
	
					return "";
	
				}else{
					
					$sql= " SELECT user_id FROM user WHERE username =  '{$_SESSION['username']}' and status = 1 " ;
					$rs = _doQuery($sql);
					$user_array = populate_array($rs);
					
					$query = "select * from clinic_user where user_id = '".$user_array['user_id']."'";
					$result = _doQuery($query);					
				
				}
			}
			$row = populate_array($result);
			if(!empty($row)){
				if($field != ""){
					return $field_value = $row[$field];
				}
				return "";
			}
			return "";
}
/**
 * This function checks after execution it return row or not
 *
 * @param array $result
 * @param integer $fetch_type - fetch result type (object, array, row)
 * @return array
 * @access public
 */
function populate_array($result, $fetch_type=""){
				$returnValue = "";
				if(is_resource($result)){
					if(@mysql_num_rows($result) > 0 ){
							switch($fetch_type)
							{
								case '0':
									$array = array();
									while($row = @mysql_fetch_assoc($result)){
										$array[] = $row;
									}
								break;								
								case '1':
									$array = @mysql_fetch_row($result);
								break;
								case '2':
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
						@mysql_free_result($result);
						if(is_array($array) && (count($array) > 0)){
							$returnValue = $array;
						}
					}
				}
				return $returnValue;
}
function execute_query($query) {
					
					if (empty($query)) {
						return 0;
					}
					$result = @mysql_query($query);
					$error = @mysql_error();
					if (!$result){
							echo "<h2>Can't execute query $query</h2>";
							echo "<p><b>Error:</b> ". @mysql_error();
							exit;
					}
					return $result;
}
/**
 * This function formats the relative path of file.
 *
 * @param string $address
 * @return string
 *
 */
function canonicalize($address)
{
	$address = explode('/', $address);
	$keys = array_keys($address, '..');

	foreach($keys AS $keypos => $key)
	{
		array_splice($address, $key - ($keypos * 2 + 1), 2);
	}
	$address = implode('/', $address);
	$address = str_replace('./', '', $address);
	return $address;
}
/**
	 * Escapes a string for use in SQL.
	 * Private, and must be implemented by subclasses.
	 * 
	 */
function _doEscape($str)
{
		if (function_exists("mysql_real_escape_string"))
			return mysql_real_escape_string($str, db::getinstance('../../config.php'));
		else
			return mysql_escape_string($str);
}
/**
	 * Returns a version of the given string escaped for unquoted use in a SQL statement.
	 *
	 * To foil SQL-injection attacks, this truncates the given value at the first whitespace,
	 * semicolon, or SQL comment.  If you are trying to escape a value which could legitimately
	 * contain such characters, you should use escapeString() instead of this function.
	 *
	 * Note that if magic_quotes_gpc is on and Runtime is not set to undo its effects,
	 * and your string came from a request variable (get, post or cookie),
	 * you'll need to call stripslashes() on the string before escaping it.
	 */
function escape($str)
{
		if ($str === NULL) return NULL;

        $terminators = array("\r", "\n", "\t", ' ', ';', "--", "/*");
        $terminators = implode(",",$terminators);
        $str = trim($str,$terminators);
        return _doEscape($str);

		/*for ($i = 0; $i < strlen($str); ++$i)
		{
			$ch = $str{$i};
			if (ctype_space($ch) || $ch == ';')
			{
				$str = substr($str, 0, $i);
				break;
			}
		}

		$commentStrings = array('--', '/*');
		foreach ($commentStrings as $commentString)
		{
			$pos = strpos($str, $commentString);
			if ($pos !== false) $str = substr($str, 0, $pos);
		}

		return _doEscape($str);*/
}
/**
	 * Returns a version of the given string escaped for quoted use in a SQL statement.
	 *
	 * In order to prevent SQL-injection attacks, it's important for the escaped version
	 * of the string to get wrapped in single quotes as it's added to the SQL statement.
	 * So for convenience and safety, by default this function adds the single quotes for you
	 * as it returns the escaped string; pass $autoSingleQuotes as false to override that.
	 *
	 * The escape() method performs additional checks for values which won't be wrapped in quotes,
	 * so you may want to use that function instead of this one in some cases.
	 *
	 * Note that if magic_quotes_gpc is on and Runtime is not set to undo its effects,
	 * and your string came from a request variable (get, post or cookie),
	 * you'll need to call stripslashes() on the string before escaping it.
	 */
	function escapeString($str, $autoSingleQuotes = true)
	{
		if ($str === NULL) return NULL;
		$escapedStr = _doEscape($str);
		return ($autoSingleQuotes) ? "'$escapedStr'" : $escapedStr;
	}
/**
	 * This is a convenience function that returns a count of rows in a table
	 * that match a given condition, or false if an error occurs.
	 *
	 * If you will be accessing the rows, you should call select() (or query()),
	 * and then call ResultSet's numRows().  But, if you just need a count
	 * of matching rows, you can use this function to do it in one call.
	 */
	function countRows($tableName, $where = NULL, $field = '*')
	{
		$results = select($tableName, 'COUNT('.$field.') AS row_count', $where);
		if (!$results) return false;
        $row = @mysql_fetch_array($results);
		return $row['row_count'];
	}
/**
	 * Selects records from a table, and returns a ResultSet (or subclass),
	 * or false if the select failed.
	 *
	 * You can assign the return value by reference for efficiency.<br>
	 * For example: $results =& $dbConn->select('foo');
	 */
	function select($tableName, $columns = '*', $where = NULL, $orderBy = NULL, $numRows = NULL, $beginRow = NULL, $groupBy = NULL )
	{   
		$sql = "SELECT $columns FROM $tableName";

		if (!empty($where))
		{
			if (!strncasecmp(trim($where), "where", 5)) $sql .= " $where";
			else $sql .= " WHERE $where";
		}
        
        if (!empty($groupBy))
        {
            if (!strncasecmp(trim($groupBy), "group by", 8)) $sql .= " $groupBy";
            else $sql .= " GROUP BY $groupBy";
        }
        
		if (!empty($orderBy))
		{
			if (!strncasecmp(trim($orderBy), "order by", 8)) $sql .= " $orderBy";
			else $sql .= " ORDER BY $orderBy";
		}
        
		$limited = false;
		if (!empty($numRows))
		{
			$limited = true;
			$sql = _doAddSqlLimit($sql, $numRows, $beginRow);
		}
        //echo $sql."<br><br><br>";      
		$nativeResults = _doQuery($sql);
		
		if(is_resource($nativeResults)){
			return  $nativeResults;
		}
		return false;

	}

	/**
	 * Selects a page of records from a table, and returns a ResultSet (or subclass),
	 * or false if the select failed.
	 *
	 * You can assign the return value by reference for efficiency.<br>
	 * For example: $results =& $dbConn->selectPage('foo');
	 *
	 * Page numbers begin at 1, so if there are 10 items per page,
	 * page 1 has items 0-9, page 2 has items 10-19, etc.
	 */
	function selectPage($tableName, $columns = '*', $where = NULL, $orderBy = NULL, $numPerPage, $pageNum, $groupBy )
	{
		
        $numPerPage = (int) $numPerPage;
		$pageNum = (int) $pageNum;
		$beginRow = ($pageNum - 1) * $numPerPage;
        
		return select($tableName, $columns, $where, $orderBy, $numPerPage, $beginRow, $groupBy);
	}
/**
	 * Adds a "limit" or "top" clause to the SQL string, as appropriate.
	 * Private, and must be implemented by subclasses.
	 */
	function _doAddSqlLimit($sql, $numRows, $beginRow)
	{
		$sql .= " LIMIT ";
		if ($beginRow) $sql .= "$beginRow,";
		$sql .= $numRows;
		
		return $sql;
	}
	/**
	 * Runs a query against the database and returns the native result.
	 * Private, and must be implemented by subclasses.
	 */
	function _doQuery($sql)
	{
		global $sql_query;
		$sql_query = $sql;
  		return mysql_query($sql, db::getinstance('../../config.php'));
	}
    function stopword($word){
        $stopword = array("a's","able","about","above","according","accordingly","across","actually","after","afterwards","again","against","ain't","all","allow","allows","almost","alone","along","already","also","although","always","am","among","amongst","an","and","another","any","anybody","anyhow","anyone","anything","anyway","anyways","anywhere","apart","appear","appreciate","appropriate","are","aren't","around","as","aside","ask","asking","associated","at","available","away","awfully","be","became","because","become","becomes","becoming","been","before","beforehand","behind","being","believe","below","beside","besides","best","better","between","beyond","both","brief","but","by","c'mon","c's","came","can","can't","cannot","cant","cause","causes","certain","certainly","changes","clearly","co","com","come","comes","concerning","consequently","consider","considering","contain","containing","contains","corresponding","could","couldn't","course","currently","definitely","described","despite","did","didn't","different","do","does","doesn't","doing","don't","done","down","downwards","during","each","edu","eg","eight","either","else","elsewhere","enough","entirely","especially","et","etc","even","ever","every","everybody","everyone","everything","everywhere","ex","exactly","example","except","far","few","fifth","first","five","followed","following","follows","for","former","formerly","forth","four","from","further","furthermore","get","gets","getting","given","gives","go","goes","going","gone","got","gotten","greetings","had","hadn't","happens","hardly","has","hasn't","have","haven't","having","he","he's","hello","help","hence","her","here","here's","hereafter","hereby","herein","hereupon","hers","herself","hi","him","himself","his","hither","hopefully","how","howbeit","however","i'd","i'll","i'm","i've","ie","if","ignored","immediate","in","inasmuch","inc","indeed","indicate","indicated","indicates","inner","insofar","instead","into","inward","is","isn't","it","it'd","it'll","it's","its","itself","just","keep","keeps","kept","know","knows","known","last","lately","later","latter","latterly","least","less","lest","let","let's","like","liked","likely","little","look","looking","looks","ltd","mainly","many","may","maybe","me","mean","meanwhile","merely","might","more","moreover","most","mostly","much","must","my","myself","name","namely","nd","near","nearly","necessary","need","needs","neither","never","nevertheless","new","next","nine","no","nobody","non","none","noone","nor","normally","not","nothing","novel","now","nowhere","obviously","of","off","often","oh","ok","okay","old","on","once","one","ones","only","onto","or","other","others","otherwise","ought","our","ours","ourselves","out","outside","over","overall","own","particular","particularly","per","perhaps","placed","please","plus","possible","presumably","probably","provides","que","quite","qv","rather","rd","re","really","reasonably","regarding","regardless","regards","relatively","respectively","right","said","same","saw","say","saying","says","second","secondly","see","seeing","seem","seemed","seeming","seems","seen","self","selves","sensible","sent","serious","seriously","seven","several","shall","she","should","shouldn't","since","six","so","some","somebody","somehow","someone","something","sometime","sometimes","somewhat","somewhere","soon","sorry","specified","specify","specifying","still","sub","such","sup","sure","t's","take","taken","tell","tends","th","than","thank","thanks","thanx","that","that's","thats","the","their","theirs","them","themselves","then","thence","there","there's","thereafter","thereby","therefore","therein","theres","thereupon","these","they","they'd","they'll","they're","they've","think","third","this","thorough","thoroughly","those","though","three","through","throughout","thru","thus","to","together","too","took","toward","towards","tried","tries","truly","try","trying","twice","two","un","under","unfortunately","unless","unlikely","until","unto","up","upon","us","use","used","useful","uses","using","usually","value","various","very","via","viz","vs","want","wants","was","wasn't","way","we","we'd","we'll","we're","we've","welcome","well","went","were","weren't","what","what's","whatever","when","whence","whenever","where","where's","whereafter","whereas","whereby","wherein","whereupon","wherever","whether","which","while","whither","who","who's","whoever","whole","whom","whose","why","will","willing","wish","with","within","without","won't","wonder","would","would","wouldn't","yes","yet","you","you'd","you'll","you're","you've","your","yours","yourself","yourselves","zero");
        if( in_array($word,$stopword) ){
            return true;
        }
        else
            return false;
    }
    function sliceword($word){
        $input = array('ed', 'ing', 'es', 's', 'tion');
        foreach($input as $value){
            if(  preg_match( "/{$value}$/i" , $word ) ){
                $flag = preg_replace( "/{$value}$/i" , '',$word );
                return $flag;
            }
        }
        return false;
  }

?>