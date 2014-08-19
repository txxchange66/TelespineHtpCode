/* ---------- Dashboard Functions ---------- */
$(document).ready(function(e) {
	/* Set UI Design Override */
	
	
	/* Load Page Components */
	if(properties.components.weeklyTimeline){var timeline = new WeeklyTimeline();timeline.init();}
	if(properties.components.goalsAndStatistics){setStats(properties);}
	if(properties.components.functionalScore){setFunctionalScore(properties);}
	if(properties.components.painMeter){setPainLevelGraph(properties);}
	if(properties.components.messageCenter){}
	if(properties.components.messages){}
	if(properties.components.healthyBackBasics){}
	if(properties.components.onlineCoaching){}
	
	/* Survey*/
	if(properties.survey){setTimeout(function(){$('div.notify').slideDown(400);},2000);}
	/* First Time User */
	if(properties.firstTimeUser){
		$("#FirstTimeUser").modal("show");
		/*$.gritter.add({
			title: 'First Time Notification',
			text: 'You can click on the "Week" buttons to open your week view timeline.'+ 
				  'In the timeline you can monitor your progress, and manage your goals.',
			class_name: 'my-sticky-class'
		});*/
	}
});
function ts_loadComponent(component){
	/* Ajax with JSONP */
	$.ajax({
	   type: 'GET',
		url: properties.baseComponentURL + component.url+'?callback=?',
		async: false,
		jsonpCallback: 'jsonCallback',
		contentType: "application/json",
		dataType: 'jsonp',
		success: function(response) {
		   $("#"+component.id).html(response+'<div class="clearfix"></div>');
		},
		error: function(ajaxErr) {
		   console.log("Ajax Error when invoking "+component.id+": "+ajaxErr);
		}
	});
}
function ts_loadResponse(){
	
}
/* Goals */
function addGoal(){
	/* Example Only - AJAX required */
	var s= '<li> <span class="todo-actions"> <a href="#"> <i class="fa fa-square-o"></i></a> </span> <span class="desc">{value}</span> </li>';	
	var goal = $("#AddGoal").val();
	s = s.replace("{value}",goal);
	$("#GoalsList").append(s);
	$("#AddGoal").val("");
}
function showHelp(id){
	$('div.dialog-help').hide();
	$('#'+id).slideDown();	
}

function setPainLevel(){
	$.gritter.add({
		// (string | mandatory) the heading of the notification
		title: 'Pain Level Updated',
		// (string | mandatory) the text inside the notification
		text: 'You have added a status update to your pain level. You set it to: '+$("#pain-level-control").html()+' out of 10.',
		class_name: 'my-sticky-class'
	});
}
function setStats(properties){
	$("#MyGoals").val(properties.goals);
	$("#MyLogins").html(properties.logins);
}
function setFunctionalScore(properties){
	var slen = properties.functionalScore.scores.length-1;
	$("#currentFunctionalScore").html(properties.functionalScore.scores[slen][0]+ "%");
	$("#currectPain").html((properties.functionalScore.scores[slen][1]/10)+ "/10 "); 
	/*"*/
	for(var s=0; s<4; s++){
		setScore(s);
	}
}
function setPainLevelGraph(properties){
	try{
		$("#pain-level").slider({
			range: "min",
			value: 3,
			min: 0,
			max: 10,
			slide: function( event, ui ) {$("#pain-level-control").html(ui.value );}
		});
	} catch(e){console.log(e);}
	//$("#pain-graph").html(properties.pain);	
}
function setScore(survey){
	try{
		var score = properties.functionalScore.scores[survey];
		$('.f-score-'+(survey+1)).css('top',(100-score[0])+'%').html(score[0]+'%');
		console.log(" > > Functional Score from Survey "+(survey+1)+": "+ (100-score[0]));
		console.log(" > > Pain Score from Survey "+(survey+1)+": "+ (100-score[1]));
		$('.p-score-'+(survey+1)).css('top',(100-score[1])+'%').html(score[1]+'%');
	} catch (se){
		console.log(se+ " (Expected)");
		$('.f-bar-'+(survey+1)).html('Pending');
	}
}
