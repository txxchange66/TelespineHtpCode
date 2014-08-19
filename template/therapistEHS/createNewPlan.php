<div id="container">
	<div id="header">
			<!header>
	</div>
	
	<div id="sidebar">
		<!sidebar>
	</div>
	<div id="mainContent">
<!--
<script language="JavaScript" src="/protosite2/form/validateform.js"></script>
<script language="JavaScript">

window.formRules = new Array(
	new Rule("plan_name", "plan_name", true, "string|1,100"));

</script> -->

<script type="text/javascript">
function validateForm() {

var plan_name = document.getElementById('plan_name').value;
if(plan_name == '') { 
        alert("Please enter plan name.");
        return false;
}
}
</script>

<table style="vertical-align:middle; width:700px;"><tr><td style=" width:400px;"><!--<div id="breadcrumbNav">
<a href="index.php?action=therapistPlan"><!path_name></a><!patient_name>/ <span class="highlight">PLAN NAME</span></div>--></td>
<td style="float:right; vertical-align:middle;">
<div id="page_nav" style="text-align:right;">
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<!plan>
		</td>
		<td>
		<img src="images/02_customize_red.gif" />
		</td>
		<td>
		<table cellpadding="0" cellspacing="0" style="text-align:center; font-size:7pt;">
			<tr>
				<tr>
					<td>
						<!step1>
					</td>
				</tr>
				<tr>
				<td>
						<!step2>
				</td>
			</tr>
			<tr>
				<td>
					<!step3>
				</td>
			</tr>
			<tr>
				<td><!step4></td>
			</tr>
		</table>
		</td>
		<td><!patient_image></td>
		<td><!assign_image></td>
		</tr>
		</table>
		</div>
		</td>
		</tr>
		</table>
		<h1 class="largeH1">Customize Plan Name</h1>
		<br>
<form name="detailform" id="detailform" enctype="multipart/form-data" method="POST" action="index.php?action=createNewEhsPlan&ehsflag=1&type=finish" onsubmit="return validateForm()">			
			<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form_container">
			<tr class="input" >
				<td colspan="2"><div><!error></div></td>
			</tr>
			<tr class="input" >
				<td><div style="width:160px"><label for="plan_name" onMouseOver="help_text(this, 'Enter the plan name to create a new customized plan.')")>*&nbsp;Plan Name:&nbsp;</label></div></td>
				<td width="100%">
				<input type="text" name="plan_name" id="plan_name" value="<!plan_name>" size="50" maxlength="50" onMouseOver="help_text(this, 'Enter the plan name to create a new customized plan.')"/></td>
			</tr>
			</table>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form_container">
			<!-- Ends -->
			<tr class="input">
				<td style="padding-left:170pt;padding-top:10pt" >
					<input type="hidden" name="plan_id" value="<!plan_id>"  />
					<input type="hidden" name="patient_id" value="<!patient_id>"  />
					<input type="hidden" name="type" value="<!type>"  />
                                        <input type="hidden" name="schd" value="<!schd>"  />
					
					<!-- <a href="#"><img src="images/btn-back.jpg" name="back" alt="Back" onClick="javascript:history.go(-1);"  /></a> -->&nbsp;
					<input type="image" src="images/btn-next.jpg"  name="submitted" value="Next" />
				</td>
			</tr>
			</table>
			</form>		</div>
	</div>
	<div id="footer">
		<!footer>
	</div>

</div>
<script src="js/jquery.min.js"></script>  
<script language="javascript" type="text/javascript">
      $(document).ready(function() {
        switchMenu('eMaintenance',document.getElementById('switch'));
      });
</script>
