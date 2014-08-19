<link href="/css/intake_form.css" rel="stylesheet" type="text/css"/>  
<script src="js/jquery.js"></script>  
<script language="JavaScript" src="js/countryState.js"></script>
<script type="text/javascript" language="javascript">
    var data1={<!IntakeArrayData>};
    var patient_id='<!patient_id>';

    $(document).ready(function(){
           
     if(data1['in_retired']=='RTD')
    	$("#in_emp_id, #in_work_add_id,#in_occupation_id,#in_hpweek_id").attr("disabled","disabled");
    });


</script>
<script src="js/fill_intake_form.js"></script>  
<div id="msg" style="color:#f00; font-size:17px; font-weight: bold;" >*</div>
 


<form name="intakeform" id="intakeform" action="index.php" method="post" >

<div class="wrapper" style="padding-bottom:20px; padding-top:20px; padding-left:20px;">
<h1>ADULT INTAKE FORM</h1>
<!navigation_header>
<p><strong>Patient Privacy:</strong> Patient information will never be disclosed or sold to an individual or company. The information you provide herein is used soloely by us for administrative, diagnostic and/or treatment purposes, and will be treated in the strictest confidence.</p>
<div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form1-name">
        <tr>
            <td style="width:200px;"><strong>Name:</strong></td>
            <td><!patientName></td>
            <td style="width:200px;"><strong>Date:</strong></td>
            <td><!IntakeCreatedDate></td>
        </tr>
        <tr>
            <td><strong>Address:</strong></td>
            <td><input name="in_add" id="in_add_id" type="text" maxlength="254" value="<!address>" /></td>
            <td><strong>City:</strong></td>
            <td><input name="in_city" id="in_city_id" type="text" maxlength="50" value="<!city>" /></td>
        </tr>
        <tr>
         
            <td><strong>Country:</strong></td>
            <td><select name="in_country" id="country" class="country" onchange="toggleState();" name="clinic_country" tabindex="5" >		
		
	<!patient_country_options>
	  </select>
</td>		
           <td><strong>State/Province:</strong></td>
            <td>
                
                
                <select name="in_prov" id="state" >
						<option value="" >Choose State...</option>
							<!stateOption>
					 </select>
            </td>					
        </tr>
        <tr>
            <td><strong>Telephone:</strong> (Home)</td>
            <td><input name="in_tel" id="in_tel_id" type="text"  maxlength="15" value="<!tel_home>" /></td>
            <td><strong>Postal Code:</strong></td>
            <td><input name="in_postal" id="in_postal_id"  type="text" maxlength="8" value="<!zipcode>" /></td>
        </tr>
        <tr class="radioCustom">
            <td><strong>E-mail address:</strong></td>
            <td><!patientEmailAddress></td>
            <td><strong>Gender:</strong> </td>
            <td class="radio"><input name="in_gen" id="in_gen_id" type="radio"  value="F" <!in_genF>/> Female <input name="in_gen" id="in_gen" value="M" type="radio"  <!in_genM> /> Male</td>
        </tr>
        <tr>
            <td><strong>Age:</strong></td>
            <td><input name="in_age" id="in_age_id" type="text" maxlength="10" /></td>
            <td><strong>Date of Birth:</strong></td>
            <td><select name="in_dob_mon" id="in_dob_mon_id" class="month">
                <option value=''>Month</option>
                <!selectMonthOption>
            </select> <select name="in_dob_date" id="in_dob_date_id" class="years">
                <option value=''>Date</option>
                    <!selectDateOption>
            </select> <select name="in_dob_year" id="in_dob_year_id" class="years">
                <option value=''>Year</option>
                    <!selectYearOption>
            </select></td>
        </tr>
        <tr>
            <td valign="top"><strong>Education:</strong></td>
            <td><textarea name="in_edu" id="in_edu_id"></textarea></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>
    </div>
<div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form1-status">
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
            <td><input name="in_live" id="in_live_id" type="radio" value="PRT" /> Parents  </td>
            <td><input name="in_live" id="in_live_id" type="radio" value="CLN" /> Children  </td>
            <td><input name="in_live" id="in_live_id" type="radio" value="FRD" /> Friends</td>
            <td><input name="in_live" id="in_live_id" type="radio" value="ALN" /> Alone</td>
        </tr>
    </table>
        </div>
        
        <div>
            <table cellpadding="0" cellspacing="0" style="width: 100%" class="form1-name">
                <tr>
                    <td style="width:200px;"><strong>Occupation:</strong></td>
                    <td><input name="in_occupation" id="in_occupation_id" type="text"  maxlength="254" /></td>
                    <td style="width:200px;"><strong>Hours per week: </strong> </td>
                    <td class="check"><input  name="in_hpweek" id="in_hpweek_id" class="small" maxlength="5" /><input name="in_retired" id="in_retired_id" type="checkbox" class="check" value="RTD" /> <strong> Retired</strong></td>
                </tr>
                <tr>
                    <td><strong>Employer:</strong></td>
                    <td><input name="in_emp" id="in_emp_id" type="text" maxlength="50" /></td>
                    <td><strong>Work Address:</strong></td>
                    <td><input name="in_work_add" id="in_work_add_id" type="text" maxlength="254" /></td>
                </tr>
                <tr class="radioCustom">
                    <td><strong>How did you hear about us?</strong></td>
                    <td><input name="in_hear" id="in_hear_id" type="text" maxlength="254"/></td>
                    <td><strong>Have you any family<br/>
members that are<br/>
patients at us?</strong></td>
                    <td class="radio"><input name="in_fam_mem_pat" id="in_fam_mem_pat_id" type="radio" value="Y" /> Yes <input name="in_fam_mem_pat" id="in_fam_mem_pat_id" type="radio" value="N" /> No</td>
                </tr>
                <tr>
                    <td><strong>Name of next of kin or other<br/>
to contact in an emergency:</strong></td>
                    <td><input name="in_emer_name" id="in_emer_name_id" type="text" maxlength="254" /></td>
                    <td><strong>Relationship to you:</strong></td>
                    <td><input name="in_emer_rel" id="in_emer_rel_id" type="text" maxlength="254" /></td>
                </tr>
                <tr>
                    <td><strong>Phone Number:</strong></td>
                    <td><input name="in_emer_ph" id="in_emer_ph_id" type="text" maxlength="15"/></td>
                    <td><strong>Address:</strong></td>
                    <td><input name="in_emer_add" id="in_emer_add_id" type="text" maxlength="254"/></td>
                </tr>
            </table>
        </div>
        <input type="hidden" name="action" value="saveintakepaperwork" />
        <input type="hidden" name="intake_last_page" value="1" />
        <input type="hidden" name="intake_compl_status" value="0" />
        <input type="hidden" name="nextPage" id="nextPage" value="1" />        
        <input type="hidden" name="closeChild" id="closeChild" value="1" />        
        
        
        <div align="center" class="form-footer"><input name="Button" type="submit" value="Save & Exit" /> &nbsp;<input name="Button" type="button" value="Next" style="width:65px" onclick="return validate('intakeform', '2');"
/></div>
</div>
</form>
