<script language="JavaScript" src="js/countryState.js"></script>
<div id="container">
    <div id="header">
        <!header>
    </div>
    
    <div id="sidebar">
        <!sidebar>
    </div>
    
        
<div id="mainContent">
        <!--
        <img src="skin/tx/images/icons/user48.png" width="48" height="48" alt="" hspace="8">
        Subscriber Manager    -->
            <!-- <p class="breadcrumbNav"><a href="#">Breadcrumb Nav</a></p> -->
<table style="vertical-align:middle;width:700px;">
<tr><td style="width:400px;">
<div id="breadcrumbNav" style="margin-top:41px;"><a href="index.php?action=viewMyAccount" >MY ACCOUNT</a> / <span class="highlight">EDIT ACCOUNT INFORMATION</span></div></td><td style="width:300px;"></td></tr></table>
<script language="JavaScript" src="js/validateform.js"></script>
<script language="JavaScript">
<!--
window.formRules = new Array(
    //new Rule("username", "username", true, "string|4,20"),
    new Rule("username", "username", true, "email"),
    new Rule("question_id", "question", true, "integer"),
    new Rule("answer", "answer", true, "string|0,50"),
    new Rule("name_title", "title", false, "string|0,5"),
    new Rule("name_first", "first name", true, "string|0,50"),
    new Rule("name_last", "last name", true, "string|0,50"),
    new Rule("name_suffix", "name suffix", false, "string|0,5"),
    new Rule("address", "address", false, "string|0,50"),
    new Rule("address2", "address line 2", false, "string|0,50"),
    new Rule("city", "city", false, "string|0,50"),
    new Rule("state", "state", false, "string|0,2"),
    //new Rule("zip", "zip code", false, "zipcode"),
    //new Rule("email_address", "the users email address", true, "email"),
    //new Rule("phone1", "Subscriber's 1st phone number", false, "usphone"),
    //new Rule("phone2", "Subscriber's 2nd phone number", false, "usphone"),
    //new Rule("fax", "Subscriber's fax number", false, "usphone"),
    new Rule("new_password", "new password", false, "string|4,20"),
    new Rule("new_password2", "confirm password", false, "string|4,20"),
    new Rule("usertype_id", "user type", true, "integer"),
    new Rule("status", "user status", false, "integer"));
// -->
</script>
<!-- 
        
         Work : implement Uplodify in prefrrences page to upload profile photo page.
         Code Desc : placed code for uplodify(user may select files,3 picture(jpg,gif or png) and 1 video ,maximum.this code          will uplode file automatically)
         Author : Abhishek Sharma
         Created Date : 09 may 2011
         Organization : Hytech Professionals
         */
         
         
         -->
<link href="include/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="include/uploadify/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="include/uploadify/swfobject.js"></script>
<script type="text/javascript" src="include/uploadify/jquery.uploadify.v2.1.4.min.js"></script>  

<script type="text/javascript">
      
    

$(document).ready(function() {
        
                 

    
 $('#file_upload1').uploadify({
      'uploader'    : 'include/uploadify/uploadify.swf',
      'script'      : 'include/uploadify/uploadify.php',
      'cancelImg'   : 'include/uploadify/cancel.png',
      'folder'      : 'asset/images/profilepictures/<!user_id>',
      'fileExt'     : '*.jpg;*.gif;*.png;*.JPG;*.PNG;*.GIF',
      'fileDesc'    : 'Image Files',
      'displayData' : 'speed',
      'buttonImg'   : 'images/btn-browse.gif',
      'script' : 'include/uploadify/uploadify.php?name=<!user_id>_profile_pic',
       'onProgress'  : function(event,ID,fileObj,data) {
                          $("#upload").attr("disabled","disabled");  
                             },
          onComplete: function(event, queueID, fileObj, reponse, data) {
                             
                $("#upload").removeAttr("disabled");
                 location.reload();
           }   
           });  
  
  
          
                                                                       

} );

</script>

<script type="text/javascript">
<!--

function handle_action(s, id)
{
    var a = s.options[s.options.selectedIndex].value;
    var c = false;

    switch (a)
    {
        case 'delete':
            c = confirm('Deleting this user will remove him/her completely from the system.  Are you sure you would like to continue with deleting this user?');
            break;
        default:
            c = true;
            break;
    }

    s.options.selectedIndex = 0;

    if (c) window.location.href = '/admin/user_detail.php?' + 'act=' + a + '&id=' + id;
}


//-->
</script>
<!-- [title and common actions] -->

