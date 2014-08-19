if(window.console){if(!console.trace) {console.trace = console.log;}}
/* App Functions by Creative Slave */
/**
 @classdesc App is the priary controller class with modules that can be extended.
 @class
*/
var App = {
	version:2.5,
	history:[],
	user:{name:{first:'',last:''}},util:{support:{}},config:{components:{},services:{},errors:{}},settings:{},
	init: function(){
		App.console.log("Loading TeleSpine Application");
		$(".userName").html(App.user.name.first+" "+App.user.name.last);
		$("#OverlayBG").css("visibility","visible");
//		if(App.isMobile()){
//			App.user.startDate = new Date( Date.parse(App.user.startDate) );
//			App.user.lastLogin = new Date( Date.parse(App.user.lastLogin) );
//                        App.user.currentDate = new Date(App.user.currentDate);
//                        App.user.startDate = new Date(App.user.startDate.replace(/-/,"/").replace(/-/,"/"));
//                        App.user.lastLogin = new Date(App.user.lastLogin.replace(/-/,"/").replace(/-/,"/"));
//                        App.user.currentDate = new Date(App.user.currentDate.replace(/-/,"/").replace(/-/,"/"));
//		} else {
			/*App.user.startDate = new Date(App.user.startDate);
			App.user.lastLogin = new Date(App.user.lastLogin);
                        App.user.currentDate = new Date(App.user.currentDate);*/
            try{
                        App.user.startDate = new Date(App.user.startDate.replace(/-/,"/").replace(/-/,"/"));
                        App.user.lastLogin = new Date(App.user.lastLogin.replace(/-/,"/").replace(/-/,"/"));
                        App.user.currentDate = new Date(App.user.currentDate.replace(/-/,"/").replace(/-/,"/"));
                } catch (de) {
                    App.console.log("Exception in startdate, lastlogin, currentdate, setting it to new DATE()");
                    App.user.startDate = new Date();
                    App.user.lastLogin = new Date();
                    App.user.currentDate = new Date();
                }
//		}
                if(!new Date(App.user.startDate).getMonth()){
                    App.user.startDate = new Date( Date.parse(App.user.startDate) );
                    App.user.lastLogin = new Date( Date.parse(App.user.lastLogin) );
                    App.user.currentDate = new Date( Date.parse(App.user.currentDate) );
                }
		$("#loading").css("visibility","visible");
		App.load();
		App.checkUserFlow();
	},
	load: function(){
		var len= App.config.components.length;
		App.console.log("Loading App Components ("+len+")",2);
        App.console.group("Components");
		var base = App.config;
        for(var c=0; c<len; c++){
			var component = App.config.components[c];
			App.console.log("App.load: Reading Component: "+component.id);
			try{
				/* Component Collapsed State Cookie*/
				if(!$.cookie(component.id+"_menu")){
					$.cookie(component.id+"_menu", 'open', { expires: 7, path: '/' });	
				}
            } catch(cookieE){App.console.trace(cookieE);}
			try{
				if(component.active && component.url!="void"){
					App.console.log(component,3);	
					App.console.log(" > App.load: Loading Component: ("+component.id+") from "+component.url,3);
					$.ajax({
					   type: 'GET',
						url: component.url,
						async: false,
						contentType: "application/text",
						success: function(response) {
						   $("#col_"+component.column).append(response+'<div class="clearfix"></div>');
						   try{
							   if(component.onLoad!="void"){App[component.onLoad](component);}
							} catch(compError){
                                App.console.trace("App.load: Error executing function ("+component.onLoad+"): "+ compError);
							}
							try{
							   /* Component Collapsed State Cookie*/
							   var isOpen  = ($.cookie(component.id+"_menu")=='open');
							   if(!isOpen){
								   $("#"+component.id+" div.box-content").slideUp();
							   }
							} catch(compError){
                                App.console.trace(compError);
							}
						},
						error: function(a,b,d) {
						   App.console.log(" > > App.load: Ajax Error when invoking "+component.id+": "+a+"; "+b+"; "+d);
						}
					});
				} else {
					App.console.log(" > App.load: Skip load component: "+ component.id,3);	
					if(component.active && component.url=="void"){
						App[component.onLoad]();
					}
				}
			} catch (ajaxErrorOuter){
                App.console.trace(" > > App.load: Ajax Outer Error when invoking "+component.id+": "+ajaxErrorOuter.message);
			}
		}
				App.console.groupEnd();
		setTimeout(function(){
			$("#loading div").html("");
			$("#loading").animate({
					width:"10%",
					height:"10%",
					"margin-left":"-5%",
					opacity:0.0
				},1000,"easeInOutBack")
			setTimeout(function(){
				$("#OverlayBG").animate({opacity:0.0},600,"easeInOutBack");
				setTimeout(function(){
					$("#loading").css("visibility","hidden");
					$("#OverlayBG").css("visibility","hidden");
				},500);
			},500);
		},300);
	},
	getComponent: function(id){
		try{
			var len= App.config.components.length;
            for(var c=0; c<len; c++){
				App.console.log(" > > App.getComponent: "+App.config.components[c].id,4);	
				if(App.config.components[c].id == id){
					return App.config.components[c];
				}
			}
        } catch(compE){App.console.trace(" > App.getComponent(): Error "+compE);return null;}
	},
	showHelp: function(componentId){
		App.console.log("function :: App.showHelp("+componentId+")");
		var title = [];
		title["painMeter"] = "Current Pain Level Overview";
		title["functionalScore"] = "Functional Score Overview";
		title["goalsAndStatistics"] = "Goals and Statistics Overview";
		title["healthyHabits"] = "Healthy Back Reminders";
                title["timeline"] = "Daily Activity Timeline Overview";
		title["today"] = "Today's Activities Overview";
		title["other"] = "Overview";
		$('div.dialog-help').hide();
		$("#overviewTitle").html(title[componentId]);
		$('#dialog-'+componentId).show();	
	},
	initServiceCall: function(service){
		App.console.log("service :: App.initServiceCall for "+service.name);
		$.ajax({
		   type: 'GET',
			url: service.url,
			data: service.data,
			async: false,
			contentType: "application/text",
			success: function(response) {
                try{service.callback(response);} catch(sce){console.trace("Error while making a service call with: \""+service.name+"\": "+sce);}
			},
			error: function(ajaxErr) {
			   App.console.log(" > App.callService Ajax Error when invoking "+componentId+": "+ajaxErr.message);
			}
		});
	},
	callService: function(componentId){
		App.console.log("service :: App.callService for "+componentId);
		var base = App;
		if(App.getComponent(componentId)){
			$.ajax({
			   type: 'GET',
				url: App.getComponent(componentId).service.url,
				async: false,
				contentType: "application/text",
				success: function(response) {
				   App.getComponent(componentId).service.callback(response);
				},
				error: function(ajaxErr) {
				   App.console.log(" > App.callService Ajax Error when invoking "+componentId+": "+ajaxErr.message);
				}
			});
		}
	},
	callServiceModule: function(obj){
		App.console.log("App.callServiceModule >  for "+obj.componentId);
		if(App.getComponent(obj.componentId)){
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
				   App.console.log(" > Ajax Error when invoking "+obj.componentId+": "+ajaxErr.message);
				}
			});
		}
	},
	displayComponent: function(component, bool){
		component.active = bool;
		try{
			if(bool){$("#"+component.id+"_comp").show();} else {$("#"+component.id+"_comp").hide();}
        }catch(compErr){App.console.trace(compErr);}
	},
	isMobile: function(){
            return (/Android|Nokia|Samsung|Doris|SonyEricsson|Presto|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent));
	},
	console:{
		log: function(l, level/*:int*/){
			try{
                if(App.settings.logging==true && window.console){
					if(level==null){
						if(console) console.log(l);
					} else if(level<=App.settings.loggingLevel){
						if(console) console.log(l);
					}
				}
            } catch(e){console.trace(e);}
        },
				trace: function(l, level/*:int*/){
            try{
                if(App.settings.logging==true && window.console){
                    if(level==null){
                        if(console.trace) {
                            console.trace(l);
                        } else {
                            console.trace = console.log;
                            console.log(l);
                        }
                    } else if(level<=App.settings.loggingLevel){
                        if(console) console.trace(l);
                    }
                }
            } catch(e){console.trace(e);}
        },
				group: function(l, level/*:int*/){
            try{
                if(App.settings.logging==true && window.console){
                    if(level==null){
                        if(console) console.group(l);
                    } 
                }
			} catch(e){console.warn(e);}
        },
				groupEnd: function(l, level/*:int*/){
            try{
                if(App.settings.logging==true && window.console){
                    if(console) console.groupEnd();
		}
            } catch(e){console.trace(e);}
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
		return (App.history[arg]);
	},
	setHistory: function(arg,val){
		App.history[arg] = val;
	},
	getHistory: function(arg){
		return App.history[arg];
	}
}
/* Objects */
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
/* END App Controller */
/* Start Extend App Controller Class */
/****************************************
 * @method
 * @author
 * @param
    openFirstTimeResetPassword
 * Purpose: Step 1
 * Type: Overlay Component
 * Used By: App Onboarding Process
****************************************/
App.openFirstTimeUser = function(callback){
	App.console.log(" First Time User Message welcome screen.",2);
	$("#FirstTimeUser").modal({backdrop: 'static',keyboard: false});
	$("#FirstTimeUser").on('hidden.bs.modal', 
		function(e){
			App.console.log(" Closing First Time User Message welcome screen.",3);
			callback();
		}
	);
};


