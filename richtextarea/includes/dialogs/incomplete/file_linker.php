<?php
	$base_path=$_SERVER['DOCUMENT_ROOT'] . "/aplastic/";
	$file_path=$base_path . "files/dms/";
	//phpinfo();
?>
<html>
<head>
<title>File Linker</title>
</head>
<body>
	<script language="javascript">
		function makeFileLink()
		{
			linkString = "<a href=\"";
			linkString += document.filer.fileLibrarySelect.value;
			if(document.filer.fileOpenModeRadio[0].checked == true) linkString += "\" target=\"_blank\">"
			else linkString += "\">"
			linkString += document.filer.fileLibrarySelect.value;
			linkString += "</a>";
			document.writeln(linkString);
		}
	</script>							
	<form name="filer" method="post" enctype="multipart/form-data" action="<?= $PHP_SELF ?>">
		<table border="1">
			<tr>
				<td>
					Select File:
				</td>
				<td>
					<select name="fileLibrarySelect">
						<?php
							if(is_dir($file_path))
							{
								$file_dir=opendir($file_path);
								while($file=readdir($file_dir))
								{
									if(!is_dir($file))
									{
										echo "<option value=".'"'.$file.'"'.">".$file."</option>\n";
									}
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					Open in: <input type="radio" name="fileOpenModeRadio" checked="true" value="new">New Window
				</td>
				<td>
					<input type="radio" name="fileOpenModeRadio" value="same">Same Window
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="button" name="fileSubmitButton" value="Insert File" onclick="javascript:makeFileLink();">
				</td>
			</tr>
		</table>
	</form>
</body>
</html>
