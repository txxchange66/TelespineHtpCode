<div style="position: absolute; top: 15px; left: 30px;width:500px;" >
        <div style="text-align:center;"><!message></div>
        <div style="margin-left: 150px; margin-top:35px;">
                <form action="/index.php?action=ehsonetimepaymentComplete" method="post" >
                        <input type="hidden" name="confirm" value="yes" />
                        <input type="hidden" name="ehs" value="<!ehs>" />
                        <input type="hidden" name="userId" value="<!userId>" />
                         <input type="hidden" name="clinicId" value="<!clinicId>" />
                         <input type="hidden" name="ehsAction" value="<!ehsAction>">    
                        
                        <input type="submit" value="Confirm" />
      <input type="button" value="Cancel" onclick = "parent.parent.window.location.href='/index.php?action=HeadAdminEditPatients&patient_id=<!userId>&clinic_id=<!clinicId>';parent.parent.GB_hide();" />
                </form>
        </div>
 </div>