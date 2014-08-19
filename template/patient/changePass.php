<script src="js/jquery.min.js"></script>
<!-- Grey box -->
<script type="text/javascript">  
var GB_ROOT_DIR = "js/greybox/";
</script>
<script type="text/javascript" src="js/greybox/AJS.min.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.min.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.min.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
<!-- End -->




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
                          $("#unsubscribe").attr("disabled","disabled");   
                             },
          onComplete: function(event, queueID, fileObj, reponse, data) {
                             
                $("#upload").removeAttr("disabled");
                  $("#unsubscribe").removeAttr("disabled");      
                 location.reload();
           }   
           });  
  
function funSummaryservices(patient_id){
     if(patient_id != null && patient_id != "" ){
         $.post('index.php?action=providerServiceslist',{id:patient_id}, function(data,status){
               
         
           if( status == "success" ){
               document.getElementById("summaryservices").innerHTML = data;
           }
           else{
               alert("Ajax connection failed.");
           }    
           
              
       }
   )        
   
}
else{
   alert("Patient Id not Found.");
            
    }
}

funSummaryservices("<!user_id>");          

    $('#timezone').change(function(){
        $('#timezoneform').submit();
    });

});

</script>
<script>
<!--
function action_handler(value){
    
   
    if(value==true)
                var status=1;
            else if(value==false)
                var status=2;
    if( status == ''){
        return;
    }
    url = '/index.php?action=patient_mass_subscribe&mass_message_access=' +status;
       GB_showCenter('Marketing Messages Confirmation', url, 210,500 );
    //window.location = url;
}

//-->
</script>


<script>
<!--
/*function subscribe_unsubscribe(value){
        if(confirm('By unsubscribing, you will not receive messages that contain market offers or business updates from Clinic. You will continue to receive messages from your provider that directly relate to the health, wellness, or fitness services you are receiving.\r\n \r\nDo you still want to unsubscribe?')){
            if(value==true)
                var status=1;
            else if(value==false)
                var status=2;
            $.post('index.php?action=patient_mass_subscribe',{mass_message_access:status}, function(data,status){
                    $content = document.getElementById("loading").innerHTML;    
                    document.getElementById("loading").innerHTML = "<img src='images/ajax-loader.gif' />";
                    if( status == "success" ){
                        if(/success/.test(data)){
                            GB_showCenter('Opting In/Out of Receiving Mass Messages from Account Admin', '/template/alert.php?action=mass_message_success', 100, 350 );
                        }
                        else if( /failed/.test(data) ){
                            GB_showCenter('Opting In/Out of Receiving Mass Messages from Account Admin', '/template/alert.php?action=mass_message_fail', 100, 350 );
                        }    
                        else{
                            alert(data);
                        }
                    }
                    else{
                        alert("Ajax connection failed.");
                    }    
                    
                    document.getElementById("loading").innerHTML = $content;   
                }
            )        
        
    }
    else
        return false;
    
}*/

// -->
</script>

<div id="container">
    <div id="header">
        <!header>
    </div>
<!sidebar>
    <div id="mainContent">
            <div id="breadcrumbNav" style="padding-left:5px;padding-top:40px;font-size:12px;">
                <a class="breadcrumbNavUnderline" href="index.php?action=patient">HOME</a>
                / <span class="highlight">MY ACCOUNT</span>
            </div>
        <!timezonechangedmessage>
                <div>    
                <div style="width: 729px; height: 10px; position: relative; left: -10px;">
            </div>            
<div style="padding:10px;color:red;display:none" id="error" ><!error></div>

<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
<tr>
    <td colspan="2"><form name="detailform" id="detail_form" enctype="multipart/form-data" method="POST" action="index.php?action=changePass"><table width="100%" >
<tr>
    <td colspan="2"><div align="left" class="largeH6" style="padding-left:5px;padding-top:5px;font-size:13px;letter-spacing: 1px;">&nbsp;&nbsp;CHANGE PASSWORD</div>
    </td>
</tr>
<tr>
    <td colspan="2">&nbsp;</div>
    </td>
</tr>

<tr class="inputRow">
    <td><div style="width:160px;font-size:13px;padding-left:5px;"><label for="old_password" >Old Password&nbsp;</label></div></td>
    <td width="100%"><input type="password" name="old_password" id="old_password" size="20" maxlength="20" /></td>
