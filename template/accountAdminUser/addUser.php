<script language="JavaScript" src="js/addressPopulate.js"></script>
<script language="JavaScript" src="js/common.js"></script>
	
<script type="text/javascript">
<!--

function popupConfirm()
{
	
		
	document.getElementById('errorDiv').innerHTML ="";
	
	if (document.addUserForm.therapistAccess.checked == true)
	{
		checkValidation('NA','');
	}
	else
	{
		document.addUserForm.submit();
	}
		
		
}

//-->
</script>
<script language="JavaScript" src="js/show_menu.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>


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
<table border="0" cellpadding="0" cellspacing="0" style="vertical-align:middle; width:100%; height:81px;">
  <tr>
    <td colspan="5" style="height:9px;">    </td>
    </tr>
  <tr>
    <td colspan="5" style="width:741px;">
        <div id="breadcrumbNav" style="margin-top:40px;float:left;width:550px;" >
            <a href="index.php">HOME</a> / <a href="index.php?action=userListing">ACCOUNT ADMINISTRATOR </a>/ <span class="highlight"  >CLINIC</span>
        </div>
    </td>
  <tr>
    <td colspan="5" valign="top" style=" width:400px; font-size: large;font-weight: bold;padding-bottom: 35px; margin:0px; vertical-align:top;">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" valign="top" >
    <div class="error" id="errorDiv"><!error></div>
    <div style="padding-top:11px;width:741px;float:left;">
        <!navigationTab>
    </div>
     </td>
   </tr>
  <tr>
    <td width="151" valign="top" class="toptitle" style="padding-left:10px;" >Add User</td>
    <td width="143" valign="top" class="toptitle" >&nbsp;</td>
    <td width="146" valign="top" class="toptitle" >&nbsp;</td>
    <td valign="top" class="toptitle" >&nbsp;</td>
    <td valign="top" class="toptitle" >&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC;">
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
                                                <label for="name_first" >*&nbsp;First Name :&nbsp;</label>
                                              </div></td>
                                            <td><input type="text" name="name_first" id="name_first" size="20" maxlength="20" value="<!name_first>"/></td>
                                          </tr>
                                          <tr>
                                            <td><div align="right">
                                                <label for="name_last" >*&nbsp;Last Name :&nbsp;</label>
                                              </div></td>
                                            <td><input type="text" name="name_last" id="name_last" size="20" maxlength="20" value="<!name_last>"/></td>
                                          </tr>
                                          <tr>
                                            <td><div align="right">
                                                <label for="username" >*&nbsp;Email :&nbsp;</label>
                                              </div></td>
                                            <td><input type="text" name="username" id="username" size="20" maxlength="50" value="<!username>"/></td>
                                          </tr>
                                          <tr>
                                            <td><div align="right">
                                                <label for="confirmUsername" >*&nbsp;Confirm Email :&nbsp;</label>
                                              </div></td>
                                            <td><input type="text" name="confirmUsername" id="confirmUsername" size="20" maxlength="50" onMouseOver="help_text(this, 'Enter the email address used for this User to login to the application for accessing it.')" value="<!confirmUsername>"/></td>
                                          </tr>
                                          <tr>
                                            <td><div align="right">
                                                <label for="new_password" >*&nbsp;New Password :&nbsp;</label>
                                              </div></td>
                                            <td><input type="password" name="new_password" id="new_password" size="20" maxlength="50" /></td>
                                          </tr>
                                          <tr class="inputRow">
                                            <td><div align="right">
                                                <label for="new_password2" >*&nbsp;Confirm Password :&nbsp;</label>
                                              </div></td>
                                            <td><input type="password" name="new_password2" id="new_password2" size="20" maxlength="20" /></td>
                                          </tr>
                                           <tr>
                                            <td><div align="right">
                                                <label >*&nbsp;Provider Type:&nbsp;</label>
                                              </div></td>
                                            <td><select name="practitioner_type" id="practitioner_type" ><!PractitionerOptions></select></td>
                                          </tr>
                                          <tr>
                                            <td><div align="right">
                                                <label >*&nbsp;Account Privileges :&nbsp;</label>
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
                                    <td colspan="2" valign="top"><table cellspacing="2" cellpadding="2" width="100%">
                                        <tr>
                                          <td width="24%">&nbsp;</td>
                                          <td><input type="checkbox" name="clinicAddress" class="checkBoxAlign" <!checkedAddress> onclick="populateAddress('Add');" />
                                            <span > Address Same as Clinic </span> </td>
                                        </tr>
                                        <tr>
                                          <td>&nbsp;</td>
                                          <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td colspan="2"><div id="divAddress">
                                              <table width="100%" cellspacing="2" cellpadding="2">
                                                <tr>
                                                  <td width="26%"><div align="right">
                                                      <label for="address" >&nbsp;Address :&nbsp;</label>
                                                    </div></td>
                                                  <td width="74%"><input type="text" name="address" id="address" size="20" maxlength="50" value="<!address>"/></td>
                                                </tr>
                                                <tr>
                                                  <td>&nbsp;</td>
                                                  <td><input type="text" name="address2" id="address2" size="20" maxlength="50" value="<!address2>"/></td>
                                                </tr>
                                                <tr>
                                                  <td><div align="right">
                                                      <label for="city" >&nbsp;City :&nbsp;</label>
                                                    </div></td>
                                                  <td><input type="text" name="city" id="city" size="20" maxlength="20" value="<!city>"/></td>
                                                </tr>
                                                <tr>
                                                  <td><div align="right">
                                                      <label for="state" >&nbsp; State / Province :&nbsp;</label>
                                                    </div></td>
                                                  <td><select name="state" id="state" >
                                                      <!stateOptions>
                                                    </select>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td><div align="right">
                                                      <label for="zip" >&nbsp;Zip Code :&nbsp;</label>
                                                    </div></td>
                                                  <td><input type="text" name="zip" id="zip" size="10" maxlength="7" value="<!zip>"/></td>
                                                </tr>
												<tr>
													<td><div align="right">
														<label for="phone1" >&nbsp;Phone :&nbsp;</label>
													</div></td>
												  <td><input type="text" name="phone1" id="phone1" size="20" maxlength="20" value="<!phone1>"/></td>
												</tr>												
												<tr>
													<td><div align="right">
														<label for="fax" >&nbsp;Fax :&nbsp;</label>
													</div></td>
												   <td><input type="text" name="fax" id="fax" size="20" maxlength="20" value="<!fax>"/></td>
												</tr>
                                                <tr>
                                                  <td>&nbsp;</td>
                                                  <td>&nbsp;</td>
                                                </tr>
                                              </table>
                                            </div></td>
                                        </tr>
                                      </table></td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td width="27%" align="right">&nbsp;</td>
                                    <td width="27%" align="right">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td colspan="3">&nbsp;</td>
                                  </tr>
                                </table>
<div class="paging" align="right">
  <input type="button" name="Submit" value="Add User" onclick="popupConfirm();"/>
</div></td>
    </tr>
    <tr>
      <td colspan="2"><div id="footer">
          <!footer>
        </div></td>
    </tr>
  </table>
  <input type="hidden" name="submitted_add" value="Add User"/>
  <input type="hidden" name="action" value="addUser"/>
  </form>
</div><!-- div ( container ) Ends -->
<script language="JavaScript1.2">mmLoadMenus();</script>