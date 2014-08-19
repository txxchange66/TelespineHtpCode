<link href="css/intake_form.css" rel="stylesheet" type="text/css"/>
<script src="js/jquery.js"></script>  
<script type="text/javascript" language="javascript">
    var data1={<!IntakeArrayData>};
    var patient_id='<!patient_id>';
</script>
<script type='text/javascript'>


var data1={<!IntakeArrayData>};
	$(document).ready(function() { 


		if(data1['in_cur_hcare']=='y')
		$("#in_cur_hcreN_id").attr("disabled","disabled");
		if(data1['in_cur_hcare']=='n')
		$("#in_cur_hcreY_id").attr("disabled","disabled");
		if(data1['in_knwn_dis']=='N')
		$("#in_knw_dis_w_id").attr("disabled","disabled");
	}); 




</script>
<script src="js/fill_intake_form.js"></script>  
<div id="msg" style="color:#f00; font-size:17px;" >*</div>


<form name="intakeform" id="intakeform" action="index.php" method="post" >

<div class="wrapper" style="padding-bottom:20px; padding-top:20px; padding-left:20px;">
<h1>ADULT INTAKE FORM</h1>
<!navigation_header>
<p>Wellness is a balance of many factors. By choosing a number in each scale, rate your level of satisfaction in each area of your life. For example, if you are extremely happy with your career, choose 9 or 10. Do the same for each area of your life.</p>

