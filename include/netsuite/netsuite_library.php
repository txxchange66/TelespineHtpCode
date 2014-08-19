<?php


/**
 * 
 * Copyright (c) 2008 Tx Xchange
 * 
 * 
 * This Class includes the functionality of creating, edit, delete customer, contact, opportunity records.
 * This function are basically make Soap compatible request and call the services of netsuite.
 * It includes some utility functions also.
 * 
 * // necessary classes
 * require_once('nusoap.php');
 * 
 */


// including class
require_once('nusoap.php');


/*
	$soapbasic = new nsSoapval('q1:customerJoin', 'CustomerSearchBasic', array($soapName), '', $ns2);
	
	##  NetSuite WSDL Path --> https://webservices.netsuite.com/wsdl/v2_6_0/netsuite.wsdl
	##  array('xsi:type'=>'q4:CustomRecordSearch', 'xmlns:q4'=>$ns3)  === array('xmlns'=>$ns1)
	
	$attr2 = array('xsi:type'=>'q2:CustomerSearchBasic', 'xmlns:q2'=>$ns2);
	$soapbasic = new nsSoapval('q1:customerJoin', 'CustomerSearchBasic', array($soapName), '', '');
	
*/

// Netsuite WS API classes
// Base class, we need to always serialize literally because nusoap does not handle our namespaces/types correctly


class nsSoapval extends soapval {

	/**
	 * calling function of super class (soapval).
	 */
  	function nsSoapval($name='soapval',$type=false,$value=-1,$element_ns=false,$type_ns=false,$attributes=false) {
  		parent::soapval($name,$type,$value,$element_ns,$type_ns,$attributes);
  	}


	/**
	 * serialize the literals.
	 * Always encode literally
	 *
	 */
	function serialize($use='literal')
	{
		return parent::serialize('literal');
	}

}

class nsRecordRef extends nsSoapval {
	/**
	* Ubiquitous RecordRef, used both to indicate a reference on a record, and as input to a "get" type operation.
	*
	* @param	String	$elementName REQUIRED The element level name that this RecordRef represents (eg "role" on login)
	* @param	String	$internalId REQUIRED You always need to know the internalId of the recordRef you are specifying
	* @param	String	$name OPTIONAL Name specified on RecordRef, in general this is a read parameter
	* @param	String	$type OPTIONAL Type specified on RecordRef, eg "customer" or "salesOrder" (see RecordType in core.xsd) use this if you are doing a get. Not needed during adds.
	* @param	String	$elementNamespace OPTIONAL The namespace of the owning element. EG "role" on login is defined in the Core namespace.
	* @access	public
	*/
	function nsRecordRef($elementName, $internalId,$name='',$type='', $elementNamespace='')
	{
		parent::nusoap_base();
		$this->name = $elementName;
		$this->type = 'RecordRef';
		$this->value = $name;
		$this->element_ns = $elementNamespace;

		$rr = array('xsi:type' => 'nsRecordRef:RecordRef', 'xmlns:nsRecordRef' => 'urn:core_2_6.platform.webservices.netsuite.com');
		if ($internalId != '')
			$rr['internalId'] = $internalId;
		if ($type != '')
		    $rr['type'] = $type;

		$this->attributes = $rr;
	}
}





class nsBaseRef extends nsSoapval {
	/**
	 * BaseRef Record
	 * Used to indicate the base record type.
	 * 
	 */
	function nsBaseRef(){
		$ns='urn:core_2_6.platform.webservices.netsuite.com';
		
		parent::nusoap_base();
		$this->name = 'baseRef';
		$this->type = 'BaseRef';
		$this->value = array();
		$this->element_ns = $ns;
		$this->type_ns = $ns;
		$rr = array('xsi:type' => 'q1:BaseRef', 'xmlns:q1' => 'urn:core_2_6.platform.webservices.netsuite.com');
		$this->attributes = $rr;
	}
}





class nsSearchRecord extends  nsSoapval {
	/**
	 * Getting all Records (rows) of Specified RecordType.
	 *
	 * @param integer $recordTypeInternalId
	 * @access public
	 */
	function nsSearchRecord($recordTypeInternalId)
	{
		parent::nusoap_base();
		$ns1 = 'urn:core_2_6.platform.webservices.netsuite.com';
		$ns2 = 'urn:customization_2_6.setup.webservices.netsuite.com';
		$ns3 = 'urn:common_2_6.platform.webservices.netsuite.com';
		
		$this->name = 'searchRecord';
		$this->value = array();
		$attr=array('xsi:type'=>'q1:CustomRecordSearch', 'xmlns:q1'=>$ns2);
		$this->attributes = $attr;
		
		$soapRecType = new nsSoapval('recType', '', '', '', '', array('xmlns'=>$ns3, 'internalId'=>$recordTypeInternalId));
		
		$soapSearchValue = new nsSoapval('searchValue', '', array(), '', '', array('xmlns'=>$ns1));
		$soapName = new nsSoapval('name', '', array($soapSearchValue), '', '', array('xmlns'=>$ns3, 'operator'=>'contains'));
		
		$soapbasic = new nsSoapval('q1:basic', '', array($soapRecType, $soapName), '', '');
		$this->value[] = $soapbasic;
	}	
	
}





class nsRecord extends nsSoapval {

