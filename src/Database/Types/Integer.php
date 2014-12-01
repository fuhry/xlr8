<?php

namespace fuhry\Database\Types;

class Integer extends AbstractType
{
	// in bits
	private $int_sizes = [
			'tiny' => 8,
			'small' => 16,
			'medium' => 24,
			'long' => 32,
			'big' => 64
		];
		
	private $min, $max;
	
	private $auto_increment = false;
	
	public function getTypeRegexp()
	{
		// subpattern markings:
		//        1                          2            3
		return '/^(tiny|small|medium|big)?int(\([0-9]+\))?( unsigned)?$/i';
	}
	
	public function loadColumnDefinition($column)
	{
		preg_match($this->getTypeRegexp(), strtolower($column['Type']), $match);
		
		$size = empty($match[1]) ? 'long' : $match[1];
		$signed = trim($match[3]) !== 'unsigned';
		
		if ( $signed ) {
			$this->min = -(2 ** ($this->int_sizes[$size]-1));
			$this->max = (2 ** ($this->int_sizes[$size]-1)) - 1;
		}
		else {
			$this->min = 0;
			$this->max = (2 ** $this->int_sizes[$size]) - 1;
		}
		
		$this->null_ok = $column['Null'] === 'YES';
		
		// FIXME is the "Extra" column able to contain multiple values?
		$this->auto_increment = $column['Extra'] === 'auto_increment';
		
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
		
		if ( !is_int($value) ) {
			return false;
		}
		
		if ( $value > $this->max || $value < $this->min ) {
			return false;
		}
		
		return true;
	}
	
	public function cast($value)
	{
		return intval($value);
	}
	
	public function isAutoIncrement()
	{
		return $this->auto_increment;
	}
}

