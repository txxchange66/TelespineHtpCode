/* Dashboard Functions by Creative Slave */

/*
	Please do not alter this Controller. This is a base controller.
	See dashboard.controllers for individual control modules.
*/
var Dashboard = {
	properties:{},
	init: function(properties){
		Dashboard.console.log("Loading Dashboard...");
		this.properties= properties;
		$("#loading").css("visibility","visible");
		Dashboard.load();
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
		}
		$("#loading").animate(
			{
				width:"50%",
				height:"50%",
				top:"10px",
				"margin-left":"-25%",
				opacity:0.0
			},2000)
		setTimeout(function(){$("#loading").css("visibility","hidden");},2200);
		try{if(this.openSurvey) this.openSurvey();} catch(e){}
	},
	getComponent: function(id){
		var base = this;
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
		Dashboard.console.log("function :: showHelp("+componentId+")");
		var title = [];
		title["painMeter"] = "Current Pain Level Overview";
		title["functionalScore"] = "Functional Score Overview";
		title["goalsAndStatistics"] = "Goals and Statistics Overview";
		title["healthyHabits"] = "Healthy Back Habits Overview";
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
function ServiceCall(componentId){
	this.componentId=componentId;
	this.url="";
	this.name="";
	this.data = {};
	this.callBack=function(response){};
}
var navUp=true;
var toggleNav = function(event){
	try{event.preventDefault();} catch(er){}
	if(navUp){
		$('#topNav').slideDown(800);
	} else {
		$('#topNav').slideUp(300);
	}
	navUp = !navUp;
}
$(document).ready(function(event) {
	$("a.nav-menu-btn, #mobileMenu").on("click",
		function(event){
			//event.preventDefault();
			toggleNav();
		}
	);
});