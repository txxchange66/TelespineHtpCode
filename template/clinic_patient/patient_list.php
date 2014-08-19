<script type="text/JavaScript">
    var GB_ROOT_DIR = "js/greybox/";
</script>
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" /> 

<script type="text/javascript">
<!--    
function action_handler(val,clinic_id,patient_id){
    var action = val.value;
    if( action == '' && clinic_id == null && patient_id == null ){
        return;
    }
    switch (action)
    {
    case "SystemAdminEditPatients":
        url = 'index.php?action=SystemAdminEditPatients&patient_id=' + patient_id + '&clinic_id=' + clinic_id;
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
        <div id="header">
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
        <div id="mainContent">
        <!--  MAIN TABLE STARTS -->
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top" align="left">
                <!--  INNER MAIN TABLE STARTS (5) ROWS STARTS  -->
                <table width="100%" border="0" style="vertical-align:middle;width:700px;margin-top:35px;">
                  <tr>
                    <td>
                        <table style="vertical-align:middle;width:700px;">
                            <tr>
                                <td >
                                    <div id="breadcrumbNav"><a href="index.php?action=sysAdmin" >HOME </a> / <a href="index.php?action=listClinic" >ACCOUNT</a> / <span class="highlight"><!account_name></span> / <span class="highlight">PATIENTS LIST</span></div>
                                </td>
                            </tr>
                        </table>
                    </td>
                  </tr>
                  
                  </table>
                </td>
              </tr>
              <tr>
              <td>
                    <table border='0' width='100%' style='margin-top:30px;'>
                        <tr>
                            <td  align="left"; style="left-margin:0px;" >
                            <form method="post" action="<!patientLocation>" name="filter" onSubmit="return search_key(document.filter.search.value);" >
                                <table >
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
                    <div class="error" ><!error></div>
                    <div >
                        <!tabNavigation>
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
