        <script src="js/jquery.js"></script>  
<!-- Grey box -->
 <script language="JavaScript" type="text/javascript">
    var hook = false;
    window.onbeforeunload = function() {
        if($('#file_upload1Queue').html() == '' && $('#file_upload2Queue').html() == '' && $('#file_upload3Queue').html() == '' && $('#file_upload4Queue').html() == '' && $('#treatment_name').val() == '' && $('#instruction').val() == '' && $('#benefit').val() == '' && $('#sets').val() == '' && $('#reps').val() == ''  && $('#hold').val() == '' && $('#tag').val() == '' ) {
            hook = false;
           // alert('hi');
         }else if($('#clik_on_save').val()==1){
             hook = false;
            // alert('hi');
        }else{
            hook = true;
            //alert('by');
            }
         
      if (hook) {
          $('#clik_on_save').val(0);
          return "You have attempted to leave this page.  If you have made any changes to the fields without clicking the Save button, your changes will be lost.  Are you sure you want to exit this page?"
      }
    }
    function unhook() {
      hook=false;
      $('#clik_on_save').val(1);
    }
</script>
<script type="text/javascript">  
/*$(window).unload( function () { alert("Bye now!"); } );*/


var GB_ROOT_DIR = "js/greybox/";
</script>
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript"  >
function openWindow(title, url){
    GB_showCenter(title, url, 570, 970 );
    //GB_showFullScreen(title, url);
}
function show_videomsg(){
    GB_showCenter('Video Conversion', '/index.php?action=show_videomsg',210, 400 );
 return false;
}
</script>
    
    
    
        <!-- 
        
         Work : implement Uplodify in create treatment page.
         Code Desc : placed code for uplodify(user may select files,3 picture(jpg,gif or png) and 1 video ,maximum.this code will uplode file automatically)
         Created Date : 09 may 2011              
         */
         
         
         -->
<link href="include/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="include/uploadify/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="include/uploadify/swfobject.js"></script>
<script type="text/javascript" src="include/uploadify/jquery.uploadify.v2.1.4.min.js"></script>      
<script type="text/javascript">
function validateForm()
{
var x=document.forms["detailform"]["treatment_name"].value
if (x==null || x=="")
  {
  alert("Treatment Name must be filled out");
 //       alert("Video Name must be filled out");
  $('#clik_on_save').val(0);
  hook = true;
  return false;
  }
else {
        if($('#file_upload1Queue').html() == '' && $('#file_upload2Queue').html() == '' && $('#file_upload3Queue').html() == '' && $('#file_upload4Queue').html() == '') {
                alert("Please upload either 1 picture or Video file");
                hook = true;
                $('#clik_on_save').val(0);
                return false;
        }

if(document.getElementById("video_field").value=='1'){
GB_showCenter('Video Conversion', '/index.php?action=show_videomsg', 170, 400 );
return false;


}
}


}



</script>    

<script type="text/javascript">
      
    

