<input type="hidden"  name="showsoap" id="showsoap" value="<!shopshow>">
<script language="JavaScript" src="js/show_menu.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>

<script language="JavaScript">
function toggleRequest(id1,id2)
{	document.getElementById(id1).style.display='none';
	document.getElementById(id2).style.display='inline';
	//document.getElementById('load').src='ajax-loader.gif';
}
function showHide()
{	
	if(document.getElementById("result").style.display=="inline")
		document.getElementById("result").style.display='none';
	else
		document.getElementById("result").style.display='inline';
}
</script>
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
          <td colspan="3" style=" width:400px; height:9px;"></td>
        </tr>
        <tr>
          <td width="151" style=" width:400px;"><div id="breadcrumbNav" style="margin-top:27px;"> <a href="index.php">HOME</a> / <span class="highlight"  > <a href="index.php?action=accountAdminClinicList">ACCOUNT</a>/ UPLOAD PATIENT LIST</span></div></td>
          <td width="138"></td>
          <td width="173" align="right" style="padding-right:75px; margin-top:11px;height:70px;">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="top" style=" width:400px; font-size: large;font-weight: bold;padding-bottom: 15px; margin:0px; vertical-align:top;">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="top"  ><div style=" padding-top:24px;">
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
        <!results>
		<tr>
          <td class="marketingtitle">Upload Patient List</td>
        </tr>
        <tr>
          <td></td>
        </tr>
        <tr>
          <td><p class="text">To upload your existing list of patients or clients to Tx Xchange and create health records for them, please download the example template and follow the simple instructions. Don't forget to use a CSV file type for the upload. CSV file types can<br />be created from an Excel spreadsheet by using the "Save as" function. If you have any questions, please contact Tx Xchange Support by clicking on the "Feedback" button.<br /><br /><span><!error></span><br />
		  <input type="file" name="patient_list">
		  <span class="downloadfile" style="padding-left:15px;"><a  href="<!url>/patient_lists_uploaded/Example-Patient-Upload-Template.xls" >Example Upload Template</a></span><br /><br /></p>
		  </td>
        </tr>
        <tr>
			<td><p class="text"><span id="submit"><input type="submit" name="submit_patientlist"  value="UPLOAD" onClick="javascript:toggleRequest('submit','process');"/></span><span id="process" style="display:none;padding-left:0px;font-weight:bold;"><!--<img id="load" src="ajax-loader.gif" valign="absmiddle"/>--><input type="button" name="dummy"  value="PROCESSING..." disabled/>&nbsp;&nbsp;Please wait while we process your request. This may take few minutes.</span>
			</p>
			<br /><br /><br />
			</td>
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