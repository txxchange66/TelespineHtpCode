function XMLHTTPObject()
{
	var xmlhttp; 

	if (window.ActiveXObject) 
	{
		// Instantiate the latest Microsoft ActiveX Objects
		if (_XML_ActiveX)
		{
			xmlhttp = new ActiveXObject(_XML_ActiveX);
		
		}
		else
		{ 
			// loops through the various versions of XMLHTTP to ensure we're using the latest
			var versions = ["MSXML2.XMLHTTP", "Microsoft.XMLHTTP", "Msxml2.XMLHTTP.7.0", "Msxml2.XMLHTTP.6.0", "Msxml2.XMLHTTP.5.0", "Msxml2.XMLHTTP.4.0", "MSXML2.XMLHTTP.3.0"];			

			for (var i = 0; i < versions.length ; i++) 
			{ 
				try
				{
					// Try and create the ActiveXObject for Internet Explorer, if it doesn't work, try again.
					xmlhttp = new ActiveXObject(versions[i]); 
						
					if (xmlhttp) 
					{ 
						var _XML_ActiveX = versions[i];
						break;
					}
				}
				catch (e)
				{
					// TRAP
				};
			}
			;
		}			
	}// Well if there is no ActiveXObject available it must be firefox, opera, or something else

	if (!xmlhttp && typeof XMLHttpRequest != 'undefined')
	{
		try 
		{ 
			xmlhttp = new XMLHttpRequest(); 
		} 
		catch (e) 
		{
			xmlhttp = false; 
		}
	}
	
	return xmlhttp;
}

function processRequest(contentId,httpRequest,refreshContentOnFunction,parameterForRefresh)
{ 
	
	if (httpRequest.readyState == 4) 
	{ 
		if(httpRequest.status == 200)
		{ 
			results = httpRequest.responseText; // http.responseXML; which will lead to an XML based response, if we were to have some XML output from a server file
			
			var para = document.getElementById(contentId); //or whatever ID you gave your element. 			
			para.innerHTML = results; 		
			if (refreshContentOnFunction != "null")
			{		
				refreshContent(refreshContentOnFunction,parameterForRefresh);	
							
			}
			
		}
		else 
		{ 
			var results = "Sorry, there was an error finding the server-side file. Please contact support."; 
			var para = document.getElementById(contentId); 
			para.innerHTML = results; 
		}
		
	}	

	
}

