<!-- Grey box -->
<script type="text/javascript">  
var GB_ROOT_DIR = "js/greybox/";
function disableButton(){
	
	document.getElementById('submitbutton').disabled=true;
	document.getElementById("submitbutton").className = 'rgt-d';
	return true;
}
</script>
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>

<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function checkemail(str){
var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
if (filter.test(str))
return true;
else
return false;
}
function trim(str, chars) {
	return ltrim(rtrim(str, chars), chars);
}
 
function ltrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("^[ ]+", "g"), "");
}
 
function rtrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("[ ]+$", "g"), "");
}

function checkAlpha(str)
{	var regexNum = /\d/;
	var regexLetter = /[a-zA-z]/;
	if(!regexNum.test(str) && regexLetter.test(str))
		return true
	else
		return false;
	
}
// Check that a string contains only numbers
function isNumeric(string, ignoreWhiteSpace) {
   if (string.search) {
      if ((ignoreWhiteSpace && string.search(/[^\d\s]/) != -1) || (!ignoreWhiteSpace && string.search(/\D/) != -1)) return false;
   }
   return true;
}

function toggleDivs()
{	document.getElementById("failmessage").style.display="none";
	document.getElementById("form").style.display="inline";
	document.getElementById("toggle").style.marginTop="0px";
	document.getElementById("toggle").style.lineHeight="15px";
	document.getElementById("emailadd").focus();
}
// Check that the number of characters in a string is between a max and a min
function isValidLength(string, min, max) {
   if (string.length < min || string.length > max) return false;
   else return true;
}
// Check that a string contains only letters and numbers
function isAlphanumeric(string, ignoreWhiteSpace) {
   if (string.search) {
      if ((ignoreWhiteSpace && string.search(/[^\w\s]/) != -1) || (!ignoreWhiteSpace && string.search(/\W/) != -1)) return false;
   }
   return true;
}
/*function isValidphonenumber(str){
var filter=/^[+]?[01]?[- .]?(\\([2-9]\\d{2}\\)|[2-9]\\d{2})[- .]?\\d{3}[- .]?\\d{4}$/i

if (filter.test(str))
return true;
else
return false;

}*/
function isValidUSPhoneNumber(areaCode, prefixNumber, suffixNumber) {
   if (arguments.length == 1) {
      var phoneNumber = arguments[0];
      phoneNumber = phoneNumber.replace(/\D+/g, '');
      var length = phoneNumber.length;
      if (phoneNumber.length >= 7) {
         var areaCode = phoneNumber.substring(0, length-7);
         var prefixNumber = phoneNumber.substring(length-7, length-4);
         var suffixNumber = phoneNumber.substring(length-4);
      }
      else return false;
   }
   else if (arguments.length == 3) {
      var areaCode = arguments[0];
      var prefixNumber = arguments[1];
      var suffixNumber = arguments[2];
   }
   else return true;

   if (areaCode.length != 3 || !isNumeric(areaCode) || prefixNumber.length != 3 || !isNumeric(prefixNumber) || suffixNumber.length != 4 || !isNumeric(suffixNumber)) return false;
   return true;
}
function validateFreeTrial(Frm)
{ 
 var flag1=flag2=flag3=flag4=flag5=flag6=flag7=flag8=flag9=flag10=flag11=flag12=1;
 //alert("hi");
 
 if(trim(Frm.business_name.value)==""){
     document.getElementById("business_names").innerHTML="<br />Please enter the business name";
     //return false;
  }
  else{
    document.getElementById("business_names").innerHTML="";
	flag1=0;
  }	
  if(trim(Frm.name_first.value)==""){
    document.getElementById("name_firsts").innerHTML="<br />Please enter first name";
  }
  else{
    document.getElementById("name_firsts").innerHTML="";
	flag2=0;
  }
  
  if(trim(Frm.name_first.value)!="")
  {
    if(!checkAlpha(trim(Frm.name_first.value)))
    { 
	  document.getElementById("name_firsts").innerHTML="<br />Please enter alphabets only";
	  flag2=1;
    }
	else{
    document.getElementById("name_firsts").innerHTML="";
	flag2=0;
    }
  }
	
  if(trim(Frm.name_last.value)==""){
    document.getElementById("name_lasts").innerHTML="<br />Please enter last name";
  }
  else{
    document.getElementById("name_lasts").innerHTML="";
	flag3=0;
  }
  if(trim(Frm.name_last.value)!="")
  {
    if(!checkAlpha(trim(Frm.name_last.value)))
    { 
	  document.getElementById("name_lasts").innerHTML="<br />Please enter alphabets only";
	  flag3=1;
    }
	else{
    document.getElementById("name_lasts").innerHTML="";
	flag3=0;
    }
  }
    
  if(Frm.practitiner_type.value==""){
    document.getElementById("provider_types").innerHTML="<br />Please choose provider type";
  }
  else{
    document.getElementById("provider_types").innerHTML="";
	flag4=0;
  }
  
  if(trim(Frm.phone1.value)==""){
    document.getElementById("phone1s").innerHTML="<br />Please enter phone number";
  }
  else{
    document.getElementById("phone1s").innerHTML="";
	flag5=0;
  }
  if(trim(Frm.phone1.value)!="")
  {
   if(!isValidUSPhoneNumber(trim(Frm.phone1.value))){
	document.getElementById("phone1s").innerHTML="<br />Please enter valid phone number only";
	  flag5=1
	}
	else{
    document.getElementById("phone1s").innerHTML="";
	flag5=0;
    }
  }
if(Frm.clinic_type.value==""){
    document.getElementById("clinic_types").innerHTML="<br />Please choose primary service type";
    flag6=1;
  }
  else{
    document.getElementById("clinic_types").innerHTML="";
	flag6=0;
  }
   if(trim(Frm.username.value)==""){
    document.getElementById("usernames").innerHTML="<br />Please enter email address";
  }
  else{
    document.getElementById("usernames").innerHTML="";
	flag7=0;
  }
  if(trim(Frm.username.value)!="")
  {
    if(!checkemail(Frm.username.value))
    { 
	  document.getElementById("usernames").innerHTML="<br />Please enter a valid email address";
	  flag7=1;
    }
	else if(Frm.username.value.indexOf("info@")!=-1)
    { 
	  document.getElementById("usernames").innerHTML="<br />info@XX.XXX like format is not allowed";
	  flag7=1;
    }
	/*else if(1){
            
        }*/
            else{
    document.getElementById("usernames").innerHTML="";
	flag7=0;
    }
  }

if(document.getElementById("name_firsts").innerHTML=="" && document.getElementById("name_lasts").innerHTML=="" 
  && document.getElementById("provider_types").innerHTML=="" && document.getElementById("phone1s").innerHTML=="" && document.getElementById("business_names").innerHTML==""
   && document.getElementById("clinic_types").innerHTML=="" && document.getElementById("usernames").innerHTML==""
  )
return true;
else
	{
                        if(flag1==1){
			Frm.business_name.focus();
			}
			else if(flag2==1){
			Frm.name_first.focus();
			}
			else if(flag3==1){
			Frm.name_last.focus();
			}
			else if(flag4==1){
			Frm.practitiner_type.focus();
			}
			else if(flag5==1){
			Frm.phone1.focus();
			}
			else if(flag6==1){
			Frm.clinic_type.focus();
			}else if(flag7==1){
			Frm.username.focus();
			}
                        
		return false;
	}
}
</script>
<style type="text/css">
table tr td.yellow-btn span{background:url('images/img_btn_lft_y.jpg') no-repeat; border:0; padding-left:1px; display:inline-block; } 
table tr td.yellow-btn input.rgt{background:url('images/img_btn_rgt_y.jpg') 100% 0; height:29px; padding: 0 6px 3px 5px;  border:0; color:#4c2506; font-family:Verdana; font-size:12px; font-weight:bold;  width:80px;}
table tr td.yellow-btn input.rgt-d{background:url('images/img_btn_rgt_y.jpg') 100% 0; height:29px; padding: 0 6px 3px 5px;  border:0; color:gray; font-family:Verdana; font-size:12px; font-weight:bold;  width:80px;}
</style>
<!-- End --><div id="container">
   <div id="header">
    <!header>                            
  </div>
    <div id="sidebar" style="padding-top:15px;">
    <div style="padding-top:300px;" >
     <table width="10" border="0" cellspacing="0" >
         <tr>
            <td>
                <!-- script goes Here -->
            </td>
         </tr>
         </table>
    </div>    
    </div>
    <div id="mainContent" style="padding-top:14px;">
    <div class=" heath-prog-con">
<div class="key-feature" >

<div class="cl">&nbsp;</div>
</div>
<div class="heath-prog-con"><!--<span>Tx Xchange Telehealth Software</span>--><h1>Tx Xchange Telehealth Software</h1></div>
<p>With a Tx Xchange account, you get our easy to use, cloud-based telehealth software, unlimited support, free training, and free expert consultation. There are no contracts, so sign-up risk-free and begin to increase your revenue, improve your profitability, and deliver health and wellness services much more efficiently than the traditional office-based model.</p>
<div class="singnupDetail">
<form name="patientSubs" id="patientSubs" action="index.php" method="post" onSubmit="return validateFreeTrial(this);">
  <table cellpadding="4" cellspacing="0" style="width: 100%" border="0">
		<tr>
			<td><b>First Name</b></td>
                                            <td><input name="name_first" id="name_first" type="text" value="<!name_first>" class="notfocussed"  onFocus="this.className='focussed'" onBlur="this.className='notfocussed'"  tabindex="1"/>
                                            <span id="name_firsts" style="color:#FF0000;font-weight:normal;"></span></td>
			<td><b>Business Name</b></td>
            <td><input name="business_name" id="business_name" type="text" value="<!business_name>" class="notfocussed"  onFocus="this.className='focussed'" onBlur="this.className='notfocussed'" tabindex="2"/><span id="business_names" style="color:#FF0000;font-weight:normal;"></span></td>
	    </tr>
		<tr><td colspan="4"></td></tr>
		
                <tr>
			<td><b>Last Name</b></td><td><input name="name_last" id="name_last" type="text" value="<!name_last>" class="notfocussed"  onFocus="this.className='focussed'" onBlur="this.className='notfocussed'"  tabindex="3"/><span id="name_lasts" style="color:#FF0000;font-weight:normal;"></span></td>
			<td><b>What type of provider are you?</b></td>
			<td>
                         <select name="practitiner_type" id="practitioner_type"  tabindex="4" ><option value="">What type of provider are you?</option><!PractitionerOptions></select>
			 <span id="provider_types" style="color:#FF0000;font-weight:normal;"></span></td>
		</tr>
		<tr><td colspan="4"></td></tr>
		<tr>
			<td><b>Email Address</b></td><td><input name="username" id="username" type="text" value="<!username>" class="notfocussed"  onFocus="this.className='focussed'" onBlur="this.className='notfocussed'" tabindex="5"/><!dublicateuser><span id="usernames" style="color:#FF0000;font-weight:normal;"></span></td>
			<td><b>What is the primary type of health<br/>
service your business provides?</b></td>
			<td>
            <select name="clinic_type" id="clinic_type" tabindex="6">
                <option value="">Please Select...</option>
                <!clinic_typeOptions>
			 	
            </select><span id="clinic_types" style="color:#FF0000;font-weight:normal;"></span></td>
		</tr>
		<tr><td colspan="4"></td></tr>
        
        <tr>
			<td><b>Phone Number</b></td><td><input name="phone1" id="phone1" type="text" value="<!phone1>" class="notfocussed"  onFocus="this.className='focussed'" onBlur="this.className='notfocussed'" tabindex="7"/><span id="phone1s" style="color:#FF0000;font-weight:normal;"></span></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
	</tr>
		
        
       
		<tr><td colspan="4"></td></tr>

                <tr>
                <td class="yellow-btn" style="padding-top:40px; padding-left: 300px;" colspan="4" >

                    <input type="hidden" value="account_regestration" name="action"><input type="hidden" name="cid" value="<!cid>">
<span><input type="submit" id="submit_input" class="rgt" value="Continue"  name="submit" tabindex="13"/></span>&nbsp;&nbsp;&nbsp; <a onclick="GB_showCenter('Privacy Policy', '/index.php?action=show_Privacy_policy',750,970);" title="" href="javascript:void(0);"><b>Privacy Policy</b></a></td>
                                                                                                                                                              
             </tr>
	</table>
	
</form>    
    </div>
</div>
    </div>
</div>
<div id="footer">
    <!footer>
</div>
</div>
