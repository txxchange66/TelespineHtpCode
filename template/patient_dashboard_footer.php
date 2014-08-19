<!-- Overlay Overview -->

<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="overviewTitle">Overview</h4>
            </div>
            <div class="modal-body">
                <div class="dialog-help" style="display:none;" id="dialog-painMeter">
                    <h3>Current Pain Level</h3>
                    <p>This box is designed to give you a quick view of your pain history. It is intended to help see the changes during your eight week program.</p>
                    <p>With the pain level bar you can set your current pain level. You can do this as often as you wish. Far left is no pain, and far right is high pain. Then click the check button when you have set the pain level.</p>
                </div>
                <div class="dialog-help" style="display:none;" id="dialog-functionalScore">
                    <h3>Functional Score</h3>
                    <p>The Functional Score section of your portal is comprised of two data: your functional score and your pain level score. Both of these results come from the Oswestry Survey that you've already taken at least once and will take 4x throughout our 8-week program. </p>
                    <p>If you participate in the program (at least 3x a week and hopefully more) and stay engaged throughout the entire program, you should see your functional score and pain levels go down over time (i.e. lower scores are better).</p>
                </div>
                <div class="dialog-help" style="display:none;" id="dialog-goalsAndStatistics">
                    <h3>Goals and Statistics</h3>
                    <p>The Telespine program helps you keep track of your goals and the frequency with which you login. Why are these numbers important? </p>
                    <p>Research shows that identifying the goals you want to achieve and tracking your achievement of them will help to stay more engaged with the program. By being able to see how many times you've logged in (we recommend a minimum of 3x a week), you can help to stay accountable to yourself, which will help you achieve your goals!</p>
                </div>
                <div class="dialog-help" style="display:none;" id="dialog-healthyHabits">
                    <h3>Telespine Reminders</h3>
                    <p>Everyday we’ll give you ideas and recommendations we’d like you to try and remember throughout the day. The reminders will also be sent to you via email in case you don’t login for a day or two.</p>
                    <p>You'll notice that the reminders may stay the same from day to day. That’s ok. It only means that they’re valuable to remember - and we really want you to put them into practice!</p>
                </div>
                <div class="dialog-help" style="display:none;" id="dialog-today">
                    <h3>Today's Activities</h3>
                    <p>This box will show you the assignment for today.</p>
                </div>
                <div class="dialog-help" style="display:none;" id="dialog-timeline">
                    <p>As part of Telespine’s healthy back program, we give you daily activities to follow, like watching videos and doing the associated exercises/stretches or reading educational articles. You’ll also see daily messages where we provide feedback and help keep you focused on key ideas, guidance, or objectives. </p>
                    <p>Use the Daily Activities Timeline to participate in today’s activities or review past ones over the course of our 56-day program. To watch a video or read an article, simply click on the link.</p>
                </div>
				 
				
				
				
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onClick="location.href = 'index.php?action=faqs'">View FAQs</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
        <!-- /.modal-content --> 

    </div>
    <!-- /.modal-dialog --> 

</div>
<!-- /.modal -->

<div class="clearfix"></div>

<!-- Survey Overlay -->

<div class="modal fade" id="videoViewer">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" 

                        onClick="$('video').trigger('pause');">&times;</button>
                <h4 class="modal-title" id="videoName">Video</h4>
            </div>
            <div class="modal-body" style="min-height:150px">
                <div  class="video-container-lg">
                    <video  id="overlayVideo"  controls  width="100%"  preload="none" autoplay

                            title="" > </video>
                </div>
                <div class="video-details">
                    <div id="videoSmallName" style="display:inline;"></div>
                    <span id="videoTime"></span>
                    <div><!--<li class="fa fa-check"></li>Watched--></div>
					
					<div class="clearfix"></div>
				<div id="videoProperties">
				</div>
                </div>
				
				
				
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" 
                        onClick="$('video').trigger('pause');">Close</button>
            </div>
        </div>
        <!-- /.modal-content --> 

    </div>
    <!-- /.modal-dialog --> 

</div>
<!-- /.modal -->

<div class="modal fade" id="articleViewer">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="articleName">Article</h4>
            </div>
            <div class="modal-body" style="min-height:200px"> You are about to open a PDF.
                <hr>
                <br>
                <div>
                    <div id="pdfName" style="font-weight:400;font-size:18px"></div>
                    <div style="padding:30px; font-size:12px;">
                        <hr>
                        You can open this file in "Adobe Reader, free PDF viewer download".<br>
                        <a href="http://get.adobe.com/reader/">Get Adobe Reader for Desktop.</a> <br>
                        <a href="http://www.adobe.com/products/reader-mobile.html">For Adobe Reader on iOS and Android devices, visit the mobile app page ›</a> </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content --> 

    </div>
    <!-- /.modal-dialog --> 

</div>


