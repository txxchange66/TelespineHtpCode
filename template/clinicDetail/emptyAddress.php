<table cellspacing="2" cellpadding="2" width="100%">
  <tr>
    <td width="26%"><div align="right"><label for="address" onMouseOver="help_text(this, 'Enter the User\'s address')")>&nbsp;Address :&nbsp;</label></div></td>
    <td width="74%"><input type="text" name="address" id="address" size="20" maxlength="150" onMouseOver="help_text(this, 'Enter the User\'s address')" value="<!address>" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="text" name="address2" id="address2" size="20" maxlength="150" onMouseOver="help_text(this, 'Enter the User\'s address line 2')" value="<!address2>" /></td>
  </tr>
  <tr>
    <td><div align="right"><label for="city" onMouseOver="help_text(this, 'Enter the User\'s city')")>&nbsp;City :&nbsp;</label></div></td>
    <td><input type="text" name="city" id="city" size="20" maxlength="150" onMouseOver="help_text(this, 'Enter the User\'s city')" value="<!city>" /></td>
  </tr>
   <tr>
    <td><div align="right"><label for="country" onMouseOver="help_text(this, 'Enter the User\'s country')")>&nbsp;Country :&nbsp;</label></div></td>
    <td><select id="country" onchange="toggleState();" name="clinic_country" tabindex="5" >     
    <!patient_country_options>
      </select>     </td>
  </tr>
  <tr>
    <td><div align="right"><label for="state" onMouseOver="help_text(this, 'Enter the User\'s state')")>&nbsp;State / Province :&nbsp;</label></div></td>
    <td><select name="state" id="state" onMouseOver="help_text(this, 'Enter the User\'s state')">
						<!stateOptions>
					</select>     </td>
  </tr>
  <tr>
    <td><div align="right"><label for="zip" onMouseOver="help_text(this, 'Enter the User\'s zip code')")>&nbsp;Zip Code :&nbsp;</label> </div></td>
    <td><input type="text" name="zip" id="zip" size="10" maxlength="7" onMouseOver="help_text(this, 'Enter the User\'s zip code')" value="<!zip>" /></td>
  </tr>
  <tr>
	<td><div align="right"><label for="phone1" onMouseOver="help_text(this, 'Enter the User\'s phone number')")>&nbsp;Phone :&nbsp;</label></div></td>
	<td><input type="text" name="phone1" id="phone1" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the User\'s phone number')" value="<!phone1>"/></td>

</tr>
<tr>
	<td><div align="right"><label for="fax" onMouseOver="help_text(this, 'Enter the User\'s fax number')")>&nbsp;Fax :&nbsp;</label></div></td>
	<td><input type="text" name="fax" id="fax" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the User\'s fax number')" value="<!fax>"/></td>
</tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>