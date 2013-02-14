<?php
global $db;
global $amp_conf;
global $asterisk_conf;

if (! function_exists("out")) {
	function out($text) {
		echo $text."<br />";
	}
}

if (! function_exists("outn")) {
	function outn($text) {
		echo $text;
	}
}

if($amp_conf["AMPDBENGINE"] == "mysql")  {
	$sql = "CREATE TABLE IF NOT EXISTS `motif` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`phonenum` varchar( 12 ) NOT NULL ,
	`username` varchar( 50 ) NOT NULL ,
	`password` varchar( 50 ) NOT NULL ,
	`type` varchar( 50 ) NOT NULL DEFAULT 'googlevoice' ,
	`settings` blob NOT NULL,
    `statusmessage` varchar( 50 ) NOT NULL,
	PRIMARY KEY (`id`)
)
";
	$check = $db->query($sql);
	if(DB::IsError($check)) {
		die_freepbx(_("Can not create Google Voice/Motif table"));
	} else {
		out("Database table for Google Voice/Motif installed");
	}
} else {
	die_freepbx(_("Unknown/Not Supported database type: ".$amp_conf["AMPDBENGINE"]));
}

out('Updating Route Settings');
$sql = 'SELECT `id`, `settings` FROM `motif`';
$accounts = sql($sql, 'getAll', DB_FETCHMODE_ASSOC);

foreach($accounts as $list) {
    $data = unserialize($list['settings']);
    $tmp = array();
    $new = array();
    if(isset($data['route'])) {
        $tmp['obroute'] = $data['route'];
        $tmp['obroute_number'] = $data['route_number'];
        unset($data['route']);
        unset($data['route_number']);
        $new = serialize(array_merge($data,$tmp));
        $sql = "UPDATE `motif` SET `settings` = '".mysql_real_escape_string($new)."' WHERE id = " . $list['id'];
        sql($sql);
    }
}

if (!$db->getAll('SHOW COLUMNS FROM motif WHERE FIELD = "statusmessage"')) {
	sql('ALTER TABLE motif ADD statusmessage varchar( 50 ) NOT NULL default "I am available"');
}

if(file_exists($amp_conf['ASTETCDIR'].'/rtp.conf') && ((!file_exists($amp_conf['ASTETCDIR'].'/rtp_custom.conf')) || (filesize($amp_conf['ASTETCDIR'].'/rtp_custom.conf') == 0))) {
    $contents = file_get_contents($amp_conf['ASTETCDIR'].'/rtp.conf');
    $rtpstart = preg_match('/rtpstart=(.*)/i',$contents,$m) ? $m[1] : '10000';
    $rtpend = preg_match('/rtpend=(.*)/i',$contents,$m) ? $m[1] : '20000';            
    file_put_contents($amp_conf['ASTETCDIR'].'/rtp_custom.conf',"rtpstart=".$rtpstart."\nrtpend=".$rtpend."\n");
}
