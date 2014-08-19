<div id="container">
	<div id="header">
		<!header>
	</div>
	
	<div id="sidebar">
		<!sidebar>
	</div>
<div id="mainContent">
<table style="vertical-align:middle;width:700px;"><tr><td style="width:400px;"><div id="breadcrumbNav"><a href="index.php?action=therapistLibrary" >ARTICLE</a> / <span ><!articleBreadcrumb></span> / <span class="highlight">EDIT ARTICLE</span></div></td><td style="width:300px;"><table border="0" cellpadding="5" cellspacing="0" style="float:right;">
<tr>
 	<td class="iconLabel"><a href="index.php?action=articleCreateThpst"><img src="images/createNewArticle.gif" width="127" height="81" alt=""></a></td>
</tr>
</table> </td></tr></table><script language="JavaScript" src="js/validateform.js"></script>

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
	if (c) window.location.href = 'index.php?action=therapistLibArticleDelete&' + 'id=' + id;
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
	switch(s.options[s.selectedIndex].value)
	{
		case '1':
			div1.style.display = 'block';
			div2.style.display = 'none';
			break;
		case '2':
			div2.style.display = 'block';
			div1.style.display = 'none';
			break;		
		default:
			div1.style.display = div2.style.display = 'none';
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

<!-- [detail form] -->
<h1 class="largeH1">Edit Article: <!articleHeaderBar></h1>
<div style="padding:10px;color:red"><!error></div>
<form name="detailform" id="detailform" enctype="multipart/form-data" method="POST" action="index.php?id=<!article_id>" >
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
<tr>
	<td colspan="2"><h3>Article Details</h3></td>
</tr>
<tr class="input">
	<td><div style="width:160px"><label for="article_name" >*&nbsp;Article Name:&nbsp;</label></div></td>
	<td width="100%"><input type="text" name="article_name" id="article_name" size="50" maxlength="100" value="<!article_name>" /></td>
</tr>
<!-- 
<tr class="input">
	<td valign="top">
		<label >
			&nbsp;&nbsp;&nbsp;Speciality:&nbsp;
		</label>
	</td>	
	<td>
    	<!speciality>				
	</td>
</tr>
-->
<!specialityform>
<tr class="input">
	<td><label for="headline" >&nbsp;&nbsp;&nbsp;Tags:&nbsp;</label></td>
	<td><input type="text" name="headline" id="headline" size="50" maxlength="250" value="<!headline>"/></td>
</tr>
<tr class="input">
	<td><label for="article_type" >*&nbsp;Content Type:&nbsp;</label></td>
	<td><select name="article_type" id="article_type" onChange="handle_type_change(this)" >
		<!options>
		</select>	
</td>
</tr>
</table>
<div id="content_1" style="display:<!displayContent1>">
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
	<tr class="input">
		<td><div style="width:160px"><label for="link_url" >&nbsp;&nbsp;&nbsp;Link URL:&nbsp;</label></div></td>
        <td width="100%"><input type="text" name="link_url" id="link_url" size="50" maxlength="250" onMouseOver="help_text(this, 'Enter the web address for the link (please remember to begin with http:// or https://)')" value="<!link_url>"/></td>
	</tr>
</table>
</div>
<div id="content_2" style="display:<!displayContent2>">
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
	<tr class="input">
		<td><div style="width:160px"><label for="file_url" >&nbsp;&nbsp;&nbsp;File:&nbsp;</label></div></td>
		<td width="100%"><input  name="file_url" type="file" /> <!mayBeShowMedia></td>
	</tr>
</table>
</div>

<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
<tr>
	<td colspan="2"><h3>Article Permissions</h3></td>
</tr>
<tr class="input">
	<td><div style="width:160px"><label for="status" >&nbsp;&nbsp;&nbsp;Status:&nbsp;</label></div></td>
	<td width="100%"><select name="status" id="status" >		
<!optionStatus>
</select></td>
</tr>
<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
<tr class="input">
	<td><div style="width:160px"><label for="actions" >&nbsp;&nbsp;&nbsp;Actions:&nbsp;</label></div></td>
	<td width="100%">
        <select size="1" style="width:100px;" class="action_select" onChange="handle_action(this, <!article_id>)"><option value="">Actions...</option><option value="delete" id="act_delete">Delete Article</option></select>		
	</td>
</tr>
<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
<tr class="input" align="left">
	<td><div style="width:160px">&nbsp;</div></td>
	<td width="100%">
	<input type="hidden" name="submitted_save" value="Save Changes">
	<input type="hidden" name="action" value="articleEditThpst" />
	<a  href="index.php?action=confirmPopupTherapistArticleEdit"  rel="gb_page_center[600, 310]"><input type="button" name="submitted_save" value="Save Changes"></a>		
	</td>
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