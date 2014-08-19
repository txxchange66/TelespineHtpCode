<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Admin Control Panel</title>
    <link rel="STYLESHEET" type="text/css" href="css/styles.css">
    <link rel="STYLESHEET" type="text/css" href="css/calendar.css">
    <script language="JavaScript" type="text/javascript" src="js/common.js"></script>
<!--    <script language="JavaScript" type="text/javascript" src="js/prototype.js"></script>-->
    
<script language='javascript' type='text/javascript' src='js/jquery.js'></script>
<!-- Grey box -->
<script type="text/javascript">  
var GB_ROOT_DIR = "js/greybox/";
</script>
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
<!-- End -->
</head>
<body>
<div id="container">
    <div id="header">
            <!header>        
    </div>
    <div id="sidebar">
        <!sidebar>
    </div>
    <div id="mainContent">
<table style="vertical-align:middle;width:700px;">
<tr>
<td style="width:400px;"><div id="breadcrumbNav"><a href="index.php?action=patientList" >PATIENT</a> / <span ><SPAN CLASS="CURRENT_ACTION"><!full_name></SPAN></span> / <span class="highlight">EDIT / VIEW PATIENT</span></div></td>
<td style="width:300px;">
    <table border="0" cellpadding="5" cellspacing="0" style="float:right;">
        <tr>
            <td class="iconLabel"></td>
            <td class="iconLabel" style="height:98px;">
          <!--      <!navigation_image_view_patient>-->
            </td>
        </tr>
    </table>
</td>
</tr>
</table>
<!--    
<script language="JavaScript" src="js/validateform.js"></script>
<script language="JavaScript">


window.formRules = new Array(
    new Rule("name_title", "title", false, "string|0,5"),
    new Rule("name_first", "first name", true, "string|0,50"),
    new Rule("name_last", "last name", true, "string|0,50"),
    new Rule("name_suffix", "name suffix", false, "string|0,5"),
    new Rule("address", "address", false, "string|0,50"),
    new Rule("address2", "address line 2", false, "string|0,50"),
    new Rule("city", "city", false, "string|0,50"),
    new Rule("state", "state", false, "string|0,2"),
    new Rule("zip", "zip code", false, "zipcode"),
    new Rule("phone1", "1st phone number", false, "usphone"),
    new Rule("phone2", "2nd phone number", false, "usphone"),
    new Rule("status", "status", false, "integer"),
    //new Rule("diagnosis", "current diagnosis", true, "string|1,50"),
    new Rule("email_address", "email address", true, "email"),
    new Rule("reminder", "reminder", false, "string|0,250"),
    new Rule("choose_therapists", "reminder", false, "string|0,250"),
    new Rule("new_password", "new password", false, "string|4,20"),
    new Rule("new_password2", "confirm password", false, "string|4,20"));

</script>
-->
<script type="text/javascript">
<!--
function handleAction(s, id)
    {
        var a = s.options[s.options.selectedIndex].value;
        var c = false;
        switch (a)
        {
            case 'view_patient_details':
                if(!patient_detail_win)
                {
                    var patient_detail_win = window.open('patient_detail_popup.php?id='+patient_id, 'Patient Details', 'width=750,height=480,resizable=1,scrollbars=auto');
                }
                patient_detail_win.focus();
                c = false;
                break;
            default:
                c = true;
                break;
        }
        s.options.selectedIndex = 0;
        if (c) window.location.href = '/admin/patient_detail.php?id=11'+ '&act=' + a + '&id=' + _id;
    }
    function submitFilter()
    {
        if(document.forms['filter'].elements['search'].value != '') document.forms['filter'].submit();
    }
    
    function showCatSelect(patient_id)
    {
        if(!csw) var csw = window.open('patient2user_cat_select.php?patient_id='+patient_id, 'catSelectWindow', 'width=750, height=480, status=no, toolbar=no, resizable=1');
        csw.focus();
    }
function handlePlanAction(s, p2p_id)
{
    var a = s.options[s.options.selectedIndex].value;
    var c = false;

    switch (a)
    {
        case 'deletePlan':
            c = confirm('Deleting this plan will remove all record of it from the site. Are you sure you want to continue with deleting this plan?');
            break;
        case 'viewPlan':
            if(!plan_detail_win)
            {
                var plan_detail_win = window.open('index.php?action=planViewer&id='+p2p_id, 'PlanPreview', 'width=969,height=688,resizable=1,scrollbars=auto');
            }
            plan_detail_win.focus();
            c = false;
            break;
        default:
            c = true;
            break;
    }
    s.options.selectedIndex = 0;
    if (c) window.location.href = 'index.php?action=therapistViewPatient&' + 'act=' + a + '&plan_id=' + p2p_id + '&id=' + <!patientId>;
}