/****************************************
 * @method
 * @author
 * @param
    openInformationMessage
 * Purpose: Step 1
 * Type: Overlay Component
 * Used By: App Onboarding Process
****************************************/
App.openInformationMessage = function(callback){
	App.console.log(" First Time User Message welcome screen.",2);
	$("#ie8message").modal({backdrop: 'static',keyboard: false});
	$("#ie8message").on('hidden.bs.modal', 
		function(e){
			App.console.log(" Closing First Time User Message welcome screen.",3);
			callback();
		}
	);
};
/****************************************
 * @method
 * @author
 * @param
    openFirstTimeResetPassword
 * Purpose: Step 2
 * Type: Overlay Component
 * Used By: App Onboarding Process
****************************************/
App.openFirstTimeResetPassword = function(callback){
	App.console.log(" First Time User Reset Password.",2);
	if(App.user.password.status!="complete"){
		$("#ResetPassword").modal({backdrop: 'static',keyboard: false});
		try{
			inForm.init("changePassword-form","fieldError",function(){
				try{
					$.ajax({
						type: 'GET',
//						url: App.user.resetPassword.service.url,
                                                url: "index.php?action=changepass_firsttime",
						async: false,
						data: {password:$("#newPassword").val()},
						contentType: "application/json",
						success: function(response) {
								App.console.log(" Response: "+response);
								$('#ResetPassword').modal('hide');
								callback();
						},
						error: function(ajaxErr) {
							 App.console.log(" > Ajax Error when invoking password update: "+ajaxErr.message);
						}
					});
				} catch (es){
                    App.console.trace(" > Set Password Error: "+es);
                };
				return false;
			});
        } catch(ets){};
	} else {
		callback();
	}
};


