<?php
/**
 * Checks if the form field's value is a valid date
 * @package validation
 * @subpackage validators
 */
class DateValidator extends Validator
{
	// object variables
	var $str_fieldName;
	var $str_fieldValue;
	var $str_errorMessage;
	var $obj_validationSet;
	
	// constructor
	/**
	 * create new DateValidator object
	 *
	 * @param string $a_str_fieldName
	 * @param string $a_str_errorMessage
	 * @param string $a_str_fieldValue
	 * @return StringMinLengthValidator
	 */
	function DateValidator($a_str_fieldName, $a_str_errorMessage = null, $a_str_fieldValue = null)
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
			$this->str_errorMessage = "Date is invalid";
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
		// replace dashes with slashes (dashes are seen as minuses)
		$this->str_fieldValue = str_replace('-', '/', $this->str_fieldValue);
		if(strtotime($this->str_fieldValue) == -1 || strtotime($this->str_fieldValue) == false)
		{
			$this->obj_validationSet->addValidationError(new ValidationError($this->str_fieldName, $this->str_errorMessage));
		}
	}
}
?>