	/**
	* Record object. You need of these to do writes ("update" and "add" operations)
	*
	* @param	String	$recordType REQUIRED What you are adding/updateing. See RecordType in core.xsd
	* @param	String	$internalId OPTIONAL If adding, required if updating
	* @param	associative_array	$fields REQUIRED This is the array of all your fields, including lists, etc.
	* @access	public
	*/
	function nsRecord($recordType, $internalId='', $fields)
	{
		parent::nusoap_base();
		$ns = lookupNamespace($recordType);
		$this->name = 'record';
		$this->type = 'Record';
		$this->value = array();
		$this->element_ns = '';
		$this->type_ns = $ns;

		$attr = array('xsi:type' => 'nsRecord:' . ucfirst($recordType), 'xmlns:nsRecord' => $ns);
		if ($internalId != '')
			$attr['internalId'] = $internalId;
		$this->attributes = $attr;

		foreach ($fields as $fieldName => $fieldValue)
		{
			$this->debug($fieldName . (is_a($fieldValue, 'soapval') ? (' is a soapval.' . $fieldValue->serialize()) : (' is NOT a soapval : ' . $fieldValue)));
			array_push($this->value, is_a($fieldValue, 'soapval') ? $fieldValue : new nsSoapval($fieldName, '', $fieldValue, $ns ));
		}
	}
}


class nsList extends nsSoapval {
	/**
	 * Used in getList operation is used to retrieve a list of one or more records by providing the uniqueids that identify those records.
	 *
	 * @param string $recordType - pass name of record type.
	 * @param string $listName
	 * @param array $listArray
	 * @access public
	 */
	function nsList($recordType, $listName, $listArray)
	{
		parent::nusoap_base();
		$ns = lookupNamespace(lcfirst($recordType) . ucfirst($listName) . 'List');
		$this->name = lcfirst($listName) . 'List';
		$this->type = ucfirst($listName) . 'List';
		$this->element_ns = $ns;
		$this->attributes = array('replaceAll' => 'false', 'xsi:type' => 'nsList:' . ucfirst($recordType) . ucfirst($listName) . 'List', 'xmlns:nsList' => $ns);

		$nsListArray = array();
		foreach ($listArray as $listEntry)
		{
			$nsListValue = array();
			foreach ($listEntry as $fieldName => $fieldValue)
			{
				array_push($nsListValue, new nsSoapval($fieldName, '', $fieldValue, $ns ));
			}
			array_push($nsListArray, new nsSoapval(lcfirst($listName), '', $nsListValue, $ns));
		}
		$this->value = $nsListArray;
	}
}


class nsEnum extends nsSoapval {

	/**
	* Record object. You need of these to do writes ("update" and "add" operations)
	*
	* @param	String	$enumType REQUIRED eg 'Country', 'SupportCaseStage' or 'AccountType'
	* @param	String	$enumValue REQUIRED The String value, not the '_' for everything but platform types : '_unitedStates', '_open' or '_accountsPayable'.
	* @param	String  $elementName OPTIONAL But you'll probably always want it. This is the name of the Record Element that has the enum as data (eg 'stage' for SupportCaseStage)
	* @param	String  $elementNamespace OPTIONAL But again you'll probably always want it. This is the namepace of the owning
	*						of the Record Element, for supportCase, it would be 'support...' while the ENUM's namespace is 'types.support...'
	* @access	public
	**/
	//       nsEnum('urn:coretypes', 'GetCustomizationType', 'customRecordType', '', '');
	function nsEnum($ns, $enumType, $enumValue, $elementName='', $elementNamespace='')
	{
		parent::nusoap_base();
		//$ns = lookupNamespace(ucfirst($enumType));
		$this->name = isset($elementName) ? $elementName : lcfirst($enumType);
		$this->type = ucfirst($enumType);
		$this->value = $enumValue;
		$this->element_ns = $elementNamespace;

		$this->attributes = array('xsi:type' => 'nsEnum:' . ucfirst($enumType), 'xmlns:nsEnum' => $ns);
	}
}


class nsSearchBasic extends nsSoapval {
	/**
	 * Used in search operation execute a search on a specific record type based on a set of search criteria.
	 *
	 * @param string $searchType
	 * @param string $searchFields
	 * @access public
	 */
	function nsSearchBasic($searchType, $searchFields)
	{
		parent::nusoap_base();
		$this->name = 'searchRecord';
		$this->type = 'SearchRecord';

		$this->attributes = array('xsi:type' => 'nsCommon:' . ucfirst($searchType), 'xmlns:nsCommon' => 'urn:common_2_6.platform.webservices.netsuite.com');

		$this->value = $searchFields;
	}
}

class nsSearchField extends nsSoapval {

	function nsSearchField($fieldType, $fieldName,  $operator, $searchValue='', $searchValue2='', $predefinedSearchValue='')
	{
		parent::nusoap_base();
		$nsCore = 'urn:core_2_6.platform.webservices.netsuite.com';
		$this->name = 'nsCommon:' . $fieldName;
		$this->type = '';

		$this->attributes = array('operator' => $operator, 'xsi:type' => 'nsCommon:' . ucfirst($fieldType), 'xmlns:nsCore' => $nsCore);

		$this->value = array(new nsSoapval('nsCore:' . 'searchValue', '', $searchValue));

	}
}

// --- Netsuite API methods ---


/**
 * Call getAll operation and serialize the output.
 *
 * @param object $port
 * @param object $record_type
 * @return array
 * @access public
 */
