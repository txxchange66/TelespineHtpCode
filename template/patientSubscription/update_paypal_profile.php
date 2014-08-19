<!-- Grey box -->
<script type="text/javascript">  
var GB_ROOT_DIR = "js/greybox/";
function disableButton(){
		document.getElementById('submitbutton').disabled=true;
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
</style>
<!-- End --><div id="container">
    <div id="header">
    <!header>                            
  </div>
    <div id="sidebar" style="padding-top:15px;">
        <!--<ul>&nbsp;</ul>
        <ul class="sideNav">
            <li class="helpBtn">Tx Xchange<br> <span style="font-size: 70%; text-align:left;" >(Release <!release_version>)</span></li>
        </ul>-->  
        
        <a href="index.php?action=logout">
        <span style="font-size:14px;">Logout</span>
    </a>
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
<!--<h1><!HealthProgramName></h1>
<p><!HealthProgramDescription></p>-->
<div class="key-feature" >
<div class="key-feature-lft">
<!-- <h2><!KeyFeatureHeading></h2>
<ul>
<!keyFeatures>
</ul>
 -->
</div>
<!--<div class="key-feature-rgt">
<img alt="" title="" src="images/img-price-top.gif" style="vertical-align:bottom;" />
<div class="key-feature-rgt-inner"><!HealthProgramPriceshow></div>
<img alt="" title="" src="images/img-price-bot.gif" style="vertical-align:top;" />
</div>-->
<div class="cl">&nbsp;</div>
</div>
<div class="card-detail" ><span style="font-size:13px;letter-spacing: 1px;">UPDATE CREDIT CARD INFORMATION</span>
<div class="card-detail-inner">
<form name="patientSubs" id="patientSubs" action="index.php" method="post" onsubmit="return disableButton();" >
    <input type="hidden" name="action" value="update_paypal_profile_submit">
    <input type="hidden" name="ehsTimePeriod" value="<!ehsTimePeriod>">
    <input type="hidden" name="cardPayment" value="<!HealthProgramPrice>">
    <input type="hidden" name="HealthDescription" value="<!HealthProgramName>">
    <input type="hidden" name="HealthProgramID" value="<!HealthProgramID>">
    <input type="hidden" name="profileId" value="<!profileId>">
     <input type="hidden" name="user_subs_id" value="<!user_subs_id>">
    <table cellpadding="4" cellspacing="0" style="width: 100%" id="abhi" border="0">
    <div style="height:10px;"></div>
          <!errorMessage>
         <tr><td colspan="4" style="height:5px; color:#0069a0;; font-weight:bold"></td></tr>
        <tr>
            <td colspan="4" style="padding-bottom:20px;font-size:13px;">Your Current Credit Card No. &nbsp;<label style="font-weight:normal">xxxxxxxxxxxx<!ccno></label></td>
            
        </tr>
       
        <tr>
            <td valign="top" style="margin-top:10px;font-size:13px;"> Card Type</td>
        
            <td class="ct" valign="top"><select name="cardType" style="margin-top: 0px;width:158px;">
                <option value="">Card Type</option>
                <option value="Visa" <!retainVisaType>>Visa</option>
                <option value="MasterCard" <!retainMasterType>>Master Card</option>
            </select><br><!ctypeValid></td>
            <td valign="top" style="margin-top:10px;font-size:13px;">Card No.</td>
            <td valign="top"> <input name="cardNumber" type="text" AUTOCOMPLETE=OFF maxlength="16" value="<!retainedcardNumber>" style="margin-top: 0px;width:152px;"/><br><!cnumberValid></td>
        </tr><!--
       
        <tr>
            <td>&nbsp;</td>
            <td class="ct"><label><!ctypeValid></label></td>
            <td>&nbsp;</td>
            <td><label><!cnumberValid></label></td>
        </tr>
       
        --><tr>
            <td valign="top" style="padding-top:15px;font-size:13px;">CVV No.</td>
            <td class="cvv-no" valign="top"><input type="password" size="6" maxlength="5" AUTOCOMPLETE=OFF name="cvvNumber" value="<!retainedcvvNumber>" style="margin-top: 12px;"/><br><!ccvvValid></td>
            <td valign="top" style="padding-top:15px;font-size:13px;">Expiration </td>
            <td class="expdate" valign="top"><select name="exprMonth" style="margin-top: 12px;width:70px;">
                  <!monthsOptions>
           </select><select name="exprYear" style="margin-top: 12px;width:70px;">
                 <!yearOptions>
            </select><br><!cexpValid></td>
        </tr>
          <tr><td colspan="4" style="height:5px; color:#0069a0;; font-weight:bold"></td></tr>
        <tr><td colspan="4" style="height:20px; color:#0069a0; font-weight:bold;font-size:13px;">Billing Address</td></tr>
         <tr><td colspan="4" style="height:5px; color:#0069a0;; font-weight:bold"></td></tr>
        <tr>
            <td valign="top" style="padding-top:15px;font-size:13px;">Address1</td>
            <td valign="top" ><input name="address1" type="text" AUTOCOMPLETE=OFF value="<!retainedadd1>" style="margin-top: 12px;width:152px;"/><br><!address1Valid></td>
            <td valign="top" style="padding-top:15px;font-size:13px;">Address2    </td>
            <td valign="top" ><input name="address2" type="text" AUTOCOMPLETE=OFF value="<!retainedadd2>" style="margin-top: 12px;width:152px;"/></td>
        </tr>
        <tr>
            <td  valign="top" style="padding-top:15px;font-size:13px;">City</td>
            <td valign="top" ><input name="city" type="text" AUTOCOMPLETE=OFF value="<!retainedcity>" style="margin-top: 12px;width:152px;"/><br><!cityValid></td>
            <td  valign="top" style="padding-top:15px;font-size:13px;">State/Provinces</td>
            <td valign="top" ><select name="state" id="state" style="margin-top: 12px;width:158px;">
                                        <!stateOptions>
                                      </select><br><!stateValid></td>
        </tr>
        
        <tr><td valign="top" style="padding-top:15px;font-size:13px;">Country</td>
            <td valign="top"  ><select name="country" tabindex="" id="country" onchange="toggleState();" style="margin-top: 12px;width:158px;"><!country></select><br><!countryValid></td>
            <td  valign="top" style="padding-top:15px;font-size:13px;">Zip/Postal Code</td>
            <td valign="top"  ><input name="zipcode" type="text" AUTOCOMPLETE=OFF maxlength="7" value="<!retainedzip>" style="margin-top: 12px;width:152px;"/><br><!zipcodeValid></td>
        </tr>
	<tr >

                        
            <td  colspan="2" align="right" style="padding-top:25px;" class="yellow-btn" >
<div style="float:right;"><span><input name="Submit" type="submit" value="Update" class="rgt" id="submitbutton" /></span></div></td>
              <td   align="left" style="padding-top:25px;"><a href="javascript:void(0);"  title="" onclick="GB_showCenter('Refund Policy', '/index.php?action=show_refund_policy',193,600);">Refund Policy</a>&nbsp;&nbsp;</td>
                                                                                                                                                              
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

function disable(){
var tempTable = document.getElementById('submitbutton');
alert(tempTable.getElementsByTagName('label'));

}


</script>
<!show>
