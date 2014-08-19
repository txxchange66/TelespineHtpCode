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
		My Patients	-->
			<!-- <p class="breadcrumbNav"><a href="#">Breadcrumb Nav</a></p> -->
<table style="vertical-align:middle;width:700px;"><tr><td style="width:400px;"><div id="breadcrumbNav"><a href="index.php?action=myPatients" >PATIENT</a> / <!--<span class="highlight">SELECT PATIENT</span>--><span class="highlight">SELECT HEALTH RECORD</span></div></td><td style="width:300px;">	<table border="0" cellpadding="5" cellspacing="0" style="float:right;">
<tr>
	<td class="iconLabel">

		<a href="index.php?action=therapistCreatePatient">
			<img src="images/createNewPatient.gif" width="127" height="81" alt="Create New Patient"></a></td>
</tr>
</table>
</td></tr></table>
<script language="JavaScript">
<!--
function handleAction(s, id)
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
			c = confirm('Deleting this user will remove all record of them from the site. Are you sure you want to continue with deleting this patient?');
			break;
		case 'act_view':
			a = 'therapistViewPatient';
			c = true;
			break;
		case 'act_edit':
			a = 'therapistEditPatient';
			c = true;
			break;
		case 'act_assign':
			a = 'therapistPlan';
			c = true;
			if (c) window.location.href = 'index.php?' + 'action=' + a + '&path=my_patient&patient_id=' + id;
			c = false;
			break;
		case 'act_delete':
			a = 'therapistDeletePatient';
			c = true;
			break;	
		default:
			c = true;
			break;
	}

	s.options.selectedIndex = 0;
	if (c) window.location.href = 'index.php?' + 'action=' + a + '&id=' + id;
	
}
-->
</script>
<!-- [title and common actions] -->

<!-- [title and common actions] -->
<h1 class="largeH1"  style="margin-top:-2px; height:26px;">Health Record List</h1>
<!-- [/title and common actions] -->
<!-- [search] -->

<table border="0" cellpadding="8" cellspacing="0" height="47px" >
<tr>
	<td style="valign:middle;">
					
			<form method="POST" action="index.php?action=myPatients" name="filter" onSubmit="return search_key(document.filter.search.value);" onMouseOver="help_text(this, 'Please enter text to restrict the list to only items that contain the text entered')">
				<label for="search">Search:</label>
				<input type="text" size="20" maxlength="250" name="search" value="">
				<input type="submit" size="20" name="sub" value=" Search " >
				<input type="button" size="20" name="clear" value="   Clear   " onclick="location.href='index.php?action=myPatients'">
				<input type="hidden" size="20" name="restore_search" value="<!search>">
			</form>
			
				
	</td>
</tr>
</table>

<!-- [search] -->
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" onMouseOver="help_text(this, 'Displays the Patients, click on one to edit it.  To sort this list click on the column header you would like to sort by')">
<!patientHeading>
<!patientRec>
</table>
<div class="paging">

		<span onMouseOver="help_text(this, 'Select a page number to go to that page')"><!link></div>
<!-- [/items] -->

<!-- [/list] -->

		</div>
	</div>
	<div id="footer">
		<!footer>
	</div>
</div>
