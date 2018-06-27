<?php
namespace FreePBX\modules;
use BMO;
use FreePBX_Helpers;
use PDO;
class Motif extends FreePBX_Helpers implements BMO {

	public function install() {}

	public function uninstall() {

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
				'trunk' => true,
				'ibroute' => false,
				'obroute' => true,
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
		$this->FreePBX->Modules->loadFunctionsInc('core');
		$account = $this->getAccountByID($id);

		if(isset($account['settings']['trunk_number']) && $this->FreePBX->Core->getTrunkTech($account['settings']['trunk_number'])) {
			$this->FreePBX->Core->deleteTrunk($account['settings']['trunk_number']);
		}

		//If we created a route then delete it
		if(isset($account['settings']['obroute_number']) && $this->FreePBX->Core->getRouteByID($account['settings']['obroute_number'])) {
			core_routing_delbyid($account['settings']['obroute_number']);
		}

		$sql = "DELETE FROM motif WHERE `id` = :id";
		$sth = $this->FreePBX->Database->prepare($sql);
		$sth->execute(array(
			":id" => $id
		));
		needreload();
	}

	public function saveAccount($data) {
		$sql = "INSERT INTO `motif` (`authmode`,`phonenum`, `username`, `password`, `settings`, `statusmessage`, `priority`, `refresh_token`, `oauth_secret`, `oauth_clientid`) VALUES (:authmode, :phonenum, :username, :password, :settings, :statusmessage, :priority, :refresh_token, :oauth_secret, :oauth_clientid)";
		$sth = $this->FreePBX->Database->prepare($sql);
		$settings = array(
			"trunk" => $data['trunk'] == 'yes' ? true : false,
			"ibroute" => false,
			"obroute" => $data['obroute'] == 'yes' ? true : false,
			"gvm" => $data['gvm'] == 'yes' ? true : false,
			"greeting" => $data['greeting'] == 'yes' ? true : false,
		);
		$settings = $this->updateTrunks($data['username'],$data['phonenum'],$settings);
		$settings = $this->updateRoutes($data['username'],$data['phonenum'],$settings);
		$sth->execute(array(
			":authmode" => $data['authmode'],
			":phonenum" => $data['phonenum'],
			":username" => $data['username'],
			":password" => $data['password'],
			":settings" => serialize($settings),
			":statusmessage" => $data['statusmessage'],
			":priority" => $data['priority'],
			":refresh_token" => $data['refresh_token'],
			":oauth_secret" => $data['oauth_secret'],
			":oauth_clientid" => $data['oauth_clientid']
		));
		needreload();
	}

	public function updateAccount($id, $data) {
		$account = $this->getAccountByID($id);

		$sql = "UPDATE `motif` SET `authmode` = :authmode, `phonenum` = :phonenum, `username` = :username, `password` = :password, `settings` = :settings, `statusmessage` = :statusmessage, `priority` = :priority, `refresh_token` = :refresh_token, `oauth_secret` = :oauth_secret, `oauth_clientid` = :oauth_clientid WHERE `id` = :id";
		$sth = $this->FreePBX->Database->prepare($sql);
		$settings = array(
			"trunk" => $data['trunk'] == 'yes' ? true : false,
			"ibroute" => false,
			"obroute" => $data['obroute'] == 'yes' ? true : false,
			"gvm" => $data['gvm'] == 'yes' ? true : false,
			"greeting" => $data['greeting'] == 'yes' ? true : false
		);
		if(isset($account['settings']['trunk_number'])) {
			$settings['trunk_number'] = $account['settings']['trunk_number'];
		}
		if(isset($account['settings']['obroute_number'])) {
			$settings['obroute_number'] = $account['settings']['obroute_number'];
		}
		$settings = $this->updateTrunks($data['username'],$data['phonenum'],$settings);
		$settings = $this->updateRoutes($data['username'],$data['phonenum'],$settings);
		$sth->execute(array(
			":id" => $id,
			":authmode" => $data['authmode'],
			":phonenum" => $data['phonenum'],
			":username" => $data['username'],
			":password" => $data['password'],
			":settings" => serialize($settings),
			":statusmessage" => $data['statusmessage'],
			":priority" => $data['priority'],
			":refresh_token" => $data['refresh_token'],
			":oauth_secret" => $data['oauth_secret'],
			":oauth_clientid" => $data['oauth_clientid']
		));
		needreload();
	}

