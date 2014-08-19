<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language='javascript' type='text/javascript' src='/js/jquery.js'></script>
<script language="javascript" type="text/javascript">
function unassignintake(provider_id,patient_id)
{
        
    $.post('../index.php?action=unassignIntake',{patient_id:patient_id,provider_id:provider_id}, function(data,status){
            if( status == "success" ){
                if(/success/.test(data)){
                    //GB_showCenter('Adult Intake Paperwork', '/template/alert.php?action=successunassignintake', 100, 350 );
                    parent.parent.GB_hide();
                }
                else if( /failed/.test(data) ){
                    GB_showCenter('Adult Intake Paperwork', '/template/alert.php?action=fail', 100, 350 );
                }    
                else{
                    alert(data);
                }
            }
            else{
                alert("Ajax connection failed.");
            } 
             parent.document.getElementById("unassign_intake_but").style.display= 'none';  
             parent.document.getElementById("intake_but").style.display='block';
             parent.document.getElementById("intake_but").style.fontSize = '11px';
             parent.document.getElementById("intake_but").style.cssFloat = 'right';
          	         $("#intakelbl").html('Assign Adult Intake Paperwork');    
             if(parent.document.getElementById("brifeintake_but").value== 'Assign'){
        	   parent.document.getElementById("brifeintake_but").disabled= false;   
            } 
            //document.getElementById("intake_but").value= 'Assign'; 
            //document.getElementById("intake_but").onclick = assignintake(provider_id ,patient_id);
        }
    )      
        
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
      <span>Do you want to unassign intake paperwork?</span>   
      </td>
    </tr>
    <tr>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2"><div align="center"><input type="button" value="  Ok  " onclick="unassignintake(<?php echo $_REQUEST['provider_id'] ?>,<?php echo $_REQUEST['patient_id'] ?>);"/>&nbsp;&nbsp;<input type="button" value="  Cancel  " onclick="parent.parent.GB_hide();"/></div></td>
    </tr>
  </table>
</div>

</div>
 </body>
</html>
