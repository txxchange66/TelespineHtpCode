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
				<!--<div id="breadcrumbNav">
					<a href="index.php?action=therapistPlan"> <!path_name> </a><!patient_name>
					 / <a href="index.php?action=createNewPlan&act=plan_edit&plan_id=<!plan_id>&type=<!type>&patient_id=<!patient_id>"><!plan_title></a>
					  / <span class="highlight">SELECT TREATMENTS</span>
				</div>-->
				</td>
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
				<script type="text/javascript" src="js/swfobject.js"></script>
				<script type="text/javascript" src="js/js2flash.js"></script>
			<div id="flashcontent">
				<p style="padding:0px; margin:0px;">Please upgrade your flash player.</p>
			</div>
			<script type="text/javascript" language="Javascript">
				// <![CDATA[
				var so = new SWFObject("asset/flash/plan_creator.swf", "plancreator", "750", "700", "8", "#FFFFFF");
				so.addVariable("plan_id", <!plan_id>);
				so.addVariable("SEARCH_TREATMENT", "<!SEARCH_TREATMENT>");
				so.addVariable("TREATMENT_RESULTS", "<!TREATMENT_RESULTS>");
				so.addVariable("SELECTED_TREATMENTS", "<!SELECTED_TREATMENTS>");
				so.write("flashcontent");
				// ]]>
			</script>
			<table border="0" cellpadding="0" cellspacing="1" width="100%" class="form_container">
			<tr class="input">

				<td align="center">				
				
					<!-- <input type="image" src="images/btn-back.jpg" value="Back" onClick="javascript:history.go(-1);" />&nbsp; -->
					<input type="image" src="images/btn-back.jpg" value="Back" onClick="window.location='<!back_url>'" />&nbsp;
					
					<input type="image" src="images/btn-next.jpg" value="Next" onClick="doSave('index.php?action=customize_instruction_ehs&plan_id=<!plan_id>', this);" /></td>
			</tr>
			</table>
					</div>
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
