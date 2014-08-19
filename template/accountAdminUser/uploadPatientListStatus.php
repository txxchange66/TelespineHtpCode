<script language="JavaScript" src="js/show_menu.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>


<div class="center-align" >
  <div id="container" style="padding-top:14px;">
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
          <td colspan="3" style=" width:400px; height:9px;"></td>
        </tr>
        <tr>
          <td width="151" style=" width:400px;"><div id="breadcrumbNav" style="margin-top:18px;"> <a href="index.php">HOME</a> / <span class="highlight"  > <a href="index.php?action=accountAdminClinicList">ACCOUNT</a>/ UPLOAD PATIENT LIST</span></div></td>
          <td width="138"></td>
          <td width="173" align="right" style="padding-right:75px; margin-top:11px;height:70px;">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="top" style=" width:400px; font-size: large;font-weight: bold;padding-bottom: 15px; margin:0px; vertical-align:top;">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="top"  ><div style=" padding-top:15px;">
            <!tabNavigation>
          </div></td>
        </tr>
        <tr>
          <td colspan="3" valign="top" class="topnavnew" >&nbsp;</td>
        </tr>
      </table>
      
      
 <!-- best practices table starts from here-->  
      <form name="uploadpatientlist" action="" enctype="multipart/form-data" method="post">
      <table width="100%" border="0" cellspacing="0" cellpadding="0"  style="border:1px solid #CCCCCC;">
        <tr>
          <td class="marketingtitle" style="color:#54A113;"><span class='highlight'>You have successfully created <!successnum> of  <!totalnum> health records.</span></td>
        </tr>
        <tr>
          <td></td>
        </tr>
		<tr>
          <td><p class="text" style="color:#FF0000;"><!failnum></p></td>
        </tr>
        
		<tr>
          <td style="padding-left:38px;"><ul type="square"><!statusMessage></ul>
		  </td>
        </tr>
		<tr>
          <td><p class="text" style='color:#0069A0;'><strong><!custommessage></strong></p></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
	  </form>
 <!-- best practices table ends here--> 
      <div class="paging" align="right"></div>
      <!-- body part ends-->
      <!-- [/items] -->
      <div align="center" style="padding:5px;"> </div>
      <!-- [/list] -->
    </div>
  </div>
  
</div>
<script language="JavaScript1.2">mmLoadMenus();</script>