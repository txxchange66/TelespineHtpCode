<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language='javascript' type='text/javascript' src='/js/jquery.js'></script>
<script language="javascript" type="text/javascript">
function deletefile(file_id,patientId)
{
    $.post('../index.php?action=deletedownloadfile',{file_id:file_id}, function(data,status){
	       if( status == "success" ){
		    //alert("Document deleted successfully");
            parent.parent.GB_hide();
            parent.reloadlab(patientId);
	       }
	       else{
		        alert("Ajax connection failed.");
		    }     
	    })
}

</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Resend Patient's Login Info</title>
</head>
<body>
<div id="alert_container">
<div id="popup_body" style="margin:0px; padding:0px; vertical-align:top;">
  <table width="99%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="82%" align="center" >
      <span>Do you want to delete this Document?</span>   
      </td>
    </tr>
    <tr>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2"><div align="center"><input type="button" value="  Ok  " onclick="deletefile(<?php echo $_REQUEST['file_id'] ?>,<?php echo $_REQUEST['patientId'] ?>);"/>&nbsp;&nbsp;<input type="button" value="  Cancel  " onclick="parent.parent.GB_hide();"/></div></td>
    </tr>
  </table>
</div>

</div>
 </body>
</html>
