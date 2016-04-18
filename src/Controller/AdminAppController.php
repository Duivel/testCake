<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
class AdminAppController extends AppController {
	public function initialize() {
		//parent::initialize();
		$this->loadComponent('Flash');
		$this->loadModel('Users');
		$this->loadModel('UserSessions');
		$this->Session = $this->request->session();
	}
	
	/**
	 * load a ctp file 
	 * @param string $pageTitle: Title of the page
	 * @param string $layout: layout file used to load ctp file
	 * @param string $ctpFile: ctp file name
	 */
	protected function move($pageTitle, $layout = NULL, $ctpFile = null) {
		if (is_null($layout) || empty($layout)) {
			
		} else {
			$this->viewBuilder()->layout($layout);
		}
		$this->set('title', $pageTitle);
		if (!is_null($ctpFile)) {
			$this->render($ctpFile);
		}
		return;
	}
	
	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);
		if (!$this->isLogin()) {
			$this->redirect('/'.\Constants::ADMIN_PREFIX);
			return;
		}
	}

	/**
	 * Get session value (need to decrypt) 
	 * @param string $name
	 */
	protected function getSession($name) {
		//$session = $this->request->session();
		return $this->Session->read($name);
	}

	/**
	 * get current administrator's session value
	 * decrypt it
	 */
	protected function getSessionNo() {
		$value = $this->getSession(\Constants::SESSION_ADMIN_NAME);
		return \Crypt::decrypt($value, Configure::read(\Crypt::SESSION_NO_KEY_NAME));
	}

	/**
	 * Encrypt a string and assign it for current administrator's session
	 * @param unknown $value
	 */
	protected function setSessionNo($value) {
		$value = \Crypt::encrypt($value, Configure::read(\Crypt::SESSION_NO_KEY_NAME));
		$this->setSession(\Constants::SESSION_ADMIN_NAME, $value);		
	}

	/**
	 * Assign a session value ($value) for a $name
	 * @param string $name
	 * @param string $value
	 */
	protected function setSession($name, $value) {
		//$session = $this->request->session();
		$this->Session->write($name, $value);
	}

	protected function makeSessionNo() {
		$sessionNo = md5(rand(rand(),rand()));
		$sessionNo .= md5(rand(rand(),rand()));
		$count = $this->UserSessions->findBySessionId($sessionNo);
		if ($count > 0) {
			$sessionNo = $this->makeSessionNo();
		}
		return $sessionNo;
	}
	
	protected function setMessage($message) {
		$this->Flash->error($message);
	}
	
	public function isLogin() {
		if (!$this->Session->check(\Constants::SESSION_ADMIN_NAME)) {
			return false;
		}
		
		try {
			$sessionNo = $this->getSessionNo();
			$user = $this->Users->findUserBySessionNo($sessionNo);
			if (empty($user)) {
				return false;
			}
			$this->set('loginUser', $user);
			//pr($user);exit();
			
			$newSessionNo = $this->makeSessionNo();
			$userSession = TableRegistry::get('UserSessions');
			$userSession->connection()->transactional(function () use($userSession, $newSessionNo, $sessionNo, $user) {
				//issue a new session for each controller
				$userSession->findBySessionIdForUpdate($newSessionNo);
				$userSession->updateSessionId($sessionNo, $newSessionNo);
				
				//delete expired session
				$userSession->deleteExpireSession();
				$this->setSessionNo($newSessionNo);
				
				\Util::setLoginId($user->userId);
			});
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}
?>