/* Dashboard Functions by Creative Slave */

/*
	Please do not alter this Controller. This is a base controller.
	See dashboard.controllers for individual control modules.
*/
var Dashboard = {
	properties:{
		configuration:{
		   baseImageURL:		"/admin/assets/img/",
		   baseComponentURL:	"components/"
		}
	},
	history:[],
	init: function(properties){
		Dashboard.console.log("Loading Dashboard...");
		$("#OverlayBG").css("visibility","visible");
		this.properties= properties;
		if(Dashboard.isMobile()){
			Dashboard.properties.configuration.startDate = new Date( Date.parse(Dashboard.properties.configuration.startDate) );
		} else {
			Dashboard.properties.configuration.startDate = new Date(Dashboard.properties.configuration.startDate);
		}
		$("#loading").css("visibility","visible");
		/*Dashboard.loadProperties("/admin/components/properties.html");*/
		Dashboard.load();
	},
	loadProperties: function(propurl){
		Dashboard.console.log("Dashboard: Testing Load Properties...");
		
	},
	load: function(){
		var len= this.properties.components.length;
		Dashboard.console.log("Loading Dashboard Components ("+len+")");
		var base = this.properties;
		for(c=0; c<len; c++){
			Dashboard.console.log("Reading Component: "+base.components[c].id);
			/* Ajax with JSONP */
			try{
				if(base.components[c].load && base.components[c].url!="void"){
					if(Dashboard.properties.configuration.loggingVerbose){
						Dashboard.console.log(base.components[c]);	
					}
					Dashboard.console.log(" > Loading Component: ("+base.components[c].id+") from "+base.baseComponentURL + base.components[c].url,2);
					$.ajax({
					   type: 'GET',
						url: base.configuration.baseComponentURL + base.components[c].url,
						async: false,
						contentType: "application/text",
						success: function(response) {
						   $("#col_"+base.components[c].column).append(response+'<div class="clearfix"></div>');
						   try{base.components[c].callback(base.components[c]);} catch(compError){
							   Dashboard.console.log("Error executing function ("+base.components[c].callback+"): "+ compError);}
						},
						error: function(a,b,d) {
						   Dashboard.console.log(" > > Ajax Error when invoking "+base.components[c].id+": "+a+"; "+b+"; "+d);
						}
					});
				} else {
					Dashboard.console.log(" > Skip load component: "+ base.components[c].id);	
					if(base.components[c].load && base.components[c].url=="void"){
						base.components[c].callback();
					}
				}
			} catch (ajaxErrorOuter){
				Dashboard.console.log(" > > Ajax Outer Error when invoking "+base.components[c].id+": "+ajaxErrorOuter.message);
			}
		}
		setTimeout(function(){
			$("#loading div").html("");
			$("#loading").animate({
					width:"10%",
					height:"10%",
					"margin-left":"-5%",
					opacity:0.0
				},400)
			setTimeout(function(){
				$("#OverlayBG").animate({opacity:0.0},400);
				setTimeout(function(){
					$("#loading").css("visibility","hidden");
					$("#OverlayBG").css("visibility","hidden");
				},400);
			},400);
		},400);
		try{/*if(this.openSurvey) this.openSurvey();*/} catch(e){}
	},
	getComponent: function(id){
		var base = this;
		try{
			var len= base.properties.components.length;
			for(c=0; c<len; c++){
				/*Dashboard.console.log(base.properties.components[c].id);*/
				if(Dashboard.properties.configuration.loggingVerbose){
					Dashboard.console.log(" > > getComponent: "+base.properties.components[c].id,4);	
				}
				if(base.properties.components[c].id == id){
					return base.properties.components[c];
				}
			}
		} catch(compE){base.console.log(" > getComponent(): Error "+compE);return null;}
	},
	showHelp: function(componentId){
		Dashboard.console.log("function :: showHelp("+componentId+")");
		var title = [];
		title["painMeter"] = "Current Pain Level Overview";
		title["functionalScore"] = "Functional Score Overview";
		title["goalsAndStatistics"] = "Goals and Statistics Overview";
		title["healthyHabits"] = "Healthy Back Habits Overview";
		title["timelie"] = "Timeline Overview";
		title["today"] = "Today's Activities Overview";
		title["other"] = "Overview";
		$('div.dialog-help').hide();
		$("#overviewTitle").html(title[componentId]);
		$('#dialog-'+componentId).show();	
	},
	callService: function(componentId){
		Dashboard.console.log("service :: callService for "+componentId);
		var base = this;
		if(base.getComponent(componentId)){
			$.ajax({
			   type: 'GET',
				url: base.properties.configuration.baseComponentURL + base.getComponent(componentId).service.url,
				async: false,
				contentType: "application/text",
				success: function(response) {
				   base.getComponent(componentId).service.callback(response);
				},
				error: function(ajaxErr) {
				   base.console.log(" > Ajax Error when invoking "+componentId+": "+ajaxErr.message);
				}
			});
		}
	},
	callServiceModule: function(obj){
		Dashboard.console.log("service :: callService for "+obj.componentId);
		var base = this;
		if(base.getComponent(obj.componentId)){
			$.ajax({
			   type: 'GET',
				url: obj.url,
				async: false,
				data:obj.data,
				contentType: "application/text",
				success: function(response) {
				   obj.callback(response);
				},
				error: function(ajaxErr) {
				   Dashboard.console.log(" > Ajax Error when invoking "+obj.componentId+": "+ajaxErr.message);
				}
			});
		}
	},
	displayComponent: function(component, bool){
		component.load = bool;
		try{
			if(bool){$("#"+component.id+"_comp").show();} else {$("#"+component.id+"_comp").hide();}
		}catch(compErr){Dashboard.console.log(compErr);}
	},
	isMobile: function(){
		return (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent));	
	},
	console:{
		log: function(l, level/*:int*/){
			try{
				if(Dashboard.properties.configuration.logging==true){
					if(!level){
						if(console) console.log(l);
					} else if(level<=Dashboard.properties.configuration.loggingLevel){
						if(console) console.log(l);
					}
				}
			} catch(e){}
		}
	},
	daysBetween: function (date1, date2) {
		var hoursToAdjust = Math.abs(date1.getTimezoneOffset() /60) - Math.abs(date2.getTimezoneOffset() /60);
		date2.addHours(hoursToAdjust); 
		var ONE_DAY = 1000 * 60 * 60 * 24;
		var date1_ms = date1.getTime();
		var date2_ms = date2.getTime();
		var difference_ms = Math.abs(date1_ms - date2_ms);
		return Math.round(difference_ms/ONE_DAY);
	},
	/* Preferences are cookie/LocalStorage options for setting/sticking last set actions*/
	preference: function(name,value){
		var COOKIEID = "TelespinePrefs"
		var preferences = {
			components:["weeklyTimeline:0","goalsAndStatistics:0","painMeter:0","functionalScore:0","onlineCoaching:0","messages:0","today:0"],
			theme:"default"
		}
		if(!value){
			/* return value */
			
		} else {
			if(value.toLowerCase()=="delete"){
				
			} else {
				/* Set Cookie */	
			}
		}
	},
	hasHistory: function(arg){
		return (Dashboard.history[arg]);
	},
	setHistory: function(arg,val){
		Dashboard.history[arg] = val;
	},
	getHistory: function(arg){
		return Dashboard.history[arg];
	}
}
function ServiceCall(componentId){
	this.componentId=componentId;
	this.url="";
	this.name="";
	this.data = {};
	this.callBack=function(response){};
}
function DomRenderer(componentId){
	this.componentId=componentId;
	this.data = {};
	this.apply=function(response){};
}
/* END Dashboard Controller */

