<?php
class PhpcaptchaComponent extends Object {
	var $Controller = null;
	
	function startup(&$controller)
	{
		$this->Controller = $controller;
	}
	
	function render()
	{
		App::import('vendor', 'phpcaptcha/securimage');
		$options = array(
			'text_color' => new Securimage_Color('#e80707'),
			'captcha_type' => 1
		);
		$phpcaptcha = new Securimage($options);
		$phpcaptcha->show();
		exit;
	}
}
?>