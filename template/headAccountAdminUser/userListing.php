<input type="hidden"  name="showsoap" id="showsoap" value="<!shopshow>">
<script language="JavaScript" src="js/show_menu_head.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>


<script type="text/javascript">
<!--

function showConfirmBox(id,therapist_access,patient_association,clinic_id)
{
	var c = false;		
	
	if (therapist_access == 1 && patient_association == 1)
	{
		if(!csw) 
		{
			var csw = window.open('index.php?action=popupConfirmRemoveHead&clinic_id=' + clinic_id + '&id='+id, 'removeConfirmWindow', 'width=600, height=470, status=no, toolbar=no, resizable=no, scrollbars=no');
			csw.focus();	
		}	
	}
	else
	{
		if(!csw) 
		{
			var csw = window.open('index.php?action=popupConfirmRemoveHead&clinic_id=' + clinic_id + '&id='+id, 'removeConfirmWindow', 'width=600, height=310, status=no, toolbar=no, resizable=no, scrollbars=no');
			csw.focus();	
		}
	}
	
}
function action_handler(val,clinic_id,user_id,therapist_access,patient_association){
    var action = val.value;
    if( action == '' && clinic_id == null && user_id == null ){
        return;
    }
    switch (action)
    {
        case "editUserHead":
            url = 'index.php?action=editUserHead&id=' + user_id + '&clinic_id=' + clinic_id;
            window.location = url;
            break
        case "showConfirmBox":
                showConfirmBox(user_id,therapist_access,patient_association,clinic_id);
            break
        case "activeUserHead":
            url = 'index.php?action=activeUserHead&id=' + user_id;
            window.location = url;
            break
        case "inactiveUserHead":
            url = 'index.php?action=inactiveUserHead&id=' + user_id;
            window.location = url;
            break
        default:
            url = "";
    }
    val.selectedIndex = 0;
}
//-->
</script>


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
	  <div id="mainContent-sec">
	  <!--  MAIN TABLE STARTS -->
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top" align="left">
			  <!--  INNER MAIN TABLE STARTS (5) ROWS STARTS  -->
			  <table width="100%" border="0" style="vertical-align:middle; width:700px;margin-top:36px;margin-bottom:28px;">
                  <tr>
                    <td>
                    <table style="vertical-align:middle;width:700px;">
                        <tr>
                          <td >
                            <div id="breadcrumbNav" style="padding-top:1px;">
                                <a href="index.php" >HOME </a> / <a href="index.php?action=accountAdminClinicList" >ACCOUNT</a> / <span class="highlight">PROVIDER LIST</span>
                            </div>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
              </table>
            </td>
          </tr>
          <tr>
             <td >
             <table border="0" width="100%"  >
                <tr >
                    <td align="left" >
                    <form method="post" action="<!userLocation>" name="filter" onsubmit="return search_key(document.filter.search.value);" >
                        <table>
                            <tr>
                            <td><input size="20" maxlength="250" name="search" value="" type="text"></td>
                            <td>
                                <input size="20" name="sub" value=" Search " type="submit">
                                <input size="20" name="restore_search" value="" type="hidden">
								<!--<span style="padding-left: 10px;"><!error></span>-->
                             </td>   
                            </tr>    
                        </table>    
                    </form>
                    </td>
                    <td align="right">
                        <b><!totTherapist></b> Active Provider Subscription(s) 
                    </td>
             </tr>
             </table>
             </td>
          </tr>
				  <!--  3rd ROW ENDS  -->				  
                  <tr>
                    <td>
                    
					<div style="padding-top:1px;">
                         <!tabNavigation><div style="padding-top: 7px; ">
                    <!error></div>
                      </div>
                    </td>
                   </tr>
                   <tr>
                        <td>  
                        <div >
						  <table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" >
                            <!userTblHead>
                            <!userTblRecord>
                          </table>
                          <div class="paging"> <span >
                            <!link>
                            </span> </div>
						</div>
                      <div >
                           <!--<input type="submit" name="Submit" value="Add New User" />
                          <input type="hidden" name="action" value="addUserSystem"/>
                          <input type="hidden" name="clinic_id" value="<!clinic_id>"/> -->
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

</div><!-- div ( container ) Ends -->
<script type="text/javascript">
    mmLoadMenus("<!showmenu>");
</script>t>