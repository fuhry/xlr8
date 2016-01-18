<?php

namespace fuhry\Application;
use fuhry\Database;
use fuhry\XLR8\Configuration as XLR8_Configuration;
use fuhry\XLR8\Models;
use PDO;
use Smarty;

class XLR8 extends AbstractApplication
{
	private $SessionManager;
	
	public function __construct(PDO $DB = null)
	{
		if ( $DB === null ) {
			$DB = Database\ConnectionFactory::getDatabaseConnection(new XLR8_Configuration);
		}
		
		$this->DB = $DB;
		$this->SessionManager = new \fuhry\XLR8\SessionManager($this);
		
		set_exception_handler([$this, '_handleException']);
	}
	
	public function getName()
	{
		return 'Urban Impact XLR(8)';
	}
	
	public function getDefaultController()
	{
		return 'Console';
	}
	
	public function showError($title, $body)
	{
		$Smarty = $this->getSmarty();
		
		$Smarty->assign('page', 'Error');
		$Smarty->assign('title', $title);
		$Smarty->assign('body', $body);
		
		$Smarty->display('Page/Full.tpl');
	}
	
	public function _handleException(\Exception $e)
	{
		$this->showError('Exception caught in application', get_class($e) . ': ' . $e->getMessage());
	}
	
	public function getSessionManager()
	{
		return $this->SessionManager;
	}
	
	public function verifyAuthorization($class, $method)
	{
		if ( $class === 'fuhry\\XLR8\\Controllers\\Session' && $method == 'Login' ) {
			return true;
		}
		
		if ( $this->SessionManager->getLoggedInUser() instanceof Models\User ) {
			// FIXME
			return true;
		}
		
		return false;
	}
	
	public function handleAuthorizationFailure()
	{
		$this->Router->redirect('Session/Login');
	}
	
	protected function assignSmartyVariables(Smarty $Smarty)
	{
		$Smarty->assign('user', $this->SessionManager->getLoggedInUser());
		if ( !isset($_SESSION['messages']) ) {
			$_SESSION['messages'] = [];
		}
		
		$Smarty->assign('moods', [
				'angry' => [
					'description' => 'Grouchy',
					'emoji' => '&#x1f624;',
					'btn' => 'danger'
				],
				'neutral' => [
					'description' => 'Meh.',
					'emoji' =>'&#x1f610;',
					'btn' => 'default'
				],
				'okay' => [
					'description' => 'Alright',
					'emoji' => '&#x1f642;',
					'btn' => 'info'
				],
				'great' => [
					'description' => 'Awesome!',
					'emoji' => '&#x1f600;',
					'btn' => 'success'
				]
			]);
		
		$Smarty->assign('message_levels', [
				E_NOTICE => 'success',
				E_WARNING => 'warning',
				E_ERROR => 'danger'
			]);
		
		$Smarty->assign('messages', $_SESSION['messages']);
		
		$_SESSION['messages'] = [];
	}
	
	public function queueMessage($level, $msg)
	{
		if ( !isset($_SESSION['messages']) ) {
			$_SESSION['messages'] = [];
		}
		
		$_SESSION['messages'][] = [
				'level' => $level,
				'message' => $msg
			];
	}
}
