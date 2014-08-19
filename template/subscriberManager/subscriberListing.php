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
		Subscriber Manager	-->
			<!-- <p class="breadcrumbNav"><a href="#">Breadcrumb Nav</a></p> -->
<table style="vertical-align:middle;width:700px;"><tr><td style="width:400px;"><div id="breadcrumbNav"><a href="index.php?action=subscriberListing" >SUBSCRIBER</a> / <span class="highlight">SELECT SUBSCRIBER</span></div></td><td style="width:300px;">	<table border="0" cellpadding="5" cellspacing="0" style="float:right;">

<tr>
	<td class="iconLabel"><a href="index.php?action=createSubscriber"><img src="images/createNewSubscriber.gif" width="127" height="81" alt=""></a></td>
</tr>
</table>
	</td></tr></table>
<script language="JavaScript">
<!--
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

function handleAction(s, id)
{
	var a = s.options[s.options.selectedIndex].value;
	var c = false;

	switch (a)
	{
		case 'delete':
			c = confirm('Deleting this user will remove him/her completely from the system.  Are you sure you would like to continue with deleting this user?');
			break;	
		case 'inactive':
			c = true;
			break;
		case 'active':			
			c = true;	
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
			window.location.href = 'index.php?action=editSubscriber&' + 'id=' + id;
		}	
		
		if ('inactive' == a)
		{
			//window.location.href = 'index.php?action=inactiveSubscriber&' + 'id=' + id;
			window.location.href = 'index.php?action=subscriberListing&' +'id=' + id+'&do='+a;
		}
		
		if ('active' == a)
		{
			window.location.href = 'index.php?action=activeSubscriber&' + 'id=' + id;
		}
		
		if ('delete' == a)
		{
			//window.location.href = 'index.php?action=deleteSubscriber&' +'id=' + id;
			window.location.href = 'index.php?action=subscriberListing&' +'id=' + id+'&do='+a;
		}
	}
}


-->
</script>

<!-- [title and common actions] -->
<h1 class="largeH1">Subscriber Listing</h1>
<div style="padding:10px;color:red"><!error></div>

<!-- Filter -->
<!filter>
<!-- Filter -->

<!-- [/title and common actions] -->

<!-- [user list] -->


<!-- [items] -->
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" onMouseOver="help_text(this, 'Displays the Subscribers, click on one to edit it.  To sort this list click on the column header you would like to sort by')">
<!subscriberTblHead>
<!subscriberTblRecord>

<!-- Sample Rows-->
<!--
<tr class="line2"><td><a href="user_detail.php?id=1"><b>303admin</b></a></td>
<td> 303 Admin </td>
<td>Site Admin</td>
<td>Tue, Aug 14th 2007 at 02:43PM</td>
<td style="white-space:nowrap;text-align:right"><nobr><select size="1" class="action_select" onChange="handleAction(this, 1)"><option value="">Actions...</option><option value="edit"			id="act_edit">Edit Subscriber</option><option value="delete"			id="act_delete">Delete Subscriber</option></select></nobr></td>
</tr>
<tr class="line1"><td><a href="user_detail.php?id=10"><b>hytechpro</b></a></td>
<td> hytech pro </td>

<td>Provider</td>
<td>Wed, Jun 6th 2007 at 11:03PM</td>
<td style="white-space:nowrap;text-align:right"><nobr><select size="1" class="action_select" onChange="handleAction(this, 10)"><option value="">Actions...</option><option value="edit"			id="act_edit">Edit Subscriber</option><option value="delete"			id="act_delete">Delete Subscriber</option></select></nobr></td>
</tr> -->
<!-- Sample Rows-->


</table>
<!-- [/items] -->
<div class="paging">
		<span onMouseOver="help_text(this, 'Select a page number to go to that page')"><!link> </div>
<!-- [/user list] -->

		</div>
		</div>
	<div id="footer">
		<!footer>
	</div>
</div>