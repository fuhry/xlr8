<?php

namespace fuhry\Database\Types;

abstract class AbstractType
{
	protected $null_ok = true;
	protected $defaultValue = null;
	
	abstract public function getTypeRegexp();
	abstract public function loadColumnDefinition($column);
	abstract public function validate($value);
	abstract public function cast($value);
	
	final public function getDefault()
	{
		return $this->defaultValue;
	}
	
	final public function isNullOk()
	{
		return !!$this->null_ok;
	}
}
