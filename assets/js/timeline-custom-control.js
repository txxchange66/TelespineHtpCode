/* TeleSpine Custom Timeline Control */
/**
 * Build Interface Function
 * View Week navigation function
 * Version 2.5
 */
function WeeklyTimeline(src){
	App.console.log("WeeklyTimeline called",1);
	this.TIMELINE_ID="Weekly-timeline";
	var WEEKS_ID="";
	this.display = true;
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
		if(days<8){week=1;
		} else if(days<=14){week=2;
		} else if(days<=21){week=3;
		} else if(days<=28){week=4;
		} else if(days<=35){week=5;
		} else if(days<=42){week=6;
		} else if(days<=49){week=7;
		} else{week=8;}
		return week;
	}
    /**
     * @method
     * @returns int
     */
	this.getCurrentWeekFromDay = function(days){
		var week =0;
		if(days<8){week=1;
		} else if(days<=14){week=2;
		} else if(days<=21){week=3;
		} else if(days<=28){week=4;
		} else if(days<=35){week=5;
		} else if(days<=42){week=6;
		} else if(days<=49){week=7;
		} else{week=8;}
		return week;
	}
    /**
     * @method
     * Builds and constructs the GUI
     */
	this.getTimeline = function(src){
		this.setDaysSinceStart();
		TimelineBuilder.build(this.getDaysSinceStart(), this.getCurrentWeek());
		if(this.display) this.buildNavigation();
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
					var DAY_HTML = '<div class="futureTsActivities">Your future daily activities will be shown here.</div>';
					DAYS_HTML += DAY_HTML;
				}
				day_odd = !day_odd;
				} catch (wt){
					
				}
			}
			//WEEK_HTML = HTML + DAYS_HTML +'</div>';
			$("#"+this.TIMELINE_ID).append(HTML);
		}
	}
	this.getActivities = function(day){
		return ""; 
	}
	this.setTodaysActivities = function(day){
		//App.console.group("Activities 2");
		$("#dailyActivities2").html("");
		var activities = App.user.activities;
		activities.sort(compareDays);
		var len = activities.length;
		/* activity: {day:2, label:'', type:'posture', kind:'video', status:'complete|pending', action:'javascript'} */
		var itemsToday = 0;
		var shouldConinue = true;
		if(day > this.getDaysSinceStart()){
			var HTML = '<div class="daily-item">'+
				'	<div class="col-xs-1 col-md-1"><!--<i class="ico fa fa-exclamation-circle"></i>--></div>'+
				'	<div class="col-xs-10 col-md-11">Your future daily activities will be shown here.</div>'+
				'</div><div class="clearfix" style="margin-bottom:10px;"></div>';
			$("#dailyActivities2").html(HTML);	
			shouldConinue = false;
		}
		for(a=0; a<len; a++){
			if(activities[a].day==day && shouldConinue){
				try{
					var dayNum = a;
						var activity = activities[a];
						var status = (activity.status=="complete")?"Complete":"Not complete";
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
							icon= 'film';	
							label = 'Watch the "'+activity.label+'" video.';
						} else if(activity.kind=="article"){
							icon= 'file-text-o';	
							label = 'Read the "'+activity.label+'" article.';
						}
						var HTML = '<div class="row daily-item" title="'+status+'">'+
							'	<div class="col-xs-1 col-md-1"><i class="ico fa fa-'+icon+'"></i></div>'+
							'	<div class="col-xs-10 col-md-11"><a href="javascript:'+action+'('+dayNum+');">'+label+'</a></div>'+
							'</div><div class="clearfix" style="margin-bottom:10px;"></div>';
									
						$("#dailyActivities2").append(HTML);
				} catch(sdf){App.console.log(sdf);}
			}
		}
		//App.console.groupEnd();
	}
	this.setDayText = function(currentDay){
		var thisDay = "Day "+currentDay;
		if(currentDay==timeline.getDaysSinceStart()){
			thisDay = "Today";
		}
		$("span.currentDay").html(thisDay);	
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
					icon= 'film';	
					label = 'Watch the "'+activity.label+'" video.';
				} else if(activity.kind=="article"){
					icon= 'file-text-o';	
					label = 'Read the "'+activity.label+'" article.';
				}
				var HTML = '<div class="row daily-item">'+
							'	<div class="col-xs-2 col-md-1"><i class="ico fa fa-'+icon+'"></i></div>'+
							'	<div class="col-xs-10 col-md-11"><a href="javascript:'+action+'('+dayNum+');">'+label+'</a></div>'+
							'</div><div class="clearfix" style="margin-bottom:10px;"></div>';
				if(!App.isMobile()){
					$("#dayEvents_"+activity.day).append(HTML);
				} 
				if(this.getDaysSinceStart()==activity.day){
					itemsToday++;
					App.console.group("Today");
					App.console.log("Adding Today's Activities: ",4);
					App.console.log(" >       Day: "+this.getDaysSinceStart(),4);
					App.console.log(" >  Event ID: "+dayNum,4);
					App.console.groupEnd();
					/* Add event to today's timeline component on Dashboard. */
					//$("#dailyActivities2").append(HTML);
				}
				/**********************************/
				/* Device Overlay Week Activities */
				/**********************************/
				var week = this.getCurrentWeekFromDay(activity.day);
				TimelineBuilder.addActivity(activity.day,HTML);
			} catch (wt){App.console.log(wt);}
			$(".getNumberOfItems").html(itemsToday);
			//App.console.log(" > Adding Activity to: #dayEvents_"+activity.day,5);
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
		showWeekActivites(id);
		jQuery('body, html').animate({
       scrollTop: jQuery("body").offset().top
    }, 400);
	
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
						html="<div class='' id='day-"+day+"'><h3 class='section week'>Day: "+dayNum+
							" Activities</h3><div class='day-content overlay-day' id='day-content-"+dayNum+"'></div></div>";
						$("#dailyActivitiesWeek"+week).append(html);
					}
				}
			} else {
				$("#dailyActivitiesWeek"+week).append("<h4 class='weekOverlayFuture'>"+
					"Your future daily activities will be shown here.</h4>");
			}
		}
	},
	addActivity: function(day, activityHTML){
		$("#day-content-"+day).append(activityHTML);
	}
}

