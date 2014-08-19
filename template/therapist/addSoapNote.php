<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script lanaguage="javascript" >
<!javascript>
</script>
</head>
<body>
<form name="frm" action="index.php" method="post" >
<div style="width:840px; margin:0 auto;">
<div style="float:left">
<table width="250" border="0" >
  <tr>
    <td><strong>Subjective</strong></td>
  </tr>
  <tr>
    <td valign="top"><textarea name="subjective" id="subjective" cols="" rows="" style="width:98%; height:70px;"><!subjective></textarea></td>
  </tr>
  <tr>
    <td style="padding-top:5px;"><strong>Objective</strong></td>
  </tr>
  <tr>
    <td valign="top"><textarea name="objective" id="objective" cols="" rows="" style="width:98%; height:70px;"><!objective></textarea></td>
  </tr>
  <tr>
    <td style="padding-top:5px;"><strong>Assessment</strong></td>
  </tr>
  <tr>
    <td><textarea name="assessment" id="assessment" cols="" rows="" style="width:98%; height:70px;"><!assessment></textarea></td>
  </tr>
  <tr>
    <td style="padding-top:5px;"><strong>Plan</strong></td>
  </tr>
  <tr>
    <td><textarea name="plan" id="plan" cols="" rows="" style="width:98%; height:70px;"><!plan></textarea></td>
  </tr>
</table></div>
<div style="float:right; padding-top:25px; padding-bottom:40px; "><img src="images/Images_Body_Front_Back_Side.jpg" width="550"  />
</div>
<Span style="padding-left:250px;">
    <input name="action" type="hidden" value="addSoapNote" />
    <input name="patient_id" type="hidden" value="<!patient_id>" />
    <input name="save" type="submit" value="Save" />
</Span>

</div>
</body>
</html>
