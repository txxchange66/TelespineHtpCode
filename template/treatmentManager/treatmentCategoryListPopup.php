<link rel="STYLESHEET" type="text/css" href="css/styles_popup.css">
<body>
<div id="container">
<div id="mainContent">
			<!thumbImage>
			<h1 class="largeH1">SELECT CATEGORIES FOR TREATMENT </h1>
<form name="detailform" id="detail_form" enctype="multipart/form-data" method="POST" action="index.php?action=setCategories<!queryStrTreatment>">
<div align="center">
<table border="0" cellpadding="2" cellspacing="1" width="100%">
	<tr>
		<!categoryList>
	</tr>
</table>
</div>
<div align="center" style="padding-bottom:20px;" >
            <input type="submit" name="submitted" value="Save Categories" />&nbsp;

            <!--<input type="reset" name="reset" value="Reset" />&nbsp;-->
            <input type="button" name="clear" value="Cancel" onClick="window.close()" />
        </div>
</form>
	</div>
</body>
</html>