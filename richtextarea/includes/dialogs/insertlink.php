<?php

include_once(dirname(__FILE__) . '/../../../runtime/_runtime.php');

include_once(dirname(__FILE__) . '/../../../db/_db.php');
include_once(dirname(__FILE__) . '/../../../db/_dbtrees.php');
include_once(dirname(__FILE__) . '/../../../form/_utils.php');

// instantiate the database and database tree modules
$db =& Db::getDefaultConnection();
$dbTree = new DbTrees('section', 'parent_section_id');

//$file_path = $siteCfg['runtime']['dir'].$siteCfg['rta']['file_path'];

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
		linktext = window.rta.getSelectedText(window.region);
		if (typeof(linktext) != 'undefined') document.getElementById('linktext').value = linktext;
	}

	function insertlink()
	{
		var f = document.rta_form;

		// validate the form
		if (f.externurl.value == '' && f.linkTypeRadio[0].checked == true)
		{
			alert('Please enter a url or choose an internal or file link.');
			return false;
		}
		if (f.linktext.value == '' && f.linkTypeRadio[3].checked != true)
		{
			alert('Please enter link text.');
			return false;
		}

		// if this is a bookmark, make the bookmark and return
		if (f.linkTypeRadio[3].checked == true)
		{
			var linkString = '<a name="' + f.anchortag.value + '">';
			linkString += f.linktext.value;
			linkString += '</a>';

			window.rta.handleCommand('insertlink', linkString);
			window.close();
			return true;
		}

		// construct the URL for the link
		var urlString = "";
		if (f.linkTypeRadio[0].checked == true)
		{
			urlString += f.externurl.value;
		}
		else if (f.linkTypeRadio[1].checked == true)
		{
			urlString += "<?= $siteCfg['weblisher']['site_url'] ?>index.php?id=" + f.internalPageSelect.value;
		}
		else
		{
			urlString += "<?= $siteCfg['rta']['file_url'] ?>" + f.fileLinkSelect.value;
		}

		// construct the <a> tag

		var linkString = "<a href=\"";
		if (f.linktarget.value == 'popup')
		{
			linkString += "javascript:void window.open('";
			linkString += urlString;
			linkString += "','','width=";
			linkString += f.popupWidth.value;
			linkString += ', height=';
			linkString += f.popupHeight.value;
			if (f.popupTools.checked == true)
			{
				linkString += ", toolbar=yes";
			}
			else
			{
				linkString += ", toolbar=no";
			}
			if (f.popupStatus.checked == true)
			{
				linkString += ", status=yes";
			}
			else
			{
				linkString += ", status=no";
			}
			if (f.popupResizeable.checked == true)
			{
				linkString += ", resizeable=yes";
			}
			else
			{
				linkString += ", resizeable=no";
			}
			linkString += ", scrollbars=yes');\">";
			linkString += f.linktext.value;
			linkString += "</a>";
		}
		else
		{
			linkString += urlString;
			if (f.linktarget.value == '_blank')
			{
				linkString += "\" target=\"_blank\">";
			}
			else if (f.linktarget.value == '_parent')
			{
				linkString += "\" target=\"_parent\">";
			}
			else
			{
				linkString += "\">";
			}
			linkString += f.linktext.value;
			linkString += "</a>";
		}

		// return HTML to caller
		window.rta.handleCommand('insertlink', linkString);

		window.close();
		return true;
	}

	function setOptions()
	{
		if(document.getElementById('linktarget').value != 'popup')
		{
			document.getElementById('popupWidth').disabled = true;
			document.getElementById('popupHeight').disabled = true;
			document.getElementById('popupTools').disabled = true;
			document.getElementById('popupStatus').disabled = true;
			document.getElementById('popupResizeable').disabled = true;
		}
		else
		{
			document.getElementById('popupWidth').disabled = false;
			document.getElementById('popupHeight').disabled = false;
			document.getElementById('popupTools').disabled = false;
			document.getElementById('popupStatus').disabled = false;
			document.getElementById('popupResizeable').disabled = false;
		}
	}

	//-->
	</script>
</head>
<body id="rta_dialog">

