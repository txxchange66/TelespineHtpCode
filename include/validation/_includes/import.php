<?php
function import($a_str_packageClassName)
{
	$str = $a_str_packageClassName;
	$arr = explode(".", $str);
	// import all
	if ($arr[count($arr)-1] == "*")
	{
		$w = array_pop(&$arr);
		$dirPath = "include/validation/_includes/". join("/", $arr);
		
		if ($handle = opendir($dirPath))
		{
			while (false !== ($file = readdir($handle)))
				if ($file != "." && $file != "..")
				{
					if (file_exists($dirPath ."/". $file) && !is_dir($dirPath."/". $file))
					{
						require_once($dirPath ."/". $file);
					}
				}
			closedir($handle);
		} 
	}
	else
	{
		$pi = pathinfo($_SERVER['PHP_SELF']);
		$dir = $pi['dirname'];
		$filePath = "_includes/phplib/". join("/", $arr) . ".php";
		if (file_exists($filePath))
		{
			require_once($filePath);
		}
		else
		{
			echo $filePath . " does not exist<br/>";
		}
	}
}

?>