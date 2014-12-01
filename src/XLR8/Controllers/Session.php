<?php

namespace fuhry\XLR8\Controllers;
use fuhry\Framework;

class Session extends Framework\AbstractController
{
	public static function getDefaultMethod()
	{
		return 'Login';
	}
	
	public function Login()
	{
		if ( isset($_POST['email']) && isset($_POST['password']) ) {
			$result = $this->App->getSessionManager()->login($_POST['email'], $_POST['password']);
			
			if ( $result ) {
				$this->App->queueMessage(E_NOTICE, "Logged in successfully. Welcome, {$result->get('given_name')}!");
				$this->App->getRouter()->redirect('Console');
			}
			
			$this->App->queueMessage(E_ERROR, "Your e-mail address or password was invalid.");
		}
		
		$Smarty = $this->App->getSmarty();
		$Smarty->assign('page', 'Login');
		$Smarty->display("Page/Full.tpl");
	}
	
	public function Logout()
	{
		$this->App->getSessionManager()->logout();
		$this->App->getRouter()->redirect('Session/Login');
	}
	
	public function ManageAccount()
	{
		if ( !empty($_POST) ) {
			$SM = $this->App->getSessionManager();
			$User = $SM->getLoggedInUser();
			
			$passwordValid = !empty($_POST['password']) ? password_verify($_POST['password']['old'], $User->get('password')) : false;
			
			if ( !empty($_POST['password']['new']) && !empty($_POST['password']['confirm']) ) {
				if ( $_POST['password']['new'] !== $_POST['password']['confirm'] ) {
					$this->App->queueMessage(E_ERROR, "The password and confirmation you entered did not match, so your password was not changed.");
				}
				else if ( strlen($_POST['password']['new']) < 6 ) {
					$this->App->queueMessage(E_ERROR, "Your new password needs to be at least 6 characters long. Your password was not changed.");
				}
				else if ( !$passwordValid ) {
					$this->App->queueMessage(E_ERROR, "You did not enter your old password correctly. Your password was not changed.");
				}
				else {
					$this->App->queueMessage(E_NOTICE, "Your password has been changed.");
					$User->set('password', password_hash($_POST['password']['new'], PASSWORD_BCRYPT));
				}
			}
			
			if ( $_POST['email'] !== $User->get('email') ) {
				if ( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
					$this->App->queueMessage(E_ERROR, "An invalid e-mail address was entered. Your e-mail address was not changed.");
				}
				else if ( !$passwordValid ) {
					$this->App->queueMessage(E_ERROR, "You did not enter your old password correctly. Your e-mail address was not changed.");
				}
				else {
					$this->App->queueMessage(E_NOTICE, "Your e-mail address has been changed.");
					$User->set('email', $_POST['email']);
				}
			}
			
			$User->update();
		}
		
		$Smarty = $this->App->getSmarty();
		$Smarty->assign('page', 'Session/ManageAccount');
		$Smarty->display("Page/Full.tpl");
	}
}

