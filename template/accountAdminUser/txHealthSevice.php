<input type="hidden"  name="showsoap" id="showsoap" value="<!shopshow>">
    <!-- include the Tools -->
<script src="js/jquery.js"></script>
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
        z-index:10000;
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
    
    
 /*   $("img[title]").tooltip({

       // var getDim = $()
        
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
        offset: [-5, $(this).offset().top]
    });  */
});
</script>
The E-Health Service gives health, wellness, and fitness professionals the ability to launch new subscription-based revenue streams online and better meet consumer demand for more convenient health and wellness services. With E-Health Service, you can create a new online health service for your patients or clients and deliver care to them via their patient portal. Determine the key features, benefits, and expertise you'll provide, then decide how much you want to charge for the service. It's that simple. To learn more, please click on the FAQ above.<br>&nbsp;
<div class=" enable-con">
<div class="enable-con-inner">
<p><input name="healthEnable" id="healthEnable" value="1" type="checkbox" onclick="javascript:toggleHealthCheck();"  <!health>/> Turn on E-Health Service <img height="17" src="images/img-question.gif" width="19" name="By turning on the E-Health Service, your patients will be required to pay you for access to their patient portal." />&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="action_handler(this.checked);" ><u>Terms of Service</u></a> </p>
<h4>Service Details</h4>
<table cellpadding="5" cellspacing="0" style="width: 100%" class="service-detail">
<tbody id="HealthFeatures">
<tr>
  	<td><strong>*Service Name</strong> (Max. 40 char.) <img height="17" width="19" src="images/img-question.gif" name="Customize the name of your health service so it's appropriate for your practice." /> </td>
  	<td>&nbsp;</td>
  	<td><strong>*Monthly Price</strong></td>
</tr>
<tr>
	<td><input name="subs_title" id="subs_title" type="text" value="<!subs_title>" maxlength="40" <!readonly> /></td>
	<td>&nbsp;</td>
	<td class="short">US $&nbsp;&nbsp; <input name="subs_price" id="subs_price" type="text" value="<!subs_price>" maxlength="6" <!readonly>  /></td>
</tr>
<tr>
	<td><strong>Key Features</strong> (Max. 50 char.) <img height="17" src="images/img-question.gif" width="19" name="List the key features of your health service that you want your patients to know about."   /></td>
	<td>&nbsp;</td>
	<td><strong>*Service Description </strong>(Max. 500 char.) <img height="17" src="images/img-question.gif" width="19" name="Provide a description of your health service. This will help your patients understand what they are paying for." /></td>
</tr>
<tr>
	<td><input name="subs_feature1" id="subs_feature1" type="text" value="<!subs_feature1>" maxlength="50"  <!readonly> /></td>
	<td>&nbsp;</td>
	<td rowspan="8" valign="top"><textarea name="subs_description" id="subs_description"  <!readonly> cols="20" rows="2" onkeyup="checkDescriptContent();" ><!subs_description></textarea></td>
</tr>
<tr>
	<td><input name="subs_feature2" type="text" maxlength="50" id="subs_feature2" value="<!subs_feature2>" <!readonly> /></td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td><input name="subs_feature3" type="text" maxlength="50" id="subs_feature3" value="<!subs_feature3>" <!readonly> /></td>
	<td>&nbsp;</td>
</tr>
<!editSubsFeature>
<tr id="HealthFeaturesAddLink">
	<td><a href="javascript:void(0);" id="link_more" onclick="javasvcript:return addFeatures();" style="display:<!display>; float:left;" >Add Another </a> <img id="link_more_help" style="display:<!display>;" height="17" src="images/img-question.gif" width="19" name="List the key features of your health service that you want your patients to know about." /></td>
	<td>&nbsp;</td>
</tr>
</tbody>                            
</table>
</div>
</div>
<script>
<!--
function action_handler(value){
    
   
    if(value==true)
                var status=1;
            else if(value==false)
                var status=2;
    if( status == ''){
        return;
    }
    url = '/index.php?action=termsOfServiceForEhealth';
       GB_showCenter('E-Health Service � Terms of Service', url, 540,700 );
    //window.location = url;
}

//-->
</script>