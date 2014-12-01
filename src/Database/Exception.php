<?php

namespace fuhry\Database;
use PDOStatement;

class Exception extends \Exception
{
	public function __construct(PDOStatement $DB)
	{
		list($errcode,,$errmsg) = $DB->errorInfo();
		$this->message = "Database error (code $errcode): $errmsg";
	}
}
