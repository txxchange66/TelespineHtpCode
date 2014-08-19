<input type="hidden"  name="showsoap" id="showsoap" value="<!shopshow>">
<script language="JavaScript" src="js/show_menu_head.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>
<script language="JavaScript" src="js/countryState.js"></script>

<script language="JavaScript" src="js/addressPopulate.js"></script>
<script language="JavaScript" src="js/common.js"></script>
<script language="JavaScript" src="js/jquery.js"></script>
	
<script type="text/javascript">
<!--
function popupConfirm()
{
	document.getElementById('errorDiv').innerHTML ="";
	if (document.addUserForm.therapistAccess.checked == true)
	{
		checkValidation('NA',<!clinic_id>);
	}
	else
	{
		document.addUserForm.submit();
	}
}

function populateClinicAddress(obj,clinic_id){
    if( obj.checked == true && clinic_id != null ){         
     $.post('index.php?action=populateAddressHead',{clinic_id:clinic_id}, function(data,status){
                $content = document.getElementById("divAddress").innerHTML;    
                document.getElementById("divAddress").innerHTML = "<img src='images/ajax-loader.gif' />";
                if( status == "success" ){
                    document.getElementById("divAddress").innerHTML = data;
                }
                else{
                    alert("Ajax connection failed.");
                    document.getElementById("login_detail").innerHTML = $content;   
                }    
                
            }
        )
    } 
    else{
                                                
            document.addUserForm.address.value = '';
            document.addUserForm.address2.value = '';
            document.addUserForm.phone1.value = '';
            document.addUserForm.zip.value = '';
            document.addUserForm.state.value = '';
            document.addUserForm.city.value = '';
            document.addUserForm.state.value = '';
            document.addUserForm.state.value = '';
    }
}

//-->
</script>

