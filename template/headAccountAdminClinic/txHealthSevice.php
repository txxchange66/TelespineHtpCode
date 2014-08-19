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

/*jQuery(function($){
$('#healthEnable').click(function(){
    if($('#healthEnable').is(':checked'))
    {
        $('#teleconferencedisable').removeAttr('disabled');
    }
    else
    {
        $('#teleconferencedisable').attr('disabled', 'disabled');
    }
});
})*/
</script>

The E-Health Service gives health, wellness, and fitness professionals the ability to launch new subscription-based revenue streams online and better meet consumer demand for more convenient health and wellness services. With E-Health Service, you can create a new online health service for your patients or clients and deliver care to them via their patient portal. Determine the key features, benefits, and expertise you'll provide, then decide how much you want to charge for the service. It's that simple. To learn more, please click on the FAQ above.<br>&nbsp;
<div class=" enable-con">
<div class="enable-con-inner">
    
    
    
      
<p style="width:46%; float:left;"><input name="healthEnable" id="healthEnable" value="1" type="checkbox" onclick="javascript:toggleHealthCheck();"  <!health>/> Turn on E-Health Service <img height="17" src="images/img-question.gif" width="19" name="By turning on the E-Health Service, your patients will be required to pay you for access to their patient portal."   /><br><a href="#" onclick="action_handler(this.checked);" style="margin-left: 27px;"><u>Terms of Service</u></a> </p>
  
<p>
    <input name="autoscheduleEnable" id="autoscheduleEnable" value="1" type="checkbox" <!autoschedule> <!paymentTypeDisable> /> Turn on automatic scheduler<img height="17" src="images/img-question.gif" width="19" name="By turning on the automatic scheduler, your patients will get every week content set by provider."   />
</p>

<h4>Service Details</h4>
<table cellpadding="4" cellspacing="0" style="width: 100%" class="service-detail" border="0">
<tbody id="HealthFeatures">
<tr>
  	<td><strong>*Service Name</strong> (Max. 40 char.) <img height="17" src="images/img-question.gif" width="19" name="Customize the name of your health service so it's appropriate for your practice." /> </td>
  	<td >&nbsp;</td>
  	

<td><div style="height:4px;"></div><table  cellpadding="0" cellspacing="0"><tr><td class="monthly" style="padding-top:2px;"><input type="radio" name="paymentType" id="paymentType" value="0" onclick="javascript:paymentTypeCheck(this.value);" <!monthlyCheck> <!paymentTypeDisable>></td><td><strong >*Monthly Price</strong></td></tr></table></td>

</tr>
<tr>
	<td><input name="subs_title" id="subs_title" type="text" value="<!subs_title>" maxlength="40" <!readonly> /></td>
	<td>&nbsp;</td>
	<td class="short" style="padding-left:10px;"> US $&nbsp;&nbsp; <input name="subs_price" id="subs_price" type="text" value="<!subs_price>" maxlength="6" <!subpricereadonly>  /></td>


</tr>
<tr>
  	<td> <strong>Website URL</strong> <img height="17" src="images/img-question.gif" width="19" name="Copy and paste this link into your website so your patients/clients will be taken directly to your EHS sign-up page. Please note: this link will not work unless you turn on the E-Health Service feature." /></td>
  	<td>&nbsp;</td>
        <td><div style="height:5px;"></div><table  cellpadding="0" cellspacing="0"><tr><td class="monthly" style="padding-top:2px;"><input type="radio" name="paymentType" id="paymentType1" value="1" onclick="javascript:paymentTypeCheck(this.value);" <!oneTimeCheck> <!paymentTypeDisable>></td><td><strong >*One Time Price</strong></td></tr></table></td>
  	

</tr>




<tr>
  	<td ><div style="width:269px;word-wrap: break-word;"> <!clinicURL></div></td>
  	<td>&nbsp;</td>
  	<td class="short sel" style="padding-left:10px;" valign="top">US $&nbsp;&nbsp; <input name="onetime_price" id="onetime_price" type="text" value="<!onetime_price>" maxlength="6"  <!onetimepricereadonly>  />
<select id="ehsTimePeriod" name="ehsTimePeriod" tabindex="5" <!Durationreadonly>>
        <!durationOptions>
</select>
 
</td></tr>
<tr>
	<td><strong>Key Features</strong> (Max. 50 char.) <img height="17" src="images/img-question.gif" width="19" name="List the key features of your health service that you want your patients to know about."   /></td>
	<td>&nbsp;</td>
	<td style="padding-left:10px;"><strong>*Service Description </strong>(Max. 750 char.) <img height="17" src="images/img-question.gif" width="19" name="Provide a description of your health service. This will help your patients understand what they are paying for."  /></td>
</tr>
<tr>
	<td><input name="subs_feature1" id="subs_feature1" type="text" value="<!subs_feature1>" maxlength="50"  <!readonly> /></td>
	<td>&nbsp;</td>
	<td rowspan="8" valign="top" style="padding-left:10px;"><div id="subdescription" <!disabletine> ><textarea name="subs_description"  id="subs_description"   cols="20" rows="2" ><!subs_description></textarea><div style="float: right;">Characters: <input id="deslen" name="deslen" readonly  type="text" style="width: 50px;background: #F5F5F5;border: 0px;" ></div></div></td>
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
	<td ><a href="javascript:void(0);" id="link_more" onclick="javasvcript:return addFeatures();" style="display:<!display>; float:left;" >Add Another </a> <img id="link_more_help" style="display:<!display>;" height="17" src="images/img-question.gif" width="19" name="List the key features of your health service that you want your patients to know about." /></td>
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
       GB_showCenter('E-Health Service- Terms of Service', url, 540,700 );
    //window.location = url;
}

//-->
</script>