<form name="rta_form" id="rta_form" onSubmit="return insertlink();">
<table border="0" cellpadding="4" cellspacing="0" width="100%" height="100%">
<tr>
	<td valign="top" id="rta_panel">

		<table border="0" cellpadding="4" cellspacing="0" width="100%">
		<tr>
			<td align="right">
				<label for="linktext">Hyperlink Text:</label>
			</td>
			<td width="100%">
				<input type="text" name="linktext" id="linktext" size="40" style="width:100%" value="" />
			</td>
		</tr>
		<tr>
			<td align="left" width="15%">
				<label for="ltr1"><input type="radio" name="linkTypeRadio" checked="true" id="ltr1"/>External URL:</label>
			</td>
			<td width="100%">
				<input type="text" name="externurl" id="externurl" size="40" style="width:100%" value="http://" onclick="javascript:document.getElementById('ltr1').checked = true;"/>
			</td>
		</tr>
		<tr>
			<td align="left" width="15%">
				<label for="ltr2"><input type="radio" name="linkTypeRadio" id="ltr2"/>Internal URL:</label>
			</td>
			<td>
				<?php

				$sections = $dbTree->getTree(0, 'type, position, title', '1');
				$sections = array('section_id' => 0, 'title' => 'Home', 'children' => $sections);

				$values = array();
				$labels = array();
				build_select_tree($sections, $values, $labels);

				echo make_select('internalPageSelect', $formValues, $values, $labels, 'onclick="javascript:document.getElementById(\'ltr2\').checked = true;"');

				?>
			</td>
		</tr>
		<tr>
			<td align="left" width="15%">
				<label for="ltr3"><input type="radio" name="linkTypeRadio" id="ltr3"/>File Link:</label>
			</td>
			<td>
				<select name="fileLinkSelect" onclick="javascript:document.getElementById('ltr3').checked = true;">
				<?php

				if (is_dir($siteCfg['rta']['file_path']))
				{
					$fileDir = opendir($siteCfg['rta']['file_path']);
					while ($file = readdir($fileDir))
					{
						if (!is_dir($file)) echo '<option value="'.$file.'">'.$file.'</option>'."\n";
					}
				}
				else echo '<option>No files</option>';

				?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="left" width="15%">
				<label for="ltr4"><input type="radio" name="linkTypeRadio" id="ltr4"/>Bookmark Tag:</label>
			</td>
			<td width="100%">
				<input type="text" name="anchortag" id="anchortag" size="40" style="width:100%" onclick="javascript:document.getElementById('ltr4').checked = true;"/>
			</td>
		</tr>
		<tr>
			<td align="right">
				<label for="linktarget">Target:</label>
			</td>
			<td align="left">
				<select name="linktarget" id="linktarget" onclick="setOptions();">
				<option value="" selected="true">Same Window</option>
				<option value="_blank">New Blank Window</option>
				<option value="_parent">Parent Window</option>
				<option value="popup">Popup Window</option>
				</select>
			</td>
		</tr>
		<tr><td>&nbsp;</td><td colspan="2">Popup Options:</td></tr>
		<tr>
			<td>&nbsp;</td>
			<td>

				<table border="0" cellpadding="2" cellspacing="0">
				<tr>
					<td align="right"><label for="popupWidth">Width:</label></td>
					<td><input type="text" name="popupWidth" id="popupWidth" value="800" disabled></td>
				</tr>
				<tr>
					<td align="right"><label for="popupHeight">Height:</label></td>
					<td><input type="text" name="popupHeight" id="popupHeight" value="600" disabled></td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="checkbox" name="popupTools" id="popupTools" disabled><label for="popupTools">Toolbar</label>
						<input type="checkbox" name="popupStatus" id="popupStatus" disabled><label for="popupStatus">Status</label>
						<input type="checkbox" name="popupResizeable" id="popupResizeable" disabled><label for="popupResizeable">Resizeable</label>
					</td>
				</tr>
				</table>

			</td>
		</tr>
		</table>

	</td>
</tr>
<tr>
	<td height="1" id="rta_buttons">

		<hr>
		<input type="button" value="Cancel" onClick="window.close();" />
		<input type="submit" value="Insert Hyperlink" />

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
<?php

function build_select_tree(&$node, &$values, &$labels, $level = 0)
{
	if ($level == 0)
	{
		$values[] = $node['section_id'];
		$labels[] = $node['title'];
	}

	if (empty($node['children'])) return;

	foreach ($node['children'] as $child)
	{
		$values[] = $child['section_id'];
		$labels[] = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level + 1).$child['title'];

		if (!empty($child['children'])) build_select_tree($child, $values, $labels, $level + 1);
	}
}


?>
