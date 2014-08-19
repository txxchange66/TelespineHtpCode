<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title><!browserTitle></title>
<meta name="description" content="<!metaDesc>">
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;	font-size: 10px;
	color: #333333;
}
body {
	background-color: #FFFFFF;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
<script type="text/javascript" language="JavaScript" src="js/swfobject.js"></script>
<script type="text/javascript" language="JavaScript" src="js/jquery.js"></script>
</head>

<body>                        
<div style="width:990;margin:auto;" align="center">
<div id="flashcontent">
<p>Please upgrade your flash player.</p></div>
<script type="text/javascript" language="Javascript">
// <![CDATA[
	var so = new SWFObject("asset/flash/plan_viewer.swf?rand=<!rand>", "plancreator", "969", "688", "8", "#FFFFFF");
	so.addVariable("plan_id", '<!planId>' );
    //so.addParam("scale", "showall");
	so.write("flashcontent");
// ]]>
			</script>
            </div>
<script language="Javascript">
$(document).ready(function() {
var flag = '<!flag>';
if( flag == '1' ){
        var planId = '<!planId>';            
        var userId = '<!userId>';
        $.post('index.php?action=insert_record_plan_view',{plan_id:planId,user_id:userId}, function(data,status){
                    /*if( status == "success" ){
                        alert(data);
                    }*/
                }
        );           
}
});
</script>
</body>
</html>