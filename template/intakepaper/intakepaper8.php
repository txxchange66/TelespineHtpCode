<link href="css/intake_form.css" rel="stylesheet" type="text/css"/>
<script src="js/jquery.js"></script>  
<script type="text/javascript" language="javascript">
    var data1={<!IntakeArrayData>};
    var patient_id='<!patient_id>';

    

</script>
<script type='text/javascript'>


var data1={<!IntakeArrayData>};
	$(document).ready(function() { 


		if(data1['in_pms']=='n')
		$("#in_sympt_b_id").attr("disabled","disabled");
		if(data1['in_b_cont']=='n')
		$("#in_sympt_a_id").attr("disabled","disabled");
		
	}); 




</script>

<script src="js/fill_intake_form.js"></script>  
<div id="msg" style="color:#f00; font-size:17px;" >*</div>
<form name="intakeform" id="intakeform" action="index.php" method="post" >
<div class="wrapper" style="padding-bottom:20px; padding-top:20px; padding-left:20px;">
<h1>ADULT INTAKE FORM</h1>
<!navigation_header>
<div style="height:20px;"></div>
    <div class="form5-condition">
    
        <table cellpadding="4" cellspacing="0" style="width: 100%">
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
             <td  class="radio"  colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td  style="width:55px;"><input name="in_pain" id="in_pain_id" type="radio" value="y" /> Y</td>
            <td  style="width:55px;"><input name="in_pain" id="in_pain_id" type="radio" value="n" /> N</td>
            <td  ><input name="in_pain" id="in_pain_id" type="radio" value="p" /> p</td>
             </tr>
                </table>
            </td>

            <td  style="width:250px;"><strong>Increased frequency</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td  style="width:55px;"><input name="in_freq" id="in_freq_id" type="radio" value="y" /> Y</td>
            <td  style="width:55px;"><input name="in_freq" id="in_freq_id" type="radio" value="n" /> N</td>
            <td ><input name="in_freq" id="in_freq_id" type="radio" value="p" /> p</td>
             </tr>
                </table>
            </td>

            
        </tr>
        
        
        
        
        <tr>
            <td><strong>Frequency at night</strong></td>
             <td class="radio"  colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_freq_night" id="in_freq_night_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_freq_night" id="in_freq_night_id" type="radio" value="n" /> N</td>
            <td ><input name="in_freq_night" id="in_freq_night_id" type="radio" value="p" /> p</td>
             </tr>
                </table>
            </td>

            <td><strong>Inability to hold urine</strong></td>
             <td class="radio"  colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_hold_urin" id="in_hold_urin_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_hold_urin" id="in_hold_urin_id" type="radio" value="n" /> N</td>
            <td ><input name="in_hold_urin" id="in_hold_urin_id" type="radio" value="p" /> p</td>
             </tr>
                </table>
            </td>
            
        </tr>
        <tr>
            <td><strong>Frequent infections</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_freq_infc" id="in_freq_infc_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_freq_infc" id="in_freq_infc_id" type="radio" value="n" /> N</td>
            <td ><input name="in_freq_infc" id="in_freq_infc_id" type="radio" value="p" /> p</td>
             </tr>
                </table>
            </td>

            <td><strong>Kidney stones</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_kid_stone" id="in_kid_stone_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_kid_stone" id="in_kid_stone_id" type="radio" value="n" /> N</td>
            <td ><input name="in_kid_stone" id="in_kid_stone_id" type="radio" value="p" /> p</td>
             </tr>
                </table>
            </td>
            
        </tr>
        
        
        
        

    </table>
    </div>    
    
    <h3>MUSCULOSKELETAL</h3>
    <div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Joint pain or stiffness</strong></td>
              <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td  style="width:55px;"><input name="in_j_pain" id="in_j_pain_id" type="radio" value="y" /> Y</td>
            <td  style="width:55px;"><input name="in_j_pain" id="in_j_pain_id" type="radio" value="n" /> N</td>
            <td  ><input name="in_j_pain" id="in_j_pain_id" type="radio" value="p" /> p</td>
             </tr>
                </table>
            </td>

            <td  style="width:250px;"><strong>Arthritis</strong></td>
              <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td  style="width:55px;"><input name="in_arth" id="in_arth_id" type="radio" value="y" /> Y</td>
            <td  style="width:55px;"><input name="in_arth" id="in_arth_id" type="radio" value="n" /> N</td>
            <td ><input name="in_arth" id="in_arth_id" type="radio" value="p" /> p</td>
             </tr>
                </table>
            </td>

            
        </tr>
