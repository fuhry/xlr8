<?php

namespace fuhry\XLR8\Controllers;
use fuhry\Framework;

class Debug extends Framework\AbstractController
{
	public static function getDefaultMethod()
	{
		return null;
	}
	
	public function GetBaseURI()
	{
		echo $this->App->getRouter()->getApplicationBaseURI();
	}
	
	public function testError()
	{
		$this->App->showError('Example error', 'This is some example error text.');
	}
}

