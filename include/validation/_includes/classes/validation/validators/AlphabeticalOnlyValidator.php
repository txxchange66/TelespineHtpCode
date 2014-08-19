<?php
/**
 * Checks if the form field's value is an alphabetical string: a-z A-Z, also allows the option of passing special characters to allow
 * @package validation
 * @subpackage validators
 */
class AlphabeticalOnlyValidator extends Validator
{
	// object variables
	var $str_fieldName;
	var $str_fieldValue;
	var $str_errorMessage;
	var $obj_validationSet;
	var $arr_specialCharacters;
	
	// constructor
	/**
	 * create new AlphabeticalOnlyValidator object
	 *
	 * @param string $a_str_fieldName
	 * @param array $a_arr_specialCharacters;
	 * @param string $a_str_errorMessage
	 * @param string $a_str_fieldValue
	 * @return AlphabeticalOnlyValidator
	 */
	function AlphabeticalOnlyValidator($a_str_fieldName, $a_arr_specialCharacters = null, $a_str_errorMessage = null, $a_str_fieldValue = null)
	{
		$this->str_fieldName = $a_str_fieldName;
		$this->str_fieldValue = $a_str_fieldValue;
		$this->arr_specialCharacters = $a_arr_specialCharacters;
		
		// check for null field value
		if($this->str_fieldValue == null)
		{
			$this->str_fieldValue = $this->getValueByFieldName($this->str_fieldName);
		}
		
		// check for default error message
		if($a_str_errorMessage == null)
		{
			$this->str_errorMessage = "Invalid characters found";
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
		$str_pattern = '/[^[:alpha:]';
		
		// append special characters to the pattern
		for($i = 0; $i < sizeof($this->arr_specialCharacters); $i++)
		{
			if($this->arr_specialCharacters[$i] == ' ')
			{
				$str_pattern .= '\\s';
			}
			else
			{
				$str_pattern .= '\\'.addslashes($this->arr_specialCharacters[$i]);
			}
		}
		
		$str_pattern .= ']/';
		
		preg_match_all($str_pattern, $this->str_fieldValue, $arr_matches);
		
		// check for illegal characters
		foreach($arr_matches[0] as $str_value)
		{
			$this->obj_validationSet->addValidationError(new ValidationError($this->str_fieldName, $this->str_errorMessage));
			break;
		}
	}
}
?>