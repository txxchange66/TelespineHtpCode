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
<table  border="0"style="vertical-align:middle;width:700px; margin-bottom:0px;" ><tr>
<td style="width:400px; " >
	<div id="breadcrumbNav"  style="margin-top:0px;margin-bottom:22;">
		<a href="index.php?action=therapistLibrary" >ARTICLE</a> / <span class="highlight" >SELECT ARTICLE</span></div>
		</td><td style="width:300px;">	<table border="0" cellpadding="5" cellspacing="0" style="float:right;">
<tr>
	<td class="iconLabel"><a href="index.php?action=articleCreateThpst"><img src="images/createNewArticle.gif" width="127" height="81" alt=""></a></td>

</tr>
</table>
	</td>
		</tr></table>
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
			window.location.href = 'index.php?action=articleEditThpst&' + 'id=' + id;
		}	
		
		
		if ('delete' == a)
		{
			window.location.href = 'index.php?action=therapistLibArticleDelete&' +'id=' + id;
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
<h1 class="largeH1" style="margin-top:-2px; height:26px;">Article Library</h1>
<span style="color:red"><!error></span>
<table border="0" cellpadding="10" cellspacing="1"  height="47px" width="100%">
	<tr>
	<td style="valign:middle;">
<!-- Filter -->
<!filter>
<!-- Filter -->
</td>
	<td align="right"></td>
	</tr>
	</table>
<!-- [/title and common actions] -->
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" onMouseOver="help_text(this, 'Display the articles. To sort this list click on the column header you would like to sort by. Use action menu to preview or delete the articles')">
<!therapistArticleTblHead>
<!articleRows>

</table>
<!-- [/items] -->

<div class="paging">
		<span onMouseOver="help_text(this, 'Select a page number to go to that page')"><!link></span> </div>

<!-- [/list] -->

		</div>
	</div>
	<div id="footer">
		<!footer>
	</div>
</div>