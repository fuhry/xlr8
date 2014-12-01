<?php

namespace fuhry\XLR8\Models;
use fuhry\Framework;
use fuhry\Application;
use fuhry\Database;
use PDO;
use InvalidArgumentException;
use LogicException;

class User extends Framework\AbstractModel
{
	protected static function getTable()
	{
		return 'users';
	}
	
	public static function getStudentsStartingWithLetter(Application\XLR8 $App, $letter)
	{
		if ( strlen($letter) !== 1 ) {
			throw new InvalidArgumentException("Please provide a single lowercase letter");
		}
		
		$letter = strtolower($letter);
		
		return self::loadWhere($App, 'role = ? AND LOWER(given_name) LIKE ?', [ 'student', "{$letter}%" ]);
	}
	
	public function getChildren()
	{
		if ( $this->get('role') !== 'parent' ) {
			throw new LogicException("Cannot get children of a user when that user is not a parent.");
		}
		
		$stmt = $this->DB->prepare("SELECT users.* FROM users LEFT JOIN guardians ON ( guardians.child_id = users.user_id ) WHERE guardians.parent_id = ?;");
		if ( !$stmt->execute([$this->getID()]) ) {
			throw new Database\Exception($stmt);
		}
		
		$results = [];
		while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
			$results[] = new self($this->App, $row);
		}
		
		return $results;
	}
}

