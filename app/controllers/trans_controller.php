<?php
App::import('vendor', 'ExtraKits', array('file' => 'extrakits.inc.php'));
?>
<?php
class TransController extends AppController {
	var $name = 'Trans';
	var $uses = array(
		'Account', 'Admin', 'Company', 'Agent',
		'Country', 'Bulletin', 'ChatLog', 'ViewChatLog',
		'OnlineLog', 'ViewOnlineLog',
		'Link', 'Clickout', 'AgentSiteMapping', 'Type',
		'Site', 'SiteExcluding', 'Stats',
		'ViewAdmin', 'ViewCompany', 'ViewAgent',
		'ViewStats', 'ViewMapping', 'SiteManual',
		'FakeContactUs'
	);
	var $components = array(
		'Auth',
		'Email',
		'Phpcaptcha'
	);
	var $helpers = array(
		'Form', 'Html', 'Javascript',
		'ExPaginator'
	);
	var $__limit = 50;
	var $__svrtz = "Asia/Manila";
	var $__timeout = 21600;// in seconds, shoud be the same with the php timeout setting

	function beforeFilter() {
		//Configure::write('debug', 2);
		$this->pageTitle = 'ChatVazoo';
		$this->Auth->authenticate = ClassRegistry::init('Account'); 
		$this->Auth->userModel = 'Account'; 
		$this->Auth->loginAction = array('controller' => 'trans', 'action' => 'login');
		$this->Auth->loginRedirect = array('controller' => 'trans', 'action' => 'index');
		$this->Auth->logoutRedirect = array('controller' => 'trans', 'action' => 'login');
		$this->Auth->loginError = 'Sorry, login failed, please try again.';
		$this->Auth->authError = 'Sorry, you are not authorized to access that location.';
		$this->Auth->userScope = array('Account.status' => '1');
		$this->Auth->autoRedirect = false;
		$this->Auth->allow('login', 'logout', 'forgotpwd', 'contactus', 'phpcaptcha', 'playPhpcaptcha', 'index', 'golink', 'go');
		
		/*check if the user could visit some actions*/
		$this->__handleAccess();
		
		parent::beforeFilter();
	}
	
	function __accessDenied() {
		$this->Session->setFlash('Sorry, you are not authorized to visit that location, so you\'ve been relocated here.');
		$this->redirect(array('controller' => 'trans', 'action' => 'index'));
	}
	
	function __handleAccess() {		
		if ($this->Auth->user('role') == 0) {//means an administrator
			return;
		}
		if ($this->Auth->user('role') == 1) {//means an office
			switch ($this->params['action']) {
				case 'updadmin':
				case 'addnews':
				case 'updalerts':
				case 'regcompany':
				case 'lstnewmembers':
				case 'lstcompanies':
				case 'updtoolbox':
					$this->__accessDenied();
					return;
				case 'lstagents':
					if (count($this->params['pass']) == 1 
						&& $this->params['pass'][0] == $this->Auth->user('id')) {
						return;
					} else if (array_key_exists('page', $this->passedArgs)) {
						return;
					} else if (array_key_exists('ViewAgent', $this->data)
						&& array_key_exists('AgentSiteMapping', $this->data)) {
						return;
					} else {
						$this->__accessDenied();
						return;	
					}
				case 'updcompany':
					if (count($this->params['pass']) == 1 
						&& $this->params['pass'][0] == $this->Auth->user('id')) {
						return;
					} else {
						$this->__accessDenied();
						return;	
					}
			}
		}
		if ($this->Auth->user('role') == 2) {//means an agent
			switch ($this->params['action']) {
				case 'updadmin':
				case 'addnews':
				case 'updalerts':
				case 'regcompany':
				case 'regagent':
				case 'updcompany':
				case 'rptpayouts':
				case 'lstnewmembers':
				case 'lstcompanies':
				case 'lstagents':
				case 'lstlogins':
				case 'updtoolbox':
					$this->__accessDenied();
					return;
				case 'updagent':
					if (count($this->params['pass']) == 1 
						&& $this->params['pass'][0] == $this->Auth->user('id')) {
						return;
					} else {
						$this->__accessDenied();
						return;	
					}
			}
		}
	}
	
	function __sendemail($subject = 'empty', $content = 'empty',
		$from = 'support@chatvazoo.com',
		$mailto = 'support@chatvazoo.com',
		$replyto = 'support@chatvazoo.com') {
		/* SMTP Options */
		$this->Email->smtpOptions = array(
			//'request' => array('uri' => array('scheme' => 'https')),
			'port'=>'25',
			'timeout'=>'60',
			'host' => 'smtpout.secureserver.net',
			'username'=>'support@chatvazoo.com',
			'password'=>'Adutta11'
		);
		$this->Email->from = '<' . $from . '>';
		$this->Email->to = '<' . $mailto . '>';
		$this->Email->replyTo = '<' . $replyto . '>';
		$this->Email->subject = $subject;
		/* Set delivery method */
		$this->Email->delivery = 'smtp';
		/* Send the email */
		$this->Email->send($content);
		/* Check for SMTP errors */ 
		if (!empty($this->Email->smtpError)) return $this->Email->smtpError;
		else return true;
	}
	
	function phpcaptcha() {
		Configure::write('debug', '0');
		$this->autoRender = false;
		$this->Phpcaptcha->show();
	}
	
	function playPhpcaptcha() {
		Configure::write('debug', '0');
		$this->autoRender = false;
		$this->Phpcaptcha->play();
	}
	
	function __checkPhpcaptcha($vcode) {
		if ($this->Session->check('securimage_code_value')) {
			$s_phpcaptcha = $this->Session->read('securimage_code_value');
			if (!empty($vcode) && $vcode == $s_phpcaptcha['default']) {
				return true;
			}
		}
		return false;
	}
	
	function __top10($conds) {
		/*
		 * for the "selling contestants" stuff
		 */
		//avoid those data which are not in types
		$tps = $this->Type->find('list',
			array(
				'fields' => array('id', 'id')
			)
		);
		$rs = $this->Stats->find('all',
			array(
				'fields' => array('agentid', 'sum(sales_number) as sales'),
				'conditions' => array(
					'convert(trxtime, date) <=' => $conds['enddate'],
					'convert(trxtime, date) >=' => $conds['startdate'],
					'typeid' => $tps,
					'agentid >' => '0'//avoid those data that don't belog to any agent
				),
				'group' => array('agentid'),
				'order' => array('sales desc'),
				'limit' => 10
			)
		);
		$i = 0;
		foreach ($rs as $r) {
			$topag = $this->Account->find('first',
				array(
					'fields' => array('username'),
					'conditions' => array('id' => $r['Stats']['agentid'])
				)
			);
			$topcom = $this->Agent->find('first',
				array(
					'fields' => array('companyid'),
					'conditions' => array('id' => $r['Stats']['agentid'])
				)
			);
			$topcom = $this->Company->find('first',
				array(
					'fields' => array('officename'),
					'conditions' => array('id' => $topcom['Agent']['companyid'])
				)
			);
			$rs[$i]['ViewStats']['officename'] = $topcom['Company']['officename'];
			$rs[$i]['ViewStats']['username'] = $topag['Account']['username'];
			$rs[$i]['ViewStats']['sales'] = $r[0]['sales'];
			$i++;
		}
		return $rs;
	}
	
	function top10() {
		$this->layout = 'defaultlayout';
		
		$lastday = date("Y-m-d", strtotime(date('Y-m-d') . " Sunday"));
		$lastday = date("Y-m-d", strtotime($lastday . " - 1 days"));
		$weekend = $lastday;
		$weekstart = date("Y-m-d", strtotime($lastday . " - 6 days"));
		$periods = array();
		for ($i = 0; $i < 52; $i++) {
			$oneweek = date("Y-m-d", strtotime($lastday . " - " . (7 * $i + 6) . " days"))
				. ','
				. date("Y-m-d", strtotime($lastday . " - " . (7 * $i) . " days"));
			$v = $oneweek;
			switch ($i) {
				case 0:
					$v = 'THIS WEEK';
					break;
				case 1:
					$v = 'LAST WEEK';
					break;
				default:
					break;
			}
			$periods += array($oneweek => $v);
		}
		
		$weekrs = array();
		if (!empty($this->data)) {
			$conds = array();
			$weekstart = $this->data['Top10']['weekstart'];
			$weekend = $this->data['Top10']['weekend'];
			$conds['startdate'] = $weekstart;
			$conds['enddate'] = $weekend;
			$weekrs = $this->__top10($conds);
		}
		$this->set(compact('weekrs'));
		$this->set(compact('weekstart'));
		$this->set(compact('weekend'));
		$this->set(compact('periods'));
	}
	
	function pass() {
		$this->Session->write('switch_pass', 1);
		$this->redirect(array('controller' => 'trans', 'action' => 'index'));
	}
	
