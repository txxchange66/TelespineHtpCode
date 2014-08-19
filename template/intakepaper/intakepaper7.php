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
            <td  colspan="3" class="radio">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td  style="width:55px;"><input name="in_freq_sore" id="in_freq_sore_id" type="radio" value="y" /> Y</td>
            <td  style="width:55px;"><input name="in_freq_sore" id="in_freq_sore_id" type="radio" value="n" /> N</td>
            <td  ><input name="in_freq_sore" id="in_freq_sore_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>

            <td  style="width:250px;"><strong>Copious saliva</strong></td>
            <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_copi_sal" id="in_copi_sal_id" type="radio" value="y" /> Y</td>
            <td  style="width:55px;"><input name="in_copi_sal" id="in_copi_sal_id" type="radio" value="n" /> N</td>
            <td ><input name="in_copi_sal" id="in_copi_sal_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>

            
        </tr>
        <tr>
            <td><strong>Teeth grinding</strong></td>
            <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;" ><input name="in_teeth" id="in_teeth_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_teeth" id="in_teeth_id" type="radio" value="n" /> N</td>
            <td><input name="in_teeth" id="in_teeth_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>

            <td><strong>Sore tongue / lips</strong></td>
            <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_s_tongue" id="in_s_tongue_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_s_tongue" id="in_s_tongue_id" type="radio" value="n" /> N</td>
            <td ><input name="in_s_tongue" id="in_s_tongue_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>
            
        </tr>
        <tr>
            <td><strong>Gum problems</strong></td>
            <td   class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_gum_prob" id="in_gum_prob_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_gum_prob" id="in_gum_prob_id" type="radio" value="n" /> N</td>
            <td ><input name="in_gum_prob" id="in_gum_prob_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>

            <td><strong>Hoarseness</strong></td>
            <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_hoars" id="in_hoars_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_hoars" id="in_hoars_id" type="radio" value="n" /> N</td>
            <td ><input name="in_hoars" id="in_hoars_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>
            
        </tr>
        <tr>
            <td><strong>Dental cavities</strong></td>
            <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_den_cavi" id="in_den_cavi_id"  type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_den_cavi" id="in_den_cavi_id" type="radio" value="n" /> N</td>
            <td ><input name="in_den_cavi" id="in_den_cavi_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>

            <td><strong>Jaw clicks</strong></td>
            <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_jaw_clic" id="in_jaw_clic_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_jaw_clic" id="in_jaw_clic_id" type="radio" value="n" /> N</td>
            <td ><input name="in_jaw_clic" id="in_jaw_clic_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>
            
        </tr>
        
        
        

    </table>
    </div>    
    
    <h3>NECK</h3>
    <div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Lumps</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td  style="width:55px;"><input name="in_lumps_neck" id="in_lumps_neck_id" type="radio" value="y" /> Y</td>
            <td  style="width:55px;"><input name="in_lumps_neck" id="in_lumps_neck_id" type="radio" value="n" /> N</td>
            <td ><input name="in_lumps_neck" id="in_lumps_neck_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

            <td  style="width:250px;"><strong>Swollen glands</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td  style="width:55px;"><input name="in_swollen" id="in_swollen_id" type="radio" value="y" /> Y</td>
            <td  style="width:55px;"><input name="in_swollen" id="in_swollen_id" type="radio" value="n" /> N</td>
            <td ><input name="in_swollen" id="in_swollen_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

            
        </tr>
<tr>
            <td><strong>Goiter</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_goit" id="in_goit_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_goit" id="in_goit_id" type="radio" value="n" /> N</td>
            <td ><input name="in_goit" id="in_goit_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

            <td><strong>Pain or stiffness</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_pain_stif" id="in_pain_stif_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_pain_stif" id="in_pain_stif_id" type="radio" value="n" /> N</td>
            <td ><input name="in_pain_stif" id="in_pain_stif_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>
            
        </tr>
        