<div class="modal fade" id="articleViewer1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" >You are about to open a PDF.</h4>
            </div>
            <div class="modal-body" style="min-height:200px"> 
                
                    <div style="padding:30px; font-size:12px;">
                       
                        You can open this file in "Adobe Reader, free PDF viewer download".<br>
                        <a href="http://get.adobe.com/reader/">Get Adobe Reader for Desktop.</a> <br>
                        <a href="http://www.adobe.com/products/reader-mobile.html">For Adobe Reader on iOS and Android devices, visit the mobile app page ›</a> </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content --> 

    </div>
    <!-- /.modal-dialog --> 
	
	<!-- Model window for -->
	
	<div class="modal fade" id="MedicalDisclaimer">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="MedicalDisclaimerTitle">Medical Disclaimer</h4>
            </div>
            <div class="modal-body">
                <div style="min-height:300px">
				<p>The information contained on the TeleSpine, Inc. Site is intended for informational purposes only. This Site, including text, images, video or any other formats or material contained on the Site (the “Content”) are not intended or implied to be a substitute for professional medical advice, diagnosis, or treatment. You should not use any of the Content in place of a visit or call, to, consultation with, or the advice of a professional healthcare provider. Telespine, Inc. is not liable or responsible for any advice, course of treatment, diagnosis you obtain through this Site.  Never disregard professional medical advice or delay in seeking care because of something you have read or watched on this Site.</p>
					 <p>Reliance on any information provided by Telespine, Inc. on this Site is solely at your own risk.  Telespine, Inc. assumes no responsibility for the accuracy of information contained or available through this Site, and information posted is subject to change without notice. Telespine, Inc. will not be liable for any direct, indirect, consequential, special, exemplary, or other damages arising therefrom.  TeleSpine, Inc. does not recommend or endorse any specific tests, physicians, products, procedures, opinions, or other information that may be mentioned on the Site.</p>
					 
					 <p>If you believe you have a medical emergency, you should call 911 or your physician.</p>
                <div class="clearfix"></div>
                <hr>
                <div class="actions" style="">
                    <button type="button" class="btn btn-success" data-last="Finish" style="float:right;" onClick="$('#MedicalDisclaimer').modal('hide');">Close</button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog --> 
</div><!-- /.modal -->
	
	
	
	
	
	
	
	
	<!--end model---->
	
	
	
	
	
	
	

</div>






<!-- /.modal -->

<div class="modal fade" id="ResetPassword">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create a Password</h4>
            </div>
            <div class="modal-body" style="min-height:300px">
                <p><strong>Welcome <!userName>!</strong> Before we get started let's set your password. After, we will walk you through the program and you will be on your way in just minutes! &nbsp;<br>
                <form action="index.php?action=changepass_firsttime" id="changePassword-form" class="form-horizontal" method="post" title="Password">
                    <div id="changePassword-form-message" class="errorMessage"> </div>
                    <fieldset class="col-sm-12" style="padding:0% 8% 4% 8%;">
                        <p>Passwords must be at least 8 characters and must contain at least one number [0-9], and at least one character [a-z, A-Z].</p>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group form-box">
                                    <label class="control-label" for="newPassword">New Password</label>
                                    <div class="">
                                        <input class="form-control required" id="newPassword" name="newPassword" type="password" value="" placeholder="New Password" data-role="not-blank" minlength="8" maxlength="35">
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
                                        <input class="form-control required" id="confirmNewPassword" name="confirmNewPassword" data-role="not-blank" minlength="8" maxlength="35" type="password" value="" placeholder="Confirm New Password">
                                        <i class="fa fa-asterisk form-indicator"></i> 
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <!-- /.modal-content --> 

    </div>
    <!-- /.modal-dialog --> 

</div>
<!-- /.modal --> 

<!-- ********************* First Time User ********************* -->

<div class="modal fade" id="FirstTimeUser">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Welcome <!userName> <!userLastName>!</h4>
            </div>
            <div class="modal-body" style="min-height:100px">
                <p>Telespine is an 8-week program designed to help you recover from low back pain and keep it from returning. Over the course of the program, we'll help you focus on mobility, flexibility, core strengthening, and posture - all the things that will lead to lasting, not just temporary, pain relief.</p>
                <p>At four different times throughout the 8-weeks, you'll be asked to take a few minutes and complete an "Oswestry Survey". This is an industry standard tool we use to measure how your pain is affecting your life. By completing the surveys, we can better help in your recovery.</p>
                <p>If you stick with the program and invest a few minutes each day in yourself (even once the pain starts to go away!), you'll get back to enjoying a life free of low back pain.<br>
                    <a onclick="javaScript:$('#FirstTimeUser').modal('hide');" class="btn btn-warning" style="clear:both;margin-top:16px;font-size:20px">Let's Get Started</a>
                </p>
            </div>
        </div>
        <!-- /.modal-content --> 

    </div>
    <!-- /.modal-dialog --> 

</div>
<!-- /.modal --> 

