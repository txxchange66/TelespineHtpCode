/* *****************************************************
 * Global & Extensible Validation Script by Drew Ambrose 
 * Created 6/12/2013. - Andrew Ambrose
 *
 * Added support for placeholder with no event conflicts.
 * Version 2.14 Added:
 * 1) Conditional validation checking. See inForm.hasCondition()
 * 2) Property to disable scrolling to the message. See inForm.properties.scrollToMessage
 * *****************************************************
*/ 

 if(window.console){if(!console.trace) {console.trace = console.log;}}

jQuery(document).ready(
	function(){
		if(typeof App.console == "undefined"){App.console = {log: function(){}};}
        App.console.group("InForm Validation");
		App.console.log("**************************************************************************");
		App.console.log("* Script inForm: Generic Validation Tool");
		App.console.log("* jQuery Required: Loaded: "+ (jQuery!=undefined));
		try{
			if(inForm){
				App.console.log("* Script: inForm.version: "+inForm.properties.version);
				App.console.log("* Note: Fields should contain a class called 'required'. ");
				App.console.log("*  <input id='fieldName' class='required' data-role='characters-only'> ");
				App.console.log("*  'data-role' specifies the type of validation to perform. ");
				App.console.log("*  Validation methods:");
				App.console.log("*   > email, not-empty, selected, characters-only, numbers-only, phone ");
				App.console.log("*  Validate length: [maxlength, minlength]");
				App.console.log("*  <input type='text' name='usrname' maxlength='10' minlength='5' data-role='not-blank' class='required' placeholder='First Name'> ")
				App.console.log("*  (If maxlength, minlength are provided, then it is validated accoringly) ")
			}
			/* Add placeholder to support */
			jQuery.support.placeholder = (function(){
				var i = document.createElement('input');
				App.console.log("Adding placeholder to support to jQuery.");
				return 'placeholder' in i;
			})();
		} catch(ert){
			App.console.log("**************************************************************************");
			App.console.log("* Error: Required Libraries jQuery");
			App.console.log(ert);	
		}
		App.console.log("**************************************************************************\n");
        App.console.groupEnd();
	}
);

