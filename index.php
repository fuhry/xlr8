<?php

use fuhry\Framework\Router;

require 'loader.php';

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

