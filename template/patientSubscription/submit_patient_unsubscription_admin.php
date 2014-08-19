<script src="js/jquery.js"></script>
<link rel="STYLESHEET" type="text/css" href="css/styles.css">
<script>
    function changeCaptionTitle(){
              //document.getElementsByClassName('caption').innerHTML="Changed.";
              //alert(parent.parent.parent.parent.document.getElementsByClassName('caption').innerHTML);
              //alert($(".caption").css("color:red;"));
              //parent.parent.document.getElementById('GB_overlay').value="Hello";
              //alert($("GB_window").value);
}
    
</script>
<table width="100%" border="0" style="position: absolute; top: 15px; left: 30px; width: 450px;">
    <tr>
        <td align="left">
        <span style="font-size: 12px;line-height:1.5">You have unsubscribed from <!ServiceName>. You can still login and receive online care through the end of your current service billing period. Following the end of your current service billing period, your credit card will not be charged again. Thank you.</span>
        </td>
    </tr>
    <tr>
        <td align="center" valign="bottom" height="55px" style="padding-right:18px;"><input type="button" value="OK" style="width:44px;text-align:center;" onclick = "parent.parent.window.location.href='/index.php?action=<!ehsAction>&patient_id=<!userId>&clinic_id=<!clinicId>';parent.parent.GB_hide();" /> </td>
    </tr>
</table>

<br><br>

<script type="text/javascript" language="javascript">
    //changeCaptionTitle();
</script>