function getAll ($port, $record_type)
{
	$ns1 = 'urn:messages_2_6.platform.webservices.netsuite.com';
	$getAll = new nsSoapval('getAll','getAllRequest', array('getall' => $record_type), '', $ns1);
	$getAllMsg = $port->serializeEnvelope($getAll->serialize('literal'));

	return $port->send($getAllMsg, 'getAll');
}


/**
 * Call add operation and serialize the output.
 *
 * @param object $port
 * @param object $record_type
 * @return array
 * @access public
 */
function add ($port, $record)
{
	$ns1='urn:messages_2_6.platform.webservices.netsuite.com';

	$soapRecord = array();
	$add = new nsSoapval('add','AddRequest', array('add' => $record), '', $ns1);
	$addMsg = $port->serializeEnvelope($add->serialize('literal'));

	return $port->send($addMsg, 'add');
}


/**
 * Call attach operation and serialize the output.
 *
 * @param object $port
 * @param object $record_type
 * @return array
 * @access public
 */
function attach ($port, $record)
{
	$ns1='urn:messages_2_6.platform.webservices.netsuite.com';

	$soapRecord = array();
	$attach = new nsSoapval('attach','attachRequest', array('attach' => $record), '', $ns1);
	$attachMsg = $port->serializeEnvelope($attach->serialize('literal'));

	return $port->send($attachMsg, 'attach');
}


/**
 * Call update operation and serialize the output.
 *
 * @param object $port
 * @param object $record_type
 * @return array
 * @access public
 */
function update ($port, $record)
{
	$ns1='urn:messages_2_6.platform.webservices.netsuite.com';

	$soapRecord = array();
	$update = new nsSoapval('update','UpdateRequest', array('update' => $record), '', $ns1);
	$updateMsg = $port->serializeEnvelope($update->serialize('literal'));

	return $port->send($updateMsg, 'update');
}


/**
 * Call delete operation and serialize the output.
 *
 * @param object $port
 * @param object $record_type
 * @return array
 * @access public
 */
function delete ($port, $record)
{
	$ns1='urn:messages_2_6.platform.webservices.netsuite.com';

	$soapRecord = array();
	$delete = new nsSoapval('delete','DeleteRequest', array('delete' => $record), '', $ns1);
	$deleteMsg = $port->serializeEnvelope($delete->serialize('literal'));

	return $port->send($deleteMsg, 'delete');
}


/**
 * Call get operation and serialize the output.
 *
 * @param object $port
 * @param object $record_type
 * @return array
 * @access public
 */
function get ($port, $recordRef)
{
	$ns1='urn:messages_2_6.platform.webservices.netsuite.com';

	$soapRecord = array();
	$add = new nsSoapval('get','GetRequest', array('get' => $recordRef), '', $ns1);
	$addMsg = $port->serializeEnvelope($add->serialize('literal'));

	return $port->send($addMsg, 'get');
}


/**
 * Call search operation and serialize the output.
 *
 * @param object $port
 * @param object $record_type
 * @return array
 * @access public
 */
function search ($port, $recordRef)
{
	$ns1='urn:messages_2_6.platform.webservices.netsuite.com';

	$soapRecord = array();
	$searchh = new nsSoapval('search','searchRequest', array('search' => $recordRef), '', $ns1);
	$searchMsg = $port->serializeEnvelope($searchh->serialize('literal'));

	return $port->send($searchMsg, 'search');
}


/**
 * Returns searchbasic elements.
 *
 * @param object $port
 * @param object $searchRecord
 * @param boolean $pageSize
 * @param boolean $bodyFieldsOnly
 * @return array
 * @access public
 */
function searchBasic($port, $searchRecord, $pageSize=false, $bodyFieldsOnly=true)
{
	$ns1='urn:messages_2_6.platform.webservices.netsuite.com';
	$soapHeader = false;
	if (!$bodyFieldsOnly || $pageSize)
	{
		$soapHeader='<ns1:searchPreferences xmlns:ns1="urn:messages.platform.webservices.netsuite.com">';
		$soapHeader .= '<ns2:bodyFieldsOnly xmlns:ns2="urn:messages_2_6.platform.webservices.netsuite.com">' . ($bodyFieldsOnly ? 'true' : 'false') . '</ns2:bodyFieldsOnly>';
		if ($pageSize)
			$soapHeader .= '<ns3:pageSize xmlns:ns3="urn:messages_2_6.platform.webservices.netsuite.com">' . $pageSize . '</ns3:pageSize>';
	    $soapHeader .= '</ns1:searchPreferences>';
	}

	$search = new nsSoapval('search','SearchRequest', array('search' => $searchRecord), $ns1);
	$msg = $port->serializeEnvelope($search->serialize('literal'), $soapHeader );
	$result = $port->send($msg,'search');
	return $result;
}

// --- Utility Methods ---
/**
 * Convert first character into lower case letter.
 *
 * @param string $strIn
 * @return string
 * @access public
 */
function lcfirst($strIn)
{
	return strtolower(substr($strIn,0,1)) . substr($strIn,1);
}


/**
 * Returns corosponding namesspaces.
 *
 * @param string $objectName
 * @return string
 * @access public
 */
