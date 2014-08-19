<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" language="javascript">
	var data1={<!IntakeArrayData>};
	var patient_id='<!patient_id>';
</script>
<script type="text/javascript" language="javascript" src= "js/intake_form.js"></script>
<link href="css/intake_form.css" rel="stylesheet" type="text/css"/>
<style>
input { border:none;}
</style>
<div id="msg" style="border:#333 1px solid; font-size:10px; background:#ccc; padding:2px 5px; color:#000; display:none;position:absolute;">Please Answer this question.</div>                        
<form name="intakeform" id="intakeform" action="" method="post" onsubmit="return validate();" >
<div class="wrapper">
	<table cellpadding="0" cellspacing="0" style="width: 100%">
		<tr>
			<td style="width: 200px"><span class="top-Name"><!patientName> </span></td>
			<td style="width: 630px;"><h1>ADULT INTAKE FORM</h1></td>
			<td class="date" ><!IntakeCreatedDate><br/><a href="index.php?action=view_intake_paperwork&printform=yes&patient_id=<!patient_id>" target="_blank" ><img src="images/img-print.jpg" style="border:none;" /></a></td>
		</tr>
	</table>
<!navigation_header>
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
</form>
