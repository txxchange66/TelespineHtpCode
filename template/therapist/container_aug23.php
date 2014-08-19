<div id="container">
	<div id="header">
		<!header>
	</div>
	<div id="sidebar">
		<!sidebar>
	</div>
	<div id="mainContent">
	<script language="JavaScript" type="text/javascript">
	<!--
		function showPlan(id)
		{
			if(! g_plan_win) var g_plan_win = window.open('/patient/plan_viewer.php?id='+id, 'g_plan_win', 'width=1024,height=768,scrollbars=auto');
			g_plan_win.focus();
		}
		function showArticlePreview(id)
		{
			if(!csw) var csw = window.open('index.php?action=articlePreview&aid='+id, 'catSelectWindow', 'width=750, height=480, status=no, toolbar=no, resizable=1,scrollbars=yes');
			csw.focus();
		}
//-->
</script>

<!-- patient quick search -->
<table>
<tr><td>
<div style="width:470px; display:inline;">
	<span style="border-top: 0px solid;font-size: 110%;color: #ed1f24;font-weight: normal;" >PATIENT QUICK SEARCH</span>
	<form style="padding-top: 5px;" action="index.php?action=myPatients" method="POST" name="filter" onSubmit="return search_key(document.filter.search.value);"  onMouseOver="help_text(this, 'Please enter text to restrict the list to only items that contain the text entered')"  >
		<input type="text" size="20" maxlength="250" name="search" value="" />
		<input type="submit" name="searched" value="Submit" />
	</form>
</div>
</td><td>
<!-- buttons -->
<div style="width:290px; float:right;" align="right"><br />
	<table border="0" cellpadding="5" cellspacing="0" style="float:right;">
	<tr>
		<td class="iconLabel"><a href="index.php?action=therapistCreatePatient&type=treatment_plan"><img src="images/createNewPatient.gif" width="127" height="81" alt="Create New Patient" ></a></td>
		<td class="iconLabel"><a href="index.php?action=createNewPlan"><img src="images/createNewTemplatePlan.gif" width="127" height="81" alt="Create New Template Plan" ></td>
	</tr>
	</table>
</div>
</td>
</tr>
<tr>
<td colspan="2">
<div >
<h1 class="largeH1"><span>Recent Messages</span><span align="right" style="padding-left:446px;"><a onMouseOver="help_text(this, 'Click here to compose a new message')" class="headerButton" href="index.php?action=compose_message">Compose New Message</a></span></h1> 

<table border="0" align="center" cellpadding="2" cellspacing="1" width="100%" class="list"  >
<!recent_message>
</table>
<a onMouseOver="help_text(this, 'Click  here to view all the messages in the message center')" href="index.php?action=message_listing&sort=sent_date&order=desc" ><b>View All Messages</b></a>
<br />
<br />
<table width="70%" cellpadding="0" cellspacing="2" border="0" >

	<tr>
		<td style="width:500px;vertical-align:top;"><h1 class="largeH1">Current Patient Activity</h1>
			<table border="0" align="left" cellpadding="2" cellspacing="0" style="width:459px;" class="list" >


<!current_patient_activity>

	</table>
</td>
		<td valign="top" style="width:280px;"><!-- top article list --><h1 class="largeH1">Top Plans<!-- Top Articles --></h1>
			<table border="0" align="left" cellpadding="2" cellspacing="0" style="width:280px;" class="list" >


<!TopAssignedPlans>
    </td>
	</tr>
</table>
</div>
</td>
</table>


		</div>

	</div>
	
	<!footer>
</div>
<!adminNotifications>