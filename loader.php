<?php

error_reporting(E_ALL & ~E_STRICT);

global $Composer;
$Composer = require "vendor/autoload.php";

define('ROOT', dirname(__FILE__) . '/');

