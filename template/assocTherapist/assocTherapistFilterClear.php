<div class="filter">				
	<form method="POST" action="index.php?action=associateTherapist&patient_id=<!patientId>" name="filter" onsubmit="return search_key(document.filter.search.value);">
		<label for="search">Search:</label>
		<input type="text" size="20" maxlength="250" name="search" value="">
		<!-- @A
		<select name="field" id="field">
<option value="" selected="true">All Columns</option>
<option value="treatment_name">Treatment Name</option>
</select>
@A -->
		<!-- <a href="javascript:void(0)" onClick="return submitFilter()" class="formButton">Apply Search</a> -->
			
	<input type="submit" name="submitted" value="Apply Search">
	<input type="hidden" name="clinic_id" value="<!clinic_id>">
	<!--<input type="hidden" name="action" value="associateTherapist"/>-->
	<input type="button" name="clearFilter" value="Clear Search" onClick="window.location.href='index.php?action=associateTherapist&patient_id=<!patientId>&clinic_id=<!clinic_id>'" />
		
		
			</form>
</div>

<!searchOn>