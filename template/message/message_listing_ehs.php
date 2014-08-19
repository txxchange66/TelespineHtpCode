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
		width:225px;	/* Width of box */
		height:150px;	/* Height of box */
		overflow:auto;	/* Scrolling features */
		border:1px solid #317082;	/* Dark green border */
		background-color:#FFF;	/* White background color */
		text-align:left;
		font-size:0.9em;
		z-index:100;
	}
	#ajax_listOfOptions div{	/* General rule for both .optionDiv and .optionDivSelected */
		margin:1px;		
		padding:1px;
		cursor:pointer;
		font-size:0.9em;
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
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2">
      <!-- Header div -->
      <div id="header">
          <!header>
      </div>
      <!-- End of Header div -->
      </td>
    </tr>
    <tr>
      <td width="16%" align="left" valign="top">
      
      <!-- Sidebar div -->
      <div id="sidebar">
          <!sidebar>
      </div>
      <!-- End of Sidebar div -->
      
      </td>
      <td width="84%"  align="left" valign="top">
      	<div id="mainContent"  >
			<table style="vertical-align:middle; width:700px; height:100px;" border="0">
				<tr>
					<td valign="middle" style=" width:400px; height:">
						<!--<div id="breadcrumbNav" style="vertical-align:middle;  "> 
							<a href="index.php?action=therapist">HOME</a> / <span class="highlight"  >MESSAGE CENTER</span>
						</div>
					--></td>
					<td>
						<div id="page_nav" style="text-align:right;">
							<table cellpadding="0" cellspacing="0" border="0">
								<tr><td></td><td></td><td></td><td></td></tr>
							</table>
						</div>
					<td style="padding-left:176px; margin-top:11px;">
						<a href="index.php?action=compose_message_ehs&from_record=1" ><img src="images/img-compose-message.gif" alt=""  /></a>
					</td>
				</tr>
			</table>
			<table border="0" cellpadding="00" cellspacing="0"   width="100%">
				<tr><td><span style="color:#54A113;font-weight:bold;padding:0px;margin:0px;"><!mass_message_message></span></td></tr>
                <tr>
					<td  class="largeH1" style="valign:middle;padding-left:13px;">EHS Message Center	</td>
				</tr>
				<!--<tr>
	  				<td  style="valign:middle;">
	  					<table width="100%" border="0" style="height:47px;" cellspacing="0" cellpadding="0">
        					<tr>
          						<td>
          							<form method="GET" name="frm" action="index.php" AUTOCOMPLETE="OFF">	
          							<table border="0" cellpadding="0" cellspacing="0"  width="100%">
            							<tr>
              								<td colspan="3" class="showmessage" style="valign:middle; padding-left:12px; height:48px;"> Show Messages From&nbsp;&nbsp;&nbsp;
              								<input type="text" id="patient_name" name="patient_name" size="20" value="<!patient_name>" onkeyup="ajax_showOptions(this,'getCountriesByLetters',event)">&nbsp;                  
											<input type="submit"  name="sub1" value="Search" onclick="javascript:document.frm.submit();" >&nbsp;&nbsp;
											<input type="hidden"  name="sub" value=" Search ">
                  							<input type="button"  name="sub" value="View All" onclick="javascript:window.location='index.php?action=message_listing';" >
                  				            </td>
              								<td width="1%" align="right"></td>
            							</tr>
					    		   </table>
					    		   <input type="hidden" id="patient_name_hidden" name="patient_name_id" value="<!patient_name_id>" >
								   <input type="hidden"  name="action" value="message_listing" >
					    		   </form>
					    		</td>
        					</tr>
      					</table>
      				</td>
				</tr>
			--></table>
			<table border="0" cellpadding="2" cellspacing="1" width="100%" class="list"  >
      			<!message_list_head>
	  			<!message_list_record> 
			</table>
			<div class="paging">
			<span onMouseOver="help_text(this, 'Select a page number to go to that page')">
				<!link>
			</span>	
			</div>

	<!-- [/items] -->
	<div align="center" style="padding:5px;">
	  </div>
	<!-- [/list] -->
		
	</div>
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

</div><!-- div ( container ) Ends -->
<script src="js/jquery.js"></script>  
<script language="javascript" type="text/javascript">
      $(document).ready(function() {
        switchMenu('eMaintenance',document.getElementById('switch'));
      });
</script>
