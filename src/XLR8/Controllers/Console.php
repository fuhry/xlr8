<?php

namespace fuhry\XLR8\Controllers;
use fuhry\Framework;
use fuhry\Database;
use fuhry\XLR8\Models;

class Console extends Framework\AbstractController
{
	public static function getDefaultMethod()
	{
		return 'Home';
	}
	
	public function Home()
	{
		$Smarty = $this->App->getSmarty();
		
		$Smarty->assign('page', 'Console');
		$Smarty->display("Page/Full.tpl");
	}
	
	public function Attendance($date = null)
	{
		// attendance calendar and data for a specific day
		$Smarty = $this->App->getSmarty();
		$DB = $this->App->getDatabaseConnection();
		
		// if the date is NULL, we set it to the current year and month
		if ( !is_string($date) ) {
			$date = date('Y-m');
		}
		
		if ( !preg_match('/^([0-9]{4})-([0-9]{2})(?:-([0-9]{2}))?$/', $date, $match) ) {
			$this->App->queueMessage(E_ERROR, "Invalid date provided.");
			$this->App->getRouter()->redirect('Console/Attendance');
		}
		
		// if a year and month are provided, show calendar
		// if a date is provided, show attendance data
		if ( empty($match[3]) ) {
			// year and month provided - show calendar
			list(, $year, $month) = $match;
			$basetime = mktime(0, 0, 0, $month, 1, $year);
			$Smarty->assign('date', $basetime);
			
			$Smarty->assign('page', 'Attendance/Calendar');
			$month_start_dow = date('w', $basetime);
			$last_dom = date('j', mktime(0, 0, 0, intval($month)+1, 0, $year));
			
			$Smarty->assign('month_start_dow', $month_start_dow);
			$Smarty->assign('last_dom', $last_dom);
			
			$next_month = mktime(0, 0, 0, intval($month)+1, 1, $year);
			$prev_month = mktime(0, 0, 0, intval($month)-1, 1, $year);
			$Smarty->assign('next_month', $next_month);
			$Smarty->assign('prev_month', $prev_month);
			
			// get attendance numbers
			$stmt = $DB->prepare('SELECT UNIX_TIMESTAMP(`date`) AS `date`, COUNT(*) AS attendance_count FROM attendance WHERE `date` >= :month_start AND `date` <= :month_end GROUP BY `date`;');
			
			$month_start = date('Y-m-d', $basetime);
			$month_end = date('Y-m-d', mktime(0, 0, 0, intval($month)+1, 0, $year));
			
			if ( !$stmt->execute(['month_start' => $month_start, 'month_end' => $month_end]) ) {
				throw new Database\Exception($stmt);
			}
			
			$attendance = [];
			while ( $row = $stmt->fetch($DB::FETCH_ASSOC) ) {
				$attendance[ intval($row['date']) ] = intval($row['attendance_count']);
			}
			
			$Smarty->assign('attendance', $attendance);
		}
		else {
			// full date provided - show attendance data
			list(, $year, $month, $day) = $match;
			$year = intval($year);
			$month = intval($month);
			$day = intval($day);
			$Smarty->assign('page', 'Attendance/DateView');
			$Smarty->assign('date', $basetime = mktime(0, 0, 0, $month, $day, $year));
			
			$datefmt = date('Y-m-d', $basetime);
			$stmt = $DB->prepare('SELECT attendance.*, users.* FROM attendance LEFT JOIN users USING ( user_id ) WHERE attendance.`date` = :date ORDER BY surname ASC, given_name ASC;');
			
			if ( !$stmt->execute(['date' => $datefmt]) ) {
				throw new Database\Exception($stmt);
			}
			
			$records = $students = [];
			while ( $row = $stmt->fetch($DB::FETCH_ASSOC) ) {
				$records[] = new Models\Attendance($this->App, $row);
				$students[ intval($row['user_id']) ] = new Models\User($this->App, $row);
			}
			
			$Smarty->assign('records', $records);
			$Smarty->assign('students', $students);
		}
		$Smarty->display("Page/Full.tpl");
	}
}
