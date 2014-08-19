<div id="container">
	<div id="header">
		<ul id="mainNav"><li class="navBtn"><a href="index.php?action=patient" id="nav_item35"  class="selected">PATIENT&nbsp;HOME</a></li>
		<li class="navBtn"><a href="?action=logout" id="nav_item18" >LOGOUT</a></li>
		<li class="navBtn"><a href="?action=comingSoon" id="nav_item37" >ABOUT&nbsp;US</a></li>
		<li class="navBtn"><a href="?action=comingSoon" id="nav_item34" >CONTACT&nbsp;US</a></li>
		</ul></ul>
		
		<!header>
	</div>
	
	<div id="sidebar">
		<br><br><a href="?action=comingSoon"><img src="images/feedbackIcon.gif" alt="Give Us Feedback" width="117" height="27" /></a>
		
		<ul>&nbsp;</ul>	
	
			<ul class="sideNav">
	
				<li class="rolloverhelp"><div id="rolloverhelp" style="font-size: 85%; text-align:left;padding: 5px 1em;">Rollover buttons or elements on the page to get help and tips.</div><li>
	
			</ul>
	</div>
<div id="mainContent">
		<!--
		<img src="skin/tx/images/icons/user48.png" width="48" height="48" alt="" hspace="8">
		My Library	-->
			<!-- <p class="breadcrumbNav"><a href="#">Breadcrumb Nav</a></p> -->
<table style="vertical-align:middle;width:700px;"><tr><td style="width:400px;"><div id="breadcrumbNav"><a href="article.php" >ARTICLE</a> / <span ><!articleName></span> / <span class="highlight">EDIT ARTICLE</span></div></td><td style="width:300px;"><table border="0" cellpadding="5" cellspacing="0" style="float:right;">
<tr>

	<td class="iconLabel"><a href="article_detail.php"><img src="images/createNewArticle.gif" width="127" height="81" alt=""></a></td>
</tr>
</table> </td></tr></table><script language="JavaScript" src="/protosite2/form/validateform.js"></script>
<script language="JavaScript">
<!--
window.formRules = new Array(
	new Rule("article_name", "name", true, "string|1,100"),
	new Rule("headline", "headline", false, "string|0,250"),
	new Rule("content_type", "content type", true, "integer"),
	new Rule("is_deleted", "status", false, "string|1,1"),
	new Rule("content_text", "text", false, "string"),
	new Rule("content_html", "html text", false, "string"),
	new Rule("link_url", "link url", false, "string|5,250"),
	new Rule("file_url", "file_url", false, "string|5,250"));
// -->
</script>

<script language="javascript" type="text/javascript" src="richtextarea/richtextarea.js"></script>
<script type="text/javascript">
<!--

function handle_action(s, id)
{
	var a = s.options[s.options.selectedIndex].value;
	var c = false;

	switch (a)
	{
		case 'delete':
			c = confirm('Deleting this article will remove it completely from the system.  Are you sure you would like to continue with deleting this article?');
			break;
		default:
			c = true;
			break;
	}

	s.options.selectedIndex = 0;

	if (c) window.location.href = '/admin/article_detail.php?' + 'act=' + a + '&id=' + id;
}

function validate_article_form(f)
{
	rta_submitWithRichTextareas();
	return validate_form(f);
}

function handle_type_change(s)
{
	var div1 = document.getElementById('content_1');
	var div2 = document.getElementById('content_2');
	var div3 = document.getElementById('content_3');
	switch(s.options[s.selectedIndex].value)
	{
		case '1':
			div1.style.display = 'block';
			div2.style.display = div3.style.display = 'none';
			break;
		case '2':
			div2.style.display = 'block';
			div1.style.display = div3.style.display = 'none';
			break;
		case '3':
			div3.style.display = 'block';
			div2.style.display = div1.style.display = 'none';
			break;
		default:
			div1.style.display = div2.style.display = div3.style.display = 'none';
			break;
	}
}
function toggleMediaDisplay(fieldName)
{
	var f = document.getElementById(fieldName);
	if(f)
	{
		if(f.style.display == 'none')
		{
			f.style.display = 'block';
		}
		else
		{
			f.style.display = 'none';
		}
	}
}
//-->
</script>

<!-- [title and common actions] -->