<!-- ********************* Oswestery ********************* -->
<div class="modal fade" id="OswesteryModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Oswestry Survey</h4>
            </div>
            <div class="modal-body" style="min-height:280px; background-color:#fafafa;"> 
                <!-- Oswestery Start -->

                <form class="form-horizontal" style="min-height:160px" id="Oswestery-form">
                    <div id="OswesteryWiz" style="padding:2px 12px;">
                        <div class="step" valid="true">
                            <p>The Oswestry Survey is a very valuable tool we use to measure the functional disability caused by your low back pain. The Oswestry is used by virtually ever healthcare provider that works with low back pain and is considered the ‘gold standard’ of low back functional assessment.</p>
                            <p>We realize that two of the statements may describe how you feel, but please select only the one that MOST CLOSELY describes your condition. Please make sure to answer every question.</strong></p>
                            <!--<center>
                                    <a class="btn btn-primary" href="#/ok" onClick="App.wizard.setAsValid(true)">OK</a>
                            </center>-->
                        </div>
                        <div class="step">
                            <fieldset class="col-sm-12">
                                <div class="form-group">
                                    <table class="second-tab Oswestery-table" align="center" border="0" cellpadding="0" cellspacing="0" style="padding-left: 8px;width:100%">
                                        <tr>
                                            <td valign="top" colspan="2" class="space-heading2">1) PAIN INTENSITY</td>
                                        </tr>
                                        <tr>
                                            <td valign="top" width="24" class="spacer" style="padding-right: 8px;"><input name="pain_intensity" id="pain_intensity_1" value="1" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="pain_intensity_1">The pain is mild and comes and goes.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="pain_intensity_2" name="pain_intensity" value="2" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="pain_intensity_2">The pain is mild and does not vary much.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="pain_intensity_3" name="pain_intensity" value="3" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="pain_intensity_3">The pain is moderate and comes and goes.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="pain_intensity_4" name="pain_intensity" value="4" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="pain_intensity_4">The pain is moderate and does not vary much.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="pain_intensity_5" name="pain_intensity" value="5" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="pain_intensity_5">The pain is severe and come and goes.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="pain_intensity_6" name="pain_intensity" value="6" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="pain_intensity_6">The pain is severe and and does not vary much.</label></td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                        <div class="step">
                            <fieldset class="col-sm-12">
                                <div class="form-group">
                                    <table class="second-tab Oswestery-table" align="center" border="0" cellpadding="0" cellspacing="0" style="padding-left: 8px;width:100%">
                                        <tr>
                                            <td valign="top" colspan="2" class="space-heading2">2) PERSONAL CARE (Washing, Dressing, etc.)</td>
                                        </tr>
                                        <tr>
                                            <td valign="top" width="24"  class="spacer"><input id="personal_care_1" name="personal_care" value="1" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="personal_care_1">I do not have to change the way I wash and dress myself to avoid the pain.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="personal_care_2" name="personal_care" value="2" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="personal_care_2">I do not normally change the way I wash or dress myself even though it causes some pain.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="personal_care_3" name="personal_care" value="3" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="personal_care_3">Washing and dressing increases my pain, but I can do it without changing my way of doing it.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="personal_care_4" name="personal_care" value="4" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="personal_care_4">Washing and dressing increases my pain, and I find if necessary to change the way I do it.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="personal_care_5" name="personal_care" value="5" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="personal_care_5">Because of my pain I am partially unable to wash and dress without help.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="personal_care_6" name="personal_care" value="6" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="personal_care_6">Because of my pain I am completely unable to wash and dress without help.</label></td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                        <div class="step">
                            <fieldset class="col-sm-12">
                                <div class="form-group">
                                    <table class="second-tab Oswestery-table" align="center" border="0" cellpadding="0" cellspacing="0" style="padding-left: 8px;width:100%">
                                        <tr>
                                            <td valign="top" colspan="2" class="space-heading2"> 3) LIFTING</td>
                                        </tr>
                                        <tr>
                                            <td valign="top" width="24"  class="spacer"><input id="lifting_1" name="lifting" value="1" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="lifting_1">I can lift heavy weights without increased pain.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="lifting_2" name="lifting" value="2" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="lifting_2">I can lift heavy weights but it causes increased pain.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="lifting_3" name="lifting" value="3" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="lifting_3">Pain prevents me from lifiting heavy weights off of the floor, but I can manage if they are conveniently positioned (ex. on a table,etc.).</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="lifting_4" name="lifting" value="4" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="lifting_4">Pain prevents me from lifiting heavy weights off of the floor, but I can manage light to medium weights if they are conveniently positioned.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="lifting_5" name="lifting" value="5" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="lifting_5">I can lift only very light weights.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="lifting_6" name="lifting" value="6" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="lifting_6">I can not lift or carry anything at all.</label></td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                        <div class="step">
                            <fieldset class="col-sm-12">
                                <div class="form-group">
                                    <table class="second-tab Oswestery-table" align="center" border="0" cellpadding="0" cellspacing="0" style="padding-left: 8px;width:100%">
                                        <tr>
                                            <td valign="top" colspan="2" class="space-heading2"> 4) WALKING</td>
                                        </tr>
                                        <tr>
                                            <td valign="top" width="24"  class="spacer"><input id="walking_1" name="walking" value="1" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="walking_1">I have no pain when walking.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="walking_2" name="walking" value="2" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="walking_2">I have pain when walking, but I can still walk my required normal distances.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="walking_3" name="walking" value="3" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="walking_3">Pain pevents me from walking long distance.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="walking_4" name="walking" value="4" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="walking_4">Pain pevents me from walking intermediate distance.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="walking_5" name="walking" value="5" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="walking_5">Pain pevents me from walking even short distance.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="walking_6" name="walking" value="6" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="walking_6">Pain pevents me from walking at all.</label></td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                        <div class="step">
                            <fieldset class="col-sm-12">
                                <div class="form-group">
                                    <table class="second-tab Oswestery-table" align="center" border="0" cellpadding="0" cellspacing="0" style="padding-left: 8px;width:100%">
                                        <tr>
                                            <td valign="top" colspan="2" class="space-heading2"> 5) SITTING</td>
                                        </tr>
                                        <tr>
                                            <td valign="top" width="24"  class="spacer"><input id="sitting_1" name="sitting" value="1" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="sitting_1">Sitting does not cause me any pain.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="sitting_2" name="sitting" value="2" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="sitting_2">I can only sit as long as I like providing that I have my choice of seating surface.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="sitting_3" name="sitting" value="3" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="sitting_3">Pain prevent me from sitting for more than 1 hour.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="sitting_4" name="sitting" value="4" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="sitting_4">Pain prevent me from sitting for more than 1/2 hour.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="sitting_5" name="sitting" value="5" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="sitting_5">Pain prevent me from sitting for more than 10 minutes.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="sitting_6" name="sitting" value="6" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="sitting_6">Pain prevent me from sitting at all.</label></td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                        <div class="step">
                            <fieldset class="col-sm-12">
                                <div class="form-group">
                                    <table class="second-tab Oswestery-table" align="center" border="0" cellpadding="0" cellspacing="0" style="padding-left: 8px;width:100%">
                                        <tr>
                                            <td valign="top" colspan="2" class="space-heading2"> 6) STANDING</td>
                                        </tr>
                                        <tr>
                                            <td valign="top" width="24"  class="spacer"><input id="standing_1" name="standing" value="1" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="standing_1">I can stand as long as I want without increased pain.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="standing_2" name="standing" value="2" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="standing_2">I can stand as long as I want but my pain increases with time.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="standing_3" name="standing" value="3" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="standing_3">Pain prevents me from standing more than 1 hour.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="standing_4" name="standing" value="4" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="standing_4">Pain prevents me from standing more than 1/2 hour.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="standing_5" name="standing" value="5" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="standing_5">Pain prevents me from standing more than 10 minutes.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="standing_6" name="standing" value="6" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="standing_6">I avoid standing because it increases my pain right away.</label></td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                        <div class="step">
                            <fieldset class="col-sm-12">
                                <div class="form-group">
                                    <table class="second-tab Oswestery-table" align="center" border="0" cellpadding="0" cellspacing="0" style="padding-left: 8px;width:100%">
                                        <tr>
                                            <td valign="top" colspan="2" class="space-heading2"> 7) SLEEPING</td>
                                        </tr>
                                        <tr>
                                            <td valign="top" width="24"  class="spacer"><input id="sleeping_1" name="sleeping" value="1" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="sleeping_1">I get no pain when I am in bed.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="sleeping_2" name="sleeping" value="2" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="sleeping_2">I get pain in bed, but it does not prevent me from sleeping well.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="sleeping_3" name="sleeping" value="3" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="sleeping_3">Because of my pain, my sleep is only 3/4 of my normal amount.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="sleeping_4" name="sleeping" value="4" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="sleeping_4">Because of my pain, my sleep is only 1/2 of my normal amount.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="sleeping_5" name="sleeping" value="5" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="sleeping_5">Because of my pain, my sleep is only 1/4 of my normal amount.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="sleeping_6" name="sleeping" value="6" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="sleeping_6">Pain prevents me from sleeping at all.</label></td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                        <div class="step">
                            <fieldset class="col-sm-12">
                                <div class="form-group">
                                    <table class="second-tab Oswestery-table" align="center" border="0" cellpadding="0" cellspacing="0" style="padding-left: 8px;width:100%">
                                        <tr>
                                            <td valign="top" colspan="2" class="space-heading2"> 8) SOCIAL LIFE</td>
                                        </tr>
                                        <tr>
                                            <td valign="top" width="24"  class="spacer"><input id="social_life_1" name="social_life" value="1" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="social_life_1">My social life is normal and does not increase my pain.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="social_life_2" name="social_life" value="2" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="social_life_2">My social life is normal, but it increases my level of pain.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="social_life_3" name="social_life" value="3" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="social_life_3">Pain prevents me from participating in more energetic activities (ex. sports, dancing, etc.)</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="social_life_4" name="social_life" value="4" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="social_life_4">Pain prevents me from going out very often.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="social_life_5" name="social_life" value="5" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="social_life_5">Pain has restricted my social life to my home.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="social_life_6" name="social_life" value="6" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="social_life_6">I have hardly any social life because of my pain.</label></td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                        <div class="step">
                            <fieldset class="col-sm-12">
                                <div class="form-group">
                                    <table class="second-tab Oswestery-table" align="center" border="0" cellpadding="0" cellspacing="0" style="padding-left: 8px;width:100%">
                                        <tr>
                                            <td valign="top" colspan="2" class="space-heading2"> 9) TRAVELING</td>
                                        </tr>
                                        <tr>
                                            <td valign="top" width="24"  class="spacer"><input id="traveling_1" name="traveling" value="1" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="traveling_1">I get no increased pain when traveling.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="traveling_2" name="traveling" value="2" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="traveling_2">I get some pain while traveling, but none of my usual forms of travel make it any worse.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="traveling_3" name="traveling" value="3" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="traveling_3">I get increased pain while traveling, but it does not cause me to seek alternative forms of travel.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="traveling_4" name="traveling" value="4" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="traveling_4">I get increased pain while traveling which causes me to seek alternative forms of travel.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="traveling_5" name="traveling" value="5" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="traveling_5">My pain restricts all forms of travel except that which is done while Iam lying down.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="traveling_6" name="traveling" value="6" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="traveling_6">My pain restricts all forms of travel.</label></td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                        <div class="step">
                            <fieldset class="col-sm-12">
                                <div class="form-group">
                                    <table class="second-tab Oswestery-table" align="center" border="0" cellpadding="0" cellspacing="0" style="padding-left: 8px;width:100%">
                                        <tr>
                                            <td valign="top" colspan="2" class="space-heading2"> 10) EMPLOYMENT / HOMEMAKING</td>
                                        </tr>
                                        <tr>
                                            <td valign="top" width="24"  class="spacer"><input id="employment_homemaking_1" name="employment_homemaking" value="1" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="employment_homemaking_1">My normal job/homemaking activities do not cause pain.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="employment_homemaking_2" name="employment_homemaking" value="2" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="employment_homemaking_2">My normal job/homemaking activities increase my pain, but I can still perform all that is required of me.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="employment_homemaking_3" name="employment_homemaking" value="3" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="employment_homemaking_3"> I can perform most of my job/homemaking duties, but painprevents me from performing more physically stressful activities (ex. lifting, vacuuming)</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="employment_homemaking_4" name="employment_homemaking" value="4" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="employment_homemaking_4">Pain prevents me from doing anything but light duties.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="employment_homemaking_5" name="employment_homemaking" value="5" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="employment_homemaking_5">Pain prevents me from doing even light duties.</label></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="spacer"><input id="employment_homemaking_6" name="employment_homemaking" value="6" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                            <td valign="top" class="pad-bottom"><label for="employment_homemaking_6">Pain prevents me from performing any job or homemaking chores.</label></td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                        <div class="step">
                            <fieldset class="col-sm-12">
                                <div class="form-group">
                                    <table class="second-tab Oswestery-table" align="center" border="0" cellpadding="0" cellspacing="0" style="padding-left: 8px;width:100%">
                                        <tr>
                                            <td valign="top" colspan="2" class="space-heading2"> 11) PAIN SCALE</td>
                                        </tr>
                                        <tr>
                                            <td valign="top" colspan="2">During the last 4 weeks, how intense or severe have your symptoms been:</td>
                                        </tr>
                                        <tr>
                                            <td valign="top" colspan="2">
                                                <br>
                                                <br>
                                        <center>
                                            <table width="100%">
                                                <tr>
                                                    <td colspan="10" align="left" valign="middle">1 = Low Pain, 10 = Unbearable Pain</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="10" align="left" valign="middle">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                        <td align="center" valign="middle">1</td>
                                                        <td align="center" valign="middle">2</td>
                                                        <td align="center" valign="middle">3</td>
                                                        <td align="center" valign="middle">4</td>
                                                        <td align="center" valign="middle">5</td>
                                                        <td align="center" valign="middle">6</td>
                                                        <td align="center" valign="middle">7</td>
                                                        <td align="center" valign="middle">8</td>
                                                        <td align="center" valign="middle">9</td>
                                                        <td align="center" valign="middle">10</td>
                                                </tr>
                                                <tr>
                                                        <td align="center" valign="middle"><input id="painscale_1" type="radio" name="painscale" value="1" onClick="App.wizard.setAsValid(true)"></td>
                                                        <td align="center" valign="middle"><input id="painscale_2" type="radio" name="painscale" value="2" onClick="App.wizard.setAsValid(true)"></td>
                                                        <td align="center" valign="middle"><input id="painscale_3" type="radio" name="painscale" value="3" onClick="App.wizard.setAsValid(true)"></td>
                                                        <td align="center" valign="middle"><input id="painscale_4" type="radio" name="painscale" value="4" onClick="App.wizard.setAsValid(true)"></td>
                                                        <td align="center" valign="middle"><input id="painscale_5" type="radio" name="painscale" value="5" onClick="App.wizard.setAsValid(true)"></td>
                                                        <td align="center" valign="middle"><input id="painscale_6" type="radio" name="painscale" value="6" onClick="App.wizard.setAsValid(true)"></td>
                                                        <td align="center" valign="middle"><input id="painscale_7" type="radio" name="painscale" value="7" onClick="App.wizard.setAsValid(true)"></td>
                                                        <td align="center" valign="middle"><input id="painscale_8" type="radio" name="painscale" value="8" onClick="App.wizard.setAsValid(true)"></td>
                                                        <td align="center" valign="middle"><input id="painscale_9" type="radio" name="painscale" value="9" onClick="App.wizard.setAsValid(true)"></td>
                                                        <td align="center" valign="middle"><input id="painscale_10" type="radio" name="painscale" value="10" onClick="App.wizard.setAsValid(true)"></td>
                                                </tr>
                                            </table>
                                        </center></td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <!-- Oswestery End -->
            </div>
            <div class="modal-footer">
                <div class="wizard">
                    <div class="clearfix"></div>
                    <div class="actions" style="">
                        <button id="OswesteryPreviousBtn" type="button" class="btn btn-prev" style="float:left;"> <i class="fa fa-arrow-left"></i> Prev</button>
                        <button id="OswesteryNextBtn" type="button" class="btn btn-success btn-next" data-last="Finish" style="float:right;">Next <i class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content --> 
    </div><!-- /.modal-dialog --> 
