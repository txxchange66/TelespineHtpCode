<script language='javascript' type='text/javascript' src='js/jquery.js'></script>
<input type="hidden"  name="showsoap" id="showsoap" value="<!shopshow>">
<script language="JavaScript" src="js/show_menu_head.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>
<script language="JavaScript" src="js/countryState.js"></script>
                                                                   
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
<script language="JavaScript" src="js/validateform.js"></script>  
<script language="JavaScript">
<!--
window.formRules = new Array(
	new Rule("patient_first_name", "First name", true, "string|0,20"),
	new Rule("patient_last_name", "Last name", true, "string|0,50"),
	new Rule("patient_email", "email address", true, "email"),
	new Rule("patient_cemail", "confirm email address", true, "email"),
	new Rule("patient_address", "address", false, "string|0,50"),
	new Rule("patient_address2", "address line 2", false, "string|0,50"),	
	new Rule("patient_phone1", "1st phone number", false, "usphone"),
	new Rule("patient_phone2", "2nd phone number", false, "usphone"),
	new Rule("patient_city", "city", false, "string|0,50"),
	new Rule("patient_state", "State", false, "string|0,2"),
	new Rule("patient_zip", "Zip Code", false, "zipcode")	
							);
// -->
</script>
<script>
function action_handler(value, userId,subs_status,subscrp_id, clinicId, ehsAction,paymentType) {

      
                if(value){document.getElementById('ehsEnable').checked=false;}else document.getElementById('ehsEnable').checked=true;

                if(paymentType == '1') {

                        url = '/index.php?action=ehsonetimepayment&userId=' +userId+'&ehs= ' +ehs+'&clinicId= ' +clinicId;
                 GB_showCenter('E-Health Service Subscription Status', url, 100,550 );
              
        } else {
                
                if(value == true && userId!= '') {
                        var ehs = 1;
                         url = '/index.php?action=eHealthServiceProvider&userId=' +userId+'&ehs= ' +ehs+'&clinicId= ' +clinicId+'&ehsAction= ' +ehsAction;
                         GB_showCenter('E-Health Service Subscription Status', url, 100,550 );

                }
                else if(value == false && subs_status == '0') {
                       var ehs = 0;
                       url = '/index.php?action=eHealthServiceProvider&userId=' +userId+'&ehs= ' +ehs+'&clinicId= ' +clinicId+'&ehsAction= ' +ehsAction;
                       GB_showCenter('E-Health Service Subscription Status', url, 100,550 );
                        
               } else {
                        url = '/index.php?action=patient_ehs_unsubscribe&subscrp_id=' +subscrp_id+'&userId= ' +userId+'&clinicId= ' +clinicId+'&ehsAction= ' +ehsAction;
                        GB_showCenter('E-Health Service Subscription Status', url, 170,500 );

                }

        }
 
}
<!--
function view_intake_paper(){
    GB_showCenter('View Intake Paperwork', '/index.php?action=view_intake_paperwork&patient_id=<!patient_id>', 720,960 );
    
}
function  view__brief_intake_paper(){
    GB_showCenter('View Brief Intake Paperwork', '/index.php?action=view_brief_intake_paperwork&patient_id=<!patient_id>', 570,960 );
    
}
function reloadlab(patient_id){
	 if(patient_id != null && patient_id != "" ){
		 $.post('index.php?action=labreportlist',{patient_id:patient_id}, function(data,status){
			   
         
           if( status == "success" ){
        	   data = data.replace(/13px/gi, "5px");
         	  document.getElementById("labresults").innerHTML = data;
              
                 
             
           }
           else{
               alert("Ajax connection failed.");
           }    
           
              
       }
   )        
   
}
else{
   alert("Patient Id not Found.");
			
	}
}
function treatementprogram(patient_id){
    if(patient_id != null && patient_id != "" ){
        $.post('index.php?action=treatementprogramlist',{id:patient_id}, function(data,status){
              
        //alert(data);
          if( status == "success" ){
              data = data.replace(/13px/gi, "5px");
             // alert(data);
              //document.getElementById("treatementprogram").innerHTML = data;
             $('#treatementprogram').html(data);
                
            
          }
          else{
              alert("Ajax connection failed.");
          }    
          
             
      }
  )        
  
}
else{
  alert("Patient Id not Found.");
           
   }
}
function funSummaryservices(patient_id){
     if(patient_id != null && patient_id != "" ){
         $.post('index.php?action=providerServiceslist',{id:patient_id}, function(data,status){
               
         
           if( status == "success" ){
               data = data.replace(/13px/gi, "5px");
               document.getElementById("summaryservices").innerHTML = data;
               treatementprogram("<!patient_id>");
                 
             
           }
           else{
               alert("Ajax connection failed.");
               treatementprogram("<!patient_id>");
           }    
           
              
       }
   )        
   
}
else{
   alert("Patient Id not Found.");
            
    }
}

