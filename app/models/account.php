<?php
class Account extends AppModel {
	var $name = 'Account';
	var $validate = array(
		'username' => array(
			/*
			'usernameRule_1' => array(
				'rule' => 'alphaNumeric',
				'message' => 'Usernames must only contain letters and numbers.'
			),
			*/
			'usernameRule_2' => array(
				'rule' => 'isCaseInsensitiveUnique',
				'message' => 'Sorry, the username has already been taken.(case-insensitive)'
			),
			'usernameRule_3' => array(
				'rule' => 'isAgentUsernameOrganized',
				'message' => 'Managers, please sign up your agents with the alpha (uppercase) numeric user names/rids, or the sales will not track properly.(If the last user name rid was LL22, the next agent will be LL23, and so on.)'
			),
			'usernameRule_4' => array(
				'rule' => 'isAgentUsernameInMappings',
				'message' => 'This username is saved for campaign id, please use another one.'
			)
		),
		'password' => array(
			'rule' => 'notEmpty'
		)
	);
	
	var $status = array('-1' => 'unapproved', '0' => 'suspended', '1' => 'activated');
	var $online = array('0' => 'offline', '1' => 'online');
	
	function hashPasswords($data) {
		if (isset($data['Account']['password'])) {
			$data['Account']['password'] = md5($data['Account']['password']);
			return $data; 
		}
		return $data;
	}
	
	function isCaseInsensitiveUnique($check) {
		$r = $this->find('first',
			array(
				'conditions' => array(
					'lower(username)' => strtolower($check['username'])
				)
			)
		);
		if (isset($this->data['Account']['id'])) {//if it's an updating operation
			if (empty($r)) return true;
			else {
				return ($r['Account']['id'] == $this->data['Account']['id'] ? true : false);
			}
		} else {//otherwise, it's an inserting operation
			return empty($r);
		}
	}
	
	function isAgentUsernameOrganized($check) {
		$data = $this->data[$this->name];
		if (isset($data) && $data['role'] == 2) {//only if it's an agent
			$value = array_values($check);
			$value = $value[0];
			/*
			 * this rule means:
			 * the first 0~4 chars should be A-Z or a-z or 0-9,
			 * and following by a _ or nothing, and there two means a prefix which
			 * is used to do the "delete an account" stuff.
			 * and the following 1~3 chars should be A-Z or a-z, 
			 * and the following 0~4 chars should be 0-9,
			 * and the following 1 char should be _ or nothing,
			 * and the following 0~2 chars should be 0-9.
			 * /i means that both uppercase and lowercase should be fine.
			 */
			return preg_match('/^[A-Z0-9]{0,4}_{0,1}[A-Z]{1,3}\d{0,4}_{0,1}\d{0,2}$/i', $value);
		}
		return true;
	}
	
	function isAgentUsernameInMappings($check) {
		$data = $this->data[$this->name];
		if (isset($data) && $data['role'] == 2) {//only if it's an agent
			$value = array_values($check);
			$value = $value[0];
			if (strtolower($value) == strtolower($data['username'])) return true;
			$rs = $this->query(
				sprintf(
					'select id from agent_site_mappings where LOWER(campaignid) = "%s"',
					strtolower($value)
				)
			);
			return (empty($rs));
		}
		return true;
	}
}
?>