</div><!-- /.modal --> 


<!-- ********************* Oswestery ********************* -->
<div class="modal fade" id="R4CSurveyModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ready for Change Survey</h4>
            </div>
            <div class="modal-body" style="min-height:280px; background-color:#fafafa;"> 
                <!-- Ready for Change Survey  -->

                <form class="form-horizontal" style="min-height:260px" id="R4CSurvey-form">
                    <div id="R4CSurveyWiz" style="padding:2px 12px;">
                            <div class="step">
                                    <p>This survey has been designed to give your Provider 
                                            information as to how your back pain has affected your ability 
                                            to manage in everyday life.</p>
                                    <p>Please answer every question that best describes your condition 
                                            today. <strong>We realize that you may feel that two of the statements 
                                            may describe your condition, but please select only the 
                                            description which most closely describes your current condition.</strong></p>
                                    <center>
                                            <a class="btn btn-primary" href="#/ok" onClick="App.wizard.setAsValid(true)">OK</a>
                                    </center>
                            </div>
                            <!-- Start Step 1 -->
                            <div class="step">
                                    <fieldset class="col-sm-12">
                                            <div class="form-group">
                                                    <table class="second-tab survey-table" align="center" border="0" cellpadding="0" cellspacing="0" style="padding-left: 8px;width:100%">
                                                            <tr>
                                                                    <td valign="top" colspan="2"><h3  class="space-heading2">1) Understanding Your Goals</h3>
                                                                            <p>What are the improvements you would like to experience regarding your back pain by 
                                                                                    participating in this program? Please select all that apply.</p></td>
                                                            </tr>
                                                            <tr>
                                                                    <td valign="top" width="24" class="spacer" style="padding-right: 8px;"><input name="goals" id="goals_1" value="1" type="checkbox" onClick="App.wizard.setAsValid(true)"></td>
                                                                    <td valign="top" class="pad-bottom"><label for="goals_1">Choice Text Option</label></td>
                                                            </tr>
                                                            <tr>
                                                                    <td valign="top" width="24" class="spacer" style="padding-right: 8px;"><input name="goals" id="goals_2" value="2" type="checkbox" onClick="App.wizard.setAsValid(true)"></td>
                                                                    <td valign="top" class="pad-bottom"><label for="goals_2">Choice Text Option</label></td>
                                                            </tr>
                                                    </table>
                                            </div>
                                    </fieldset>
                            </div>
                            <!-- End Step 1 --> 

                            <!-- Start Step 2 -->
                            <div class="step">
                                    <fieldset class="col-sm-12">
                                            <div class="form-group">
                                                    <table class="second-tab survey-table" align="center" border="0" cellpadding="0" cellspacing="0" style="padding-left: 8px;width:100%">
                                                            <tr>
                                                                    <td valign="top" colspan="2"><h3  class="space-heading2">2) Understanding Your Potential Barriers</h3>
                                                                            <p>What might be difficult about participating in this program? Please choose the answer that applies most to your condition. </p></td>
                                                            </tr>
                                                            <tr>
                                                                    <td valign="top" width="24" class="spacer" style="padding-right: 8px;"><input name="barriers" id="barriers_1" value="1" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                                                    <td valign="top" class="pad-bottom"><label for="barriers_1"> Choice Text Option</label></td>
                                                            </tr>
                                                            <tr>
                                                                    <td valign="top" width="24" class="spacer" style="padding-right: 8px;"><input name="barriers" id="barriers_2" value="2" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                                                    <td valign="top" class="pad-bottom"><label for="barriers_2"> Choice Text Option</label></td>
                                                            </tr>
                                                    </table>
                                            </div>
                                    </fieldset>
                            </div>
                            <!-- End Step 2 --> 

                            <!-- Start Step 3 -->
                            <div class="step">
                                    <fieldset class="col-sm-12">
                                            <div class="form-group">
                                                    <table class="second-tab survey-table" align="center" border="0" cellpadding="0" cellspacing="0" style="padding-left: 8px;width:100%">
                                                            <tr>
                                                                    <td valign="top" colspan="2"><h3  class="space-heading2">3) Understanding Your Strengths </h3>
                                                                            <p>What are your strengths that will help you succeed in this program? Please select all that apply. </p></td>
                                                            </tr>
                                                            <tr>
                                                                    <td valign="top" width="24" class="spacer" style="padding-right: 8px;"><input name="strengths" id="strengths_1" value="1" type="checkbox" onClick="App.wizard.setAsValid(true)"></td>
                                                                    <td valign="top" class="pad-bottom"><label for="strengths_1"> Choice Text Option</label></td>
                                                            </tr>
                                                            <tr>
                                                                    <td valign="top" width="24" class="spacer" style="padding-right: 8px;"><input name="strengths" id="strengths_2" value="2" type="checkbox" onClick="App.wizard.setAsValid(true)"></td>
                                                                    <td valign="top" class="pad-bottom"><label for="strengths_2"> Choice Text Option</label></td>
                                                            </tr>
                                                    </table>
                                            </div>
                                    </fieldset>
                            </div>
                            <!-- End Step 3 --> 

                            <!-- Start Step 4 -->
                            <div class="step">
                                    <fieldset class="col-sm-12">
                                            <div class="form-group">
                                                    <table class="second-tab survey-table" align="center" border="0" cellpadding="0" cellspacing="0" style="padding-left: 8px;width:100%">
                                                            <tr>
                                                                    <td valign="top" colspan="2"><h3  class="space-heading2">4) Understanding Your Readiness For Change </h3>
                                                                            <p>Please select the answer that describes where you are currently in the management of your low back pain. Please select one answer. </p></td>
                                                            </tr>
                                                            <tr>
                                                                    <td valign="top" width="24" class="spacer" style="padding-right: 8px;"><input name="readinesschange" id="readinesschange_1" value="1" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                                                    <td valign="top" class="pad-bottom"><label for="readinesschange_1"> Choice Text Option</label></td>
                                                            </tr>
                                                            <tr>
                                                                    <td valign="top" width="24" class="spacer" style="padding-right: 8px;"><input name="readinesschange" id="readinesschange_2" value="2" type="radio" onClick="App.wizard.setAsValid(true)"></td>
                                                                    <td valign="top" class="pad-bottom"><label for="readinesschange_2"> Choice Text Option</label></td>
                                                            </tr>
                                                    </table>
                                            </div>
                                    </fieldset>
                            </div>
                            <!-- End Step 4 --> 

                            <!-- Start Step 5 -->
                            <div class="step">
                                    <fieldset class="col-sm-12">
                                            <div class="form-group">
                                                    <table class="second-tab survey-table" align="center" border="0" cellpadding="0" cellspacing="0" style="padding-left: 8px;width:100%">
                                                            <tr>
                                                                    <td valign="top" colspan="2"><h3  class="space-heading2">5) Understanding Your Quality of Life </h3>
                                                                            <p>What might be different about your life if you have relief from low back pain?  Please select all that apply. </p></td>
                                                            </tr>
                                                            <tr>
                                                                    <td valign="top" width="24" class="spacer" style="padding-right: 8px;"><input name="quality" id="quality_1" value="1" type="checkbox" onClick="App.wizard.setAsValid(true)"></td>
                                                                    <td valign="top" class="pad-bottom"><label for="quality_1"> Choice Text Option</label></td>
                                                            </tr>
                                                            <tr>
                                                                    <td valign="top" width="24" class="spacer" style="padding-right: 8px;"><input name="quality" id="quality_2" value="2" type="checkbox" onClick="App.wizard.setAsValid(true)"></td>
                                                                    <td valign="top" class="pad-bottom"><label for="quality_2"> Choice Text Option</label></td>
                                                            </tr>
                                                    </table>
                                            </div>
                                    </fieldset>
                            </div>
                            <!-- End Step 5 --> 

                    </div>
                </form>
                
                <!-- Ready for Change Survey End -->
            </div>
            <div class="modal-footer">
                <div class="wizard">
                    <div class="clearfix"></div>
                    <div class="actions" style="">
                        <button id="R4CSurveyPreviousBtn" type="button" class="btn btn-prev" style="float:left;"> <i class="fa fa-arrow-left"></i> Prev</button>
                        <button id="R4CSurveyNextBtn" type="button" class="btn btn-success btn-next" data-last="Finish" style="float:right;">Next <i class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content --> 
    </div><!-- /.modal-dialog --> 
