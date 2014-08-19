<script language="JavaScript" src="js/show_menu.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>


<script>
function changetextbox(){
	if(document.getElementById("schedul").checked==true){
		document.getElementById("schedulUrl").disabled = false;
	}else{
		document.getElementById("schedulUrl").disabled = true;	
	}
}
function show_toggle(value)
{ 
	//alert(value);
	//alert(document.getElementById("settings").style.display);
	
	if (value=='settings')
	{
		if(document.getElementById("settings").style.display=='inline')
		{
			document.getElementById("settings").style.display='none';
			document.getElementById("mp").style.display='inline';
			document.getElementById("mn").style.display='none';
			
		}else{
			document.getElementById("settings").style.display='inline';
			document.getElementById("wellnessStore").style.display='none';
			//document.getElementById("service").style.display='none';
			document.getElementById("scheduling").style.display='none';
			document.getElementById("mp").style.display='none';
			document.getElementById("mn").style.display='inline';
			document.getElementById("wp").style.display='inline';
			document.getElementById("wn").style.display='none';
			//document.getElementById("sp").style.display='inline';
			//document.getElementById("sn").style.display='none';
			document.getElementById("schedulingP").style.display='inline';
			document.getElementById("schedulingN").style.display='none';
			document.getElementById("healthservice").style.display='none';
			document.getElementById("healthPostive").style.display='inline';
			document.getElementById("healthNegative").style.display='none';


			
		}
	}
	if (value=='wellnessStore')
	{
		if(document.getElementById("wellnessStore").style.display=='inline')
		{
			document.getElementById("wellnessStore").style.display='none';
			document.getElementById("wp").style.display='inline';
			document.getElementById("wn").style.display='none';

			
		}else{
			document.getElementById("wellnessStore").style.display='inline';
			document.getElementById("settings").style.display='none';
			//document.getElementById("service").style.display='none';
			document.getElementById("scheduling").style.display='none';
			document.getElementById("wp").style.display='none';
			document.getElementById("wn").style.display='inline';
			document.getElementById("mp").style.display='inline';
			document.getElementById("mn").style.display='none';
			//document.getElementById("sp").style.display='inline';
			//document.getElementById("sn").style.display='none';
			document.getElementById("schedulingP").style.display='inline';
			document.getElementById("schedulingN").style.display='none';
			document.getElementById("healthservice").style.display='none';
			document.getElementById("healthPostive").style.display='inline';
			document.getElementById("healthNegative").style.display='none';


			
		}
	}
/*if (value=='service')
	{
		if(document.getElementById("service").style.display=='inline')
		{
			document.getElementById("service").style.display='none';
			document.getElementById("sp").style.display='inline';
			document.getElementById("sn").style.display='none';

			
		}else{
			//document.getElementById("service").style.display='inline';
			document.getElementById("settings").style.display='none';
			document.getElementById("wellnessStore").style.display='none';
			document.getElementById("scheduling").style.display='none';
			//document.getElementById("sp").style.display='none';
			//document.getElementById("sn").style.display='inline';
			document.getElementById("mp").style.display='inline';
			document.getElementById("mn").style.display='none';
			document.getElementById("wp").style.display='inline';
			document.getElementById("wn").style.display='none';
			document.getElementById("schedulingP").style.display='inline';
			document.getElementById("schedulingN").style.display='none';



		}
	}*/
if (value=='scheduling')
	{
		if(document.getElementById("scheduling").style.display=='inline')
		{
			document.getElementById("scheduling").style.display='none';
			document.getElementById("schedulingP").style.display='inline';
			document.getElementById("schedulingN").style.display='none';

			
		}else{
			document.getElementById("scheduling").style.display='inline'
			//document.getElementById("service").style.display='none'
			document.getElementById("settings").style.display='none';
			document.getElementById("wellnessStore").style.display='none';
			document.getElementById("schedulingP").style.display='none';
			document.getElementById("schedulingN").style.display='inline';
			//document.getElementById("sp").style.display='inline';
			//document.getElementById("sn").style.display='none';
			document.getElementById("mp").style.display='inline';
			document.getElementById("mn").style.display='none';
			document.getElementById("wp").style.display='inline';
			document.getElementById("wn").style.display='none';
			document.getElementById("healthservice").style.display='none';
			document.getElementById("healthPostive").style.display='inline';
			document.getElementById("healthNegative").style.display='none';


		}
	}

	if (value=='healthservice')
	{
		if(document.getElementById("healthservice").style.display=='inline')
		{
			document.getElementById("healthservice").style.display='none';
			document.getElementById("healthPostive").style.display='inline';
			document.getElementById("healthNegative").style.display='none';
			
		}else{
			document.getElementById("healthservice").style.display='inline';
			document.getElementById("settings").style.display='none';
			document.getElementById("wellnessStore").style.display='none';
			//document.getElementById("service").style.display='none';
			document.getElementById("scheduling").style.display='none';
			document.getElementById("mp").style.display='inline';
			document.getElementById("mn").style.display='none';
			document.getElementById("wp").style.display='inline';
			document.getElementById("wn").style.display='none';
			//document.getElementById("sp").style.display='inline';
			//document.getElementById("sn").style.display='none';
			document.getElementById("schedulingP").style.display='inline';
			document.getElementById("schedulingN").style.display='none';
			document.getElementById("healthPostive").style.display='none';
			document.getElementById("healthNegative").style.display='inline';


			
		}
	}

	
}
</script>
<script language="javascript" type="text/javascript">
    iCounterCheck=<!count>;  
    //alert(iCounterCheck );

    function toggleHealthCheck(){
             //alert(document.getElementById('healthEnable').checked);
             if(document.getElementById('healthEnable').checked) {
             	
             	$('#subs_description,#subs_title,#subs_feature1,#subs_feature2,#subs_feature3,#subs_feature4,#subs_feature5,#subs_feature6,#subs_feature7,#subs_price,#link_more,#link_more_help').css({background:"#ffffff"});
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

                    
             }  else{
$('#subs_description,#subs_title,#subs_feature1,#subs_feature2,#subs_feature3,#subs_feature4,#subs_feature5,#subs_feature6,#subs_feature7,#subs_price,#link_more,#link_more_help').css({background:"#ebebe4"});
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
			   document.getElementById('link_more').style.display="none";
				document.getElementById('link_more_help').style.display="none";
                
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
        //alert(iCounterCheck );
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
          <td width="173" align="right" style="padding-right:75px; margin-top:11px;height:70px;">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="top" style=" width:400px; font-size: large;font-weight: bold;padding-bottom: 15px; margin:0px; vertical-align:top;">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="top"  ><div style=" padding-top:26px;">
            <!navigationTab>
          </div></td>
        </tr>
        <tr>
          <td colspan="3" valign="top" class="topnavnew" >&nbsp;</td>
        </tr>
      </table>
      
      
 <!-- best practices table starts from here-->  
      <form name="addon" method="post" action="index.php">
      <table width="100%" border="0" cellspacing="1" cellpadding="2"  style="border:1px solid #CCCCCC;padding-top:15px;">
        <tr>
          <td width="5%" align="right" valign="middle"><a href="javascript:void(0);" onClick="javascript:show_toggle('settings');"><span id="mp" style="font-weight:bold; font-size:14px; font-family:Arial, Helvetica, sans-serif; display:none;">+</span><span id="mn" style="font-size:22px;font-weight:bold; font-family:Arial, Helvetica, sans-serif;">-</span></a></td>
		  <td style="font-size:12px;font-weight:bold;">Referral Marketing Service - <a href="javascript:void(0);" onclick="GB_showCenter('FAQ - Referral Marketing Service', '/index.php?action=faq_therapist',570,970);">FAQ</a></td>
        </tr>
		<tr>
          <td>&nbsp;</td>
		  <td valign="top"><span id="settings" style="display:inline;"><!referralSettings></span>
		  </td>
        </tr>
       
        <tr>
         <td align="right"><a href="javascript:void(0);" onClick="javascript:show_toggle('wellnessStore');">
		  <span id="wp" style="font-weight:bold; font-size:14px; font-family:Arial, Helvetica, sans-serif;">+</span><span id="wn" style="display:none;font-size:22px;font-weight:bold; font-family:Arial, Helvetica, sans-serif;">-</span></a></td>            <td style="font-size:12px;font-weight:bold;">Provider Branded Wellness Store - <a href="javascript:void(0);" onclick="GB_showCenter('FAQ - Provider Branded E-Commerce Store', '/index.php?action=faq_therapist_store',570,970);">FAQ</a></td>
        </tr> 
        <tr>
          <td>&nbsp;</td>
		  <td valign="top"><span id="wellnessStore" style="display:none;" ><!storelink><br><br>With Tx Xchange's Provider Branded Wellness Store, you can take part in the growing \$25B/yr supplement market and increase revenue for your business without having to invest capital, manage more vendors, or expand operations. To learn how to easily launch a profitable new revenue stream and give your patients the ability to spend more of their health related dollars with you, please click on the FAQ above. 
		  <br><br><table border="0" cellpadding="1" cellspacing="1" width="90%" style="border:2px solid #CCCCCC;background:#F5F5F5;">
                                         <tr colspan="2">
                                            <td style="padding-left:10px; font-weight:bold;">
                                            <table cellspacing="0" cellpadding="0">
                                            <tr>
                                            <td valign="top" style="padding-top:1px;">Turn on Provider Branded Wellness Store</td>
                                            <td valign="middle" style="padding-left: 2px;"><input type="checkbox" value="1" name="store" <!store> />
                                            </td>
                                            
                                            </tr>
                                            
                                            </table>
                                            
                                            
                                            </td>

                                        </tr>
                                    </table><br>
		 </span> </td>
        </tr>
        
       <!-- <tr>
          <td align="right"><a href="javascript:void(0);" onClick="javascript:show_toggle('service');"><span id="sp" style="font-weight:bold; font-size:14px; font-family:Arial, Helvetica, sans-serif;">+</span><span id="sn" style="display:none;font-size:22px;font-weight:bold; font-family:Arial, Helvetica, sans-serif;" >-</span></a></td>            <td style="font-size:12px;font-weight:bold;">E-Rehab Service (Beta) - <a href="javascript:void(0);" onclick="GB_showCenter('FAQ - E-Rehab Service', '/index.php?action=faq_therapist_erehab',570,970);">FAQ</a></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
		  <td valign="top"><span id="service" style="display:none;">Tx Xchanges E-Rehab Service gives health, wellness, and fitness professionals the ability to "productize" and charge their patients for online personalized plans, guidance, and access to the professional's expertise. E-Rehab can be offered to all patients or clients that are being discharged, have stopped scheduling sessions, or have run out of benefits. The E-Rehab Service addresses increasing consumer demand for web-based services from their health, wellness, and fitness providers. With E-Rehab, providers can now launch new subscription-based revenue streams and increase the lifetime value of each of their customers. To learn more, please click on the FAQ above.
		  <br><br><table border="0" cellpadding="1" cellspacing="1" width="90%" style="border:2px solid #CCCCCC;background:#F5F5F5;">
                                         <tr colspan="2">
                                            <td style="padding-left:10px; font-weight:bold;">
                                            <table cellspacing="0" cellpadding="0">
                                            <tr>
                                            <td valign="top" style="padding-top:1px;">Turn on E-Rehab Service</td>
                                            <td valign="middle" style="padding-left: 2px;"><input type="checkbox" value="1" name="program" <!program> /></td>
                                            
                                            </tr>
                                            
                                            </table>
                                            
                                            
                                            </td>
                                        </tr>
                                    </table><br>

		 </span> <br> </td>
        </tr>-->
        <tr>
          <td align="right" valign="middle" style="height: 18px; width: 5%;"><a href="javascript:void(0);" onClick="javascript:show_toggle('healthservice');"><span id="healthPostive" style="font-weight:bold; font-size:14px; font-family:Arial, Helvetica, sans-serif; ">+</span><span id="healthNegative" style="font-size:22px;font-weight:bold; font-family:Arial, Helvetica, sans-serif;display:none;">-</span></a></td>
		  <td style="font-size:12px;font-weight:bold; height: 18px;">E-Health Service - <a href="javascript:void(0);" onclick="GB_showCenter('FAQ - E-Health Service', '/index.php?action=faq_HealthServies',570,970);">FAQ</a></td>
        </tr>
		<tr>
          <td style="width: 5%">&nbsp;</td>
		  <td valign="top"><span id="healthservice" style="display:none;"><!healthsevice></span>
		  </td>
        </tr>

        <tr>
          <td align="right"><a href="javascript:void(0);" onClick="javascript:show_toggle('scheduling');"><span id="schedulingP" style="font-weight:bold; font-size:14px; font-family:Arial, Helvetica, sans-serif;">+</span><span id="schedulingN" style="display:none;font-size:22px;font-weight:bold; font-family:Arial, Helvetica, sans-serif;" >-</span></a></td>            <td style="font-size:12px;font-weight:bold;">Integrated Online Scheduling - <a href="javascript:void(0);" onclick="GB_showCenter('FAQ - Integrated Online Scheduling ', '/index.php?action=faq_therapist_scheduling',570,970);">FAQ</a></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
		  <td valign="top"><span id="scheduling" style="display:none;">With Integrated Online Scheduling, you can put your existing online schedule in your Tx Xchange patient portal and make it even easier for patients and clients to schedule an appointment with you. Click on the FAQ above to learn how Integrated Online Scheduling will help you book more appointments and make more money.<br><br><table border="0" cellpadding="1" cellspacing="1" width="95%" style="border:2px solid #CCCCCC;background:#F5F5F5;">
                                         <tr colspan="2">
                                            <td style="padding-left:10px; font-weight:bold; width: 825px;">
                                            <table cellspacing="0" cellpadding="0">
                                            <tr>
                                            <td valign="top" style="padding-top:1px;">
											Turn on Integrated Online Scheduling</td>
                                            <td valign="middle" style="padding-left: 2px;">&nbsp;<input type="checkbox" onclick="javascript:changetextbox()" value="1" name="schedul" id="schedul"  <!schedul> /></td>
                                            </tr>
                                            <tr>
                                            <td valign="top" style="padding-top:3px;font-weight:normal;">
											Enter Schedule Web Address/URL</td>
                                            <td valign="middle" style="padding-left: 2px;">&nbsp;<input type="text" value="<!schedulUrl>" name="schedulUrl" id="schedulUrl" maxlength="100" size="56" <!disabled> ></td>
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
          <td style="padding-left:18px;">&nbsp;</td>
          <td  class="error"> <input type="submit" value="&nbsp;&nbsp;Save&nbsp;&nbsp;"/>&nbsp;<!error></td>
          </tr>
          
      </table>
	  <input type="hidden" name="action" value="addonServices" />
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
<script language="JavaScript1.2">mmLoadMenus();</script>