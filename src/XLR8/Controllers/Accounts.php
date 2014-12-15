<?php

namespace fuhry\XLR8\Controllers;
use fuhry\Framework;
use fuhry\XLR8\Models;

class Accounts extends Framework\AbstractController
{
	public static function getDefaultMethod()
	{
		return 'UserList';
	}
	
	public function UserList()
	{
		$Smarty = $this->App->getSmarty();
	}
}

