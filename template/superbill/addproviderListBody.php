<tr class="<!TRclass>" id="table_id_<!billingID>">
<td><input type="text" autocomplete="off" name="row<!sno>[]" id="bill_services_id<!sno>" value="" size="12"  maxlength="9"></td>
<td><input type="text" name="row<!sno>[]" id="bill_services_id<!sno>_descr" style="width:300px;" maxlength="50" value="" readonly="" ></td>
<td>\$ <input type="text" name="row<!sno>[]" id="bill_services_id<!sno>_price" size='7' maxlength="7" value="" readonly="" ></td>
<input type="hidden" name="row<!sno>[]" id="bill_services_id<!sno>_id" value="">
<input type="hidden" name="row<!sno>[]" id="bill_services_id<!sno>_desval" value="">
</tr>
<script>
    $(function() {
        $( "#bill_services_id<!sno>" ).autocomplete({
            source: "index.php?action=getBillingServicesData",
            minLength: 1,
            select: function( event, ui ) {
                fillData( ui.item,"#bill_services_id<!sno>", $(this));
            }
        });
    });
</script>