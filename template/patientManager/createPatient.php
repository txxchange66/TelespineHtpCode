<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>TxXchange - Admin Control Panel</title>
	<link rel="STYLESHEET" type="text/css" href="/protosite2/../asset/style/styles.css">
	<script language="JavaScript" type="text/javascript" src="includes/common.js"></script>
	<!--<script language="JavaScript" type="text/javascript" src="includes/prototype.js"></script>-->
</head>
<body>

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
		Patient Manager	-->
			<!-- <p class="breadcrumbNav"><a href="#">Breadcrumb Nav</a></p> -->
<table style="vertical-align:middle;width:700px;"><tr><td style="width:400px;"><div id="breadcrumbNav"><a href="patient.php" >PATIENT</a> / <span class="highlight">CREATE PATIENT</span></div></td><td style="width:300px;">	<table border="0" cellpadding="5" cellspacing="0" style="float:right;">

<tr>
	<td class="iconLabel"><!--<a href="patient_detail.php"><img src="skin/tx/images/icons/createNewPatient.gif" width="127" height="81" alt="">--></td>
	</tr>
</table>
	</td></tr></table><script language="JavaScript" src="/protosite2/form/validateform.js"></script>
<script language="JavaScript">
<!--
window.formRules = new Array(
	new Rule("name_title", "title", false, "string|0,5"),
	new Rule("name_first", "first name", true, "string|0,50"),
	new Rule("name_last", "last name", true, "string|0,50"),
	new Rule("name_suffix", "name suffix", false, "string|0,5"),
	new Rule("address", "address", false, "string|0,50"),
	new Rule("address2", "address line 2", false, "string|0,50"),
	new Rule("city", "city", false, "string|0,50"),
	new Rule("state", "state", false, "string|0,2"),
	new Rule("zip", "zip code", false, "zipcode"),
	new Rule("phone1", "1st phone number", false, "usphone"),
	new Rule("phone2", "2nd phone number", false, "usphone"),
	new Rule("status", "status", false, "integer"),
	new Rule("diagnosis", "current diagnosis", true, "string|1,50"),
	new Rule("email_address", "email address", true, "email"),
	new Rule("reminder", "reminder", false, "string|0,250"),
	new Rule("new_password", "new password", false, "string|4,20"));
// -->
</script>

<script type="text/javascript">
<!--
function handleAction(s, id)
	{
		var a = s.options[s.options.selectedIndex].value;
		var c = false;
		switch (a)
		{
			case 'view_patient_details':
				if(!patient_detail_win)
				{
					var patient_detail_win = window.open('patient_detail_popup.php?id='+patient_id, 'Patient Details', 'width=750,height=480,resizable=1,scrollbars=auto');
				}
				patient_detail_win.focus();
				c = false;
				break;
			default:
				c = true;
				break;
		}
		s.options.selectedIndex = 0;
		if (c) window.location.href = '/admin/patient_detail.php'+ '&act=' + a + '&id=' + _id;
	}
	function submitFilter()
	{
		if(document.forms['filter'].elements['search'].value != '') document.forms['filter'].submit();
	}
	
	function showCatSelect(patient_id)
	{
		if(!csw) var csw = window.open('patient2user_cat_select.php?patient_id='+patient_id, 'catSelectWindow', 'width=750, height=480, status=no, toolbar=no, resizable=1');
		csw.focus();
	}
function handlePlanAction(s, p2p_id)
{
	var a = s.options[s.options.selectedIndex].value;
	var c = false;

	switch (a)
	{
		case 'deletePlan':
			c = confirm('Deleting this plan will remove all record of it from the site. Are you sure you want to continue with deleting this plan?');
			break;
		case 'viewPlan':
			if(!plan_detail_win)
			{
				var plan_detail_win = window.open('/patient/plan_viewer.php?p2p_id='+p2p_id, 'Plan Preview', 'width=1024,height=768,resizable=1,scrollbars=auto');
			}
			plan_detail_win.focus();
			c = false;
			break;
		default:
			c = true;
			break;
	}
	s.options.selectedIndex = 0;
	if (c) window.location.href = '/admin/patient_detail.php?' + 'act=' + a + '&plan2patient_id=' + p2p_id + '&id=' + -1;
}

