<div style="position: absolute; top: 46px; left: 80px;width:350px;" >
    <b><!message></b>
    <br><br><br>
    <div style="position: absolute; top: 46px; left: 100px;">
    <form action="index.php?action=trialStatusChangeClinic" method="post" >
        <input type="hidden" name="confirm" value="yes" />
        <input type="hidden" name="clinic_id" value="<!clinic_id>" />
        <input type="submit" value="Confirm" />
        <input type="button" value="Cancel" onclick = "parent.parent.GB_hide();" />
    </form>
    </div>
    
</div>
