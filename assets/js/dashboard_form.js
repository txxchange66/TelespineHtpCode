/*

$(document).ready(function () {  
  Dashboard.init({logging:true,components:[]});
  if(location.hash!=""){
	  loadForm(location.hash.replace("#",""));
  } else {
	  loadForm("general");
  }
  location.hash="";
	
	$(".dropzone").dropzone();
});
*/
function loadForm(sectionId, bool){
	location.hash=sectionId;
	$.gritter.removeAll();
	var formId = sectionId+"-form";
	inForm.init(formId,"fieldError",function(){
		/* OR just return true to send to a server without using ajax.
		return true; 
		*/ 
		if(!hasLoaded(formId)){
			$.ajax({
				type: 'GET',
				async: false,
				contentType: "application/text",
				/* This uses the action attribute in the form tag.*/
				url:inForm.properties.action,
				/* This collects all the form fields and creates a JSON object to send to the server in a request format/standard way. */
				data:inForm.getFormJSON(),
				success: function(data, text){
					showSection(sectionId,false);
					/* This is a confirmation notice of success.*/
					var titleText = "received";
					if(text=="success"){titleText = "Successful!";}
					$.gritter.add({
						title: titleText,
						text: $("#"+formId).attr("title") + ' has been submitted. ' ,
						class_name: 'my-sticky-class'
					});
				},
				error: function(xhr, ajaxOptions, thrownError) {
				   Dashboard.console.log(" > > There was an error when saving "+$("#"+formId).attr("title")+": "+xhr.status+"; "+ajaxOptions+"; "+thrownError);
					$.gritter.add({
						title: 'Error!',
						text: "There was an error when saving "+$("#"+formId).attr("title")+"<br>Satus:"+xhr.status+"; <br>Option: "+ajaxOptions+"; <br>Message: "+thrownError,
						class_name: 'my-sticky-class'
					});
				}
			});
		}
		return false;
	});
	closeAllSections();
	showSection(sectionId,true);
	
}
var formsLoaded="";
function hasLoaded(formId){
	//inForm.properties.formId = formId;
	if(formsLoaded.indexOf(formId)==-1){
		formsLoaded += "|"+formId;
		return false
	} 
	return true;
}