</div><!-- /.modal --> 


<!-- ********************* Tip 1 ********************* -->
<div class="modal fade" id="Tip1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="tip1Title">Tip: Videos &amp; Articles</h4>
            </div>
            <div class="modal-body">
                <div style="min-height:100px">
                    <h3>Accessing Videos &amp; Articles</h3>
                    <p>Every day you’ll be given videos to watch (and exercises to do) and articles to read. The more you stay engaged with your videos and articles, the sooner you’ll have relief from your low back pain You can access your videos and articles from your Daily Activity Timeline or you can also access them from your “My Videos” or “My Articles” pages.</p>
                </div>
                <div class="clearfix"></div>
                <hr>
                <div class="actions" style="">
                    <button type="button" class="btn btn-success btn-next" data-last="Finish" style="float:right;" onClick="$('#Tip1').modal('hide'); App.openTip('Tip2')">Next Tip <i class="fa fa-arrow-right"></i></button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- /.modal-content --> 

    </div>
    <!-- /.modal-dialog --> 

</div>
<!-- /.modal --> 

<!-- ********************* Tip 2 ********************* -->

<div class="modal fade" id="Tip2">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="tip2Title">Tip: Goals</h4>
            </div>
            <div class="modal-body">
                <div style="min-height:100px">
                    <h3>Importants of Setting Goals</h3>
                    <p>Research clearly shows that when people set goals and track the achievement of those goals, they achieve a greater percentage of them. Please take the time to create some achievable near term and longer term goals. </p>
                    <p>Examples? Walk a mile, start playing golf again, sit for 30 minutes without pain...get the picture. Start with easy to achieve goals and progress to more challenging ones over time. Don’t forget to cross them of when you've achieved one!</p>
                </div>
                <div class="clearfix"></div>
                <hr>
                <div class="actions" style="">
                    <button type="button" class="btn btn-success btn-next" data-last="Finish" style="float:right;" onClick="$('#Tip2').modal('hide'); App.openTip('Tip3')">Next Tip <i class="fa fa-arrow-right"></i></button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- /.modal-content --> 

    </div>
    <!-- /.modal-dialog --> 

