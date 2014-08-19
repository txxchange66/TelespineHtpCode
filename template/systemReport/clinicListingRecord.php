<tr class="<!style>" onmouseover="highlight('in',this)" onmouseout="highlight('out',this)" >
<td><a href="index.php?action=viewTherapistOfClinic&clinic_id=<!clinic_id>"><!clinic_name></a></td>
<td ><!state></td>
<td ><!status></td>
<td ><!creationDate></td>
<td style="white-space:nowrap;text-align:right">
<nobr>
<select size="1" style="width:125pt" class="action_select" onChange="handleAction(this,'<!clinic_id>')">
    <option value="">Actions............</option>
    <option value="one"> Template Plans Created </option>
    <option value="two">Assigned Plans</option>
    <option value="three">New Patients Created</option>
    <option value="four">Messages Sent</option>
    <option value="five">User Logins</option>
    <option value="six">Patient Logins</option>
</select>
</nobr>
</td>
</tr>