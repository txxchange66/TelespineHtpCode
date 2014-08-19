<input type="hidden"  name="showsoap" id="showsoap" value="<!shopshow>">
<script language="JavaScript" src="js/show_menu.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>


<div id="container">
  <form action="index.php?action=editClinicDetails" method="post">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2"><div id="header">
          <!header>
        </div></td>
    </tr>
    <tr>
      <td width="16%" align="left" valign="top"><div id="sidebar">
          <!sidebar>
        </div></td>
      <td width="84%"  align="left" valign="top">
      <div id="mainContent">
<table border="0" cellpadding="0" cellspacing="0" style="vertical-align:middle; width:100%; height:81px;">
  <tr>
    <td colspan="5" style="height:9px;">    </td>
    </tr>
  <tr>
    <td colspan="5" width="741px;" >
        <div id="breadcrumbNav" style="margin-top:40px;float:left;width:550px;">
            <a href="index.php">HOME</a>
            /
            <a href="index.php?action=userListing">ACCOUNT ADMINISTRATOR </a>
            / 
            <span class="highlight"  >CLINIC</span>
        </div>
    </td>
  </tr>
  <tr>
    <td colspan="5" valign="top" style=" width:741px;font-size: large;font-weight: bold;padding-bottom: 30px; margin:0px; vertical-align:top;">
        <table>
    	    <tr>
    		    <td>&nbsp;</td>
    		    <td>&nbsp;</td>
    		    <td>&nbsp;</td>
	   	    </tr>	
        </table>
    </td>
    </tr>
  <tr>
    <td colspan="5" valign="top"  >
        <div style="padding-top:18px;width:741px;float:left;">
            <!navigationTab>
        </div> 
    </td>
  </tr>
  <tr>
    <td width="151" valign="top" class="toptitle" >&nbsp;</td>
    <td width="143" valign="top" class="toptitle" >&nbsp;</td>
    <td width="146" valign="top" class="toptitle" >&nbsp;</td>
    <td valign="top" class="toptitle" >&nbsp;</td>
    <td valign="top" class="toptitle" >&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="1" onMouseOver="help_text(this, 'View Clinic Details')" style="border:1px solid #CCCCCC;">
						  <tr>
							<td width="16%"><div align="left"><strong>Clinic Name : </strong></div></td>
							<td width="29%"><!clinic_name></td>
							<td width="8%">&nbsp;</td>

							<td width="24%"><div align="left"><strong>City :</strong></div></td>
							<td width="23%"><!clinic_city></td>
						  </tr>
						  <tr>
							<td><div align="left"><strong>Address : </strong></div></td>
							<td><!clinic_address><!commaseprator></td>
							<td>&nbsp;</td>

							<td><div align="left"><strong>State / Province :</strong></div></td>
							<td><!clinic_state></td>
						  </tr>
						    <td>&nbsp;</td>
						    <td><!clinic_address2></td>
						    <td>&nbsp;</td>
						    <td><div align="left"><strong>Zip Code :</strong></div></td>

						    <td><!clinic_zip></td>
						    </tr>
						  <tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td><div align="left"><strong>Phone : </strong></div></td>
							<td><!clinic_phone></td>
						  </tr>
						  <tr>
						    <td>&nbsp;
					        </td><td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
	    </tr>
      </table>
<div class="paging" align="right">
  <input type="submit" name="Submit" id="button" value="Edit Clinic Info." />
</div></td>
    </tr>
    <tr>
      <td colspan="2"><div id="footer">
          <!footer>
        </div></td>
    </tr>
  </table>
  </form>
</div><!-- div ( container ) Ends -->
<script language="JavaScript1.2">mmLoadMenus();</script>