function lookupNamespace($objectName)
{
	$namespaceMap = array(
	'customer' => 'urn:relationships_2_6.lists.webservices.netsuite.com',
	'contact' => 'urn:relationships_2_6.lists.webservices.netsuite.com',
	'contactAddressbookList' => 'urn:relationships_2_6.lists.webservices.netsuite.com',
	'contactAddressbook' => 'urn:relationships_2_6.lists.webservices.netsuite.com',
	'country' => 'urn:types.common_2_6.platform.webservices.netsuite.com',
	'patient' => 'urn:relationships_2_6.lists.webservices.netsuite.com'
	);
	return $namespaceMap[lcfirst($objectName)];
}


/**
 * Looks the specific value into an array.
 *
 * @param string $toCheck
 * @param array $targetArray
 * @return boolean
 * @access public
 */
function in($toCheck, $targetArray)
{
	foreach ($targetArray as $i)
	{
		if ($i == $toCheck)
			return true;
	}
	return false;
}


/**
 * Used to login into netsuite to call webservices.
 *
 * @param string $port
 * @param string $email
 * @param string $password
 * @param integer $account
 * @param integer $role
 * @access public
 */
function login ($port, $email, $password, $account, $role)
{
	$ns='urn:core_2_6.platform.webservices.netsuite.com';
	$ns1='urn:messages_2_6.platform.webservices.netsuite.com';

	$soapemail = new nsSoapval('email', 'string',$email, $ns, '');
	$soappassword = new nsSoapval('password', 'string',$password, $ns, '');
	$soapaccount = new nsSoapval('account', 'string',$account, $ns, '');
	$soaprole = new nsSoapval('role', 'RecordRef',array('internalId' => $role),$ns,'');

	$ppt = new nsSoapval('passport', 'Passport', array($soapemail , $soappassword, $soapaccount, $soaprole), $ns, '');
	$login = new nsSoapval('login','LoginRequest', array('login' => $ppt), $ns1);
	$msg = $port->serializeEnvelope($login->serialize('literal'));

	return $port->send($msg,'login');
}


/**
 * Call login Api and set cookie.
 *
 * @return mixed
 * @access public
 */
function mylogin()
{
	$output = '';
	$client = new soapclientw('https://webservices.netsuite.com/services/NetSuitePort_2_6', false);
	
	$result = login($client, 'Mbarnes@txxchange.com', 'hytechpro66', '700337', '18');

	// if logged in successfully.
	if ($result['sessionResponse']['status']['!isSuccess'])
	{
		$cookieStr = 'Cookies:<br>';
		foreach ($client->cookies as $cookie)
		{
			$cookieStr .= 'Cookie Name: ' . $cookie['name'] . '<br>Cookie Value: ' .$cookie['value'] . '<br><br>';
			setcookie($cookie['name'], $cookie['value']);
		}
		
		$output = $client;
	}
	// if not logged in successfully.
	else{
		echo $client;
		echo "<br/>Result Array = "; print_r($result);
		echo "<br/>ResultStatus = ".$result['sessionResponse']['status']['!isSuccess'];
		echo "<br/><h1> Error Occured during Login into Netsuite (".gettype($output)."), Please Try Later!</h1><br/>";
		exit;
	}
	return $output;
}



/**
 *  Function to find a single value or list of values(Array) from source array
 * 	e.g., Capture the value and occurance of 'stage' field comes in source array
 *	@Param $sourceArray -- array() -- Single or Multidimensional array
 *	@Param $toFindValue -- Single value or an array(one dimensional)
 *	@Param $parentLevel -- Tells that the value which i have to search, found in which Level of Source Array
 *	@Param $parentLevel -- no need to pass as parameter when call this function
 *	@Param $parentLevel -- no need to pass as parameter when call this function
 *	Rest of two parameter need not to pass in function calling
 */
function getValueFromArray($sourceArray, $toFindValue='', $parentLevel='', $arr=array(), $level=0) {
	$returnValue = '';
	$levelFound = true;
	
	if((!empty($toFindValue)) && ($toFindValue != '')){
			foreach($sourceArray as $key => $value) {
        		if (is_array($value) && (count($value)>0)) {
        				$level++;
        				getValueFromArray($value, $toFindValue, $parentLevel, &$arr, $level);
        				$level--;
        		}else{
        			
        			//  Set value $levelFound either true or false after checking it is passed as parameter or not
        			
    				if((!empty($parentLevel)) && ($parentLevel != '')){
						if($parentLevel == $level){
							$levelFound = true;
						}else{
							$levelFound = false;
						}
					}
					
					//  If parentLevel is passing as parameter and found then block runs or if parentLevel is not passing as parameter then also block is running
					
					if($levelFound == true){
	        			if(is_array($toFindValue)){
	    					foreach($toFindValue as $value2){ // $toFindvalue array is like array('stage', 'name', 'roll') -- 1 dimensional
	    						if($key == $value2){
	    							$arr[$key] = $value;
	    						}
	    					}
	        			}else{
	        				
	        				//  $Searching Value is not array
	        				
		        			if($key == $toFindValue){
		        				$arr[$key] = $value;
		        			}
	        			}
					}
        		}
			}
		}
	
	if(count($arr) > 0){
		$returnValue = $arr;
	}
	return $returnValue;
}




/**
 * This function is to count the values of array
 *
 * @param array $arr
 * @access public
 */
function countArrayValues($arr){
	$return = 0;
	if(is_array($arr)){
		$secondArr = array();
		$count = count($arr);
		if($count >= 0){
			foreach($arr as $value){
				if(!empty($value)){
					$secondArr[] = $value;
				}
				$return = count($secondArr);
			}
		}
	}
	return $return;
}



