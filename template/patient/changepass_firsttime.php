 <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/style.css" rel="stylesheet">
        <link href="assets/css/retina.min.css" rel="stylesheet">
        <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
                <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
                <script src="assets/js/respond.min.js"></script>
                <script src="assets/css/ie6-8.css"></script>
        <![endif]-->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/img/icon-telespine-144.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/img/icon-telespine-114.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/img/icon-telespine-72.png">
        <link rel="apple-touch-icon-precomposed" sizes="57x57" href="assets/img/icon-telespine-57.png">
        <link rel="shortcut icon" href="assets/img/icon-telespine-16.png">
        <link href="assets/css/style-alt4.css" rel="stylesheet">
        <!--[if !IE]>-->
        <script src="assets/js/jquery-2.0.3.min.js"></script>
        <!--<![endif]-->
        
        <!--[if IE]>
                <script src="assets/js/jquery-1.10.2.min.js"></script>
        <![endif]-->
        
        <!--[if !IE]>-->
        <script>
            window.jQuery || document.write("<script src='assets/js/jquery-2.0.3.min.js'>" + "<" + "/script>");
        </script>
        <!--<![endif]-->
        
        <!--[if IE]>
        <script>
            window.jQuery || document.write("<script src='assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
        </script>
        <![endif]-->

      <script src="assets/js/handlebars-v1.3.0.js"></script>
        <script src="assets/js/jquery.sparkline.min.js"></script>
        <!--[if lte IE 8]><script src="assets/js/excanvas.min.js"></script><![endif]-->
        <script src="assets/js/jquery.autosize.min.js"></script>
        <script src="assets/js/jquery.chosen.min.js"></script>
        <script src="assets/js/respond.min.js"></script>
        <!-- :: Dashboard inline scripts :: -->
        <script src="assets/js/jquery.gritter.min.js"></script>
        <script src="assets/js/wizard.min.js"></script>
        <script src="assets/js/jquery.knob.modified.min.js"></script> 
        <script src="assets/js/jquery.easy-pie-chart.min.js"></script>
        <script src="assets/js/justgage.1.0.1.min.js"></script> 
        <script src="assets/js/timeline-custom-control.js"></script>
        <!--[if lte IE 8]><script src="assets/js/excanvas.min.js"></script><![endif]--> 
        <script src="assets/js/dashboard.js"></script>
		
		 <script src="assets/js/inForm.js"></script>
 
 <script>
 $(document).ready(function () {  
  Dashboard.init({logging:true,components:[]});
  loadForm("changePassword","true");
	
	
});
 </script>

 
 <script>
$(document).ready(function () {  
  Dashboard.init({logging:true,components:[]});
  loadForm("changePassword","true");
});

function loadForm(sectionId, bool){
	location.hash=sectionId;
	$.gritter.removeAll();
	var formId = sectionId+"-form";
	inForm.init(formId,"fieldError",function(){
		/* OR just return true to send to a server without using ajax.
		return true; 
		*/ 
		if(!hasLoaded(formId)){
			$.ajax({
				type: 'GET',
				async: false,
				contentType: "application/text",
				/* This uses the action attribute in the form tag.*/
				url:'index.php?action=changepass_firsttimesubmit',
				/* This collects all the form fields and creates a JSON object to send to the server in a request format/standard way. */
				data:{newPassword:$("#newPassword").val(),confirmNewPassword:$("#confirmNewPassword").val()},
				success: function(data, text){
					//showSection(sectionId,true);
					/* This is a confirmation notice of success.*/
					
				
					var titleText = "received";
					if(text=="success"){titleText = "Successful!";}
					$.gritter.add({
						title: titleText,
						text: $("#"+formId).attr("title") + ' has been submitted. ' ,
						class_name: 'my-sticky-class'
					});
				},
				error: function(xhr, ajaxOptions, thrownError) {
				   Dashboard.console.log(" > > There was an error when saving "+$("#"+formId).attr("title")+": "+xhr.status+"; "+ajaxOptions+"; "+thrownError);
					$.gritter.add({
						title: 'Error!',
						text: "There was an error when saving "+$("#"+formId).attr("title")+"<br>Satus:"+xhr.status+"; <br>Option: "+ajaxOptions+"; <br>Message: "+thrownError,
						class_name: 'my-sticky-class'
					});
				}
			});
		}
		return false;
	});
	//closeAllSections();
	// loadForm("changePassword","true");
	
}
var formsLoaded="";
function hasLoaded(formId){
	//inForm.properties.formId = formId;
	if(formsLoaded.indexOf(formId)==-1){
		formsLoaded += "|"+formId;
		return false
	} 
	return true;
}
function showSection(sectionId, bool){
	sectionId = sectionId.replace("-form","");
	$("#"+sectionId).show();
	var $target = $("#"+sectionId +' div.box-content');
	if(bool){ 
		$("#"+sectionId+" .box-icon i").removeClass('fa fa-chevron-up').addClass('fa fa-check');
	} else {
		$("#"+sectionId+" .box-icon i").removeClass('fa fa-check').addClass('fa fa-circle'); 
	}
	$target.slideToggle('slow', function() {
		//widthFunctions();
	});
	
}
function closeAllSections(){
	var $target = $("div.form-section div.box-content");
	$target.parent(".box-icon i").removeClass('fa fa-chevron-up').addClass('fa fa-chevron-down');
	$target.hide();
}
</script>
<style>
#lgMenu.pos {
  position:absolute;
  top:0;
  margin-top: 0px;
  padding-top: 0px;
}
#lgMenu.fixed {position: fixed;width:380px !important;}
.form-box{padding:0px 2px;}
.form-section{}
.form-box input, .form-box select{
	width:89%;	
	border-radius:4px;
	border:2px solid #ddd;
	background-color:#f9f9f9;
	color:#666 !important;
	display:block;
	float:left;
	height:36px !important;
	box-shadow:none !important;
}
.form-control{
	text-indent:8px;	
	padding:8px;
	color:#666 !important;
}
select{
	-webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
	text-indent: 8px;	
	position:relative;
}
.form-box input:focus{
	border:2px solid #9C8CFF !important;
	background-color:#fafafa;
	color:#000 !important;
}
.form-box select:focus{
	border:2px solid #9C8CFF !important;
	background-color:#fafafa;
	color:#000 !important;
}
.errorMessage {
	position:relative;
	display: none;
	border-radius: 60px;
	background-color: rgba(255,70,73,0.81);
	color: #fff;
	/*border: 2px solid rgba(215,34,37,0.81);*/
	padding: 14px 22px 14px 42px;
	background: rgba(255,0,4,0.81);
	margin: 8px 22px 8px 22px;
	font-size: 15px !important;
	text-shadow: 1px 1px 3px rgba(215,34,37,0.81);
}
.alert-icon{
	position:absolute;
	top:22%;
	left:6px;
	font-size:32px !important;
	color:rgba(245,245,245,1.00);
}
.fieldError {
	color: #fff !important;
	font-weight: bold;
	box-shadow: none !important;
	background-color: rgba(255,70,73,0.81) !important;
	border: 2px solid rgba(215,34,37,0.81) !important;
}

