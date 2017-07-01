<?php


///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////Login//////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
include('functions.php');

getCheck('u','p');

$ch = curl_init('http://eclass.chonghwakl.edu.my/templates/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
$result = curl_exec($ch);

$json = new JSON();

if(!$result) $json->alert('err', "Connection Timeout.");

// get cookie
// multi-cookie variant contributed by @Combuster in comments
$regex = preg_match_all('/securetoken" value="(.*)"\/>/U', $result, $token);
$regex_cookie = preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);

if(!$regex && !$regex_cookie) $json->alert('err', "Connection Timeout.");

$cookies = array();
foreach($matches[1] as $item) {
    parse_str($item, $cookie);
    $cookies = array_merge($cookies, $cookie);
}

$securetoken = $token[1][0];
$eclass_session = $cookies['PHPSESSID'];

/*
var_dump($eclass_session);
echo 'ST: '.$securetoken ."\n";
echo 'ES: '.$eclass_session ."\n";
*/

$ch = curl_init('http://eclass.chonghwakl.edu.my/login.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID=".$eclass_session);
curl_setopt($ch,CURLOPT_USERAGENT,'CHKL');
// get headers too with this line
curl_setopt($ch, CURLOPT_HEADER, true);
//curl_setopt($ch, CURLOPT_FILETIME, true);
//curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,
            "UserLogin=".$_GET['u']."&UserPassword=".$_GET['p']."&url=wrong&securetoken=".$securetoken);
$result = curl_exec($ch);
// get cookie
// multi-cookie variant contributed by @Combuster in comments
preg_match('/^Location: (.*)$/mi', $result, $location);

if($location){
	$url = trim($location[1]);
	if($url == "/wrong") $json->alert('err', "用户ID/密码错误");
	if($url == "/home/index.php") $json->alert('token', $eclass_session);
	//if($url == "/templates/index.php?err=1") echo "Invalid LoginID/Password.";
}else{
	$json->alert('err', 'OOPS, eClass死了');
}




?>