<!-- [/title and common actions] -->
<h1 class="largeH1" style="margin-top:39px;">Editing  Account&nbsp;<small><a href="index.php?action=viewMyAccount">[ View Mode ]</a></small></h1><!-- [user detail form] -->
<div color:red"><!error></div>

<form name="detailform" id="detail_form" enctype="multipart/form-data" method="POST" action="index.php?action=editMyAccount" onSubmit="return validate_form(this);">

<input type="hidden" name="id" value="<!id>">

<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
<div style="height:10px"></div>
<tr>
    <td colspan="2"><h3>Login Information</h3></td>
</tr>
<tr class="inputRow">
    <td><div style="width:160px"><label for="username" onMouseOver="help_text(this, 'Enter the login name used for this Subscriber to login to the admin panel and to access parts of the site if applicable')")>*&nbsp;Email Login&nbsp;</label></div></td>

    <td width="100%"><input type="text" name="username" id="username" size="20" maxlength="50" onMouseOver="help_text(this, 'Enter the login name used for this Subscriber to login to the admin panel and to access parts of the site if applicable')" value="<!username>"/></td>
</tr>
<tr class="inputRow">
    <td><div style="width:160px"><label for="new_password" onMouseOver="help_text(this, 'Enter the Subscriber\'s password (type a new password here to reset it)')")>&nbsp;&nbsp;&nbsp;New Password&nbsp;</label></div></td>
    <td width="100%"><input type="password" name="new_password" id="new_password" size="20" maxlength="20" value="<!new_password>"  /></td>
</tr>
<tr class="inputRow">
    <td><div style="width:160px"><label for="new_password2" onMouseOver="help_text(this, 'Enter the Subscriber\'s password (must match New Password)')")>&nbsp;&nbsp;&nbsp;Confirm Password&nbsp;</label></div></td>
    <td width="100%"><input type="password" name="new_password2" id="new_password2" size="20" maxlength="20" value="<!new_password2>"  /></td>
</tr>
<tr class="inputRow">
    <td><label for="question_id" onMouseOver="help_text(this, 'Choose the secret question')")>*&nbsp;Secret Question&nbsp;</label></td>

    <td><select name="question_id" id="question_id" onMouseOver="help_text(this, 'Choose the secret question')">
        <!questionOptions>
        </select>    

</td>
<tr class="inputRow">
    <td><div style="width:160px"><label for="answer" onMouseOver="help_text(this, 'Enter the answer for the secret question')")>*&nbsp;Answer&nbsp;</label></div></td>
    <td width="100%"><input type="text" name="answer" id="answer" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the answer for the secret question')" value="<!answer>" /></td>
</tr>
</tr>

<tr>
    <td colspan="2"><h3>Contact Information</h3></td>
</tr>
<tr><td colspan="2">
      <table style="width: 100%" cellpadding="3" cellspacing="0">
    <tr>
        <td><label for="name_first" onMouseOver="help_text(this, 'Enter the Subscriber\'s first name')")>*&nbsp;First Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </label></td>
        <td><input type="text" name="name_first" id="name_first"  maxlength="50" onMouseOver="help_text(this, 'Enter the Subscriber\'s first name')" value="<!name_first>"/></td>
        <td><label for="name_last" onMouseOver="help_text(this, 'Enter the Subscriber\'s last name')")>*&nbsp;Last Name&nbsp;</label></td>
        <td><input type="text" name="name_last" id="name_last"  maxlength="50" onMouseOver="help_text(this, 'Enter the Subscriber\'s last name')" value="<!name_last>"/></td>
    </tr>
    <tr>
        <td><label for="address" onMouseOver="help_text(this, 'Enter the Subscriber\'s address')")>&nbsp;&nbsp;&nbsp;Address&nbsp;</label></td>
        <td><input type="text" name="address" id="address"  maxlength="150" onMouseOver="help_text(this, 'Enter the Subscriber\'s address')" value="<!address>"/></td>
        <td><label for="address2" onMouseOver="help_text(this, 'Enter the Subscriber\'s address line 2')")>&nbsp;&nbsp;&nbsp;Address 2&nbsp;</label></td>
        <td><input type="text" name="address2" id="address2"  maxlength="150" onMouseOver="help_text(this, 'Enter the Subscriber\'s address line 2')" value="<!address2>"/></td>
    </tr>
    <tr>
        <td><label for="city" onMouseOver="help_text(this, 'Enter the Subscriber\'s city')")>&nbsp;&nbsp;&nbsp;City&nbsp;</label></td>
        <td><input type="text" name="city" id="city"  maxlength="150" onMouseOver="help_text(this, 'Enter the Subscriber\'s city')" value="<!city>"/></td>

        <td><label for="country" onMouseOver="help_text(this, 'Enter the Subscriber\'s country')")>&nbsp;&nbsp;&nbsp;Country&nbsp;</label></td>
        <td><select id="country" onchange="toggleState();" name="clinic_country" tabindex="5" >
		
									<!patient_country_options>
									  </select></td>
    </tr>
    <tr>
        <td><label for="zip" onMouseOver="help_text(this, 'Enter the Subscriber\'s zip code')")>&nbsp;&nbsp;&nbsp;Zip Code&nbsp;</label></td>
        <td><input type="text" name="zip" id="zip" size="10" maxlength="7" onMouseOver="help_text(this, 'Enter the Subscriber\'s zip code')" value="<!zip>"/></td>
        <td><label for="state" onMouseOver="help_text(this, 'Enter the Subscriber\'s state')")>&nbsp;&nbsp;&nbsp;State / Province &nbsp;</label></td>

        <td><select name="state" id="state" onMouseOver="help_text(this, 'Enter the Subscriber\'s state')">

