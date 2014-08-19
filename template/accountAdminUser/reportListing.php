<input type="hidden"  name="showsoap" id="showsoap" value="<!shopshow>">
<script language="JavaScript" src="js/show_menu.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>

<script type="text/JavaScript">
     var GB_ROOT_DIR = "js/greybox/";
</script>
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
<link href="js/greybox/gbs" rel="stylesheet" type="text/css" media="all" />
    
<script type="text/javascript">
<!--

function showreport(val){
	   var action = val.value;
	   	url = 'index.php?action=txReferralReports&period='+action;
        window.location = url;
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
    <div id="mainContent-sec">
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
                                        <div id="breadcrumbNav" style="padding-left:0px;margin-top:18px;">
                                            <a href="index.php?action=sysAdmin" >HOME </a> / <span class="highlight">REFERRAL REPORTS</span>
                                        </div>
                                        </td>
                                        <td style="width:300px;height: 72px;">
                                            &nbsp;
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
					<div align="right" style=" padding-top:53px;" ><a href="<!downloadreport>">Export Report</a>
                         <!navigationTab><div style="padding-left:50px; padding-top:5px;"><span style="padding-left: 5px;">Run Report: <select name="period"  onchange="showreport(this);"><!period></select></span></div>
                    </div>
                 </td>
            </tr>
            <tr>
                <td>
                    <div>
                        <table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" >
                            <!reportTblHead>
                            <!reportTblRecord>
                        </table>
					<table border="0" cellpadding="2" cellspacing="1" width="100%">
                     		<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td style="width: 166px">Total Number of Referrals</td>
								<td><!totalReferrals></td>
							</tr>
                            
                        </table>
                    </div>
                    <div class="paging">
                        <span >
                            <!link>
                        </span>
                    </div>
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
<script language="JavaScript1.2">mmLoadMenus();</script>