<div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form3">
        <tr class="radioCustom">
            <td style="width:200px;"><strong>Physical Environment:</strong></td>
            <td ><input name="in_phy_env" id="in_phy_env_id" type="radio" style="vertical-align: middle; " value="1" /> 1</td>
            <td ><input name="in_phy_env" id="in_phy_env_id" type="radio" value="2" /> 2</td>
            <td ><input name="in_phy_env" id="in_phy_env_id" type="radio" value="3" /> 3</td>
            <td ><input name="in_phy_env" id="in_phy_env_id" type="radio" value="4" /> 4</td>
            <td ><input name="in_phy_env" id="in_phy_env_id" type="radio" value="5" /> 5</td>
            <td ><input name="in_phy_env" id="in_phy_env_id" type="radio" value="6" /> 6</td>
            <td ><input name="in_phy_env" id="in_phy_env_id" type="radio" value="7" /> 7</td>
            <td ><input name="in_phy_env" id="in_phy_env_id" type="radio" value="8" /> 8</td>
            <td ><input name="in_phy_env" id="in_phy_env_id" type="radio" value="9" /> 9</td>
            <td ><input name="in_phy_env" id="in_phy_env_id" type="radio" value="10" /> 10</td>
                    </tr>
        <tr class="radioCustom">
            <td style="width:200px;"><strong>Career:</strong></td>
            <td ><input name="in_career" id="in_career_id" type="radio" style="vertical-align: middle; " value="1" /> 1</td>
            <td ><input name="in_career" id="in_career_id" type="radio" value="2" /> 2</td>
            <td ><input name="in_career" id="in_career_id" type="radio" value="3" /> 3</td>
            <td ><input name="in_career" id="in_career_id" type="radio" value="4" /> 4</td>
            <td ><input name="in_career" id="in_career_id" type="radio" value="5" /> 5</td>
            <td ><input name="in_career" id="in_career_id" type="radio" value="6" /> 6</td>
            <td ><input name="in_career" id="in_career_id" type="radio" value="7" /> 7</td>
            <td ><input name="in_career" id="in_career_id" type="radio" value="8" /> 8</td>
            <td ><input name="in_career" id="in_career_id" type="radio" value="9" /> 9</td>
            <td ><input name="in_career" id="in_career_id" type="radio" value="10" /> 10</td>
                    </tr>
        <tr class="radioCustom">
            <td style="width:200px;"><strong>Money:</strong></td>
            <td ><input name="in_money" id="in_money_id" type="radio" style="vertical-align: middle; " value="1" /> 1</td>
            <td ><input name="in_money" id="in_money_id" type="radio" value="2" /> 2</td>
            <td ><input name="in_money" id="in_money_id" type="radio" value="3" /> 3</td>
            <td ><input name="in_money" id="in_money_id" type="radio" value="4" /> 4</td>
            <td ><input name="in_money" id="in_money_id" type="radio" value="5" /> 5</td>
            <td ><input name="in_money" id="in_money_id" type="radio" value="6" /> 6</td>
            <td ><input name="in_money" id="in_money_id" type="radio" value="7" /> 7</td>
            <td ><input name="in_money" id="in_money_id" type="radio" value="8" /> 8</td>
            <td ><input name="in_money" id="in_money_id" type="radio" value="9" /> 9</td>
            <td ><input name="in_money" id="in_money_id" type="radio" value="10" /> 10</td>
                    </tr>
        <tr class="radioCustom">
            <td style="width:200px;"><strong>Health:</strong></td>
            <td ><input name="in_health" id="in_health_id" type="radio" style="vertical-align: middle; " value="1" /> 1</td>
            <td ><input name="in_health" id="in_health_id" type="radio" value="2" /> 2</td>
            <td ><input name="in_health" id="in_health_id" type="radio" value="3" /> 3</td>
            <td ><input name="in_health" id="in_health_id" type="radio" value="4" /> 4</td>
            <td ><input name="in_health" id="in_health_id" type="radio" value="5" /> 5</td>
            <td ><input name="in_health" id="in_health_id" type="radio" value="6" /> 6</td>
            <td ><input name="in_health" id="in_health_id" type="radio" value="7" /> 7</td>
            <td ><input name="in_health" id="in_health_id" type="radio" value="8" /> 8</td>
            <td ><input name="in_health" id="in_health_id" type="radio" value="9" /> 9</td>
            <td ><input name="in_health" id="in_health_id" type="radio" value="10" /> 10</td>
                    </tr>
          <tr class="radioCustom">
            <td style="width:200px;"><strong>Significant Other/Romance:</strong></td>
            <td ><input name="in_romance" id="in_romance_id" type="radio" style="vertical-align: middle; " value="1" /> 1</td>
            <td ><input name="in_romance" id="in_romance_id" type="radio" value="2" /> 2</td>
            <td ><input name="in_romance" id="in_romance_id" type="radio" value="3" /> 3</td>
            <td ><input name="in_romance" id="in_romance_id" type="radio" value="4" /> 4</td>
            <td ><input name="in_romance" id="in_romance_id" type="radio" value="5" /> 5</td>
            <td ><input name="in_romance" id="in_romance_id" type="radio" value="6" /> 6</td>
            <td ><input name="in_romance" id="in_romance_id" type="radio" value="7" /> 7</td>
            <td ><input name="in_romance" id="in_romance_id" type="radio" value="8" /> 8</td>
            <td ><input name="in_romance" id="in_romance_id" type="radio" value="9" /> 9</td>
            <td ><input name="in_romance" id="in_romance_id" type="radio" value="10" /> 10</td>
                    </tr>
        <tr class="radioCustom">
            <td style="width:200px;"><strong>Fun & Recreation:</strong></td>
            <td ><input name="in_fun" id="in_fun_id" type="radio" style="vertical-align: middle; " value="1" /> 1</td>
            <td ><input name="in_fun" id="in_fun_id" type="radio" value="2" /> 2</td>
            <td ><input name="in_fun" id="in_fun_id" type="radio" value="3" /> 3</td>
            <td ><input name="in_fun" id="in_fun_id" type="radio" value="4" /> 4</td>
            <td ><input name="in_fun" id="in_fun_id" type="radio" value="5" /> 5</td>
            <td ><input name="in_fun" id="in_fun_id" type="radio" value="6" /> 6</td>
            <td ><input name="in_fun" id="in_fun_id" type="radio" value="7" /> 7</td>
            <td ><input name="in_fun" id="in_fun_id" type="radio" value="8" /> 8</td>
            <td ><input name="in_fun" id="in_fun_id" type="radio" value="9" /> 9</td>
            <td ><input name="in_fun" id="in_fun_id" type="radio" value="10" /> 10</td>
                    </tr>
        <tr class="radioCustom">
            <td style="width:200px;"><strong>Personal Growth:</strong></td>
            <td ><input name="in_growth" id="in_growth_id" type="radio" style="vertical-align: middle; " value="1" /> 1</td>
            <td ><input name="in_growth" id="in_growth_id" type="radio" value="2" /> 2</td>
            <td ><input name="in_growth" id="in_growth_id" type="radio" value="3" /> 3</td>
            <td ><input name="in_growth" id="in_growth_id" type="radio" value="4" /> 4</td>
            <td ><input name="in_growth" id="in_growth_id" type="radio" value="5" /> 5</td>
            <td ><input name="in_growth" id="in_growth_id" type="radio" value="6" /> 6</td>
            <td ><input name="in_growth" id="in_growth_id" type="radio" value="7" /> 7</td>
            <td ><input name="in_growth" id="in_growth_id" type="radio" value="8" /> 8</td>
            <td ><input name="in_growth" id="in_growth_id" type="radio" value="9" /> 9</td>
            <td ><input name="in_growth" id="in_growth_id" type="radio" value="10" /> 10</td>
                    </tr>
        <tr class="radioCustom">
            <td style="width:200px;"><strong>Family & Friends:</strong></td>
            <td ><input name="in_fam" id="in_fam_id" type="radio" style="vertical-align: middle; " value="1" /> 1</td>
            <td ><input name="in_fam" id="in_fam_id" type="radio" value="2" /> 2</td>
            <td ><input name="in_fam" id="in_fam_id" type="radio" value="3" /> 3</td>
            <td ><input name="in_fam" id="in_fam_id" type="radio" value="4" /> 4</td>
            <td ><input name="in_fam" id="in_fam_id" type="radio" value="5" /> 5</td>
            <td ><input name="in_fam" id="in_fam_id" type="radio" value="6" /> 6</td>
            <td ><input name="in_fam" id="in_fam_id" type="radio" value="7" /> 7</td>
            <td ><input name="in_fam" id="in_fam_id" type="radio" value="8" /> 8</td>
            <td ><input name="in_fam" id="in_fam_id" type="radio" value="9" /> 9</td>
            <td ><input name="in_fam" id="in_fam_id" type="radio" value="10" /> 10</td>
                    </tr>
