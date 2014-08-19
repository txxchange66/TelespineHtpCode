<?php
/**
 * Checks if the form field's value is a valid email address
 * @package validation
 * @subpackage validators
 */
class EmailValidator extends Validator
{
	// object variables
	var $str_fieldName;
	var $str_fieldValue;
	var $str_errorMessage;
	var $obj_validationSet;
	
	// constructor
	/**
	 * create new EmailValidator object
	 *
	 * @param string $a_str_fieldName
	 * @param string $a_str_errorMessage
	 * @param string $a_str_fieldValue
	 * @return EmailValidator
	 */
	function EmailValidator($a_str_fieldName, $a_str_errorMessage = null, $a_str_fieldValue = null)
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
			$this->str_errorMessage = "Invalid e-mail address";
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
		if(strlen($this->str_fieldValue) != 0)
		{
			if (!eregi('^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$', $this->str_fieldValue))
			{
				$this->obj_validationSet->addValidationError(new ValidationError($this->str_fieldName, $this->str_errorMessage));
			}
		}
	}
}
?>