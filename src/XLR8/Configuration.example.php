<?php

namespace fuhry\XLR8;
use PDO;
use fuhry\Database;

class Configuration extends Database\AbstractConfiguration {
	protected $driver = 'mysql';
	protected $host = 'localhost';
	protected $user = 'xlr8';
	protected $password = 'ItSaSeCrEt123';
	protected $database = 'xlr8';
	protected $options = [
			PDO::ATTR_PERSISTENT => true
		];
}
