<script>
$(document).ready(function() {
	//alert("<!bill_services_code>");

	if("<!bill_services_code>"=='')
		{
		$('#edit_<!billingID>').css('visibility', 'hidden');
		$('#trash_<!billingID>').css('visibility', 'hidden');
		
		}
	if("<!bill_services_code>"!=''){
	        $('#edit_<!billingID>').css('visibility', 'visible');
	        $('#trash_<!billingID>').css('visibility', 'visible');
	        $('#bill_services_code_id<!sno>').css("background","#EBEBE4");
		$('#bill_services_descr_id<!sno>').css("background","#EBEBE4");
		$('#bill_services_price_id<!sno>').css("background","#EBEBE4");
	    }
    
});
</script>
<tr class="<!TRclass>" id="table_id_<!billingID>" >
<td><input type="text" name="row<!sno>[]" id="bill_services_code_id<!sno>" value="<!bill_services_code>" size="12"  maxlength="9" <!bgclass0> <!read> ></td>
<td><input type="text" name="row<!sno>[]" id="bill_services_descr_id<!sno>" size="50" maxlength="50" value="<!bill_services_descr>" <!bgclass1> <!read>></td>
<td>\$ <input type="text" name="row<!sno>[]" id="bill_services_price_id<!sno>" size='7' maxlength="7" value="<!bill_services_price>" <!bgclass2> <!read> > <span style='padding-left:5px;' >
                                    <span id='trash_<!billingID>' style='visibility:hidden;' onclick='del_goal(this);'>
                                        <img src='/images/b_drop.png' /></span><span id='edit_<!billingID>' style='visibility:hidden;'><span id='edit_service_<!sno>' onclick='edit_service(this);'>
                                        <img src='/images/b_edit.png' /></span></span></span></td>
<input type="hidden" name="row<!sno>[]" value="<!billingID>">
</tr>