	function index($id = null) {
		if (!$this->Auth->user()) $this->redirect(array('controller' => 'trans', 'action' => 'login'));
		$this->layout = 'defaultlayout';
		
		/*try to archive the bulletin*/
		if ($id == -1 && $this->Auth->user('role') == 0) {
			$this->Bulletin->updateAll(
				array('archdate' => "'" . date('Y-m-d h:i:s') . "'"),
				array('archdate' => null)
			);
			if ($this->Bulletin->getAffectedRows() > 0) {
				$this->Session->setFlash("Bulletin archived.");
			} else {
				$this->Session->setFlash("No current bulletin exists.");
			}
		}
		/*prepare the historical bulletins*/
		$archdata = $this->Bulletin->find('all',
			array(
				'fields' => array('id', 'title', 'archdate'),
				'conditions' => array('archdate not' => null),
				'order' => array('archdate desc')
			)
		);
		$this->set(compact('archdata'));
		/*prepare the ALERTS for the current logged-in user*/
		$info = array();
		if ($id == null) {
			$info = $this->Bulletin->find('first',
				array(
					'fields' => array('info'),
					'conditions' => array('archdate' => null)
				)
			);
		} else {
			$info = $this->Bulletin->find('first',
				array(
					'fields' => array('info'),
					'conditions' => array('id' => $id)
				)
			);
		}
		$this->set('topnotes',  empty($info) ? '...' : $info['Bulletin']['info']);
		if ($this->Auth->user('role') == 0) {//means an administrator
			$this->set('notes', '');//set the additional notes here
		} else if ($this->Auth->user('role') == 1) {//means a company
			$cominfo = $this->Company->find('first',
				array(
					'fields' => array('agentnotes'),
					'conditions' => array('id' => $this->Auth->user('id'))
				)
			);
			$this->set('notes', '');//set the additional notes here
		} else {//means an agent
			$aginfo = $this->Agent->find('first',
				array(
					'fields' => array('companyid'),
					'conditions' => array('id' => $this->Auth->user('id'))
				)
			);
			$cominfo = $this->Company->find('first',
				array(
					'fileds' => array('agentnotes'),
					'conditions' => array('id' => $aginfo['Agent']['companyid'])
				)
			);
			$this->set('notes', '<font size="3"><b>Office news&nbsp;&nbsp;</b></font>' . $cominfo['Company']['agentnotes']);
			
			
		}
		
		/*
		 * for the "selling contestants" stuff
		 */
		//avoid those data which are not in types
		$conds['startdate'] = '0000-00-00';
		$conds['enddate'] = date('Y-m-d');
		$rs = $this->__top10($conds);
		$this->set(compact('rs'));
		$weekend = date("Y-m-d", strtotime(date('Y-m-d') . " Saturday"));
		$weekstart = date("Y-m-d", strtotime($weekend . " - 6 days"));
		$conds['startdate'] = $weekstart;
		$conds['enddate'] = $weekend;
		$weekrs = $this->__top10($conds);
		$this->set(compact('weekrs'));
		$this->set(compact('weekstart'));
		$this->set(compact('weekend'));
				
		/*prepare the totals demo data*/
		/*## for accounts overview*/
		/*
		$totals = array('cpofflines' => 0, 'cponlines' => 0, 'agofflines' => 0, 'agonlines' => 0,
			'cpacts' => 0, 'cpsusps' => 0, 'agacts' => 0, 'agsusps' => 0);
		$totals['cpofflines'] = 
			$this->ViewCompany->find('count',
				array('conditions' => array('online' => 0))
			);
		$totals['cponlines'] = 
			$this->ViewCompany->find('count',
				array('conditions' => array('online' => 1))
			);
		
		$totals['agofflines'] =
			$this->ViewAgent->find('count',
				array('conditions' => array('online' => 0))
			);
		$totals['agonlines'] =
			$this->ViewAgent->find('count',
				array('conditions' => array('online' => 1))
			);
		
		$totals['cpsusps'] =
			$this->ViewCompany->find('count',
				array('conditions' => array('status' => 0))
			);
		$totals['cpacts'] =
			$this->ViewCompany->find('count',
				array('conditions' => array('status' => 1))
			);
		
		$totals['agsusps'] =
			$this->ViewAgent->find('count',
				array('conditions' => array('status' => 0))
			);
		$totals['agacts'] =
			$this->ViewAgent->find('count',
				array('conditions' => array('status' => 1))
			);
		
		$this->set('totals', $totals);
		*/
		
		/*prepare the online demo data*/
		/*
		$this->set('cprs',
			$this->ViewCompany->find('all',
				array(
					'fields' => array('username', 'officename', 'contactname', 'regtime'),
					'conditions' => array('online' => 1)
				)
			)
		);
		
		$this->set('agrs',
			$this->ViewAgent->find('all',
				array(
					'fields' => array('username', 'officename', 'name', 'regtime'),
					'conditions' => array('online' => 1)
				)
			)
		);
		*/
	}
	
	function login() {
		$this->layout = 'loginlayout';
		
		if (!empty($this->data)) {//if there are any POST data
			
			/*try to find the account info from DB who try to login*/
			$userinfo = $this->Account->find('first',
				array('conditions' => array('lower(username)' => strtolower($this->data['Account']['username'])))
			);
			
				/*the follwing codes are just in case of "agent name changed" situation-start*/
				if (empty($userinfo)) {
					$asmrs = $this->AgentSiteMapping->find('first',
						array('conditions' => array('lower(campaignid)' => strtolower($this->data['Account']['username'])))
					);
					if (!empty($asmrs)) {
						$userinfo = $this->Account->find('first',
							array('conditions' => array('id' => $asmrs['AgentSiteMapping']['agentid']))
						);
						
					}
				}
				/*the up block codes are just in case of "agent name changed" situation-end*/
				
				/*try to judge if the office which the agent belongs to is suspended*/
				if ($userinfo['Account']['role'] == 2) {
					$aginfo = $this->Agent->find('first',
						array('conditions' => array('id' => $userinfo['Account']['id']))
					);
					$cpinfo = $this->Account->find('first',
						array('conditions' => array('id' => $aginfo['Agent']['companyid']))
					);
					if ($cpinfo['Account']['status'] == 0) {
						$this->Session->setFlash(
							"(Your office is suspended right now, please contact your administrator.)",
							'default',
							array('class' => 'suspended-warning')
						);
						$this->data['Account']['password'] = '';
						$this->Auth->logout();
						return;
					}
					if ($cpinfo['Account']['status'] == -1) {
						$this->Session->setFlash(
								"(Your office is not approved for the moment, please contact your administrator.)",
								'default',
								array('class' => 'suspended-warning')
						);
						$this->data['Account']['password'] = '';
						$this->Auth->logout();
						return;
					}
				}
				
			$vcode = $this->data['Account']['vcode'];
			if ($this->__checkPhpcaptcha($vcode)) {//if phpcaptcha code is correct
				/*login part*/
				if ($this->Auth->user()) {//means username/password/status are all correct, login succeeded
					$this->Auth->login();
					/*
					 * log login start:
					 * before login redirect, we try to log the login time of the user. 
					 */
					$gonnalog = true;
					$now = new DateTime("now", new DateTimeZone($this->__svrtz));
					/*
					if ($this->Auth->user('online') != -1) {
						$ollog = $this->OnlineLog->find('first',
							array(
								'conditions' => array('id' => $this->Auth->user('online'))
							)
						);
						$logtimediff =
							strtotime($now->format('Y-m-d H:i:s'))
							- strtotime($ollog['OnlineLog']['intime']);
						if ($logtimediff < $this->__timeout) $gonnalog = false;
					}
					*/
					if ($gonnalog) {
						$terminalcookie = $this->Session->check('terminalcookie') ?
							$this->Session->read('terminalcookie') : null;
						$ollog = array('OnlineLog' => array());
						$ollog['OnlineLog'] += array('accountid' => $this->Auth->user('id'));
						$ollog['OnlineLog'] += array('intime' => $now->format('Y-m-d H:i:s'));
						$ollog['OnlineLog'] += array('inip' => __getclientip());
						$ollog['OnlineLog'] += array(
							'terminalcookieid' => (($terminalcookie != null && isset($terminalcookie['id'])) ?
								$terminalcookie['id'] : -2)
						);
						$ollog['OnlineLog'] += array(
							'outtime' => date(
								'Y-m-d H:i:s',
								strtotime(
									"+" . $this->__timeout . " second",
									strtotime($now->format('Y-m-d H:i:s'))
								)
							)
						);
						$this->OnlineLog->id = null;
						$this->OnlineLog->save($ollog);
						$this->Account->id = $this->Auth->user('id');
						$this->Account->saveField('online', $this->OnlineLog->id);
						$this->Account->saveField('lastlogintime', $now->format('Y-m-d H:i:s'));
					}
					/*
					 * log login end
					 */
					$this->redirect($this->Auth->redirect());
				} else {// means login failed
					if (!empty($userinfo)) {
						if ($userinfo['Account']['status'] == 0) {
							$this->Session->setFlash(
								'(Your account for this site is temporarily suspended for fraud review.)',
								'default',
								array('class' => 'suspended-warning')
							);
						} else if ($userinfo['Account']['status'] == -1) {
							$this->Session->setFlash(
									'(Your account for this site is not approved for the moment.)',
									'default',
									array('class' => 'suspended-warning')
							);
						} else {
							/*try to find the new username by searching the mappings table*/
							if ($userinfo['Account']['username'] != $this->data['Account']['username']) {
								$this->Session->setFlash(
									sprintf('Your username has been changed from "%s" to "%s", please use the new one to login.',
										$this->data['Account']['username'],
										$userinfo['Account']['username']
									)
								);
								$this->data['Account']['username'] = $userinfo['Account']['username'];
							} else {
								$this->Session->setFlash('(incorrect password)');
							}
						}
					} else {
						if ($this->data['Account']['username'])
							$this->Session->setFlash('(username: "' . $this->data['Account']['username'] . '" doesn\'t exist.)');
					}
				}
			} else {
				$this->data['Account']['password'] = '';
				$tmpzzz = $this->Session->read('securimage_code_value');
				$this->Session->setFlash('Your validation codes are incorrect, please try again.');
			}
		}
	}
	
	function logout() {
		if ($this->Auth->user()) {
			$this->Account->id = $this->Auth->user('id');
			$userinfo = $this->Account->read();
			if ($userinfo['Account']['online'] != -1) {
				$this->OnlineLog->id = $userinfo['Account']['online'];
				$ollog = $this->OnlineLog->read();
				if ($ollog['OnlineLog']['accountid'] == $this->Auth->user('id')) {
					$now = new DateTime("now", new DateTimeZone($this->__svrtz));
					$this->OnlineLog->id = $userinfo['Account']['online'];
					$this->OnlineLog->saveField('outtime', $now->format('Y-m-d H:i:s'));
					$this->Account->id = $this->Auth->user('id');
					$this->Account->saveField('online', -1);
				}
			}
		}
		
		/*logout part*/
		$this->Session->destroy();
		$this->Auth->logout();
		$this->redirect($this->Auth->redirect());
		
	}
	