function handle_action(s, id)
{
    var a = s.options[s.options.selectedIndex].value;
    var c = false;

    switch (a)
    {
        case 'delete':
            c = confirm('Discharging this patient will prevent them from logging-in and viewing treatment plans.  Are you sure you would like to continue with discharging this patient?');
            break;
        case 'undelete':
            c = confirm('Restoring this discharged patient will allow them to log-in and view treatment plans.  Are you sure you would like to continue with restoring this patient?');
            break;
        case 'realdelete':
            c = confirm('Deleting this patient will remove all record of them from the site. Are you sure you want to continue with deleting this patient?');
            break;
        
        default:
            c = true;
            break;
    }

    s.options.selectedIndex = 0;

    if (c) window.location.href = '/admin/patient_detail.php?' + 'act=' + a + '&id=' + id;
}
function maybeShowBillingAddress(cb)
{
    b = document.getElementById('billing_address');
    (cb.checked) ? b.style.display = 'block' : b.style.display = 'none';
}
function login_detail(patient_id){
    if(patient_id != null && patient_id != "" ){
        $content = document.getElementById("login_detail").innerHTML;    
        document.getElementById("login_detail").innerHTML = "<img src='images/ajax-loader.gif' />";
        $.post('index.php?action=system_mail_login_detail_patient',{patient_id:patient_id}, function(data,status){
                
                if( status == "success" ){
                    if(/success/.test(data)){
                        //alert("Login info email successfully sent to Patient.");    
                        //showme("Login info email successfully sent to Patient.");
                        GB_showCenter('Resend Patient\'s Login Info', '/template/alert.php?action=success', 100, 350 );
                    }
                    else if( /failed/.test(data) ){
                        //alert("E-mail delivery failed.");
                        //showme("E-mail delivery failed.");
                        GB_showCenter('Resend Patient\'s Login Info', '/template/alert.php?action=fail', 100, 350 );
                    }    
                    else{
                        alert(data);
                    }
                }
                else{
                    alert("Ajax connection failed.");
                }    
                
                document.getElementById("login_detail").innerHTML = $content;   
            }
        )        
        
    }
    else{
        alert("Patient Id not Found.");
    }
    
}
function checkAssociationClinic(patient_id){
    if(patient_id != null && patient_id != "" ){
        $content = document.getElementById("editAssociateTherapist").innerHTML;
        document.getElementById("editAssociateTherapist").innerHTML = "<img src='images/ajax-loader.gif' />";
        $.post('index.php?action=checkAssociationClinic',{patient_id:patient_id}, function(data,status){
                
                if( status == "success" ){
                    if(/success/.test(data)){
                        GB_showCenter('Associate Provider', '/index.php?action=associateTherapist&patient_id=' + patient_id + '&clinic_id=<!clinic_id>&from=patient_manager', 550, 800 );
                    }
                    else if( /failed/.test(data) ){
                        alert('Sorry, Patient does not belongs to any clinic. Please first associate the patient with any clinic. Then try again.');
                    }    
                    else{
                        alert(data);
                    }
                }
                else{
                    alert("Ajax connection failed.");
                }    
                
                document.getElementById("editAssociateTherapist").innerHTML = $content;   
            }
        )        
        
    }
    else{
        alert("Patient Id not Found.");
    }
    
}
//-->
</script>
<h1 class="largeH1">Viewing Patient: <!name_title>&nbsp;<!name_first>&nbsp;<!name_last></h1>
<form name="detailform" id="detailform" enctype="multipart/form-data" method="POST" action="/admin/patient_detail.php?id=11" onSubmit="return validate_form(this);">
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
<tr class="input">
    <td><div style="width:160px"><label for="email_address" >Email Address&nbsp;</label></div></td>
    <td width="100%" >
        <table border="0" cellspacing="0" cellpadding="0"  width="100%"   >
            <td>
                <a href="mailto:<!username>"><!username></a>
            </td>
            <td width="180px" style="align:right" id="login_detail" style="color:#0069a0;" >    
                <a href="javascript:void(0);" onclick="login_detail('<!id>');" ><strong>Resend Patient's Login Info</strong></a>
            </td>
        </table>
    </td>
</tr>
<tr class="input">
    <td><label for="choose_therapists" >Associated Provider&nbsp;</label></td>
    <td  >
        <span id='editAssociateTherapist' >
            <a href="javascript:void(0);"  onclick="checkAssociationClinic('<!patientId>')" >Edit Associated Provider</a>
        </span>
        
    </td>

</tr>
<tr class="input">
    <td><label for="login_history" >Patient Login History&nbsp;</label></td>
    <td>
        <a tabindex="19" href="javascript:void(0);" onclick="GB_showCenter('Login History', '/index.php?action=systemLoginHistory&patient_id=<!patientId>', 400, 490 );"  >View Login History</a> 
    </td>
</tr>
<tr class="input">
    <td><label for="choose_therapists" >Created On&nbsp;</label></td>
    <td><!creation_date></td>
</tr>
<tr>
    <td colspan="2" width="100%" >
        <h3>
        <div style="width:450px;float:left;padding-bottom:3px;">Patient Information</div>
        <div style="width:280px;float:right;padding-bottom:3px;">Referring Dr.</div>
        </h3>
    </td>
</tr>
<tr>
    <td colspan="2" width="100%" >
        <div style="width:450px;float:left"><!patient_address></div>
        <div style="width:280px;float:right"><!refering_physician></div>
    </td>
</tr>
<tr>
    <td colspan="2" width="100%" >
        <h3>
        <div style="width:440px;float:left">Patient Permissions</div>
        <div style="width:280px;float:right">Programs</div>
        </h3>
    </td>
</tr>
<tr class="input" >
    <td colspan="2" width="100%">
        <div style="width:450px;float:left">
                <label for="status" >Status</label>
                <span style="padding-left:107px;" >
                <!status>   
                </span>
        </div>
        <div style="width:280px;float:right;">
            <!program>
        </div>
    </td>
</tr>
<tr>
    <td colspan="2" align="center" >&nbsp;</td>
</tr>

<tr>
    <td colspan="2" align="right" ><input type="button" value="Return to Patient list" onclick="window.location='index.php?action=redirectPatientList' "/> </td>
</tr>
</table>
</form>
<!-- [end edit form] -->

        </div>
    </div>
    
    <div id="footer">
        <!footer>
    </div>
</div>

<div id="example" class="flora" title="Resend Patient's Login Info"></div>

</body>
</html>
