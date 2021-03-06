<link href="css/intake_form.css" rel="stylesheet" type="text/css"/>
<script src="js/jquery.js"></script>  
<script type="text/javascript" language="javascript">
    var data1={<!IntakeArrayData>};
    var patient_id='<!patient_id>';
</script>

<script src="js/fill_intake_form.js"></script>  
<div id="msg" style="color:#f00; font-size:17px;" >*</div>


<form name="intakeform" id="intakeform" action="index.php" method="post" >

<div class="wrapper" style="padding-bottom:20px; padding-top:20px; padding-left:20px;">
<h1>ADULT INTAKE FORM</h1>
<!navigation_header>
<h3>FAMILY HISTORY</h3>

<div style="height:10px;"></div>
    <div>
    
    
    
        <table cellpadding="0" cellspacing="0" style="width: 100%" class="form4-history">
            <tr>
                <td colspan="5"><strong>Do you have a family history of any of the following?</strong></td>
                
            </tr>
            <tr>
                <td><input name="in_his_cancer" value="Cancer" id="in_his_cancer_id" type="checkbox" class="channel" value="Cancer" /> Cancer </td>
                <td><input name="in_his_diabetes" value="Diabetes" id="in_his_diabetes_id" type="checkbox" class="channel" value="Diabetes" /> Diabetes </td>
                <td><input name="in_his_heart_disease" value="Heart Disease" id="in_his_heart_disease_id" type="checkbox"  class="channel" value="Heart Disease" /> Heart Disease </td>
                <td><input name="in_his_high_blood_pressure" value="High Blood Pressure" id="in_his_high_blood_pressure_id" type="checkbox"  class="channel" value="High Blood Pressure" /> High Blood Pressure </td>
                <td><input name="in_his_kidney_disease" value="Kidney Disease" id="in_his_kidney_disease_id" type="checkbox"  class="channel" value="Kidney Disease" /> Kidney Disease</td>
            </tr>
            <tr>
                <td><input name="in_his_epilepsy" value="Epilepsy" id="in_his_epilepsy_id" type="checkbox" class="channel" value="Epilepsy" /> Epilepsy </td>
                <td><input name="in_his_arthritis" value="Arthritis" id="in_his_arthritis_id" type="checkbox" class="channel" value="Arthritis" /> Arthritis </td>
                <td><input name="in_his_glaucoma" value="Glaucoma" id="in_his_glaucoma_id" type="checkbox" class="channel" value="Glaucoma" /> Glaucoma </td>
                <td><input name="in_his_tuberculosis" value="Tuberculosis" id="in_his_tuberculosis_id"  class="channel" type="checkbox" value="Tuberculosis" /> Tuberculosis </td>
                <td><input name="in_his_stroke" value="Stroke" id="in_his_stroke_id" type="checkbox"  class="channel" value="Stroke" /> Stroke </td>
            </tr>
            <tr>
                <td><input name="in_his_anaemia" value="Anaemia" id="in_his_anaemia_id" type="checkbox"  class="channel" value="Anaemia" /> Anemia </td>
                <td><input name="in_his_mental_illness" value="Mental Illness" id="in_his_mental_illness_id"  class="channel" type="checkbox"  value="Mental Illness"/> Mental Illness </td>
                <td><input name="in_his_asthama" value="Asthama" id="in_his_asthama_id" type="checkbox"  class="channel" value="Asthama" /> Asthma</td>
                <td><input name="in_his_hay_fever" value="Hay Fever" id="in_his_hay_fever_id" type="checkbox"  class="channel" value="Hay Fever" /> Hay Fever </td>
                <td><input name="in_his_hives" value="Hives" id="in_his_hives_id" type="checkbox"  class="channel" value="Hives" />  Hives</td>
            </tr> 
            <tr>
                <td colspan="5"><strong>Any other relevant family history?</strong></td>
            </tr>
            <tr>
                <td colspan="5"><textarea  name="in_his_other" id="in_his_other_id" class="nonmandatorytextarea"></textarea></td>
                
            </tr>
            <tr>
                <td colspan="5"><strong>What is your heritage:</strong></td>
                
            </tr>
            <tr>
               <td><input  name="in_heritage" id="in_heritage_id" type="text" maxlength="30" style="width: 470px !important;"/></td> 
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
                <td><input name="in_ill_sca" id="in_ill_sca_id" type="checkbox" class="chIll" value="Scarlet Fever" /> Scarlet Fever</td>
                <td><input name="in_ill_dip" id="in_ill_dip_id"  type="checkbox" class="chIll" value="Diphtheria" /> Diphtheria</td>
                <td><input name="in_ill_rheu" id="in_ill_rheu_id" type="checkbox" class="chIll" value="Rheumatic Fever" /> Rheumatic Fever</td>
            </tr>
            <tr>
                <td><input name="in_ill_mumps" id="in_ill_mumps_id" type="checkbox" class="chIll" value="Mumps" /> Mumps</td>
                <td><input name="in_ill_measls" id="in_ill_measls_id" type="checkbox" class="chIll" value="Measles" /> Measles</td>
                <td><input name="in_ill_gmeasls" id="in_ill_gmeasls_id" type="checkbox" class="chIll" value="German Measles" /> German Measles </td>
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
                    <td style="width:320px;"><input name="in_had_surg1" id="in_had_surg1_id" type="text" class="nonmandatorytextbox" maxlength="254"/></td>
                    <td style="width:130px;">
                    <select name="in_had_surg1y" id="in_had_surg1y_id" class="year">
                        <option>Year</option>
                        <!selectReportYearOption>
                    </select> 
                    </td>
                    <td style="width:320px;"><input  name="in_had_surg2" id="in_had_surg2_id" type="text" maxlength="254" class="nonmandatorytextbox"/></td>
                    <td>
                    <select  name="in_had_surg2y" id="in_had_surg2y_id"  class="year" >
                        <option value=''>Year</option>
                        <!selectReportYearOption>
                    </select></td>
                </tr>
                <tr>
                    <td><input  name="in_had_surg3" id="in_had_surg3_id" type="text" maxlength="254" class="nonmandatorytextbox"/></td>
                    <td><select  name="in_had_surg3y" id="in_had_surg3y_id" class="year" >
                            <option value=''>Year</option>
                            <!selectReportYearOption>                
            </select></td>
                    <td><input  name="in_had_surg4" id="in_had_surg4_id" type="text" maxlength="254" class="nonmandatorytextbox"/></td>
                    <td><select  name="in_had_surg4y" id="in_had_surg4y_id"  class="year">
                <option>Year</option>
                <!selectReportYearOption>
            </select></td>
                </tr>
                <tr>
                    <td><input  name="in_had_surg5" id="in_had_surg5_id" type="text" maxlength="254" class="nonmandatorytextbox"/></td>
                    <td><select name="in_had_surg5y" id="in_had_surg5y_id" class="year" >
                        <option>Year</option>
                        <!selectReportYearOption>                
            </select></td>
                    <td><input  name="in_had_surg6" id="in_had_surg6_id" type="text" maxlength="254" class="nonmandatorytextbox"/></td>
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
                <td><input  name="in_allergy_drugs" id="in_allergy_drugs_id" type="text" maxlength="254" /></td>
            </tr>
            <tr>
                <td><strong>Any foods?</strong></td>
            </tr>
            <tr>
                <td><input name="in_allergy_food" id="in_allergy_food_id" type="text" maxlength="254" /></td>
            </tr>
            <tr>
                <td><strong>Any environmental influences or chemicals?</strong></td>
            </tr>
            <tr>
                <td><input name="in_env_influ" id="in_env_influ_id" type="text" maxlength="254" /></td>
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
                <td class="radioCustom"><input name="in_take_thyo" id="in_take_thyo_id" type="radio" value="Y" /> Yes &nbsp;&nbsp;&nbsp; <input name="in_take_thyo" id="in_take_thyo_id" type="radio" value="N" /> No</td>
                <td class="radioCustom"><input name="in_take_sleep" id="in_take_sleep_id" type="radio" value="Y" /> Yes &nbsp;&nbsp;&nbsp; <input name="in_take_sleep" id="in_take_sleep_id" type="radio" value="N" /> No</td>
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
                <td style="width:400px;"><input name="in_prescript_1" id="in_prescript_1_id" type="text" maxlength="254" class="nonmandatorytextbox"/></td>
                <td><input name="in_prescript_2" id="in_prescript_2_id" type="text" maxlength="254" class="nonmandatorytextbox"/></td>
            </tr>
            <tr>
                <td><input name="in_prescript_3" id="in_prescript_3_id" type="text" maxlength="254" class="nonmandatorytextbox"/></td>
                <td><input name="in_prescript_4" id="in_prescript_4_id" type="text" maxlength="254" class="nonmandatorytextbox"/></td>
            </tr>
            <tr>
                <td><input name="in_prescript_5" id="in_prescript_5_id" type="text" maxlength="254" class="nonmandatorytextbox"/></td>
                <td><input name="in_prescript_6" id="in_prescript_6_id" type="text" maxlength="254" class="nonmandatorytextbox"/></td>
            </tr>
        </table>
    </div>
        <input type="hidden" name="action" value="saveintakepaperwork" />
        <input type="hidden" name="intake_last_page" value="4" />
        <input type="hidden" name="intake_compl_status" value="0" />
        <input type="hidden" name="nextPage" id="nextPage" value="4" />            
        <input type="hidden" name="closeChild" id="closeChild" value="1" />        
                
        <div align="center" class="form-footer"><input name="Button" type="button" value="Previous" onclick="return previous_page('intakeform', '3');"
 />&nbsp;<input name="Button" type="submit" value="Save & Exit" /> &nbsp;<input name="Button" type="button" style="width:65px" value="Next" onclick="return validate('intakeform', '5');"
  /></div>
</div>
</form>
