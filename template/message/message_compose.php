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
<style type="text/css">
	
	/* START CSS NEEDED ONLY IN DEMO */

	#mainContainer{
		width:660px;
		margin:0 auto;
		text-align:left;
		height:100%;
		background-color:#FFF;
		border-left:3px double #000;
		border-right:3px double #000;
	}
	#formContent{
		padding:5px;
	}
	/* END CSS ONLY NEEDED IN DEMO */
	
	
	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:294px;	/* Width of box */
		height:150px;	/* Height of box */
		overflow:auto;	/* Scrolling features */
		border:1px solid #317082;	/* Dark green border */
		background-color:#FFF;	/* White background color */
		text-align:left;
		font-size:1.05em;
		z-index:100;
	}
	#ajax_listOfOptions div{	/* General rule for both .optionDiv and .optionDivSelected */
		margin:1px;		
		padding:1px;
		cursor:pointer;
		font-size:1.05em;
	}
	#ajax_listOfOptions .optionDiv{	/* Div for each item in list */
		
	}
	#ajax_listOfOptions .optionDivSelected{ /* Selected item in the list */
		background-color:#317082;
		color:#FFF;
	}
	#ajax_listOfOptions_iframe{
		background-color:#F00;
		position:absolute;
		z-index:5;
	}
	
	form{
		display:inline;
	}
	
	</style>
	<script type="text/javascript" src="js/auto_suggest/ajax.js"></script>
	<script type="text/javascript" src="js/auto_suggest/ajax-dynamic-list.js">
	/************************************************************************************************************
	(C) www.dhtmlgoodies.com, April 2006
	
	This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.	
	
	Terms of use:
	You are free to use this script as long as the copyright message is kept intact. However, you may not
	redistribute, sell or repost it without our permission.
	
	Thank you!
	
	www.dhtmlgoodies.com
	Alf Magne Kalleland
	
	************************************************************************************************************/	
</script>
<div id="container">
 <form action="index.php"  method="POST"  name="my_form" onSubmit="return checkLength();" AUTOCOMPLETE="off">
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
									  <a href="index.php?action=message_listing">MESSAGE CENTER</a>
									/ 
									<span class="highlight">COMPOSE MESSAGE</span></div></td>
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
					    <div align="left" class="largeH1" style="padding-left:13px;">
                        			Message Center
                        	
                        </div>

                        <div >
						   <!--<table border="0" cellpadding="2" cellspacing="1" width="100%"  onMouseOver="help_text(this, 'Displays the Users, click on one to edit or remove it.  To sort this list click on the column header you would like to sort by')">-->
						   <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" ><tr><td width="90%"  ><table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" >
                             <tr>
                               <td align="right" width="9%" style="padding-top:3px;"><div class="sub_header" >TO</div></td>
                               <td width="91%"  style="padding-top:3px;" ><table width="100%" cellpadding="0" cellspacing="0">
                                   <tr>
                                     <td class="messageInfo">
                                     <input onMouseOver="help_text(this, 'Enter the Provider\'s associated patient name')" type="text" id="patient_name" style="width:665px;" name="patient_name" size="800" value="<!patient_name>" onKeyUp="ajax_showOptions(this,'getCountriesByLetters',event)">
									<input type="hidden" id="patient_name_hidden" name="patient_name_id" value="<!patient_name_id>" >
                                     </td>
                                   </tr>
                               </table></td>
                             </tr>
                             <tr>
                               <td align="right" ><div class="sub_header" >SUBJECT</div></td>
                               <td class="messageInfo">
                               	<input onMouseOver="help_text(this, 'Enter the subject of the message')" type="text" name="subject" size="38" style="width:665px;" value="<!subject>" maxlength="250"/>
                               </td>
                             </tr>
                             <tr>
                               <td colspan="2" style="background-color:#ffffff; "></td>
                             </tr>


                             <tr>
                               <td colspan="2">
                                   
								  <textarea onMouseOver="help_text(this, 'Please enter the message here')" onKeyup="check_length(this.form);" name="content" rows="10" cols="" style="width:736px;"><!content></textarea>
                               </td>
                             </tr>
                             <tr>
                               <td colspan="2" style="padding-top:3px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                   <tr>
                                     <td width="37%">
                                     <div id="maxlimit">750 Characters Left</div>
                                       Posts must be less than 750 characters.</td>
                                     <td width="63%" align="right">
                                     	<input type="submit" name="Submit" id="button" value="Submit Message" />
                          				<input type="hidden" name="action" value="compose_message"/>
                          				<input type="hidden" name="action_submit" value="submit"/>
                                        <input type="hidden" name="from_record" value="<!from_record>" />
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
      
      	<!-- body part ends-->
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
