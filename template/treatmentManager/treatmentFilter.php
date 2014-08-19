<div >
<form method="POST" action="index.php" name="filter" onMouseOver="help_text(this, 'Please enter text to restrict the list to only items that contain the text entered')" onSubmit="return search_key(document.filter.search.value);">
<table border="0" cellpadding="2" cellspacing="1" width="100%"height="48px" class="form_container">
	<tr class="input">
		<td style="padding-left:10px;"><label for="search">Search:</label></td>
		<td width="100%"><input type="hidden" name="action" value="treatmentManager"/>
		<input type="text" size="20"  maxlength="250" name="search" value="<!keyword>">
		<input type="submit" name="submitted" value="Apply Search"></td>

	</tr>
</table>
</form>
</div>