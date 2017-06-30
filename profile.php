<?php
///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////P_Announcement//////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
include('functions.php');
getCheck('token');

$ch = curl_init('http://eclass.chonghwakl.edu.my/home/iaccount/account/index.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID=".$_GET['token']);
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

$date_list_c = count($data[0]);

if($date_list_c == 0) {
	$json = new JSON();
	$json->alert('err', 'Invalid Token!');
}

preg_match('/value="(.*)"/U', $data[1][2], $nick);
preg_match('/value="(.*)"/U', $data[1][6], $birth);
$JSON = (object)array();
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