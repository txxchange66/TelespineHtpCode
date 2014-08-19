<div style="position: absolute; top: 15px; left: 30px;width:450px;line-height:1.5" >
    <!message>
    <div style="position: absolute; top: 100px; left: 173px;">
    <form action="/index.php?action=unsubscribePatientServicesEHS" method="post" >
            <input type="hidden" name="action" value="unsubscribePatientServicesEHS">
            <input type="hidden" name="subscrp_id" value="<!subscrp_id>">
            <input type="hidden" name="userId" value="<!userId>">
            <input type="hidden" name="clinicId" value="<!clinicId>">
            <input type="hidden" name="ehsAction" value="<!ehsAction>">                
            <input type="submit" value="Yes" name="submit" style="width:44px;text-align:center;" />
                <!--<input type="button" value="Cancel" onclick = "parent.parent.GB_hide();" />-->
            <input type="button" value="No" onclick = "parent.parent.window.location.href='/index.php?action=clinic_patient&clinic_id=<!clinicId>';parent.parent.GB_hide();"  style="width:44px;text-align:center;"  />
    </form>
    </div>
    
</div>
<script src="js/jquery.js"></script>    
<script>
    function changeCaptionTitle(){
              //document.getElementsByClassName('caption').innerHTML="Changed.";
              //alert(parent.parent.document.getElementsByClassName('caption').innerHTML);
              //alert($(".caption").css("color:red;"));
              parent.parent.document.getElementById('GB_overlay').value="Hello";
              alert($("GB_window").value);
}
         
</script>
