/**
 *   Telespine Static Configuration
 *   Version 2.5
 *  */
App.settings = {
	baseImageURL:		"assets/img/",
	baseComponentURL:	"",
	logging:true,
	loggingLevel:6
}

App.config.components = [
	{
		id:"today",
		column:2,
		active:false,
//		url:"components/today.html",
                url: "index.php?action=todaysactivities",
		onLoad:"void"
	},
	{
		id:"newTimeline",
		column:-1,
		active:true,
		url:"void",
		onLoad:"timeline"
	},
	{
		id:"messageCenter",
		column:1,
		active:false,
//		url:"components/notification.html",
                 url:"index.php?action=messagecenter",
		onLoad:"void"
	},
	{
		id:"goalsAndStatistics",
		column:1,
		active:true,
//		url:"components/statistics.html",
                url: "index.php?action=patientgoals",
		onLoad:"setStats"
	},
	{
		id:"painMeter",
		column:3,
		active:false,
//		url:"components/pain-level.html",
                url:"index.php?action=currentpainlevel",
		onLoad:"setPainLevelGraph"
	},
	{
		id:"functionalScore",
		column:3,
		active:true,
//		url:"components/functional-score.html",
                url:"index.php?action=functionalscore",
		onLoad:"setFunctionalScore"
	},
	{
		id:"onlineCoaching",
		column:1,
		active:false,
		url:"void",
		onLoad:"void"
	},
	{
		id:"messages",
		column:1,
		active:false,
		url:"components/messages.html",
		onLoad:"void"
	},
        /*	{
		id:"getDayMessage",
		column:1,
		active:false,
		url:"index.php/action=getDayMessage",
		onLoad:"void"
	},*/
	{
		id:"healthyHabits",
		column:2,
		active:true,
//		url:"components/healthy-habits.html",
                url:"index.php?action=healthybackhabits",
		onLoad:"void"
	}
]

/* User Controlled Flows */
App.config.userFlows=[
	{
		trigger:{onDay:true, day:1, property:App.user.oswestery.status.survey1, value:"pending"},
		flow: [
                        
                        {
				id:"ie8message", 
				type: "information Message", 
				call: App.openInformationMessage,
				callback: App.void
			},
                        {
				id:"welcome", 
				type: "Welcome Message", 
				call: App.openFirstTimeUser,
				callback: App.void
			},
			{
				id:"survey", 
				type: "Oswestery Wizard",
				call: App.openOswestery
			},
			{
				id:"Tip1", 
				type: "Tip", 
				call: App.openTip1
			},
			{
				id:"Tip2", 
				type: "Tip", 
				call: App.openTip2
			},
			{
				id:"Tip3", 
				type: "Tip", 
				call: App.openTip3
			}
		]
	},
	/*{
		trigger:{onDay:true, day:2, property:App.user.survey.status, value:"pending"},
		flow: [
			{
				id:"R4CSurvey", 
				type: "R4CSurvey", 
				call: App.openR4CSurvey
			}
		]
	},*/
	{
		trigger:{onDay:true, day:18, property:App.user.oswestery.status.survey2, value:"pending"},
		flow: [
			{
				id:"OswesteryModal", 
				type: "Oswestery Wizard", 
				call: App.openOswestery
			}
		]
	},
	{
		trigger:{onDay:true, day:37, property:App.user.oswestery.status.survey3, value:"pending"},
		flow: [
			{
				id:"survey", 
				type: "Oswestery Wizard", 
				call: App.openOswestery
			}
		]
	},
	{
		trigger:{onDay:true, day:53, pproperty:App.user.oswestery.status.survey4, value:"pending"},
		flow: [
			{
				id:"survey", 
				type: "Oswestery Wizard", 
				call: App.openOswestery
			}
		]
	}
]

App.config.services= {
	addGoal:{
//		url:"components/addGoal.html?noCache"
                url:"index.php?action=addgoal"
	},
	updateGoal:{
//		url:"components/updateGoal.html?noCache"
                url:"index.php?action=updategoal"
	},
	updatePassword:{
//		url:"components/create-password-service.html"
                url:"index.php?action=changepass_firsttime"
	},
	oswestery:{
//		url:"components/oswestery-service.html"
                url:"index.php?action=submitoswestrysurvey"
	},
	r4CSurvey:{
//		url:"components/r4CSurvey-service.html"
                url:"index.php?action=submitreadyforchangesurvey"
	},
	updatePainLevel:{
		url:"index.php?action=addpatientpainlevel"
	},
	updateFunctionalScore:{
		url:"components/survey-service.html"
	},
        getDayMessage:{
		url:"index.php?action=getDayMessage",
        }
}

/* Error Handler Messages */
App.config.errors.messages= {
	UNKNOWN_ERROR: { 
		code:0, 
		name:"Unknown Error", 
		message:"We appologize for the inconveinence, but we have encountered an error. You may need to refresh your browser. Please try again." 
	}
}

