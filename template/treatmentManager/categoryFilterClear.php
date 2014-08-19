<div class="filter">				
	<form method="POST" action="index.php?action=searchCategory" name="filter" onMouseOver="help_text(this, 'The filter is designed to allow you to select a column and enter text to restrict the list to only items that contain the text entered')" onsubmit="return search_key(document.filter.search.value);">
		<label for="search">Search:</label>
		<input type="text" size="20" maxlength="250" name="search" value="">
		<!--@A
		<select name="field" id="field">
<option value="" selected="true">All Columns</option>
<option value="category_name">Category Name</option>
<option value="parent_category_id">Parent Category ID</option>

</select>
@A-->
		<!-- <a href="javascript:void(0)" onClick="return submitFilter()" class="formButton">Apply Search</a> -->
		 <input type="submit" name="submitted" value="Apply Search"> 
		 <input type="button" name="clearFilter" value="Clear Search" onClick="window.location.href='index.php?action=categoryManager'" />

			</form>
</div>
<!searchOn>