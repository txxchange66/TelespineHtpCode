<tr class="<!classname>"  onmouseover="highlight('in',this)" onmouseout="highlight('out',this)" >
  <td  ><!patientName></td>
 <!--  <td ><!patientEmailId></td>-->
  <td ><!associatedTherapistName></td>
 <!--  <td ><!associatedClinicName></td>-->
<td ><!EHealthService></td>
  <td ><!patientStatus></td>
  <td style="white-space:nowrap;text-align:left;padding-left:10px;">
         <select name="action" onchange="action_handler(this,<!clinic_id>,<!userId>);" style="width:100px;">
            <option value='' >Action...</option>
            <option value='HeadAdminEditPatients' >Edit Patient</option>
         </select   
    </td>
</tr>