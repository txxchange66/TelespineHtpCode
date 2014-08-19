<!-- Grey box -->
<script type="text/javascript">  
var GB_ROOT_DIR = "js/greybox/";
function disableButton(){
	
	document.getElementById('submitbutton').disabled=true;
	document.getElementById("submitbutton").className = 'rgt-d';
	return true;
}
</script>
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
<script>
</script>
<style type="text/css">

 
table tr td.yellow-btn span{background:url('images/img_btn_lft_y.jpg') no-repeat; border:0; padding-left:1px; float:left; display:block; } 
table tr td.yellow-btn input.rgt{background:url('images/img_btn_rgt_y.jpg') 100% 0; height:29px; padding: 0 6px 3px 5px;  float:left; border:0; color:#4c2506; font-family:Verdana; font-size:12px; font-weight:bold;  width:80px;}
table tr td.yellow-btn input.rgt-d{background:url('images/img_btn_rgt_y.jpg') 100% 0; height:29px; padding: 0 6px 3px 5px;  float:left; border:0; color:gray; font-family:Verdana; font-size:12px; font-weight:bold;  width:80px;}
</style>
<!-- End --><div id="container"><!--
    <div id="header">
        <div id='line'>
                                <div id='txlogo' style='float:left; padding-left:55px;' ></div>
                             
                             <div id='subheader' style='float:right; padding-right:55px;' >
                                <div id='cliniclogo'  ></div>
                             </div>
                             <div style='clear:both;'></div>
                  </div>
    </div>
    
    -->
   <div id="header">
    <!header>                            
  </div>
    <div id="sidebar" style="padding-top:15px;">
    <div style="padding-top:300px;" >
     <table width="10" border="0" cellspacing="0" >
         <tr>
            <td>
                <!-- script goes Here -->
            </td>
         </tr>
         </table>
    </div>    
    </div>
    <div id="mainContent" style="padding-top:14px;">
    <div class=" heath-prog-con">
<h1><!HealthProgramName></h1>


<p><!HealthProgramDescription></p>
<div class="key-feature" >
<div class="key-feature-lft">
<h2><!KeyFeatureHeading></h2>
<ul>
<!keyFeatures>
</ul>

</div>
<div class="key-feature-rgt"><!SubscriptionMessage><!RegistrationMessage>
<img alt="" title="" src="images/img-price-top.gif" style="vertical-align:bottom;" />
<div class="key-feature-rgt-inner"><h1>&#36;<!HealthProgramPrice> <!HealthProgramDuration></h1><!HealthProgramData></div>
<img alt="" title="" src="images/img-price-bot.gif" style="vertical-align:top;" />
</div>
<div class="cl">&nbsp;</div>
</div>
<div class="card-detail"><span>Payment Information</span>
<div class="card-detail-inner">
<form name="patientSubs" id="patientSubs" action="index.php" method="post" onsubmit="return disableButton();">
    <input type="hidden" name="action" value="submit_ehs_patient_subscription">
    <input type="hidden" name="cardPayment" value="<!HealthProgramPrice>">
    <input type="hidden" name="HealthDescription" value="<!HealthProgramName>">
    <input type="hidden" name="HealthProgramID" value="<!HealthProgramID>">
    <input type="hidden" name="ehsTimePeriod" value="<!ehsTimePeriod>">
    <input type="hidden" name="paymentType" value="<!paymentType>">
     <input type="hidden" name="cid" value="<!clinic_id>">
    
    <table cellpadding="4" cellspacing="0"  border="0" style="width: 100%">
    <div style="height:15px;"></div>
     <!errorMessage>
        <tr>
            <td style="width:110px; padding-top:10px" valign="top" >First Name</td>
            <td  valign="top"><input type="text" name="firstname" AUTOCOMPLETE=OFF  " value="<!retainedFname>" tabindex="1"/><br><!namefValid></td>
            <td style="width:120px; padding-top:10px" valign="top">Last Name</td>
            <td  valign="top"> <input name="lastname" type="text" AUTOCOMPLETE=OFF value="<!retainedlname>" tabindex="2"/><br><!namelValid></td>
        </tr>
		<!--<tr>
            <td style="width:110px;">&nbsp;</td>
            <td ><!namefValid></td>
            <td style="width:120px;">&nbsp;</td>
            <td ><!namelValid></td>
        </tr>
   --><tr>
        <td style=" padding-top:19px" valign="top">Email Address</td>
        <td  valign="top"><input type="text" name="emailaddress" AUTOCOMPLETE=OFF  style="margin-top:12px;" value="<!retainedEmail>" tabindex="3"/><br><!emailValid></td>
        <td></td>
        <td class="ct"></td>
        </tr><!--
        <tr>
        <td>&nbsp;</td>
        <td><!emailValid></td>
        <td></td>
        <td class="ct"></td>
        </tr>
         -->
         
       <tr><td colspan="4" style="height:20px; color:#0069a0; font-weight:bold;padding-top:25px;">Credit Card Details</td></tr>
       
       <tr>
            <td style="padding-top:15px" valign="top">Card Type</td>
            <td class="ct"  valign="top"><select name="cardType" tabindex="5" style="margin-top:10px;" >
                <option value="">Card Type </option>
                <option value="Visa" <!retainVisaType>>Visa</option>
                <option value="MasterCard" <!retainMasterType>>Master Card</option>
            </select><br><!ctypeValid></td>
            <td style="padding-top:15px" valign="top">Card No.</td>
            <td  valign="top"><input name="cardNumber" type="text" AUTOCOMPLETE=OFF maxlength="16" tabindex="6" 
                value="<!retainedcardNumber>" style="margin-top:10px;" /><br><!cnumberValid></td>
        </tr>
        <!--<tr>
            <td>&nbsp;</td>
            <td class="ct"><!ctypeValid></td>
            <td>&nbsp;</td>
            <td><!cnumberValid></td>
        </tr>
        --><tr>
            <td  style="padding-top:19px" valign="top">CVV No.</td>
            <td class="cvv-no"  valign="top"><input type="password" size="6" maxlength="5" AUTOCOMPLETE=OFF name="cvvNumber" tabindex="7" value="<!retainedcvvNumber>"  style="margin-top:12px;" /><br><!ccvvValid></td>
            <td  style="padding-top:19px" valign="top">Expiration </td>
            <td class="expdate"  valign="top"><select name="exprMonth" tabindex="8" style="margin-top:12px;">
                <!monthsOptions>
           </select><select name="exprYear" tabindex="9" style="margin-top:12px;">
                <!yearOptions>
            </select><br><!cexpValid></td>
        </tr>
		<!--<tr>
            <td>&nbsp;</td>
            <td class="cvv-no"><!ccvvValid></td>
            <td>&nbsp; </td>
            <td class="expdate"><!cexpValid></td>
        </tr>
         -->
        <tr><td colspan="4" style="height:20px; color:#0069a0;; font-weight:bold ;padding-top:25px;">Billing Address</td></tr>
         
        <tr>
            <td style="padding-top:15px" valign="top">Address1</td>
            <td  valign="top" ><input name="address1" type="text" AUTOCOMPLETE=OFF value="<!retainedadd1>" tabindex="10" style="margin-top:10px;"/><br><!address1Valid></td>
            <td style="padding-top:15px" valign="top">Address2    </td>
            <td valign="top">  <input name="address2" type="text" AUTOCOMPLETE=OFF value="<!retainedadd2>" tabindex="11" style="margin-top:10px;"/></td>
        </tr><!--

        <tr>
            <td>&nbsp;</td>
            <td ><!address1Valid></td>
            <td>&nbsp;   </td>
            <td>&nbsp;</td>
        </tr>


        
        --><tr>
            <td   style="padding-top:19px" valign="top">City</td>
             <td class="ct"  valign="top"><input name="city" type="text" AUTOCOMPLETE=OFF value="<!retainedcity>" tabindex="12"  style="margin-top:12px;" /><br><!cityValid></td>
            <td   style="padding-top:19px" valign="top">State/Provinces</td>
            <td  valign="top"><select name="state" id="state" tabindex="13" style="margin-top:12px;width:165px;" >
                                        <!stateOptions>
                                      </select><br><!stateValid></td>
        </tr><!--

        <tr>
            <td>&nbsp;</td>
             <td class="ct"><!cityValid></td>
            <td>&nbsp;</td>
            <td><!stateValid></td>
        </tr>
        --><tr><td  style="padding-top:19px" valign="top">Country</td>
            <td class="ct" valign="top"><select name="country" tabindex="14" id="country" onchange="toggleState();"  style="margin-top:12px;" ><!country></select><!countryValid></td>
           
            <td  style="padding-top:19px" valign="top">Zip/Postal Code</td>
            <td class="ct"  valign="top"><input name="zipcode" type="text" AUTOCOMPLETE=OFF maxlength="7" value="<!retainedzip>" tabindex="15"  style="margin-top:12px;" /><br><!zipcodeValid></td>
        </tr>
     <!--<tr><td>&nbsp;</td>
            <td class="ct"><!countryValid></td>
           
            <td>&nbsp;</td>
            <td class="ct"><!zipcodeValid></td>
        </tr>   
 --><tr >

                        
            <td  colspan="2" align="right" style="padding-top:40px;" class="yellow-btn" >
<div style="float:right;"><span><input name="Submit" type="submit" value="Subscribe" class="rgt" id="submitbutton" /></span></div></td>
              <td   align="left" style="padding-top:35px;"><a href="javascript:void(0);"  title="" onclick="GB_showCenter('Refund Policy', '/index.php?action=show_refund_policy1',193,600);">Refund Policy</a>&nbsp;&nbsp;</td>
                                                                                                                                                              
        </tr>
        
    </table>
</form>    
    </div>
</div>
<div align="center" style="padding:35px 0 20px 0;"><img src="images/visa_master.jpg" /></div>

</div>
    </div>
</div>
<div id="footer">
    <!footer>
</div>
</div>
<script language="javascript">
function addOption(selectbox,text,value )
                {
                                var optn = document.createElement("OPTION");
                                optn.text = text;
                                optn.value = value;
                                checkedState='';
                                if(checkedState==text) {
                                                optn.selected = 'selected';
                                }
                                selectbox.options.add(optn);
                }

function toggleState()
                {
                               // alert(document.patientSubs.country.value);
                                document.patientSubs.state.value='';
                                var statearray= Array();
                                if(document.patientSubs.country.value!='')
                                {              
                                                document.getElementById('state').options.length = 0
                                                contury=document.patientSubs.country.value;
                                                $.ajax({
                                              	  url: 'index.php?action=getstate&country_code='+contury,
                                              	  success: function(data) {
                                                	var mySplitResult = data.split(",");
                                                	for(i = 0; i < mySplitResult.length; i++){
                                                		stateval=mySplitResult[i].split(":");
                                                		addOption(document.getElementById('state'),stateval[1],stateval[0]);
                                                		 
                                                	}
                                                 }
                                              	});
                                                
                                                                                            
                                }
                                else
                                {
                                                document.getElementById('state').style.display='none';
                                                
                                }              
                }

</script>
