<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
class LastAccessComponent extends Component {
	public function setLastAccess() {
		$url = '/'.$this->request->url;
		$this->request->session()->write(\Constants::SESSION_LAST_ACCESS, $url);
	}
	
	public function getLastAccess() {
		return $this->request->session()->read(\Constants::SESSION_LAST_ACCESS);
	}
}
?>