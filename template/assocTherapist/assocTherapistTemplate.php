<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>Associate Provider</title>
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
function handleAction(s, id, user_id, actionActivateFrom)
{
	var a = s.options[s.options.selectedIndex].value;
	var c = true;

	s.options.selectedIndex = 0;
	if (c) window.location.href = 'index.php?action=assignTherapistToPatient&patient_id=' + id + '&user_id=' + user_id + '&actionActivateFrom=' + actionActivateFrom + '&clinic_id=' + '<!clinic_id>';
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

function submitFilter()
{
	var searchValue = Trim(document.forms['filter'].elements['search'].value);
	if(searchValue != '') 
	{
		if (isAlphaNumeric(searchValue))
		{
			document.forms['filter'].submit();			
		}
		else
		{
			alert("Please enter valid search key in search text box");
			return false;
		}
		
	}
	else
	{
		alert("Please enter search key in search text box.");
		return false;
	}
}
function closeWindow(){
    parent.parent.GB_hide(); 
}
-->
</script>	

<h1  class="largeH1" >LISTING PROVIDER</h1>

<!statusMessage>

<span style=" font-size:16px;"> <strong>Assign a Provider to user.</strong></span>
	<br /><span>To associate a new Provider simply find the desired Provider and add to patient in actions.</span>

<!filter>	
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" >
<!assocTherapistTblHead>
<!assocTherapistTblRecord>
</table>
<!link>
<br/>
<h1 class="largeH1">ASSOCIATED PROVIDER FOR PATIENT</h1>
<br />
	<span style=" font-size:16px;"> <strong>Remove a Provider from a patient.</strong></span>
	<br /><span>Below is a listing of all assigned Provider for this patient.</span><br /><br />
<div align="center">
<table border="0" cellpadding="2" cellspacing="1" width="100%">
	<tr>
		<th>
			This patient has <!totTherapist>
		</th>
	</tr>		
	<!assignedTherapistRecord>
	<tr>
		<td><div align="center">
			<input type="button" name="clear" value="Close Window" onClick="closeWindow();" />
		</div></td>
	</tr>
</table>
</div>
<script language="javascript">
if( top.document.getElementById('therapistName') != null ){
top.document.getElementById('therapistName').innerHTML = '<!therapistName>';
}
</script>
</body>
</html>