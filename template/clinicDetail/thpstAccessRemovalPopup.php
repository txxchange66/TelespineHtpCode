<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

            <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

            <title>Subscription Confirmation</title>

            <meta name="description" content="<!metaDesc>">        

            <link rel="stylesheet" type="text/css" href="css/styles_popup.css" />

                        

</head>

<body>

 

<div id="container">

 

            <div id="mainContent">

 

<script language="JavaScript">

<!--

function processConfirm(opcode)

{

	if (opcode == 'yes')
	{
		window.opener.document.getElementById('therapistAccess').checked = false;
	}
	else if (opcode == 'no')
	{
		window.opener.document.getElementById('therapistAccess').checked = true;
	}
	
	window.close();

}

-->

</script>           

 <table width="75%" border="0" cellspacing="0" cellpadding="0" align="center">

  <tr>

    <td><h2>Provider Removal Confirmation <!clinic_name></h2></td>

  </tr>

  <tr>

    <td style="padding-left:25px; padding-right:25px;"><b><!name_first>&nbsp;<!name_last></b></td>

  </tr>

  <tr>

            <td>&nbsp;

            

            </td>

  </tr>

  <tr>

    <td style="padding-left:25px; padding-right:25px;"><strong>You have selected to remove Provider access for this User. By doing this, this user will not be able to create or assign plans to the patients.</strong></td>

  </tr>

  <tr>

    <td style="padding-left:40px; padding-right:40px;">&nbsp;</td>

  </tr>

  <tr>

    <td style="padding-left:20px; padding-right:20px;"m align="center"><!patientListTbl></td>

  </tr>

  <tr>

    <td>
	<div align="right">
	<form name="therapistDelConfirmForm">

            <table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-right:40px;"><div align="right">Your account will now have <b><!totTherapists></b> Provider Subscriptions</div></td>
  </tr>
  <tr>

            <td>&nbsp;            </td>
 </tr>

 <tr>


            <td style="padding-right:40px;" align="right">
<div>
      <input type="button" name="Cancel" value="Cancel" onclick="processConfirm('no');" />

      <input type="button" name="Confirm" value="Confirm" onclick="processConfirm('yes');" />    </div></td>
    </tr>

 <tr>

            <td>&nbsp;            </td>
 </tr>
</table>

</form>
</div>
            </td>

            <tr>

    <td style="padding-left:40px; padding-right:40px;">&nbsp;</td>

  </tr>

  </tr>

</table>

</div>

            </div>

</body>

</html>
