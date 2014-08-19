<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" language="javascript">
	var data1={<!IntakeArrayData>};
	var patient_id='<!patient_id>';
</script>
<script type="text/javascript" language="javascript" src= "js/intake_form.js"></script>
<link href="/css/intake_form.css" rel="stylesheet" type="text/css"/>
<style>
.form1-name tr td input { border:none;}
</style>
<div id="msg" style="border:#333 1px solid; font-size:10px; background:#ccc; padding:2px 5px; color:#000; display:none;position:absolute;">Please Answer this question.</div>
<form name="intakeform" id="intakeform" action="" method="post" onsubmit="return validate();" >
<div class="wrapper">

	<table cellpadding="0" cellspacing="0" style="width: 100%">
		<tr>
			<td style="width: 200px"><span class="top-Name"><!patientName></span></td>
			<td style="width: 630px;"><h1>ADULT INTAKE FORM</h1></td>
			<td class="date" ><!IntakeCreatedDate><br/><a href="index.php?action=view_intake_paperwork&printform=yes&patient_id=<!patient_id>" target="_blank" ><img src="images/img-print.jpg" style="border:none;" /></a></td>
		</tr>
	</table>

<!navigation_header>
<p><strong>Patient Privacy:</strong> Patient information will never be disclosed or sold to an individual or company. The information you provide herein is used soloely by us for administrative, diagnostic and/or treatment purposes, and will be treated in the strictest confidence.</p>
<div>
    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%" class="form1-name">
	<tr>
            <td style="width:200px;"><strong>Name:</strong></td>
            <td style="width:200px;"><!patientName></td>
            <td style="width:200px;"><strong>Date:</strong></td>
            <td><!IntakeCreatedDate></td>
        </tr>
        <tr>
            <td><strong>Address:</strong></td>
            <td ><input name="in_add" id="in_add_id" type="text" /><div id="div_in_add_id"></div></td>
            <td><strong>City:</strong></td>
            <td><input name="in_city" id="in_city_id" type="text" /><div id="div_in_city_id"></div></td>
        </tr>
        <tr>
	            <td><strong>Country:</strong></td>
            <td><select name="in_country" id="country" class="country" style="width: 200px !important;" onchange="toggleState();" name="clinic_country" tabindex="5" >		
		
			<!patient_country_options>
			  </select>
				</td>	
<td><strong>State/Province:</strong></td>
            <td>
                <select name="in_prov" id="in_prov_id">
                    
                    <!stateOption>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Telephone:</strong> (Home)</td>
            <td><input name="in_tel" id="in_tel_id" type="text" /><div id="div_in_tel_id"></div></td>
           <td><strong>Postal Code:</strong></td>
            <td><input name="in_postal" id="in_postal_id" type="text" /><div id="div_in_postal_id"></div></td>
        </tr>
        <tr class="radioCustom">
            <td><strong>E-mail address:</strong></td>
            <!--     email will be q9       -->
            <td><!patientEmailAddress></td>
            <td><strong>Gender:</strong> </td>
            <td class="radio"><input name="in_gen" id="in_gen_id" type="radio"  value="F" /> Female <input name="in_gen" id="in_gen" value="M" type="radio" /> Male</td>
        </tr>
        <tr>
            <td><strong>Age:</strong></td>
            <td><input name="in_age" id="in_age_id" type="text" /><div id="div_in_age_id"></div></td>
            <td><strong>Date of Birth:</strong></td>
            <td><select name="in_dob_mon" id="in_dob_mon_id" class="month">
                <option value=''>Month</option>
                <!selectMonthOption>
            </select> <select name="in_dob_date" id="in_dob_date_id" class="year">
                <option value=''>Date</option>
                    <!selectDateOption>
            </select> <select name="in_dob_year" id="in_dob_year_id" class="year">
                <option value=''>Year</option>
                    <!selectYearOption>
            </select></td>
        </tr>
        <tr>
            <td valign="top"><strong>Education:</strong></td>
            <td><textarea name="in_edu" id="in_edu_id"></textarea><div id="div_in_edu_id"></div></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>
    </div>
