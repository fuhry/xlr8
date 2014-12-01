<?php

namespace fuhry\Database\Types;

class Text extends AbstractType
{
	private $maxlens = [
			'tinytext' => (2**8)-1,
			'text' => (2**16)-1,
			'mediumtext' => (2**24)-1,
			'longtext' => (2**32)-1
		];
	
	private $maxlen;
	
	public function getTypeRegexp()
	{
		return '/^(tiny|medium|long)?text$/i';
	}
	
	public function loadColumnDefinition($column)
	{
		$this->maxlen = $this->maxlens[ strtolower($column['Type']) ];
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
		
		if ( $value !== null && strlen($value) > $this->maxlen ) {
			return false;
		}
		
		return true;
	}
	
	public function cast($value)
	{
		return strval($value);
	}
}

