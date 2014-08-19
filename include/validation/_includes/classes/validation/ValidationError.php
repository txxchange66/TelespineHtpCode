<?php
/**
 * Contains a field name with relevant error message
 * @package validation
 */
class ValidationError
{
	// object variables
	var $str_fieldName;
	var $str_errorMessage;
	
	// constructor
	/**
	 * create new ValidationError object
	 *
	 * @param string $a_str_fieldName
	 * @param string $a_str_errorMessage
	 * @return ValidationError
	 */
	function ValidationError($a_str_fieldName, $a_str_errorMessage)
	{
		$this->str_fieldName = $a_str_fieldName;
		$this->str_errorMessage = $a_str_errorMessage;
	}
	
	
	/**
	 * get field name
	 *
	 * @return string
	 */
	function getFieldName()
	{
		return $this->str_fieldName;	
	}
	
	
	/**
	 * get error message
	 *
	 * @return string
	 */
	function getErrorMessage()
	{
		return $this->str_errorMessage;	
	}
}
?>