<div>
    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%" class="form1-status">
        <tr class="radioCustom">
            <td style="width:198px;"><strong>Marital Status:</strong></td>
            <td><input name="in_marital" id="in_marital_id" type="radio" value="MRD" /> Married </td>
            <td><input name="in_marital" id="in_marital_id" type="radio" value="SRD" /> Separated</td>
            <td><input name="in_marital" id="in_marital_id" type="radio" value="DVD" /> Divorced </td>
            <td><input name="in_marital" id="in_marital_id" type="radio" value="WDD"/> Widowed</td>
            <td><input name="in_marital" id="in_marital_id" type="radio" value="SNG" /> Single</td>
            <td><input name="in_marital" id="in_marital_id" type="radio" value="PRP" /> Partnership</td>
        </tr>
        <tr class="radioCustom">
            <td><strong>Live with:</strong></td>
            <td><input name="in_live" id="in_live_id" type="radio" value="SPC" /> Spouse </td>
            <td><input name="in_live" id="in_live_id" type="radio" value="PRT" /> Partner</td>
            <td><input name="in_live" id="in_live_id" type="radio" value="PRNT" /> Parents  </td>
            <td><input name="in_live" id="in_live_id" type="radio" value="CLN" /> Children  </td>
            <td><input name="in_live" id="in_live_id" type="radio" value="FRD" /> Friends</td>
            <td><input name="in_live" id="in_live_id" type="radio" value="ALN" /> Alone</td>
        </tr>
    </table>
        </div>
        
        <div>
            <table cellpadding="0" cellspacing="0" style="width: 100%"  border="0" class="form1-name">
                <tr>
                    <td style="width:200px;"><strong>Occupation:</strong></td>
                    <td style="width:200px;"><input name="in_occupation" id="in_occupation_id" type="text" /><div id="div_in_occupation_id"></div></td>
                    <td style="width:200px;"><strong>Hours per week: </strong> </td>
                    <td class="check"><input  name="in_hpweek" id="in_hpweek_id" class="small" type="text" /><span id="div_in_hpweek_id"></span>&nbsp;&nbsp;<input name="in_retired" id="in_retired_id" type="checkbox" class="check" value="RTD" /> <strong> Retired</strong></td>
                </tr>
                <tr>
                    <td><strong>Employer:</strong></td>
                    <td><input name="in_emp" id="in_emp_id" type="text" /><div id="div_in_emp_id"></div></td>
                    <td><strong>Work Address:</strong></td>
                    <td><input name="in_work_add" id="in_work_add_id" type="text" /><div id="div_in_work_add_id"></div></td>
                </tr>
                <tr class="radioCustom">
                    <td><strong>How did you hear about us?</strong></td>
                    <td><input name="in_hear" id="in_hear_id" type="text" /><div id="div_in_hear_id"></div></td>
                    <td><strong>Have you any family<br/>
members that are<br/>
patients at us?</strong></td>
                    <td class="radio"><input name="in_fam_mem_pat" id="in_fam_mem_pat_id" type="radio" value="Y" /> Yes <input name="in_fam_mem_pat" id="in_fam_mem_pat_id" type="radio" value="N" /> No</td>
                </tr>
                <tr>
                    <td><strong>Name of next of kin or other<br/>
to contact in an emergency:</strong></td>
                    <td><input name="in_emer_name" id="in_emer_name_id" type="text" /><div id="div_in_emer_name_id"></div></td>
                    <td><strong>Relationship to you:</strong></td>
                    <td><input name="in_emer_rel" id="in_emer_rel_id" type="text" /><div id="div_in_emer_rel_id"></div></td>
                </tr>
                <tr>
                    <td><strong>Phone Number:</strong></td>
                    <td><input name="in_emer_ph" id="in_emer_ph_id" type="text" /><div id="div_in_emer_ph_id"></div></td>
                    <td><strong>Address:</strong></td>
                    <td><input name="in_emer_add" id="in_emer_add_id" type="text" /><div id="div_in_emer_add_id"></div></td>
                </tr>
            </table>
        </div>
        
        
        <div align="center" class="form-footer"></div>
</div>
</form>