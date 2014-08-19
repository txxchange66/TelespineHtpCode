<script type="text/JavaScript">
            var GB_ROOT_DIR = "js/greybox/";
    </script>
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>

<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" /> 

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
    <td width="84%"  align="left" valign="top" class="mainContent-sec">
    <div id="breadcrumbNav" style="float:left;padding-left:20px;margin-top:40px;" >
        <a href="index.php?action=listClinic" >ACCOUNT</a> / <span class="highlight">SELECT ACCOUNT</span>
    </div>
    <div style="float:right;padding-right:45px;padding-bottom:15px;margin-top:10px;">
            <a href="index.php?action=editClinicInfo" ><img src='images/img-createnewaccount.gif' /></a>
    </div>
	
    <!--<div id="mainContent">-->
    <!-- Main Content Starts -->
		
		<br /><br /><br /><br /><br /><br /><br /><table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td>
			<!--  Start  -->
			<h1 class="largeH1"><!heading></h1>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="48%" colspan="2">
                              <table border="0" cellpadding="8" cellspacing="0" height="47px" >
                                <tr>
                                    <td style="valign:middle;">
                                    <form method="POST" action="index.php?action=listClinic" name="filter" onSubmit="return search_key(document.filter.search.value);" onMouseOver="help_text(this, 'Please enter text to restrict the list to only items that contain the text entered')">
                                        <label for="search">Search:</label>
                                        <input type="text" size="20" maxlength="250" name="search" value="">
                                        <input type="submit" size="20" name="sub" value=" Search " >
                                        <input type="button" size="20" name="clear" value="   Clear   " onclick="location.href='index.php?action=listClinic'">
                                        <input type="hidden" size="20" name="restore_search" value="">
                                    </form>
                                    </td>
                                </tr>
                            </table>
                      </td>
                    </tr>                    
                    <tr>
                      <td colspan="2"><table width="100%" border="0" cellspacing="1" cellpadding="1" class="list">
						  <!listClinicHead>
						  <!rowdata>
                      </table>
                      <table onMouseOver="help_text(this, 'Select a page number to go to that page')">
                         <tr>
                    	  <td>
                    		<!link>                    	  </td>
                    	</tr>
                      </table>                      </td>
                    </tr>
					<tr>
					  <td>&nbsp;</td>
					</tr>
                  </table>
			<!--  End  -->
			</td>
		  </tr>
		</table>
		
    <!-- Main Content Ends -->
	</td>
  </tr>
  <tr>
    <td colspan="2">	
	<div id="footer">
		<!footer>
	</div>
	</td>
  </tr>
</table>
<!--</div>-->
