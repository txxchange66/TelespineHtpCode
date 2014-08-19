<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en" ng-app>
    <head>
        <meta charset="utf-8">
        <title>TeleSpine</title>
        
        <!-- meta and head files -->
        <!meta_head>
        <!-- meta and head files end -->

    </head>
    
    <body>
        <div class="page" style="">
            
            <!-- start: Header -->
            <!header>
            <!-- end: Header -->
           
            
            
           
            <div class="container">
                <div class="row">
                    <!-- start: Content -->
                     <div id="content" class="col-lg-12 col-sm-12 full">
                         
                           <div class="intro">
                            <h2 class="m-center">
                                <div style="font-size:28px;line-height:26px;color:#777;text-shadow:0px 1px 1px #ffffff;margin-top:-8px;">Welcome <span class="userName"></span></div>
                                <div class="hidden-sm hidden-md hidden-lg m-center mobile-day">
                                    <center>
                                            <div style="display:block; color:orange;font-weight:300 !important;font-size:32px;">
                                                &mdash; Day <span class="getDaysSinceStart"></span> &mdash;
                                            </div>
                                            <div style="padding-top:12px;"><!currentDate></div>
                                    </center>
                                </div>
                                <div class="date hidden-xs" style="text-shadow:1px 1px 1px #fff;margin-bottom:18px;color:#bbb;">
                                    <span style="color:orange;font-weight:300;font-size:38px;position:relative;bottom:16px;float:right;border-bottom:dotted 1px #ccc;padding-bottom:9px;">Today is Day <span class="getDaysSinceStart"></span></span><br><span style=""><!currentDate></span>
                                </div>
                            </h2>
                        </div>
                            <div id="col_0">
                           <div id="col_0">
    <div style="position:relative; padding:0% 2%"></div> 
    
    <div class="box" id="today" style="margin-top:69px;">
      <div class="box-header" style="width:100%"><h2><i class="fa fa-coffee"></i>Daily Activity Timeline </h2> 
                                        <div class="box-icon pull-right"><a href="#" class="btn-setting" onClick="App.showHelp('timeline')"><i class="fa fa-info"></i></a> 
                                                               <a href="#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a> 
                                        </div> 
                                </div> 
          <div class="box-content" style="border-radius:8px; min-height:100px">
                                    <div class="container tl-nav">
                                        <div id="Weekly-timeline" class="visible-xs"></div>
                                        <div class=" visible-sm visible-md visible-lg">
                                            <div class="row" style="margin-top:12px;">
                                                <div class="col-xs-2 col-sm-1">
                                                    <div id="tl-nav-previous" class="tl-nav-arrow"><i class="fa fa-angle-left"></i></div>
                                                </div>
                        <div class="col-xs-8 col-sm-10 weeks-container"> 
                                                    <div style="overflow: hidden;" id="weeks">
                                                        <table style="width: 100%;">
                                    <tbody> 
                                                            <tr>
                                                                <td>
                                                                    <div class="tl-nav-bubbles" id="week1">
                                                    <div class="nav-week"> 
                                                        <div class="nav-bubble" id="day1">1</div> 
                                                        <div class="nav-bubble" id="day2">2</div> 
                                                        <div class="nav-bubble" id="day3">3</div> 
                                                        <div class="nav-bubble" id="day4">4</div> 
                                                        <div class="nav-bubble" id="day5">5</div> 
                                                        <div class="nav-bubble" id="day6">6</div> 
                                                        <div class="nav-bubble" id="day7">7</div> 
                                                                            <div class="clearfix"></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="tl-nav-bubbles" id="week2">
                                                                        <div class="nav-week">
                                                        <div class="nav-bubble" id="day8">8</div> 
                                                        <div class="nav-bubble" id="day9">9</div> 
                                                        <div class="nav-bubble" id="day10">10</div> 
                                                        <div class="nav-bubble" id="day11">11</div> 
                                                        <div class="nav-bubble" id="day12">12</div> 
                                                        <div class="nav-bubble" id="day13">13</div> 
                                                        <div class="nav-bubble" id="day14">14</div> 
                                                                            <div class="clearfix"></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="tl-nav-bubbles" id="week3">
                                                    <div class="nav-week"> 
                                                        <div class="nav-bubble" id="day15">15</div> 
                                                        <div class="nav-bubble" id="day16">16</div> 
                                                        <div class="nav-bubble" id="day17">17</div> 
                                                        <div class="nav-bubble" id="day18">18</div> 
                                                        <div class="nav-bubble" id="day19">19</div> 
                                                        <div class="nav-bubble" id="day20">20</div> 
                                                        <div class="nav-bubble" id="day21">21</div> 
                                                                            <div class="clearfix"></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="tl-nav-bubbles" id="week4">
                                                                        <div class="nav-week">
                                                        <div class="nav-bubble" id="day22">22</div> 
                                                        <div class="nav-bubble" id="day23">23</div> 
                                                        <div class="nav-bubble" id="day24">24</div> 
                                                        <div class="nav-bubble" id="day25">25</div> 
                                                        <div class="nav-bubble" id="day26">26</div> 
                                                        <div class="nav-bubble" id="day27">27</div> 
                                                        <div class="nav-bubble" id="day28">28</div> 
                                                                            <div class="clearfix"></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="tl-nav-bubbles" id="week5">
                                                                        <div class="nav-week">
                                                        <div class="nav-bubble" id="day29">29</div> 
                                                        <div class="nav-bubble" id="day30">30</div> 
                                                        <div class="nav-bubble" id="day31">31</div> 
                                                        <div class="nav-bubble" id="day32">32</div> 
                                                        <div class="nav-bubble" id="day33">33</div> 
                                                        <div class="nav-bubble" id="day34">34</div> 
                                                        <div class="nav-bubble" id="day35">35</div> 
                                                                            <div class="clearfix"></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="tl-nav-bubbles" id="week6">
                                                                        <div class="nav-week">
                                                        <div class="nav-bubble" id="day36">36</div> 
                                                        <div class="nav-bubble" id="day37">37</div> 
                                                        <div class="nav-bubble" id="day38">38</div> 
                                                        <div class="nav-bubble" id="day39">39</div> 
                                                        <div class="nav-bubble" id="day40">40</div> 
                                                        <div class="nav-bubble" id="day41">41</div> 
                                                        <div class="nav-bubble" id="day42">42</div> 
                                                                            <div class="clearfix"></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="tl-nav-bubbles" id="week7">
                                                                        <div class="nav-week">
                                                        <div class="nav-bubble" id="day43">43</div> 
                                                        <div class="nav-bubble" id="day44">44</div> 
                                                        <div class="nav-bubble" id="day45">45</div> 
                                                        <div class="nav-bubble" id="day46">46</div> 
                                                        <div class="nav-bubble" id="day47">47</div> 
                                                        <div class="nav-bubble" id="day48">48</div> 
                                                        <div class="nav-bubble" id="day49">49</div> 
                                                                            <div class="clearfix"></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="tl-nav-bubbles" id="week8">
                                                                        <div class="nav-week">
                                                        <div class="nav-bubble" id="day50">50</div> 
                                                        <div class="nav-bubble" id="day51">51</div> 
                                                        <div class="nav-bubble" id="day52">52</div> 
                                                        <div class="nav-bubble" id="day53">53</div> 
                                                        <div class="nav-bubble" id="day54">54</div> 
                                                        <div class="nav-bubble" id="day55">55</div> 
                                                        <div class="nav-bubble" id="day56">56</div> 
                                                                            <div class="clearfix"></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                            <td> 
                                                <div style="width:1000px;"><!-- Allows scrolling week to placement--></div> 
                                            </td> 
                                                            </tr>
                                    </tbody> 
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-xs-2 col-sm-1">
                            <div id="tl-nav-next" class="tl-nav-arrow" style="margin:0 20%;">
                                <i class="fa fa-angle-right"></i>
                                                </div>
                                            </div>
                                        </div>
                </div> 
                                        <div class="clearfix"></div>
                                        <div>
                                            <div class="">
                                                <div class="info-box">
                                                    <div class="">
                                                        <div class="col-sm-6 col-md-5">
                                                            <h2 style="font-weight:300;font-size:22px;margin-bottom:18px !important;">
                                        <span class="currentDay">Day </span> - Daily Activities </h2> 
                                    <div class="nav-content" id="dailyActivities2"> 
                                        <div class="daily-item"> 
                                            <div class="col-xs-1 col-md-1">
                                                <i class="ico fa fa-exclamation-circle"></i> 
                                                        </div>
                                            <div class="col-xs-10 col-md-11">Your future Telespine activities will be shown here. </div> 
                                        </div> 
                                        <div class="clearfix" style="margin-bottom:10px;"></div> 
                                    </div> 
                                </div> 
                                                        <div class="col-sm-6 col-md-7">
                                                            <div class="nav-content">
                                                                <div class="row">
                                            <div id='separator' class="col-sm-1 col-md-1 hidden-sm hidden-xs" style="border-left:1px dotted rgba(0,172,237,1.0)!important;min-height:160px;margin-top:24px; margin-bottom:24px;"></div> 
                                            <div class="col-sm-11"><h2 style="font-weight:300;font-size:20px;margin-bottom:18px !important;"> 
                                                    <span class="currentDay">Day #</span> - Messages </h2> 
                                                                        <div id="getDayMessage"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                     </div>
        
    </div>
    
    <div class="clearfix"></div>
                           </div>
                  <div class="clearfix"></div>       
                     </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-6" id="col_1"></div><!--/col-->
                            <div class="col-lg-4 col-md-4 col-sm-6" id="col_2"></div><!--/col-->
                            <div class="col-lg-4 col-md-4 col-sm-6" id="col_3"></div><!--/col-->
                        </div><!--/row--> 
                     </div>    
                        <div class="clearfix"></div>
                    <!-- start: footer -->
                    <!footer>
                    <!-- end: footer -->
                    
                    <div class="clearfix"></div> 
                    
               <!-- end: row -->
           <!-- end: container -->
            
            <!-- start: JavaScript-->
            <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
            <script src="assets/js/bootstrap.min.js"></script>
            <script src="assets/js/jquery-ui-1.10.3.custom.min.js"></script>
            <!-- page scripts -->
            
        <!--/page-->
                </div>
            </div>
        
        <div id="OverlayBG">
		<div class="box loading" id="loading">
                    <img src="assets/img/telespine-logo.png" alt="TeleSpine" style="width:100%;max-width:280px;padding:10px 10px;">
                    <div><img src="assets/img/loading-orange.gif" width="32" alt="TeleSpine" style="width:32px !important;"></div>
		</div>
	</div>
        
        <!-- :: Site Script :: --> 
        <script src="assets/js/jquery.sparkline.min.js"></script> 
	<!--[if lte IE 8]><script src="assets/js/excanvas.min.js"></script><![endif]--> 
	<script src="assets/js/jquery.autosize.min.js"></script> 
	<script src="assets/js/jquery.chosen.min.js"></script> 
	<script src="assets/js/respond.min.js"></script>

        <!-- :: Dashboard inline scripts :: -->
        <script src="assets/js/jquery.gritter.min.js"></script> 
	<script src="assets/js/jquery.knob.modified.min.js"></script> 
	<script src="assets/js/jquery.easy-pie-chart.min.js"></script> 
	<script src="assets/js/justgage.1.0.1.min.js"></script> 
	<!--[if lte IE 8]><script src="assets/js/excanvas.min.js"></script><![endif]--> 
	<script src="assets/js/timeline-custom-control.js"></script>
	<script src="assets/js/app.controllers.js?noChache"></script>
        
        <script type="text/javascript">
           /**
            * Application User Object 
            * This is expected to load externally, as through a script link. Use PHP to parse the data.
            * @App.user
            */
           App.user = {
                   name: {
                           first:"<!userName>", 
                           last: "<!userLastName>"
                   },
                   startDate:"<!startdate>", 
                   lastLogin:"<!lastlogin>",
                   currentDate:"<!currentdate>",
                   password:{
                           //activates the setting up of password i.e. first time password change
                           status:"<!passwordchangestatus>"
                   },
                   schedule:	 App.activities,
                   notification: {
                           status:"unread",
                           notifiedOn:"2014-02-12"
                   },
                   stats: {
                           //data for goals is being set in the patientgoals template file
                   },
                   pain: {
                           history: [<!painscorestring>],
                           lastSet:"<!lastpainlevelupdate>"
                   },
                   oswestery: {
                            // data is being set in the functionalscore template file
                           status: {
                                   survey1:"<!oswestrysurvey1status>",
                                   survey2:"<!oswestrysurvey2status>",
                                   survey3:"<!oswestrysurvey3status>",
                                   survey4:"<!oswestrysurvey4status>",
                           }
                   },
                   survey:{
                           /* Ready for Change */
                           status: "<!readyforchangesurveystatus>"
                   },
                   program:{
                           status:"Active"
                   }
           }

           App.user.activities = <!activities>;
        </script>

        <script>$(document).ready(function(e) {var timeline;App.init();});</script>
	<script src="assets/js/config.js?clearCacheNow=true&ready=true"></script>
	<script src="assets/js/core.min.js"></script>
	<script src="assets/js/inForm.js"></script>
        
	<!-- Mobile Interface Control Enhancement [touch-punch]--> 
	<script src="assets/js/jquery.ui.touch-punch.min.js"></script>
