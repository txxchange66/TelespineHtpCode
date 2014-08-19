<?php
/**
 * Checks if the form field's value length exceeds a maximum number of characters
 * @package validation
 * @subpackage validators
 */
class StringMaxLengthValidator extends Validator
{
	// object variables
	var $str_fieldName;
	var $str_fieldValue;
	var $str_errorMessage;
	var $int_maxValue;
	var $obj_validationSet;
	
	// constructor
	/**
	 * create new StringMaxLengthValidator object
	 *
	 * @param string $a_str_fieldName
	 * @param integer $a_int_maxValue
	 * @param string $a_str_errorMessage
	 * @param string $a_str_fieldValue
	 * @return StringMaxLengthValidator
	 */
	function StringMaxLengthValidator($a_str_fieldName, $a_int_maxValue, $a_str_errorMessage = null, $a_str_fieldValue = nul)
	{
		$this->str_fieldName = $a_str_fieldName;
		$this->str_fieldValue = $a_str_fieldValue;
		$this->int_maxValue = $a_int_maxValue;
		
		// check for null field value
		if($this->str_fieldValue == null)
		{
			$this->str_fieldValue = $this->getValueByFieldName($this->str_fieldName);
		}
		
		// check for default error message
		if($a_str_errorMessage == null)
		{
			$this->str_errorMessage = "Length is too long";
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
		if(strlen($this->str_fieldValue) > $this->int_maxValue)
		{
			$this->obj_validationSet->addValidationError(new ValidationError($this->str_fieldName, $this->str_errorMessage));
		}
	}
}
?>