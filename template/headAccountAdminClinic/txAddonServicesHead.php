<script type="text/javascript">  
var GB_ROOT_DIR = "js/greybox/";
</script>
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
<script>
</script>
<input type="hidden"  name="showsoap" id="showsoap" value="<!shopshow>">
<script language="JavaScript" src="js/show_menu_head.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>

<!-- TinyMCE -->
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">

	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,bullist,numlist,|,link,unlink",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",
		setup : function (ed) {
			ed.onKeyUp.add(
			function (ed, evt) {
			document.addon.deslen.value = tinyMCE.activeEditor.getContent().replace(/<[^>]+>/g, '').length;
			}
			);
			},
		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

				
		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	
	
	});
	
		
	
</script>
<!-- /TinyMCE -->



<script>

function changetextbox(){
	if(document.getElementById("schedul").checked==true){
		document.getElementById("schedulUrl").disabled = false;
		 $('#schedulUrl').css({background:"#ffffff"});
	}else{
		document.getElementById("schedulUrl").disabled = true;
                document.getElementById("schedulUrl").value = '';
                $('#schedulUrl').css({background:"#ebebe4"});	
	}
}

function changewellnesstextbox(){
	if(document.getElementById("wellness_store_check").checked==true){
		document.getElementById("wellnessStoreUrl").disabled = false;
		$('#wellnessStoreUrl').css({background:"#ffffff"});
	}else{
		document.getElementById("wellnessStoreUrl").disabled = true;
                document.getElementById("wellnessStoreUrl").value = '';	
                $('#wellnessStoreUrl').css({background:"#ebebe4"});
	}
}
function show_toggle(value) {      
	
        document.getElementById("addonval").value = value;
        
	if (value=='settings') {
		if(document.getElementById("settings").style.display=='inline')
		{
			document.getElementById("settings").style.display='none';
			document.getElementById("mp").style.display='inline';
			document.getElementById("mn").style.display='none';
			
		} else {
                        document.getElementById("settings").style.display='inline';
                        document.getElementById("wellness_store").style.display='none';
                        document.getElementById("scheduling").style.display='none';
                        document.getElementById("healthservice").style.display='none';
                        document.getElementById("mp").style.display='none';
                        document.getElementById("mn").style.display='inline';
                        document.getElementById("wp").style.display='inline';
                        document.getElementById("wn").style.display='none';
                        document.getElementById("schedulingP").style.display='inline';
                        document.getElementById("schedulingN").style.display='none';
                        document.getElementById("healthPostive").style.display='inline';
                        document.getElementById("healthNegative").style.display='none';
                        document.getElementById("notifications").style.display='none';
                        document.getElementById("wellnessStore").style.display='none';
                        document.getElementById("widgetP").style.display='inline';
                        document.getElementById("widgetN").style.display='none';
                        document.getElementById("notificationsP").style.display='inline';
                        document.getElementById("notificationsN").style.display='none';
                        document.getElementById("wellness_storeP").style.display='inline';
                        document.getElementById("wellness_storeN").style.display='none';
                        document.getElementById("widget").style.display='none';

		}
	}

	if (value== 'wellnessStore') {
		if(document.getElementById("wellnessStore").style.display=='inline') {
			document.getElementById("wellnessStore").style.display='none';
			document.getElementById("wp").style.display='inline';
			document.getElementById("wn").style.display='none';

			
		} else {
			document.getElementById("wellnessStore").style.display='inline';
			document.getElementById("settings").style.display='none';
			document.getElementById("scheduling").style.display='none';
			document.getElementById("healthservice").style.display='none';
			document.getElementById("wp").style.display='none';
			document.getElementById("wn").style.display='inline';
			document.getElementById("mp").style.display='inline';
			document.getElementById("mn").style.display='none';
			document.getElementById("wellness_store").style.display='none';
			document.getElementById("schedulingP").style.display='inline';
			document.getElementById("schedulingN").style.display='none';
			document.getElementById("healthPostive").style.display='inline';
			document.getElementById("healthNegative").style.display='none';
             document.getElementById("notifications").style.display='none';
                        document.getElementById("widgetP").style.display='inline';
                        document.getElementById("widgetN").style.display='none';
                        document.getElementById("notificationsP").style.display='inline';
                        document.getElementById("notificationsN").style.display='none';
                        document.getElementById("wellness_storeP").style.display='inline';
                        document.getElementById("wellness_storeN").style.display='none';
                        document.getElementById("widget").style.display='none';
		}
	}

	
        if (value=='scheduling') {
		if(document.getElementById("scheduling").style.display=='inline')
		{
			document.getElementById("scheduling").style.display='none';
			document.getElementById("schedulingP").style.display='inline';
			document.getElementById("schedulingN").style.display='none';

			
		} else {
                        document.getElementById("scheduling").style.display='inline'
                        //document.getElementById("service").style.display='none'
                        document.getElementById("wellness_store").style.display='none';
                        document.getElementById("settings").style.display='none';
                        document.getElementById("wellnessStore").style.display='none';
                        document.getElementById("healthservice").style.display='none';
                        document.getElementById("schedulingP").style.display='none';
                        document.getElementById("schedulingN").style.display='inline';
                        //document.getElementById("sp").style.display='inline';
                        //document.getElementById("sn").style.display='none';
                        document.getElementById("mp").style.display='inline';
                        document.getElementById("mn").style.display='none';
                        document.getElementById("wp").style.display='inline';
                        document.getElementById("wn").style.display='none';
                        document.getElementById("healthPostive").style.display='inline';
                        document.getElementById("healthNegative").style.display='none';
                        document.getElementById("notifications").style.display='none';
                        document.getElementById("wellnessStore").style.display='none';
                        document.getElementById("wellness_storeP").style.display='inline';
                        document.getElementById("wellness_storeN").style.display='none';
                        document.getElementById("widgetP").style.display='inline';
                        document.getElementById("widgetN").style.display='none';
                        document.getElementById("notificationsP").style.display='inline';
                        document.getElementById("notificationsN").style.display='none';
                        document.getElementById("widget").style.display='none';
		}
	}
        if (value=='healthservice') {
		if(document.getElementById("healthservice").style.display=='inline')
		{
			document.getElementById("healthservice").style.display='none';
			document.getElementById("healthPostive").style.display='inline';
			document.getElementById("healthNegative").style.display='none';
			
		} else {
                        document.getElementById("healthservice").style.display='inline';
                        document.getElementById("settings").style.display='none';
                        document.getElementById("wellness_store").style.display='none';
                        document.getElementById("scheduling").style.display='none';
                        document.getElementById("mp").style.display='inline';
                        document.getElementById("mn").style.display='none';
                        document.getElementById("wp").style.display='inline';
                        document.getElementById("wn").style.display='none';
                        document.getElementById("schedulingP").style.display='inline';
                        document.getElementById("schedulingN").style.display='none';
                        document.getElementById("healthPostive").style.display='none';
                        document.getElementById("healthNegative").style.display='inline';
                        document.getElementById("notifications").style.display='none';
                        document.getElementById("wellnessStore").style.display='none';
                        document.getElementById("wellness_storeP").style.display='inline';
                        document.getElementById("wellness_storeN").style.display='none';
                        document.getElementById("widgetP").style.display='inline';
                        document.getElementById("widgetN").style.display='none';
                        document.getElementById("notificationsP").style.display='inline';
                        document.getElementById("notificationsN").style.display='none';
                        document.getElementById("widget").style.display='none';
		}
	}

        if (value=='wellness_store')
	{
		if(document.getElementById("wellness_store").style.display=='inline')
		{
			document.getElementById("wellness_store").style.display='none';
			document.getElementById("wellness_storeP").style.display='inline';
			document.getElementById("wellness_storeN").style.display='none';

			
		}else{
                        document.getElementById("wellness_store").style.display='inline';
                        document.getElementById("wellness_storeP").style.display='none';
                        document.getElementById("wellness_storeN").style.display='inline';
                        document.getElementById("settings").style.display='none';
                        document.getElementById("wellnessStore").style.display='none';
                        document.getElementById("healthservice").style.display='none';
                        document.getElementById("scheduling").style.display='none';
                        document.getElementById("schedulingP").style.display='inline';
                        document.getElementById("schedulingN").style.display='none';
                        document.getElementById("mp").style.display='inline';
                        document.getElementById("mn").style.display='none';
                        document.getElementById("wp").style.display='inline';
                        document.getElementById("wn").style.display='none';
                        document.getElementById("healthPostive").style.display='inline';
                        document.getElementById("healthNegative").style.display='none';
                        document.getElementById("notifications").style.display='none';
                        document.getElementById("widgetP").style.display='inline';
                        document.getElementById("widgetN").style.display='none';
                        document.getElementById("notificationsP").style.display='inline';
                        document.getElementById("notificationsN").style.display='none';
                        document.getElementById("widget").style.display='none';
                        
		}
	}

        if (value=='notifications')
	{
		if(document.getElementById("notifications").style.display=='inline')
		{
			document.getElementById("notifications").style.display='none';
			document.getElementById("notificationsP").style.display='inline';
			document.getElementById("notificationsN").style.display='none';
		}else{
                        document.getElementById("wellnessStore").style.display='none';
                        document.getElementById("widget").style.display='none';
                        document.getElementById("notifications").style.display='inline';
                        document.getElementById("notificationsP").style.display='none';
                        document.getElementById("notificationsN").style.display='inline';
                        document.getElementById("wellness_store").style.display='none';
                        document.getElementById("settings").style.display='none';			
                        document.getElementById("wellnessStore").style.display='none';
                        document.getElementById("healthservice").style.display='none';
                        document.getElementById("mp").style.display='inline';
                        document.getElementById("mn").style.display='none';
                        document.getElementById("wp").style.display='inline';
                        document.getElementById("wn").style.display='none';
                        document.getElementById("healthPostive").style.display='inline';
                        document.getElementById("healthNegative").style.display='none';
                        document.getElementById("widgetP").style.display='inline';
                        document.getElementById("widgetN").style.display='none';
                        document.getElementById("notificationsP").style.display='none';
                        document.getElementById("notificationsN").style.display='inline';
                        document.getElementById("wellness_storeP").style.display='inline';
                        document.getElementById("wellness_storeN").style.display='none';
                        document.getElementById("scheduling").style.display='none';
                        document.getElementById("schedulingP").style.display='inline';
                        document.getElementById("schedulingN").style.display='none';
		}
	}

        if (value=='widget')
	{
		if(document.getElementById("widget").style.display=='inline')
		{
			document.getElementById("widget").style.display='none';
			document.getElementById("widgetP").style.display='inline';
			document.getElementById("widgetN").style.display='none';
		}else{
                        document.getElementById("widget").style.display='inline';
                        document.getElementById("widgetP").style.display='none';
                        document.getElementById("widgetN").style.display='inline';
                        document.getElementById("wellness_store").style.display='none';
                        document.getElementById("settings").style.display='none';			
                        document.getElementById("wellnessStore").style.display='none';
                        document.getElementById("healthservice").style.display='none';
                        document.getElementById("mp").style.display='inline';
                        document.getElementById("mn").style.display='none';
                        document.getElementById("wp").style.display='inline';
                        document.getElementById("wn").style.display='none';
                        document.getElementById("healthPostive").style.display='inline';
                        document.getElementById("healthNegative").style.display='none';
                        document.getElementById("notifications").style.display='none';
                        document.getElementById("widgetP").style.display='none';
                        document.getElementById("widgetN").style.display='inline';
                        document.getElementById("notificationsP").style.display='inline';
                        document.getElementById("notificationsN").style.display='none';
                        document.getElementById("wellness_storeP").style.display='inline';
                        document.getElementById("wellness_storeN").style.display='none';
                        document.getElementById("scheduling").style.display='none';
                        document.getElementById("schedulingP").style.display='inline';
                        document.getElementById("schedulingN").style.display='none';
		}
	}

	
	
}
</script>
<script language="javascript" type="text/javascript">
    iCounterCheck=<!count>;  
    
    function toggleHealthCheck(){
             //alert(document.getElementById('healthEnable').checked);
             if(document.getElementById('healthEnable').checked) {
             $('#subs_description,#subs_title,#subs_feature1,#subs_feature2,#subs_feature3,#subs_feature4,#subs_feature5,#subs_feature6,#subs_feature7,#subs_price,#link_more,#link_more_help,,#ehsTimePeriod,#onetime_price,#paymentType,#paymentType1').css({background:"#ffffff"});

               //alert(document.getElementById('paymentType').checked);
                document.getElementById('subs_description').readOnly =false;
                document.getElementById('subs_title').readOnly =false;
                document.getElementById('subs_feature1').readOnly =false;
                document.getElementById('subs_feature2').readOnly =false;
                document.getElementById('subs_feature3').readOnly =false;
                if(document.getElementById('subs_feature4')!=null)
		        document.getElementById('subs_feature4').readOnly =false;
		if(document.getElementById('subs_feature5')!=null)
		        document.getElementById('subs_feature5').readOnly =false;
		if(document.getElementById('subs_feature6')!=null)
		        document.getElementById('subs_feature6').readOnly =false;
		if(document.getElementById('subs_feature7')!=null)
		document.getElementById('subs_feature7').readOnly =false;
		document.getElementById('subs_price').readOnly =false;
		document.getElementById('link_more').style.display="inline";
		document.getElementById('link_more_help').style.display="inline";
                document.getElementById('onetime_price').readOnly =false;
                //document.getElementById('ehsTimePeriod').readOnly =false;
                document.getElementById('paymentType').readOnly =false;
                document.getElementById('paymentType1').readOnly =false;
                 document.getElementById('ehsTimePeriod').disabled=false;
                document.getElementById('paymentType').disabled ="";
                document.getElementById('paymentType1').disabled ="";

                document.getElementById('autoscheduleEnable').readOnly = false;
                 document.getElementById('autoscheduleEnable').disabled = "";

                if(document.getElementById('paymentType').checked) {
                       
                        document.getElementById('onetime_price').disabled=true;
                        $('#onetime_price').css({background:"#ebebe4"});
                        document.getElementById('ehsTimePeriod').disabled=true;
                        document.getElementById('subs_price').readOnly=false;
                        document.getElementById('onetime_price').readOnly =false;
                        document.getElementById('ehsTimePeriod').readOnly =false;
                } else {
                        document.getElementById('onetime_price').disabled=false;
                        document.getElementById('ehsTimePeriod').disabled=false;
                        document.getElementById('subs_price').readOnly =true;
                        $('#subs_price').css({background:"#ebebe4"});
                }
                $("#subdescription").fadeTo(500, 1);     
             }  else{
             $('#subs_description,#subs_title,#subs_feature1,#subs_feature2,#subs_feature3,#subs_feature4,#subs_feature5,#subs_feature6,#subs_feature7,#subs_price,#onetime_price, #link_more,#link_more_help,#ehsTimePeriod,#paymentType,#paymentType').css({background:"#ebebe4"});
                document.getElementById('subs_description').readOnly =true; 
                document.getElementById('subs_title').readOnly =true;
                document.getElementById('subs_feature1').readOnly =true;
                document.getElementById('subs_feature2').readOnly =true;
                document.getElementById('subs_feature3').readOnly =true;
                 if(document.getElementById('subs_feature4')!=null)
                document.getElementById('subs_feature4').readOnly =true;
                 if(document.getElementById('subs_feature5')!=null)
                document.getElementById('subs_feature5').readOnly =true;
                 if(document.getElementById('subs_feature6')!=null)
                document.getElementById('subs_feature6').readOnly =true;
                 if(document.getElementById('subs_feature7')!=null)
                document.getElementById('subs_feature7').readOnly =true;
				document.getElementById('subs_price').readOnly =true;
                                document.getElementById('onetime_price').readOnly =true;
                document.getElementById('ehsTimePeriod').readOnly =true;
			   document.getElementById('link_more').style.display="none";
				document.getElementById('link_more_help').style.display="none";
                //document.getElementById('paymentType').readOnly =true;
                //document.getElementById('paymentType1').readOnly =true;
                document.getElementById('paymentType').disabled ="disabled";
                document.getElementById('paymentType1').disabled ="disabled";
                 document.getElementById('ehsTimePeriod').disabled ="disabled";
                 document.getElementById('autoscheduleEnable').readOnly = true;
                 document.getElementById('autoscheduleEnable').disabled = "disabled";
                 $("#subdescription").fadeTo(500, 0.430);
                 
             }
          
    
    }
    
    function paymentTypeCheck(val) {
       if(val == 1) {
        $('#subs_price').css({background:"#ebebe4"});
        $('#ehsTimePeriod, #onetime_price').css({background:"#ffffff"});
         document.getElementById('subs_price').value = '';
         document.getElementById('subs_price').readOnly =true;
         document.getElementById('ehsTimePeriod').disabled =false;
         document.getElementById('onetime_price').readOnly =false;
         document.getElementById('onetime_price').disabled =false;
               
       } 

        if(val == 0) {
                $('#subs_price').css({background:"#ffffff"});
                $('#ehsTimePeriod,#onetime_price').css({background:"#ebebe4"});
                document.getElementById('subs_price').readOnly =false;
                document.getElementById('ehsTimePeriod').disabled =true;
                document.getElementById('ehsTimePeriod').value = '';
                document.getElementById('onetime_price').readOnly = true;
                document.getElementById('onetime_price').value = '';
       } 

     }
 
    function checkDescriptContent(){
            maxLen=500;
            descLength=document.getElementById('subs_description').value.length;
            
            if(descLength>maxLen){
                alert("Limit availed");
                document.getElementById('subs_description').value = document.getElementById('subs_description').value.substring(0, maxLen);
                return false; 
            }
    }
    
    
    function addFeatures(){
        iCounterCheck++
        
        name=iCounterCheck;
        name='subs_feature'+name;
        //alert(name);
        if(iCounterCheck > 7){
         alert("There's currently a limit of 7 Key Features");
         document.getElementById('link_more').style.display="none";
         
         document.getElementById('link_more_help').style.display="none";
         return false;
            
        }
    
         tBody=document.getElementById('HealthFeatures');   
         linkTR=document.getElementById('HealthFeaturesAddLink');   
         var row = document.createElement("TR");
         var td1 = document.createElement("TD") ;
         td1.innerHTML='<input name="'+name+'" id="'+name+'" type="text" maxlength="50" />';
         var td2 = document.createElement("TD") ;
         td2.innerHTML="&nbsp;";         
         row.appendChild(td1);
         
         row.appendChild(td2);
         tBody.insertBefore(row,linkTR);       
         //tBody.appendChild(row);
         return true;
         
         
         
    }