	function forgotpwd() {
		$this->layout = 'loginlayout';
		
		if ($this->Auth->user()) {
			$this->redirect(array('controller' => 'trans', 'action' => 'index'));
		}
		
		if (!empty($this->data)) {
			$this->data['Forgot']['username'] = trim($this->data['Forgot']['username']);
			$this->data['Forgot']['email'] = trim($this->data['Forgot']['email']);
			$r = $this->Account->find('first',
				array(
					'conditions' => array(
						'lower(username)' => strtolower($this->data['Forgot']['username'])
					)
				)
			);
			if (empty($r)) {
				$this->Session->setFlash('Sorry, username ' . $this->data['Forgot']['username'] . ' doesn\'t exist.');
				$this->redirect(array('controller' => 'trans', 'action' => 'forgotpwd'));
			} else {
				if ($r['Account']['role'] == 0) {//means an administrator
					$this->Session->setFlash('Sorry, we are unable to retrieve your password.');
					$this->redirect(array('controller' => 'trans', 'action' => 'forgotpwd'));
				} else if ($r['Account']['role'] == 1) {//means an office
					$_r = $this->Company->find('first',
						array(
							'conditions' => array(
								'id' => $r['Account']['id']
							)
						)
					);
					if (empty($_r)) {
						$this->Session->setFlash('Sorry, username(c) ' . $this->data['Forgot']['username'] . ' doesn\'t exist.');
						$this->redirect(array('controller' => 'trans', 'action' => 'forgotpwd'));
					}
					if (strtolower($_r['Company']['manemail']) != strtolower($this->data['Forgot']['email'])) {
						$this->Session->setFlash('Sorry, the email address is incorrect, please try again.');
						$this->redirect(array('controller' => 'trans', 'action' => 'forgotpwd'));
					}
					/*
					 * then we can use the email logic send the password with $_r['Company']['manemail']
					 */
					$issent = $this->__sendemail(
						'Your ChatVazoo Password',
						"Hi,\nYour ChatVazoo password is:" . $r['Account']['originalpwd'] . "\n"
						. "\nThanks,\nChatVazoo webmaster.",//must use " instead of ' at this $content parameter
						'support@ChatVazoo.com',
						$_r['Company']['manemail']
					);
					if ($issent) {
						$this->Session->setFlash('Password sent, please check it out.');
						$this->redirect(array('controller' => 'trans', 'action' => 'login'));
					} else {
						//$this->Session->setFlash($issent);//redim this line to debug
						$this->Session->setFlash('Failed to send password, please contact your administrator.');
					}
				} else if ($r['Account']['role'] == 2) {//means an agent
					$_r = $this->Agent->find('first',
						array(
							'conditions' => array(
								'id' => $r['Account']['id']
							)
						)
					);
					if (empty($_r)) {
						$this->Session->setFlash('Sorry, username(a) ' . $this->data['Forgot']['username'] . ' doesn\'t exist.');
						$this->redirect(array('controller' => 'trans', 'action' => 'forgotpwd'));
					}
					if (strtolower($_r['Agent']['email']) != strtolower($this->data['Forgot']['email'])) {
						$this->Session->setFlash('Sorry, the email address is incorrect, please try again.');
						$this->redirect(array('controller' => 'trans', 'action' => 'forgotpwd'));
					}
					/*
					 * then we can use the email logic send the password with $_r['Agent']['email']
					 */
					$issent = $this->__sendemail(
						'Your ChatVazoo Password',
						"Hi,\nYour ChatVazoo password is:" . $r['Account']['originalpwd'] . "\n"
						. "\nThanks,\nChatVazoo webmaster.",//must use " instead of ' at this $content parameter
						'support@ChatVazoo.com',
						$_r['Agent']['email']
					);
					if ($issent) {
						$this->Session->setFlash('YOUR PASSWORD WAS SENT, PLEASE CONTACT YOUR BRANCH MANAGER.');
						$this->redirect(array('controller' => 'trans', 'action' => 'login'));
					} else {
						//$this->Session->setFlash($issent);
						$this->Session->setFlash('Failed to send password, please contact your administrator.');
					}
				}
			}
		}
	}
	
	function contactus() {
		if ($this->Auth->user()) {
			$this->layout = 'defaultlayout';
		} else {
			$this->layout = 'loginlayout';
		}
		
		if (!empty($this->data)) {
			/*validate the posted fields*/
			$this->FakeContactUs->set($this->data);
			if (!$this->FakeContactUs->validates()) {
				$this->Session->setFlash('Please notice the tips below.');
				return;
			}
			/*send the message*/
			$this->data['FakeContactUs']['email'] = trim($this->data['FakeContactUs']['email']);
			$issent = $this->__sendemail(
				$this->data['FakeContactUs']['subject'],
				"From:" . $this->data['FakeContactUs']['email'] . "\n\n" . $this->data['FakeContactUs']['message'],
				"support@ChatVazoo.com",
				"support@ChatVazoo.com",
				$this->data['FakeContactUs']['email']
			);
			$redirecturl = '';
			if ($this->Auth->user()) {
				$redirecturl = array('controller' => 'trans', 'action' => 'index');
			} else {
				$redirecturl = array('controller' => 'trans', 'action' => 'login');
			}
			if ($issent) {
				$this->Session->setFlash('Message sent, please wait for reply.');
				$this->redirect($redirecturl);
			} else {
				$this->Session->setFlash('Failed to send message, please contact your administrator.');
				$this->redirect($redirecturl);
			}
		}
	}
	
	function addnews() {
		$this->layout = 'defaultlayout';
		
		if (empty($this->data)) {
			/*prepare the notes for the current logged in user*/
			$info = $this->Bulletin->find('first',
				array(
					'fields' => array('id', 'info'),
					'conditions' => array('archdate' => null)
				)
			);
			$this->data = $info;
		} else {
			$this->Bulletin->id = $this->data['Bulletin']['id'];
			if ($this->Bulletin->saveField('info', $this->data['Bulletin']['info'])) {
				//$this->Session->setFlash('ALERTS updated.');
				$this->redirect(array('controller' => 'trans', 'action' => 'index'));
			} else {
				$this->Session->setFlash("Something wrong, please contact your administrator.");
			}
		}
	}
	
	function updalerts() {
		$this->layout = 'defaultlayout';
		
		if (empty($this->data)) {
			/*prepare the notes for the current logged in user*/
			$this->Admin->id = 1;//HARD CODE: we put the alerts into here
			$this->data = $this->Admin->read();
			if (empty($this->data)) {
				$this->Session->setFlash('Please create your first admin account, so we could continue the alerts setup.');
				$this->redirect(array('controller' => 'trans', 'action' => 'index'));
			}
		} else {
			$this->Admin->id = $this->data['Admin']['id'];
			if ($this->Admin->saveField('notes', $this->data['Admin']['notes'])) {
				$this->Session->setFlash('Alerts updated.');
				$this->redirect(array('controller' => 'trans', 'action' => 'index'));
			} else {
				$this->Session->setFlash("Something wrong, please contact your administrator.");
			}
		}
	}
	
	function updadmin() {
		$this->layout = 'defaultlayout';
		
		if (empty($this->data)) {
			$this->Account->id = $this->Auth->user('id');
			$account = $this->Account->read();
			$account['Account']['password'] = $account['Account']['originalpwd'];
			$this->data['Account'] = $account['Account'];
			$this->Admin->id = $this->Auth->user('id');
			$admin = $this->Admin->read();
			$this->data['Admin'] = $admin['Admin'];
			$this->set('rs', $this->data);
		} else {
			/*validate the posted fields*/
			$this->Account->set($this->data);
			$this->Admin->set($this->data);
			if (!$this->Account->validates() || !$this->Admin->validates()) {
				//$this->data['Account']['password'] = '';
				$this->data['Account']['password'] = $this->data['Account']['originalpwd'];
				$this->Session->setFlash('Please notice the tips below the fields.');
				return;
			}
			
			/*check if the passwords match or empty or untrimed*/
			$originalpwd = $this->data['Account']['originalpwd'];
			if (strlen(trim($originalpwd)) != strlen($originalpwd)) {
				$this->data['Account']['password'] = $this->data['Account']['originalpwd'];
				$this->Session->setFlash('Please remove any blank in front of or at the end of your password and try again.');
				return;
			}
			//if (empty($this->data['Account']['originalpwd']) || $this->data['Account']['password'] != $this->Auth->password($this->data['Account']['originalpwd'])) {
			if (empty($this->data['Account']['originalpwd']) || $this->data['Account']['password'] != md5($this->data['Account']['originalpwd'])) {
				$this->data['Account']['password'] = '';
				$this->data['Account']['originalpwd'] = '';
				$this->Session->setFlash('The passwords don\'t match to each other, please try again(and do not left it blank).');
				return;
			}
			
			/*actually save the data*/
			if ($this->Account->save($this->data)) {
				$this->Session->setFlash('Account changed.');
				if ($this->Admin->save($this->data)) {
					$this->Session->setFlash('Profile changed. Please remember your new password if changed.');
					$this->redirect(array('controller' => 'trans', 'action' => 'index'));
				}
			}
			$this->Session->setFlash('Something wrong here, please contact your administrator.');
		}
	}
	
