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
<!--<div id="breadcrumbNav" style="margin-top:13px;">
<a href="index.php?action=therapistEhsPlan&path=<!path>"><!path_name></a>/ <span class="highlight"  >SELECT PLAN</span></div>--></td>
<td>
<div id="page_nav" style="text-align:right;">
<table cellpadding="0" cellspacing="0" border="0">
<tr><td></td><td></td><td></td><td></td></tr>
</table>
</div>
<td style="padding-left:140px;"><!--<a href="index.php?action=createNewEhsPlan"><img src="<!imageCreateNewTemplatePlan>" width="127" height="81" alt="Create New Plan"></a>--></td></tr></table>
<script language="JavaScript">
	<!--
	function handleAction(s, plan_id,day)
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
	//alert(a);
		s.options.selectedIndex = 0;
		if(a == 'plan_assign'){
			action = 'assign_plan_patient_ehs';
			if (c) window.location.href = 'index.php?action=' + action +  '&plan_id=' + plan_id + '&day=' + day;;
		}
		if(a == 'choose_patient'){
			action = 'choose_patient_automatic';
			if (c) window.location.href = 'index.php?action=' + action +  '&plan_id=' + plan_id + '&day=' + day;
		}
		if(a == 'plan_edit' ){
			action = 'createNewEhsPlan';
			act = 'plan_edit';
			if (c) window.location.href = 'index.php?action=' + action + '&act=' + act + '&plan_id=' + plan_id + '&day=' + day;;
		}
        if(a == 'copy_plan' ){
            get_template_name(plan_id);
        }
		if(a == 'plan_customize' ){
			action = 'createNewEhsPlan';
			act = 'plan_edit';
			if (c) window.location.href = 'index.php?action=' + action + '&act=' + act + '&plan_id=' + plan_id + '&type=finish' + '&day=' + day;;
		}
		if(a == 'deletePlan' ){
			action = 'therapistAutomaticPlan';
			act = 'deletePlan';
			if (c) window.location.href = 'index.php?action=' + action + '&act=' + act + '&plan_id=' + plan_id + '&day=' + day;;
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
	
	<h1 class="largeH1" style="margin-top:11px;"><!templatePlanLibrary></h1>
	<table border="0" cellpadding="10" cellspacing="1"  height="47px" width="100%">
	<tr>
	<td style="valign:middle;">
					
            <form method="POST" action="index.php?action=therapistAutomaticPlan&path=<!path>&day=<!day>" name="filter" onSubmit="return search_key(document.filter.search.value);" onMouseOver="help_text(this, 'Please enter text to restrict the list to only items that contain the text entered')">
			<label for="search">Search:</label>
			<input type="text" size="20" maxlength="250" name="search" value="">
			<input type="submit" size="20" name="sub" value=" Search ">
			<input type="button" size="20" name="clear" value="   Clear   " onclick="location.href='index.php?action=therapistAutomaticPlan&path=<!path>&day=<!day>'">
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
<script src="js/jquery.js"></script>  
<script language="javascript" type="text/javascript">
      $(document).ready(function() {
        switchMenu('eMaintenance',document.getElementById('switch'));
      });
</script>
