<input type="hidden"  name="showsoap" id="showsoap" value="<!shopshow>">
<script language="JavaScript" src="js/validateform.js"></script>
<script src="js/countryState.js"></script>
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
<script language="JavaScript" src="js/show_menu.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>


<div id="container">
  <form name="clinicdetail" id="clinic_detail" action="index.php?action=editClinicDetails" method="post">
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
    <td colspan="5" style="width:741px;">
        <div id="breadcrumbNav" style="margin-top:40px;float:left;width:550px;">
            <a href="#">HOME</a> / <span class="highlight"  >  <a href="#">ACCOUNT ADMINISTRATOR </a>/ CLINIC</span>
        </div>
    </td>
  </tr>
  <tr>
    <td colspan="5" valign="top" style=" width:400px; font-size: large;font-weight: bold;padding-bottom: 35px; margin:0px; vertical-align:top;">&nbsp;</td>
  </tr>
<tr>
    <td colspan="5" valign="top"  >
	    <span class="error"><!error></span>	    
        <div style="padding-top:11px;width:741px;float:left;">
            <!navigationTab>
        </div>
    </td>
 </tr>
  <tr>
    <td width="151" valign="top" class="toptitle" style="padding-left:10px;" >Edit Clinic</td>
    <td width="143" valign="top" class="toptitle" >&nbsp;</td>
    <td width="146" valign="top" class="toptitle" >&nbsp;</td>
    <td valign="top" class="toptitle" >&nbsp;</td>
    <td valign="top" class="toptitle" >&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="1" style="border:1px solid #CCCCCC;">

						  <tr>

							<td width="17%"><div align="right" onMouseOver="help_text(this, 'Enter clinic name')" ><strong>* Clinic Name : </strong></div></td>

							<td width="22%"><input tabindex="1" type="text" maxlength="20" name="clinic_name" onMouseOver="help_text(this, 'Enter clinic name')" value="<!clinic_name>"></td>

							<td width="1%">&nbsp;</td>
							 <td><div align="right">
                                                      <label for="state" onMouseOver="help_text(this, 'Enter the User\'s country')")>&nbsp;* Country :</label>
                                                    </div></td>
                                                  <td><select id="country" onchange="toggleState();" name="clinic_country" tabindex="5" >
		
									<!patient_country_options>
									  </select>
                                                  </td>

							
						  </tr>

						  <tr>

							<td><div align="right" onMouseOver="help_text(this, 'Enter clinic address')" ><strong>* Address : </strong></div></td>

							<td><input type="text" tabindex="2" maxlength="50" name="clinic_address" onMouseOver="help_text(this, 'Enter clinic address')" value="<!clinic_address>"></td>

							<td>&nbsp;</td>

							<td><div align="right" onMouseOver="help_text(this, 'Select clinic state')"><strong>*  State / Province : </strong></div></td>

							<td>
							<select id="state" name="clinic_state" tabindex="5" onMouseOver="help_text(this, 'Select clinic state')">
								<!stateOptions>
						    </select>
						    </td>
					  </tr>
					  <tr>
						<td>&nbsp;</td>

							<td><input type="text" tabindex="3" maxlength="50" name="clinic_address2"  onMouseOver="help_text(this, 'Enter clinic address for line2')" value="<!clinic_address2>"></td>

							<td>&nbsp;</td>

							<td><div align="right" onMouseOver="help_text(this, 'Enter clinic zip')"><strong>* Zip Code : </strong></div></td>

							<td><input type="text" tabindex="6" maxlength="7" name="clinic_zip"  onMouseOver="help_text(this, 'Enter clinic zip')" value="<!clinic_zip>"></td>

						  </tr>

						  <tr>

							<td><div align="right"  onMouseOver="help_text(this, 'Enter clinic city')" ><strong>* City : </strong></div></td>

							<td ><input tabindex="4" type="text" maxlength="20" name="clinic_city"  onMouseOver="help_text(this, 'Enter clinic city')" value="<!clinic_city>"></td>


							<td>&nbsp;</td>

							<td><div align="right"><strong>* Phone : </strong></div></td>
							<td><input type="text" tabindex="7" maxlength="15" name="clinic_phone" value="<!clinic_phone>"></td>

						  <tr>

							<td>&nbsp;</td>

							<td>&nbsp;</td>

							<td>&nbsp;</td>

							<td>&nbsp;</td>

							<td>&nbsp;</td>

						  </tr>

                      </table>
<div class="paging" align="right">
  <input type="submit" name="Submit" id="button" value="Save Changes" />
</div></td>
    </tr>
    <tr>
      <td colspan="2"><div id="footer">
          <!footer>
        </div></td>
    </tr>
  </table>
   <input type="hidden" name="option" value="update" />
  </form>
</div><!-- div ( container ) Ends -->
<script>
<!--
	window.load = document.clinicdetail.clinic_name.focus();

-->
</script><script language="JavaScript1.2">mmLoadMenus();</script>