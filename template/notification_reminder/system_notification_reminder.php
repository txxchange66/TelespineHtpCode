<script language=JavaScript>
<!--
function check_length(my_form)
{
maxLen = 750; // max number of characters allowed
if (my_form.message.value.length > maxLen) {
// Alert message if maximum limit is reached.
// If required Alert can be removed.
var msg = "You have reached your maximum limit of characters allowed";
alert(msg);
// Reached the Maximum length so trim the textarea
my_form.message.value = my_form.message.value.substring(0, maxLen);
t = document.getElementById('maxlimit');
t.innerHTML = (maxLen - my_form.message.value.length)+' Characters Left';
}
else{ // Maximum length not reached so update the value of my_text counter
t = document.getElementById('maxlimit');
t.innerHTML = (maxLen - my_form.message.value.length)+' Characters Left';}
}
function checkLength()
{
maxLen = 750; // max number of characters allowed
if (document.my_form.message.value.length > maxLen) {
var msg = "You have reached your maximum limit of characters allowed";
alert(msg);
return false;
}
}
//-->
</script>

<div id="container">
<!-- <form action="index.php"  method="POST"  name="my_form" >-->
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
                    <td ><table width="100%" border="0" cellspacing="0" cellpadding="0" style="vertical-align:middle; width:100%; height:81px;">
                      <tr>
                       <td colspan="3" style=" width:400px;">
                            <div id="breadcrumbNav" style="margin-top:18px;">
                                    <a href="index.php?action=therapist">HOME</a>
                                    / 
                                    <span class="highlight">NOTIFICATIONS</span>
                                    </div></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </table></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                      <td><!error></td>
                  </tr>
                  <tr>
                    <td>
                        <div align="left" class="largeH1" style="padding-left:13px;">Notification Interval for Non-Compliant Patients</div>

                        <div >
                           <table border="0" cellpadding="0" cellspacing="0" style="margin-top:20px;"width="100%"  >
                            <tr>
                                <td width="15%">&nbsp;</td>
                                <td>
                                <form action="index.php" method="POST" name="frm" id="frm"  >
                                    <table border="0" cellpadding="1" cellspacing="1" width="100%" >
                                        <tr>
                                            <td width="20%">To Patient</td>
                                            <td width="80%">
                                                <select name="inter_patient" id="inter_patient" style="width:150px;" >
                                                    <option value="" >Please select...</option>
                                                    <option value="1">1 Week</option>
                                                    <option value="2">2 Weeks</option>
                                                    <option value="3">3 Weeks</option>
                                                    <option value="4">4 Weeks</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td >To Provider</td>
                                            <td >
                                                <select name="inter_therapist" id="inter_therapist" style="width:150px;" >
                                                    <option value="" >Please select...</option>
                                                    <option value="1">1 Week</option>
                                                    <option value="2">2 Weeks</option>
                                                    <option value="3">3 Weeks</option>
                                                    <option value="4">4 Weeks</option>
                                                </select>
												<span style="padding-left:180px;"><input type="submit"  style=" width:120px; margin-left:5px; margin-top:5px;" value="Save"></span>
                                            </td>
                                        </tr>
                                        <!--<tr>
                                            <td colspan="2" style="padding-left:30px;" ><input type="submit"  style=" width:120px; margin-left:5px; margin-top:5px;" value="Save"></td>
                                        </tr>-->
                                    </table>
                                    <input type="hidden" name="action" value="system_notifications" />
                                    <input type="hidden" name="action_submit" value="submit" />
                                </form>
                                </td>
                                <td align="right">&nbsp;  </td>
                            </tr>
                            </table>
                      </div>                     </td>
                  </tr>
                  <!--  4th ROW ENDS  -->

				  <tr>
                      <td>&nbsp;</td>
                  </tr>
				  <tr>
                      <td><!error1></td>
                  </tr>
                  <tr>
                    <td>
                        <div align="left" class="largeH1" style="padding-left:13px;">Provider & Patient Notifications</div>

                        <div >
                           <table border="0" cellpadding="0" cellspacing="0" style="margin-top:20px;"width="100%"  >
                            <tr>
                                <td width="15%">&nbsp;</td>
                                <td>
                                <form action="index.php" method="POST" name="frmnotifyusers" id="frmnotifyusers"  >
                                    <table border="0" cellpadding="1" cellspacing="1" width="100%" >
                                        <tr>
                                            <td width="20%">Choose User Group</td>
                                            <td width="80%">
                                                <select name="user_type_id" id="user_type_id" style="width:150px;" >
                                                    <option value="2">Providers</option>
                                                    <option value="1">Patients</option>
                                                    <option value="1,2">All</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Message</td>
                                            <td>&nbsp;</td>
                                        </tr>
										</table>
										 <table border="0" cellpadding="1" cellspacing="1" width="70%" >
										<tr>
                                            <td colspan="2"><textarea onKeyup="check_length(this.form);" name="message" style="width:580px;height:180px;"></textarea></td>
                                            
                                        </tr>
										<tr>
                                            <td>
												<table width="100%" border="0" cellspacing="0" cellpadding="0">
												   <tr>
													 <td >
													 <div id="maxlimit">750 Characters Left</div>
													   Posts must be less than 750 characters.</td>
												   </tr>
												 </table>

											</td>
											<td align="right" >
											<input type="submit"  style=" width:120px; margin-left:5px; margin-top:5px;" value="Send" onClick="return confirm('Are you sure to post this notification.');">
											</td>
                                        </tr>
                                        
                                    </table>
                                    <input type="hidden" name="action" value="system_notifications" />
                                    <input type="hidden" name="action_submit" value="message_submit" />
                                </form>
                                </td>
                                <td align="right">&nbsp;  </td>
                            </tr>
                            </table>
                      </div>                     </td>
                  </tr>
                  <!--  6th ROW ENDS  -->
                  
                  <!--  7th ROW ENDS  -->
                </table>
      </div>
           <div style="clear:both;"><br></div>
          <!-- body part ends-->
                <!--  INNER MAIN TABLE STARTS (5) ROWS ENDS -->                </td>
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
  <!--</form>-->
</div><!-- div ( container ) Ends -->
<script src="js/jquery.js"></script>  
<script language="javascript" type="text/javascript">
      $(document).ready(function() {
        $("select[@name='inter_patient'] option[@value='<!inter_patient>']").attr("selected","selected");
        $("select[@name='inter_therapist'] option[@value='<!inter_therapist>']").attr("selected","selected");
      });
      
      $('#frm').submit(function() {
            if( Number($('#inter_therapist').val()) <= Number($('#inter_patient').val()) ){
                alert('Provider interval must be greater than Patient interval.');
                return false;
            }else if($('#inter_patient').val() == "" || $('#inter_therapist').val()==""){
				alert('Please select the Patient interval.');
                return false;
			}
        }
      );
</script>