$(document).ready(function() {


//$(".input input,.input object,.input div").css({float:"left"});
//$(".uploadifyQueue").remove();//({marginTop:"-4px", position:"absolute"});
  $('#file_upload1').uploadify({
      'uploader'    : 'include/uploadify/uploadify.swf',
      'script'      : 'include/uploadify/uploadify.php',
      'cancelImg'   : 'include/uploadify/cancel.png',
      'folder'      : 'asset/temporary/<!userId>',
      'fileExt'     : '*.jpg;*.gif;*.png;*.JPG;*.GIF;*.PNG;',
      'fileDesc'    : 'Image Files',
      'displayData' : 'speed',
      'buttonImg'   : 'images/btn-browse.gif',
      'script' : 'include/uploadify/uploadify.php?name=pic1',
      'auto' : 'true',
      'removeCompleted':false,
      'onProgress'  : function(event,ID,fileObj,data) {
      $("#submit_btn").attr("disabled","disabled");  
    },
      onComplete: function(event, queueID, fileObj, reponse, data) {
            
          
            $("#submit_btn").removeAttr("disabled");
       }
           });  
  
  

      $('#file_upload2').uploadify({
      'uploader'    : 'include/uploadify/uploadify.swf',
      'script'      : 'include/uploadify/uploadify.php',
      'cancelImg'   : 'include/uploadify/cancel.png',
      'folder'      : 'asset/temporary/<!userId>',
      'fileExt'     : '*.jpg;*.gif;*.png;*.JPG;*.GIF;*.PNG;',
      'fileDesc'    : 'Image Files',
      'displayData' : 'speed',
      'buttonImg'   : 'images/btn-browse.gif',
      'script' : 'include/uploadify/uploadify.php?name=pic2',
      'auto' : 'true',
      'removeCompleted':false,
      'onProgress'  : function(event,ID,fileObj,data) {
      $("#submit_btn").attr("disabled","disabled");  
    },
      onComplete: function(event, queueID, fileObj, reponse, data) {
         
         
            $("#submit_btn").removeAttr("disabled");
       }
        
     
      });
  

          $('#file_upload3').uploadify({
          'uploader'    : 'include/uploadify/uploadify.swf',
          'script'      : 'include/uploadify/uploadify.php',
          'cancelImg'   : 'include/uploadify/cancel.png',
          'fileExt'     : '*.jpg;*.gif;*.png;*.JPG;*.GIF;*.PNG;',
          'fileDesc'    : 'Image Files',
          'folder'      : 'asset/temporary/<!userId>',
          'displayData' : 'speed',
          'buttonImg'   : 'images/btn-browse.gif',
          'script' :  'include/uploadify/uploadify.php?name=pic3',
          'auto' : 'true',
          'removeCompleted':false,
          'onProgress'  : function(event,ID,fileObj,data) {
                          $("#submit_btn").attr("disabled","disabled");  
                             },
          onComplete: function(event, queueID, fileObj, reponse, data) {
                             
                $("#submit_btn").removeAttr("disabled");
           }
                 });  
     
  

  

      $('#file_upload4').uploadify({
      'uploader'    : 'include/uploadify/uploadify.swf',
      'script'      : 'include/uploadify/uploadify.php',
      'cancelImg'   : 'include/uploadify/cancel.png',
      'folder'      : 'asset/temporary/<!userId>',
      'buttonImg'   : 'images/btn-browse.gif',
      'script' : 'include/uploadify/uploadify.php?name=video',
      'auto' : 'true',
      'fileExt'     : '*.flv;*.m4v;*.mp4;*.avi;*.wmv;*.mov;*.FLV;*.M4V;*.MP4;*.AVI;*.WMV;*.MOV',
      'fileDesc'    : 'Video Files',
      'sizeLimit'   : '1073741824',
      'removeCompleted':false,
      'onProgress'  : function(event,ID,fileObj,data) {
      $("#submit_btn").attr("disabled","disabled");  
    },


        onError: function(event,queueID,fileObj,errorObj) {
                var errorText = '';
                if (errorObj.type == "File Size") {
                    setTimeout('$("#file_upload4Queue div span.percentage").html("<br>Only video less than 1 GB in size can be uploaded.")', 100)
                       
                }
               /* else 
                     errorText = 'An error occured during the upload of file ' + fileObj.name + '. Please try again.';
                    $("#uploaderror").html(errorText);*/
        
    },
      onComplete: function(event, queueID, fileObj, reponse, data) {
     
           $("#video_field").val("1");
            $("#submit_btn").removeAttr("disabled");

        
     }
  
      }); 
     

} );

</script>

<script type="text/javascript">  
var GB_ROOT_DIR = "js/greybox/";
</script>
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
    <script language="JavaScript" src="js/validateform.js"></script>
    <script language="JavaScript" src="js/treatment.js"></script>
<div id="container">
    <div id="header">
        <!header>
    </div>
    
    <div id="sidebar">
        <!sidebar>
    </div>
    
    
    
    <div id="mainContent">
<table style="vertical-align:middle;width:700px;"><tr><td style="width:400px;"><!--<div id="breadcrumbNav"><a href="index.php?action=treatmentManager" >TREATMENT</a> / <span class="highlight">CREATE TREATMENT</span></div>-->
<div id="breadcrumbNav"><a href="index.php?action=treatmentManager" >VIDEO</a> / <span class="highlight">CREATE VIDEO</span></div>
</td><td style="width:300px;">  <table border="0" cellpadding="5" cellspacing="0">

<tr>
    <td class="iconLabel">
        <a href="index.php?action=createTreatment">
            <img src="<!imageCreateTreatment>" width="127" height="81" alt="Create Video" style="padding-left:208px;"></a></td>
    
</tr>
</table>
    </td></tr></table>
