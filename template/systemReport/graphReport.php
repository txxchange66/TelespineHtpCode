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
	<table border="0" cellpadding="0" cellspacing="0" width="90%" style="border:1px solid #CCCCCC;" >
	<tr><td>
	<table border="0" cellpadding="15px;" cellspacing="0" width="100%" >
	<tr>
	<td width="70%"><!graph></td>
	<td align="right">&nbsp;</td>
	</tr>
	</table>
	</td>
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
        $("ul#tagNavigation li:first-child").addClass("current");
        $("#graphical_report").addClass("textblue");
        //$("#tabular_report").style("color:white");
        if( '<!report_for>' == 'global_report'){
            switchMenu('eMaintenance',document.getElementById('switch'));
        }
   });  
</script>