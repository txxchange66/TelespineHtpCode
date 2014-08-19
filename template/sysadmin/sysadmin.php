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

		Home	-->

			<!-- <p class="breadcrumbNav"><a href="#">Breadcrumb Nav</a></p> -->



<script language="JavaScript" type="text/javascript">

<!--

function showPlan(id)

{

	if(! g_plan_win) var g_plan_win = window.open('/patient/plan_viewer.php?id='+id, 'g_plan_win', 'width=1024,height=768,scrollbars=auto');

	g_plan_win.focus();

}

function showArticlePreview(id)

{

	if(!csw) var csw = window.open('index.php?action=articlePreview&aid='+id, 'catSelectWindow', 'width=750, height=480, status=no, toolbar=no, resizable=1,scrollbars=yes');

	csw.focus();

}

//-->

</script>



<!-- patient quick search -->

<table>

<tr><td>
<!--
<div style="width:470px; display:inline;">

	<h3>PATIENT QUICK SEARCH</h3>

	<form action="patient.php" method="GET">

		<input type="text" size="20" maxlength="250" name="search" value="" />

		<input type="submit" name="searched" value="Submit" />

	</form>

</div>
-->
</td><td>



<!-- buttons -->

<div style="width:290px; float:right;" align="right"><br />

	<table border="0" cellpadding="5" cellspacing="0" style="float:right;">

	<tr>

		<!-- new plan btn -->

		<!-- new patient btn -->

		<td class="iconLabel">&nbsp;</td>

		<td class="iconLabel">&nbsp;</td>

	</tr>

	</table>



</div>

</td>

</tr>

<tr>

<td colspan="2">

<div >

<table width="70%" cellpadding="0" cellspacing="2" border="0" >

	<tr>

		<td style="width:470px;vertical-align:top;"><!-- top assigned plan list --><h1 class="mediumH1">Top Plans</h1>

			<table border="0" align="left" cellpadding="2" cellspacing="1" style="width:459px;" class="list" >



	<thead>



<tr>

<th width="40%" class="nosort" style="white-space:nowrap;"><nobr>Template Plan</nobr></th>

<!-- <th width="60%" class="nosort" style="white-space:nowrap;"><nobr>Description</nobr></th> -->

</tr>

</thead>

<!-- Top assigned plan tag -->

<!TopAssignedPlans>

	</table>

		

		</td>

		<td valign="top" style="width:280px;"><!-- top article list --><h1 class="smallH1">Top Articles</h1>

			<table border="0" align="left" cellpadding="2" cellspacing="1" style="width:280px;" class="list" >



	<thead>



<tr>

<th width="100%" class="nosort" style="white-space:nowrap;"><nobr>Article Name</nobr></th>

</tr>

</thead>

<!-- Top assigned article tag --> 

<!TopAssignedArticles>

	</table>

		</td>

	</tr>

</table>
<br />
</div>

</td>

</table>
		</div>
    <div id="footer">
       <!footer>
</div>
</div>