function handle_action(s, id)
{
	var a = s.options[s.options.selectedIndex].value;
	var c = false;

	switch (a)
	{
		case 'delete':
			c = confirm('Discharging this patient will prevent them from logging-in and viewing treatment plans.  Are you sure you would like to continue with discharging this patient?');
			break;
		case 'undelete':
			c = confirm('Restoring this discharged patient will allow them to log-in and view treatment plans.  Are you sure you would like to continue with restoring this patient?');
			break;
		case 'realdelete':
			c = confirm('Deleting this patient will remove all record of them from the site. Are you sure you want to continue with deleting this patient?');
			break;
		
		default:
			c = true;
			break;
	}

	s.options.selectedIndex = 0;

	if (c) window.location.href = '/admin/patient_detail.php?' + 'act=' + a + '&id=' + id;
}
function maybeShowBillingAddress(cb)
{
	b = document.getElementById('billing_address');
	(cb.checked) ? b.style.display = 'block' : b.style.display = 'none';
}
//-->
</script>

<!-- [title and common actions] -->

<!-- [/title and common actions] -->

<!-- [detail form] -->
<h1 class="largeH1">Creating New Patient <small> </small></h1>
<div><!error></div>
<!-- start edit form -->
<form name="detailform" id="detailform" enctype="multipart/form-data" method="POST" action="index.php?action=submitPatient" onSubmit="return validate_form(this);">
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
<tr class="input">
	<td><div style="width:160px"><label for="email_address" onMouseOver="help_text(this, 'Enter the Patient\'s email address so the they can receive email messages')")>*&nbsp;Email Address&nbsp;</label></div></td>
	<td width="100%"><input type="text" name="email_address" id="email_address" size="50" maxlength="250" onMouseOver="help_text(this, 'Enter the Patient\'s email address so the they can receive email messages')" value="<!email_address>"/></td>

</tr>
	<tr class="input">
		<td><label for="new_password" onMouseOver="help_text(this, 'Enter the Patient\'s password (type a new password here to reset it)')")>New Password&nbsp;</label></td>
		<td><input type="text" name="new_password" id="new_password" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the Patient\'s password (type a new password here to reset it)')" value="kcjpza8a"/></td>
</tr>
<tr class="input">
	<td><label for="diagnosis" onMouseOver="help_text(this, 'Enter the Patient\'s current diagnosis')")>*&nbsp;Current Diagnosis&nbsp;</label></td>
	<td><input type="text" name="diagnosis" id="diagnosis" size="50" maxlength="50" onMouseOver="help_text(this, 'Enter the Patient\'s current diagnosis')" value="<!diagnosis>"/></td>
</tr>

</tr>
<tr>
<td colspan="2">

</td>
</tr>
<tr>
	<td colspan="2"><h3>Patient Contact Information</h3></td>
</tr>
<tr class="input">
	<td><label for="name_title" onMouseOver="help_text(this, 'Choose the Patient\'s title')")>Title&nbsp;</label></td>
	<td><select name="name_title" id="name_title" onMouseOver="help_text(this, 'Choose the Patient\'s title')">

<option value="" selected="true">Choose...</option>
<!prefixOption>
</select>
</td>
</tr>
<tr class="input">
	<td><label for="name_first" onMouseOver="help_text(this, 'Enter the Patient\'s first name')")>*&nbsp;First Name&nbsp;</label></td>

	<td><input type="text" name="name_first" id="name_first" size="50" maxlength="50" onMouseOver="help_text(this, 'Enter the Patient\'s first name')" value="<!name_first>"/></td>
