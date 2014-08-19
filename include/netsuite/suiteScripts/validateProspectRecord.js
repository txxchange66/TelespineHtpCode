

function validateProspect(){
	
	if((nlapiGetFieldValue('entitystatus')) == '11')
	{
	
		//  +++++++++++++++++++++++++++++++++++
		
		/**
		 * Create Opportunity Record (Ok)
		 */
		
		/*
		var record_contact = nlapiCreateRecord('opportunity');
		record_contact.setFieldValue('title', 'Test SS Opportunity');
		record_contact.setFieldValue('entity', '794');
		record_contact.setFieldValue('entitystatus', '11');
		var Contact = nlapiSubmitRecord(record_contact, true);
		*/
		
		//  +++++++++++++++++++++++++++++++++++
		
		/**
		 *  Creating Contact Record (Ok)
		 *  No need not to Create Contact Record by suite script, it is created manually by users
		 */
		
		/*
		var record_contact = nlapiCreateRecord('contact');
		record_contact.setFieldValue('firstname', 'RajeshV');
		record_contact.setFieldValue('lastname', 'Vverma');
		record_contact.setFieldValue('email', 'rajesh@hytechpro.com');
		
		## Trying to associate contact record with customer, but not getting success so comment it.
		//record_contact.setFieldValue('entityid', '794');
		//record_contact.setFieldValue('role', '3');
		//record_contact.setFieldValue('owner', '794');
		
		var Contact = nlapiSubmitRecord(record_contact, true);
		*/
		//  ++++++++++++++++++++++++++++++++++++
		
		var count_add = nlapiGetLineItemCount('addressbook');
		var count_cc = nlapiGetLineItemCount('creditcards');
		var count_contact = nlapiGetLineItemCount('contactroles'); // ok, it counts contacts record of customer
		alert(count_contact);
		
		/*
		// Search all contact whose email id == rajesh@hytechpro.com
		
		var filters = new nlobjSearchFilter( 'email', '', 'contains', 'rajesh@hytechpro.com', null); // ok
		var searchresults = nlapiSearchRecord( 'contact', null, filters, null ); // ok
		for ( var i = 0; searchresults != null && i < searchresults.length; i++ )
		{
			var searchresult = searchresults[ i ];
			var record2 = searchresult.getId( );
			alert(record2);
		}
		
		*/
		
		
		/*
		var record1 = nlapiLookupField('contact', '812', 'email'); // ok
		alert(record1);
		alert(record1);
		*/
		
		if(count_add >= 1){
			var a = nlapiGetFieldValue('entityid'); // not ok
			var a2 = nlapiGetFieldValue('externalid'); // not ok
			var a3 = nlapiGetRecordId(); // OK, Returns internal Id
			var a4 = nlapiGetLineItemValue('defaultBilling');
			alert(a+','+a2+','+a3+' ,'+a4);
			
			var b  = nlapiGetLineItemValue('addressbook', 'city', 1);  //ok
			var b1 = nlapiGetFieldValue('phone'); //ok
			var b2 = nlapiGetLineItemValue('addressbook', 'state', 1);  //ok
			var b3 = nlapiGetLineItemValue('addressbook', 'country', 1); //ok
			var b4 = nlapiGetLineItemValue('addressbook', 'addr1', 1); // ok
			var b5 = nlapiGetLineItemValue('addressbook', 'addr2', 1); // ok
			var b6 = nlapiGetLineItemValue('addressbook', 'addrtext', 1); // ok
			
			alert(b+', '+b1+','+b2+','+b3+','+b4+','+b5+','+b6);
			
			if(count_cc >= 1){
				// Executes this block if credit card information exist.
				var c  = nlapiGetLineItemValue('creditcards', 'ccnumber', 1);  //ok
				var c2 = nlapiGetLineItemValue('creditcards', 'account', 1);  ### not ok
				var c3 = nlapiGetLineItemValue('creditcards', 'ccname', 1); // ok
				var c4 = nlapiGetLineItemValue('creditcards', 'ccexpiredate', 1); // ok
				var c5 = nlapiGetLineItemValue('creditcards', 'ccdefault', 1); //ok
				alert(c+','+c2+','+c3+','+c4+','+c5);
			}
			
			// Check for contact records
			if(count_contact >= 1){
				var d  = nlobjSubList('contactroles');
				alert(d);
			}
		}
		
		
		if(count_add < 1){
			alert('Please enter shipping and billing address.');
			return false;
		}else if(count_cc < 1){
			alert('Please enter credit card details.');
			return false;
		}else if(count_contact < 1){
			alert('Please create a contact record & associate it with customer record.');
			return false;  // This statement terminate the script.
		}else{
			alert('Result Success');
			return true;
		}
		
	}else{
		alert('Status is not Trial Period');
		return true;
	}
}
