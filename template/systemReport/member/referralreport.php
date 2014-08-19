<div id="container">
	<div id="header">
		<!header>
	</div>
	
	<div id="sidebar">
		<!sidebar>
	</div>
	<div id="mainContent">
<table style="vertical-align:middle; width:700px; margin-top:35px;"  border="0">
    <tr>
        <td style="width:700px;">
            <div id="breadcrumbNav">
            
            </div>
        </td>
    </tr>
</table>
	
	<!-- [list] -->
	<br/>
	<h1 class="largeH1" style="margin-top:30px;"><!heading></h1>
	<table border="0" cellpadding="0" cellspacing="0" style="margin-top:10px;"width="100%"><tr><td>
	<div>				
		<form method="POST" action="index.php" name="report">
			<span style="padding-left: 20px;">Run Report For: <select name="period" ><!period></select></span>
            <input type="hidden" name="action" value="referralReports" >
			<input type="submit" size="20" name="sub" value="Export Report">
         </form>
	</div>
	</td>
	<td align="right"></td>
	</tr>
	</table>

	</script><table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" style="margin-top:10px; onMouseOver="help_text(this, 'To sort this list click on the column header you would like to sort by. Use action menus to perform action for the template plan')">
	</table>
	<div class="paging">
				<span ><!link></div>
    <div align="center" style="padding:5px;">
		</div> 
	
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