</tr>    
<tr class="inputRow">
    <td><div style="width:160px;font-size:13px;padding-left:5px;"><label for="new_password" >New Password&nbsp;</label></div></td>
    <td width="100%"><input type="password" name="new_password" id="new_password" size="20" maxlength="20" /></td>
</tr>
<tr class="inputRow">
    <td><div style="width:160px;font-size:13px;padding-left:5px;"><label for="new_password2" >Confirm Password&nbsp;</label></div></td>
    <td width="100%" VALIGN="bottom"><input type="password" name="new_password2" id="new_password2" size="20" maxlength="20" /><span style="padding-left:20px;"><input style="width:144px;" align='absmiddle' type="image" src="images/change-pass-button.jpg" name="submitted_change" value=""></span><input type="hidden" name="change_action" value="ChangePassword"></td>
</tr>
</table>  </form> 
 </td>
</tr>

 <!updatecraditcardinformation>
 <!unsubscribeButtonTemplate>
 <tr>
    <td colspan="2">&nbsp;</td>
</tr>
 <tr>
    <td colspan="2">&nbsp;</td>
</tr>
<tr>
    <td colspan="2"><div align="left" class="largeH6" style="padding-left:5px;padding-top:5px;font-size:13px;letter-spacing: 1px;">&nbsp;&nbsp;SERVICES SUMMARY</div></td>
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
</tr>
<tr class="inputRow">

    <td colspan="2">
        <!-- Summary Services -->
        <div name="summaryservices" id="summaryservices" style="width:325px;" >
        </div>        
    </td>
    
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
</tr> 
<tr>
    <td colspan="2">&nbsp;</td>
</tr>
<tr>
    <td colspan="2"><div align="left" class="largeH6" style="padding-left:5px;padding-top:5px;font-size:13px;letter-spacing: 1px;">&nbsp;&nbsp;MARKETING MESSAGES</div>
    </td>
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
</tr>
<tr class="inputRow">
	<td colspan="2"><div style="font-size:13px; padding-left:0px; padding-top:5px;"><input type="checkbox" name="mass_message_access" id="mass_message_access" class="checkBoxAlign" value="1" onclick="action_handler(this.checked,<!user_id>);" <!mass_message_access> /><span id='loading'><label for="mass_message_access" >&nbsp;I want to receive marketing messages and business updates from my provider.</label></span></div></td>
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
</tr> 
<tr>
    <td colspan="2">&nbsp;</td>
</tr>

<tr>
    <td colspan="2"><div align="left" class="largeH6" style="padding-left:5px;padding-top:5px;font-size:13px;letter-spacing: 1px;">&nbsp;&nbsp;MY PROFILE</div>
    </td>
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
</tr>
<tr>
    <td>
        <div>
            <div style="float:left; padding-right:50px;"><img width="91" height="85" src="<!propic>"></div>
            <div style="font-size:13px; float:left;"><label for="upload_image" > Upload Picture
        <br>
        <br>
       <div>
            <div style="float:left; width:155px;"><div style="float:right; position:relative;"> <img src="images/upload-button.jpg" id="upload" name="upload" value="Upload" valign="top" onClick="javascript:$('#file_upload1').uploadifyUpload();" style="cursor:pointer;"> </div><input id="file_upload1" name="file_upload1" type="file" valign="top"/><div style="clear:both;"></div></div><div style="float:left;">  </div>
       </div>

        <td>
            <div style="margin:-40px 0px 0px 0px; font-weight: bold;font-size: 13px;float: right;">
                <form id="timezoneform" name="timezoneform" method="post" action="index.php?action=changetimezone">
                    <label style="display:block;margin: 5px 0 8px;">Time Zone:</label>
                    <select name="timezone" id="timezone" style="width:95%;margin: 5px 0px 0px;">
                        <!timezoneOptions>
                    </select>
                </form>
            </div>
        </td>
    </td>
</tr>
</table>

                                                  
</div>                
</div>
<div id="footer">
    <!footer>
</div>
<script language="JavaScript" type="text/javascript">
<!--
$(document).ready(function() {
       var content = $("div#error").html();
       if( content != ''){
        if( content.match(/success/) ){
          alert(content);
          window.location = 'index.php';
        }
        else{
            alert(content);    
       }
      }
       
       
});

-->
</script>
