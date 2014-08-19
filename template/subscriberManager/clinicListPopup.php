<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Admin Control Panel</title>
	<link rel="STYLESHEET" type="text/css" href="css/styles_popup.css">
	<script language="JavaScript" type="text/javascript" src="js/common.js"></script>
	
	<script language="JavaScript">
	<!--
	function setClinicField()
	{	
		var clinic_id = "";
		
		//Check if the radio button is single or a group
		var clinicListLength = document.clinicform.selected_clinic.length;
		
		if (clinicListLength == undefined)
		{
			//single radio button
			clinic_id = document.clinicform.selected_clinic.value;
		}
		else
		{
			//radio button group
			for (var i = 0; i < document.clinicform.selected_clinic.length; i++)
			{
	      		if (document.clinicform.selected_clinic[i].checked == true)
	      		{
	         		clinic_id = document.clinicform.selected_clinic[i].value;
	         		break;
	      		}
	   		}		
		}	
		
   		if (clinic_id == "")
   		{
   			alert("Please Select A Clinic");
   		}
   		else
   		{
   			window.opener.document.getElementById('clinic_name').value = document.getElementById(clinic_id).innerHTML ;	
   			window.opener.document.getElementById('clinic_id').value = clinic_id ;	   					
			window.close();
   		}	
		
	}
	
	-->
	</script>	
</head>
<body>

<div id="container">
	<div id="header">
		<!header>
	</div>
	<div id="mainContent">
			<h1 class="largeH1">SELECT CLINIC </h1>
<form name="clinicform" id="clinic_form" enctype="multipart/form-data" method="POST" action="index.php">
<div align="center">
<table border="0" cellpadding="2" cellspacing="1" width="100%">
	<tr>
		<!clinicList>
	</tr>
	<!clinicListPopupButton>
</table>
</div>
</form>
	</div>
	</div>
</body>
</html>