	function regcompany($id = null) {
		$this->layout = 'defaultlayout';
		
		/*prepare the countries for this view*/
		$cts = $this->Country->find('list', array('fields' => array('Country.abbr', 'Country.fullname')));
		$this->set('cts', $cts);
		
		/*prepare associated sites data*/
		$exsites = $this->SiteExcluding->find('list',
			array(
				'fields' => array('id', 'siteid'),
				'conditions' => array('companyid' => $id)
			)
		);
		$sites = $this->Site->find('list',
			array(
				'fields' => array('id', 'sitename'),
				'conditions' => array('status' => 1)
			)
		);
		$exsites = array_unique($exsites);
		$exsites = array_flip($exsites);
		foreach ($exsites as $k => $v) {
			if (in_array($k, array_keys($sites))) {
				$exsites[$k] = $sites[$k];
			}
		}
		$this->set(compact('exsites'));
		$this->set(compact('sites'));
		
		$this->set('payouttype', $this->Company->payouttype);
		if (!empty($this->data)) {
			/*check if the passwords match or empty or untrimed*/
			$originalpwd = $this->data['Account']['originalpwd'];
			if (strlen(trim($originalpwd)) != strlen($originalpwd)) {
				$this->data['Account']['password'] = $this->data['Account']['originalpwd'];
				$this->Session->setFlash('Please remove any blank in front of or at the end of your password and try again.');
				return;
			}
			//if (empty($this->data['Account']['originalpwd']) || $this->data['Account']['password'] != $this->Auth->password($this->data['Account']['originalpwd'])) {
			if (empty($this->data['Account']['originalpwd']) || $this->data['Account']['password'] != md5($this->data['Account']['originalpwd'])) {
				$this->data['Account']['password'] = '';
				$this->data['Account']['originalpwd'] = '';
				$this->Session->setFlash('The passwords don\'t match to each other, please try again(and do not left it blank).');
				return;
			}
			
			/*validate the posted fields*/
			$this->Account->set($this->data);
			$this->Company->set($this->data);
			if (!$this->Account->validates() || !$this->Company->validates()) {
				$this->data['Account']['password'] = $this->data['Account']['originalpwd'];
				$this->Session->setFlash('Please notice the tips below the fields.');
				return;
			}
			
			/*make the value of field "regtime" to the current time*/
			$this->data['Account']['regtime'] = date('Y-m-d H:i:s');
			
			/*actually save the posted data*/
			$this->Account->create();
			$this->data['Account']['username4m'] = __fillzero4m($this->data['Account']['username']);
			if ($this->Account->save($this->data)) {//1stly, save the data into 'accounts'
				$this->Session->setFlash('Only account added.Please contact your administrator immediately.');
				
				$this->data['Company']['id'] = $this->Account->id;
				$this->Company->create();
				if ($this->Company->save($this->data)) {//2ndly, save the data into 'companies'
					/*after an office added, update the site_excluding data, then*/
					$__sites = $this->data['SiteExcluding']['siteid'];
					if (is_array($__sites)) {
						$__sites = array_diff(array_keys($sites), $__sites);
					} else {
						$__sites = array_keys($sites);
					}
					$exdata = array();
					foreach ($__sites as $__site) {
						array_push(
							$exdata, 
							array(
								'companyid' => $this->data['Company']['id'],
								'siteid' => $__site
							)
						);
					}
					$this->SiteExcluding->deleteAll(//since if no recs to del, it seems also return false, so we ignore it here
						array('companyid' => $this->data['Company']['id'])
					);
					$exdone = false;
					if (!empty($exdata)) {
						$exdone = ($this->SiteExcluding->saveAll($exdata) ? true : false);
					} else {
						$exdone = true;
					}
					
					/*redirect to some page*/
					$this->Session->setFlash(
						'Office "'
						. $this->data['Account']['username']
						. '" added.'
						. ($exdone ? '' : '<br><i>(Site associating failed.)</i>')
					);
					if ($id != -1) {
						$this->redirect(array('controller' => 'trans', 'action' => 'lstcompanies'));
					} else {
						$this->redirect(array('controller' => 'trans', 'action' => 'regcompany'));
					}
				} else {
					$this->data['Account']['password'] = $this->data['Account']['originalpwd'];
					//should add some codes here to delete the record that saved in 'accounts' table before if failed
				}
			} else {
				$this->data['Account']['password'] = $this->data['Account']['originalpwd'];
			}
		}
	}

	function regagent($id = null) {
		$this->layout = 'defaultlayout';
		
		/*prepare the companies for this view*/
		$cps = $this->ViewCompany->find('list',
			array(
				'fields' => array('companyid', 'officename'),
				'order' => 'username4m'
			)
		);
		$this->set('cps', $cps);
		/*prepare the email of the current office*/
		if ($this->Auth->user('role') == 1) {
			$this->Company->id = $this->Auth->user('id');
			$curcom = $this->Company->read();
			$this->set(compact('curcom'));
		}
		/*prepare the countries for this view*/
		$cts = $this->Country->find('list', array('fields' => array('Country.abbr', 'Country.fullname')));
		$this->set('cts', $cts);
		
		/*prepare associated sites data*/
		$exsites = $this->SiteExcluding->find('list',
			array(
				'fields' => array('id', 'siteid'),
				'conditions' => array('agentid' => $id)
			)
		);
		$sites = $this->Site->find('list',
			array(
				'fields' => array('id', 'sitename'),
				'conditions' => array('status' => 1)
			)
		);
		$exsites = array_unique($exsites);
		$exsites = array_flip($exsites);
		foreach ($exsites as $k => $v) {
			if (in_array($k, array_keys($sites))) {
				$exsites[$k] = $sites[$k];
			}
		}
		$this->set(compact('exsites'));
		$this->set(compact('sites'));
		
		if (!empty($this->data)) {
			/*check if the passwords match or empty or untrimed*/
			$originalpwd = $this->data['Account']['originalpwd'];
			if (strlen(trim($originalpwd)) != strlen($originalpwd)) {
				$this->data['Account']['password'] = $this->data['Account']['originalpwd'];
				$this->Session->setFlash('Please remove any blank in front of or at the end of your password and try again.');
				return;
			}
			//if (empty($this->data['Account']['originalpwd']) || $this->data['Account']['password'] != $this->Auth->password($this->data['Account']['originalpwd'])) {
			if (empty($this->data['Account']['originalpwd']) || $this->data['Account']['password'] != md5($this->data['Account']['originalpwd'])) {
				$this->data['Account']['password'] = '';
				$this->data['Account']['originalpwd'] = '';
				$this->Session->setFlash('The passwords don\'t match to each other, please try again(and do not left it blank).');
				return;
			}
			
			/*
			 * check if "auto-generate" was checked, if it was then generate a user name
			 * by +1 to the number in it, say if the largest one is "AA01", then it's
			 * "AA02".
			 */
			if ($this->data['Account']['auto'] == true) {
				$largestname = $this->ViewAgent->find('first',
					array(
						'fields' => array('username', 'RIGHT(username4m, 32) as num'),
						'conditions' => array(
							'companyid' => $this->data['Agent']['companyid']
						),
						'order' => 'RIGHT(username4m, 32) desc'
					)
				);
				if (empty($largestname)) {
					$this->data['Account']['username'] =
						strtoupper(substr($cps[$this->data['Agent']['companyid']], 0, 2))
						. '01';
				} else {
					$largestnum = intval($largestname[0]['num']) + 1;
					$this->data['Account']['username'] =
						strtoupper(substr($cps[$this->data['Agent']['companyid']], 0, 2))
						. ($largestnum < 10 ? ('0' . $largestnum) : $largestnum);
				}
			}
			
			/*validate the posted fields*/
			$this->Account->set($this->data);
			$this->Agent->set($this->data);
			if (!$this->Account->validates() || !$this->Agent->validates()) {
				//$this->data['Account']['password'] = '';
				$this->data['Account']['password'] = $this->data['Account']['originalpwd'];
				$this->Session->setFlash('Please notice the tips below the fields.');
				return;
			}

			/*make the value of field "regtime" to the current time*/
			$this->data['Account']['regtime'] = date('Y-m-d H:i:s');
			
			/*actually save the posted data*/
			$this->Account->create();
			$this->data['Account']['username4m'] = __fillzero4m($this->data['Account']['username']);
			if ($this->Account->save($this->data)) {//1stly, save the data into 'accounts'
				$this->Session->setFlash('Only account added.');
				
				$this->data['Agent']['id'] = $this->Account->id;
				//$this->data['Agent']['companyid'] = $this->data['Company']['id'] == null ? 0 : $this->data['Company']['id'];
				$this->Agent->create();
				if ($this->Agent->save($this->data)) {//2ndly, save the data into 'agents'
					/*after agent saved, update the site_excluding data, then*/ 
			        $__sites = $this->data['SiteExcluding']['siteid']; 
					if (is_array($__sites)) { 
					  $__sites = array_diff(array_keys($sites), $__sites); 
					} else { 
					  $__sites = array_keys($sites); 
					} 
					$exdata = array(); 
					foreach ($__sites as $__site) { 
					  array_push( 
					    $exdata,  
					    array( 
					      'agentid' => $this->data['Agent']['id'], 
					      'siteid' => $__site 
					    ) 
					  ); 
					} 
					$this->SiteExcluding->deleteAll(//since if no recs to del, it seems also return false, so we ignore it here 
					  array('agentid' => $this->data['Agent']['id']) 
					); 
					$exdone = false; 
					if (!empty($exdata)) { 
					  $exdone = ($this->SiteExcluding->saveAll($exdata) ? true : false); 
					} else { 
					  $exdone = true; 
					} 

					/*send out an email to inform that a new agent created*/
					$this->__sendemail(
						"A new agent '" 
							. $this->data['Account']['username'] 
							. "' created, please check it out.",
						"empty",
						"support@chatvazoo.com",
						"newagents@chatvazoo.com"
					);
					/*redirect to some page*/ 
					$this->Session->setFlash('Agent "' 
					  . $this->data['Account']['username'] . '" added.' 
					  . ($exdone ? '' : '<br><i>(Site associating failed.)</i>') 
					);
					if ($id != -1) {
						$this->redirect(array('controller' => 'trans', 'action' => 'lstagents'));
					} else {
						$this->redirect(array('controller' => 'trans', 'action' => 'regagent'));
					}
				} else {
					$this->data['Account']['password'] = $this->data['Account']['originalpwd'];
					//should add some codes here to delete the record that saved in 'accounts' table before if failed
				}
			} else {
				$this->data['Account']['password'] = $this->data['Account']['originalpwd'];
			}
		}
	}
	
