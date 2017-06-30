<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class JSON {
	function alert($key, $msg){
		$JSON = (object)array();
		$JSON -> $key = $msg;
		$JSON = json_encode($JSON, JSON_UNESCAPED_UNICODE);
		header("Content-type: application/json; charset=utf-8");
		echo $JSON;
		exit();
	}
}

function getCheck() {
	$args = func_get_args();
	if (array_diff($args, array_keys($_GET))) exit();
}
?>