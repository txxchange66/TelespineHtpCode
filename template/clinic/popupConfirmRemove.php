<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

            <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

            <title>Clinic Status</title>

            <meta name="description" content="<!metaDesc>">        

            <link rel="stylesheet" type="text/css" href="css/styles_popup.css" />

                        

</head>

<body>

 

<div>

      <div>

 

<script language="JavaScript">

<!--

function processDelConfirm(id,changeTo)

{
	
	top.window.location.href = 'index.php?action=removeClinicAllSubscribers&clinic_id=' + id+'&to='+changeTo;       
	//parent.window.location.href = 'index.php?action=articleList';                           
	
	
	//parent.parent.GB_CURRENT.hide();

}

-->

</script>           

 

<table width="75%" border="0" cellspacing="0" cellpadding="0" align="center">

  <tr>

    <td><h2><!popupConfirmationHeading></h2></td>

  </tr>

  <tr>

    <td style="padding-left:25px; padding-right:25px;"><b><!clinic_name></b></td>

  </tr>

  <tr>

            <td>&nbsp;

            

            </td>

  </tr>

  <tr>

    <td style="padding-left:25px; padding-right:25px;"><strong><!popupConfirmationMessage></strong></td>

  </tr>

  <tr>

    <td style="padding-left:40px; padding-right:40px;">&nbsp;</td>

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

            <td>&nbsp;            </td>
 </tr>

 <tr>

            <td style="padding-right:40px;" align="right">
<div>
      <input type="button" name="Cancel" value="Cancel" onclick="javascript:parent.parent.GB_CURRENT.hide();" />

      <input type="button" name="Confirm" value="Confirm" onclick="processDelConfirm('<!clinic_id>','<!changeToStatus>')" />    </div></td>
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
