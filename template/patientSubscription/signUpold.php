<!DOCTYPE html>
<html lang="en" ng-app>
<head>
<meta charset="utf-8">
<title>TeleSpine | Sign Up</title>
<!-- meta and head files -->
        <!meta_head>
        <!-- meta and head files end -->
<link href="assets/css/theme-default.css" rel="stylesheet">

<style>
h4{
	font-weight:bold;
}
</style>
</head>

<body>
<div class="page"> 
	<!-- start: Header -->
            <!header>
            <!-- end: Header -->
<div class="container">
	<div class="row"> 
	
	<!-- start: Content -->
	
	<div id="content" class="col-lg-10 col-sm-11 full">
		<div class="intro">
			<h2><span style="font-size:x-large">Sign Up</span>
				<div style="float:right;color:#777; font-weight:300" class="hidden-sm hidden-xs"><?php echo date('l, F d, Y');?></div>
			</h2>
		</div><!--/intro header--> 
		
		<!-- Column 1 -->
		<!--<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="box" id="lgMenu">
					<div class="box-header">
						<h2><i class="fa fa-exclamation-circle"></i>About the Program</h2>
						<div class="box-icon"> <a href="#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a> </div>
					</div>
					<div class="box-content">
						<div>
							<p>The TeleSpine program is a web-based telehealth service putting a major dent in healthcare costs for people with LBP. It is a 8 week online coaching program, evidence-based and patient centric, and staffed by certified providers who are experts in LBP and telehealth.</p><p>The TeleSpine healthcare service was developed to remove barriers to high quality care and achieve equal or better outcomes compared to traditional, office-based LBP care at a significantly reduced cost and risk.</p>
							<img src="assets/img/do-not.gif" width="98%">
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>--><!--/col--> 
			
			<!-- Column 2 -->
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-16"> 
				<!-- Sign Up/ Billing -->
				<div class="box form-section"  id="billing">
					<div class="box-header">
						<h2>Sign Up</h2>
						<div class="box-icon">  </div>
					</div>
					<div class="box-content">
						<form action="index.php?action=submitpatientsignup" class="form-horizontal" id="billing-form" method="post"

							title="Billing Information">
                                                    <div id="billing-form-message" class="errorMessage" <!style>><!atag><!errorMessage></i></div>
							<fieldset class="col-sm-12" style="padding:0% 8% 4% 8%;">
								<div class="row">
									<h4>Profile Info</h4>
									<div class="col-lg-5 col-md-12 col-sm-12">
										<div class="form-group form-box">
											<div class="">
												<input class="form-control required" id="firstName" name="firstName"

													type="text" value="<!retainedFname>" placeholder="First Name"  

													data-role="not-blank" minlength="1" maxlength="35" autocomplete="off">
												<i class="fa fa-asterisk form-indicator"></i> </div>
										</div>
									</div>
									<div class="col-lg-6 col-md-12 col-sm-12">
										<div class="form-group form-box">
											<div class="">
												<input class="form-control required" id="lastName" name="lastName"

													type="text" value="<!retainedlname>" placeholder="Last Name"  

													data-role="not-blank" minlength="1" maxlength="45" autocomplete="off">
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
												<input class="form-control required" id="email" name="email"

													data-role="email" minlength="5" maxlength="135"

                                                                                                        type="email" value="<!retainedEmail>" placeholder="Email: name@domain.com" autocomplete="off">
												<i class="fa fa-asterisk form-indicator"></i> </div>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
							</fieldset>
							<fieldset class="col-sm-12" style="padding:0% 8% 4% 8%;">
							<div class="row">
							<h4>Password</h4>
								<p>Passwords must be at least 6 characters long and can be a combination of letters (a-z, A-Z) and numbers (0-9).</p>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group form-box">
											<label class="control-label" for="newPassword">Password</label>
											<div class="">
												<input class="form-control required" id="password" name="password"
													type="password" value="" placeholder="Password"  
                                                                                                        data-role="alpha-numaric-only" minlength="6" maxlength="35" autocomplete="OFF">
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

													data-role="matchField:password" minlength="6" maxlength="35"

                                                                                                        type="password" value="" placeholder="Confirm New Password" autocomplete="OFF">
												<i class="fa fa-asterisk form-indicator"></i> </div>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
							</fieldset>
                                                    <fieldset style="padding:0% 8% 4% 8%;" class="col-sm-12">
								<div class="row"><h4>Payment Information</h4>
									<p> Please enter your sign-up code or credit card information below. Once your payment method has been processed, you will be automatically logged into your Telespine portal.</p>
									<div class="col-lg-1 col-md-1 col-sm-1" style="padding-top:7px;">
										<input type="radio" checked="" style="margin-top:8px;" onclick="if(this.checked){
													$('#billingCCDiv').slideUp(800);
													$('#billingPromoDiv').slideDown(800);
												}else{
													$('#billingCCDiv').slideDown(800);
													$('#billingPromoDiv').slideUp(800);
												}" value="2" name="usePromoCode" id="usePromoCodeTrue" >
									</div>
									<div class="col-lg-11 col-md-11 col-sm-11">
										<h5>Using a sign-up code?</h5>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div style="display:block;" id="billingPromoDiv" class="form-group form-box">
												<div class="">
													<input type="text" maxlength="15" minlength="11" data-role="isBillingChecked|not-blank" placeholder="Sign-up Code" value="<!promocode>"  name="promocode" id="promocode" class="form-control required <!errorclass>">
													<i class="fa fa-asterisk form-indicator"></i> </div>
											</div>
										</div>
									</div>
									<div class="col-lg-1 col-md-1 col-sm-1" style="padding-top:7px;">
										<input type="radio" style="margin-top:8px;" onclick="if(this.checked){
                                                $('#billingCCDiv').slideDown(800);
                                                $('#billingPromoDiv').slideUp(800);
											} else {
                                                $('#billingCCDiv').slideUp(800);
                                                $('#billingPromoDiv').slideDown(800);
											}" value="1" name="usePromoCode" id="usePromoCodeFalse" <!chk> >
									</div>
									<div class="col-lg-11 col-md-11 col-sm-11">
										<h5>Using a credit card?</h5>
										<div id="billingCCDiv" style="padding-left:24px;display:none;">
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12">
												<div class="form-group form-box">
													<div class="">
														<input type="text" maxlength="24" minlength="16" data-role="isPromoChecked|numbers-only" placeholder="4123412341234123 (no '-')" value="<!retainedcardNumber>"  max-length="24" name="cardNumber" id="cardNumber" class="form-control required">
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-5 col-md-5 col-sm-5">
												<div class="form-group form-box">
													<div class="">
														<select data-role="isPromoChecked|selected" class="required" name="expMonth" id="expMonth">
															<!monthsOptions>
														</select>
														<i class="fa fa-asterisk form-indicator"></i> </div>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-3">
												<div class="form-group form-box">
													<div class="">
														<select data-role="isPromoChecked|selected" class="required" name="expYear" id="expYear">
															<!yearOptions>
														</select>
														<i class="fa fa-asterisk form-indicator"></i> </div>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-3">
												<div class="form-group form-box">
													<div class="">
														<input type="password" maxlength="4" minlength="2" data-role="isPromoChecked|numbers-only" title="CVV2 number is on the back of your card" placeholder="CVV2" max-length="4" value="<!retainedcvvNumber>" name="cvv2" id="cvv2" class="form-control required" autocomplete="OFF">
														<i class="fa fa-asterisk form-indicator"></i> </div>
												</div>
											</div>
                                                                                    </div>
											<div class="clearfix"></div>
                                                                                        
                                                                                    
                                                                                       
                                                                                        <div class="row"> <h5>Billing Address <span id="cardType"></span></h5>
                                                                                            <div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group form-box">
											<div class="">
												<input class="form-control required" id="address1" name="address1"

													type="text" value="<!retainedadd1>" placeholder="Address (1)"  

													data-role="isPromoChecked|not-blank" minlength="5" maxlength="135">
												<i class="fa fa-asterisk form-indicator"></i> </div>
										</div>
									</div>
                                                              
								
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group form-box">
											<div class="">
												<input class="form-control required" id="address2" name="address2"

                                                                                                       type="text" value="<!retainedadd2>" placeholder="Address (2)"  

													data-role="!not-blank" minlength="5" maxlength="135">
											</div>
										</div>
									</div>
								
											<div class="col-lg-5 col-md-5 col-sm-5">
												<div class="form-group form-box">
													<div class="">
														<input type="text" maxlength="35" minlength="2" data-role="isPromoChecked|not-blank" placeholder="City"  value="<!retainedcity>" name="city" id="city" class="form-control required">
														<i class="fa fa-asterisk form-indicator"></i> </div>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-3">
												<div class="form-group form-box">
													<div class="">
														<!--<input type="text" maxlength="35" minlength="1" data-role="isPromoChecked|not-blank" placeholder="State" value="" name="state" id="state" class="form-control required">-->
                                                                                                                 <select id="state" name="state" class="required" data-role="isPromoChecked|selected"> <!stateOptions>	</select>
														<i class="fa fa-asterisk form-indicator"></i> </div>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-3">
												<div class="form-group form-box">
													<div class="">
														<input type="text" maxlength="11" minlength="4" data-role="isPromoChecked|numbers-only" placeholder="Zip" value="<!retainedzip>" name="zip" id="zip" class="form-control required">
														<i class="fa fa-asterisk form-indicator"></i> </div>
												</div>
											</div>
											
										</div>
										<div class="clearfix"></div>
								<div class="row"> <h5>Discount Coupon<span id="discount"></span></h5>
                                                                          <div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group form-box">
											<div class="">
												<input class="form-control required" id="discount_coupon" name="discount_coupon"

													type="text" value="<!retaineddiscount_coupon>" placeholder="Discount Coupon"  

													data-role="!not-blank" minlength="11" maxlength="15">
											</div>
										</div>
									</div>
                                                                  </div>
										</div>
									</div>
								</div>
							</fieldset>
							<div class="clearfix"></div>
							<input Type="hidden" value="<!clinicid>" name="clinic_id" id="clinic_id"/>
                                                        <input Type="hidden" value="<!cardPayment>" name="cardPayment" id="cardPayment"/>
                                                        <input Type="hidden" value="<!HealthProgramID>" name="HealthProgramID" id="HealthProgramID"/>
                                                        <input Type="hidden" value="<!ehsTimePeriod>" name="ehsTimePeriod" id="ehsTimePeriod"/>
                                                        
                                                       
						</form>
						<div class="clearfix"></div>
						<br>
						<div id="billing-form-message1" class="errorMessage" style="display:none;"></div>
						
						
						<div class="col-sm-12 form-actions" style="padding:0% 17% 4% 8%;">
							<input type="checkbox"  name="toi_chk" id="toi_chk" value="1" class="large" onclick='jQuery("#billing-form-message1").hide();' <!toichked>/>
                                                    <a href="#" style="padding-left:12px; "onClick="$('#TOS').modal('show');">Terms of Service</a> - Please confirm you have read the Terms of Service by checking the box. 
                                                    <button type="submit" class="btn btn-primary pull-right" onClick="chk_toi();">Submit</button>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<!--/col--> 
			
		</div>
		<!--/row--> 
		
	</div>
	<!--/container--> 
	<div class="clearfix"></div>
	<!-- start: footer -->
                    <!footer>
                    <!-- end: footer --> 
	
