<?php
/**
 * Checks if the form field's value length does not meet a minimum number of characters
 * @package validation
 * @subpackage validators
 */
class StringMinLengthValidator extends Validator
{
	// object variables
	var $str_fieldName;
	var $str_fieldValue;
	var $str_errorMessage;
	var $int_minValue;
	var $obj_validationSet;
	
	// constructor
	/**
	 * create new StringMinLengthValidator object
	 *
	 * @param string $a_str_fieldName
	 * @param integer $a_int_minValue
	 * @param string $a_str_errorMessage
	 * @param string $a_str_fieldValue
	 * @return StringMinLengthValidator
	 */
	function StringMinLengthValidator($a_str_fieldName, $a_int_minValue, $a_str_errorMessage = null, $a_str_fieldValue = null)
	{
			
		$this->str_fieldName = $a_str_fieldName;
		$this->str_fieldValue = $a_str_fieldValue;
		$this->int_minValue = $a_int_minValue;
		
		// check for null field value
		if($this->str_fieldValue == null)
		{
			$this->str_fieldValue = $this->getValueByFieldName($this->str_fieldName);
		}
		
		// check for default error message
		if($a_str_errorMessage == null)
		{
			$this->str_errorMessage = "Length is too short";
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
		if(strlen(trim($this->str_fieldValue)) < $this->int_minValue)
		{
			$this->obj_validationSet->addValidationError(new ValidationError($this->str_fieldName, $this->str_errorMessage));
		}
	}
}
?>