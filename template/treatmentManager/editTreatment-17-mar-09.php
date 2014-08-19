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
    <script language="javascript" type="text/javascript">
          $(document).ready(function() {
              // put all your jQuery goodness in here.
              toggle();
          });


        function toggle(){
            $("#toggle").slideToggle("normal",loadToggleDiv('<!id>'));
            
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
<table style="vertical-align:middle;width:700px;"><tr><td style="width:400px;"><div id="breadcrumbNav"><a href="index.php?action=treatmentManager" >TREATMENT</a> / <span ><SPAN CLASS="CURRENT_ACTION"><!treatmentName></SPAN></span> / <span class="highlight">EDIT TREATMENT</span></div><!thumbImage></td><td style="width:300px;">
<table border="0" cellpadding="5" cellspacing="0" style="float:right;">
<tr>
	<td class="iconLabel">
		<a href="index.php?action=createTreatment">
			<img src="images/createNewTreatment.gif" width="127" height="81" alt="Create New Treatment"></a></td>
	
</tr>
</table>
	</td></tr></table>
<!-- [detail form] -->
<h1 class="largeH1">Edit Treatment: <!treatmentHeading> </h1>
<form name="detailform" id="detailform" enctype="multipart/form-data" method="POST" action="index.php?action=editTreatment&id=<!id>&ts=<!ts>" onSubmit="return validate_form(this);">
<div style="padding:10px;color:red"><!error></div>
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
<tr>
	<td colspan="2"><h3>Treatment Details</h3></td>
</tr>
<tr class="input">
	<td><div style="width:160px"><label for="treatment_name" >*&nbsp;Treatment Name:&nbsp;</label></div></td>
	<td width="100%"><input type="text" name="treatment_name" id="treatment_name" size="50" maxlength="50" value="<!treatment_name>"/></td>
</tr>
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
<tr class="input">
	<td valign=""><label for="instruction" >&nbsp;&nbsp;&nbsp;Default Instructions:&nbsp;</label></td>
	<td><textarea name="instruction" id="instruction" rows="4" cols="50" ><!instruction></textarea></td>
</tr>
<tr class="input">
	<td valign=""><label for="benefit" >&nbsp;&nbsp;&nbsp;Benefit of Treatment:&nbsp;</label></td>
	<td><textarea name="benefit" id="benefit" rows="4" cols="50" ><!benefit></textarea></td>
</tr>
<tr class="input">
	<td valign="top"><label for="sets" >&nbsp;&nbsp;&nbsp;Sets:&nbsp;</label></td>
	<td><input type="text" name="sets" id="sets" size="20" maxlength="20" value="<!sets>"/></td>

</tr>
<tr class="input">
	<td valign="top"><label for="reps" >&nbsp;&nbsp;&nbsp;Reps:&nbsp;</label></td>
	<td><input type="text" name="reps" id="reps" size="20" maxlength="20" value="<!reps>"/></td>
</tr>
<tr class="input">
	<td valign="top"><label for="hold" >&nbsp;&nbsp;&nbsp;Hold:&nbsp;</label></td>
	<td><input type="text" name="hold" id="hold" size="20" maxlength="20" value="<!hold>"/></td>
</tr>
<tr class="input">
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
<!tagFormEdit>
<tr>
	<td colspan="2"><h3>Treatment Media</h3></td>
</tr>
<tr class="input">
	<td><label for="pic1" >&nbsp;&nbsp;&nbsp;Picture 1:&nbsp;</label></td>
	<td><input name="pic1" type="file" /><!mayBeShowMediaPic1></td>
</tr>
<tr class="input">
	<td><label for="pic2" >&nbsp;&nbsp;&nbsp;Picture 2:&nbsp;</label></td>
	<td><input name="pic2" type="file" /><!mayBeShowMediaPic2></td>
</tr>
<tr class="input">
	<td><label for="pic3" >&nbsp;&nbsp;&nbsp;Picture 3:&nbsp;</label></td>
	<td><input name="pic3" type="file" /><!mayBeShowMediaPic3></td>
</tr>
<tr class="input">
	<td><label for="video" >&nbsp;&nbsp;&nbsp;Video:&nbsp;</label></td>
	<td><input name="video" type="file" /><!mayBeShowMediaVideo></td>
</tr>
<tr>
	<td colspan="2"><h3>Treatment Availability</h3></td>
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
        	<a  href="index.php?action=confirmPopupTreatment"  title="Tx Xchange: Edit Treatment" rel="gb_page_center[600, 310]"><input type="button" name="submitted" value="Save Treatment"></a> 
	</td>
</tr>

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
