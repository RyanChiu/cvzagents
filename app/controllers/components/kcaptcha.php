<?php
class KcaptchaComponent extends Object {
	var $Controller = null;

	function startup(&$controller)
	{
		$this->Controller = $controller;
	}

	function render()
	{
		App::import('vendor', 'kcaptcha/kcaptcha');
		$kcaptcha = new KCAPTCHA();
		$this->Controller->Session->write('kcaptcha', $kcaptcha->getKeyString());
		exit;
	}

}
?>