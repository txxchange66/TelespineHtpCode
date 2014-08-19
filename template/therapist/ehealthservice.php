<div style="position: absolute; top: 15px; left: 30px;width:450px;" >
        <b><!message></b>
        <br><br><br>
        <div style="position: absolute; top: 120px; left: 150px;">
                <form action="/index.php?action=eHealthServicePatient" method="post" >
                        <input type="hidden" name="confirm" value="yes" />
                        <input type="hidden" name="ehs" value="<!ehs>" />
                        <input type="hidden" name="userId" value="<!userId>" />
                        <input type="submit" value="Confirm" />
                        <input type="button" value="Cancel" onclick = "parent.parent.window.location.href='/index.php?action=therapistViewPatient&id=<!userId>';parent.parent.GB_hide();" />
                </form>
        </div>
 </div>