<script>;(function(a){if(typeof define==='function'&&define.amd){define(['jquery'],a)}else{a(jQuery)}}(function($){var j=$.scrollTo=function(a,b,c){return $(window).scrollTo(a,b,c)};j.defaults={axis:'xy',duration:parseFloat($.fn.jquery)>=1.3?0:1,limit:true};j.window=function(a){return $(window)._scrollable()};$.fn._scrollable=function(){return this.map(function(){var a=this,isWin=!a.nodeName||$.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!isWin)return a;var b=(a.contentWindow||a).document||a.ownerDocument||a;return/webkit/i.test(navigator.userAgent)||b.compatMode=='BackCompat'?b.body:b.documentElement})};$.fn.scrollTo=function(f,g,h){if(typeof g=='object'){h=g;g=0}if(typeof h=='function')h={onAfter:h};if(f=='max')f=9e9;h=$.extend({},j.defaults,h);g=g||h.duration;h.queue=h.queue&&h.axis.length>1;if(h.queue)g/=2;h.offset=both(h.offset);h.over=both(h.over);return this._scrollable().each(function(){if(f==null)return;var d=this,$elem=$(d),targ=f,toff,attr={},win=$elem.is('html,body');switch(typeof targ){case'number':case'string':if(/^([+-]=?)?\d+(\.\d+)?(px|%)?$/.test(targ)){targ=both(targ);break}targ=$(targ,this);if(!targ.length)return;case'object':if(targ.is||targ.style)toff=(targ=$(targ)).offset()}var e=$.isFunction(h.offset)&&h.offset(d,targ)||h.offset;$.each(h.axis.split(''),function(i,a){var b=a=='x'?'Left':'Top',pos=b.toLowerCase(),key='scroll'+b,old=d[key],max=j.max(d,a);if(toff){attr[key]=toff[pos]+(win?0:old-$elem.offset()[pos]);if(h.margin){attr[key]-=parseInt(targ.css('margin'+b))||0;attr[key]-=parseInt(targ.css('border'+b+'Width'))||0}attr[key]+=e[pos]||0;if(h.over[pos])attr[key]+=targ[a=='x'?'width':'height']()*h.over[pos]}else{var c=targ[pos];attr[key]=c.slice&&c.slice(-1)=='%'?parseFloat(c)/100*max:c}if(h.limit&&/^\d+$/.test(attr[key]))attr[key]=attr[key]<=0?0:Math.min(attr[key],max);if(!i&&h.queue){if(old!=attr[key])animate(h.onAfterFirst);delete attr[key]}});animate(h.onAfter);function animate(a){$elem.animate(attr,g,h.easing,a&&function(){a.call(this,targ,h)})}}).end()};j.max=function(a,b){var c=b=='x'?'Width':'Height',scroll='scroll'+c;if(!$(a).is('html,body'))return a[scroll]-$(a)[c.toLowerCase()]();var d='client'+c,html=a.ownerDocument.documentElement,body=a.ownerDocument.body;return Math.max(html[scroll],body[scroll])-Math.min(html[d],body[d])};function both(a){return $.isFunction(a)||typeof a=='object'?a:{top:a,left:a}};return j}));
$(document).ready(function () {
setNavDayStart();
        $("#tl-nav-previous").on("click ",function(){
if(currentWeek!=1){
currentWeek--;
scrollToWeek(currentWeek);
setWeekDay(currentWeek);
}
});
        $("#tl-nav-next").on("click ",function(){
if(currentWeek!=8){
currentWeek++;
scrollToWeek(currentWeek);
setWeekDay(currentWeek);
}
});
        $(".nav-bubble").on("click ",function(event){
App.console.log('.nav-bubble.click, .nav-bubble.touchstart');
currentDay = parseInt($(event.currentTarget).text());
setDayText(currentDay);
setActiveDayDisplay();
timeline.setTodaysActivities(currentDay);
currentWeek = getWeekFromDay(currentDay);
scrollToWeek(currentWeek);
setActiveWeekDisplay(currentWeek);
})
});
var currentDay = <!currentday>;
var currentWeek = <!currentweek>;
function setActiveDayDisplay(){
$(".nav-bubble").removeClass("active");
setTimeout(function(){
$(".nav-bubble").removeClass("active");
$("#day"+currentDay).addClass("active");	
},10);
}
    function setActiveWeekDisplay(){
$(".nav-week").removeClass("active");
        $("#week"+currentWeek).find(".nav-week").addClass("active");
        console.log("setActiveWeekDisplay() "+currentWeek);
}
function scrollToWeek(week){
setTimeout(function(){
App.console.log('scrollToWeek: '+currentWeek);
$("#weeks").scrollTo($('#week'+currentWeek),800);
},20);
}
function setWeekDay(week){
$(".nav-bubble").removeClass("active");
        setActiveWeekDisplay(currentWeek);
        /*$("#week"+currentWeek).find("div.nav-bubble:first-child").addClass("active");
currentDay = parseInt($("#week"+currentWeek).find("div.nav-bubble:first-child").text());*/
currentDay = ((currentWeek-1) * 7)+1;
$("#day"+currentDay).addClass("active");
setDayText(currentDay);
timeline.setTodaysActivities(currentDay);
}
function setNavDayStart(){
        console.log("setNavDayStart()");
currentDay = timeline.getDaysSinceStart();
console.log("Today is the "+ (currentDay) + " day of the program.");
$(".nav-bubble").removeClass("active");
currentWeek = getWeekFromDay(currentDay);
console.log("This start week is: "+currentWeek);
scrollToWeek(currentWeek);
setActiveWeekDisplay(currentWeek);
        /*$("#week"+currentWeek).find("div.nav-bubble").each(function(index, element) {
if($(this).text()==currentDay){$(this).addClass("active");}
});*/
$("#day"+currentDay).addClass("active");
timeline.setTodaysActivities(currentDay);
setDayText(currentDay);
}
function setDayText(currentDay){
var thisDay = "Day "+ currentDay;
var dayOfProgram = timeline.getDaysSinceStart();
console.log(dayOfProgram);
        if(currentDay==dayOfProgram){thisDay = "Today";}
$("span.currentDay").html(thisDay);	
App.getDayMessage(currentDay);
}
function getWeekFromDay(days){
        //console.log("getWeekFromDay("+days+")");
var week =0;
if(days<8){week=1;
} else if(days<=14){week=2;
} else if(days<=21){week=3;
} else if(days<=28){week=4;
} else if(days<=35){week=5;
} else if(days<=42){week=6;
} else if(days<=49){week=7;
} else{week=8;}

        //console.log(" > Return Week: "+week);
return week;
}
</script>
    </body>

</html>