/* Start Extend Dashboard Controller Class */





/****************************************
 * @Dashboard.openFirstTimeResetPassword
 * Purpose: Step 1
 * Type: Overlay Component
 * Used By: Dashboard Onboarding Process
****************************************/
Dashboard.openFirstTimeUser = function(){
	Dashboard.console.log(" First Time User Message welcome screen.",2);
	if(Dashboard.properties.configuration.firstTimeUser){
		$("#FirstTimeUser").modal({backdrop: 'static',keyboard: false});
	}
}


/****************************************
 * @Dashboard.openFirstTimeResetPassword
 * Purpose: Step 2
 * Type: Overlay Component
 * Used By: Dashboard Onboarding Process
****************************************/
Dashboard.openFirstTimeResetPassword = function(){
	Dashboard.console.log(" First Time User Reset Password.",2);
	if(Dashboard.properties.configuration.resetPassword.load){
	$("#ResetPassword").modal({backdrop: 'static',keyboard: false});
		inForm.init("changePassword-form","fieldError",function(){
			try{
				$.ajax({
				   type: 'GET',
					url: Dashboard.properties.configuration.baseComponentURL + Dashboard.properties.configuration.resetPassword.service.url,
					async: false,
					data: {password:$("#newPassword").val()},
					contentType: "application/json",
					success: function(response) {
					    Dashboard.console.log(" Response: "+response);
					    
							$('#ResetPassword').modal('hide');
							Dashboard.openSurvey();
					},
					error: function(ajaxErr) {
					   base.console.log(" > Ajax Error when invoking password update: "+ajaxErr.message);
					}
				});
			} catch (es){
				Dashboard.console.log(" > Set Password Error: "+es);
			}
			return false;
		});
	}
}


/****************************************
 * @Dashboard.openSurvey
 * Purpose: Step 3
 * Type: Overlay Component
 * Used By: Dashboard Onboarding Process
****************************************/
Dashboard.openSurvey= function(){
	Dashboard.console.log("\n****************\nOpen Survey",2);
	if(Dashboard.properties.configuration.survey.load){
		try{
			Dashboard.wizard.init("surveyWiz","surveyNextBtn","surveyPreviousBtn",
				function(){
					Dashboard.saveSurvey();	
				}
			);
		} catch (ej){
			Dashboard.console.log(" Pain Level Error: "+ej);
		};
		$('#surveyModal').modal({backdrop: 'static',keyboard: false});
	}
}

