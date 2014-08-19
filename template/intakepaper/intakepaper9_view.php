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
    <div><strong>Is there anything else you would like to add or comment on?</strong></div>
    <div style="height:10px;"></div>
	<div><textarea name="in_comment" id="in_comment_id" style="width:450px; height:100px;" ></textarea><div id="div_in_comment_id"></div></div>
    
    
        <input type="hidden" name="action" value="view_intake_paperwork" />
        <input type="hidden" name="nextPage" id="nextPage" value="1" />
        <input type="hidden" name="id" id="id" value="<!id>" />                
            
        <div align="center" class="form-footer"></div>
</div>
</form>
