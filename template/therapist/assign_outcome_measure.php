<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Mytxplan</title>
</head>
<body>
<script language="javascript" >
 <!javascript>
</script>
<form id="frm" action="index.php?action=assign_om" method="post" >
<table border="0" cellpadding="4" cellspacing="0" align="center">
  <tr>
    <td colspan="2">&nbsp;<span id='error' ><!error></span></td>
  </tr>
  <tr>
    <td align="center">
        <input name="outcome_measure[]" type="checkbox" value="1" <!checked1> <!disabled1> />
    </td>
    <td >Assign Oswestry</td>
  </tr>
  <tr>
    <td align="center">
        <input name="outcome_measure[]"  type="checkbox" value="2" <!checked2> <!disabled2> />
    </td>
    <td>Assign NDI</td>
  </tr>
  <tr>
    <td align="center">
        <input name="outcome_measure[]"  type="checkbox" value="4" <!checked4> <!disabled4>/>
    </td>
    <td>Assign FABQ</td>
  </tr>
  <tr>
    <td align="center">
        <input name="outcome_measure[]"  type="checkbox" value="5" <!checked5> <!disabled5>/>
    </td>
    <td>Assign LEFS</td>
  </tr>
  <tr>
    <td align="center">
        <input name="outcome_measure[]"  type="checkbox" value="6" <!checked6> <!disabled6>/>
    </td>
    <td>Assign DASH</td>
  </tr>
    <tr>
    <td align="center">
        <input name="outcome_measure[]"  type="checkbox" value="7" <!checked7> <!disabled7>/>
    </td>
    <td>Assign LBHRA</td>
  </tr>
    <tr>
    <td align="center">
        <input name="outcome_measure[]"  type="checkbox" value="8" <!checked8> <!disabled8>/>
    </td>
    <td>Assign MHQ</td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">
    <input name="Submit" type="submit" value="Assign" />
    <input name="patient_id" type="hidden" value="<!patient_id>" />
    
    </td>
  </tr>
</table>
</form>
</body>

</html>
