<?php

namespace fuhry\XLR8\API;
use fuhry\Framework;
use fuhry\XLR8\Models;
use PDO;

class UserLookup extends Framework\AbstractAPI
{
	public function getUsedFirstLetters()
	{
		$results = $this->DB->query('SELECT DISTINCT LOWER(SUBSTR(given_name FROM 1 FOR 1)) AS first_letter FROM users ORDER BY first_letter ASC;');
		return array_values($results->fetchAll(PDO::FETCH_COLUMN));
	}
	
	public function getStudentsByFirstLetter($firstLetter)
	{
		$users = Models\User::getStudentsStartingWithLetter($this->App, $firstLetter);
		
		$results = [];
		foreach ( $users as $user ) {
			$result = $user->getAll();
			
			// prevent data disclosure...
			unset($result['password'], $result['email']);
			
			$results[] = $result;
		}
		
		return $results;
	}
}
