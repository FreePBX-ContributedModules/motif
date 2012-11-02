<?php
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }
isset($_REQUEST['action']) ? $action = $_REQUEST['action'] : $action = 'add';

global $astman;

if($action == 'delete') {
	$sql = "DELETE FROM `motif` WHERE id = ".mysql_real_escape_string($_REQUEST['id']);
	sql($sql);
	$action = 'add';
	needreload();
}

if($astman->connected() && $astman->mod_loaded('motif') && $astman->mod_loaded('xmpp')) {
	if(isset($_REQUEST['username'])) {
		$pn = isset($_REQUEST['number']) ? mysql_real_escape_string($_REQUEST['number']) : '';
		$un = isset($_REQUEST['username']) ? mysql_real_escape_string($_REQUEST['username']) : '';
		$pw = isset($_REQUEST['password']) ? mysql_real_escape_string($_REQUEST['password']) : '';
		
		$un = preg_match('/@/i',$un) ? $un : $un . '@gmail.com';
		
		$settings = array();
		$settings['trunk'] = isset($_REQUEST['trunk']) ? true : false;
		$settings['route'] = isset($_REQUEST['route']) ? true : false;
		
		$settings = serialize($settings);
		
		if(!empty($pn) && !empty($un) && !empty($pw)) {
			if($action == 'add') {
				$sql = "INSERT INTO `motif` (`phonenum`, `username`, `password`, `settings`) VALUES ('" . $pn . "', '" . $un . "', '" . $pw . "', '" . $settings . "')";
			} else {
				$sql = "UPDATE `motif` SET `phonenum` = '".$pn."', `username` = '".$un."', `password` = '".$pw."', `settings` = '".$settings."' WHERE id = " . mysql_real_escape_string($_REQUEST['id']);
			}
			sql($sql);
			needreload();
		}
	}
	
	$sql = 'SELECT * FROM `motif`';
	$accounts = sql($sql, 'getAll', DB_FETCHMODE_ASSOC);
	
	if($action == 'edit') {
		$sql = 'SELECT * FROM `motif` WHERE `id` = '.mysql_real_escape_string($_REQUEST['id']);
		$account = sql($sql, 'getRow', DB_FETCHMODE_ASSOC);
		//print_r($account);
		$form_password = $account['password'];
		$form_username = $account['username'];
		$form_number = $account['phonenum'];
		
		$settings = unserialize($account['settings']);
		$form_trunk = $settings['trunk'];
		$form_route = $settings['route'];
		$id = $account['id'];
		
		$r = $astman->command("xmpp show connections");
		$status['connected'] = false;
		$context = str_replace('@','',str_replace('.','',$account['username']));
		if(preg_match('/\[g'.$context.'\] '.$account['username'].'.* (Connected)/i',$r['data'],$matches)) {
			$status['connected'] = true;
		};
		
		$r = $astman->command("xmpp show buddies");
		preg_match_all('/Client: g'.$context.'\n(?:.|\n)*/i',$r['data'],$client);
		preg_match_all('/Buddy:(.*)/i',$client[0][0],$matches);
		$buddies = array();
		foreach($matches[1] as $data) {
			if(!preg_match('/@public.talk.google.com/i',$data)) {
				$buddies[] = $data;
			}
		}		
		
	}
	include('views/main.php');
	include('views/edit.php');
} else {
	echo "<h3>This Module Requires Asterisk mod_motif & mod_xmpp to be installed and loaded</h3>";
}

/*
jabber list nodes=xmpp list nodes
jabber purge nodes=xmpp purge nodes
jabber delete node=xmpp delete node
jabber create collection=xmpp create collection
jabber create leaf=xmpp create leaf
jabber set debug=xmpp set debug
jabber show connections=xmpp show connections
jabber show buddies=xmpp show buddies
*/

