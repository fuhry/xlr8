<?php

namespace fuhry\Database;
use PDO;

class ConnectionFactory
{
	public static function getDatabaseConnection(AbstractConfiguration $config)
	{
		list($driver, $host, $user, $password, $database, $options) = [
				$config->getDriver(),
				$config->getHost(),
				$config->getUser(),
				$config->getPassword(),
				$config->getDatabase(),
				$config->getOptions()
			];
		
		$dsn = "$driver:";
		if ( $host !== null ) {
			$dsn .= "host=$host;";
		}
		if ( $database !== null ) {
			$dsn .= "dbname=$database;";
		}
		
		$result = new PDO($dsn, $user, $password, $options);
		
		return $result;
	}
}
