<?php
///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////imail_List/////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
include('functions.php');

getCheck('token','mid');

$ch = curl_init('http://eclass.chonghwakl.edu.my/home/imail/viewmail_content.php?CampusMailID='.$_GET['mid']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID=".$_GET['token']);
curl_setopt($ch,CURLOPT_USERAGENT,'CHKL');
$result = curl_exec($ch);
$result = preg_replace('/\s+/', ' ', $result); // make sure there aren't multiple spaces //
//var_dump($result);
//preg_match_all('/indextabimaillist">(.*)<\/a>/U', $result, $imail_list);
//preg_match_all("/fe_eimail\('(.*)'\)/U", $result, $imail_id);

$regex = preg_match('/tabletext\' >(.*) \((?:.*)tabletext\'>(.*)<\/span>(?:.*)10%\'><tr><td>(.*)\.\.\.(?:.*)10%\'><tr><td>(.*)<\/td>(?:.*)style1\'>(.*)<\/td>/U', $result, $data);

//var_dump($data);

if(!$regex) {
	$json = new JSON();
	$json->alert('err', 'Invalid Token!');
}

$JSON = (object)array();

	$content = html_entity_decode($data[5]);
	$breaks = array("<br />","<br>","<br/>");  
	$text = str_ireplace($breaks, "\r\n", $content);  

	$imail['date'] = $data[1];
	$imail['from'] = $data[2];
	$imail['content'] = strip_tags($text);
	$imail['to'] = $data[3];
	$imail['to_long'] = $data[4];

//$JSON -> count = count($imail);
$JSON -> imail = $imail;
$JSON = json_encode($JSON, JSON_UNESCAPED_UNICODE);
header("Content-type: application/json; charset=utf-8");
echo $JSON;


?>