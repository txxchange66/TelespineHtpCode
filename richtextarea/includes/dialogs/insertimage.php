<?php

include_once(dirname(__FILE__) . '/../../../runtime/_runtime.php');

define('RESTRICT_WIDTH', 1);
define('RESTRICT_HEIGHT', 2);

if(!defined('IMAGETYPE_GIF')) define('IMAGETYPE_GIF', 1);
if(!defined('IMAGETYPE_JPEG')) define('IMAGETYPE_JPEG', 2);
if(!defined('IMAGETYPE_PNG')) define('IMAGETYPE_PNG', 3);

$msg = null;

$imagePath = $siteCfg['rta']['image_path'];
if(!empty($_GET['imagePath'])) $imagePath = $_GET['imagePath'];

$imageURL = $siteCfg['rta']['image_url'];
if(!empty($_GET['imageURL'])) $imagePath = $_GET['imageURL'];

if (isset($_POST['submitted']))
{
	
	
	if ($_POST['location'] == "library")
	{
		$imageName = $_POST['libraryimage'];
	}
	elseif ($_POST['location'] == "upload")
	{
		if (isset($_FILES['uploadimage']))
		{
			if (verify_image($_FILES['uploadimage']['tmp_name']))
			{
				if (!isset($_POST['width'])) $_POST['width'] = 0;
				if (!isset($_POST['height'])) $_POST['height'] = 0;

				/* move uploaded file to image library */

				$aspect = null;
				if (isset($_POST['aspect']) == 'width') $aspect = RESTRICT_WIDTH;
				elseif (isset($_POST['aspect']) == 'height') $aspect = RESTRICT_HEIGHT;

				$imageUpdated = false;
				$imageName = false;
				$imageDetails = resize_image($_FILES['uploadimage']['name'], $_FILES['uploadimage']['tmp_name'], $imagePath, $_POST['width'], $_POST['height'], $aspect);

				if ($imageDetails) list($imageName, $imageUpdated) = $imageDetails;
				else $msg = 'Unable to process the image because of server configuration problems (permissions).';

				if ($imageUpdated) $msg = 'The image has been updated.  Please Shift-Refresh your browser to see the change.';
			}
			else $msg = 'Unable to process the uploaded image because the image type is unknown.';
		}
		else $msg = 'You selected to upload an image, but you didn\'t select an image to upload.';
	}

	?>
	<script language="JavaScript">
	
	// get the opener information from the query string
	window.rta = window.opener.RichTextarea.getInstance('<?= $_GET['rta_id'] ?>');
	window.region = '<?= $_GET['rta_region'] ?>';

	window.rta.handleCommand('insertimage', '<?= ($imageName) ? '<img align="'.$_POST['imagealign'].'" vspace="'.$_POST['vspace'].'" hspace="'.$_POST['hspace'].'" border="'.$_POST['imageborder'].'" src="'.$imageURL.$imageName.'" alt="" />' : '' ?>');
	window.close();
	<?= ($msg) ? 'window.opener.alert("'.$msg.'");' : '' ?>

	</script>
	<?php

}
$form_action_url = $PHP_SELF . '?rta_id=' . $_GET['rta_id'] . '&rta_region=' . $_GET['rta_region'];
if(!empty($_GET['imagePath'])) $form_action_url .= '&imagePath=' . $_GET['imagePath'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Image</title>
	<link rel="stylesheet" type="text/css" href="../../richtextarea.css">
	<script language="JavaScript">
	<!--

	function setOptions()
	{
		if(document.getElementById("location1").checked == true)
		{
			document.getElementById("width").disabled = true;
			document.getElementById("height").disabled = true;
			document.getElementById("aspect1").disabled = true;
			document.getElementById("aspect2").disabled = true;
		}
		else
		{
			document.getElementById("width").disabled = false;
			document.getElementById("height").disabled = false;
			document.getElementById("aspect1").disabled = false;
			document.getElementById("aspect2").disabled = false;
		}
	}

	var step = 1;
	var stepMax = 2;
	function goNext()
	{
		document.getElementById('step' + step).style.display = 'none';
		step++;
		document.getElementById('step' + step).style.display = 'block';

		if (step > 1) document.getElementById('step_back').disabled = false;
		if (step == stepMax)
		{
			document.getElementById('step_final').disabled = false;
			document.getElementById('step_next').disabled = true;
		}
	}

	function goBack()
	{
		document.getElementById('step' + step).style.display = 'none';
		step--;
		document.getElementById('step' + step).style.display = 'block';

		if (step == 1) document.getElementById('step_back').disabled = true;
		if (step != stepMax)
		{
			document.getElementById('step_final').disabled = true;
			document.getElementById('step_next').disabled = false;
		}
	}

	//-->
	</script>
</head>
<body id="rta_dialog">

<form method="POST" action="<?= $form_action_url ?>" enctype="multipart/form-data">
<table border="0" cellpadding="4" cellspacing="0" width="100%" height="100%">
<tr>
	<td valign="top" id="rta_panel">

		<div id="step1">
		<table border="0" cellpadding="4" cellspacing="0" width="100%">
		<tr>
			<td width="1"><label for="location1"><input type="radio" name="location" id="location1" checked="true" value="library" onclick="javascript:setOptions();">Existing Image</label></td>
			<td colspan="2" width="100%">
				<select name="libraryimage" onclick="javascript:document.getElementById('location1').checked = true; setOptions();">
				<?php
				if (is_dir($imagePath))
				{
					$imageDir = opendir($imagePath);
					while ($file = readdir($imageDir))
					{
						if (!is_dir($file)) echo '<option value="'.$file.'">'.$file.'</option>'."\n";
					}
				}
				else echo '<option>No images</option>';

				?>
				</select>
			</td>
		</tr>
		<tr>
			<td><label for="location2"><input type="radio" name="location" id="location2" value="upload" onclick="javascript:setOptions();">Upload Image</label></td>
			<td colspan="2"><input type="file" name="uploadimage" size="37" onclick="javascript:document.getElementById('location2').checked = true; setOptions();"></td>
		</tr>

		<tr><td rowspan="2">&nbsp;</td><td colspan="2">Upload Image Options</td></tr>
		<tr>
			<td colspan="2" style="padding:0px">

				<table border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td width="1" align="right"><label for="width">Width:</label></td>
					<td width="1"><input type="text" name="width" id="width" size="3" disabled="true" /></td>
					<td width="100%"><label for="aspect1"><input type="radio" name="aspect" id="aspect1" value="width" disabled="true" />Maintain aspect ratio on width</label></td>
				</tr>
				<tr>
					<td align="right"><label for="height">Height:</label></td>
					<td><input type="text" name="height" id="height" size="3" disabled="true" /></td>
					<td><label for="aspect2"><input type="radio" name="aspect" id="aspect2" value="height" disabled="true" />Maintain aspect ratio on height</label></td>
				</tr>
				</table>

			</td>
		</tr>
		</table>
		</div>

		<div id="step2" style="display:none">
		<table border="0" cellpadding="4" cellspacing="0">
		<tr>
			<td width="1"><label for="imagealign">Image Alignment:</label></td>
			<td width="100%">
				<select name="imagealign" id="imagealign">
					<option value="absbottom">Absolute Bottom</option>
					<option value="absmiddle">Absolute Middle</option>
					<option value="baseline">Baseline</option>
					<option value="bottom">Bottom</option>
					<option value="left" selected="true">Left</option>
					<option value="middle">Middle</option>
					<option value="right">Right</option>
					<option value="texttop">Text Top</option>
					<option value="top" selected>Top</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><label for="imageborder">Vertical Space:</label></td>
			<td>
				<input type="text" name="vspace" id="vspace" size="2" value="0">
			</td>
		</tr>
		<tr>
			<td><label for="imageborder">Horizontal Space:</label></td>
			<td>
				<input type="text" name="hspace" id="hspace" size="2" value="0">
			</td>
		</tr>
		<tr>
			<td><label for="imageborder">Image Border:</label></td>
			<td>
				<input type="text" name="imageborder" id="imageborder" size="2" value="0">
			</td>
		</tr>
		</table>
		</div>

	</td>
</tr>
<tr>
	<td height="1" id="rta_buttons">

		<hr>
		<input type="button" value="Cancel" onClick="window.close();" />
		<input type="button" value="&lt; Back" id="step_back" onClick="goBack()" disabled="true" />
		<input type="button" value="Next &gt;" id="step_next" onClick="goNext()" />
		<input type="submit" name="submitted" id="step_final" value="Insert Image" disabled="true" />

	</td>
</tr>
</table>
</form>

</body>
</html>
<?php

function verify_image($imageName)
{
	$imgInfo = getImageSize($imageName);
	$imgType = $imgInfo[2];

	if ($imgType == IMAGETYPE_JPEG) return true;
	elseif ($imgType == IMAGETYPE_PNG) return true;
	elseif ($imgType == IMAGETYPE_GIF) return true;

	return false;
}

function resize_image($fileName, $imageName, $imagePath, $dimX, $dimY, $dimRestrict = NULL)
{
	$imgInfo = getimagesize($imageName);
	$imgType = $imgInfo[2];
	$imageX = $imgInfo[0];
	$imageY = $imgInfo[1];

	// resize the image based on restrictions, otherwise we leave it alone
	if ($dimX != 0) $imageX = $dimX;
	if ($dimY != 0) $imageY = $dimY;
	if ($dimRestrict == RESTRICT_WIDTH) $imageY = $imgInfo[1] * ($imageX / $imgInfo[0]);
	elseif ($dimRestrict == RESTRICT_HEIGHT) $imageX = $imgInfo[0] * ($imageY / $imgInfo[1]);

	// create the destination image resource
	if (function_exists('imagecreatetruecolor'))
		$imageDest = imagecreatetruecolor($imageX, $imageY);
	else
		$imageDest = imagecreate($imageX, $imageY);

	// read the source image and use the correct function depending on format
	if ($imgType == IMAGETYPE_JPEG) $imageType = imagecreatefromjpeg($imageName);
	elseif ($imgType == IMAGETYPE_PNG) $imageType = imagecreatefrompng($imageName);
	elseif ($imgType == IMAGETYPE_GIF) $imageType = imagecreatefromgif($imageName);

	// gd 2.0 is needed for clean resampling, but sloppy resizing is always available
	if (function_exists('imagecopyresampled'))
		imagecopyresampled($imageDest, $imageType, 0,0,0,0, $imageX, $imageY, $imgInfo[0], $imgInfo[1]);
	else
		imagecopyresized($imageDest, $imageType, 0,0,0,0, $imageX, $imageY, $imgInfo[0], $imgInfo[1]);

	// make the image path and image name, strip file extension and change to .jpg
	$imageFileName = $imagePath.substr($fileName, 0, strlen($fileName) - 4).'.jpg';

	// check to see if the file is being overwritten
	$imgUpdated = false;
	if (file_exists($imageFileName)) $imgUpdated = true;

	// save the image to the image path
	if (!imageJPEG($imageDest, $imageFileName, 100)) return false;

	// return in an array: the filename, if the file was overwritten
	return array(substr($fileName, 0, strlen($fileName) - 4).'.jpg', $imgUpdated);
}

?>