/**
 * This function returns array with element having some values and remove empty elements
 *
 * @param array $arr
 * @param boolean $returnValue
 * @return array
 * @access public
 */
function returnArrayValues(&$arr, $returnValue=false){
	$return = array();
	if(is_array($arr)){
		$secondArr = array();
		$count = count($arr);
		if($count >= 0){
			foreach($arr as $key => $value){
				if(!empty($value)){
					$secondArr[$key] = $value;
				}else{
					unset($arr[$key]);					
				}
				$return = $secondArr;
			}
		}
	}
	// Function return value when 2nd paramenter is true
	if($returnValue == true){
		return $return;		
	}
}



###  Customer Record Classes


###  My nsCustomRecord1 Class to ADD Custom Record (Patient)

class nsAddUpdateCustomerRecord extends nsSoapval {
	/**
	 * Class to ADD or EDIT Customr Record in Netsuite.
	 *
	 * @param array $arr
	 * @param integer $internalID_Record - In Edit case
	 * @return nsAddUpdateCustomerRecord
	 * @access public
	 */
	function nsAddUpdateCustomerRecord($arr, $internalID_Record='')
	{
		$fieldsarr = array();
		foreach($arr as $value){
			if(is_array($value)){
				$fieldsarr = array_merge($fieldsarr, $value);
			}
		}
		returnArrayValues(&$fieldsarr);
		
		parent::nusoap_base();
		$ns1 = 'urn:core_2_6.platform.webservices.netsuite.com';
		$ns4 = 'urn:relationships_2_6.lists.webservices.netsuite.com';
		$ns5 = 'urn:types.relationships_2_6.lists.webservices.netsuite.com';
		
		$this->name = 'record';
		$this->type = 'Customer';
		$cf = array();
		
		if((!empty($internalID_Record)) && isset($internalID_Record) && ($internalID_Record > 0)){
			### To Update the Record Internal Id Requirde
			$attr=array('xsi:type'=>'q1:Customer', 'xmlns:q1'=>$ns4, 'internalId'=>$internalID_Record);
		}else{
			### To Add the Record
			$attr=array('xsi:type'=>'q1:Customer', 'xmlns:q1'=>$ns4);
			$cf[] = new nsSoapval('q1:unsubscribe', 'boolean' ,'0', '', ''); // Mandatory Field
			$cf[] = new nsSoapval('q1:entityStatus', 'RecordRef', array('internalId'=>$fieldsarr['C_customerStatus']), '', $ns1);
			
		}
		$this->attributes = $attr;
		
		$cf[] = new nsSoapval('q1:companyName', 'string' , $fieldsarr['C_clinicName'], '', '');
		$cf[] = new nsSoapval('q1:salesRep', 'RecordRef', array('internalId'=>'1'), '', $ns1);

		/**
		 * Customer's Address Information
		 */
		$attr2 = array('xsi:type'=>'q3:CustomerAddressbook', 'xmlns:q3'=>$ns4);
		$add = array();
		$add[] = new nsSoapval('q3:isResidential', 'boolean', false, '', '');
		$add[] = new nsSoapval('q3:defaultShipping', 'boolean', true, '', '');
		$add[] = new nsSoapval('q3:defaultBilling', 'boolean', false, '', '');
		$add[] = new nsSoapval('q3:addr1', 'string', $fieldsarr['CR_addressLine1'], '', '');
		$add[] = new nsSoapval('q3:addr2', 'string', $fieldsarr['CR_addressLine2'], '', '');
		$add[] = new nsSoapval('q3:city', 'string', $fieldsarr['CR_city'], '', '');
		$add[] = new nsSoapval('q3:zip', 'string', $fieldsarr['CR_zip'], '', '');
		$add[] = new nsSoapval('q3:state', 'string', $fieldsarr['CR_state'], '', '');
		
		
		$add2 = array();
		$add2[] = new nsSoapval('q3:isResidential', 'boolean', false, '', '');
		$add2[] = new nsSoapval('q3:defaultShipping', 'boolean', false, '', '');
		$add2[] = new nsSoapval('q3:defaultBilling', 'boolean', true, '', '');
		$add2[] = new nsSoapval('q3:addr1', 'string', $fieldsarr['CB_addressLine1'], '', '');
		$add2[] = new nsSoapval('q3:addr2', 'string', $fieldsarr['CB_addressLine2'], '', '');
		$add2[] = new nsSoapval('q3:city', 'string', $fieldsarr['CB_city'], '', '');
		$add2[] = new nsSoapval('q3:zip', 'string', $fieldsarr['CB_zip'], '', '');
		$add2[] = new nsSoapval('q3:state', 'string', $fieldsarr['CB_state'], '', '');
		
		$addressList = new nsSoapval('addressbook','CustomerAddressbook', $add, '', '', $attr2);
		$addressList2 = new nsSoapval('addressbook','CustomerAddressbook', $add2, '', '', $attr2);
		
		$cf[] = new nsSoapval('q1:addressbookList', 'CustomerAddressbookList', array($addressList, $addressList2), '', '', array('replaceAll'=> true));
			
				
		/**
		 * Credit Card Information
		 */
		$attr_cc = array('xsi:type'=>'q4:CustomerCreditCards', 'xmlns:q4'=>$ns4);
		$ccInfo = array();
		$ccInfo[] = new nsSoapval('q4:ccNumber', 'string', $fieldsarr['CC_cardNumber']);
		$ccInfo[] = new nsSoapval('q4:ccName', 'string', $fieldsarr['CC_holderName']);
		$ccInfo[] = new nsSoapval('q4:ccDefault', 'boolean', true);
		$ccInfo[] = new nsSoapval('q4:paymentMethod', 'RecordRef', array('internalId'=>'4')); // 4 -- Master Card
		$ccInfo[] = new nsSoapval('q4:ccExpireDate', 'dateTime', $fieldsarr['CC_expDate']);
		$creditCardList = new nsSoapval('creditCards','CustomerCreditCards', $ccInfo, '', '', $attr_cc);
		$cf[] = new nsSoapval('q1:creditCardsList', 'CustomerCreditCardsList', array($creditCardList), '', '');
		

		/*
		//Code for CustomFieldList
		$ccExp = array('xsi:type'=>'qq:DateCustomFieldRef', 'xmlns:qq'=>$ns1, 'internalId'=>'custentitytxaccessdate');
		$cff[] = new nsSoapval('customField', 'DateCustomFieldRef' ,array('value'=>date('c',time())), '', '', $ccExp);
		$cf[] = new nsSoapval('q1:customFieldList', 'CustomFieldList', $cff, '', '');								
		*/
		
		$this->value = $cf;
	}
}
				



