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

<input type="hidden"  name="showsoap" id="showsoap" value="<!shopshow>">
<script language="JavaScript" src="js/show_menu_head.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>
<script src="js/countryState.js"></script>
<script language="JavaScript" src="js/validateform.js"></script>
<style type="text/css">

 
table tr td.yellow-btn span{background:url('images/img_btn_lft_y.jpg') no-repeat; border:0; padding-left:1px; float:left; display:block; } 
table tr td.yellow-btn input.rgt{background:url('images/img_btn_rgt_y.jpg') 100% 0; height:29px; padding: 0 6px 3px 5px;  border:0; color:#4c2506; font-family:Verdana; font-size:12px; font-weight:bold;  width:173px;}
table tr td.yellow-btn input.rgt-d{background:url('images/img_btn_rgt_y.jpg') 100% 0; height:29px;  padding: 0 6px 3px 5px;  border:0; color:gray; font-family:Verdana; font-size:12px; font-weight:bold;  width:173px;}
</style>
<script language="JavaScript">
<!--

window.formRules = new Array(

	//new Rule("clinic_name", "clinic name", true, "string|0,20"),

	new Rule("clinic_address", "Address", true, "string|0,50"),

	new Rule("clinic_address2", "Address line 2", false, "string|0,50"),

	new Rule("clinic_city", "city", true, "string|0,50"),

	new Rule("clinic_state", "State", false, "string|0,2"),

	new Rule("clinic_zip", "Zip Code", false, "zipcode")	

							);

// -->

</script>

<div id="container">
  <form name="patientSubs" id="patientSubs" action="index.php?action=submit_update_clinic_card" method="post">
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
      <td width="84%"  align="left" valign="top"><div id="mainContent">
	  <!--  MAIN TABLE STARTS -->
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top" align="left">
			  <!--  INNER MAIN TABLE STARTS (5) ROWS STARTS  -->
			  <table width="100%" border="0" style="vertical-align:middle; width:700px; ">
                  <tr>
                    <td>
                    <table style="vertical-align:middle;width:700px;margin-top:18px;">
                        <tr>
                          <td style="width:400px;">
                          <div id="breadcrumbNav">
                            <a href="index.php?action=sysAdmin" >HOME </a> / <a href="index.php?action=accountAdminClinicList" >ACCOUNT</a> / <span class="highlight">UPDATE CREDIT CARD</span>
                          </div>
                          </td>
                          <td style="width:300px;">
                          <table border="0" cellpadding="5" cellspacing="0" style="float:right;margin-top:27px;">
                              <tr>
                                <td class="iconLabel" >&nbsp;</td>
                              </tr>
                          </table>
                    </td>
                        </tr>
                      </table></td>
                        </tr>
                      </table>
					</td>
                  </tr>
				  <!--  1st ROW ENDS  -->
                
				  <!--  2nd ROW ENDS  -->
                  <tr>
                    <td class="error">&nbsp;<!error></td>
                  </tr>
				  <!--  3rd ROW ENDS  -->				  
                  <tr>
                    <td>
					<div style="margin-top:3px;">
                        <!tabNavigation>
                      </div>
                    </td>
                   </tr>
                   <tr>
                        <td width="151" valign="top" class="toptitle" style='padding-left:10px;' ><!heading></td>
                        <td width="143" valign="top" class="toptitle" >&nbsp;</td>
                        <td width="146" valign="top" class="toptitle" >&nbsp;</td>
                        <td valign="top" class="toptitle" >&nbsp;</td>
                        <td valign="top" class="toptitle" >&nbsp;</td>
                   </tr>
                   <tr>
                    <td width="100%" colspan="5">  
                     <div> 
                      <table width="100%" border="0" cellspacing="2" cellpadding="2" style="border:1px solid #CCCCCC;">
                <!errorMessage>           
             <tr><td colspan="4" style="height:20px; color:#0069a0;; font-weight:bold ;padding-top:25px;">credit card detail</td></tr>
						  
                                          <tr>
            <td style="padding-top:15px" valign="top">Card Type</td>
            <td class="ct"  valign="top"><select name="cardType" tabindex="5" style="margin-top:10px;" >
                <option value="">Card Type </option>
                <option value="Visa" <!retainVisaType>>Visa</option>
                <option value="MasterCard" <!retainMasterType>>Master Card</option>
            </select><br><!ctypeValid></td>
            <td style="padding-top:15px" valign="top">Card No.</td>
            <td  valign="top"><input name="cardNumber" type="text" AUTOCOMPLETE=OFF maxlength="16" tabindex="6" 
                value="<!retainedcardNumber>" style="margin-top:10px;" /><!ccno><br><!cnumberValid></td>
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

     <td colspan="4" class="yellow-btn" style="padding-top:40px;" align="center"><input type="submit" name="Submit" value="Update" />   </td>
     
             
                                                                                                                                                     
        </tr>   

                      </table>
                      </div>
                      </td>
                  </tr>
				  <!--  4th ROW ENDS  -->
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
				  <!--  5th ROW ENDS  -->
                </table>
				<!--  INNER MAIN TABLE STARTS (5) ROWS ENDS -->
          <!--  MAIN TABLE ENDS  -->
        </div><!-- div ( mainContent ) Ends --></td>
    </tr>
    <tr>
      <td colspan="2">
      <div id="footer">
          <!footer>
        </div></td>
    </tr>
  </table>
   <input type="hidden" name="option" value="update" />
   <input type="hidden" name="clinic_id" value="<!clinic_id>" />
   <input type="hidden" name="subaction" value="<!subaction>"/>
   <input type="hidden" name="cardPayment" value="<!cardPayment>"/>
   
  </form>
</div><!-- div ( container ) Ends -->
<script language="JavaScript1.2">mmLoadMenus();</script>
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