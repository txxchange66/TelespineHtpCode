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
                  <td colspan="5" style=" width:741px;">
                <div id="breadcrumbNav" style="margin-top:40px;float:left;width:550px;">
                    <a href="index.php">HOME</a>
                    / 
                    <a href="index.php?action=userListing">ACCOUNT ADMINISTRATOR</a>
                    / 
                    <span class="highlight"  >
                    MAINTENANCE
                    </span>
                </div>
                </td>
            </tr>
              <tr>
                <td colspan="5" valign="top" align="left" >
                   <div style="padding-top:70px;width:741px;float:left;">
                        <!navigationTab>
                  </div>
                  
                </td>
            </tr>
        </table>
        
        <table border="0"  margin="0" cellpadding="2" cellspacing="1" width="100%" class="list"  >
            <!listHead>     
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