<div id="container">
 <form name="addUserForm" action="index.php" enctype="multipart/form-data" method="POST" >
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
	  <!--  MAIN TABLE STARTS -->
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top" align="left">
			  <!--  INNER MAIN TABLE STARTS (5) ROWS STARTS  -->
			  <table width="100%" border="0" style="vertical-align:middle; width:700px; ">
                  <tr>
                    <td>
                    <table style="vertical-align:middle;width:700px;margin-top:40px;">
                        <tr>
                          <td style="width:400px;">
                          <div id="breadcrumbNav" >
                          <a href="index.php?action=sysAdmin" >HOME </a> / <a href="index.php?action=accountAdminClinicList" >ACCOUNT </a> / <span class="highlight">ADD USER</span>
                          </div></td>
                          </tr>
                    </table>
                    <table style="padding-bottom:5px;">
                          <tr>
                          <td style="width:300px;">
                          <table border="0" cellpadding="5" cellspacing="0" style="float:right;" >
                              <tr>
                                <td class="iconLabel">&nbsp;</td>
                              </tr>
                            </table></td>
                        </tr>
                      </table></td>
                        </tr>
                      </table></td>
                  </tr>
				  <!--  1st ROW ENDS  -->
				  <!--
                 <tr>
                    <td class="adminSubHeading">Account Administration </td>
                  </tr>
                  -->
				  <!--  2nd ROW ENDS  -->
                  <tr>
                    <td class="error">&nbsp;<div id="errorDiv"><!error></div></td>
                  </tr>
				  <!--  3rd ROW ENDS  -->				  
                  <tr>
                    <td>
					<div  >
                         <!tabNavigation>
                    </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                        <div class="toptitle" style='padding-left:10px;'>Add user</div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                        <div >
						<table width="100%" border="0" cellspacing="2" cellpadding="2" style="border:1px solid #CCCCCC;">
                            <tr>
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="46%"><div>
                                        <table border="0" cellspacing="2" cellpadding="2" width="100%">
                                          <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                          </tr>
                                          <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                          </tr>
                                           <tr>
                                            <td><div align="right">
                                                <label for="name_first" onMouseOver="help_text(this, 'Enter the Salutation')")>&nbsp;Salutation :&nbsp;</label>
                                              </div></td>
                                            <td><select onmouseover="help_text(this, 'Select provider title')" tabindex="1" name="provider_title">
												<!provider_title>
												</select></td>
                                          </tr>
                                          <tr>
                                            <td><div align="right">
                                                <label for="name_first" onMouseOver="help_text(this, 'Enter the User\'s first name')")>*&nbsp;First Name :&nbsp;</label>
                                              </div></td>
                                            <td><input type="text" name="name_first" id="name_first" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the User\'s first name')" value="<!name_first>"/></td>
                                          </tr>
                                          <tr>
                                            <td><div align="right">
                                                <label for="name_last" onMouseOver="help_text(this, 'Enter the User\'s last name')")>*&nbsp;Last Name :&nbsp;</label>
                                              </div></td>
                                            <td><input type="text" name="name_last" id="name_last" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the User\'s last name')" value="<!name_last>"/></td>
                                          </tr>
                                          <tr>
                                            <td><div align="right">
                                                <label for="username" onMouseOver="help_text(this, 'Enter the email address used for this User to login to the application for accessing it.')")>*&nbsp;Email :&nbsp;</label>
                                              </div></td>
                                            <td><input type="text" name="username" id="username" size="20" maxlength="50" onMouseOver="help_text(this, 'Enter the email address used for this User to login to the application for accessing it.')" value="<!username>"/></td>
                                          </tr>
                                          <tr>
                                            <td><div align="right">
                                                <label for="confirmUsername" onMouseOver="help_text(this, 'Enter the email address used for this User to login to the application for accessing it.')")>*&nbsp;Confirm Email :&nbsp;</label>
                                              </div></td>
                                            <td><input type="text" name="confirmUsername" id="confirmUsername" size="20" maxlength="50" onMouseOver="help_text(this, 'Enter the email address used for this User to login to the application for accessing it.')" value="<!confirmUsername>"/></td>
                                          </tr>
                                          <tr>
                                            <td><div align="right">
                                                <label for="new_password" onMouseOver="help_text(this, 'Enter the User\'s password.')")>*&nbsp;New Password :&nbsp;</label>
                                              </div></td>
                                            <td><input type="password" name="new_password" id="new_password" size="20" maxlength="50" onMouseOver="help_text(this, 'Enter the password for this User.')"/></td>
                                          </tr>
                                          <tr class="inputRow">
                                            <td><div align="right">
                                                <label for="new_password2" onMouseOver="help_text(this, 'Enter the User\'s password (must match New Password)')")>*&nbsp;Confirm Password :&nbsp;</label>
                                              </div></td>
                                            <td><input type="password" name="new_password2" id="new_password2" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the User\'s password (must match New Password)')"/></td>
                                          </tr> 
                                           <tr  class="inputRow">
                                           <td><div align="right">
