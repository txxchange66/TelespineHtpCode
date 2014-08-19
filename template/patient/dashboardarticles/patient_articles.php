<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>TeleSpine - Articles</title>

        <!-- meta and head files -->
        <!meta_head>
        <!-- meta and head files end -->

        <style>
            h3.section{margin:0px;cursor:pointer;}
        </style>
		
		<script>
		/*
		var info = getAcrobatInfo();
		
		if(info.acrobat!='installed'){
		
		alert('Not Installed');
		}else{
		
		alert('Installed');
		}
*/
//alert("browser=="+info.browser);
//alert("info.acrobat==" + info.acrobat );
//alert("acrobatVersion==" + info.acrobatVersion);

		</script>
		
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
                            <h2 class="m-center"><span style="font-size:28px;color:#777;">Your Articles</span>
                                <div class="date hidden-xs" style="text-shadow:1px 1px 1px #fff;margin-bottom:18px;color:#bbb;">
                                    <span style="color:orange;font-weight:300;font-size:38px;position:relative;bottom:16px;float:right;">Today is Day <span class="getDaysSinceStart"><!currentday></span></span><br><span style="border-top:dotted 1px #ccc;padding-top:4px;"><!currentDate></span>
                                </div>
                            </h2>
                        </div><!--/intro header-->
                        <div class="hidden-xs" style="margin-bottom:24px;"></div>

                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12"> 
                                <!-- Menu -->
				<div class="box">
					<div class="box-header">
						<h2>Weeks</h2>
						<div class="box-icon"> <a href="javascript:;" class="btn-minimize"><i class="fa fa-chevron-up"></i></a> </div>
					</div>
					<div class="box-content">
						<p style="padding:8px 10px; font-weight:300;">
						Here are articles available to you up to today. 
						Click on the week to see the articles for that week.</p>
					
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
				</div> <!--/box-->
                            </div>
                            <!--/col-->

                            <div class="col-lg-8 col-md-8 col-sm-12">
				<div class="box">
					<div class="box-header">
						<h2><i class="fa fa-book"></i>Day <span id="dayV"><!currentday></span>: Articles</h2>
						<div class="box-icon"> <a href="#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a> </div>
					</div>
					<div class="box-content article" style="padding:2px 32px;min-height:400px"> <br>
					<p>Simply click on the article links below to learn lifestyle, exercise, and workplace best practices. By incorporating what you learn into your daily routine, you maximize the benefits of the program and help yourself achieve lasting low back pain relief. </p>
						<div class="clearfix"></div>
						<div class="todo" style="border-radius:8px">
							<ul id="dayVd">
							
							<!--
								<li> <span class="todo-actions"> <i class="fa fa-external-link"></i> </span> 
									<span class="desc">
										<strong><a href="javascript:App.openArticleOverlay('Article Name', 'http://stage.txxchange.com/index.php?action=downloadfileArticle&id=98193');">
											Day: 15 - Article Title (Short Desc) PDF</a></strong>
									</span>  
								</li>
								<div class="clearfix"></div>
								<li> <span class="todo-actions"> <i class="fa fa-external-link"></i> </span> 
									<span class="desc">
										<strong><a href="javascript:App.openArticleOverlay('Article Name', 'http://stage.txxchange.com/index.php?action=downloadfileArticle&id=98193');">
											Day: 17 - Article Title (Short Desc) PDF</a></strong>
									</span>  
								</li>
								<div class="clearfix"></div>
								<li> <span class="todo-actions"> <i class="fa fa-external-link"></i> </span> 
									<span class="desc">
										<strong><a href="javascript:App.openArticleOverlay('Article Name', 'http://stage.txxchange.com/index.php?action=downloadfileArticle&id=98193');">
											Day: 18 - Article Title (Short Desc) PDF</a></strong>
									</span>  
								</li>
								<div class="clearfix"></div>
								<li> <span class="todo-actions"> <i class="fa fa-external-link"></i> </span> 
									<span class="desc">
										<strong><a href="javascript:App.openArticleOverlay('Article Name', 'http://stage.txxchange.com/index.php?action=downloadfileArticle&id=98193');">
											Day: 20 - Article Title (Short Desc) PDF</a></strong>
									</span>  
								</li>
								<div class="clearfix"></div>
								-->
								
								<!right_section>
							</ul>
						</div>
						
						<div class="clearfix"></div>
						<br>
					</div>
					<div class="clearfix"></div>
				</div>
				<!--/col--> 
                            </div> <!-- end: column -->
                        </div> <!-- end: row -->
                    </div>  <!-- end: content --> 


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
        $(document).ready(function(e) {
                var timeline;
                App.init({configuration:{startDate:"<!startdate>",logging:true},components:[]});
        });
        </script> 
        <script src="assets/js/core.min.js"></script>
    </body>
</html>