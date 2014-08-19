<?php
/**
 * Checks a database column to see if the value is unique
 * Optional parameters $a_str_idColumnName and $a_str_idColumnValue exsist if you are editing a value
 * @package validation
 * @subpackage validators
 */
class UniqueValueInColumnValidator extends Validator
{
	// object variables
	var $str_fieldName;
	var $str_fieldValue;
	var $str_errorMessage;
	var $str_tableName;
	var $str_columnName;
	var $str_idColumnName;
	var $str_idColumnValue;
	var $obj_validationSet;
	
	// constructor
	/**
	 * create new UniqueValueInColumnValidator object
	 *
	 * @param string $a_str_fieldName
	 * @param string $a_str_tableName
	 * @param string $a_str_columnName
	 * @param string $a_str_idColumnName
	 * @param string $a_str_idColumnValue
	 * @param string $a_str_errorMessage
	 * @param string $a_str_fieldValue
	 * @return UniqueValueInColumnValidator
	 */
	function UniqueValueInColumnValidator($a_str_fieldName, $a_str_tableName, $a_str_columnName, $a_str_idColumnName = null, $a_str_idColumnValue = null, $a_str_errorMessage = null, $a_str_fieldValue = null)
	{
		$this->str_fieldName = $a_str_fieldName;
		$this->str_fieldValue = $a_str_fieldValue;
		$this->str_tableName = $a_str_tableName;
		$this->str_columnName = $a_str_columnName;
		$this->str_idColumnName = $a_str_idColumnName;
		$this->str_idColumnValue = $a_str_idColumnValue;
		
		// check for null field value
		if($this->str_fieldValue == null)
		{
			$this->str_fieldValue = $this->getValueByFieldName($this->str_fieldName);
		}
		
		// check for default error message
		if($a_str_errorMessage == null)
		{
			$this->str_errorMessage = "Value already exsists";
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
		//$obj_DBConn = $this->obj_validationSet->getDBConn();
		
		
		// check that the correct number of parameters were passed
		if(($this->str_idColumnName == null && $this->str_idColumnValue != null) || ($this->str_idColumnName != null && $this->str_idColumnValue == null))
		{
			echo '*Validation error in UniqueValueInColumnValidator - improper number of parameters passed*';
			return null;
		}
		
		// check if extra params were passed
		if($this->str_idColumnName == null && $this->str_idColumnValue == null)
		{
			$sql = "SELECT $this->str_columnName FROM $this->str_tableName WHERE $this->str_columnName = '$this->str_fieldValue' LIMIT 1";
			//$rs = $obj_DBConn->query($sql);
			$rs = mysql_query($sql);
			
			
			if(mysql_num_rows($rs) > 0)//$obj_DBConn->getNumRows($rs) > 0)
			{
				$this->obj_validationSet->addValidationError(new ValidationError($this->str_fieldName, $this->str_errorMessage));
			}
		}
		else
		{
			$sql = "SELECT $this->str_columnName FROM $this->str_tableName WHERE $this->str_columnName = '$this->str_fieldValue' AND $this->str_idColumnName != '$this->str_idColumnValue' LIMIT 1";
			//$rs = $obj_DBConn->query($sql);
			$rs = mysql_query($sql);
			
			if(mysql_num_rows($rs) > 0)//$obj_DBConn->getNumRows($rs) > 0)
			{
				$this->obj_validationSet->addValidationError(new ValidationError($this->str_fieldName, $this->str_errorMessage));
			}
		}
	}
}
?>