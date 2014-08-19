<input type="hidden"  name="showsoap" id="showsoap" value="<!shopshow>">
<script language="JavaScript" src="js/addressPopulate.js"></script>
<script language="JavaScript" src="js/common.js"></script>
<script src="js/countryState.js"></script>
<script type="text/javascript">
<!--

function popupConfirm()
{
			
	document.getElementById('errorDiv').innerHTML ="";
	userId = document.editUserForm.id.value;
	
	if ( document.editUserForm.therapistAccess.checked == true)
	{
		checkValidation(userId,'');	
	}
	else
	{
		document.editUserForm.submit();
	}
	
		
}


function showPopupMsg()
{

	var therapist_access = document.editUserForm.therapist_access.value;
	var patient_association = document.editUserForm.patient_association.value;
	
	var userId = document.editUserForm.id.value;
	
	var c = false;		
	
	if (therapist_access == 1 && document.editUserForm.therapistAccess.checked == false)
	{
		if (patient_association == 1 )
		{
			if(!csw) 
			{
				var csw = window.open('index.php?action=showPopupMsg&id='+userId, 'showConfirmWindow', 'width=600, height=470, status=no, toolbar=no, resizable=no, scrollbars=no');
				csw.focus();	
			}	
		}
		else
		{	
			if(!csw) 
			{
				var csw = window.open('index.php?action=showPopupMsg&id='+userId, 'showConfirmWindow', 'width=600, height=310, status=no, toolbar=no, resizable=no, scrollbars=no');
				csw.focus();	
			}
		}
	}
	else
	{
		//do not show message
	}	
	
}

//-->
</script>
<script language="JavaScript" src="js/show_menu.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>


<div id="container">
 <form name="editUserForm" action="index.php" enctype="multipart/form-data" method="POST" >
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
                <td colspan="5" style="height:9px;"></td>
            </tr>
            <tr>
                <td colspan="5" style="width:741px;">
                    <div id="breadcrumbNav" style="margin-top:40px;float:left;width:550px;">
                        <a href="index.php">HOME</a> / <a href="index.php?action=userListing">ACCOUNT ADMINISTRATOR </a>/ <span class="highlight"  >CLINIC</span>
                    </div>
                </td>
</tr>
<tr>
    <td colspan="5" valign="top" style=" width:741px; font-size: large;font-weight: bold;padding-bottom:19px; margin:0px; vertical-align:top;">&nbsp;</td>
</tr>
<tr>
    <td colspan="5" valign="top"  >
        <div class="error" id="errorDiv">&nbsp;<!error></div>
        <div style="padding-top:11px;width:741px;float:left;">
            <!navigationTab>
        </div>
    </td>
 </tr>
  <tr>
    <td width="151" valign="top" class="toptitle" style="padding-left:10px;" >Edit User</td>
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
                                                <label for="name_last" )>*&nbsp;Last Name :&nbsp;</label>
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
                                                <label for="new_password" >*&nbsp;Password :&nbsp;</label>
                                              </div></td>
                                            <td><input type="password" name="new_password" id="new_password" size="20" maxlength="50" value="<!new_password>"  /></td>
                                          </tr>
                                          <tr class="inputRow">
                                            <td><div align="right">
                                                <label for="new_password2" >*&nbsp;Confirm Password :&nbsp;</label>
                                              </div></td>
                                            <td><input type="password" name="new_password2" id="new_password2" size="20" maxlength="20" value="<!new_password2>" /></td>
                                          </tr>
                                           <tr>
                                            <td><div align="right">
                                                <label >*&nbsp;Provider Type:&nbsp;</label>
                                              </div></td>
                                            <td><select name="practitioner_type" id="practitioner_type" ><!PractitionerOptions></select></td>
                                          </tr>
                                          <tr>
                                            <td><div align="right">
                                                <label>*&nbsp;Account Privileges :&nbsp;</label>
                                              </div></td>
                                            <td><input type="checkbox" id="therapistAccess" name="therapistAccess" class="checkBoxAlign" <!checkedTherapist> onclick="showPopupMsg();" />
                                              Provider</td>
                                          </tr>
                                          <tr>
                                            <td>&nbsp;</td>
                                            <td><input type="checkbox" name="adminAccess" class="checkBoxAlign" <!checkedAdmin> <!editAccess> />
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
                                          <td><input  type="checkbox" name="clinicAddress" class="checkBoxAlign" <!checkedAddress> onclick="populateAddress('Edit');" />
                                            <span> Address Same as Clinic </span> </td>
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
                                                      <label for="state" >&nbsp;Country :&nbsp;</label>
                                                    </div></td>
                                                  <td><select id="country" onchange="toggleState();" name="clinic_country" tabindex="5" >
		
									<!patient_country_options>
									  </select>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td><div align="right">
                                                      <label for="state" >&nbsp;State / Province :&nbsp;</label>
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
  <input type="button" name="Submit" value="Save User" onClick="popupConfirm();"/>
</div>
</td>
    </tr>
    <tr>
      <td colspan="2"><div id="footer">
          <!footer>
        </div></td>
    </tr>
  </table>
	<input type="hidden" name="submitted_edit" value="Edit User"/>
	<input type="hidden" name="action" value="editUser"/>
	<input type="hidden" name="id" value="<!id>"/>
	<input type="hidden" name="therapist_access" id="therapist_access" value="<!therapist_access>"/>
	<input type="hidden" name="patient_association" id="patient_association" value="<!patient_association>"/>						
	<input type="hidden" name="firstSubscription" id="firstSubscription" value="<!firstSubscription>"/>  	
  </form>
</div><!-- div ( container ) Ends -->
<script language="JavaScript1.2">mmLoadMenus();</script>