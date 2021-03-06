<?php
/**
 * Checks if the form field's value is less than the minimum integer value
 * @package validation
 * @subpackage validators
 */
class IntegerMinValidator extends Validator
{
	// object variables
	var $str_fieldName;
	var $str_fieldValue;
	var $str_errorMessage;
	var $int_minValue;
	var $obj_validationSet;
	
	// constructor
	/**
	 * create new IntegerMinValidator object
	 *
	 * @param string $a_str_fieldName
	 * @param integer $a_int_minValue
	 * @param string $a_str_errorMessage
	 * @param string $a_str_fieldValue
	 * @return IntegerMinValidator
	 */
	function IntegerMinValidator($a_str_fieldName, $a_int_minValue, $a_str_errorMessage = null, $a_str_fieldValue = null)
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
			$this->str_errorMessage = "Value is too small";
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
		// make sure the value is numeric
		if(is_numeric(trim($this->str_fieldValue)))
		{
			// convert the numeric string to an integer
			$int_fieldValue = trim($this->str_fieldValue);
			settype($int_fieldValue, "integer");

			if($int_fieldValue < $this->int_minValue)
			{
				$this->obj_validationSet->addValidationError(new ValidationError($this->str_fieldName, $this->str_errorMessage));
			}
		}
	}
}
?>