<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<style type="text/css">
.pop-up-con{ border-top:0;  background:#fff; padding:5px;}	
	
.pop-up-con table tr td b{color:#666666; font-size:12px; font-family:Arial, Helvetica, sans-serif; line-height:30px; }
.pop-up-con table tr td input{ width:260px;  height:23px;}
.pop-up-con table tr td select{width:260px; height:23px;}
.pop-up-con table tr td a{ color:#085aa4; text-decoration:underline;}
.pop-up-con table tr td a:hover{ color:#085aa4; text-decoration:none;}
.pop-up-con table tr td textarea{width:256px; height:100px; }
.dark{border:1px solid #808180;}
.light{border:1px solid #e0e0e0;}
.error{ 
	color: red;
}
</style>


</head>
<body><span id="error" style="color:#FF0000;font-weight:bold;"></span><div class="pop-up-con">
Send the health, wellness, and fitness providers you know across town or across the country an invite to join Tx Xchange. When they join, you'll be able to collaborate with them about shared patients and strengthen your referral network.. Thanks for being part of Tx Xchange and the future of online health, wellness, and fitness.<br /><br />
	   
    <table cellpadding="4" cellspacing="0" style="width: 100%">
		<form name="sendinvite" id="sendinvite" action="/index.php" method="post" >
		<tr>
          <td colspan="3" class="error"><!error>
          <input  type="hidden" name="action" value="send_invite_provider_to_provider"/></td>
           </tr>
		<tr>
			<td ><b>Provider Email</b><br/><input type="text" tabindex='1' autocomplete="off" class="dark"  id="provider_email" name="provider_email" value="<!provider_email>" onfocus="provider_email.className='dark';" onblur="provider_email.className='light';" /></td>
			<td style="width:100px;"></td>
			<td><b>Provider Type</b><br/><select tabindex='4' name="practitiner_type" id="practitiner_type" class="light"  onfocus="practitiner_type.className='dark';" onblur="practitiner_type.className='light';"   >
							<!PractitionerOptions>
							  </select></td>
		</tr>
		<tr><td colspan="3"></td></tr>
		<tr>
			<td><b>First Name</b><br/><input type="text" autocomplete="off" id ="name_first" tabindex='2' class="light" name="name_first" value="<!name_first>" onfocus="name_first.className='dark';" onblur="name_first.className='light';"/></td>
			<td></td>
			<td rowspan="2"><b>Personal Message</b><br/><textarea name="personal_message"  id="personal_message" class="light"  onfocus="personal_message.className='dark';" onblur="personal_message.className='light';"  tabindex='5'><!personalmessage></textarea>
			
			</td>
		</tr>
		
		<tr>
			<td><b>Last Name</b><br/><input type="text" autocomplete="off" tabindex='3' id="name_last" class="light" name="name_last" value="<!name_last>" onfocus="name_last.className='dark';" onblur="name_last.className='light';" /></td>
			<td></td>
		</tr>
		<tr><td colspan="3"></td></tr>
		<tr>
			
			<td align="right" colspan="3" class="btn">
            <input  type="hidden" name="Submit" value="send invite"/>
			<input type="image"  name ="Submit1" value="send invite" src="images/btn-send.jpg" style="width:75px; height:30px; margin-right:25px;" tabindex='6'/></td>
		</tr>
		</form>
	</table>
		</div>





</body>

</html>
