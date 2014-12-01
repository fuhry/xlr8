<?php

namespace fuhry\Framework;
use fuhry\Application;

class Router
{
	private $App;
	private $AppID;
	private $URI;
	
	public function __construct(Application\AbstractApplication $App, $URI)
	{
		$this->App = $App;
		$App->setRouter($this);
		$appClass = get_class($App);
		$this->AppID = substr($appClass, strrpos($appClass, '\\') + 1);
		$this->URI = explode('/', $URI);
	}
	
	public function execute()
	{
		if ( isset($this->URI[0]) && $this->URI[0] === 'API' ) {
			return $this->executeAPI();
		}
		$app_namespace = "fuhry\\{$this->AppID}\\Controllers\\";
		$uri = $this->URI;
		$controllerClass = false;
		$controllerMethod = false;
		$controllerParams = [];
		while ( count($uri) ) {
			$className = rtrim($app_namespace . implode('\\', $uri), '\\');
			if ( class_exists($className) ) {
				$controllerClass = $className;
				break;
			}
			array_pop($uri);
		}
		
		if ( !$controllerClass ) {
			$controllerClass = $app_namespace . $this->App->getDefaultController();
		}
		
		if ( !class_exists($controllerClass) ) {
			$this->error404("The controller \"$controllerClass\" was not found.");
		}
		
		$controller = new $controllerClass($this->App);
		
		if ( isset($this->URI[ count($uri) ]) ) {
			$method = $this->URI[ count($uri) ];
			if ( method_exists($controller, $method) ) {
				$controllerMethod = $method;
				$controllerParams = array_slice($this->URI, count($uri) + 1);
			}
			else {
				$controllerMethod = $controller->getDefaultMethod();
				$controllerParams = array_slice($this->URI, count($uri));
			}
		}
		else {
			$controllerMethod = $controller->getDefaultMethod();
		}
		
		if ( !method_exists($controller, $controllerMethod) ) {
			$this->error404("The controller \"$controllerClass\" does not have a method named \"$controllerMethod\".");
		}
		
		if ( !$this->App->verifyAuthorization($controllerClass, $controllerMethod) ) {
			$this->App->handleAuthorizationFailure();
		}
		
		call_user_func_array([$controller, $controllerMethod], $controllerParams);
	}
	
	private function executeAPI()
	{
		header('Content-type: application/json');
		
		$uri = array_values(array_slice($this->URI, 1));
		
		$app_namespace = "fuhry\\{$this->AppID}\\API\\";
		$controllerClass = false;
		$controllerMethod = false;
		$controllerParams = [];
		while ( count($uri) ) {
			$className = rtrim($app_namespace . implode('\\', $uri), '\\');
			if ( class_exists($className) ) {
				$controllerClass = $className;
				break;
			}
			array_pop($uri);
		}
		
		if ( $controllerClass && class_exists($controllerClass) ) {
			$controller = new $controllerClass($this->App);
			
			if ( isset($this->URI[ count($uri)+1 ]) ) {
				$method = $this->URI[ count($uri)+1 ];
				if ( method_exists($controller, $method) ) {
					$controllerMethod = $method;
					$controllerParams = array_slice($this->URI, count($uri) + 2);
				}
			}
			
			if ( $controllerMethod && method_exists($controller, $controllerMethod) ) {
				$result = call_user_func_array([$controller, $controllerMethod], $controllerParams);
				echo json_encode($result);
			}
			else {
				$this->sendJSONError("Method not found in API class $controllerClass: $controllerMethod");
			}
		}
		else {
			$this->sendJSONError("Unable to resolve: " . implode('/', $this->URI));
		}
	}
	
	private function error404($str = '')
	{
		header('HTTP/1.1 404 Not Found');
		$this->App->showError("Not found", "The application could not resolve the provided request. $str");
		exit;
	}
	
	private function sendJSONError($str)
	{
		header('HTTP/1.1 500 Internal Server Error');
		echo json_encode($str);
	}
	
	public function getApplicationBaseURI()
	{
		return substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
	}
	
	public function redirect($uri)
	{
		$uri = $this->getApplicationBaseURI() . '/' . $uri;
		
		header('HTTP/1.1 302 Found');
		header("Location: $uri");
		
		exit;
	}
}
