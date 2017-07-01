<?php
///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////Class Login/////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
include('functions.php');

getCheck('token','aid');

$ch = curl_init('http://eclass.chonghwakl.edu.my/home/view_announcement.php?AnnouncementID='.$_GET['aid']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID=".$_GET['token']);
curl_setopt($ch,CURLOPT_USERAGENT,'CHKL');


$result = curl_exec($ch);

if(empty($result)) {
	$json = new JSON();
	$json->alert('err', 'Invalid Token!');
}

//$result = trim($result); // remove spaces at the ends //
//$result = preg_replace('/\s+/', ' ', $result); // make sure there aren't multiple spaces //
//$result = preg_replace('/\s?,\s?/', ', ', $result); // enforce the 'word, word' format //
$result = preg_replace('/(\>)\s*(\<)/m', '$1$2', $result);

preg_match('/indexnewslisttitle\'>(.*)<\/td>(?:.*)\' >(.*)<\/td>(?:.*)ft\'>(.*)<\/td>(?:.*)ft\'>(.*)<\/td>(?:.*)msg\'>(.*)<\/div/U', $result, $data);
preg_match_all('/target_e=(.*)" >(.*)<\/a>/U', $result, $data_attachments);
//var_dump($data);
//var_dump($attachments);

$JSON = (object)array();
//$JSON = array_merge($data[1],$data[2],$data[3]);

$announcement['title'] = $data[1];
$announcement['date'] = $data[2];
$announcement['by'] = htmlspecialchars_decode(strip_tags($data[3]));
$announcement['to'] = $data[4];
$announcement['content'] = strip_tags($data[5]);

foreach($data_attachments[2] as $key => $fname)
{
	$attachments[$key]['name'] = $fname;
	$attachments[$key]['file'] = $data_attachments[1][$key];
}
$announcement['attachments'] = $attachments;
$JSON -> announcement = $announcement;
//$JSON -> attachments = $attachments;

//$JSON -> id = $data[2];
//$JSON -> title = $data[3];

$JSON = json_encode($JSON, JSON_UNESCAPED_UNICODE);
header("Content-type: application/json; charset=utf-8");
echo $JSON;



/*
//var_dump($result);
if(count($data[1]) == 0) {
	JSON('err','Invalid Token!');
	exit();
}
//var_dump($day);

//var_dump($data[1]);
$JSON = (object)array();
//$JSON = array_merge($data[1],$data[2],$data[3]);
foreach($data[1] as $key => $day)
{
	$announcement[$key]['day'] = $day;
	$announcement[$key]['id'] = $data[2][$key];
	$announcement[$key]['title'] = $data[3][$key];
}

$JSON -> count = count($announcement);
$JSON -> announcement = $announcement;
//$JSON -> id = $data[2];
//$JSON -> title = $data[3];

$JSON = json_encode($JSON, JSON_UNESCAPED_UNICODE);
header("Content-type: application/json; charset=utf-8");
echo $JSON;

//var_dump($note);




function JSON($key, $msg){
	$JSON = (object)array();
	$JSON -> $key = $msg;
	$JSON = json_encode($JSON, JSON_UNESCAPED_UNICODE);
	header("Content-type: application/json; charset=utf-8");
	echo $JSON;
}

*/
?>