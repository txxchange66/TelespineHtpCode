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
	userId = document.editUserForm.id.value;
	
	if ( document.editUserForm.therapistAccess.checked == true)
	{
		checkValidation(userId,<!clinic_id>);	
	}
	else
	{
		document.editUserForm.submit();
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
                                                
            document.editUserForm.address.value = '';
            document.editUserForm.address2.value = '';
            document.editUserForm.phone1.value = '';
            document.editUserForm.zip.value = '';
            document.editUserForm.state.value = '';
            document.editUserForm.city.value = '';
            document.editUserForm.state.value = '';
            document.editUserForm.state.value = '';
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
                var csw = window.open('index.php?action=showPopupMsgHead&id='+userId, 'showConfirmWindow', 'width=600, height=470, status=no, toolbar=no, resizable=no, scrollbars=no');
                csw.focus();    
            }    
        }
        else
        {    
            if(!csw) 
            {
                var csw = window.open('index.php?action=showPopupMsgHead&id='+userId, 'showConfirmWindow', 'width=600, height=310, status=no, toolbar=no, resizable=no, scrollbars=no');
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

<div id="container">
<form name="editUserForm" action="index.php" enctype="multipart/form-data" method="POST" >
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
      <div id="mainContent">
	  <!--  MAIN TABLE STARTS -->
          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="vertical-align:middle;width:700px;margin-top:45px;">
            <tr>
              <td valign="top" align="left" style="width:400px;">
			  <!--  INNER MAIN TABLE STARTS (5) ROWS STARTS  -->
                <div id="breadcrumbNav" ><a href="index.php?action=sysAdmin" >HOME </a> / <a href="index.php?action=listAccountClinic" >ACCOUNT</a> / <span class="highlight">EDIT USER</span></div>
              </td>
            </tr>
          </table>
          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:60px;"> 
            <tr>
                <td class="error"><div id="errorDiv"><!error></div></td>
            </tr>
            <!--  3rd ROW ENDS  -->				  
            <tr>
                <td>
                    <div >
                        <!tabNavigation>
                    </div>
                <td>
            </tr>
            <tr>
                <td>
                    <div  class="toptitle" style='padding-left:10px;' >Edit user</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div >
                        <table width="100%" border="0" cellspacing="2" cellpadding="2" style="border:1px solid #CCCCCC;">
                            <tr>
                                <td colspan="2">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td width="46%">
                                                <div>
                                                    <table cellspacing="2" cellpadding="2">
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
                                                <label for="name_first" onMouseOver="help_text(this, 'Enter the User\'s first name')")>&nbsp;Salutation :&nbsp;</label>
                                              </div></td>
                                            <td><select onmouseover="help_text(this, 'Select provider title')" tabindex="1" name="name_title">
												<!name_title>
												</select></td>
                                          </tr>
                                                        <tr>
                                                            <td><div align="right"><label for="name_first" >*&nbsp;First Name :&nbsp;</label> </div></td>
                                                            <td><input type="text" name="name_first" id="name_first" size="20" maxlength="20" value="<!name_first>"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td><div align="right"><label for="name_last" >*&nbsp;Last Name :&nbsp;</label></div></td>
                                                            <td><input type="text" name="name_last" id="name_last" size="20" maxlength="20" value="<!name_last>"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td><div align="right"><label for="username" >*&nbsp;Email :&nbsp;</label></div></td>
                                                            <td><input type="text" name="username" id="username" size="20" maxlength="50" value="<!username>"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td><div align="right"><label for="new_password" >&nbsp;Password :&nbsp;</label></div></td>
                                                            <td><input type="password" name="new_password" id="new_password" size="20" maxlength="50" value="<!new_password>" /></td>
                                                        </tr>
                                                        <tr class="inputRow">
                                                            <td><div align="right"><label for="new_password2" >&nbsp;Confirm Password :&nbsp;</label></div></td>
                                                            <td><input type="password" name="new_password2" id="new_password2" size="20" maxlength="20" value="<!new_password2>" /></td>
                                                        </tr>
                                                      <tr class="inputRow">
                                                            <td><div align="right"><label onMouseOver="help_text(this, 'Set the account privileges for the user')")>
											*&nbsp;Provider Type :</label>
                                                </div></td>
                                                            <td><select name="practitioner_type" id="practitioner_type" ><!PractitionerOptions></select></td>
                                                        </tr>
                                                        <tr>
                                                            <td><div align="right"><label >*&nbsp;Account Privileges : </label></div></td>
                                                            <td><input type="checkbox" id="therapistAccess" name="therapistAccess" class="checkBoxAlign" <!checkedTherapist> onclick="showPopupMsg();"/>Provider</td>
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
			                                </div>
                                        </td>
                                        <td colspan="2" valign="top">
                                            <table cellspacing="1" cellpadding="1" width="100%">
                                                <tr>
                                                    <td width="25%">&nbsp;</td>
                                                    <td>
                                                          <input type="checkbox" name="clinicAddress"  class="checkBoxAlign" <!checkedAddress> onclick="populateClinicAddress(this,<!clinic_id>);" />
                                                          <span > Address Same as Clinic </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div id="divAddress">
                                                            <table cellspacing="2" cellpadding="2" width="100%" border="0">
                                                                <tr>
                                                                    <td width="26%"><div align="right"><label for="address" >&nbsp;Address :&nbsp;</label></div></td>
                                                                    <td width="74%"><input type="text" name="address" id="address" size="20" maxlength="50" value="<!address>"/></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td><input type="text" name="address2" id="address2" size="20" maxlength="50" value="<!address2>"/></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><div align="right"><label for="city" >&nbsp;City :&nbsp;</label></div></td>
                                                                    <td><input type="text" name="city" id="city" size="20" maxlength="20" value="<!city>"/></td>
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
                                                                    <td><div align="right"><label for="state" >&nbsp;State / Province :&nbsp;</label></div></td>
                                                                    <td>
                                                                        <select name="state" id="state" >
						                                                    <!stateOptions>
					                                                    </select>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><div align="right"><label for="zip" >&nbsp;Zip Code :&nbsp;</label> </div></td>
                                                                    <td><input type="text" name="zip" id="zip" size="10" maxlength="7" value="<!zip>"/></td>
                                                                </tr>
                                                                <tr>
	                                                                <td><div align="right"><label for="phone1" >&nbsp;Phone :&nbsp;</label></div></td>
	                                                                <td><input type="text" name="phone1" id="phone1" size="20" maxlength="20" value="<!phone1>"/></td>
                                                                </tr>
                                                                <tr>
	                                                                <td><div align="right"><label for="fax" >&nbsp;Fax :&nbsp;</label></div></td>
	                                                                <td><input type="text" name="fax" id="fax" size="20" maxlength="20" value="<!fax>"/></td>
                                                                </tr>
  
                                                            </table>
                                                            </div>
                                                            </td>
                                                            </tr>
                                                        </table>
                                                        </td>
                                                        </tr>
                                                        </table></td>
                                                        </tr>
                                                        </table>
                                                        </div>
                                                        <div style="float:right;">
                                                            <input type="button" name="Submit" value="Save User" onClick="popupConfirm();"/>
                                                        </div>
                                                        </td>
                                                        </tr>
				                                        <!--  4th ROW ENDS  -->
                                                        <tr>
                                                            <td>&nbsp;</td>
                                                        </tr>
				                                        <!--  5th ROW ENDS  -->
                                                        </table>
                                                        </div>
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
	<input type="hidden" name="submitted_edit" value="Edit User"/>
	<input type="hidden" name="action" value="editUserHead"/>
	<input type="hidden" name="id" value="<!id>"/>
	<input type="hidden" name="clinic_id" value="<!clinic_id>"/>
	<input type="hidden" name="therapist_access" id="therapist_access" value="<!therapist_access>"/>
	<input type="hidden" name="patient_association" id="patient_association" value="<!patient_association>"/>						
	<input type="hidden" name="firstSubscription" id="firstSubscription" value="<!firstSubscription>"/>  	
  </form>
</div><!-- div ( container ) Ends -->
<script type="text/javascript">
    mmLoadMenus("<!showmenu>");
</script>