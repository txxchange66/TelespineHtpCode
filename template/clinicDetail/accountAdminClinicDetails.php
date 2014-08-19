<div id="container">
  <form action="index.php?action=systemEditClinicDetails" method="post">
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
                    <td><table style="vertical-align:middle;width:700px;">
                        <tr>
                          <td style="width:400px;">
                          	<div id="breadcrumbNav">
                          		<a href="index.php?action=sysAdmin" >HOME </a> / <a href="index.php?action=listClinic" >CLINIC LIST </a> / <span class="highlight">CLINIC INFORMATION</span>
                          		</div>
                          		</td>
                          <td style="width:300px;"><table border="0" cellpadding="5" cellspacing="0" style="float:right;">
                              <tr>
                                <td class="iconLabel">&nbsp;</td>
                              </tr>
                            </table></td>
                        </tr>
                      </table></td>
                        </tr>
                      </table>
					</td>
                  </tr>
				  <!--  1st ROW ENDS  -->
                  <tr>
                    <!-- <td class="adminSubHeading">Account Administration </td> -->
                    <td>&nbsp;</td>
                  </tr>
				  <!--  2nd ROW ENDS  -->
                  <tr>
                    <td class="error">&nbsp;<!error></td>
                  </tr>
				  <!--  3rd ROW ENDS  -->				  
                  <tr>
                    <td>
					<div id="tab_navigation">
                        <!tabNavigation>
                      </div>
                      <div id="tab_header"></div>
                      <div id="tab_main">
<table width="100%" border="0" cellspacing="1" cellpadding="1" onMouseOver="help_text(this, 'View Clinic Details')">
						  <tr>
							<td width="16%"><div align="left"><strong>Clinic Name : </strong></div></td>
							<td width="29%"><!clinic_name></td>
							<td width="8%">&nbsp;</td>
							<td width="24%"><div align="left"><strong>City :</strong></div></td>
							<td width="23%"><!clinic_city></td>
						  </tr>
						  <tr>
							<td><div align="left"><strong>Address : </strong></div></td>
							<td><!clinic_address><!commaseprator></td>
							<td>&nbsp;</td>
							<td><div align="left"><strong>State / Province :</strong></div></td>
							<td><!clinic_state></td>
						  </tr>
						    <td>&nbsp;</td>
						    <td><!clinic_address2></td>
						    <td>&nbsp;</td>
						    <td><div align="left"><strong>Zip Code :</strong></div></td>
						    <td><!clinic_zip></td>
						    </tr>
						  <tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						  </tr>
						  <tr>
						    <td>&nbsp;
						     </td><td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
						    </tr>
                      </table>
                      </div>
                      <div id="tab_footer">
                      	<input type="submit" name="Submit" value=" Edit Clinic Info. "/>                        
						<input type="hidden" name="clinic_id" value="<!clinic_id>"/>
                      </div></td>
                  </tr>
				  <!--  4th ROW ENDS  -->
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
				  <!--  5th ROW ENDS  -->
                </table>
				<!--  INNER MAIN TABLE STARTS (5) ROWS ENDS -->
          <!--  MAIN TABLE ENDS  -->
        </div><!-- div ( mainContent ) Ends --></td>
    </tr>
    <tr>
      <td colspan="2"><div id="footer">
          <!footer>
        </div></td>
    </tr>
  </table>
  </form>
</div><!-- div ( container ) Ends -->
