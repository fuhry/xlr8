<?php

namespace fuhry\Application;
use fuhry\Framework;

use Smarty;

use LogicException;

abstract class AbstractApplication
{
	protected $DB;
	protected $Router;
	
	abstract public function getName();
	abstract public function getDefaultController();
	
	final public function getDatabaseConnection()
	{
		return $this->DB;
	}
	
	final public function setRouter(Framework\Router $router)
	{
		$this->Router = $router;
	}
	
	final public function getRouter()
	{
		return $this->Router;
	}
	
	final public function getSmarty()
	{
		static $Smarty = false;
		
		if ( !is_object($Smarty) ) {
			if ( !($this->Router instanceof Framework\Router) ) {
				throw new LogicException("Cannot get a Smarty instance before reaching a controller!");
			}
			
			$clsname = get_class($this);
			$AppID = substr($clsname, strrpos($clsname, '\\')+1);
			
			$Smarty = new Smarty;
			$Smarty->setTemplateDir($templateDir = ROOT . "src/$AppID/Views");
			$Smarty->setCompileDir(ROOT . 'temp/templates-compiled');
			$Smarty->setConfigDir($templateDir);
			$Smarty->setCacheDir(ROOT . 'temp/templates-cached');
			
			$Smarty->assign('app_root', $this->Router->getApplicationBaseURI());
			$Smarty->assign('App', $this);
			
			$this->assignSmartyVariables($Smarty);
		}
		
		return $Smarty;
	}
	
	protected function assignSmartyVariables(Smarty $Smarty)
	{
	}
	
	public function showError($title, $body)
	{
		echo "<h1>$title</h1>";
		echo "<p>$body</p>";
		
		exit;
	}
	
	public function verifyAuthorization($class, $method)
	{
		return true;
	}
	
	public function handleAuthorizationFailure()
	{
		// ...
	}
}
