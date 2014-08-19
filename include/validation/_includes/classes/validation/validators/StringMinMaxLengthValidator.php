<?php
/**
 * Checks if the form field's value length is between two values
 * Wrapper class for StringMinLengthValidator and StringMaxLengthValidator
 * @package validation
 * @subpackage validators
 */
class StringMinMaxLengthValidator extends Validator
{
	// object variables
	var $str_fieldName;
	var $str_fieldValue;
	var $str_errorMessage;
	var $int_minValue;
	var $int_maxValue;
	var $obj_validationSet;
	var $obj_stringMinLengthValidator;
	var $obj_stringMaxLengthValidator;
	
	// constructor
	/**
	 * create new StringMinMaxLengthValidator object
	 *
	 * @param string $a_str_fieldName
	 * @param integer $a_int_minValue
	 * @param integer $a_int_maxValue
	 * @param string $a_str_errorMessage
	 * @param string $a_str_fieldValue
	 * @return StringMinMaxLengthValidator
	 */
	function StringMinMaxLengthValidator($a_str_fieldName, $a_int_minValue = null, $a_int_maxValue = null, $a_str_errorMessage = null, $a_str_fieldValue = null)
	{
		$this->str_fieldName = $a_str_fieldName;
		$this->str_fieldValue = $a_str_fieldValue;
		$this->str_errorMessage = $a_str_errorMessage;
		$this->int_minValue = $a_int_minValue;
		$this->int_maxValue = $a_int_maxValue;
		
		$this->obj_stringMinLengthValidator = new StringMinLengthValidator($this->str_fieldName, $this->int_minValue, $this->str_errorMessage, $this->str_fieldValue);
		$this->obj_stringMaxLengthValidator = new StringMaxLengthValidator($this->str_fieldName, $this->int_maxValue, $this->str_errorMessage, $this->str_fieldValue);
	}
	
	
	/**
	 * validate
	 *
	 * @return void
	 */
	function validate()
	{
		// check that the correct number of parameters were passed
		if($this->int_minValue == null && $this->int_maxValue == null)
		{
			echo '*Validation error in StringMinMaxLengthValidator - improper number of parameters passed*';
			return null;
		}
		
		if($this->int_minValue != null)
		{
			$this->obj_stringMinLengthValidator->setValidationSet(&$this->obj_validationSet);
			$this->obj_stringMinLengthValidator->validate();
		}
		
		if($this->int_maxValue != null)
		{
			$this->obj_stringMaxLengthValidator->setValidationSet(&$this->obj_validationSet);
			$this->obj_stringMaxLengthValidator->validate();
		}
	}
}
?>