
<script language="javascript">
function callPopup(){
    if( Trim(document.getElementById('category_name').value) == "" ){
        alert("Please enter category name");
        return false;
    }
    if( document.getElementById('parent_category_id').value == "" ){
        alert('Please select parent category');
        return false;
    }
    GB_showCenter('Create New Category', '/index.php?action=confirmCreateNewTreatmentCategory', 210,550 ); 
}
</script>

<!-- [Treatment Category add form] -->
<h1 class="largeH1">Treatment Category Form</h1>
<div style="padding:10px;color:red"><!error></div>
<form name="detailform" id="detailform" enctype="multipart/form-data" method="POST" action="index.php" onSubmit="return validate_form(this);">
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
		<input type="hidden" name="action" value="createCategory" />
		<input type="hidden" name="submitted" value="Create Category" />&nbsp;
        <!--<a  href="index.php?action=confirmCreateNewTreatmentCategory"  title="Tx Xchange: Create New Category" rel="gb_page_center[550, 210]"><input type="button" name="submitted" value="Create Category"></a> -->
        <input type="button" name="submitted" value="Create Category" onclick="callPopup();" >
		<input type="button" name="cancel" value="Cancel" onClick="window.location.href='index.php?action=categoryManager'" />
	</td>
</tr>
</table>
</form>
<!-- [/Treatment Category add form] -->