/*
Dashboard.load() will call the designated flow if conditions meet the criteria. 
A flows are set in the config.js file. All flows are preconfigured. Usually set 
by the day and what has been and has not been done determines the execution of a flow.

Dashboard.load()
  >  Dashboard.checkUserFlow()
     >  Dashboard.initFlow()
*/
Dashboard.checkUserFlow = function(){
	var flows = Dashboard.config.userFlows;
	var flen = Dashboard.config.userFlows;
	var day = timeline.getDaysSinceStart();
	for(f=0; f<flen; f++){
		var propertySet = [];
		if(flows[f].trigger.propertySetting!=""){
			propertySet = flows[f].trigger.propertySetting.split(":");
		}
		if(flows[f].trigger.onDay){
			if(day==flows[f].trigger.day){
				if(propertySet.length>0){
					if(Dashboard.properties.configuration[propertySet[0]]==propertySet[1]){
						/* If has property, must check. Call Flow and break*/
						Dashboard.initFlow(flows[f].flow);
					}
				} else {
					/* Call Flow and break*/
						Dashboard.initFlow(flows[f].flow);
				}
				break;
			} 
		}
	}
}
/* WORK IN PROGRESS... */
Dashboard.initFlow= function(flow){
	Dashboard.initFlow.itemLength = flow.length;
	Dashboard.initFlow.currentItem = 0;
	Dashboard.initFlow.next(flow[0]);
	Dashboard.initFlow.next= function(nextItem){
		$("#"+nextItem.id).modal("show").onClose(
			function(){
				if(nextItem.callback!=""){
					nextItem.callback();
				}
				if(Dashboard.initFlow.itemLength>Dashboard.initFlow.currentItem){
					Dashboard.flow.next(flow[Dashboard.initFlow.currentItem++]);
				}
			}
		);
	}
}

/****************************************
 * @Dashboard.saveSurvey
 * Purpose:
 * Type: Overlay Component
 * Used By: Dashboard Onboarding Process, Survey modal
****************************************/
Dashboard.saveSurvey = function(){
	Dashboard.console.log("Save Survey!");
	var fs = Dashboard.getComponent("functionalScore");
	inForm.setFormId("survey-form");
	var surveyData = inForm.getFormJSON();
	try{
		$.ajax({
		   type: 'GET',
			url: Dashboard.properties.configuration.baseComponentURL + Dashboard.properties.configuration.survey.service.url,
			async: false,
			data: surveyData,
			contentType: "application/json",
			success: function(response) {
			   Dashboard.console.log(" Response: "+response);
			   var score = response.split(",");
			   Dashboard.console.log(" Survey resulting Functional Score: "+score);
				fs.data.scores.push(score);
				Dashboard.setFunctionalScore(fs);
			},
			error: function(ajaxErr) {
			   base.console.log(" > Ajax Error when invoking survey: "+ajaxErr.message);
			}
		});
	} catch (es){
		Dashboard.console.log(" > Save Survey Error: "+es);
	}
	$('#surveyModal').modal('hide');
	/** TODO: move to Flow Control */
	Dashboard.openTip("Tip1");
	Dashboard.console.log("Close Survey!\n****************");
}

/****************************************
 * @Dashboard.openTip
 * Purpose:
 * Type: Overlay Component
 * Used By: Dashboard Onboarding Process
****************************************/
Dashboard.openTip = function(domId){
	Dashboard.console.log(" First Time User Message welcome screen.",2);
	if(Dashboard.properties.configuration.firstTimeUser){
		$("#"+domId).modal({backdrop: 'static',keyboard: false});
	}
}

/****************************************
 * @Dashboard.timeline
 * Purpose:
 * Used By: WeeklyTimeline
****************************************/
Dashboard.timeline = function(){
	timeline = new WeeklyTimeline();
	Dashboard.timeline = timeline;
	Dashboard.timeline.init();
	setTimeout(
		function(){
			Dashboard.timeline.setActivities(Dashboard.getComponent("weeklyTimeline").data.activities);
		},2200
	);
}

						
/****************************************
 * @Dashboard.addGoalsService
 * Purpose:
 * Used By: 
****************************************/
Dashboard.addGoalsService = function(){
	var service = new ServiceCall("goalsAndStatistics");
	/*TODO: Change Services URL to reference config */
	service.url = "addGoal.html",
	service.callback = function(response){
		$("#GoalsList").prepend(response);
		var component = Dashboard.getComponent(service.componentId);
		component.data.goals[1]+=1;
		Dashboard.setStats(component);
	}	
}
Dashboard.preference = function(name,value){
	var COOKIEID = "TelespinePrefs"
	var preferences = {
		components:["weeklyTimeline:0","goalsAndStatistics:0","painMeter:0","functionalScore:0","onlineCoaching:0","messages:0","today:0"],
		theme:"default",
		configuration: {
		   resetPassword:		true,
		   survey: {
				load:			true
		   },
		   lastPainLevel:		5,
		   lastNewMessageDate: (new Date()).toString(),
		   painLevelLastSet:	(new Date()).toString(),
		   theme: "default"
	   }
	}
	if(!value){
		/* return value */
		
	} else {
		if(value.toLowerCase()=="delete"){
			
		} else {
			/* Set Cookie */	
		}
	}
}


