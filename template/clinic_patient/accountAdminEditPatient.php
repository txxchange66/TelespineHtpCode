<!-- Grey box -->
<script type="text/javascript">  
var GB_ROOT_DIR = "js/greybox/";
</script>
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
<!-- End -->

<script language="JavaScript" src="js/validateform.js"></script>
<script src="js/countryState.js"></script>
<script src="js/jquery.tools.min.js"></script>

     


    <!-- tooltip styling -->
    <style>
    
    .tooltip {
        display:none;
        background-color:#F4F4f4;
        border:1px solid #cc9;
        width:200px;
        padding:3px;
        font-size:13px;
        position: absolute;        
        -moz-box-shadow: 2px 2px 11px #666;
        -webkit-box-shadow: 2px 2px 11px #666;
        z-index:1000000;
      /* for IE */
          
    }


</style>
<script>
// What is $(document).ready ? See: http://flowplayer.org/tools/documentation/basics.html#document_ready
$(document).ready(function() {
   
    $(".enable-con-inner img").mouseover(function(){
        
         $(".tooltip").html($(this).attr("name")).show().css({marginTop:$(this).offset().top+10+"px", marginLeft:$(this).offset().left+17+"px"});
        
    });
    $(".enable-con-inner img").mouseout(function(){
        
     $(".tooltip").hide();   
        
    });    
    /*  
    $("img[title]").tooltip({

        // use div.tooltip as our tooltip
        tip: '.tooltip',

        // use the fade effect instead of the default
        effect: 'fade',

        // make fadeOutSpeed similar to the browser's default
        fadeOutSpeed: 100,

        // the time before the tooltip is shown
        predelay: 0,

        // tweak the position
        position: "top right",
        offset: [-5, -350]
    }); */
});
</script>
<script language="JavaScript">
function action_handler(value, userId, subs_status, subscrp_id, clinicId, ehsAction,paymentType) { 
        
        if(value){document.getElementById('ehsEnable').checked=false;}else document.getElementById('ehsEnable').checked=true;
if(paymentType == '1') {
                url = '/index.php?action=ehsonetimepaymentsysadmin&userId=' +userId+'&ehs= ' +ehs+'&clinicId= ' +clinicId;
                 GB_showCenter('E-Health Service Subscription Status', url, 100,550 );
                
        } else {

                if(value == true && userId!= '') {//Ehs enable case
                        var ehs = 1;
                         url = '/index.php?action=system_admin_patient_ehs_unsubscribe&userId=' +userId+'&ehs= ' +ehs+'&clinicId= ' +clinicId+'&ehsAction= ' +ehsAction;
                        GB_showCenter('E-Health Service Subscription Status', url, 130,550 );
                }
                else if(value == false && subs_status == '0') {//Ehs disable case
                        var ehs = 0;
                        url = '/index.php?action=system_admin_patient_ehs_unsubscribe&userId=' +userId+'&ehs= ' +ehs+'&clinicId= ' +clinicId+'&ehsAction= ' +ehsAction;
                        GB_showCenter('E-Health Service Subscription Status', url, 130,550 );
               } else {//Ehs paypal cancellation case
                       url = '/index.php?action=systemadmineHealthUnsub&subscrp_id=' +subscrp_id+'&userId= ' +userId+'&clinicId= ' +clinicId+'&ehsAction= ' +ehsAction;
                       GB_showCenter('E-Health Service Subscription Status', url, 170,500 );

                }

        }
 
}


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

