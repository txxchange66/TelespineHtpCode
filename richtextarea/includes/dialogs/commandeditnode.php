<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Edit Properties</title>
	<link rel="stylesheet" type="text/css" href="../../richtextarea.css">
	<script language="JavaScript">
	<!--

	// get the opener information from the query string
	window.rta = window.opener.RichTextarea.getInstance('<?= $_GET['rta_id'] ?>');
	window.region = '<?= $_GET['rta_region'] ?>';
	window.node = false;
	window.attribute = null;

	function setup_form()
	{
		window.node = window.rta.getSelectedNode(window.region);
		if (window.node === false) window.close();
		else
		{
			document.getElementById('node_name').innerHTML = window.node.nodeName;
			var s = document.getElementById('attributes');
			for (var i in window.node.attributes)
			{
				if (typeof(window.node.attributes[i].value) != 'undefined')
					s.options[s.options.length] = new Option(window.node.attributes[i].name, window.node.attributes[i].value);
				//alert( + ":" + window.node.attributes[i].value);
			}
		}
	}

	function change_attribute(select)
	{
		var value = document.getElementById('value').value;

		// update the last selected attribute with the new value
		if (window.attribute) select.options[window.attribute].value = value;

		document.getElementById('value').value = select.options[select.selectedIndex].value;

		window.attribute = select.selectedIndex;
	}

	function reset_attribute()
	{
		if (window.attribute) document.getElementById('value').value = window.node.getAttribute(document.getElementById('attributes').options[window.attribute].text);
	}

	function new_attribute()
	{
		var newAttribute = prompt('Enter the property name to add', ' ');
		if (newAttribute != ' ' && newAttribute != null)
		{
			var s = document.getElementById('attributes');
			s.options[s.options.length] = new Option(newAttribute, '');
		}
	}

	function delete_attribute()
	{
		var s = document.getElementById('attributes');
		if (s.options[s.selectedIndex])
		{
			s.options[s.selectedIndex] = null;
			document.getElementById('value').value = '';
		}
	}

	function commandeditnode()
	{
		var s = document.getElementById('attributes');

		s.options[s.selectedIndex].value = document.getElementById('value').value;

		for (var i = 0; i <= s.options.length - 1; i++) window.node.setAttribute(s.options[i].text, s.options[i].value);

		window.close();
		return true;
	}

	//-->
	</script>
</head>
<body id="rta_dialog">

<form name="rta_form" id="rta_form" onSubmit="return commandeditnode();">
<table border="0" cellpadding="4" cellspacing="0" width="100%" height="100%">
<tr>
	<td valign="top" id="rta_panel">

		<table border="0" cellpadding="0" cellspacing="2" width="100%">
		<tr>
			<td colspan="2" rowspan="3" width="35%" style="padding-right:5px;">
				<label>Properties for <div id="node_name" style="display:inline;font-weight:bold">&nbsp;</div></label><br/>
				<select name="attributes" id="attributes" size="12" style="width:100%;" onChange="change_attribute(this)"></select><br/>
			</td>
			<td width="65%" height="1" colspan="2">
				Property Value:<br/>
			</td>
		</tr>
		<tr>
			<td width="65%" height="1">
				<input type="text" value="" name="value" id="value" size="60" style="width:100%"/>
			</td>
			<td width="1">
				<input type="button" value="Reset" onclick="reset_attribute()"/>
			</td>
		</tr>
		<tr>
			<td rowspan="2" colspan="2" valign="top">
				<br/>Information regarding the tag and attribute will be displayed here if it's available (Good idea: Have Matt collect the information regarding tags and attributes).
			</td>
		</tr>
		<tr>
			<td><input type="button" value="Delete" onClick="delete_attribute()" style="width:100%;"/></td>
			<td style="padding-right:5px;"><input type="button" value="Add" onClick="new_attribute()" style="width:100%;"/></td>
		</tr>
		</table>

	</td>
</tr>
<tr>
	<td height="1" id="rta_buttons">

		<hr>
		<input type="hidden" name="entity_hidden" id="entity_hidden" value=""/>
		<input type="button" value="Cancel" onClick="window.close();"/>
		<input type="submit" value="Apply Changes"/>

	</td>
</tr>
</table>

<script language="javaScript">
<!--
setup_form();
//-->
</script>

</body>
</html>
