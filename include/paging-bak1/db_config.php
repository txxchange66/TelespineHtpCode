<?php 
// modify these constants to fit your environment
if (!defined("DB_SERVER")) define("DB_SERVER", "localhost");
if (!defined("DB_NAME")) define("DB_NAME", "theklass");
if (!defined("DB_USER")) define ("DB_USER", "");
if (!defined("DB_PASSWORD")) define ("DB_PASSWORD", "");

// some external constants to controle the output
define("QS_VAR", "page"); // the variable name inside the query string (don't use this name inside other links)
define("NUM_ROWS",2); // the number of records on each page

define("STR_FWD", "Next&gt;&gt;"); // the string is used for a link (step forward)
define("STR_BWD", "&lt;&lt;Previous"); // the string is used for a link (step backward)

// use the rught pathes to get it working with the php function getimagesize
//define("IMG_FWD", ".e/paging/forward.gif"); // the image for forward link 
//define("IMG_BWD", "./backward.gif"); // the image for backward link 

define("NUM_LINKS", 5); // the number of links inside the navigation (the default value)
?>