/****************************************
 * @method
 * @author
 * @param
    openOswestery
 * Purpose: Step 3
 * Type: Oswestery Overlay Component
 * Used By: App Onboarding Process
****************************************/
App.openOswestery= function(callback){
	App.console.log("\n****************\nOpen Oswestery - Oswestery",2);
	try{
		App.wizard.init("OswesteryWiz","OswesteryNextBtn","OswesteryPreviousBtn",
			function(){
				App.saveOswestery();	
				callback();
			}
		);
	} catch (ej){
        App.console.trace(" Pain Level Error: "+ej);
	};
	$('#OswesteryModal').modal({backdrop: 'static',keyboard: false});
};
/****************************************
 * @method App.saveOswestery
 * @author
 * @param
    saveOswestery
 * Purpose: To reload the oswestry graph with updated scores fetched from 
 * backend script.
 * Response data should be in format [34,70] i.e. [oswestry_score, painscale]
 * Type: Overlay Component
 * Used By: App Onboarding Process, Oswestery modal
****************************************/
App.saveOswestery = function(){
	App.console.log("Save Oswestery!");
	var fs = App.getComponent("functionalScore");
	inForm.setFormId("Oswestery-form");
	var OswesteryData = inForm.getFormJSON();
	try{
		$.ajax({
				type: 'GET',
				url: App.config.services.oswestery.url,
				async: false,
				data:  {
                                    oswestrydata: OswesteryData,
                                    surveytakenon: timeline.getDaysSinceStart()
                                },
				contentType: "application/json",
				success: function(response) {
					App.console.log(" Response: "+response, 5);
                                        var score = response.split(",");
                                        if( !isNaN(score[0]) && !isNaN(score[1]) )
                                        {
                                            App.console.log(" Oswestery resulting Functional Score: "+score, 3);
                                            App.user.oswestery.scores.push(score);
                                            App.console.log("New scores :: "+App.user.oswestery.scores);
                                            App.setFunctionalScore(fs);
                                        }
				},
				error: function(ajaxErr) {
					App.console.log(" > Ajax Error when invoking Oswestery: "+ajaxErr.message);
				}
		});
	} catch (es){
        App.console.trace(" > Save Oswestery Error: "+es);
    };
	$('#OswesteryModal').modal('hide');
	/** TODO: move to Flow Control */
	App.console.log("Close Oswestery!\n****************",2);
};
/****************************************
 * @method @App.openR4CSurvey
 * @author
 * @param
    openR4CSurvey
 * Purpose: Step 3
 * Type: Oswestery Overlay Component
 * Used By: App Onboarding Process
****************************************/
App.openR4CSurvey= function(callback){
	App.console.log("\n****************\nOpen Ready for Change - Survey",2);
	try{
		App.wizard.init("R4CSurveyWiz","R4CSurveyNextBtn","R4CSurveyPreviousBtn",
			function(){
				App.saveR4CSurvey();	
				callback();
			}
		);
	} catch (ej){
        App.console.trace(" Pain Level Error: "+ej);
    };;
	$('#R4CSurveyModal').modal({backdrop: 'static',keyboard: false});
};
/****************************************
 * @method @App.saveR4CSurvey
 * @author
 * @param saveR4CSurvey
 * Purpose:
 * Type: Overlay Component
 * Used By: App Onboarding Process, R4CSurvey modal
****************************************/
App.saveR4CSurvey = function(){
	App.console.log("Save R4CSurvey!");
	var fs = App.getComponent("functionalScore");
	inForm.setFormId("R4CSurvey-form");
	var R4CSurveyData = inForm.getFormJSON();
        App.console.log(R4CSurveyData);
	try{
		$.ajax({
				type: 'GET',
				url: App.config.services.r4CSurvey.url,
				async: false,
				data: {
                                    r4csurveydata: R4CSurveyData,
                                    surveytakenon: timeline.getDaysSinceStart()
                                },
				contentType: "application/json",
				success: function(response) {
					App.console.log(" Response: "+response, 5);
					var score = response.split(",");
					App.console.log(" R4CSurvey resulting Functional Score: "+score, 3);
//					App.user.R4CSurvey.scores.push(score);
				},
				error: function(ajaxErr) {
					App.console.log(" > Ajax Error when invoking R4CSurvey: "+ajaxErr.message);
				}
		});
	} catch (es){
        App.console.trace(" > Save R4CSurvey Error: "+es);
    };
	$('#R4CSurveyModal').modal('hide');
	App.console.log("Close R4CSurvey!\n****************",2);
};
/**
 * @classdesc App.checkUserFlow() : App.load() will call the designated flow if conditions meet
 * the criteria. A flows are set in the config.js file. All flows are preconfigured. 
 * Usually set by the day and what has been and has not been done determines the 
 * execution of a flow. This uses config.js > App.config.userFlows
 * 
 * App.load()
 *   >  App.checkUserFlow()
 *      >  App.flow.init()
 **/
App.checkUserFlow = function(){
     if( $("htmlie8").hasClass("ie8") ) {
	$("#ie8message").modal({backdrop: 'static',keyboard: false});
	$("#ie8message").on('hidden.bs.modal', 
		function(e){
			App.console.log(" Closing Tip1.",3);
			callback();
		}
	);
    };
	App.console.log("Check for User Flow > Start");
    App.console.group("User Flow");
    if(App.config.userFlows){
	var flows = App.config.userFlows;
	var flen = App.config.userFlows.length;
	var day = timeline.getDaysSinceStart();
	App.console.log(" > Today: "+day+"; User Flows: "+flen);
        for(var f=0; f<flen; f++){
		try{
			var flow = flows[f];
			App.console.log(" > User Flow: On Day: "+flow.trigger.onDay+"; Day: "+flow.trigger.day);
			var propertySet = [];
			var hasProperty = false;
			var properties = "";
			if(flow.trigger.property!=undefined){
				properties = flow.trigger.property;
				hasProperty = true;
			}
			App.console.log(" > > User Flow: Properties: "+properties,4);
			if(hasProperty){
				App.console.log(" > > User Flow: Properties: ",4);
				App.console.log(" > > Check Property > "+flow.trigger.property.toString(),4);
				App.console.log(" > > > Value > "+flow.trigger.value,4);
			}
			if(flow.trigger.onDay){
				App.console.log(" > > "+day+"="+flow.trigger.day);
				if(day>=flow.trigger.day && hasProperty){
					if(flow.trigger.property==flow.trigger.value){
							/* If has property, must check. Call Flow and break*/
							if(day>flow.trigger.day && hasProperty){
								App.console.log(" > > User missed the userflow for day "+flow.trigger.day+
									" so re-engaging user to complete flow until complete. ",3);
							}
							App.console.log(" > > > ************** > ",4);
							App.console.log(" > > > Initiate Flow!",4);
							App.console.log(" > > > ************** > ",4);
							App.flow.init(flow.flow);
							App.console.log(" > Found, initiate User Flow",4);
							break;
						}
				} else if(day==flow.trigger.day){
					App.console.log(" > > > **************",4);
						App.console.log(" > > > Initiate Flow!",4);
						App.console.log(" > > > **************",4);
						App.flow.init(flow.flow);
						App.console.log(" > Found, initiate User Flow",4);
						break;
				}
			} else if(hasProperty){
				if(flow.trigger.property==flow.trigger.value){
					/* If has property, must check. Call Flow and break*/
					App.console.log(flow.trigger.property+" = "+flow.trigger.value);
					App.console.log(" > > > **************",4);
					App.console.log(" > > > Initiate Flow!",4);
					App.console.log(" > > > **************",4);
					App.flow.init(flow.flow);
					break;
                    };
                };
            } catch (configError){
                App.console.trace(configError);
				}
        };
        App.console.log("Check for User Flow > Done",2);
    } else {
        App.console.log("Not checking for User Flows > Not configured.",2);
			}
    App.console.groupEnd();
};
/**
 * @class
 * @classdesc App.flow controls the even chain for the flows.
 * When one flow is complete it checks for the next if there is one.
 * @type object
 */