<!stateOptions>
</select></td>
  </tr>
    <tr>
        <td><label for="fax" onMouseOver="help_text(this, 'Enter the Subscriber\'s fax number')")>&nbsp;&nbsp;&nbsp;Fax&nbsp;</label></td>
        <td><input type="text" name="fax" id="fax" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the Subscriber\'s fax number')" value="<!fax>"/></td>
      <td><label for="phone1" onMouseOver="help_text(this, 'Enter the Subscriber\'s 1st phone number')")>&nbsp;&nbsp;&nbsp;1st Phone&nbsp;</label></td>
        <td><input type="text" name="phone1" id="phone1" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the Subscriber\'s 1st phone number')" value="<!phone1>"/></td>
    </tr>
     <tr style="display:none;">
        <td></td>
        <td></td>
        <td><label for="phone2" onMouseOver="help_text(this, 'Enter the Subscriber\'s 2nd phone number')")>&nbsp;&nbsp;&nbsp;2nd Phone&nbsp;</label></td>
        <td><input type="text" name="phone2" id="phone2" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the Subscriber\'s 2nd phone number')" value="<!phone2>"/></td>
    </tr>
    <tr>
        <td colspan="1">
            <label for="timezone" onMouseOver="help_text(this, 'Choose the time zone')")>&nbsp;&nbsp;&nbsp;Time Zone&nbsp;</label>
        </td>
        <td colspan="3">
            <select name="timezone" id="timezone" onMouseOver="help_text(this, 'Choose the time zone')">
                <!timezoneOptions>
            </select>
        </td>
    </tr>
</table>


   </td></tr>
<!--<tr class="inputRow">
    <td><div style="width:160px"><label for="name_first" onMouseOver="help_text(this, 'Enter the Subscriber\'s first name')")>*&nbsp;First Name&nbsp;</label></div></td>
    <td width="100%"><input type="text" name="name_first" id="name_first" size="50" maxlength="50" onMouseOver="help_text(this, 'Enter the Subscriber\'s first name')" value="<!name_first>"/></td>
</tr>
<tr class="inputRow">
    <td><div style="width:160px"><label for="name_last" onMouseOver="help_text(this, 'Enter the Subscriber\'s last name')")>*&nbsp;Last Name&nbsp;</label></div></td>

    <td width="100%"><input type="text" name="name_last" id="name_last" size="50" maxlength="50" onMouseOver="help_text(this, 'Enter the Subscriber\'s last name')" value="<!name_last>"/></td> 
</tr>  
<tr class="inputRow">
    <td><div style="width:160px"><label for="address" onMouseOver="help_text(this, 'Enter the Subscriber\'s address')")>&nbsp;&nbsp;&nbsp;Address&nbsp;</label></div></td>
    <td width="100%"><input type="text" name="address" id="address" size="50" maxlength="150" onMouseOver="help_text(this, 'Enter the Subscriber\'s address')" value="<!address>"/></td>
</tr> 
<tr class="inputRow">
    <td><div style="width:160px"><label for="address2" onMouseOver="help_text(this, 'Enter the Subscriber\'s address line 2')")>&nbsp;&nbsp;&nbsp;Address 2&nbsp;</label></div></td>
    <td width="100%"><input type="text" name="address2" id="address2" size="50" maxlength="150" onMouseOver="help_text(this, 'Enter the Subscriber\'s address line 2')" value="<!address2>"/></td>
</tr>
        
