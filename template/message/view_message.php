<script language=JavaScript>
<!--
function check_length(my_form)
{
maxLen = 750; // max number of characters allowed

if (my_form.content.value.length > maxLen) {
// Alert message if maximum limit is reached.
// If required Alert can be removed.
var msg = "You have reached your maximum limit of characters allowed";
alert(msg);
// Reached the Maximum length so trim the textarea
my_form.content.value = my_form.content.value.substring(0, maxLen);
t = document.getElementById('maxlimit');
t.innerHTML = (maxLen - my_form.content.value.length)+' Characters Left';
}
else{ // Maximum length not reached so update the value of my_text counter
t = document.getElementById('maxlimit');
t.innerHTML = (maxLen - my_form.content.value.length)+' Characters Left';}
}
function checkLength()
{
maxLen = 750; // max number of characters allowed
if (document.my_form.content.value.length > maxLen) {
var msg = "You have reached your maximum limit of characters allowed";
alert(msg);
return false;
}
}
//-->
</script>
<div id="container">
 <form action="index.php"  method="POST"  name="my_form" onsubmit="return checkLength();">
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
                    <td ><table width="100%" border="0" cellspacing="0" cellpadding="0" style="vertical-align:middle; width:100%; height:81px;">
                      <tr>
                       <td colspan="3" style=" width:400px;">
							<div id="breadcrumbNav" style="margin-top:18px;">
							<!--<a href="index.php?action=therapist">HOME</a> / <a href="index.php?action=message_listing">MESSAGE CENTER</a>/ <span class="highlight"  >VIEW MESSAGE</span>-->
                                                            <a href="index.php?action=therapist">HOME</a> /<!blink>/ <span class="highlight"  >VIEW MESSAGE</span>
                                                            
							</div>
						</td>
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
                    <div  align="left" class="largeH1" style="padding-left:13px;">
                        				Message Center    				
                        	
                   </div>
                   
                   <div style="padding-top:8px;">
						   <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" >
                           <!main_message>
						   <tr><td colspan="2" style="background-color:#ffffff; height:13px;"></td></tr>
						   <tr>
						   	<td class="sub_header_red_new" ><div  >REPLIES</div></td>
						   	<td class="sub_header_red_new" >&nbsp;</td>
						   </tr>
						   <tr>
						   	<td colspan="2" style="height:11px;"></td>
						   </tr>
						   <!reply_message>
                            <tr>                            	
                           	  <td colspan="2" style="padding-top:9px;" >
                            		<div class="sub_header" >POST REPLY</div>
                   		            <textarea onmouseover="help_text(this, 'Enter here the reply of the post')" onkeyup="check_length(this.form);" name="content" rows="10" cols="" style="width:99.6%; "></textarea></td>
                            </tr>
                            <tr>
                            	<td colspan="2" style="padding-top:3px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="37%">
                                    <div id="maxlimit" >750 Characters Left</div>
                                    Posts must be less than 750 characters.</td>
                                    <td width="63%" align="right">
                                    	<input type="submit" name="Submit" id="button" value="Submit Reply" <!allows> />
                           				<input type="hidden" name="action" value="view_message" />
                           				<input type="hidden" name="message_id" value="<!message_id>" />
                          				<input type="hidden" name="submit_action" value="reply_message"/>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td width="37%">
                                    </td>
                                    <td width="63%" align="right">
                                    <!message>
                                    </td>
                                  </tr>
                                </table></td>
                               </tr>
                          </table>
						</div>                     </td>
                  </tr>
				  <!--  4th ROW ENDS  -->
                  
				  <!--  5th ROW ENDS  -->
                </table>
      </div>
				<!--  INNER MAIN TABLE STARTS (5) ROWS ENDS -->				</td>
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
  </form>
</div><!-- div ( container ) Ends -->
