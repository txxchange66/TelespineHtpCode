<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<div id="container">
	<div id="header">
		<!header>
	</div>
	
	<div id="sidebar">
		<!sidebar>
	</div>
	<div id="mainContent">
<table style="vertical-align:middle; width:700px;" border="0"><tr><td style=" width:400px;">
<div id="breadcrumbNav">
<a href="index.php?action=systemPlan">MY TX PLANS</a> / <span class="highlight">SELECT PLAN</span></div></td>
<td>
<div id="page_nav" style="text-align:right;">
<table cellpadding="0" cellspacing="0" border="0">
<tr><td></td><td></td><td></td><td></td></tr>
</table>
</div>
<td ><a href="index.php?action=createNewSystemPlan"><img style='float:right;'  src="images/createNewTemplatePlan.gif" width="127" height="81" alt="Create New Plan"></a></td></tr></table><script language="JavaScript">
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
			if (c) window.location.href = 'index.php?action=' + action +  '&plan_id=' + plan_id;
		}
		if(a == 'plan_edit' ){
			action = 'createNewSystemPlan';
			act = 'plan_edit';
			if (c) window.location.href = 'index.php?action=' + action + '&act=' + act + '&plan_id=' + plan_id;
		}
		if(a == 'plan_customize' ){
			action = 'assign_plan_patient';
			act = 'plan_customize';
			if (c) window.location.href = 'index.php?action=' + action + '&act=' + act + '&plan_id=' + plan_id;
		}
		if(a == 'deletePlan' ){
			action = 'systemPlan';
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
	<br />
	<h1 class="largeH1">Template Plan Library</h1>
	<table border="0" cellpadding="2" cellspacing="1" width="100%"><tr><td>
	<div>				
		<form method="POST" action="index.php?action=systemPlan" name="filter" onsubmit="return search_key(document.filter.search.value);" onMouseOver="help_text(this, 'Please enter text to restrict the list to only items that contain the text entered')">
			<label for="search">Search:</label>
			<input type="text" size="20" maxlength="250" name="search" value="">
			<input type="submit" size="20" name="sub" value=" Search ">
			<input type="button" size="20" name="clear" value="   Clear   " onclick="location.href='index.php?action=systemPlan'">
			<input type="hidden" size="20" name="restore_search" value="<!search>">
		</form>
	</div>
	</td>
	<td align="right"></td>
	</tr>
	</table>
	
	<!-- [items] -->

<table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" onMouseOver="help_text(this, 'To sort this list click on the column header you would like to sort by. Use action menus to perform action for the template plan')">
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