	function updcompany($id = null) {
		$this->layout = 'defaultlayout';
		
		/*prepare the countries for this view*/
		$cts = $this->Country->find('list', array('fields' => array('Country.abbr', 'Country.fullname')));
		$this->set('cts', $cts);
				
		/*prepare associated sites data*/
		$exsites = $this->SiteExcluding->find('list',
			array(
				'fields' => array('id', 'siteid'),
				'conditions' => array('companyid' => $id)
			)
		);
		$sites = $this->Site->find('list',
			array(
				'fields' => array('id', 'sitename'),
				'conditions' => array('status' => 1)
			)
		);
		$exsites = array_unique($exsites);
		$exsites = array_flip($exsites);
		foreach ($exsites as $k => $v) {
			if (in_array($k, array_keys($sites))) {
				$exsites[$k] = $sites[$k];
			}
		}
		$this->set(compact('exsites'));
		$this->set(compact('sites'));
		
		$this->set('payouttype', $this->Company->payouttype);
		$this->Account->id = $id;
		$this->Company->id = $id;
		if (empty($this->data)) {
			/*read the office into the update page*/
			$account = $this->Account->read();
			//$account['Account']['password'] = '';
			//$account['Account']['originalpwd'] = '';
			$account['Account']['password'] = $account['Account']['originalpwd'];
			$company = $this->Company->read();
			$this->data['Account'] = $account['Account'];
			$this->data['Company'] = $company['Company'];
		} else {
			/*check if the passwords match or empty or untrimed*/
			$originalpwd = $this->data['Account']['originalpwd'];
			if (strlen(trim($originalpwd)) != strlen($originalpwd)) {
				$this->data['Account']['password'] = $this->data['Account']['originalpwd'];
				$this->Session->setFlash('Please remove any blank in front of or at the end of your password and try again.');
				return;
			}
			//if (empty($this->data['Account']['originalpwd']) || $this->data['Account']['password'] != $this->Auth->password($this->data['Account']['originalpwd'])) {
			if (empty($this->data['Account']['originalpwd']) || $this->data['Account']['password'] != md5($this->data['Account']['originalpwd'])) {
				$this->data['Account']['password'] = '';
				$this->data['Account']['originalpwd'] = '';
				$this->Session->setFlash('The passwords don\'t match to each other, please try again(and do not left it blank).');
				return;
			}
			
			/*validate the posted fields*/
			$this->Account->set($this->data);
			$this->Company->set($this->data);
			if (!$this->Account->validates() || !$this->Company->validates()) {
				//$this->data['Account']['password'] = '';
				$this->data['Account']['password'] = $this->data['Account']['originalpwd'];
				$this->Session->setFlash('Please notice the tips below the fields.');
				return;
			}
			
			/*actually save the posted data*/
			$this->Account->create();
			$this->data['Account']['username4m'] = __fillzero4m($this->data['Account']['username']);
			if ($this->Account->save($this->data)) {//1stly, save the data into 'accounts'
				$this->Session->setFlash('Only account updated.');
				
				$this->data['Company']['id'] = $this->Account->id;
				$this->Company->create();
				if ($this->Company->save($this->data)) {//2ndly, save the data into 'companies'
					/*after the office saved, update the site_excluding data, then*/
					$exdone = true;
					if ($this->Auth->user('role') == 0) {//only when it's an admin
						$__sites = $this->data['SiteExcluding']['siteid'];
						if (is_array($__sites)) {
							$__sites = array_diff(array_keys($sites), $__sites);
						} else {
							$__sites = array_keys($sites);
						}
						$exdata = array();
						foreach ($__sites as $__site) {
							array_push(
								$exdata, 
								array(
									'companyid' => $this->data['Company']['id'],
									'siteid' => $__site
								)
							);
						}
						$this->SiteExcluding->deleteAll(//since if no recs to del, it seems also return false, so we ignore it here
							array('companyid' => $this->data['Company']['id'])
						);
						if (!empty($exdata)) {
							$exdone = ($this->SiteExcluding->saveAll($exdata) ? true : false);
						} else {
							$exdone = true;
						}
					}
					
					/*redirect to some page*/
					$this->Session->setFlash('Office "'
						. $this->data['Account']['username'] . '" updated.'
						. ($exdone ? '' : '<br><i>(Site associating failed.)</i>')
					);
					if ($this->Auth->user('role') == 0) {// means an administrator
						$this->redirect(array('controller' => 'trans', 'action' => 'lstcompanies'));
					} else if ($this->Auth->user('role') == 1) {// means an office
						$this->redirect(array('controller' => 'trans', 'action' => 'index'));
					}
					$this->redirect(array('controller' => 'trans', 'action' => 'lstcompanies'));
				} else {
					$this->data['Account']['password'] = $this->data['Account']['originalpwd'];
					//should add some codes here to delete the record that saved in 'accounts' table before if failed
				}
			} else {
				$this->data['Account']['password'] = $this->data['Account']['originalpwd'];
			}			
		}
	}
	
	function updagent($id = null) {
		$this->layout = 'defaultlayout';
		
		/*prepare the companies for this view*/
		$cps = $this->ViewCompany->find('list',
			array(
				'fields' => array('companyid', 'officename'),
				'order' => 'username4m'
			)
		);
		$this->set('cps', $cps);
		/*prepare the email of the current office*/
		if ($this->Auth->user('role') == 1) {
			$this->Company->id = $this->Auth->user('id');
			$curcom = $this->Company->read();
			$this->set(compact('curcom'));
		}
		/*prepare the countries for this view*/
		$cts = $this->Country->find('list', array('fields' => array('Country.abbr', 'Country.fullname')));
		$this->set('cts', $cts);
		
		/*prepare associated sites data*/
		$exsites = $this->SiteExcluding->find('list',
			array(
				'fields' => array('id', 'siteid'),
				'conditions' => array('agentid' => $id)
			)
		);
		$sites = $this->Site->find('list',
			array(
				'fields' => array('id', 'sitename'),
				'conditions' => array('status' => 1)
			)
		);
		$exsites = array_unique($exsites);
		$exsites = array_flip($exsites);
		foreach ($exsites as $k => $v) {
			if (in_array($k, array_keys($sites))) {
				$exsites[$k] = $sites[$k];
			}
		}
		$this->set(compact('exsites'));
		$this->set(compact('sites'));
		
		$this->Account->id = $id;
		$this->Agent->id = $id;
		if (empty($this->data)) {
			/*read the agent into the update page*/
			$account = $this->Account->read();
			//$account['Account']['password'] = '';
			//$account['Account']['originalpwd'] = '';
			$account['Account']['password'] = $account['Account']['originalpwd'];
			$agent = $this->Agent->read();
			$this->data['Account'] = $account['Account'];
			$this->data['Agent'] = $agent['Agent'];
			$this->set('results', $this->data);
		} else {
			$agent = $this->Agent->read();
			$this->set('results', $this->data);
			/*check if the passwords match or empty or untrimed*/
			$originalpwd = $this->data['Account']['originalpwd'];
			if (strlen(trim($originalpwd)) != strlen($originalpwd)) {
				$this->data['Account']['password'] = $this->data['Account']['originalpwd'];
				$this->Session->setFlash('Please remove any blank in front of or at the end of your password and try again.');
				return;
			}
			//if (empty($this->data['Account']['originalpwd']) || $this->data['Account']['password'] != $this->Auth->password($this->data['Account']['originalpwd'])) {
			if (empty($this->data['Account']['originalpwd']) || $this->data['Account']['password'] != md5($this->data['Account']['originalpwd'])) {
				$this->data['Account']['password'] = '';
				$this->data['Account']['originalpwd'] = '';
				$this->Session->setFlash('The passwords don\'t match to each other, please try again(and do not left it blank).');
				return;
			}
			
			/*validate the posted fields*/
			$this->Account->set($this->data);
			$this->Agent->set($this->data);
			if (!$this->Account->validates() || !$this->Agent->validates()) {
				//$this->data['Account']['password'] = '';
				$this->data['Account']['password'] = $this->data['Account']['originalpwd'];
				$this->Session->setFlash('Please notice the tips below the fields.');
				return;
			}
						
			/*actually save the posted data*/
			$this->Account->create();
			$this->data['Account']['username4m'] = __fillzero4m($this->data['Account']['username']);
			if ($this->Account->save($this->data)) {//1stly, save the data into 'accounts'
				$this->Session->setFlash('Only account updated.');
				
				$this->Agent->create();
				if ($this->Agent->save($this->data)) {//2ndly, save the data into 'agents'
					/*after agent saved, update the site_excluding data, then*/ 
					$exdone = true;
					if (in_array($this->Auth->user('role'), array(0, 1))) {//if it's an admin or an office
						$__sites = $this->data['SiteExcluding']['siteid']; 
						if (is_array($__sites)) { 
						  $__sites = array_diff(array_keys($sites), $__sites); 
						} else { 
						  $__sites = array_keys($sites); 
						} 
						$exdata = array(); 
						foreach ($__sites as $__site) { 
						  array_push( 
						    $exdata,  
						    array( 
						      'agentid' => $this->data['Agent']['id'], 
						      'siteid' => $__site 
						    ) 
						  ); 
						} 
						$this->SiteExcluding->deleteAll(//since if no recs to del, it seems also return false, so we ignore it here 
						  array('agentid' => $this->data['Agent']['id']) 
						); 
						if (!empty($exdata)) { 
						  $exdone = ($this->SiteExcluding->saveAll($exdata) ? true : false); 
						} else { 
						  $exdone = true; 
						} 
					}
					/*
					 * If the agent username is changed, then we should change the campaignid
					 * in agent_site_mappings with sites whoes campaign id rule is "__SAME__".
					 * 0.judge if the username is in agent_site_mappings.
					 * 1.set all the flags of old campaign ids to 0s.
					 * 2.insert the new one.
					 */
					//step 0
					$mpchgdone = true;
					$rs = $this->ViewMapping->find('first',
						array(
							'conditions' => array(
								'LOWER(campaignid)' => strtolower($this->data['Account']['username']),
								'flag' => 1
							)
						)
					);
					$__SAME__sites = $this->Site->find('list',
						array(
							'fields' => array('id', 'id'),
							'conditions' => array('id not' => array(-1, -2))//HARD CODE: which means every site that belongs to loadedcash so far here for CVZ
						)
					);
					if (empty($rs)) {
						//step 1
						$mpchgdone = $mpchgdone && $this->AgentSiteMapping->updateAll(
							array('flag' => 0),
							array(
								'agentid' => $this->data['Agent']['id'],
								'siteid' => $__SAME__sites
							)
						);
						//step 2
						foreach ($__SAME__sites as $site) {
							$data = array(
								'AgentSiteMapping' => array(
									'id' => null,
									'agentid' => $this->data['Agent']['id'],
									'siteid' => $site,
									'campaignid' => $this->data['Account']['username']
								)
							);
							$this->AgentSiteMapping->create();
							if ($this->AgentSiteMapping->save($data)) {
								$mpchgdone = $mpchgdone && true;
							} else {
								$mpchgdone = $mpchgdone && false;
							}
						}
					}
					
					
					 
					/*redirect to some page*/ 
					$this->Session->setFlash('Agent "' 
					  . $this->data['Account']['username'] . '" updated.' 
					  . ($exdone ? '' : '<br/><i>(Site associating failed.)</i>')
					  . ($mpchgdone ? '' : '<br/><i>(Mappings changing failed.)</i>')
					);
					if ($this->Auth->user('role') == 0) {// means an administrator
						$this->redirect(array('controller' => 'trans', 'action' => 'lstagents'));
					} else if ($this->Auth->user('role') == 1) {// means an office
						$this->redirect(
							array('controller' => 'trans', 'action' => 'lstagents',
								'id' => $this->Auth->user('id')
							)
						);
					} else if ($this->Auth->user('role') == 2) {// means an agent
						$this->redirect(array('controller' => 'trans', 'action' => 'index'));
					}
				} else {
					$this->data['Account']['password'] = $this->data['Account']['originalpwd'];
					//should add some codes here to delete the record that saved in 'accounts' table before if failed
				}
			} else {
				$this->data['Account']['password'] = $this->data['Account']['originalpwd'];
			}
		}
	}
	
