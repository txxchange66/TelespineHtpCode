<script type="text/javascript">
<!--
function showConfirmBox(id,therapist_access,patient_association)
{
	var c = false;		
	
	if (therapist_access == 1 && patient_association == 1)
	{
		if(!csw) 
		{
			var csw = window.open('index.php?action=popupConfirmRemove&id='+id, 'removeConfirmWindow', 'width=600, height=470, status=no, toolbar=no, resizable=no, scrollbars=no');
			csw.focus();	
		}	
	}
	else
	{
		if(!csw) 
		{
			var csw = window.open('index.php?action=popupConfirmRemove&id='+id, 'removeConfirmWindow', 'width=600, height=310, status=no, toolbar=no, resizable=no, scrollbars=no');
			csw.focus();	
		}
	}
	
}
//-->
</script>
<input type="hidden"  name="showsoap" id="showsoap" value="<!shopshow>">
<script language="JavaScript" src="js/show_menu.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>

<div id="container">
 <form action="index.php" enctype="multipart/form-data" method="GET">
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
  				<td colspan="5" width="741px;">
				<div id="breadcrumbNav" style="margin-top:40px;float:left;width:550px;">
					<a href="index.php">HOME</a>
					/ 
					<a href="index.php?action=userListing">ACCOUNT ADMINISTRATOR</a>
					/ 
					<span class="highlight"  >
					PROVIDER
					</span>
				</div>
                <div style"float:right;" ><a href="index.php?action=addUser"><img src="images/createNewSubscriber.gif" alt=""  /></a>  </div>     
				</td>
			</tr>
  			<tr>
    			<td colspan="5" valign="top" style=" width:741px;font-size: large;font-weight: bold;padding-bottom: 10px; margin:0px; vertical-align:top;">
    				<br/><br/>
    		    </td>
    		</tr>
  			<tr>
    			<td colspan="5" valign="top"  >
				   <div style="padding-top:0px;width:741px;float:left;">
    					<!navigationTab>
                        <div class="textblue" style="float:right;margin:0px;padding:0px;margin-top:7px;" ><!totTherapist> Provider Subscription(s)</div>
    			  </div>
    		    </td>
    		</tr>
		</table>
		<table border="0"  margin="0" cellpadding="2" cellspacing="1" width="100%" class="list" >
			<!userTblHead>
			<!userTblRecord>
		</table>
		<div class="paging">
				<span onMouseOver="help_text(this, 'Select a page number to go to that page')">
				<!link>
				</span>
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
  </form>
</div><!-- div ( container ) Ends -->
<script language="JavaScript1.2">mmLoadMenus();</script>