<div id="container">
	<div id="header">
		<!header>
	</div>
	<div id="sidebar">
		<!sidebar>
	</div>
	<div id="mainContent">
        <tr><td colspan="2"><br/> <div id = "messageDiv" style="display:block;"><span style="color:#54A113;font-weight:bold;padding:0px;margin:0px;"><!schdmsg></div></span></td></tr>
	<table style="vertical-align:middle; width:700px;">
	<tr><td style=" width:400px;">
	</td>
		<td style="float:right; vertical-align:middle;">
		<div id="page_nav" style="text-align:right;">
		
			</div></td></tr>
			</table><h1 class="largeH1">Assigned Articles&nbsp;<!plan_title></h1>
			<script language="JavaScript">
			<!--
			function handleAction(s, id ,pid,paid,day)
			{
				var a = s.options[s.options.selectedIndex].value;
				var c = false;
				switch (a)
				{
					case 'preview':
						showArticlePreview(id);
						break;
					case 'added_article_preview':
						showArticlePreview(id);
						break;
					default:
						c = true;
						break;
				}

				s.options.selectedIndex = 0;
				search=$('#search').val();
				if (c) window.location.href = 'index.php?action=assign_articles_automatic&sort=<!sort>&order=<!order>&page=<!page>&' + 'act=' + a + '&article_id=' + id + '&patient_id=' + pid + '&patientArticleId=' + paid +'&search='+search + '&day=' +day;
			}
			function showArticlePreview(id)
			{
				if(!csw) var csw = window.open('index.php?action=articlePreview&aid='+id, 'catSelectWindow', 'width=750, height=480, status=no, toolbar=no, resizable=1,scrollbars=yes');
				csw.focus();
			}
			
			//-->
			</script>
<!removePlanAricleMsg>

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
                        <td valign="top" ><input type="image" src="/images/save-btn.jpg" value=" Save " onClick="window.location.href='<!back_url>'" /></td>
                        <td valign="top" ></td>
                        <td valign="top" ></td>
                   </tr>
               </table>
			   </td></tr></tbody></table></div>
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
