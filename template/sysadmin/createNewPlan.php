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


<table style="vertical-align:middle; width:700px;"><tr><td style=" width:400px;"><div id="breadcrumbNav">
<a href="index.php?action=systemPlan">MY TX PLANS</a> / <span class="highlight">PLAN NAME</span></div></td>
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
		<td><img src="images/03_patient_gray.gif" /></td>
		<td><img src="images/04_assign_gray.gif" /></td>
		</tr>
		</table>
		</div>
		</td>
		</tr>
		</table>
		<h1 class="largeH1">Customize Plan Name</h1>
		<br>
<form name="detailform" id="detailform" enctype="multipart/form-data" method="POST" action="index.php?action=createNewSystemPlan" >			
			<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form_container">
			<tr class="input" >
				<td><div style="width:160px"><!error></div></td>
			</tr>
			<tr class="input" >
				<td><div style="width:160px"><label for="plan_name" onMouseOver="help_text(this, 'Enter the plan name to create a new customized plan.')")>*&nbsp;Plan Name:&nbsp;</label></div></td>
				<td width="100%" >
				<input type="text" class="inputleft"  name="plan_name" id="plan_name" value="<!plan_name>" size="50" maxlength="50" onMouseOver="help_text(this, 'Enter the plan name to create a new customized plan.')"/></td>
			</tr>
            <!-- Removed from First step. 
			<tr>
				<td>&nbsp;&nbsp;&nbsp;<label for="is_public" onmouseover="help_text(this, 'Check to make this plan uneditable by others.')" )="">Public:&nbsp;</label></td>
				<td><input  name="is_public" id="is_public" value="1" onmouseover="help_text(this, 'Check to make this plan uneditable by others.')" <!checked> type="checkbox" ></td>
			</tr>
            -->
			</table>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form_container">
			<!-- Ends -->
			<tr class="input">
				<td style="padding-left:170pt;padding-top:10pt" >
					<input type="hidden" name="plan_id" value="<!plan_id>"  />
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
