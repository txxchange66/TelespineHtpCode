<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" language="javascript">
	var data1={<!IntakeArrayData>};
	var patient_id='<!patient_id>';
	</script>
<script type="text/javascript" language="javascript" src= "js/intake_form.js"></script>
<link href="css/intake_form.css" rel="stylesheet" type="text/css"/>
<style>
input { border:none;}
select { border:none;border:1px solid #fff;}
textarea { border:none;}
</style>
<div class="wrapper">

	<table cellpadding="0" cellspacing="0" style="width: 100%">
		<tr>
			<td style="width: 200px"><span class="top-Name"><!patientName></span></td>
			<td style="width: 638px;"><h1>ADULT INTAKE FORM</h1></td>
			<td class="date" ><!IntakeCreatedDate></a></td>
		</tr>
	</table>
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
            <td ><input name="in_add" id="in_add_id" type="text" /><div id="div_in_add_id"></div></td>
            <td><strong>City:</strong></td>
            <td><input name="in_city" id="in_city_id" type="text" /><div id="div_in_city_id"></div></td>
        </tr>
        <tr>
             <td><strong>Country:</strong></td>
            <td><select name="in_country" id="country" class="country" onchange="toggleState();" name="clinic_country" tabindex="5" >		
		
					<!patient_country_options>
	  			</select>
				</td>		
            <td><strong>State/Province:</strong></td>
            <td>
                <select name="in_prov" id="in_prov_id">
                    <option value="">Select State</option>
                    <!stateOption>
                </select>
            </td></td>
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
            <td><input name="in_live" id="in_live_id" type="radio" value="PRNT" /> Parents  </td>
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
                    <td><input name="in_occupation" id="in_occupation_id" type="text" /><div id="div_in_occupation_id"></div></td>
                    <td style="width:200px;"><strong>Hours per week: </strong> </td>
                    <td class="check"><input  name="in_hpweek" id="in_hpweek_id" class="small" type="text" /><span id="div_in_hpweek_id"></span><input name="in_retired" id="in_retired_id" type="checkbox" class="check" value="RTD" /> <strong> Retired</strong></td>
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
<div class="wrapper">
<h2>CONTEXT OF CARE REVIEW</h2>
<p>Successful health care and preventive medicine are only possible when the physician has a complete understanding of the patient physically, mentally and emotionally. The nature of your responses to the following questions will go along way in assisting our understanding of your truest desires. Your time, thoughtfulness and honesty in completing this overview will greatly aid us to assist your health needs.</p>
<div>
    
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form2-name">
        <tr>
            <td><strong>Why did you choose to come to us?</strong></td>
        </tr>
        <tr>
            <td><input name="in_w_cm" id="in_w_cm_id" type="text" /><div id="div_in_w_cm_id"></div></td>
        </tr>
        <tr>
            <td><strong>What do you know of our approach to wellness?</strong></td>
        </tr>
        <tr>
            <td><input name="in_w_appr" id="in_w_appr_id" type="text" /><div id="div_in_w_appr_id"></div></td>
        </tr>
        <tr>
            <td><strong>What three (3) expectations do you have from this visit?</strong></td>
        </tr>
        <tr>
            <td><input name="in_w_expct_1" id="in_w_expct_1_id" type="text"/><div id="div_in_w_expct_1_id"></div></td>
        </tr>
        <tr><td><input name="in_w_expct_2" id="in_w_expct_2_id" type="text"/><div id="div_in_w_expct_2_id"></div></td></tr>
        <tr><td><input name="in_w_expct_3" id="in_w_expct_3_id" type="text"/><div id="div_in_w_expct_3_id"></div></td></tr>
        <tr>
            <td><strong>What long term expectations do you have of our doctor?</strong></td>
        </tr>
        <tr>
            <td><input name="in_w_term" id="in_w_term_id" type="text"/><div id="div_in_w_term_id"></div></td>
        </tr>
    </table>
    
    </div>
<div>
    
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form2-status">
        <tr>
            <td colspan="10" ><strong>What is your present level of commitment to address any underlying causes of your signs/symptoms that relate to your lifestyle?</strong><br/><i>(Rate from 0 to 10 with 10 being 100% committed)</i></td>
            
        </tr>
        <tr><td><table cellpadding="0" cellspacing="0" style="width: 75%" class="form2-status">
        <tr class="radioCustom">
            <td ><input name="in_lev_comit" id="in_lev_comit_id" type="radio" value="1" /> 1</td>
            <td ><input name="in_lev_comit" id="in_lev_comit_id" type="radio" value="2" /> 2</td>
            <td  ><input name="in_lev_comit" id="in_lev_comit_id"  type="radio" value="3" /> 3</td>
            <td  ><input name="in_lev_comit" id="in_lev_comit_id"  type="radio" value="4" /> 4</td>
            <td ><input name="in_lev_comit"  id="in_lev_comit_id" type="radio" value="5" /> 5</td>
            <td ><input name="in_lev_comit"  id="in_lev_comit_id" type="radio" value="6" /> 6</td>
            <td  ><input name="in_lev_comit"  id="in_lev_comit_id" type="radio" value="7" /> 7</td>
            <td  ><input name="in_lev_comit"  id="in_lev_comit_id" type="radio" value="8" /> 8</td>
            <td><input name="in_lev_comit"  id="in_lev_comit_id" type="radio" value="9" /> 9</td>
            <td ><input name="in_lev_comit"  id="in_lev_comit_id" type="radio" value="10" /> 10</td>
            </tr></table>
        </td></tr>
    </table>
    
</div>
    <div>
    
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form2-name">
        <tr>
            <td><strong>What behaviors / habits do you currently engage in regularly that you believe support your health?</strong></td>
        </tr>
        <tr>
            <td><input name="in_behave_s"  id="in_behave_id_s" type="text" /><div id="div_in_behave_id_s"></div></td>
        </tr>
        <tr>
            <td><strong>What behaviors / habits do you currently engage in regularly that you believe are self-destructive?</strong></td>
        </tr>
        <tr>
            <td><input name="in_behave_d" id="in_behave_d_id" type="text" /><div id="div_in_behave_d_id"></div></td>
        </tr>
        <tr>
            <td><strong>What potential obstacles do you foresee in addressing the lifestyle factors which are undermining your health and in<br/>
adhering to the therapeutic protocols which we will be sharing with you?</strong></td>
        </tr>
        <tr>
            <td><input name="in_pot_obst" id="in_pot_obst_id" type="text" /><div id="div_in_pot_obst_id"></div></td>
        </tr>
        
        
        <tr>
            <td><strong>Who will sincerely support you consistently with the beneficial lifestyle changes you will be making?</strong></td>
        </tr>
        <tr>
            <td><input name="in_support" id="in_support_id" type="text" /><div id="div_in_support_id"></div></td>
        </tr>
    </table>
    
    </div>
    
        
     
        
        <div align="center" class="form-footer"></div>
</div>
<form name="intakeform" id="intakeform" action="" method="post" onsubmit="return validate();" >
<div class="wrapper">
<p>Wellness is a balance of many factors. By choosing a number in each scale, rate your level of satisfaction in each area of your life. For example, if you are extremely happy with your career, choose 9 or 10. Do the same for each area of your life.</p>

<div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form3">
        <tr class="radioCustom">
            <td align="center">
				<!graphData>
			</td>
        </tr>
</table>
        </div>
    <div>
    <div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form3-bot">
        <tr>
            <td class="radio"><strong>Are you currently receiving health care?</strong> <input name="in_cur_hcare" id="in_3_9_id" type="radio" value="y" /> Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="in_cur_hcare" id="in_3_9_id" type="radio" value="n" /> No</td>
        </tr>
        
        <tr>
            <td><strong>If yes, where and from whom:</strong></td>
        </tr>
        <tr>
            <td><textarea name="in_cur_hcreY" id="in_cur_hcreY_id" ></textarea><div id="div_in_cur_hcreY_id"></div></td>
        </tr>
        <tr>
            <td><strong>If no, when and where did you last receive medical or health care?</strong></td>
        </tr>
        <tr>
            <td><textarea name="in_cur_hcreN" id="in_cur_hcreN_id" ></textarea><div id="div_in_cur_hcreN_id"></div></td>
        </tr>
        
        
        <tr>
            <td><strong>What was the reason?</strong></td>
        </tr>
        <tr>
        <td><textarea name="in_reason" id="in_reason_id" ></textarea><div id="div_in_reason_id"></div></td>
        </tr>
        <tr>
            <td><strong>What are your most important health problems? List as many as you can in order of importance:</strong></td>
        </tr>
        <tr>
            <td><input name="in_h_prob_1" id="in_h_prob_1_id" type="text" /><div id="div_in_h_prob_1_id"></div></td>
        </tr>
<tr>
            <td><input name="in_h_prob_2" id="in_h_prob_2_id" type="text" /><div id="div_in_h_prob_2_id"></div></td>
        </tr>
<tr>
            <td><input name="in_h_prob_3" id="in_h_prob_3_id" type="text" /><div id="div_in_h_prob_3_id"></div></td>
        </tr>
<tr>
            <td><input name="in_h_prob_4" id="in_h_prob_4_id" type="text" /><div id="div_in_h_prob_4_id"></div></td>
        </tr>
<tr>
            <td><input name="in_h_prob_5" id="in_h_prob_5_id" type="text" /><div id="div_in_h_prob_5_id"></div></td>
        </tr>
<tr>
            <td><input name="in_h_prob_6" id="in_h_prob_6_id" type="text" /><div id="div_in_h_prob_6_id"></div></td>
        </tr>
<tr>
            <td><input name="in_h_prob_7" id="in_h_prob_7_id" type="text" /><div id="div_in_h_prob_7_id"></div></td>
        </tr>

<tr>
            <td class="radio"><strong>Do you have any known contagious diseases at this time?</strong> <input name="in_knwn_dis" id="in_knwn_dis_id"  type="radio" value="Y" /> Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="in_knwn_dis" id="in_knwn_dis_id" type="radio" value="N" /> No</td>
        </tr>
<tr>
            <td><strong>If yes, what?</strong></td>
        </tr>
        <tr>
            <td><textarea name="in_knw_dis_w" id="in_knw_dis_w_id"></textarea><div id="div_in_knw_dis_w_id"></div></td>
        </tr>

    </table>
    
    </div>
    
   
        
        <div align="center" class="form-footer"></div>
</div>
<div class="wrapper">
<h3>FAMILY HISTORY</h3>

<div style="height:10px;"></div>
    <div>
    
    
    
        <table cellpadding="0" cellspacing="0" style="width: 100%" class="form4-history">
            <tr>
                <td colspan="5"><strong>Do you have a family history of any of the following?</strong></td>
                
            </tr>
            <tr>
                <td><input name="in_his_cancer" value="Cancer" id="in_his_cancer_id" type="checkbox" value="Cancer" /> Cancer  </td>
                <td><input name="in_his_diabetes" value="Diabetes" id="in_his_diabetes_id" type="checkbox" value="Diabetes" /> Diabetes </td>
                <td><input name="in_his_heart_disease" value="Heart Disease" id="in_his_heart_disease_id" type="checkbox" value="Heart Disease" /> Heart Disease </td>
                <td><input name="in_his_high_blood_pressure" value="High Blood Pressure" id="in_his_high_blood_pressure_id" type="checkbox" value="High Blood Pressure" /> High Blood Pressure </td>
                <td><input name="in_his_kidney_disease" value="Kidney Disease" id="in_his_kidney_disease_id" type="checkbox" value="Kidney Disease" /> Kidney Disease</td>
            </tr>
            <tr>
                <td><input name="in_his_epilepsy" value="Epilepsy" id="in_his_epilepsy_id" type="checkbox" value="Epilepsy" /> Epilepsy </td>
                <td><input name="in_his_arthritis" value="Arthritis" id="in_his_arthritis_id" type="checkbox" value="Arthritis" /> Arthritis </td>
                <td><input name="in_his_glaucoma" value="Glaucoma" id="in_his_glaucoma_id" type="checkbox" value="Glaucoma" /> Glaucoma  </td>
                <td><input name="in_his_tuberculosis" value="Tuberculosis" id="in_his_tuberculosis_id" type="checkbox" value="Tuberculosis" /> Tuberculosis </td>
                <td><input name="in_his_stroke" value="Stroke" id="in_his_stroke_id" type="checkbox" value="Stroke" /> Stroke </td>
            </tr>
            <tr>
                <td><input name="in_his_anaemia" value="Anaemia" id="in_his_anaemia_id" type="checkbox" value="Anaemia" /> Anaemia </td>
                <td><input name="in_his_mental_illness" value="Mental Illness" id="in_his_mental_illness_id" type="checkbox"  value="Mental Illness"/> Mental Illness </td>
                <td><input name="in_his_asthama" value="Asthama" id="in_his_asthama_id" type="checkbox" value="Asthama" /> Asthama</td>
                <td><input name="in_his_hay_fever" value="Hay Fever" id="in_his_hay_fever_id" type="checkbox" value="Hay Fever" /> Hay Fever </td>
                <td><input name="in_his_hives" value="Hives" id="in_his_hives_id" type="checkbox" value="Hives" />  Hives</td>
            </tr>
            <tr>
                <td colspan="5"><strong>Any other relevant family history?</strong></td>
                
            </tr>
            <tr>
                <td colspan="5"><textarea  name="in_his_other" id="in_his_other_id" ></textarea><div id="div_in_his_other_id"></div></td>
                
            </tr>
            <tr>
                <td colspan="5"><strong>What is your heritage:</strong></td>
                
            </tr>
            <tr>
               <td><input  name="in_heritage" id="in_heritage_id" type="text" maxlength="254" /><div id="div_in_heritage_id"></div></td> 
            </tr>
            <!--<tr class="radioCustom">
                <td><input name="in_heritage" id="in_heritage_id"  type="radio" value="German" /> German</td>
                <td><input name="in_heritage" id="in_heritage_id"  type="radio" value="Nordic" /> Nordic </td>

                <td><input name="in_heritage" id="in_heritage_id"  type="radio" value="Celtic" /> Celtic  </td>
                <td><input name="in_heritage" id="in_heritage_id"  type="radio" value="Other" /> Other</td>
                <td>&nbsp;</td>
            </tr>-->
        </table>
</div>
    <h3>CHILDHOOD ILLNESS</h3>
    <div>
    <div style="height:10px;"></div>
        <table cellpadding="0" cellspacing="0" style="width: 100%" class="form4-history">
            <tr>
                <td><input name="in_ill_sca" id="in_ill_sca_id" type="checkbox" value="Scarlet Fever" /> Scarlet Fever</td>
                <td><input name="in_ill_dip" id="in_ill_dip_id"  type="checkbox" value="Diphtheria" /> Diphtheria</td>
                <td><input name="in_ill_rheu" id="in_ill_rheu_id" type="checkbox" value="Rheumatic Fever" /> Rheumatic Fever</td>
            </tr>
            <tr>
                <td><input name="in_ill_mumps" id="in_ill_mumps_id" type="checkbox" value="Mumps" /> Mumps</td>
                <td><input name="in_ill_measls" id="in_ill_measls_id" type="checkbox" value="Measles" /> Measles</td>
                <td><input name="in_ill_gmeasls" id="in_ill_gmeasls_id" type="checkbox" value="German Measles" /> German Measles </td>
            </tr>
            
        </table>
    </div>    
        <h3>HOSPITALIZATION, SURGERY, IMAGING</h3>
        <div>
        <div style="height:10px;"></div>
            <table cellpadding="0" cellspacing="0" style="width: 100%" class="form4-hospital">
                <tr>
                    <td colspan="4">What hospitalizations, surgeries, X-Rays, CAT Scans, EEG, EKG's have you had?</td>
                    
                </tr>
                <tr>
                    <td style="width:320px;"><input name="in_had_surg1" id="in_had_surg1_id" type="text" /><div id="div_in_had_surg1_id"></div></td>
                    <td style="width:130px;">
                    <select name="in_had_surg1y" id="in_had_surg1y_id" class="year">
                        <option>Year</option>
                        <!selectReportYearOption>
                    </select> 
                    </td>
                    <td style="width:320px;"><input  name="in_had_surg2" id="in_had_surg2_id" type="text" /><div id="div_in_had_surg2_id"></div></td>
                    <td>
                    <select  name="in_had_surg2y" id="in_had_surg2y_id"  class="year" >
                        <option value=''>Year</option>
                        <!selectReportYearOption>
                    </select></td>
                </tr>
                <tr>
                    <td><input  name="in_had_surg3" id="in_had_surg3_id" type="text" /><div id="div_in_had_surg3_id"></div></td>
                    <td><select  name="in_had_surg3y" id="in_had_surg3y_id" class="year" >
                            <option value=''>Year</option>
                            <!selectReportYearOption>                
            </select></td>
                    <td><input  name="in_had_surg4" id="in_had_surg4_id" type="text" /><div id="div_in_had_surg4_id"></div></td>
                    <td><select  name="in_had_surg4y" id="in_had_surg4y_id"  class="year">
                <option>Year</option>
                <!selectReportYearOption>
            </select></td>
                </tr>
                <tr>
                    <td><input  name="in_had_surg5" id="in_had_surg5_id" type="text" /><div id="div_in_had_surg5_id"></div></td>
                    <td><select name="in_had_surg5y" class="year" >
                        <option>Year</option>
                        <!selectReportYearOption>                
            </select></td>
                    <td><input  name="in_had_surg6" id="in_had_surg6_id" type="text" /><div id="div_in_had_surg6_id"></div></td>
                    <td><select  name="in_had_surg6y" id="in_had_surg6y_id" class="year">
                        <option>Year</option>
                        <!selectReportYearOption>                
            </select></td>
                </tr>
            </table>
    </div>
    
    <h3>ALLERGIES</h3>
    
    <div style="height:10px;"></div>
    <div>
        <table cellpadding="0" cellspacing="0" style="width: 100%" class="form2-name">
            <tr>
                <td><strong>Are you hypersensitive or allergic to any drugs?</strong></td>
            </tr>
            <tr>
                <td><input  name="in_allergy_drugs" id="in_allergy_drugs_id" type="text" /><div id="div_in_allergy_drugs_id"></div></td>
            </tr>
            <tr>
                <td><strong>Any foods?</strong></td>
            </tr>
            <tr>
                <td><input name="in_allergy_food" id="in_allergy_food_id" type="text" /><div id="div_in_allergy_food_id"></div></td>
            </tr>
            <tr>
                <td><strong>Any environmental influences or chemicals?</strong></td>
            </tr>
            <tr>
                <td><input name="in_env_influ" id="in_env_influ_id" type="text" /><div id="div_in_env_influ_id"></div></td>
            </tr>
        </table>
    </div>
    <h3>CURRENT MEDICATIONS</h3>
    <div style="height:10px;"></div>
    <div>
        <table cellpadding="0" cellspacing="0" style="width: 100%" class="form1-status">
            <tr>
                <td colspan="3"><strong>Do you take or use?</strong></td>
                
            </tr>
            <tr>
                <td style="width:270px;"><strong>Laxatives</strong></td>
                <td style="width:270px;"><strong>Pain relievers </strong></td>
                <td><strong>Antacids</strong></td>
            </tr>
            <tr>
                <td  class="radioCustom"><input name="in_take_lax" id="in_take_lax_id" type="radio" value="Y" /> Yes &nbsp;&nbsp;&nbsp; <input name="in_take_lax" id="in_take_lax_id" type="radio" value="N" /> No</td>
                <td class="radioCustom"><input name="in_take_pain" id="in_take_pain_id"  type="radio" value="Y" /> Yes &nbsp;&nbsp;&nbsp; <input name="in_take_pain" id="in_take_pain_id"  type="radio" value="N" /> No</td>
                <td class="radioCustom"><input name="in_take_antacd" id="in_take_antacd_id"  type="radio" value="Y" /> Yes &nbsp;&nbsp;&nbsp; <input name="in_take_antacd" id="in_take_antacd_id" type="radio" value="N" /> No</td>
            </tr>
            <tr>
                <td><strong>Cortisone</strong></td>
                <td><strong>Appetite suppressants</strong></td>
                <td><strong>Antibiotics</strong></td>
            </tr>
            <tr>
                <td  class="radioCustom"><input name="in_take_cort" id="in_take_cort_id"  type="radio" value="Y" /> Yes &nbsp;&nbsp;&nbsp; <input name="in_take_cort" id="in_take_cort_id" type="radio"  value="N" /> No</td>
                <td  class="radioCustom"><input name="in_take_appe" id="in_take_appe_id" type="radio" value="Y" /> Yes &nbsp;&nbsp;&nbsp; <input name="in_take_appe" id="in_take_appe_id" type="radio" value="N" /> No</td>
                <td  class="radioCustom"><input name="in_take_anti" id="in_take_anti_id" type="radio" value="Y" /> Yes &nbsp;&nbsp;&nbsp; <input name="in_take_anti" id="in_take_anti_id" type="radio" value="N" /> No</td>
            </tr>
            <tr>
                <td><strong>Tranquilizers </strong></td>
                <td><strong>Thyroid medication</strong></td>
                <td><strong>Sleeping pills</strong></td>
            </tr>
            <tr>
                <td class="radioCustom"><input name="in_take_tran" id="in_take_tran_id" type="radio" value="Y" /> Yes &nbsp;&nbsp;&nbsp; <input name="in_take_tran" id="in_take_tran_id" type="radio" value="N" /> No</td>
                <td  class="radioCustom"><input name="in_take_thyo" id="in_take_thyo_id" type="radio" value="Y" /> Yes &nbsp;&nbsp;&nbsp; <input name="in_take_thyo" id="in_take_thyo_id" type="radio" value="N" /> No</td>
                <td  class="radioCustom"><input name="in_take_sleep" id="in_take_sleep_id" type="radio" value="Y" /> Yes &nbsp;&nbsp;&nbsp; <input name="in_take_sleep" id="in_take_sleep_id" type="radio" value="N" /> No</td>
            </tr>
        </table>
    </div>
    <div>
    <div style="height:10px;"></div>
        <table cellpadding="0" cellspacing="0" style="width: 100%" class="form4-otc">
            <tr>
                <td colspan="2"><strong>Please list any prescription and OTC medications, vitamins or other supplements you are taking</strong></td>
                
            </tr>
            <tr>
                <td style="width:400px;"><input name="in_prescript_1" id="in_prescript_1_id" type="text" /><div id="div_in_prescript_1_id"></div></td>
                <td><input name="in_prescript_2" id="in_prescript_2_id" type="text" /><div id="div_in_prescript_2_id"></div></td>
            </tr>
            <tr>
                <td><input name="in_prescript_3" id="in_prescript_3_id" type="text" /><div id="div_in_prescript_3_id"></div></td>
                <td><input name="in_prescript_4" id="in_prescript_4_id" type="text" /><div id="div_in_prescript_4_id"></div></td>
            </tr>
            <tr>
                <td><input name="in_prescript_5" id="in_prescript_5_id" type="text" /><div id="div_in_prescript_5_id"></div></td>
                <td><input name="in_prescript_6" id="in_prescript_6_id" type="text" /><div id="div_in_prescript_6_id"></div></td>
            </tr>
        </table>
    </div>
       
                
        <div align="center" class="form-footer"></div>
</div>
<div class="wrapper">
<h3>GENERAL</h3>

<div style="height:10px;"></div>
    <div>
    
    
    
        
        <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5">
            <tr>
                <td style="width:150px;"><strong>Height:</strong></td>
                <td style="width:95px;"><input name="in_height" id="in_height_id" type="text" /><div id="div_in_height_id"></div> </td>
                <td style="width:95px;">inches</td>
                <td><strong>Weight:</strong></td>
                <td style="width:95px;"><input name="in_weight" id="in_weight_id" type="text" /><div id="div_in_weight_id"></div></td>
                <td style="width:95px;">lbs.</td>
                <td style="width:150px;"><strong>Weight 1 year ago:</strong></td>
                <td style="width:93px;"><input name="in_weight_one_year" id="in_weight_one_year_id" type="text" /><div id="div_in_weight_one_year_id"></div></td>
                <td>lbs.</td>
            </tr>
            <tr>
                <td><strong>Maximum Weight:</strong></td>
                <td><input name="in_max_weight" id="in_max_weight_id" type="text" /><div id="div_in_max_weight_id"></div></td>
                <td>lbs.</td>
                <td><strong>When:</strong></td>
                <td><input name="in_when" id="in_when_id" type="text" /><div id="div_in_when_id"></div></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>

            </tr>
            <tr>
                <td colspan="3"><strong>When during the day is your energy the best?</strong> </td>
                <td><input name="in_energy_b" id="in_energy_b_id" type="text" /><div id="div_in_energy_b_id"></div></td>
                <td colspan="5"><strong style="padding-right:15px;">Worst?</strong> <input name="in_energy_w" id="in_energy_w_id" type="text" /><span id="div_in_energy_w_id"></span></td>
                
                
            </tr>
        </table>
    
    
    
        
</div>
    <h3>TYPICAL FOOD INTAKE</h3>
    <div>
    <div style="height:10px;"></div>
        
        <table cellpadding="0" cellspacing="0" style="width: 100%" class="form4-hospital">
            <tr>
                <td style="width:100px;"><strong>Breakfast:</strong></td>
                <td><input name="in_bfast" id="in_bfast_id" type="text" /><div id="div_in_bfast_id"></div></td>
            </tr>
            <tr>
                <td><strong>Lunch:</strong></td>
                <td><input name="in_lunch" id="in_lunch_id" type="text" /><div id="div_in_lunch_id"></div></td>
            </tr>
            <tr>
                <td><strong>Dinner:</strong></td>
                <td><input name="in_dinner" id="in_dinner_id" type="text" /><div id="div_in_dinner_id"></div></td>
            </tr>
            <tr>
                <td><strong>Snacks:</strong></td>
                <td><input name="in_snacks" id="in_snacks_id" type="text" /><div id="div_in_snacks_id"></div></td>
            </tr>
            <tr>
                <td><strong>To drink:</strong></td>
                <td><input name="in_drink" id="in_drink_id" type="text" /><div id="div_in_drink_id"></div></td>
            </tr>
        </table>
        
    </div>    
        <h3>HABITS</h3>
        <div>
        <div style="height:10px;"></div>
            
            <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                <tr>
                    <td colspan="2"><strong>Main interests and hobbies?</strong></td>
                    
                </tr>
                <tr>
                    <td colspan="2"><input name="in_intrest" id="in_intrest_id" type="text" style="width:500px;" /><div id="div_in_intrest_id"></div></td>
                    
                </tr>
                <tr class="radioCustom">
                    <td style="width:200px;"><strong>Do you exercise?</strong></td>
                    <td class="radio"> <input name="in_exercise" id="in_exercise_id" type="radio" value="y" /> Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="in_exercise" id="in_exercise_id" type="radio" value="n" /> No</td>
                </tr>
                <tr>
                    <td><strong>If yes, what kind?</strong></td>
                    <td><input name="in_exercise_y" id="in_exercise_y_id" type="text" /><div id="div_in_exercise_y_id"></div></td>
                </tr>
                <tr>
                    <td><strong>How often?</strong></td>
                    <td><input name="in_exercise_h" id="in_exercise_h_id" type="text" /><div id="div_in_exercise_h_id"></div></td>
                </tr>
                <tr class="radioCustom">
                    <td><strong>Watch television?</strong></td>
                    <td class="radio"> <input name="in_tele" id="in_tele_id" type="radio" value="y" /> Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="in_tele" id="in_tele_id" type="radio" value="n" /> No</td>
                </tr>
                <tr>
                    <td><strong>How many hours?</strong></td>
                    <td ><input name="in_tele_h" id="in_tele_h_id" type="text" /><div id="div_in_tele_h_id"></div></td>
                </tr>
                <tr class="radioCustom">
                    <td><strong>Do you read?</strong></td>
                    <td class="radio"> <input name="in_read" id="in_read_id" type="radio" value="y" /> Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="in_read" id="in_read_id" type="radio" value="n" /> No</td>
                </tr>
                <tr>
                    <td ><strong>How many hours?</strong></td>
                    <td ><input name="in_read_h" id="in_read_h_id" type="text" /><div id="div_in_read_h_id"></div></td>
                </tr>
                <tr class="radioCustom">
                    <td colspan="2" class="radio"><strong>Do you have a religious or 
                    spiritual practice? </strong>&nbsp; <input name="in_religoius" id="in_religoius_id" type="radio" value="y" /> Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="in_religoius" id="in_religoius_id" type="radio" value="n" /> No</td>
                    
                </tr>
                <tr>
                    <td><strong>If yes, what?</strong></td>
                    <td><input name="in_religoius_y" id="in_religoius_y_id" type="text" /><div id="div_in_religoius_y_id"></div></td>
                </tr>
                <tr class="radioCustom">
                    <td><strong>Smoked previously?</strong></td>
                    <td class="radio"> <input name="in_smoked" id="in_smoked_id" type="radio" value="y" /> Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="in_smoked" id="in_smoked_id" type="radio" value="n" /> No</td>
                </tr>
                <tr>
                    <td><strong>How many years?</strong></td>
                    <td><input name="in_smoked_yrs" id="in_smoked_yrs_id" type="text" /><div id="div_in_smoked_yrs_id"></div></td>
                </tr>
                <tr>
                    <td><strong>How many cigarettes a day?</strong></td>
                    <td><input name="in_smoked_h" id="in_smoked_h_id" type="text" /><div id="div_in_smoked_h_id"></div></td>
                </tr>
            </table>
            
    </div>
    <div style="height:10px;"></div>
    <div class="form5-condition">
    
        <table cellpadding="0" cellspacing="0" style="width: 100%">
            <tr>
                <td>Y = A condition you have now  </td>
                <td> N = Never had </td>
                <td> P = Significant problem in the past</td>
            </tr>
        </table>
    
</div>
<div>               
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
             
            
           
            
<td style="width:290px;"><strong>Average 6-8 hours of sleep daily</strong></td>
            <td class="radio" style="width:55px;"><input name="in_sleep" id="in_sleep_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_sleep" id="in_sleep_id" type="radio" value="n" /> N</td>
            <td style="width:100px;">&nbsp;</td>
            <td  style="width:260px;"><strong>Enjoy your work</strong></td>
            <td class="radio" style="width:60px;"><input name="in_enj_work" id="in_enj_work_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_enj_work" id="in_enj_work_id" type="radio" value="n" /> N</td>
            <td>&nbsp;</td>            
            
                  
        </tr>
        <tr>
            <td><strong>Sleep well</strong></td>
            <td class="radio" style="width: 60px"><input name="in_sleep_well" id="in_sleep_well_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_sleep_well" id="in_sleep_well_id" type="radio" value="n" /> N</td>
            <td>&nbsp;</td>
            <td><strong>Take vacations</strong></td>
            <td class="radio"><input name="in_vacation" id="in_vacation_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_vacation" id="in_vacation_id" type="radio" value="n" /> N</td>
            <td>&nbsp;</td>
            
        </tr>
        <tr>
            <td><strong>Awaken rested</strong></td>
            <td class="radio" style="width: 60px"><input name="in_awake" id="in_awake_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_awake" id="in_awake_id" type="radio" value="n" /> N</td>
            <td>&nbsp;</td>
            <td><strong>Spend time outside</strong></td>
            <td class="radio"><input name="in_time_out" id="in_time_out_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_time_out" id="in_time_out_id" type="radio" value="n" /> N</td>
            <td>&nbsp;</td>
            
        </tr>
        <tr>
            <td><strong>Have a supportive relationship</strong></td>
            <td class="radio" style="width: 60px"><input name="in_supp_rel" id="in_supp_rel_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_supp_rel" id="in_supp_rel_id" type="radio" value="n" /> N</td>
            <td>&nbsp;</td>
            <td><strong>Eat 3 meals a day</strong></td>
            <td class="radio"><input name="in_eat_meal" id="in_eat_meal_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_eat_meal" id="in_eat_meal_id" type="radio" value="n" /> N</td>
            <td>&nbsp;</td>
            
        </tr>
        <tr>
            <td><strong>Have a history of abuse</strong></td>
            <td class="radio" style="width: 60px"><input name="in_his_abuse" id="in_his_abuse_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_his_abuse" id="in_his_abuse_id" type="radio" value="n" /> N</td>
            <td>&nbsp;</td>
            <td><strong>Go on diets often</strong></td>
            <td class="radio"><input name="in_go_diet" id="in_go_diet_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_go_diet" id="in_go_diet_id" type="radio" value="n" /> N</td>
            <td>&nbsp;</td>
            
        </tr>
        <tr>
            <td><strong>Experienced major traumas</strong></td>
            <td class="radio" style="width: 60px"><input name="in_maj_trauma" id="in_maj_trauma_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_maj_trauma" id="in_maj_trauma_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_maj_trauma" id="in_maj_trauma_id" type="radio" value="p" /> P</td>
            <td><strong>Eat out often</strong></td>
            <td class="radio"><input name="in_eat_out" id="in_eat_out_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_eat_out" id="in_eat_out_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_eat_out" id="in_eat_out_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Have used recreational drugs</strong></td>
            <td class="radio" style="width: 60px"><input name="in_re_drug" id="in_re_drug_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_re_drug" id="in_re_drug_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_re_drug" id="in_re_drug_id" type="radio" value="p" /> P</td>
            <td><strong>Drink coffee</strong></td>
            <td class="radio"><input name="in_coffee" id="in_coffee_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_coffee" id="in_coffee_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_coffee" id="in_coffee_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Been treated for drug dependence?</strong></td>
            <td class="radio" style="width: 60px"><input name="in_drug_dep" id="in_drug_dep_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_drug_dep" id="in_drug_dep_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_drug_dep" id="in_drug_dep_id" type="radio" value="p" /> P</td>
            <td><strong>Drink black tea/green tea</strong></td>
            <td class="radio"><input name="in_tea" id="in_tea_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_tea" id="in_tea_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_tea" id="in_tea_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Use alcoholic beverages</strong></td>
            <td class="radio" style="width: 60px"><input name="in_alcohal" id="in_alcohal_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_alcohal" id="in_alcohal_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_alcohal" id="in_alcohal_id" type="radio" value="p" /> P</td>
            <td><strong>Drink cola/other pop</strong></td>
            <td class="radio"><input name="in_cola" id="in_cola_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_cola" id="in_cola_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_cola" id="in_cola_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Been treated for alcoholism</strong></td>
            <td class="radio" style="width: 60px"><input name="in_tr_alcohal" id="in_tr_alcohal_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_tr_alcohal" id="in_tr_alcohal_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_tr_alcohal" id="in_tr_alcohal_id" type="radio" value="p" /> P</td>
            <td><strong>Drink or eat refined sugar</strong></td>
            <td class="radio"><input name="in_eat_sugar" id="in_eat_sugar_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_eat_sugar" id="in_eat_sugar_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_eat_sugar" id="in_eat_sugar_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>&nbsp;</strong></td>
            <td class="radio" style="width: 60px">&nbsp;</td>
            <td class="radio">&nbsp;</td>
            <td class="radio">&nbsp;</td>
            <td><strong>Add salt to food</strong></td>
            <td class="radio"><input name="in_add_salt" id="in_add_salt_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_add_salt" id="in_add_salt_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_add_salt" id="in_add_salt_id" type="radio" value="p" /> P</td>            
        </tr>

    </table>
    </div>    
    
    
 		
    
    
        <div align="center" class="form-footer"></div>
</div>
<div class="wrapper">
<h2>REVIEW OF SYSTEMS</h2>
<div style="height:10px;"></div>
    <div class="form5-condition">
    
        <table cellpadding="0" cellspacing="0" style="width: 100%">
            <tr>
                <td style="width:300px;">Y = A condition you have now  </td>
                <td style="width:350px;"> N = Never had </td>
                <td> P = Significant problem in the past</td>
            </tr>
        </table>
    
</div>
<h4>MENTAL / EMOTIONAL</h4>
<div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Treated for emotional problems</strong></td>
            <td class="radio" style="width:55px;"><input name="in_emot_prob" id="in_emot_prob_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_emot_prob" id="in_emot_prob_id" type="radio" value="n" /> N</td>
            <td class="radio" style="width:120px;"><input name="in_emot_prob" id="in_emot_prob_id" type="radio" value="p" /> P</td>
            <td  style="width:250px;"><strong>Depression</strong></td>
            <td class="radio" style="width:60px;"><input name="in_depress" id="in_depress_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_depress" id="in_depress_id" id="in_6_2_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_depress" id="in_depress_id" type="radio" value="p" /> P</td>
            
        </tr>
        
        
        
        
        <tr>
            <td><strong>Mood Swings</strong></td>
            <td class="radio" ><input name="in_mood" id="in_mood_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_mood" id="in_mood_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_mood" id="in_mood_id" type="radio" value="p" /> P</td>
            <td><strong>Anxiety or nervousness</strong></td>
            <td class="radio"><input name="in_anexiety" id="in_anexiety_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_anexiety" id="in_anexiety_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_anexiety" id="in_anexiety_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Considered or Attempted suicide</strong></td>
            <td class="radio" ><input name="in_con_sucide" id="in_con_sucide_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_con_sucide" id="in_con_sucide_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_con_sucide" id="in_con_sucide_id" type="radio" value="p" /> P</td>
            <td><strong>Tension</strong></td>
            <td class="radio"><input name="in_tension" id="in_tension_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_tension" id="in_tension_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_tension" id="in_tension_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Poor concentration</strong></td>
            <td class="radio" ><input name="in_poor_conc" id="in_poor_conc_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_poor_conc" id="in_poor_conc_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_poor_conc" id="in_poor_conc_id" type="radio" value="p" /> P</td>
            <td><strong>Memory problems</strong></td>
            <td class="radio"><input name="in_mem_prob" id="in_mem_prob_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_mem_prob" id="in_mem_prob_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_mem_prob" id="in_mem_prob_id" type="radio" value="p" /> P</td>            
        </tr>
        
        
        

    </table>
    </div>    
    
    <h3>IMMUNE</h3>
    <div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Reactions to immunizations</strong></td>
            <td class="radio" style="width:55px;"><input name="in_react_immu" id="in_react_immu_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_react_immu" id="in_react_immu_id" type="radio" value="n" /> N</td>
            <td class="radio" style="width:120px;"><input name="in_react_immu" id="in_react_immu_id" type="radio" value="p" /> P</td>
            <td  style="width:250px;"><strong>Reactions to vaccinations</strong></td>
            <td class="radio" style="width:60px;"><input name="in_react_vac" id="in_react_vac_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_react_vac" id="in_react_vac_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_react_vac" id="in_react_vac_id" type="radio" value="p" /> P</td>
            
        </tr>
<tr>
            <td><strong>Chronic Fatigue Syndrome</strong></td>
            <td class="radio" ><input name="in_ch_syndrome" id="in_ch_syndrome_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_ch_syndrome" id="in_ch_syndrome_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_ch_syndrome" id="in_ch_syndrome_id" type="radio" value="p" /> P</td>
            <td><strong>Chronic infections</strong></td>
            <td class="radio"><input name="in_ch_infect" id="in_ch_infect_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_ch_infect" id="in_ch_infect_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_ch_infect" id="in_ch_infect_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Chronically swollen glands</strong></td>
            <td class="radio" ><input name="in_ch_swollen" id="in_ch_swollen_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_ch_swollen" id="in_ch_swollen_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_ch_swollen" id="in_ch_swollen_id" type="radio" value="p" /> P</td>
            <td><strong>Slow wound healing</strong></td>
            <td class="radio"><input name="in_s_wound" id="in_s_wound_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_s_wound" id="in_s_wound_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_s_wound" id="in_s_wound_id" type="radio" value="p" /> P</td>            
        </tr>
</table>
    </div>
    <h3>ENDOCRINE</h3>
    <div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Hypo/hyperthyroid</strong></td>
            <td class="radio" style="width:55px;"><input name="in_hypo" id="in_hypo_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_hypo" id="in_hypo_id" type="radio" value="n" /> N</td>
            <td class="radio" style="width:120px;"><input name="in_hypo" id="in_hypo_id" type="radio" value="p" /> P</td>
            <td  style="width:250px;"><strong>Heat or cold intolerance</strong></td>
            <td class="radio" style="width:60px;"><input name="in_intolerance" id="in_intolerance_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_intolerance" id="in_intolerance_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_intolerance" id="in_intolerance_id" type="radio" value="p" /> P</td>
            
        </tr>
<tr>
            <td><strong>Hypoglycemia</strong></td>
            <td class="radio" ><input name="in_hypogly" id="in_hypogly_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_hypogly" id="in_hypogly_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_hypogly" id="in_hypogly_id" type="radio" value="p" /> P</td>
            <td><strong>Diabetes</strong></td>
            <td class="radio"><input name="in_diabetes" id="in_diabetes_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_diabetes" id="in_diabetes_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_diabetes" id="in_diabetes_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Excessive thirst</strong></td>
            <td class="radio" ><input name="in_ex_thirst" id="in_ex_thirst_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_ex_thirst" id="in_ex_thirst_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_ex_thirst" id="in_ex_thirst_id" type="radio" value="p" /> P</td>
            <td><strong>Excessive hunger</strong></td>
            <td class="radio"><input name="in_ex_hunger" id="in_ex_hunger_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_ex_hunger" id="in_ex_hunger_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_ex_hunger" id="in_ex_hunger_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Fatigue</strong></td>
            <td class="radio" ><input name="in_fatigue" id="in_fatigue_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_fatigue" id="in_fatigue_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_fatigue" id="in_fatigue_id" type="radio" value="p" /> P</td>
            <td><strong>Seasonal depression</strong></td>
            <td class="radio"><input name="in_s_depresion" id="in_s_depresion_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_s_depresion" id="in_s_depresion_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_s_depresion" id="in_s_depresion_id" type="radio" value="p" /> P</td>            
        </tr>

</table>
    </div>
    <h3>NEUROLOGIC</h3>
    <div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Seizures</strong></td>
            <td class="radio" style="width:55px;"><input name="in_seizures" id="in_seizures_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_seizures" id="in_seizures_id" type="radio" value="n" /> N</td>
            <td class="radio" style="width:120px;"><input name="in_seizures" id="in_seizures_id" type="radio" value="p" /> P</td>
            <td  style="width:250px;"><strong>Paralysis</strong></td>
            <td class="radio" style="width:60px;"><input name="in_paralysis" id="in_paralysis_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_paralysis" id="in_paralysis_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_paralysis" id="in_paralysis_id" type="radio" value="p" /> P</td>
            
        </tr>
<tr>
            <td><strong>Muscle weakness</strong></td>
            <td class="radio" ><input name="in_mu_weak" id="in_mu_weak_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_mu_weak" id="in_mu_weak_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_mu_weak" id="in_mu_weak_id" type="radio" value="p" /> P</td>
            <td><strong>Numbness or tingling</strong></td>
            <td class="radio"><input name="in_numb_ting" id="in_numb_ting_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_numb_ting" id="in_numb_ting_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_numb_ting" id="in_numb_ting_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Loss of memory</strong></td>
            <td class="radio" ><input name="in_loss_mem" id="in_loss_mem_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_loss_mem" id="in_loss_mem_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_loss_mem" id="in_loss_mem_id" type="radio" value="p" /> P</td>
            <td><strong>Easily stressed</strong></td>
            <td class="radio"><input name="in_e_stress" id="in_e_stress_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_e_stress" id="in_e_stress_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_e_stress" id="in_e_stress_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Vertigo or dizziness</strong></td>
            <td class="radio" ><input name="in_ver_dizzi" id="in_ver_dizzi_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_ver_dizzi" id="in_ver_dizzi_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_ver_dizzi" id="in_ver_dizzi_id" type="radio" value="p" /> P</td>
            <td><strong>Loss of balance</strong></td>
            <td class="radio"><input name="in_loss_bal" id="in_loss_bal_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_loss_bal" id="in_loss_bal_id"  type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_loss_bal" id="in_loss_bal_id"  type="radio" value="p" /> P</td>            
        </tr>

</table>
    </div>
    <h3>SKIN</h3>
    <div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Rashes</strong></td>
            <td class="radio" style="width:55px;"><input name="in_rashes" id="in_rashes_id"  type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_rashes" id="in_rashes_id" type="radio" value="n" /> N</td>
            <td class="radio" style="width:120px;"><input name="in_rashes" id="in_rashes_id" type="radio" value="p" /> P</td>
            <td  style="width:250px;"><strong>Eczema, Hives</strong></td>
            <td class="radio" style="width:60px;"><input name="in_eczema" id="in_eczema_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_eczema" id="in_eczema_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_eczema" id="in_eczema_id" type="radio" value="p" /> P</td>
            
        </tr>
<tr>
            <td><strong>Acne, Boils</strong></td>
            <td class="radio" ><input name="in_acne" id="in_acne_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_acne" id="in_acne_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_acne" id="in_acne_id" type="radio" value="p" /> P</td>
            <td><strong>Itching</strong></td>
            <td class="radio"><input name="in_itching" id="in_itching_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_itching" id="in_itching_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_itching" id="in_itching_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Color change</strong></td>
            <td class="radio" ><input name="in_col_ch" id="in_col_ch_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_col_ch" id="in_col_ch_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_col_ch" id="in_col_ch_id" type="radio" value="p" /> P</td>
            <td><strong>Perpetual hair loss</strong></td>
            <td class="radio"><input name="in_hair_loss" id="in_hair_loss_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_hair_loss" id="in_hair_loss_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_hair_loss" id="in_hair_loss_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Lumps</strong></td>
            <td class="radio" ><input name="in_lumps" id="in_lumps_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_lumps" id="in_lumps_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_lumps" id="in_lumps_id" type="radio" value="p" /> P</td>
            <td><strong>Night sweats</strong></td>
            <td class="radio"><input name="in_n_sweates" id="in_n_sweates_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_n_sweates" id="in_n_sweates_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_n_sweates" id="in_n_sweates_id" type="radio" value="p" /> P</td>            
        </tr>

</table>
    </div>
    <h3>HEAD</h3>
    <div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Headaches</strong></td>
            <td class="radio" style="width:55px;"><input name="in_headaches" id="in_headaches_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_headaches" id="in_headaches_id" type="radio" value="n" /> N</td>
            <td class="radio" style="width:120px;"><input name="in_headaches" id="in_headaches_id" type="radio" value="p" /> P</td>
            <td  style="width:250px;"><strong>Head injury</strong></td>
            <td class="radio" style="width:60px;"><input name="in_h_injury" id="in_h_injury_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_h_injury" id="in_h_injury_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_h_injury" id="in_h_injury_id" type="radio" value="p" /> P</td>
            
        </tr>
<tr>
            <td><strong>Migraines</strong></td>
            <td class="radio" ><input name="in_migrain" id="in_migrain_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_migrain" id="in_migrain_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_migrain" id="in_migrain_id" type="radio" value="p" /> P</td>
            <td><strong>Jaw / TMJ problems</strong></td>
            <td class="radio"><input name="in_jaw_tmj" id="in_jaw_tmj_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_jaw_tmj" id="in_jaw_tmj_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_jaw_tmj" id="in_jaw_tmj_id" type="radio" value="p" /> P</td>            
        </tr>
        
        

</table>
    </div>
    <h3>EYES</h3>
    <div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Spots in Eyes</strong></td>
            <td class="radio" style="width:55px;"><input name="in_spot_eye" id="in_spot_eye_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_spot_eye" id="in_spot_eye_id" type="radio" value="n" /> N</td>
            <td class="radio" style="width:120px;"><input name="in_spot_eye" id="in_spot_eye_id" type="radio" value="p" /> P</td>
            <td  style="width:250px;"><strong>Cataracts</strong></td>
            <td class="radio" style="width:60px;"><input name="in_catract" id="in_catract_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_catract" id="in_catract_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_catract" id="in_catract_id" type="radio" value="p" /> P</td>
            
        </tr>
<tr>
            <td><strong>Impaired vision</strong></td>
            <td class="radio" ><input name="in_imp_vision" id="in_imp_vision_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_imp_vision" id="in_imp_vision_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_imp_vision" id="in_imp_vision_id" type="radio" value="p" /> P</td>
            <td><strong>Wear glasses or contacts</strong></td>
            <td class="radio"><input name="in_glas_contct" id="in_glas_contct_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_glas_contct" id="in_glas_contct_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_glas_contct" id="in_glas_contct_id" type="radio" value="p" /> P</td>            
        </tr>
    <tr>
            <td><strong>Blurriness</strong></td>
            <td class="radio" ><input name="in_blurri" id="in_blurri_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_blurri" id="in_blurri_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_blurri" id="in_blurri_id" type="radio" value="p" /> P</td>
            <td><strong>Eye pain / strain</strong></td>
            <td class="radio"><input name="in_eye_pain" id="in_eye_pain_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_eye_pain" id="in_eye_pain_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_eye_pain" id="in_eye_pain_id" type="radio" value="p" /> P</td>            
        </tr>
<tr>
            <td><strong>Color blindness</strong></td>
            <td class="radio" ><input name="in_col_blind" id="in_col_blind_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_col_blind" id="in_col_blind_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_col_blind" id="in_col_blind_id" type="radio" value="p" /> P</td>
            <td><strong>Tearing or dryness</strong></td>
            <td class="radio"><input name="in_tear_dry" id="in_tear_dry_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_tear_dry" id="in_tear_dry_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_tear_dry" id="in_tear_dry_id" type="radio" value="p" /> P</td>            
        </tr>
<tr>
            <td><strong>Double vision</strong></td>
            <td class="radio" ><input name="in_d_vision" id="in_d_vision_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_d_vision" id="in_d_vision_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_d_vision" id="in_d_vision_id" type="radio" value="p" /> P</td>
            <td><strong>Glaucoma</strong></td>
            <td class="radio"><input name="in_glaucoma" id="in_glaucoma_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_glaucoma" id="in_glaucoma_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_glaucoma" id="in_glaucoma_id" type="radio" value="p" /> P</td>            
        </tr>
    
        

</table>
    </div>

<h3>EARS</h3>
    <div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Impaired hearing</strong></td>
            <td class="radio" style="width:55px;"><input name="in_imp_hear" id="in_imp_hear_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_imp_hear" id="in_imp_hear_id" type="radio" value="n" /> N</td>
            <td class="radio" style="width:120px;"><input name="in_imp_hear" id="in_imp_hear_id" type="radio" value="p" /> P</td>
            <td  style="width:250px;"><strong>Ringing</strong></td>
            <td class="radio" style="width:60px;"><input name="in_ringing" id="in_ringing_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_ringing" id="in_ringing_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_ringing" id="in_ringing_id" type="radio" value="p" /> P</td>
            
        </tr>
<tr>
            <td><strong>Earaches</strong></td>
            <td class="radio" ><input name="in_earaches" id="in_earaches_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_earaches" id="in_earaches_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_earaches" id="in_earaches_id" type="radio" value="p" /> P</td>
            <td><strong>Dizziness</strong></td>
            <td class="radio"><input name="in_dizzi" id="in_dizzi_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_dizzi" id="in_dizzi_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_dizzi" id="in_dizzi_id" type="radio" value="p" /> P</td>            
        </tr>
        
        

</table>
    </div>
    <h3>NOSE AND SINUSES</h3>
    <div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Frequent colds</strong></td>
            <td class="radio" style="width:55px;"><input name="in_f_cold" id="in_f_cold_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_f_cold" id="in_f_cold_id" type="radio" value="n" /> N</td>
            <td class="radio" style="width:120px;"><input name="in_f_cold" id="in_f_cold_id" type="radio" value="p" /> P</td>
            <td  style="width:250px;"><strong>Nose Bleeds</strong></td>
            <td class="radio" style="width:60px;"><input name="in_n_blees" id="in_n_blees_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_n_blees" id="in_n_blees_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_n_blees" id="in_n_blees_id" type="radio" value="p" /> P</td>
            
        </tr>
<tr>
            <td><strong>Stuffiness</strong></td>
            <td class="radio" ><input name="in_stuffi" id="in_stuffi_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_stuffi" id="in_stuffi_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_stuffi" id="in_stuffi_id" type="radio" value="p" /> P</td>
            <td><strong>Hay fever</strong></td>                    
            <td class="radio"><input name="in_hay" id="in_hay_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_hay" id="in_hay_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_hay" id="in_hay_id" type="radio" value="p" /> P</td>            
        </tr>
        
    <tr>
            <td><strong>Sinus problems</strong></td>
            <td class="radio" ><input name="in_sinus" id="in_sinus_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_sinus" id="in_sinus_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_sinus" id="in_sinus_id" type="radio" value="p" /> P</td>
            <td><strong>Loss of smell</strong></td>
            <td class="radio"><input name="in_l_smell" id="in_l_smell_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_l_smell" id="in_l_smell_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_l_smell" id="in_l_smell_id" type="radio" value="p" /> P</td>            
        </tr>
    

</table>
    </div>




              
    
        <div align="center" class="form-footer"></div>
</div>
<div class="wrapper">
<h2>REVIEW OF SYSTEMS</h2>
<div style="height:10px;"></div>
    <div class="form5-condition">
    
        <table cellpadding="0" cellspacing="0" style="width: 100%">
            <tr>
                <td style="width:300px;">Y = A condition you have now  </td>
                <td style="width:350px;"> N = Never had </td>
                <td> P = Significant problem in the past</td>
            </tr>
        </table>
    
</div>
<h4>MOUTH AND THROAT</h4>
<div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Frequent sore throat</strong></td>
            <td class="radio" style="width:55px;"><input name="in_freq_sore" id="in_freq_sore_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_freq_sore" id="in_freq_sore_id" type="radio" value="n" /> N</td>
            <td class="radio" style="width:120px;"><input name="in_freq_sore" id="in_freq_sore_id" type="radio" value="p" /> P</td>
            <td  style="width:250px;"><strong>Copious saliva</strong></td>
            <td class="radio" style="width:60px;"><input name="in_copi_sal" id="in_copi_sal_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_copi_sal" id="in_copi_sal_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_copi_sal" id="in_copi_sal_id" type="radio" value="p" /> P</td>
            
        </tr>
        
        
        
        
        <tr>
            <td><strong>Teeth grinding</strong></td>
            <td class="radio" ><input name="in_teeth" id="in_teeth_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_teeth" id="in_teeth_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_teeth" id="in_teeth_id" type="radio" value="p" /> P</td>
            <td><strong>Sore tongue / lips</strong></td>
            <td class="radio"><input name="in_s_tongue" id="in_s_tongue_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_s_tongue" id="in_s_tongue_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_s_tongue" id="in_s_tongue_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Gum problems</strong></td>
            <td class="radio"><input name="in_gum_prob" id="in_gum_prob_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_gum_prob" id="in_gum_prob_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_gum_prob" id="in_gum_prob_id" type="radio" value="p" /> P</td>
            <td><strong>Hoarseness</strong></td>
            <td class="radio"><input name="in_hoars" id="in_hoars_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_hoars" id="in_hoars_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_hoars" id="in_hoars_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Dental cavities</strong></td>
            <td class="radio" ><input name="in_den_cavi" id="in_den_cavi_id"  type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_den_cavi" id="in_den_cavi_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_den_cavi" id="in_den_cavi_id" type="radio" value="p" /> P</td>
            <td><strong>Jaw clicks</strong></td>
            <td class="radio"><input name="in_jaw_clic" id="in_jaw_clic_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_jaw_clic" id="in_jaw_clic_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_jaw_clic" id="in_jaw_clic_id" type="radio" value="p" /> P</td>            
        </tr>
        
        
        

    </table>
    </div>    
    
    <h3>NECK</h3>
    <div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Lumps</strong></td>
            <td class="radio" style="width:55px;"><input name="in_lumps_neck" id="in_lumps_neck_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_lumps_neck" id="in_lumps_neck_id" type="radio" value="n" /> N</td>
            <td class="radio" style="width:120px;"><input name="in_lumps_neck" id="in_lumps_neck_id" type="radio" value="p" /> P</td>
            <td  style="width:250px;"><strong>Swollen glands</strong></td>
            <td class="radio" style="width:60px;"><input name="in_swollen" id="in_swollen_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_swollen" id="in_swollen_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_swollen" id="in_swollen_id" type="radio" value="p" /> P</td>
            
        </tr>
<tr>
            <td><strong>Goiter</strong></td>
            <td class="radio" ><input name="in_goit" id="in_goit_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_goit" id="in_goit_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_goit" id="in_goit_id" type="radio" value="p" /> P</td>
            <td><strong>Pain or stiffness</strong></td>
            <td class="radio"><input name="in_pain_stif" id="in_pain_stif_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_pain_stif" id="in_pain_stif_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_pain_stif" id="in_pain_stif_id" type="radio" value="p" /> P</td>            
        </tr>
        
</table>
    </div>
    <h3>RESPIRATORY</h3>
    <div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Cough</strong></td>
            <td class="radio" style="width:55px;"><input name="in_cough" id="in_cough_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_cough" id="in_cough_id" type="radio" value="n" /> N</td>
            <td class="radio" style="width:120px;"><input name="in_cough" id="in_cough_id" type="radio" value="p" /> P</td>
            <td  style="width:250px;"><strong>Sputum</strong></td>
            <td class="radio" style="width:60px;"><input name="in_sput" id="in_sput_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_sput" id="in_sput_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_sput" id="in_sput_id" type="radio" value="p" /> P</td>
            
        </tr>
<tr>
            <td><strong>Spitting up blood</strong></td>
            <td class="radio" ><input name="in_spit_bld" id="in_spit_bld_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_spit_bld" id="in_spit_bld_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_spit_bld" id="in_spit_bld_id" type="radio" value="p" /> P</td>
            <td><strong>Wheezing</strong></td>
            <td class="radio"><input name="in_wheez" id="in_wheez_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_wheez" id="in_wheez_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_wheez" id="in_wheez_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Asthma</strong></td>
            <td class="radio" ><input name="in_asthma" id="in_asthma_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_asthma" id="in_asthma_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_asthma" id="in_asthma_id" type="radio" value="p" /> P</td>
            <td><strong>Bronchitis</strong></td>
            <td class="radio"><input name="in_bronch" id="in_bronch_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_bronch" id="in_bronch_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_bronch" id="in_bronch_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Pneumonia</strong></td>
            <td class="radio" ><input name="in_pneumo" id="in_pneumo_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_pneumo" id="in_pneumo_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_pneumo" id="in_pneumo_id" type="radio" value="p" /> P</td>
            <td><strong>Pleurisy</strong></td>
            <td class="radio"><input name="in_Pleuri" id="in_Pleuri_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_Pleuri" id="in_Pleuri_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_Pleuri" id="in_Pleuri_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Emphysema</strong></td>
            <td class="radio" ><input name="in_emph" id="in_emph_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_emph" id="in_emph_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_emph" id="in_emph_id" type="radio" value="p" /> P</td>
            <td><strong>Difficulty breathing</strong></td>
            <td class="radio"><input name="in_diff_brth" id="in_diff_brth_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_diff_brth" id="in_diff_brth_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_diff_brth" id="in_diff_brth_id" type="radio" value="p" /> P</td>            
        </tr>

<tr>
            <td><strong>Pain on breathing</strong></td>
            <td class="radio" ><input name="in_pain_brth" id="in_pain_brth_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_pain_brth" id="in_pain_brth_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_pain_brth" id="in_pain_brth_id" type="radio" value="p" /> P</td>
            <td><strong>Shortness of breath</strong></td>
            <td class="radio"><input name="in_sh_brth" id="in_sh_brth_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_sh_brth" id="in_sh_brth_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_sh_brth" id="in_sh_brth_id" type="radio" value="p" /> P</td>            
        </tr>

<tr>
            <td><strong>Shortness of breath at night</strong></td>
            <td class="radio" ><input name="in_sh_nbrth" id="in_sh_nbrth_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_sh_nbrth" id="in_sh_nbrth_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_sh_nbrth" id="in_sh_nbrth_id" type="radio" value="p" /> P</td>
            <td><strong>Shortness of breath when<br/>lying down</strong></td>
            <td class="radio"><input name="in_sh_lybrth" id="in_sh_lybrth_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_sh_lybrth" id="in_sh_lybrth_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_sh_lybrth" id="in_sh_lybrth_id" type="radio" value="p" /> P</td>            
        </tr>

<tr>
            <td><strong>Tuberculosis</strong></td>
            <td class="radio" ><input name="in_tuber" id="in_tuber_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_tuber" id="in_tuber_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_tuber" id="in_tuber_id" type="radio" value="p" /> P</td>
            <td><strong>&nbsp;</strong></td>
            <td class="radio"> &nbsp;</td>
            <td class="radio"> &nbsp;</td>
            <td class="radio"> &nbsp;</td>        
        </tr>


</table>
    </div>
    <h3>CARDLOVASCULAR</h3>
    <div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Heart disease</strong></td>
            <td class="radio" style="width:55px;"><input name="in_heart" id="in_heart_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_heart" id="in_heart_id" type="radio" value="n" /> N</td>
            <td class="radio" style="width:120px;"><input name="in_heart" id="in_heart_id" type="radio" value="p" /> P</td>
            <td  style="width:250px;"><strong>Angina</strong></td>
            <td class="radio" style="width:60px;"><input name="in_angina" id="in_angina_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_angina" id="in_angina_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_angina" id="in_angina_id" type="radio" value="p" /> P</td>
            
        </tr>
<tr>
            <td><strong>High / Low Blood Pressure</strong></td>
            <td class="radio" ><input name="in_bp" id="in_bp_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_bp" id="in_bp_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_bp" id="in_bp_id" type="radio" value="p" /> P</td>
            <td><strong>Murmurs</strong></td>
            <td class="radio"><input name="in_murm" id="in_murm_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_murm" id="in_murm_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_murm" id="in_murm_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Blood clots</strong></td>
            <td class="radio" ><input name="in_b_clots" id="in_b_clots_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_b_clots" id="in_b_clots_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_b_clots" id="in_b_clots_id" type="radio" value="p" /> P</td>
            <td><strong>Fainting</strong></td>
            <td class="radio"><input name="in_faint" id="in_faint_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_faint" id="in_faint_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_faint" id="in_faint_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Phlebitis</strong></td>
            <td class="radio" ><input name="in_phleb" id="in_phleb_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_phleb" id="in_phleb_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_phleb" id="in_phleb_id" type="radio" value="p" /> P</td>
            <td><strong>Palpitations / Fluttering</strong></td>
            <td class="radio"><input name="in_palpi" id="in_palpi_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_palpi" id="in_palpi_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_palpi" id="in_palpi_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Rheumatic Fever</strong></td>
            <td class="radio"><input name="in_rheu" id="in_rheu_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_rheu" id="in_rheu_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_rheu" id="in_rheu_id" type="radio" value="p" /> P</td>
            <td><strong>Chest Pain</strong></td>
            <td class="radio"><input name="in_chest_p" id="in_chest_p_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_chest_p" id="in_chest_p_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_chest_p" id="in_chest_p_id" type="radio" value="p" /> P</td>            
        </tr>

<tr>
            <td><strong>Swelling in ankles</strong></td>
            <td class="radio" ><input name="in_swel" id="in_swel_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_swel" id="in_swel_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_swel" id="in_swel_id" type="radio" value="p" /> P</td>
            <td><strong>&nbsp;</strong></td>
            <td><strong>&nbsp;</strong></td>
            <td><strong>&nbsp;</strong></td>
            <td><strong>&nbsp;</strong></td>            
        </tr>


</table>
    </div>
    <h3>GASTROINTESTINAL</h3>
    <div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Trouble swallowing</strong></td>
            <td class="radio" style="width:55px;"><input name="in_tr_swall" id="in_tr_swall_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_tr_swall" id="in_tr_swall_id" type="radio" value="n" /> N</td>
            <td class="radio" style="width:120px;"><input name="in_tr_swall" id="in_tr_swall_id" type="radio" value="p" /> P</td>
            <td  style="width:250px;"><strong>Heartburn</strong></td>
            <td class="radio" style="width:60px;"><input name="in_hburn" id="in_hburn_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_hburn" id="in_hburn_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_hburn" id="in_hburn_id" type="radio" value="p" /> P</td>
            
        </tr>
<tr>
            <td><strong>Change in thirst</strong></td>
            <td class="radio" ><input name="in_thirst" id="in_thirst_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_thirst" id="in_thirst_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_thirst" id="in_thirst_id" type="radio" value="p" /> P</td>
            <td><strong>Abdominal pain or cramps</strong></td>
            <td class="radio"><input name="in_ab_pain" id="in_ab_pain_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_ab_pain" id="in_ab_pain_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_ab_pain" id="in_ab_pain_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Change in appetite</strong></td>
            <td class="radio" ><input name="in_ch_appe" id="in_ch_appe_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_ch_appe" id="in_ch_appe_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_ch_appe" id="in_ch_appe_id" type="radio" value="p" /> P</td>
            <td><strong>Belching or passing gas</strong></td>
            <td class="radio"><input name="in_bl_gas" id="in_bl_gas_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_bl_gas" id="in_bl_gas_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_bl_gas" id="in_bl_gas_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Nausea / vomiting</strong></td>
            <td class="radio" ><input name="in_vomit" id="in_vomit_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_vomit" id="in_vomit_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_vomit" id="in_vomit_id" type="radio" value="p" /> P</td>
            <td><strong>Constipation</strong></td>
            <td class="radio"><input name="in_consti" id="in_consti_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_consti" id="in_consti_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_consti" id="in_consti_id" type="radio" value="p" /> P</td>            
        </tr>
        <tr>
            <td><strong>Ulcer</strong></td>
            <td class="radio" ><input name="in_ulser" id="in_ulser_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_ulser" id="in_ulser_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_ulser" id="in_ulser_id" type="radio" value="p" /> P</td>
            <td><strong>Diarrhea</strong></td>
            <td class="radio"><input name="in_diarr" id="in_diarr_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_diarr" id="in_diarr_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_diarr" id="in_diarr_id" type="radio" value="p" /> P</td>            
        </tr>

<tr>
            <td><strong>Jaundice (yellow skin)</strong></td>
            <td class="radio" ><input name="in_jaundice" id="in_jaundice_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_jaundice" id="in_jaundice_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_jaundice" id="in_jaundice_id" type="radio" value="p" /> P</td>
            <td><strong>Bowel Movements: How often?</strong></td>
            <td colspan="3"><input name="in_bowel" id="in_bowel_id" type="text"  style="width:130px;"/><div id="div_in_bowel_id"></div></td>
                        
        </tr>

<tr>
            <td><strong>Gall Bladder disease</strong></td>
            <td class="radio" ><input name="in_gall_dis" id="in_gall_dis_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_gall_dis" id="in_gall_dis_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_gall_dis" id="in_gall_dis_id" type="radio" value="p" /> P</td>
            <td><strong>Is this a change?</strong></td>
            <td class="radio"><input name="in_is_change" id="in_is_change_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_is_change" id="in_is_change_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_is_change" id="in_is_change_id" type="radio" value="p" /> P</td>            
        </tr>

<tr>
            <td><strong>Liver Disease</strong></td>
            <td class="radio" ><input name="in_liv_dis" id="in_liv_dis_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_liv_dis" id="in_liv_dis_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_liv_dis" id="in_liv_dis_id" type="radio" value="p" /> P</td>
            <td><strong>Black stools</strong></td>
            <td class="radio"><input name="in_b_stool" id="in_b_stool_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_b_stool" id="in_b_stool_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_b_stool" id="in_b_stool_id" type="radio" value="p" /> P</td>            
        </tr>

<tr>
            <td><strong>Hemorhoids</strong></td>
            <td class="radio" ><input name="in_hermor" id="in_hermor_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_hermor" id="in_hermor_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_hermor" id="in_hermor_id" type="radio" value="p" /> P</td>
            <td><strong>Blood / Mucus in stools</strong></td>
            <td class="radio"><input name="in_bl_mucus" id="in_bl_mucus_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_bl_mucus" id="in_bl_mucus_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_bl_mucus" id="in_bl_mucus_id" type="radio" value="p" /> P</td>            
        </tr>


</table>
    </div>
    
    
  		
    
    
        <div align="center" class="form-footer"></div>
</div>
<div class="wrapper">
<div style="height:20px;"></div>
    <div class="form5-condition">
    
        <table cellpadding="0" cellspacing="0" style="width: 100%">
            <tr>
                <td style="width:300px;">Y = A condition you have now  </td>
                <td style="width:350px;"> N = Never had </td>
                <td> P = Significant problem in the past</td>
            </tr>
        </table>
    
</div>
<h4>URINARY</h4>
<div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Pain on urination</strong></td>
            <td class="radio" style="width:55px;"><input name="in_pain" id="in_pain_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_pain" id="in_pain_id" type="radio" value="n" /> N</td>
            <td class="radio" style="width:120px;"><input name="in_pain" id="in_pain_id" type="radio" value="p" /> p</td>
            <td  style="width:250px;"><strong>Increased frequency</strong></td>
            <td class="radio" style="width:55px;"><input name="in_freq" id="in_freq_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_freq" id="in_freq_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_freq" id="in_freq_id" type="radio" value="p" /> p</td>
            
        </tr>
        
        
        
        
        <tr>
            <td><strong>Frequency at night</strong></td>
            <td class="radio" ><input name="in_freq_night" id="in_freq_night_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_freq_night" id="in_freq_night_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_freq_night" id="in_freq_night_id" type="radio" value="p" /> p</td>
            <td><strong>Inability to hold urine</strong></td>
            <td class="radio"><input name="in_hold_urin" id="in_hold_urin_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_hold_urin" id="in_hold_urin_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_hold_urin" id="in_hold_urin_id" type="radio" value="p" /> p</td>            
        </tr>
        <tr>
            <td><strong>Frequent infections</strong></td>
            <td class="radio" ><input name="in_freq_infc" id="in_freq_infc_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_freq_infc" id="in_freq_infc_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_freq_infc" id="in_freq_infc_id" type="radio" value="p" /> p</td>
            <td><strong>Kidney stones</strong></td>
            <td class="radio"><input name="in_kid_stone" id="in_kid_stone_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_kid_stone" id="in_kid_stone_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_kid_stone" id="in_kid_stone_id" type="radio" value="p" /> p</td>            
        </tr>
        
        
        
        

    </table>
    </div>    
    
    <h3>MUSCULOSKELETAL</h3>
    <div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Joint pain or stiffness</strong></td>
            <td class="radio" style="width:55px;"><input name="in_j_pain" id="in_j_pain_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_j_pain" id="in_j_pain_id" type="radio" value="n" /> N</td>
            <td class="radio" style="width:120px;"><input name="in_j_pain" id="in_j_pain_id" type="radio" value="p" /> p</td>
            <td  style="width:250px;"><strong>Arthritis</strong></td>
            <td class="radio" style="width:55px;"><input name="in_arth" id="in_arth_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_arth" id="in_arth_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_arth" id="in_arth_id" type="radio" value="p" /> p</td>
            
        </tr>
<tr>
            <td><strong>Broken bones</strong></td>
            <td class="radio" ><input name="in_brk_bones" id="in_brk_bones_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_brk_bones" id="in_brk_bones_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_brk_bones" id="in_brk_bones_id" type="radio" value="p" /> p</td>
            <td><strong>Weakness</strong></td>
            <td class="radio"><input name="in_weakness" id="in_weakness_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_weakness" id="in_weakness_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_weakness" id="in_weakness_id" type="radio" value="p" /> p</td>            
        </tr>
        <tr>
            <td><strong>Muscle spasms or cramps</strong></td>
            <td class="radio" ><input name="in_m_spasm" id="in_m_spasm_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_m_spasm" id="in_m_spasm_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_m_spasm" id="in_m_spasm_id" type="radio" value="p" /> p</td>
            <td><strong>Sciatica</strong></td>
            <td class="radio"><input name="in_sciatica" id="in_sciatica" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_sciatica" id="in_sciatica" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_sciatica" id="in_sciatica" type="radio" value="p" /> p</td>            
        </tr>

</table>
    </div>
    <h3>BLOOD / PERIPHERAL VASCULAR</h3>
    <div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Easy bleeding or bruising</strong></td>
            <td class="radio" style="width:55px;"><input name="in_e_bleed" id="in_e_bleed_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_e_bleed" id="in_e_bleed_id" type="radio" value="n" /> N</td>
            <td class="radio" style="width:120px;"><input name="in_e_bleed" id="in_e_bleed_id" type="radio" value="p" /> p</td>
            <td  style="width:250px;"><strong>Anemia</strong></td>
            <td class="radio" style="width:55px;"><input name="in_anamia" id="in_anamia_id" type="radio" value="y" /> Y</td>
            <td class="radio" style="width:55px;"><input name="in_anamia" id="in_anamia_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_anamia" id="in_anamia_id" type="radio" value="p" /> p</td>
            
        </tr>
<tr>
            <td><strong>Deep leg pain</strong></td>
            <td class="radio" ><input name="in_d_lpain" id="in_d_lpain_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_d_lpain" id="in_d_lpain_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_d_lpain" id="in_d_lpain_id" type="radio" value="p" /> p</td>
            <td><strong>Cold hands / feet</strong></td>
            <td class="radio"><input name="in_c_hands" id="in_c_hands_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_c_hands" id="in_c_hands_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_c_hands" id="in_c_hands_id" type="radio" value="p" /> p</td>            
        </tr>
        <tr>
            <td><strong>Varicose Veins</strong></td>
            <td class="radio" ><input name="in_v_veins" id="in_v_veins_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_v_veins" id="in_v_veins_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_v_veins" id="in_v_veins_id" type="radio" value="p" /> p</td>
            <td><strong>Thrombophlebitis</strong></td>
            <td class="radio"><input name="in_trombo" id="in_trombo_id" type="radio" value="y" /> Y</td>
            <td class="radio"><input name="in_trombo" id="in_trombo_id" type="radio" value="n" /> N</td>
            <td class="radio"><input name="in_trombo" id="in_trombo_id" type="radio" value="p" /> p</td>            
        </tr>
        
        





</table>
    </div>
  <!genderBaseSection>
    
    
        <div align="center" class="form-footer"></div>
</div>
<div class="wrapper">
<div style="height:20px;"></div>
    <div><strong>Is there anything else you would like to add or comment on?</strong></div>
    <div style="height:10px;"></div>
	<div><textarea name="in_comment" id="in_comment_id" style="width:450px; height:100px;" ></textarea><div id="div_in_comment_id"></div></div>
    
    
        <input type="hidden" name="action" value="view_intake_paperwork" />
        <input type="hidden" name="nextPage" id="nextPage" value="1" />
        <input type="hidden" name="id" id="id" value="<!id>" />                
        <input type="hidden" name="comp" id='comp' value="print" />    
        <div align="center" class="form-footer"></div>
</div>

