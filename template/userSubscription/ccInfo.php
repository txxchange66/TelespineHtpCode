
<script language="JavaScript" src="js/trialPeriodAddressPopulate.js"></script>
<script language="JavaScript" src="js/validateform.js"></script>
<script language="JavaScript" src="js/validateform2.js"></script>


<SCRIPT LANGUAGE="JavaScript">
<!--
function isValidCreditCard(type, ccnum) {
   // VISA
   if (type == "5") {  
      // Visa: length 16, prefix 4, dashes optional.
      var re = /^4\d{3}-?\d{4}-?\d{4}-?\d{4}$/;
   // MASTER CARD
   } else if (type == "4") {  
      // Mastercard: length 16, prefix 51-55, dashes optional.
      var re = /^5[1-5]\d{2}-?\d{4}-?\d{4}-?\d{4}$/;
   // DISCOVER
   } else if (type == "3") {
      // Discover: length 16, prefix 6011, dashes optional.
      var re = /^6011-?\d{4}-?\d{4}-?\d{4}$/;
   // AMERICAN EXPRESS
   } else if (type == "6") {
      // American Express: length 15, prefix 34 or 37.
      var re = /^3[4,7]\d{13}$/;
   // DINNERS CLUB
   } else if (type == "2") {
      // Diners: length 14, prefix 30, 36, or 38.
      var re = /^3[0,6,8]\d{12}$/;
   }
   if (!re.test(ccnum)) return false;
   // Remove all dashes for the checksum checks to eliminate negative numbers
   ccnum = ccnum.split("-").join("");
   // Checksum ("Mod 10")
   // Add even digits in even length strings or odd digits in odd length strings.
   var checksum = 0;
   for (var i=(2-(ccnum.length % 2)); i<=ccnum.length; i+=2) {
      checksum += parseInt(ccnum.charAt(i-1));
   }
   // Analyze odd digits in even length strings or even digits in odd length strings.
   for (var i=(ccnum.length % 2) + 1; i<ccnum.length; i+=2) {
      var digit = parseInt(ccnum.charAt(i-1)) * 2;
      if (digit < 10) { checksum += digit; } else { checksum += (digit-9); }
   }
   if ((checksum % 10) == 0){ 
   	//alert('TRUE');
   	return true; 
   }else{
   	//alert('FALSE');
   	return false;
   }
}
// -->
</script>



<style type="text/css">
<!--
.style1 {
	font-size: 16px;
	font-weight: bold;
}
-->
</style>

<div class="adminSubHeadingblue">
	Tx Xchange 30 Day Free Trial Signup
	<div class="error" style="font-size:11px;"><!error></div>
</div>
<!whiteSpace14>

<div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-style:solid; border-width:thin;">
  <tr>
    <td><form id="form1" name="form1" method="post" action=""  onsubmit="return validate_form(this);">
	<table width="100%" border="0" cellspacing="1" cellpadding="1">
      <tr>
        <td colspan="7" style="padding-left:25px;"><!whiteSpace14>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut          laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation
          ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</td>
        </tr>
      <tr>
        <td colspan="7" style="padding-left:25px;"><!whiteSpace14>
          <span class="style1"><u>Billing Information</u></span><!whiteSpace14></td>
      </tr>
      <tr>
        <td width="12%">&nbsp;</td>
        <td width="17%">&nbsp;</td>
        <td width="18%">&nbsp;</td>
        <td width="13%">&nbsp;</td>
        <td colspan="3">&nbsp;</td>
        </tr>
      <tr>
        <td colspan="4" align="left" valign="top">
		<div>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
      <tr>
        <td width="22%">&nbsp;</td>
        <td width="36%">&nbsp;</td>
        <td width="42%">&nbsp;</td>
      </tr>
	  <tr>
        <td colspan="7"><!whiteSpace5></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>Type of Card : </td>
        <td>
          <select name="cardType" tabindex="1">
		  <!cardTypeOptions>
		  </select>		  </td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>Card Holder Name   : </td>
        <td><input type="text" name="cardName" tabindex="2" value="<!cardName>" /></td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>Card Number : </td>
        <td><input type="text" name="cardNo" tabindex="3" maxlength="16" value="<!cardNo>" /></td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>Expiration Date : </td>
        <td><select name="expMonth" tabindex="4">
			<!monthOptions>
		</select>
		
		<select name="expYear" tabindex="5">
		<!yearOptions>
		</select>		</td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
    </table>
		</div>
		</td>
        <td colspan="3" align="left" valign="top">
		<div id="billingadd">
		  <!billingAddressPage>
		</div>		</td>
      </tr>
      
      <tr>
        <td colspan="7"><div style="padding-left:40px; padding-right:40px;">
		At the end of your 30 day trial you will automatically be billed $XXX.XX per subscription. If you
