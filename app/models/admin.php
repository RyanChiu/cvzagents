<?php
class Admin extends AppModel {
	var $name = "Admin";
	
	var $validate = array(
		'email' => array(
			'rule' => 'email',
			'message' => 'Please fill out a valid email address.'
		)
	);
}
?>