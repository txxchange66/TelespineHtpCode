<div id="container">
	<div id="header">
		<!header>
	</div>
	<div id="sidebar">
		<!sidebar>
	</div>
	<div id="mainContent">
	<table style="vertical-align:middle; width:700px;">
	<tr><td style=" width:400px;">
	<div id="breadcrumbNav">
		<a href="index.php?action=systemPlan"> MY TX PLANS </a>
					 / <a href="index.php?action=createNewSystemPlan&act=plan_edit&plan_id=<!plan_id>"><!plan_title></a> / <span class="highlight">CUSTOMIZE ARTICLES</span></div></td>
		<td style="float:right; vertical-align:middle;">
		<div id="page_nav" style="text-align:right;">
		<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td><a href="index.php?action=createNewSystemPlan&act=plan_edit&plan_id=<!plan_id>">
			<img src="/images/01_plan_gray.gif" /></a></td>
			<td><img src="/images/02_customize_red.gif" /></td><td>
			<table cellpadding="0" cellspacing="0" style="text-align:center; font-size:7pt;">
			<tr><tr><td><a href="index.php?action=createNewSystemPlan&act=plan_edit&plan_id=<!plan_id>">
			<img src="/images/stepIcons_1_gray_sm.gif"></a></td></tr>
			<tr><td><a href="index.php?action=systemSelectTreatment&plan_id=<!plan_id>">
			<img src="/images/stepIcons_2_gray_sm.gif"></a></td></tr>
			<tr><td><a href="index.php?action=systemCustomize_instruction&plan_id=<!plan_id>">
			<img src="/images/stepIcons_3_gray_sm.gif"></a></td></tr>
			<tr><td><img src="/images/stepIcons_4_red_sm.gif"></td></tr>
			</table>
			</td>
			<td><img src="images/03_patient_gray.gif" /></td>
		<td><img src="images/04_assign_gray.gif" /></td></tr>
			</table>
			</div></td></tr>
			</table><h1 class="largeH1">Articles in Plan:&nbsp;<!plan_title></h1>
			<script language="JavaScript">
			<!--
			function handleAction(s, id, a_id)
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

				if (c) window.location.href = 'index.php?action=systemCustomize_articles&plan_id=<!plan_id>&sort=<!sort>&order=<!order>&' + 'act=' + a + '&article_id=' + id;
			}
			function showArticlePreview(id)
			{
				if(!csw) var csw = window.open('index.php?action=articlePreview&aid='+id, 'catSelectWindow', 'width=750, height=480, status=no, toolbar=no, resizable=1,scrollbars=yes');
				csw.focus();
			}
			//-->
			</script>
<!-- Grey box -->
<script type="text/javascript">  
var GB_ROOT_DIR = "js/greybox/";
</script>
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
<!-- End -->

<table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" onMouseOver="help_text(this, 'Display the list of articles included in the plan. Use the action menu to preview or remove articles from the current plan')">
	<thead>
		<tr>
			<th width="20%" >Article Name</th>
			<th width="70%" >Headline</th>
			<th width="10%" class="nosort" style="white-space:nowrap;text-align:right"><nobr>&nbsp;</nobr></th>
		</tr>
	</thead>
	<!added_articles>
</table>
<br>
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
                            <td valign="top" ><input type="image" src="/images/btn-back.jpg"' onClick="window.location='<!back_url>'" /></td>
                            <td valign="top" ><!type_of_button></td>
                            <td valign="top" ><!publish_button></td>
                          </tr>
                     </table>			
                </td>

			</tr>

			</table>
					</div>
	</div>
	
	<div id="footer">
		<!footer>
	</div>
</div>
</body>
</html>