	public function updateTrunks($username,$number,$settings) {
		$this->FreePBX->Modules->loadFunctionsInc('core');
		$dialstring = 'Motif/g'.str_replace(array('@','.'),'',$username).'/$OUTNUM$@voice.google.com';
		if($settings['trunk']) {
			if(isset($settings['trunk_number']) && $this->FreePBX->Core->getTrunkTech($settings['trunk_number'])) {
				core_trunks_edit($settings['trunk_number'], $dialstring, '', '', $number, '', 'notneeded', '', '', 'off', '', 'off', 'GVM_' . $number, '', 'off', 'r');
			} else {
				$trunknum = core_trunks_add('custom', $dialstring, '', '', $number, '', 'notneeded', '', '', 'off', '', 'off', 'GVM_' . $number, '', 'off', 'r');
				$settings['trunk_number'] = $trunknum;
			}
		} else {
			if(isset($settings['trunk_number']) && $this->FreePBX->Core->getTrunkTech($settings['trunk_number'])) {
				$this->FreePBX->Core->deleteTrunk($s['trunk_number']);
			}
			unset($settings['trunk_number']);
		}
		return $settings;
	}

    public function upsert($id, $phonenum, $username, $password, $settings, $statusmessage, $priority){
        $sql = "REPLACE INTO motif (id, phone, phonenum, username, password, settings, statusmessage, priority) VALUES (:id, :phone, :phonenum, :username, :password, :settings, :statusmessage, :priority)";
        $this->FreePBX->Database->prepare($sql)
            ->execute([
                ':id' => $id, 
                ':phone' => $phone, 
                ':phonenum' => $phonenum, 
                ':username' => $username , ':password' => $password, 
                ':settings' => $settings, 
                ':statusmessage' => $statusmessage, 
                ':priority' => $priority, 
            ]);
        return $this;
    }

	public function updateRoutes($username,$number,$settings) {
		$this->FreePBX->Modules->loadFunctionsInc('core');
		$dialpattern = array(
			array(
				'prepend_digits' => '1',
				'match_pattern_prefix' => '',
				'match_pattern_pass' => 'NXXNXXXXXX',
				'match_cid' => ''
			),
			array(
				'prepend_digits' => '',
				'match_pattern_prefix' => '',
				'match_pattern_pass' => '1NXXNXXXXXX',
				'match_cid' => ''
			)
		);

		//Replace all non-standard characters for route names.
		$routename = 'GVM-'.str_replace(array('@','.'),'',$username);
		if($settings['obroute']) {
			if(isset($settings['trunk_number'])) {
				if(isset($settings['obroute_number']) && $this->FreePBX->Core->getRouteByID($settings['obroute_number'])) {
					core_routing_editbyid($settings['obroute_number'], $routename, '', '', '', '', '', 'default', '', $dialpattern, array($settings['trunk_number']));
				} else {
					$routenum = core_routing_addbyid($routename, '', '', '', '', '', 'default', '', $dialpattern, array($settings['trunk_number']));
					$settings['obroute_number'] = $routenum;
				}
			} else {
				//no trunk but they wanted to create routes??? what?
			}
		} else {
			if(isset($settings['obroute_number']) && $this->FreePBX->Core->getRouteByID($settings['obroute_number'])) {
				core_routing_delbyid($settings['obroute_number']);
			}
		}
		return $settings;
	}

	public function getAllAccounts() {
		$sql = "SELECT * FROM `motif`";
		$sth = $this->FreePBX->Database->query($sql);
		$out = $sth->fetchAll(PDO::FETCH_ASSOC);
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
		$astman = $this->FreePBX->astman;
		$sql = "SELECT * FROM `motif` WHERE `id` = :id";
		$sth = $this->FreePBX->Database->prepare($sql);
		$sth->execute(array(":id" => $id));
		$out = $sth->fetch(PDO::FETCH_ASSOC);
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
