<html>
<head>
<title>Link Editor</title>
</head>
	<script language="javascript">
		function makeLink()
		{
			var linkString = "<a href=\"";
			//linkString += document.glossaryLink.glossaryTerm.value;
			linkString += "\">";
			linkString += glossaryLink.elements['glossaryTerm'].options[glossaryLink.elements['glossaryTerm'].selectedIndex].text;
			linkString += "</a>";
			document.writeln(linkString);
		}
	</script>
<body>
	<form name="glossaryLink">
		<table border="1">
			<tr>
				<td>
					Choose Glossary term:
				</td>
				<td>
					<select name="glossaryTerm">
					<?php
						// Connecting, selecting database
						$link = mysql_connect('localhost', 'root', 'area102')
   						or die('Could not connect: ' . mysql_error());
						mysql_select_db('aamdsif') or die('Could not select database');
						// Performing SQL query
						$query = 'SELECT glossary_id, term, alternate_terms FROM glossary';
						$result = mysql_query($query) or die('Query failed: ' . mysql_error());
						while($row = mysql_fetch_assoc($result))
						{
							echo '<option value="' . $row[glossary_id] . '">' . $row[term] . '</option>';
							if($row[alternate_terms] != "")
							{
								$alt_terms=explode(",",$row[alternate_terms]);
								for($ctr=0; $ctr < count($alt_terms); $ctr++)
								{
									echo '<option value="' . $row[glossary_id] . '">' . trim($alt_terms[$ctr]) . '</option>';
								}
							}
						}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<input type="button" name="go" value="Make Link" onclick="javascript:makeLink()">
				</td>
				<td>
					<input type="button" value="Open glossary admin page" onclick="javascript:window.open('','','width=800, height=600, toolbar=yes, status=yes, resizeable=yes')">
				</td>
			</tr>
		</table>
	</form>
</body>
</html>
