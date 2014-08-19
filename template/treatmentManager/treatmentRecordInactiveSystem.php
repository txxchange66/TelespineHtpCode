<tr class="<!style>"><td><a href="index.php?action=editTreatment&id=<!treatment_id>"><!treatment_name></a></td>
<td style="white-space:nowrap;"><nobr><!mediaImages><!mediaVideo></nobr></td>
<!--<td><!is_deleted></td>--><td><!status></td>
<!--<td><!is_locked></td>-->
<td style="white-space:nowrap;text-align:right">
    <nobr>
        <select size="1" style="width:130px;" class="action_select" onChange="handleAction(this, <!treatment_id>)">
            <option value="">Actions...</option>
            <option value="tag" >Add Tags</option>
            <option value="view"     id="act_view">View Treatment</option>
            <option value="edit"     id="act_edit">Edit Treatment</option>
            <option value="active"    id="act_active">Activate Treatment</option>
            <option value="delete"    id="act_delete">Delete Treatment</option>
        </select>
    </nobr>
</td>
</tr>