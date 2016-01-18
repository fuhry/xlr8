<?php

namespace fuhry\XLR8\Models;
use fuhry\Framework;
use fuhry\Application;

class HomeworkLog extends Framework\AbstractModel
{
	protected static function getTable()
	{
		return 'homework';
	}
	
	public static function getByAttendanceID(Application\XLR8 $App, $attID)
	{
		return self::loadWhere($App, 'attendance_id = ? AND amount > 0', [ $attID ]);
	}
}

