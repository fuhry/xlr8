<?php

namespace fuhry\XLR8;
use fuhry\Application;
use fuhry\XLR8\Models;

class SessionManager
{
	private $App;
	private $DB;
	
	public function __construct(Application\XLR8 $App)
	{
		$this->App = $App;
		$this->DB = $App->getDatabaseConnection();
		
		session_start();
	}
	
	public function getLoggedInUser()
	{
		static $User = false;
		if ( !isset($_SESSION['user_id']) ) {
			return false;
		}
		
		if ( !is_object($User) ) {
			$User = new Models\User($this->App, intval($_SESSION['user_id']));
		}
		
		return $User;
	}
	
	public function login($email, $password)
	{
		$users = Models\User::loadWhere($this->App, "email = ?", [$email]);
		if ( count($users) ) {
			list($user) = $users;
			
			if ( password_verify($password, $user->get('password')) ) {
				$_SESSION['user_id'] = $user->getID();
				return $user;
			}
		}
		
		return false;
	}
	
	public function logout()
	{
		session_destroy();
	}
}
