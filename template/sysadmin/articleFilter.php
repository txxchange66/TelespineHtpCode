<div>
  <form method="POST" action="<!search_url>" name="filter" onMouseOver="help_text(this, 'Please enter text to restrict the list to only items that contain the text entered')" onSubmit="return search_key(document.filter.search.value);">
    <label for="search">Search:</label>
    <input type="hidden" name="action" value="systemCustomize_articles"/>
	<input type="text" size="20" maxlength="250" name="search" value="<!keyword>" style="vertical-align:middle;">
    <input type="submit" name="submitted" value="Apply Search" style="vertical-align:middle;">
	<input type="button" name="clear_search" value="Clear Search" onclick='window.location="<!clear_search_url>"' style="vertical-align:middle;">
  </form>
</div>
