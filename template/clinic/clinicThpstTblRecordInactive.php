<tr class="<!style>">
	<td><a href="index.php?action=editClinicSubscriber&id=<!user_id>&clinic_id=<!clinic_id>"><b><!username></b></a></td>
	<td><!full_name></td>
	<td><!usertype></td>
	<td><!statusType></td>
	<td><!last_login></td>
	<td><!creation_date></td>
	<td style="white-space:nowrap;text-align:right">
	<nobr>	
	<select size="1" style="width:125px;" class="action_select" onChange="handleAction(this, '<!user_id>','<!clinic_id>')">.
		<option value="">Actions...</option>
		<option value="edit"			id="act_edit">Edit Subscriber</option>
		<option value="active"			id="act_active">Activate Subscriber</option>
		<option value="delete"			id="act_delete">Delete Subscriber</option>
	</select>												
	</nobr>
	</td>
</tr>	