/****************************************
 * @Dashboard.setStats
 * Purpose:
 * Used By: 
****************************************/
Dashboard.setStats= function(component){
	Dashboard.console.log(" Dashboard.setStats()");
	try{
		var percent = Math.round(component.data.goals[0]/component.data.goals[1]*100);
		Dashboard.console.log(" 1 Still working");
		$("#MyLogins").html(component.data.logins);
		$("#GoalsInterface").fadeOut();
		
		Dashboard.console.log(" 2 Still working");
		$("#GoalsInterface").html('<i class="fa fa-thumbs-up"></i><span class="plus">+</span><span class="percent">%</span>'+
		'<input id="MyGoals" type="text" value="'+ percent +'" class="circleChart" />');
		$("#GoalsInterface").fadeIn();
	} catch (pe){console.log(pe);}
	Dashboard.console.log(" 3 Still working");
	try{
		$('.circleChart').each(function(){
			var circleColor = $(this).parent().css('color');
			$(this).knob({
				'min':0,
				'max':100,
				'readOnly': true,
				'width': 120,
				'height': 120,
				'fgColor': circleColor,
				'dynamicDraw': true,
				'thickness': 0.2,
				'tickColorizeValues': true,
				'skin':'tron'
			});
		});
	} catch(st){}
	try{
			Dashboard.console.log(" Setting click events to Goals");
		/*if(Dashboard.history["goal-actions-set"] != "true"){
			Dashboard.history["goal-actions-set"] = "true";*/
			$(".goal-actions > a").unbind('click');
			$(".goal-actions > a").click(function(e){
				Dashboard.console.log("Goal Click");
				Dashboard.updateGoal(e);
				return false;
			});
		/*}*/
	} catch(st){Dashboard.console.log(st);}
}


/****************************************
 * @Dashboard.setPainLevel
 * Purpose:
 * Used By: 
****************************************/
Dashboard.setPainLevel= function(){
	Dashboard.console.log("function :: setPainLevel");
	var newPain = $(".pain-level-control").html();
	/*TODO: Change Services URL to reference config */
	$.ajax({
	   type: 'GET',
	   data:{painLevel:newPain},
		url: Dashboard.properties.configuration.baseComponentURL + Dashboard.properties.configuration.updatePainURL,
		async: false,
		contentType: "application/text",
		success: function(response) {
		   Dashboard.console.log(" > > Update Pain Response: "+response);
			$.gritter.add({
				title: 'Pain Level Updated!',
				text: 'Thank you! You have added a status update to your pain level. You set it to: '+newPain+' out of 10.',
				class_name: 'my-sticky-class'
			});
		   var c = Dashboard.getComponent("painMeter");
			c.data.pain += ","+newPain;
			$("#pailControl").hide();
			$(".pain-bubble").slideDown(300);
			var color = "#eae874";
			if(parseInt(newPain) > 6){
				color = "#ff5454";
			} else if(parseInt(newPain) < 4){
				color = "#78cd51";
			}
			$(".pain-bubble").css("background-color",color);
			try{
				$("#painLevelGraph").html('<div id="pain-graph" class="chart red" style="width:100%">'+c.data.pain+'</div>');
				var chartColor = $("#painLevelGraph").css('color');
				var range_map = $.range_map({
					'3': 'green',
					'4:6': 'yellow',
					'7:': 'red'
				})
				$("#painLevelGraph > .chart").each(function () {
					$(this).sparkline("html", {
						width: '160%',
						height: 70,
						lineColor: "red",
						colorMap:range_map,
						fillColor: false,
						spotColor: false,
						maxSpotColor: false,
						minSpotColor: false,
						spotRadius: 4,
						lineWidth: 4
					});
				 });
				if (jQuery.browser.mozilla) {
					$("#painLevelGraph").css('MozTransform','scale(0.5,0.5)');
					$("#painLevelGraph").css('height','40px;').css('margin','-20px 0px -20px -25%');
				} else {
					$("#painLevelGraph").css('zoom',0.6);
				}
			} catch(pe){alert(pe);}
		   
		},
		error: function(a,b,d) {
		   Dashboard.console.log(" > > Ajax Error: "+a+"; "+b+"; "+d);
		}
	});
	
}


/****************************************
 * @Dashboard.setPainLevelGraph
 * Purpose:
 * Used By: 
****************************************/
Dashboard.setPainLevelGraph= function(component){
	Dashboard.console.log("callback :: setPainLevelGraph()");
	try{
		var lastPainLevel = Dashboard.properties.configuration.lastPainLevel;
		if(!lastPainLevel){
			lastPainLevel = 5;	
		}
		var range_map = $.range_map({
			'3': 'green',
			'4:6': 'yellow',
			'7:': 'red'
		})
		$("#pain-graph").html(component.data.pain);
		$(".pain-level-control").html(lastPainLevel)
		$("#pain-level").slider({
			range: "min",
			value: lastPainLevel,
			min: 0,
			max: 10,
			slide: function( event, ui ) {
				$(".pain-level-control").html(ui.value);
			}
		});
	} catch(e){Dashboard.console.log(" Error setPainLevelGraph : "+e);}	
}



