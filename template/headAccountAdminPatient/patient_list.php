<input type="hidden"  name="showsoap" id="showsoap" value="<!shopshow>">
<script language="JavaScript" src="js/show_menu_head.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>

<script type="text/javascript">
<!--    
function action_handler(val,clinic_id,patient_id){
    var action = val.value;
    if( action == '' && clinic_id == null && patient_id == null ){
        return;
    }
    switch (action)
    {
    case "HeadAdminEditPatients":
        url = 'index.php?action=HeadAdminEditPatients&patient_id=' + patient_id + '&clinic_id=' + clinic_id;
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
      <td colspan="2">
        <div id="header" >
          <!header>
        </div>
      </td>
    </tr>
    <tr>
      <td width="16%" align="left" valign="top">
        <div id="sidebar">
          <!sidebar>
        </div>
      </td>
      <td width="84%"  align="left" valign="top">
        <div id="mainContent-sec">
	    <!--  MAIN TABLE STARTS -->
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top" align="left">
			    <!--  INNER MAIN TABLE STARTS (5) ROWS STARTS  -->
			    <table width="100%" border="0" style="vertical-align:middle;">
                  <tr>
                    <td>
                        <table style="vertical-align:middle;margin-top:39px;margin-bottom:30px;">
                            <tr>
                                <td >
                                    <div id="breadcrumbNav" style="margin-top:-2px;"><a href="index.php" >HOME </a> / <a href="index.php?action=accountAdminClinicList" >ACCOUNT</a> / <span class="highlight">PATIENT LIST</span></div>
                                </td>
                            </tr>
                        </table>
                    </td>
                  </tr>
                  <tr>
                        <td  align="left"; style="left-margin:0px;" >
                            <form method="post" action="<!patientLocation>" name="filter" onsubmit="return search_key(document.filter.search.value);" >
                                <table>
                                        <tr>
                                        <td><input size="20" maxlength="250" name="search" value="" type="text"></td>
                                        <td>
                                            <input size="20" name="sub" value=" Search " type="submit">
                                            <input size="20" name="restore_search" value="" type="hidden">
											
                                         </td>   
                                        </tr>    
                                </table>    
                            </form>
                        </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                    <td>
                    <!--<div class="error" style="padding-left: 5px;" ><!error></div>-->
					<div style="padding-top:1px;">
                        <!tabNavigation><div class="error" style="padding-top: 7px;" ><!error></div>
                    </div>
                    </td>
                   </tr>
                   <tr>
                    <td>
                        <div >
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td colspan="2">
                                    <table width="100%" border="0" cellspacing="1" cellpadding="1" class="list">
                                        <!patientAcctListHead>
                                        <!rowdata>
                                      </table>
                                      <table >
                                        <tr>
                                          <td><!link>
                                          </td>
                                        </tr>
                                      </table></td>
                                  </tr>
                                </table></td>
                            </tr>
                          </table></div>
                      <div >
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
</script>