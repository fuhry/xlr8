<?php

namespace fuhry\Framework;
use fuhry\Application;

abstract class AbstractController
{
	protected $App;
	protected $DB;
	
	final public function __construct(Application\AbstractApplication $App)
	{
		$this->App = $App;
		$this->DB = $App->getDatabaseConnection();
	}
	
	abstract public static function getDefaultMethod();
}

