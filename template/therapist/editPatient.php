<script src="js/jquery.min.js"></script>      
<script src="js/jquery.tools.min.js"></script>

     


    <!-- tooltip styling -->
    <style>
    
    .tooltip {
        display:none;
        background-color:#F4F4f4;
        border:1px solid #cc9;
        width:200px;
        padding:3px;
        font-size:13px;
        position: absolute;        
        -moz-box-shadow: 2px 2px 11px #666;
        -webkit-box-shadow: 2px 2px 11px #666;
        z-index:1000000;
      /* for IE */
          
    }


</style>
<script>
// What is $(document).ready ? See: http://flowplayer.org/tools/documentation/basics.html#document_ready
$(document).ready(function() {
   
    $(".enable-con-inner img").mouseover(function(){
        
         $(".tooltip").html($(this).attr("name")).show().css({marginTop:$(this).offset().top+10+"px", marginLeft:$(this).offset().left+17+"px"});
        
    });
    $(".enable-con-inner img").mouseout(function(){
        
     $(".tooltip").hide();   
        
    });    
    /*  
    $("img[title]").tooltip({

        // use div.tooltip as our tooltip
        tip: '.tooltip',

        // use the fade effect instead of the default
        effect: 'fade',

        // make fadeOutSpeed similar to the browser's default
        fadeOutSpeed: 100,

        // the time before the tooltip is shown
        predelay: 0,

        // tweak the position
        position: "top right",
        offset: [-5, -350]
    }); */
});
</script>

<script language="JavaScript" src="js/countryState.js"></script>
<div id="container">
    <div id="header">
        <!header>
    </div>
    <div id="sidebar">
        <!sidebar>
    </div>
    <div id="mainContent">
<table style="vertical-align:middle;width:700px;"><tr><td style="width:400px;"><div id="breadcrumbNav"><a href="index.php?action=myPatients" >PATIENT</a> / <span ><SPAN CLASS="CURRENT_ACTION"><!full_name></SPAN></span> / <!--<span class="highlight">EDIT PATIENT</span>--><span class="highlight">EDIT HEALTH RECORD</span></div></td><td style="width:300px;">    <table border="0" cellpadding="5" cellspacing="0" style="float:right;">
<tr>
        <td class="iconLabel" style="height:81px;" >&nbsp;<!--<!navigation_image>--></td>
    </tr>
</table>
    </td></tr></table>
    <!--
<script language="JavaScript" src="js/validateform1.js"></script>
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
        if (c) window.location.href = '/admin/patient_detail.php?id=11&edit=1'+ '&act=' + a + '&id=' + _id;
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
                var plan_detail_win = window.open('index.php?action=planViewer&id='+p2p_id, 'PlanPreview', 'width=1024,height=768,resizable=1,scrollbars=auto');
            }
            plan_detail_win.focus();
            c = false;
            break;
        default:
            c = true;
            break;
    }
    s.options.selectedIndex = 0;
    if (c) window.location.href = 'index.php?action=therapistEditPatient&' + 'act=' + a + '&plan_id=' + p2p_id + '&id=' + '<!patientId>';
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

    if (c) window.location.href = '/admin/patient_detail.php?edit=1&' + 'act=' + a + '&id=' + id;
}
function maybeShowBillingAddress(cb)
{
    b = document.getElementById('billing_address');
    (cb.checked) ? b.style.display = 'block' : b.style.display = 'none';
}
function isValidEmail(emailAddress) {
     var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
     return re.test(emailAddress);
}
//-->
</script>
<!-- [detail form] -->
<h1 class="largeH1">Edit Health Record: <!name_title>&nbsp;<!name_first>&nbsp;<!name_last><small>&nbsp;&nbsp;&nbsp;<a href="index.php?action=therapistViewPatient&id=<!id>">View Health Record</a></small></h1>
<!-- start edit form -->
<div>
<!error>
</div>
<form name="detailform" id="detailform" enctype="multipart/form-data" method="POST" action="index.php?action=therapistEditPatient&subaction=update&id=<!id>"  onsubmit="if (isValidEmail(document.forms['detailform'].email_address.value)) { return true; } else { alert('NOT a valid email address');return false; }" >
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
<tr class="input">
    <td><div style="width:160px"><label for="email_address" >*&nbsp;Email Address&nbsp;</label></div></td>
    <td width="100%"><input type="text" name="email_address" id="email_address" size="50" maxlength="50" value="<!email_address>"/></td>
