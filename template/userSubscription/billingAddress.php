

<div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="3"><input type="checkbox" name="chkBox" <!checked> onClick="populateShippingAddress(this.checked);" />
          Billing Address same as Clinic</td>
        </tr>
      <tr>
        <td colspan="3"><!whiteSpace5></td>
      </tr>
      <tr>
        <td width="11%">Address : </td>
        <td width="16%"><input type="text" name="add1_bill" tabindex="6" value="<!add1_bill>" /></td>
        <td width="18%">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="add2_bill" tabindex="7" value="<!add2_bill>" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>City : </td>
        <td><input type="text" name="city_bill" tabindex="8" value="<!city_bill>" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>State / Province :</td>
        <td>
		<select name="state_bill" id="state_bill" tabindex="9">
		<!stateOptions>
		</select>		</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Zip Code : </td>
        <td><input type="text" name="zip_bill" maxlength="7" value="<!zip_bill>" tabindex="10" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
</div>
