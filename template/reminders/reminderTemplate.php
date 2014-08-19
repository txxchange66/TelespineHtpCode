<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>Edit Reminder</title>
	<meta name="description" content="<!metaDesc>">	
	<link rel="stylesheet" type="text/css" href="css/styles_popup.css" />
		<script language="JavaScript" type="text/javascript" src="js/common.js"></script>
</head>
<body>
<div id="container">
<div id="mainContent">
<script language="JavaScript" src="js/validateform.js"></script>
<script language="JavaScript">
<!--
window.formRules = new Array(
	new Rule("reminder", "reminder", true, "string|1,250"));
// -->
</script>
<script language="JavaScript">
<!--
function handleAction(s,ptId ,id,reminder_set)
{
	var a = s.options[s.options.selectedIndex].value;
	var c = false;

	switch (a)
	{
		case 'remove':
			c = confirm('Removing this reminder will delete it completely from the system.  Are you sure you would like to continue with deleting this reminder?');
			break;
		default:
			c = true;
			break;
	}

	s.options.selectedIndex = 0;

	if (c) window.location.href = 'index.php?action=removeReminder&patient_id='+ptId+'&id=' + id +'&reminder_set=' +reminder_set;
}
function doClose()
{
	if(window.opener)
	{
		window.opener.location.reload();
		window.opener.focus();
	}
	window.close();
}
function doSimpleClose()
{
	window.close();
}
function closeWindow(){
    //top.location.reload(true);
    parent.parent.GB_hide(); 
}
-->
</script>	

<h1 >NEW REMINDER</h1>
<!statusMessage>

<form name="detailform" id="detailform" enctype="multipart/form-data" method="POST" action="index.php?action=addReminder&patient_id=<!patientId>&reminder_set=<!reminder_set>" onSubmit="return validate_form(this);">
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
	<tr class="input">
		<td><div style="width:160px"><label for="reminder" >*&nbsp;Reminder:&nbsp;</label></div></td>
		<td width="100%"><input type="text" name="reminder" id="reminder" size="50" maxlength="250"/></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" name="submitted" value="Save Reminder">&nbsp;
			<!--<input type="button" name="close" value="Close"  onClick="do<!closePopUpFunction>Close();" >-->
            <input type="button" name="close" value="Close"  onClick="closeWindow();" >
        </td>
            

	</tr>
</table>
</form>

<h1 >PATIENT REMINDERS for <!patientName> </h1>

<table border="0" cellpadding="2" cellspacing="1" width="100%" class="list">
<!reminderTblHead>
<!reminderTblRecord>
</table>
<script language="javascript">
if( top.document.getElementById('patient_reminder<!reminder_set>') != null ){
    top.document.getElementById('patient_reminder<!reminder_set>').innerHTML = '<!reminderText>';
}
</script>
</body>
</html>