#!/usr/bin/php
<?php
error_reporting(E_ALL | E_STRICT);
// ini_set('display_errors', true);
ini_set('auto_detect_line_endings', true);



$day=$argv[1];
if ($day=="" || $day===false) {
	exit("Usage: " . basename($argv[0]) . " 2015-01-12\n");
}

$api_key=rtrim(file_get_contents($_SERVER['HOME'] . "/.yourshot-api-key"));
if (!$api_key) {
 exit("\nPlease put your Your Shot API key in ~/.yourshot-api-key\n");
}



$json_string_dailydozen = file_get_contents("http://yourshot.nationalgeographic.com/api/v1/dailydozen/$day/photo/?format=jsonp&apikey=$api_key&limit=18&page=1");
$parsed_json = json_decode($json_string_dailydozen);

$objects = $parsed_json->{'objects'};

foreach ($objects as $object) {


	
}



?>