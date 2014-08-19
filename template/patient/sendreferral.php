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
<script language="javascript">
function closeWindow(){
    parent.parent.GB_hide(); 
}
<!close>
</script>
<body><span id="error" style="color:#FF0000;font-weight:bold;"></span><div class="pop-up-con">
Thanks for taking a moment to refer us to someone you know. We'll provide them with the expertise and resources they need to achieve the outcomes they want. If you'd fill out the fields below for the person you want to refer us to and click "Send", they'll receive an email with our contact information, website, and your personal note. We appreciate your recommendations.
	   
    <table cellpadding="4" cellspacing="0" style="width: 100%" border="0">
		<form name="sendreferral" id="sendreferral" action="/index.php" method="post" >
		<tr>
          <td colspan="3" class="error"><!error></td>
        </tr>
		<tr>
          <td colspan="3" class="error">&nbsp;</td>
        </tr>
		<tr>
			<td><b>Their First Name</b><br/><input type="text" autocomplete="off" id ="name_first" tabindex='1' class="dark" name="name_first" value="<!name_first>" onfocus="name_first.className='dark';" onblur="name_first.className='light';"></td>
			<td style="width:55px;"></td>
		</tr>
		<tr><td colspan="3"></td></tr>
		<tr>
			<td><b>Their Last Name</b><br/><input type="text" autocomplete="off" tabindex='2' id="name_last" class="light" name="name_last" value="<!name_last>" onfocus="name_last.className='dark';" onblur="name_last.className='light';" ></td>
			<td></td>
			<td rowspan="2"><b>Your Personal Note</b><br/><textarea name="personal_message"  id="personal_message" class="light"  onfocus="personal_message.className='dark';" style="width:300px;" onblur="personal_message.className='light';"  tabindex='4'><!personalmessage></textarea>
			
			</td>
		</tr>
		
		<tr>
			<td ><b>Their Email Address</b><br/><input type="text" tabindex='3' autocomplete="off" class="light"  id="email" name="email" value="<!email>" onfocus="email.className='dark';" onblur="email.className='light';" ></td>
			<td></td>
		</tr>
		<tr><td colspan="3"></td></tr>
		<tr>
			
			<td align="right" colspan="3" class="btn">
			<input  type="hidden" name="action" value="sendreferral_send"/>
            <input  type="hidden" name="Submit" value="send referral"/>
			<input type="image"  name ="Submit1" value="send invite" src="images/btn-send.jpg" style="width:75px; height:30px; margin-right:25px;" tabindex='5'/></td>
		</tr>
		</form>
	</table>
		</div>





</body>

</html>