class nsDeleteRecord extends nsSoapval {
	/**
	 * Delete the record of sepecified type by passing their internalId.
	 *
	 * @param integer $internalIdCustomer
	 * @param string $recordType
	 * @access public
	 */
	function nsDeleteRecord($internalIdCustomer, $recordType){
		
		$ns='urn:core_2_6.platform.webservices.netsuite.com';
		
		parent::nusoap_base();
		$this->name = 'record';
		$this->type = 'RecordRef';
		$this->value = array();
		$this->element_ns = $ns;
		
		$rr = array('xsi:type'=>'q1:RecordRef', 'xmlns:q1'=>$ns, 'type'=>$recordType, 'internalId'=>$internalIdCustomer);
		$this->attributes = $rr;
	}
}

class nsJoinSearchCustomerRecord extends  nsSoapval {
	/**
	 * Class to search the Customer Record with joining the different RecordTypes like Opportunity
	 *
	 * @return object
	 * @access public
	 */
	function nsJoinSearchCustomerRecord()
	{
		parent::nusoap_base();
		$ns1 = 'urn:core_2_6.platform.webservices.netsuite.com';
		$ns2 = 'urn:common_2_6.platform.webservices.netsuite.com';
		$ns4 = 'urn:relationships_2_6.lists.webservices.netsuite.com';
		
		$this->name = 'CustomerSearch'; // Extends SearchRecord
		$this->value = array();
		$attr=array('xsi:type'=>'q1:CustomerSearch', 'xmlns:q1'=>$ns4);
		$this->attributes = $attr;
		
		$soapValue = new nsSoapval('searchValue', 'RecordRef', array('internalId'=>'19'), '', $ns1);
		$soapName = new nsSoapval('entity', 'SearchMultiSelectField', array($soapValue), '', $ns1, array('operator'=>'anyOf'));
		
		//Code for CustomFieldList
		//$ccExp = array('xsi:type'=>'q2:LongCustomFieldRef', 'xmlns:q2'=>$ns1, 'internalId'=>'tranid');
		//$cff[] = new nsSoapval('customField', 'LongCustomFieldRef' ,array('value'=>'0'), '', '', $ccExp);
		//$soapCustomList = new nsSoapval('q1:customFieldList', 'CustomFieldList', $cff, '', '');
		
		$soapbasic = new nsSoapval('q1:opportunityJoin', 'OpportunitySearchBasic', array($soapName), '', $ns2);
		$this->value[] = $soapbasic;
	}
}


class nsSearchCustomerRecord extends  nsSoapval {
	/**
	 * Class to search the customer Record on some critieria
	 *
	 * @access public
	 */
	function nsSearchCustomerRecord()
	{
		parent::nusoap_base();
		$ns1 = 'urn:core_2_6.platform.webservices.netsuite.com';
		$ns1_1 = 'urn:types.core_2_6.platform.webservices.netsuite.com';
		$ns2 = 'urn:common_2_6.platform.webservices.netsuite.com';
		$ns4 = 'urn:relationships_2_6.lists.webservices.netsuite.com';
		
		$soapName = array();
		
		$this->name = 'CustomerSearch'; // Extends SearchRecord
		$attr=array('xsi:type'=>'q1:CustomerSearch', 'xmlns:q1'=>$ns4);
		$this->attributes = $attr;

		
		//#### Custom Field List Created
		$cf = array();
		$customAttr = array('xsi:type'=>'q2:SearchDateCustomField', 'xmlns:q2'=>$ns1, 'internalId'=>'custentitytxaccessdate', 'operator'=>'on');
		//$cf[] = new nsSoapval('predefinedSearchValue', '', array('today'), '', '', array('xsi:type'=>'q3:SearchDate', 'xmlns:q3'=>$ns1_1));
		//$cf[] = new nsSoapval('customField', '' ,array('searchValue'=>date('Y-m-d\TH:i:s.BP', time())), '', '', $customAttr);
		$cf[] = new nsSoapval('customField', '' ,array('searchValue'=>'2008-03-29T00:00:00.000-07:00'), '', '', $customAttr);
		$soapName[] = new nsSoapval('customFieldList', 'SearchCustomFieldList', $cf, '', $ns1);
		//#### Custom Field List Ends
		
		
		
		//$soapValue = new nsSoapval('searchValue', 'string', 'ma', '', $ns1);
		//$soapName[] = new nsSoapval('email', 'SearchStringField', array($soapValue), '', '', array('operator'=>'contains'));
		$soapValue = new nsSoapval('searchValue', 'RecordRef', array('internalId'=>'11'), '', $ns1); // 11 - Trial Period
		$soapName[] = new nsSoapval('entityStatus', 'SearchMultiSelectField', array($soapValue), '','', array('operator'=>'anyOf'));
		$soapbasic[] = new nsSoapval('q1:basic', 'CustomerSearchBasic', $soapName, '', $ns2);
		
		//$soapValue = new nsSoapval('searchValue', 'dateTime', '2008-01-01T00:00:00.000-00:00', '', $ns1);
		//$soapName = new nsSoapval('dateCreated', 'SearchDateField', array($soapValue), '', $ns1, array('operator'=>'onOrAfter'));
		//$soapbasic[] = new nsSoapval('q1:opportunityJoin', 'OpportunitySearchBasic', array($soapName), '', $ns2);
		
		$this->value = $soapbasic;
	}
}


