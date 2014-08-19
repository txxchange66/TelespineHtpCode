<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>TeleSpine - Videos</title>
        <!-- meta and head files -->
        <!meta_head>
        <!-- meta and head files end -->
        <style>
            h3.section{margin:0px;cursor:pointer;}
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
                    <div id="content" class="col-lg-12 col-md-12 col-xs-12 col-sm-12 full">
                        <div class="intro">
                            <h2 class="m-center"><span style="font-size:28px;color:#777;">Your Videos</span>
                                <div class="date hidden-xs" style="text-shadow:1px 1px 1px #fff;margin-bottom:18px;color:#bbb;">
                                    <span style="color:orange;font-weight:300;font-size:38px;position:relative;bottom:16px;float:right;">Today is Day <span class="getDaysSinceStart"><!currentday></span></span><br><span style="border-top:dotted 1px #ccc;padding-top:4px;"><!currentDate></span>
                                </div>
                            </h2>
                        </div><!--/intro header-->
                        <div class="hidden-xs" style="margin-bottom:24px;"></div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <!-- start: videos menu -->

				<!-- Menu -->
				<div class="box">
					<div class="box-header">
						<h2><i class="fa fa-check"></i>Weeks</h2>
						<div class="box-icon"> <a href="javascript:;" class="btn-minimize"><i class="fa fa-chevron-up"></i></a> </div>
					</div>
					<div class="box-content">
						<p style="padding:8px 10px; font-weight:300;">
						Here are videos available to you up to today. 
						Click on the week to see the videos for that week.</p>
					
						<div class="todo" style="border-radius:8px">
							<div>

                                                <h3 class="menu-week" onClick="$('#week-1').slideToggle();">Week 1</h3>
                                                
												<!left_menu_week1>

                                                <h3 class="menu-week" onClick="$('#week-2').slideToggle();">Week 2</h3>
                                               
												<!left_menu_week2>

                                                <h3 class="menu-week" onClick="$('#week-3').slideToggle();">Week 3</h3>
                                               
												<!left_menu_week3>

                                                <h3 class="menu-week" onClick="$('#week-4').slideToggle();">Week 4</h3>
                                              
												<!left_menu_week4>

                                                <h3 class="menu-week" onClick="$('#week-5').slideToggle();">Week 5</h3>
                                                
												<!left_menu_week5>

                                                <h3 class="menu-week" onClick="$('#week-6').slideToggle();">Week 6</h3>
                                               
												<!left_menu_week6>

                                                <h3 class="menu-week" onClick="$('#week-7').slideToggle();">Week 7</h3>
                                               
												<!left_menu_week7>

                                                <h3 class="menu-week" onClick="$('#week-8').slideToggle();">Week 8</h3>
                                              
												<!left_menu_week8>
												
                                           </div>
						</div>
					<div class="clearfix"></div><br>
					</div>
				</div><!--/box--> 
                                <!-- end: videos menu -->
                            </div><!--/col-->

                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h2><i class="fa fa-film"></i> Day <span id="dayV"><!currentday></span>: Videos</h2>
                                        <div class="box-icon"> 
                                            <a href="#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a> 
                                        </div>
                                    </div>
								
                                    <div class="box-content" style="padding:2px 32px;">
                                        <div class="row">
                                            <p>To watch a video, simply click on “play” button. Please note that we’ve numbered each video because they’re designed to be watched, and the exercises completed, in order. For example, Video 1 would be watched first, the associated exercises/stretches completed, and then Video 2 would be watched.</p><br>
												<div id="dayVd">
												<!right_section>
												</div>
												
                                         <!--    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                <div  class="video-container watched" title="Video Name" 
                                                      onClick="Dashboard.openMovieOverlay(this.title, $(this).attr('data-url'))"
                                                      data-url="http://htptest.txxchange.com/asset/images/treatment/558/video.mp4">
                                                </div>
                                                <div class="video-details">Day: 15 - Acute LBP<span>2 min 16 s</span></div>
                                            </div>
											
											
											
                                           <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div  class="video-container watched" title="Video Name" 
                                                      onClick="Dashboard.openMovieOverlay(this.title, $(this).attr('data-url'))"
                                                      data-url="http://htptest.txxchange.com/asset/images/treatment/558/video.mp4">
                                                </div>
                                                <div class="video-details">Day: 16 - Video Name <span>5 min 30 s</span></div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div  class="video-container watched" title="Video Name" 
                                                      onClick="Dashboard.openMovieOverlay(this.title, $(this).attr('data-url'))"
                                                      data-url="http://htptest.txxchange.com/asset/images/treatment/558/video.mp4">
                                                </div>
                                                <div class="video-details">Day: 17 - Video Name <span>5 min 30 s</span></div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div  class="video-container" title="Video Name" 
                                                      onClick="Dashboard.openMovieOverlay(this.title, $(this).attr('data-url'))"
                                                      data-url="http://htptest.txxchange.com/asset/images/treatment/558/video.mp4">
                                                </div>
                                                <div class="video-details">Day: 18 - Video Name <span>5 min 30 s</span></div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div  class="video-container" title="Video Name" 
                                                      onClick="Dashboard.openMovieOverlay(this.title, $(this).attr('data-url'))"
                                                      data-url="http://htptest.txxchange.com/asset/images/treatment/558/video.mp4">
                                                </div>
                                                <div class="video-details">Day: 20 - Video Name <span>5 min 30 s</span></div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div  class="video-container" title="Video Name" 
                                                      onClick="Dashboard.openMovieOverlay(this.title, $(this).attr('data-url'))"
                                                      data-url="http://htptest.txxchange.com/asset/images/treatment/558/video.mp4">
                                                </div>
                                                <div class="video-details">Day: 21 - Video Name <span>5 min 30 s</span></div>
                                            </div>-->
                                        </div>
                                        <br>
                                    </div>
                                </div>

                            </div> <!--/col--> 
                        </div> <!-- end: row -->
                    </div> <!-- end: content --> 



                    <!-- start: footer -->
                    <!footer>
                    <!-- end: footer -->

                </div> <!-- end: row -->
            </div> <!-- end: container -->

        </div> <!--/page-->
    
        <!-- inline scripts related to this page -->
        <script src="assets/js/timeline-custom-control.js"></script> 
        <script src="assets/js/app.controllers.js"></script> 
        <script type="text/javascript">
           /*
			$(document).ready(function(e) {
                var timeline;
                Dashboard.init({configuration: {startDate: "<!startdate>", logging: true}, components: []});
            });
			*/
			
			$(document).ready(function(e) {
				var timeline;
				App.init({configuration:{startDate:"<!startdate>",logging:true},components:[]});
			});
			
			
        </script>
        <script src="assets/js/core.min.js"></script>
    </body>
</html>
