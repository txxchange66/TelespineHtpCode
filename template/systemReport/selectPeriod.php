<div id="container">
	<div id="header">
		<!header>
	</div>
	<div id="sidebar">
		<!sidebar>
	</div>
	<div id="mainContent">
<table style="vertical-align:middle; width:700px; margin-top:35px;"  border="0"><tr><td style=" width:700px;">
<div id="breadcrumbNav">
    <!breadcrumb>
</div>
</td>
<td>
</table>
	<br />
	<h1 class="largeH1" style="margin-top:30px;"><!heading></h1>
	<table border="0" cellpadding="0" cellspacing="0" style="margin-top:20px;"width="100%">
	<tr>
		<td width="15%">&nbsp;</td>
		<td>
        <!-- [form] -->
		<form action="index.php?randnumber=<!randnumber>" method="POST" >
		    <table border="0" cellpadding="1" cellspacing="1" width="40%">
		    <tr><td colspan="2" style="font-weight:bold">Select Period for Report:</tr>
		    <tr><td width="10%"><input type="radio" id="period" name="period" value="one"  ></td><td width="90%">Last 7 days</span></td></tr>
		    <tr><td><input type="radio" id="period" name="period" value="two"></td><td>Current Month to date</td></tr>
		    <tr><td><input type="radio" id="period" name="period" value="three"></td><td>Last Month</td></tr>
		    <tr><td><input type="radio" id="period" name="period" value="four"></td><td>Last Quarter</td></tr>
		    <tr><td><input type="radio" id="period" name="period" value="five"></td><td>Current Year to date</td></tr> 
		    <tr>
                <td colspan="2">
                    <input type="submit" name="report" style="margin-left:5px; margin-top:5px;" value="Generate Report" >
                </td>
            </tr>
		    </table>
            <input type="hidden" name="clinic_id" value="<!clinic_id>" />
            <input type="hidden" name="user_id" value="<!user_id>" />
            <input type="hidden" name="report_type" value="<!report_type>" />
            <input type="hidden" name="action" value="graphical_report" />
            <input type="hidden" id="report_for" name="report_for" value="<!report_for>" />
		</form>
        <!-- [form] -->
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
        $("input[@id='period']").val(['<!period>']);
      });
      if( document.getElementById('report_for').value == 'global_report'){
            switchMenu('eMaintenance',document.getElementById('switch'));
      }
</script>