<body>
	<link rel="STYLESHEET" type="text/css" href="css/styles.css">
	<script language="JavaScript" src="js/validateform.js"></script>
	<script language="JavaScript" src="js/treatment.js"></script>
	
	<script type="text/JavaScript">
    	var GB_ROOT_DIR = "js/greybox/";
    </script>
    <script type="text/javascript" src="js/greybox/AJS.js"></script>
    <script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
    <script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
	<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" /> 
<div id="container">
	<div id="header">
		<!header>
	</div>
	
	<div id="sidebar">
		<!sidebar>
	</div>
	
	
	
	<div id="mainContent">
<table style="vertical-align:middle;width:700px;"><tr><td style="width:400px;"><div id="breadcrumbNav"><a href="index.php?action=treatmentManager" >TREATMENT</a> / <span class="highlight">CREATE TREATMENT</span></div></td><td style="width:300px;">	<table border="0" cellpadding="5" cellspacing="0">

<tr>
	<td class="iconLabel">
		<a href="index.php?action=createTreatment">
			<img src="images/createNewTreatment.gif" width="127" height="81" alt="Create New Treatment" style="padding-left:208px;"></a></td>
	
</tr>
</table>
	</td></tr></table>
<!-- [detail form] -->
<h1 class="largeH1">Create New Treatment</h1><form name="detailform" id="detailform" enctype="multipart/form-data" method="POST" action="index.php?action=createTreatment&formSubmit=submit" onSubmit="return validate_form(this);">
<div style="padding:10px;color:red"><!error></div>
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">

<tr>
	<td colspan="2"><h3>Treatment Details</h3></td>
</tr>
<tr class="input">
	<td><div style="width:160px"><label for="treatment_name" >*&nbsp;Treatment Name:&nbsp;</label></div></td>
	<td width="100%"><input type="text" name="treatment_name" id="treatment_name" size="50" value="<!treatment_name>"/></td>
</tr>
<tr class="input">
	<td valign="top">
		<label >
			&nbsp;&nbsp;&nbsp;Speciality:&nbsp;
		</label>
	</td>	
	
	<td>
					<!speciality>				
				
	</td>
</tr>
<tr class="input">
	<td valign=""><label for="instruction" >&nbsp;&nbsp;&nbsp;Default Instructions:&nbsp;</label></td>
	<td><textarea name="instruction" id="instruction" rows="4" cols="50" ><!instruction></textarea></td>
</tr>
<tr class="input">
	<td valign=""><label for="benefit" >&nbsp;&nbsp;&nbsp;Benefit of Treatment:&nbsp;</label></td>
	<td><textarea name="benefit" id="benefit" rows="4" cols="50" ><!benefit></textarea></td>
</tr>
<tr class="input">
	<td valign="top"><label for="sets" >&nbsp;&nbsp;&nbsp;Sets:&nbsp;</label></td>
	<td><input type="text" name="sets" id="sets" size="20" maxlength="20"  value="<!sets>"/></td>

</tr>
<tr class="input">
	<td valign="top"><label for="reps" >&nbsp;&nbsp;&nbsp;Reps:&nbsp;</label></td>
	<td><input type="text" name="reps" id="reps" size="20" maxlength="20"  value="<!reps>"/></td>
</tr>
<tr class="input">
	<td valign="top"><label for="hold" >&nbsp;&nbsp;&nbsp;Hold:&nbsp;</label></td>
	<td><input type="text" name="hold" id="hold" size="20" maxlength="20"  value="<!hold>"/></td>
</tr>
<tr class="input">
	<td valign="top"><label for="lrb" >&nbsp;&nbsp;&nbsp;LRB:&nbsp;</label></td>
	<td>
		<table>
			<tr>
				<td>
					<!lrb>
				</td>				
			</tr>
		</table>
	</td>
</tr>
<tr class="input">
<!tagForm>
<!--<tr class="input">
<td>&nbsp;</td>
	<td><div align="left"><a href="javascript:void(0)" onClick="showCatSelect()" >Choose Treatment Categories</a></div></td>

</tr>-->
<tr>
	<td colspan="2"><h3>Treatment Media</h3></td>
</tr>
<tr class="input">
	<td><label for="pic1" >&nbsp;&nbsp;&nbsp;Picture 1:&nbsp;</label></td>
	<td><input name="pic1" type="file"  />&nbsp;No File</td>
</tr>
<tr class="input">
	<td><label for="pic2" >&nbsp;&nbsp;&nbsp;Picture 2:&nbsp;</label></td>
	<td><input name="pic2" type="file" />&nbsp;No File</td>
</tr>
<tr class="input">
	<td><label for="pic3" >&nbsp;&nbsp;&nbsp;Picture 3:&nbsp;</label></td>
	<td><input name="pic3" type="file" />&nbsp;No File</td>
</tr>
<tr class="input">
	<td><label for="video" >&nbsp;&nbsp;&nbsp;Video:&nbsp;</label></td>
	<td><input name="video" type="file" />&nbsp;No File</td>
</tr>
<tr>
	<td colspan="2"><h3>Treatment Availability</h3></td>
</tr>
<tr class="input">
	<td><label for="status" >&nbsp;&nbsp;&nbsp;Status:&nbsp;</label></td>
	<td><select name="status" id="status" >
<!statusOption>
</select>
</td>

</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr class="input">
		<td>&nbsp;</td>
		<td style="width:200px;" align="left" colspan="2"><br /><br />
		<input type="hidden" name="submitted" value="Add Treatment">
		<a  href="index.php?action=confirmPopupTreatmentCreate" title="Tx Xchange: Add Treatment" rel="gb_page_center[600, 310]"><input type="button" name="submitted" value="Save Treatment"></a>
	</td>
</tr>
</table>
</form>
<!-- [/detail form] -->

		</div>
	</div>
	
	<div id="footer">
		<!footer>
	</div>
</div>

</body>
</html>
