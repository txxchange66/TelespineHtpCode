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
            <td class="radio"><strong>Are you currently receiving health care?</strong> <input name="in_cur_hcare" id="in_3_9_id" type="radio" value="y"/> Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="in_cur_hcare" id="in_3_9_id" type="radio" value="n"/> No</td>
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
</form>