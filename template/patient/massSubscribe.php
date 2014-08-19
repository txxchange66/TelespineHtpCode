<div style="position: absolute; top: 15px; left: 30px;width:450px;" >
    <b><!message></b>
    <br><br><br>
    <div style="position: absolute; top: 120px; left: 150px;">
    <form action="/index.php?action=patient_mass_subscribe" method="post" >
        <input type="hidden" name="confirm" value="yes" />
        <input type="hidden" name="mass_message_access" value="<!mass_message_access>" />
		<input type="submit" value="Confirm" />
        <!--<input type="button" value="Cancel" onclick = "parent.parent.GB_hide();" />-->
		<input type="button" value="Cancel" onclick = "parent.parent.window.location.href='/index.php?action=changePass';parent.parent.GB_hide();" />
    </form>
    </div>
    
</div>
