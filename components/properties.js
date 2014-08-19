var TeleSpineProperties = {

   configuration: {

	   logging:			true,

	   loggingVerbose:	false,

	   loggingLevel:		5,

	   startDate:			"2014-01-24",

	   firstTimeUser:		false,

	   resetPassword:		{

			load:			false,

			service: {

				url: 		"create-password-service.html"

			}

	   },

	   survey: {

			load:			false,

			service: {

				url: 		"survey-service.html"

			}

	   },

	   lastPainLevel:		5,

	   lastNewMessageDate: "2014-02-01",

	   painLevelLastSet:	"2014-02-01",

	   baseImageURL:		"/admin/assets/img/",

	   baseComponentURL:	"components/",

	   updateGoalURL:		"updateGoal.html",

	   updatePainURL:		"updatePainLevel.html"

   },

   /* Load Components for page.  Load order matters bc shared access data is intrinsic. */

   components:[

      {

         id:"firstimeMessage",

         load:false,

         column:-1,

         url:"void",

         data:{},

         callback:function(c)         {

            $("#FirstTimeUser").modal({backdrop: 'static',keyboard: false});

         }

      },

      {

         id:"today",

         load:true,

         column:2,

         url:"today.html",

         data:{},

         callback:function() {}

      },

      {

         id:"weeklyTimeline",

         load:true,

         column:0,

         url:"void",

         data:{

           activities:[

							{

								day:1,

								label:'TeleSpine LBP Prevention Tips',

								type:'posture',

								kind:'tip',

								status:'complete',

								media:"/admin/assets/pdf/TeleSpine LBP Prevention Tips.pdf"

							},

							{

								day:1,

								label:'Acute LBP Self Help: Extension',

								type:'posture',

								kind:'video',

								media:"http://htptest.txxchange.com/asset/images/treatment/502/video.mp4"

							},

							{

								day:2,

								label:'TeleSpine Top 10 Tips to Prevent Lower Back Pain',

								type:'mobility',

								kind:'article',

								status:'complete',

								media:"/admin/assets/pdf/TeleSpine Top 10 Tips to Prevent Lower Back Pain.pdf"

							},

							{

								day:3,

								label:'TeleSpine Stress and Back Pain',

								type:'mobility',

								kind:'article',

								status:'complete',

								media:"/admin/assets/pdf/TeleSpine Stress and Back Pain.pdf"

							},

							{

								day:4,

								label:'Sitting Stretch Routine',

								type:'core strength',

								kind:'video',

								status:'complete',

								media:"https://acornmedia.herokuapp.com/media/tears_of_steel_480.mp4"

							},

							{

								day:5,

								label:'Prone Press-up',

								type:'flexibility',

								kind:'video',

								status:'complete',

								media:"https://acornmedia.herokuapp.com/media/tears_of_steel_480.mp4"

							},

							{

								day:6,

								label:'Stretching Everyday',

								type:'posture',

								kind:'article',

								status:'complete',

								media:"/admin/assets/pdf/TeleSpine Stress and Back Pain.pdf"

							},

							{

								day:7,

								label:'Sleeping Positions for LBP',

								type:'core strength',

								kind:'video',

								status:'complete',

								media:"http://htptest.txxchange.com/asset/images/treatment/558/video.mp4"

							},

							{

								day:8,

								label:'Good Sitting Posture',

								type:'posture',

								kind:'video',

								status:'complete',

								media:"/admin/assets/pdf/TeleSpine Stress and Back Pain.pdf"

							},

							{

								day:9,

								label:'Good Sitting Posture',

								type:'mobility',

								kind:'article',

								status:'complete',

								media:"/admin/assets/pdf/TeleSpine Stress and Back Pain.pdf"

							},

							{

								day:10,

								label:'Proper Work Posture - Sitting',

								type:'flexibility',

								kind:'video',

								status:'complete',

								media:"http://htptest.txxchange.com/asset/images/treatment/558/video.mp4"

							},

							{

								day:11,

								label:'TeleSpine Keys to Success for Core Strengthening',

								type:'posture',

								kind:'article',

								status:'complete',

								media:"/admin/assets/pdf/TeleSpine Keys to Success for Core Strengthening.pdf"

							},

							{

								day:12,

								label:'TeleSpine Strengthening Your Core',

								type:'core strength',

								kind:'exercise',

								status:'complete',

								media:"/admin/assets/pdf/TeleSpine Strengthening Your Core.pdf"

							},

							{

								day:13,

								label:'Good Sitting Posture',

								type:'posture',

								kind:'video',

								status:'complete',

								media:"http://htptest.txxchange.com/asset/images/treatment/558/video.mp4"

							},

							{

								day:14,

								label:'TeleSpine Protecting Your Back at Work',

								type:'mobility',

								kind:'article',

								status:'complete',

								media:"/admin/assets/pdf/TeleSpine Protecting Your Back at Work.pdf"

							},

							{

								day:15,

								label:'Achilles Tendon Rupture', 

								type:'posture', 

								kind:'article', 

								status:'complete',

								media:"http://www.mayoclinic.com/health/achilles-tendon-rupture/DS00160" 

							},

							{

								day:17,

								label:'100\'s Modified Head Supported', 

								type:'posture', 

								kind:'article', 

								status:'complete',

								media:"/admin/assets/pdf/TeleSpine Strengthening Your Core.pdf"

							},

							{

								day:16,

								label:'Achilles Tendonitis',

								type:'posture',

								kind:'video',

								status:'complete',

								media:"http://htptest.txxchange.com/asset/images/treatment/558/video.mp4"  

							},

							{

								day:18,

								label:'Achilles Tendon Rupture', 

								type:'posture', 

								kind:'article', 

								status:'complete',

								media:"/admin/assets/pdf/TeleSpine Strengthening Your Core.pdf" 

							},

							{

								day:19,

								label:'TeleSpine Strengthening Your Core', 

								type:'posture', 

								kind:'article', 

								status:'complete',

								media:"/admin/assets/pdf/TeleSpine Strengthening Your Core.pdf"

							},

							{

								day:20,

								label:'Achilles Tendonitis',

								type:'posture',

								kind:'video',

								status:'complete',

								media:"http://htptest.txxchange.com/asset/images/treatment/558/video.mp4"  

							},

							{

								day:22,

								label:'Good Sitting Posture',

								type:'posture',

								kind:'article',

								status:'pending',

								media:""

							},

							{

								day:21,

								label:'Good Sitting Posture',

								type:'core strength',

								kind:'video',

								status:'pending',

								media:"http://htptest.txxchange.com/asset/images/treatment/558/video.mp4"  

							},

							{

								day:23,

								label:'Good Sitting Posture',

								type:'core strength',

								kind:'video',

								status:'pending',

								media:"http://htptest.txxchange.com/asset/images/treatment/558/video.mp4"  

							},

							{

								day:24,

								label:'Good Sitting Posture',

								type:'core strength',

								kind:'video',

								status:'pending',

								media:"http://htptest.txxchange.com/asset/images/treatment/558/video.mp4"  

							},

							{

								day:25,

								label:'Good Sitting Posture',

								type:'core strength',

								kind:'video',

								status:'pending',

								media:"http://htptest.txxchange.com/asset/images/treatment/558/video.mp4"  

							},

							{

								day:26,

								label:'Good Sitting Posture',

								type:'core strength',

								kind:'video',

								status:'pending',

								media:"http://htptest.txxchange.com/asset/images/treatment/558/video.mp4"  

							}

						]

         },

         callback:function(c)         {

            window.Dashboard.timeline()

         }

      },

      {

         id:"messageCenter",

         load:true,

         column:1,

         url:"notification.html",

         data:{



         },

         callback:function(c)         {

			window.Dashboard.showMessage()

         }

      },

      {

         id:"goalsAndStatistics",

         load:true,

         column:1,

         url:"statistics.html",

         data:{

            goals:[

               9,

               12

            ],

            logins:252

         },

         callback:function(c)         {

            window.Dashboard.setStats(c);

         },

         /* Creating ServiceCall; TODO: Move to Service Object Registry; This will be removed and localized. */

		 service:{

            url:"addGoal.html",

            callback:function(response)            {

               $("#GoalsList").prepend(response);

			   		var component = Dashboard.getComponent('goalsAndStatistics');

			   		component.data.goals[1] +=1;

					Dashboard.setStats(component);

            }

         }

      },

      {

         id:"painMeter",

         load:true,

         column:3,

         url:"pain-level.html",

         data:{

            pain:"10,9,9,9,8,8,7,6,6,5,4,4,4,3"

         },

         callback:function(c)         {

            window.Dashboard.setPainLevelGraph(c);

         },

         /* Creating ServiceCall; TODO:         Move to Service Object Registry */service:{

            url:"addGoal.html",

            callback:function(response)            {

               $("#pailControl").hide();$(".pain-bubble").slideDown(200);var c = Dashboard.getComponent('painMeter');window.Dashboard.setPainLevelGraph(c);

            }

         }

      },

      {

         id:"functionalScore",

         load:true,

         column:3,

         url:"functional-score.html",

         data:{

            scores:[

               [  /* Survey 1*/

                  85 /* FS */,

                  70 /* PL */

               ],

               [  /* Survey 2*/

                  60 /* FS */,

                  50 /* PL */

               ]

            ]

         },

         callback:function(c)         {

            window.Dashboard.setFunctionalScore(c);

         }

      },

      {

         id:"onlineCoaching",

         load:false,

         column:1,

         url:"void",

         data:{



         },

         callback:function(c)         {}

      },

      {

         id:"messages",

         load:false,

         column:1,

         url:"messages.html",

         data:{



         },

         callback:function()         {}

      },

      {

         id:"healthyHabits",

         load:true,

         column:2,

         url:"healthy-habits.html",

         data:{



         },

         callback:function()         {}

      }

   ]

}