</table>
        </div>
    <div>
    <div style="height:10px;"></div>
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form3-bot">
        <tr class="radioCustom">
            <td class="radio"><strong>Are you currently receiving health care?</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input name="in_cur_hcare" id="in_cur_hcare_id_y" type="radio" value='y'/> Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="in_cur_hcare" id="in_cur_hcare_id_n" type="radio" value='n'/> No</td>
        </tr>
        
        <tr>
            <td><strong>If yes, where and from whom:</strong></td>
        </tr>
        <tr>
            <td><textarea name="in_cur_hcreY" id="in_cur_hcreY_id" ></textarea></td>
        </tr>
        <tr>
            <td><strong>If no, when and where did you last receive medical or health care?</strong></td>
        </tr>
        <tr>
            <td><textarea name="in_cur_hcreN" id="in_cur_hcreN_id" ></textarea></td>
        </tr>
        
        
        <tr>
            <td><strong>What was the reason?</strong></td>
        </tr>
        <tr>
        <td><textarea name="in_reason" id="in_reason_id" ></textarea></td>
        </tr>
        <tr>
            <td><strong>What are your most important health problems? List as many as you can in order of importance:</strong></td>
        </tr>
        <tr class="mostImpProb">
        <tr>
            <td><input name="in_h_prob_1" id="in_h_prob_1_id" type="text" class="nonmandatorytextboxother" maxlength="254"/></td>
        </tr>
<tr>
            <td><input name="in_h_prob_2" id="in_h_prob_2_id" type="text" class="nonmandatorytextboxother" maxlength="254"/></td>
        </tr>
<tr>
            <td><input name="in_h_prob_3" id="in_h_prob_3_id" type="text" class="nonmandatorytextboxother" maxlength="254"/></td>
        </tr>
<tr>
            <td><input name="in_h_prob_4" id="in_h_prob_4_id" type="text" class="nonmandatorytextboxother" maxlength="254"/></td>
        </tr>
<tr>
            <td><input name="in_h_prob_5" id="in_h_prob_5_id" type="text" class="nonmandatorytextboxother" maxlength="254"/></td>
        </tr>
<tr>
            <td><input name="in_h_prob_6" id="in_h_prob_6_id" type="text" class="nonmandatorytextboxother" maxlength="254"/></td>
        </tr>
<tr>
            <td><input name="in_h_prob_7" id="in_h_prob_7_id" type="text" class="nonmandatorytextboxother" maxlength="254"/></td>
        </tr>
        </tr>

<tr class="radioCustom">
            <td class="radio"><strong>Do you have any known contagious diseases at this time?</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input name="in_knwn_dis" id="in_knwn_dis_id_y"  type="radio" value="Y" /> Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="in_knwn_dis" id="in_knwn_dis_id_n" type="radio" value="N" /> No</td>
        </tr>
<tr>
            <td><strong>If yes, what?</strong></td>
        </tr>
        <tr>
            <td><textarea name="in_knw_dis_w" id="in_knw_dis_w_id"></textarea></td>
        </tr>

    </table>
    
    </div>
    
        <input type="hidden" name="action" value="saveintakepaperwork" />
        <input type="hidden" name="intake_last_page" value="3" />
        <input type="hidden" name="intake_compl_status" value="0" />
        <input type="hidden" name="nextPage" id="nextPage" value="3" />                
        <input type="hidden" name="closeChild" id="closeChild" value="1" />                
        
        <div align="center" class="form-footer"><input name="Button" type="button" value="Previous" onclick="return previous_page('intakeform', '2');"
 />&nbsp;<input name="Button" type="submit" value="Save & Exit" /> &nbsp;<input name="Button" type="button" style="width:65px" value="Next"onclick="return validate('intakeform', '4');"
  /></div>
</div>
</form>