</tr>
<tr class="input">
    <td><label for="new_password" >&nbsp;&nbsp;Password&nbsp;</label></td>
    <td><input type="password" name="new_password" id="new_password" size="20" maxlength="20" value="<!new_password>" /></td>
</tr>

<tr class="input">
    <td><label for="confirm_password" >&nbsp;&nbsp;Confirm Password&nbsp;</label></td>
    <td><input type="password" name="confirm_password" id="confirm_password" size="20" maxlength="20" value="<!confirm_password>" /></td>
</tr>
<tr>
    <td colspan="2"><h3>Referring Dr.</h3></td>
</tr>
<tr class="input">
    <td><label for="name_title" >&nbsp;&nbsp;Referring Dr.&nbsp;</label></td>
    <td><input type="text" name="refering_physician" id="refering_physician" size="50" maxlength="20"  value="<!refering_physician>" /></td>
</tr>
<tr>
    <td colspan="2"><h3>Patient Information</h3></td>
</tr>
<!--</a><tr class="input">
    <td><label for="name_first" >&nbsp;Company Name&nbsp;</label></td>
    <td><input type="text" name="company" id="company" size="50" maxlength="50"  value="<!company>"/></td>
</tr>-->
<tr class="input">
    <td><label for="name_title" >&nbsp;&nbsp;Title&nbsp;</label></td>
    <td><select name="name_title" id="name_title" >
<option value="">Choose...</option>
<!prefixOption>
</select>
</td>
</tr>
<!--<tr class="input">
    <td><label for="name_first" >*&nbsp;First Name&nbsp;</label></td>
    <td><input type="text" name="name_first" id="name_first" size="50" maxlength="20"  value="<!name_first>"/></td>
</tr>
<tr class="input">
    <td><label for="name_last" >*&nbsp;Last Name&nbsp;</label></td>

    <td><input type="text" name="name_last" id="name_last" size="50" maxlength="20"  value="<!name_last>"/></td>
</tr>
-->
<tr><td colspan="2">
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
	<tr class="input">
	    <td width="155px;"><label for="name_first" >*&nbsp;First Name&nbsp;</label></td>
	    <td width="255px;"><input type="text" name="name_first" id="name_first" size="25" maxlength="20"  value="<!name_first>"/></td>
	    <td width="90px;"><label for="Gender" >Gender</label></td>
	    <td><select name="gender" id="gender" >
<option value="" selected="true">Select</option>
<!genderOption>
</select></td>
	    
	</tr>
	<tr class="input">
	    <td><label for="name_last" >*&nbsp;Last Name&nbsp;</label></td>
	    <td><input type="text" name="name_last" id="name_last" size="25" maxlength="20"  value="<!name_last>"/></td>
	    <td><label for="Date Of Birth" >Date Of Birth</label></td>
	    <td><input type="text" name="dob" id="dob" size="10" maxlength="10"  value="<!dob>"></td>
	</tr>
</table>
</td>
</tr>
<tr class="input">
    <td><label for="name_suffix" >&nbsp;&nbsp;Suffix&nbsp;</label></td>
    <td><select name="name_suffix" id="name_suffix" >
<option value="" selected="true">Choose...</option>
<!suffixOption>
</select>
</td>
</tr>
<tr class="input">
    <td><label for="address" >&nbsp;&nbsp;Address&nbsp;</label></td>
    <td><input type="text" name="address" id="address" size="50" maxlength="150"  value="<!address>"/></td>
