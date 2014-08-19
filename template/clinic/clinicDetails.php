<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"

	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">

<head>

	<title><!browser_title></title>

	<link rel="STYLESHEET" type="text/css" href="css/styles.css">

	<!style>

	<script language="JavaScript" type="text/javascript" src="js/common.js"></script>

	<script language="JavaScript">
		function handleAction(s, id, clinic_id)
		{
			var a = s.options[s.options.selectedIndex].value;
			var c = false;
		
			switch (a)
			{
				case 'delete':
					c = confirm('Deleting this user will remove him/her completely from the system.  Are you sure you would like to continue with deleting this user?');
					break;	
				case 'inactive':
					c = true;
					break;
				case 'active':			
					c = true;	
					break;		
				default:
					c = true;
					break;
			}
		
			s.options.selectedIndex = 0;
		
			if (c) 
			{
				if ('edit' == a)
				{
					window.location.href = 'index.php?action=editClinicSubscriber&' + 'id=' + id + '&clinic_id=' + clinic_id;
				}	
				
				if ('inactive' == a)
				{
					//window.location.href = 'index.php?action=inactiveSubscriber&' + 'id=' + id;
					window.location.href = 'index.php?action=viewClinicDetails&' +'id=' + id+'&do='+a + '&clinic_id=' + clinic_id;
				}
				
				if ('active' == a)
				{
					window.location.href = 'index.php?action=activeClinicSubscriber&' + 'id=' + id + '&clinic_id=' + clinic_id;
				}
				
				if ('delete' == a)
				{
					//window.location.href = 'index.php?action=deleteSubscriber&' +'id=' + id;
					window.location.href = 'index.php?action=viewClinicDetails&' +'id=' + id+'&do='+a + '&clinic_id=' + clinic_id;
				}
			}
		}
	
	
	</script>
	

</head>

<body>
	
<div id="container">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2">	
	<div id="header">
		<!header>
	</div>
	</td>
  </tr>
  <tr>
    <td width="16%" align="left" valign="top">	
	<div id="sidebar">
		<!sidebar>
	</div>
	</td>
    <td width="84%"  align="left" valign="top">	<div id="mainContent">
    <!-- Main Content Starts -->
		<div id="mbody">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td>
			<!--  Start  -->
			<h1 class="largeH1"><!heading></h1>
			<div style="padding:10px;color:red"><!error></div>
			<table  border="0" cellspacing="0" cellpadding="0">
			
                   
                    <tr>
                      <td colspan="2">
					  <form action="index.php?action=editClinicInfo&clinic_id=<!clinic_id>" method="post">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td colspan="2"><table border="0" cellspacing="1" cellpadding="1" style="width:auto;">
						  <tr>
							<td width="120"><div align="left" onMouseOver="help_text(this, 'View clinic details')"><strong>Clinic Name : </strong></div></td>
							<td><div onMouseOver="help_text(this, 'View clinic details')"><!clinic_name></div></td>
							</tr>
						  <tr>
							<td><div align="left" onMouseOver="help_text(this, 'View clinic details')"><strong>Address : </strong></div></td>
							<td><div onMouseOver="help_text(this, 'View clinic details')"><!clinic_address><!commaseprator><!clinic_address2></div></td>
							
							</tr>
						    <td><div align="left" onMouseOver="help_text(this, 'View clinic details')"><strong>City :</strong></div></td>
						    <td><div onMouseOver="help_text(this, 'View clinic details')"><!clinic_city></div></td>
						    
						    </tr>
						  <tr>
							<td><div align="left" onMouseOver="help_text(this, 'View clinic details')"><strong>State / Province :</strong></div></td>
							<td><div onMouseOver="help_text(this, 'View clinic details')"><!clinic_state></div></td>
							</tr>
						  <tr>
							<td><div align="left" onMouseOver="help_text(this, 'View clinic details')"><strong>Zip Code :</strong></div></td>
							<td><div onMouseOver="help_text(this, 'View clinic details')"><!clinic_zip></div></td>
							</tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    </tr>
						  <tr>
						    <td>
						      <div align="left">
						        <input type="submit" name="Submit" value=" Edit Clinic Info. " />
						        </div></td><td>&nbsp;</td>
						    </tr>
                      </table>
                      
              </table></form>					  </td>
                    </tr>
					
					<tr>
						<td>
							 <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td >&nbsp;</td>
                                    <td >&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td colspan="2"><table width="100%" border="0" cellspacing="1" cellpadding="1" class="list" onMouseOver="help_text(this, 'Displays the Subscribers for the clinic, click on one to edit it.  To sort this list click on the column header you would like to sort by')">
                                        <!clinicThpstTblHead>
                                        <!clinicThpstTblRecord>
                                      </table>
                                      <table onMouseOver="help_text(this, 'Select a page number to go to that page')">
                                        <tr>
                                          <td><!link>
                                          </td>
                                        </tr>
                                      </table></td>
                                  </tr>
                                </table>
							</td>	
					</tr>
					<tr>
					  <td>&nbsp;</td>
					</tr>
                  </table>
			<!--  End  -->
			</td>
		  </tr>
		</table>
		</div>
    <!-- Main Content Ends -->
	</div>
	</td>
	

  </tr>  
  <tr>
    <td colspan="2">	
	<div id="footer">
		<!footer>
	</div>
	</td>
  </tr>
</table>
</div>

</body>

</html>