<?php

namespace fuhry\XLR8\Models;
use fuhry\Framework;
use fuhry\Application\XLR8;
use PDO;

class Attendance extends Framework\AbstractModel
{
	protected static function getTable()
	{
		return 'attendance';
	}
	
	public static function getAttendanceOnDate(XLR8 $App, $date)
	{
		$DB = $App->getDatabaseConnection();
		
		$stmt = $DB->prepare("SELECT attendance.*, users.* FROM attendance LEFT JOIN users ON ( attendance.user_id = users.user_id ) WHERE attendance.`date` = ?;");
		if ( !$stmt->execute([$date]) ) {
			throw new Database\Exception($stmt);
		}
		
		$results = [];
		while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
			$results[] = [
					'attendance' => new self($App, $row),
					'user' => new User($App, $row)
				];
		}
		
		return $results;
	}
}

