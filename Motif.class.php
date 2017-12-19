<?php
namespace FreePBX\modules;
class Motif implements \BMO {

	public function __construct($freepbx = null) {
		$this->freepbx = $freepbx;
		$this->db = $freepbx->Database;
	}

	public function install() {
		$table = $this->db->migrate("motif");
		$cols = array (
			'id' =>
			array (
				'type' => 'integer',
				'autoincrement' => true,
				'primaryKey' => true,
			),
			'phonenum' =>
			array (
				'type' => 'string',
				'length' => 12,
			),
			'username' =>
			array (
				'type' => 'string',
				'length' => 100,
			),
			'password' =>
			array (
				'type' => 'string',
				'length' => 150,
			),
			'refresh_token' =>
			array (
				'type' => 'string',
				'length' => 150,
			),
			'oauth_secret' =>
			array (
				'type' => 'string',
				'length' => 150,
			),
			'oauth_clientid' =>
			array (
				'type' => 'string',
				'length' => 150,
			),
			'type' =>
			array (
				'type' => 'string',
				'length' => 50,
				'default' => 'googlevoice',
			),
			'settings' =>
			array (
				'type' => 'blob',
			),
			'statusmessage' =>
			array (
				'type' => 'string',
				'length' => 50,
			),
			'priority' =>
			array (
				'type' => 'integer',
				'default' => '127',
			),
			'authmode' =>
			array (
				'type' => 'string',
				'length' => 50,
				'default' => 'plaintext'
			),
		);


		$indexes = array (
		);
		$out = $table->modify($cols, $indexes);
		unset($table);
	}

	public function uninstall() {

	}

	public function backup() {

	}

	public function restore($backup) {

	}

	public function getRightNav($request) {
		if(isset($request['action']) && in_array($request['action'], array('addaccount','showaccount'))) {
			return load_view(__DIR__."/views/rnav.php",array());
		} else {
			return '';
		}
	}

	public function showPage() {
		$html = '';
		$defaults = array(
			'phonenum' => '',
			'username' => '',
			'password' => '',
			'type' => 'googlevoice',
			'refresh_token' => '',
			'oauth_secret' => '',
			'oauth_clientid' => '',
			'settings' => array(
				'trunk' => false,
				'ibroute' => false,
				'obroute' => false,
				'gvm' => false,
				'greeting' => false
			),
			'authmode' => 'plaintext',
			'statusmessage' => 'I am Available',
			'priority' => '127',
			'status' => array(
				'connected' => false
			)
		);

		$engineinfo = engine_getinfo();
		$version =  $engineinfo['version'];
		$showOauth2 = version_compare($version, '15.1.0', 'ge');
		if(!$showOauth2) {
			$showOauth2 = version_compare($version, '14.0', 'ge') && version_compare($version, '15.0', 'lt') && version_compare($version, '14.7.0', 'ge');
		}
		if(!$showOauth2) {
			$showOauth2 = version_compare($version, '13.0', 'ge') && version_compare($version, '14.0', 'lt') && version_compare($version, '13.18.0', 'ge');
		}

		$defaults['showOauth2'] = $showOauth2;

		switch($_REQUEST['action']) {
			case "showaccount":
				$account = $this->getAccountByID($_REQUEST['account']);
				if(!$showOauth2) {
					$account['authmode'] == 'plaintext';
				}
				$html .= load_view(__DIR__."/views/account.php",array_merge($defaults,$account));
			break;
			case "addaccount":
				$html .= load_view(__DIR__."/views/account.php",$defaults);
			break;
			default:
				$html .= load_view(__DIR__."/views/grid.php",array());
			break;
		}
		return $html;
	}

	public function getActionBar($request){
		$buttons = array();
		$request['action'] = !empty($request['action']) ? $request['action'] : '';
		$request['display'] = !empty($request['display']) ? $request['display'] : '';
		switch($request['action']) {
			case 'addaccount':
			case 'showaccount':
				$buttons = array(
					'submit' => array(
						'name' => 'submit',
						'id' => 'submit',
						'value' => _("Submit"),
					),
					'delete' => array(
						'name' => 'delete',
						'id' => 'delete',
						'value' => _("Delete"),
						'class' => array('hidden')
					),
					'reset' => array(
						'name' => 'reset',
						'id' => 'reset',
						'value' => _("Reset"),
					),
				);
			break;
		}
		return $buttons;
	}

	public function doConfigPageInit($page) {
		if($page == 'motif' && !empty($_POST['phonenum'])) {
			if(!empty($_POST['id'])) {
				$this->updateAccount($_POST['id'], $_POST);
			} else {
				$this->saveAccount($_POST);
			}
		}
		if($page == 'motif' && !empty($_GET['action']) && $_GET['action'] == 'delete') {
			$this->deleteAccount($_GET['account']);
		}
	}