	function lstnewmembers() {
		$this->layout = 'defaultlayout';
		
		if (!empty($this->data)) {// if there are any POST data
		} else {
			$conditions = array(
				'status' => '-1'
			);
			$this->paginate = array(
				'Account' => array(
					'conditions' => $conditions,
					'limit' => $this->__limit,
					'order' => 'username4m'
				)
			);
			$this->set('status', $this->Account->status);
			$this->set('limit', $this->__limit);
			$this->set('rs',
				$this->paginate('Account')
			);
		}
	}
		
	function lstcompanies($id = null) {
		$this->layout = 'defaultlayout';
		
		/*prepare for the searching part*/
		if (!empty($this->data)) {
			$searchfields = $this->data['ViewCompany'];
			if (strlen($searchfields['username']) == 0 && empty($searchfields['username'])) {
				$conditions = array('1' => '1');
			} else {
				$conditions = array(
					'username like' => ('%' . $searchfields['username'] . '%')
				);
			}
		} else {
			if ($id == null || !is_numeric($id)) {
				if ($this->Session->check('conditions_com')) {
					$conditions = $this->Session->read('conditions_com');
				} else {
					$conditions = array('1' => '1');
				}
			} else {
				if ($id != -1) {
					$conditions = array('companyid' => $id);
				} else {//"-1" is specially for the administrator
					$conditions = array('1' => '1');
				}
			}
		}
		
		$this->Session->write('conditions_com', $conditions);
		
		$this->paginate = array(
			'ViewCompany' => array(
				'limit' => $this->__limit,
				'conditions' => $conditions,
				'order' => 'regtime desc'
			)
		);
		
		$this->set('status', $this->Account->status);
		$this->set('online', $this->Account->online);
		$this->set('rs',
			$this->paginate('ViewCompany')
		);
	}
	
	function lstagents($id = null) {
		$this->layout = 'defaultlayout';
		
		/*prepare for the searching part*/
		if (!empty($this->data)) {// if there are any POST data
			$conditions = array(
				'username like' => ('%' . trim($this->data['ViewAgent']['username']) . '%'),
				'lower(ag1stname) like' => ('%' . strtolower(trim($this->data['ViewAgent']['ag1stname'])) . '%'),
				'lower(aglastname) like' => ('%' . strtolower(trim($this->data['ViewAgent']['aglastname'])) . '%'),
				'lower(email) like' => ('%' . strtolower(trim($this->data['ViewAgent']['email'])) . '%')
			);
			if ($this->Auth->user('role') == 0) {
				$companyid = $this->data['Company']['id'];
				if ($companyid != 0) {
					$conditions['companyid'] = array(-1, $companyid);
				}
			} else if ($this->Auth->user('role') == 1){
				$companyid = $this->data['ViewAgent']['companyid'];
				$conditions['companyid'] = array(-2, $companyid);
			}
			$status = $this->data['ViewAgent']['status'];
			if ($status != -1) {
				$conditions['status'] = $status;
			}
			$campaignid = trim($this->data['AgentSiteMapping']['campaignid']);
			if (!empty($campaignid)) {
				$ags = $this->AgentSiteMapping->find('list',
					array(
						'fields' => array('id', 'agentid'),
						'conditions' => array(
							'campaignid like' => ('%' . $campaignid . '%')
						)
					)
				);
				$ags = array_unique($ags);
				$conditions['id'] = $ags;
			}
			$exsite = $this->data['SiteExcluding']['siteid'];
			if ($exsite != -1) {
				$exags = $this->SiteExcluding->find('list',
					array(
						'fields' => array('id', 'agentid'),
						'conditions' => array('siteid' => $exsite)
					)
				);
				$exags = array_unique($exags);
				if (array_key_exists('id', $conditions)) {
					$conditions['id'] = array_intersect($conditions['id'], $exags);
				} else {
					$conditions['id'] = $exags;
				}
			}
		} else {
			if ($id == null || !is_numeric($id)) {
				if ($this->Session->check('conditions_ag')) {
					$conditions = $this->Session->read('conditions_ag');
				} else {
					$conditions = array('1' => '1');
				}
			} else {
				if ($id != -1) {
					$arr = array(-3, $id);//!!!important!!!we must do this to ensure that the "order by" in MYSQL could work normally but not being misunderstanding 
					$conditions = array('companyid' => $arr);
				} else {//"-1" is specially for the administrator
					$conditions = array('1' => '1');
				}
			}
			
		}

		$this->Session->write('conditions_ag', $conditions);
		
		$this->paginate = array(
			'ViewAgent' => array(
				'conditions' => $conditions,
				'limit' => $this->__limit,
				'order' => 'username4m'
			)
		);
		
		$coms = array();
		if ($this->Auth->user('role') == 0) {
			$coms = $this->Company->find('list',
				array(
					'fields' => array('id', 'officename'),
					'order' => 'officename'
				)
			);
		}
		$coms = array('0' => 'All') + $coms;
		$this->set(compact('coms'));

		$sites = $this->Site->find('list',
			array(
				'fields' => array('id', 'sitename'),
				'conditions' => array('status' => 1)
			)
		);
		$sites = array('-1' => '-----------------------') + $sites;
		$this->set(compact('sites'));
		
		$this->set('status', $this->Account->status);
		$this->set('online', $this->Account->online);
		$this->set('limit', $this->__limit);
		$this->set('rs',
			$this->paginate('ViewAgent')
		);
	}
	
	function lstlogins($id = -1) {
		$this->layout = 'defaultlayout';
		
		$selcom = $selagent = 0;
		$startdate = $enddate = date("Y-m-d", strtotime(date('Y-m-d') . " Sunday"));
		//$startdate = date("Y-m-d", strtotime($enddate . " - 7 days"));
		//$enddate = date("Y-m-d", strtotime($startdate . " + 6 days"));
		if (date("D") == "Sun") {
			$enddate = date("Y-m-d", strtotime($startdate . " + 6 days"));
		} else {
			$startdate = date("Y-m-d", strtotime($enddate . " - 7 days"));
			$enddate = date("Y-m-d", strtotime($startdate . " + 6 days"));
		}
		$inip = '';
		
		if (!empty($this->data)) {
			$startdate = $this->data['ViewOnlineLog']['startdate'];
			$enddate = $this->data['ViewOnlineLog']['enddate'];
			$inip = trim($this->data['ViewOnlineLog']['inip']);
			$selcom = $this->data['Stats']['companyid'];
			$selagent = $this->data['ViewOnlineLog']['agentid'];
		} else {
			if ($id != -1) {
				$startdate = '0000-00-00';
				$enddate = date('Y-m-d', mktime (0,0,0,date("m"), date("d") ,date("Y") + 1));
				$inip = '';
				$selagent = $id;
			} else {
				if (array_key_exists('page', $this->passedArgs)) {
					if ($this->Session->check('conditions_loginlogs')) {
						$conds = $this->Session->read('conditions_loginlogs');
						$startdate = $conds['startdate'];
						$enddate = $conds['enddate'];
						$inip = array_key_exists('inip', $conds) ? $conds['inip'] : '';
						$selcom = $conds['selcom'];
						$selagent = $conds['selagent'];
					}
				}
			}
		}
		
		if ($this->Auth->user('role') == 1) {
			$selcom = $this->Auth->user('id');
		} else if ($this->Auth->user('role') == 2) {
			$selagent = $this->Auth->user('id');
			$rs = $this->Agent->find('first',
				array(
					'fields' => array('companyid'),
					'conditions' => array('id' => $selagent)
				)
			);
			$selcom = $rs['Agent']['companyid'];
		}
		
		$conditions = array('1' => '1');
		if ($this->Auth->user('role') == 1) {
			$conditions = array('id' => $this->Auth->user("id"));
		}
		$coms = $this->Company->find('list',
			array(
				'fields' => array('id', 'officename'),
				'order' => 'officename',
				'conditions' => $conditions 
			)
		);
		if (count($coms) > 1) $coms = array('0' => 'All') + $coms;
		$ags = $this->ViewAgent->find('list',
			array(
				'fields' => array('id', 'username'),
				'conditions' => array('companyid' => ($selcom == 0 ? array_keys($coms) : $selcom)),
				'order' => 'username4m'
			)
		);
		if (count($ags) > 1) $ags = array('0' => 'All') + $ags;
		$this->set(compact('coms'));
		$this->set(compact('ags'));
		
		$conds = array(
				'startdate' => $startdate, 'enddate' => $enddate,
				'selcom' => $selcom, 'selagent' => $selagent
		);
		if (!empty($inip)) {
			$conds['inip'] = $inip;
		}
		$this->Session->write('conditions_loginlogs', $conds);

		$conditions = array(
			'accountid !=' => '2',//HARD CODE:NOT TO SHOW THE adminuser's logs
			'convert(intime, date) >=' => $startdate,
			'convert(outtime, date) <=' => $enddate
		);
		if (!empty($inip)) {
			$conditions['inip'] = $inip;
		}
		$comcond = $agentcond = array('1' => '1');
		if ($selcom != 0) {
			$comcond = array('accountid' => array(-1, $selcom));
			if ($selagent != 0) {
				$agentcond = array('accountid' => array(-1, $selagent));
			} else {
				$agentcond = array(
					'accountid' => $this->Agent->find('list',
						array(
							'fields' => array('id', 'id'),
							'conditions' => array('companyid' => $selcom)
						)
					)
				);
			}
			$conditions['OR'] = array($comcond, $agentcond);
		} else {
			if ($selagent != 0) {
				$agentcond = array('accountid' => array(-1, $selagent));
			}
			$conditions['AND'] = array($comcond, $agentcond);
		}
		
		if ($selcom != 0) $conditions['accountid'] = array(-1, $selcom);
		if ($selagent != 0) {
			if (array_key_exists("accountid", $conditions))
				array_push($conditions['accountid'], $selagent);
			else $conditions['accountid'] = array(-1, $selagent);
		} else {
			if (array_key_exists("accountid", $conditions)) {
				$conditions['accountid'] += array_keys($ags);
			}
		}
		
		$this->set(compact('startdate'));
		$this->set(compact('enddate'));
		$this->set(compact('inip'));
		$this->set(compact('selcom'));
		$this->set(compact('selagent'));
		
		$this->paginate = array(
			'ViewOnlineLog' => array(
				'conditions' => $conditions,
				'order' => 'intime desc',
				'limit' => $this->__limit
			)
		);
		$this->set('rs',
			$this->paginate('ViewOnlineLog')
		);
	}
	
