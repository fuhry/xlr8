<?php

namespace fuhry\Database;
use fuhry\Framework\Enumerator;

use InvalidArgumentException;

class ColumnFactory
{
	public static function loadColumn($column)
	{
		static $types = false;
		if ( !is_array($types) ) {
			$types = Enumerator::getClasses('fuhry\\Database\\Types', [ ['abstract' => false] ]);
		}
		
		$Type = false;
		
		foreach ( $types as $type ) {
			$typeInstance = new $type;
			if ( preg_match($typeInstance->getTypeRegexp(), $column['Type']) ) {
				$Type = $typeInstance;
				break;
			}
		}
		
		if ( !($Type instanceof Types\AbstractType) ) {
			throw new InvalidArgumentException("Specified column \"{$column['Field']}\" is of an unsupported type: \"{$column['Type']}\"");
		}
		
		$Type->loadColumnDefinition($column);
		return $Type;
	}
}
