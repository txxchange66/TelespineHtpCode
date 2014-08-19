
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

<div id="container">
  <form name="clinicdetail" id="clinic_detail" action="index.php?action=systemEditClinicDetails" method="post">
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
      <td width="84%"  align="left" valign="top"><div id="mainContent">
	  <!--  MAIN TABLE STARTS -->
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top" align="left">
			  <!--  INNER MAIN TABLE STARTS (5) ROWS STARTS  -->
			  <table width="100%" border="0" style="vertical-align:middle; width:700px; ">
                  <tr>
                    <td><table style="vertical-align:middle;width:700px;">
                        <tr>
                          <td style="width:400px;"><div id="breadcrumbNav">
                          <a href="index.php?action=sysAdmin" >HOME </a> / <a href="index.php?action=listClinic" >CLINIC LIST </a> / <span class="highlight">EDIT CLINIC INFORMATION</span>
                          </div></td>
                          <td style="width:300px;"><table border="0" cellpadding="5" cellspacing="0" style="float:right;">
                              <tr>
                                <td class="iconLabel">&nbsp;</td>
                              </tr>
                            </table></td>
                        </tr>
                      </table></td>
                        </tr>
                      </table>
					</td>
                  </tr>
				  <!--  1st ROW ENDS  -->
                  <tr>
                    <!-- <td class="adminSubHeading">Account Administration </td> -->
                    <td>&nbsp;</td>
                  </tr>
				  <!--  2nd ROW ENDS  -->
                  <tr>
                    <td class="error">&nbsp;<!error></td>
                  </tr>
				  <!--  3rd ROW ENDS  -->				  
                  <tr>
                    <td>
					<div id="tab_navigation">
                        <ul>
                          <li> <a href="index.php?action=user_listing&clinic_id=<!clinic_id>">Users</a></li>
                          <li><a href="index.php?action=clinic_patient&clinic_id=<!clinic_id>">Patient List</a></li>
                          <li id="current"><a href="index.php?action=systemAdminClinic&clinic_id=<!clinic_id>">Clinic Information</a></li>
                        </ul>
                      </div>
                      <div id="tab_header">Edit Clinic </div>
                      <div id="tab_main">
<table width="100%" border="0" cellspacing="1" cellpadding="1">

						  <tr>

							<td width="17%"><div align="right" onMouseOver="help_text(this, 'Enter clinic name')" ><strong>* Clinic Name : </strong></div></td>

							<td width="22%"><input tabindex="1" type="text" maxlength="20" name="clinic_name" onMouseOver="help_text(this, 'Enter clinic name')" value="<!clinic_name>"></td>

							<td width="1%">&nbsp;</td>

							<td width="14%"><div align="right"  onMouseOver="help_text(this, 'Enter clinic city')" ><strong>* City : </strong></div></td>

							<td width="46%"><input tabindex="4" type="text" maxlength="20" name="clinic_city"  onMouseOver="help_text(this, 'Enter clinic city')" value="<!clinic_city>"></td>

						  </tr>

						  <tr>

							<td><div align="right" onMouseOver="help_text(this, 'Enter clinic address')" ><strong>* Address : </strong></div></td>

							<td><input type="text" tabindex="2" maxlength="50" name="clinic_address" onMouseOver="help_text(this, 'Enter clinic address')" value="<!clinic_address>"></td>

							<td>&nbsp;</td>

							<td><div align="right" onMouseOver="help_text(this, 'Select clinic state')"><strong>* State / Province : </strong></div></td>

							<td><select name="clinic_state" tabindex="5" onMouseOver="help_text(this, 'Select clinic state')">

							<!stateOptions>

							  </select>							  </td>

						  </tr>

						  <tr>

							<td>&nbsp;</td>

							<td><input type="text" tabindex="3" maxlength="50" name="clinic_address2"  onMouseOver="help_text(this, 'Enter clinic address for line2')" value="<!clinic_address2>"></td>

							<td>&nbsp;</td>

							<td><div align="right" onMouseOver="help_text(this, 'Enter clinic zip')"><strong>* Zip Code : </strong></div></td>

							<td><input type="text" tabindex="6" maxlength="7" name="clinic_zip"  onMouseOver="help_text(this, 'Enter clinic zip')" value="<!clinic_zip>"></td>

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

							<td>&nbsp;</td>

						  </tr>

                      </table>
                      </div>
                      <div id="tab_footer">
						<input type="submit" name="Submit" value=" Save Changes " />                        
                      </div></td>
                  </tr>
				  <!--  4th ROW ENDS  -->
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
				  <!--  5th ROW ENDS  -->
                </table>
				<!--  INNER MAIN TABLE STARTS (5) ROWS ENDS -->
          <!--  MAIN TABLE ENDS  -->
        </div><!-- div ( mainContent ) Ends --></td>
    </tr>
    <tr>
      <td colspan="2"><div id="footer">
          <!footer>
        </div></td>
    </tr>
  </table>
   <input type="hidden" name="option" value="update" />
   <input type="hidden" name="clinic_id" value="<!clinic_id>" />
  </form>
</div><!-- div ( container ) Ends -->
<script>
<!--
	window.load = document.clinicdetail.clinic_name.focus();

-->
</script>