	function activatem() {
		/*prepare the parameters*/
		$ids = null;
		if (array_key_exists('ids', $this->passedArgs)) {
			$ids = explode(',', $this->passedArgs['ids']);
		}
		$status = -1;
		if (array_key_exists('status', $this->passedArgs)) {
			$status = intval($this->passedArgs['status']);
		}
		$from = -1;
		if (array_key_exists('from', $this->passedArgs)) {
			$from = intval($this->passedArgs['from']);
		}
		if ($ids == null || $status == -1 || $from == -1) {
			$this->redirect(array('controller' => 'trans', 'action' => 'index'));
		}
		if ($status > 1 || $status < -1) {
			$this->redirect(array('controller' => 'trans', 'action' => 'index'));
		}
		$action = 'lstcompanies';
		if ($from == 1) $action = 'lstagents';
		if ($from == 2) $action = 'lstnewmembers';
		
		/*update the field "status" of table accounts*/
		if ($this->Account->updateAll(array('status' => $status), array('id' => $ids))) {
			$this->Session->setFlash('The selected all have been ' . $this->Account->status[$status] . '.');
		};
		
		$this->redirect(array('controller' => 'trans', 'action' => $action));
	}
	
	function requestchg() {
		$this->layout = 'defaultlayout';
		
		$data = $this->data;
		$content = "";
		if (!empty($data)) {
			if (array_key_exists('Requestchg', $data)) {
				/*try to send the request*/
				if ($data['Requestchg']['role'] == 2) {//means a request for changing an agent
					$sites = $this->Site->find('list',
						array(
							'fields' => array('id', 'abbr'),
							'conditions' => array('id' => $data['SiteExcluding']['siteid'])
						)
					);
					
					$content = "Request for:\n\n" 
						. "Office(*):" . $data['Agent']['companyshadow'] . "\n"
						. "First Name(*):" . $data['Agent']['ag1stname'] . "\n"
						. "Last Name(*):" . $data['Agent']['aglastname'] . "\n"
						. "Email(*):" . $data['Agent']['email'] . "\n"
						. "Username(*):" . $data['Account']['username'] . "\n"
						. "Password(*):" . $data['Account']['originalpwd'] . "\n"
						. "Street Name & Number:" . $data['Agent']['street'] . "\n"
						. "City:" . $data['Agent']['city'] . "\n"
						. "State & Zip:" . $data['Agent']['state'] . "\n"
						. "Country(*):" . $data['Agent']['country'] . "\n"
						. "Instant Messenger Contact(*):" . $data['Agent']['im'] . "\n"
						. "Cell NO.(*):" . $data['Agent']['cellphone'] . "\n"
						. "Associated Sites:" . implode(",", $sites) . "\n";
						
					/*send the message*/
					$issent = false;
					if ($data['Requestchg']['type'] == 'reg') {//means an adding request
						$subject = "Request For New Agent";
						$content .= "\n\n(Request from office manager \"" . $data['Requestchg']['offiname']
							. "\", with email address \"" . $data['Requestchg']['from'] . "\").";
						$issent = $this->__sendemail($subject, $content);
					} else if ($data['Requestchg']['type'] == 'upd') {//means an updating request
						$subject = "Request For Updating Agent";
						$content .= "\n\n(Request from office manager \"" . $data['Requestchg']['offiname']
							. "\", with email address \"" . $data['Requestchg']['from'] . "\").";
						$issent = $this->__sendemail($subject, $content);
					}
					
					if ($issent) {
						$this->Session->setFlash('Request sent, please wait for reply.');
					} else {
						//$this->Session->setFlash($issent);
						$this->Session->setFlash('Failed to send request, please contact your administrator.');
					}
				}
			}
		}
		$this->set('data', $this->data);
		$this->set(compact("content"));
	}
	
	function addchatlogs() {
		$this->layout = 'defaultlayout';
		
		if ($this->Auth->user('role') != 2) {// if not an agent
			$this->Session->setFlash('Only agent could submit chat logs.');
			$this->redirect(array('controller' => 'trans', 'action' => 'index'));	
		}
		if (!empty($this->data)) {
			$this->ChatLog->create();
			$submittime = new DateTime("now", new DateTimeZone($this->__svrtz));
			$this->data['ChatLog'] = array_merge(
				$this->data['ChatLog'],
				array('submittime' => $submittime->format("Y-m-d H:i:s"))
			);
			if ($this->ChatLog->save($this->data)) {
				$r = $this->ViewAgent->find('first',
					array(
						'conditions' => array('id' => $this->data['ChatLog']['agentid'])
					)
				);
				$r0 = $this->Site->find('first',
					array(
						'conditions' => array('id' => $this->data['ChatLog']['siteid'])
					)
				);
				$subject = 'Office:' . $r['ViewAgent']['officename']
					. ' Agent:' . $r['ViewAgent']['username']
					. ' -- Chat Log';
				$content = "Client:" . $this->data['ChatLog']['clientusername'] . "\n"
					. "Conversation:(" . $r0['Site']['sitename'] . ")\n"
					. $this->data['ChatLog']['conversation'] . "\n"
					. "-" . $submittime->format("Y-m-d H:i:s") . " " . $this->__svrtz;
				$fmsg = '';
				/*
				$mailto = ''
					//. strtolower($r['ViewAgent']['officename']) . '_qa@cleanchattersinc.com';
					. 'support@chatvazoo.com';
				if ($this->__sendemail(
						$subject, $content,
						'support@chatvazoo.com',
						$mailto
					) != true) {
					$fmsg = '(Failed to email out.<0>)';
				};
				*/
				if ($this->__sendemail(
						$subject, $content,
						'support@chatvazoo.com',
						//'qa@cleanchattersinc.com'
						'agentlogs@chatvazoo.com'
					) != true) {
					$fmsg = '(Failed to email out.<1>)';
				};
				$this->Session->setFlash('Chat log submitted.' . $fmsg);
				$this->redirect(array('controller' => 'trans', 'action' => 'lstchatlogs'));
			}
		}
		
		$sites = $this->Site->find('list',
			array(
				'fields' => array('id', 'sitename'),
				'conditions' => array('status' => 1)
			)
		);
		$this->set(compact("sites"));
	}
	
	function lstchatlogs($id = -1) {
		$this->layout = 'defaultlayout';
		
		$startdate = $enddate = date("Y-m-d", strtotime(date('Y-m-d') . " Sunday"));
		//$startdate = date("Y-m-d", strtotime($enddate . " - 7 days"));
		//$enddate = date("Y-m-d", strtotime($startdate . " + 6 days"));
		if (date("D") == "Sun") {
			$enddate = date("Y-m-d", strtotime($startdate . " + 6 days"));
		} else {
			$startdate = date("Y-m-d", strtotime($enddate . " - 7 days"));
			$enddate = date("Y-m-d", strtotime($startdate . " + 6 days"));
		}
		$selcom = $selagent = $selsite = 0;

		if ($this->Auth->user('role') == 1) {// means an office
			$selcom = $this->Auth->user('id');
			if ($id != -1) {
				$selagent = $id;
			}
		} else if ($this->Auth->user('role') == 2) {// means an agent
			$selagent = $this->Auth->user('id');
			$rs = $this->Agent->find('first',
				array(
					'fields' => array('companyid'),
					'conditions' => array('id' => $selagent)
				)
			);
			if (empty($rs)) $selcom = 0;
			else $selcom = $rs['Agent']['companyid'];
		} else if ($this->Auth->user('role') == 0) {// means an admin
			if ($id != -1) {
				$selagent = $id;
			}
		}
		
		if (!empty($this->data)) {
			$startdate = $this->data['ViewChatLog']['startdate'];
			$enddate = $this->data['ViewChatLog']['enddate'];
			$selcom = $this->data['Stats']['companyid'];
			$selagent = $this->data['ViewChatLog']['agentid'];
			$selsite = $this->data['ViewChatLog']['siteid'];
		} else {
			if ($id != -1) {
				if ($this->Session->check('conditions_chatlogs')) {
					$conds = $this->Session->read('conditions_chatlogs');
					$startdate = $conds['startdate'];
					$enddate = $conds['enddate'];
					$selcom = $conds['selcom'];
					$selagent = $conds['selagent'];
					$selsite = $conds['selsite'];
				} else {
					$conditions = '0 = 1';
				}
			} else {
				if (array_key_exists('page', $this->passedArgs)) {
					if ($this->Session->check('conditions_chatlogs')) {
						$conds = $this->Session->read('conditions_chatlogs');
						$startdate = $conds['startdate'];
						$enddate = $conds['enddate'];
						$selcom = $conds['selcom'];
						$selagent = $conds['selagent'];
						$selsite = $conds['selsite'];
					}
				}
			}
		}
		
		$conditions = array('1' => '1');
		if ($this->Auth->user('role') == 1) {
			$conditions = array('id' => $this->Auth->user('id'));
		}
		$coms = $this->Company->find('list',
			array(
				'fields' => array('id', 'officename'),
				'order' => 'officename',
				'conditions' => $conditions 
			)
		);
		if (count($coms) > 1) $coms = array('0' => 'All') + $coms;
		$ags = $this->ViewAgent->find('list',
			array(
				'fields' => array('id', 'username'),
				'conditions' => array('companyid' => ($selcom == 0 ? array_keys($coms) : $selcom)),
				'order' => 'username4m'
			)
		);
		if (count($ags) > 1) $ags = array('0' => 'All') + $ags;
		$sites = $this->Site->find('list',
			array(
				'fields' => array('id', 'sitename')
			)
		);
		$sites = array('0' => 'All') + $sites;
		$this->set(compact('coms'));
		$this->set(compact('ags'));
		$this->set(compact('sites'));
		
		$conds = array(
			'startdate' => $startdate, 'enddate' => $enddate,
			'selcom' => $selcom, 'selagent' => $selagent, 'selsite' => $selsite
		);
		$this->Session->write('conditions_chatlogs', $conds);
		
		$conditions = array(
			'convert(submittime, date) >=' => $startdate,
			'convert(submittime, date) <=' => $enddate
		);
		if ($selcom != 0) $conditions['companyid'] = array(-1, $selcom);
		if ($this->Auth->user('role') == 1) {
			$conditions['companyid'] = array(-1, $this->Auth->user('id'));
		}
		if ($selagent != 0) $conditions['agentid'] = array(-1, $selagent);
		if ($this->Auth->user('role') == 2) {
			$conditions['agentid'] = array(-1, $this->Auth->user('id'));
		}
		if ($selsite != 0) $conditions['siteid'] = array(-1, $selsite);
		
		$this->set(compact('startdate'));
		$this->set(compact('enddate'));
		$this->set(compact('selcom'));
		$this->set(compact('selagent'));
		$this->set(compact('selsite'));
		
		$this->paginate = array(
			'ViewChatLog' => array(
				'conditions' => $conditions,
				'order' => 'username4m',
				'limit' => $this->__limit
			)
		);
		$this->set('rs', $this->paginate('ViewChatLog'));
	}
		