</div>
<!--/page--> 
	<div class="clearfix"></div>



<!-- ********************* Answer Yes ********************* -->
<div class="modal fade" id="QuestionYes">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title" id="QuestionYesTitle">Is Telespine Right For You?</h4>
</div>
<div class="modal-body">
We're sorry, but it appears as if Telespine may not be appropriate or safe for you. Please consider contacting your doctor.
<div class="clearfix"></div>
<hr>
<div class="actions" style="">
<button type="button" class="btn btn-warning btn-lg" data-last="Finish"
style="float:right;" onClick="window.location.href = 'http://telespine.com/'">Ok</button>
</div>
<div class="clearfix"></div>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- ********************* Answer No ********************* -->
<div class="modal fade" id="QuestionNo">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title" id="QuestionNoTitle">Is Telespine Right For You?</h4>
</div>
<div class="modal-body">
We're glad to say that Telespine is right for you! Please continue on with the sign-up process and start getting relief today.
<div class="clearfix"></div>
<hr>
<div class="actions" style="">
<button type="button" class="btn btn-success btn-lg" data-last="Finish"
style="float:right;" onClick="$('#QuestionNo').modal('hide');">Sign-up to Start Feeling Better</button>
</div>
<div class="clearfix"></div>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- ********************* Questionnair ********************* -->
<div class="modal fade" id="Questionnair">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title" id="QuestionnairTitle">Is Telespine Right For You?</h4>
</div>
<div class="modal-body QuestionnairContent">
<div style="min-height:200px">
<p id="QuestionnairSubtitle">Thank you for your interest in signing-up for Telespine. Please take one minute to answer the following questions so we can make sure Telespine is appropriate for you.</p>
<div id="QuestionnairForm-message"
style="display:none;margin:12px 0px;padding:12px;background-color: rgba(255,0,4,1.00);color:#fff;border-radius:8px">
<i class="fa fa-exclamation-circle"></i> Please answer all questions.
</div>
<div id="QuestionnairContent">
<form id="QuestionnairForm">
<table class="second-tab survey-table" align="center"
border="0" cellpadding="2" cellspacing="0" style="padding-left: 8px;margin-bottom:16px;width:100%">
<tr>
<td valign="top" colspan="2" class="space-heading2">Questions</td>
</tr>
<tr>
<td valign="top" align="center" width="24" class="q-yes">
<label for="q1_Yes" class="q-label">Yes</label>
<input name="q1" id="q1_Yes" value="1" type="radio">
</td>
<td valign="top" align="center" width="24" class="q-no">
<label for="q1_No" class="q-label">No</label>
<input name="q1" id="q1_No" value="1" type="radio">
</td>
<td valign="top" class="q-desc"><b>1.</b> Have you had unbearable pain for more than three days (i.e. a 10 out of 10)?</td>
</tr>
<tr style="padding-top:14px;">
<td valign="top" align="center" width="24" class="q-yes">
<label for="q2_Yes" class="q-label">Yes</label>
<input name="q2" id="q2_Yes" value="1" type="radio">
</td>
<td valign="top" align="center" width="24" class="q-no">
<label for="q2_No" class="q-label">No</label>
<input name="q2" id="q2_No" value="1" type="radio">
</td>
<td valign="top" class="q-desc">
<b>2.</b> Does your pain travel below your hips into your thigh(s), lower leg(s) or feet?</td>
</tr>
<tr style="padding-top:14px;">
<td valign="top" align="center" width="24" class="q-yes">
<label for="q3_Yes" class="q-label">Yes</label>
<input name="q3" id="q3_Yes" value="1" type="radio">
</td>
<td valign="top" align="center" width="24" class="q-no">
<label for="q3_No" class="q-label">No</label>
<input name="q3" id="q3_No" value="1" type="radio">
</td>
<td valign="top" class="q-desc">
<b>3.</b> Are you experiencing any numbness or tingling in your pelvic region, thighs or lower legs?</td>
</tr>
<tr style="padding-top:14px;">
<td valign="top" align="center" width="24" class="q-yes">
<label for="q4_Yes" class="q-label">Yes</label>
<input name="q4" id="q4_Yes" value="1" type="radio">
</td>
<td valign="top" align="center" width="24" class="q-no">
<label for="q4_No" class="q-label">No</label>
<input name="q4" id="q4_No" value="1" type="radio">
</td>
<td valign="top" class="q-desc"><b>4.</b> Do you have noticeable leg weakness associated with your pain? </td>
</tr>
<tr style="padding-top:14px;">
<td valign="top" align="center" width="24" class="q-yes">
<label for="q5_Yes" class="q-label">Yes</label>
<input name="q5" id="q5_Yes" value="1" type="radio">
</td>
<td valign="top" align="center" width="24" class="q-no">
<label for="q5_No" class="q-label">No</label>
<input name="q5" id="q5_No" value="1" type="radio">
</td>
<td valign="top" class="q-desc"><b>5.</b> Are you experiencing any bowel or bladder control issues since the onset of the back pain?</td>
</tr>
<tr style="padding-top:14px;">
<td valign="top" align="center" width="24" class="q-yes">
<label for="q6_Yes" class="q-label">Yes</label>
<input name="q6" id="q6_Yes" value="1" type="radio">
</td>
<td valign="top" align="center" width="24" class="q-no">
<label for="q6_No" class="q-label">No</label>
<input name="q6" id="q6_No" value="1" type="radio">
</td>
<td valign="top" class="q-desc"><b>6.</b> Do you currently have a fever?</td>
</tr>
<tr style="padding-top:14px;">
<td valign="top" align="center" width="24" class="q-yes">
<label for="q7_Yes" class="q-label">Yes</label>
<input name="q7" id="q7_Yes" value="1" type="radio">
</td>
<td valign="top" align="center" width="24" class="q-no">
<label for="q7_No" class="q-label">No</label>
<input name="q7" id="q7_No" value="1" type="radio">
</td>
<td valign="top" class="q-desc"><b>7.</b> Do you have any serious medical or physical illness?</td>
</tr>
</table>
</form>
</div>
</div>
<div class="clearfix"></div>
<hr>
<div class="actions" style="">
<button type="button" class="btn btn-success btn-lg" data-last="Finish"
style="float:right;" onClick="openQResponse();">Next</button>

