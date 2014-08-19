<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>Subscription Confirmation</title>
	<meta name="description" content="<!metaDesc>">	
	<link rel="stylesheet" type="text/css" href="css/styles_popup.css" />
	
		
</head>
<body>

<div id="containerrm">

	<div id="mainContentrm">

<script language="JavaScript">
<!--

function processConfirm(formType)
{
	/*if(window.opener)
	{
		window.opener.location.reload();
		window.opener.focus();
	}
	*/
	//window.close();
	
	//submit form
	if (formType == 'add')
	{
		window.opener.document.addUserForm.submit();
	}
	else if (formType == 'edit')
	{
		window.opener.document.editUserForm.submit();
	}
	
	
	window.close();
}

function populateFields()
{
	
	
	/*
	
	Commenting because window.opener.addUserForm.name_first.value didn't worked in mozilla
	document.getElementById('name_first').innerHTML = window.opener.addUserForm.name_first.value;
	document.getElementById('name_last').innerHTML = window.opener.addUserForm.name_last.value;
	document.getElementById('username').innerHTML = window.opener.addUserForm.username.value;
	document.getElementById('confirmUsername').innerHTML = window.opener.addUserForm.confirmUsername.value;
	document.getElementById('address').innerHTML = window.opener.addUserForm.address.value;
	document.getElementById('address2').innerHTML = window.opener.addUserForm.address2.value;
	document.getElementById('city').innerHTML = window.opener.addUserForm.city.value;
	document.getElementById('state').innerHTML = window.opener.addUserForm.state.value;
	document.getElementById('zip').innerHTML = window.opener.addUserForm.zip.value;
	*/
	
	document.getElementById('name_first').innerHTML = window.opener.document.getElementById('name_first').value;
	document.getElementById('name_last').innerHTML = window.opener.document.getElementById('name_last').value;
	document.getElementById('username').innerHTML = window.opener.document.getElementById('username').value;
	document.getElementById('confirmUsername').innerHTML = window.opener.document.getElementById('confirmUsername').value;
	document.getElementById('address').innerHTML = window.opener.document.getElementById('address').value;
	document.getElementById('address2').innerHTML = window.opener.document.getElementById('address2').value;
	document.getElementById('city').innerHTML = window.opener.document.getElementById('city').value;
	document.getElementById('state').innerHTML = window.opener.document.getElementById('state').value;
	document.getElementById('zip').innerHTML = window.opener.document.getElementById('zip').value;
	
	
}

-->
</script>	

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td><form name="userConfirmForm" action="index.php" enctype="multipart/form-data" method="POST">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-style:solid; border-width:thin;">			
		 <tr>
			<td colspan="3">
				<h2>&nbsp;New Subscription Confirmation&nbsp;</h2>
			</td>
		  </tr>
		  <tr>
		   <td colspan="3">
		   	<div align="justify"> &nbsp;A new User will be created with the user details as listed below:</div>
		   </td>
		  </tr>	
		  <tr>
 			<td colspan="3">&nbsp;
 				
 			</td>
 		  </tr>	
		  <TR><TD colspan="3"><table width="660px">	  
          <tr>
            <td width="50%" valign="top">
			<div>
<!-- 2 -->

			<table cellspacing="2" cellpadding="2" width="100%">
              <tr>
                <td width="51%" nowrap="nowrap"><div align="right"><label for="name_first">First Name :&nbsp;</label> 
                </div></td>
                <td width="49%"><div id="name_first"> </div></td>              </tr>
              <tr>
                <td nowrap="nowrap"><div align="right"><label for="name_last" >Last Name :&nbsp;</label>
                </div></td>
                <td><div name="name_last" id="name_last"></div></td>
              </tr>
              <tr>
                <td nowrap="nowrap"><div align="right"><label for="username" >Email :&nbsp;</label>
                </div></td>
                <td><div name="username" id="username" ></div></td>
              </tr>
              <tr>
                <td nowrap="nowrap"><div align="right"><label for="confirmUsername" >Confirm Email :&nbsp;</label>
                </div></td>
                <td><div name="confirmUsername" id="confirmUsername"></div> </td>
              </tr>              
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>			
			</div></td>
            <td valign="top">
			<div id="divAddress">
<!--  1-->
			
<table cellspacing="2" cellpadding="2" width="100%">
  <tr>
    <td width="38%" valign="top" nowrap="nowrap"><div align="right"><label for="address" >Address :&nbsp;</label>
    </div></td>
    <td width="62%" valign="top"><div name="address" id="address"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div name="address2" id="address2"></div></td>
  </tr>
  <tr>
    <td nowrap="nowrap"><div align="right"><label for="city">City :&nbsp;</label>
    </div></td>
    <td><div name="city" id="city"></div></td>
  </tr>
  <tr>
    <td nowrap="nowrap"><div align="right"><label for="state" >State / Province :&nbsp;</label>
    </div></td>
    <td><div name="state" id="state"></div>   </td>
  </tr>
  <tr>
    <td nowrap="nowrap"><div align="right"><label for="zip" >Zip Code :&nbsp;</label> 
    </div></td>
    <td><div name="zip" id="zip"></div> </td>
  </tr>

</table>						
			</div>			
			</td>
            </tr>
			</table></TD></TR>
  <tr>
 	<td colspan="3">&nbsp;
 		
 	</td>
 </tr>           
 <tr>
 	<td colspan="3" align="right">
		Your account will now have <b><!totTherapists></b> Provider Subscriptions	
	</td>
 </tr>
 <tr>
 	<td colspan="3">&nbsp;
 		
 	</td>
 </tr>
 <tr>
            <td>&nbsp;</td>
           
            
    
    <td width="27%" align="right">&nbsp;</td>
 
    <td width="27%" align="right"><div align="left">
      <input type="button" name="Cancel" value="Cancel" onclick="javascript:window.close();" />
      <input type="button" name="Confirm" value="Confirm" onclick="processConfirm('<!formType>');" />
    </div></td>
 </tr>     
 <tr>
 <td>&nbsp;	</td>
	</tr>   </table>
		</form>			</td>
      </tr>
    </table>
	</div>
	</div>
	<script type="text/javascript" language="JavaScript">
<!--
window.onLoad = populateFields();
//-->
</script>
</body>
</html>