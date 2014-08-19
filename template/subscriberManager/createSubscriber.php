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
<table style="vertical-align:middle;width:700px;"><tr><td style="width:400px;"><div id="breadcrumbNav"><a href="index.php?action=subscriberListing" >SUBSCRIBER</a> / <span class="highlight">CREATE SUBSCRIBER</span></div></td><td style="width:300px;">	<table border="0" cellpadding="5" cellspacing="0" style="float:right;">

		<tr>
			<td class="iconLabel"><a href="index.php?action=createSubscriber"><img src="images/createNewSubscriber.gif" width="127" height="81" alt=""></a></td>
		</tr>
	</table>
	</td></tr></table><script language="JavaScript" src="js/validateform.js"></script>
<script language="JavaScript">
<!--
/*window.formRules = new Array(
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
	//new Rule("phone1", "Subscriber's 1st phone number", false, "usphone"),
	//new Rule("phone2", "Subscriber's 2nd phone number", false, "usphone"),
	//new Rule("fax", "Subscriber's fax number", false, "usphone"),
	new Rule("new_password", "new password", false, "string|4,20"),
	new Rule("new_password2", "confirm password", false, "string|4,20"),
	new Rule("usertype_id", "user type", true, "integer"),
	new Rule("status", "user status", false, "integer"));*/
// -->
</script>
<script src="js/jquery.js"></script>
<script src="js/countryState.js"></script>

<script type="text/javascript">
<!--
function showClinic()
{
	if(!csw) var csw = window.open('index.php?action=PopupClinicList', 'clinicSelectWindow', 'width=750, height=580, status=no, toolbar=no, resizable=yes, scrollbars=yes');
	csw.focus();
}

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
function isValidEmail(emailAddress) {
     var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
     return re.test(emailAddress);
}


function handle_type_change(s)
{
	var div1 = document.getElementById('selClinictd1');
	var div2 = document.getElementById('selClinictd2');
	switch(s.options[s.selectedIndex].value)
	{
		case '2':
			div1.style.display = 'block';
			div2.style.display = 'block';
			break;
		case '3':
			div2.style.display = 'block';
			div1.style.display = 'block';
			break;
			break;
		case '4':
			div2.style.display = 'none';
			div1.style.display = 'none';
			break;	
		default:
			div1.style.display = div2.style.display = 'none';
			break;
	}
}

//-->
</script>
<!-- [title and common actions] -->

<!-- [/title and common actions] -->
<h1 class="largeH1">Creating New Subscriber</h1><!-- [user detail form] -->
<div style="padding:10px;color:red"><!error></div>

<form name="detailform" id="detail_form" enctype="multipart/form-data" method="POST" action="index.php?action=createSubscriber" onsubmit="if (isValidEmail(document.forms['detailform'].username.value)) { return true; } else { alert('NOT a valid email address');return false; }">
<input type="hidden" name="submitted" value="1" />
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
<tr>
	<td colspan="2"><h3>Subscriber Details</h3></td>
</tr>
<tr class="inputRow">
	<td><div style="width:160px"><label for="username" onMouseOver="help_text(this, 'Enter the email address used for this Subscriber to login to the application for accessing it.')")>*&nbsp;Login&nbsp;</label></div></td>

	<td width="100%"><input type="text" name="username" id="username" size="20" maxlength="50" onMouseOver="help_text(this, 'Enter the email address used for this Subscriber to login to the application for accessing it.')" value="<!username>"/></td>
</tr>
<tr class="inputRow">
	<td><div style="width:160px"><label for="new_password" onMouseOver="help_text(this, 'Enter the Subscriber\'s password (type a new password here to reset it)')")>New Password&nbsp;</label></div></td>
	<td width="100%"><input type="password" name="new_password" id="new_password" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the Subscriber\'s password (type a new password here to reset it)')"/></td>
</tr>
<tr class="inputRow">
	<td><div style="width:160px"><label for="new_password2" onMouseOver="help_text(this, 'Enter the Subscriber\'s password (must match New Password)')")>Confirm Password&nbsp;</label></div></td>
	<td width="100%"><input type="password" name="new_password2" id="new_password2" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the Subscriber\'s password (must match New Password)')"/></td>