/* Not an instance object, namespace reserved for inForm */
var inForm = {
	properties: {
       version:"2.14",
		eventsSet: false,
		formId: "",
		action: "",
		callback: null,
		callbackSet: false,
		errorClass:"",
		errorTrigger:false,
		scrollToMessage:false
	},
	init: function(formId, errorClass, callback){
                App.console.group("Instatiate InForm");
		App.console.log("*************************************");
                App.console.log(" Load Form Validation (inForm) \n Make sure all form IDs are unique.");
		if(formId!=undefined){this.properties.formId = formId;}
		if(errorClass!=undefined){this.properties.errorClass = errorClass;}
		if(callback!=undefined){
			App.console.log("A callback method was supplied. When validation is complete the form will call the boolean callback(formId) method.");
			this.properties.callback = callback;
			this.properties.callbackSet = true;	
		} else {
			App.console.log("No callback method supplied. When validation is complete the form will submit.");	
		}
		try{
			/************************************** 
			In the case of a JavaScript error forms will 
			continue processing, and thus submit. We set a 
			preference here to remove the action so that 
			the form does not submit. When validate we set 
			back the action.  
			**************************************/
			this.properties.action = jQuery("#"+this.properties.formId).attr("action");
			
			var base = this;
			//jQuery("#"+this.properties.formId).attr("action","#StopSubmit");
			jQuery("#"+this.properties.formId).submit(
				function(e){return base.validate();}
			);
		} catch (iE){
			App.console.log(iE);	
		}
		
		try{this.setPlaceholder();} catch (iE){App.console.log(iE);}
		App.console.log( this.properties );
		App.console.log("*************************************");
        App.console.groupEnd();
	},
	/**
	@method
	This is a placeholder function that if the HTML5 attribute placeholder is unavailable 
	will create the functionality inline of each input and textarea tag that has the 
	placeholder attribute. This is important to localize here as the onBlur event has 
	been reserved for form validation. Just simply use the placeholder as it ought to 
	be used in HTML5 boilerplate standards.
	*/
	setPlaceholder: function(){
		if(!jQuery.support.placeholder){
			/* If it is supported do nothing at all, else execute this function. */
			App.console.log("Creating placeholder support because this browser does not support it. ");
			var base = this;
			/* 
			Do this once on init:
			The focus event is not used elsewhere, only blur. 
			So adding a fucus event will not cause issues.
			Iterate through each field, set the placeholder text and className.
			*/
			jQuery("#"+base.properties.formId+" input, #"+base.properties.formId+" textarea, #"+base.properties.formId+" select").each(function() {
				/* Set inital value */
				if (jQuery(this).hasClass("required")) {
					if(jQuery(this).attr("placeholder")!=""){
						if(jQuery(this).val()==''){
							jQuery(this).val(jQuery(this).attr("placeholder")).addClass('placeholder');
						}
						/* Now, Add event*/
						jQuery(this).focus(function(e) {
							if( jQuery(this).val() == jQuery(this).attr("placeholder") ){
								jQuery(this).val('');
								jQuery(this).removeClass('placeholder');
							}
						});
					}
					/* ****************************************************
					This may not be best set here. Dev Int Test;
					This is to set onBlur onload. - axambro
					This will also call validation before submitting. */
					base.setValidationEvent(jQuery(this));
				}
			});
		}
	},
	/**
	* @method 
	* The blur event will call this function. 
	*/
	isPlaceholderText: function(obj){
		if(!jQuery.support.placeholder){
			/* If it is supported do nothing at all, else execute this function. */
			if( jQuery(obj).val() == jQuery(obj).attr("placeholder") ){
				jQuery(obj).addClass('placeholder');
				/* Stops here because the value has not changed. So validation failed for this required field. */
				return true;
			} else if(jQuery(obj).val() ==""){
				jQuery(obj).val(jQuery(obj).attr("placeholder"));
				jQuery(obj).addClass('placeholder'); 
				return true;
			} else {
				jQuery(obj).removeClass('placeholder');
				return false;
			}
		} else {
			/* If reaching here validation does not do anything else 
			   to further validate the placeholder. */
			return false;	
		}
	},
	/**
	* @method
	* see isConditionMet()
	*/
	hasCondition: function(methodName,obj){
		if(methodName.indexOf("|")!=-1){
			App.console.log(" > An extra condition was found in inForm.hasCondition() ",2);
			return true;
		}
		return false;
	},
	/**
	* @method
	* the data-role attribute will contain the methodName. 
	* If the methodName contains a pipe "|" then this indicates a condition.
	* So, it will assume the condition method proceeds before the pipe as a string function.
	* Syntax:  data-role="isBillingChecked|not-blank". 
	* This means that if isBillingChecked(obj) is true then validate "not-blank".
	* This would be an externally defined function you must provide on the html page script.
	*/
	isConditionMet: function(methodName,obj){
		/* Returns true to continue validation. If false no validation should be required for the field. */
		var conditionStringStart = methodName.indexOf("|");
		var validationMethod = methodName.substring(0,conditionStringStart);
		console.log(" > isConditionMet(): "+validationMethod);
		try{
			App.console.log(" > > Checking "+validationMethod+"() ",2);
			this.clearErrorMessage(obj);
			return window[validationMethod]();
		} catch(ve){
			App.console.trace(ve,2);
			return false;
		}
	},
	/**
	@method
	These are the validation Methods. 
	Next version will create an array of validation 
	methods to enable specific conditioning.
	*/
	isNotValid: function(methodName,obj){
		App.console.log(" > Validating "+jQuery(obj).attr("id"));
        /** IMPORTANT: Returns true if NOT valid! TODO: Reverse function boolean.  */
        /** First, check for placeholder text. If it is then the value has not been set. */
		if(this.isPlaceholderText(obj) && methodName!="none"){return true;}
        /**
		  If we want to validate the field only if not blank then we can test 
		  if the methodName has "!" in it. This identifies that we validate only 
		  if the field is not blank, but still remains optional. If the field is 
		  blank it is also valid. 
		*/
		if(jQuery(obj).val().trim().length==0 && methodName.indexOf("!")!=-1){
                        return false; /* This means it IS valid */
		} else if(methodName.indexOf("!")!=-1){
            /* OK, its not blank, validate as usual. */
			methodName = methodName.replace("!","");
		}
		/* Check for a conditional validation argument */
		if(this.hasCondition(methodName,obj)){
			try{
				App.console.log(" > methodName is: "+methodName);
				if(this.isConditionMet(methodName,obj)){
					/* Stop, no validation required. See this.isConditionMet() */
					return false;
				} else {
					/* proceed as usual */
				} 
			} catch (cvme){}
			methodName = methodName.substring(methodName.indexOf("|")+1);
			App.console.log(" > methodName is: "+methodName);
		} /* else { proceed as usual. } */
		/*
		Now Validate if maxlength or minlength has been set. If so, validate first.
		*/
		try{
                    if(jQuery(obj).attr("maxlength") && jQuery(obj).attr("maxlength")!=""){
				if(jQuery(obj).val().length > parseInt(jQuery(obj).attr("maxlength"))){
					App.console.log(" The attribute 'maxlength' was set for the field and it was exceeded. (Possible with paste)");
                                    return true; /* Not Valid */
				}
			}
                        if(jQuery(obj).attr("minlength") && jQuery(obj).attr("minlength")!=""){
				if(jQuery(obj).val().length < parseInt(jQuery(obj).attr("minlength"))){
					App.console.log(" The attribute 'minlength' was set for the field and it was not met.");
                                        return true; /* Not Valid */
				}
			}
                } catch (efg){App.console.trace(efg);}
		/* Validate Email Address  */
		if(methodName=="email"){
			//App.console.log(" > Is this a valid email? ");
			var emailAddressPattern = new RegExp(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/);
			return !emailAddressPattern.test(jQuery(obj).val());
		} 
		/* Validate if a select list item has been selected  */
		else if(methodName=="selected"){
			var selIndex = jQuery(obj).prop("selectedIndex");
			App.console.log(" > Is an item selected from the list? Selected Index: "+selIndex);
			return !(selIndex!=0 && selIndex!=-1);
		} 
		/* Validate that the field is not blank  */
                else if(methodName=="not-empty" || methodName=="not-blank"){
			return !(jQuery(obj).val().trim()!="");
		} 
		/* Validate that the field is not blank AND matches the value of another field  */
		else if(methodName.indexOf("matchField:")!=-1){
                    try{
			var mtch = methodName.split(":");
                        return !(jQuery(obj).val().trim()!="" && (jQuery("#"+mtch[1]).val()==jQuery(obj).val()));
                    } catch (mfe){
                        App.console.trace(mfe);
			} finally {}
                    }
		/* Validate that the field is a phone number */
		else if(methodName=="phone"){
                    App.console.log(" > Validating that the phone number allows formatting, but is at least 10 numbers. \n > (303)123-1234, 303-123-1234 are acceptable.");
                    var pNum = jQuery(obj).val();
                    var phonePattern = new RegExp(/^([0-9]( |-)?)?(\(?[0-9]{3}\)?|[0-9]{3})( |-)?([0-9]{3}( |-)?[0-9]{4}|[0-9]{7})$/);
                    return !phonePattern.test(jQuery(obj).val());
		} else  if (methodName=="characters-only") {
                    var charPattern = new RegExp(/[a-zA-Z]+/);
                    return !charPattern.test(jQuery(obj).val());
		} else  if (methodName=="numbers-only") {
                    var numPattern = new RegExp(/[0-9]+/);
                    return !numPattern.test(jQuery(obj).val());
                } else if(methodName=="password"){
           /* value must have a character, and a didgit, and length must be at least 8 */ 
					 var numPattern = new RegExp(/[0-9]+/);
					 var charPattern = new RegExp(/[a-zA-Z]+/);
					 var hasNumber = numPattern.test(jQuery(obj).val());
					 var hasChar = charPattern.test(jQuery(obj).val());
					  App.console.log(" > Has number? "+hasNumber);
					  App.console.log(" > Has character? "+hasChar);
						return !((hasChar && hasNumber) && (jQuery(obj).val().length>=8));
		} else  if (methodName=="none") {
			/* this will force it to bypass validation */
			return false;
		} else if(methodName=="alpha-numaric-only"){
                  var numPattern = new RegExp(/^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$/);
                  //var numPattern = new RegExp(/^[0-9a-zA-Z`~!@#$%^&*()-_=+.><?|]+$/);
                    //var numPattern = new RegExp(/^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9`~!@#$%^&*()-_=+.><?|]+)$/);
                    return !numPattern.test(jQuery(obj).val());
                } else{
                    App.console.log("The method you provided does not exist. Please check the '"+ jQuery(obj).attr("id") +
				"' field for the data-role attribute for '"+ methodName +"'. \n > Validating to make sure it's not blank. ");
			return !(jQuery(obj).val()!="");
		}
	},
	clearErrorMessage: function(obj){
		jQuery(obj).removeClass(this.properties.errorClass);
		var vfid = "#"+jQuery(obj).attr("id")+"-message";
		jQuery(vfid).fadeOut(400);
	},
	showErrorMessage: function(obj){
            try{
		jQuery(obj).addClass(this.properties.errorClass);
		var vfid = "#"+jQuery(obj).attr("id")+"-message";
		jQuery(vfid).slideDown(400);
            } catch (se){
                App.console.trace(" > Errors Highlighting Field: "+se);
            }
	},
	getErrorCount: function(){
		var count=0;
		var base = this;
		//App.console.log(" > > getErrorCount");
		jQuery("#"+base.properties.formId+" input, #"+base.properties.formId+" select, #"+ base.properties.formId +" textarea").each(function() {
			if (jQuery(this).hasClass(base.properties.errorClass)) {
				count++;
			}
		});
		App.console.log(" > Errors Encountered: "+count);
		return count;
	},
	validateField: function(obj){
		var base = this;
		var valType = jQuery(obj).attr("data-role");
		/* returns true/false */
		var valid = base.isNotValid(valType,jQuery(obj));
		App.console.log(" > Validate: "+ valType +"('"+ jQuery(obj).attr("id") +"'). \n > > Is it valid? "+!valid);
		return valid;
	},
	setValidationEvent: function(obj){
                App.console.log(" > Setting the onBlur event for "+jQuery(obj).attr("id"),5);
		var base = this;
		jQuery(obj).on('blur',
			function(e){
				try{
					if(base.validateField(jQuery(obj))){
						base.showErrorMessage(jQuery(obj));
						base.showFormError();
					} else {
                                                /*var formId = jQuery(this).parent('form').attr("id");*/
						base.showFormError();
						base.clearErrorMessage(jQuery(obj));
					}
				} catch (eR){
                                    App.console.trace("Error while setting onBlur event. Could also be getErrorCount"+eR);
                                } finally {
										e.stopPropagation();
                                }
                        }
		);
	},
        showFormError: function(customMessage){
       App.console.log(" > inForm.showFormError()",2);
		/* 
		Setting a timeout to release this function from 
		the current thread which prevents the proper update 
		of the count of errors.
		*/
		setTimeout(
			function(){
                        try{
                            if(customMessage==undefined){
                                App.console.log(" > Check for Errors",2);
				var errorCount = inForm.getErrorCount();
				if(errorCount > 0){
					jQuery("#"+inForm.properties.formId+"-message").fadeOut(100);
					jQuery("#"+inForm.properties.formId+"-message").fadeIn(500);
					jQuery("#"+inForm.properties.formId+"-message").focus();
					try{
                                                /* Scroll to error message - first time only */
						if(!inForm.properties.errorTrigger){
									if(inForm.properties.scrollToMessage){
						jQuery('body, html').animate({
							 scrollTop: jQuery("#"+inForm.properties.formId+"-message").offset().top
						 }, 2000);
									}
						 inForm.properties.errorTrigger = true;
						}
                                        } catch (err){App.console.log(err);}
				} else {
					jQuery("#"+inForm.properties.formId+"-message").hide();
				}
				if(errorCount==1){
					jQuery("#"+inForm.properties.formId+"-message").html("<!--<i class='fa fa-info-circle alert-icon'></i>--> There is 1 error that needs "+
							"your attention before you can submit this form.");
				} else {
					jQuery("#"+inForm.properties.formId+"-message").html("<!--<i class='fa fa-info-circle alert-icon'></i>--> There are "+errorCount+" errors that need "+
							"your attention before you can submit this form.");
				}
                    } else {
                        App.console.log(" > inForm.showFormError: "+ customMessage,2);
                        jQuery("#"+inForm.properties.formId+"-message").fadeIn(500);
                        jQuery("#"+inForm.properties.formId+"-message").focus();
                        jQuery("#"+inForm.properties.formId+"-message").html("<!--<i class='fa fa-info-circle alert-icon'></i>--> "+customMessage);
                    }
                } catch(ger){App.console.log(ger);}
			},100
		);
	},
        /** Validate the whole form */
	validate: function(formId){
        App.console.group("Validate");
		var base = this;
                App.console.log("\nLoop through all form fields > \n************************************", 2);
                jQuery("#"+base.properties.formId+" input, #"+base.properties.formId+" select, #"+base.properties.formId+" textarea").each(
					function() {
                        App.console.log(" > Field: "+jQuery(this).attr("name"), 2);
			if (jQuery(this).hasClass("required")) {
                            App.console.log(" > > Required field: "+jQuery(this).attr("name"), 2);
				if(base.validateField(jQuery(this))){
					base.showErrorMessage(jQuery(this));
					/* Setting the blur event now only happens once on init */
					if(jQuery.support.placeholder){
						base.setValidationEvent(jQuery(this));
					}
				}
			}
		});
		var errorCount = base.getErrorCount();
		App.console.log("Form: \n"+base.getFormJSON());
		if(errorCount > 0){
			base.showFormError();
			return false;
		} else {
			/* Now set back the action  */
            App.console.log("\n********************************", 1);
            App.console.log(" The form looks good.", 2);
            App.console.log("********************************", 1);
			jQuery("#"+base.properties.formId).attr("action",base.properties.action);	
			/* Check for a callback(formId) method. */
			if(base.properties.callbackSet){
                            App.console.log("This validation method has been extended using a callback method. "+
										"The method must return true or false; An argument of the form id is passed to the callback method.");
				try{
					base.properties.callback(base.properties.formId);
				} catch (eRR){
					App.console.log(eRR.message);
				}
				return false;
			} else {
				return true;
			}
		}
        App.console.groupEnd();
	},
	getJSON: function(obj){
		var name = jQuery(obj).attr("name");
		return "\""+name+"\": \""+jQuery(obj).val()+"\"";
	},
	getFormJSON: function(){
		var jsonString="{";
		var base = this;
		App.console.log("Find fields in form: "+base.properties.formId,5);
		jQuery("#"+base.properties.formId+" input, #"+base.properties.formId+
			" textarea, #"+base.properties.formId+" select, input[type='radio']:checked, input[type='checkbox']:checked").each(function() {
				App.console.log(" > Field: "+ jQuery(this).attr("name") +", id: "+ jQuery(this).attr("id"),5);
				if(jQuery(this).attr("type")!="submit" && jQuery(this).attr("type")!="reset" && jQuery(this).attr("name")!= undefined){
					if(jQuery(this).attr("type")!="radio" && jQuery(this).attr("type")!="checkbox"){
						jsonString += base.getJSON(this)+", "
					} else if(jQuery(this).attr("checked")){
						jsonString += base.getJSON(this)+", "
					}
				}
		});
		jsonString= jsonString.substring(0,jsonString.length-2);
		jsonString = jsonString + "}";
		return jsonString;
	},
	setFormId: function(formId){
		if(formId!=undefined){this.properties.formId = formId;}
	}
}