<div id="container">
	<div id="header">
		<!header>		
	</div>
	
	<div id="sidebar">
		<!sidebar>
	</div>
	
	
	
<div id="mainContent">
    <div id="breadcrumbNav" style="padding-top:40px;padding-bottom:30px;">
        <a href="index.php?action=patientList" >PATIENT</a> / <span class="highlight">PATIENT LIST</span>
    </div>
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
			a = 'editPatient';
			c = true;
			break;
		case 'act_edit':
			a = 'editPatient';
			c = true;
			break;
		case 'act_assign':
			a = 'assignPatient';
			c = true;
			break;
		case 'act_delete':
			a = 'deletePatient';
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
<h1 class="largeH1">Patient Listing</h1>
<!-- [/title and common actions] -->
<div>
    <table border="0" cellpadding="8" cellspacing="0" height="47px" >
        <tr>
            <td style="valign:middle;">
            <form method="POST" action="index.php?action=patientList" name="filter" onSubmit="return search_key(document.filter.search.value);" onMouseOver="help_text(this, 'Please enter text to restrict the list to only items that contain the text entered')">
                <label for="search">Search:</label>
                <input type="text" size="20" maxlength="250" name="search" value="">
                <input type="submit" size="20" name="sub" value=" Search " >
                <input type="button" size="20" name="clear" value="   Clear   " onclick="location.href='index.php?action=patientList'">
                <input type="hidden" size="20" name="restore_search" value="">
            </form>
            </td>
        </tr>
    </table>
</div>
<!-- [items] -->
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" onMouseOver="help_text(this, 'Displays the Patients, click on one to edit it.  To sort this list click on the column header you would like to sort by')">
<!patientListHeading>
<!patientListRec>
</table>
<div class="paging">
    <!link>
</div>
<!-- [/items] -->
</div>
</div>
	<div id="footer">
		<!footer>
	</div>
</div>