</div>
<div class="clearfix"></div>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- #include virtual="/admin/inc/footer.asp" -->

</div>
<!--/page-->
<!--TOS start hear-->
<!-- Model window for -->
	
	<div class="modal fade" id="TOS">
    <div class="modal-dialog">
        <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="TOStitle">Terms of Service</h4>
            </div>
            <div class="modal-body">
                <div style="height:600px;overflow-y:auto;">
                    <p><b>PLEASE READ THESE TERMS OF SERVICE CAREFULLY BEFORE USING THIS SITE.</b></p>
                    <p>Your use or access of this site indicates your acceptance of these Terms. If you do not agree to all of these Terms of Use, do not use this site.</p>
                    <p><b>Introduction.</b> Welcome to www.telespine.com, the TeleSpine, Inc. ("TeleSpine") web site (the "Site”). 

Please read the following terms and conditions of service ("Terms”) carefully as they contain the legal 

terms and conditions of an agreement ("Agreement”) that you agree to when you use the services 

provided to you by TeleSpine through the Site and all other services accessed through the Site 

(collectively, the "Services”).</p>
                    <p>By accessing or using any of the Services you agree to be bound by the Terms of Service For the 

purposes of this Agreement, "you” means you, the person using the Site, and, if applicable, the person

that agrees to the Terms when registering for an account. You and any persons that you authorize to use

your account, are sometimes referred to in this Agreement as the "User.” "TeleSpine,” "we” or "us” means

TeleSpine, Inc. In the case of inconsistencies between these Terms and information on the Site and

included in off-line materials (e.g., promotional materials and mailers), these Terms will always govern 

and take precedence.</p>
                    <p><b>The Site Does Not Provide Medical Advice.</b></p>
                    <p>The information contained on the TeleSpine Site, is intended for informational purposes only. This 

Site, including text, images, video or any other formats or material contained on the Site (the "Content” 

are not intended or implied to be a substitute for professional medical advice, diagnosis, or treatment. 

You should not use any of the Content in place of a visit or call, to, consultation with, or the advice 

of a professional healthcare provider. Telespine is not liable or responsible for any advice, course of 

treatment, diagnosis you obtain through this Site. Never disregard professional medical advice or delay 

in seeking care because of something you have read or watched on this Site.</p>
				<p>Reliance on any information provided by Telespine, Inc. on this Site is solely at your own risk.  Telespine, Inc. assumes no responsibility for the accuracy of information contained or available through this Site, and information posted is subject to change without notice. Telespine, Inc. will not be liable for any direct, indirect, consequential, special, exemplary, or other damages arising therefrom.  TeleSpine, Inc. does not recommend or endorse any specific tests, physicians, products, procedures, opinions, or other information that may be mentioned on the Site.</p>
				
				<p><b>Be Aware:</b></p>
				<p>If you have unbearable pain for more than three days (i.e. a 10/10), consult a physician prior to participating in the Telespine program.</p>
				<p>If you pain travels below your hips into your thigh(s), lower leg(s), or feet, consult a physician prior to participating in the Telespine program.</p>
				<p>If you are experiencing any numbness or tingling in your pelvic region, thighs, or lower legs, consult a physician prior to participating in the Telespine program.</p>
				<p>If you have any noticeable leg weakness associated with your pain, consult a physician prior to participating in the Telespine program.</p>
				<p>If you are experiencing any bowel or bladder control issues since the onset of your back pain, consult a physician prior to participating in the Telespine program.</p>
				<p>If you currently have a fever, consult a physician prior to participating in the Telespine program.</p>
				<p>If you have any serious medical or physical illness, consult a physician prior to participating in the Telespine program.</p>
				



                    <p>If you believe you have a medical emergency you should call 911 or your physician.</p>
                    <p>The Site may contain health or medical-related materials that are sexually explicit. If you find such 

materials offensive, you may not want to use the Site.</p>
                    <p><b>TERMS APPLICABLE TO ALL USERS OF THE SITE</b></p>
                    <p><b>Availability.</b> TeleSpine uses reasonable efforts to ensure that the Services are available 24 hours a day

7 days a week. However, there will be occasions when the Services will be interrupted for maintenance, 

upgrades and emergency repairs or due to failure of telecommunications links and equipment that are 

beyond the control of TeleSpine. TeleSpine will use reasonable commercial efforts to minimize such 

disruption where it is within the reasonable control of TeleSpine. You agree that TeleSpine shall not be 

liable to you for any modification, suspension or discontinuance of the Services. You are responsible for 

obtaining access to any Services and that access may involve third party fees (such as Internet service 

provider or airtime charges). You are responsible for those fees. In addition, you must provide and are 

responsible for all equipment necessary to access the Services.</p>
                    
                    
                    <p><b>Trademarks.</b> All brand, product and service names used in the Services which identify TeleSpine

and www.teleSpine.com are proprietary marks of TeleSpine. All rights reserved. These and other 

www.telespine.com graphics, logos, and service marks and trademarks of Telespine, Inc. and its affiliates 

may not be used without prior written consent of Telespine, Inc. or its affiliates, as the case may be. All 

brand, product and service names used in the Services or on the Site which identify third parties and 

their products and services are proprietary marks of such third parties. Nothing in the Services shall 

be deemed to confer on any person any license or right on the part of TeleSpine or any third party with 

respect to any such image, logo or name.</p>
                    <p><b>Ownership and Copyright.</b> You acknowledge and agree that the Site and the information and Content

contained in it are owned by TeleSpine and/or its third party content providers ("Content Providers"), 

and are protected by U.S. and international copyright and other intellectual property laws. You further 

acknowledge and agree that the Site contains confidential and proprietary data and information of 

TeleSpine and its Content Providers, that you will not use this data or information for any unlawful 

or unauthorized purpose, and that you will use reasonable efforts to keep such data and information 

confidential. You may not modify, remove, delete, augment, add to, publish, transmit, participate in 

the sale or transfer of, create derivative works from, or in any way exploit any of the Content. Except 

as otherwise provided herein or as permitted by the fair use privilegeunder the U.S. copyright laws 

(see, e.g., 17 U.S.C.A. Section 107), you may not upload, post, reproduce, or distribute in any way, 

the Content protected by copyright, or other proprietary right, without obtaining the permission of the 

owner of the copyright or other proprietary right. Each Content Provider is a third party beneficiary 

under this Agreement to the extent required to enable it to enforce its proprietary rights in the data and 

the applicable use restrictions in this Agreement. TeleSpine has the absolute right to terminate your 

account or exclude you from the Site if you use the Services to violate the intellectual property rights or 

other rights of third parties. You agree to indemnify and hold TeleSpine harmless for any violation of this 

provision.</p>
                    <p><b>Third Party Software and Linking.</b> Although we may make software, hyperlinks, and other products

of third-party companies available to you, your use of such products is subject to the respective terms 

and conditions imposed by the third party owning, manufacturing or distributing such products, and the 

agreement for your use will be between you and such third party. TeleSpine makes no warranty with 

regard to the products or website of any other entity. Tx Xchange has no control over the content or 

availability of any third-party software or website. In particular, (a) Telespine makes no warranty that any 

third-party software you download or website you visit will be free of any contaminating or destructive 

code, such as viruses, worms or Trojan horses and (b) TeleSpine notifies you that it is your responsibility 

to become familiar with any website's privacy and other policies and terms of service, and to contact that 

site's webmaster or site administrator with any concerns. All links to third party web sites are provided 

for your convenience only. If you decide to access linked web sites you do so at your own risk. It is your 

responsibility to evaluate the information, opinion, advice, or other Information and/or Content available 

through www. Telespine.com. TeleSpine does not endorse or take responsibility for the content on other 

web sites or the availability of other web sites and you agree that TeleSpine is not liable for any loss or 

damage that you may suffer by using other web sites.</p>
                    <p><b>Amendment of Terms.</b> TeleSpine may amend, revise or update these Terms from time to time without

notice. Your continued use of the Site constitutes acceptance of any such amendments, additions, or 

modifications.</p>
                    
                    
                    <p><b>Privacy Policy.</b> Registration data and certain other information about you is subject to our Privacy Policy. 

For more information, see our full privacy policy below. You understand that through your use of the 

Services you consent to the collection and use (as set forth in the Privacy Policy) of this information, 

including the transfer of this information to the United States and/or other countries for storage, 

processing, and use by Tx Xchange and its affiliates.</p>
                    <p><b>Contact.</b> Telespine is located in Boulder, Colorado, U.S.A. Any questions, comments or suggestions,

including any report of violation of this Agreement should be provided to the Site administrator as follows: 
<br>
By E-mail: support@telespine.com
<br>
By Fax: 720.306.8256
<br>
By Postal Mail: TeleSpine, Inc., 3020 Carbon Place, Boulder, CO 80302</p>
                    <p><b>Monitoring of Content.</b> You understand that TeleSpine can access your account and disclose 

information or otherwise provide access to third parties for the following reasons:</p>
                    <ul>
                        <li>to remind you of your password in case you forget it; if this becomes necessary, we send an e-
mail upon your request to the address from which you opened your account;</li>
                        <li>to maintain the Site and to develop new and useful features and services;</li>
                        <li>to follow a court order, subpoena, complaint or a lawful request from governmental authorities;</li>
                        <li>to enforce these Terms;</li>
                        <li>to respond to claims that any User Content violates the rights of third-parties;</li>
                        <li>to respond to your requests for customer service; and</li>
                        <li>to protect the rights, property, or personal safety of TeleSpine, its users and the public.</li>
                       </ul>
                    <p><b>Passwords</b> You are responsible for ensuring that no unauthorized person has or gains access to your 

www.telespine.com passwords or accounts. It is your sole responsibility to (1) control the dissemination 

and use of sign-in name and password; (2) authorize, monitor, and control access to and use of your 

www.telespine.com account and password; (3) promptly inform Telespine or www.telespine.com if you 

believe your account or password has been compromised or if you need to deactivate a password.</p>
                    <p><b>Suspension and Termination of Acces</b> TeleSpine reserves the right to suspend or terminate your 

account and use of the Site and remove and discard any User Content at any time, without notice, for any 

reason, including but not limited to the following:</p>
                    <ul>
                        <li>breach of these Terms, including policies or guidelines set forth by TeleSpine elsewhere; or</li>
                        <li>conduct that TeleSpine believes is harmful to other users of www.telespine.com or the business

of TeleSpine or other third party information providers.</li>
                    </ul>
                    <p>Further, you agree that TeleSpine shall not be liable to you or any third party for any termination of

your access to the Site. TeleSpine reserves the right at any time and from time to time to modify or 

discontinue, temporarily or permanently, the Services (or any part thereof) with or without notice. You 

agree that TeleSpine shall not be liable to you or to any third party for any modification, suspension or 

discontinuance of the Services.</p>
                    <p><b>Disclaimer and Warranty</b></p>
                    <p>THE CONTENT, SERVICES AND/OR MATERIALS AVAILABLE THROUGH THE SITE ARE PROVIDED

ON AN "AS IS” AND "AS AVAILABLE” BASIS AND WITHOUT WARRANTIES OF ANY KIND EITHER 

EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, IMPLIED WARRANTIES OF 

MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. IN PARTICULAR, TELESPINE MAKES NO REPRESENTATIONS OF WARRANTIES THAT THE SITE, 

INCLUDING THE SERVICES, WILL BE UNINTERRUPTED, TIMELY, SECURE, OR ERROR FREE, 

THAT THE SITE OR OUR SERVER IS FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS, 

THAT THE SITE, INCLUDING THE SERVICES, WILL BE AVAILABLE, OR THAT DATA ENTERED ARE 

SECURE FROM UNAUTHORIZED ACCESS. TELESPINE MAKES NO REPRESENTAIONS OR 

WARRANTIES OF ANY KIND REGARDING ANY SOFTWARE, GOODS, SERVICES, PROMOTIONS, 

OR THE DELIVERY OF ANY SOFTWARE, GOODS OR SERVICES, PURCHASED, ACCESSED OR 

OBTAINED THROUGH THE SITE OR ADVERTISED THROUGH THE TELESPINE SITE. NO ADVICE 

OR INFORMATION GIVEN BY TELESPINE, ITS EMPLOYEES OR AFFILIATES SHALL CREATE A 

WARRANTY. YOU EXPRESSLY AGREE THAT YOUR USE OF THE CONTENT, SERVICES AND/OR 

MATERIALS AVAILABLE THROUGH THE SITE IS AT YOUR OWN DISCRETION AND AT YOUR SOLE 

RISK. TELESPINE MAKES NO REPRESENTAIONS OR WARRANTIES OF ANY KINGD ABOUT THE 

RESULTS TO BE OBTAINED FROM USING THE CONTENT, SERVICES AND/OR MATERIALS 

AVAILABLE THROUGH THE SITE. THE CONTENT, SERVICES AND MATERIALS AVAILABLE 

THROUGH THE SITE ARE PRESENTED IN SUMMARY FORM, ARE GENERAL IN NATURE, AND 

ARE PROVIDED FOR INFORMATIONAL PURPOSES ONLY. THE CONTENT, SERVICES AND 

MATERIALS ARE DESIGNED TO SUPPLEMENT THE ADVICE AND COUNSEL OF YOUR 

THERAPIST, PHYSICIAN, OR OTHER QUALIFIED HEALTH CARE PROFESSIONAL AND ARE NOT 

INTENDED IN ANY WAY TO BE A SUBSTITUTE FOR SUCH PROFESSIONAL ADVICE OR COUNSEL. 

ALWAYS SEEK THE ADVICE OF YOUR THERAPIST, PHYSICIAN OR OTHER QUALIFIED HEALTH 

CARE PROFESSIONAL WITH ANY QUESTIONS YOU MAY HAVE REGARDING A MEDICAL OR 

HEALTH CONDITION. NEITHER THE CONTENT NOR ANY OTHER SERVICES OR MATERIALS 

OFFERED BY OR THROUGH THE SITE ARE INTENDED TO BE RELIED UPON FOR MEDICAL 

DIAGNOSIS OR TREATMENT. NEVER DISREGARD MEDICAL ADVICE OR DELAY IN SEEKING IT 

BECAUSE OF SOMETHING YOU HAVE READ ON OR OBTAINED FROM THE SITE. THE CONTENT, 

SERVICES AND MATERIALS AVAILABLE THROUGH THE SITE MAY NOT BE APPROPRIATE FOR 

ALL USERS.</p>
                    <p><b>Limitation of Liability.</b></p>
                    <p>YOU ACKNOWLEDGE, BY YOUR USE OF THE SITE, THAT YOUR USE OF THE SITE, INCLUDING

ANY SOFTWARE, CONTENT OR OTHER MATERIALS ON THE SITE, AND ANY RELIANCE UPON IT, 

IS AT YOUR SOLE RISK. YOU AGREE THAT, TO THE FULLEST EXTENT PERMITTED BY 

APPLICABLE LAW, UNDER NO CIRCUMSTANCES SHALL TELESPINE OR ITS OFFICERS, 

DIRECTORS, SHAREHOLDERS, EMPLOYEES, PARENTS, SUBSIDIARIES, AFFILIATES, AGENTS 

OR LICENSORS, OR ANY THIRD PARTY PROVIDER OF DATA, CONTENT OR INFORMATION, BE 

LIABLE FOR ANY DAMAGES WHATSOEVER, INCLUDING WITHOUT LIMITATION, DIRECT, 

INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL OR PUNITIVE DAMAGES, LOSS OF 

BUSINESS REVENUE, LOST PROFITS, OR LOSS OF DATA, WHETHER IN AN ACTION UNDER 

CONTRACT, NEGLIGENCE OR ANY OTHER THEORY, ARISING OUT OF YOUR USE OF OR 

INABILITY TO USE THE SERVICES OR THE SITE, OR YOUR RELIANCE ON ANY ADVICE, 

INFORMATION, OR CONTENT ON THE SITE OR PROVIDED AS PART OF THE SERVICES, EVEN IF 

TELESPINE OR ANY RELATED PARTY HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH 

DAMAGES. YOU AGREE THAT TELESPINE SHALL HAVE NO LIABILITY FOR ANY USER OR THIRD 

PARTY CONTENT. YOU ALSO AGREE THAT (1) TELESPINE SHALL NOT BE LIABLE TO YOU OR 

ANY THIRD PARTY UNDER ANY THEORY FOR ANY LOSS OR DAMAGE TO YOUR COMPUTER 

SYSTEM, HANDHELD DEVICE, OR ANY OTHER STORAGE/PLAYBACK DEVICE USED BY YOU TO 

STORE OR DISPLAY THE CONTENT OR TO UTILIZE THE SERVICES AND (2) TELESPINE SHALL 

NOT BE LIABLE UNDER ANY THEORY FOR ANY LOSS OR DAMAGE TO ANY DATA ON YOUR

PERSONAL COMPUTER, HANDHELD DEVICE OR ANY OTHER STORAGE/PLAYBACK DEVICE USED BY YOU TO STORE OR DISPLAY THE CONTENT OR TO UTILIZE THE SERVICES, AND YOU

SHALL BE SOLEY RESPONSIBLE FOR ANY SUCH LOSS OR DAMAGE. YOU SPECIFICALLY 

ACKNOWLEDGE THAT DOWN-TIME, LOSS OF CONTENT, AND COMPUTER VIRUSES ARE RISKS 

INHERENT IN THE USE OF THE INTERNET AND SOFTWARE PRODUCTS, AND YOU AGREE TO 

ASSUME RESPONSIBILITY FOR ANY HARM OR DAMAGES OF ANY KIND OR CHARACTER 

WHATSOEVER RESULTING FROM THESE POSSIBLE HARMS. YOU ALSO SPECIFICALLY 

ACKNOWLEDGE THAT YOU MAY BE DISCLOSING SENSITIVE, PRIVATE AND CONFIDENTIAL 

CONTENT ABOUT YOURSELF IN YOUR USE OF THE SITE AND YOU AGREE TO ASSUME 

RESPONSIBILITY FOR ANY HARM OR DAMAGES OF ANY KIND OR CHARACTER WHATSOEVER 

RESULTING FROM YOUR RELEASE OF SUCH CONTENT. IF YOU ARE DISSATISFIED WITH THE 

SITE, OR WITH ANY OF THESE TERMS, OR FEEL TELESPINE HAS BREACHED THESE TERMS, 

YOUR SOLE AND EXCLUSIVE REMEDY IS TO DISCONTINUE USING THE SITE AND THE 

SERVICES. THE TOTAL LIABILITY OF TELESPINE TO YOU FOR ANY CLAIM ARISING FROM OR 

RELATING TO THESE TERMS OR USE OF THE SITE SHALL NOT EXCEED THE AMOUNT PAID BY 

YOU FOR THE SERVICES IN QUESTION DURING THE THREE (3) MONTH PERIOD PRIOR TO 

WHEN SUCH CLAIM AROSE. IT IS THE INTENTION OF BOTH OF US THAT THIS PROVISION BE 

CONSTRUED BY A COURT AS BEING THE BROADEST LIMITATION OF LIABILITY CONSISTENT 

WITH APPLICABLE LAW. SOME JURISDICTIONS DO NOT ALLOW THE LIMITATION OR 

EXCLUSION OF INCIDENTAL, CONSEQUENTIAL OR OTHER TYPES OF DAMAGES, SO SOME OF 

THE ABOVE LIMITATIONS MAY NOT APPLY TO YOU.</p>
                    <p><b>Indemnification.</b> You agree to indemnify, defend and hold TeleSpine, and its subsidiaries, affiliates, 

officers, agents, co-branders or other partners, and employees, harmless from any claim or demand, 

including reasonable attorneys' fees, made by any third party due to or arising out of User Content, your 

use of the Services, your connection to the Services, your violation of these Terms, or your violation of 

any rights of another.</p>
                    <p><b>Updates and Health.</b> You are advised that health advice is often subject to updating and refining due

to medical research and developments. However, we do not assure that the advice contained on the 

Site will include the most recent findings or developments with respect to the particular material. You 

are encouraged to consult with your health care provider with any questions or concerns you may have 

regarding any health condition that you may have. TeleSpine is not responsible for your use of the 

information, Content, or materials contained on or obtained from the Site.</p>
                    <p><b>Usage by Children under 13.</b> Telespine and www.telespine.com cannot prohibit minors from visiting this

Site. Telespine must rely on parents, guardians and those responsible for supervising children under 13 

to decide which materials are appropriate for such children to view and/or purchase.</p>
                    <p><b>Applicable Law and Jurisdiction.</b> You agree that regardless of any statute or law to the contrary, any 

claim or cause of action arising out of or related to the use of the Site or these Terms of Service must be 

brought, if at all, within one (1) year from the accrual of the claim or cause of action or be forever barred. 

The parties agree that this Agreement and any claims hereunder shall be governed by and subject to the 

laws of the state of Colorado, without giving effect to any principles of conflicts of law.</p>
                    <p><b>Dispute Resolution.</b> All disputes arising under this Agreement shall be submitted to mediation. Each 

party shall designate an executive officer or principal empowered to resolve the dispute. Should the 

designated representatives be unable to agree on a resolution, a mediation service acceptable to both 

parties shall select a mediator to mediate the dispute. Each disputing party shall pay an equal percentage of the mediator's fees and expenses. No suit or arbitration proceeding shall be commenced under this 

Agreement until at least 60 days after the mediator's first meeting with the involved parties. In the event 

that a dispute is required to be litigated, you agree that the proper forum for any and all claims under 

this Agreement will be the state and federal courts located in Denver County, Colorado, and you agree 

to submit to the jurisdiction of these courts. The prevailing party in any action will be entitled to recover 

reasonable expenses, including attorneys' fees.</p>
                    <p><b>Independent Contractors.</b> No joint venture, partnership, employment, or agency relationship exists 

between you and TeleSpine as a result of this Agreement or use of the Services.</p>
                    <p><b>Force Majeure</b>. TeleSpine will be not liable hereunder by reason of any failure or delay in the

performance of its obligations hereunder on account of strikes, shortages, riots, insurrection, fires, 

flood, storm, explosions, acts of God, war, governmental action, labor conditions, earthquakes, material 

shortages or any other cause which is beyond TeleSpine's reasonable control.</p>
                    <p><b>Waiver.</b> The failure of TeleSpine to enforce any right or provision in this Agreement will not constitute a

waiver of such right or provision unless acknowledged and agreed to by TeleSpine in writing.</p>
                    <p><b>Construction.</b> The headings of Sections of this Agreement are for convenience and are not to be used in 

interpretation.</p>
                    <p><b>Entire Agreement.</b> This Agreement, including the Privacy Policy and Medical Disclaimer, constitutes 

the entire agreement between you and TeleSpine and governs your use of the Site, superseding any 

prior agreements between you and Telespine, whether written or oral. If any provision of this Agreement 

is found by a court of competent jurisdiction to be invalid, the parties nevertheless agree that the court 

should endeavor to give effect to the parties' intentions as reflected in the provision, and the other 

provisions of this Agreement remain in full force and effect.</p>
                    <p><b>BY CLICKING ON THE "ACCEPT” BUTTON BELOW, YOU ACKNOWLEDGE THAT YOU HAVE 

READ THIS AGREEMENT, UNDERSTAND IT, AND AGREE TO BE BOUND BY IT. IF YOU DO 

NOT AGREE TO ANY OF THE TERMS ABOVE, TELESPINE IS UNWILLING TO PROVIDE THE 

SERVICES TO YOU, AND YOU SHOULD CLICK ON THE "DO NOT ACCEPT” BUTTON BELOW TO 

DISCONTINUE THE REGISTRATION PROCESS.</b></p>
                    <p>YOU AGREE TO BE BOUND BY THE TERMS OF SERVICE by using the TeleSpine, Inc. Site.</p>
                    <p><b>Privacy Policy</b></p>
                    <p>This statement discloses the privacy practices for www.txxchangetelespine.com (the "Site”), the

TeleSpine, Inc. ("TeleSpine") web site and informs you about:</p>
                    <ul>
                        <li>what personally identifiable information is collected;</li>
                        <li>what organization is collecting the information;</li>
                        <li>how the information is used and disclosed to third parties;</li>
                        <li>what choices are available to you regarding collection, use and distribution of the information;</li>
                        <li>how to correct, update or delete personally identifiable information; and</li>
                        <li>what kind of security procedures are in place to protect the loss, misuse or alteration of

information under the company's control.</li>
                    </ul>
                    <p>"Personally identifiable information” is information that would allow someone to identify or contact you,

including, for example, your full name, address, telephone number or email address.</p>
                    <p>Questions regarding this Privacy Policy should be directed to the Site Administrator:
<br>
By E-mail: support@txxchangetelespine.com
<br>
By Fax: 720.306.8256
<br>
By Postal Mail: TeleSpine, Inc., 3020 Carbon Place, Boulder, CO 80302</p>
                    <p>This Privacy Policy is incorporated into and subject to the terms of our Terms of Service. This policy only

addresses the data transactions that occur on TeleSpine owned or leased servers and databases. Other 

sites (including those that we link to and third party sites or services) may have their own policies, which 

we do not control, and thus are not addressed by this policy.</p>
                    <p><b>(1) What Information Is Collected</b></p>
                    <p>You provide certain personal information to us, including when you: (a) register for an account, (b) 

register for free newsletters, (c) send email messages, submit forms or send other information to us, 

and (d) subscribe to receive Services. We may collect personal information from you at other points on 

our Site that state that personal information is being collected. We may also receive certain personal 

information from healthcare providers or third party partners.</p>
                    <p>In addition, our servers automatically collect information about which pages are viewed within our Site,

but in these instances, we do not obtain personally identifiable information about you. We use a feature 

of your browser called a "cookie” to assign a User ID that automatically identifies your computer when 

you visit the Site. Cookies are small pieces of information stored on your hard drive, not on our Site. You 

are always free to decline our cookies if your browser permits, but some parts of the Site may not work 

properly if you do.</p>
                    <p><b>(2) What Organization Is Collecting the Information</b></p>
                    <p>TeleSpine owns and operates the Site and is the company that collects personal information on the Site. 

Our contact information is listed above.</p>
                    <p><b>(3) How the Information Is Used and Disclosed to Third Parties.</b></p>
                    <p>As a general practice, we do not provide your personally identifiable information to third parties. So, for 

example, we do not sell your email address or your name and personal demographic information to mass 

marketers. However, on particular pages where we ask for your personally identifiable information, we 

may explicitly tell you that the data we are collecting on that page will be shared with third parties, in 

which case those disclosures shall override anything to the contrary in this policy. In some cases, we use 

your personally identifiable information to provide information to you, such as to send to you requested 

newsletters or to provide to you requested services.</p>
                    <p><b>(4) Controlling the Collection, Use and Distribution of Your Information.</b></p>
                    <p>You can control the content of your personal information by following the instructions stated below in

Section 5.</p>
                    <p>Please note that if you provide any information on your own or directly to parties who provide service to 

our web site or any other sites you encounter on the Internet (even if these sites are branded with our 

branding), different rules may apply to their use or disclosure of the personal information you disclose to 

them. We encourage you to investigate and ask questions before disclosing information to third parties.</p>
                    <p><b>(5) Correcting, Updating and Deleting Personal Information.</b></p>
                    <p>You can access and update your personal information, including changing your subscriptions to our

email newsletters and opting out of receiving email about our services, by logging into your account at 

www.telespine.com. Even if you delete or change some personal information in our registration database, 

it may still be stored on other databases (including those kept for archival purposes).</p>
                    <p><b>(6) Our Security Procedures.</b></p>
                    <p>We have established and maintain reasonable security procedures to protect the confidentiality, security

and integrity of your personally identifiable information. These measures include firewalls, password 

protection, electronic monitoring, data encryption, and physical security of our servers, Our employees 

and our sub-contractors follow specific business practices to maintain the integrity and security of your 

personal information. "Perfect security,” however, does not exist on the Internet.</p>
                    <p>Although your privacy is very important to us, due to the existing legal regulatory and security 

environment, we cannot fully ensure that your private communications and other personally identifiable 

information will not be disclosed to third parties. For example, we may be forced to disclose information 

to the government or third parties under certain circumstances, or third parties may unlawfully intercept or 

access transmissions or private communications. Additionally, in the unlikely event we need to investigate 

or resolve possible problems or inquiries, we can (and you authorize us to do so) disclose any information 

about you to private entities, law enforcement or other government officials as we, in our sole discretion, 

believe is necessary or appropriate.</p>
                    <p>We hope this policy clarifies our procedures regarding your personal information. We reserve the right to 

change this policy at any time, so please re-visit this page as often as you wish.</p>
                    
                    
                    
                    
                <div class="clearfix"></div>
                <hr>
                <div class="actions" style="">
                    <button type="button" class="btn btn-success" data-last="Finish" style="float:right;" onClick="$('#TOS').modal('hide');">Close</button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog --> 
</div><!-- /.modal -->
<!-- Tos End -->
<!-- inline scripts related to this page -->

<script src="assets/js/app.controllers.js"></script>
<script src="assets/js/config.js"></script>
<script src="assets/js/inForm.js"></script>
<script src="assets/js/jquery.gritter.min.js"></script>
<script src="assets/js/core.min.js"></script>
<script src="assets/js/bootstrap-checkbox.js"></script>
<script>
$(document).ready(function () {
    if($("#billing-form-message").html()=='&gt;<!--atag--><!--errorMessage-->'){
//$("#Questionnair").modal({backdrop: 'static',keyboard: false});
$.gritter.removeAll();
var formId = "billing-form";
inForm.init(formId,"fieldError");
    }
});
function openQResponse(){
var hasAnsweredYes = false;
console.log("openQResponse() ");
var optionsCount = 0;
$("#QuestionnairForm input:checked").each(
function(index, elm){
console.log("Checked Radio Option: "+ $(this).attr("id"));
optionsCount++;
if($(this).attr("id").indexOf("Yes")!=-1){
console.log(""+ elm);
hasAnsweredYes = true;
}
}
);
if(optionsCount<7){
$("#QuestionnairForm-message").show();
} else {
console.log("hasAnsweredYes:"+ hasAnsweredYes);
$('#Questionnair').modal('hide');
if(hasAnsweredYes){
$("#QuestionYes").modal({backdrop: 'static',keyboard: false});
} else {
$("#QuestionNo").modal({backdrop: 'static',keyboard: false});
}
}
}
</script>
<!-- inline scripts related to this page --> 
<script>
/** Extends inForm */
function isPromoChecked(methodName,obj){
    console.log("Checking function isPromoChecked "+jQuery("#usePromoCodeTrue").is(':checked'));
    return jQuery("#usePromoCodeTrue").is(':checked');
}
function isBillingChecked(methodName,obj){
    console.log("Checking function isBillingChecked "+jQuery("#usePromoCodeFalse").is(':checked'));
    return jQuery("#usePromoCodeFalse").is(':checked');
}

function chk_toi(){

//alert($("#toi_chk").is(':checked') );

if($("#toi_chk").is(':checked')){
$('#billing-form').submit();

}else{
jQuery("#billing-form-message1").show();
jQuery("#billing-form-message1").html("Please confirm you have read the Terms of Service by checking the box. ");
return false;
}

 
}


$('input[type="checkbox"].large').checkbox({
    buttonStyle: 'btn-link btn-large',
    checkedClass: 'icon-check',
    uncheckedClass: 'icon-check-empty'
});




</script>
<script src="assets/js/app.controllers.js"></script> 
<script src="assets/js/config.js"></script> 
<script src="assets/js/inForm.js"></script> 
<script src="assets/js/jquery.gritter.min.js"></script> 
<script src="assets/js/core.min.js"></script> 


<script>
$(document).ready(function () {  
	$.gritter.removeAll();
	var formId = "billing-form";
	inForm.init(formId,"fieldError");
        
});

</script>
<!chkevent>
</body>
</html>
