/* Base Component Interface */

/* Properties Interface */
var ControllerProperties = function(properties){
	this.userName=				(properties.userName)?		properties.userName:		"Guest";
	this.userId=				(properties.userId)?			properties.userId:			"GUEST-00000";
	this.theme=				(properties.theme)?			properties.theme:			"default";
	this.startDate=			(properties.startDate)?		properties.startDate:		new Date("1-15-2014");
	this.survey=				(properties.survey)?			properties.survey:			false;
	this.firstTimeUser=		(properties.firstTimeUser)?	properties.firstTimeUser:false;
	this.logging=				(properties.logging)?			properties.logging:		true;
	this.loggingVerbose=		(properties.loggingVerbose)?	properties.loggingVerbose:false;
	this.loggingLevel=			(properties.loggingLevel)?	properties.loggingLevel:	2;
	this.baseURL=				(properties.baseURL)?			properties.baseURL:		"components/";
	this.components=			[];
	this.addComponent= 		function(component){
		components[component.id] = component;
	}
}

/* Component Interface */
var Component = function(){
	/*[String]:	*/ 		this.id=			"";
	/*[Boolean]:	*/ 		this.shallLoad=	true;
	/*[Boolean]:	*/ 		this.column=		0;
	/*[Function]:	*/ 		this.onLoadService=		function(){};
	/*[Array]:		*/ 		this.services=		[]
	/*[Function]:	*/ 		this.addCallBackService=		function(service){
		services[service.id] = service;
	};
}

/* Service Interface */
var Service = function(){
	/*[String]:	*/ 		this.name=			"";
	/*[Object]:	*/ 		this.preferences=	{};
	/*[String]:	*/ 		this.serviceURI= 	"";
	/*[Object]:	*/ 		this.data=			{};
	/*[Boolean]:	*/ 		this.hasCalled=	false;
	/*[Int]:		*/ 		this.callCount=	0;
	/*[Function]:	*/ 		this.callBack=		function(c,e){};
}

var test1 = new Component();
test1.id="Test Service 1";
var onLoadService = new Service();
onLoadService.name="";
test1.onLoadService = onLoadService;

function testComponents(){
	var properties = new ControllerProperties();
	properties.userName="";
	properties.startDate=new Date("1-15-2014");
	properties.addComponent(test1);
	load(properties.components[i])
	getComponent("Test Service 1").callService("Get Function Score");
}

