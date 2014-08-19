<div id="container">
	<div id="header">
		<!header>
	</div>
	
	<div id="sidebar">
		<!sidebar>
	</div>
	<div id="mainContent">
<table style="vertical-align:middle; width:700px; margin-top:35px;"  border="0"><tr><td style=" width:400px;">
<div id="breadcrumbNav">
<!breadcrumb>
</div>
</td>
<td>
</table>
	<!-- [list] -->
	<br />
	<h1 class="largeH1" style="margin-top:30px;"><!heading></h1>
	<table border="0" cellpadding="0" cellspacing="0" style="margin-top:20px;"width="100%">
	<tr>
		<td width="15%">&nbsp;</td>
		<td>
		<form action="index.php" method="POST" >
		    <table border="0" cellpadding="1" cellspacing="1" width="40%">
		        <tr><td colspan="2" style="font-weight:bold">Please Select Report Type:</tr>
		        <tr><td width="10%"><input type="radio" id="report_type" name="report_type" value="one"></td><td width="90%">Template Plans Created</span></td></tr>
		        <tr><td><input type="radio" id="report_type" name="report_type" value="two"></td><td>Assigned Plans</td></tr>
		        <tr><td><input type="radio" id="report_type" name="report_type" value="three"></td><td>New Patients Created</td></tr>
		        <tr><td><input type="radio" id="report_type" name="report_type" value="four"></td><td>Messages Sent</td></tr>
		        <tr><td><input type="radio" id="report_type" name="report_type" value="five"></td><td>User Logins</td></tr> 
		        <tr><td><input type="radio" id="report_type" name="report_type" value="six"></td><td>Patient Logins</td></tr>
		        <tr><td><input type="radio" id="report_type" name="report_type" value="seven"></td><td>Total network invites sent by patients</td></tr>
		        <tr><td><input type="radio" id="report_type" name="report_type" value="eight"></td><td>Total network invites sent by Provider</td></tr>
		        <tr><td colspan="2"><input type="submit"  style=" width:120px; margin-left:5px; margin-top:5px;" value="Next"></td></tr>
		    </table>
            <input type="hidden" name="action" value="select_period_global" />
            <input type="hidden" name="report_for" value="global_report" />
		</form>
		</td>
		<td align="right"> &nbsp; </td>
	</tr>
	</table>
	</div>
    <div id="footer">
		<!footer>
	</div>
</div>

<script src="js/jquery.js"></script>  
<script language="javascript" type="text/javascript">
      $(document).ready(function() {
        $("input[@id='report_type']").val(['<!report_type>']);
        switchMenu('eMaintenance',document.getElementById('switch'));
      });
</script>