function login_detail(patient_id){
    if(patient_id != null && patient_id != "" ){
        
        $.post('index.php?action=Headmail_login_detail_patient',{patient_id:patient_id}, function(data,status){
                $content = document.getElementById("login_detail").innerHTML;    
                document.getElementById("login_detail").innerHTML = "<img src='images/ajax-loader.gif' />";
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
/*function assignintake(provider_id,patient_id){
    if(patient_id != null && patient_id != "" && provider_id!=null && provider_id!="" ){
        
        $.post('index.php?action=assignIntake',{patient_id:patient_id,provider_id:provider_id}, function(data,status){
                $content = document.getElementById("intakework").innerHTML;    
                document.getElementById("intakework").innerHTML = "<img src='images/ajax-loader.gif' />";
                if( status == "success" ){
                    if(/success/.test(data)){
                        //alert("Login info email successfully sent to Patient.");    
                        //showme("Login info email successfully sent to Patient.");
                        GB_showCenter('Intake Paperwork Assigned', '/template/alert.php?action=successintake', 100, 350 );
                    }
                    else if( /failed/.test(data) ){
                        //alert("E-mail delivery failed.");
                        //showme("E-mail delivery failed.");
                        GB_showCenter('Intake Paperwork Assigned', '/template/alert.php?action=fail', 100, 350 );
                    }    
                    else{
                        alert(data);
                    }
                }
                else{
                    alert("Ajax connection failed.");
                }    
                
                document.getElementById("intakework").innerHTML = '<b>Intake Paperwork</b>';   
                document.getElementById("intake_but").value= 'Assigned'; 
                document.getElementById("intake_but").disabled= 'true'; 
                  
            }
        )        
        
    }
    else{
        alert("Patient Id not Found.");
    }
    
}*/
function assignintake(provider_id,patient_id){
    if(patient_id != null && patient_id != "" && provider_id!=null && provider_id!="" ){
       
        $.post('index.php?action=assignIntake',{patient_id:patient_id,provider_id:provider_id}, function(data,status){
                //$content = document.getElementById("intakework").innerHTML;    
                //document.getElementById("intakework").innerHTML = "<img src='images/ajax-loader.gif' />";
                if( status == "success" ){
                    if(/success/.test(data)){
                        
                        GB_showCenter('Adult Intake Paperwork', '/template/alert.php?action=successintake', 100, 350 );
                          document.getElementById("intake_but").style.display= 'none';
	                 document.getElementById("unassign_intake_but").style.display= 'block';
   
                    }
                    else if( /failed/.test(data) ){
                        GB_showCenter('Adult Intake Paperwork', '/template/alert.php?action=fail', 100, 350 );
                    }  
                    else if( /brifeintakeassign/.test(data) ){
                        GB_showCenter('Adult Intake Paperwork', '/template/alert.php?action=brifeintakeassign', 130, 450 );
                    }      
                    else{
                        alert(data);
                    }
                }
                else{
                    alert("Ajax connection failed.");
                } 
                 
                
                
                  
            }
        )        
        
    }
    else{
        alert("Patient Id not Found.");
    }
    
}

function unassignintake(provider_id,patient_id){
    if(patient_id != null && patient_id != "" && provider_id!=null && provider_id!="" ){
         var r=confirm("Do you want to unassign Adult Intake Paperwork?");
         if(r==true)
         {
            $.post('index.php?action=unassignIntake',{patient_id:patient_id,provider_id:provider_id}, function(data,status){
                    if( status == "success" ){
                        if(/success/.test(data)){
                            GB_showCenter('Adult Intake Paperwork', '/template/alert.php?action=successunassignintake', 100, 350 );
                        }
                        else if( /failed/.test(data) ){
                            GB_showCenter('Adult Intake Paperwork', '/template/alert.php?action=fail', 100, 350 );
                        }    
                        else{
                            alert(data);
                        }
                    }
                    else{
                        alert("Ajax connection failed.");
                    } 
                     $("#unassign_intake_but").hide();  
                     $("#intake_but").show();

                     
                }
            )        
        }
        else
        {
            return false;
        }
    }
    else{
        alert("Patient Id not Found.");
    }
    
}

function assignbriefintake(provider_id,patient_id){
    if(patient_id != null && patient_id != "" && provider_id!=null && provider_id!="" ){
        
        $.post('index.php?action=assignbriefIntake',{patient_id:patient_id,provider_id:provider_id}, function(data,status){
                //$content = document.getElementById("intakework").innerHTML;    
                //document.getElementById("intakework").innerHTML = "<img src='images/ajax-loader.gif' />";
                //alert(data);
                if( status == "success" ){
                    if(/success/.test(data)){
                        GB_showCenter('Brief Intake Paperwork', '/template/alert.php?action=successbriefintake', 100, 350 );
                        document.getElementById("brifeintake_but").style.display= 'none';
                document.getElementById("unassign_brifeintake_but").style.display= 'block';
   
  
                    }
                    else if( /failed/.test(data) ){
                        GB_showCenter('Brief Intake Paperwork', '/template/alert.php?action=fail', 100, 350 );
                    }    
   	                else if(/intakeassign/.test(data)){
        		GB_showCenter('Brief Intake Paperwork', '/template/alert.php?action=intakeassign', 130, 450 );
                    }else{
                        alert(data);
                    }
                }
                else{
                    alert("Ajax connection failed.");
                }    
                
                //document.getElementById("intakework").innerHTML = '<b>Intake Paperwork</b>';   
                //document.getElementById("brifeintake_but").value= 'Unssign'; 
                //document.getElementById("brifeintake_but").disabled= 'true'; 
                
                  
            }
        )        
        
    }
    else{
        alert("Patient Id not Found.");
    }
    
}

function unassignbriefintake(provider_id,patient_id){
    if(patient_id != null && patient_id != "" && provider_id!=null && provider_id!="" ){
         var r=confirm("Do you want to unassign Brief Intake Paperwork?");
         if(r==true)
         {
            $.post('index.php?action=unassignbriefIntake',{patient_id:patient_id,provider_id:provider_id}, function(data,status){
                    if( status == "success" ){
                        if(/success/.test(data)){
                            GB_showCenter('Brief Intake Paperwork', '/template/alert.php?action=successunassignbriefintake', 100, 350 );
                        }
                        else if( /failed/.test(data) ){
                            GB_showCenter('Brief Intake Paperwork', '/template/alert.php?action=fail', 100, 350 );
                        }    
                        else{
                            alert(data);
                        }
                    }
                    else{
                        alert("Ajax connection failed.");
                    } 
                     $("#unassign_brifeintake_but").hide();  
                     $("#brifeintake_but").show();  
                    
                     
                }
            )        
        }
        else
        {
            return false;
        }
    }
    else{
        alert("Patient Id not Found.");
    }
    
}



// -->

</script>    

<form name="clinicdetail" id="clinic_detail" action="index.php?action=HeadAdminEditPatients&patient_id=<!patient_id>&clinic_id=<!clinic_id>" method="post" onload="this.patient_title.focus();">
<div id="container_custom"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2"><div id="header">
          <!header>
        </div></td>
    </tr>
    <tr>
      <td width="16%" align="left" valign="top"><div id="sidebar">
          <!sidebar>
        </div></td>   
      <td width="84%"  align="left" valign="top"><div id="mainContent-sec">
	  <!--  MAIN TABLE STARTS -->
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top" align="left">
			  <!--  INNER MAIN TABLE STARTS (5) ROWS STARTS  -->      
			  <table width="100%" border="0" style="vertical-align:middle; width:700px;margin-top:38px;padding-bottom:24px;">
                  <tr>
                    <td>
                        <table style="vertical-align:middle;width:700px;">
                            <tr>
                            <td style="width:400px;"><div id="breadcrumbNav"><a href="index.php" >HOME </a> / <a href="index.php?action=accountAdminClinicList" >CLINIC LIST</a> / <span class="highlight">EDIT PATIENT</span></div></td>
                            </tr>
                        </table>
                    </td>
                  </tr>
              </table>
              </td>
            </tr>
            <!--  1st ROW ENDS  -->
            <tr>
                <!-- <td class="adminSubHeading">Account Administration </td> -->
                <td>&nbsp;</td>
            </tr>
            <!--  2nd ROW ENDS  -->
            <tr>
                <td class="error">&nbsp;<!error></td>
            </tr>
				  <!--  3rd ROW ENDS  -->				  
                  <tr>
                    <td>
					<div >
                       <!tabNavigation>
                      </div>
                     </td>
                    </tr>  
                    <tr>
                        <td>
                            <div class="toptitle" style='padding-left:10px;' ><!operationTitle> Patient</div>
                        </td>
                    </tr>           
                    <tr>
                        <td>
                            <div > 
					  <table width="100%" border="0" cellspacing="2" cellpadding="2" style="border:1px solid #CCCCCC;">

                            <tr>
                              <td colspan="2"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                              
								  <tr>
									<td><div align="right" onMouseOver="help_text(this, 'Enter the Referring Dr. name')"><strong>Referring Dr. 
										&nbsp;</strong></div></td>
									<td><input type="text" name="refering_physician" id="refering_physician" maxlength="20" onMouseOver="help_text(this, 'Enter the Referring Dr. name')" value="<!refering_physician>" /></td>
									<td>&nbsp;</td>
									<td width="13%"><div align="right"  onMouseOver="help_text(this, 'Enter patients address')" ><strong>Address &nbsp; </strong></div></td>
                                    <td width="41%"><input tabindex="9" maxlength="50" type="text"  onMouseOver="help_text(this, 'Enter patients address')" name="patient_address" value="<!patient_address>"></td>
                                  
								  </tr>
                                  <tr>
                                    <td width="22%"><div align="right" onMouseOver="help_text(this, 'Select patient title')"><strong>Title  &nbsp; </strong></div></td>
                                    <td width="22%"><select name="patient_title" tabindex="1" onMouseOver="help_text(this, 'Select patient title')">
                                        <!patient_title_options>
                                      </select></td>
                                    <td width="2%">&nbsp;</td>
                                    <td></td>
                                    <td><input tabindex="10" maxlength="50" type="text"  onMouseOver="help_text(this, 'Enter address for line2')" name="patient_address2" value="<!patient_address2>"/></td>
                                 
                                    </tr>
                                  <tr>
                                    <td><div align="right"  onMouseOver="help_text(this, 'Enter patient first name')"><strong>* First Name  &nbsp; </strong></div></td>
                                    <td><input tabindex="2" maxlength="20" type="text" name="patient_first_name"  onMouseOver="help_text(this, 'Enter patient first name')" value='<!patient_first_name>'></td>
                                    <td>&nbsp;</td>
                                    <td><div align="right"  onMouseOver="help_text(this, 'Enter city for patient')" ><strong>City &nbsp; </strong></div></td>
                                    <td><input tabindex="11" maxlength="20" type="text"  onMouseOver="help_text(this, 'Enter city for patient')" name="patient_city" value="<!patient_city>"></td>
                                 
                                     </tr>
                                  <tr>
                                    <td><div align="right"  onMouseOver="help_text(this, 'Enter patient last name')" ><strong>* Last Name &nbsp; </strong></div></td>
                                    <td><input tabindex="3" maxlength="20" type="text"  onMouseOver="help_text(this, 'Enter patient last name')" name="patient_last_name" value='<!patient_last_name>'></td>
                                    <td>&nbsp;</td>
                                     <td><div align="right">
                                                      <label for="state" onMouseOver="help_text(this, 'Enter the User\'s country')")>&nbsp;Country &nbsp;</label>
                                                    </div></td>
                                                  <td><select id="country" onchange="toggleState();" name="clinic_country" tabindex="5" >
		
									<!patient_country_options>
									  </select>
                                                  </td>
                                     </tr>
                                  <tr>
                                    <td><div align="right" onMouseOver="help_text(this, 'Select Patient suffix')"><strong>Suffix &nbsp;</strong> </div></td>
                                    <td><select name="patient_suffix" tabindex="4" onMouseOver="help_text(this, 'Select Patient suffix')">
                                        <!patient_suffix_options>
                                      </select>                                    </td>
                                    <td>&nbsp;</td>
                                    <td><div align="right" onMouseOver="help_text(this, 'Select state from list')"><strong>State / Province &nbsp; </strong></div></td>
                                    <td><select id="state" name="patient_state" tabindex="12" onMouseOver="help_text(this, 'Select state from list')">
                                        <!patient_state_options>
                                      </select></td>
                                  </tr>
                                  <tr>
                                    <td><div align="right" onMouseOver="help_text(this, 'Enter email address of patient')" ><strong>* Email &nbsp;</strong> </div></td>
                                    <td><input tabindex="5" maxlength="50" type="text" name="patient_email" onMouseOver="help_text(this, 'Enter email address of patient')" value="<!patient_email>" /></td>
                                    <td>&nbsp;</td>
                                    <td><div align="right"  onMouseOver="help_text(this, 'Enter zip code in 5 to 6 alphanumeric character')" ><strong>Zip Code &nbsp;</strong> </div></td>
                                    <td><input tabindex="13" maxlength="7" type="text" name="patient_zip"  onMouseOver="help_text(this, 'Enter zip code in 6 alphanumeric character')" value="<!patient_zip>" /></td>
                                  </tr>
                                  <tr>
                                    <td><!newPasswordLabel></td>
                                    <td><!newPasswordField></td>
                                    <td>&nbsp;</td>
                                    <td><div align="right"  onMouseOver="help_text(this, 'Enter your first phone number')" ><strong>Phone 1&nbsp;</strong> </div></td>
                                    <td><input tabindex="14" type="text" name="patient_phone1"  onMouseOver="help_text(this, 'Enter your first phone number')" value="<!patient_phone1>" /></td>
                                  </tr>
                                  <tr>
                                    <td><!confirmPasswordTextField></td>
                                    <td><!confirmPasswordField></td>
                                    <td>&nbsp;</td>
                                    <td><div align="right" onMouseOver="help_text(this, 'Enter your second phone number')"><strong>Phone 2&nbsp;</strong> </div></td>
                                    <td><input tabindex="15" type="text" name="patient_phone2" onMouseOver="help_text(this, 'Enter your second phone number')" value="<!patient_phone2>" /></td>
                                  </tr>
								 <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><div align="right"
onMouseOver="help_text(this, 'Select your status')"><strong>Status
&nbsp;</strong> </div></td>
  <td><select name="patient_status"
tabindex="16" onMouseOver="help_text(this, 'Select your status')" >
                                        <!patient_status_options>
                                      </select></td>
                                    <!--<td>&nbsp;</td>
                                    <td colspan="5"><input tabindex="16"
type="checkbox" name="mass_message_access"   value="<!
mass_message_access>" <!mass_message_checked> <!
mass_message_disabled> />&nbsp;Unsubscribed to Marketing Messages</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>-->
                                  </tr>

                               
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>

                                  <tr>
                                    <td>&nbsp;</td>
                                    <td colspan="3"><input tabindex="16" type="checkbox" name="mass_message_access"  class="checkBoxAlign" value="1" <!mass_message_checked> <!mass_message_disabled> /><strong>Unsubscribed to Marketing Messages</strong></td>

                                    <td style="padding-left:78px">

<input tabindex="19" type="submit" name="Submit" value=" <!buttonName> " />

									</td>
                                  </tr>
                                  <tr>
                                    <td
style="padding-left:32px;">&nbsp;</td>
                                    <td colspan="3"></td>
                                    <td  align="left"
valign="top">


                                    </td>
                                  </tr>

                                  <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>

                                  </tr>
                                  <tr>
                                    <td colspan="5" style="border-top:1px 
solid #cbcbcb;"> <div style="line-height:35px; padding-left:15px;"><h3 style="color:#005b85;">Additional 
Information</h3></div>


                                     <table cellpadding="5" cellspacing="0" style="width: 100%">
          <tr>
               


                                   
           <td style="width:320px; padding-left:15px;" valign="top">
        
                <div id="showehs" style="<!SHOWEHS>">                
                <table cellpadding="5" cellspacing="0" style="width: 100%">
                    <tr style="background:url('../images/img-table-head-bg-rep.jpg') top left repeat-x;" >
                     <td style="border-top:1px solid #cbcbcb; border-bottom:1px 
        solid #cbcbcb; color:#005b85; font-weight:bold; display:<!corporate>" ><div  class="enable-con-inner" style="padding:0px;">
                      <input type="checkbox" name="ehsEnable" id="ehsEnable"  style="margin:0px 5px 7px !important;position:relative;bottom:-2px;" <!ehsEnable> onclick="action_handler(this.checked, '<!patient_id>', '<!subs_status>', '<!subscrp_id>', '<!clinic_id>','<!ehsAction>','<!paymentType>');">&nbsp;&nbsp;<strong><span id="eh" style="margin:0px 5px 7px !important;position:relative;bottom:1px;">E-Health Service</span> &nbsp;</strong>&nbsp;&nbsp;<img height="17" src="images/img-question.gif" width="19" name="By checking the box, this patient or client will be required to sign-up for your business' E-Health Service."   />
                      <input  type="hidden" name="ehsDisable"  id="ehsDisable" value="<!ehsDisable>" /></div></td>
                    </tr>
           </table>
        </div>

 <div style="clear:both"></div>
           <br>  
           <table cellpadding="5" cellspacing="0" style="width: 100%">

             

            <tr 
style="background:url('../images/img-table-head-bg-rep.jpg') top left 
repeat-x;" >
             <td style="border-top:1px solid #cbcbcb; border-bottom:1px 
solid #cbcbcb; color:#005b85; font-weight:bold;">
             Results & Documents</td>
             <td align="right" style="border-top:1px solid #cbcbcb; 
border-bottom:1px solid #cbcbcb;"><!LabResult></td>
            </tr>
            
           </table>
           <div name="labresults" id="labresults" ><table cellpadding="5" cellspacing="0" style="width:325px;"> 
            <!labresult_display></table>
           </div>
           <div style="clear:both"></div>
           <br>           
            <table cellpadding="5" cellspacing="0" style="width: 100%">
                        <tr 
            style="background:url('../images/img-table-head-bg-rep.jpg') top left 
            repeat-x;" >
                         <td style="border-top:1px solid #cbcbcb; border-bottom:1px 
            solid #cbcbcb; color:#005b85; font-weight:bold;">
                          Services Summary</td>
                         <td align="right" style="border-top:1px solid #cbcbcb; 
            border-bottom:1px solid #cbcbcb;">&nbsp;</td>
                        </tr>
                        
                       </table>
                       <div name="summaryservices" id="summaryservices" >&nbsp;</div>           
           </td>
           <td style="width:100px;">&nbsp;</td>
           <td valign="top">
           <table cellpadding="5" cellspacing="0" style="width: 100%">
           
            <!intakeAssign>
            <!briefintakeAssign>
<tr>

             <td><a href="javascript:void(0);"
tabindex="17" onclick="GB_showCenter('Change Reminders',
'/index.php?action=editReminder&patient_id=<!editReminderlink>', 550,
800 );"  ><input type="button" value="Change" style="width:80px"></a></td><td><strong> Reminders</strong></td>
            </tr>
            <tr>
             
             <td> <a href="javascript:void(0);" 
tabindex="18" onclick="GB_showCenter('Associate Providers',
'/index.php?action=associateTherapist&patient_id=<!editReminderlink>&actionActivateFrom=accountAdmin', 550, 800 );"
><input type="button" name="EditTherapist" value="Edit" style="width:80px"></a></td><td><strong>Associated Providers</strong></td>
            </tr>
            <tr>
            
             <td><a href="javascript:void(0);" 
tabindex="19" onclick="GB_showCenter('Login History',
'/index.php?action=loginHistoryAccountAdmin&patient_id=<!editReminderlink>', 400, 490 );"  ><input type="button" name="ViewHistry" value="View" style="width:80px" ></a></td> <td><strong> Login
History</strong></td>
            </tr>
            <tr>
            
             <td><span id='login_detail'><a href="javascript:void(0);"
onclick="login_detail('<!editReminderlink>');" ><input type="button" name="Resend" value="Resend" style="width:80px"></a></span>
</td> <td><strong> Login
Info</strong></td>
            </tr>
              </table>
            <table width="100%"><tr><td><div id="treatementprogram" name="treatementprogram"></div></td></tr></table>
            </td>
          </tr>
         </table>
</td>
                                  </tr>
                                </table>

</td>
    </tr>
    <tr>
      <td colspan="2"><div id="footer">
          <!footer>
        </div></td>
    </tr>
  </table>
  <input type="hidden" name="option" value="update" />
  <input type="hidden" name="patient_id" value="<!patient_id>" />  
  <input type="hidden" name="clinic_id" value="<!clinic_id>" />  
  </div> 
    </td></tr></table></div> 

</div><!-- div ( container ) Ends -->
  </form>

 
<script>
<!--
	window.load = document.clinicdetail.patient_title.focus();
-->
</script>
<script language="JavaScript1.2">
funSummaryservices("<!patient_id>");
mmLoadMenus("<!showmenu>");
</script>>
