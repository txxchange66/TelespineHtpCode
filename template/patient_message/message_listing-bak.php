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
                          		<a href="index.php?action=therapist" >HOME </a> / 
                          		<span class="highlight">MESSAGE CENTER</span></div></td>
                          <td style="width:300px;"><table border="0" cellpadding="5" cellspacing="0" style="float:right;">
                              <tr>
                                <td class="iconLabel">&nbsp;</td>
                              </tr>
                            </table></td>
                        </tr>
                      </table></td>
                        </tr>
                      </table></td>
                  </tr>
				  <!--  1st ROW ENDS  -->
                
				  <!--  2nd ROW ENDS  -->
				  
                  <!--  3rd ROW ENDS  -->				  
                  <tr>
                    <td>
                        <div id="tab_header">
                        	<table width="100%">
                        		<tr>
                        			<td align="left">
                        				Message center
                        			</td>
									<td align="right">
                        				<a style="color:#ffffff" href="index.php?action=compose_message" >Compose New</a>
                        			</td>
                        		</tr>
                        		
                        	
                        	</table>
                        </div>
                        
                        <div id="tab_main" >
                       		<table>
				  				<td>
				  					Show messages from
				  				</td>
				  				<td>
				  				  <form method="post" name="frm" action="index.php?action=message_listing" >	
				  					<select name="seleted_patient_id" onchange="javascript:document.frm.submit();" style="width:200px;">
				  						<option value="" >All</option>
				  						<!patientListOption>
				  					</select>
				  				  </form>	
				  				</td>	
				  			</table>	
				  		
						  <table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" onMouseOver="help_text(this, 'Displays the Users, click on one to edit or remove it.  To sort this list click on the column header you would like to sort by')">
                            <!message_list_head>
                            <!message_list_record>
                          </table>
                          <div class="paging"> <span onMouseOver="help_text(this, 'Select a page number to go to that page')">
                            <!link>
                            </span>
                           </div>
						</div>
                      <div id="tab_footer" style="height:20px;">
                      		
                           
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
