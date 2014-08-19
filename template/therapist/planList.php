<script type="text/javascript">  
function get_template_name(plan_id){
    GB_showCenter('Create Copy of Template Plan', '/index.php?action=save_as_template_plan&plan_id=' + plan_id, 110, 360 );
}
</script>
    <div id="container">
	<div id="header">
		<!header>
	</div>
	
	<div id="sidebar">
		<!sidebar>
	</div>
	<div id="mainContent">
<table style="vertical-align:middle; width:700px;" border="0"><tr><td style=" width:400px;">
<div id="breadcrumbNav" style="margin-top:11px;">
<a href="index.php?action=therapistPlan&path=<!path>&patient_id=<!patient_id>"><!path_name></a> <!patient_name>/ <span class="highlight"  >SELECT PLAN</span></div></td>
<td>
<div id="page_nav" style="text-align:right;">
<table cellpadding="0" cellspacing="0" border="0">
<tr><td></td><td></td><td></td><td></td></tr>
</table>
</div>
<td style="padding-left:140px;"><a href="index.php?action=createNewPlan"><img src="<!imageCreateNewTemplatePlan>" width="127" height="81" alt="Create New Plan"></a></td></tr></table>
<script language="JavaScript">
	<!--
	function handleAction(s, plan_id)
	{
		var a = s.options[s.options.selectedIndex].value;
		var c = false;

		switch (a)
		{
			case 'plan_view':
				if(!plan_detail_win)
				{
					var plan_detail_win = window.open('index.php?action=planViewer&id='+ plan_id, 'PlanPreview', 'width=970,height=688,resizable=1,scrollbars=auto');
				}
				plan_detail_win.focus();
				c = false;
				break;
			case 'deletePlan':
				c = confirm('Deleting this plan will remove all record of it from the site. Are you sure you want to continue with deleting this plan?');
				break;
			default:
				c = true;
				break;
		}
	
		s.options.selectedIndex = 0;
		if(a == 'plan_assign'){
			action = 'assign_plan_patient';
			if (c) window.location.href = 'index.php?action=' + action +  '&patient_id=<!patient_id>&plan_id=' + plan_id;
		}
		if(a == 'choose_patient'){
			action = 'choose_patient';
			if (c) window.location.href = 'index.php?action=' + action +  '&patient_id=<!patient_id>&plan_id=' + plan_id;
		}
		if(a == 'plan_edit' ){
			action = 'createNewPlan';
			act = 'plan_edit';
			if (c) window.location.href = 'index.php?action=' + action + '&act=' + act + '&plan_id=' + plan_id;
		}
        if(a == 'copy_plan' ){
            get_template_name(plan_id);
        }
		if(a == 'plan_customize' ){
			action = 'assign_plan_patient';
			act = 'plan_customize';
			if (c) window.location.href = 'index.php?action=' + action + '&act=' + act + '&plan_id=' + plan_id + '&patient_id=' + '<!patient_id>';
		}
		if(a == 'deletePlan' ){
			action = 'therapistPlan';
			act = 'deletePlan';
			if (c) window.location.href = 'index.php?action=' + action + '&act=' + act + '&plan_id=' + plan_id;
		}
		
	}
	function submitFilter()
	{
		if(document.forms['filter'].elements['search'].value != '')
		{
			document.forms['filter'].submit();
			return true;
		}
		else return false;
	}
	-->
	</script>
	
	<!-- [list] -->
	
	<h1 class="largeH1" style="margin-top:9px; height:26px;"><!templatePlanLibrary></h1>
	<table border="0" cellpadding="10" cellspacing="1"  height="47px" width="100%">
	<tr>
	<td style="valign:middle;">
					
		<form method="POST" action="index.php?action=therapistPlan&path=<!path>&patient_id=<!patient_id>" name="filter" onSubmit="return search_key(document.filter.search.value);" onMouseOver="help_text(this, 'Please enter text to restrict the list to only items that contain the text entered')">
			<label for="search">Search:</label>
			<input type="text" size="20" maxlength="250" name="search" value="">
			<input type="submit" size="20" name="sub" value=" Search ">
			<input type="button" size="20" name="clear" value="   Clear   " onclick="location.href='index.php?action=therapistPlan&path=<!path>&patient_id=<!patient_id>'">
			<input type="hidden" size="20" name="restore_search" value="<!search>">
		</form>
	
	</td>
	<td align="right"></td>
	</tr>
	</table>
	
	<!-- [items] -->

<table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" onMouseOver="help_text(this, 'To sort this list click on the column header you would like to sort by. Use action menus to perform action for the template plan')" >
	<!planListHead>
	<!planRecord>
	</table>
	<div class="paging">
				<span onMouseOver="help_text(this, 'Select a page number to go to that page')"><!link></div>

	<!-- [/items] -->
	<div align="center" style="padding:5px;">
		</div>
	<!-- [/list] -->
		
		</div>
	</div>
	
	<div id="footer">
		<!footer>
	</div>
</div>
