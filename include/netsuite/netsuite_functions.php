<?php

/**
 * 
 * Copyright (c) 2008 Tx Xchange
 * 
 * This Class is basically to call the netsuite library classes or functions and serialize it to get result..
 * Application uses these functions to call services of netsuite.
 * This is adviced that don't call library functions directly in your application.
 * 
 * // necessary classes
 * require_once('nusoap.php');
 * require_once('netsuite_library.php');
 * 
 */


// including classes
require_once('nusoap.php');
require_once('netsuite_library.php');



// Class starts
class cc {
	/**
	 * Netsuite client (Creates if login is successfull in netsuite.)
	 *
	 * @var object
	 * @access private
	 */
	public $client = '';
	
	
	/**
	 * Used to store the result array returned by netsuite after calling a service.
	 * It also stores the customize messages.
	 *
	 * @var array
	 * @access public
	 */
	public $result = array();
	
	
	
	/**
	 * Constructor
	 * Do Login into Netsuite and Set cookie.
	 *
	 * @return object
	 * @access public
	 */
	function cc()  ## -- Constructur
	{
		$this->client = mylogin();
		if( (!isset($this->client)) && (empty($this->client)) ) {
			// if login is not successfull.
			echo "Login Failed";
			exit;
		}
	}


	/**
	 * Returns a Single Row of specified type.
	 *
	 * @param integer $TypeID - CustomRecordType Id
	 * @param integer $RecordID - Records internal Id
	 * @access public
	 */
	function getRecord($TypeID, $RecordID)
	{
		$cust_record_ref = new CustomRecordRef($TypeID, $RecordID);
		$this->result = get($this->client, $cust_record_ref);
	}
	
	

	
	/**
	 * Function to get all records to custom type without any search criteria
	 *
	 * @access public
	 */
	function getAllRecord()
	{
		$getall = new nsCustomRecordType('ccinfo');
		$this->result = getAll($this->client, $getall);
	}
	
	
	