cancel your membership before your trial expires your card will not be charged.
		</div></td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td width="2%">&nbsp;</td>
        <td width="0%">&nbsp;</td>
        <td width="38%">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td></td>
        <td>
		  <div align="right" style="padding-right:20px;">
		    <input type="hidden" name="option" value="update" />
		    <input type="submit" name="Submit" value="Next >>" tabindex="11" />
		      </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
	</form>
	</td>
  </tr>
</table>
</div>

<script>
<!--
	window.load = document.form1.cardType.focus();
-->
</script>

<script language="JavaScript" type="text/javascript">
//You should create the validator only after the definition of the HTML form
  var frmvalidator  = new Validator("form1");
  frmvalidator.addValidation("cardType","req","Please enter Card Type");
  
  frmvalidator.addValidation("cardName","req","Please enter Card Holder Name");
  frmvalidator.addValidation("cardName","minlen=5", "Min length for Holder Name is 5");
  frmvalidator.addValidation("cardName","maxlen=30", "Max length for Holder Name is 30");
  frmvalidator.addValidation("cardName","alnumhyphenspace");
  
  frmvalidator.addValidation("cardNo","req","Please enter Credit Card Number");
  frmvalidator.addValidation("cardNo","maxlen=16", "Max length for credit card number is 16 digit");
  frmvalidator.addValidation("cardNo","minlen=16", "Min length for credit card number is 13 digit");
  frmvalidator.addValidation("cardNo","num", "Please enter only numeric character.");
  
  frmvalidator.addValidation("expMonth","req","Please select expiry month");
  
  frmvalidator.addValidation("expYear","req","Please selecte expiry year");
  
  frmvalidator.addValidation("add1_bill","req","Please enter address line 1");
  frmvalidator.addValidation("add1_bill","minlen=5", "Min length for address line 1 is 5");
  frmvalidator.addValidation("add1_bill","maxlen=50", "Max length for address line 1 is 50");
  frmvalidator.addValidation("add1_bill","alnumhyphenspace");
  
  frmvalidator.addValidation("add2_bill","req","Please enter address line 2");
  frmvalidator.addValidation("add2_bill","minlen=5", "Min length for address line 2 is 5");
  frmvalidator.addValidation("add2_bill","maxlen=50", "Max length for address line 2 is 50");
  frmvalidator.addValidation("add2_bill","alnumhyphenspace");
  
  frmvalidator.addValidation("city_bill","req","Please enter city name");
  frmvalidator.addValidation("city_bill","minlen=2", "Min length for city name is 2");
  frmvalidator.addValidation("city_bill","maxlen=50", "Max length for city name is 20");
  frmvalidator.addValidation("city_bill","alnumhyphenspace");  
  
  frmvalidator.addValidation("state_bill","req","Please select state name");
  
  frmvalidator.addValidation("zip_bill","req","Please enter zip code");
  frmvalidator.addValidation("zip_bill","maxlen=5", "Max length for zip code is 5 digit");
  frmvalidator.addValidation("zip_bill","minlen=5", "Min length for zip code is 5 digit");
  frmvalidator.addValidation("zip_bill","num", "Please enter only numeric character in zip code.");  
</script>
 
