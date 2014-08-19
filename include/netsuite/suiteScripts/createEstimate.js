
// Important Points
// ----------------
// 1). Alert statement exits the program
// NonInventorySaleItem

function createEstimate(){
	var id = nlapiGetRecordId(); // Returns internal ID of Current Record opened
	var record1 = nlapiLoadRecord('prospect', id);
	var phone1 = record1.getFieldValue('phone');
	//var countAddress = record1.getLineItemCount('addressbook');
	var countCreditCard = record1.getLineItemCount('creditcards'); // Count how many credit cards details entered in current record.
	//var creditCardList = record1.getAllLineItemFields('creditcards');
	var creditCardNo = record1.getLineItemValue('creditcards', 'ccnumber', 1);  // Pick credit card number from first row of list
	//alert(creditCardNo);
	//alert(creditCardList);
	//alert(id+' '+phone1+' CountCreditCards='+countCreditCard);
	if(parseInt(countCreditCard) >= 1){
		// Run this block when at least one credit card details found
		var fromRecord = 'opportunity';
		var fromId = 10; // Id of the Prospect Record
		var toRecord = 'salesorder';
		/* Converting opportunity Record to SalesOrder */
		var record = nlapiTransformRecord(fromRecord, fromId, toRecord);
		//var salesOrder = nlapiCreateRecord('salesorder');
		//salesOrder.setFieldValue('entity', id);
		//salesOrder.setFieldValue('opportunity', '');
		//record.setFieldValue('orderStatus', 'pendingFulfillment');
		record.setFieldValue('memorized', 1);
		//var fieldValue=salesOrder.getFieldValue('entity');
		//alert(fieldValue);  
		//salesOrder.insertLineItem('item', '4');
		//alert(record);
		//salesOrder.setLineItemValue('item', 'item', '4', '10');
		//salesOrder.setLineItemValue('item', 'item', '2', '4');
		//salesOrder.setLineItemValue('item', 'quantity', '2', '20');
		//salesOrder.setLineItemValue('item', 'price', '2', '');
		var salesOrderId = nlapiSubmitRecord(record, true);
		//alert("Internal ID of Created Sales Order Id = " + salesOrderId);
	}
}