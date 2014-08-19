<div class="filter">				
	<form method="POST" action="index.php" name="filter" onMouseOver="help_text(this, 'Please enter text to restrict the list to only items that contain the text entered')" onSubmit="return search_key(document.filter.search.value);">
		<label for="search">Search:</label>
		<input type="text" size="20" maxlength="250" name="search" value="">
		<!--@A
		<select name="field" id="field">
<option value="" selected="true">All Columns</option>
<option value="treatment_name">Treatment Name</option>
</select>
@A-->
		<!-- <a href="javascript:void(0)" onClick="return submitFilter()" class="formButton">Apply Search</a> -->
		<input type="hidden" name="action" value="subscriberListing"/>
		<input type="submit" name="submitted" value="Apply Search">
			</form>
</div>