</tr>
<!-- commented in 1010 release. Need to be removed.
<tr class="inputRow">
	<td><label for="question_id" onMouseOver="help_text(this, 'Choose the secret question')")>*&nbsp;Secret Question&nbsp;</label></td>

	<td><select name="question_id" id="question_id" onMouseOver="help_text(this, 'Choose the secret question')">
		<!questionOptions>
		</select>	

</td>
<tr class="inputRow">
	<td><div style="width:160px"><label for="answer" onMouseOver="help_text(this, 'Enter the answer for the secret question')")>*&nbsp;Answer&nbsp;</label></div></td>
	<td width="100%"><input type="text" name="answer" id="answer" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the answer for the secret question')" value="<!answer>"/></td>
</tr>
</tr>
-->

<tr>
	<td colspan="2"><h3>Subscriber Contact Information</h3></td>
</tr>
<tr class="inputRow">
	<td><div style="width:160px"><label for="name_first" onMouseOver="help_text(this, 'Enter the Subscriber\'s first name')")>*&nbsp;First Name&nbsp;</label></div></td>
	<td width="100%"><input type="text" name="name_first" id="name_first" size="50" maxlength="20" onMouseOver="help_text(this, 'Enter the Subscriber\'s first name')" value="<!name_first>"/></td>
</tr>
<tr class="inputRow">
	<td><div style="width:160px"><label for="name_last" onMouseOver="help_text(this, 'Enter the Subscriber\'s last name')")>*&nbsp;Last Name&nbsp;</label></div></td>

	<td width="100%"><input type="text" name="name_last" id="name_last" size="50" maxlength="20" onMouseOver="help_text(this, 'Enter the Subscriber\'s last name')" value="<!name_last>"/></td>
</tr>
<tr class="inputRow">
	<td><div style="width:160px"><label for="address" onMouseOver="help_text(this, 'Enter the Subscriber\'s address')")>Address&nbsp;</label></div></td>
	<td width="100%"><input type="text" name="address" id="address" size="50" maxlength="50" onMouseOver="help_text(this, 'Enter the Subscriber\'s address')" value="<!address>"/></td>
</tr>
<tr class="inputRow">
	<td><div style="width:160px"><label for="address2" onMouseOver="help_text(this, 'Enter the Subscriber\'s address line 2')")>Address 2&nbsp;</label></div></td>
	<td width="100%"><input type="text" name="address2" id="address2" size="50" maxlength="50" onMouseOver="help_text(this, 'Enter the Subscriber\'s address line 2')" value="<!address2>"/></td>
</tr>

<tr class="inputRow">
	<td><div style="width:160px"><label for="city" onMouseOver="help_text(this, 'Enter the Subscriber\'s city')")>City&nbsp;</label></div></td>
	<td width="100%"><input type="text" name="city" id="city" size="50" maxlength="20" onMouseOver="help_text(this, 'Enter the Subscriber\'s city')" value="<!city>"/></td>
</tr>
<tr class="inputRow">
	<td><div style="width:160px"><label for="conutry" onMouseOver="help_text(this, 'Enter the Subscriber\'s country')")>Country&nbsp;</label></div></td>
	<td width="100%"><select id="country" onchange="toggleState();" name="clinic_country" tabindex="5" >

							<!patient_country_options>
							  </select>
</td>
</tr>
<tr class="inputRow">
	<td><div style="width:160px"><label for="state" onMouseOver="help_text(this, 'Enter the Subscriber\'s state')")>State / Province&nbsp;</label></div></td>
	<td width="100%"><select name="state" id="clinic_state" onMouseOver="help_text(this, 'Enter the Subscriber\'s state')">

<!stateOptions>
</select>
</td>
</tr>
<tr class="inputRow">
	<td><div style="width:160px"><label for="zip" onMouseOver="help_text(this, 'Enter the Subscriber\'s zip code')")>Zip Code&nbsp;</label></div></td>

	<td width="100%"><input type="text" name="zip" id="zip" size="10" maxlength="7" onMouseOver="help_text(this, 'Enter the Subscriber\'s zip code')" value="<!zip>"/></td>
