<!-- [Treatment Category edit form] -->
<h1 class="largeH1">Treatment Category Form</h1>
<div style="padding:10px;color:red"><!error></div>
<form name="detailform" id="detailform" enctype="multipart/form-data" method="POST" action="index.php?id=<!category_id>" onSubmit="return validate_form(this);">
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
<tr class="inputRow">
	<td><div style="width:160px"><label for="category_name" onMouseOver="help_text(this, 'Enter a name for this category')")>*&nbsp;Category Name:&nbsp;</label></div></td>
	<td width="100%"><input type="text" name="category_name" id="category_name" size="50" maxlength="50" onMouseOver="help_text(this, 'Enter a name for this category')" value="<!category_name>"/></td>
</tr>
<tr class="input">
	<td><label for="parent_category_id" onMouseOver="help_text(this, 'Choose a parent category for this category')")>*&nbsp;Parent Category:&nbsp;</label></td>
	<td>
		<select name="parent_category_id" id="parent_category_id" onMouseOver="help_text(this, 'Choose a parent category for this category')">
			<!options>
		</select>
	</td>
</tr>
<tr>
	<td colspan="2" align="center">
		<input type="hidden" name="action" value="editCategory" />
		<input type="submit" name="submitted" value="Save Category" />&nbsp;
		<input type="button" name="cancel" value="Cancel" onClick="window.location.href='index.php?action=categoryManager'" />
	</td>
</tr>
</table>
</form>
<!-- [/Treatment Category edit form] -->