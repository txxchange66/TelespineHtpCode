<script type="text/javascript">  
function get_template_name(){
    GB_showCenter('Create Copy of Template Plan', '/index.php?action=save_as_template_plan&plan_id=<!plan_id>', 110, 360 );
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
	<table style="vertical-align:middle; width:700px;padding-top:15px;">
	<tr><td style=" width:400px;">
	<div id="breadcrumbNav">
		<a href="index.php?action=therapistPlan"> <!path_name> </a><!patient_name>
					 / <a href="index.php?action=createNewPlan&act=plan_edit&plan_id=<!plan_id>&type=<!type>&patient_id=<!patient_id>"><!plan_title></a> / <span class="highlight">CUSTOMIZE ARTICLES</span></div></td>
		<td style="float:right; vertical-align:middle;">
		<div id="page_nav" style="text-align:right;">
		<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td><!plan></td>
			<td><img src="/images/02_customize_red.gif" /></td><td>
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
			</td><td><!patient_image></td>
			<td><!assign_image></td></tr>
			</table>
			</div></td></tr>
			</table><h1 class="largeH1">Articles in Plan:&nbsp;<!plan_title></h1>
			<script language="JavaScript">
			<!--
			function handleAction(s, id ,a_id)
			{
				var a = s.options[s.options.selectedIndex].value;
				var c = false;
				switch (a)
				{
					case 'preview':
						showArticlePreview(id);
						break;
					case 'added_article_preview':
						showArticlePreview(a_id);
						break;
					default:
						c = true;
						break;
				}

				s.options.selectedIndex = 0;
				search=$('#search').val();
				if (c) window.location.href = 'index.php?action=customize_articles&plan_id=<!plan_id>&sort=<!sort>&order=<!order>&' + 'act=' + a + '&article_id=' + id +'&search='+search+'&page=<!page>';
			}
			function showArticlePreview(id)
			{
				if(!csw) var csw = window.open('index.php?action=articlePreview&aid='+id, 'catSelectWindow', 'width=750, height=480, status=no, toolbar=no, resizable=1,scrollbars=yes');
				csw.focus();
			}
			function previewPlan(id)
			{
				if(! g_plan_win) var g_plan_win = window.open('index.php?action=planViewer&id='+id, 'g_plan_win', 'width=1024,height=768,scrollbars=auto');
				g_plan_win.focus();
			}
			//-->
			</script>

<table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" onMouseOver="help_text(this, 'Display the list of articles included in the plan. Use the action menu to preview or remove articles from the current plan')">
	<thead>
		<tr>
			<th width="20%" >Article Name</th>
			<th width="60%" >Headline</th>
			<th width="18%" class="nosort" style="white-space:nowrap;text-align:right"><nobr>&nbsp;</nobr></th>
		</tr>
	</thead>
	<!added_articles>
</table>
<!articleFilter>
<h1 class="largeH1">Article Library</h1>
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" onMouseOver="help_text(this, 'Display all the available articles. Use the action menu to add or preview articles in the current plan')">
<!article_head>
<!article_library>
</table>
<div class="paging">
				<span onMouseOver="help_text(this, 'Select a page number to go to that page')"><!link></span></div>
			<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
			<tr class="input">
				<td colspan="2" align="center">
                <table>
                    <tr>
                        <td valign="top" ><input type="image" src="/images/btn-back.jpg" value=" Back " onClick="window.location.href='<!back_url>'" /></td>
                        <td valign="top" ><!type_of_button></td>
                        <td valign="top" ><!publish_button></td>
                   </tr>
               </table>
			   </td></tr></tbody></table></div>
					</div>
	</div>
	
	<div id="footer">
		<!footer>
	</div>
</div>
