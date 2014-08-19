<?php
/** \file
 * Utility functions for the rich text editor.  These utilities are similar to the
 * form utils, and should be used in the same way.
 *
 * Version 2
 *
 * Copyright 2003-2004 303software.  All Rights Reserved.  This software
 * is protected by U.S. copyright law and international copyright treaty.
 * This file is licensed for your use, but you may not redistribute it.
 * See copyright.txt for details.
 */

include_once(dirname(__FILE__) . '/../runtime/_runtime.php');


/**
 * Makes the HTML for a text input, and fills it in with its current value if possible.
 * Returns the result as a string.
 *
 * $lookIn should be an associative array to look for the current value of the form field
 * in, such as $_GET, $_POST, or $GLOBALS.  It is passed by reference for efficiency.
 * You can also pass an empty array here if for some odd reason that suits you.
 * $mode can be any number of predefined toolbar configurations (check RichTextarea_loadCfg()
 *   in the javascript).
 * $height and $width will default to 200px and 100% respectively if not passed.
 */
function make_richtextarea($varName, &$lookIn, $mode = 0, $height = NULL, $width = NULL, $includeCSS = true, $baseUrl = NULL, $imagePath = NULL, $imageURL = NULL)
{
	global $siteCfg;

	if (!$height) $height = '200px';
	if (!$width) $width = '100%';
	if ($includeCSS) $includeCSS = 'true';
	else $includeCSS = 'false';

	$ret = '<script language="JavaScript" type="text/javascript">'."\n";
	$ret .= "<!--\n";
	$ret .= "if (!window.rta) rta = new RichTextarea('".$siteCfg['runtime']['url']."richtextarea/');\n";
	$ret .= "rta.writeRichTextarea(window.document, '".$varName."', ".$mode.", '".((!empty($lookIn[$varName])) ? javascript_safe_string($lookIn[$varName]) : '&nbsp;')."', '".$height."', '".$width."', ".$includeCSS.", '".$baseUrl."', '".$imagePath."', '".$imageURL."');\n";
	$ret .= "//-->\n";
	$ret .= '</script>'."\n";

	return $ret;
}

/**
 * Return html "<SCRIPT>" tag linking to necessary JavaScript files
 */
function RTA_make_include()
{
	global $siteCfg;
	
	return '<script language="javascript" type="text/javascript" src="' . 
		$siteCfg['runtime']['url'].'richtextarea/richtextarea.js"></script>' . "\n";
}

function javascript_safe_string($str)
{
	// convert all types of single quotes
	$str = str_replace(chr(145), chr(39), $str);
	$str = str_replace(chr(146), chr(39), $str);
	$str = str_replace("'", "&#39;", $str);

	// convert all types of double quotes
	$str = str_replace(chr(147), chr(34), $str);
	$str = str_replace(chr(148), chr(34), $str);

	// replace carriage returns & line feeds
	$str = str_replace(chr(10), " ", $str);
	$str = str_replace(chr(13), " ", $str);

	return $str;
}

?>