<!-- [/title and common actions] -->

<!-- [detail form] -->
<h1 class="largeH1">Editing Article: new article</h1>
<form name="detailform" id="detailform" enctype="multipart/form-data" method="POST" action="/admin/article_detail.php?id=40" onSubmit="return validate_article_form(this);">

<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
<tr>
	<td colspan="2"><h3>Article Details</h3></td>
</tr>
<tr class="input">
	<td><div style="width:160px"><label for="article_name" onMouseOver="help_text(this, 'Enter the article\'s name')")>*&nbsp;Article Name:&nbsp;</label></div></td>

	<td width="100%"><input type="text" name="article_name" id="article_name" size="50" maxlength="100" onMouseOver="help_text(this, 'Enter the article\'s name')" value="<!article_name>"/></td>
</tr>
<tr class="input">
	<td><label for="headline" onMouseOver="help_text(this, 'Enter the article\'s headline')")>Headline:&nbsp;</label></td>
	<td><input type="text" name="headline" id="headline" size="50" maxlength="250" onMouseOver="help_text(this, 'Enter the article\'s headline')" value="<!headline>"/></td>
</tr>
<tr>
	<td colspan="2"><h3>Article Content</h3></td>
</tr>
<tr class="input">
	<td><label for="content_type" onMouseOver="help_text(this, 'Choose the content type for this article')")>*&nbsp;Content Type:&nbsp;</label></td>

	<td><select name="content_type" id="content_type" onChange="handle_type_change(this)" onMouseOver="help_text(this, 'Choose the content type for this article')">
		<!options>
		</select>

</td>
</tr>
</table>

<div id="content_1" style="display:<!displayContent1>">
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">

<tr class="input">
	<td valign="top"><label for="content_html" onMouseOver="help_text(this, 'Enter the HTML text for the article')")>Content:&nbsp;</label></td>
	<td><script language="JavaScript" type="text/javascript">
<!--
if (!window.rta) rta = new RichTextarea('richtextarea/');
rta.writeRichTextarea(window.document, 'content_html', 1, '<!htmlContent><br>', '300px', '680px', false, '/', 'asset/images/article/', 'asset/images/article/');
//-->
</script>
</td>
</tr>
</table>
</div>

<div id="content_2" style="display:<!displayContent2>">
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
	<tr class="input">
		<td><div style="width:160px"><label for="link_url" onMouseOver="help_text(this, 'Enter the web address for the link (please remember to begin with "http://" or "https://")')")>Link URL:&nbsp;</label></div></td>

		<td width="100%"><input type="text" name="link_url" id="link_url" size="50" maxlength="250" onMouseOver="help_text(this, 'Enter the web address for the link (please remember to begin with "http://" or "https://")')" value="http://google.com"/></td>
	</tr>
</table>
</div>

<div id="content_3" style="display:<!displayContent3>">
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
	<tr class="input">
		<td><div style="width:160px"><label for="file_url" onMouseOver="help_text(this, 'Choose a file to upload and use for this article\'s content')")>File:&nbsp;</label></div></td>
		<td width="100%"><input name="file_url" type="file" /> Click browse to add a file.</td>

	</tr>
</table>
</div>

<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
	<tr>
		<td colspan="3"><h3>Article Permissions</h3></th>
	</tr>
	<tr class="input">
		<td><label for="is_deleted" onMouseOver="help_text(this, 'Choose the status for this article')")>Status:&nbsp;</label></td>

		<td><select name="is_deleted" id="is_deleted" onMouseOver="help_text(this, 'Choose the status for this article')">		
<option value="">Choose...</option>
<option value="0" selected="true">Active</option>
<option value="1">Inactive</option>
</select>
</td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>

	<tr>
		<td style="width:100px;"><strong>Actions:</strong></td>
		<td align="center" style="width:100px;">
			
			<br /><br/><select size="1" class="action_select" onChange="handle_action(this, 40)"><option value="">Actions...</option><option value="delete" id="act_delete">Delete Article</option></select>			<br /><br /><input type="submit" name="submitted_save" value="Save Changes">
		</td>
		<td></td>
	</tr>

</table>
</form>
<!-- [/detail form] -->

		</div>
		</div>
	<div id="footer">
		<!footer>
	</div>
</div>