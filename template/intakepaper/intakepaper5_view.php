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
<h3>GENERAL</h3>

<div style="height:10px;"></div>
    <div>
    
    
    
        
<table cellpadding="0" cellspacing="0" style="width: 100%" class="form5">
            <tr>
                <td style="width:150px;"><strong>Height:</strong></td>
                <td style="width:95px;"> <input name="in_height" id="in_height_id" type="text" /><div id="div_in_height_id"></div> 
                <!--<select name="in_height" id="in_height_id" >
                        <optgroup label="Inches">
                        <!heightInch></optgroup>
                        <optgroup label="Cm">
                        <!heightCms></optgroup>
                    </select>-->
                    
                     </td>
                <td style="width:95px;">inches/cm</td>
                <td><strong>Weight:</strong></td>
                <td style="width:95px;"><input name="in_weight" id="in_weight_id" type="text" /><div id="div_in_weight_id"></div>
                <!-- <select name="in_weight" id="in_weight_id" >
                        <optgroup label="Lbs">
                        <!weightLbs></optgroup>
                        <optgroup label="Kg">
                        <!weightKg></optgroup>
                    </select>-->
                
                </td>
                <td style="width:95px;">lbs/kg</td>
                <td style="width:150px;"><strong>Weight 1 year ago:</strong></td>
                <td style="width:93px;"><input name="in_weight_one_year" id="in_weight_one_year_id" type="text" /><div id="div_in_weight_one_year_id"></div></td>
                <td>lbs.</td>
            </tr>
            <tr>
                <td><strong>Maximum Weight:</strong></td>
                <td><input name="in_max_weight" id="in_max_weight_id" type="text" /><div id="div_in_max_weight_id"></div>
                <!--<select name="in_max_weight" id="in_max_weight_id" >
                        <optgroup label="Lbs">
                        <!weightLbs></optgroup>
                        <optgroup label="Kg">
                        <!weightKg></optgroup>
                    </select>-->

                </td>
                <td>lbs/kg</td>
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
                    <td style="width:315px;"><strong>Do you exercise?</strong></td>
                    <td class="radio"><input name="in_exercise" id="in_exercise_id" type="radio" value="y" /> Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="in_exercise" id="in_exercise_id" type="radio" value="n" /> No</td>
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
            <td style="width:100px;">&nbsp;</td>            
            
                  
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
            <td class="radio" style="width: 60px"><input name="in_coffee" id="in_coffee_id" type="radio" value="y" /> Y</td>
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
</form>
