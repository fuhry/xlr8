<?php

namespace fuhry\XLR8\Controllers;
use fuhry\Framework;
use fuhry\XLR8\Models;

class Accounts extends Framework\AbstractController
{
	public static function getDefaultMethod()
	{
		return 'UserList';
	}
	
	public function UserList($role = 'student')
	{
		$this->App->getSessionManager()->assertRole(['administrator']);
		
		$Smarty = $this->App->getSmarty();
		
		$users = Models\User::loadWhere($this->App, '1 ORDER BY role DESC, surname ASC, given_name ASC');
		
		$Smarty->assign('users', $users);
		
		$Smarty->assign('page', 'Accounts/List');
		$Smarty->display("Page/Full.tpl");
	}
	
	public function Edit($user_id)
	{
		$this->App->getSessionManager()->assertRole(['administrator']);
		
		$currentUser = $this->App->getSessionManager()->getLoggedInUser();
		
		$user = new Models\User($this->App, intval($user_id));
		
		if ( !empty($_POST) ) {
			try {
				$role = $_POST['attrs']['role'];
				
				if ( $user->getID() === $currentUser->getID() && $role !== $currentUser->get('role') ) {
					throw new \InvalidArgumentException("You cannot change the role of your own user account.");
				}
				
				$user->set('grade', null);
				foreach ( $_POST['attrs'] as $attr => $value ) {
					if ( $role !== 'student' && $attr === 'grade' ) {
						continue;
					}
					
					if ( $user->getID() === $currentUser->getID() && $attr === 'email' ) {
						// prevent changing own email address
						continue;
					}
					
					$user->set($attr, $value);
				}
				
				// password update?
				if ( $user->getID() !== $currentUser->getID() ) {
					if ( !empty($_POST['password']['new']) && !empty($_POST['password']['login_ok']) ) {
						if ( $_POST['password']['new'] !== $_POST['password']['confirm'] ) {
							throw new \InvalidArgumentException("The passwords you entered did not match.");
						}
						
						if ( strlen($_POST['password']['new']) < 6 ) {
							throw new \InvalidArgumentException("Your password must be at least 6 characters long.");
						}
						
						$user->set('password', password_hash($_POST['password']['new'], PASSWORD_DEFAULT));
					}
				}
				
				$user->update();
				$this->App->queueMessage(E_NOTICE, "Updated user \"{$user->get('given_name')} {$user->get('surname')}\".");
				$this->App->getRouter()->redirect('Accounts');
			}
			catch ( \Exception $e ) {
				$this->App->queueMessage(E_ERROR, $e->getMessage());
			}
		}
		
		$Smarty = $this->App->getSmarty();
		
		$Smarty->assign('userToEdit', $user);
		
		$Smarty->assign('page', 'Accounts/Edit');
		$Smarty->display("Page/Full.tpl");
	}
	
	public function Delete($user_id)
	{
		$Smarty = $this->App->getSmarty();
		
		$this->App->getSessionManager()->assertRole(['administrator']);
		
		$currentUser = $this->App->getSessionManager()->getLoggedInUser();
		$user = new Models\User($this->App, intval($user_id));
		
		if ( $currentUser->getID() === $user->getID() ) {
			throw new \Exception("You cannot delete your own user account.");
		}
		
		if ( !empty($_POST['confirm']) ) {
			$user->delete();
			
			$this->App->queueMessage(E_NOTICE, "{$user->get('given_name')} {$user->get('surname')}'s account was deleted.");
			$this->App->getRouter()->redirect('Accounts');
		}
		
		$Smarty->assign('userToDelete', $user);
		$Smarty->assign('page', 'Accounts/Delete');
		$Smarty->display("Page/Full.tpl");
	}
}