</table>
    </div>
    <h3>RESPIRATORY</h3>
    <div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Cough</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td  style="width:55px;"><input name="in_cough" id="in_cough_id" type="radio" value="y" /> Y</td>
            <td  style="width:55px;"><input name="in_cough" id="in_cough_id" type="radio" value="n" /> N</td>
            <td  ><input name="in_cough" id="in_cough_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>

            <td  style="width:250px;"><strong>Sputum</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td  style="width:55px;"><input name="in_sput" id="in_sput_id" type="radio" value="y" /> Y</td>
            <td  style="width:55px;"><input name="in_sput" id="in_sput_id" type="radio" value="n" /> N</td>
            <td ><input name="in_sput" id="in_sput_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>

            
        </tr>
<tr>
            <td><strong>Spitting up blood</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_spit_bld" id="in_spit_bld_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_spit_bld" id="in_spit_bld_id" type="radio" value="n" /> N</td>
            <td ><input name="in_spit_bld" id="in_spit_bld_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>

            <td><strong>Wheezing</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_wheez" id="in_wheez_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_wheez" id="in_wheez_id" type="radio" value="n" /> N</td>
            <td ><input name="in_wheez" id="in_wheez_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>
            
        </tr>
        <tr>
            <td><strong>Asthma</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_asthma" id="in_asthma_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_asthma" id="in_asthma_id" type="radio" value="n" /> N</td>
            <td ><input name="in_asthma" id="in_asthma_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>

            <td><strong>Bronchitis</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_bronch" id="in_bronch_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_bronch" id="in_bronch_id" type="radio" value="n" /> N</td>
            <td ><input name="in_bronch" id="in_bronch_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>
            
        </tr>
        <tr>
            <td><strong>Pneumonia</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_pneumo" id="in_pneumo_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_pneumo" id="in_pneumo_id" type="radio" value="n" /> N</td>
            <td ><input name="in_pneumo" id="in_pneumo_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>

            <td><strong>Pleurisy</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_Pleuri" id="in_Pleuri_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_Pleuri" id="in_Pleuri_id" type="radio" value="n" /> N</td>
            <td ><input name="in_Pleuri" id="in_Pleuri_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>
            
        </tr>
        <tr>
            <td><strong>Emphysema</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_emph" id="in_emph_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_emph" id="in_emph_id" type="radio" value="n" /> N</td>
            <td ><input name="in_emph" id="in_emph_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>

            <td><strong>Difficulty breathing</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_diff_brth" id="in_diff_brth_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_diff_brth" id="in_diff_brth_id" type="radio" value="n" /> N</td>
            <td ><input name="in_diff_brth" id="in_diff_brth_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>
            
        </tr>

<tr>
            <td><strong>Pain on breathing</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_pain_brth" id="in_pain_brth_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_pain_brth" id="in_pain_brth_id" type="radio" value="n" /> N</td>
            <td ><input name="in_pain_brth" id="in_pain_brth_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>

            <td><strong>Shortness of breath</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_sh_brth" id="in_sh_brth_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_sh_brth" id="in_sh_brth_id" type="radio" value="n" /> N</td>
            <td ><input name="in_sh_brth" id="in_sh_brth_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>
            
        </tr>

<tr>
            <td><strong>Shortness of breath at night</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_sh_nbrth" id="in_sh_nbrth_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_sh_nbrth" id="in_sh_nbrth_id" type="radio" value="n" /> N</td>
            <td ><input name="in_sh_nbrth" id="in_sh_nbrth_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>

            <td><strong>Shortness of breath when<br/>lying down</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_sh_lybrth" id="in_sh_lybrth_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_sh_lybrth" id="in_sh_lybrth_id" type="radio" value="n" /> N</td>
            <td ><input name="in_sh_lybrth" id="in_sh_lybrth_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>
            
        </tr>

<tr>
            <td><strong>Tuberculosis</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_tuber" id="in_tuber_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_tuber" id="in_tuber_id" type="radio" value="n" /> N</td>
            <td ><input name="in_tuber" id="in_tuber_id" type="radio" value="p" /> P</td>
            </tr>
                </table>
            </td>

            <td><strong>&nbsp;</strong></td>
            <td > &nbsp;</td>
            <td > &nbsp;</td>
            <td > &nbsp;</td>        
        </tr>


</table>
    </div>
    <h3>CARDLOVASCULAR</h3>
    <div>
