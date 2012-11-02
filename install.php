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