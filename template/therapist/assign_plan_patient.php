<div id="container">
	<div id="header">
		<!header>
	</div>
	<div id="sidebar">
		<!sidebar>	
	</div>
	<div id="mainContent">
	<!--
<script language="JavaScript" src="js/validateform.js"></script>
<script language="JavaScript">

window.formRules = new Array(
	new Rule("treatment_name", "treatment name", true, "string|0,50"),
	new Rule("description", "description", false, "string|0,250"),
	new Rule("instruction", "default instructions", false, "string"),
	new Rule("is_deleted", "status", false, "string|1,1"),
	new Rule("is_locked", "locked", false, "string|1,1"),
	new Rule("pic1", "picture 1 file", false, "string|5,250"),
	new Rule("pic2", "picture 2 file", false, "string|5,250"),
	new Rule("pic3", "picture 3 file", false, "string|5,250"),
	new Rule("video", "video file", false, "string|5,250"));
// 
</script>
-->
<table style="vertical-align:middle;width:700px;"><tr><td style=" width:400px;">
<div id="breadcrumbNav">
<a href="index.php?action=therapistPlan">MY TX PLANS</a>
 / <a href="index.php?action=createNewPlan&act=plan_edit&plan_id=<!plan_id>&type=<!type>"><!plan_title></a>
  / <span class="highlight">SELECT PATIENT</span>
</div></td>
<td style="float:right; vertical-align:middle;">
<div id="page_nav" style="text-align:right;">
<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td>
	<!-- <a href="index.php?action=createNewPlan&act=plan_edit&plan_id=<!plan_id>&type=<!type>"><img src="images/global/02_plan_blue.gif" /></a> -->
	<img src="images/01_plan_gray.gif" />
</td>
<td>
	<!-- <a href="index.php?action=selectTreatment&plan_id=<!plan_id>&type=<!type>"><img src="images/global/03_customize_blue.gif" /></a> -->
	<img src="images/02_customize_gray.gif" />
</td>
<td>
<img src="images/03_patient_red.gif" />
</td>
<td>
<img src="images/04_assign_gray.gif" />
</td>
</tr>
</table>
</div>
</td>
</tr>
</table>
	<script language="JavaScript">
	<!--
	
	function handleAction(s, patient_id)
	{
		var a = s.options[s.options.selectedIndex].value;
		var c = false;
		switch (a)
		{
			case 'view_patient_details':
				if(!patient_detail_win)
				{
					var patient_detail_win = window.open('index.php?action=view_patient_details&id=' + patient_id , 'PatientDetails', 'width=750,height=480,resizable=1,scrollbars=auto');
				}
				patient_detail_win.focus();
				c = false;
				break;
			default:
				c = true;
				break;
		}
		s.options.selectedIndex = 0;
		if (c) window.location.href = 'index.php?action=' + a + '&act=<!act>&plan_id=<!plan_id>' + '&patient_id=' + patient_id;
	}
	function submitFilter()
	{
		if(document.forms['filter'].elements['search'].value != '') document.forms['filter'].submit();
	}
	
	function showCatSelect(patient_id)
	{
		if(!csw) var csw = window.open('patient2user_cat_select.php?patient_id='+patient_id, 'catSelectWindow', 'width=750, height=480, status=no, toolbar=no, resizable=1');
		csw.focus();
	}
	-->
	</script>

	<!-- [list] -->
	<h1 class="largeH1">Patient List</h1>
	<table border="0" cellpadding="2" cellspacing="1" width="100%"><tr><td>
	<div>				
		<form method="POST" action="index.php?action=assign_plan_patient&plan_id=<!plan_id>&type=<!type>" name="filter" onSubmit="return search_key(document.filter.search.value);" onMouseOver="help_text(this, 'Please enter text to restrict the list to only items that contain the text entered')">
			<label for="search">Search:</label>
			<input type="text" size="20" maxlength="250" name="search" value="">
			<input type="submit" size="20" name="sub" value=" Search ">
			<input type="button" size="20" name="clear" value="   Clear   " onclick="location.href='index.php?action=assign_plan_patient&plan_id=<!plan_id>&type=<!type>'" >
			<input type="hidden" size="20" name="restore_search" value="<!search>">
		</form>
	</div>
	</td>
	<td align="right"></td>
	</tr>
	</table>
	<!-- [items] -->
	<table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" onMouseOver="help_text(this, 'Display all the patient listing. To sort this list click on the column header you would like to sort by. Use menu action to view patient detail or select the patient for the plan')">
	<!assign_plan_patient_head>
	<!assign_plan_patient_record>
	</table>
	<!-- [/items] -->
	<div class="paging">
	<span onMouseOver="help_text(this, 'Select a page number to go to that page')"><!link>
	</div>
	<!-- [/list] -->
	<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
	<tr class="input">
		<td colspan="2" align="center">
			<!back_url>&nbsp;
		</tr>
	</table>
		</div>
	</div>

	
	<div id="footer">
		<!footer>
	</div>
</div>