<div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
        <tr>
            <td style="width:250px;"><strong>Heart disease</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td  style="width:55px;"><input name="in_heart" id="in_heart_id" type="radio" value="y" /> Y</td>
            <td  style="width:55px;"><input name="in_heart" id="in_heart_id" type="radio" value="n" /> N</td>
            <td  ><input name="in_heart" id="in_heart_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

            <td  style="width:250px;"><strong>Angina</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td  style="width:55px;"><input name="in_angina" id="in_angina_id" type="radio" value="y" /> Y</td>
            <td  style="width:55px;"><input name="in_angina" id="in_angina_id" type="radio" value="n" /> N</td>
            <td ><input name="in_angina" id="in_angina_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

            
        </tr>
<tr>
            <td><strong>High / Low Blood Pressure</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_bp" id="in_bp_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_bp" id="in_bp_id" type="radio" value="n" /> N</td>
            <td ><input name="in_bp" id="in_bp_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

            <td><strong>Murmurs</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_murm" id="in_murm_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_murm" id="in_murm_id" type="radio" value="n" /> N</td>
            <td ><input name="in_murm" id="in_murm_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>
            
        </tr>
        <tr>
            <td><strong>Blood clots</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_b_clots" id="in_b_clots_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_b_clots" id="in_b_clots_id" type="radio" value="n" /> N</td>
            <td ><input name="in_b_clots" id="in_b_clots_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

            <td><strong>Fainting</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_faint" id="in_faint_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_faint" id="in_faint_id" type="radio" value="n" /> N</td>
            <td ><input name="in_faint" id="in_faint_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>
            
        </tr>
        <tr>
            <td><strong>Phlebitis</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_phleb" id="in_phleb_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_phleb" id="in_phleb_id" type="radio" value="n" /> N</td>
            <td ><input name="in_phleb" id="in_phleb_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

            <td><strong>Palpitations / Fluttering</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_palpi" id="in_palpi_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_palpi" id="in_palpi_id" type="radio" value="n" /> N</td>
            <td ><input name="in_palpi" id="in_palpi_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>
            
        </tr>
        <tr>
            <td><strong>Rheumatic Fever</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_rheu" id="in_rheu_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_rheu" id="in_rheu_id" type="radio" value="n" /> N</td>
            <td ><input name="in_rheu" id="in_rheu_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

            <td><strong>Chest Pain</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_chest_p" id="in_chest_p_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_chest_p" id="in_chest_p_id" type="radio" value="n" /> N</td>
            <td ><input name="in_chest_p" id="in_chest_p_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>
            
        </tr>

<tr>
            <td><strong>Swelling in ankles</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_swel" id="in_swel_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_swel" id="in_swel_id" type="radio" value="n" /> N</td>
            <td ><input name="in_swel" id="in_swel_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

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
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td  style="width:55px;"><input name="in_tr_swall" id="in_tr_swall_id" type="radio" value="y" /> Y</td>
            <td  style="width:55px;"><input name="in_tr_swall" id="in_tr_swall_id" type="radio" value="n" /> N</td>
            <td  ><input name="in_tr_swall" id="in_tr_swall_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

            <td  style="width:250px;"><strong>Heartburn</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td  style="width:55px;"><input name="in_hburn" id="in_hburn_id" type="radio" value="y" /> Y</td>
            <td  style="width:55px;"><input name="in_hburn" id="in_hburn_id" type="radio" value="n" /> N</td>
            <td ><input name="in_hburn" id="in_hburn_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

            
        </tr>
