<tr class="<!classname>"  onmouseover="highlight('in',this)" onmouseout="highlight('out',this)" >
  <td  ><!patientName></td>
  <td ><!patientEmailId></td>
  <td ><!associatedTherapistName></td>
  <td ><!associatedClinicName></td>
  <td ><!patientStatus></td>
  <!--<td ><a href="index.php?action=SystemAdminEditPatients&patient_id=<!userId>&clinic_id=<!clinic_id>">Edit</a></td>-->
  <td style="white-space:nowrap;text-align:left;padding-left:10px;">
         <select name="action" onchange="action_handler(this,'<!clinic_id>','<!userId>');" style="width:100px;">
            <option value='' >Action...</option>
            <option value='SystemAdminEditPatients' >Edit Patient</option>
         </select   
    </td>
</tr>