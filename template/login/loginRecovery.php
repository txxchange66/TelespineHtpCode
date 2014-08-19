<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

	<title><!browser_title></title>

	<link rel="STYLESHEET" type="text/css" href="css/styles.css">

	<script language="JavaScript" type="text/javascript" src="js/common.js"></script>	

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
       <a style="font-size:12px; margin-right:-8px; display:block; line-height:15px; padding-top:5px;" href="mailto:support@txxchange.com"><img src="images/icon-email.jpg" align="left" style="margin-right:5px;" /><span style="display:block; width:105px; float:left; padding-top:5px;">Question?<br /> Email Support</span>
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

	

	new Rule("email_address", "email address", true, "email"));

// -->

</script>



<form name="retrieve_form" id="retrieve_form" method="POST" action="index.php?action=loginRecovery" onSubmit="return validate_form(this);">

<table border="0" cellpadding="2" cellspacing="0" width="100%" class="form_container">



<tr class="inputRow">

	<td><div style="width:115px"><label for="email_address" onMouseOver="help_text(this, 'Enter your email address')" >*&nbsp;Email Address:&nbsp;</label></div></td>

	<td width="100%"><input type="text" onMouseOver="help_text(this, 'Enter your email address')" name="email_address" id="email_address" size="30" maxlength="100" value="<!email_address>"/></td>

</tr>

<tr class="inputRow">

	<td>&nbsp;</td>

	<td><input type="submit" name="submitted" value="Retrieve Password"></td>

</tr>

</table>

</form>

		</div>

	</div>

	<div id="footer">

		<!footer>

	</div>

</div>