<tr>
            <td><strong>Change in thirst</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_thirst" id="in_thirst_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_thirst" id="in_thirst_id" type="radio" value="n" /> N</td>
            <td ><input name="in_thirst" id="in_thirst_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

            <td><strong>Abdominal pain or cramps</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_ab_pain" id="in_ab_pain_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_ab_pain" id="in_ab_pain_id" type="radio" value="n" /> N</td>
            <td ><input name="in_ab_pain" id="in_ab_pain_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>
            
        </tr>
        <tr>
            <td><strong>Change in appetite</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_ch_appe" id="in_ch_appe_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_ch_appe" id="in_ch_appe_id" type="radio" value="n" /> N</td>
            <td ><input name="in_ch_appe" id="in_ch_appe_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

            <td><strong>Belching or passing gas</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_bl_gas" id="in_bl_gas_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_bl_gas" id="in_bl_gas_id" type="radio" value="n" /> N</td>
            <td ><input name="in_bl_gas" id="in_bl_gas_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>
            
        </tr>
        <tr>
            <td><strong>Nausea / vomiting</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_vomit" id="in_vomit_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_vomit" id="in_vomit_id" type="radio" value="n" /> N</td>
            <td ><input name="in_vomit" id="in_vomit_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

            <td><strong>Constipation</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_consti" id="in_consti_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_consti" id="in_consti_id" type="radio" value="n" /> N</td>
            <td ><input name="in_consti" id="in_consti_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>
            
        </tr>
        <tr>
            <td><strong>Ulcer</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_ulser" id="in_ulser_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_ulser" id="in_ulser_id" type="radio" value="n" /> N</td>
            <td ><input name="in_ulser" id="in_ulser_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

            <td><strong>Diarrhea</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_diarr" id="in_diarr_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_diarr" id="in_diarr_id" type="radio" value="n" /> N</td>
            <td ><input name="in_diarr" id="in_diarr_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>
            
        </tr>

<tr>
            <td><strong>Jaundice (yellow skin)</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_jaundice" id="in_jaundice_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_jaundice" id="in_jaundice_id" type="radio" value="n" /> N</td>
            <td ><input name="in_jaundice" id="in_jaundice_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

            <td><strong>Bowel Movements: How often?</strong></td>
            <td colspan="3"><input name="in_bowel" id="in_bowel_id" type="text" maxlength="254" style="width:130px;"/></td>
                        
        </tr>

<tr>
            <td><strong>Gall Bladder disease</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_gall_dis" id="in_gall_dis_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_gall_dis" id="in_gall_dis_id" type="radio" value="n" /> N</td>
            <td ><input name="in_gall_dis" id="in_gall_dis_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

            <td><strong>Is this a change?</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_is_change" id="in_is_change_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_is_change" id="in_is_change_id" type="radio" value="n" /> N</td>
            <td ><input name="in_is_change" id="in_is_change_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>
            
        </tr>

<tr>
            <td><strong>Liver Disease</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_liv_dis" id="in_liv_dis_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_liv_dis" id="in_liv_dis_id" type="radio" value="n" /> N</td>
            <td ><input name="in_liv_dis" id="in_liv_dis_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

            <td><strong>Black stools</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_b_stool" id="in_b_stool_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_b_stool" id="in_b_stool_id" type="radio" value="n" /> N</td>
            <td ><input name="in_b_stool" id="in_b_stool_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>
            
        </tr>

<tr>
            <td><strong>Hemorhoids</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_hermor" id="in_hermor_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_hermor" id="in_hermor_id" type="radio" value="n" /> N</td>
            <td ><input name="in_hermor" id="in_hermor_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>

            <td><strong>Blood / Mucus in stools</strong></td>
             <td  class="radio" colspan="3">
                <table cellpadding="0" cellspacing="0" style="width: 100%" class="form5-habits">
                    <tr class="radioCustom">

            <td style="width:55px;"><input name="in_bl_mucus" id="in_bl_mucus_id" type="radio" value="y" /> Y</td>
            <td style="width:55px;"><input name="in_bl_mucus" id="in_bl_mucus_id" type="radio" value="n" /> N</td>
            <td ><input name="in_bl_mucus" id="in_bl_mucus_id" type="radio" value="p" /> P</td>
             </tr>
                </table>
            </td>
            
        </tr>


</table>
    </div>
    
    
    
    
        <input type="hidden" name="action" value="saveintakepaperwork" />
        <input type="hidden" name="intake_last_page" value="7" />
        <input type="hidden" name="intake_compl_status" value="0" />
        <input type="hidden" name="nextPage" id="nextPage" value="7" />         
        <input type="hidden" name="closeChild" id="closeChild" value="1" />            
    
    
        <div align="center" class="form-footer"><input name="Button" type="button" value="Previous" onclick="return previous_page('intakeform', '6');"
  />&nbsp;<input name="Button" type="submit" value="Save & Exit" /> &nbsp;<input name="Button" type="button" value="Next" style="width:65px" onclick="return validate('intakeform', '8');"
  /></div>
</div>
</form>