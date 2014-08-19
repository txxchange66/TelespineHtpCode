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
    
    <script src="js/jquery-latest.js"></script> 
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
        function validateForm() {
                var x=document.forms["detailform"]["treatment_name"].value
                if (x==null || x=="") {
                        alert("Treatment Name must be filled out");
                        $('#clik_on_save').val(0);
                        hook = true;
                        return false;
                  }
        else {
                if($('#deletetreatment').html() == null && $('#file_upload1Queue').html() == '' && $('#file_upload2Queue').html() == '' && $('#file_upload3Queue').html() == '' && $('#file_upload4Queue').html() == '') {

                        alert("Please upload either 1 picture or Video file");
                        $('#clik_on_save').val(0);
                        hook = true;
                                return false;
                } else {
                      if($('#file_upload1Queue').html() == '' && $('#file_upload2Queue').html() == '' && $('#file_upload3Queue').html() == '' && $('#deletetreatment').html() == '') {
                                alert("Please upload either 1 picture or Video file.");
                                $('#clik_on_save').val(0);
                                hook = true;
                                return false;
                        }
                }
                if(document.getElementById("video_field").value=='1'){
                        GB_showCenter('Video Conversion', '/index.php?action=show_videomsg', 170, 400 );
                        return false;
                }
        }
        GB_showCenter('Edit Video', '/index.php?action=confirmPopupTreatment', 310, 600 );
         return false;
        }



        </script> 
    
     <!-- 
        
         Work : implement Uplodify in edit treatment page.
         Code Desc : placed code for uplodify(user may select files,3 picture(jpg,gif or png) and 1 video ,maximum.this code will uplode file automatically)
         Author : Abhishek Sharma
         Created Date : 09 may 2011
         Organization : Hytech Professionals
         */
         
         
         -->
         
         
    <link href="include/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="include/uploadify/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="include/uploadify/swfobject.js"></script>
<script type="text/javascript" src="include/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
    <script language="javascript" type="text/javascript">
          $(document).ready(function() {
              // put all your jQuery goodness in here.
              // $(".input input,.input object,.input div").css({float:"left"});
                $('#file_upload1').uploadify({
      'uploader'    : 'include/uploadify/uploadify.swf',
      'script'      : 'include/uploadify/uploadify.php',
      'cancelImg'   : 'include/uploadify/cancel.png',
      'folder'      : 'asset/temporary/<!userId>',
      'fileExt'     : '*.jpg;*.gif;*.png;*.JPG;*.GIF;*.PNG',
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
      'fileExt'     : '*.jpg;*.gif;*.png;*.JPG;*.GIF;*.PNG',
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
          'fileExt'     : '*.jpg;*.gif;*.png;*.JPG;*.GIF;*.PNG',                                                   
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
      'fileExt'     : '*.flv;*.m4v;*.mp4;*.avi;*.wmv;*.mov;*.FLV;*.M4V;*.MP4;*.AVI;*.WMV;*.MOV',
      'fileDesc'    : 'Video File',
      'buttonImg'   : 'images/btn-browse.gif',
      'script' : 'include/uploadify/uploadify.php?name=video',                                        
      'auto' : 'true',
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
        /*else errorText = 'An error occured during the upload of file ' + fileObj.name + '. Please try again.';
        $("#uploaderror").html(errorText);*/
        
    },
      onComplete: function(event, queueID, fileObj, reponse, data) {
     
            $("#video_field").val("1");
            $("#submit_btn").removeAttr("disabled");
       }
  
      }); 
              toggle1();//change by sanjay
              //loadToggleDiv('<!id>');
              
              
           
          });


        function toggle(){
            $("#toggle").slideToggle("normal",loadToggleDiv('<!id>'));
            
        }
        function toggle1(){
            $("#toggle").slideToggle("normal",loadToggleDiv1('<!id>'));
            
        }
        function showTag(){
            // Tell jQuery that our div is to be a dialog
            $('#dialogTest').addClass('flora').dialog({
                height: 330,
                width: 450
            });

            $('#dialogTest').html( loadToggleDivPopup('<!id>'));
        }
        
        function remove(id){
            if(confirm('Are you sure you want to delete this tag?')){
                //$('#' + id).remove();
                if( (id != null && id != "") ){
                    $.post('index.php?action=removeTag',{id:id}, function(data,status){
                        if( status == "success" ){
                          loadToggleDiv('<!id>');   
                        }
                        else{
                            alert("Ajax connection failed.");
                        }    
                        }
                    )        
                }
                else{
                    alert("Tag not found.");
                }
                
            }
            
        }
        function loadToggleDiv(id){
            if( (id != null && id != "") ){
                $('#toggle').html("<img src='images/ajax-loader.gif' />");
                $.post('index.php?action=showTag',{id:id}, function(data,status){
                    if( status == "success" ){
                        $('#toggle').html(data);
                    }
                    else{
                        alert("Ajax connection failed.");
                    }    
                }
                )        
            }
            else{
                alert("Treatment Id not found.");
                
            }
        }
         function loadToggleDiv1(id){
            if( (id != null && id != "") ){
                //$('#toggle').html("<img src='images/ajax-loader.gif' />");
                $.post('index.php?action=showTag',{id:id}, function(data,status){
                    if( status == "success" ){
                        $('#toggle').html(data);
                    }
                    else{
                        alert("Ajax connection failed.");
                    }    
                }
                )        
            }
            else{
                alert("Treatment Id not found.");
            }
        }   
        function addTag(id,tag){
            if( (tag != null && tag != "") && ( id != null && id != "")  ){
                    $.post('index.php?action=insertTag',{id:id,tag:tag}, function(data,status){
                    if( status == "success" ){
                        if( /success/.test(data)){
                            
                        }     
                        else if( /failed/.test(data) ){
                            alert('Failed to add tag.');
                        }
                    }
                    else{
                        alert("Ajax connection failed.");
                    }
                    
                    //reloadTag(id);
                    loadToggleDiv(id)
                    //document.getElementById('tag').value = '';    
                  }
                )        
                
            }
            else{
                alert("Please enter tag.");
            }
            
        }
        function reloadTag(id){
             if( (id != null && id != "") ){
                $('#tagList').html("<img src='images/ajax-loader.gif' />");
                $.post('index.php?action=reloadTag',{id:id}, function(data,status){
                    if( status == "success" ){
                        $('#tagList').html(data);
                    }
                    else{
                        alert("Ajax connection failed.");
                    }    
                }
                )        
            }
            else{
                alert("Treatment Id not found.");
            }
        }
    </script>
