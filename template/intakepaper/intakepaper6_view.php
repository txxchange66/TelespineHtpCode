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
</form>
