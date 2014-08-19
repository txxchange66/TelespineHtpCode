<?php
/**
 * Checks a form field's value for a certain number of integers.
 * If $bool_allowExtension is set to false, exactly 10 integers are required.
 * Less than 10 integers is never accepted.
 * Does not validate if the field value is empty.
 * Dev Note: This validator ignores non-numeric characters; successful db submission depends on column type.
 * @package validation
 * @subpackage validators
 */
class PhoneValidator extends Validator
{
	// object variables
	var $str_fieldName;
	var $str_fieldValue;
	var $str_errorMessage;
	var $bool_allowExtension;
	var $obj_validationSet;
	var $bool_allowLetters;
	
	// constructor
	/**
	 * create new PhoneValidator object
	 *
	 * @param string $a_str_fieldName
	 * @param boolean $a_bool_allowExtension
	 * @param boolean $a_bool_allowLetters
	 * @param string $a_str_errorMessage
	 * @param string $a_str_fieldValue
	 * @return PhoneValidator
	 */
	function PhoneValidator($a_str_fieldName, $a_bool_allowExtension = true, $a_bool_allowLetters = false, $a_str_errorMessage = null, $a_str_fieldValue = null)
	{
		$this->str_fieldName = $a_str_fieldName;
		$this->str_fieldValue = $a_str_fieldValue;
		$this->bool_allowExtension = $a_bool_allowExtension;
		$this->bool_allowLetters = $a_bool_allowLetters;
		
		// check for null field value
		if($this->str_fieldValue == null)
		{
			$this->str_fieldValue = $this->getValueByFieldName($this->str_fieldName);
		}
		
		// check for default error message
		if($a_str_errorMessage == null)
		{
			$this->str_errorMessage = "Invalid phone number";
		}
		else
		{
			$this->str_errorMessage = $a_str_errorMessage;
		}
	}
	
	
	/**
	 * validate
	 *
	 * @return void
	 */
	function validate()
	{
		$str_phone = '';
		
		if($this->bool_allowLetters == false)
		{
			$str_pattern = '/[0-9]/';
		}
		else
		{
			$str_pattern = '/[[:alnum:]]/';
		}
		
		preg_match_all($str_pattern, $this->str_fieldValue, $arr_matches);
		
		// reform the phone number with only integers
		foreach($arr_matches[0] as $int_value)
		{
			$str_phone.=$int_value;
		}
		
		// make sure the phone length is not less than 10 integers
		// make sure the phone length is exactly 10 integers if it allows an extension
		if(strlen($this->str_fieldValue) != 0 && ((strlen($str_phone) < 10) || ($this->bool_allowExtension == false && strlen($str_phone) != 10)))
		{
			$this->obj_validationSet->addValidationError(new ValidationError($this->str_fieldName, $this->str_errorMessage));
		}
	}
}
?>