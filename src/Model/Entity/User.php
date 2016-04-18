<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class User extends Entity {
	protected $_accessible = [
			'*' => true,
			'userId' => false
	];

	public function displayUserEmail() {
		return $this->email . '/' . $this->user_name;
	}

	public function displayDate() {
		return date_format($this->birthDay, 'Y-m-d');
	}
	
}
?>