<!-- [detail form] -->
<h1 class="largeH1"><!labelCreateNewTreatment></h1><form name="detailform" id="detailform" enctype="multipart/form-data" onsubmit="return validateForm()"  method="POST" action="index.php?action=createTreatment&formSubmit=submit" onSubmit="return validate_form(this);">
<div style="padding:10px;color:red"><!error></div>
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">

<tr>
    <td colspan="2"><h3><!labelTreatmentDetails></h3></td>
</tr>
<tr class="input">
    <td><div style="width:160px"><label for="treatment_name" >*&nbsp;<!labelTreatmentName>:&nbsp;</label></div></td>
    <td width="100%"><input type="text" name="treatment_name" id="treatment_name" size="50" value="<!treatment_name>"/></td>
</tr>
<!specialityform>
<!-- 
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
-->
    <tr class="input">
    <td valign=""><label for="instruction" >&nbsp;&nbsp;&nbsp;<!labelDefaultInstructions>:&nbsp;</label></td>
    <td><textarea name="instruction" id="instruction" rows="4" cols="50" ><!instruction></textarea></td>
</tr>
<tr class="input">
    <td valign=""><label for="benefit" >&nbsp;&nbsp;&nbsp;<!labelBenefitofTreatment>:&nbsp;</label></td>
    <td><textarea name="benefit" id="benefit" rows="4" cols="50" ><!benefit></textarea></td>
</tr>
<tr class="input" style="display:<!displayFieldSets>">
    <td valign="top"><label for="sets" >&nbsp;&nbsp;&nbsp;Sets:&nbsp;</label></td>
    <td><input type="text" name="sets" id="sets" size="20" maxlength="20"  value="<!sets>"/></td>

</tr>
<tr class="input" style="display:<!displayFieldReps>">
    <td valign="top"><label for="reps" >&nbsp;&nbsp;&nbsp;Reps:&nbsp;</label></td>
    <td><input type="text" name="reps" id="reps" size="20" maxlength="20"  value="<!reps>"/></td>
</tr>
<tr class="input" style="display:<!displayFieldHold>">
    <td valign="top"><label for="hold" >&nbsp;&nbsp;&nbsp;Hold:&nbsp;</label></td>
    <td><input type="text" name="hold" id="hold" size="20" maxlength="20"  value="<!hold>"/></td>
</tr>
<tr class="input" style="display:<!displayFieldLRB>">
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
<!tagForm1>
<tr>
    <td colspan="2"><h3><!labelTreatmentMedia></h3></td>
</tr>

</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form_container">
<tr class="input">
    <td valign="top" style="width:170px;"><label for="pic1" >&nbsp;&nbsp;&nbsp;Picture 1:&nbsp;</label></td>
    <td><!--<input name="pic1" type="file"  />--><input id="file_upload1" name="file_upload1" type="file" /></td>   
</tr>
<tr class="input">
    <td valign="top"><label for="pic2" >&nbsp;&nbsp;&nbsp;Picture 2:&nbsp;</label></td>
    <td><!--<input name="pic2" type="file" />--><input id="file_upload2" name="file_upload2" type="file" /></td>
</tr>
<tr class="input">
    <td valign="top"><label for="pic3" >&nbsp;&nbsp;&nbsp;Picture 3:&nbsp;</label></td>
    <td><!--<input name="pic3" type="file" />--> <input id="file_upload3" name="file_upload3" type="file" /></td>
</tr>
<tr class="input">
    <td valign="top"><label for="video" >&nbsp;&nbsp;&nbsp;Video:&nbsp;<span>(Max Size</span> <div style="margin-left:60px;">1 GB)</div></label></td>
    <td><!--<input name="video" type="file" />--><input id="file_upload4" name="file_upload4" type="file" /></td>
</tr>
<tr class="input">
<!tagForm>
</tr>
<tr>
    <td colspan="2"><h3><!labelTreatmentAvailability></h3></td>
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
          
    <input id="submit_btn" type="submit" name="submitted" value="Save Video" onclick="javascript:$('#file_upload1').uploadifyUpload();javascript:$('#file_upload2').uploadifyUpload();javascript:$('#file_upload3').uploadifyUpload();javascript:$('#file_upload4').uploadifyUpload();javascript:unhook();">
    </td>
</tr>
<input type="hidden" value='0' id="video_field">
<input type="hidden" value='0' id="clik_on_save" name="clik_on_save">
</table>
</form>
<!-- [/detail form] -->

        </div>
    </div>
    
    <div id="footer">
        <!footer>
    </div>
</div>
