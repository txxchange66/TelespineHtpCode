/* Extend Dashboard Controller Class */

Dashboard.prototype.setStats= function(component){
	Dashboard.console.log(" Dashboard.setStats()");
	var percent = Math.round(component.data.goals[0]/component.data.goals[1]*100);
	$("#MyLogins").html(component.data.logins);
	$("#GoalsInterface").fadeOut();
	$("#GoalsInterface").html('<i class="fa fa-thumbs-up"></i><span class="plus">+</span><span class="percent">%</span>'+
	'<input id="MyGoals" type="text" value="'+percent+'" class="circleChart" />');
	$("#GoalsInterface").fadeIn();
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
}

Dashboard.prototype.setPainLevel= function(){
	Dashboard.console.log("function :: setPainLevel");
	/* TODO: Make call to update pain.*/
	var newPain = $(".pain-level-control").html();
	$.gritter.add({
		title: 'Pain Level Updated!',
		text: 'Thank you! You have added a status update to your pain level. You set it to: '+newPain+' out of 10.',
		class_name: 'my-sticky-class'
	});
	var c = Dashboard.getComponent("painMeter");
	c.data.pain += ","+newPain;
	$("#pailControl").hide();
	$(".pain-bubble").slideDown(300);
	try{
		$("#painLevelGraph").html('<div id="pain-graph" class="chart red" style="width:100%">'+c.data.pain+'</div>');
		var chartColor = $("#painLevelGraph").css('color');
		$("#painLevelGraph").sparkline(c.data.pain, {width: '90%',height: 80,lineColor: chartColor,fillColor: false,spotColor: false,maxSpotColor: false,minSpotColor: false,spotRadius: 2,lineWidth: 2});
		
		if (jQuery.browser.mozilla) {
			$("#painLevelGraph").css('MozTransform','scale(0.5,0.5)');
			$("#painLevelGraph").css('height','40px;').css('margin','-20px 0px -20px -25%');
		} else {
			$("#painLevelGraph").css('zoom',0.6);
		}
	} catch(pe){alert(pe);}
}

Dashboard.prototype.setPainLevelGraph= function(component){
	Dashboard.console.log("callback :: setPainLevelGraph()");
	try{
		$("#pain-graph").html(component.data.pain);
		$("#pain-level").slider({
			range: "min",
			value: 3,
			min: 0,
			max: 10,
			slide: function( event, ui ) {
				$(".pain-level-control").html(ui.value);
			}
		});
	} catch(e){Dashboard.console.log(" Error setPainLevelGraph : "+e);}	
}
	
Dashboard.prototype.setFunctionalScore= function(component){
	Dashboard.console.log("callback :: setFunctionalScore()");
	try{
		var slen = component.data.scores.length-1;
		$("#currentFunctionalScore").html(component.data.scores[slen][0]+ "%");
		$("#currectPain").html( (component.data.scores[slen][1]/10) + "/10");/*"*/
		for(var s=0; s<4; s++){this.setScore(component, s);}
	} catch(e){Dashboard.console.log(" Error setFunctionalScore : "+e);}	
}

Dashboard.prototype.setScore= function(component, survey){
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
}

Dashboard.prototype.updateGoal= function(obj){
	Dashboard.console.log(":: updateGoal");
	/* TODO: AJAX required */
	var StatisticsComponent = Dashboard.getComponent('goalsAndStatistics');
	Dashboard.console.log(" > Click Event [Goal Action] ");
	if ($(obj).find('i').attr('class') == 'fa fa-square-o') {
		$(obj).find('i').removeClass('fa-square-o').addClass('fa-check-square-o');
		$(obj).parent().parent().find('span').css({ opacity: 0.25 });
		$(obj).parent().parent().find('.desc').css('text-decoration', 'line-through');
		/* Set Status Action to Complete. */
		Dashboard.console.log(" > > Set Status Action to Complete");
		StatisticsComponent.data.goals[0]+=1;
	} else {
		$(obj).find('i').removeClass('fa-check-square-o').addClass('fa-square-o');
		$(obj).parent().parent().find('span').css({ opacity: 1 });
		$(obj).parent().parent().find('.desc').css('text-decoration', 'none');
		/* Set Status Action to active. */
		Dashboard.console.log(" > > Set Status Action to Active.");
		StatisticsComponent.data.goals[0]-=1;
	}
	Dashboard.setStats(StatisticsComponent);
	return false;
}

Dashboard.prototype.addGoal= function(){
	Dashboard.console.log(":: addGoal");
	if($("#AddGoal").val().length>0){
		var StatisticsComponent = Dashboard.getComponent('goalsAndStatistics');
		var goal = $("#AddGoal").val();
		var base = this;
		$("#AddGoal").val("");
		try{
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

Dashboard.prototype.openMovie= function(a){
	try{
		var activity = Dashboard.getComponent("weeklyTimeline").data.activities[a];
		if(Dashboard.properties.loggingVerbose){
			Dashboard.console.log(" > > Activity: "+ activity,2);	
		}
		/* Custom code to set movie and open modal. */
		$("#videoViewer").modal("show");
	} catch(ea){Dashboard.console.log(ea);}
}

Dashboard.prototype.openSurvey= function(justOpen){
	if(this.properties.survey){
		$('.notify').slideDown(400);
	}
	if(this.properties.survey && !this.properties.firstTimeUser){
		Dashboard.console.log(" > Open Survey Notification");
		/*setTimeout(
			function(){
				$('div.notify').slideDown(400);
			},
		2000);*/
	} else if(justOpen){
		Dashboard.console.log("This is a first time user! \n > Open Welcome Window. ");
		$('#surveyModal').modal('show');
	}
}