</script>

<div class="center-align" >
  <div id="container">
    <div id="header">
      <!header>
    </div>
    <div id="sidebar">
          <!sidebar>
        </div>
    <!-- body part starts-->
    <div id="mainContent-sec">
      <table border="0" cellpadding="0" cellspacing="0" style="vertical-align:middle; width:100%; height:81px;">
        <tr>
          <td colspan="3" style=" width:400px; height:9px;"></td>
        </tr>
        <tr>
          <td width="151" style=" width:400px;"><div id="breadcrumbNav" style="padding-left:6px;margin-top:12px;"> <a href="index.php">HOME</a> / <span class="highlight"  >APP  & SERVICES STORE</span></div></td>
          <td width="138"></td>
          <td width="173" align="right" style="padding-right:75px; margin-top:11px;height:70px;">
		  &nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="top" style=" width:400px; font-size: large;font-weight: bold;padding-bottom: 7px; margin:0px; vertical-align:top;">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="top"  ><div>
            <!tabNavigation>
          </div></td>
        </tr>
        <tr>
          <td colspan="3" valign="top" class="topnavnew" >&nbsp;</td>
        </tr>
      </table>
      
      
 <!-- best practices table starts from here-->  
      <form name="addon" method="post" action="index.php" id="addon">
        <input type="hidden" name="addonval" id="addonval" >
        <table width="100%" border="0" cellspacing="1" cellpadding="2"  style="border:1px solid #CCCCCC;padding-top:15px;border-bottom: 0px;display:<!corporate>">
        <tr>
          <td align="right" valign="middle" style="height: 18px; width: 33px;"><a href="javascript:void(0);" onClick="javascript:show_toggle('healthservice');"><span id="healthPostive" style="font-weight:bold; font-size:14px; font-family:Arial, Helvetica, sans-serif; ">+</span><span id="healthNegative" style="font-size:22px;font-weight:bold; font-family:Arial, Helvetica, sans-serif;display:none;">-</span></a></td>
		  <td style="font-size:12px;font-weight:bold; height: 18px;">E-Health Service - <a href="javascript:void(0);" onclick="GB_showCenter('FAQ - E-Health Service', '/index.php?action=faq_HealthServies',570,970);">FAQ</a></td>
        </tr>
		<tr>
          <td style="width: 5%">&nbsp;</td>
		  <td valign="top"><span id="healthservice" style="display:none;"><!healthsevice></span>
		  </td>
        </tr>
        </table>
        
        
      <table width="100%" border="0" cellspacing="1" cellpadding="2"  style="border:1px solid #CCCCCC;padding-top:15px;border-top: 0px;">
        <tr>
          <td align="right" style="width: 5%"><a href="javascript:void(0);" onClick="javascript:show_toggle('wellnessStore');">
		  <span id="wp" style="font-weight:bold; font-size:14px; font-family:Arial, Helvetica, sans-serif;">+</span><span id="wn" style="display:none;font-size:22px;font-weight:bold; font-family:Arial, Helvetica, sans-serif;">-</span></a></td>
            <td style="font-size:12px;font-weight:bold;">Provider Branded Wellness Store - <a href="javascript:void(0);" onclick="GB_showCenter('FAQ - Provider Branded E-Commerce Store', '/index.php?action=faq_therapist_head_store',570,970);">FAQ</a></td>
        </tr> 
        <tr>
          <td style="width: 5%">&nbsp;</td>
		  <td valign="top"><span id="wellnessStore" style="display:none;" ><!storelink><br><br>With Tx Xchange's Provider Branded Wellness Store, you can take part in the growing \$25B/yr supplement market and increase revenue for your business without having to invest capital, manage more vendors, or expand operations. To learn how to easily launch a profitable new revenue stream and give your patients the ability to spend more of their health related dollars with you, please click on the FAQ above. 
		  <br><br><table border="0" cellpadding="1" cellspacing="1" width="90%" style="border:2px solid #CCCCCC;background:#F5F5F5;">
                                         <tr colspan="2">
                                            <td style="padding-left:10px; font-weight:bold;">
                                            <table cellspacing="0" cellpadding="0">
                                            <tr>
                                            <td valign="top" style="padding-top:1px;">Turn on Provider Branded Wellness Store</td>
                                            <td valign="middle" style="padding-left: 2px;">&nbsp;<input type="checkbox" value="1" name="store" <!store> />
                                            </td>
                                            
                                            </tr>
                                            
                                            </table>
                                            
                                            
                                            </td>

                                        </tr>
                                    </table><br>
		 </span> </td>
        </tr>

        <tr>
          <td align="right" style="width: 5%"><a href="javascript:void(0);" onClick="javascript:show_toggle('wellness_store');"><span id="wellness_storeP" style="font-weight:bold; font-size:14px; font-family:Arial, Helvetica, sans-serif;">+</span><span id="wellness_storeN" style="display:none;font-size:22px;font-weight:bold; font-family:Arial, Helvetica, sans-serif;" >-</span></a></td><td style="font-size:12px;font-weight:bold;">Integrated Wellness Store - <a href="javascript:void(0);" onclick="GB_showCenter('FAQ - Integrated Wellness Store', '/index.php?action=faq_therapist_integrated_wellness',570,970);">FAQ</a></td>
        </tr>
        <tr>
          <td style="width: 5%">&nbsp;</td>
		  <td valign="top"><span id="wellness_store" style="display:none;">With Integrated Wellness Store, you can put your existing online store right in your patient's portal. Every time they login to follow your plan of care, watch videos, read articles, and communicate with you, they will see your store. The more they see your store, the more likely they will be to buy more of your supplements and products.  To learn how to easy it is give your patients the ability to spend more of their health related dollars with you, please click on the FAQ above.<br><br><table border="0" cellpadding="1" cellspacing="1" width="95%" style="border:2px solid #CCCCCC;background:#F5F5F5;">
                                         <tr colspan="2">
                                            <td style="padding-left:10px; font-weight:bold; width: 825px;">
                                            <table cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                            <td valign="top" style="padding-top:1px;">
											Turn on Integrated Wellness Store</td>
                                            <td valign="left" style="padding-left: 1px;">&nbsp;<input type="checkbox" style="margin:0;" onclick="javascript:changewellnesstextbox()" value="1" name="wellness_store_check" id="wellness_store_check"  <!wellness_store_check> /></td>
                                            </tr>
                                            <tr>
                                            <td valign="top" style="padding-top:3px;font-weight:normal;">
											Enter Store Web Address/URL</td>
                                            <td valign="left" style="padding-left: 1px;">&nbsp;<input type="text" value="<!wellnessStoreUrl>" name="wellnessStoreUrl" id="wellnessStoreUrl" maxlength="100" size="56" <!urldisabled> ></td>
                                            </tr>
                                            <tr>
                                            <td valign="top" style="padding-top:3px;">
											&nbsp;</td>
                                            <td valign="middle" style="padding-left: 2px; font-size: 9px; font-weight: normal;">&nbsp;&nbsp;(e.g. https://clients.mindbodyonline.com/ASP/home.asp?studioid=10130)</td>
                                            </tr>
                                            </table>
                                            
                                            
                                            </td>
                                        </tr>
                                    </table><br>

		 </span> <br> </td>
        </tr>


        <tr>
          <td align="right" valign="middle" style="height: 18px; width: 5%;"><a href="javascript:void(0);" onClick="javascript:show_toggle('settings');"><span id="mp" style="font-weight:bold; font-size:14px; font-family:Arial, Helvetica, sans-serif; display:none;">+</span><span id="mn" style="font-size:22px;font-weight:bold; font-family:Arial, Helvetica, sans-serif;">-</span></a></td>
		  <td style="font-size:12px;font-weight:bold; height: 18px;">Referral Marketing Service - <a href="javascript:void(0);" onclick="GB_showCenter('FAQ - Referral Marketing Service', '/index.php?action=faq_therapist_head',570,970);">FAQ</a></td>
        </tr>
		<tr>
          <td style="width: 5%">&nbsp;</td>
		  <td valign="top"><span id="settings" style="display:none;"><!referralSettings></span>
		  </td>
        </tr>
        
        
        

        <tr>
          <td align="right" style="width: 5%"><a href="javascript:void(0);" onClick="javascript:show_toggle('scheduling');"><span id="schedulingP" style="font-weight:bold; font-size:14px; font-family:Arial, Helvetica, sans-serif;">+</span><span id="schedulingN" style="display:none;font-size:22px;font-weight:bold; font-family:Arial, Helvetica, sans-serif;" >-</span></a></td><td style="font-size:12px;font-weight:bold;">Integrated Online Scheduling - <a href="javascript:void(0);" onclick="GB_showCenter('FAQ - Integrated Online Scheduling', '/index.php?action=faq_therapist_head_scheduling',570,970);">FAQ</a></td>
        </tr>
        <tr>
          <td style="width: 5%">&nbsp;</td>
		  <td valign="top"><span id="scheduling" style="display:none;">With Integrated Online Scheduling, you can put your existing online schedule in your Tx Xchange patient portal and make it even easier for patients and clients to schedule an appointment with you. Click on the FAQ above to learn how Integrated Online Scheduling will help you book more appointments and make more money.<br><br><table border="0" cellpadding="1" cellspacing="1" width="95%" style="border:2px solid #CCCCCC;background:#F5F5F5;">
                                         <tr colspan="2">
                                            <td style="padding-left:10px; font-weight:bold; width: 825px;">
                                            <table cellspacing="0" cellpadding="0">
                                            <tr>
                                            <td valign="top" style="padding-top:1px;">
											Turn on Integrated Online Scheduling</td>
                                            <td valign="left" style="padding-left: 1px;">&nbsp;<input type="checkbox" onclick="javascript:changetextbox()" style="margin:0;" value="1" name="schedul" id="schedul"  <!schedul> /></td>
                                            </tr>
                                            <tr>
                                            <td valign="top" style="padding-top:3px;font-weight:normal;">
											Enter Schedule Web Address/URL</td>
                                            <td valign="left" style="padding-left: 1px;">&nbsp;<input type="text" value="<!schedulUrl>" name="schedulUrl" id="schedulUrl" maxlength="100" size="56" <!disabled> ></td>
                                            </tr>
                                            <tr>
                                            <td valign="top" style="padding-top:3px;">
											&nbsp;</td>
                                            <td valign="middle" style="padding-left: 2px; font-size: 9px; font-weight: normal;">&nbsp;&nbsp;(e.g. https://clients.mindbodyonline.com/ASP/home.asp?studioid=10130)</td>
                                            </tr>
                                            </table>
                                            
                                            
                                            </td>
                                        </tr>
                                    </table><br>

		 </span> <br> </td>
        </tr>

        

        <tr>
          <td align="right" style="width: 5%"><a href="javascript:void(0);" onClick="javascript:show_toggle('notifications');"><span id="notificationsP" style="font-weight:bold; font-size:14px; font-family:Arial, Helvetica, sans-serif;">+</span><span id="notificationsN" style="display:none;font-size:22px;font-weight:bold; font-family:Arial, Helvetica, sans-serif;" >-</span></a></td><td style="font-size:12px;font-weight:bold;">Notifications </td>
        </tr>
        <tr>
          <td style="width: 5%">&nbsp;</td>
		  <td valign="top">
		  <span id="notifications" style="display:none;">By turning on these notification services, you can receive an email notification when one of your patients sends you a messages from their portal or receive a message when a patient completes a goal. If you are not logged into the software everyday, these notification services will help you be more responsive to patient or client inquiries.<br><br>
                <table border="0" cellpadding="1" cellspacing="1" width="95%" style="border:2px solid #CCCCCC;background:#F5F5F5;">
                                         <tr colspan="2">
                                            <td style="padding-left:10px; font-weight:bold; width: 825px;">
                                            <table cellspacing="0" cellpadding="0">
                                             <tr>
                                            <td valign="top" style="padding:5px 0;">Send a message notification to associated providers when a patient completes a goal</td>
                                            <td valign="middle" style="padding-left: 2px;">&nbsp;
<input type="checkbox"  value="1" name="goal_notification" id="goal_notification"  <!goal_notification_check> /></td>
                                            </tr>
                                            <tr>
                                            <td valign="top" style="padding:5px 0;">Send an  email notification to associated providers when a patient sends a message </td>
<td valign="middle" style="padding-left: 2px;">&nbsp;<input type="checkbox"  value="1" name="patient_email_notification" id="patient_email_notification"  <!patient_email_notification_check> /></td>
                                            </tr>
                                            </table>
                                            
                                            
                                            </td>
                                        </tr>
                                    </table><br>

		 </span> <br> </td>
        </tr>


        <tr>
          <td align="right" style="width: 5%"><a href="javascript:void(0);" onClick="javascript:show_toggle('widget');"><span id="widgetP" style="font-weight:bold; font-size:14px; font-family:Arial, Helvetica, sans-serif;">+</span><span id="widgetN" style="display:none;font-size:22px;font-weight:bold; font-family:Arial, Helvetica, sans-serif;" >-</span></a></td><td style="font-size:12px;font-weight:bold;">Clinic Settings</td>
        </tr>
        <tr>
          <td style="width: 5%" >&nbsp;</td>
		  <td valign="top"><span id="widget" style="display:none;">
                        <table border="0" cellpadding="1" cellspacing="1" width="95%" style="border:2px solid #CCCCCC;background:#F5F5F5;">
                                         <tr colspan="2">
                                            <td style="padding-left:10px; font-weight:bold; width: 825px;">
                                            <table cellspacing="0" cellpadding="0">
                                            <tr>
                                            <td valign="top" style="padding-top:1px;">
                                                    <input name="teleconferencedisable" id="teleconferencedisable" value="1" type="checkbox" <!teleconference> /> Turn off teleconference<img height="17" src="images/img-question.gif" width="19" name="By turning off the teleconference, your patients will not get teleconference section."   />
                                                </td>  

                                                <td valign="middle" style="padding-left: 2px;">
                                                    <input name="messagedisable" id="messagedisable" value="1" type="checkbox" <!message> /> Turn off message section<img height="17" src="images/img-question.gif" width="19" name="By turning off the message , your patients will not get message section."   />
                                                </td>
                                            </tr>
                                            
                                            </table>
                                            
                                            
                                            </td>
                                        </tr>
                                    </table><br>

		 </span> <br> </td>
        </tr>

        <tr>
          <td  style="padding-left:18px; width: 5%;">&nbsp;</td>
          <td  class="error"><table border="0" ><tr><td width="10%" ><input type="submit" value="&nbsp;&nbsp;Save&nbsp;&nbsp;" name="submit"/>&nbsp;</td><td width="90%" valign="top"><!error></td></tr></table></td>
		</tr>
      </table>
	  <input type="hidden" name="action" value="addonServicesHead" />
      <input type="hidden" name="action_submit" value="submit" />
	  </form>
 <!-- best practices table ends here--> 
      <div class="paging" align="right"></div>
      <!-- body part ends-->
      <!-- [/items] -->
      <div align="center" style="padding:5px;"> </div>
      <!-- [/list] -->
    </div>
  </div>
  
</div>
<!js>
<script type="text/javascript">
    mmLoadMenus("<!showmenu>");
</script>
<script>show_toggle('<!addonval>');
document.addon.deslen.value = document.getElementById('subs_description').value.replace(/<[^>]+>/g, '').length;
</script>
