<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Datasource\ConnectionManager;
class AppTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);
	}

	/**
	 * Equal function
	 * @param string $columnName
	 * @param string $value
	 * @param boolean $flg
	 */
	public function eq($columnName, $value, $flg = false) {
		if (!is_null($flg) || $flg == true) {
			if (is_null($value) || $value == '') {
				return null;
			}
		}
		return array("$columnName = "=>$value);
	}
	

	/**
	 * Not Equal function
	 * @param string $columnName
	 * @param string $value
	 * @param boolean $flg
	 */
	protected function ne($columnName, $value, $flg = false) {
		if (!is_null($flg) || $flg == true) {
			if (is_null($value) || $value == '') {
				return null;
			}
		}
		return array("$columnName != "=>$value);
	}
	

	/**
	 * Less than function
	 * @param string $columnName
	 * @param string $value
	 * @param boolean $flg
	 */
	public function le($columnName, $value, $flg = false) {
		if (!is_null($flg) || $flg == true) {
			if (is_null($value) || $value == '') {
				return null;
			}
		}
		return array("$columnName < "=>$value);
	}
	

	/**
	 * Greater than function
	 * @param string $columnName
	 * @param string $value
	 * @param boolean $flg
	 */
	public function ge($columnName, $value, $flg = false) {
		if (!is_null($flg) || $flg == true) {
			if (is_null($value) || $value == '') {
				return null;
			}
		}
		return array("$columnName > "=>$value);
	}
}
?>