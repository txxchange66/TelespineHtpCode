<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Resend Patient's Login Info</title>
</head>
<body>
<div id="alert_container">
<div id="popup_body" style="margin:0px; padding:0px; vertical-align:top;">
  <table width="99%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="82%" align="left" >
      <?php
        if( $_REQUEST['action'] == 'success' ){
            echo "Login info email successfully sent to Patient";
        }
        else if( $_REQUEST['action'] == 'fail'  ){
            echo "E-mail delivery failed.";
        }
	else if( $_REQUEST['action'] == 'mass_message_success'  ){
            echo "Your preference has been applied successfully.";
        }
	else if( $_REQUEST['action'] == 'mass_message_fail'  ){
            echo "Your preference has not been applied. Try again later.";
        }else if( $_REQUEST['action'] == 'success_subscriber'  ){
            echo "Login info email successfully sent to subscriber.";
        }
        else if( $_REQUEST['action'] == 'successintake'  ){
            echo "Adult Intake Paperwork has been assigned successfully";
        }else if( $_REQUEST['action'] == 'successbriefintake'  ){
            echo "Brief Intake Paperwork has been assigned successfully";
        }
        else if( $_REQUEST['action'] == 'successunassignintake'  ){
            echo "Adult Intake Paperwork has been unassigned successfully";
        }
        else if( $_REQUEST['action'] == 'successunassignbriefintake'  ){
            echo "Brief Intake Paperwork has been unassigned successfully";
        }else if($_REQUEST['action'] == 'intakeassign'){
        	//echo "Adult Intake Paperwork already assigned to this patient. Please wait for complete it first.";
            echo "The Brief Intake Paperwork can't be assigned because the Adult Intake Form is currently assigned";
        }else if($_REQUEST['action'] == 'brifeintakeassign'){
        	echo "The Adult Intake Paperwork can't be assigned because the Brief Intake Form is currently assigned.";
        }
        
        
      ?>   
      </td>
    </tr>
    <tr>
        <td align="center">&nbsp;</td>
    </tr>
    <tr>
        <td align="center"><input type="button" value="  Ok  " onclick="parent.parent.GB_hide();"/></td>
    </tr>
  </table>
</div>

</div>
 </body>
</html>
