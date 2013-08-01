<?php
App::import('vendor', 'ExtraKits', array('file' => 'extrakits.inc.php'));
?>
<?php
class CanalController extends AppController {
	var $name = 'Canal';
	var $uses = array();
	
	/**
	 * overrides
	 */
	function beforeFilter() {
		
		parent::beforeFilter();
	}
	
	/**
	 * views
	 */
	function index() {
		$this->layout = "emptylayout";
		
		$n = -1;
		$ip = __getclientip();
		if (isset($_POST['ch'])) {
			$n = $_POST['ch'];
		}
		switch ($n) {
			case 0:
			case 1:
				$s = "from $n, accepted";
				break;
			default:
				$s = "illegal post";
				break;
		}
		$this->set(compact("s"));
		$this->set(compact("n"));
		$this->set(compact("ip"));
	}
}
	