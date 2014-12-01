<?php

namespace fuhry\Database\Types;

class Character extends AbstractType
{
	private $maxlen;
	private $variable_length;
	
	public function getTypeRegexp()
	{
		return '/^(var)?char\(([0-9]+)\)$/i';
	}
	
	public function loadColumnDefinition($column)
	{
		preg_match($this->getTypeRegexp(), $column['Type'], $match);
		
		$this->variable_length = $match[1] === 'var';
		$this->maxlen = intval($match[2]);
		
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
		
		if ( $this->variable_length ) {
			if ( strlen($value) > $this->maxlen ) {
				return false;
			}
		}
		else {
			if ( strlen($value) !== $this->maxlen ) {
				return false;
			}
		}
		
		return true;
	}
	
	public function cast($value)
	{
		return strval($value);
	}
}

