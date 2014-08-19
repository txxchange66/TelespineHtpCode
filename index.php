<?php

ob_start();
session_start();

require_once("include/db.class.php");
require_once("include/common.class.php");
require_once("module/controller.class.php");
require_once("module/view.class.php");
// Personalized GUI Class
require_once("module/lib_gui.class.php");

db::getinstance();
$class_name = controller::httpRequest('action');
if($class_name != "")
{
    $class_name .= ".class.php";
    include_once "module/$class_name";
}
else
{
    echo "class name not found";
}
controller::httpResponse();
?>
