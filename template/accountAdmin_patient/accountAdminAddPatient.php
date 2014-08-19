<script language="JavaScript" src="js/validateform.js"></script>
<script language="JavaScript">
<!--
window.formRules = new Array(
    new Rule("patient_first_name", "First name", true, "string|0,20"),
    new Rule("patient_last_name", "Last name", true, "string|0,50"),
    new Rule("patient_email", "email address", true, "email"),
    new Rule("patient_cemail", "confirm email address", true, "email"),
    new Rule("patient_address", "address", false, "string|0,50"),
    new Rule("patient_address2", "address line 2", false, "string|0,50"),    
    new Rule("patient_phone1", "1st phone number", false, "usphone"),
    new Rule("patient_phone2", "2nd phone number", false, "usphone"),
    new Rule("patient_city", "city", false, "string|0,50"),
    new Rule("patient_state", "State", false, "string|0,2"),
    new Rule("patient_zip", "Zip Code", false, "zipcode")    
                            );
// -->
</script>
<input type="hidden"  name="showsoap" id="showsoap" value="<!shopshow>">
<script language="JavaScript" src="js/show_menu.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>


<div id="container">
<form name="clinicdetail" id="clinic_detail" action="index.php?action=accountAdminEditPatients&patient_id=<!patient_id>" method="post" onload="this.patient_title.focus();">
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
                </tr>
                <tr>
                    <td colspan="5" valign="top"  >
                        <div class="error" style="padding-top:10px;"><!error></div>
                        <div style="padding-top:11px;width:741px;float:left;">
                            <!navigationTab>
                        </div>
                    </td>
                </tr>
              <tr>
                <td width="151" valign="top" class="toptitle" style="padding-left:10px;" ><!operationTitle> Patient</td>
                <td width="143" valign="top" class="toptitle" >&nbsp;</td>
                <td width="146" valign="top" class="toptitle" >&nbsp;</td>
                <td valign="top" class="toptitle" >&nbsp;</td>
                <td valign="top" class="toptitle" >&nbsp;</td>
              </tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="1" style="border:1px solid #CCCCCC;" >
                              
                                  <tr>
                                    <td><div align="right" ><strong>Referring Dr. &nbsp;</strong></div></td>
                                    <td><input type="text" name="refering_physician" id="refering_physician" maxlength="20" value="<!refering_physician>" /></td>
                                  </tr>

                                  <tr>
                                    <td width="22%"><div align="right" ><strong>Title  &nbsp; </strong></div></td>
                                    <td width="22%"><select name="patient_title" tabindex="1" >
                                        <!patient_title_options>
                                      </select></td>
                                    <td width="2%">&nbsp;</td>

                                    <td width="13%"><div align="right"   ><strong>Address &nbsp; </strong></div></td>
                                    <td width="41%"><input tabindex="9" maxlength="50" type="text"  name="patient_address" value="<!patient_address>"></td>
                                  </tr>
                                  <tr>
                                    <td><div align="right"  ><strong>* First Name  &nbsp; </strong></div></td>
                                    <td><input tabindex="2" maxlength="20" type="text" name="patient_first_name"  value='<!patient_first_name>'></td>
                                    <td>&nbsp;</td>
                                    <td></td>

                                    <td><input tabindex="10" maxlength="50" type="text" name="patient_address2" value="<!patient_address2>"/></td>
                                  </tr>
                                  <tr>
                                    <td><div align="right"  ><strong>* Last Name &nbsp; </strong></div></td>
                                    <td><input tabindex="3" maxlength="20" type="text"  name="patient_last_name" value="<!patient_last_name>"></td>
                                    <td>&nbsp;</td>
                                    <td><div align="right" ><strong>City &nbsp; </strong></div></td>
                                    <td><input tabindex="11" maxlength="20" type="text"  name="patient_city" value="<!patient_city>"></td>
                                  </tr>
                                  <tr>
                                    <td><div align="right" ><strong>Suffix &nbsp;</strong> </div></td>
                                    <td>
                                    <select name="patient_suffix" tabindex="4" >
                                        <!patient_suffix_options>
                                    </select>
                                    </td>
                                    <td>&nbsp;</td>
                                    <td><div align="right" ><strong> State / Province &nbsp; </strong></div></td>
                                    <td><select name="patient_state" tabindex="12" >
                                        <!patient_state_options>
                                      </select></td>
                                  </tr>
                                  <tr>
                                    <td><div align="right" ><strong>* Email &nbsp;</strong> </div></td>
                                    <td><input tabindex="5" maxlength="50" type="text" name="patient_email" value="<!patient_email>" /></td>
                                    <td>&nbsp;</td>
                                    <td><div align="right" ><strong>Zip Code &nbsp;</strong> </div></td>

                                    <td><input tabindex="13" maxlength="7" type="text" name="patient_zip"  value="<!patient_zip>" /></td>
                                  </tr>
                                  <tr>
                                    <td><div align="right"  name="patient_cemail"><strong>* Confirm Email &nbsp;</strong> </div></td>
                                    <td><input tabindex="6" maxlength="50" type="text"  name="patient_cemail" value="<!patient_cemail>" /></td>
                                    <td>&nbsp;</td>
                                    <td><div align="right" ><strong>Phone 1&nbsp;</strong> </div></td>

                                    <td><input tabindex="14" type="text" name="patient_phone1" value="<!patient_phone1>" /></td>
                                  </tr>
                                  <tr>
                                    <td><!newPasswordLabel></td>
                                    <td><!newPasswordField></td>
                                    <td>&nbsp;</td>
                                    <td><div align="right" ><strong>Phone 2&nbsp;</strong> </div></td>

                                    <td><input tabindex="15" type="text" name="patient_phone2" value="<!patient_phone2>" /></td>
                                  </tr>
                                  <tr>
                                    <td><!confirmPasswordTextField></td>
                                    <td><!confirmPasswordField></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>

                                  <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td><div align="right" ><strong>Status &nbsp;</strong> </div></td>
                                    <td><select name="patient_status" tabindex="16" onMouseOver="help_text(this, 'Select your status')" >
                                        <!patient_status_options>
                                      </select></td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td rowspan="2" align="left" valign="top">
                                        <a href="javascript:void(0);" tabindex="17" onclick="GB_showCenter('Edit Reminder', '/index.php?action=editReminder&patient_id=<!editReminderlink>', 550, 800 );"  ><strong>Edit Reminder</strong></a><br/>
                                        <a tabindex="18" href="javascript:void(0);" onclick="GB_showCenter('Associate Provider', '/index.php?action=associateTherapist&patient_id=<!editReminderlink>&actionActivateFrom=accountAdmin', 550, 800 );"  ><strong>Edit Associated Providers</strong></a>
                                    </td>
                                  </tr>
                                  <tr>
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
                                        <div class="paging" align="right">
                                          <input tabindex="19" type="submit" name="Submit" value=" <!buttonName> " />
                                        </div>
</td>
    </tr>
    <tr>
      <td colspan="2"><div id="footer">
          <!footer>
        </div></td>
    </tr>
  </table>
  <input type="hidden" name="option" value="update" />
  <input type="hidden" name="patient_id" value="<!patient_id>" />  
  </form>
</div><!-- div ( container ) Ends -->

<script>
<!--
    window.load = document.clinicdetail.patient_title.focus();
-->
</script>
<script type="text/javascript">
    mmLoadMenus("<!showmenu>");
</script>