App.flow= {
	flows: {},
	flowlength:0,
	currentItem:0,
	next: function(nextItem){
		App.console.log(" > Flow Next >  ", 2);
		nextItem.call(function(){
			App.flow.currentItem++;
			if(App.flow.currentItem<=App.flow.flowlength){
				if(App.flow.flows[App.flow.currentItem]) {
					App.flow.next(App.flow.flows[App.flow.currentItem]);
				}
			}
		});
	},
	init: function(flows){
		App.flow.flows = flows;
		App.flow.flowlength = App.flow.flows.length;
		App.flow.currentItem = 0;
		App.flow.next(App.flow.flows[App.flow.currentItem])
	}
};
/****************************************
 * @method @App.openTip
 * @author
 * @param
    openTip
 * Purpose:
 * Type: Overlay Component
 * Used By: App Onboarding Process
****************************************/
App.openTip = function(domId, callback){
	App.console.log(" Open Tip: First Time User Message welcome screen.",3);
	if(App.user.startDate = new Date()){
		$("#"+domId).modal({backdrop: 'static',keyboard: false});
	}
};

/****************************************
 * @method @App.openTip1
 * @author
 * @param
    openTip1
 * Purpose:
 * Type: Overlay Component
 * Used By: App Onboarding Process
****************************************/
App.openTip1 = function(callback){
	App.console.log(" Open Tip: 1",2);
	$("#Tip1").modal({backdrop: 'static',keyboard: false});
	$("#Tip1").on('hidden.bs.modal', 
		function(e){
			App.console.log(" Closing Tip1.",3);
			callback();
		}
	);
};

/****************************************
 * @method @App.openTip2
 * @author
 * @param
    openTip2
 * Purpose:
 * Type: Overlay Component
 * Used By: App Onboarding Process
****************************************/
App.openTip2 = function(callback){
	App.console.log(" Open Tip: 2",2);
	$("#Tip2").modal({backdrop: 'static',keyboard: false});
	$("#Tip2").on('hidden.bs.modal', 
		function(e){
			App.console.log(" Closing Tip2.",3);
			callback();
		}
	);
};

/****************************************
 * @method App.openTip3
 * @author
 * @param
    openTip1
 * Purpose:
 * Type: Overlay Component
 * Used By: App Onboarding Process
****************************************/
App.openTip3 = function(callback){
	App.console.log(" Open Tip: 3",2);
	$("#Tip3").modal({backdrop: 'static',keyboard: false});
	$("#Tip3").on('hidden.bs.modal', 
		function(e){
			App.console.log(" Closing Tip3.",3);
			callback();
		}
	);
};

/****************************************
 * @method @App.timeline
 * @author
 * @param
    timeline
 * Purpose:
 * Used By: WeeklyTimeline
****************************************/
App.timeline = function(){
	timeline = new WeeklyTimeline();
	App.timeline = timeline;
	App.timeline.init();
	setTimeout(
		function(){
			App.timeline.setActivities(App.user.activities);
		},2200
	);
};

/****************************************
 * Messages
 ****************************************/
/****************************************
 * @method
 * @author
 * @param
    updateGoal
 * Purpose:
 * Used By: Timeline
 ****************************************/
App.getDayMessage= function(dayNum){
    App.console.log("App.getDayMessage("+dayNum+")",2);
    
    /*TODO: Change Services URL to reference config */
    $.ajax({
        type: 'GET',
        data:{day:dayNum},
        url: App.config.services.getDayMessage.url,
        async: false,
        contentType: "application/text",
        success: function(response) {
            
            if(response.search("Login to your account")== -1){
            var h;
            $("#getDayMessage").html(response);
            if($("#dailyActivities2").height() > $("#getDayMessage").height()){
                h=$("#dailyActivities2").height();
                console.log('dailyActivities2 is bigger');
            }else{
                h=$("#getDayMessage").height();
                console.log('getDayMessageis bigger');
            }
            $("#separator").height(h);
            }
            else{
                $("#getDayMessage").html("<div style=\"display:block;\" class=\"errorMessage\" id=\"login-form-message\">Someone has logged in as this user from a different computer or browser window. Only one person may login as a given user at a time. As a consequence, this session has been terminated.Please <a href=\"/index.php\"> click here </a> to login again</div>");
            }
        },
        error: function(a,b,d) {
            App.console.log(" > > Ajax Error: "+a+"; "+b+"; "+d);
        }
    });
    return false;
};

