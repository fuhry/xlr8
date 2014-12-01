<?php

namespace fuhry\Framework;
use fuhry\Application;

abstract class AbstractAPI
{
	protected $App;
	protected $DB;
	
	final public function __construct(Application\AbstractApplication $App)
	{
		$this->App = $App;
		$this->DB = $App->getDatabaseConnection();
	}
}

