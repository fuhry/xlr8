<?php

namespace fuhry\XLR8\Controllers;
use fuhry\Database;
use fuhry\Framework;
use fuhry\XLR8\Models;

class SignIn extends Framework\AbstractController
{
	public static function getDefaultMethod()
	{
		return 'Student';
	}
	
	public function Student()
	{
		$Smarty = $this->App->getSmarty();
		$Smarty->assign('page', 'SignIn/Student');
		$Smarty->display("Page/Barebones.tpl");
	}
	
	public function StudentPost()
	{
		// create attendance record
		$user = new Models\User($this->App, intval($_POST['user_id']));
		
		try
		{
			$attendance = new Models\Attendance($this->App);
			$attendance->set('user_id', intval($_POST['user_id']));
			$attendance->set('date', date('Y-m-d'));
			$attendance->set('behavior_score', 5);
			$attendance->insert();
		
			$attendanceID = $attendance->getID();
		}
		catch ( Database\Exception $e ) {
			$this->App->showError('Whoops! You\'re already signed in for today.', 'It looks like you\'ve already signed in for today. That\'s alright - come on in!');
			return;
		}
		
		foreach ( $_POST['subject'] as $subject => $amount ) {
			$homework = new Models\HomeworkLog($this->App);
			$homework->set('attendance_id', $attendanceID);
			$homework->set('subject', $subject);
			$homework->set('amount', intval($amount));
			$homework->insert();
		}
		
		$Smarty = $this->App->getSmarty();
		$Smarty->assign('page', 'SignIn/StudentPost');
		$Smarty->assign('signin_user', $user->getAll());
		$Smarty->display("Page/Barebones.tpl");
	}
	
	public function StudentNew()
	{
		$Smarty = $this->App->getSmarty();
		$Smarty->assign('page', 'SignIn/StudentNew');
		$Smarty->display("Page/Barebones.tpl");
	}
}

