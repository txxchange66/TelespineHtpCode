<table border="0" cellpadding="10" cellspacing="1"  height="47px" width="100%">
	<tr>
	<td style="valign:middle;">
<!-- Filter -->

	
	<form method="POST" action="<!search_url>" name="filter" onMouseOver="help_text(this, 'Please enter text to restrict the list to only items that contain the text entered')" onSubmit="return search_key(document.filter.search.value);">

		<label for="search">Search:</label>
		<input type="text" size="20" maxlength="250" name="search" value="<!search>" id="search">
		
		<input type="submit" name="submitted" value="Apply Search">
		<input type="button" name="clear_search" value="Clear Search" onclick='window.location="<!clear_search_url>"' >
			</form>

<!-- Filter -->
</td>
	<td align="right"></td>
	</tr>

	</table>