<?php

include_once(dirname(__FILE__) . '/../../../runtime/_runtime.php');

include_once(dirname(__FILE__) . '/../../../db/_db.php');
include_once(dirname(__FILE__) . '/../../../form/_utils.php');

// instantiate the database and database tree modules
$db =& Db::getDefaultConnection();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Hyperlink</title>
	<link rel="stylesheet" type="text/css" href="../../richtextarea.css">
	<script language="JavaScript">
	<!--

	// get the opener information from the query string
	window.rta = window.opener.RichTextarea.getInstance('<?= $_GET['rta_id'] ?>');
	window.region = '<?= $_GET['rta_region'] ?>';

	function setup_form()
	{
	}

	function insertcomponent()
	{
		var selectedComponent = document.getElementById('component_id').options[document.getElementById('component_id').selectedIndex];

		var componentString = '<img src="<?= $siteCfg['weblisher']['root_url'] ?>admin/skin/global/component.png" name="component:' + selectedComponent.text + '" id="' + selectedComponent.value + '">';

		// return HTML to caller
		window.rta.handleCommand('insertcomponent', componentString);

		window.close();
		return true;
	}

	//-->
	</script>
</head>
<body id="rta_dialog">

<form name="rta_form" id="rta_form" onSubmit="return insertcomponent();">
<table border="0" cellpadding="4" cellspacing="0" width="100%" height="100%">
<tr>
	<td valign="top" id="rta_panel">

		<table border="0" cellpadding="4" cellspacing="0">
		<tr>
			<td align="right">
				<label for="component">Select a Component:</label>
			</td>
			<td>
				<?php

				$components = $db->select('component', 'component,component_id', '1', 'name');
				while ($component = $components->fetch())
				{
					$values[] = $component['component_id'];
					$labels[] = $component['component'];
				}

				echo make_select('component_id', $formValues, $values, $labels);

				?>
			</td>
		</tr>
		</table>

	</td>
</tr>
<tr>
	<td height="1" id="rta_buttons">

		<hr>
		<input type="button" value="Cancel" onClick="window.close();" />
		<input type="submit" value="Insert Component" />

	</td>
</tr>
</table>
</form>

<script language="javaScript">
<!--
setup_form();
//-->
</script>

</body>
</html>