</tr>
<!--
<tr class="inputRow">
	<td><div style="width:160px"><label for="email_address" onMouseOver="help_text(this, 'Enter the Subscriber\'s email address so the they can receive email messages')")>*&nbsp;Email Address&nbsp;</label></div></td>
	<td width="100%"><input type="text" name="email_address" id="email_address" size="50" maxlength="150" onMouseOver="help_text(this, 'Enter the Subscriber\'s email address so the they can receive email messages')" value="<!email_address>"/></td>
</tr>
-->
<tr class="inputRow">
	<td><div style="width:160px"><label for="phone1" onMouseOver="help_text(this, 'Enter the Subscriber\'s 1st phone number')")>1st Phone&nbsp;</label></div></td>
	<td width="100%"><input type="text" name="phone1" id="phone1" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the Subscriber\'s 1st phone number')" value="<!phone1>"/></td>

</tr>
<tr class="inputRow">
	<td><div style="width:160px"><label for="phone2" onMouseOver="help_text(this, 'Enter the Subscriber\'s 2nd phone number')")>2nd Phone&nbsp;</label></div></td>
	<td width="100%"><input type="text" name="phone2" id="phone2" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the Subscriber\'s 2nd phone number')" value="<!phone2>"/></td>
</tr>
<tr class="inputRow">
	<td><div style="width:160px"><label for="fax" onMouseOver="help_text(this, 'Enter the Subscriber\'s fax number')")>Fax&nbsp;</label></div></td>
	<td width="100%"><input type="text" name="fax" id="fax" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the Subscriber\'s fax number')" value="<!fax>"/></td>
</tr>
<tr>
	<td colspan="2"><h3>Subscriber Permissions</h3></td>

</tr>
<tr class="inputRow">
	<td><label for="usertype_id" onMouseOver="help_text(this, 'Choose the Subscriber\'s access level type')")>*&nbsp;User Type&nbsp;</label></td>
	<td><select name="usertype_id" id="usertype_id" onChange="handle_type_change(this)" onMouseOver="help_text(this, 'Choose the Subscriber\'s access level type')">
<!optionsUserType>
</select>
</td>
</tr>
<tr>
<td colspan="2" width="100%">
<table id="selClinictd1" width="100%" cellspacing="0" cellpadding="2" style="display:<!selClinicDisplay>">
<tr>
<td width="160">
<span><label for="clinic_name" onMouseOver="help_text(this, 'Click the link to choose the clinic name from the pop-up window for the user')">Clinic Name&nbsp;</label></span></td>
	<td><span id="selClinictd2"><input type="hidden" name="clinic_id" id="clinic_id" value="<!clinic_id>"/><input type="text" name="clinic_name" id="clinic_name" size="20" maxlength="20" onMouseOver="help_text(this, 'Click the link to choose the clinic name from the pop-up window for the user')" readonly value="<!clinic_name>"/>&nbsp;&nbsp;<a href="javascript:void(0)" onClick="showClinic();" onMouseOver="help_text(this, 'Click the link to choose the clinic name from the pop-up window for the user')">Choose Clinic</a>
	</span></td>
</tr>
<tr>
<td width="160">
<span><label for="clinic_name" onMouseOver="help_text(this, 'Click the link to choose the clinic name from the pop-up window for the user')">*Provider Type&nbsp;</label></span></td>
	<td><span id="selClinictd2"><select tabindex='5' name="practitioner_type" ><!PractitionerOptions></select></span></td>
</tr>
</table>

</tr>

<tr class="inputRow">
	<td><label for="status" onMouseOver="help_text(this, 'Choose the Subscriber\'s status')")>Status&nbsp;</label></td>
	<td><select name="status" id="status" onMouseOver="help_text(this, 'Choose the Subscriber\'s status')">
<!optionsStatus>
</select>
</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr class="input" align="left">
	<td><div style="width:160px">&nbsp;</div></td>
	<td width="100%">
	<input type="submit" name="submitted_add" value="Add Subscriber">
	</td>
</tr>
</table>
</form>
<!-- [/user detail form] -->

		</div>
		</div>
	<div id="footer">
		<!footer>
	</div>
</div>