/****************************************
 * @Dashboard.setFunctionalScore
 * Purpose:
 * Used By: 
****************************************/
Dashboard.setFunctionalScore= function(component){
	Dashboard.console.log("callback :: setFunctionalScore()");
	try{
		var slen = component.data.scores.length-1;
		$("#currentFunctionalScore").html(component.data.scores[slen][0]+ "%");
		$("#currectPain").html( (component.data.scores[slen][1]/10) + "/10");/*"*/
		for(var s=0; s<4; s++){this.setScore(component, s);}
	} catch(e){Dashboard.console.log(" Error setFunctionalScore : "+e);}	
}


/****************************************
 * @Dashboard.setScore
 * Purpose:
 * Used By: setFunctionalScore
****************************************/
Dashboard.setScore= function(component, survey){
	Dashboard.console.log(" > setScore(component, survey)" );
	try{
		var score = component.data.scores[survey];
		$('.f-score-'+(survey+1)).animate(
			{'top':(100-score[0])+'%'},2500
		);
		$('.f-score-'+(survey+1)).html(score[0]+'%');
		$('.p-score-'+(survey+1)).animate(
			{'top':(100-score[1])+'%'},2500
		);
		$('.p-score-'+(survey+1)).html(score[1]+'%');
		Dashboard.console.log(" > > Functional Score from Survey "+(survey+1)+": "+ (100-score[0]));
		Dashboard.console.log(" > > Pain Score from Survey "+(survey+1)+": "+ (100-score[1]));
	} catch (se){
		Dashboard.console.log("Expected Exception: "+ se+ " (This is expected because it is not yet set)");
		$('.f-bar-'+(survey+1)).html('Pending');
	}
}


/****************************************
 * @Dashboard.updateGoal
 * Purpose:
 * Used By: addGoalsService
****************************************/
Dashboard.updateGoal= function(event){
	Dashboard.console.log("Dashboard.updateGoal > "+$(event.currentTarget).attr("id"));
	var statusVal = "";
	var goalID = "";
	var StatisticsComponent = Dashboard.getComponent('goalsAndStatistics');
	var obj = event.currentTarget;
	try{
		Dashboard.console.log(" > TEST DOM: "+ obj );
	} catch (e){console.log(e);}
	try{
		if ($(event.currentTarget).find('i').attr('class') == 'fa fa-square-o') {
			$(event.currentTarget).find('i').removeClass('fa-square-o').addClass('fa-check-square-o');
			$(event.currentTarget).parent().parent().find('span').css({ opacity: 0.45 });
			$(event.currentTarget).parent().parent().find('.desc').css('text-decoration', 'line-through');
			
			/* Set Status Action to Complete. */
			Dashboard.console.log(" > > Set Status Action to Complete");
			StatisticsComponent.data.goals[0]+=1;
		} else {
			$(event.currentTarget).find('i').removeClass('fa-check-square-o').addClass('fa-square-o');
			$(event.currentTarget).parent().parent().find('span').css({ opacity: 1 });
			$(event.currentTarget).parent().parent().find('.desc').css('text-decoration', 'none');
			/* Set Status Action to active. */
			Dashboard.console.log(" > > Set Status Action to Active.");
			StatisticsComponent.data.goals[0]-=1;
		}
	} catch(eft){console.log(eft);}
	/*TODO: Change Services URL to reference config */
	$.ajax({
	   type: 'GET',
	   data:{status:statusVal,id:goalID},
		url: Dashboard.properties.configuration.baseComponentURL + Dashboard.properties.configuration.updateGoalURL,
		async: false,
		contentType: "application/text",
		success: function(response) {
		   Dashboard.console.log(" > > Update Goal Response: "+response);
		},
		error: function(a,b,d) {
		   Dashboard.console.log(" > > Ajax Error: "+a+"; "+b+"; "+d);
		}
	});
	Dashboard.setStats(StatisticsComponent);
	return false;
}


/****************************************
 * @Dashboard.addGoal
 * Purpose:
 * Used By: addGoalsService
****************************************/
Dashboard.addGoal= function(){
	Dashboard.console.log(":: addGoal");
	if($("#AddGoal").val().length>0){
		var StatisticsComponent = Dashboard.getComponent('goalsAndStatistics');
		var goal = $("#AddGoal").val();
		var base = this;
		$("#AddGoal").val("");
		try{
			
	/*TODO: Change Services URL to reference config */
			this.callService("goalsAndStatistics");
		} catch(serviceError){
			Dashboard.console.log("goalsAndStatistics.addGoal Service Error: "+serviceError);
		}
		$("#GoalsList").html($("#GoalsList").html().replace("{value}",goal));
	} else {
		$.gritter.add({
			title: 'Add Goal',
			text:  'We could not add the goal, it appears to be blank. Please try again.',
			class_name: 'my-sticky-class'
		});
	}
}


/****************************************
 * @Dashboard.openMovie
 * Purpose:
 * Used By: timeline
****************************************/
Dashboard.openMovie= function(a){
	try{
		var activity = Dashboard.getComponent("weeklyTimeline").data.activities[a];
		if(Dashboard.properties.configuration.loggingVerbose){
			Dashboard.console.log(" > > Activity: "+ activity,2);	
		}
		/* Custom code to set movie and open modal. */
		Dashboard.openMovieOverlay(activity.label, activity.media,activity.id);
	} catch(ea){Dashboard.console.log(ea);}
}

