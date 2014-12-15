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
		
		$user = new Models\User($this->App, intval($user_id));
		
		if ( !empty($_POST) ) {
			foreach ( $_POST['attrs'] as $attr => $value ) {
				$user->set($attr, $value);
			}
			
			$user->update();
			$this->App->queueMessage(E_NOTICE, "User information updated.");
		}
		
		$Smarty = $this->App->getSmarty();
		
		$Smarty->assign('userToEdit', $user);
		
		$Smarty->assign('page', 'Accounts/Edit');
		$Smarty->display("Page/Full.tpl");
	}
}