App.preference = function(name,value){
	var COOKIEID = "TelespinePrefs"
	var preferences = {
		components:["weeklyTimeline:0","goalsAndStatistics:0","painMeter:0","functionalScore:0","onlineCoaching:0","messages:0","today:0"],
		theme:"default",
		configuration: {
		   resetPassword:		true,
		   Oswestery: {
				load:			true
		   },
		   lastPainLevel:		0,
		   notifiedOn: (new Date()).toString(),
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
};


/****************************************
 * @method @App.setStats
 * @author
 * @param
    setStats
 * Purpose:
 * Used By: 
****************************************/
App.setStats= function(component){
	App.console.log(" App.setStats()");
	try{
		var percent = Math.round(App.user.stats.goals[0]/App.user.stats.goals[1]*100);
                if(isNaN(percent))
                {
                    percent = 0;
                }
		$("#MyLogins").html(App.user.stats.logins);
		$("#GoalsInterface").fadeOut();
        $("#GoalsInterface").html('<i class="fa fa-thumbs-up"></i><span class="percent">%</span>'+
		'<input id="MyGoals" type="text" value="'+ percent +'" class="circleChart" />');
		$("#GoalsInterface").fadeIn();
    } catch (pe){console.trace(pe);};
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
    } catch(st){};
	try{
			App.console.log(" Setting click events to Goals", 3);
			$(".goal-actions > a").unbind('click');
			$(".goal-actions > a").click(function(e){
				App.console.log("Goal Click");
				App.updateGoal(e);
				return false;
			});
        /*};*/
    } catch(st){App.console.trace(st);};
};
/****************************************
 * @method @App.setPainLevel
 * @author
 * @param
    setPainLevel
 * Purpose:
 * Used By: 
****************************************/
App.setPainLevel= function(){
	App.console.log("function :: setPainLevel");
	var newPain = $(".pain-level-control").html();
	$.ajax({
	   type: 'GET',
        data:{painLevel:newPain},
		url: App.config.services.updatePainLevel.url,
		async: false,
		contentType: "application/text",
		success: function(response) {
		   App.console.log(" > > Update Pain Response: "+response);
			$.gritter.add({
				title: 'Pain Level Updated!',
				text: 'Thank you! You have added a status update to your pain level. You set it to: '+newPain+' out of 10.',
				class_name: 'my-sticky-class'
			});
		   var c = App.getComponent("painMeter");
		   App.user.pain.history.push(newPain);
		   App.user.pain.lastSet = new Date();
			$("#pailControl").hide();
			$(".pain-bubble").slideDown(300);
			var color = "#e4d700";
			if(parseInt(newPain) > 6){
				color = "#ff5454";
			} else if(parseInt(newPain) < 4){
				color = "#78cd51";
			}
			$(".pain-bubble").css("background-color",color);
			try{
				$("#painLevelGraph").html('<div id="pain-graph" class="chart red" style="width:100%">'+ App.user.pain.history.toString() +'</div>');
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
										$("#pain-graph").css("MozTransform","scale(0.5,0.5)").css("margin","-15px 15px -15px -40px");
				} else {
					$("#painLevelGraph").css('zoom',0.6);
                };
            } catch(pe){App.console.trace(pe);};
		},
		error: function(a,b,d) {
		   App.console.log(" > > Ajax Error: "+a+"; "+b+"; "+d);
		}
	});
};
/****************************************
 * @method @App.setPainLevelGraph
 * @author
 * @param
    setPainLevelGraph
 * Purpose:
 * Used By: 
****************************************/
App.setPainLevelGraph= function(component){
	App.console.log("callback :: setPainLevelGraph()");
	try{
		var lastPainLevel = App.user.pain.history[App.user.pain.history.length-1];
		//alert(lastPainLevel);
		if(!lastPainLevel){
			lastPainLevel = 0;	
		}
		var range_map = $.range_map({
			'3': 'green',
			'4:6': 'yellow',
			'7:': 'red'
		})
		$("#pain-graph").html(App.user.pain.history.toString());
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
		var lastSet = new Date(Date.parse(App.user.pain.lastSet));
                var curedate= new Date(Date.parse(App.user.currentDate));
		//var daysSince = daysBetween(lastSet, new Date());
                var daysSince = daysBetween(lastSet, curedate);
		if(daysSince<1){
			$("#pailControl").hide();
			$(".pain-bubble").slideDown(300);
			var color = "#e4d700";
			if(parseInt(lastPainLevel) > 6){
				color = "#ff5454";
			} else if(parseInt(lastPainLevel) < 4){
				color = "#78cd51";
			}
			$(".pain-bubble").css("background-color",color);
		}
	} catch(e){App.console.trace(" Error setPainLevelGraph : "+e);}	
};
/****************************************
 * @method App.setFunctionalScore
 * @author
 * @param
    setFunctionalScore
 * Purpose:
 * Used By: 
****************************************/
App.setFunctionalScore= function(component){
	App.console.log("callback :: setFunctionalScore()");
	try{
		var slen = App.user.oswestery.scores.length-1;
		$("#currentFunctionalScore").html(App.user.oswestery.scores[slen][0]+ "%");
		$("#currectPain").html( (App.user.oswestery.scores[slen][1]/10) + "/10");/*"*/
        for(var s=0; s<4; s++){this.setScore(component, s);};
    } catch(e){App.console.trace(" Error setFunctionalScore : "+e);};
};
/****************************************
 * @method @App.setScore
 * @author
 * @param
    setScore
 * Purpose:
 * Used By: setFunctionalScore
****************************************/
App.setScore= function(component, Oswestery){
	App.console.log(" > setScore(component, Oswestery)" );
	try{
		var score = App.user.oswestery.scores[Oswestery];
		$('.f-score-'+(Oswestery+1)).animate(
			{'top':(100-score[0])+'%'},2500,"easeInOutBack"
		);
		$('.f-score-'+(Oswestery+1)).html(score[0]+'%');
		$('.p-score-'+(Oswestery+1)).animate(
			{'top':(100-score[1])+'%'},2500,"easeInOutBack"
		);
		$('.p-score-'+(Oswestery+1)).html(score[1]+'%');
		App.console.log(" > > Functional Score from Oswestery "+(Oswestery+1)+": "+ (100-score[0]));
		App.console.log(" > > Pain Score from Oswestery "+(Oswestery+1)+": "+ (100-score[1]));
	} catch (se){
        /*App.console.log("Expected Exception: "+ se+ " (This is expected because it is not yet set)");*/
		$('.f-bar-'+(Oswestery+1)).html('Pending');
	}
};