</div>
<!-- /.modal --> 

<!-- ********************* Tip 3 ********************* -->

<div class="modal fade" id="Tip3">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="tip3Title">Tip: Completing Activities</h4>
            </div>
            <div class="modal-body">
                <div style="min-height:100px">
                    <h3>Daily Activities</h3>
                    <p>Everyday you’ll be given new videos to watch and articles to read. We call these “activities” because they represent the key ways you’ll participate in the Telespine program. Make sure you do your activities every day if you can (or at a minimum, 3x a week).</p>
                    <p>By watching the videos and doing the associated exercises and reading the lifestyle and workplace articles, you’ll put yourself in the best position to achieve near term and lasting low back pain relief.</p>
                </div>
                <div class="clearfix"></div>
                <hr>
                <div class="actions" style="">
                    <button type="button" class="btn btn-success " data-last="Finish" style="float:right;" onClick="$('#Tip3').modal('hide')">Close</button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div><!-- /.modal-content --> 
    </div><!-- /.modal-dialog --> 
</div><!-- /.modal --> 

<!-- ********************* Mobile Timeline ********************* -->
<div class="modal fade" id="week-day-view">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="WeekActivitiesTitle">Activities for the Week</h4>
            </div>
            <div class="modal-body" style="min-height:100px" id="WeekActivitiesContent">
                <!--<p>Your future Telespine activities will be shown here.</p>-->
                <div class="todo" style="border-radius:8px">
                    <ul id="dailyActivitiesWeek1" class="viewWeeksOverlayDisplay"></ul>
                    <ul id="dailyActivitiesWeek2" class="viewWeeksOverlayDisplay"></ul>
                    <ul id="dailyActivitiesWeek3" class="viewWeeksOverlayDisplay"></ul>
                    <ul id="dailyActivitiesWeek4" class="viewWeeksOverlayDisplay"></ul>
                    <ul id="dailyActivitiesWeek5" class="viewWeeksOverlayDisplay"></ul>
                    <ul id="dailyActivitiesWeek6" class="viewWeeksOverlayDisplay"></ul>
                    <ul id="dailyActivitiesWeek7" class="viewWeeksOverlayDisplay"></ul>
                    <ul id="dailyActivitiesWeek8" class="viewWeeksOverlayDisplay"></ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content --> 
    </div><!-- /.modal-dialog --> 