<label onMouseOver="help_text(this, 'Set the account privileges for the user')")>
											*&nbsp;Provider Type :&nbsp;</label></div></td>
                                                            <td><select name="practitioner_type" id="practitioner_type" ><!PractitionerOptions></select></td>
                                                        </tr>

                                          <tr>
                                            <td><div align="right">
                                                <label onMouseOver="help_text(this, 'Set the account privileges for the user')")>*&nbsp;Account Privileges :&nbsp;</label>
                                              </div></td>
                                            <td><input type="checkbox" name="therapistAccess" class="checkBoxAlign" <!checkedTherapist> />
                                              Provider</td>
                                          </tr>
                                          <tr>
                                            <td>&nbsp;</td>
                                            <td><input type="checkbox" name="adminAccess" class="checkBoxAlign" <!checkedAdmin> />
                                              Clinic Administrator </td>
                                          </tr>
                                         
                                          <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                          </tr>
                                        </table>
                                      </div></td>
                                    <td colspan="2" valign="top"><table cellspacing="1" cellpadding="1" width="100%" border="0">
                                        <tr>
                                          <td width="24%">&nbsp;</td>
                                          <td><input type="checkbox" name="clinicAddress"  class="checkBoxAlign" <!checkedAddress> onclick="populateClinicAddress(this,<!clinic_id>);" />
                                            <span > Address Same as Clinic </span> </td>
                                        </tr>
                                          <tr>
                                          <td >&nbsp;</td>
                                          <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td colspan="2">
                                          <div id="divAddress">
                                              <table width="100%" cellspacing="2" cellpadding="2" border="0">
                                            
                                                <tr>
                                                  <td width="26%"><div align="right">
                                                      <label for="address" onMouseOver="help_text(this, 'Enter the User\'s address')")>&nbsp;Address :&nbsp;</label>
                                                    </div></td>
                                                  <td width="74%"><input type="text" name="address" id="address" size="20" maxlength="50" onMouseOver="help_text(this, 'Enter the User\'s address')" value="<!address>"/></td>
                                                </tr>
                                                <tr>
                                                  <td>&nbsp;</td>
                                                  <td><input type="text" name="address2" id="address2" size="20" maxlength="50" onMouseOver="help_text(this, 'Enter the User\'s address line 2')" value="<!address2>"/></td>
                                                </tr>
                                                <tr>
                                                  <td><div align="right">
                                                      <label for="city" onMouseOver="help_text(this, 'Enter the User\'s city')")>&nbsp;City :&nbsp;</label>
                                                    </div></td>
                                                  <td><input type="text" name="city" id="city" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the User\'s city')" value="<!city>"/></td>
                                                </tr>
                                                <tr>
                                                  <td><div align="right">
                                                      <label for="state" onMouseOver="help_text(this, 'Enter the User\'s country')")>&nbsp;Country :&nbsp;</label>
                                                    </div></td>
                                                  <td><select id="country" onchange="toggleState();" name="clinic_country" tabindex="5" >
		
									<!patient_country_options>
									  </select>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td><div align="right">
                                                      <label for="state" onMouseOver="help_text(this, 'Enter the User\'s state')")>&nbsp;State / Province :&nbsp;</label>
                                                    </div></td>
                                                  <td><select name="state" id="state" onMouseOver="help_text(this, 'Enter the User\'s state')">
                                                      <!stateOptions>
                                                    </select>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td><div align="right">
                                                      <label for="zip" onMouseOver="help_text(this, 'Enter the User\'s zip code')")>&nbsp;Zip Code :&nbsp;</label>
                                                    </div></td>
                                                  <td><input type="text" name="zip" id="zip" size="10" maxlength="7" onMouseOver="help_text(this, 'Enter the User\'s zip code')" value="<!zip>"/></td>
                                                </tr>
												<tr>
													<td><div align="right">
														<label for="phone1" onMouseOver="help_text(this, 'Enter the User\'s phone number')")>&nbsp;Phone :&nbsp;</label>
													</div></td>
												  <td><input type="text" name="phone1" id="phone1" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the User\'s phone number')" value="<!phone1>"/></td>
												</tr>												
												<tr>
													<td><div align="right">
														<label for="fax" onMouseOver="help_text(this, 'Enter the User\'s fax number')")>&nbsp;Fax :&nbsp;</label>
													</div></td>
												   <td><input type="text" name="fax" id="fax" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the User\'s fax number')" value="<!fax>"/></td>
												</tr>
                                               
                                              </table>
                                            </div></td>
                                        </tr>
                                      </table></td>
                                  </tr>
                                </table>
                                </td>
                            </tr>
                          </table>
                          <div style="float:right;">
                           <input type="button" name="Submit" value="Add User" onclick="popupConfirm();"/>
                      </div>
						</div>
                      </td>
                  </tr>
				  <!--  4th ROW ENDS  -->
                  
				  <!--  5th ROW ENDS  -->
                </table></div>
				<!--  INNER MAIN TABLE STARTS (5) ROWS ENDS -->
				</td>
            </tr>
          </table>
          <!--  MAIN TABLE ENDS  -->
        <!-- div ( mainContent ) Ends --></td>
    </tr>
    <tr>
      <td colspan="2"><div id="footer">
          <!footer>
        </div></td>
    </tr>
  </table>
  <input type="hidden" name="submitted_add" value="Add User"/>
  <input type="hidden" name="action" value="addUserHead"/>
  <input type="hidden" name="clinic_id" value="<!clinic_id>"/>
  </form>
</div><!-- div ( container ) Ends -->
<script type="text/javascript">
    mmLoadMenus("<!showmenu>");
</script>