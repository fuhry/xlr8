<?php

namespace fuhry\Database\Types;

class Date extends AbstractType
{
	public function getTypeRegexp()
	{
		return '/^date$/i';
	}
	
	public function loadColumnDefinition($column)
	{
		$this->null_ok = $column['Null'] === 'YES';
		
		if ( !empty($column['Default']) ) {
			$this->defaultValue = $this->cast($column['Default']);
		}
	}
	
	public function validate($value)
	{
		if ( $value === null && $this->null_ok ) {
			return true;
		}
		else if ( $value === null && !$this->null_ok ) {
			return false;
		}
		
		if ( !is_string($value) ) {
			return false;
		}
		
		if ( !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $value) ) {
			return false;
		}
		
		return true;
	}
	
	public function cast($value)
	{
		return strval($value);
	}
}

