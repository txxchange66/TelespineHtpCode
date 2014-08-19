<div id="container">
 <form action="index.php"  method="POST"  name="my_form" >
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
                                          <a href="index.php?action=choose_notification_reminder">NOTIFICATION AND REMINDERS</a>
                                    / 
                                    <span class="highlight">SET INTERVAL</span>
                                    </div></td>
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
                        <div align="left" class="largeH1" style="padding-left:13px;">Setting Interval for Outcome Measure</div>

                        <div >
                           <table border="0" cellpadding="0" cellspacing="0" style="margin-top:20px;"width="100%"  >
                            <tr>
                                <td width="15%">&nbsp;</td>
                                <td>
                                <form action="index.php" method="POST" >
                                    <table border="0" cellpadding="1" cellspacing="1" width="70%" >
                                        <tr>
                                            <td width="20%">Acute</td>
                                            <td width="50%">
                                                <select name="acute" id="acute" style="width:150px;" >
                                                    <option value="" >Please select...</option>
                                                    <option value="2">Two Weeks</option>
                                                    <option value="4">Four Weeks</option>
                                                    <option value="6">Six Weeks</option>
                                                    <option value="8">Eight Weeks</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20%">Sub-Acute</td>
                                            <td width="50%">
                                                <select name="sub-acute" id="sub-acute" style="width:150px;" >
                                                    <option value="" >Please select...</option>
                                                    <option value="2">Two Weeks</option>
                                                    <option value="4">Four Weeks</option>
                                                    <option value="6">Six Weeks</option>
                                                    <option value="8">Eight Weeks</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20%">Chronic</td>
                                            <td width="50%">
                                                <select name="chronic" id="chronic" style="width:150px;" >
                                                    <option value="" >Please select...</option>
                                                    <option value="2">Two Weeks</option>
                                                    <option value="4">Four Weeks</option>
                                                    <option value="6">Six Weeks</option>
                                                    <option value="8">Eight Weeks</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding-left:30px;" ><input type="submit"  style=" width:120px; margin-left:5px; margin-top:5px;" value="Save Setting"></td>
                                        </tr>
                                    </table>
                                    <input type="hidden" name="action" value="set_interval_om" />
                                    <input type="hidden" name="action_submit" value="submit" />
                                </form>
                                </td>
                                <td align="right"> &nbsp; </td>
                            </tr>
                            </table>
                      </div>                     </td>
                  </tr>
                  <!--  4th ROW ENDS  -->
                  
                  <!--  5th ROW ENDS  -->
                </table>
      </div>
      
          <!-- body part ends-->
                <!--  INNER MAIN TABLE STARTS (5) ROWS ENDS -->                </td>
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
<script src="js/jquery.js"></script>  
<script language="javascript" type="text/javascript">
      $(document).ready(function() {
        $("select[@name='acute'] option[@value='<!acute>']").attr("selected","selected");
        $("select[@name='sub-acute'] option[@value='<!sub-acute>']").attr("selected","selected");
        $("select[@name='chronic'] option[@value='<!chronic>']").attr("selected","selected");
      });
</script>