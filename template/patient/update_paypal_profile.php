<!-- Grey box -->
<script type="text/javascript">  
function disableButton(){
		document.getElementById('submitbutton').disabled=true;
		document.getElementById("submitbutton").className = 'rgt-d';
		return true;
}
</script>
<style type="text/css">

 
table tr td.yellow-btn span{background:url('images/img_btn_lft_gray.jpg') no-repeat; border:0; padding-left:1px; float:left; display:block; } 
table tr td.yellow-btn input.rgt{background:url('images/img_btn_rgt_gray.jpg') 100% 0; height:22px; padding: 0 6px 3px 5px;  float:left; border:0; color:black; font-family:Verdana; font-size:12px; width:80px;}
table tr td.yellow-btn input.rgt-d{background:url('images/img_btn_rgt_gray.jpg') 100% 0; height:22px; padding: 0 6px 3px 5px;  float:left; border:0; color:gray; font-family:Verdana; font-size:12px; width:80px;}
</style>
<style>
#abhi td{font-weight:bold;}</style>
 <tr>
    <td colspan="2">&nbsp;
    </td>
</tr>    
<tr>
    <td colspan="2">&nbsp;
    </td>
</tr>    
<tr>
    <td colspan="2"><div align="left" class="largeH6" style="padding-top:5px;padding-left:5px;font-size:13px;letter-spacing: 1px;text-transform:uppercase;">&nbsp;&nbsp;Credit Card Information</div>
    </td>
</tr>
<tr>
    <td colspan="2">
     
<form name="patientSubs" id="patientSubs" action="index.php" method="post" onsubmit="return disableButton();" >
    <input type="hidden" name="action" value="update_credit_card_informaion">
    <input type="hidden" name="HealthProgramID" value="<!HealthProgramID>">
    <input type="hidden" name="profileId" value="<!profileId>">
     <input type="hidden" name="user_subs_id" value="<!user_subs_id>">
    <table cellpadding="4" cellspacing="0" style="width: 100%" id="abhi" border="0">
    <div style="height:5px;"></div>
          <!errorMessage>
        <tr class="inputRow">
            <td colspan="4" style="padding-bottom:10px; font-size:13px;" ><lable>Your Current Credit Card No.</lable>&nbsp;&nbsp;&nbsp;&nbsp; <label style="font-weight:normal;">xxxxxxxxxxxx<!ccno></label></td>
     
        </tr>
       
        <tr class="inputRow">
            <td valign="top" style="padding-top:15px;font-size:13px;" width="100px;"><lable> Card Type</lable></td>
            <td class="ct" valign="top" width="215px;"><select name="cardType" style="margin-top: 12px;width:165px;">
                <option value="">Card Type</option>
                <option value="Visa" <!retainVisaType>>Visa</option>
                <option value="MasterCard" <!retainMasterType>>Master Card</option>
            </select><br><!ctypeValid></td>
            <td valign="top" style="padding-top:15px; font-size:13px;"  width="155px;"><lable>Card No.</lable></td>
            <td valign="top"  width="265px;"> <input name="cardNumber" type="text" AUTOCOMPLETE=OFF maxlength="16" value="<!retainedcardNumber>" style="margin-top: 12px; width:158px;"/><br><!cnumberValid></td>
        </tr>
 		<tr class="inputRow">
            <td valign="top" style="padding-top:15px;font-size:13px;"><lable>CVV No.</lable></td>
            <td class="cvv-no" valign="top"><input type="password" size="6" maxlength="5" AUTOCOMPLETE=OFF name="cvvNumber" value="<!retainedcvvNumber>" style="margin-top: 12px;"/><br><!ccvvValid></td>
            <td valign="top" style="padding-top:15px;font-size:13px; ">Expiration </td>
            <td class="expdate" valign="top"><select name="exprMonth" style="margin-top: 12px;width:70px;">
                  <!monthsOptions>
           </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="exprYear" style="margin-top: 12px;width:70px;">
                 <!yearOptions>
            </select><br><!cexpValid></td>
        </tr>
         <tr><td colspan="4" style="height:5px; color:#0069a0;; font-weight:bold"></td></tr>
        <tr><td colspan="4" style="height:20px; color:#0069a0; font-weight:bold;font-size:13px;">Billing Address</td></tr>
         <tr><td colspan="4" style="height:5px; color:#0069a0;; font-weight:bold"></td></tr>
        <tr class="inputRow">
            <td valign="top" style="padding-top:15px;font-size:13px;"><lable>Address1</lable></td>
            <td valign="top" ><input name="address1" type="text" AUTOCOMPLETE=OFF value="<!retainedadd1>" style="margin-top: 12px;width:158px;"/><br><!address1Valid></td>
            <td valign="top" style="padding-top:15px;font-size:13px;"><lable>Address2 </lable>   </td>
            <td valign="top"><input name="address2" type="text" AUTOCOMPLETE=OFF value="<!retainedadd2>" style="margin-top: 12px;width:158px;"/></td>
        </tr>
        <tr class="inputRow">
            <td  valign="top" style="padding-top:15px;font-size:13px;"><lable>City</lable></td>
            <td class="ct" valign="top"><input name="city" type="text" AUTOCOMPLETE=OFF value="<!retainedcity>" style="margin-top: 12px;width:158px;"/><br><!cityValid></td>
            <td  valign="top" style="padding-top:15px;font-size:13px;"><lable>State/Provinces</lable></td>
            <td valign="top"><select name="state" id="state" style="margin-top: 12px;width:165px;">
                                       <!stateOptions>
                                      </select><br><!stateValid></td>
        </tr>
        
        <tr class="inputRow"><td valign="top" style="padding-top:15px;font-size:13px;"><lable>Country</lable></td>
            <td class="ct" valign="top"><select name="country" tabindex="" id="country" onchange="toggleState();" style="margin-top: 12px; width:165px;"><!country></select><br><!countryValid></td>
            <td  valign="top" style="padding-top:15px;font-size:13px;"><lable>Zip/Postal Code</lable></td>
            <td class="ct" valign="top"><input name="zipcode" type="text" AUTOCOMPLETE=OFF maxlength="7" value="<!retainedzip>" style="margin-top: 12px;width:158px;"/><br><!zipcodeValid></td>
        </tr>
		 <tr>
            <td  colspan="4" align="left" style="padding-top:25px;padding-left:562px;" class="yellow-btn"><span><input name="Submit" type="submit" value="Update" class="rgt" id="submitbutton" /></span></td>
                                                                                                                                                                      
		</tr>
    </table>
</form>
</td>
</tr>
    
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

