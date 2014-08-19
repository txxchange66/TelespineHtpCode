<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<link href="/css/intake_form.css" rel="stylesheet" type="text/css"/>  
<script language="JavaScript" type="text/javascript" src="js/jquery-latest.js"></script>  
<script language="JavaScript" src="js/countryState.js"></script> 
<script language="JavaScript" type="text/javascript" src="js/common.js"></script>
<script language="JavaScript" type="text/javascript">

$(document).ready(function () { 
   var gender_id = '<!gender>';
    $("#"+ gender_id).attr('checked', 'checked');
});

function close_gb()
{
    var street = document.getElementById("address").value;
    var city = document.getElementById("city").value;
    var age = document.getElementById("age").value;
    var state = document.getElementById("state").value;
    var zip = document.getElementById("zipcode").value;
  
    document.getElementById("address").style.border='';
    document.getElementById("city").style.border='';
    document.getElementById("age").style.border='';
    document.getElementById("state").style.border='';
    document.getElementById("zipcode").style.border='';
    document.getElementById("astrik1").style.display='none';
    document.getElementById("astrik2").style.display='none';
    document.getElementById("astrik3").style.display='none';
    document.getElementById("astrik4").style.display='none';
    document.getElementById("astrik5").style.display='none';
    if(street == "")
    {
        document.getElementById("address").style.border='2px solid red';
        document.getElementById("astrik1").style.display='block';
        document.getElementById('address').focus();
        return false;
    }
    else if(city == "")
    {
        document.getElementById("city").style.border='2px solid red';
        document.getElementById("astrik2").style.display='block';
        document.getElementById('city').focus();
        return false;
    }
    else if(state == "")
    {
        document.getElementById("state").style.border='2px solid red';
        document.getElementById("astrik3").style.display='block';
        document.getElementById('state').focus();
        return false;
    }
    else if(age == "")
    {
        document.getElementById("age").style.border='2px solid red';
        document.getElementById("astrik4").style.display='block';
        document.getElementById('age').focus();
        return false;
    }
    else if(zip == "")
    {
        document.getElementById("zipcode").style.border='2px solid red';
        document.getElementById("astrik5").style.display='block';
        document.getElementById('zipcode').focus();
        return false;
    }
     $("#briefintakeform").submit();
     
     //setTimeout(parent.parent.GB_hide(),3000);
}
</script>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
</head>
<body>
<div class="wrapper">

	<table cellpadding="0" cellspacing="0" style="width: 100%">
		<tr>
			<td class="top-Name" style="width: 170px; " ><h2 style="font-size: 22px; font-family: Arial,Helvetica,sans-serif; font-weight: normal;text-align: left;"><!patientname></h2></td>
			<td style="width: 670px"><h1>BRIEF INTAKE FORM</h1></td>
			<td class="date" ><!assign_datetime></td>
		</tr>
	</table>

