<?php
namespace App\Controller\Admin;
use App\Controller\AdminAppController;
use Cake\Event\Event;
use Cake\Network\Exception\ForbiddenException;
use Cake\ORM\TableRegistry;
use Aura\Intl\Exception;

class LoginsController extends AdminAppController {
	public function initialize() {
		parent::initialize();
	}
	
	public function beforeFilter(Event $event) {
		$this->loadComponent('LastAccess');
	}
	
	public function index() {
		parent::move('Please login', 'test_layout', 'login');
	}
	
	public function login() {
		if ($this->request->is('post')) {
			$email = $this->request->data['email'];
			$password = $this->request->data['password'];
			if (empty($email) || empty($password)) {
				throw new ForbiddenException();
				return;
			}
			$userLogin = $this->Users->login($email, $password);
			if (empty($userLogin)) {
				parent::setMessage(__('User and password is not correct !'));
				$this->redirect(['controller' => 'Logins', 'action' => 'index']);
				return;
			}
			
			//Save session for user
			$sessionNo = $this->makeSessionNo();
			$userSessionTable = TableRegistry::get('UserSessions');
			$userSession = $userSessionTable->newEntity();
			$userSession->id = $sessionNo;
			$userSession->userId = $userLogin->userId;
			$userSession->limit_time = strtotime(\Constants::SESSION_VALID_SECOND.' seconds '.date('Y-m-d H:i:s'));
			//pr(strtotime(\Constants::SESSION_VALID_SECOND.' seconds '.date('Y-m-d H:i:s')));
			if ($userSessionTable->save($userSession)) {
				//Save session information into browser
				$this->setSessionNo($sessionNo);
				$url = $this->LastAccess->getLastAccess();
				if (is_null($url) || empty($url)) {
					$this->redirect(['controller' => 'Users', 'action' => 'index']);
				} else {
					$this->redirect($url);
				}
			} else {
				throw Exception(__('Error when saving session into database, please try again !'));
			}
		}
	}
}
?>