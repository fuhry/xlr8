<?php

namespace fuhry\XLR8\Controllers;
use fuhry\Framework;

class Console extends Framework\AbstractController
{
	public static function getDefaultMethod()
	{
		return 'Home';
	}
	
	public function Home()
	{
		$Smarty = $this->App->getSmarty();
		$Smarty->assign('page', 'Console');
		$Smarty->display("Page/Full.tpl");
	}
}