<div id="container">
<form name="clinicdetail" id="clinic_detail" action="index.php?action=SystemAdminEditPatients&patient_id=<!patient_id>&clinic_id=<!clinic_id>" method="post" onload="this.patient_title.focus();">
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
			  <table width="100%" border="0" style="vertical-align:middle; width:700px;margin-top:35px;padding-bottom:15px;">
                  <tr>
                    <td>
                        <table style="vertical-align:middle;width:700px;">
                            <tr>
                            <td style="width:400px;"><div id="breadcrumbNav"><a href="index.php?action=sysAdmin" >HOME </a> / <a href="index.php?action=listClinic" >ACCOUNT</a> / <span class="highlight"><!account_name></span> / <span class="highlight">EDIT PATIENT</span></div></td>
                            </tr>
                        </table>
                    </td>
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
					<div style='margin-top:18px;' >
                       <!tabNavigation>
                      </div>
                     <td>
                    </tr>
                    <tr>
                        <td>
                            <div class="toptitle" style="padding-left:10px;" ><!operationTitle> Patient</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div >
					  <table width="100%" border="0" cellspacing="2" cellpadding="2" style="border:1px solid #CCCCCC;">

                            <tr>
                              <td colspan="2"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                              
								  <tr>
									<td><div align="right" onMouseOver="help_text(this, 'Enter the Referring Dr. name')"><strong>Referring Dr. :</strong></div></td>
									<td><input type="text" name="refering_physician" id="refering_physician" maxlength="20" onMouseOver="help_text(this, 'Enter the Referring Dr. name')" value="<!refering_physician>" /></td>
									<td width="2%">&nbsp;</td>
								 <td width="13%"><div align="right"  onMouseOver="help_text(this, 'Enter patients address')" ><strong>Address : </strong></div></td>
                                    				<td width="41%"><input tabindex="9" maxlength="50" type="text"  onMouseOver="help_text(this, 'Enter patients address')" name="patient_address" value="<!patient_address>"></td>
                                  
								  </tr>
                                  <tr>
                                    <td width="22%"><div align="right" onMouseOver="help_text(this, 'Select patient title')"><strong>Title  : </strong></div></td>
                                    <td width="22%"><select name="patient_title" tabindex="1" onMouseOver="help_text(this, 'Select patient title')">
                                        <!patient_title_options>
                                      </select></td>
                                    <td width="2%">&nbsp;</td>
                                    <td></td>
                                    <td><input tabindex="10" maxlength="50" type="text"  onMouseOver="help_text(this, 'Enter address for line2')" name="patient_address2" value="<!patient_address2>"/></td>
                                 
                                    </tr>
                                  <tr>
                                    <td><div align="right"  onMouseOver="help_text(this, 'Enter patient first name')"><strong>* First Name  : </strong></div></td>
                                    <td><input tabindex="2" maxlength="20" type="text" name="patient_first_name"  onMouseOver="help_text(this, 'Enter patient first name')" value='<!patient_first_name>'></td>
                                    <td>&nbsp;</td>
                                     <td><div align="right"  onMouseOver="help_text(this, 'Enter city for patient')" ><strong>City : </strong></div></td>
                                    <td><input tabindex="11" maxlength="20" type="text"  onMouseOver="help_text(this, 'Enter city for patient')" name="patient_city" value="<!patient_city>"></td>
                                 
                                     </tr>
                                  <tr>
                                    <td><div align="right"  onMouseOver="help_text(this, 'Enter patient last name')" ><strong>* Last Name : </strong></div></td>
                                    <td><input tabindex="3" maxlength="20" type="text"  onMouseOver="help_text(this, 'Enter patient last name')" name="patient_last_name" value='<!patient_last_name>'></td>
                                    <td>&nbsp;</td>
                                    <td><div align="right" onMouseOver="help_text(this, 'Select country from list')"><strong>Country : </strong></div></td>
                                    <td><select id="country" onchange="toggleState();" name="clinic_country" tabindex="5" >

							<!patient_country_options>
							  </select></td>
                                    </tr>
                                  <tr>
                                    <td><div align="right" onMouseOver="help_text(this, 'Select Patient suffix')"><strong>Suffix :</strong> </div></td>
                                    <td><select name="patient_suffix" tabindex="4" onMouseOver="help_text(this, 'Select Patient suffix')">
                                        <!patient_suffix_options>
                                      </select>                                    </td>
                                    <td>&nbsp;</td>
                                    <td><div align="right" onMouseOver="help_text(this, 'Select state from list')"><strong>State / Province : </strong></div></td>
                                    <td><select id="state" name="patient_state" tabindex="12" onMouseOver="help_text(this, 'Select state from list')">
                                        <!patient_state_options>
                                      </select></td>
                                  </tr>
                                  <tr>
                                    <td><div align="right" onMouseOver="help_text(this, 'Enter email address of patient')" ><strong>* Email :</strong> </div></td>
                                    <td><input tabindex="5" maxlength="50" type="text" name="patient_email" onMouseOver="help_text(this, 'Enter email address of patient')" value="<!patient_email>" /></td>
                                    <td>&nbsp;</td>
                                    <td><div align="right"  onMouseOver="help_text(this, 'Enter zip code in 5 numeric character')" ><strong>Zip Code :</strong> </div></td>
                                    <td><input tabindex="13" maxlength="7" type="text" name="patient_zip"  onMouseOver="help_text(this, 'Enter zip code in 5 numeric character')" value="<!patient_zip>" /></td>
                                  </tr>
                                  <tr>
                                    <td><!newPasswordLabel></td>
                                    <td><!newPasswordField></td>
                                    <td>&nbsp;</td>
                                    <td><div align="right"  onMouseOver="help_text(this, 'Enter your first phone number')" ><strong>Phone 1:</strong> </div></td>
                                    <td><input tabindex="14" type="text" name="patient_phone1"  onMouseOver="help_text(this, 'Enter your first phone number')" value="<!patient_phone1>" /></td>
                                  </tr>
                                  <tr>
                                    <td><!confirmPasswordTextField></td>
                                    <td><!confirmPasswordField></td>
                                        
                                    <td>&nbsp;</td>
                                    <td><div align="right" onMouseOver="help_text(this, 'Enter your second phone number')"><strong>Phone 2:</strong> </div></td>
                                    <td><input tabindex="15" type="text" name="patient_phone2" onMouseOver="help_text(this, 'Enter your second phone number')" value="<!patient_phone2>" /></td>
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
<td><span style="display:<!corporate>">
<div  class="enable-con-inner" style="width: 187px; float: right; color: #0069A0; background: url(/images/bg-gray-heading.gif) top left repeat-x; border-top: #bbb solid 1px; border-bottom: #bbb solid 1px; padding: 2px 8px; margin: 0px 5px;<!SHOWEHS>"> <input name="ehsEnable" id="ehsEnable" style="margin:0px 5px 7px !important;position:relative;bottom:-2px;" value="1"  <!ehsEnable> type="checkbox" onclick="action_handler(this.checked, '<!patient_id>', '<!subs_status>', '<!subscrp_id>', '<!clinicId>','<!ehsAction>','<!paymentType>');"> <b><span id="eh" style="margin:0px 5px 7px !important;position:relative;bottom:1px;">E-Health Service</span></b>&nbsp;&nbsp;<img height="17" src="images/img-question.gif" width="19" name="By checking the box, this patient or client will be required to sign-up for your business' E-Health Service."   /><input  type="hidden" name="ehsDisable"  id="ehsDisable" value="<!ehsDisable>" />
</div></span>
</td>
                                    <td>&nbsp;</td>
                                    <td><div align="right" onMouseOver="help_text(this, 'Select your status')"><strong>Status :</strong> </div></td>
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
                                        <a href="javascript:void(0);" tabindex="17" onclick="GB_showCenter('Change Reminders', '/index.php?action=editReminder&patient_id=<!editReminderlink>', 550, 800 );"  ><strong>Change Reminders</strong></a><br/>
                                        <a tabindex="18" href="javascript:void(0);" onclick="GB_showCenter('Associate Providers', '/index.php?action=associateTherapist&patient_id=<!editReminderlink>&actionActivateFrom=accountAdmin', 550, 800 );"  ><strong>Edit Associated Providers</strong></a><br/>
                                        <a tabindex="19" href="javascript:void(0);" onclick="GB_showCenter('Login History', '/index.php?action=systemLoginHistory&patient_id=<!editReminderlink>', 400, 490 );"  ><strong>View Login History</strong></a> 
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
                                </table></td>
                            </tr>
                          </table>
					  </div>
                      <div style="float:right;">
						<input tabindex="19" type="submit" name="Submit" value=" <!buttonName> " />                      
                      </div></td>
                  </tr>
				  <!--  4th ROW ENDS  -->
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
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
  <input type="hidden" name="option" value="update" />
  <input type="hidden" name="patient_id" value="<!patient_id>" />  
  <input type="hidden" name="clinic_id" value="<!clinic_id>" />  
  </form>
</div><!-- div ( container ) Ends -->

<script>
<!--
	window.load = document.clinicdetail.patient_title.focus();
-->
</script>
