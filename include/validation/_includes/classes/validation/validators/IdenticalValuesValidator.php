<?php
/**
 * Checks if form field's values are identical
 * @package validation
 * @subpackage validators
 */
class IdenticalValuesValidator extends Validator
{
	// object variables
	var $arr_fieldNames;
	var $str_errorMessage;
	var $arr_fieldValues;
	
	// constructor
	/**
	 * create new IdenticalValuesValidator object
	 *
	 * @param array $a_arr_fieldNames
	 * @param string $a_str_errorMessage
	 * @param array $a_arr_fieldValues
	 * @return AlphabeticalOnlyValidator
	 */
	function IdenticalValuesValidator($a_arr_fieldNames, $a_str_errorMessage = null, $a_arr_fieldValues = null)
	{
		$this->arr_fieldNames = $a_arr_fieldNames;
		$this->arr_fieldValues = $a_arr_fieldValues;
		
		// check for null field value
		if($this->arr_fieldValues == null)
		{
			// form array of field values
			for($i = 0; $i < sizeof($this->arr_fieldNames); $i++)
			{
				$this->arr_fieldValues[$i] = $this->getValueByFieldName($this->arr_fieldNames[$i]);
			}
		}
		
		// check for default error message
		if($a_str_errorMessage == null)
		{
			$this->str_errorMessage = "These fields do not match";
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
		$str_previousValue = $this->arr_fieldValues[0];
		
		// loop through each field
		for($i = 1; $i < sizeof($this->arr_fieldNames); $i++)
		{
			// check if the current value does not match the previous
			if($this->arr_fieldValues[$i] != $str_previousValue)
			{
				// validation erros found, add errors for each field name
				for($j = 0; $j < sizeof($this->arr_fieldNames); $j++)
				{
					$this->obj_validationSet->addValidationError(new ValidationError($this->arr_fieldNames[$j], $this->str_errorMessage));
				}
				
				break;
			}
			
			$str_previousValue = $this->arr_fieldValues[$i];
		}
	}
}
?>