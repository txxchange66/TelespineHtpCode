
	
	<form method="POST" action="index.php" name="filter" onMouseOver="help_text(this, 'Please enter text to restrict the list to only items that contain the text entered')" onSubmit="return search_key(document.filter.search.value);">
		<label for="search">Search:</label>
		<input type="text" size="20" maxlength="250" name="search" value="" >
		<input type="hidden" name="action" value="therapistLibrary"/>
		<input type="submit" name="submitted" value="Apply Search">
			</form>
