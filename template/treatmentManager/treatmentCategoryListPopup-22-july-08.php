<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Tx Xchange - Admin Control Panel</title>
	<link rel="STYLESHEET" type="text/css" href="css/styles_popup.css">
	<script language="JavaScript" type="text/javascript" src="js/common.js"></script>
</head>
<body>

<div id="container">
	<div id="header">
		<!header>
	</div>
	<div id="mainContent">
			<h1 class="largeH1">SELECT CATEGORIES FOR TREATMENT </h1>
<form name="detailform" id="detail_form" enctype="multipart/form-data" method="POST" action="index.php?action=setCategories<!queryStrTreatment>">
<div align="center">
<table border="0" cellpadding="2" cellspacing="1" width="100%">
	<tr>
		<!categoryList>
	</tr>
	<tr>
		<td colspan="4"><div align="center">
			<input type="submit" name="submitted" value="Save Categories" />&nbsp;

			<input type="reset" name="reset" value="Reset" />&nbsp;
			<input type="button" name="clear" value="Cancel" onClick="window.close()" />
		</div></td>
	</tr>
</table>
</div>
</form>
	</div>
</body>
</html>