	/**
	 * Searching Records on Certain Critaria.
	 *
	 * @param integer $recordTypeInternalId
	 * @access public
	 */
	function searchRecord($recordTypeInternalId)
	{
		$search = new nsSearchRecord($recordTypeInternalId);
		$this->result = search($this->client, $search);
	}
	
	
	/**
	 * Used to show customize message.
	 *
	 * @param string $msg
	 */
	function showMessage($msg=''){
		$this->result['customMsg'] = $msg;
	}
	
	
	/**
	 * Used to show result, fault and error returned by netsuite.
	 *
	 * @access public
	 */
	function showResult(){
		if($this->result == ''){
			echo 'Please call any operation'; exit;
		}
		if($this->client->fault) {
		    echo '<h2>Fault</h2><pre>' . $this->result .'</pre>';
		} else {
		    $err = $this->client->getError();
		    if ($err) {
		        echo '<h2>Error</h2><pre>' . $err . '</pre>';
		    } else {
		        echo '<h2>Result</h2>';
		        print_r($this->result);
		    }
		}
		echo "<H1>REQUEST XML</H1>".htmlspecialchars($this->client->request, ENT_QUOTES)."<br/><br/>";
		echo "<H1>RESPONSE XML</H1><pre>".htmlspecialchars($this->client->response, ENT_QUOTES)."</pre><br/><br/>";
	}
	
	
	/**
	 * Used to returned the result array.
	 *
	 * @return array.
	 * @access public
	 */
	function getResult(){
		return $this->result;
	}
	

	
	/**
	 * Used to Create or edit a customer record.
	 *
	 * @param array $arr
	 * @param internalId $internalIdCustomer - pass in case of edit only
	 * @return object type
	 * @access public
	 */
	function AddUpdateCustomerRecord($arr, $internalIdCustomer='')
	{
		$result2 = 0;
		$rc = new nsAddUpdateCustomerRecord($arr, $internalIdCustomer);
		if((!empty($internalIdCustomer)) && ($internalIdCustomer > 0)) {
			$add_cust = update($this->client, $rc);
		}else{
			$add_cust = add($this->client, $rc);
		}
		
		$this->result['arr_CustomerRecord'] = $add_cust;
		
		$resultArray = getValueFromArray($add_cust, array('!isSuccess','!internalId'));
		$nsStatus = $resultArray['!isSuccess'];
		$newInternalIdCustomer = $resultArray['!internalId'];
		// This block runs when Customer Record is created Successfully and returns internalId
		
		if(($nsStatus == "true") && ($newInternalIdCustomer >0) && (!empty($newInternalIdCustomer))){
			$result2 = $this->AddUpdateContactRecord($arr['contactArr'], '', $newInternalIdCustomer);
		}else{
			$this->showMessage('Problem occured while Creating Customer Record, Please try later!');
		}
		return $result2;
	}

	
	/**
	 * Searching for records based on some criteria.
	 *
	 * @param integer $internalID
	 * @access public
	 */
	function SearchCustomerRecord($internalID='')
	{
		$rc = new nsSearchCustomerRecord($internalID);
		$this->result = search($this->client, $rc);
	}

	
	/**
	 * Searching Customer Record with joining with other Recordtypes.
	 *
	 * @param integer $internalID
	 * @access public
	 */
	function JoinSearchCustomerRecord($internalID='')
	{
		$rc = new nsJoinSearchCustomerRecord($internalID);
		$this->result = search($this->client, $rc);
	}
		
	
	/**
	 * To get a Single Customer record of that particulat internalID.
	 *
	 * @param integer $internalID
	 * @access public
	 */
	function GetCustomerRecord($internalID='')
	{
		$rc = new nsGetCustomerRecord($internalID);
		$this->result = get($this->client, $rc);
	}
	
	
	/**
	 * Returns all records of customer Type
	 *
	 * @param string $recordType
	 * @access public
	 */
	function getAllCustomerRecord($recordType){
		$rc = new nsGetAllCustomerRecord($recordType);
		$this->result = getAll($this->client, $rc);
	}
	
	
	/**
	 * Used to create or edit opportunity record.
	 *
	 * @access public
	 */
	function AddUpdateOpportunityRecord() {
		$rc = new nsAddUpdateOpportunityRecord($recordsInternalID='');
		if((!empty($internalID)) && isset($internalID) && ($internalID > 0)) {
			$this->result = update($this->client, $rc);
		}else{
			$this->result = add($this->client, $rc);
		}
	}
	
	
	/**
	 * Function to get all Opportunity Records of a specific Customer Record by passing internalId of Customer Record.
	 *
	 * @param integer $internalIdCustomer
	 * @access public
	 */
	function SearchOpportunityRecord($internalIdCustomer='')
	{
		if((!empty($internalIdCustomer)) && ($internalIdCustomer > 0)){
			$search = new nsSearchOpportunityRecord($internalIdCustomer);
			$this->result = search($this->client, $search);
		}else{
			$this->showMessage('Please pass internal Id of Customer for which you are searching Opportunity Records');
		}
	}
	
		
	/**
	 * Function to get all Contact Records of a specific Customer Record by passing internalId
	 *
	 * @param integer $internalIdCustomer
	 * @access public
	 */
	function SearchContactRecord($internalIdCustomer)
	{
		$search = new nsSearchContactRecord($internalIdCustomer);
		$this->result = search($this->client, $search);
	}
	
	
	/**
	 * Used to associate contact record with customer record.
	 *
	 * @param integer $internalIdCustomer
	 * @param integer $internalIdContact
	 * @access public
	 */
	function AssociateContactToCustomer($internalIdCustomer, $internalIdContact){
		$rc = new nsAssociateContactToCustomer($internalIdCustomer, $internalIdContact);
		$this->result = attach($this->client, $rc);
	}
	
	
	/**
	 * Create new of Edit existing Contact Record
	 *
	 * @param array $arr
	 * @param integer type $internalIdContact in case of Updating or Editing Contact Record
	 * @param integer $internalIdCustomer
	 * @return object
	 * @access public
	 */
	function AddUpdateContactRecord($arr, $internalIdContact='', $internalIdCustomer='')
	{
		$isSuccess = 0;
		/**
		 * Creating Contact Record
		 */
		$rc = new nsAddUpdateContactRecord($arr, $internalIdContact, $internalIdCustomer);
		if((!empty($internalIdContact)) && ($internalIdContact > 0)) {
			$result = update($this->client, $rc);
		}else{
			$result = add($this->client, $rc);
		}
		$this->result['arr_ContactRecord'] = $result;
		
		// Run if new Contact Record is created ie. $internalIdContact == blank
		if(empty($internalIdContact)) {
			// Associate Contact Record with Customer Record and define their Role
			$resultArray = getValueFromArray($result, array('!isSuccess','!internalId'));
			$nsStatus = $resultArray['!isSuccess'];
			$newInternalIdContact = $resultArray['!internalId'];
			
			// This block runs when Contact Record is created Successfully and returns internalId
			if(($nsStatus == "true") && (!empty($newInternalIdContact)) && ($newInternalIdContact > 0)){
				
				// Run if Customer is Exist
				if((!empty($internalIdCustomer)) && ($internalIdCustomer > 0))
				{
					$rc2 = new nsAssociateContactToCustomer($internalIdCustomer, $newInternalIdContact);
					$result2 = attach($this->client, $rc2);
					$this->result['arr_AssociateContactToCustomer'] =$result2;
					$resultArray2 = getValueFromArray($result2, array('!isSuccess','!internalId'));
					$nsStatus2 = $resultArray2['!isSuccess'];
					
					// This block runs when Successfully Associate a Contact Record with Customer Record
					if($nsStatus2 == "true"){
						// Function to create a Opportunity Record
						$rc3 = new nsAddUpdateOpportunityRecord($internalIdCustomer);
						$result3 = add($this->client, $rc3);
						$this->result['arr_OpportunityRecord'] = $result3;
						
						$resultArray3 = getValueFromArray($result3, array('!isSuccess'));
						$nsStatus3 = $resultArray3['!isSuccess'];
						
						// This block runs when Successfully Associate a Contact Record with Customer Record
						if($nsStatus2 != "true"){
							
							### Deleting Contact Record
							$this->DelRecord($newInternalIdContact, 'contact');
							
							### Deleting Customer Record
							$this->DelRecord($internalIdCustomer, 'customer');
							$this->showMessage('Problem occured while Creating Opportunity Record, Please try later!');
							
						}else{
							$isSuccess = 1;
						}
					}else{
						$this->showMessage('Problem occured while Associating Contact Record with Customer Record');
					}
				}
			}else{
				
				// Delete Customer Record and show messge
				$this->DelRecord($internalIdCustomer, 'customer');
				$this->showMessage('Problem occured while Contact Record is Created, Please try later!');
			}
		}
		return $isSuccess;
		
	} // Function AddUpdateContactRecord() Ends
	
	
	/**
	 * Used to delete record of specified type.
	 *
	 * @param internalId $internalIdCustomer
	 * @param string $recordType
	 * @access public
	 */
	function DelRecord($internalIdCustomer, $recordType){
		
		$rc = new nsDeleteRecord($internalIdCustomer, $recordType);
		$result = delete($this->client, $rc);
		$this->result['arr_DeleteCustomerRecord'] = $result;
	}
	

}// --Class 'cc' Ends Here--

?>