</tr>
<tr class="input">
	<td><label for="name_last" onMouseOver="help_text(this, 'Enter the Patient\'s last name')")>*&nbsp;Last Name&nbsp;</label></td>
	<td><input type="text" name="name_last" id="name_last" size="50" maxlength="50" onMouseOver="help_text(this, 'Enter the Patient\'s last name')" value="<!name_last>"/></td>
</tr>
<tr class="input">
	<td><label for="name_suffix" onMouseOver="help_text(this, 'Choose the Patient\'s name suffix')")>Suffix&nbsp;</label></td>
	<td><select name="name_suffix" id="name_suffix" onMouseOver="help_text(this, 'Choose the Patient\'s name suffix')">

<option value="" selected="true">Choose...</option>
<!suffixOption>
</select>
</td>
</tr>

<tr class="input">
	<td><label for="address" onMouseOver="help_text(this, 'Enter the Patient\'s address')")>Address&nbsp;</label></td>
	<td><input type="text" name="address" id="address" size="50" maxlength="150" onMouseOver="help_text(this, 'Enter the Patient\'s address')" value="<!address>"/></td>
</tr>
<tr class="input">
	<td><label for="address2" onMouseOver="help_text(this, 'Enter the Patient\'s address line 2')")>Address 2&nbsp;</label></td>
	<td><input type="text" name="address2" id="address2" size="50" maxlength="150" onMouseOver="help_text(this, 'Enter the Patient\'s address line 2')" value="<!address2>"/></td>
</tr>
<tr class="input">
	<td><label for="city" onMouseOver="help_text(this, 'Enter the Patient\'s city')")>City&nbsp;</label></td>

	<td><input type="text" name="city" id="city" size="50" maxlength="150" onMouseOver="help_text(this, 'Enter the Patient\'s city')" value="<!city>"/></td>
</tr>
<tr class="input">
	<td><label for="state" onMouseOver="help_text(this, 'Enter the Patient\'s state')")>State / Province&nbsp;</label></td>
	<td><select name="state" id="state" onMouseOver="help_text(this, 'Enter the Patient\'s state')">
<option value="" selected="true">choose State...</option>
<!stateOption>
</select>
</td>
</tr>
<tr class="input">
	<td><label for="zip" onMouseOver="help_text(this, 'Enter the Patient\'s zip code')")>Zip Code&nbsp;</label></td>
	<td><input type="text" name="zip" id="zip" size="10" maxlength="7" onMouseOver="help_text(this, 'Enter the Patient\'s zip code')" value="<!zip>"/></td>
</tr>
<tr class="input">

	<td><label for="phone1" onMouseOver="help_text(this, 'Enter the Patient\'s 1st phone number')")>1st Phone&nbsp;</label></td>
	<td><input type="text" name="phone1" id="phone1" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the Patient\'s 1st phone number')" value="<!phone1>"/></td>
</tr>
<tr class="input">
	<td><label for="phone2" onMouseOver="help_text(this, 'Enter the Patient\'s 2nd phone number')")>2nd Phone&nbsp;</label></td>
	<td><input type="text" name="phone2" id="phone2" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the Patient\'s 2nd phone number')" value="<!phone2>"/></td>
</tr>
<tr>
	<td colspan="2"><h3>Patient Permissions</h3></th>

</tr>
<tr class="input">
	<td><label for="status" onMouseOver="help_text(this, 'Choose the Patient\'s status')")>Status&nbsp;</label></td>
	<td>
	<select name="status" id="status" onMouseOver="help_text(this, 'Choose the Patient\'s status')">
		<!statusOption>
	</select>
</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>

</tr>
<tr>
	<td colspan="2" align="center">
		<input type="submit" name="submitted_save" value="Add Patient">		&nbsp;
			</td>
</tr>
</table>
</form>
<!-- [end edit form] -->

		</div>
	</div>

	
	<div id="footer">
		<!footer>
	</div>
</div>