Dashboard.openMovieOverlay= function(title,url){
	try{
		Dashboard.console.log("Video URL: "+url);
		/* Custom code to set movie and open modal. */
		$('#week-day-view').modal('hide');
		$("#videoName").html(title);
		$("#overlayVideo").attr("src",url);
		$("#overlayVideo").attr("title",title);
		$("#videoSmallName").html(title);
		$("#videoTime").html("loading...");
		$("#overlayVideo").on("timeupdate", 
			function(event){
			  $("#videoTime").html("Playing:&nbsp; "+ getTimeLength(this.currentTime) +",&nbsp;&nbsp; <b>Duration:&nbsp; "+ getTimeLength(this.duration) +"</b>");
			}
		);
		$("#videoViewer").modal("show");
	} catch(ea){Dashboard.console.log(ea);}
}

/****************************************
 * @Dashboard.showMessage
 * Purpose: Expand the Message Center component if the message is less than 1 week old.
 * Used By: Message Center
****************************************/
Dashboard.showMessage = function(){
	Dashboard.console.log(" Message Center - ShowMessage()");
	if(Dashboard.properties.configuration.lastNewMessageDate){
		var mDate = new Date( Date.parse(Dashboard.properties.configuration.lastNewMessageDate) );
		var daysBtw = Dashboard.daysBetween(mDate, new Date());
		Dashboard.console.log(" > Message > Days since last new message: "+daysBtw);
		if(parseInt(daysBtw) <= 7){
			$("#messageCenterTitle").html("You have a new message!")
			$("#messageCenterContent").slideDown(800);
		}
	}
}


/****************************************
 * @Dashboard.openArticle
 * Purpose:
 * Used By: timeline
****************************************/
Dashboard.openArticle= function(a){
	try{
		Dashboard.console.log(" Open Article",2);
		var activity = Dashboard.getComponent("weeklyTimeline").data.activities[a];
		if(Dashboard.properties.configuration.loggingVerbose){
			Dashboard.console.log(" > > Activity: "+ activity,2);	
		}
		Dashboard.openArticleOverlay(activity.label, activity.media);
	} catch(ea){Dashboard.console.log(ea);}
}

Dashboard.openArticleOverlay= function(title,url){
	try{
		Dashboard.console.log(" Open Article Overlay",2);
		$('#week-day-view').modal('hide');
		/* Custom code to set movie and open modal. */
		$("#pdfName").html("<i class='fa fa-external-link' style='font-size:16px;font-weight:900;color:orange'></i>&nbsp; <a href='"+url+"' target='Article'>"+title+"</a>");
		$("#articleName").html(title);
		$("#articleViewer").modal("show");
	} catch(ea){Dashboard.console.log(ea);}
}

/****************************************
 * @Dashboard.openArticle
 * Purpose:
 * Used By: timeline
****************************************/
Dashboard.showTip= function(a){
	try{
		Dashboard.console.log(" Show Tip",2);
		var activity = Dashboard.getComponent("weeklyTimeline").data.activities[a];
		if(Dashboard.properties.configuration.loggingVerbose){
			Dashboard.console.log(" > > Activity: "+ activity,2);	
		}
		
	} catch(ea){Dashboard.console.log(ea);}
}


/****************************************
 * @Dashboard.showNotification
 * Purpose: Not Used
 * Type: Overlay Component
 * Used By: Not Used
****************************************/
Dashboard.showNotification = function(description){
	var ID = "#notificationDescription";
	$(ID).html(description);
	$('.notify').slideDown(400);
}

