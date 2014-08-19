<script type="text/JavaScript">
     var GB_ROOT_DIR = "js/greybox/";
</script>
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />
    
<script type="text/javascript">
<!--

function showConfirmBox(id,therapist_access,patient_association)
{
	var c = false;		
	
	if (therapist_access == 1 && patient_association == 1)
	{
		if(!csw) 
		{
			var csw = window.open('index.php?action=popupConfirmRemoveSystem&clinic_id=<!clinic_id>&id='+id, 'removeConfirmWindow', 'width=600, height=470, status=no, toolbar=no, resizable=no, scrollbars=no');
			csw.focus();	
		}	
	}
	else
	{
		if(!csw) 
		{
			var csw = window.open('index.php?action=popupConfirmRemoveSystem&clinic_id=<!clinic_id>&id='+id, 'removeConfirmWindow', 'width=600, height=310, status=no, toolbar=no, resizable=no, scrollbars=no');
			csw.focus();	
		}
	}
	
}

function action_handler(val,clinic_id){
    var action = val.value;
    if( action == '' && clinic_id == null ){
        return;
    }
    switch (action)
    {
    case "addEditClinicInAccount":
        url = 'index.php?action=addEditClinicInAccount&subaction=edit&clinic_id=' + clinic_id;
        window.location = url;
        break
    case "confirmStatusChangeClinic":
        url = '/index.php?action=confirmStatusChangeClinic&clinic_id=' + clinic_id;
        GB_showCenter('Change Status of Clinic', url, 210,500 );
        break
    case "addUserSystem":
        url = 'index.php?action=addUserSystem&clinic_id=' + clinic_id;
        window.location = url;
        break
    case "SystemAdminEditPatients":
        url = 'index.php?action=SystemAdminEditPatients&clinic_id=' + clinic_id;
        window.location = url;  
        break
    case "trialStatusChangeClinic":
        url = '/index.php?action=trialStatusChangeClinic&clinic_id=' + clinic_id;
        GB_showCenter('Change  of Clinic', url, 210,500 );
        //window.location = url;
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
		          <table width="100%" border="0" style="vertical-align:middle; width:700px; ">
                      <tr>
                        <td>
                                <table style="vertical-align:middle;width:700px;" >
                                    <tr>
                                        <td style="width:400px;">
                                        <div id="breadcrumbNav">
                                            <a href="index.php?action=sysAdmin" >HOME </a> / <a href="index.php?action=listClinic" >ACCOUNT</a> / <span class="highlight"><!account_name></span> / <span class="highlight">CLINIC LIST</span>
                                        </div>
                                        </td>
                                        <td style="width:300px;">
                                            <table border="0" cellpadding="5" cellspacing="0" style="float:right;">
                                            <tr>
                                                <td class="iconLabel" height="89px;" ><!--<a href="index.php?action=addEditClinicInAccount&subaction=add&clinic_id=<!clinic_id>"><img src='images/img-create-new-account-clinic.gif' ></a>--></td>
                                            </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align"left"; style="left-margin:0px;" >
                                        <form method="post" action="<!location>" name="filter" onsubmit="return search_key(document.filter.search.value);" >
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
                  </table>
              </td>
            </tr>
            <tr>
                <td>
                    <!error>
					<div >
                         <!tabNavigation>
                    </div>
                 </td>
            </tr>
            <tr>
                <td>
                    <div>
                        <table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" >
                            <!clinicTblHead>
                            <!clinicTblRecord>
                        </table>
                    </div>
                    <div class="paging">
                        <span >
                            <!link>
                        </span>
                    </div>
						    
                    <!--<div id="tab_footer">
                       <input type="submit" name="Submit" value="Add Clinic" />
                      <input type="hidden" name="action" value="addEditClinicInAccount"/>
                      <input type="hidden" name="subaction" value="add"/>
                      <input type="hidden" name="clinic_id" value="<!clinic_id>"/>
                    </div>-->
                </td>
            </tr>
				  <!--  4th ROW ENDS  -->
            <tr>
                <td>&nbsp;</td>
            </tr>
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
  
</div><!-- div ( container ) Ends -->