</div><!-- /.modal --> 

<!-- ********************* General Info ********************* -->
<div class="modal fade" id="General">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="generalTitle">Title</h4>
            </div>
            <div class="modal-body">
                <div style="min-height:200px">
                    <h3 id="generalSubtitle"></h3>
                    <div id="generalContent"></div>
                </div>
                <div class="clearfix"></div>
                <hr>
                <div class="actions" style="">
                    <button type="button" class="btn btn-success" data-last="Finish" style="float:right;" onClick="$('#General').modal('hide');">Close</button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog --> 
</div><!-- /.modal -->

<!-- ********************* ie8message ********************* -->

<div class="modal fade" id="ie8message">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ie8messagetitle">INFORMATION</h4>
            </div>
            <div class="modal-body">
                <div style="min-height:100px">
                <h3>Telespine is web-based software that runs in a web-browser. We support the following web-browsers running in Windows or Mac OS X:</h3>
                    <p>Google Chrome v.32 and higher</p>
                    <p>Mozilla Firefox v.29 and higher</p>
                    <p>Apple Safari v.6.1 and higher</p>
                    <p>Microsoft Internet Explorer v.9 and higher</p>
                    <p></p>
                    <p>Please note: if you are not using one of the browser versions listed above, Telespine may not work properly.</p>
                </div>
                <div class="clearfix"></div>
                <hr>
                <div class="actions" style="">
                    <button type="button" class="btn btn-success " data-last="Finish" style="float:right;" onClick="$('#ie8message').modal('hide')">Next</button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div><!-- /.modal-content --> 
    </div><!-- /.modal-dialog --> 