	function __go($siteid, $typeid, $url, $referer, $agentid, $clicktime, $linkid = null) {
		//if (__isblocked(__getclientip())) {
		if (false) {
			$this->Session->setFlash('Sorry, you\'re not allowed to check the link.');
			$this->render('/trans/go');
			return;
		} else {
			/*log this click*/
			$this->data['Clickout']['linkid'] = $linkid;
			$this->data['Clickout']['agentid'] = $agentid;
			$this->data['Clickout']['clicktime'] = $clicktime;
			$this->data['Clickout']['fromip'] = __getclientip();
			$this->data['Clickout']['siteid'] = $siteid;
			$this->data['Clickout']['typeid'] = $typeid;
			$this->data['Clickout']['url'] = $url;
			$this->data['Clickout']['referer'] = $referer;
			$this->Clickout->save($this->data);
			/*and redirect to the real url*/
			$this->redirect($url);
		}
	}
	
	function golink() {
		$to = '';
		if (array_key_exists('to', $this->passedArgs)) {
			$to = $this->passedArgs['to'];
		}
		
		$ids = explode(',', __codec($to, 'D'));//$ids[0] will be the link id, and $ids[1] should be the agent id
		$linkid = $ids[0];
		$agentid = $ids[1];
		$this->Link->id = $linkid;
		$this->data = $this->Link->read();
		$siteid = $this->data['Link']['siteid'];
		
		$r = $this->Site->find('first',
			array(
				'conditions' => array(
					'id' => $siteid
				)
			)
		);
		if (empty($r)) {
			$this->Session->setFlash("No such site!");
			$this->render('/trans/go', 'errorlayout');
			return;
		} else {
			/*
			if ($r['Site']['status'] == 0) {
				$this->Session->setFlash("The site has been suspended for now, contact you aministrator for further informations, please.");
				$this->render('/trans/go', 'errorlayout');
				return;
			}
			*/
		}
		$r = $this->Agent->find('first',
			array(
				'conditions' => array('id' => $agentid)
			)
		);
		if (empty($r)) {
			$this->Session->setFlash("No such agent!");
			$this->render('/trans/go', 'errorlayout');
			return;
		}
		/*
		$companyid = $r['Agent']['companyid'];
		$r = $this->SiteExcluding->find('first',
			array(
				'conditions' => array(
					'OR' => array(
						array('siteid' => $siteid, 'agentid' => $agentid),
						array('siteid' => $siteid, 'companyid' => $companyid)
					)
				)
			)
		);
		if (!empty($r)) {
			$this->Session->setFlash("Sorry, you are not allowed to the link for the moment.");
			$this->render('/trans/go', 'errorlayout');
			return;
		}
		*/
		
		$this->__go($siteid, -1, '', $this->data['Link']['url'], '', $agentid, date('Y-m-d H:i:s'), $linkid);
	}
	
	function go() {
		$this->layout = 'errorlayout';
		
		/*
		 * get referer URL and parse it
		 */
		$referer = (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
		$purl = parse_url($referer);
		$phost = '';
		if ($purl !== false && array_key_exists("host", $purl)) {
			$phost = $purl['host'];
		}
		
		if (count($this->passedArgs) != 3) {//if there are illegal args passed
			$this->Session->setFlash("Undefined link, please try another one.");
			return;
		}
		$siteid = $this->passedArgs[0];
		$typeid = $this->passedArgs[1];
		$agentusername = trim($this->passedArgs[2]);
		
		$r = $this->Site->find('first',
			array(
				'conditions' => array(
					'id' => $siteid
				)
			)
		);
		if (empty($r)) {
			$this->Session->setFlash("No such site!");
			return;
		} else {
			/*
			if ($r['Site']['status'] == 0) {
				$this->Session->setFlash("The site has been suspended for now, contact you aministrator for further informations, please.");
				return;
			}
			*/
		}
		
		$r = $this->ViewAgent->find('first',
			array(
				'conditions' => array(
					'lower(username)' => strtolower($agentusername)
				)
			)
		);
		if (empty($r)) {
			$this->Session->setFlash("Agent does not exist, please try again.");
			return;
		}
		$agentid = $r['ViewAgent']['id'];
		/*
		$companyid = $r['ViewAgent']['companyid'];		
		$r = $this->SiteExcluding->find('first',
			array(
				'conditions' => array(
					'OR' => array(
						array('siteid' => $siteid, 'agentid' => $agentid),
						array('siteid' => $siteid, 'companyid' => $companyid)
					)
				)
			)
		);
		if (!empty($r)) {
			$this->Session->setFlash("Sorry, you are not allowed to the link for the moment.");
			return;
		}
		*/
		
		$r = $this->Type->find('first',
			array(
				'conditions' => array(
					'siteid' => $siteid,
					'id' => $typeid
				)
			)
		);
		if (empty($r)) {
			$this->Session->setFlash("Undefined type, please try another one.");
			return;
		}
		$url = $r['Type']['url'];
		$searchstr = '__CAM__';
		if (strpos($url, $searchstr) === false) {
			$this->Session->setFlash("Undefined replace string, please try another one.");
			return;
		}
		$r = $this->AgentSiteMapping->find('first',
			array(
				'conditions' => array(
					'siteid' => $siteid,
					'agentid' => $agentid
				)
			)
		);
		if (empty($r)) {//no campaign id found
			$this->Session->setFlash("Undefined campaign, please try another one.");
			return;
		}
		$campaignid = $r['AgentSiteMapping']['campaignid'];
		$url = str_replace($searchstr, $campaignid, $url);
		
		//$this->Session->setFlash($url);//for debug
		$this->__go($siteid, $typeid, $url, $phost, $agentid, date('Y-m-d H:i:s'));
	}
	
	function updtoolbox() {
		$this->layout = 'defaultlayout';
		
		$site = -1;
		
		if (empty($this->data)) {
			if (array_key_exists('site', $this->passedArgs)) {
				$site = $this->passedArgs['site'];
			}
			
			$this->data = $this->SiteManual->find('first',
				array(
					'conditions' => array('siteid' => $site)
				)
			);
		} else {
			if ($this->SiteManual->save($this->data)) {
				$this->Session->setFlash('Updted.');
			} else {
				$this->Session->setFlash('Failed to update.');
			}
			
			$site = $this->data['SiteManual']['siteid'];
		}
		
		$rs = $this->Site->find('first',
			array(
				'conditions' => array('id' => $site)
			)
		);
		$this->set(compact('rs'));
	}
	
	function toolbox() {
		$this->layout = 'defaultlayout';
		
		$site = -1;
		if (array_key_exists('site', $this->passedArgs)) {
			$site = $this->passedArgs['site'];
		}
		$rs = $this->Site->find('first',
			array(
				'conditions' => array('id' => $site)
			)
		);
		$this->set(compact('rs'));
		
		$this->set('data',
			$this->SiteManual->find('first',
				array(
					'conditions' => array('siteid' => $site)
				)
			)
		);
	}
	
	function upload() {
		Configure::write('debug', '0');
		$this->layout = 'errorlayout';
		
		if (!array_key_exists('CKEditorFuncNum', $_GET)) {
			$this->set('script', __makeuploadhtml(1, '', 'Illegal request!'));
			exit();
		}
		$funcn = $_GET['CKEditorFuncNum'];
		/*
		 * see if there is any file uploads
		*/
		if (!isset($HTTP_POST_FILES) && !isset($_FILES)) {
			$this->set('script', __makeuploadhtml(1, '', "No file uploads."));
			exit();
		}
		
		if(!isset($_FILES) && isset($HTTP_POST_FILES)) {
			$_FILES = $HTTP_POST_FILES;
		}
		
		$files = array_values($_FILES);
		$_file = $files[0];
		
		$filename = "/var/www/awl/uploads/images/" . $_file['name'];
		if (!copy($_file['tmp_name'], $filename)) {
			$this->set('script', __mkuploadhtml($funcn, '', 'Failed to upload.'));
		} else {
			$this->set('script', __mkuploadhtml($funcn, '/../awl/uploads/images/' . $_file['name'], ''));
		}
	}
}
