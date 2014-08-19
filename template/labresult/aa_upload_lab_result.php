<form name="labresult" method="post" enctype="multipart/form-data" action="index.php?action=aa_submit_lab_result">
<table>
	<tr>
	<td colspan="2"><!error><div id="checkvalidfilename" style="color: red;"></div></td>
	</tr>
	<tr>
		<td>Select File:</td>
		<td><input type="file" name="labfile" value="Browse" /></td>
	</tr>
	<tr>
	<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td>File Name:</td>
                <td><input type="text" name="labname" id="labname" maxlength="25" value="<!filename>"><!makeprivatetemplate></td>
	</tr>
	<tr>
    <td>&nbsp;</td>
	<td><div style="color: gray; font-size: 12px; font-family: sans-serif ;">Use only (a-z, A-Z, 0-9,space,_, -)</div></td>
	</tr>
	<tr style="margin-top: 5px;">
		<td>&nbsp;</td>
		<td><input type="submit" name="save" value="Save" onclick="return checkvalidfilename();" style="width: 100px">
		<input type="hidden" value="<!pid>" name="pid"></td>
	</tr>
</table>
<input type="hidden" name="role"  value="<!role>">
</form>
<script language="javascript">
<!javascript>
function checkvalidfilename()
{
    var filename = document.getElementById('labname').value;
    filename = filename.replace(/^\s+|\s+$/g,'')
    if(filename==''){
        document.getElementById("checkvalidfilename").innerHTML = "Please enter valid file name";
        return false; 
    }
        
    if (!(/^[a-zA-Z0-9\-\ _]+$/.test(filename))) {
       document.getElementById("checkvalidfilename").innerHTML = "Please enter valid file name";
       return false; 
    }
    else 
    return true;
}
</script>