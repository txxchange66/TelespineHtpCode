/* TeleSpine Custom Timeline Control */
/**
 * Build Interface Function
 * View Week navigation function
 */
function WeeklyTimeline(src){
	App.console.log("WeeklyTimeline called",1);
	this.TIMELINE_ID="Weekly-timeline";
	var WEEKS_ID="";
	this.init = function(){
		App.console.group("Weekly Timeline");
		
		this.getTimeline();
		App.console.groupEnd();
	}
	this.setDaysSinceStart = function(){
		$("span.getDaysSinceStart").html(this.getDaysSinceStart());
	}
	this.getDaysSinceStart = function(){
		if(App.user.startDate){
			var startDate = App.user.startDate;
			var today = App.user.currentDate;//new Date();
			var numOfDays = daysBetween(startDate,today);
			return numOfDays+1;
		}
		return 1;
	}
	this.getCurrentWeek = function(){
		var days = this.getDaysSinceStart();
		var week =0;
		if(days<=7){week=1;
		} else if(days<=14){week=2;
		} else if(days<=21){week=3;
		} else if(days<=28){week=4;
		} else if(days<=35){week=5;
		} else if(days<=42){week=6;
		} else if(days<=49){week=7;
		} else{week=8;}
		return week;
	}
	this.getCurrentWeekFromDay = function(days){
		var week =0;
		if(days<=7){week=1;
		} else if(days<=14){week=2;
		} else if(days<=21){week=3;
		} else if(days<=28){week=4;
		} else if(days<=35){week=5;
		} else if(days<=42){week=6;
		} else if(days<=49){week=7;
		} else{week=8;}
		return week;
	}
	this.getTimeline = function(src){
		this.setDaysSinceStart();
		TimelineBuilder.build(this.getDaysSinceStart(), this.getCurrentWeek());
		this.buildNavigation();
		this.buildWeekDays();
	}
	this.buildNavigation = function(){
		App.console.log(" > buildNavigation called. Current Week: "+this.getCurrentWeek(),2);
		var currentWeek = this.getCurrentWeek(); /*0-7*/
		var OPEN_TABLE = '<div class="hidden-lg hidden-md hidden-sm col-sm-12 col-xs-12 timeline-header">Your 8 Week Program</div><div class="clearfix"></div><div class="row week-routine"><table class="col-lg-12 col-sm-12 col-xs-12 col-xxs-12"><tr>';
		var CLOSE_TABLE = '</tr></table></div>';
		var weeks = '';
		var START_WEEK = '<td width="7%" class="hidden-xs"><div><div class="smallstat box round dark-grey falign"><div class="text-center hidden-xs hidden-sm size12">Begin</div><div class="text-center hidden-lg hidden-md size12"><li class="fa fa-circle"></li></div></div></div></td>';
		var END_WEEK = START_WEEK.replace("Begin", "Finish");
		var END_WEEK = END_WEEK.replace("falign", "text-right");
		weeks += START_WEEK;
		for(var w=0; w<8; w++){
			var thisWeek = w+1;
			var CURRENT = "past";
			var CHECKED_OFF="display:none;";
			var startDayeSame = this.getCurrentWeek()==Math.round(thisWeek);
			if(this.getCurrentWeek()==Math.round(thisWeek)){
				CURRENT = "present";
				CHECKED_OFF="visibility:hidden;";
			} else {
				CURRENT = "future";
				CHECKED_OFF="visibility:hidden;";
			}
			if((thisWeek)<this.getCurrentWeek()){
				CURRENT = "past";
				CHECKED_OFF="";
			} 
			var NEW_WEEK = '<td width="10.5%"><div><div class="box week-status-btn status-'+CURRENT+'-btn" onClick="viewWeek(\''+(thisWeek)+'\');" title="Click to open Week '+(thisWeek)+' Activities"><div class="color-black hidden-xs hidden-sm text-center">Week</div><div class="value m-pad text-center">'+(w+1)+'</div><li class="fa fa-check hidden-xs hidden-sm week-complete" style="position:absolute; right:16px; top:10px;color:#eee;font-size:18px;'+CHECKED_OFF+'"></li></div></div></td>';
			weeks += NEW_WEEK;
		}
		weeks += END_WEEK;
		$("#"+this.TIMELINE_ID).html(OPEN_TABLE + weeks + CLOSE_TABLE);
	}
	this.buildWeekDays = function(){
		var base = this;
		var today = new Date();
		var startDate = new Date(today.setDate(today.getDate() -base.getDaysSinceStart()));
		App.console.log(" > buildWeekDays called",3);
		for(var w=0; w<8; w++){
			var thisWeek = w+1;
			var HTML = '<div class="week-status week'+(thisWeek)+' week-tl" id="weekView"><div class="week-text">Week '+(thisWeek)+'</div>';
			App.console.log(" > > Week: "+ (w+1),5);
			var WEEK_HTML = HTML; 
			var DAYS_HTML="";
			var day_odd = false;
			for(var d=0; d<7; d++){
				try{
				var dayCount = ((7*w) + (d+1));
				var startDate = new Date(startDate.setDate(startDate.getDate() + 1));
				var dateString = startDate.toDateString();
				App.console.log(" > > > Day: "+ (d+1)+" "+dateString,3);
				var placement = (day_odd)? "top" : "bottom";
				var setBottom = (day_odd)? "" : "-bottom";
				var todayClass = (base.getDaysSinceStart()==dayCount)?"today":"";
				var todayNote = (base.getDaysSinceStart()==dayCount)?" (Today)":"";
				if(dayCount<=base.getDaysSinceStart()){
					var DAY_HTML = '<div class="week-tl-box'+setBottom+' hidden-xs" style="left:'+(13.5*d +2)+'%"><div class="week-tl-'+placement+'-flag "><div class="week-tl-flag-text">Day&nbsp;'+ dayCount +'</div><i class="fa fa-calendar week-tl-'+placement+'-flag-li"></i> </div><div class="week-tl-'+placement+'-box hidden-xs '+todayClass+'"><div style="padding:1px 4px;" onClick="showWeekActivites('+(thisWeek)+')"><b>'+ dateString +' '+todayNote+'</b></div><br><div class="todo" style="border-radius:8px; padding:4px;"><ul id="dayEvents_'+((7*w) + (d+1))+'">'+ base.getActivities(d+1)+'</ul></div><div class="clearfix"></div></div></div>';
					
				DAYS_HTML += DAY_HTML;
				} else if(w+1>base.getCurrentWeek()){
					var DAY_HTML = '<div class="futureTsActivities">Your future Telespine activities will be shown here.</div>';
					DAYS_HTML += DAY_HTML;
				}
				day_odd = !day_odd;
				} catch (wt){
					
				}
			}
			WEEK_HTML = HTML + DAYS_HTML +'</div>';
			var arrow = '<div style="position:absolute; top:97px !important;width:40px;display:none;z-index:200" id="weekViewArrow"><img src="'+App.settings.baseImageURL+'top-white-arrow.png" width="30" height="49"></div><div style="position:absolute; top:120px;right:-8px; width:30px;display:none" id="weekViewActions"><li class="fa week-tl-close" onClick="viewWeek(currentWeek)">x</li></div><div style="position:absolute; top:150px;right:-8px; width:30px;display:none; " id="weekViewActions2"><li class="fa fa-info week-tl-close btn-setting text-center" onClick="App.showHelp(\'timeline\')" style="width:20px"></li></div>';
			$("#"+this.TIMELINE_ID).append(WEEK_HTML+arrow);
		}
	}
	this.getActivities = function(day){
		return ""; //HTML;
	}
	this.setActivities = function(activities){
		App.console.group("Activities");
		activities.sort(compareDays);
		var len = activities.length;
		/* activity: {day:2, label:'', type:'posture', kind:'video', status:'complete|pending', action:'javascript'} */
		var itemsToday = 0;
		for(a=0; a<len; a++){
			try{
				var dayNum = a;
				var activity = activities[a];
				var status = (activity.status=="complete")?"fa-check":"fa-angle-double-right";
				var type = activity.type;
				var action = "";
				if(activity.kind.toLowerCase()=="video"){
					action = "App.openMovie";
				} else if(activity.kind.toLowerCase()=="article"){
					action = "App.openArticle";
				} else {
					action="App.showTip";
				}
				var label = activity.label;
				var icon="";
				if(activity.kind=="video"){
					icon= ' &nbsp;<i class="fa fa-play-circle" style="color:#3E70DA !important;padding-left:8px"></i>';	
					label = 'Watch the "'+activity.label+'" video.';
				} else if(activity.kind=="article"){
					icon= ' &nbsp;<i class="fa fa-file-text" style="color:#3E70DA !important;padding-left:8px"></i>';	
					label = 'Read the "'+activity.label+'" article.';
				}
				if(type.toLowerCase()=="posture"){
					type = '<span class="label label-important" title="Posture">p</span>';
				} else if(type.toLowerCase()=="mobility"){
					type = '<span class="label label-success" title="Mobility">m</span>';
				} else if(type.toLowerCase()=="core strength"){
					type = '<span class="label label-warning" title="Core Strength">c</span>';
				} else if(type.toLowerCase()=="flexibility"){
					type = '<span class="label label-info" title="Flexibility">f</span>';
				}
				var HTML = '<li class="bg-none li-activity"><span class="todo-actions"> <i class="fa '+ status +'" style="color:#0D3EA6 !important;"></i> </span><span class="desc li-activity"><a href="javascript:'+action+'('+dayNum+');">'+label+' '+icon+'</a></span></li>';
				if(!App.isMobile()){
					$("#dayEvents_"+activity.day).append(HTML);
				} 
				if(this.getDaysSinceStart()==activity.day){
					itemsToday++;
					App.console.group("Today");
					App.console.log("Setting Today's Activities: dailyActivities2",4);
					App.console.log(" > getDaysSinceStart: "+this.getDaysSinceStart(),4);
					App.console.log(" > dayNum: "+dayNum,4);
					App.console.groupEnd();
					/* Add event to today's timeline component on Dashboard. */
					$("#dailyActivities2").append(HTML);
				}
				/**********************************/
				/* Device Overlay Week Activities */
				/**********************************/
				var week = this.getCurrentWeekFromDay(activity.day);
				TimelineBuilder.addActivity(activity.day,HTML);
			} catch (wt){App.console.log(wt);}
			$(".getNumberOfItems").html(itemsToday);
			App.console.log(" > Adding Activity to: #dayEvents_"+activity.day,5);
		}
		App.console.groupEnd();
	}
}
function daysBetween(date1, date2) {
	var hoursToAdjust = Math.abs(date1.getTimezoneOffset() /60) - Math.abs(date2.getTimezoneOffset() /60);
	date2.addHours(hoursToAdjust); 
    var ONE_DAY = 1000 * 60 * 60 * 24;
    var date1_ms = date1.getTime();
    var date2_ms = date2.getTime();
    var difference_ms = Math.abs(date1_ms - date2_ms);
    return Math.round(difference_ms/ONE_DAY);
}

