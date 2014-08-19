 if(window.console){if(!console.trace) {console.trace = console.log;}}
'use strict';

function $slideCcInfoDrawer () {
		if(this.checked){
				$('#billingCCDiv').slideDown(800);
				$('#billingPromoDiv').slideUp(800);
                                $('#promocode').prop('required',false);
		} else {
				$('#billingCCDiv').slideUp(800);
				$('#billingPromoDiv').slideDown(800);
		}
		//for tests
		return true;
}

function $slidePromoInfoDrawer(){
		if(this.checked){
				$('#billingCCDiv').slideUp(800);
				$('#input-radio-cc').prop('checked', false);
				$('#billingPromoDiv').slideDown(800);
                                $('#promocode').prop('required',true);
		}else{
				$('#billingCCDiv').slideDown(800);
				$('#input-radio-cc').prop('checked', true);
				$('#billingPromoDiv').slideUp(800);
		}
		//for tests
		return true;
}

//Get purchase price
function getPrice(priceType,discount){
   
		var regularPrice = $('#cardPayment').val();
		var salePrice = Number(regularPrice)-Number(discount);
                 
		var purchasePrice = {};
		//Different type of prices, switch
		switch(priceType){
				case 'price-regular' :
						purchasePrice = regularPrice;
				break;

				case 'price-discount-1' :
						purchasePrice = salePrice.toFixed(2);
				break;
		}
		return purchasePrice;
}

//Sets the text value of #pricetag element
function updatePriceEl(value){
		var $pricetag = $('#pricetag');
		$pricetag.text('$' + value);
		return $pricetag.text();
}

//Check for promo code validity
function $checkIfValidPromocode(){
		 if(window.console){if(!console.trace) {console.trace = console.log;}}
		/*
		If the promo code is valid, update the price, add logic here via regex or API

		Example :*/
		var discountCodeInputValue = $('#discount_coupon').val();
		
		//this API call would return a JSON response with a falid attribute (string) = "true"|"false"
		var discountCodeCheck = $.post( "index.php?action=checkdiscountcode", { discountCodeToCheck: discountCodeInputValue } )
				.done(function(data) {
					if (data === 'FALSE'){
                                            //console.log('invalid coupon code');
						$('#discountCodeMatchError').slideDown().delay(3000).slideUp('slow');
                                                updatePriceEl( getPrice('price-regular',0));
						
					} else {
						updatePriceEl( getPrice('price-discount-1',data));
					}
					return data.valid;
				})
				.fail(function(data) {
					promoCodeIsValid = 'error';
					//alert( "error validating discount coupon" );
				});
						

		//Demo only
		
}

//Check for sign-up code validity
function $checkIfValidSignupCode(){
 if(window.console){if(!console.trace) {console.trace = console.log;}}
		//Reference $checkIfValidPromocode() for logic applying to validating the code with a webservice/API
var signUpCodeInputValue = $('#promocode').val();
var signupCodeIsValid=true;
		
		//this API call would return a JSON response with a falid attribute (string) = "true"|"false"
		var promoCodeCheck = $.post( "index.php?action=checksignupcode", { signupCodeToCheck: signUpCodeInputValue } )
				.done(function(data) {
                            		if (data === 'TRUE'){
						$('#billingCCDiv').remove();
                                                return signupCodeIsValid;
					} else {
						//console.log('invalid coupon code');
                                                $('#signupCodeMatchError').slideDown().delay(3000).slideUp('slow');
					}
					return data;
				})
				.fail(function(data) {
					signupCodeIsValid = 'error';
                                        $('#signupCodeMatchError').slideDown().delay(3000).slideUp('slow');
					//alert( "error validating discount coupon" );
				});
}

function $checkPwEquality(){
		var $pw_input = $('#password');
		var $pwConfirm_input = $('#confirmNewPassword');
		var firstPwVal = $pw_input.val();
		var confirmPwVal = $pwConfirm_input.val();
		var isEqual = (firstPwVal === confirmPwVal) ? true : false;
		//if invalid
		if (!isEqual){
				$('#passwordMatchError').slideDown().delay(3000).slideUp('slow');
		}
		return (isEqual);
}


//Procedures for submiting the form upon a successful validation.
function $submitBillingForm(){
     if(window.console){if(!console.trace) {console.trace = console.log;}}
		//Add any additional validation logic and payment gateway handlers
		console.log('Submit billing form if valid');
		//for tests
		return true;
}


/*
Extends inForm 
*/
function isPromoChecked(methodName,obj){
     if(window.console){if(!console.trace) {console.trace = console.log;}}
		console.log('Checking function isPromoChecked '+jQuery('#usePromoCodeTrue').is(':checked'));
		return jQuery('#usePromoCodeTrue').is(':checked');
}

function isBillingChecked(methodName,obj){
     if(window.console){if(!console.trace) {console.trace = console.log;}}
		console.log('Checking function isBillingChecked '+jQuery('#usePromoCodeFalse').is(':checked'));
		return jQuery('#usePromoCodeFalse').is(':checked');
}



//DOM ready closure
$(function(){
 if(window.console){if(!console.trace) {console.trace = console.log;}}
		var $billing_radio = $('#input-radio-cc');
		var $promo_checkbox = $('#input-checkbox-promo');
		var $submit_btn = $('input[type="submit"]');
		var $discount_input = $('#discount_coupon');
		var $signupcode_input = $('#promocode');
		var formId = 'billing-form';

		var $pwConfirm_input = $('#confirmNewPassword');

		//Page Load Events
		$.gritter.removeAll();
		//inForm.init(formId,'fieldError');
		updatePriceEl( getPrice('price-regular',0) );

		//Event Binding
		$billing_radio.on('change', $slideCcInfoDrawer);
		$promo_checkbox.on('change', $slidePromoInfoDrawer);
		$submit_btn.on('click', $submitBillingForm);
		
		$discount_input.on('blur', $checkIfValidPromocode);
		$signupcode_input.on('blur', $checkIfValidSignupCode);

		$pwConfirm_input.on('blur', $checkPwEquality);
});


