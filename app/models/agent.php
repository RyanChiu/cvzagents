<?php
class Agent extends AppModel {
	var $name = 'Agent';
	var $validate = array(
		'companyid' => array(
			'rule' => array('comparison', 'is greater', 0),
			'message' => 'Please choose an office.'
		),
		'ag1stname' => array(
			'rule' => 'notEmpty'
		),
		'aglastname' => array(
			'rule' => 'notEmpty'
		),
		'email' => array(
			'rule' => 'email',
			'message' => 'Please fill out a valid email address.'
		),
		'country' => array(
			'rule' => 'notEmpty'
		),
		'im' => array(
			'rule' => 'notEmpty'
		),
		'cellphone' => array(
			'rule' => 'notEmpty'
		)
	);
}
?>