<?php

global $db;

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

out("Dropping all relevant tables");
$sql = "DROP TABLE `motif`";
$result = $db->query($sql);