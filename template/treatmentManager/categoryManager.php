<script type="text/JavaScript">
     var GB_ROOT_DIR = "js/greybox/";
</script>
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />

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
		Treatment Manager	-->
			<!-- <p class="breadcrumbNav"><a href="#">Breadcrumb Nav</a></p> -->
<table style="vertical-align:middle;width:700px;"><tr><td style="width:400px;"><div id="breadcrumbNav"><a href="index.php?action=categoryManager" >TREATMENT CATEGORY</a> /<!breadcrumbCatName><span class="highlight"><!operationName> TREATMENT CATEGORY</span></div></td><td style="width:300px;">	<table border="0" cellpadding="5" cellspacing="0">

<tr>
	<td class="iconLabel">
		<a href="index.php?action=createCategory">
			<img src="images/createNewTreatment.gif" width="46" height="46" alt="Create New Treatment Category"><br/>
			CREATE NEW TREATMENT CATEGORY</a></td>
</tr>
</table>
	</td></tr></table><script language="JavaScript" src="js/validateform.js"></script>
<script language="JavaScript">
<!--
window.formRules = new Array(
	new Rule("category_name", "category name", true, "string|0,50")
	//new Rule("parent_category_id", "parent category", true, "integer")
     );
// -->
</script>

<script language="JavaScript">
<!--

function handleAction(s, id)
{
	var a = s.options[s.options.selectedIndex].value;
	var c = false;

	switch (a)
	{
		case 'delete':
			c = confirm('Deleting this treatment category will remove it completely from the system.  Are you sure you would like to continue with deleting this treatment category?');
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
			window.location.href = 'index.php?action=editCategory&' + 'id=' + id;
		}	
		
		if ('delete' == a)
		{
			window.location.href = 'index.php?action=deleteCategory&' +'id=' + id;
		}			
		
	}
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
<!-- [Treatment Category edit form] -->
<!categoryForm>
<!-- [/Treatment Category edit form] -->
<!-- [items] -->
<h1 class="largeH1">Treatment Category List</h1>
<!-- Filter --->
<!filter>
<!-- Filter --->
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" onMouseOver="help_text(this, 'Displays the Treatment Categories, click on one to edit it.  To sort this list click on the column header you would like to sort by')">
    <!categoryTableHead>
    <!categoryRecord>
</table>
<div class="paging">
		<span ><!link></div>
</div>
</div>
    <div id="footer">
 		<!footer>
	</div>
</div>