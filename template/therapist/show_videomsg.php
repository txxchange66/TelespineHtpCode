<script language="JavaScript">
function closeWindow(){
    parent.parent.GB_hide(); 
}

function change_parent_url(url)
 {
	//alert('hello sir');
	parent.parent.GB_CURRENT.hide();
	top.parent.document.detailform.submit();
 }
</script>
<table>
    <tr>
		<td><div align="left">

			Your video has been uploaded and is now being converted. Depending on it's size this process may take just a few minutes or up to an hour. You'll receive an email when it's ready for use.
		</div></td>
	</tr>

<tr><td>&nbsp;</td></tr>


<tr>		<td><div align="center">

			<input name="clear" value="&nbsp;OK&nbsp;" onclick="change_parent_url('h');" type="button">
		</div></td>
	</tr>
</table>
