<div id="container">
	<div id="header">
	<!header>
	</div>
	<div id="sidebar">
	<!sidebar>
	</div>
	<div id="mainContent">
	<table style="vertical-align:middle;width:700px;"><tr><td style=" width:400px;">
<!--div id="breadcrumbNav">
		<a href="index.php?action=therapistPlan">MY TX PLANS</a>
		 / <a href="index.php?action=createNewPlan&act=plan_edit&plan_id=<!plan_id>&type=<!type>&patient_id=<!patient_id>"><!plan_title></a>
		  / <!name_first> <!name_last>
		   / <span class="highlight">ASSIGN PLAN</span>
		   </div>--></td><td style="float:right; vertical-align:middle;" align="right">
		   <div id="page_nav" style="text-align:right;">
		   <table cellpadding="0" cellspacing="0" border="0">
		   <tr>
		   	<td>
		   		<!step1>
		   	</td>
		   	<td>
		   		<!step2>
		   	</td>
		   <td>
		   		<!step3>
		   	</td>
		   	<td>
		   		<!step4>
		   	</td>
		   	</tr>
		   	</table></div></td></tr></table>	
	<h1 class="largeH1">Health Video Series Assignment</h1>
	<script language="JavaScript" type="text/javascript">
	<!--
	function previewPlan(id)
	{
		if(! g_plan_win) var g_plan_win = window.open('index.php?action=planViewer&id='+id, 'g_plan_win', 'width=1024,height=768,scrollbars=auto');
		g_plan_win.focus();
	}
	function handleAction(s, plan_id, patient_id)
	{
		var a = s.options[s.options.selectedIndex].value;
		var c = false;
	
		switch (a)
		{
		
			default:
				c = true;
				break;
		}
	
		s.options.selectedIndex = 0;
	
		if (c) window.location.href = '/admin/assign.php?step=4&patient_id=13&' + 'act=' + a + '&patient_id=' + patient_id + '&plan_id=' + plan_id;
	}

	function submitForm()
	{
		document.forms['assignform'].submit();
	}
	function showCatSelect(plan_id)
	{
		if(!csw) var csw = window.open('treatment_plan_select.php?plan_id='+ plan_id, 'catSelectWindow', 'width=750, height=480, status=no, toolbar=no, resizable=1');
		csw.focus();
	}
	function showArticlePreview(id)
	{
		if(!csw) var csw = window.open('articlePopup.php?id='+id, 'catSelectWindow', 'width=750, height=480, status=no, toolbar=no, resizable=1,scrollbars=yes');
		csw.focus();
	}
	-->
	</script>
	
	<br />
	<span style="font-weight:bolder; font-size:16px;">You are about to assign a Video Series to your E-Health Service subscribers.</span>
	<br /><span>By notifying them, they will receive an email notification asking them to log in.</span><br /><br />
	<div align="left">
		<form name="assignform" enctype="multipart/form-data" method="POST" action="index.php?action=therapistEhsPlan" onSubmit="return validate_form(this);">

		<a href="index.php?action=assigned_plan_patient_ehs&notify=1&plan_id=<!plan_id>" ><img src="images/btn-assign-notify.jpg" alt="Assign &amp; Notify" /></a>
		
		<a href="index.php?action=assigned_plan_patient_ehs&plan_id=<!plan_id>"><img src="images/btn-assign-nonotify.jpg" alt="Assign w/o Notification" /></a>
		
		<a href="javascript:void(0);"><img src="images/btn-preview.jpg"  alt="Preview" onclick="previewPlan(<!plan_id>);" /></a>
	
		</form>
	</div>
	</div>
			</div>
	</div>
	
	<div id="footer">

		<p>&copy; 2006 Tx Xchange </p>
	</div>
</div>
</body>
</html>
<script src="js/jquery.min.js"></script>  
<script language="javascript" type="text/javascript">
      $(document).ready(function() {
        switchMenu('eMaintenance',document.getElementById('switch'));
      });
</script>
