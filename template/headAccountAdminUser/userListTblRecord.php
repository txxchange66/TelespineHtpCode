<tr class="<!style>" onmouseover="highlight('in',this)" onmouseout="highlight('out',this)" >
	<td><!full_name></td>
	<td><!username></td>
	<td><!usertype></td>
    <td><!clinic_name></td>
	<td><!last_login></td>
    <td style="white-space:nowrap;text-align:left;">
         <select name="action" style='width:120px;' onchange="action_handler(this,'<!clinic_id>','<!user_id>','<!therapist_access>','<!patient_association>');" style="width:100px;">
            <option value='' >Action...</option>
            <option value='editUserHead' >Edit Provider</option>
            <option value='showConfirmBox' >Remove Provider</option>
            <option value='<!status_url>' ><!status_text></option>
         </select   
    </td>
</tr>	