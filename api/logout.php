<?php


///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////Logout//////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////

$ch = curl_init('http://eclass.chonghwakl.edu.my/logout.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID=".@$_GET['token']);
$result = curl_exec($ch);
if($result == "") JSON ('logout', '1');


function JSON($key, $msg){
	$JSON = (object)array();
	$JSON -> $key = $msg;
	$JSON = json_encode($JSON, JSON_UNESCAPED_UNICODE);
	header("Content-type: application/json; charset=utf-8");
	echo $JSON;
}


?>