/****************************************
 * GOALS
****************************************/
/****************************************
 * @method @App.updateGoal
 * @author
 * @param
    updateGoal
 * Purpose:
 * Used By: addGoalsService
****************************************/
App.updateGoal= function(event){
	App.console.log("App.updateGoal > "+$(event.currentTarget).attr("id"),2);
        App.console.log(event);
	var statusVal = "";
	var goalID = $(event.currentTarget).attr("id");
	var StatisticsComponent = App.getComponent('goalsAndStatistics');
	var obj = event.currentTarget;
	try{
		App.console.log(" > App.updateGoal > DOM Reference: "+ obj, 5 );
                App.console.log(obj, 5 );
	} catch (e){console.trace(e);}
	try{
		if ($(event.currentTarget).find('i').attr('class') == 'fa fa-square-o') {
			$(event.currentTarget).find('i').removeClass('fa-square-o').addClass('fa-check-square-o');
			$(event.currentTarget).parent().parent().find('span').css({ opacity: 0.45 });
			$(event.currentTarget).parent().parent().find('.desc').css('text-decoration', 'line-through');
			/* Set Status Action to Complete. */
                        statusVal = '2';
			App.console.log(" > > Set Status Action to Complete",3);
//                        App.user.stats.goals[0]+=1;
		} else {
			$(event.currentTarget).find('i').removeClass('fa-check-square-o').addClass('fa-square-o');
			$(event.currentTarget).parent().parent().find('span').css({ opacity: 1 });
			$(event.currentTarget).parent().parent().find('.desc').css('text-decoration', 'none');
			/* Set Status Action to active. */
                        statusVal = '1';
			App.console.log(" > > Set Status Action to Active.",3);
//			App.user.stats.goals[0]-=1;
		}
	} catch(eft){console.trace(eft);}
	/*TODO: Change Services URL to reference config */
	$.ajax({
	   type: 'GET',
	   data:{status:statusVal,id:goalID},
		url: App.config.services.updateGoal.url,
		async: false,
		contentType: "application/text",
		success: function(response) {
                    App.user.stats.goals[0] = response;
                    App.console.log(" > > Update Goal Response: "+response,3);
		},
		error: function(a,b,d) {
                    App.console.log(" > > Ajax Error: "+a+"; "+b+"; "+d);
		}
	});
	App.setStats(StatisticsComponent);
	return false;
};
/****************************************
 * @method @App.addGoal
 * @author
 * @param
    addGoal
 * Purpose:
 * Used By: addGoalsService
****************************************/
App.addGoal= function(){
	App.console.log("App.addGoal()",2);
	if($("#AddGoal").val().length>0){
		var StatisticsComponent = App.getComponent('goalsAndStatistics');
		var goal = $("#AddGoal").val();
		$("#AddGoal").val("");
		App.initServiceCall(App.addGoalsService(goal));
		$("#GoalsList").html($("#GoalsList").html().replace("{value}",goal));
	} else {
		$.gritter.add({
			title: 'Add Goal - Issue',
			text:  'We could not add the goal, it appears to be blank. Please try again.',
			class_name: 'my-sticky-class'
		});
	}
}

/****************************************
 * @method @App.addGoalsService
 * @author
 * @param
    addGoalsService
 * Instance Of: ServiceCall()
 * Used By: 
****************************************/
App.addGoalsService = function(goal){
	var service = new ServiceCall("goalsAndStatistics");
	service.name = "addGoalsService";
	service.url = App.config.services.addGoal.url,
        service.data = {goal: goal};
	service.callback = function(response){
             if(response.search("Login to your account")== -1){
		$("#GoalsList").prepend(response);
		var component = App.getComponent(service.componentId);
		App.user.stats.goals[1]+=1;
		App.setStats(component);
             }else{
                 $("#GoalsList").html();
                 $("#GoalsList").html("<div style=\"display:block;\" class=\"errorMessage\" id=\"login-form-message\">Someone has logged in as this user from a different computer or browser window. Only one person may login as a given user at a time. As a consequence, this session has been terminated.Please <a href=\"/index.php\"> click here </a> to login again</div>"); 
             }
	};
	return service;
};


/****************************************
 * @method @App.displaydayvideo
 * @author
 * @param
   openMovie
 * Purpose:
 * Used By: videopage
****************************************/
App.displaydayvideo= function(day){
	try{
		
		$("#dayV").html();
        $("#dayV").html(day);
		
		$.ajax({

	   type: 'GET',

	   data:{day:day},

		url: 'index.php?action=getdaywiserightsection',

		async: false,

		contentType: "application/text",

		success: function(response) {

		     if(response.search("Login to your account")== -1){
                        App.console.log(" > > Update watched video: "+response);
                        $("#dayVd").html();
                        $("#dayVd").html(response);
                        }else{
                            $("#dayVd").html("<div style=\"display:block;\" class=\"errorMessage\" id=\"login-form-message\">Someone has logged in as this user from a different computer or browser window. Only one person may login as a given user at a time. As a consequence, this session has been terminated.Please <a href=\"/index.php\"> click here </a> to login again</div>");
                        }
   

		},

		error: function(a,b,d) {

		   App.console.log(" > > Ajax Error: "+a+"; "+b+"; "+d);

		}

	});
				 
		
                
	} catch(ea){App.console.trace(ea);}
}



/****************************************
 * @method @App.displaydayvideo
 * @author
 * @param
   openMovie
 * Purpose:
 * Used By: videopage
****************************************/
App.displaydayarticle= function(day){
	try{
		
		$("#dayV").html();
        $("#dayV").html(day);
		
		$.ajax({

	   type: 'GET',

	   data:{day:day},

		url: 'index.php?action=getdaywisearticlerightsection',

		async: false,

		contentType: "application/text",

		success: function(response) {
                    if(response.search("Login to your account")== -1){
                        App.console.log(" > > Update watched video: "+response);
                        $("#dayVd").html();
                        $("#dayVd").html(response);
                        }else{
                            $("#dayVd").html("<div style=\"display:block;\" class=\"errorMessage\" id=\"login-form-message\">Someone has logged in as this user from a different computer or browser window. Only one person may login as a given user at a time. As a consequence, this session has been terminated.Please <a href=\"/index.php\"> click here </a> to login again</div>");
                        }
   

		},

		error: function(a,b,d) {

		   App.console.log(" > > Ajax Error: "+a+"; "+b+"; "+d);

		}

	});
				 
		
                
	} catch(ea){App.console.trace(ea);}
}

/****************************************
 * @method @App.openMovie
 * @author
 * @param
    openMovie
 * Purpose:
 * Used By: timeline
****************************************/
App.openMovie= function(a){
	try{
		var activity = App.user.activities[a];
		App.console.log(" > > Activity: "+ activity,2);	
		var videoProps;
		/* Custom code to set movie and open modal. */
		App.openMovieOverlay(activity.title, activity.media,activity.id,activity.canvasurl,'dashboard',activity.videoprop);
                
	} catch(ea){App.console.trace(ea);}
}