function processRequestConfirm(contentId,httpRequest,extraParam,parameterForRefresh,clinic_id)
{ 
	
	if (httpRequest.readyState == 4) 
	{ 
		if(httpRequest.status == 200)
		{ 
			results = httpRequest.responseText; // http.responseXML; which will lead to an XML based response, if we were to have some XML output from a server file
			
			//results = Trim(results);
			//alert(results.length);
			
			var para = document.getElementById(contentId); //or whatever ID you gave your element. 			
			
			
			if (results.length == 4)
			{
				para.innerHTML = ""; 	
			}
			else
			{
				para.innerHTML = results; 		
			}
			
			
			
			if (extraParam == "Add")
			{		
				addProcess(clinic_id);	
							
			}
			else if (extraParam == "Edit")
			{
				editProcess(clinic_id);
			}
			
		}
		else 
		{ 
			var results = "Sorry, there was an error finding the server-side file. Please contact support."; 
			var para = document.getElementById(contentId); 
			para.innerHTML = results; 
		}
		
	}	

	
}
function processRequestConfirmSystem(contentId,httpRequest,extraParam,parameterForRefresh,clinic_id)
{ 
	
	if (httpRequest.readyState == 4) 
	{ 
		if(httpRequest.status == 200)
		{ 
			results = httpRequest.responseText; // http.responseXML; which will lead to an XML based response, if we were to have some XML output from a server file
			
			//results = Trim(results);
			//alert(results.length);
			
			var para = document.getElementById(contentId); //or whatever ID you gave your element. 			
			
			
			if (results.length == 4)
			{
				para.innerHTML = ""; 	
			}
			else
			{
				para.innerHTML = results; 		
			}
			
			
			
			if (extraParam == "Add")
			{		
			//	alert('ass');
                addProcessSystem(clinic_id);	
							
			}
			else if (extraParam == "Edit")
			{
				editProcessSystem(clinic_id);
			}
			
		}
		else 
		{ 
			var results = "Sorry, there was an error finding the server-side file. Please contact support."; 
			var para = document.getElementById(contentId); 
			para.innerHTML = results; 
		}
		
	}	

	
}
function checkValidation(uniqueId,clinic_id)
{
	var httpRequest=null;
	var extraParam = "";
	var formName = "";
	
	if (uniqueId == 'NA')
	{
		extraParam = 'Add';
		formName = "addUserForm";
	}
	else
	{
		extraParam = 'Edit';
		formName = "editUserForm";
	}
	
	
	var result = true;	
	var message = "";
	
	var contentId = "errorDiv";	
	
	httpRequest = XMLHTTPObject();				
	var varContentUrl =  "../index.php?action=checkValidation&uniqueId="+uniqueId;				
	
		
	var para = document.getElementById(contentId);
	//para.innerHTML = "<img src='../resources/images/loading.gif'/>"; 
	
	//The field values
	var params = "name_first=" + encodeURI(document.getElementById("name_first").value)+
	"&name_last=" + encodeURI(document.getElementById("name_last").value)+
	"&username=" + encodeURI(document.getElementById("username").value)+
	//"&confirmUsername=" + encodeURI(document.getElementById("confirmUsername").value)+
	"&new_password=" + encodeURI(document.getElementById("new_password").value)+
	"&new_password2=" + encodeURI(document.getElementById("new_password2").value)+
	"&therapistAccess=" + eval("document."+formName+".therapistAccess.checked")+
	"&adminAccess=" + eval("document."+formName+".adminAccess.checked")+
	"&address=" + encodeURI(document.getElementById("address").value)+
	"&address2=" + encodeURI(document.getElementById("address2").value)+	
	"&city=" + encodeURI(document.getElementById("city").value)+
	"&state=" + encodeURI(document.getElementById("state").value)+
	"&zip=" + encodeURI(document.getElementById("zip").value)+
	"&practitioner_type=" + encodeURI(document.getElementById("practitioner_type").value);

	
	

	httpRequest.open("POST", varContentUrl, true);
	httpRequest.onreadystatechange = function () {processRequestConfirm(contentId,httpRequest,extraParam,"",clinic_id); } ;
	httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	httpRequest.setRequestHeader("Content-length", params.length);
	//httpRequest.setRequestHeader("Connection", "close");
	httpRequest.send(params);

	
	
}
function checkValidationSystem(uniqueId,clinic_id)
{
    var httpRequest=null;
	var extraParam = "";
	var formName = "";
	
	if (uniqueId == 'NA')
	{
		extraParam = 'Add';
		formName = "addUserForm";
	}
	else
	{
		extraParam = 'Edit';
		formName = "editUserForm";
	}
	
	
	var result = true;	
	var message = "";
	
	var contentId = "errorDiv";	
	
	httpRequest = XMLHTTPObject();				
	var varContentUrl =  "../index.php?action=checkValidationSystem&uniqueId="+uniqueId;				
	
		
	var para = document.getElementById(contentId);
	//para.innerHTML = "<img src='../resources/images/loading.gif'/>"; 
	
	//The field values
	var params = "name_first=" + encodeURI(document.getElementById("name_first").value)+
	"&name_last=" + encodeURI(document.getElementById("name_last").value)+
	"&username=" + encodeURI(document.getElementById("username").value)+
	//"&confirmUsername=" + encodeURI(document.getElementById("confirmUsername").value)+
	"&new_password=" + encodeURI(document.getElementById("new_password").value)+
	"&new_password2=" + encodeURI(document.getElementById("new_password2").value)+
	"&therapistAccess=" + eval("document."+formName+".therapistAccess.checked")+
	"&adminAccess=" + eval("document."+formName+".adminAccess.checked")+
	"&address=" + encodeURI(document.getElementById("address").value)+
	"&address2=" + encodeURI(document.getElementById("address2").value)+	
	"&city=" + encodeURI(document.getElementById("city").value)+
	"&clinic_country=" + encodeURI(document.getElementById("country").value)+
	"&state=" + encodeURI(document.getElementById("state").value)+
	"&zip=" + encodeURI(document.getElementById("zip").value)+
	"&practitioner_type=" + encodeURI(document.getElementById("practitioner_type").value);

	
	

	httpRequest.open("POST", varContentUrl, true); 
	httpRequest.onreadystatechange = function () {processRequestConfirmSystem(contentId,httpRequest,extraParam,"",clinic_id); } ;
	httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	httpRequest.setRequestHeader("Content-length", params.length);
	//httpRequest.setRequestHeader("Connection", "close");
	httpRequest.send(params);

	
	
}

