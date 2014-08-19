<script src="js/jquery.js"></script>  
<script type="text/javascript" language="javascript">
    var data1={<!IntakeArrayData>};
    var patient_id='<!patient_id>';
</script>
<script src="js/fill_intake_form.js"></script>  
<link href="css/intake_form.css" rel="stylesheet" type="text/css"/>
<form name="intakeform" id="intakeform" action="" method="post" >

<div class="wrapper" style="padding-bottom:20px; padding-top:20px; padding-left:20px;">
<h1>ADULT INTAKE FORM</h1>
<!navigation_header>
<div style="height:20px;"></div>
    <div><strong>Is there anything else you would like to add or comment on?</strong></div>
    <div style="height:10px;"></div>
<div><textarea name="in_comment" id="in_comment_id" style="width:450px; height:100px;" class="nonmandatorytextarea"></textarea></div>
    
    
        <input type="hidden" name="action" value="saveintakepaperwork" />
        <input type="hidden" name="intake_last_page" value="9" />
        <input type="hidden" name="intake_compl_status" value="1" />
        <input type="hidden" name="nextPage" id="nextPage" value="9" />            
        <input type="hidden" name="closeChild" id="closeChild" value="1" />        
            
        <div align="center" class="form-footer"><input name="Button" type="button" value="Previous" onclick="return previous_page('intakeform', '8');"
 />&nbsp;<input name="Button" type="submit" value="Save & Submit" /> </div>
</div>
</form>
