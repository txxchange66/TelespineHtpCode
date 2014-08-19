<?php
@session_start();
/**
 * This file helps in showing plan view.
 * 1) This file returns article and treatment of any plan.
 * 3) Plan id has to be passed in the url.
 */
require_once ('../../config.php');

//if(empty($_SESSION[$siteCfg['customer']['session']])) UserManager::checkLoginRedirect(25, $_SERVER['REQUEST_URI']);

//echo $_SERVER['DOCUMENT_ROOT'];
//echo '<pre>';
//print_r($_SESSION);exit;
if( isset($_SESSION['planViewType']) && $_SESSION['planViewType'] == "treatment" ){
	
	if( isset($_GET['id']) )
	{
		$id = $_GET['id'];
		$arr = explode("_",$id);
		if(in_array("t",$arr)){
			$id = $arr[0];
		}
		$host = $txxchange_config['dbconfig']['db_host_name'];
		$user = $txxchange_config['dbconfig']['db_user_name'];
		$pass = $txxchange_config['dbconfig']['db_password'];
		$dbName = $txxchange_config['dbconfig']['db_name'];

		// Connect database.
		$dbLink = mysql_connect($host, $user, $pass, true); 
	
		// Select database.
		mysql_select_db($dbName, $dbLink);	

		
		// Retrive treatments of plan id.
		$treatmentSql = 'SELECT treatment_id, clinic_id, treatment_name, pic1, pic2, pic3, video, 
				sets, reps, hold, instruction, benefit, lrb FROM treatment where treatment_id  = ' . escape($id) . ' '; 
		
		header('Pragma: private');
		header('Cache-control: private, must-revalidate');
		header('Content-Type: text/xml');
				
		echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
		echo '<plan id="' . $id . '_t">';
		$res = mysql_query($treatmentSql);
		if($res !== false)
		{	$clinic_logo=$txxchange_config['images_url']."/images/img-logo-print.gif";
			while($r = mysql_fetch_array($res))
			{
				if(!empty($r['treatment_id']))
				{
					//code to find out clinic logo image of respective treatment if exist otherwise default is shown.
				    echo '<treatment id="' . $r['treatment_id'] . '" name="' . $r['treatment_name'] . '" order="' . "1" . '">';
				
					if(!empty($r['pic1']))
					{
						//echo '<image order="0" src="'. canonicalize($siteCfg['tx']['treatment']['media_url'] . $r['treatment_id']) . '/thumb.jpg" />';
						echo '<image order="1" src="'. canonicalize($txxchange_config['tx']['treatment']['media_url'] . $r['treatment_id']) . '/'.$r['pic1'].'"/>';
					}
					if(!empty($r['pic2'])) echo '<image order="2" src="'. canonicalize($txxchange_config['tx']['treatment']['media_url'] . $r['treatment_id']) . '/'.$r['pic2'].'"/>';
					if(!empty($r['pic3'])) echo '<image order="3" src="'. canonicalize($txxchange_config['tx']['treatment']['media_url'] . $r['treatment_id']) . '/'.$r['pic3'].'"/>';
					if(!empty($r['video'])) echo '<video src="'. canonicalize($txxchange_config['tx']['treatment']['media_url'] . $r['treatment_id']) . '/' . $r['video'] .'" />';
					
                    $lrb = $txxchange_config['lrb'][$r['lrb']];
					$instruction_xml = '<instruction sets="'.((!empty($r['sets'])) ? $r['sets'] : $r['sets'] ).
						'" reps="'.((!empty($r['reps'])) ? $r['reps'] : $r['reps'] ).
						'" hold="'.((!empty($r['hold'])) ? $r['hold'] : $r['hold'] ).
                        '" lrb="'.((!empty($r['lrb'])) ? strtoupper($lrb) : '' ).'">';
						
					if(!empty($r['instruction'])) $instruction_xml .= '<![CDATA[' . $r['instruction'] . ']]>';
					//elseif(!empty($r['instruction'])) $instruction_xml .= '<![CDATA[' . $r['instruction'] . ']]></instruction>';
					$instruction_xml .= '</instruction>';
					echo $instruction_xml;
                    if(!empty($r['benefit'])) echo '<benefit>'.$r['benefit'].'</benefit>';
					echo '</treatment>';
					
				}
			}
           	if(!empty($_SESSION['username']))
            {
                $sqluser="select user_id from user where username='".$_SESSION[username]."' and status=1";
                $resuser=@mysql_query($sqluser);
                $userId=mysql_result($resuser,0,0);
                $sqlclinic="select clinic_id from clinic_user where user_id=".$userId;
                $resclinic=@mysql_query($sqlclinic);
                $clinicId=@mysql_result($resclinic,0,0);
                $clinicLogoSql = 'SELECT clinic_logo FROM clinic where clinic_id  = '.$clinicId;
                $clinicRes=mysql_query($clinicLogoSql);
				$clinicLogoRow=mysql_fetch_object($clinicRes);
                if(empty($clinicLogoRow->clinic_logo))
                    {
                        $clinic_logo=$txxchange_config['images_url']."/images/img-logo-print.gif";
					}
					else
					{
					   $clinic_logo=$txxchange_config['images_url']."/asset/images/clinic_logo/".$clinicLogoRow->clinic_logo;
                    }
			}
           echo "<clinic_logo><![CDATA[".$clinic_logo."]]></clinic_logo>";
            
		}

		echo '</plan>';
		
	}
	unset($_SESSION['planViewType']);
	unset($_GET['id']);
	return;
}


