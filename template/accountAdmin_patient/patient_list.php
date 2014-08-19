<input type="hidden"  name="showsoap" id="showsoap" value="<!shopshow>">
<script language="JavaScript" src="js/show_menu.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>


<div id="container">
 
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
				<div id="breadcrumbNav" style="margin-top:40px;float:left;width:550px;" >
					<a href="index.php">HOME</a>
					/ 
					<a href="index.php?action=userListing">ACCOUNT ADMINISTRATOR</a>
					/ 
					<span class="highlight"  >
					PATIENT
					</span>
				</div>
                <div style"float:right;" >
                    <a href="index.php?action=accountAdminEditPatients">
                        <img src="images/createNewPatient.gif" alt="" />
                    </a>
                </div>
				</td>
			</tr>
  			<tr>
    			<td colspan="5" valign="top" style="width:741px;padding-bottom:5px; margin:0px; valign:middle;">
    				
    				<form method="post" action="index.php?action=accountadmin_patient" name="filter" onsubmit="return search_key(document.filter.search.value);" onmouseover="help_text(this, 'Please enter text to restrict the list to only items that contain the text entered')">
    				<table>
    				<tr>
    					<td><label for="search"   >Search:</label></td>
    					<td><input size="20" maxlength="250" name="search" value="" type="text"></td>
    					<td><input size="20" name="sub" value=" Search " type="submit"></td>
    					<input size="20" name="restore_search" value="" type="hidden">
    				</tr>	
    				</table>	
					</form>
				
    		    </td>
    		</tr>
  			<tr>
    			<td colspan="5" valign="top" align="left" >
				   <div style="padding-top:6px;width:741px;float:left;">
                        <!navigationTab>
    			  </div>
    			  
    		    </td>
    		</tr>
		</table>
		
		<table border="0"  margin="0" cellpadding="2" cellspacing="1" width="100%" class="list"  >
			<!patientAcctListHead> 	
			<!rowdata>
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

</div><!-- div ( container ) Ends -->
<script type="text/javascript">
    mmLoadMenus("<!showmenu>");
</script>