Date.prototype.addHours= function(h){
    this.setHours(this.getHours()+h);
    return this;
}

var currentWeek = "";
function viewWeek(id){
	$("div.week-status").css("height","60px").hide();
	$("div.week-status-btn").attr("rel","");
	App.console.log(" > viewWeek("+id+")",2);
	var mobile=App.isMobile();
	if(!mobile && window.outerWidth>479){
		if(currentWeek!=id) {
			$("#weekViewArrow").slideDown(200);
			$("div.week"+id).fadeIn(1000);
			/* Current Week plus 1 for less than*/
			if(parseInt(id) <= (timeline.getCurrentWeek())){
				$("div.week"+id).animate({height:"340px"},400,"easeInOutBack");
			} else {
				$("div.week"+id).animate({height:"110px"},300,"easeInOutBack");
			}
			var lap = (parseInt(id) *9.72) + parseInt(id) +"%";
			$("#weekViewArrow").animate({left:lap},500,"easeInOutBack");
			$("#weekViewActions").fadeIn();
			$("#weekViewActions2").fadeIn();
			currentWeek = id;
		} else {
			$("div.week"+id).animate({height:"60px"},200,"easeInOutBack");
			currentWeek="";
			$("#weekViewArrow").hide();
			$("#weekViewActions").fadeOut();
			$("#weekViewActions2").fadeOut();
			$("div."+id).hide();
		}
	} else {
		showWeekActivites(id);
		jQuery('body, html').animate({
                    scrollTop: jQuery("body").offset().top
                }, 400);
	}
}

