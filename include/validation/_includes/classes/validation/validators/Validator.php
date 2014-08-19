<?php
/**
 * Superclass of all validator objects
 * @package validation
 * @subpackage validators
 */
class Validator
{
	// object variables
	var $str_errorMessage;
	var $obj_validationSet;
	
	/**
	 * set object reference to the ValidationSet object
	 *
	 * @param object $a_obj_validationSet
	 * @return void
	 */
	function setValidationSet(&$a_obj_validationSet)
	{
		$this->obj_validationSet =& $a_obj_validationSet;
	}
	
	
	/**
	 * get a field's submitted value
	 *
	 * @return string
	 */
	function getValueByFieldName($a_str_fieldName)
	{
		// loop through all submitted variables
		foreach($_REQUEST as $key => $value)
		{
		   //$$key = addslashes($value);
		   
		   if(strtolower($key) == strtolower($a_str_fieldName))
		   {
		   		return $value;
		   }
		}
	}
}
?>