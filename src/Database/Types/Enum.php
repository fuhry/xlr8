<?php

namespace fuhry\Database\Types;

class Enum extends AbstractType
{
	private $values = [];
	
	public function getTypeRegexp()
	{
		return '/^enum\(\'([^\']+)\'(?:,\'(?:[^\']+)\')*\)$/i';
	}
	
	public function loadColumnDefinition($column)
	{
		$this->values = [];
		$valueList = preg_replace('/\)$/', '', preg_replace('/^enum\(/', '', $column['Type']));
		preg_match_all("/'([^']+)'/", $valueList, $matches);
		$this->values = $matches[1];
		
		$this->null_ok = $column['Null'] === 'YES';
		
		if ( !empty($column['Default']) ) {
			$this->defaultValue = $this->cast($column['Default']);
		}
	}
	
	public function validate($value)
	{
		if ( $value === null && !$this->null_ok ) {
			return false;
		}
		
		if ( !is_string($value) && $value !== null ) {
			return false;
		}
		
		if ( $value !== null && !in_array($value, $this->values) ) {
			return false;
		}
		
		return true;
	}
	
	public function cast($value)
	{
		return strval($value);
	}
}

