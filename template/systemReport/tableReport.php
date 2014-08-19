<div id="container">
	<div id="header">
		<!header>
	</div>
	
	<div id="sidebar">
		<!sidebar>
	</div>
	<div id="mainContent">
<table style="vertical-align:middle; width:700px; margin-top:35px;"  border="0"><tr><td style=" width:600px;">
<div id="breadcrumbNav">
    <!breadcrumb>
</div>
</td>
<td>
</table>
	
	<!-- [list] -->
	<br />
	
	<table border="0" cellpadding="0" cellspacing="0" style="margin-top:10px;"width="100%">
	<tr>
	<td width="70%">
	<table border="0" cellpadding="0" cellspacing="3" width="100%">
	    <!lineContent>
	</table>
    </td>	
	<td align="right">&nbsp;</td> 
	</tr>
	</table>
	
	<table border="0" cellpadding="0" cellspacing="0" style="margin-top:40px;"width="100%">
	<tr>
                <td>
                    <!error>
					<div >
                         <!tabNavigation>
                    </div>
                 </td>
    </tr>
	<tr>
	<td>
	<table border="0" cellpadding="0" cellspacing="0" width="100%"  >
	<tr>
	<td width="70%"><!table></td>
	<td align="right">&nbsp;</td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	<!-- [items] -->
	</div>
<div id="footer">
		<!footer>
	</div>
</div>
<script src="js/jquery-latest.js"></script>  
<script language="javascript" type="text/javascript">
 $(document).ready(function() {  
        $("ul#tagNavigation li:last-child").addClass("current");
        $("#tabular_report").addClass("textblue");
        if( '<!report_for>' == 'global_report'){
            switchMenu('eMaintenance',document.getElementById('switch'));
        }
   });  
</script>