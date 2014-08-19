<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en" ng-app>
    <head>
        <meta charset="utf-8">
        <title>TeleSpine | Account Manangement</title>

        <!-- meta and head files -->
        <!meta_head>
        <!-- meta and head files end -->

    </head>

    <body>
        <div class="page">

            <!-- start: Header -->
            <!header>
            <!-- end: Header -->

            <div class="container" style="min-height:800px">

                <div class="row">

                    <!-- start: Content -->

                    <div id="content" class="col-lg-10 col-sm-11 full" style="height:1100px">

                        <div class="intro">

                            <h2><span style="font-size:x-large">Account Information</span>

                                <div style="float:right;color:#777; font-weight:300" class="hidden-sm hidden-xs"><!currentDate></div>

                            </h2>

                        </div><!--/intro header-->



                        <!-- Column 1 -->

                        <div class="row">
<!--				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="box hidden-xs hidden-sm" id="lgMenu">
						<div class="box-header">
							<h2><i class="fa fa-edit"></i>Settings</h2>
							<div class="box-icon"> <a href="#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a> </div>
						</div>
						<div class="box-content">
							<div>
								<p>General information... Duis convallis tellus eget felis semper placerat. 
									
									Class aptent taciti. </p>
								<a href="javaScript:loadForm('general',true);" class="section"><i class="fa fa-user"></i> &nbsp; General Account Info</a> <a href="javaScript:loadForm('changeEmail',true);" class="section"><i class="fa fa-envelope"></i> &nbsp; Change Email Address</a> <a href="javaScript:loadForm('changeMyPassword',true);" class="section"><i class="fa fa-lock"></i> &nbsp; Change Password</a> <a href="javaScript:loadForm('changeProfileImage',true);" class="section"><i class="fa fa-cog"></i> &nbsp; Application Preferences</a> 
								
								<a href="javaScript:loadForm('billing',true);" class="section"><i class="fa fa-money"></i> &nbsp; Manage Billing Info</a> 
								
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="box form-section hidden-lg hidden-md" id="xsMenu">
						<div class="box-header">
							<h2><i class="fa fa-edit"></i>Settings</h2>
							<div class="box-icon"> <a href="#" class="btn-minimize"><i class="fa fa-check"></i></a> </div>
						</div>
						<div class="form-box box-content" style="display:none;">
							<div>
								<p>General information... Duis convallis tellus eget felis semper placerat. 
									
									Class aptent taciti. </p>
								<a href="javaScript:loadForm('general',true);" class="section"><i class="fa fa-user"></i> &nbsp; General Account Info</a> <a href="javaScript:loadForm('changeEmail',true);" class="section"><i class="fa fa-envelope"></i> &nbsp; Change Email Address</a> <a href="javaScript:loadForm('changeMyPassword',true);" class="section"><i class="fa fa-lock"></i> &nbsp; Change Password</a> <a href="javaScript:loadForm('changeProfileImage',true);" class="section"><i class="fa fa-cog"></i> &nbsp; Application Preferences</a> <a href="javaScript:loadForm('billing',true);" class="section"><i class="fa fa-money"></i> &nbsp; Manage Billing Info</a> </div>
							<div style="position:relative;"> 
								
								<select id="menuSelect" style="width:98%;" onChange="loadForm($('#menuSelect').val());">

									<option>Go to...</option>

									<option value="general">General Account Info</option>

									<option value="changeEmail">Change Email Address</option>

									<option value="changePassword">Change Password</option>

									<option value="changeProfileImage">Application Preferences</option>

									<option value="billing">Manage Billing Info</option>

								</select>

           					<div class="fa fa-check" 

									style="font-size:20px; height:24px; width:24px;position:absolute;right:6% !important;top:6px;color:#ddd;" 

									onClick="$('#menuSelect').focus()"></div> 
								
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>-->
				<!--/col--> 
				
				<!-- Column 2 -->
				
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					
					<!-- Profile -->
					
					<div class="box form-section" style="display:block" id="general">
						<div class="box-header" onClick="loadForm('general',true);">
							<h2><i class="fa fa-user"></i>Update Profile</h2>
							<div class="box-icon"> <a href="#" class=""><i class="fa fa-check"></i></a> </div>
						</div>
						<div class="box-content" style="display:none">
							<form action="index.php?action=profileaccount" class="form-horizontal" id="general-form" method="post"

							title="Profile Information">
								<div id="general-form-message" class="errorMessage"> </div>
								<fieldset class="col-sm-12" style="padding:0% 8% 4% 8%;">
									<div class="row">
										<!--<h4>Profile Info</h4>-->
										<div class="avatar" style="float:left;padding-right:12px;border-radius:4px;"><img src="<!profile_picture>" alt="Avatar" height="40"></div>
										<p style="min-height:46px"> </p>
										<div class="col-lg-5 col-md-12 col-sm-12">
											<div class="form-group form-box">
												<div class="">
													<input class="form-control required" id="firstName" name="firstName"

													type="text" value="<!name_first>" placeholder="First Name"  

													data-role="not-blank" minlength="1" maxlength="35">
													<i class="fa fa-asterisk form-indicator"></i> </div>
											</div>
										</div>
										<div class="col-lg-6 col-md-12 col-sm-12">
											<div class="form-group form-box">
												<div class="">
													<input class="form-control required" id="lastName" name="lastName"

													type="text" value="<!name_last>" placeholder="Last Name"  

													data-role="not-blank" minlength="2" maxlength="45">
													<i class="fa fa-asterisk form-indicator"></i> </div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
								</fieldset>
							</form>
							<div class="clearfix"></div>
							<br>
							<div class="form-actions">
								<button type="button" class="btn btn-primary" onClick="$('#general-form').submit()">Save</button>
								<button type="reset" class="btn" onClick="showSection('general-form')">Cancel</button>
							</div>
						</div>
					</div>
					
					<!-- Email -->
					
					<div class="box form-section"  id="changeEmail">
						<div class="box-header" onClick="loadForm('changeEmail',true);">
							<h2><i class="fa fa-envelope"></i>Change Email Address</h2>
							<div class="box-icon"> <a href="#" class=""><i class="fa fa-check"></i></a> </div>
						</div>
						<div class="box-content" style="display:none;">
							<form action="index.php?action=profilechangeemail" class="form-horizontal" id="changeEmail-form" method="post"

							title="Email Address">
								<div id="changeEmail-form-message" class="errorMessage"> </div>
								<fieldset class="col-sm-12" style="padding:0% 8% 4% 8%;">
									<h4>Email</h4>
									<p>You will receive an email when you update your email address. When you confirm your email 
										
										address by clicking "Confirm" in your email it will be set. Until then your current email 
										
										address will remain, and still used for logging in.</p>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group form-box"> 
												
												<!--<label class="control-label" for="email">Email</label>-->
												
												<div class="">
													<input class="form-control required" id="email" name="email"

													data-role="email" minlength="5" maxlength="135"

													type="email" value="" placeholder="Email: name@domain.com">
													<i class="fa fa-asterisk form-indicator"></i> </div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group form-box"> 
												
												<!--<label class="control-label" for="email">Email</label>-->
												
												<div class="">
													<input class="form-control required" id="emailConfirm" name="emailConfirm"

													type="email" value="" placeholder="Confirm Email" 

													data-role="matchField:email" minlength="5" maxlength="135">
													<i class="fa fa-asterisk form-indicator"></i> </div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
								</fieldset>
							</form>
							<div class="clearfix"></div>
							<br>
							<div class="form-actions">
								<button type="button" class="btn btn-primary" onClick="$('#changeEmail-form').submit();">Save</button>
								<button type="reset" class="btn" onClick="showSection('changeEmail-form')">Cancel</button>
							</div>
						</div>
					</div>
					
					<!-- Password -->
					
					<div class="box form-section"  id="changeMyPassword">
						<div class="box-header" onClick="loadForm('changeMyPassword',true);">
							<h2><i class="fa fa-lock"></i>Change Password</h2>
							<div class="box-icon"> <a href="#" class=""><i class="fa fa-check"></i></a> </div>
						</div>
						<div class="box-content" style="display:none">
							<form action="index.php?action=profilechangepass" id="changeMyPassword-form" class="form-horizontal" method="post"

							title="Password">
								<div id="changeMyPassword-form-message" class="errorMessage"> </div>
								<fieldset class="col-sm-12" style="padding:0% 8% 4% 8%;">
									<h4>Change Password</h4>
									<p>Passwords must be at least 8 characters long and can be a combination of letters (a-z, A-Z) and numbers (0-9).</p>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group form-box">
												<label class="control-label" for="password">Current Password</label>
												<div class="">
													<input class="form-control required" id="password" name="password"

													type="password" value="" placeholder="Existing Password"  

													data-role="alpha-numaric-only" minlength="5" maxlength="35">
													<i class="fa fa-asterisk form-indicator"></i> </div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group form-box">
												<label class="control-label" for="newPassword">New Password</label>
												<div class="">
													<input class="form-control required" id="newPassword" name="newPassword"

													type="password" value="" placeholder="New Password"  

													data-role="alpha-numaric-only" minlength="8" maxlength="35">
													<i class="fa fa-asterisk form-indicator"></i> </div>
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

													data-role="matchField:newPassword" minlength="8" maxlength="35"

													type="password" value="" placeholder="Confirm New Password">
													<i class="fa fa-asterisk form-indicator"></i> </div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
								</fieldset>
							</form>
							<div class="clearfix"></div>
							<br>
							<div class="form-actions">
								<button type="button" class="btn btn-primary" onClick="$('#changeMyPassword-form').submit()">Save</button>
								<button type="reset" class="btn" onClick="showSection('changeMyPassword-form')">Cancel</button>
							</div>
						</div>
					</div>
					
					<!-- Preferences -->
					
					<div class="box form-section"  id="changeProfileImage">
						<div class="box-header" onClick="loadForm('changeProfileImage',true);">
							<h2><i class="fa fa-cog"></i>Application Preferences</h2>
							<div class="box-icon"> <a href="#" class=""><i class="fa fa-check"></i></a> </div>
						</div>
						<div class="box-content" style="display:none">
							<div class="alert alert-warning">
								<button type="button" class="close" data-dismiss="alert">×</button>
								Drag & drop file upload with image preview </div>
							<h4>Change Theme Color: <span id="newThemeColor">(default)</span></h4>
							<div class="clearfix"></div>
							<div class="form-group">
								<div class="">
									<div class="theme-container" style="background-color:rgba(246,246,246,1.00);" onClick="$('#theme').val('default');$('#newThemeColor').html('(default)');"> <img src="assets/img/theme-default.png"> </div>
									<div class="theme-container" style="background-color:rgba(6,222,0,1.00);" onClick="$('#theme').val('blue');$('#newThemeColor').html('(blue)');"> <img src="assets/img/theme-blue.png"> </div>
									<div class="theme-container" style="background-color:rgba(110,97,255,1.00);" onClick="$('#theme').val('green');$('#newThemeColor').html('(green)');"> <img src="assets/img/theme-green.png"> </div>
								</div>
							</div>
							<div class="clearfix"></div>
							<h4>Change Profile Image</h4>
							<form action="index.php?action=profileapplicationpreferences" class="form-horizontal dropzone" id="changeProfileImage-form" method="post"

							title="Account Preferences" enctype="multipart/form-data" >
								<div id="changeProfileImage-form-message" class="errorMessage"> </div>
								<div id="changeProfileImage-form-message1" class="alert-success"> </div>
								
								
								<fieldset class="col-sm-12" style="padding:0% 8% 4% 8%;">
									<input id="theme" name="theme" type="hidden" value="blue"/>
									<div class="clearfix"></div>
									<div class="form-group" style="min-height:15px">
										<div class="">
											<div id="dropzone">
												<div class="fallback">
													<label class="control-label">File Upload</label>
													<input name="file" type="file" multiple placeholder="Select File"   

												data-role="not-blank" minlength="5" maxlength="255"/>
												</div>
											</div>
										</div>
									</div>
								</fieldset>
							</form>
							<div class="clearfix"></div>
							<br>
							<div class="form-actions">
								<button type="button" class="btn btn-primary" onClick="$('#changeProfileImage-form').submit()">Save</button>
								<button type="reset" class="btn" onClick="showSection('changeProfileImage-form')">Cancel</button>
							</div>
						</div>
					</div>
					
					<!-- Billing --> 
					
					<!--

				<div class="box form-section"  id="billing">

					<div class="box-header">

						<h2><i class="fa fa-money"></i>Change Billing Information</h2>

						<div class="box-icon"> <a href="#" class=""><i class="fa fa-check"></i></a> </div>

					</div>

					<div class="box-content" style="display:none">

						<form action="components/account.ajax.response.html" class="form-horizontal" id="billing-form" method="post"

							title="Billing Information">

						<div id="billing-form-message" class="errorMessage"> </div>

							<fieldset class="col-sm-12" style="padding:0% 8% 4% 8%;">

								<div class="row">

									<h4>Manage Billing Information</h4>

									<p>Here you can update your billing address and/or credit card information. Once your 

									credit card is validated your card will be updated in our system.</p>

									<h5>Billing Address</h5>

									<div class="col-lg-12 col-md-12 col-sm-12">

										<div class="form-group form-box">

											<div class="">

												<input class="form-control required" id="address1" name="address1"

													type="text" value="" placeholder="Address (1)"  

													data-role="not-blank" minlength="5" maxlength="135">

													<i class="fa fa-asterisk form-indicator"></i>

											</div>

										</div>

									</div>

								</div>

								<div class="row">

									<div class="col-lg-12 col-md-12 col-sm-12">

										<div class="form-group form-box">

											<div class="">

												<input class="form-control required" id="address2" name="address2"

													type="text" value="" placeholder="Address (2)"  

													data-role="!not-blank" minlength="5" maxlength="135">

											</div>

										</div>

									</div>

								</div>

								<div class="clearfix"></div>

								<div class="row">

									<div class="col-lg-5 col-md-5 col-sm-5">

										<div class="form-group form-box">

											<div class="">

												<input class="form-control required" id="city" name="city"

													type="text" value="" placeholder="City"  

													data-role="not-blank" minlength="2" maxlength="35">

													<i class="fa fa-asterisk form-indicator"></i>

											</div>

										</div>

									</div>

									<div class="col-lg-3 col-md-3 col-sm-3">

										<div class="form-group form-box">

											<div class="">

												<input class="form-control required" id="state" name="state"

													type="text" value="" placeholder="State"  

													data-role="not-blank" minlength="2" maxlength="35">

													<i class="fa fa-asterisk form-indicator"></i>

											</div>

										</div>

									</div>

									<div class="col-lg-3 col-md-3 col-sm-3">

										<div class="form-group form-box">

											<div class="">

												<input class="form-control required" id="zip" name="zip"

													type="text" value="" placeholder="Zip"  

													data-role="numbers-only" minlength="4" maxlength="11">

													<i class="fa fa-asterisk form-indicator"></i>

											</div>

										</div>

									</div>

									<div class="clearfix"></div>

								</div>

								<div class="row">

									<h5>Billing Credit Card (<span id="cardType">Visa 5324</span>)</h5>

									<div class="col-lg-12 col-md-12 col-sm-12">

										<div class="form-group form-box">

											<div class="">

												<input class="form-control required" id="cardNumber" name="cardNumber" 

													max-length="24" type="text" value="************5324" 

													placeholder="4123412341234123 (no '-')"  

													data-role="not-blank" minlength="1" maxlength="24">

											</div>

										</div>

									</div>

								</div>

								<div class="row">

									<div class="col-lg-5 col-md-5 col-sm-5">

										<div class="form-group form-box">

											<div class="">

												<select id="expMonth" name="expMonth" class="required" 

													data-role="selected">

													<option>Exp. Month</option>

													<option value="01">Jan 01</option>

													<option value="02">Feb 02</option>

													<option value="03">Mar 03</option>

													<option value="04">Apr 04</option>

													<option value="05">May 05</option>

													<option value="06">Jun 06</option>

													<option value="07">Jul 07</option>

													<option value="08">Aug 08</option>

													<option value="09">Sep 09</option>

													<option value="10">Oct 10</option>

													<option value="11">Dov 11</option>

													<option value="12">Dec 12</option>

												</select>

												<i class="fa fa-asterisk form-indicator"></i>

											</div>

										</div>

									</div>

									<div class="col-lg-3 col-md-3 col-sm-3">

										<div class="form-group form-box">

											<div class="">

												<select id="expYear" name="expYear" class="required" 

													data-role="selected" >

													<option>Exp. Year</option>

													<option value="2014">2014</option>

													<option value="2015">2015</option>

													<option value="2016">2016</option>

													<option value="2017">2017</option>

													<option value="2018">2018</option>

													<option value="2019">2019</option>

													<option value="2020">2020</option>

													<option value="2021">2021</option>

													<option value="2022">2022</option>

												</select>

												<i class="fa fa-asterisk form-indicator"></i>

											</div>

										</div>

									</div>

									<div class="col-lg-3 col-md-3 col-sm-3">

										<div class="form-group form-box">

											<div class="">

												<input class="form-control required" id="cvv2" name="cvv2"

													type="number" value="" max-length="4" 

													placeholder="CVV2 Number on Back"  

													data-role="not-blank" minlength="3" maxlength="4">

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

							<button type="submit" class="btn btn-primary" onClick="$('#billing-form').submit()">Save</button>

							<button type="reset" class="btn" onClick="showSection('billing-form')">Cancel</button>

						</div>

					</div>

					

					--> 
					
				</div>
				<div class="clearfix"></div>
			</div><!--/col--> 

                    </div><!--/content--> 

                </div> <!--/row --> 

                <!-- start: footer -->
                <!footer>
                <!-- end: footer -->

            </div> <!--/container--> 
        </div> <!-- /page -->

        <!-- inline scripts related to this page -->
		<link href="assets/css/dropzone.css" rel="stylesheet">
        <script src="assets/js/app.controllers.js"></script>
        <script src="assets/js/dropzone.js"></script>
        <script src="assets/js/inForm.js"></script>
        <script src="assets/js/jquery.gritter.min.js"></script>
        <script src="assets/js/core.min.js"></script>
        <script>

        $(document).ready(function () {  

        App.init();
          
        //App.init({logging:true,components:[]});

          if(location.hash!=""){

                  loadForm(location.hash.replace("#",""),true);

          } else {

                  loadForm("general");

          }

          location.hash="";

                /*------ Dropzone Init ------*/

                $(".dropzone").dropzone();
				
				
				
				 //$(".dropzone").dropzone.on("success", function(files, responseText, e) { alert(responseText); });

        });



        function loadForm(sectionId, bool){

                //location.hash=sectionId;

                //$('body').scrollTo('#'+sectionId,{offsetTop : '50'});
				
				var aa=1;

                $.gritter.removeAll();

                var formId = sectionId+"-form";
				var messgeId = formId+"-message";
				
				console.log(formId);

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

                                        url:inForm.properties.action,

                                        /* This collects all the form fields and creates a JSON object to send to the server in a request format/standard way. */

                                        data:{
										data:inForm.getFormJSON()
										},

                                        success: function(data, text){

                                                showSection(sectionId,false);

                                                /* This is a confirmation notice of success.*/
												var obj = jQuery.parseJSON(data);
												$("#"+messgeId).hide();
												
                                                var titleText = "received";

                                                if(text=="success"){titleText = "Successful!";}
												
												if(obj.status==1){
												
												
												aa=aa+1;
												if(aa%2==0){
                                            if($("#"+formId).attr("title")=='Password'){
                                               $.gritter.add({

                                                        title: titleText,

                                                        text: 'Please check your email to confirm your new password.' ,

                                                        class_name: 'my-sticky-class'

                                                });  
                                            }else{    
                                            $.gritter.add({

                                                        title: titleText,

                                                        text: $("#"+formId).attr("title") + ' has been submitted. ' ,

                                                        class_name: 'my-sticky-class'

                                                });
                                            }						}
												
												
												}else{
												//alert(obj.message);
												App.console.log(" > > There was an error when saving "+$("#"+formId).attr("title")+": "+obj.message);
												
												
												
												
												inForm.showFormError(obj.message);
												
												//$("#"+messgeId).show();
												//$("#"+messgeId).html(obj.message);
												
												/*
                                                $.gritter.add({

                                                        title: 'Error!',

                                                        text: obj.message,

                                                        class_name: 'my-sticky-class'

                                                });
												
												*/
												
												 
												
												}

                                        },

                                        error: function(xhr, ajaxOptions, thrownError) {

                                           App.console.log(" > > There was an error when saving "+$("#"+formId).attr("title")+": "+xhr.status+"; "+ajaxOptions+"; "+thrownError);

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

               closeAllSections();

                showSection(sectionId,true);



        }

        var formsLoaded="";

        function hasLoaded(formId){

                //inForm.properties.formId = formId;

                if(formsLoaded.indexOf(formId)==-1){

                        formsLoaded += "|"+formId;

            return false;

                } 

        return false;

        }

        function showSection(sectionId, bool){

                sectionId = sectionId.replace("-form","");

                $("#"+sectionId).show();

                var $target = $("#"+sectionId +' div.box-content');

                if(bool){ 

                        $("#"+sectionId+" .box-icon i").removeClass('fa fa-chevron-up').addClass('fa fa-check');

                } /*else {

                        $("#"+sectionId+" .box-icon i").removeClass('fa fa-check').addClass('fa fa-circle'); 

                }*/

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

    </body>

</html>