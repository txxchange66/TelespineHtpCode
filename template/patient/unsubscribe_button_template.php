<script>
// Unsubscribe user.
<!--
function action_handler1(value,subscribe_id){
    
    
    if(value==true)
                var status=1;
            else if(value==false)
                var status=2;
    if( status == ''){
        return;
    }
    url = '/index.php?action=patient_ehealth_unsubscribe&subscrp_id=' +subscribe_id;
       // Original Text from client
       //GB_showCenter('Unsubscribe from <!ServiceName>?', url, 210,500 );
       GB_showCenter("Subscription Status for <!ServiceNameTitle>", url, 170,500 );
    //window.location = url;
}

//-->
</script>
<!-- Unsubcription Section -->
<tr>
    <td colspan="2">&nbsp;
    </td>
</tr>    
<tr>
    <td colspan="2">&nbsp;
    </td>
</tr>    
<tr>
    <td colspan="2"><div align="left" class="largeH6" style="padding-top:5px;padding-left:5px;font-size:13px;letter-spacing: 1px;text-transform:uppercase;">&nbsp;&nbsp;E-HEALTH SERVICES</div>
    </td>
</tr> 
<tr>
    <td colspan="2">&nbsp;</td>
</tr>   
<tr class="inputRow">
<td colspan="2"><div style="font-size:13px;padding-left:0px;padding-top:5px;"><input type="checkbox" name="current_e_health" id="current_e_health"  class="checkBoxAlign" value="1" <!checkBoxCheck> disabled /><label for="current_e_health" > <!ServiceName> :&nbsp;<!subsStartDate> to <!subsEndDate></label></div></td>
 </tr>
 <!trdisplay>
<tr>
    <td ><img name="unsubscribe" src="images/unsubscribe.jpg" onclick="action_handler1(this.checked,'<!subscribe_id>');" <!visibilityButton>/></td><td colspan="2" ></td>
</tr>
