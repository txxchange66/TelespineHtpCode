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
		My Library	-->
			<!-- <p class="breadcrumbNav"><a href="#">Breadcrumb Nav</a></p> -->
<table style="vertical-align:middle;width:700px;"><tr><td style="width:400px;"><div id="breadcrumbNav"><a href="index.php?action=articleList" >ARTICLE</a> / <span class="highlight">SELECT ARTICLE</span></div></td><td style="width:300px;">	<table border="0" cellpadding="5" cellspacing="0" style="float:right;">
<tr>
	<td class="iconLabel"><a href="index.php?action=articleCreate"><img src="images/createNewArticle.gif" width="127" height="81" alt=""></a></td>

</tr>
</table>
	</td></tr></table>
<script language="JavaScript">
<!--

function handleAction(s, id)
{
	var a = s.options[s.options.selectedIndex].value;
	var c = false;

	switch (a)
	{
		case 'delete':
			c = confirm('Deleting this article will remove it completely from the system.  Are you sure you would like to continue with deleting this article?');
			break;
		case 'preview':
		showArticlePreview(id);
		break;
		default:
			c = true;
			break;
	}

	s.options.selectedIndex = 0;

	if (c) 
	{
		if ('edit' == a)
		{
			window.location.href = 'index.php?action=articleEdit&' + 'id=' + id;
		}	
		
		if ('delete' == a)
		{
			window.location.href = 'index.php?action=articleDelete&' +'id=' + id;
		}
	}
}
function showArticlePreview(id)
{
	if(!csw) var csw = window.open('index.php?action=articlePreview&aid='+id, 'catSelectWindow', 'width=750, height=480, status=no, toolbar=no, resizable=1,scrollbars=yes');
	csw.focus();
}

function submitFilter()
{
	var searchValue = document.forms['filter'].elements['search'].value;
	searchValue = Trim(searchValue);
	
	if(searchValue != '') 
	{
		if (isAlphaNumeric(searchValue))
		{
			document.forms['filter'].submit();			
		}
		else
		{
			//alert("Please enter valid search key in search text box");
			return true;
		}
		
	}
	else
	{
		alert("Please enter search key in search text box.");
		return false;
	}
}



-->
</script>

<!-- [title and common actions] -->
<h1 class="largeH1">Article Listing</h1>
<div style="padding:10px;color:red"><!error></div>

<!-- Filter -->
<!filter>
<!-- Filter -->

<!-- [/title and common actions] -->

<!-- [list] -->


<!-- [items] -->

<table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" onMouseOver="help_text(this, 'Displays the Articles, click on one to edit it.  To sort this list click on the column header you would like to sort by')">
<thead>
<tr>
<th width="20%" ><a href="index.php?action=articleList&sort=article_name<!order1><!searchStr>">Article Name</a>&nbsp;<!sortOrderImg1></th>
<th width="50%" ><a href="index.php?action=articleList&sort=headline<!order2><!searchStr>">Headline</a>&nbsp;<!sortOrderImg2></th>
<th width="20%" ><a href="index.php?action=articleList&sort=modified<!order3><!searchStr>">Last Update</a>&nbsp;<!sortOrderImg3></th>
<th width="10%" class="nosort" style="white-space:nowrap;text-align:right"><nobr>&nbsp;</nobr></th>
</tr>
</thead>

<!-- The sample row -->
<!--
<tr class="line2"><td><a href="article_detail.php?id=34" onClick="help_text(this, 'Click to edit this article')">Lumbar Spine Fusion</a></td>
<td>Surgery and Indications</td>
<td>Mon, Apr 30th 2007 at 11:21AM</td>

<td style="white-space:nowrap;text-align:right"><nobr><select size="1" class="action_select" onChange="handleAction(this, 34)"><option value="">Actions...</option><option value="preview"			id="act_preview">Preview Article</option><option value="edit"			id="act_edit">Edit Article</option><option value="delete"			id="act_delete">Delete Article</option></select></nobr></td>
</tr>


<tr class="line1"><td><a href="article_detail.php?id=36" onClick="help_text(this, 'Click to edit this article')">Rotator Cuff Injury</a></td>
<td>Basic Information for patients</td>
<td>Mon, Apr 30th 2007 at 11:21AM</td>
<td style="white-space:nowrap;text-align:right"><nobr><select size="1" class="action_select" onChange="handleAction(this, 36)"><option value="">Actions...</option><option value="preview"			id="act_preview">Preview Article</option><option value="edit"			id="act_edit">Edit Article</option><option value="delete"			id="act_delete">Delete Article</option></select></nobr></td>

</tr>
-->
<!--End of sample row-->

<!articleRows>

</table>
<!-- [/items] -->

<div class="paging">
		<span onMouseOver="help_text(this, 'Select a page number to go to that page')"><!link> </div>

<!-- [/list] -->

		</div>
	</div>
	<div id="footer">
		<!footer>
	</div>
</div>