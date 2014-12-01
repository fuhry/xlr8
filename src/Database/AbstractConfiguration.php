<?php

namespace fuhry\Database;

abstract class AbstractConfiguration
{
	protected $driver = null;
	protected $host = null;
	protected $user = null;
	protected $password = null;
	protected $database = null;
	protected $options = [];
	
	final public function getDriver()
	{
		return $this->driver;
	}
	
	final public function getHost()
	{
		return $this->host;
	}
	
	final public function getUser()
	{
		return $this->user;
	}
	
	final public function getPassword()
	{
		return $this->password;
	}
	
	final public function getDatabase()
	{
		return $this->database;
	}
	
	final public function getOptions()
	{
		return $this->options;
	}
}

