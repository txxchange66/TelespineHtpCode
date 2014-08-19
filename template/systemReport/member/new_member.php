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
		        <tr><td colspan="2" style="font-weight:bold">Please Select Report:</tr>
		        <tr><td width="10%"><input type="radio" id="report_type" name="report_type" value="one" checked ></td><td width="90%">YTD</span></td></tr>
                <tr><td colspan="2"><input type="submit"  style=" width:120px; margin-left:5px; margin-top:5px;" value="Next"></td></tr>
		    </table>
            <input type="hidden" name="action" value="member_list" />
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
                switchMenu('eMaintenance',document.getElementById('switch'));
      });
</script>
