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
 <form action="index.php"  method="POST" name="my_form" onsubmit="return checkLength();">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2">
      <div id="header">
		<ul id="mainNav">
		<!-- <li class="navBtn"><a href="?action=logout" id="nav_item18" >LOGOUT</a></li>
		 <li class="navBtn"><a href="index.php?action=patient" id="nav_item35"  class="selected">PATIENT&nbsp;HOME</a></li>
		<li class="navBtn"><a href="?action=logout" id="nav_item18" >LOGOUT</a></li>
		<li class="navBtn"><a href="?action=comingSoon" id="nav_item37" >ABOUT&nbsp;US</a></li>
		<li class="navBtn"><a href="?action=comingSoon" id="nav_item34" >CONTACT&nbsp;US</a></li> -->
		</ul>
		</ul>
		<!header>
	</div></td>
    </tr>
    <tr>
      <td width="16%" align="left" valign="top">
      	<div id="sidebar">
          <!sidebar>
        </div>
       </td>
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
                       <td colspan="3" style=" width:450px;">
							<div id="breadcrumbNav" style="font-size:12px;">
                          			<a class="breadcrumbNavUnderline" href="index.php?action=patient">HOME</a>
                          			 / <a href="index.php?action=patient_message_listing" ><!headingMessages> CENTER</a>
                          			 /<span class="highlight"> COMPOSE MESSAGE</span></div></td>
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
					    <div align="left" class="largeH6" style="padding-left:13px;font-size:13px;letter-spacing: 1px;" >
                        			<!headingMessages> CENTER
                        	
                        </div>

                        <div >
						   <!--<table border="0" cellpadding="2" cellspacing="1" width="100%"  onMouseOver="help_text(this, 'Displays the Users, click on one to edit or remove it.  To sort this list click on the column header you would like to sort by')">-->
						   <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" ><tr><td width="90%"  ><table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" >
                             <tr>
                               <td align="right" width="9%" style="padding-top:3px;"><div class="sub_header_green_new" style="font-size:13px;" >TO</div></td>
                               <td width="91%"  style="padding-top:3px;" ><table width="100%" cellpadding="0" cellspacing="0">
                                   <tr>
                                     <td class="messageInfo" style="font-size:13px;" >
                                     <!therapist_name><input type="hidden" name="to" value="<!therapist_name>"/>
                                     </td>
                                   </tr>
                               </table></td>
                             </tr>
                             <tr>
                               <td align="right" ><div class="sub_header_green_new" style="font-size:13px;" >SUBJECT</div></td>
                               <td class="messageInfo">
                               <!-- width change to 663 px by MOHIT SHARMA -->
                               	<input style="width:663px; " type="text" name="subject" size="38" value="<!subject>" maxlength="250"/>
                               </td>
                             </tr>
                             <tr>
                               <td colspan="2" style="background-color:#ffffff; "></td>
                             </tr>


                             <tr>
                               <td colspan="2" >
                               <!-- width changed to 735 px by MOHIT SHARMA -->
                                   <textarea style="font-size:13px;width:735px;" name="content" class="textarea" rows="10" cols="" onKeyup="check_length(this.form);" ><!content></textarea>
                               </td>
                             </tr>
                             <tr>
                               <td colspan="2" style="padding-top:3px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                   <tr>
                                     <td width="37%">
                                     <div id="maxlimit" style="font-size:13px;" >750 Characters Left</div>
                                       Posts must be less than 750 characters.</td>
                                     <td width="63%" align="right">
                                     	  <input type="submit" name="Submit" value="Submit Message" />
				                          <input type="hidden" name="action" value="patient_compose_message"/>
				                          <input type="hidden" name="action_submit" value="submit"/>
                                     </td>
                                   </tr>
                               </table></td>
                             </tr>
                           </table>						     <table width="100%"><tr></tr>
						   </table>                            	</td>
                            </tr>
					      </table>
					  </div>                     </td>
                  </tr>
				  <!--  4th ROW ENDS  -->
                  
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
  </form>
</div><!-- div ( container ) Ends -->
