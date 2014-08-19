<tr class="<!style>"  onmouseover="highlight('in',this)" onmouseout="highlight('out',this)" >
	<td><!clinic_name></td>
	<td><!city></td>
	<td><!state></td>
    <td><!status></td>
	<td><!creationDate></td>	
	<td style="white-space:nowrap;text-align:left;padding-left:10px;">
         <select name="action" onchange="action_handler(this,<!clinic_id>);" style="width:100px;">
            <option value='' >Action...</option>
            <option value='addEditClinicInHeadAccount' >Edit Clinic</option>
          <!--  <option value='confirmStatusChangeClinicHead' ></option>-->
            <option value='addUserHead' >Add Provider</option>
            <option value='HeadAdminEditPatients' >Add Patient</option>
            <option value='updatecliniccreditcard' >Update Credit Card</option>
            <option value='confirmStatusChangeClinicHead' ><!status_text></option>
        </select>   
    </td>
</tr>
 	