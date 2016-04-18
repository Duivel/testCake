<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Log\Log;
use Cake\Database\Schema\Table as Schema;

class UsersTable extends AppTable {
	public function initialize(array $config) {
		parent::initialize($config);
		$this->table('users');
		$this->primaryKey('userId');
		$this->displayField('userName');
		$this->addBehavior('TimeStamp',[
				'events' => [
						'Model.beforeSave' => [
								'created' => 'new',
								'modified' => 'always'
						],
				]
		]);
		
		
		$this->addBehavior('UpdateUser');
// 		Log::info('This is user scope', ['users']);
	}
	
	/**
	 * Initialize column name for the custom type
	 * {@inheritDoc} Quan
	 * @see \Cake\ORM\Table::_initializeSchema()
	 */
	public function _initializeSchema(Schema $table) {
		$table->columnType('password', 'crypted');
		return $table;
	}
	
	public function validationDefault(Validator $validator) {
		$validator
			->requirePresence('userName')
			->add('userName', [
					'notEmpty' => [
							'rule' => 'notEmpty',
							'message' => __('Please fill this field'),
					],
					'minLength' => [
							'rule' => ['minLength', 10],
							'message' => __('please input more than 10 characters')
					],
					'maxLength' => [
							'rule' => ['maxLength', 20],
							'message' => __('Please input less than 20 characters')
					],
			]);
		$validator
			->requirePresence('email')
			->add('email',[
					'notEmpty' => [
							'rule' => 'notEmpty',
							'message' => __('Please fill in this field')
					],
					'Email' => [
							'rule' => 'email',
							'message' => __('Please input a valid email address')
					]
			]);
		$validator
			->requirePresence('birthDay')
			->add('birthDay', [
					'notEmpty' => [
							'rule' => 'notEmpty',
							'message' => 'Please fill this field !'
					],
					'Date' => [
							'rule' => 'date',
							'message' => 'Please input a valid date'
					]
			]);
		return $validator;
	}

	/**
	 * Find all active user
	 */
	public function findAllByDelflg() {
		$options = array(
				'conditions' => array(
						parent::eq('Users.del_flg',0)
				),
				'order' => array('created' => 'asc')
		);
		
		$query = $this->find('all',$options);
		return $query->all();
	}
	
	
	/**
	 * Find user by user_id
	 * @param string $userId
	 */
	 public function findByUserId($userId = null) {
	 	if (is_null($userId) || strlen($userId) == 0) {
	 		return null;
	 	}
		$options = array(
				'conditions' => array(
						parent::eq('Users.userId', $userId),
						parent::eq('Users.del_flg', '0')
				)
		);
		$query = $this->find('all', $options);
		return $query->first();
	}
	
	public function login($email, $password) {
		$options = [
				'conditions' => [
						parent::eq('Users.email', $email),
						parent::eq('Users.password', $password),
						parent::eq('Users.del_flg', 0)
				]
		];
		$query = $this->find('all', $options);
		return $query->first();
	}
	
	public function findUserBySessionNo($sessionNo) {
		if (empty($sessionNo)) {
			return null;
		}
		
		$options = [
				'fields' => ['Users.userId', 'Users.userName'],
				'conditions' => [
						parent::eq('Users.del_flg', 0)
				],
				'join' => [
						[
								'type' => 'inner',
								'table'=> 'user_sessions',
								'alias' => 'UserSession',
								'conditions' => [
										'Users.userId = UserSession.userId',
										parent::ge('UserSession.limit_time', strtotime(date('Y-m-d H:i:s'))),
										parent::eq('UserSession.id', $sessionNo)
								]
						]
				]
		];
		$query = $this->find('all', $options);
		return $query->first();
	}
}
?>