<tr>
            <td><strong>Broken bones</strong></td>
              <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_brk_bones" id="in_brk_bones_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_brk_bones" id="in_brk_bones_id" type="radio" value="n" /> N</td>
            <td ><input name="in_brk_bones" id="in_brk_bones_id" type="radio" value="p" /> p</td>
             </tr>
                </table>
            </td>

            <td><strong>Weakness</strong></td>
              <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_weakness" id="in_weakness_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_weakness" id="in_weakness_id" type="radio" value="n" /> N</td>
            <td ><input name="in_weakness" id="in_weakness_id" type="radio" value="p" /> p</td>
             </tr>
                </table>
            </td>
            
        </tr>
        <tr>
            <td><strong>Muscle spasms or cramps</strong></td>
              <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_m_spasm" id="in_m_spasm_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_m_spasm" id="in_m_spasm_id" type="radio" value="n" /> N</td>
            <td ><input name="in_m_spasm" id="in_m_spasm_id" type="radio" value="p" /> p</td>
             </tr>
                </table>
            </td>

            <td><strong>Sciatica</strong></td>
              <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_sciatica" id="in_sciatica" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_sciatica" id="in_sciatica" type="radio" value="n" /> N</td>
            <td ><input name="in_sciatica" id="in_sciatica" type="radio" value="p" /> p</td>
             </tr>
                </table>
            </td>
            
        </tr>

</table>
    </div>
    <h3>BLOOD / PERIPHERAL VASCULAR</h3>
    <div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Easy bleeding or bruising</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td  style="width:55px;"><input name="in_e_bleed" id="in_e_bleed_id" type="radio" value="y" /> Y</td>
            <td  style="width:55px;"><input name="in_e_bleed" id="in_e_bleed_id" type="radio" value="n" /> N</td>
            <td  ><input name="in_e_bleed" id="in_e_bleed_id" type="radio" value="p" /> p</td>
             </tr>
                </table>
            </td>

            <td  style="width:250px;"><strong>Anemia</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td  style="width:55px;"><input name="in_anamia" id="in_anamia_id" type="radio" value="y" /> Y</td>
            <td  style="width:55px;"><input name="in_anamia" id="in_anamia_id" type="radio" value="n" /> N</td>
            <td ><input name="in_anamia" id="in_anamia_id" type="radio" value="p" /> p</td>
             </tr>
                </table>
            </td>

            
        </tr>
<tr>
            <td><strong>Deep leg pain</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_d_lpain" id="in_d_lpain_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_d_lpain" id="in_d_lpain_id" type="radio" value="n" /> N</td>
            <td ><input name="in_d_lpain" id="in_d_lpain_id" type="radio" value="p" /> p</td>
             </tr>
                </table>
            </td>

            <td><strong>Cold hands / feet</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_c_hands" id="in_c_hands_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_c_hands" id="in_c_hands_id" type="radio" value="n" /> N</td>
            <td ><input name="in_c_hands" id="in_c_hands_id" type="radio" value="p" /> p</td> 
             </tr>
                </table>
            </td>
           
        </tr>
        <tr>
            <td><strong>Varicose Veins</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_v_veins" id="in_v_veins_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_v_veins" id="in_v_veins_id" type="radio" value="n" /> N</td>
            <td ><input name="in_v_veins" id="in_v_veins_id" type="radio" value="p" /> p</td>
             </tr>
                </table>
            </td>

            <td><strong>Thrombophlebitis</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_trombo" id="in_trombo_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_trombo" id="in_trombo_id" type="radio" value="n" /> N</td>
            <td ><input name="in_trombo" id="in_trombo_id" type="radio" value="p" /> p</td>
             </tr>
                </table>
            </td>
            
        </tr>
        
        





</table>
    </div>

    <!genderBaseSection>
    
    
    
        <input type="hidden" name="action" value="saveintakepaperwork" />
        <input type="hidden" name="intake_last_page" value="8" />
        <input type="hidden" name="intake_compl_status" value="0" />
        <input type="hidden" name="nextPage" id="nextPage" value="8" />          
        <input type="hidden" name="closeChild" id="closeChild" value="1" />            
    
        <div align="center" class="form-footer"><input name="Button" type="button" value="Previous" onclick="return previous_page('intakeform', '7');"
  />&nbsp;<input name="Button" type="submit" value="Save & Exit" /> &nbsp;<input name="Button" type="button" value="Next" style="width:65px" onclick="return validate('intakeform', '9');"
  /></div>
</div>
</form>