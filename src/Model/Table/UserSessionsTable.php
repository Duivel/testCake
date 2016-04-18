<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Datasource\ConnectionManager;

class UserSessionsTable extends AppTable {
	public function initialize(array $config) {
		parent::initialize($config);
		$this->table('user_sessions');
		$this->addBehavior('TimeStamp',[
				'events' => [
						'Model.beforeSave' => [
								'created' => 'new',
								'modified' => 'always'
						],
				]
		]);
	}
	
	/**
	 * 
	 * @param unknown $sessionNo
	 */
	public function findBySessionIdForUpdate($sessionNo) {
		$conn = ConnectionManager::get('default');
		$stmt = $conn->execute('Select * from user_sessions where id = ? for update',[$sessionNo]);
		return $stmt;
	}
	
	public function updateSessionId($oldSessionId, $newSessionId = null) {
		$fields = [
				'id' => $newSessionId,
				'limit_time' => strtotime(\Constants::SESSION_VALID_SECOND.' seconds '.date('Y-m-d H:m:s'))
		];
		$conditions = [
				'id' => $oldSessionId
		];
		return parent::updateAll($fields, $conditions);
	}
	
	public function deleteExpireSession() {
		$conditions = [
				parent::le('UserSessions.limit_time', strtotime(date('Y-m-d H:m:s')))
		];
		return parent::deleteAll($conditions);
	}
	
	public function findBySessionId($sessionId) {
		$options = [
				'conditions' => [
						parent::eq('UserSessions.id', $sessionId)
				]
		];
		$query = $this->find('all', $options);
		return $query->count();
	}
}
?>