<?php
/**
 * Checks if the form field's value is between two integer values
 * Wrapper class for IntegerMinValidator and IntegerMaxValidator
 * @package validation
 * @subpackage validators
 */
class IntegerMinMaxValidator extends Validator
{
	// object variables
	var $str_fieldName;
	var $str_fieldValue;
	var $str_errorMessage;
	var $int_minValue;
	var $int_maxValue;
	var $obj_validationSet;
	var $obj_integerMinValidator;
	var $obj_integerMaxValidator;
	
	// constructor
	/**
	 * create new IntegerMinMaxValidator object
	 *
	 * @param string $a_str_fieldName
	 * @param integer $a_int_minValue
	 * @param integer $a_int_maxValue
	 * @param string $a_str_errorMessage
	 * @param string $a_str_fieldValue
	 * @return IntegerMinMaxValidator
	 */
	function IntegerMinMaxValidator($a_str_fieldName, $a_int_minValue = null, $a_int_maxValue = null, $a_str_errorMessage = null, $a_str_fieldValue = null)
	{
		$this->str_fieldName = $a_str_fieldName;
		$this->str_fieldValue = $a_str_fieldValue;
		$this->str_errorMessage = $a_str_errorMessage;
		$this->int_minValue = $a_int_minValue;
		$this->int_maxValue = $a_int_maxValue;
		
		$this->obj_integerMinValidator = new IntegerMinValidator($this->str_fieldName, $this->int_minValue, $this->str_errorMessage, $this->str_fieldValue);
		$this->obj_integerMaxValidator = new IntegerMaxValidator($this->str_fieldName, $this->int_maxValue, $this->str_errorMessage, $this->str_fieldValue);
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
			echo '*Validation error in IntegerMinMaxValidator - improper number of parameters passed*';
			return null;
		}
		
		if($this->int_minValue != null)
		{
			$this->obj_integerMinValidator->setValidationSet(&$this->obj_validationSet);
			$this->obj_integerMinValidator->validate();
		}
		
		if($this->int_maxValue != null)
		{
			$this->obj_integerMaxValidator->setValidationSet(&$this->obj_validationSet);
			$this->obj_integerMaxValidator->validate();
		}
	}
}
?>