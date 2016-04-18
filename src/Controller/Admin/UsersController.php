<?php
namespace App\Controller\Admin;

use Cake\Network\Exception\NotFoundException;
use App\Controller\AdminAppController;
use Cake\Core\Configure;
use Cake\Utility\Security;

class UsersController extends AdminAppController {
	public function initialize() {
		parent::initialize();
		$this->loadComponent('LastAccess');
	}
	
	public function index() {
		$userList = $this->Users->findAllByDelflg();
// 		if ($userList->has('password')) {
// 			$userList->__unset('password');
// 		}
		$this->set('userList', $userList);
		$this->LastAccess->setLastAccess();
		parent::move('User list', 'test_layout', 'index');
	}
	
	public function edit($userId = null) {
		$this->LastAccess->setLastAccess();
		if (!is_null($userId) || strlen($userId) > 0) {
			$user = $this->Users->findByUserId($userId);
			if (empty($user)) {
				throw new NotFoundException(__('Users not found'));
			} else {
				$user->birthDay = $user->displayDate();
			}
		} else {
			$user = $this->Users->newEntity();
			
		}
		$this->set('user', $user);

		if ($this->request->is(['post', 'put'])) {
			$date = date_create($this->request->data['birthDay']);
			$this->request->data['birthDay'] = $date;
 			$user = $this->Users->patchEntity($user, $this->request->data);

			if ($this->Users->save($user)) {
				$this->redirect(array('controller'=>'Users','action' => 'index'));
			}
		} else {
			$this->set('user', $user);
		}
		parent::move("user edit", 'test_layout', 'edit');
	}
	
	private function move_regist($data) {
		if ($this->request->is(['post', 'put'])) {
			$user = $this->Users->patchEntity($user, $data);
			
		}
	}
}
?>