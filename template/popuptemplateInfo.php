<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title><!browserTitle></title>
	<meta name="description" content="<!metaDesc>">
	<link href="css/styles_popup.css" rel="stylesheet" type="text/css" media="screen" />  
	<script language="JavaScript" type="text/javascript" src="js/common.js"></script>
    <script language="Javascript" >
    function close_window(){
        parent.parent.GB_CURRENT.hide(); 
    }
    </script>
</head>
<body>
<div id='containerPopupInfo' style="padding-left:20px;">
		<div id="infoContent">
		    <!mainRegion>
		</div>
        <div  style="margin-top:25px;">
            <input type="button" name="clear" value=" Close " onClick="close_window();" />
            </div>
</div>

</body>
</html>