<div style="height:20px;"></div>
<form name="briefintakeform" id="briefintakeform" action="index.php" method="post"> 
<div>
	<table cellpadding="0" cellspacing="0" style="width: 100%" class="form1-name">
		<tr>
			<td style="width:200px;"><strong>Name:</strong></td>
			<td><!patientname></td>
			<td style="width:200px;"><strong>Gender:</strong> </td>
			<td class="radio"><input name="gender" id="Male" type="radio" value="Male" <!in_genM> /> Male <input name="gender" type="radio" id="Female" value="Female" <!in_genF>/> Female</td>
		</tr>
		<tr>
			<td><strong>Street Address:</strong><div id="astrik1" style="float: right; font-size: 17px; color:#f00; display: none;">*</div></td>
			<td><input name="address" id="address" type="text" value="<!address>" /></td>
			<td><strong>City:</strong><div id="astrik2" style="float: right; font-size: 17px; color:#f00; display: none;">*</div></td>
			<td><input name="city" id="city" type="text" value="<!city>" /></td>
		</tr>
		<tr>
            <td><strong>Country:</strong></td>
            <td><select name="country" id="country" onchange="toggleState();">
                <!patient_country_options>
			</select></td>
			<td><strong>State/Province:</strong><div id="astrik3" style="float: right; font-size: 17px; color:#f00; display: none;">*</div></td>
			<td><select name="province" id="state">
				<option value="">Choose State...</option>
                <!stateOption>
			</select></td>
		</tr>
		<tr>
            <td><strong>Email (or parents email):</strong></td>
			<td><!email></td>
			<td><strong>Zip/Postal:</strong><div id="astrik5" style="float: right; font-size: 17px; color:#f00; display: none;">*</div></td>
			<td><input name="zipcode" id ="zipcode" type="text" value="<!zipcode>" /></td>
		</tr>
		<tr>
			<td><strong>Age:</strong><div id="astrik4" style="float: right; font-size: 17px; color:#f00; display: none;">*</div></td>
			<td><input name="age" id="age" type="text" /></td>
			<td><strong>Birth Date:</strong></td>
			<td><select name="bir_month" id="bir_month" class="month">
				<option>Month</option><!selectMonthOption>
			</select> <select name="bir_date" id="bir_date" class="date" style="font-weight: normal !important;">
				<option>Date</option><!selectDateOption>
			</select> <select name="bir_year" id="bir_year" class="year">
				<option>Year</option><!selectYearOption>
			</select></td>
		</tr>
		<tr>
			<td><strong>Tel Home:</strong> </td>
			<td><input name="tel_home" type="text" value="<!tel_home>" /></td>
			<td><strong>Tel Cell:</strong></td>
			<td><input name="tel_cell" type="text" value="<!tel_cell>" /></td>
		</tr>
		
		<tr>
			<td valign="top"><strong>Major health concerns, diagnoses (current and past):</strong></td>
			<td><textarea name="health_concerns"></textarea></td>
            <td><strong>Occupation:</strong> </td>
			<td><input name="occupation" type="text" /></td>
		</tr>
	
				<tr>
					<td style="width:200px;"><strong>Date of last visit to doctor:</strong></td>
					<td><input name="last_visit" type="text" /></td>
					<td style="width:200px;"><strong>Reason for last visit to doctor: </strong> </td>
					<td><input name="reason_last_visit" type="text" /></td>
				</tr>
				<tr>
					<td><strong>Any tests (blood, x-ray etc):</strong></td>
					<td><input name="tests" type="text" /></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Allergies:</strong></td>
					<td class="radio"><input name="allergies" type="radio" value="yes"/> Yes <input name="allergies" type="radio" value="no" checked="checked" /> No</td>
					<td><strong>Specify:</strong></td>
					<td ><input  name="specify" style="width:50px;" /> &nbsp; Energy  (1 low, 10 high)</td>
				</tr>
				<tr>
					<td><strong>Current Medications (Specify name and dosages)</strong></td>
					<td><textarea name="medications"></textarea></td>
					<td><strong>Current vitamins, supplements and therapies (Specify name and dosages):</strong></td>
					<td><textarea name="doseges"></textarea></td>				</tr>
				
				<tr>
					<td ><strong>Children only - Mom's name:</strong></td>
					<td><input name="mom_name" type="text" /></td>
					<td ><strong>Dad's name:</strong> </td>
					<td><input name="dad_name" type="text" /></td>
				</tr>
				<tr>
					<td style="width:200px;"><strong>Previous illness(es):</strong></td>
					<td><input name="pre_illness" type="text" /></td>
					<td ><strong>Immunizations   :</strong> </td>
					<td class="radio"><input name="immunizations" type="radio" value="yes" checked="checked" /> Yes <input name="immunizations" type="radio" value="no" /> No</td>
				</tr>
<tr>
					<td style="width:200px;"><strong>How did you hear us?  </strong></td>
					<td><input name="hear_abt_us" type="text" /></td>
					<td ><strong>If referred, who can we thank?  </strong> </td>
					<td ><input name="referred_person" type="text" /></td>
				</tr>

			</table>
		</div>
		<input type="hidden" name="action" value="fillbriefintakepaperwork" />
		<div align="center" class="form-footer">
		<input type="Button" onclick="return close_gb();" value="Submit"  /> &nbsp;
		<input name="Button" type="button" value="Close" onclick="parent.parent.GB_hide()" /></div>
</form>
</div>

</body>