	/**
	 * Ajax Request
	 * @param string $req     The request type
	 * @param string $setting Settings to return back
	 */
	public function ajaxRequest($req, &$setting){
		switch($req){
			case "getaccounts":
				return true;
			break;
		}
		return false;
	}

	public function ajaxHandler(){
		$request = $_REQUEST;
		switch($request['command']){
			case "getaccounts":
				return $this->getAllAccounts();
			break;
		}
	}

	public function deleteAccount($id) {
		$sql = "DELETE FROM motif WHERE `id` = :id";
		$sth = $this->db->prepare($sql);
		$sth->execute(array(
			":id" => $id
		));
		needreload();
	}

	public function saveAccount($data) {
		$sql = "INSERT INTO `motif` (`authmode`,`phonenum`, `username`, `password`, `settings`, `statusmessage`, `priority`, `refresh_token`, `oauth_secret`, `oauth_clientid`) VALUES (:authmode, :phonenum, :username, :password, :settings, :statusmessage, :priority, :refresh_token, :oauth_secret, :oauth_clientid)";
		$sth = $this->db->prepare($sql);
		$settings = serialize(array(
			"trunk" => $data['trunk'] == 'yes' ? true : false,
			"ibroute" => false,
			"obroute" => $data['obroute'] == 'yes' ? true : false,
			"gvm" => $data['gvm'] == 'yes' ? true : false,
			"greeting" => $data['greeting'] == 'yes' ? true : false,
		));
		$sth->execute(array(
			":authmode" => $data['authmode'],
			":phonenum" => $data['phonenum'],
			":username" => $data['username'],
			":password" => $data['password'],
			":settings" => $settings,
			":statusmessage" => $data['statusmessage'],
			":priority" => $data['priority'],
			":refresh_token" => $data['refresh_token'],
			":oauth_secret" => $data['oauth_secret'],
			":oauth_clientid" => $data['oauth_clientid']
		));
		needreload();
	}

	public function updateAccount($id, $data) {
		$sql = "UPDATE `motif` SET `authmode` = :authmode, `phonenum` = :phonenum, `username` = :username, `password` = :password, `settings` = :settings, `statusmessage` = :statusmessage, `priority` = :priority, `refresh_token` = :refresh_token, `oauth_secret` = :oauth_secret, `oauth_clientid` = :oauth_clientid WHERE `id` = :id";
		$sth = $this->db->prepare($sql);
		$settings = serialize(array(
			"trunk" => $data['trunk'] == 'yes' ? true : false,
			"ibroute" => false,
			"obroute" => $data['obroute'] == 'yes' ? true : false,
			"gvm" => $data['gvm'] == 'yes' ? true : false,
			"greeting" => $data['greeting'] == 'yes' ? true : false,
		));
		$sth->execute(array(
			":id" => $id,
			":authmode" => $data['authmode'],
			":phonenum" => $data['phonenum'],
			":username" => $data['username'],
			":password" => $data['password'],
			":settings" => $settings,
			":statusmessage" => $data['statusmessage'],
			":priority" => $data['priority'],
			":refresh_token" => $data['refresh_token'],
			":oauth_secret" => $data['oauth_secret'],
			":oauth_clientid" => $data['oauth_clientid']
		));
		needreload();
	}

	public function getAllAccounts() {
		$sql = "SELECT * FROM `motif`";
		$sth = $this->db->query($sql);
		$out = $sth->fetchAll(\PDO::FETCH_ASSOC);
		if(empty($out)) {
			return array();
		}
		foreach($out as &$o) {
			$o['context'] = str_replace(array('@','.'),'',$o['username']);
			$o['settings'] = unserialize($o['settings']);
		}
		return $out;
	}

	public function getAccountByID($id) {
		$astman = $this->freepbx->astman;
		$sql = "SELECT * FROM `motif` WHERE `id` = :id";
		$sth = $this->db->prepare($sql);
		$sth->execute(array(":id" => $id));
		$out = $sth->fetch(\PDO::FETCH_ASSOC);
		if(empty($out)) {
			return array();
		}
		$r = $astman->command("xmpp show connections");
		$out['connected'] = false;
		$context = str_replace(array('@','.'),'',$out['username']);
		if(preg_match('/\[g'.$context.'\] '.$out['username'].'.* (Connected)/i',$r['data'],$matches)) {
			$out['connected'] = true;
		};
		$out['context'] = $context;
		$out['settings'] = unserialize($out['settings']);
		return $out;
	}
}