if( isset($_GET['id'])  )
{
	$id = trim($_GET['id']);
	
	$host = $txxchange_config['dbconfig']['db_host_name'];
	$user = $txxchange_config['dbconfig']['db_user_name'];
	$pass = $txxchange_config['dbconfig']['db_password'];
	$dbName = $txxchange_config['dbconfig']['db_name'];

	
	// Connect database.
	$dbLink = mysql_connect($host, $user, $pass, true); 
	
	// Select database.
	mysql_select_db($dbName, $dbLink);	
	
	// Retrive treatments of plan id.
	$treatmentSql = 'SELECT pt.treatment_id, t.treatment_name,t.clinic_id, pt.treatment_order,t.pic1, t.pic2, t.pic3, t.video, 
				pt.sets, pt.reps, pt.hold, pt.instruction, pt.lrb, pt.benefit FROM plan p
				inner join plan_treatment pt on pt.plan_id = p.plan_id
				inner join treatment t on t.treatment_id = pt.treatment_id
				where p.plan_id  = ' . escape($id) . ' ' . 	'ORDER BY pt.treatment_order'; 
	
	// Retrive articles of plan id.
	$articleSql = 'SELECT pa.article_id, a.article_name, a.link_url, a.article_type, a.file_url,a.file_path FROM plan p
					inner join plan_article pa on pa.plan_id = p.plan_id 
					inner join article a on a.article_id = pa.article_id and a.status = 1
					where p.plan_id = ' . escape($id) . ' ' . 'ORDER BY a.article_name';
	

	header('Pragma: private');
	header('Cache-control: private, must-revalidate');
	header('Content-Type: text/xml');
	echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
	echo '<plan id="' . $id . '">';
	
	$res = mysql_query($treatmentSql);
	
	if($res !== false)
	{	$clinic_logo=$txxchange_config['images_url']."/images/img-logo-print.gif";
		while($r = mysql_fetch_array($res))
		{
			if(!empty($r['treatment_id']))
			{ 
				echo '<treatment id="' . $r['treatment_id'] . '" name="' . $r['treatment_name'] . '" order="' . $r['treatment_order'] . '">';
				
				if(!empty($r['pic1']))
				{
					//echo '<image order="0" src="'. canonicalize($siteCfg['tx']['treatment']['media_url'] . $r['treatment_id']) . '/thumb.jpg" />';
					echo '<image order="1" src="'. canonicalize($txxchange_config['tx']['treatment']['media_url'] . $r['treatment_id']) . '/'.$r['pic1'].'"/>';
				}
				if(!empty($r['pic2'])) echo '<image order="2" src="'. canonicalize($txxchange_config['tx']['treatment']['media_url'] . $r['treatment_id']) . '/'.$r['pic2'].'"/>';
				if(!empty($r['pic3'])) echo '<image order="3" src="'. canonicalize($txxchange_config['tx']['treatment']['media_url'] . $r['treatment_id']) . '/'.$r['pic3'].'"/>';
				if(!empty($r['video'])) echo '<video src="'. canonicalize($txxchange_config['tx']['treatment']['media_url'] . $r['treatment_id']) . '/' . $r['video'] .'" />';
				
                 $lrb = $txxchange_config['lrb'][$r['lrb']];   
				$instruction_xml = '<instruction sets="'.((!empty($r['sets'])) ? $r['sets'] : $r['sets'] ).
					'" reps="'.((!empty($r['reps'])) ? $r['reps'] : $r['reps'] ).
					'" hold="'.((!empty($r['hold'])) ? $r['hold'] : $r['hold'] ).
                    '" lrb="'.((!empty($r['lrb'])) ? strtoupper($lrb) : '' ).'">';
					
				if(!empty($r['instruction'])) $instruction_xml .= '<![CDATA[' . $r['instruction'] . ']]>';
				//elseif(!empty($r['instruction'])) $instruction_xml .= '<![CDATA[' . $r['instruction'] . ']]></instruction>';
				$instruction_xml .= '</instruction>';
				echo $instruction_xml;
                if(!empty($r['benefit']))echo '<benefit>'.$r['benefit'].'</benefit>';
				echo '</treatment>';
				}
		}
       	if(!empty($_SESSION['username']))
            {
                $sqluser="select user_id from user where username='".$_SESSION['username']."'  and status=1";
                $resuser=@mysql_query($sqluser);
                $userId=mysql_result($resuser,0,0);
                $sqlclinic="select clinic_id from clinic_user where user_id=".$userId;
                $resclinic=@mysql_query($sqlclinic);
                $clinicId=@mysql_result($resclinic,0,0);
                $clinicLogoSql = 'SELECT clinic_logo FROM clinic where clinic_id  = '.$clinicId;
                $clinicRes=mysql_query($clinicLogoSql);
				$clinicLogoRow=mysql_fetch_object($clinicRes);
                if(empty($clinicLogoRow->clinic_logo))
                    {
                        $clinic_logo=$txxchange_config['images_url']."/images/img-logo-print.gif";
					}
					else
					{
					   $clinic_logo=$txxchange_config['images_url']."/asset/images/clinic_logo/".$clinicLogoRow->clinic_logo;
                    }
			}
           echo "<clinic_logo><![CDATA[".$clinic_logo."]]></clinic_logo>";
        
        
		
	}
	
	$res = mysql_query($articleSql);
	
	if($res !== false)
	{
		while($r = mysql_fetch_array($res))
		{
			if(!empty($r['article_id']))
			{
				if($r['article_type'] == 1) 
					echo '<article name="'.$r['article_name'].'" href="'.$r['link_url'].'" />';
				elseif($r['article_type'] == 2) 
					echo '<article name="'.$r['article_name'].'" href="'.$txxchange_config['images_url'].canonicalize($txxchange_config['tx']['article']['media_url'] . $r['file_path']).'" />'; 
			}
		}
	}
	
	echo '</plan>';
	//echo "PlanId".$_GET['id'];
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

	for ($i = 0; $i < strlen($str); ++$i)
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

	return _doEscape($str);
}
/**
	 * Escapes a string for use in SQL.
	 * Private, and must be implemented by subclasses.
	 * 
	 */
function _doEscape($str)
{
	if (function_exists("mysql_real_escape_string"))
		return mysql_real_escape_string($str);
	else
		return mysql_escape_string($str);
}
?>