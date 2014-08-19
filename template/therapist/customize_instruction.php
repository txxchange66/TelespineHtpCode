<div id="container">
	<div id="header">
		<!header>
	</div>
	
	<div id="sidebar">
		<!sidebar>
	</div>
	
	
	
	<div id="mainContent">
		<!--
		<img src="skin/tx/images/icons/user48.png" width="48" height="48" alt="" hspace="8">
		My TX Plans	-->
			<!-- <p class="breadcrumbNav"><a href="#">Breadcrumb Nav</a></p> -->
<table style="vertical-align:middle; width:700px;padding-top:15px;">
	<tr>
		<td style=" width:400px;">
		<div id="breadcrumbNav">
			<a href="index.php?action=therapistPlan"><!path_name></a><!patient_name>
					 / <a href="index.php?action=createNewPlan&act=plan_edit&plan_id=<!plan_id>&type=<!type>&patient_id=<!patient_id>"><!plan_title></a> / <span class="highlight">CUSTOMIZE INSTRUCTIONS</span></div></td><td style="float:right; vertical-align:middle;">
					 <div id="page_nav" style="text-align:right;">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr><td><!plan></td>
				<td><img src="/images/02_customize_red.gif" /></td>
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
				</tr></table></div></td></tr></table>
				<h1 class="largeH1"><!Treatments_in_Plan></h1>
							<script language="JavaScript">
			<!--
			function handleAction(s, id, treatment_id)
			{
				var a = s.options[s.options.selectedIndex].value;
				var c = false;
              //  alert(a);
              //  alert(c);
				switch (a)
				{
					case 'tag':
                        GB_showCenter('Add Tags/Favorites', '/index.php?action=tagPopup&id=' + treatment_id, 150, 480 );
                        c = false;
                    break;
					case 'edit_instruction':
						//alert('index.php?action=edit_instruction&id='+ id);
						if(!g_win) var g_win = window.open('index.php?action=edit_instruction&id='+ id, 'TreatmentInstructions','width=750, height=480, status=no, toolbar=no, resizable=1');
						g_win.focus();
						c = false;
						break;
					default:
						c = true;
						break;
				}

				s.options.selectedIndex = 0;
              //  alert(c);
			//	if (c) window.location.href = '/admin/assign.php?ss=3&plan_id=346&update=1&step=1&' + 'act=' + a + '&treatment_id=' + treatment_id;
			}
			//-->
			</script>
<div><!msg></div>
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" onMouseOver="help_text(this, 'Display the treatments in the plan. Use the action menu to edit instructions for the selected treatment')">
	<thead>
	<!customize_instruction_head>
	</thead>
	<!customize_instruction_rec>
</table>
			<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
			<tr class="input">
				<td colspan="2" align="center">
					<input type="image" src="images/btn-back.jpg" value="Back" onClick="window.location='<!back_url>'" />&nbsp;
					<input type="image" src="images/btn-next.jpg" value="Next" onClick="window.location.href='index.php?action=customize_articles&plan_id=<!plan_id>'" /></td>
			</tr>
			</table>
					</div>

	</div>
	<div id="footer">
		<!footer>
	</div>
</div>
