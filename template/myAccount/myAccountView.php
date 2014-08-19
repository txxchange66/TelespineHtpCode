<div id="container">
	<div id="header">
		<!header>
	</div>
	
	<div id="sidebar">
		<!sidebar>
	</div>
	
		
<div id="mainContent">
		<!--
		<img src="skin/tx/images/icons/user48.png" width="48" height="48" alt="" hspace="8">
		Subscriber Manager	-->
			<!-- <p class="breadcrumbNav"><a href="#">Breadcrumb Nav</a></p> -->
<table style="vertical-align:middle;width:700px;"><tr><td style="width:400px; ">
	<div id="breadcrumbNav"  style=" padding-bottom:40px; padding-top:40px;"><a href="index.php?action=viewMyAccount" >MY ACCOUNT</a> / <span class="highlight">VIEW ACCOUNT INFORMATION</span></div>
	</td><td style="width:300px;"></td></tr></table>
<script language="JavaScript" src="js/validateform.js"></script>
<script language="JavaScript">
<!--
window.formRules = new Array(
	//new Rule("username", "username", true, "string|4,20"),
	new Rule("username", "username", true, "email"),
	new Rule("question_id", "question", true, "integer"),
	new Rule("answer", "answer", true, "string|0,50"),
	new Rule("name_title", "title", false, "string|0,5"),
	new Rule("name_first", "first name", true, "string|0,50"),
	new Rule("name_last", "last name", true, "string|0,50"),
	new Rule("name_suffix", "name suffix", false, "string|0,5"),
	new Rule("address", "address", false, "string|0,50"),
	new Rule("address2", "address line 2", false, "string|0,50"),
	new Rule("city", "city", false, "string|0,50"),
	new Rule("state", "state", false, "string|0,2"),
	new Rule("zip", "zip code", false, "zipcode"),
	//new Rule("email_address", "the users email address", true, "email"),
	new Rule("phone1", "Subscriber's 1st phone number", false, "usphone"),
	new Rule("phone2", "Subscriber's 2nd phone number", false, "usphone"),
	new Rule("fax", "Subscriber's fax number", false, "usphone"),
	new Rule("new_password", "new password", false, "string|4,20"),
	new Rule("new_password2", "confirm password", false, "string|4,20"),
	new Rule("usertype_id", "user type", true, "integer"),
	new Rule("status", "user status", false, "integer"));
// -->
</script>

<script type="text/javascript">
<!--

function handle_action(s, id)
{
	var a = s.options[s.options.selectedIndex].value;
	var c = false;

	switch (a)
	{
		case 'delete':
			c = confirm('Deleting this user will remove him/her completely from the system.  Are you sure you would like to continue with deleting this user?');
			break;
		default:
			c = true;
			break;
	}

	s.options.selectedIndex = 0;

	if (c) window.location.href = '/admin/user_detail.php?' + 'act=' + a + '&id=' + id;
}


//-->
</script>
<!-- [title and common actions] -->

<!-- [/title and common actions] -->
<h1 class="largeH1" style="margin-top:-1px; height:26px;">Profile Information&nbsp;<small><!--<a href="index.php?action=editMyAccount&id=<!user_id>">[ Edit Mode ]</a>--></small></h1>
<br/><table cellpadding="2"><tr><td valign="top"><img width="149" height="162" src="<!propic>" align="left" style="padding-right:15px;"></td><td valign="top"><!therapistInfo><br/><a href="index.php?action=editMyAccount&id=<!user_id>"><input type="button" name="edit" value="Edit" onClick="location.href='index.php?action=editMyAccount&id=<!user_id>';"></a></td></tr></table>
		</div>
	</div>
	<div id="footer">
		<!footer>
	</div>
</div>