<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"

	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">

<head>

	<title><!browser_title></title>

	<link rel="STYLESHEET" type="text/css" href="css/styles.css">

	<!style>

	<script language="JavaScript" type="text/javascript" src="js/common.js"></script>

	<script language="JavaScript" src="js/validateform.js"></script>
	<script language="JavaScript">
	<!--
	window.formRules = new Array(
		new Rule("clinic_name", "clinic name", true, "string|0,20"),
		new Rule("clinic_address", "Address", true, "string|0,50"),
		new Rule("clinic_address2", "Address line 2", false, "string|0,50"),
		new Rule("clinic_city", "city", true, "string|0,50"),
		new Rule("clinic_state", "State", false, "string|0,2"),
		new Rule("clinic_zip", "Zip Code", false, "zipcode")	
								);
	// -->
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
    <td width="84%"  align="left" valign="top">	
    <div id="breadcrumbNav" style="float:left; padding-left:20px; margin-top:40px;padding-bottom:50px;">
        <a href="index.php?action=listClinic">ACCOUNT</a> / <span class="highlight">CREATE ACCOUNT</span>
    </div>
    <div id="mainContent">
    <!-- Main Content Starts -->
		<div id="mbody">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td>
			<!--  Start  -->
			<h1 class="largeH1"><!heading></h1>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
					 <tr>
                      <td colspan="2">&nbsp;</td>
                    </tr>	
                    <tr>
                      <td colspan="2">
					  <form name="freetrial_register" id="freetrial_register" action="index.php?action=freetrial_register" method="post" >
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  	<tr>
                      <td colspan="2" class="error"><!error>&nbsp;</td>
                    </tr> 
                    <tr>
                      <td colspan="2"><table width="100%" border="0" cellspacing="1" cellpadding="1">
						  <tr>
							<td width="14%"><div align="right" ><strong>* Business name : </strong></div></td>
							<td width="22%"><input type="text" tabindex='1'  name="business_name" value="<!clinic_name>" ></td>
							<td width="3%">&nbsp;</td>
							<td width="11%"><div align="right" ><strong> First Name : </strong></div></td>
							<td width="50%"><input type="text" tabindex='2' name="name_first" value="<!name_first>" ></td>
						  </tr>
						  <tr>
							<td><div align="right" ><strong> Last Name : </strong></div></td>
							<td><input type="text" tabindex='3' name="name_last" value="<!name_last>" ></td>
							<td>&nbsp;</td>
							<td><div align="right" ><strong> Email Address : </strong></div></td>
							<td><input type="text" tabindex='4' name="email" value="<!email>" ></td>
						  </tr>
						  <tr>
							<td><strong>Provider Type : </strong></td>
							<td><select tabindex='5' name="practitiner_type" >
							<!PractitionerOptions>
							  </select></td>
							<td>&nbsp;</td>
							<td><div align="right" ><strong>* Zip Code : </strong></div></td>
							<td><input type="text" tabindex='6' name="clinic_zip" value="<!clinic_zip>" ></td>
						  </tr>
						  <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
						  <tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td><input type="submit" tabindex='8' name="submit_x" value="Free Trial"></td>
						  </tr>
                      </table>
                      <table onMouseOver="help_text(this, 'Select a page number to go to that page')">
                         <tr>
                    	  <td>
                    		<!link>                    	  </td>
                    	</tr>
                      </table>                      </td>
                    </tr>
                  </table>
				  <input type="hidden" name="option" value="update" />
              </form>					  </td>
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