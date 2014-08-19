<input type="hidden"  name="showsoap" id="showsoap" value="<!shopshow>">
<script language="JavaScript" src="js/show_menu_head.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>
<script src="js/countryState.js"></script>
<script language="JavaScript" src="js/validateform.js"></script>
<script language="JavaScript">
<!--

window.formRules = new Array(

	//new Rule("clinic_name", "clinic name", true, "string|0,20"),

	new Rule("clinic_address", "Address", true, "string|0,50"),

	new Rule("clinic_address2", "Address line 2", false, "string|0,50"),

	new Rule("clinic_city", "city", true, "string|0,50"),

	new Rule("clinic_state", "State", false, "string|0,2"),

	new Rule("clinic_zip", "Zip Code", false, "zipcode")	

							);

// -->

</script>

<div id="container">
  <form name="clinicdetail" id="clinic_detail" action="index.php?action=addEditClinicInHeadAccount" method="post">
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
                    <td>
                    <table style="vertical-align:middle;width:700px;margin-top:18px;">
                        <tr>
                          <td style="width:400px;">
                          <div id="breadcrumbNav">
                            <a href="index.php?action=sysAdmin" >HOME </a> / <a href="index.php?action=listClinic" >ACCOUNT</a> / <span class="highlight"><!heading1></span>
                          </div>
                          </td>
                          <td style="width:300px;">
                          <table border="0" cellpadding="5" cellspacing="0" style="float:right;margin-top:27px;">
                              <tr>
                                <td class="iconLabel" >&nbsp;</td>
                              </tr>
                          </table>
                    </td>
                        </tr>
                      </table></td>
                        </tr>
                      </table>
					</td>
                  </tr>
				  <!--  1st ROW ENDS  -->
                
				  <!--  2nd ROW ENDS  -->
                  <tr>
                    <td class="error">&nbsp;<!error></td>
                  </tr>
				  <!--  3rd ROW ENDS  -->				  
                  <tr>
                    <td>
					<div style="margin-top:3px;">
                        <!tabNavigation>
                      </div>
                    </td>
                   </tr>
                   <tr>
                        <td width="151" valign="top" class="toptitle" style='padding-left:10px;' ><!heading></td>
                        <td width="143" valign="top" class="toptitle" >&nbsp;</td>
                        <td width="146" valign="top" class="toptitle" >&nbsp;</td>
                        <td valign="top" class="toptitle" >&nbsp;</td>
                        <td valign="top" class="toptitle" >&nbsp;</td>
                   </tr>
                   <tr>
                    <td width="100%" colspan="5">  
                     <div> 
                      <table width="100%" border="0" cellspacing="2" cellpadding="2" style="border:1px solid #CCCCCC;">
                           <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                          </tr>
                                          <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                          </tr>
						  <tr>

							<td width="17%"><div align="right"  ><strong>* Clinic Name : </strong></div></td>

							<td width="22%"><input tabindex="1" type="text" maxlength="30" name="clinic_name"  value="<!clinic_name>"></td>

							<td width="1%">&nbsp;</td>

							<td width="14%"><div align="right"  ><strong>* Country : </strong></div></td>

							<td width="46%"><select id="country" onchange="toggleState();" name="clinic_country" tabindex="5" >
		
									<!patient_country_options>
									  </select></td>

						  </tr>

						  <tr>

							<td><div align="right"  ><strong>* Address : </strong></div></td>

							<td><input type="text" tabindex="2" maxlength="50" name="clinic_address" value="<!clinic_address>"></td>

							<td>&nbsp;</td>

							<td><div align="right" ><strong>* State / Province : </strong></div></td>

							<td><select id="clinic_state" name="clinic_state" tabindex="5" >

							<!stateOptions>

							  </select>							  </td>

						  </tr>

						  <tr>

							<td>&nbsp;</td>

							<td><input type="text" tabindex="3" maxlength="50" name="clinic_address2"   value="<!clinic_address2>"></td>

							<td>&nbsp;</td>

							<td><div align="right" ><strong>* Zip Code : </strong></div></td>

							<td><input type="text" tabindex="6" maxlength="7" name="clinic_zip"  value="<!clinic_zip>"></td>

						  </tr>

						  <tr>

							<td ><div align="right"  ><strong>* City : </strong></div></td>

							<td ><input tabindex="4" type="text" maxlength="30" name="clinic_city"  value="<!clinic_city>"></td>


							<td>&nbsp;</td>

							<td><div align="right" ><strong>* Phone : </strong></div></td>

                            <td><input type="text" tabindex="7" maxlength="15" name="clinic_phone"  value="<!clinic_phone>"></td>

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
                      <div style="float:right;" >
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
      <td colspan="2">
      <div id="footer">
          <!footer>
        </div></td>
    </tr>
  </table>
   <input type="hidden" name="option" value="update" />
   <input type="hidden" name="clinic_id" value="<!clinic_id>" />
   <input type="hidden" name="subaction" value="<!subaction>"/>
  </form>
</div><!-- div ( container ) Ends -->
<script>
<!--
	window.load = document.clinicdetail.clinic_name.focus();

-->
</script>
<script type="text/javascript">
    mmLoadMenus("<!showmenu>");
</script>