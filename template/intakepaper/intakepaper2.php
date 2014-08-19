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
<h2>CONTEXT OF CARE REVIEW</h2>
<p>Successful health care and preventive medicine are only possible when the physician has a complete understanding of the patient physically, mentally and emotionally. The nature of your responses to the following questions will go along way in assisting our understanding of your truest desires. Your time, thoughtfulness and honesty in completing this overview will greatly aid us to assist your health needs.</p>
<div>
    
    <table cellpadding="0" cellspacing="0" style="width: 100%" class="form2-name">
        <tr>
            <td><strong>Why did you choose to come to us?</strong></td>
        </tr>
        <tr>
            <td><input name="in_w_cm" id="in_w_cm_id" type="text" maxlength="254"/></td>
        </tr>
        <tr>
            <td><strong>What do you know of our approach to wellness?</strong></td>
        </tr>
        <tr>
            <td><input name="in_w_appr" id="in_w_appr_id" type="text" maxlength="254" /></td>
        </tr>
        <tr>
            <td><strong>What three (3) expectations do you have from this visit?</strong></td>
        </tr>
        <tr>
            <td><input name="in_w_expct_1" id="in_w_expct_1_id" type="text" maxlength="254"/></td>
        </tr>
        <tr><td><input name="in_w_expct_2" id="in_w_expct_2_id" type="text" maxlength="254"/></td></tr>
        <tr><td><input name="in_w_expct_3" id="in_w_expct_3_id" type="text" maxlength="254"/></td></tr>
        <tr>
            <td><strong>What long term expectations do you have of our doctor?</strong></td>
        </tr>
        <tr>
            <td><input name="in_w_term" id="in_w_term_id" type="text" maxlength="254"/></td>
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
            <td><input name="in_behave_s"  id="in_behave_id_s" type="text" maxlength="254"/></td>
        </tr>
        <tr>
            <td><strong>What behaviors / habits do you currently engage in regularly that you believe are self-destructive?</strong></td>
        </tr>
        <tr>
            <td><input name="in_behave_d" id="in_behave_d_id" type="text" maxlength="254"/></td>
        </tr>
        <tr>
            <td><strong>What potential obstacles do you foresee in addressing the lifestyle factors which are undermining your health and in<br/>
adhering to the therapeutic protocols which we will be sharing with you?</strong></td>
        </tr>
        <tr>
            <td><input name="in_pot_obst" id="in_pot_obst_id" type="text" maxlength="254"/></td>
        </tr>
        
        
        <tr>
            <td><strong>Who will sincerely support you consistently with the beneficial lifestyle changes you will be making?</strong></td>
        </tr>
        <tr>
            <td><input name="in_support" id="in_support_id" type="text" maxlength="254" /></td>
        </tr>
    </table>
    
    </div>
    
        
        <input type="hidden" name="action" value="saveintakepaperwork" />
        <input type="hidden" name="intake_last_page" value="2" />
        <input type="hidden" name="intake_compl_status" value="0" />
        <input type="hidden" name="nextPage" id="nextPage" value="2" />        
        <input type="hidden" name="closeChild" id="closeChild" value="1" />                
        
        <div align="center" class="form-footer"><input name="Button" type="Button" value="Previous" onclick="return previous_page('intakeform', '1');"
  />&nbsp;<input name="Button" type="submit" value="Save & Exit" /> &nbsp;<input name="Button" type="button" style="width:65px" value="Next" onclick="return validate('intakeform', '3');"
 /></div>
</div>
</form>