class nsGetCustomerRecord extends nsSoapval {
	/**
	 * Returns a signle record of customer type by passing the internalId of that Record.
	 *
	 * @param integer $internamId
	 * @access public
	 */
	function nsGetCustomerRecord($internamId=''){
		$ns1 = 'urn:core_2_6.platform.webservices.netsuite.com';
		
		$this->name = 'getCustomerRecord';
		$this->value = array();
		$attr = array('xsi:type'=>'q1:RecordRef', 'xmlns:q1'=>$ns1, 'internalId'=>$internamId, 'type'=>'customer');
		$this->attributes = $attr;
	}
}

class nsGetAllCustomerRecord extends nsSoapval {
	/**
	 * Returns all records of specified type.
	 *
	 * @param string $recordType - pass name of record type.
	 * @access public
	 */
	function nsGetAllCustomerRecord($recordType) {
		$ns  = 'urn:types.core_2_5.platform.webservices.netsuite.com';
		$ns2 = 'urn:core_2_6.platform.webservices.netsuite.com';
		
		$this->name = 'getallrecord';
		$this->value = '';
		$rr = array('xsi:type'=>'q2:GetAllRecord', 'xmlns:q2'=>$ns2, 'recordType'=>$recordType);
		$this->attributes = $rr;
	}
}



###  Opportunity Records Classes



class nsAddUpdateOpportunityRecord extends nsSoapval {
	/**
	 * Create a new or Edit existing Opportunity Record
	 *
	 * @param integer $internalIdCustomer
	 * @param integer $internalIdOpportunity
	 * @access public
	 */
	function nsAddUpdateOpportunityRecord($internalIdCustomer, $internalIdOpportunity='')
	{
		parent::nusoap_base();
		$ns1 = 'urn:core_2_6.platform.webservices.netsuite.com';
		$ns6 = 'urn:sales_2_6.transactions.webservices.netsuite.com';
		$ns7 = 'urn:types.relationships_2_6.lists.webservices.netsuite.com';
		
		$this->name = 'record';
		$this->type = 'Opportunity';

		if((!empty($internalIdOpportunity)) && ($internalIdOpportunity > 0)){
			##  To Update the Record Internal Id Requirde
			$attr=array('xsi:type'=>'q1:Opportunity', 'xmlns:q1'=>$ns6, 'internalId'=>$internalID_Record);
		}else{
			##  To Add the Record
			$attr=array('xsi:type'=>'q1:Opportunity', 'xmlns:q1'=>$ns6);
		}
		$this->attributes = $attr;
		
		$cf = array();
		//$cs = array();
		
		$cf[] = new nsSoapval('q1:entityStatus', 'RecordRef',array('internalId'=>'11'), '',''); // Trial Period Status
		$cf[] = new nsSoapval('q1:entity', 'RecordRef',array('internalId' => $internalIdCustomer),'', ''); // internal Id of Customer Record
		$this->value = $cf;
	
	}
}



class nsSearchOpportunityRecord extends  nsSoapval {
	/**
	 * Function to list Contact Records of a specific Customer Record
	 *
	 * @param integer $internalIdCustomer
	 * @access public
	 */
	function nsSearchOpportunityRecord($internalIdCustomer)
	{
		parent::nusoap_base();
		$ns1 = 'urn:core_2_6.platform.webservices.netsuite.com';
		$ns2 = 'urn:common_2_6.platform.webservices.netsuite.com';
		$ns6 = 'urn:sales_2_6.transactions.webservices.netsuite.com';
		$ns7 = 'urn:types.relationships_2_6.lists.webservices.netsuite.com';
		
		$this->name = 'opportunitySearch'; // Extends SearchRecord
		$attr=array('xsi:type'=>'q1:OpportunitySearch', 'xmlns:q1'=>$ns6);
		$this->attributes = $attr;
		
		$soapValue = new nsSoapval('searchValue', 'RecordRef', array('internalId'=>$internalIdCustomer), '', $ns1);
		$soapName[] = new nsSoapval('entity', 'SearchMultiSelectField', array($soapValue), '','', array('operator'=>'anyOf'));
		$soapbasic[] = new nsSoapval('q1:basic', 'OpportunitySearchBasic', $soapName, '', $ns2);
		
		$this->value = $soapbasic;
	}
}




