<tr class="<!style>"  onmouseover="highlight('in',this)" onmouseout="highlight('out',this)" >
	<td><!clinic_name></td>
	<td><!city></td>
	<td><!state></td>
    <td><!status></td>
	<td><!creationDate></td>	
	<td style="white-space:nowrap;text-align:left;padding-left:10px;">
         <select name="action" onchange="action_handler(this,<!clinic_id>);" style="width:100px;">
            <option value='' >Action...</option>
            <option value='addEditClinicInAccount' >Edit Clinic</option>
            <option value='trialStatusChangeClinic' >Trial</option>
            <option value='confirmStatusChangeClinic' ><!status_text></option>
            <option value='addUserSystem' >Add Provider</option>
            <option value='SystemAdminEditPatients' >Add Patient</option>
         </select   
    </td>
</tr>
 	