App.openMovieOverlay= function(title,url,id,canvasurl,from,videoProps){
	try{
		App.console.log("Video URL: "+url);
                url= url+'?'+(new Date).getTime();
                
		/* Custom code to set movie and open modal. */
		$('#week-day-view').modal('hide');
		$("#videoName").html(title);
		//$("#overlayVideo").attr("src",url);
		$("#overlayVideo").attr("title",title);
		var ogvUrl = url.substring(0,url.lastIndexOf(".")+1)+"ogv"+"?"+(new Date).getTime();
		
		var mptxt='<source src="'+url+'" type="video/mp4" />';
		
		var ogvtxt='<source src="'+ogvUrl+'" type="video/ogg" />';
		
		$("#overlayVideo").empty();
                //$("#overlayVideo").remove();
		
		$("#overlayVideo").append(mptxt);
		$("#overlayVideo").append(ogvtxt);
		$("#overlayVideo").load();
                //$("#overlayVideo").play();
		try{
            var videoProperties="";
            for (var key in videoProps) {
                var val = videoProps[key];
               
                videoProperties += "<div class='row'><div class='col-sm-2 propName'>"+ key +"</div><div class='col-sm-10 propValue'>"+ val +"</div></div>";
            }
			
            $("#videoProperties").html(videoProperties);
        } catch(ve){App.console.trace(ve);
            $("#videoProperties").html(ve.message);}
		
		
		canvasurl=canvasurl+"?"+(new Date).getTime();
		$("#videoSmallName").html(title);
		$('#overlayVideo').attr('poster', canvasurl);
		$("#videoTime").html("loading...");
		$("#overlayVideo").on("timeupdate", 
			function(event){
			  $("#videoTime").html("Playing:&nbsp; "+ getTimeLength(this.currentTime) +",&nbsp;&nbsp; <b>Duration:&nbsp; "+ getTimeLength(this.duration) +"</b>");
			}
		);
		
		//$("#videoViewer").modal("show");
		 $("#videoViewer").modal({backdrop: 'static',keyboard: false});
        $("#videoViewer").modal('show');
		$("#overlayVideo")[0].autoplay = true;
		
		$.ajax({

	   type: 'GET',

	   data:{id:id,from:from},

		url: 'index.php?action=markwatched',

		async: false,

		contentType: "application/text",

		success: function(response) {

		   App.console.log(" > > Update watched video: "+response);
   

		},

		error: function(a,b,d) {

		   App.console.log(" > > Ajax Error: "+a+"; "+b+"; "+d);

		}

	});
		
		
		
		
	} catch(ea){App.console.trace(ea);}
}

/****************************************
 * @method @App.showMessage
 * @author
 * @param
    showMessage
 * Purpose: Expand the Message Center component if the message is less than 1 week old.
 * Used By: Message Center
****************************************/
App.showMessage = function(){
	App.console.log(" Message Center - ShowMessage()");
	if(App.user.notification.notifiedOn){
		var mDate = new Date( Date.parse(App.user.notification.notifiedOn) );
		var daysBtw = App.daysBetween(mDate, new Date());
		App.console.log(" > Message > Days since last new message: "+daysBtw);
		if(parseInt(daysBtw) <= 7){
			$("#messageCenterTitle").html("You have a new message!")
			$("#messageCenterContent").slideDown(800);
		}
	}
};
/****************************************
 * @method @App.openArticle
 * @author
 * @param
    openArticle
 * Purpose:
 * Used By: timeline
****************************************/
App.openArticle= function(a){
	try{
		App.console.log(" Open Article",2);
		var activity = App.user.activities[a];
		App.console.log(" > > Activity: "+ activity,2);	
		App.openArticleOverlay(activity.label, activity.media,activity.id,activity.plan_id,'dashboard');
	} catch(ea){App.console.trace(ea);}
};

App.openArticleOverlay= function(title,url,articleid,planid,from){
	try{
		App.console.log(" Open Article Overlay",2);
		//$('#week-day-view').modal('hide');
		/* Custom code to set movie and open modal. */
		//$("#pdfName").html("<i class='fa fa-external-link' style='font-size:16px;font-weight:900;color:orange'></i>&nbsp; <a href='"+url+"' target='Article'>"+title+"</a>");
		//$("#articleName").html(title);
		
		/*
		//var info = getAcrobatInfo();
		
		if(info.acrobat!='installed'){
		
		$("#articleViewer1").modal("show");
		
		}
		*/
		var win = window.open(url, '_blank');
				if(win){
					//Browser has allowed it to be opened
					win.focus();
				}else{
					//Broswer has blocked it
					alert('Please allow popups for this site');
				}
		
		
		
		
		$.ajax({

	   type: 'GET',

	   data:{aid:articleid,plan_id:planid,from:from},

		url: 'index.php?action=markreadarticle',

		async: false,

		contentType: "application/text",

		success: function(response) {

		   App.console.log(" > > Update watched video: "+response);
   

		},

		error: function(a,b,d) {

		   App.console.log(" > > Ajax Error: "+a+"; "+b+"; "+d);

		}
		
		
		
		

	});
		
		
		
		
		
		
		//$("#articleViewer").modal("show");
	} catch(ea){App.console.trace(ea);}
};

/****************************************
 * @method
 * @author
 * @param
    openArticle
 * Purpose:
 * Used By: timeline
****************************************/
App.showTip= function(a){
	try{
		App.console.log(" Show Tip",2);
		var activity = App.user.activities[a];
		App.console.log(" > > Activity: "+ activity,2);	
	} catch(ea){App.console.trace(ea);}
};
/****************************************
 * @method @App.showNotification
 * @author
 * @param
    showNotification
 * Purpose: Not Used
 * Type: Overlay Component
 * Used By: Not Used
****************************************/
App.showNotification = function(description){
	$("#notificationDescription").html(description);
	$('.notify').slideDown(400);
};
/****************************************
 * Oswestery Wizard:
 * App.wizard.init("OswesteryWiz","OswesteryNextBtn","OswesteryPreviousBtn");
 ****************************************/
