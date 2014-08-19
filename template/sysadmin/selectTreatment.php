<div id="container">
	<div id="header">
		<!header>
	</div>
	<div id="sidebar">
		<!sidebar>
	</div>
	<div id="mainContent">
	<table style="vertical-align:middle; width:700px;">
		<tr>
			<td style=" width:400px;">
				<div id="breadcrumbNav">
					<a href="index.php?action=systemPlan"> MY TX PLANS </a>
					 / <a href="index.php?action=createNewSystemPlan&act=plan_edit&plan_id=<!plan_id>"><!plan_title></a>
					  / <span class="highlight">SELECT TREATMENTS</span>
				</div>
				</td>
				<td style="float:right; vertical-align:middle;">
				<div id="page_nav" style="text-align:right;">
				<table cellpadding="0" cellspacing="0" border="0">
				<tr>
				<td>
					<a href="index.php?action=createNewSystemPlan&act=plan_edit&plan_id=<!plan_id>"><img src="images/01_plan_gray.gif" /></a>
				</td>
				<td>
					<img src="images/02_customize_gray.gif" />
				</td>
				<td>
					<table cellpadding="0" cellspacing="0" style="text-align:center; font-size:7pt;">
						<tr>
							<tr>
								<td>
									<a href="index.php?action=createNewSystemPlan&act=plan_edit&plan_id=<!plan_id>">
									<img src="images/stepIcons_1_gray_sm.gif">
									</a>
								</td>
							</tr>
						<tr>
						<td>
						<img src="images/stepIcons_2_red_sm.gif">
						</td>
						</tr>
					<tr>
				<td>
				<a href="index.php?action=systemCustomize_instruction&plan_id=<!plan_id>">
				<img src="images/stepIcons_3_gray_sm.gif">
				</a>
				</td>
				</tr>
				<tr>
				<td>
				<a href="index.php?action=systemCustomize_articles&plan_id=<!plan_id>">
				<img src="images/stepIcons_4_gray_sm.gif">
				</a>
				</td>
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
				<script type="text/javascript" src="js/swfobject.js"></script>
				<script type="text/javascript" src="js/js2flash.js"></script>
			<div id="flashcontent">
				<p style="padding:0px; margin:0px;">Please upgrade your flash player.</p>
			</div>
			<script type="text/javascript" language="Javascript">
				// <![CDATA[
				var so = new SWFObject("asset/flash/plan_creator.swf", "plancreator", "750", "700", "8", "#FFFFFF");			
				so.addVariable("SEARCH_TREATMENT", "SEARCH TREATMENT");
				so.addVariable("TREATMENT_RESULTS", "TREATMENT RESULTS");
				so.addVariable("SELECTED_TREATMENTS", "SELECTED TREATMENTS");
				so.addVariable("plan_id", <!plan_id>);
				so.write("flashcontent");
				// ]]>
			</script>
			<table border="0" cellpadding="0" cellspacing="1" width="100%" class="form_container">
			<tr class="input">

				<td align="center">				
				
					<input type="image" src="images/btn-back.jpg" value="Back" onClick="window.location='<!back_url>'" />&nbsp;
					<input type="image" src="images/btn-next.jpg" value="Next" onClick="doSave('index.php?action=systemCustomize_instruction&plan_id=<!plan_id>', this);" /></td>
			</tr>
			</table>
					</div>
	</div>
	
	<div id="footer">
		<!footer>
	</div>
</div>
