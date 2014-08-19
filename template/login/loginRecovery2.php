<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><!browser_title></title>
	<link rel="STYLESHEET" type="text/css" href="css/styles.css">
	<script language="JavaScript" type="text/javascript" src="js/common.js"></script>
	<style type="text/css">
.error
{
float: left;
    margin-left: 213px;
    position: absolute;
    top: 242px;
}
.error a
{
font-family:Geneva,Verdana,san-serif;
font-size:11px;
color:red;
background:url('images/img_callout_bg_left.png') no-repeat left top;
padding: 10px 0 19px 20px;
	text-decoration: none;
}
.error a:hover
{

	text-decoration: none;
}
.error a span
{
font-family:Geneva,Verdana,san-serif;
font-size:11px;
color:red;
background:url('images/img_callout_bg_right.png') no-repeat right  top;
padding: 10px 6px 19px 0px;
	font-weight: bold;
		text-decoration: none;
}

</style>
	
</head>
<body>

<div id="container">
	<div id="header">
		<!header>
	</div>
	<div id="sidebar">		
        <div style='margin-top:37px;' >        
		<ul class="sideNav">
		<li class="loginBtn"><a href="index.php">LOGIN</a></li>
	  <li style="padding-top:10px; ">
       <a style="font-size:12px; margin-right:-8px; display:block; line-height:15px; padding-top:5px;" href="mailto:support@txxchange.com"><img src="images/icon-email.jpg" align="left" style="margin-right:5px;" /><span style="display:block; width:105px; float:left; padding-top:5px;">Question? Email TX Support</span>
        </a><div style="clear:both;"></div>
      </li>

		
        </ul>
		<ul>&nbsp;</ul>
		</div>
	</div>	
		
<div id="mainContent" style="padding-top:4px;" >
<h1 class="largeH1">PASSWORD RECOVERY</h1>
<div style="padding:10px;color:red"><!error></div>
<script language="JavaScript" src="js/validateform.js"></script>
<script language="JavaScript">
<!--
window.formRules = new Array(	
	new Rule("question_id", "question", true, "integer"),
	new Rule("answer", "answer", true, "string|0,250"));
// -->
</script>

<form name="retrieve_form" id="retrieve_form" method="POST" action="index.php?action=loginRecovery2" onSubmit="return validate_form(this);">
<table border="0" cellpadding="2" cellspacing="0" width="100%" class="form_container">
<tr class="inputRow">
	<td><label for="question_id" onMouseOver="help_text(this, 'Choose the secret question')")>*&nbsp;Secret Question&nbsp;</label></td>

	<td><select name="question_id" id="question_id" style="width:220px;" onMouseOver="help_text(this, 'Choose the secret question')">
		<!questionOptions>
		</select>	

</td>
</tr>
<tr class="inputRow">
	<td><div style="width:160px"><label for="answer" onMouseOver="help_text(this, 'Enter the answer for the secret question')")>*&nbsp;Answer&nbsp;</label></div></td>
	<td width="100%"><input type="text" name="answer" id="answer" style="width: 215px;"  onMouseOver="help_text(this, 'Enter the answer for the secret question')" value="<!answer>"/><!callout></td>
</tr>
<tr class="inputRow">
	<td>&nbsp;</td>
	<td>
	<input type="hidden" name="email_address" id="email_address" value="<!email_address>"/>
	<input type="hidden" name="submit" id="submit" value="Retrieve Password2"/>
	<input type="submit" name="submitted" value="Retrieve Password"></td>
</tr>
</table>
</form>
		</div>
	</div>
	<div id="footer">
		<!footer>
	</div>
</div>