<div id="container">
	<div id="header">
		<!header>
	</div>
	<div id="sidebar">
		<!sidebar>
	</div>
	<div id="mainContent">
<table style="vertical-align:middle;width:700px;"><tr><td style="width:400px;"><div id="breadcrumbNav"><a href="index.php?action=treatmentManager" >VIDEO</a> / <span ><SPAN CLASS="CURRENT_ACTION"><!treatmentName></SPAN></span> / <!--<span class="highlight">EDIT TREATMENT</span>--><span class="highlight">EDIT VIDEO</span></div><!thumbImage></td><td style="width:300px;">
<table border="0" cellpadding="5" cellspacing="0" style="float:right;">
<tr>
	<td class="iconLabel">
		<a href="index.php?action=createTreatment">
			<img src="<!imageCreateTreatment>" width="127" height="81" alt="Create New Treatment"></a></td>
	
</tr>
</table>
	</td></tr></table>
<!-- [detail form] -->
<!--<h1 class="largeH1">Edit Treatment: <!treatmentHeading> </h1>-->
<h1 class="largeH1">Edit Video: <!treatmentHeading> </h1>
<form name="detailform" id="detailform" enctype="multipart/form-data" method="POST" action="index.php?action=editTreatment&id=<!id>&ts=<!ts>" onSubmit="return validateForm(this);">
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
	<td><input type="text" name="sets" id="sets" size="20" maxlength="20" value="<!sets>"/></td>

</tr>
<tr class="input" style="display:<!displayFieldReps>">
	<td valign="top"><label for="reps" >&nbsp;&nbsp;&nbsp;Reps:&nbsp;</label></td>
	<td><input type="text" name="reps" id="reps" size="20" maxlength="20" value="<!reps>"/></td>
</tr>
<tr class="input" style="display:<!displayFieldHold>">
	<td valign="top"><label for="hold" >&nbsp;&nbsp;&nbsp;Hold:&nbsp;</label></td>
	<td><input type="text" name="hold" id="hold" size="20" maxlength="20" value="<!hold>"/></td>
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
<!tagFormEdit1>
<tr>
	<td colspan="2"><h3><!labelTreatmentMedia></h3></td>
</tr>
<tr class="input">
	<td valign="top"><label for="pic1" >&nbsp;&nbsp;&nbsp;Picture 1:&nbsp;</label></td>
	<td><!--<input name="pic1" type="file" />--><input id="file_upload1" name="file_upload1" type="file" /><!mayBeShowMediaPic1></td>
</tr>
<tr class="input">
	<td valign="top"><label for="pic2" >&nbsp;&nbsp;&nbsp;Picture 2:&nbsp;</label></td>
	<td><!--<input name="pic2" type="file" />--><input id="file_upload2" name="file_upload2" type="file" /><!mayBeShowMediaPic2></td>
</tr>
<tr class="input">
	<td valign="top"><label for="pic3" >&nbsp;&nbsp;&nbsp;Picture 3:&nbsp;</label></td>
	<td><!--<input name="pic3" type="file" />--><input id="file_upload3" name="file_upload3" type="file" /><!mayBeShowMediaPic3></td>
</tr>
<tr class="input">
	<td valign="top"><label for="video" >&nbsp;&nbsp;&nbsp;Video:&nbsp;<span>(Max Size</span> <div style="margin-left:60px;">1 GB)</div></label></td>
	<td><!--<input name="video" type="file" />--><input id="file_upload4" name="file_upload4" type="file" /><!mayBeShowMediaVideo></td>
</tr>
<tr class="input">
<!tagFormEdit>
<tr> 
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
		<td style="width:200px;" align="left">
		
				<br />
		<br />
		<input type="hidden" name="clinic_id" value="<!clinic_id>">
		<input type="hidden" name="submitted" value="Save Treatment">
        	<!--<a  href="index.php?action=confirmPopupTreatment"  title="Edit Video" rel="gb_page_center[600, 310]">
</a> -->
<input id="submit_btn" type="submit" name="submitted" value="Save Video" onclick="javascript:$('#file_upload1').uploadifyUpload();javascript:$('#file_upload2').uploadifyUpload();javascript:$('#file_upload3').uploadifyUpload();javascript:$('#file_upload4').uploadifyUpload();javascript:unhook();">
	</td>
</tr>
<input type="hidden" value='0' id="video_field" name="video_field">
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
<div id="dialogTest" title="Tag List" >
<div id='togglePopup' ></div>
<div id='tagListPopup' ><!reload></div>
</div>
</body>
</html>