function showWeekActivites(id){
	$('#WeekActivitiesTitle').html("Activities for Week "+id);
	$(".viewWeeksOverlayDisplay").hide();
	$("#dailyActivitiesWeek"+id).show();
	$('#week-day-view').modal('show');
}

function Activity(day, week){
	this.day = day;
	this.week = week;
	this.html = "";
							}
function compareDays(a, b) {
  App.console.log(":: SORT :: - a.day"+ a.day ,4)
	if (parseInt(a.day) < parseInt(b.day))
     return -1;
  if (parseInt(a.day) > parseInt(b.day))
     return 1;
  return 0;
					}

var TimelineBuilder = {
	build: function(thisDay, thisWeek){
		App.console.log(" > Today is day: "+thisDay+", and week: "+thisWeek,2)
		var html="";
		for(var week=1; week<8; week++){
			if(week<=thisWeek){
				for(var day=1; day<8; day++){
					var dayNum = day + (week-1)*7;
					if(dayNum<=thisDay){
						html="<div id='day-"+day+"'><h3 class='section week'>Day: "+dayNum+
							" Activities</h3><div class='day-content' id='day-content-"+dayNum+"'></div></div>";
						$("#dailyActivitiesWeek"+week).append(html);
			}
					}
							} else {
				$("#dailyActivitiesWeek"+week).append("<h4 class='weekOverlayFuture'>"+
					"Your future Telespine activities will be shown here.</h4>");
							}
					}
	},
	addActivity: function(day, activityHTML){
		$("#day-content-"+day).append(activityHTML);
			}
	}

































