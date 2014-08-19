<?php
require_once('include/validation/_includes/import.php');
require_once('validators/Validator.php');
require_once('ValidationError.php');
import('classes.validation.validators.*');

/**
 * Main object in hQ PHP form validation. This is the only validation file that should be included in your script.
 * @package validation
 */
class ValidationSet
{
	// object variables
	var $arr_validators;
	var $arr_validationErrors;
	var $obj_DBConn;
	var $bool_hasErrors;
	
	// constructor
	/**
	 * create new ValidationSet object
	 *
	 * @param object $a_obj_DBConn
	 * @return ValidationSet
	 */
	function ValidationSet($a_obj_DBConn = null)
	{
		$this->arr_validators = array();
		$this->arr_validationErrors = array();
		$this->obj_DBConn = $a_obj_DBConn;
		$this->bool_hasErrors = false;
	}

	
	/**
	 * set databse connection
	 *
	 * @param object $a_obj_DBConn
	 * @return void
	 */
	function setDBConn($a_obj_DBConn)
	{
		$this->obj_DBConn = $a_obj_DBConn;
	}
	
	
	/**
	 * get databse connection
	 *
	 * @return object
	 */
	function getDBConn()
	{
		return $this->obj_DBConn;
	}
	
	
	/**
	 * add a validator object to the array
	 *
	 * @param Validator $a_obj_validator
	 * @return void
	 */
	function addValidator($a_obj_validator)
	{
		// store reference to this ValidationSet
		$a_obj_validator->setValidationSet(&$this);
		
		// add the validator to the array
		array_push($this->arr_validators, $a_obj_validator);
	}
	
	
	/**
	 * add a ValidationError to the array
	 *
	 * @param ValidationError $a_obj_validationError
	 * @return void
	 */
	function addValidationError($a_obj_validationError)
	{
		array_push($this->arr_validationErrors, $a_obj_validationError);
		
		// declare that the ValidationSet contains validation errors
		$this->setHasErrors(true);
	}
	
	
	/**
	 * get all validation errors
	 *
	 * @return array
	 */
	function getErrors($a_bool_allowMultipleErrors = false)
	{
		$arr_returnedErrors = array();
		$arr_fieldNames = array();
		
		if($a_bool_allowMultipleErrors)
		{
			$arr_returnedErrors = $this->arr_validationErrors;
		}
		else
		{
			for($i = 0; $i < sizeof($this->arr_validationErrors); $i++)
			{
				if(!in_array($this->arr_validationErrors[$i]->getFieldName(), $arr_fieldNames))
				{
					array_push($arr_fieldNames, $this->arr_validationErrors[$i]->getFieldName());
					array_push($arr_returnedErrors, $this->arr_validationErrors[$i]);
				}
			}
		}
		
		return $arr_returnedErrors;
	}
	
	
	/**
	 * find out if the ValidationSet contains validation errors
	 *
	 * @return boolean
	 */
	function hasErrors()
	{
		return $this->bool_hasErrors;
	}
	
	
	/**
	 * set whether the ValidationSet contains validation errors
	 *
	 * @param boolean $a_bool_hasErrors
	 * @return void
	 */
	function setHasErrors($a_bool_hasErrors)
	{
		$this->bool_hasErrors = $a_bool_hasErrors;
	}
	
	
	/**
	 * get a validation error by its field name
	 *
	 * @return string
	 */
	function getErrorByFieldName($a_str_fieldName)
	{
		//print_r($this->arr_validationErrors);
		for($i = 0; $i < sizeof($this->arr_validationErrors); $i++)
		{
			if($this->arr_validationErrors[$i]->getFieldName() == $a_str_fieldName)
			{
				return $this->arr_validationErrors[$i]->getErrorMessage();
			}
		}
	}
	
	
	/**
	 * run validate function on all validators
	 *
	 * @return void
	 */
	function validate()
	{
		//echo sizeof($this->arr_validators);
		for($i = 0; $i < sizeof($this->arr_validators); $i++)
		{
			$obj_validator = $this->arr_validators[$i];
			$obj_validator->validate();
		}
	}
}