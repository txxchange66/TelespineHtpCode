/* Dashboard Functions by Creative Slave */
var Dashboard = {
	properties:{},
	init: function(properties){
		Dashboard.console.log("Loading Dashboard...");
		this.properties= properties;
		this.load();
	},
	load: function(){
		var len= this.properties.components.length;
		Dashboard.console.log("Loading Dashboard Components ("+len+")");
		var base = this.properties;
		$("#loading").css("visibility","visible");
		for(c=0; c<len; c++){
			Dashboard.console.log("Reading Component: "+base.components[c].id);
			/* Ajax with JSONP */
			try{
				if(base.components[c].load && base.components[c].url!="void"){
					if(Dashboard.properties.loggingVerbose){
						Dashboard.console.log(base.components[c]);	
					}
					Dashboard.console.log(" > Loading Component: ("+base.components[c].id+") from "+base.baseComponentURL + base.components[c].url,2);
					$.ajax({
					   type: 'GET',
						url: base.baseComponentURL + base.components[c].url,
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
			$("#loading").css("visibility","hidden");
		}
		this.openSurvey();
	},
	openSurvey: function(justOpen){
		/* Survey*/
		if(this.properties.survey){
			$('.notify').slideDown(400);
		}
		if(this.properties.survey && !this.properties.firstTimeUser){
			Dashboard.console.log(" > Open Survey Notification");
			setTimeout(
				function(){
					$('div.notify').slideDown(400);
				},
			2000);
		} else if(justOpen){
			Dashboard.console.log("This is a first time user! \n > Open Welcome Window. ");
			$('#surveyModal').modal('show');
		}
	},
	getComponent: function(id){
		var base = this;
		//alert('abc');
		try{
			var len= base.properties.components.length;
			for(c=0; c<len; c++){
				/*Dashboard.console.log(base.properties.components[c].id);*/
				if(Dashboard.properties.loggingVerbose){
					Dashboard.console.log(" > > getComponent: "+base.properties.components[c].id,4);	
				}
				if(base.properties.components[c].id == id){
					return base.properties.components[c];
				}
			}
		} catch(compE){base.console.log(" > getComponent(): Error "+compE);return null;}
	},
	showHelp: function(componentId){
		Dashboard.console.log("function :: showHelp('"+componentId+"')");
		$('div.dialog-help').hide();
		$('#dialog-'+componentId).show();	
	},
	setStats: function(component){
		Dashboard.console.log("callback :: setStats()");
		$("#MyGoals").val(component.data.goals);
		$("#MyLogins").html(component.data.logins);
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
	},
	setPainLevel: function(){
                Dashboard.console.log("function :: setPainLevel");

                /*
                * Adding the information into the database
                */

                $.ajax({
                    type: "POST",
                    url: "index.php?action=addpatientpainlevel",
                    data: {
                        painlevel: $("#pain-level-control").html()
                    }
                })
                .done(function(){
                    //alert('saved');
                })
                .fail(function(){
                    alert('There was some problem performing action on this job.');
                });

                var comp = Dashboard.getComponent("painMeter");
                comp.data.pain=comp.data.pain+","+$("#pain-level-control").html();

                /* TODO: Make call to update pain.*/
                $.gritter.add({
                        title: 'Pain Level Updated',
                        text: 'You have added a status update to your pain level. You set it to: '+$("#pain-level-control").html()+' out of 10.',
                        class_name: 'my-sticky-class'
                });

                //Dashboard.setPainLevelGraph(comp);
	},
	setPainLevelGraph: function(component){
		Dashboard.console.log("callback :: setPainLevelGraph()");
		try{
			$("#pain-graph").html(component.data.pain);
			$("#pain-level").slider({
				range: "min",
				value: 3,
				min: 0,
				max: 10,
				slide: function( event, ui ) {$("#pain-level-control").html(ui.value);}
			});
		} catch(e){Dashboard.console.log(" Error setPainLevelGraph : "+e);}	
	},
	setFunctionalScore: function(component){
		Dashboard.console.log("callback :: setFunctionalScore()");
		try{
			var slen = component.data.scores.length-1;
			$("#currentFunctionalScore").html(component.data.scores[slen][0]+ "%");
			$("#currectPain").html( (component.data.scores[slen][1]/10) + "/10");/*"*/
			for(var s=0; s<4; s++){this.setScore(component, s);}
		} catch(e){Dashboard.console.log(" Error setFunctionalScore : "+e);}	
	}, 
	setScore: function(component, survey){
		try{
			var score = component.data.scores[survey];
			$('.f-score-'+(survey+1)).css('top',(100-score[0])+'%').html(score[0]+'%');
			Dashboard.console.log(" > > Functional Score from Survey "+(survey+1)+": "+ (100-score[0]));
			Dashboard.console.log(" > > Pain Score from Survey "+(survey+1)+": "+ (100-score[1]));
			$('.p-score-'+(survey+1)).css('top',(100-score[1])+'%').html(score[1]+'%');
		} catch (se){
			Dashboard.console.log("Expected Exception: "+ se+ " (This is expected because it is not yet set)");
			$('.f-bar-'+(survey+1)).html('Pending');
		}
	},
	addGoal: function() {
            Dashboard.console.log(":: addGoal");
            
            /* ajax call to add goal */
            var goal = $.trim($("#AddGoal").val());

            if(goal != "") {
                $.ajax({
                    type: "POST",
                    url: "index.php?action=addgoal",
                    data: {
                        goal: goal
                    }
                })
                .done(function(response){
                    var jr = $.parseJSON(response);

                    $("#GoalsList").prepend('<li id="goal_'+jr.newlyinsertedgoalid+'"> <span class="todo-actions"> <a href="javascript:void(0);"> <i onClick="javascript:Dashboard.updateGoal(this);" name="chk_'+jr.newlyinsertedgoalid+'" value="'+jr.newlyinsertedgoalid+'" class="fa fa-square-o"></i></a> </span> <span id="span_'+jr.newlyinsertedgoalid+'" class="desc">'+goal+'</span> </li>');

                    $("#AddGoal").val("");

                    //update the circular graph with new percentage received from server.
                    $("#MyGoals").val(jr.goalscompletedpercentage);
                })
                .fail(function(ajaxErr){
                    $.gritter.add({
                        title: 'Goal could not be added',
                        text: 'There was a problem adding goal. Please try again later.',
                        class_name: 'my-sticky-class'
                    });
                    console.log('There was some problem adding goal : '+ajaxErr.message);
                });
            } else {
                $.gritter.add({
                    title: 'Goal text needed',
                    text: 'Please enter a Gaol.',
                    class_name: 'my-sticky-class'
                });
            }

            /*var base = this;
            try{
                this.callService("goalsAndStatistics");
            } catch(serviceError){
                Dashboard.console.log("goalsAndStatistics.addGoal Service Error: "+serviceError);
            }*/
		
	},
        updateGoal: function(obj) {
            Dashboard.console.log(":: updateGoal");
            var goalid = $(obj).attr('value');

            if($(obj).attr('class') == "fa fa-square-o")
            {
                //checked, i.e. disable the status
                status = '2';
            }
            else if ($(obj).attr('class') == "fa fa-check-square-o")
            {
                status = '1';
            }
            /* ajax call to update goal */
            if(typeof obj === "object") {
                $.ajax({
                    type: "POST",
                    url: "index.php?action=updategoal&status="+status,
                    data: {
                        goalid: goalid,
                        status: status
                    }
                })
                .done(function(response){
                    Dashboard.console.log(response);
                    var jr = $.parseJSON(response);


                    //update the circular graph with new percentage received from server.
                    $("#MyGoals").val(jr.goalscompletedpercentage);

//                        Dashboard.setStats(Dashboard.getComponent('goalsAndStatistics'));
                })
                .fail(function(ajaxErr){
                    $.gritter.add({
                        title: 'Goal could not be updated',
                        text: 'There was a problem updating goal. Please try again later.',
                        class_name: 'my-sticky-class'
                    });
                    console.log('There was some problem updating goal : '+ajaxErr.message);
                });
            } else {
                console.log('goalid is empty.');
            }
	},
	callService: function(componentId){
		Dashboard.console.log("service :: callService for "+componentId);
		var base = this;
		if(base.getComponent(componentId)){
			$.ajax({
			   type: 'GET',
				url: base.properties.baseComponentURL + base.getComponent(componentId).service.url,
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
	displayComponent: function(component, bool){
		component.load = bool;
		try{
			if(bool){$("#"+component.id+"_comp").show();} else {$("#"+component.id+"_comp").hide();}
		}catch(compErr){Dashboard.console.log(compErr);}
	},
	isMobile: function(){
		return (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent));	
	},
	openMovie: function(a){
		try{
			var activity = Dashboard.getComponent("weeklyTimeline").data.activities[a];
			if(Dashboard.properties.loggingVerbose){
				Dashboard.console.log(" > > Activity: "+ activity,2);	
			}
			/* Custom code to set movie and open modal. */
			$("#videoViewer").modal("show");
		} catch(ea){Dashboard.console.log(ea);}
	},
	console:{
		log: function(l, level/*:int*/){
			try{
				if(Dashboard.properties.logging){
					if(!level){
						if(console) console.log(l);
					} else if(level<=Dashboard.properties.loggingLevel){
						if(console) console.log(l);
					}
				}
			} catch(e){console.log(e);}
		}
	}
}