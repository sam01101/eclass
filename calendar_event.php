<?php
///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////Announcement//////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////

if(isset($_GET['type'])){
	if($_GET['type'] == "today") $type = 0;
	if($_GET['type'] == "month") $type = 1;
}else{
	$type = 0;
}

$ch = curl_init('http://eclass.chonghwakl.edu.my/home/index20_aj_event.php?type='. $type);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID=".@$_GET['token']);
curl_setopt($ch,CURLOPT_USERAGENT,'CHKL');
$result = curl_exec($ch);

var_dump($result);
?>