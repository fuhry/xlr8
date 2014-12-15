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
		$this->App->getSessionManager()->assertRole(['administrator', 'leader']);
		
		$Smarty = $this->App->getSmarty();
		$Smarty->assign('page', 'SignIn/Student');
		$Smarty->display("Page/Barebones.tpl");
	}
	
	public function StudentPost()
	{
		$this->App->getSessionManager()->assertRole(['administrator', 'leader']);
		
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
			$homework->set('need_pc', isset($_POST['pc'][$subject]) ? 1 : 0);
			$homework->insert();
		}
		
		$Smarty = $this->App->getSmarty();
		$Smarty->assign('page', 'SignIn/StudentPost');
		$Smarty->assign('signin_user', $user->getAll());
		$Smarty->display("Page/Barebones.tpl");
	}
	
	public function StudentNew()
	{
		$this->App->getSessionManager()->assertRole(['administrator', 'leader']);
		
		$Smarty = $this->App->getSmarty();
		
		if ( !empty($_POST) ) {
			$errors = [];
			
			if ( trim($_POST['given_name']) === '' ) {
				$errors[] = 'Please enter your first name.';
			}
			
			if ( trim($_POST['surname']) === '' ) {
				$errors[] = 'Please enter your last name.';
			}
			
			if ( intval($_POST['grade']) < 1 || intval($_POST['grade']) > 12 ) {
				$errors[] = 'Please set your grade (1-12).';
			}
			
			if ( empty($errors) ) {
				$user = new Models\User($this->App);
				
				$user->set('given_name', $_POST['given_name'])
					 ->set('surname', $_POST['surname'])
					 ->set('grade', intval($_POST['grade']))
					 ->set('role', 'student');
				
				try {
					$user->insert();
					
					$attendance = new Models\Attendance($this->App);
					$attendance->set('user_id', $user->getID());
					$attendance->set('date', date('Y-m-d'));
					$attendance->set('behavior_score', 5);
					$attendance->insert();
					
					$Smarty->assign('page', 'SignIn/StudentPost');
					$Smarty->assign('signin_user', $user->getAll());
					$Smarty->display("Page/Barebones.tpl");
					
					return;
				}
				catch ( Database\Exception $e ) {
					$this->App->queueMessage(E_ERROR, "Looks like registration failed. There's probably already a student with the name {$_POST['given_name']} {$_POST['surname']}. If that's the case, please sign in on the sign in page");
				}
			}
			else {
				foreach ( $errors as $errmsg ) {
					$this->App->queueMessage(E_ERROR, $errmsg);
				}
			}
		}
		
		$Smarty->assign('page', 'SignIn/StudentNew');
		$Smarty->display("Page/Barebones.tpl");
	}
}

