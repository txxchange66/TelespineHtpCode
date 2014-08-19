<script language="JavaScript" src="js/show_menu.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>
<link rel="STYLESHEET" type="text/css" href="css/styles.css" />
<!-- code added by MOHIT SHARMA. -->
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	theme_advanced_buttons1 : "bold,italic,underline,separator, undo,redo",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	 // theme_advanced_statusbar_location : "bottom", removed for a time MOHIT SHARMA
	extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]"
	
});
</script>

 
 <!-- addititons ends here -->

</head>

<body>

<div class="center-align" >
<div id="container"><!--style="padding-top:14px;"-->
	<div id="header">
      <!header>
    </div>
    <div id="sidebar">
          <!sidebar>
        </div>
	<!-- body part starts-->
	
	<div id="mainContent">
<table border="0" cellpadding="0" cellspacing="0" style="vertical-align:middle; width:100%; height:81px;">
  <tr>
    <td colspan="5" style="height:9px;">    </td>
    </tr>
  <tr>
    <td colspan="5" style="width:741px;">
        <div id="breadcrumbNav" style="margin-top:40px;float:left;width:550px;">
            <a href="index.php">HOME</a> / <span class="highlight"  >  <a href="index.php?action=userListing">ACCOUNT ADMINISTRATOR </a>/ MARKETING</span>
        </div>
    </td>
  </tr>
  <tr>
    <td colspan="5" valign="top" style=" width:741px; font-size: large;font-weight: bold;padding-bottom: 15px; margin:0px; vertical-align:top;">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="5" valign="top"  >
     <div class="error" id="errorDiv" style="color:#54a113" >&nbsp;<!error></div>
    <div style="padding-top:24px;width:741px;float:left;">
            <!navigationTab>
    </div> </td>
    </tr>
     <tr>
          <td colspan="5" valign="top" class="topnavnew" >&nbsp;</td>
        </tr>
</table>
<form name="bulletin_board" action="index.php" method="POST" >
<table width="100%" border="0" cellspacing="0" cellpadding="0" onMouseOver="help_text(this, 'View Clinic Details')" style="border:1px solid #CCCCCC;">
						  <tr>
							<td colspan="2" class="marketingtitle">Bulletin Board</td>
		</tr>
						  <tr>
							<td colspan="2">						 </td>
		</tr>
						    <tr><td colspan="2"></td>
						    <tr><td colspan="2"></tr>
						  <tr>
							<td colspan="2" valign="top" class="text">
							
							  <textarea name="message" id="textarea"  word-wrap: break-word; style="width:410px; height:200px; border:1px solid #999999;" cols="300" rows="13"><!message></textarea>
							  <input type="hidden" name="submit" value="submit" />
							  <input type="hidden" name="action" value="bulletinBoard" />
							  
							
							
							</td>
						  </tr>
						  <tr>
						    <td width="58%" align="right" valign="top" style="padding-top:5px; padding-bottom:5px;"><input type="submit" name="button" id="button" value="Submit" /></td>
	                        <td width="41%">&nbsp;</td>
	    </tr>
      </table>
      </form>
<div class="paging" align="right"></div>
	<!-- body part ends-->
	<!-- [/items] -->
	<div align="center" style="padding:5px;">
	  </div>
	<!-- [/list] -->
		
	</div>
  </div>
</div>
<script type="text/javascript">
    mmLoadMenus("<!showmenu>");
</script>