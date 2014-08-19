<div id="mainContent">
			<script language="JavaScript" src="js/validateform.js"></script>
<script language="JavaScript">
<!--
window.formRules = new Array(
	new Rule("or_instruction", "custom instructions", false, "string"),
	new Rule("or_reps", "custom reps", false, "string|0,20"),
	new Rule("or_sets", "custom sets", false, "string|0,20"),
	new Rule("or_hold", "custom hold time", false, "string|0,20"));
// -->
</script>
<h1 class="largeH1">TREATMENTS > Edit Instructions for Treatment <small><!treatment_name></small> in Plan <small><!plan_name><small></h1>
<form name="detailform" id="detailform" enctype="multipart/form-data" method="POST" action="index.php?action=systemEdit_instruction" onSubmit="return validate_form(this);">

<table border="0" cellpadding="2" cellspacing="1" width="100%">
	<tr class="input">
		<td><div style="width:160px"><label for="or_instruction" onMouseOver="help_text(this, 'Edit your treatment instructions')")>Instructions:&nbsp;</label></div></td>
		<td width="100%"><textarea name="instruction" id="or_instruction" rows="4" cols="50" onMouseOver="help_text(this, 'Edit your treatment instructions')"><!instruction></textarea></td>
	</tr>
	<tr class="input">
		<td><div style="width:160px"><label for="or_instruction" onMouseOver="help_text(this, 'Edit your treatments benefits')")>Benefits:&nbsp;</label></div></td>
		<td width="100%"><textarea name="benefit" id="or_instruction" rows="4" cols="50" onMouseOver="help_text(this, 'Edit your treatments benefits')"><!benefit></textarea></td>
	</tr>
	<tr class="input">
		<td><label for="or_sets" onMouseOver="help_text(this, 'Edit your treatment sets')")>Sets:&nbsp;</label></td>
		<td><input type="text" name="sets" id="or_sets" size="20" maxlength="20" onMouseOver="help_text(this, 'Edit your treatment sets')" value="<!sets>"/></td>

	</tr>
	<tr class="input">
		<td><label for="or_reps" onMouseOver="help_text(this, 'Edit your treatment reps')")>Reps:&nbsp;</label></td>
		<td><input type="text" name="reps" id="or_reps" size="20" maxlength="20" onMouseOver="help_text(this, 'Edit your treatment reps')" value="<!reps>"/></td>
	</tr>
	<tr class="input">
		<td><label for="or_hold" onMouseOver="help_text(this, 'Edit your treatment hold time')")>Hold Time:&nbsp;</label></td>
		<td><input type="text" name="hold" id="or_hold" size="20" maxlength="20" onMouseOver="help_text(this, 'Edit your treatment hold time')" value="<!hold>"/></td>
	</tr>
	<tr class="input">
		<td><label for="or_hold" onMouseOver="help_text(this, 'Edit your treatment LRB')">LRB:&nbsp;</label></td> 
		<td>
		<select name="lrb" onMouseOver="help_text(this, 'Edit your treatment LRB')" >
			<option value="" selected >None</option>		
			<!lrboption>
		</select>	
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
		<input type="hidden" name="id" value="<!plan_treatment_id>" />
		<input type="hidden" name="update" value="update" />
		<input type="submit" name="submitted" value="Save Custom Instructions" />&nbsp;
		<input type="button" name="clear" value="Close" onClick="window.close()" /></td>
	</tr>
</table>
</form>
<div id="rolloverhelp">Rollover buttons or elements on the page to get help and tips.</div>
