<script>
$(document).ready(function() {
    //alert("<!bill_services_code>");

    if("<!soap_answer_id>"=='')
        {
        $('#edit_<!soap_answer_id>').css('visibility', 'hidden');
        $('#trash_<!soap_answer_id>').css('visibility', 'hidden');
        
        }
    if("<!soap_answer_id>"!=''){
            $('#edit_<!soap_answer_id>').css('visibility', 'visible');
            $('#trash_<!soap_answer_id>').css('visibility', 'visible');
            $('#soap_answer<!soap_answer_id>').css("background","#ECEAE9");
        }
    
});
</script>
<tr class="<!TRclass>" id="table_id_<!soap_answer_id>" >
<td><input type="text" name="row<!sno>[]" id="soap_answer<!soap_answer_id>" value="<!answer>" style="width:659px" <!bgclass0> <!read> ></td>
<td><span>
    <span id='trash_<!soap_answer_id>' style='visibility:hidden;' onclick='del_goal(this);'>
    <img src='/images/b_drop.png' /></span><span id='edit_<!soap_answer_id>' style='visibility:hidden;'><span id='edit_service_<!soap_answer_id>' onclick='edit_service(this);'>
    <img src='/images/b_edit.png' /></span></span></span></td>
    <input type="hidden" name="row<!sno>[]" value="<!soap_answer_id>">
    <input type="hidden" name="row<!sno>[]" value="<!soap_question_id>">
</tr>