App.wizard = {
	id: 				"",
	btnPreviousId: 	"",
	btnNextId: 		"",
	maxSteps: 			0,
	previousStep: 		0,
	currentStep: 		0,
	callback: function(){},
	init: function(id, nextId, previousId,callback){
		App.wizard.id = id;
		App.wizard.btnNextId = nextId;
		App.wizard.btnPreviousId = previousId;
		App.wizard.callback = (callback)?callback:function(){};
		$("#"+App.wizard.btnPreviousId).click(function(){
			App.wizard.previous();
		});
		$("#"+App.wizard.btnNextId).click(
			function(){App.wizard.next();
		});
		App.wizard.maxSteps = $("#"+App.wizard.id).children("div.step").length;
		App.console.log("How many steps in this Wizard? "+App.wizard.maxSteps,3);
		$("#"+App.wizard.id).before(
            "<div id='WizardSteps' class='OswesteryWizard'></div>"
		);
		$("#"+App.wizard.id).parent().css("overflow","hidden");
		$("#"+App.wizard.id).parent().css("min-height","200px");
		$("#"+App.wizard.id).children("div.step").each(function(index, element) {
			$(this).attr("complete","false");
			$(this).attr("id","step-"+index);
			if(!$(this).attr("valid")){
				$(this).attr("valid","false");
			}
			$(this).css("min-height","200px");
			$(this).css("position","relative");
			$(this).addClass("dashboard-wizard-step");
			$(this).hide();
		});
		$("#step-"+App.wizard.currentStep).show();
		App.wizard.setButtonStates();
		if($("#step-"+App.wizard.currentStep).attr("valid")=="true"){
			$("#"+App.wizard.btnNextId).removeAttr("disabled");
		}
		
	},
	next: function(){
		App.console.log("Next >>",3);
		App.wizard.previousStep=App.wizard.currentStep;
		App.wizard.currentStep++;
		App.wizard.goToStep();
		App.wizard.setButtonStates();
                App.console.log("Is step valid?  "+$("#step-"+App.wizard.currentStep).attr("valid"));
	},
	previous: function(){
		App.console.log("<< Previous",3);
		App.wizard.previousStep=App.wizard.currentStep;
		App.wizard.currentStep--;
		App.wizard.goToStep();
		App.wizard.setButtonStates();
	},
	setButtonStates: function(){
		$previous = $("#"+App.wizard.btnPreviousId);
		$next = $("#"+App.wizard.btnNextId);
		if(App.wizard.currentStep>0){
			$("#"+App.wizard.btnPreviousId).removeAttr("disabled");
		} else {
			$("#"+App.wizard.btnPreviousId).attr("disabled", "disabled");
		}
		if(App.wizard.currentStep<(App.wizard.maxSteps-1)){
			if($("#step-"+App.wizard.currentStep).attr("valid") == "true"){
				$("#"+App.wizard.btnNextId).removeAttr("disabled");
			} else {
				$("#"+App.wizard.btnNextId).attr("disabled", "disabled");
            };
		} else if(App.wizard.currentStep>(App.wizard.maxSteps-1)){
			$(".dashboard-wizard-step").fadeOut();
			$("#"+App.wizard.id).fadeOut();
			$("#"+App.wizard.btnNextId).html($("#"+App.wizard.btnNextId).html().replace("Finish","Next"));
			App.wizard.callback();
        };
        if(App.wizard.currentStep>=(App.wizard.maxSteps-1)){
            App.console.log("End reached.");
            $("#"+App.wizard.btnNextId).html($("#"+App.wizard.btnNextId).html().replace("Next","Finish"));
							if($("#step-"+App.wizard.currentStep).attr("valid") == "true"){
									$("#"+App.wizard.btnNextId).removeAttr("disabled");
							} else {
									$("#"+App.wizard.btnNextId).attr("disabled", "disabled");
							};
        } else {
            $("#"+App.wizard.btnNextId).html($("#"+App.wizard.btnNextId).html().replace("Finish","Next"));
		}
	},
	goToStep: function(){
		App.console.log("  -Previous Step: "+App.wizard.previousStep,3);
		App.console.log("  -Current Step: "+App.wizard.currentStep,3);
		if(App.wizard.previousStep<App.wizard.currentStep){
			/* move right */
			console.log("  Slide Left to the next << [ NEXT ]",3);
			$("#step-"+App.wizard.previousStep).animate({
				left:"-100%"},400,"",function(){
				$("#step-"+App.wizard.previousStep).hide();
				$("#step-"+App.wizard.currentStep).css("left","100%");
				$("#step-"+App.wizard.currentStep).show()
				$("#step-"+App.wizard.currentStep).animate({left:"0%"},600,"easeInOutBack");
			});
		} else {
			/* move left */
			console.log("  Slide Right to the previus >> [ PREVIOUS ]",3);
			$("#step-"+App.wizard.previousStep).animate({
				left:"100%"},400,"",function(){
				$("#step-"+App.wizard.previousStep).hide();
				$("#step-"+App.wizard.currentStep).css("left","-100%");
				$("#step-"+App.wizard.currentStep).show()
				$("#step-"+App.wizard.currentStep).animate({left:"0%"},600,"easeInOutBack");
			});
		}
		if($("#step-"+App.wizard.currentStep).attr("valid")=="true"){
			$("#"+App.wizard.btnNextId).removeAttr("disabled");
		}
	},
	setAsValid: function(bool){
		$("#step-"+App.wizard.currentStep).attr("valid",bool);
		App.console.log(" > Step["+App.wizard.currentStep+"] Valid: "+$("#step-"+App.wizard.currentStep).attr("valid"),3);
		App.wizard.setButtonStates();
	}
};
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

	if(isNaN(milliseconds)){
	return '00:00:00';
	}
	else{
	return milliseconds.toString().toHHMMSS();
	}
};
App.formatDate = function(date, format, utc) {
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
};
/****************************************
NOT USED - WILL REMOVE - Instead will not use array.
 * @method @App.config.errors.getError(name or code)
 * @author
 * @param
    config.errors.getError(name or code)
 * Purpose: Manage error messages presented to the user.
 * Used By: App.config.errors
****************************************/
App.config.errors = {}
App.config.errors.getError = function(errorId){
	var tempError={};
	if(isNaN(errorId)){
		tempError = App.config.errors.findErrByName(errorId);
	} else {
		tempError = App.config.errors.findErrByCode(errorId);
	}
	var error = new Error();
	error.code = tempError.code;
	error.name = tempError.name;
	error.message = tempError.message;
	return error;
};
App.config.errors.findErrByName = function(name){
	var errors = App.config.errors.messages;
	var eLen = errors.length;
	for(var er=0; er<eLen; er++){
		if(errors[er].name.toLowerCase() == name.toLowerCase()){
			return errors[er];
		}
	}
	return errors[0];
};
App.config.errors.findErrByCode = function(code){
	var errors = App.config.errors.messages;
	var eLen = errors.length;
	for(var er=0; er<eLen; er++){
		if(errors[er].code == code){
			return errors[er];
		}
	}
	return errors[0];
};
//App.void = function(arg){};
/****************************************
 * Local Storage/Cookie
 * Purpose:
 * Used By: 
****************************************/
App.util.support.local_storage = function(){
  try {
    return 'localStorage' in window && window['localStorage'] !== null;
  } catch (e) {
    return false;
  }
};
App.util.storage = {
	get: function(name){
		if(App.util.support.local_storage){
			return localStorage[name];
		}
	},
	set: function(name,value){
		if(App.util.support.local_storage){
			localStorage[name] = value;
		}
	}
};