</div><!-- /.modal --> 

<div class="clearfix"></div>
<footer style="border-radius:0px 0px 8px 8px" class="accent-dark">
    <div class="col-lg-5 col-md-4 col-sm-12">
        <p style="font-size:11px;text-align:justify;font-weight:200;letter-spacing:2px;color:#ddd;">Telespine a web-based telehealth program for low back pain sufferers. It was developed to provide people with affordable, effective, and convenient care. Telespine delivers equal or better outcomes than traditional, office-based care at a significantly reduced cost and risk.</p>
        <br>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-3 m-center"><span class="title">Help and Support</span><br>
        <a href="index.php?action=faqs">Frequently Asked Questions</a><br>
        <a href="mailto:support@telespine.com">Email Technical Support</a><br>
		 <a href="#" onClick="$('#MedicalDisclaimer').modal('show');">Medical Disclaimer</a><br>
		
        <br>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-3 m-center"><span class="title">Telespine</span><br>
        <a href="index.php?action=patientaccount">Account</a><br>
        <a href="index.php?action=patientdashboard">Home</a><br>
        <a href="index.php?action=patientvideos">Videos</a><br>
        <a href="index.php?action=patientarticles">Articles</a><br>
        <br>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 m-center"><span class="title">Telespine, Inc.</span><br>
        3020 Carbon Place, Suite 204<br>
        Boulder Colorado, 80302<br>
        <br>
    </div>
    <div class="col-lg-5 col-md-4 col-sm-12 m-center">
        <p style="text-align:left;float:left; ;margin-bottom:10px;"> Copyright &copy; 2010-2014 TeleSpine, and affiliates. All rights reserved. <a href="http://creativeslave.com" style="color:rgba(255,255,255,0.7)">Design by Creative Slave.</a> Powered by TeleSpine.</p>
    </div>
    <div class="clearfix"></div>
</footer>

<!-- start: JavaScript--> 
<!--[if !IE]>--><script src="assets/js/jquery-2.0.3.min.js"></script><!--<![endif]--> 
<!--[if IE]><script src="assets/js/jquery-1.10.2.min.js"></script><![endif]--> 
<!--[if !IE]>--><script>window.jQuery || document.write("<script src='assets/js/jquery-2.0.3.min.js'>" + "<" + "/script>");</script><!--<![endif]--> 
<!--[if IE]>
        <script>
        window.jQuery || document.write("<script src='assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]--> 
<script src="assets/js/jquery-migrate-1.2.1.min.js"></script> 
<!--<script src="assets/js/bootstrap.min.js"></script> -->


<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="assets/js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>


<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script> 
<script src="assets/js/jquery-ui-1.10.3.custom.min.js"></script> 
<!-- Mobile Interface Control Enhancement [touch-punch]-->
<script src="assets/js/jquery.ui.touch-punch.min.js"></script>
<script>
    var navUp = true;
    var toggleNav = function(event) {
        try {
            event.preventDefault();
        } catch (er) {
        }
        if (navUp) {
            $('#topMobileNav').slideDown(800);
        } else {
            $('#topMobileNav').slideUp(300);
        }
        navUp = !navUp;
    }
    $(document).ready(function(event) {
        $("#mobileMenu").on("click", function(event) {
            toggleNav();
        });
    });
    
</script>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-KLJ4LL"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KLJ4LL');</script>
<!-- End Google Tag Manager -->