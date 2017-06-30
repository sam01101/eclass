<?php
///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////Class Login/////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////

include('functions.php');

getCheck('token','cid');

$ch = curl_init('http://eclass.chonghwakl.edu.my/home/eLearning/login.php?uc_id='.$_GET['cid']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID=".$_GET['token']);
curl_setopt($ch,CURLOPT_USERAGENT,'CHKL');
$result = curl_exec($ch);

if (strpos($result, 'src/b.php?toMax=1') !== false) {
    $json = new JSON();
    $json->alert('succcess',1);
}else{
	$json = new JSON();
    $json->alert('err','Invalid Token!');
}


?>