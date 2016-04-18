<?php
namespace App\Model\Behavior;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use Cake\Utility\Text;

class UpdateUserBehavior extends Behavior {
	/**
	 * Update modify_user and create_user accordingly
	 * if add a new user -> add modify_user, create_user and del_flg
	 * if edit an existing user -> add modify_user
	 * @param Entity $entity
	 */
	public function userUpdate(Entity $entity) {
		$fields = ['modify_user'];

		if ($entity->isNew()) {
			$fields = ['create_user', 'modify_user', 'del_flg', 'userId'];
		} 

		//load login user
		$user = is_null(\Util::getLoginId()) ? '0' : \Util::getLoginId();

		$columns = $this->_table->schema()->columns();
		if (!empty($columns)) {
			foreach ($columns as $column) {
				foreach ($fields as $field) {
					if ($field == $column) {
						if ($field == 'create_user' || $field == 'modify_user') {	//Add create_user or modify_user
							$entity->set($field, $user);
						} else if ($field == 'del_flg') {							//Add del_flg
							$entity->set($field, 0);
						} else if ($field == 'userId') {							//Add userId 
							$entity->set($field, Text::uuid());
						}
					}
				}
			}
		}		
	}

	public function beforeSave(Event $event, EntityInterface $entity) {
		$this->userUpdate($entity);
	}
}
?>
