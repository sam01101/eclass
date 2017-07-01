<?php
///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////Class_List/////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
include('functions.php');

getCheck('token');

$ch = curl_init('http://eclass.chonghwakl.edu.my/eclass40/src/portal/announcement/announcement_full_list.php?clearCoo=1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID=".$_GET['token']);
curl_setopt($ch,CURLOPT_USERAGENT,'CHKL');
$result = curl_exec($ch);
$result = str_replace(["\n", "\r", "\t"], '', $result);
//$result = preg_replace('/\s+/', ' ', $result); // make sure there aren't multiple spaces //
//var_dump($result);
//preg_match_all('/indextabclasslist">(.*)<\/a>/U', $result, $class_list);
//preg_match_all("/fe_eclass\('(.*)'\)/U", $result, $class_id);

$regex = preg_match_all('/\'\);">(.*)<\/a>/U', $result, $data);

//var_dump($text);

if(!$regex) {
	$json = new JSON();
	$json->alert('err', 'Invalid Token!');
}

$JSON = (object)array();
foreach($data[1] as $key => $content)
{
	$breaks = array("<br />","<br>","<br/>");  
	$text = str_ireplace($breaks, "\r\n", $content);  
	$announcements[$key]['content'] = $text;
}
$JSON -> count = count($announcements);
$JSON -> announcements = $announcements;
$JSON = json_encode($JSON, JSON_UNESCAPED_UNICODE);
header("Content-type: application/json; charset=utf-8");
echo $JSON;


?>