###  Contact Records Classes




class nsSearchContactRecord extends  nsSoapval {
	/**
	 * Function to list Contact Records of a specific Customer Record
	 *
	 * @param integer $internalIdCustomer
	 * @access public
	 */
	function nsSearchContactRecord($internalIdCustomer)
	{
		parent::nusoap_base();
		$ns1 = 'urn:core_2_6.platform.webservices.netsuite.com';
		$ns2 = 'urn:common_2_6.platform.webservices.netsuite.com';
		$ns4 = 'urn:relationships_2_6.lists.webservices.netsuite.com';
		
		$this->name = 'searchContactRecord'; // Extends SearchRecord
		$attr=array('xsi:type'=>'q1:ContactSearch', 'xmlns:q1'=>$ns4);
		$this->attributes = $attr;
		$cf = array();
		
		$attr2 = array('xsi:type'=>'q2:CustomerSearchBasic', 'xmlns:q2'=>$ns2);
		$attr3 = array('xsi:type'=>'q3:RecordRef', 'xmlns:q3'=>$ns1, 'type'=>'customer', 'internalId'=>$internalIdCustomer);
		$soapValue = new nsSoapval('searchValue', 'RecordRef', '', '', '', $attr3); 
		$soapName[] = new nsSoapval('q2:internalId', 'SearchMultiSelectField', array($soapValue), '','', array('operator'=>'anyOf'));		
		$cf[] = new nsSoapval('q1:customerJoin', 'CustomerSearchBasic', $soapName, '', '', $attr2);
		
		$this->value = $cf;
	}
}

class nsAddUpdateContactRecord extends nsSoapval {
	/**
	 * Used to create and edit the contact record.
	 *
	 * @param array $fieldsarr
	 * @param integer $internalIdContact
	 * @param integer $internalIdCustomer
	 * @access public
	 */
	function nsAddUpdateContactRecord($fieldsarr, $internalIdContact='', $internalIdCustomer='')
	{
		parent::nusoap_base();
		$ns1 = 'urn:core_2_6.platform.webservices.netsuite.com';
		$ns2 = 'urn:common_2_6.platform.webservices.netsuite.com';
		$ns4 = 'urn:relationships_2_6.lists.webservices.netsuite.com';
		
		$this->name = 'record';
		$this->type = 'Contact';
		$cf = array();

		if((!empty($internalIdContact)) && ($internalIdContact > 0)){
			### To Update the Record Internal Id Requirde
			$attr=array('xsi:type'=>'q1:Contact', 'xmlns:q1'=>$ns4, 'internalId'=>$internalIdContact); 
		}else{
			### To Add the Record
			$attr=array('xsi:type'=>'q1:Contact', 'xmlns:q1'=>$ns4);
			$cf[] = new nsSoapval('q1:unsubscribe', 'boolean' ,'0', '', ''); // Mandatory Field
		}
		$this->attributes = $attr;
		
		## Maindatory Fields
		$cf[] = new nsSoapval('q1:firstName', 'string' ,$fieldsarr['CO_firstName'], '', '');
		$cf[] = new nsSoapval('q1:lastName', 'string' ,$fieldsarr['CO_lastName'], '', '');
		$cf[] = new nsSoapval('q1:email', 'string' ,$fieldsarr['CO_email'], '', '');
		$cf[] = new nsSoapval('q1:phone', 'string' ,$fieldsarr['CO_phone'], '', '');
		
		## Above are the Mandatory Fields for creating Contact Record
		
		## pass customer's internal Id if you want, but this is not mandatory.
		if(!empty($internalIdCustomer)){
			$cf[] = new nsSoapval('q1:company', 'RecordRef', array('internalId'=>$internalIdCustomer), '', '');
		}
		
		$this->value = $cf;
	}
}

class nsAssociateContactToCustomer extends nsSoapval {
	/**
	 * Used to associate the contact record from customer record.
	 *
	 * @param integer $internalIdCustomer
	 * @param interger $internalIdContact
	 * @access public
	 */
	function nsAssociateContactToCustomer($internalIdCustomer, $internalIdContact)
	{
		parent::nusoap_base();
		$ns1 = 'urn:core_2_6.platform.webservices.netsuite.com';
		
		$this->name = 'attachReferece';
		$this->type = 'AttachContactReference';
		$attr=array('xsi:type'=>'q1:AttachContactReference', 'xmlns:q1'=>$ns1); 
		$this->attributes = $attr;
		$cf = array();
		$cf2 = array();
		
		$attr2 = array('internalId'=>$internalIdCustomer, 'type'=>'customer', 'xsi:type'=>'q2:RecordRef', 'xmlns:q2'=>$ns1);
		$cf[] = new nsSoapval('attachTo', 'RecordRef', '', '', '', $attr2);
		$cf[] = new nsSoapval('q1:contact', 'RecordRef', array('internalId'=>$internalIdContact), '', '');
		$cf[] = new nsSoapval('q1:contactRole', 'RecordRef', array('internalId'=>'3'), '', '');
		
		$this->value = $cf;
	}
}

?>