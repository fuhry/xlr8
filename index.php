<?php

use fuhry\Framework\Router;

require 'loader.php';

// fix REDIRECT variables
foreach ( $_SERVER as $key => $value ) {
	if ( preg_match('/^REDIRECT_/', $key) ) {
		$newKey = preg_replace('/^(REDIRECT_)+/', '', $key);
		if ( !isset($_SERVER[$newKey]) ) {
			$_SERVER[$newKey] =& $_SERVER[$key];
		}
	}
	else if ( preg_match('/^ORIG_/', $key) ) {
		$newKey = preg_replace('/^(ORIG_)+/', '', $key);
		if ( !isset($_SERVER[$newKey]) ) {
			$_SERVER[$newKey] =& $_SERVER[$key];
		}
	}
}

// parse application file
$application = json_decode(file_get_contents(ROOT . 'application.json'), true);

// instanciate application
$appClass = "fuhry\\Application\\{$application['application']}";
$App = new $appClass;

// get URI
$pi = isset($_SERVER['PATH_INFO']) ? ltrim($_SERVER['PATH_INFO'], '/') : '';

// pass application and URI to the router and load the page
$router = new Router($App, $pi);
$router->execute();