.fieldError::-webkit-input-placeholder {
	color:#fff !important;
	font-weight:300;
}

i.form-indicator {
	margin:2px;
	font-size:8px;
	color:#FF4747;
	float:left;
}
a.section{
	padding:6px 8px 6px 12px;
	display:block;
	border-radius:4px;
	background-color:#fefefe;
	color:#8573F5 !important;
	font-size:15px;
	font-weight:300;
	margin-bottom:4px;
	text-shadow:1px 1px 1px #fff;
}
a.section i.fa{
	width:28px;
	padding-left:8px;	
	text-align:center;
}
a.section:hover{
	color:#5B43F1 !important;
	background-color:#f5f5f5;
	text-decoration:none !important;
	box-shadow: inset 0px 0px 4px #ededed;
}
p{
font-weight:300;
padding:0px 10px 2px 4px;	
}
.theme-container{
	float:left; 
	border-radius:4px;
	border:2px solid #fff;
	box-shadow:0px 0px 8px rgba(0,0,0,0.12);
	margin:12px	
}
.theme-container:hover{
	border:2px solid #999;
	box-shadow:0px 0px 8px rgba(0,0,0,0.3);
}
</style>



<!-- Password -->
				<div class="box form-section"  id="changePassword">
					
					<div class="box-content" >
						<form action="index.php?action=changepass_firsttimesubmit" id="changePassword-form" class="form-horizontal" method="post"
							title="Password">
							<div id="changePassword-form-message" class="errorMessage"> </div>
							<fieldset class="col-sm-12" style="padding:0% 8% 4% 8%;">
								
								<p>Passwords must be at least 8 characters and must contain at least one number [0-9], and at least one chracter [a-z, A-Z].</p>
								<!--<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group form-box">
											<label class="control-label" for="password">Current Password</label>
											<div class="">
												<input class="form-control required" id="password" name="password"
													type="password" value="" placeholder="Existing Password"  
													data-role="not-blank" minlength="8" maxlength="35">
													<i class="fa fa-asterisk form-indicator"></i>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>-->
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group form-box">
											<label class="control-label" for="newPassword">New Password</label>
											<div class="">
												<input class="form-control required" id="newPassword" name="newPassword"
													type="password" value="" placeholder="New Password"  
													data-role="not-blank" minlength="8" maxlength="35">
													<i class="fa fa-asterisk form-indicator"></i>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group form-box">
											<label class="control-label" for="confirmNewPassword">Confirm New Password</label>
											<div class="">
												<input class="form-control required" id="confirmNewPassword" name="confirmNewPassword"  
													data-role="not-blank" minlength="8" maxlength="35"
													type="password" value="" placeholder="Confirm New Password">
													<i class="fa fa-asterisk form-indicator"></i>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
					
							</fieldset>
						</form>
						<div class="clearfix"></div><br>
						<div class="form-actions">
							<button type="submit" class="btn btn-primary" onClick="$('#changePassword-form').submit()">Save</button>
							<button type="reset" class="btn" onClick="showSection('changePassword-form')">Cancel</button>
						</div>
					</div>
				</div>