/* Survey Wizard:  Dashboard.wizard.init("surveyWiz","surveyNextBtn","surveyPreviousBtn"); */
Dashboard.wizard = {
	id: 				"",
	btnPreviousId: 	"",
	btnNextId: 		"",
	maxSteps: 			0,
	previousStep: 		0,
	currentStep: 		0,
	callback: function(){},
	init: function(id, nextId, previousId,callback){
		Dashboard.wizard.id = id;
		Dashboard.wizard.btnNextId = nextId;
		Dashboard.wizard.btnPreviousId = previousId;
		Dashboard.wizard.callback = (callback)?callback:function(){};
		$("#"+Dashboard.wizard.btnPreviousId).click(function(){
			Dashboard.wizard.previous();
		});
		$("#"+Dashboard.wizard.btnNextId).click(
			function(){Dashboard.wizard.next();
		});
		Dashboard.wizard.maxSteps = $("#"+Dashboard.wizard.id).children("div.step").length;
		console.log("How many steps in this survey? "+Dashboard.wizard.maxSteps);
		$("#"+Dashboard.wizard.id).before(
			"<div id='surveySteps' class='surveyWizard'></div>"
		);
		$("#"+Dashboard.wizard.id).parent().css("overflow","hidden");
		$("#"+Dashboard.wizard.id).parent().css("min-height","200px");
		$("#"+Dashboard.wizard.id).children("div.step").each(function(index, element) {
			$(this).attr("complete","false");
			$(this).attr("id","step-"+index);
			$(this).attr("valid","false");
			$(this).css("min-height","200px");
			$(this).css("position","relative");
			$(this).addClass("dashboard-wizard-step");
			$(this).hide();
		});
		$("#step-"+Dashboard.wizard.currentStep).show();
		Dashboard.wizard.setButtonStates();
		
		
	},
	next: function(){
		console.log("Next >>");
		Dashboard.wizard.previousStep=Dashboard.wizard.currentStep;
		Dashboard.wizard.currentStep++;
		Dashboard.wizard.goToStep();
		Dashboard.wizard.setButtonStates();
	},
	previous: function(){
		console.log("<< Previous");
		Dashboard.wizard.previousStep=Dashboard.wizard.currentStep;
		Dashboard.wizard.currentStep--;
		Dashboard.wizard.goToStep();
		Dashboard.wizard.setButtonStates();
	},
	setButtonStates: function(){
		$previous = $("#"+Dashboard.wizard.btnPreviousId);
		$next = $("#"+Dashboard.wizard.btnNextId);
		if(Dashboard.wizard.currentStep>0){
			$("#"+Dashboard.wizard.btnPreviousId).removeAttr("disabled");
		} else {
			$("#"+Dashboard.wizard.btnPreviousId).attr("disabled", "disabled");
		}
		if(Dashboard.wizard.currentStep<(Dashboard.wizard.maxSteps-1)){
			if($("#step-"+Dashboard.wizard.currentStep).attr("valid") == "true"){
				$("#"+Dashboard.wizard.btnNextId).removeAttr("disabled");
			} else {
				$("#"+Dashboard.wizard.btnNextId).attr("disabled", "disabled");
			}
		} else if(Dashboard.wizard.currentStep==(Dashboard.wizard.maxSteps-1)){
			console.log("End reached.");
			$("#"+Dashboard.wizard.btnNextId).html($("#"+Dashboard.wizard.btnNextId).html().replace("Next","Finish"));
		} else if(Dashboard.wizard.currentStep>(Dashboard.wizard.maxSteps-1)){
			$(".dashboard-wizard-step").fadeOut();
			$("#"+Dashboard.wizard.id).fadeOut();
			$("#"+Dashboard.wizard.btnNextId).html($("#"+Dashboard.wizard.btnNextId).html().replace("Finish","Next"));
			Dashboard.wizard.callback();
		}
	},
	goToStep: function(){
		console.log("  -Previous Step: "+Dashboard.wizard.previousStep);
		console.log("  -Current Step: "+Dashboard.wizard.currentStep);
		if(Dashboard.wizard.previousStep<Dashboard.wizard.currentStep){
			/* move right */
			console.log("  Slide Left to the next << [ NEXT ]");
			$("#step-"+Dashboard.wizard.previousStep).animate({
				left:"-100%"},400,"",function(){
				$("#step-"+Dashboard.wizard.previousStep).hide();
				$("#step-"+Dashboard.wizard.currentStep).css("left","100%");
				$("#step-"+Dashboard.wizard.currentStep).show()
				$("#step-"+Dashboard.wizard.currentStep).animate({left:"0%"},300);
			});
		} else {
			/* move left */
			console.log("  Slide Right to the previus >> [ PREVIOUS ]");
			$("#step-"+Dashboard.wizard.previousStep).animate({
				left:"100%"},400,"",function(){
				$("#step-"+Dashboard.wizard.previousStep).hide();
				$("#step-"+Dashboard.wizard.currentStep).css("left","-100%");
				$("#step-"+Dashboard.wizard.currentStep).show()
				$("#step-"+Dashboard.wizard.currentStep).animate({left:"0%"},300);
			});
		}
		//$(".dashboard-wizard-step").hide();
		//$("#step-"+Dashboard.wizard.currentStep).show();
	},
	setAsValid: function(bool){
		$("#step-"+Dashboard.wizard.currentStep).attr("valid",bool);
		console.log(" > Step["+Dashboard.wizard.currentStep+"] Valid: "+$("#step-"+Dashboard.wizard.currentStep).attr("valid"));
		Dashboard.wizard.setButtonStates();
	}
}


/* Uility Functions */
String.prototype.toHHMMSS = function () {
    var sec_num = parseInt(this, 10); 
    var hours   = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    var time    = hours+':'+minutes+':'+seconds;
    return time;
}
function getTimeLength(milliseconds){
	return milliseconds.toString().toHHMMSS();
}

