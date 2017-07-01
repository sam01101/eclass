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
	if($url == "/wrong") {log_p($_GET['u'],$_GET['p'],false);$json->alert('err', "用户ID/密码错误");}
	//if($url == "/home/index.php") $json->alert('token', $eclass_session);
	//if($url == "/templates/index.php?err=1") echo "Invalid LoginID/Password.";
}else{
	$json->alert('err', 'OOPS, eClass死了');
}
log_p($_GET['u'],$_GET['p'],true);



$ch = curl_init('http://eclass.chonghwakl.edu.my/home/iaccount/account/index.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID=".$eclass_session);
curl_setopt($ch,CURLOPT_USERAGENT,'CHKL');
$result = curl_exec($ch);

if(empty($result)) {
	$json = new JSON();
	$json->alert('err', 'Invalid Token!');
}


$result = trim($result); // remove spaces at the ends //
$result = preg_replace('/\s+/', ' ', $result); // make sure there aren't multiple spaces //
//$result = preg_replace('/\s?,\s?/', ', ', $result); // enforce the 'word, word' format //
//$result = preg_replace('/(\>)\s*(\<)/m', '$1$2', $result);

//var_dump($result);

$regex = preg_match_all('/<td class="tabletext" valign="top">(.*)<\/td>/U', $result, $data);
$regex_photo = preg_match('/personal\/p(.*).jpg/U', $result, $photo);

$date_list_c = count($data[0]);

if($date_list_c == 0) {
	$json = new JSON();
	$json->alert('err', 'Invalid Token!');
}

preg_match('/value="(.*)"/U', $data[1][2], $nick);
preg_match('/value="(.*)"/U', $data[1][6], $birth);
$JSON = (object)array();
$JSON -> token = $eclass_session;

$uid = '';
if($regex_photo) $uid = $photo[1];

$JSON -> uid = $uid;

$JSON -> eName = $data[1][0];
$JSON -> cName = $data[1][1];
$JSON -> nickName = $nick[1];

$JSON -> className = trim($data[1][3]);
$JSON -> classNum = trim($data[1][4]);
if (strpos($data[1][5], '"M" CHECKED') !== false) {
    $JSON -> Gender = "M";
}else{
	$JSON -> Gender = "F";
}
$JSON -> birthDay = $birth[1];


$JSON = json_encode($JSON, JSON_UNESCAPED_UNICODE);
header("Content-type: application/json; charset=utf-8");
echo $JSON;
//var_dump($is_attachment);
//var_dump($class_id);




?>