</tr>
<tr class="input">

    <td><label for="address2" >&nbsp;&nbsp;Address 2&nbsp;</label></td>
    <td><input type="text" name="address2" id="address2" size="50" maxlength="150"  value="<!address2>"/></td>
</tr>
<tr class="input">
    <td><label for="city" >&nbsp;&nbsp;City&nbsp;</label></td>
    <td><input type="text" name="city" id="city" size="50" maxlength="150"  value="<!city>"/></td>
</tr>
<tr class="input">
    <td><label for="country" >&nbsp;&nbsp;Country&nbsp;</label></td>

    <td><select id="country" onchange="toggleState();" name="clinic_country" tabindex="5" >
		
									<!patient_country_options>
									  </select>

</td>
</tr>
<tr class="input">
    <td><label for="state" >&nbsp;&nbsp;State / Province&nbsp;</label></td>

    <td><select name="state" id="state" >

<!stateOption>
</select>

</td>
</tr>
<tr class="input">
    <td><label for="zip" >&nbsp;&nbsp;Zip Code&nbsp;</label></td>
    <td><input type="text" name="zip" id="zip" size="10" maxlength="7" value="<!zip>"/></td>
</tr>
<tr class="input">
    <td><label for="phone1" >&nbsp;&nbsp;1st Phone&nbsp;</label></td>
    <td><input type="text" name="phone1" id="phone1" size="20" maxlength="20" value="<!phone1>"/></td>
</tr>
<tr class="input">

    <td><label for="phone2" >&nbsp;&nbsp;2nd Phone&nbsp;</label></td>
    <td><input type="text" name="phone2" id="phone2" size="20" maxlength="20"  value="<!phone2>"/></td>
</tr>
<!--<tr>
    <td colspan="2"><h3>Patient Permissions</h3></th>
</tr>-->
<tr>
    <td colspan="2" width="100%" >
        <h3>
        <div style="width:440px;float:left">Patient Permissions</div>
        <div style="width:280px;float:right;display:<!display>">Programs</div>
        </h3>
    </td>
</tr>
<tr class="input" >
    <td colspan="2" width="100%">
        <div style="">
                <label for="status" >&nbsp;&nbsp;Status&nbsp;</label>
                <span style="padding-left:107px;" >
                    <select name="status" id="status" >
                    <!statusOption>
                </select>


<!--<div  class="enable-con-inner" style="width: 219px; float: right; color: #0069A0; background: url(/images/bg-gray-heading.gif) top left repeat-x; border-top: #bbb solid 1px; border-bottom: #bbb solid 1px; padding: 2px 8px; margin: 0px 5px;"> <input name="ehsEnable" id="ehsEnable" value="1"  <!ehsEnable> type="checkbox" > <b>E-Health Service</b>&nbsp;&nbsp;<img height="17" src="images/img-question.gif" width="19" name="By checking the box, this patient or client will be required to sign-up for your business E-Health Service."   />-->
</div>
                              
        </div>
        <!--<div style="width:283px;float:right; display:<!display>">
            <table border="0" cellpadding="0" cellspacing="0" >
                <tr><td><input type="checkbox" name="program" value="1" <!checked> /></td>
                <td valign="middle" >e-Rehab Program</td></tr>
            </table>
        </div>-->
    </td>
</tr>
<!--<tr class="input" >
    <td><label for="status" >&nbsp;&nbsp;Status&nbsp;</label></td>
    <td>
        <select name="status" id="status" >
                <!statusOption>
        </select>
    </td>
</tr>-->
<tr>
    <td colspan="2">&nbsp;</td>
</tr>
<tr>
    <td colspan="2" align="center">
        <input type="submit" name="submitted_save" value="Save Health Record"/>
    </td>
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
