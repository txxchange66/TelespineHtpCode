<?php
/**
 * Checks a form field's value for a certain number of integers.
 * Either 5 or 9 integers are required.
 * Does not validate if the field value is empty.
 * Dev Note: This validator ignores non-numeric characters; successful db submission depends on column type.
 * @package validation
 * @subpackage validators
 */
class ZipValidator extends Validator
{
	// object variables
	var $str_fieldName;
	var $str_fieldValue;
	var $str_errorMessage;
	var $obj_validationSet;
	
	// constructor
	/**
	 * create new ZipValidator object
	 *
	 * @param string $a_str_fieldName
	 * @param string $a_str_errorMessage
	 * @param string $a_str_fieldValue
	 * @return ZipValidator
	 */
	function ZipValidator($a_str_fieldName, $a_str_errorMessage = null, $a_str_fieldValue = null)
	{
		$this->str_fieldName = $a_str_fieldName;
		$this->str_fieldValue = $a_str_fieldValue;
		
		// check for null field value
		if($this->str_fieldValue == null)
		{
			$this->str_fieldValue = $this->getValueByFieldName($this->str_fieldName);
		}
		
		// check for default error message
		if($a_str_errorMessage == null)
		{
			$this->str_errorMessage = "Invalid Zip Code";
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
		$str_zip = '';
		$str_pattern = '/[0-9]/';
		
		preg_match_all($str_pattern, $this->str_fieldValue, $arr_matches);
		
		// reform the zip code with only integers
		foreach($arr_matches[0] as $int_value)
		{
			$str_zip.=$int_value;
		}
		
		if(strlen($this->str_fieldValue) != 0 && strlen($str_zip) != 5 && strlen($str_zip) != 9)
		{
			$this->obj_validationSet->addValidationError(new ValidationError($this->str_fieldName, $this->str_errorMessage));
		}
	}
}
?>