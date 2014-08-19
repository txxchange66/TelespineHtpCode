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
                                          <span class="highlight">NOTIFICATIONS</a>
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
                        <div align="left" class="largeH1" style="padding-left:13px;">Select Option</div>

                        <div >
                           <table border="0" cellpadding="0" cellspacing="0" style="margin-top:20px;"width="100%">
                            <tr>
                                <td width="15%">&nbsp;</td>
                                <td>
                                <form action="index.php" method="POST" >
                                    <table border="0" cellpadding="1" cellspacing="1" width="70%">
                                        <tr>
                                            <td width="10%"><input type="radio" id="select_option" name="select_option" value="one" checked="true" ></td>
                                            <td width="90%">Edit/Create Patient Non-Compliance Message for all their Patients</td>
                                        </tr>
                                        <!--<tr>
                                            <td><input type="radio" id="select_option" name="select_option" value="two"></td>
                                            <td>Set Interval of Outcome Measure for Patients</td>
                                        </tr>-->
                                        <tr>
                                            <td colspan="2"><input type="submit"  style=" width:120px; margin-left:5px; margin-top:5px;" value="Next"></td>
                                        </tr>
                                    </table>
                                    <input type="hidden" name="action" value="choose_notification_reminder" />
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
         <div style="clear:both;"><br></div>
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
