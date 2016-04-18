<?php
namespace App\Database\Type;
use Cake\Database\Driver;
use Cake\Database\Type;

class CryptedType extends Type {
	public function toDatabase($value, Driver $driver) {
		return \Crypt::hash($value);
	}
}
?>