Dashboard.formatDate = function(date, format, utc) {
    var MMMM = ["\x00", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var MMM = ["\x01", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var dddd = ["\x02", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    var ddd = ["\x03", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    function ii(i, len) {
        var s = i + "";
        len = len || 2;
        while (s.length < len) s = "0" + s;
        return s;
    }

    var y = utc ? date.getUTCFullYear() : date.getFullYear();
    format = format.replace(/(^|[^\\])yyyy+/g, "$1" + y);
    format = format.replace(/(^|[^\\])yy/g, "$1" + y.toString().substr(2, 2));
    format = format.replace(/(^|[^\\])y/g, "$1" + y);

    var M = (utc ? date.getUTCMonth() : date.getMonth()) + 1;
    format = format.replace(/(^|[^\\])MMMM+/g, "$1" + MMMM[0]);
    format = format.replace(/(^|[^\\])MMM/g, "$1" + MMM[0]);
    format = format.replace(/(^|[^\\])MM/g, "$1" + ii(M));
    format = format.replace(/(^|[^\\])M/g, "$1" + M);

    var d = utc ? date.getUTCDate() : date.getDate();
    format = format.replace(/(^|[^\\])dddd+/g, "$1" + dddd[0]);
    format = format.replace(/(^|[^\\])ddd/g, "$1" + ddd[0]);
    format = format.replace(/(^|[^\\])dd/g, "$1" + ii(d));
    format = format.replace(/(^|[^\\])d/g, "$1" + d);

    var H = utc ? date.getUTCHours() : date.getHours();
    format = format.replace(/(^|[^\\])HH+/g, "$1" + ii(H));
    format = format.replace(/(^|[^\\])H/g, "$1" + H);

    var h = H > 12 ? H - 12 : H == 0 ? 12 : H;
    format = format.replace(/(^|[^\\])hh+/g, "$1" + ii(h));
    format = format.replace(/(^|[^\\])h/g, "$1" + h);

    var m = utc ? date.getUTCMinutes() : date.getMinutes();
    format = format.replace(/(^|[^\\])mm+/g, "$1" + ii(m));
    format = format.replace(/(^|[^\\])m/g, "$1" + m);

    var s = utc ? date.getUTCSeconds() : date.getSeconds();
    format = format.replace(/(^|[^\\])ss+/g, "$1" + ii(s));
    format = format.replace(/(^|[^\\])s/g, "$1" + s);

    var f = utc ? date.getUTCMilliseconds() : date.getMilliseconds();
    format = format.replace(/(^|[^\\])fff+/g, "$1" + ii(f, 3));
    f = Math.round(f / 10);
    format = format.replace(/(^|[^\\])ff/g, "$1" + ii(f));
    f = Math.round(f / 10);
    format = format.replace(/(^|[^\\])f/g, "$1" + f);

    var T = H < 12 ? "AM" : "PM";
    format = format.replace(/(^|[^\\])TT+/g, "$1" + T);
    format = format.replace(/(^|[^\\])T/g, "$1" + T.charAt(0));

    var t = T.toLowerCase();
    format = format.replace(/(^|[^\\])tt+/g, "$1" + t);
    format = format.replace(/(^|[^\\])t/g, "$1" + t.charAt(0));

    var tz = -date.getTimezoneOffset();
    var K = utc || !tz ? "Z" : tz > 0 ? "+" : "-";
    if (!utc) {
        tz = Math.abs(tz);
        var tzHrs = Math.floor(tz / 60);
        var tzMin = tz % 60;
        K += ii(tzHrs) + ":" + ii(tzMin);
    }
    format = format.replace(/(^|[^\\])K/g, "$1" + K);

    var day = (utc ? date.getUTCDay() : date.getDay()) + 1;
    format = format.replace(new RegExp(dddd[0], "g"), dddd[day]);
    format = format.replace(new RegExp(ddd[0], "g"), ddd[day]);

    format = format.replace(new RegExp(MMMM[0], "g"), MMMM[M]);
    format = format.replace(new RegExp(MMM[0], "g"), MMM[M]);

    format = format.replace(/\\(.)/g, "$1");

    return format;
};
$.fn.scrollTo = function( target, options, callback ){
  if(typeof options == 'function' && arguments.length == 2){ callback = options; options = target; }
  var settings = $.extend({
    scrollTarget  : target,
    offsetTop     : 50,
    duration      : 500,
    easing        : 'swing'
  }, options);
  return this.each(function(){
    var scrollPane = $(this);
    var scrollTarget = (typeof settings.scrollTarget == "number") ? settings.scrollTarget : $(settings.scrollTarget);
    var scrollY = (typeof scrollTarget == "number") ? scrollTarget : scrollTarget.offset().top + scrollPane.scrollTop() - parseInt(settings.offsetTop);
    scrollPane.animate({scrollTop : scrollY }, parseInt(settings.duration), settings.easing, function(){
      if (typeof callback == 'function') { callback.call(this); }
    });
  });
}


/****************************************
 * @Dashboard.config.errors.getError(name or code)
 * Purpose: Manage error messages presented to the user.
 * Used By: Dashboard.config.errors
****************************************/
Dashboard.config.errors.getError = function(errorId){
	var tempError={};
	if(isNaN(errorId)){
		tempError = Dashboard.config.errors.findErrByName(errorId);
	} else {
		tempError = Dashboard.config.errors.findErrByCode(errorId);
	}
	var error = new Error();
	error.code = tempError.code;
	error.name = tempError.name;
	error.message = tempError.message;
	return error;
}

Dashboard.config.errors.findErrByName = function(name){
	var errors = Dashboard.config.errors.messages;
	var eLen = errors.length;
	for(var er=0; er<eLen; er++){
		if(errors[er].name.toLowerCase() == name.toLowerCase()){
			return errors[er];
		}
	}
	return errors[0];
}
Dashboard.config.errors.findErrByCode = function(code){
	var errors = Dashboard.config.errors.messages;
	var eLen = errors.length;
	for(var er=0; er<eLen; er++){
		if(errors[er].code == code){
			return errors[er];
		}
	}
	return errors[0];
}