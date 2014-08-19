<div style="position: absolute; top: 46px; left: 80px;width:350px;" >
    <b><!message></b>
    <br><br><br>
    <div style="position: absolute; top: 80px; left: 100px;">
        <form action="index.php?action=confirmStatusChangeClinicHead" method="post" >
            <input type="hidden" name="confirm" value="yes" />
            <input type="hidden" name="clinic_id" value="<!clinic_id>" />
            <input type="submit" value="Yes" />
            <input type="button" value="No" onclick = "parent.parent.GB_hide();" />
        </form>
    </div>

</div>