<tr class="inputRow">
    <td><div style="width:160px"><label for="city" onMouseOver="help_text(this, 'Enter the Subscriber\'s city')")>&nbsp;&nbsp;&nbsp;City&nbsp;</label></div></td>
    <td width="100%"><input type="text" name="city" id="city" size="50" maxlength="150" onMouseOver="help_text(this, 'Enter the Subscriber\'s city')" value="<!city>"/></td>
</tr>  
<tr class="inputRow">
    <td><div style="width:160px"><label for="state" onMouseOver="help_text(this, 'Enter the Subscriber\'s state')")>&nbsp;&nbsp;&nbsp;State&nbsp;</label></div></td>
    <td width="100%"><select name="state" id="state" onMouseOver="help_text(this, 'Enter the Subscriber\'s state')">

<!stateOptions>
</select>
</td>
</tr>  
<tr class="inputRow">
    <td><div style="width:160px"><label for="zip" onMouseOver="help_text(this, 'Enter the Subscriber\'s zip code')")>&nbsp;&nbsp;&nbsp;Zip Code&nbsp;</label></div></td>

    <td width="100%"><input type="text" name="zip" id="zip" size="10" maxlength="10" onMouseOver="help_text(this, 'Enter the Subscriber\'s zip code')" value="<!zip>"/></td>
</tr>  -->  
<!--
<tr class="inputRow">
    <td><div style="width:160px"><label for="email_address" onMouseOver="help_text(this, 'Enter the Subscriber\'s email address so the they can receive email messages')")>*&nbsp;Email Address&nbsp;</label></div></td>
    <td width="100%"><input type="text" name="email_address" id="email_address" size="50" maxlength="150" onMouseOver="help_text(this, 'Enter the Subscriber\'s email address so the they can receive email messages')" value="<!email_address>"/></td>
</tr>

<tr class="inputRow">
    <td><div style="width:160px"><label for="phone1" onMouseOver="help_text(this, 'Enter the Subscriber\'s 1st phone number')")>&nbsp;&nbsp;&nbsp;1st Phone&nbsp;</label></div></td>
    <td width="100%"><input type="text" name="phone1" id="phone1" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the Subscriber\'s 1st phone number')" value="<!phone1>"/></td>

</tr>  
<tr class="inputRow">
    <td><div style="width:160px"><label for="phone2" onMouseOver="help_text(this, 'Enter the Subscriber\'s 2nd phone number')")>&nbsp;&nbsp;&nbsp;2nd Phone&nbsp;</label></div></td>
    <td width="100%"><input type="text" name="phone2" id="phone2" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the Subscriber\'s 2nd phone number')" value="<!phone2>"/></td>
</tr>
<tr class="inputRow">
    <td><div style="width:160px"><label for="fax" onMouseOver="help_text(this, 'Enter the Subscriber\'s fax number')")>&nbsp;&nbsp;&nbsp;Fax&nbsp;</label></div></td>
    <td width="100%"><input type="text" name="fax" id="fax" size="20" maxlength="20" onMouseOver="help_text(this, 'Enter the Subscriber\'s fax number')" value="<!fax>"/></td>
</tr>  -->  
<tr>
    <td colspan="2">&nbsp;</td>
</tr>
   <tr>
    <td colspan="2"><h3>My Profile</h3></td>
</tr>
<tr>
    <td colspan="2" >
         <table>
           <tr><td style="width:160px;"><img width="91" height="85" src="<!propic>"></td><td  valign="top"><strong>Upload Picture </strong>
               <br>
        <br>
            <div>
            <div style="float:left; width:155px;"><div style="float:right; position:relative;"><img src="images/btn-upload.jpg" id="upload" name="upload" value="Upload" onClick="javascript:$('#file_upload1').uploadifyUpload();" style="cursor:pointer;"> <!-- <input type="button" id="upload" style="padding:1px 5px 3px 5px; margin-top:-1px;" name="upload" value="Upload" onClick="javascript:$('#file_upload1').uploadifyUpload();"> --> </div><input id="file_upload1" name="file_upload1" type="file"  /><div style="clear:both;"></div></div><div style="float:left;">  </div>
              </div>
           
           
            </td></tr>
         </table>
    
    
    
   
    
    </td>
</tr>



<tr>
    <td>&nbsp;</td>
    <td style="width:200px;" align="left">
                <br /><br />
        <input type="submit" name="submitted_edit" value="Save Changes">        &nbsp;                
    </td>
    

</tr>
</table>
</form>
<!-- [/user detail form] -->

        </div>
        </div>
    <div id="footer">
        <!footer>
    </div>
</div>