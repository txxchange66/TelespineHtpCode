<div class="filter" style="padding-bottom:11px;">				
	<form method="POST" action="index.php?action=searchCategory" name="filter" onMouseOver="help_text(this, 'Please enter text to restrict the list to only items that contain the text entered')" onSubmit="return search_key(document.filter.search.value);">
		<label for="search">Search:</label>
		<input type="text" size="20" maxlength="250" name="search" value="">
		 <input type="submit" name="submitted" value="Apply Search"> 
	</form>
</div>