function populateAddress(formType,clinicId)
{
	var httpRequest=null;

	var refreshContent = "null";	
	
	var contentId = "divAddress";	
	
	var strEval = "";
	
	if (formType == 'Add')
	{
		strEval = document.addUserForm.clinicAddress.checked;
	}
	else if (formType == 'Edit')
	{
		strEval = document.editUserForm.clinicAddress.checked;
	}

	if (eval(strEval) == true)
	{
		httpRequest = XMLHTTPObject();				
		if( clinicId != null ){
			var varContentUrl =  "../index.php?action=populateAddressSystem" + "&clinic_id= " + clinicId;				
		}
		else{
			var varContentUrl =  "../index.php?action=populateAddress";	
		}
	
		
	}
	else
	{
	
		httpRequest = XMLHTTPObject();
		if( clinicId != null ){
			var varContentUrl =  "../index.php?action=emptyAddressSystem" + "&clinic_id= " + clinicId;				
		}				
		else{
			var varContentUrl =  "../index.php?action=emptyAddress";			
		}	
	}	
		
	var para = document.getElementById(contentId);
	//para.innerHTML = "<img src='../resources/images/loading.gif'/>"; 

	httpRequest.open("GET", varContentUrl, true); 
	httpRequest.onreadystatechange = function () {processRequest(contentId,httpRequest,refreshContent,""); } ;
	httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	httpRequest.send(null);	
	
}

function addProcess(clinic_id)
{
    
	var objErrDiv = document.getElementById('errorDiv');	
    if(typeof clinic_id == 'undefined'){
        clinic_id = '';
    }
	if (objErrDiv.innerHTML == "		" || objErrDiv.innerHTML == "")
	{
		if (document.addUserForm.therapistAccess.checked == true)
		{
            window.open('index.php?action=popupConfirm&formType=add&clinic_id=' + clinic_id, 'confirmSubmit', 'width=740, height=310, status=no, toolbar=no, resizable=yes, scrollbars=yes');	
		}
		else
		{
			//submit form			
			document.addUserForm.submit();
		}
	}
	
}
function trim(str, chars) {
	return ltrim(rtrim(str, chars), chars);
}
function ltrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("^[ ]+", "g"), "");
}
 
function rtrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("[ ]+$", "g"), "");
}


function addProcessSystem(clinic_id)
{
	var objErrDiv = document.getElementById('errorDiv');	
	
	//alert("'"+objErrDiv.innerHTML+"'");
    //document.getElementById('sanjayTest').value=objErrDiv.innerHTML;
    //alert(trim(objErrDiv.innerHTML).length);
	if (objErrDiv.innerHTML == "		" || objErrDiv.innerHTML=="")
	{
		//alert(trim(objErrDiv.innerHTML));
        //alert(document.addUserForm.therapistAccess.checked);
        if (document.addUserForm.therapistAccess.checked == true)
		{
			window.open('index.php?action=popupConfirmSystem&formType=add&clinic_id=' + clinic_id, 'confirmSubmit', 'width=740, height=310, status=no, toolbar=no, resizable=yes, scrollbars=yes');	
		}
		else
		{
			//submit form			
			document.addUserForm.submit();
		}
	}
	
}
function editProcess(clinic_id)
{
	var objErrDiv = document.getElementById('errorDiv');	
	 if(typeof clinic_id == 'undefined'){
        clinic_id = '';
    }
	
	if (objErrDiv.innerHTML == "" || objErrDiv.innerHTML == "		" )
	{
		if (document.editUserForm.firstSubscription.value == 'Yes' && document.editUserForm.therapistAccess.checked == true)
		{
			window.open('index.php?action=popupConfirm&formType=edit&clinic_id=' + clinic_id, 'confirmSubmit', 'width=740, height=310, status=no, toolbar=no, resizable=yes, scrollbars=no');	
		}
		else
		{
			//submit form			
			document.editUserForm.submit();
		}
	}
	
}
function editProcessSystem(clinic_id)
{
	var objErrDiv = document.getElementById('errorDiv');	
	
	
	if (objErrDiv.innerHTML == "" || objErrDiv.innerHTML == "		" )
	{
		if (document.editUserForm.firstSubscription.value == 'Yes' && document.editUserForm.therapistAccess.checked == true)
		{
			window.open('index.php?action=popupConfirmSystem&formType=edit&clinic_id=' + clinic_id, 'confirmSubmit', 'width=740, height=310, status=no, toolbar=no, resizable=yes, scrollbars=no');	
		}
		else
		{
			//submit form			
			document.editUserForm.submit();
		}
	}
	
}