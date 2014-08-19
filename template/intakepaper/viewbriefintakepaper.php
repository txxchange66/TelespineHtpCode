<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<link href="/css/intake_form.css" rel="stylesheet" type="text/css"/>  
<script src="js/jquery.js"></script>  
<script language="JavaScript" src="js/countryState.js"></script> 
<script type="text/javascript">
$(document).ready(function () {
    var gender_id = '<!gender>';
    var allergies_id = '<!allergies>';
    var immunizations_id = '<!immunizations>';
    $("#"+ gender_id).attr('checked', 'checked');
    $("#"+ allergies_id).attr('checked', 'checked');
    $("#"+ immunizations_id).attr('checked', 'checked');
});
</script>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
</head>
<body>
<div class="wrapper">

	<table cellpadding="0" cellspacing="0" style="width: 100%">
		<tr>
			<td class="top-Name" style="width: 170px; " ><h2 style="font-size: 22px; font-family: Arial,Helvetica,sans-serif; font-weight: normal;text-align: left;"><!patientName></h2></td>
			<td style="width: 670px"><h1>BRIEF INTAKE FORM</h1></td>
			<td class="date" ><!IntakeCreatedDate><br/><a href="index.php?action=print_brief_intake_paperwork&printform=yes&patient_id=<!patient_id>" target="_blank"><img src="images/img-print.jpg" style="border:none;" /></a></td>
		</tr>
	</table>

<div style="height:20px;"></div>
<form name="briefintakeform" id="briefintakeform" action="index.php" method="post"> 
<div>
	<table cellpadding="0" cellspacing="0" style="width: 100%" class="form1-name">
		<tr>
			<td style="width:200px;"><strong>Name:</strong></td>
			<td><!patientName></td>
			<td style="width:200px;"><strong>Gender:</strong> </td>
			<td class="radio"><input name="gender" id="Male" type="radio" value="Male" /> Male <input name="gender" id="Female" type="radio" value="Female" /> Female</td>
		</tr>
		<tr>
			<td><strong>Street Address:</strong></td>
			<td><!address></td>
			<td><strong>City:</strong></td>
			<td><!city></td>
		</tr>
		<tr>
            <td><strong>Country:</strong></td>
            <td><select name="country" id="country" onchange="toggleState();">
                <!patient_country_options>
			</select></td>
			<td><strong>State/Province:</strong></td>
			<td><select name="province" id="state">
				<option>Choose State...</option>
                <!stateOption>
			</select></td>
		</tr>
		<tr>
            <td><strong>Email (or parents email):</strong></td>
			<td><!patientEmailAddress></td>
			<td><strong>Zip/Postal:</strong></td>
			<td><!zipcode></td>
		</tr>
		<tr>
			<td><strong>Age:</strong></td>
			<td><!age></td>
			<td><strong>Birth Date:</strong></td>
			<td><select name="bir_month" class="month">
				<option>Month</option><!selectMonthOption>
			</select> <select name="bir_date" class="date" style="font-weight: normal !important;">
				<option>Date</option><!selectDateOption>
			</select> <select name="bir_year" class="year">
				<option>Year</option><!selectYearOption>
			</select></td>
		</tr>
		<tr>
			<td><strong>Tel Home:</strong> </td>
			<td><!tel_home></td>
			<td><strong>Tel Cell:</strong></td>
			<td><!tel_cell></td>
		</tr>
		
		<tr>
			<td valign="top"><strong>Major health concerns, diagnoses (current and past):</strong></td>
			<td><!health_concerns></td>
            <td><strong>Occupation:</strong> </td>
			<td><!occupation></td>
		</tr>
	
				<tr>
					<td style="width:200px;"><strong>Date of last visit to doctor:</strong></td>
					<td><!last_visit></td>
					<td style="width:200px;"><strong>Reason for last visit to doctor: </strong> </td>
					<td><!reason_last_visit></td>
				</tr>
				<tr>
					<td><strong>Any tests (blood, x-ray etc):</strong></td>
					<td><!tests></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Allergies:</strong></td>
					<td class="radio"><input name="allergies" id="allergies_yes" type="radio" value="yes"/> Yes <input name="allergies" id="allergies_no" type="radio" value="no" /> No</td>
					<td><strong>Specify:</strong></td>
					<td ><!specify> &nbsp; Energy  (1 low, 10 high)</td>
				</tr>
				<tr>
					<td><strong>Current Medications (Specify name and dosages)</strong></td>
					<td><!medications></td>
					<td><strong>Current vitamins, supplements and therapies (Specify name and dosages):</strong></td>
					<td><!doseges></td>				
                </tr>
				
				<tr>
					<td ><strong>Children only - Mom's name:</strong></td>
					<td><!mom_name></td>
					<td ><strong>Dad's name:</strong> </td>
					<td><!dad_name></td>
				</tr>
				<tr>
					<td style="width:200px;"><strong>Previous illness(es):</strong></td>
					<td><!pre_illness></td>
					<td ><strong>Immunizations   :</strong> </td>
					<td class="radio"><input name="immunizations" id="immunizations_yes" type="radio" value="yes" checked="checked" /> Yes <input name="immunizations" id="immunizations_no" type="radio" value="no" /> No</td>
				</tr>
                <tr>
					<td style="width:200px;"><strong>How did you hear us?  </strong></td>
					<td><!hear_abt_us></td>
					<td ><strong>If referred, who can we thank?  </strong> </td>
					<td ><!referred_person></td>
				</tr>

        </table>
    </div>	
</form>
</div>

</body>
